<?php
      $user = $_SESSION['user'];
?>

<div class="dashboard_sidebar" id="dashboard_sidebar">
            <h3 class="dashboard_logo" id="dashboard_logo">IMS</h3>
            <div class="dashboard_sidebar_user">
                <img src="images/user/profile.png" alt="user image" id="userImage">
                <span><?= $user['first_name']. ' '. $user['last_name'] ?></span>
            </div>
            <div class="dashboard_sidebar_menus">
                <ul class="dashboard_menu_lists">
                    <li class="liMainMenu">
                        <a href="./dashboard.php"><i class="fa-solid fa-gauge "></i><span class="menuText">Dashboard</span></a>
                    </li>
                    <li class="liMainMenu">
                        <a href="./report.php"><i class="fa-solid fa-file "></i><span class="menuText">Reports</span></a>
                    </li>
                    <li class="liMainMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu" > 
                            <i class="fa-solid fa-tag showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu" >Product</span>
                            <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu" ></i>
                        </a>
                        <ul class="subMenus" >
                            <li><a href="./product-view.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>View Product</a></li>
                            <li><a href="./product-add.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>Add Product</a></li>
                            
                        </ul>
                    </li>
                    <li class="liMainMenu">
                        
                        <li class="liMainMenu showHideSubMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu" > 
                            <i class="fa-solid fa-truck showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu" >Supplier</span>
                            <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu" ></i>
                        </a>
                        <ul class="subMenus" >
                            <li><a href="./supplier-view.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>View Supplier</a></li>
                            <li><a href="./supplier-add.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>Add Supplier</a></li>
                        </ul>
                        
                    </li>
                    <li class="liMainMenu">
                        
                        <li class="liMainMenu showHideSubMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu" > 
                            <i class="fa-solid fa-cart-shopping showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu" >Purchase Order</span>
                            <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu" ></i>
                        </a>
                        <ul class="subMenus" >
                            <li><a href="./product-order.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>Create Order</a></li>
                            <li><a href="./view-order.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>View Orders</a></li>
                        </ul>
                        
                    </li>
                    <li class="liMainMenu showHideSubMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu" > 
                            <i class="fa-solid fa-user-plus showHideSubMenu" ></i>
                            <span class="menuText showHideSubMenu" >User</span>
                            <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu" ></i>
                        </a>
                        <ul class="subMenus" >
                            <li><a href="./users-view.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>View Users</a></li>
                            <li><a href="./users-add.php" class="subMenuLink"><i class="fa-regular fa-circle"></i>Add User</a></li>

                        </ul>
                    </li>
                </ul>
            </div>

        </div>