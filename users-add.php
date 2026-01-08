<?php
session_start();
if (!isset($_SESSION['user'])) header('loaction: login.php');
$_SESSION['table'] = 'users';
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="css/font-awesome/css/all.min.css">

    <!-- <link rel="stylesheet" href="css/font-awesome"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> -->
    <!-- <link rel="stylesheet" href="css/font-awesome"> -->
    <title>Users</title>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sideBar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
            <div class="dashboard_content">
                <div class="dashboard_content_main">
                    <div id="userAddFormContainer">
                        <form action="./database/add.php" method="POST" class="appForm" id="userAddForm">
                        <div class="appFormInputContainer">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" class="appFormInput" name="first_name">
                        </div>
                        <div class="appFormInputContainer">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" class="appFormInput" name="last_name">
                        </div>
                        <div class="appFormInputContainer">
                            <label for="email">Email</label>
                            <input type="text" id="email" class="appFormInput" name="email">
                        </div>
                        <div class="appFormInputContainer">
                            <label for="password">Password</label>
                            <input type="password" id="password" class="appFormInput" name="password">
                        </div>
                        <!-- <input type="hidden" name="table" value="users"> -->
                        <button type="submit" class="appBtn"><i class="fa-solid fa-plus"></i>Add User</button>
                        
                    </form>

                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>

</body>

</html>