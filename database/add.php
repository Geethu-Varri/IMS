<?php 
     session_start();
     $table_name = $_SESSION['table'];
     $_SESSION['table'] = '';

     $first_name = $_POST=['first_name'];
     $last_name = $_POST['last_name'];
     $email = $_POST['email'];
     $password = $_POST['password'];
     $encrypted = password_hash($password,PASSWORD_DEFAULT);
     var_dump($_POST);
?>