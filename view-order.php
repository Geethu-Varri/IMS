<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');

$show_table = 'suppliers';
$suppliers = include('database/show.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('partials/app-header-scripts.php'); ?>
    <title>View Purchase Orders</title>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sideBar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
            <div class="dashboard_content">
                <?php 
                    $permissions = $user['permissions'];
                         if(in_array('po_view', $permissions)){
                ?>
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="section-header"><i class="fa-solid fa-list"></i>List of Purchase Orders</h1>
                            <div class="section-content">
                                <div class="poListContainers">
                                    <?php
                                    $stmt = $conn->prepare("SELECT order_product.id,order_product.product,products.product_name,order_product.quantity_ordered,users.first_name,
                                                                order_product.batch, users.last_name,order_product.quantity_received, 
                                                                suppliers.supplier_name, order_product.status,order_product.created_at
                                                                    FROM order_product, suppliers, products, users 
                                                                    WHERE
                                                                          order_product.supplier = suppliers.id
                                                                    AND
                                                                        order_product.product = products.id
                                                                    AND
                                                                         order_product.created_by = users.id 
                                                                    ORDER BY
                                                                          order_product.created_at DESC

                                                            ");
                                    $stmt->execute();
                                    $purchase_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    $data = [];
                                    foreach ($purchase_orders as $purchase_order) {
                                        $data[$purchase_order['batch']][] = $purchase_order;
                                    }

                                    ?>
                                    <?php
                                    foreach ($data as $batch_id => $batch_pos) {
                                    ?>
                                        <div class="poList" id="container-<?= $batch_id ?>">
                                            <p>Batch #: <?= $batch_id ?></p>
                                            <table class="batchTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Product</th>
                                                        <th>Qty Ordered</th>
                                                        <th>Qty Received</th>
                                                        <th>Supplier</th>
                                                        <th>Status</th>
                                                        <th>Ordered By</th>
                                                        <th>Created Date</th>
                                                        <th>Delivery History</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($batch_pos as $index => $batch_po) {

                                                    ?>
                                                        <tr>
                                                            <td><?= $index + 1 ?></td>
                                                            <td class="po_product"><?= $batch_po['product_name'] ?></td>
                                                            <td class="po_qty_ordered"><?= $batch_po['quantity_ordered'] ?></td>
                                                            <td class="po_qty_received"><?= $batch_po['quantity_received'] ?></td>
                                                            <td class="po_qty_supplier"><?= $batch_po['supplier_name'] ?></td>
                                                            <td class="po_qty_status"><span class="po-badge po-badge-<?= $batch_po['status'] ?>"><?= $batch_po['status'] ?></span></td>
                                                            <td><?= $batch_po['first_name'] . ' ' . $batch_po['last_name'] ?></td>
                                                            <td>
                                                                <?= $batch_po['created_at'] ?>
                                                                <input type="hidden" class="po_qty_row_id" value="<?= $batch_po['id'] ?>">
                                                                <input type="hidden" class="po_qty_productid" value="<?= $batch_po['product'] ?>">
                                                            </td>
                                                            <td>
                                                                <button class="appbtn appDeliveryHistory" data-id="<?= $batch_po['id'] ?>">Deliveries</button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                            <?php 
                                                $permissions = $user['permissions'];
                                                    if(in_array('po_edit', $permissions)){
                                            ?>
                                            <div class="poOrderUpdateBtnContainer alignRight">
                                                <button class="appbtn updatePoBtn" data-id="<?= $batch_id ?>">Update</button>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>

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
function script() {
    var vm = this;

    this.registerEvents = function() {
        document.addEventListener('click', function(e) {
            targetElement = e.target;
            classList = targetElement.classList;

            if (classList.contains('updatePoBtn')) {

                e.preventDefault();

                batchNumber = targetElement.dataset.id;
                batchNumberContainer = 'container-' + batchNumber;

                productList = document.querySelectorAll('#' + batchNumberContainer + ' .po_product');
                qtyOrderedList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_ordered');
                qtyReceivedList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_received');
                supplierList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_supplier');
                statusList = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_status');
                rowIds = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_row_id');
                pIds = document.querySelectorAll('#' + batchNumberContainer + ' .po_qty_productid');

                poListsArr = [];

                for (i = 0; i < productList.length; i++) {
                    poListsArr.push({
                        name: productList[i].innerText,
                        qtyOrdered: qtyOrderedList[i].innerText,
                        qtyReceived: qtyReceivedList[i].innerText,
                        supplier: supplierList[i].innerText,
                        status: statusList[i].innerText,
                        id: rowIds[i].value,
                        pid: pIds[i].value
                    });
                }

                var poListHtml = '\
                            <table id="formTable_' + batchNumber + '">\
                            <thead>\
                            <tr>\
                            <th>Product Name</th>\
                            <th>Qty Ordered</th>\
                            <th>Qty Received</th>\
                            <th>Qty Delivered</th>\
                            <th>Supplier</th>\
                            <th>Status</th>\
                            </tr>\
                            </thead>\
                            <tbody>';

                poListsArr.forEach((poList) => {
                    poListHtml += '\
                                    <tr>\
                                    <td class="po_product alignLeft">' + poList.name + '</td>\
                                    <td class="po_qty_ordered">' + poList.qtyOrdered + '<input type="hidden" class="qty-ordered" value="' + poList.qtyOrdered + '"></td>\
                                    <td class="po_qty_received">' + poList.qtyReceived + '</td>\
                                    <td class="po_qty_delivered">\
                                        <input type="number" value="0" class="qty-delivered">\
                                        <div class="qty-error" style="color:red;font-size:12px;display:none;">\
                                            Delivered quantity cannot be more than ordered </div>\
                                    </td>\
                                    <td class="po_qty_supplier alignLeft">' + poList.supplier + '</td>\
                                    <td>\
                                    <select class="po_qty_status status">\
                                    <option value="pending" ' + (poList.status == 'pending' ? 'selected' : '') + '>pending</option>\
                                    <option value="incomplete" ' + (poList.status == 'incomplete' ? 'selected' : '') + '>incomplete</option>\
                                    <option value="complete" ' + (poList.status == 'complete' ? 'selected' : '') + '>complete</option>\
                                    </select>\
                                    <input type="hidden" class="po_qty_row_id" value="' + poList.id + '">\
                                    <input type="hidden" class="po_qty_pid" value="' + poList.pid + '">\
                                    </td>\
                                    </tr>';
                });

                poListHtml += '</tbody></table>';

                BootstrapDialog.show({
                    type: BootstrapDialog.TYPE_PRIMARY,
                    title: 'Update Purchase Order: Batch #: <strong>' + batchNumber + '</strong>',
                    message: poListHtml,
                    buttons: [
                        {
                            label: 'Cancel',
                            action: function(dialogRef) {
                                dialogRef.close();
                            }
                        },
                        {
                            label: 'OK',
                            cssClass: 'btn-primary',
                            action: function(dialogRef) {

                                formTableContainer = 'formTable_' + batchNumber;

                                qtyReceivedList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_received');
                                qtyDeliveredList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_delivered input');
                                statusList = document.querySelectorAll('#' + formTableContainer + ' .po_qty_status');
                                rowIds = document.querySelectorAll('#' + formTableContainer + ' .po_qty_row_id');
                                qtyOrdered = document.querySelectorAll('#' + formTableContainer + ' .po_qty_ordered');
                                pids = document.querySelectorAll('#' + formTableContainer + ' .po_qty_pid');

                                poListsArrForm = [];

                                for (i = 0; i < qtyDeliveredList.length; i++) {
                                    poListsArrForm.push({
                                        qtyReceive: qtyReceivedList[i].innerText,
                                        qtyDelivered: qtyDeliveredList[i].value,
                                        status: statusList[i].value,
                                        id: rowIds[i].value,
                                        qtyOrdered: qtyOrdered[i].innerText,
                                        pid: pids[i].value
                                    });
                                }

                                $.ajax({
                                    method: 'POST',
                                    data: { payload: poListsArrForm },
                                    url: 'database/update-order.php',
                                    dataType: 'json',
                                    success: function(data) {
                                        BootstrapDialog.alert({
                                            type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                            message: data.message,
                                            callback: function() {
                                                if (data.success) location.reload();
                                            }
                                        });
                                    }
                                });

                                dialogRef.close();
                            }
                        }
                    ],
                    onshown: function(dialogRef) {

                        // ===== AUTO STATUS FEATURE (ONLY ADDITION) =====
                        document.querySelectorAll('#formTable_' + batchNumber + ' .qty-delivered')
                        .forEach(input => {
                            input.addEventListener('input', function() {

                                let row = this.closest('tr');
                                let ordered = parseInt(row.querySelector('.qty-ordered').value);
                                let delivered = parseInt(this.value) || 0;
                                let status = row.querySelector('.status');

                                row.querySelector('.qty-error').style.display = 'none';

                                if (delivered === 0) {
                                    status.value = 'pending';
                                } 
                                else if (delivered < ordered) {
                                    status.value = 'incomplete';
                                } 
                                else if (delivered === ordered) {
                                    status.value = 'complete';
                                } 
                                else {
                                    row.querySelector('.qty-error').style.display = 'block';
                                    status.value = 'pending';   // ✅ not complete
                                }
                            });
                        });
                        // ===== END FEATURE =====
                    }
                });

            }

            if(classList.contains('appDeliveryHistory')){
                let id = targetElement.dataset.id;
                $.get('database/view-delivery-history.php', {id:id}, function(data){
                    if(data.length){
                        rows = '';
                        data.forEach((row,id) => {
                            receivedDate = new Date(row['date_received']); 
                            rows += '\
                                    <tr>\
                                    <td>'+ (id+1) +'</td>\
                                    <td>'+ receivedDate.toUTCString()  +'</td>\
                                    <td>'+ row['qty_received'] +'</td>\
                                    </tr>';
                        });

                        deliveryHistoryHtml = '<table class="deliveryHistoryTable">\
                                                <thead>\
                                                <tr>\
                                                <th>#</th>\
                                                <th>Date Received</th>\
                                                <th>Quantity Received</th>\
                                                </tr>\
                                                </thead>\
                                                <tbody>'+ rows +'</tbody>\
                                                </table>';

                        BootstrapDialog.show({
                            title: '<strong>Delivery Histories</strong>',
                            type: BootstrapDialog.TYPE_PRIMARY,
                            message: deliveryHistoryHtml
                        })
                    } else {
                        BootstrapDialog.alert({
                            title: '<strong>No Delivery History</strong>',
                            type: BootstrapDialog.TYPE_INFO,
                            message: 'No delivery history found on selected product.'
                        });
                    }
                },'json');
            }

        });
    },

    this.initialize = function() {
        this.registerEvents();
    };
}

var script = new script();
script.initialize();
</script>
<script>
$(document).ready(function(){

    $('.batchTable').each(function(index, table){

        $(table).DataTable({
            dom: 'lBfrtip',

            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // S.NO per batch
                    }
                }
            ],

            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'Order_Report',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7],
                        format: {
                            body: function (data) {
                                if (!data) return '';
                                return data.toString().replace(/<[^>]*>/g, '').trim();
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export PDF',
                    title: 'Order_Report',
                    orientation: 'landscape',
                    pageSize: 'A4',

                    customize: function (doc) {

                        // Center title
                        doc.styles.title.alignment = 'center';

                        // Reduce page margins (optional)
                        doc.pageMargins = [40, 60, 40, 60];

                        // Find the table
                        doc.content.forEach(function(item){
                            if (item.table) {

                                // Make table narrower instead of full width
                                var colCount = item.table.body[0].length;
                                item.table.widths = Array(colCount).fill('*'); // auto width

                                // Center the table block
                                item.alignment = 'center';

                                // Add top margin
                                item.margin = [0, 10, 0, 0];
                            }
                        });
                    },

                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7],
                        format: {
                            body: function (data) {
                                if (!data) return '';
                                return data.toString().replace(/<[^>]*>/g, '').trim();
                            }
                        }
                    }
                }

            ]
        });

    });

});
</script>

</body>

</html>