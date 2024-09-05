<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Cake Template">
    <meta name="keywords" content="Cake, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$title;?></title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="icon" href="<?=base_url();?>uploads/photo/logo/shoplogo.png" type="image/x-icon">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/flaticon.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/barfiller.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url();?>assets/css/style.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include Toastr CSS and JS files from CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <script src="<?= base_url('assets/js/toastr.min.js')?>"></script>
    <script src="<?=base_url();?>assets/js/script.js"></script>
   <style>
    .dropdown:hover>.dropdown-menu {
  display: block;
}

.dropdown>.dropdown-toggle:active {
     pointer-events: none;
}

   </style>
    <script>
        
        // Example initialization
toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    onclick: null,
    showDuration: '300',
    hideDuration: '1000',
    timeOut: '5000',
    extendedTimeOut: '1000',
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
};

    </script>
    <style>
         /* Customize Parsley error messages */
    .parsley-errors-list {
        color: red; /* Set the color to red */
        font-size: 0.9em; /* Optional: Adjust font size */
    }
        .modal.left .modal-dialog {
	position:fixed;
	right: 0;
    top: 102px;
    margin: auto;
    width: 320px;
    height: 85%;
    z-index: 9999;
	-webkit-transform: translate3d(0%, 0, 0);
	-ms-transform: translate3d(0%, 0, 0);
	-o-transform: translate3d(0%, 0, 0);
	transform: translate3d(0%, 0, 0);
}

.modal.left .modal-content {
	height: 100%;
	overflow-y: auto;
}

.modal.right .modal-body {
	padding: 15px 15px 80px;
}

.modal.right.fade .modal-dialog {
	left: -320px;
	-webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
	-moz-transition: opacity 0.3s linear, left 0.3s ease-out;
	-o-transition: opacity 0.3s linear, left 0.3s ease-out;
	transition: opacity 0.3s linear, left 0.3s ease-out;
}

.modal.right.fade.show .modal-dialog {
	right: 0;
}

/* ----- MODAL STYLE ----- */
.modal-content {
	border-radius: 0;
	border: none;
}

