<?php

$host = 'localhost';
$db   = 'uviscan';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_GET['s'] == "all") {

    $year = date("Y");  // This will get the current year

    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $result = array();

    foreach ($months as $index => $month) {
        $start_date = "$year-$index-01";
        $end_date = "$year-$index-31"; // This assumes all months have 31 days which isn't true. You might want to adjust for months with fewer days.

        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM entrances WHERE DATE(entry_date) BETWEEN :start_date AND :end_date");
        $stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);

        $row = $stmt->fetch();
        $result[$month] = $row['count'];
    }

    echo json_encode($result);
}

if ($_GET['s'] == "byscannername") {
    // ... PDO database connection as explained previously

    $currentYear = date('Y');

    $stmt = $pdo->prepare("SELECT s.name AS scanner_name, COUNT(e.id) AS entrance_count 
                       FROM entrances e
                       JOIN scanner s ON e.scanner = s.id
                       WHERE YEAR(e.entry_date) = :currentYear
                       GROUP BY s.name");
    $stmt->execute(['currentYear' => $currentYear]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);
}

if ($_GET['s'] == 'bymonthbyscannername') {
    $currentYear = date('Y');
    $query = "
    SELECT MONTH(entry_date) as month, name, COUNT(*) as count
    FROM entrances
    JOIN scanner ON entrances.scanner = scanner.id
    WHERE YEAR(entry_date) = ". $currentYear ."
    GROUP BY MONTH(entry_date), name
    ORDER BY MONTH(entry_date), name
";

    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll();

    header('Content-Type: application/json');
    echo json_encode($data);
}
