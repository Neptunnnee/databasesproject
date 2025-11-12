#!/usr/bin/env python3
"""
weblog_eval.py
Analyze Apache access and error logs and generate:
- CSV summaries (pages, ip-page, browsers, errors, timelines)
- A multi-page PDF report with timeline diagrams

Usage:
  python3 weblog_eval.py --access /path/to/access.log --error /path/to/error.log --out /tmp/report.pdf
"""
import argparse, re, os, csv, math, sys, textwrap
from collections import defaultdict, Counter
from datetime import datetime, timezone, timedelta
import pandas as pd
import matplotlib.pyplot as plt
from matplotlib.backends.backend_pdf import PdfPages

ACCESS_RE = re.compile(
    r'^(?P<ip>\S+) \S+ \S+ \[(?P<time>[^\]]+)\] '
    r'"(?P<method>[A-Z]+) (?P<path>[^"]*?)(?: HTTP/\d\.\d)?" '
    r'(?P<status>\d{3}) (?P<size>\S+) "(?P<referer>[^"]*)" "(?P<agent>[^"]*)"'
)

ERROR_RE = re.compile(
    r'^\[(?P<time>[^]]+)\]\s+(?P<mods>\[[^\]]+\]\s+)*'
    r'(?:\[pid\s+\d+\]\s+)?(?:\[client\s+(?P<client>[^]]+)\]\s+)?(?P<msg>.*)$'
)
LEVEL_RE = re.compile(r'\[(?P<module>[^:]+):(?P<level>[a-zA-Z]+)\]')

ACCESS_TIME_FMT = "%d/%b/%Y:%H:%M:%S %z"
ERROR_TIME_FMTS = ["%a %b %d %H:%M:%S.%f %Y", "%a %b %d %H:%M:%S %Y"]

def parse_args():
    p = argparse.ArgumentParser(description="Apache log analyzer -> PDF + CSVs")
    p.add_argument("--access", required=True, help="Path to Apache access log")
    p.add_argument("--error", required=True, help="Path to Apache error log")
    p.add_argument("--out", required=True, help="Output PDF path")
    p.add_argument("--outdir", default=None, help="Output directory for CSVs (default: alongside PDF)")
    return p.parse_args()

def parse_access(path):
    rows = []
    with open(path, "r", encoding="utf-8", errors="ignore") as f:
        for line in f:
            m = ACCESS_RE.match(line.strip())
            if not m:
                continue
            d = m.groupdict()
            try:
                t = datetime.strptime(d["time"], ACCESS_TIME_FMT)
            except Exception:
                continue
            path_only = d["path"].split("?", 1)[0]
            rows.append({
                "ip": d["ip"],
                "time": t,
                "method": d["method"],
                "path": path_only,
                "status": int(d["status"]),
                "size": 0 if d["size"] == "-" else int(d["size"]),
                "referer": d["referer"],
                "agent": d["agent"],
            })
    return pd.DataFrame(rows)

def parse_error(path):
    recs = []
    with open(path, "r", encoding="utf-8", errors="ignore") as f:
        for line in f:
            s = line.strip()
            m = ERROR_RE.match(s)
            if not m:
                continue
            g = m.groupdict()
            ts_raw = g.get("time", "")
            dt = None
            for fmt in ERROR_TIME_FMTS:
                try:
                    dt = datetime.strptime(ts_raw, fmt)
                    break
                except Exception:
                    pass
            if dt is None:
                continue
            level = None
            for lev in LEVEL_RE.finditer(s):
                level = lev.group("level").lower()
            client_ip = None
            client = g.get("client")
            if client:
                client_ip = client.split(":")[0]
            recs.append({
                "time": dt,
                "ip": client_ip,
                "level": level or "notice",
                "message": g.get("msg","").strip()
            })
    return pd.DataFrame(recs)

def detect_browser(ua: str) -> str:
    u = ua or ""
    if "bot" in u.lower() or "spider" in u.lower() or "crawl" in u.lower():
        return "Bot"
    if "Edg" in u or "Edge" in u:
        return "Edge"
    if "OPR" in u or "Opera" in u:
        return "Opera"
    if "Chrome" in u and "Chromium" not in u and "OPR" not in u and "Edg" not in u:
        return "Chrome"
    if "Firefox" in u:
        return "Firefox"
    if "Safari" in u and "Chrome" not in u:
        return "Safari"
    if "curl" in u.lower() or "wget" in u.lower():
        return "CLI"
    return "Other"

def bucket_times(series: pd.Series):
    if series.empty:
        return "H"
    span = series.max() - series.min()
    if span <= pd.Timedelta(days=3):
        return "H"
    return "D"

def timeline_counts(df_times: pd.Series, freq: str):
    if df_times.empty:
        return pd.Series(dtype=int)
    ts = df_times.dt.floor(freq)
    return ts.value_counts().sort_index()

def add_text_page(pdf, title, lines):
    fig = plt.figure(figsize=(8.5, 11))
    plt.axis("off")
    wrapped = []
    for L in lines:
        wrapped.extend(textwrap.wrap(L, width=95) if L else [""])
    text = "\n".join(wrapped)
    fig.text(0.05, 0.95, title, fontsize=16, weight="bold", va="top")
    fig.text(0.05, 0.90, text, fontsize=10, va="top")
    pdf.savefig(fig, bbox_inches="tight")
    plt.close(fig)

def plot_timeline(pdf, counts: pd.Series, title: str, ylabel: str):
    fig = plt.figure(figsize=(11, 4))
    ax = fig.add_subplot(111)
    if not counts.empty:
        ax.plot(counts.index, counts.values, marker="o")
    ax.set_title(title)
    ax.set_ylabel(ylabel)
    ax.set_xlabel("Time")
    fig.autofmt_xdate()
    pdf.savefig(fig, bbox_inches="tight")
    plt.close(fig)

