<?php 
// Database connection info 
$dbDetails = array( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'db'   => 'uviscan' 
); 
 
// DB table to use 
$table = 'entrances'; 
 
// Table's primary key 
$primaryKey = 'id'; 
 
// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
    array( 'db' => 'scanner', 'dt' => 0 ), 
    array( 'db' => 'plate',  'dt' => 1 ), 
    array( 'db' => 'entry_date',      'dt' => 2 ), 
    array( 'db' => 'category',     'dt' => 3 )
); 
 
// Include SQL query processing class 
require 'libs/SSP.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);