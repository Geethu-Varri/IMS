<?php
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'products';
$_SESSION['redirect_to'] = 'product-add.php';

$user = $_SESSION['user'];
// $users = include('database/show-users.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <?php include('partials/app-header-scripts.php'); ?>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sideBar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
            <div class="dashboard_content">
                <?php 
                    $permissions = $user['permissions'];
                         if(in_array('product_create', $permissions)){
                ?>
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="section-header"><i class="fa-solid fa-plus"></i>Create Product</h1>

                            <!-- <div class="dashboard_content_main"> -->
                            <div id="userAddFormContainer">
                                <form action="./database/add.php" method="POST" class="appForm" id="userAddForm" enctype="multipart/form-data">
                                    <div class="appFormInputContainer">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" id="product_name" placeholder="Enter product name..." class="appFormInput" name="product_name">
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="description">Description</label>
                                        <textarea id="description" class="appFormInput productTextAreaInput" placeholder="Enter product description..." name="description"></textarea>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="description">Suppliers</label>
                                        <select name="suppliers[]\"  id="suppliersSelect" multiple="">
                                            <option value="">Select Supplier</option>
                                            <?php
                                            //  $_SESSION['table'] = "suppliers";
                                             $show_table = 'suppliers';
                                             $suppliers = include('database/show.php');
                                             foreach($suppliers as $supplier){
                                                echo "<option value='". $supplier['id'] ."' >".$supplier['supplier_name']."</option>"; 
                                             }
                                            ?>
                                    
                                        </select>
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="product_name">Product Image</label>
                                        <input type="file" name="img">
                                    </div>
                                    
                                    <!-- <input type="hidden" name="table" value="users"> -->
                                    <button type="submit" class="appBtn"><i class="fa-solid fa-plus"></i>Create Product</button>

                                </form>
                                <?php
                                if (isset($_SESSION['response'])) {
                                    $response_message = $_SESSION['response']['message'];
                                    $is_success = $_SESSION['response']['success'];
                                ?>
                                    <div class="responseMessage">
                                        <p class="responseMessage <?= $is_success ? 'responseMessage__success' : 'responseMessage__error' ?>">
                                            <?= $response_message ?>
                                        </p>

                                    </div>
                                <?php unset($_SESSION['response']);
                                } ?>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <?php } else { ?>
                            <div id="errorMessage"> Access denied.</div>
                <?php } ?>

            </div>
        </div>
        <?php include('partials/app-scripts.php'); ?>

</body>

</html>