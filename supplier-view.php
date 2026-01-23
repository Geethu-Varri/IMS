<?php
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
// $_SESSION['table'] = 'products';
// $user = $_SESSION['user'];
$show_table = 'suppliers';
$suppliers = include('database/show.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <?php include('partials/app-header-scripts.php'); ?>
    <title>View Suppliers</title>
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
                            <h1 class="section-header"><i class="fa-solid fa-list"></i>List of Suppliers</h1>
                            <div class="section-content">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Supplier Name</th>
                                                <th>Supplier Location</th>
                                                <th>Contact Details</th>
                                                <th>Products</th>
                                                <th>Created By</th>
                                                <th>Craeted At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  foreach ($suppliers as $index => $supplier) { ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td>
                                                        <?= $supplier['supplier_name'] ?>
                                                    </td>
                                                    <td><?= $supplier['supplier_location'] ?></td>
                                                    <td><?= $supplier['email'] ?></td>
                                                    <td>
                                                        <?php
                                                           $product_list = '-';
                                                           $sid = $supplier['id'];
                                                           $stmt = $conn->prepare("SELECT product_name 
                                                                                       FROM products, productsuppliers 
                                                                                       WHERE
                                                                                          productsuppliers.supplier=$sid
                                                                                       AND
                                                                                          productsuppliers.product = products.id

                                                                                       ");
                                                           $stmt->execute();
                                                           $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                           if($row){
                                                            $product_arr = array_column($row,'product_name');
                                                            // $supplier_list = '<li>'.implode("</li><li>",$supplier_arr);
                                                            $product_list = '<ul><li>' . implode('</li><li>', $product_arr) . '</li></ul>';

                                                           }
                                                           echo $product_list;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                           $uid = $supplier['created_by'];
                                                           $stmt = $conn->prepare("SELECT * FROM users WHERE id=$uid");
                                                           $stmt->execute();
                                                           $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                           
                                                           $created_by_name = $row['first_name'] . ' ' . $row['last_name'];
                                                           echo $created_by_name;
                                                        ?>

                                                    </td>
                                                    <td><?= date('M d, Y @ h:i:s A', strtotime($supplier['created_at']))  ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($supplier['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" class="updateSupplier" data-sid="<?= $supplier['id'] ?>" ><i class="fa-solid fa-pencil"></i>Edit</a>
                                                        <a href="" class="deleteSupplier" data-name = "<?= $supplier['supplier_name'] ?>" data-sid="<?= $supplier['id'] ?>"><i class="fa-solid fa-trash-can"></i>Delete</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($suppliers) ?>Suppliers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
 include('partials/app-scripts.php'); 

    $show_table = 'products';
    $products = include('database/show.php');

    $products_arr = [];

    foreach($products as $product){
    $products_arr[$product['id']] = $product['product_name']; "</option>";
    }
    $products_arr = json_encode($products_arr);
   
 ?>

<script>
    var productsList = <?= $products_arr ?>;


    function script() {
        var vm = this;

        this.registerEvents = function() {
            document.addEventListener('click', function(e) {
                targetElement = e.target;
                classList = targetElement.classList;

                if (classList.contains('deleteSupplier')) {
                    e.preventDefault();

                    sId = targetElement.dataset.sid;
                    supplierName = targetElement.dataset.name;

                    BootstrapDialog.confirm({
                        type: BootstrapDialog.TYPE_DANGER,
                        title: 'Delete Supplier',
                        message: 'Are you sure to delete <strong>' + supplierName + '</strong>?',
                        callback: function(isDelete) {
                            if (isDelete) {
                                $.ajax({
                                    method: 'POST',
                                    data: {
                                        id: sId,
                                        table: 'suppliers'
                                    },
                                    url: 'database/delete.php',
                                    dataType: 'json',
                                    success: function(data) {
                                        message = data.success ?
                                            supplierName + ' successfully deleted' :
                                            'Error processing your request!';

                                        BootstrapDialog.alert({
                                            type: data.success
                                                ? BootstrapDialog.TYPE_SUCCESS
                                                : BootstrapDialog.TYPE_DANGER,
                                            message: message,
                                            callback: function() {
                                                if (data.success) location.reload();
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });
                }

                if (classList.contains('updateSupplier')) {
                    e.preventDefault();
                    sId = targetElement.dataset.sid;
                    vm.showEditDialog(sId);
                }
            });
            
            // $('#editProductForm').on('submit',function(e){
            //     e.preventDefault();

            // });
            document.addEventListener('submit',function(e){
                e.preventDefault();
                targetElement = e.target;
                if(targetElement.id === 'editSupplierForm'){
                   vm.saveUpdatedData(targetElement);
                }
            })
        },
        this.saveUpdatedData = function(form){
            $.ajax({
                method: 'POST',
                data: {
                    supplier_name: document.getElementById('supplier_name').value,
                    supplier_location: document.getElementById('supplier_location').value,
                    email: document.getElementById('email').value,
                    products: $('#products').val(),
                    sid: document.getElementById('sid').value
                                },
                url: 'database/update-supplier.php',
                // processData:false,
                // contentType:false,
                dataType:'json',
                success: function(data) {
                    BootstrapDialog.alert({
                        type:data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                        message: data.message,
                        callback:function(){
                            if(data.success) location.reload();
                        } 
                    });
                    
                }
            });

        },

        this.showEditDialog = function(id) {
            $.get(
                'database/get-supplier.php',
                { id: id },
                function(supplierDetails) {
                     
                     let curProducts = supplierDetails['products'];
                     let productOptions = '';

                    for(const [pId,pName] of Object.entries(productsList)){
                        selected = curProducts.indexOf(parseInt(pId)) > -1 ? 'selected' : '';
                        productOptions += "<option "+ selected +" value='"+ pId +"'>"+ pName +"</option>";
                    }

                    BootstrapDialog.confirm({
                       
                        title: 'Update <strong>' + supplierDetails.supplier_name + '</strong>',
                        message: '<form enctype="multipart/form-data" id="editSupplierForm" >\
                            <div class="appFormInputContainer">\
                                <label for="supplier_name">Supplier Name</label>\
                                <input type="text" id="supplier_name" value="'+ supplierDetails.supplier_name +'" placeholder="Enter supplier name..." class="appFormInput" name="supplier_name">\
                            </div>\
                            <div class="appFormInputContainer">\
                                <label for="supplier_location">Location</label>\
                                <input type="text" id="supplier_location" class="appFormInput " value="'+ supplierDetails.supplier_location +'"  placeholder="Enter product supplier location..." name="supplier_location">\
                            </div>\
                            <div class="appFormInputContainer">\
                                <label for="email">Email</label>\
                                <input type="text" id="email" class="appFormInput " value="'+ supplierDetails.email +'" placeholder="Enter supplier email..." name="email">\
                            </div>\
                        <div class="appFormInputContainer">\
                            <label for="products">Products</label>\
                            <select name="products[]"  id="products" multiple="">\
                                <option value="">Select Products</option>\
                                ' + productOptions + '\
                            </select>\
                        </div>\
                        <input type="hidden" name="sid" id="sid" value="'+ supplierDetails.id +'">\
                        <input type="submit" value="submit" id="editSupplierSubmitBtn" class="hidden">\
                        </form>\ ',
                        callback: function(isUpdate) {
                            if (isUpdate) {
                                // update logic here
                                document.getElementById('editSupplierSubmitBtn').click();
                               
                            }
                        }
                    });
                },
                'json'
            );
        };

        this.initialize = function() {
            this.registerEvents();
        };
    }

    var script = new script();
    script.initialize();

</script>
</body>
</html>