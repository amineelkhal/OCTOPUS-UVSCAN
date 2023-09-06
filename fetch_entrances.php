<?php
$host = 'localhost';
$db   = 'uviscan';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$query = "SELECT 
e1.*, 
s.name, 
s.description AS scannerdescription, 
CASE 
    WHEN e1.plate = 'no plate' OR e1.plate = '' THEN NULL 
    ELSE (
        SELECT e2.scan 
        FROM entrances AS e2 
        WHERE e2.plate = e1.plate 
        AND e2.entry_date < e1.entry_date 
        AND e2.deleted = 0
        ORDER BY e2.entry_date DESC 
        LIMIT 1
    )
END AS previousScan,
CASE 
    WHEN e1.plate = 'no plate' OR e1.plate = '' THEN NULL 
    ELSE (
        SELECT e2.entry_date 
        FROM entrances AS e2 
        WHERE e2.plate = e1.plate 
        AND e2.entry_date < e1.entry_date 
        AND e2.deleted = 0
        ORDER BY e2.entry_date DESC 
        LIMIT 1
    )
END AS previousEntryDate
FROM `entrances` e1 
JOIN `scanner` s ON e1.scanner = s.id 
WHERE e1.deleted = 0 
ORDER BY e1.entry_date DESC;
";

$stmt = $pdo->query($query);
$data = $stmt->fetchAll();

echo json_encode(array("data" => $data));
?>
