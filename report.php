<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');

$user = $_SESSION['user'];
$permissions = $user['permissions'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <title>Dashboard</title>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sidebar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
                <?php 
                    $permissions = $user['permissions'];
                         if(in_array('report_view', $permissions)){
                ?>
                      <div id="reportsContainer">
                        <div class="reportTypeContainer">
                            <div class="reportType">
                                <p>Export Products</p>
                                <div class="alignRight">
                                    <a href="database/report_csv.php?report=product" class="reportExportBtn">Excel</a>
                                    <a href="database/report_pdf.php?report=product" target="_blank" class="reportExportBtn">PDF</a>
                                </div>
                            </div>
                            <div class="reportType">
                                <p>Export Suppliers</p>
                                <div class="alignRight">
                                    <a href="database/report_csv.php?report=supplier" class="reportExportBtn">Excel</a>
                                    <a href="database/report_pdf.php?report=supplier" target="_blank" class="reportExportBtn">PDF</a>
                                </div>
                            </div>
                        </div>
                        <div class="reportTypeContainer">
                            <div class="reportType">
                                <p>Export Deliveries</p>
                                <div class="alignRight">
                                    <a href="database/report_csv.php?report=delivery" class="reportExportBtn">Excel</a>
                                    <a href="database/report_pdf.php?report=delivery" target="_blank" class="reportExportBtn">PDF</a>
                                </div>
                            </div>
                            <div class="reportType">
                                <p>Export Purchase Orders</p>
                                <div class="alignRight">
                                    <a href="database/report_csv.php?report=purchase_orders" class="reportExportBtn">Excel</a>
                                    <a href="database/report_pdf.php?report=purchase_orders" target="_blank" class="reportExportBtn">PDF</a>
                                </div>
                            </div>
                        </div>
                      </div>
                      <?php } else { ?>
                            <div id="errorMessage"> Access denied.</div>
                    <?php } ?>
                </div>
            </div>
<script src="js/script.js"></script>

</body>

</html>