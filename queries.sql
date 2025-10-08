-- query 1: list every tech manager who sources any Smartwatch bands. show manager id/name, product id and the length of bands.
SELECT e.emp_id, e.name, p.product_id, sb.length
FROM Tech_Manager tm
JOIN Employees e         ON e.emp_id = tm.emp_id
JOIN Sources s           ON s.manager_id = tm.emp_id
JOIN Product p           ON p.product_id = s.product_id
JOIN Smartwatch_bands sb ON sb.product_id = p.product_id
ORDER BY e.emp_id, p.product_id;

-- query 2: for each product count how many promotes campaigns look at it. show only products with more than or equal to 1 campaign sorted by campaign count.
SELECT p.product_id, p.brand, COUNT(*) AS campaign_count
FROM Product p
JOIN Promotes pr ON pr.product_id = p.product_id
GROUP BY p.product_id, p.brand
HAVING COUNT(*) >= 1
ORDER BY campaign_count DESC, p.product_id;

-- query 3: list all business managers who manage at least one product that more expensive or equal to 50. show each such manager once
SELECT DISTINCT e.emp_id, e.name
FROM Business_Manager bm
JOIN Employees e ON e.emp_id = bm.emp_id
JOIN Manages m   ON m.emp_id = e.emp_id
JOIN Product p   ON p.product_id = m.product_id
WHERE p.price >= 50.00
ORDER BY e.emp_id;

-- query 4: for every employee count how many products they manage show only those with more than equal to 1 product starting with the highest first
SELECT e.emp_id, e.name, COUNT(m.product_id) AS managed_products
FROM Employees e
LEFT JOIN Manages m ON m.emp_id = e.emp_id
GROUP BY e.emp_id, e.name
HAVING COUNT(m.product_id) >= 1
ORDER BY managed_products DESC, e.emp_id;

-- query 5: show the product mix as in total number of products that are earphone cases, smartwatch bands and cable organizers
SELECT
  SUM(ec.product_id IS NOT NULL) AS earphone_cases,
  SUM(sb.product_id IS NOT NULL) AS smartwatch_bands,
  SUM(co.product_id IS NOT NULL) AS cable_organizers
FROM Product p
LEFT JOIN Earphone_case    ec ON ec.product_id = p.product_id
LEFT JOIN Smartwatch_bands sb ON sb.product_id = p.product_id
LEFT JOIN Cable_organizers co ON co.product_id = p.product_id;

-- query 6: for each brand calculate average price and item count. show only brands with an average price more than or equal to 75 and more than two items sorted by avg price.
SELECT brand, AVG(price) AS avg_price, COUNT(*) AS items
FROM Product
GROUP BY brand
HAVING AVG(price) >= 75.00 AND COUNT(*) >= 2
ORDER BY avg_price DESC, brand;

-- query 7: list products that currently have no manager assigned.
SELECT p.product_id, p.brand, p.price
FROM Product p
WHERE NOT EXISTS (
  SELECT 1 FROM Manages m WHERE m.product_id = p.product_id
)
ORDER BY p.product_id;

-- query 8: count payments by subtype like number of card payment, giftcard payment and other payment services
SELECT
  SUM(cp.payment_id IS NOT NULL) AS card_payments,
  SUM(gp.payment_id IS NOT NULL) AS giftcard_payments,
  COUNT(p.payment_id)
    - SUM(cp.payment_id IS NOT NULL)
    - SUM(gp.payment_id IS NOT NULL) AS service_payments_est
FROM Payment p
LEFT JOIN Card_Payment     cp ON cp.payment_id = p.payment_id
LEFT JOIN Giftcard_Payment gp ON gp.payment_id = p.payment_id;

-- query 9: find the most promoted products as in those with the maximum number of campaigns—and return product id/brand with that count
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