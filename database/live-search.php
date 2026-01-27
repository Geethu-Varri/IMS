<?php
include('connection.php');

$search_term = isset($_GET['search_term']) ? trim($_GET['search_term']) : '';

$tables = [
    'users' => '',
    'products' => 'product_name',
    'suppliers' => 'supplier_name'
];

$results = [];
$length = 0;

foreach($tables as $table_name => $col){

    if($table_name === 'users'){
        $stmt = $conn->prepare("
            SELECT * FROM users 
            WHERE first_name LIKE :term 
               OR last_name LIKE :term
            ORDER BY created_at DESC
        ");
    } else {
        $stmt = $conn->prepare("
            SELECT * FROM $table_name 
            WHERE $col LIKE :term
            ORDER BY created_at DESC
        ");
    }

    $like = "%$search_term%";
    $stmt->bindParam(':term', $like);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $length += count($rows);
    $results[$table_name] = $rows;
}

echo json_encode([
    'data' => $results,
    'total' => $length
]);
