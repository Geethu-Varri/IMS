<?php
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'users';
$_SESSION['redirect_to'] = 'users-add.php';

$show_table = 'users';
$users = include('database/show.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Users</title>
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
                         if(in_array('user_create', $permissions)){
                ?>
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="section-header"><i class="fa-solid fa-plus"></i>Create User</h1>

                            <!-- <div class="dashboard_content_main"> -->
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
                                    <input type="hidden" id="permission_el" name="permissions">
                                    <!-- 
                                         Create a section for permissions
                                         organized by modules > functions
                                     -->
                                    <?php include('partials/permissions.php') ?>
                                    <button type="submit" class="appBtn"><i class="fa-solid fa-plus"></i>Add User</button>

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
<script>
    function loadScript(){
        this.permissions = [];
        this.initialize = function(){
            this.registerEvents();
        },

        this.registerEvents = function(){
            // Click
            document.addEventListener('click',function(e){
                let target = e.target;

                // Check if class name - moduleFnc - is clicked
                if(target.classList.contains('moduleFunc')){

                    // Get the value
                    let permissionName = target.dataset.value;

                    // Set the active class
                    if(target.classList.contains('permissionActive')){
                        target.classList.remove('permissionActive');

                        // Remove from array
                        script.permissions = script.permissions.filter((name) =>{
                            return name !== permissionName;
                        });
                    }
                    else{
                        target.classList.add('permissionActive');
                        script.permissions.push(permissionName);
                    } 

                    // Update the hidden element
                    document.getElementById('permission_el')
                         .value = script.permissions.join(',');
                }
            })

        }

    }
    var script = new loadScript;
    script.initialize();
</script>

</body>

</html>