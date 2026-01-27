<?php
//Start the session
session_start();
// Capture the table mapping.
include('table_columns.php');
include('connection.php');

// Capture the table name.
$table_name = $_SESSION['table'];
$columns = $table_columns_mapping[$table_name];

//Loop through the columns
$db_arr = [];
$user = $_SESSION['user'];
foreach ($columns as $column) {
    if (in_array($column, ['created_at', 'updated_at'])) $value = date('Y-m-d H:i:s');
    // if (in_array($column, ['created_at', 'updated_at'])) $value = 'NOW()';
    else if ($column == 'created_by') $value = $user['id'];
    else if ($column == 'password') $value = password_hash($_POST[$column], PASSWORD_DEFAULT);
    else if ($column == 'img') {
        // Upload or move the file to our directory
        $target_dir = "../uploads/products/";
        $file_data = $_FILES[$column];

        $value = NULL;
        $file_data = $_FILES['img'];

        if ($file_data['tmp_name'] !== '') {
            $file_name = $file_data['name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_name = 'product-' . time() . '.' . $file_ext;

            $check = getimagesize($file_data['tmp_name']);
            // Move the file
            if ($check) {
                if (move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)) {
                    // Save the file_name to the database.
                    $value = $file_name;
                }
            }
        }
    } else if ($column == 'permissions') {
        $value = isset($_POST['permissions']) ? $_POST['permissions'] : null;
    } else $value = isset($_POST[$column]) ? $_POST[$column] : '';

    $db_arr[$column] = $value;
}

$table_properties = implode(", ", array_keys($db_arr));
$table_placeholders = ':' . implode(", :", array_keys($db_arr));

//Add error handler if permissions is empty
if (isset($db_arr['permissions'])) {
    if (!$db_arr['permissions']) {
        $_SESSION['response'] = [
            'success' => false,
            'message' => 'Please make sure permission is set!'
        ];
        header('location: ../' . $_SESSION['redirect_to']);
    }
}

//Adding to record to main table.
try {
    $sql = "INSERT INTO 
                        $table_name($table_properties) 
                    VALUES
                        ($table_placeholders)";


    $stmt = $conn->prepare($sql);
    $stmt->execute($db_arr);
    // Get saved id
    $product_id = $conn->lastInsertId();

    // Add Supplier
    if ($table_name === 'products') {
        $suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];
        if ($suppliers) {
            foreach ($suppliers as $supplier) {
                $supplier_data = [
                    'supplier_id' => $supplier,
                    'product_id' => $product_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $sql = "INSERT INTO productsuppliers
                            (supplier, product, updated_at, created_at) 
                        VALUES 
                            (:supplier_id, :product_id, :updated_at, :created_at)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($supplier_data);
            }
        }
    }
    // Add Products to Supplier
    if ($table_name === 'suppliers') {

        $products = isset($_POST['products']) ? $_POST['products'] : [];

        if ($products) {
            $supplier_id = $conn->lastInsertId();

            foreach ($products as $product_id) {
                $supplier_data = [
                    'supplier_id' => $supplier_id,
                    'product_id'  => $product_id,
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'created_at'  => date('Y-m-d H:i:s')
                ];

                $sql = "INSERT INTO productsuppliers
                            (supplier, product, updated_at, created_at)
                        VALUES
                            (:supplier_id, :product_id, :updated_at, :created_at)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($supplier_data);
            }
        }
    }


    $response = [
        'success' => true,
        'message' => ' Successfully added to the System.'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../' . $_SESSION['redirect_to']);
