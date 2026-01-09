<?php
session_start();
if (!isset($_SESSION['user'])) header('loaction: login.php');
$_SESSION['table'] = 'users';
$user = $_SESSION['user'];
$users = include('database/show-users.php');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="css/login.css">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <title>Users</title>
</head>


<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sideBar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
            <div class="dashboard_content">
                <div class="dashboard_contenty_main">
                    <div class="row">
                        <div class="column column-5">
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
                                    <!-- <input type="hidden" name="table" value="users"> -->
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
                        <div class="column column-7">
                            <h1 class="section-header"><i class="fa-solid fa-list"></i>List of Users</h1>
                            <div class="section-content">
                                <div class="users">

                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Craeted At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($users as $index => $user) { ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= $user['first_name'] ?></td>
                                                    <td><?= $user['last_name'] ?></td>
                                                    <td><?= $user['email'] ?></td>
                                                    <td><?= date('M d, Y @ h:i:s A', strtotime($user['created_at']))  ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($user['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" class="updateUser"><i class="fa-solid fa-pencil"></i>Edit</a>
                                                        <a href="" class="deleteUser" data-userid="<?= $user['id'] ?>" data-fname="<?= $user['first_name'] ?>" data-lname="<?= $user['last_name'] ?>"><i class="fa-solid fa-trash-can"></i>Delete</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($users) ?>Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script src="js/script.js"></script>
        <script>
            function script() {
                this.initialize = function() {
                        this.registerEvents();
                    },
                    this.registerEvents = function() {
                        document.addEventListener('click', function(e) {
                            targetElement = e.target;
                            classList = targetElement.classList;
                            if (classList.contains('deleteUser')) {
                                e.preventDefault();
                                userId = targetElement.dataset.userid;
                                fname = targetElement.dataset.fname;
                                lname = targetElement.dataset.lname;
                                fullname = fname + ' ' + lname;
                                if (window.confirm('Are you sure to delete?' + fullname + '?')) {
                                    $.ajax({
                                        method: 'POST',
                                        data: {
                                            user_id: userId,
                                            f_name: fname,
                                            l_name: lname
                                        },
                                        url: 'database/delete-user.php',
                                        dataType: 'json',
                                        success: function(data) {
                                            if (data.success) {
                                                if (window.confirm(data.message)) {
                                                    location.reload();
                                                }

                                            } else window.alert(data.message);
                                        }
                                    })

                                } else {
                                    console.log("will not delete");
                                }
                            }
                            if (classList.contains('updateUser')) {
                                e.preventDefault(); // prevent from loading
                                alert('editing');

                            }

                        });
                    }
            }
            var script = new script;
            script.initialize();
        </script>

</body>

</html>