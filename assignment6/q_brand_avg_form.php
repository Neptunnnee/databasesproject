<form action="q_brand_avg_results.php" method="get">
  <label>Min average price:
    <input
      type="number"
      step="0.01"
      name="min_avg"
      id="min_avg"
      value="75"
      required
      data-autocomplete="min_avg">
  </label>
  <label>Min items:
    <input
      type="number"
      name="min_items"
      id="min_items"
      value="2"
      required
      data-autocomplete="min_items">
  </label>
  <button type="submit">Search</button>
</form>
