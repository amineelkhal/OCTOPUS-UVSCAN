<?php
$host = 'localhost';
$db   = 'uviscan';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

$plate = $_GET['plate'];
$query = "SELECT e.*, s.name AS scanner_name, s.description AS scanner_description 
FROM entrances e 
JOIN scanner s ON e.scanner = s.id
WHERE plate = ? AND deleted = 0
ORDER BY entry_date DESC";
$stmt = $pdo->prepare( $query );
$stmt->execute([$plate]);
$data = $stmt->fetchAll();

echo json_encode($data);
?>
