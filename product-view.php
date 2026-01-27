<?php
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
// $_SESSION['table'] = 'products';
// $user = $_SESSION['user'];

//Get all products
$show_table = 'products';
$products = include('database/show.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <?php include('partials/app-header-scripts.php'); ?>
    <title>View Products</title>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sideBar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
            <div class="dashboard_content">
                <?php 
                    $permissions = $user['permissions'];
                         if(in_array('product_view', $permissions)){
                ?>
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="section-header"><i class="fa-solid fa-list"></i>List of Products</h1>
                            <div class="section-content">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Stock</th>
                                                <th>Description</th>
                                                <th>Suppliers</th>
                                                <th>Created By</th>
                                                <th>Craeted At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($products as $index => $product) { ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td class="firstname">
                                                        <img src="uploads/products/<?= $product['img'] ?>" alt="" class="productImages">

                                                    </td>
                                                    <td class="lastName"><?= $product['product_name'] ?></td>
                                                    <td class="lastName"><?= $product['stock'] ?></td>
                                                    <td class="email"><?= $product['description'] ?></td>
                                                    <td class="email">
                                                        <?php
                                                           $supplier_list = '-';
                                                           $pid = $product['id'];
                                                           $stmt = $conn->prepare("SELECT supplier_name 
                                                                                       FROM suppliers, productsuppliers 
                                                                                       WHERE
                                                                                          productsuppliers.product=$pid
                                                                                       AND
                                                                                          productsuppliers.supplier = suppliers.id

                                                                                       ");
                                                           $stmt->execute();
                                                           $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                           if($row){
                                                            $supplier_arr = array_column($row,'supplier_name');
                                                            // $supplier_list = '<li>'.implode("</li><li>",$supplier_arr);
                                                            $supplier_list = '<ul><li>' . implode('</li><li>', $supplier_arr) . '</li></ul>';

                                                           }
                                                           echo $supplier_list;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                           $uid = $product['created_by'];
                                                           $stmt = $conn->prepare("SELECT * FROM users WHERE id=$uid");
                                                           $stmt->execute();
                                                           $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                           
                                                           $created_by_name = $row['first_name'] . ' ' . $row['last_name'];
                                                           echo $created_by_name;
                                                        ?>

                                                    </td>
                                                    <td><?= date('M d, Y @ h:i:s A', strtotime($product['created_at']))  ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($product['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" 
                                                           class="<?= in_array('product_edit', $user['permissions']) ? 'updateProduct' : 'accessDeniedErr' ?>" 
                                                           data-pid="<?= $product['id'] ?>" >
                                                           <i class="fa-solid fa-pencil"></i>Edit</a>
                                                        <a href=""
                                                           class="<?= in_array('product_delete', $user['permissions']) ? 'deleteProduct' : 'accessDeniedErr' ?>" 
                                                           data-name = "<?= $product['product_name'] ?>"
                                                           data-pid="<?= $product['id'] ?>"><i class="fa-solid fa-trash-can"></i>Delete</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($products) ?>Products</p>
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
<?php
 include('partials/app-scripts.php'); 

    $show_table = 'suppliers';
    $suppliers = include('database/show.php');

    $suppliers_arr = [];

    foreach($suppliers as $supplier){
    $suppliers_arr[$supplier['id']] = $supplier['supplier_name']; "</option>";
    }
    $suppliers_arr = json_encode($suppliers_arr);
   
 ?>

<script>
    var suppliersList = <?= $suppliers_arr ?>;

    function script() {
        var vm = this;

        this.registerEvents = function() {
            document.addEventListener('click', function(e) {
                targetElement = e.target;
                classList = targetElement.classList;

                if (classList.contains('deleteProduct')) {
                    e.preventDefault();

                    pId = targetElement.dataset.pid;
                    pName = targetElement.dataset.name;

                    BootstrapDialog.confirm({
                        type: BootstrapDialog.TYPE_DANGER,
                        title: 'Delete Product',
                        message: 'Are you sure to delete <strong>' + pName + '</strong>?',
                        callback: function(isDelete) {
                            if (isDelete) {
                                $.ajax({
                                    method: 'POST',
                                    data: {
                                        id: pId,
                                        table: 'products'
                                    },
                                    url: 'database/delete.php',
                                    dataType: 'json',
                                    success: function(data) {
                                        message = data.success ?
                                            pName + ' successfully deleted' :
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
                // if(classList.contains('accessDeniedErr')){
                //     e.preventDefault();
                //     BootstrapDialog.alert({
                //         type: BootstrapDialog.TYPE_DANGER,
                //         message: 'Access Denied'
                //     });
                // }

                if (classList.contains('updateProduct')) {
                    e.preventDefault();
                    pId = targetElement.dataset.pid;
                    vm.showEditDialog(pId);
                }
            });
            
            // $('#editProductForm').on('submit',function(e){
            //     e.preventDefault();

            // });
            document.addEventListener('submit',function(e){
                e.preventDefault();
                targetElement = e.target;
                if(targetElement.id === 'editProductForm'){
                   vm.saveUpdatedData(targetElement);
                }
            })
        },
        this.saveUpdatedData = function(form){
            $.ajax({
                method: 'POST',
                data: new FormData(form),
                url: 'database/update-product.php',
                processData:false,
                contentType:false,
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
                'database/get-product.php',
                { id: id },
                function(productDetails) {
                     
                     let curSuppliers = productDetails['suppliers'];
                     let supplierOption = '';

                    for(const [supId,supName] of Object.entries(suppliersList)){
                        selected = curSuppliers.indexOf(parseInt(supId)) > -1 ? 'selected' : '';
                        supplierOption += "<option "+ selected +" value='"+ supId +"'>"+ supName +"</option>";
                    }

                    BootstrapDialog.confirm({
                       
                        title: 'Update <strong>' + productDetails.product_name + '</strong>',
                        message: '<form enctype="multipart/form-data" id="editProductForm" >\
                            <div class="appFormInputContainer">\
                            <label for="product_name">Product Name</label>\
                            <input type="text" id="product_name" value = "'+ productDetails.product_name +'" placeholder="Enter product name..." class="appFormInput" name="product_name">\
                        </div>\
                        <div class="appFormInputContainer">\
                            <label for="description">Suppliers</label>\
                            <select name="suppliers[]"  id="suppliersSelect" multiple="">\
                                <option value="">Select Supplier</option>\
                                ' + supplierOption + '\
                            </select>\
                        </div>\
                        <div class="appFormInputContainer">\
                            <label for="description">Description</label>\
                            <textarea id="description" class="appFormInput productTextAreaInput"  placeholder="Enter product description..." name="description">'+ productDetails.description +'</textarea>\
                        </div>\
                        <div class="appFormInputContainer">\
                            <label for="product_name">Product Image</label>\
                            <input type="file" name="img">\
                        </div>\
                        <input type="hidden" name="pid" value="'+ productDetails.id +'">\
                        <input type="submit" value="submit" id="editProductSubmitBtn" class="hidden">\
                        </form>\ ',
                        callback: function(isUpdate) {
                            if (isUpdate) {
                                // update logic here
                                document.getElementById('editProductSubmitBtn').click();
                               
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