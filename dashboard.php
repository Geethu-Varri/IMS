<?php
session_start();

if (!isset($_SESSION['user'])) header('location: login.php');
$user = $_SESSION['user'];

// Get graph data - purchase order by status
include('database/po_status_pie_graph.php');

// Get graph data - supplier product count
include('database/supplier_product_bar_graph.php');

// Get line graph data - delivery history per day
include('database/delivery_history.php');

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
            // if(in_array('dashboard_view',$user['permissions'])){ 
            // $permissions = explode(',', $user['permissions']);
            //     if(in_array('dashboard_view', $permissions)){
                $permissions = $user['permissions'];
                if(in_array('dashboard_view', $permissions)){
            ?>
            <div class="dashboard_content">
                <div class="dashboard_content_main">
                    <div class="col50">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                            <p class="highcharts-description">
                               Here is the breakdown of the purchase orders by statuses
                            </p>
                        </figure>
                    </div>
                    <div class="col50">
                        <figure class="highcharts-figure">
                            <div id="containerBarChart"></div>
                            <p class="highcharts-description">
                               Here is the breakdown of the purchase orders by statuses
                            </p>
                        </figure>
                    </div>
                    <div id="deliveryHistory" style="width:868px; height:400px;"></div>      
                </div>
            <?php } else { ?>
                    <div id="errorMessage"> Access denied.</div>
            <?php } ?>
            </div>
        </div>
    </div>
<script src="js/script.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
var graphData = <?= json_encode($results) ?>;
Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Purchase Orders By Status',
        align: 'left'
    },
    tooltip: {
        pointFormatter: function(){
            var point = this,
            series = point.series;
            return `<b>${point.name}</b>: <b>${point.y}`
        }
    },
    
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y}'
            }
        }
    },
    series: [{
        name: 'Status',
        colorByPoint: true,
        data: graphData
    }]
});

var barGraphData = <?= json_encode($bar_chart_data) ?>;
var barGraphCategories = <?= json_encode($categories) ?>;

Highcharts.chart('containerBarChart', {
    chart: {
        type: 'column'
    },

    title: {
        text: 'Product Count Assigned to supplier'
    },

    xAxis: {
        categories: barGraphCategories,
        crosshair: true
    },

    yAxis: {
        min: 0,
        title: {
            text: 'Product Count'
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormatter: function(){
            var point = this,
            series = point.series;
            return `<b>${point.category}</b>: ${point.y}`
        }
    },

    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },

    series: [{
        name: 'Suppliers',
        data: barGraphData
    }]
});

var lineCategories = <?= json_encode($line_categories) ?>;
var lineData = <?= json_encode($line_data) ?>;
Highcharts.chart('deliveryHistory', {
    chart: {
        type: 'spline'
    },

    title: {
        text: 'Delivery History Per Day',
        align: 'left'
    },
    xAxis: {
        categories: lineCategories
        
    },

    yAxis: {
        title: {
            text: 'Product Delivered'
        },
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
        }
    },

    series: [{
        name: 'Product Delivered',
        data: lineData
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }
});

</script>


</body>

</html>