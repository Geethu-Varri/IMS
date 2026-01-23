<?php
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'suppliers';
$_SESSION['redirect_to'] = 'supplier-add.php';

$user = $_SESSION['user'];
// $users = include('database/show-users.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
    <?php include('partials/app-header-scripts.php'); ?>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sideBar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
            <div class="dashboard_content">
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="section-header"><i class="fa-solid fa-plus"></i>Create Supplier</h1>

                            <!-- <div class="dashboard_content_main"> -->
                            <div id="userAddFormContainer">
                                <form action="./database/add.php" method="POST" class="appForm" id="userAddForm" enctype="multipart/form-data">
                                    <div class="appFormInputContainer">
                                        <label for="supplier_name">Supplier Name</label>
                                        <input type="text" id="supplier_name" placeholder="Enter supplier name..." class="appFormInput" name="supplier_name">
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="supplier_location">Location</label>
                                        <input type="text" id="supplier_location" class="appFormInput " placeholder="Enter product supplier locationy..." name="supplier_location">
                                    </div>
                                    <div class="appFormInputContainer">
                                        <label for="email">Email</label>
                                        <input type="text" id="email" class="appFormInput " placeholder="Enter supplier email..." name="email">
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
            </div>
        </div>
        <?php include('partials/app-scripts.php'); ?>

</body>

</html>