SELECT e.emp_id, e.name, p.product_id, sb.length
FROM Tech_Manager tm
JOIN Employees e         ON e.emp_id = tm.emp_id
JOIN Sources s           ON s.manager_id = tm.emp_id
JOIN Product p           ON p.product_id = s.product_id
JOIN Smartwatch_bands sb ON sb.product_id = p.product_id
ORDER BY e.emp_id, p.product_id;

SELECT p.product_id, p.brand, COUNT(*) AS campaign_count
FROM Product p
JOIN Promotes pr ON pr.product_id = p.product_id
GROUP BY p.product_id, p.brand
HAVING COUNT(*) >= 1
ORDER BY campaign_count DESC, p.product_id;

SELECT DISTINCT e.emp_id, e.name
FROM Business_Manager bm
JOIN Employees e ON e.emp_id = bm.emp_id
JOIN Manages m   ON m.emp_id = e.emp_id
JOIN Product p   ON p.product_id = m.product_id
WHERE p.price >= 50.00
ORDER BY e.emp_id;

SELECT e.emp_id, e.name, COUNT(m.product_id) AS managed_products
FROM Employees e
LEFT JOIN Manages m ON m.emp_id = e.emp_id
GROUP BY e.emp_id, e.name
HAVING COUNT(m.product_id) >= 1
ORDER BY managed_products DESC, e.emp_id;

SELECT
  SUM(ec.product_id IS NOT NULL) AS earphone_cases,
  SUM(sb.product_id IS NOT NULL) AS smartwatch_bands,
  SUM(co.product_id IS NOT NULL) AS cable_organizers
FROM Product p
LEFT JOIN Earphone_case    ec ON ec.product_id = p.product_id
LEFT JOIN Smartwatch_bands sb ON sb.product_id = p.product_id
LEFT JOIN Cable_organizers co ON co.product_id = p.product_id;

SELECT brand, AVG(price) AS avg_price, COUNT(*) AS items
FROM Product
GROUP BY brand
HAVING AVG(price) >= 75.00 AND COUNT(*) >= 2
ORDER BY avg_price DESC, brand;

SELECT p.product_id, p.brand, p.price
FROM Product p
WHERE NOT EXISTS (
  SELECT 1 FROM Manages m WHERE m.product_id = p.product_id
)
ORDER BY p.product_id;

SELECT
  SUM(cp.payment_id IS NOT NULL) AS card_payments,
  SUM(gp.payment_id IS NOT NULL) AS giftcard_payments,
  COUNT(p.payment_id)
    - SUM(cp.payment_id IS NOT NULL)
    - SUM(gp.payment_id IS NOT NULL) AS service_payments_est
FROM Payment p
LEFT JOIN Card_Payment     cp ON cp.payment_id = p.payment_id
LEFT JOIN Giftcard_Payment gp ON gp.payment_id = p.payment_id;

WITH promo_counts AS (
  SELECT pr.product_id, COUNT(*) AS c
  FROM Promotes pr
  GROUP BY pr.product_id
),
max_c AS (
  SELECT MAX(c) AS mc FROM promo_counts
)
SELECT p.product_id, p.brand, pc.c AS campaign_count
FROM promo_counts pc
JOIN max_c ON pc.c = max_c.mc
JOIN Product p ON p.product_id = pc.product_id
ORDER BY p.product_id;

CREATE OR REPLACE VIEW v_select_products AS
  SELECT product_id AS id, brand AS label
  FROM Product
  ORDER BY brand, product_id;

CREATE OR REPLACE VIEW v_select_business_managers AS
  SELECT e.emp_id AS id, e.name AS label
  FROM Business_Manager bm
  JOIN Employees e ON e.emp_id = bm.emp_id
  ORDER BY e.name, e.emp_id;

CREATE OR REPLACE VIEW v_select_tech_managers AS
  SELECT e.emp_id AS id, e.name AS label
  FROM Tech_Manager tm
  JOIN Employees e ON e.emp_id = tm.emp_id
  ORDER BY e.name, e.emp_id;

CREATE OR REPLACE VIEW v_select_marketing_managers AS
  SELECT e.emp_id AS id, e.name AS label
  FROM Marketing_Manager mm
  JOIN Employees e ON e.emp_id = mm.emp_id
  ORDER BY e.name, e.emp_id;

CREATE OR REPLACE VIEW v_select_smartwatch_bands AS
  SELECT sb.product_id AS id,
         CONCAT(p.brand, ' (', sb.length, ')') AS label
  FROM Smartwatch_bands sb
  JOIN Product p ON p.product_id = sb.product_id
  ORDER BY p.brand, sb.length;

CREATE OR REPLACE VIEW v_select_earphone_cases AS
  SELECT ec.product_id AS id, p.brand AS label
  FROM Earphone_case ec
  JOIN Product p ON p.product_id = ec.product_id
  ORDER BY p.brand;

CREATE OR REPLACE VIEW v_select_cable_organizers AS
  SELECT co.product_id AS id, p.brand AS label
  FROM Cable_organizers co
  JOIN Product p ON p.product_id = co.product_id
  ORDER BY p.brand;