.modal-header {
	border-bottom-color: #eeeeee;
	background-color: #fafafa;
}

    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__cart">
            <div class="offcanvas__cart__links">
                <a href="#" class="search-switch"><img src="<?=base_url();?>assets/img/icon/search.png" alt=""></a>
                <a href="<?=base_url('wishlist');?>"><img src="<?=base_url();?>assets/img/icon/heart.png" alt=""></a>
            </div>
            <div class="offcanvas__cart__item">
        
                <a href="<?=base_url('cart');?>"><img src="<?=base_url();?>assets/img/icon/cart.png" alt=""> <span class="cart_count_inner_mobile" id="cart_count_inner_mobile"><?php $total_count = 0; foreach(cart_data() as $row){ $total_count += $row->qty; } ?><?= cart_data() ? $total_count.'' : '0' ?></span></a>
                <div class="cart__price cart__price__new">Cart: <span>Rs <?=cart_price();?></span></div>
            </div>
        </div>
        <div class="offcanvas__logo">
            <a href="<?=base_url();?>"><img src="<?=base_url();?>uploads/photo/logo/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__option">
        <ul>
        <?php
        if( is_logged_in() ):
        $user_name = $this->session->user_name ? $this->session->user_name : get_cookie('user_name');
        $user_photo = $this->session->user_photo ? $this->session->user_photo : get_cookie('user_photo');
        ?>
        <li>
        <?php if(!empty($user_photo)){?>
            <img src="<?=base_url('uploads/photo/').$user_photo; ?>" style="border-radius: 50%; height: 40px; width: 40px;" alt="">
            <?php }else{?>
                <img src="<?=base_url('assets/img/user.png'); ?>" style="border-radius: 50%; height: 40px; width: 40px;" alt="">
            <?php }?>
            <b><?= $user_name; ?></b>
        </li>
        <?php endif; ?>
        <?php
          if( is_logged_in() ):
          ?>
          <li>Profile</li>
          <li><a href="<?=base_url();?>logout">Logout</a></li>
          <?php else: ?>
          <li><a href="#">Sign in</a> <span class="arrow_carrot-down"></span>
              <ul>
                  <li><a href="<?=base_url();?>mobile-login" class="text-white">Login</a></li>
                  <li><a href="<?=base_url();?>new-account" class="text-white">New Account</a></li>
              </ul>
          </li>
          <?php
          endif;
          ?>
         </ul>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header ">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header__top__inner">
  <div class="header__top__left">
  <div class="container demo">
  <div class="modal left fade" id="exampleModal" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <div class="nav flex-sm-column flex-row">
                  <div class="container h-100">
                  <h4 class="text-center" style="font-weight: 700;
    text-transform: UPPERCASE;
    color: black;">Customer Login</h4>
                  <div class="d-flex justify-content-center h-100">
                      <div class="user_card">
                          <div class="d-flex justify-content-center">
                           
   <div class="brand_logo_container">
       <img src="<?=base_url();?>uploads/photo/logo/logo.png" class="brand_logo" height="76px" alt="Logo">
   </div>
   
                          </div>
                          <div class="d-flex justify-content-center form_container">
   <form id="login-form">
       <div class="input-group mb-3">
       <span id="error-login-form" class="text-white"></span>
           <div class="input-group-append">
               <span class="input-group-text"><i class="fa fa-user"></i></span>
           </div>
           <input type="number" id="mobile" name="mobile" placeholder="Enter Mobile Number" onkeyup='validate(this)' required class="form-control input_user">
           <span class="error text-white"></span>
       </div>
       <div class="input-group mb-2">
           <div class="input-group-append">
               <span class="input-group-text"><i class="fa fa-key"></i></span>
           </div>
           <input type="password"  class="form-control input_pass" name="password" id="password" placeholder="Enter Password">
       </div>
       <div class="form-group">
           <div class="custom-control custom-checkbox">
               <input type="checkbox" id="signed_in" name="signed_in" value="1" required class="custom-control-input" id="customControlInline">
               <label class="custom-control-label" for="customControlInline">Remember me</label>
           </div>
       </div>
           <div class="d-flex justify-content-center mt-3 login_container">
   <a href="javascript:void(0)" onclick="user_login(this)"  class="btn login_btn">Login</a>
                          </div>
   </form>
                          </div>
                      </div>
                  </div>
              </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>
                        </div>
                        <!-- container -->
      <ul>
      <?php
        if( is_logged_in() ):
        $user_name = $this->session->user_name ? $this->session->user_name : get_cookie('user_name');
        $user_photo = $this->session->user_photo ? $this->session->user_photo : get_cookie('user_photo');
        ?>
        <li>
            <?php if(!empty($user_photo)){?>
            <img src="<?=base_url('uploads/photo/').$user_photo; ?>" style="border-radius: 50%; height: 40px; width: 40px;" alt="">
            <?php }else{?>
                <img src="<?=base_url('assets/img/user.png'); ?>" style="border-radius: 50%; height: 40px; width: 40px;" alt="">
            <?php }?>
            <b> <?= $user_name; ?></b>
        </li>
        <?php endif; ?>
         
          <?php
          if( is_logged_in() ):
          ?>
          <li>Profile</li>
          <li><a href="<?=base_url();?>logout">Logout</a></li>
          <?php else: ?>
          <li><a href="#">Sign in</a> <span class="arrow_carrot-down"></span>
              <ul>
                  <li><a href="#"data-toggle="modal" data-target="#exampleModal" class="text-white">Login</a></li>
                  <li><a href="<?=base_url();?>new-account" class="text-white">New Account</a></li>
              </ul>
          </li>
          <?php
          endif;
          ?>
      </ul>
  </div>
  <div class="header__logo">
      <a href="<?=base_url();?>"><img src="<?=base_url();?>uploads/photo/logo/logo.png" alt="" height="76pxpx" style="margin-top: -18px;"></a>
  </div>
  <div class="header__top__right">
      <div class="header__top__right__links">
          <a href="#" class="search-switch"><img src="<?=base_url();?>assets/img/icon/search.png" alt=""></a>
          <a href="<?=base_url('wishlist');?>"><img src="<?=base_url();?>assets/img/icon/heart.png" alt=""></a>
      </div>
      <div class="header__top__right__cart">
          <a href="<?=base_url('cart');?>"><img src="<?=base_url();?>assets/img/icon/cart.png" alt=""> 
          <span class="cart_count_inner_mobile" id="cart_count_inner_mobile"><?php $total_count = 0; foreach(cart_data() as $row){ $total_count += $row->qty; } ?><?= cart_data() ? $total_count.' ' : '0 ' ?>
        </span>
        </a>
        <div class="cart__price">
        <div class="container">
        <div class="dropdown">
        <span class=" dropdown-toggle" tid="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="cart__price__new" style="cursor:pointer">
        Cart: <span>Rs <?=cart_price();?></span>
        </span>
        </span>
        <div class="dropdown-menu pt-2" aria-labelledby="dropdownMenuButton">
        <div class="cart-div">
                
                </div>  
                <script>
                    $(document).ready(function() {
        // Load cart content initially
        $(".cart-div").load("<?=base_url()?>home/cart_view");

        // Close dropdown when clicking outside
        $(document).on("click", function(event) {
            var dropdownMenu = $(".dropdown-menu");
            var target = $(event.target);

            // Check if the clicked element is not part of the dropdown
            if (!target.closest(".dropdown").length) {
                dropdownMenu.hide();
            }
        });

        // Prevent closing dropdown when clicking inside
        $(".dropdown-menu").on("click", function(event) {
            event.stopPropagation();
        });
    });                      
                </script>
        </div>
        </div>
        </div>
        </div>
      </div>
  </div>
                        </div>
                    </div>
                </div>
                <div class="canvas__open"><i class="fa fa-bars"></i></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="header__menu mobile-menu">
                    <ul>
                        <li class="active"><a href="<?=base_url();?>">Home</a></li>
                        <?php
  $category = $this->category_model->get_category();
  $sub_category = $this->category_model->get_subcategory();
  foreach($category as $row): 
                        ?>
                        <li>
  <a href="<?= base_url('category/') ?><?= !empty($row->url) ? $row->url : 'null' ?>"><?= ucfirst($row->name) ?></a>
  <?php
  // Check if subcategories exist for this category
  $has_subcategories = false;
  foreach($sub_category as $rowsub) {
      if($rowsub->is_parent == $row->id) {
          $has_subcategories = true;
          break;
      }
  }
  
  // Render dropdown if subcategories exist
  if ($has_subcategories):
  ?>
  <ul class="dropdown">
      <?php
      foreach($sub_category as $rowsub):
          if($rowsub->is_parent == $row->id):
      ?> 
      <li><a href="<?= base_url('category/') ?><?= !empty($rowsub->url) ? $rowsub->url : 'null' ?>"><?= ucfirst($rowsub->name) ?></a></li>
      <?php
          endif;
      endforeach;
      ?>
  </ul>
  <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                        <li><a href="<?=base_url();?>contact-us">Contact</a></li>
                    </ul>

                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header Section End -->
    