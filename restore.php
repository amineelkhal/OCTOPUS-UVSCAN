<?php
include "includes/_inc_conn.php";
$sql = "UPDATE entrances SET deleted = 0 WHERE id = " . $_GET['id'];
$result = mysqli_query($conn, $sql);
?>