def main():
    args = parse_args()
    outdir = args.outdir or os.path.dirname(os.path.abspath(args.out)) or "."
    os.makedirs(outdir, exist_ok=True)

    access_df = parse_access(args.access)
    error_df  = parse_error(args.error)

    if not access_df.empty:
        access_df["browser"] = access_df["agent"].apply(detect_browser)
        # Normalize to naive datetime for easier grouping
        try:
            access_df["time"] = pd.to_datetime(access_df["time"])
        except Exception:
            pass

    pages_summary = pd.DataFrame()
    ip_page_summary = pd.DataFrame()
    browsers_summary = pd.DataFrame()
    errors_clean = error_df.copy()

    if not access_df.empty:
        grp = access_df.groupby("path")
        pages_summary = grp.agg(
            hits=("path", "count"),
            unique_ips=("ip", pd.Series.nunique),
            first_seen=("time", "min"),
            last_seen=("time", "max"),
        ).reset_index().sort_values("hits", ascending=False)

        ipg = access_df.groupby(["path","ip"]).agg(
            hits=("path","count"),
            first_seen=("time","min"),
            last_seen=("time","max"),
        ).reset_index().sort_values(["path","hits"], ascending=[True, False])

        ip_page_summary = ipg

        brow = access_df.groupby("browser").size().reset_index(name="hits").sort_values("hits", ascending=False)
        browsers_summary = brow

    if not errors_clean.empty:
        errors_clean = errors_clean.sort_values("time")

    acc_freq = bucket_times(access_df["time"]) if not access_df.empty else "H"
    err_freq = bucket_times(error_df["time"]) if not error_df.empty else "H"
    acc_timeline = timeline_counts(access_df["time"], acc_freq) if not access_df.empty else pd.Series(dtype=int)
    err_timeline = timeline_counts(error_df["time"], err_freq) if not error_df.empty else pd.Series(dtype=int)

    if not access_df.empty:
        pages_summary.to_csv(os.path.join(outdir, "pages_summary.csv"), index=False)
        ip_page_summary.to_csv(os.path.join(outdir, "ip_page_summary.csv"), index=False)
        browsers_summary.to_csv(os.path.join(outdir, "browsers_summary.csv"), index=False)
        acc_timeline.to_csv(os.path.join(outdir, f"access_timeline_{acc_freq}.csv"), header=["hits"])
    if not errors_clean.empty:
        errors_clean.to_csv(os.path.join(outdir, "errors.csv"), index=False)
        err_timeline.to_csv(os.path.join(outdir, f"errors_timeline_{err_freq}.csv"), header=["errors"])

    with PdfPages(args.out) as pdf:
        n_hits = int(len(access_df)) if not access_df.empty else 0
        n_errs = int(len(error_df)) if not error_df.empty else 0
        lines = [
            f"Access log: {args.access}",
            f"Error log : {args.error}",
            f"Total requests parsed: {n_hits}",
            f"Total error entries parsed: {n_errs}",
            f"CSV outputs saved to: {outdir}",
            "",
            "This report summarizes how often each page was accessed, by which IPs, when, and from which browsers.",
            "It also lists errors with origin (client IP when available) and shows timelines for both requests and errors.",
        ]
        add_text_page(pdf, "Web Log Evaluation Report", lines)

        if not access_df.empty:
            fig = plt.figure(figsize=(11, 5))
            ax = fig.add_subplot(111)
            top = pages_summary.head(15)
            ax.barh(top["path"][::-1], top["hits"][::-1])
            ax.set_title("Top Pages by Hits")
            ax.set_xlabel("Hits")
            ax.set_ylabel("Page")
            pdf.savefig(fig, bbox_inches="tight")
            plt.close(fig)

            fig = plt.figure(figsize=(8, 4))
            ax = fig.add_subplot(111)
            ax.bar(browsers_summary["browser"], browsers_summary["hits"])
            ax.set_title("Traffic by Browser")
            ax.set_xlabel("Browser")
            ax.set_ylabel("Hits")
            pdf.savefig(fig, bbox_inches="tight")
            plt.close(fig)

            plot_timeline(pdf, acc_timeline, f"Access Timeline (bucket={acc_freq})", "Requests")

        if not error_df.empty:
            lev = error_df["level"].fillna("notice").str.lower().value_counts()
            fig = plt.figure(figsize=(8, 4))
            ax = fig.add_subplot(111)
            ax.bar(lev.index, lev.values)
            ax.set_title("Errors by Level")
            ax.set_xlabel("Level")
            ax.set_ylabel("Count")
            pdf.savefig(fig, bbox_inches="tight")
            plt.close(fig)

            plot_timeline(pdf, err_timeline, f"Error Timeline (bucket={err_freq})", "Errors")

            if "ip" in error_df.columns and error_df["ip"].notna().any():
                top_e = error_df.dropna(subset=["ip"])["ip"].value_counts().head(15)
                fig = plt.figure(figsize=(11, 5))
                ax = fig.add_subplot(111)
                ax.barh(top_e.index[::-1], top_e.values[::-1])
                ax.set_title("Top IPs in Error Log")
                ax.set_xlabel("Errors")
                ax.set_ylabel("IP")
                pdf.savefig(fig, bbox_inches="tight")
                plt.close(fig)

    print(f"Done. PDF: {args.out}")
    print(f"CSVs in: {outdir}")

if __name__ == "__main__":
    main()
