<?php

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/inc/db_bootstrap.php';  

$field = $_GET['field'] ?? '';
$term  = $_GET['term']  ?? '';

$term = trim($term);

if ($field === '' || $term === '') {
    echo json_encode([]);
    exit;
}

$suggestions = [];

try {
    switch ($field) {

        case 'min_price':
        case 'min_avg':
            $sql = "SELECT DISTINCT price
                    FROM Product
                    WHERE CAST(price AS CHAR) LIKE :pattern
                    ORDER BY price
                    LIMIT 20";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':pattern' => $term . '%']);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $suggestions[] = (string)$row['price'];
            }
            break;

        case 'min_items':
            $sql = "SELECT DISTINCT cnt
                    FROM (
                        SELECT COUNT(*) AS cnt
                        FROM Product
                        GROUP BY brand
                    ) AS t
                    WHERE CAST(cnt AS CHAR) LIKE :pattern
                    ORDER BY cnt
                    LIMIT 20";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':pattern' => $term . '%']);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $suggestions[] = (string)$row['cnt'];
            }
            break;

        case 'min_campaigns':
            $sql = "SELECT DISTINCT cnt
                    FROM (
                        SELECT COUNT(*) AS cnt
                        FROM Promotes
                        GROUP BY product_id
                    ) AS t
                    WHERE CAST(cnt AS CHAR) LIKE :pattern
                    ORDER BY cnt
                    LIMIT 20";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':pattern' => $term . '%']);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $suggestions[] = (string)$row['cnt'];
            }
            break;

        default:
            echo json_encode([]);
            exit;
    }

} catch (Throwable $e) {
    echo json_encode([]);
    exit;
}

echo json_encode($suggestions);
