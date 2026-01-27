<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');
    $_SESSION['table'] = 'users';
    $user = $_SESSION['user'];
    $permissions = $user['permissions'];

    if (!is_array($permissions)) {
        $permissions = explode(',', $permissions);
    }

    $show_table = 'users';
    $users = include('database/show.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <?php include('partials/app-header-scripts.php'); ?>
    <title>View Users</title>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sideBar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topNav.php') ?>
            <div class="dashboard_content">
                <?php 
                         if(in_array('user_view', $permissions)){
                ?>
                <div class="dashboard_content_main">
                    <div class="row">
                        <div class="column column-12">
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
                                            <?php foreach ($users as $index => $u) { ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td class="firstName"><?= $u['first_name'] ?></td>
                                                    <td class="lastName"><?= $u['last_name'] ?></td>
                                                    <td class="email"><?= $u['email'] ?></td>
                                                    <td><?= date('M d, Y @ h:i:s A', strtotime($u['created_at']))  ?></td>
                                                    <td><?= date('M d,Y @ h:i:s A', strtotime($u['updated_at'])) ?></td>
                                                    <td>
                                                        <a href=""
                                                            class="<?= in_array('user_edit', $permissions) ? 'updateUser' : 'accessDeniedErr' ?>" 
                                                            data-userid="<?= $u['id'] ?>"
                                                            data-permissions="<?= $u['permissions'] ?>" ><i class="fa-solid fa-pencil"></i>Edit</a>
                                                        <a href="" 
                                                           class="<?= in_array('user_delete', $permissions) ? 'deleteUser' : 'accessDeniedErr' ?>" 
                                                           data-userid="<?= $u['id'] ?>" data-fname="<?= $u['first_name'] ?>" 
                                                           data-lname="<?= $u['last_name'] ?>"><i class="fa-solid fa-trash-can"></i>Delete</a>
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
                <?php } else { ?>
                            <div id="errorMessage"> Access denied.</div>
                <?php } ?>
            </div>
        </div>
<?php include('partials/app-scripts.php'); ?>

<!-- <script>
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
                                fullName = fname + ' ' + lname;

                                BootstrapDialog.confirm({
                                    title:'Delete User',
                                    type: BootstrapDialog.TYPE_DANGER,
                                    message: 'Are you sure to delete <strong>' + fullName + '</strong> ?',
                                    callback: function(isDelete){
                                        if(isDelete){
                                            $.ajax({
                                        
                                                method: 'POST',
                                                data: {
                                                    id : userId,
                                                    table: 'users',
                                                    
                                                },
                                                url: 'database/delete.php',
                                                dataType: 'json',
                                                success: function(data) {
                                                    message = data.success ?
                                                    fullName + ' successfully deleted' : 'Error processing your request!';
                                                    BootstrapDialog.alert({
                                                        type: data.success  ? BootstrapDialog. TYPE_SUCCESS :BootstrapDialog. TYPE_DANGER ,
                                                        message:message,
                                                        callback: function(){
                                                            if(data.success) location.reload();
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
                                
                            if (classList.contains('updateUser')) {

                                e.preventDefault(); // prevent from loading
                                userPermissions = targetElement.dataset.permissions;
                                userPermissions = userPermissions ? userPermissions.split(',') : [];

                                
                                //Get data
                               firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
                               lastName =  targetElement.closest('tr').querySelector('td.lastName').innerHTML;
                               email = targetElement.closest('tr').querySelector('td.email').innerHTML;
                               userId = targetElement.dataset.userid;

                               BootstrapDialog.confirm({
                                title: 'Update ' + firstName + ' ' + lastName,
                                message: '<form>\
                                            <div class="form-group">\
                                            <label>First Name:</label>\
                                            <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
                                            </div>\
                                            <div class="form-group">\
                                            <label>Last Name:</label>\
                                            <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
                                            </div>\
                                            <div class="form-group">\
                                            <label>Email:</label>\
                                            <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
                                            </div>\
                                            \
                                            <div id="permissions">\
                                            <h4>Permissions</h4>\
                                            <hr>\
                                            <div class="permissionsContainer">\
                                            <div class="permission">\
                                            <div class="row">\
                                            <div class="col-md-3"><p class="moduleName">Dashboard</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="dashboard_view">View</p></div>\
                                            </div></div>\
                                            \
                                            <div class="permission">\
                                            <div class="row">\
                                            <div class="col-md-3"><p class="moduleName">Reports</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="report_view">View</p></div>\
                                            </div></div>\
                                            \
                                            <div class="permission">\
                                            <div class="row">\
                                            <div class="col-md-3"><p class="moduleName">Purchase Order</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="po_view">View</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="po_create">Create</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="po_edit">Edit</p></div>\
                                            </div></div>\
                                            \
                                            <div class="permission">\
                                            <div class="row">\
                                            <div class="col-md-3"><p class="moduleName">Product</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="product_view">View</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="product_create">Create</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="product_edit">Edit</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="product_delete">Delete</p></div>\
                                            </div></div>\
                                            \
                                            <div class="permission">\
                                            <div class="row">\
                                            <div class="col-md-3"><p class="moduleName">Supplier</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="supplier_view">View</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="supplier_create">Create</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="supplier_edit">Edit</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="supplier_delete">Delete</p></div>\
                                            </div></div>\
                                            \
                                            <div class="permission">\
                                            <div class="row">\
                                            <div class="col-md-3"><p class="moduleName">User</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="user_view">View</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="user_create">Create</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="user_edit">Edit</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="user_delete">Delete</p></div>\
                                            </div></div>\
                                            \
                                            <div class="permission">\
                                            <div class="row">\
                                            <div class="col-md-3"><p class="moduleName">Point of Sale</p></div>\
                                            <div class="col-md-2"><p class="moduleFunc perm" data-value="pos">Grant</p></div>\
                                            </div></div>\
                                            </div></div>\
                                            </form>',


                                callback:function(isUpdate){
                                    
                                    if(isUpdate){
                                        var perms = [];
                                            document.querySelectorAll('.moduleFunc.active').forEach(function(p){
                                                perms.push(p.dataset.value);
                                            });


                                        $.ajax({
                                        method: 'POST',
                                        data: {
                                        userId: userId,
                                        f_name: document.getElementById('firstName').value,
                                        l_name: document.getElementById('lastName').value,
                                        email: document.getElementById('emailUpdate').value,
                                        permissions: perms.join(',')
                                        },

                                        url: 'database/update-user.php',
                                        dataType: 'json',
                                        success: function(data) {
                                            if (data.success) {
                                                BootstrapDialog.alert({
                                                    type: BootstrapDialog. TYPE_SUCCESS,
                                                    message:data.message,
                                                    callback: function(){
                                                        location.reload();
                                                    }
                                                });
                                               

                                            } else {
                                                BootstrapDialog.alert({
                                                    type: BootstrapDialog. TYPE_DANGER,
                                                    message:data.message
                                                });
                                            }
                                        }
                                    })
                                        
                                    }
                                }
                               });
                               setTimeout(function(){
                                document.querySelectorAll('.moduleFunc').forEach(function(p){
                                    if(userPermissions.includes(p.dataset.value)){
                                        p.classList.add('active');
                                    }
                                });
                            }, 200);

          
                            }
                           
                        });


                        });
                    }
            }
            var script = new script;
            script.initialize();
</script> -->
<script>
function script() {
    this.initialize = function() {
        this.registerEvents();
    },

    this.registerEvents = function() {
        document.addEventListener('click', function(e) {
            targetElement = e.target;
            classList = targetElement.classList;

            /* DELETE USER */
            if (classList.contains('deleteUser')) {
                e.preventDefault();
                userId = targetElement.dataset.userid;
                fname = targetElement.dataset.fname;
                lname = targetElement.dataset.lname;
                fullName = fname + ' ' + lname;

                BootstrapDialog.confirm({
                    title:'Delete User',
                    type: BootstrapDialog.TYPE_DANGER,
                    message: 'Are you sure to delete <strong>' + fullName + '</strong> ?',
                    callback: function(isDelete){
                        if(isDelete){
                            $.ajax({
                                method: 'POST',
                                data: {
                                    id : userId,
                                    table: 'users',
                                },
                                url: 'database/delete.php',
                                dataType: 'json',
                                success: function(data) {
                                    message = data.success ?
                                    fullName + ' successfully deleted' : 'Error processing your request!';
                                    BootstrapDialog.alert({
                                        type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                        message: message,
                                        callback: function(){
                                            if(data.success) location.reload();
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }

            /* UPDATE USER */
            if (classList.contains('updateUser')) {
                e.preventDefault();

                userPermissions = targetElement.dataset.permissions;
                userPermissions = userPermissions ? userPermissions.split(',') : [];

                firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
                lastName  = targetElement.closest('tr').querySelector('td.lastName').innerHTML;
                email     = targetElement.closest('tr').querySelector('td.email').innerHTML;
                userId    = targetElement.dataset.userid;

                BootstrapDialog.confirm({
                    title: 'Update ' + firstName + ' ' + lastName,
                    message: '<form>\
                        <div class="form-group">\
                        <label>First Name:</label>\
                        <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
                        </div>\
                        <div class="form-group">\
                        <label>Last Name:</label>\
                        <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
                        </div>\
                        <div class="form-group">\
                        <label>Email:</label>\
                        <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
                        </div>\
                        <div id="permissions">\
                        <h4>Permissions</h4><hr>\
                        <div class="permissionsContainer">\
                        <div class="permission"><div class="row">\
                        <div class="col-md-3"><p class="moduleName">Dashboard</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="dashboard_view">View</p></div>\
                        </div></div>\
                        <div class="permission"><div class="row">\
                        <div class="col-md-3"><p class="moduleName">Reports</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="report_view">View</p></div>\
                        </div></div>\
                        <div class="permission"><div class="row">\
                        <div class="col-md-3"><p class="moduleName">Purchase Order</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="po_view">View</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="po_create">Create</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="po_edit">Edit</p></div>\
                        </div></div>\
                        <div class="permission"><div class="row">\
                        <div class="col-md-3"><p class="moduleName">Product</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="product_view">View</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="product_create">Create</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="product_edit">Edit</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="product_delete">Delete</p></div>\
                        </div></div>\
                        <div class="permission"><div class="row">\
                        <div class="col-md-3"><p class="moduleName">Supplier</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="supplier_view">View</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="supplier_create">Create</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="supplier_edit">Edit</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="supplier_delete">Delete</p></div>\
                        </div></div>\
                        <div class="permission"><div class="row">\
                        <div class="col-md-3"><p class="moduleName">User</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="user_view">View</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="user_create">Create</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="user_edit">Edit</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="user_delete">Delete</p></div>\
                        </div></div>\
                        <div class="permission"><div class="row">\
                        <div class="col-md-3"><p class="moduleName">Point of Sale</p></div>\
                        <div class="col-md-2"><p class="moduleFunc" data-value="pos">Grant</p></div>\
                        </div></div>\
                        </div></div>\
                        </form>',
                    callback:function(isUpdate){
                        if(isUpdate){
                            var perms = [];
                            document.querySelectorAll('.moduleFunc.active').forEach(function(p){
                                perms.push(p.dataset.value);
                            });

                            $.ajax({
                                method: 'POST',
                                data: {
                                    userId: userId,
                                    f_name: document.getElementById('firstName').value,
                                    l_name: document.getElementById('lastName').value,
                                    email: document.getElementById('emailUpdate').value,
                                    permissions: perms.join(',')
                                },
                                url: 'database/update-user.php',
                                dataType: 'json',
                                success: function(data) {
                                    if (data.success) {
                                        BootstrapDialog.alert({
                                            type: BootstrapDialog.TYPE_SUCCESS,
                                            message: data.message,
                                            callback: function(){ location.reload(); }
                                        });
                                    } else {
                                        BootstrapDialog.alert({
                                            type: BootstrapDialog.TYPE_DANGER,
                                            message: data.message
                                        });
                                    }
                                }
                            });
                        }
                    }
                });

                setTimeout(function(){
                    document.querySelectorAll('.moduleFunc').forEach(function(p){
                        if(userPermissions.includes(p.dataset.value)){
                            p.classList.add('active');
                        }
                    });
                }, 200);
            }

        });
    }
}

var script = new script;
script.initialize();

/* PERMISSION TOGGLE */
document.addEventListener('click', function(e){
    let el = e.target.closest('.moduleFunc');
    if(el){
        el.classList.toggle('active');
    }
});

</script>


</body>

</html>