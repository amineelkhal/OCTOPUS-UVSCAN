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

$query = "
    SELECT 
        e.*, s.name, s.description as scannerdescription
    FROM 
        `entrances` e 
    JOIN 
        `scanner` s ON e.scanner = s.id 
    WHERE 
        e.deleted = 1 
    ORDER BY 
        e.entry_date DESC
";

$stmt = $pdo->query($query);
$data = $stmt->fetchAll();

echo json_encode(array("data" => $data));
?>
