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
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Login </h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="<?=base_url();?>">Home</a>
                        <span>Login</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart mt-3 mb-5">
        <div class="container">
            <div class="row">
            <div class="nav flex-sm-column flex-row">
                  <div class="container h-100">
                  <h4 class="text-center" style="font-weight: 700;
    text-transform: UPPERCASE;
    color: black;">Customer Login</h4>
                  <div class="d-flex justify-content-center h-100">
                      <div class="user_card_mobile">
                          <div class="d-flex justify-content-center">
                           
   <div class="brand_logo_container">
       <img src="<?=base_url();?>uploads/photo/logo/logo.png" class="brand_logo" height="76px" alt="Logo">
   </div>
   
                          </div>
                          <div class="d-flex justify-content-center form_container">
   <form id="customer_login">
       <div class="input-group mb-3">
       <span id="error-login-formcustomer_login" class="text-white"></span>
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
   <a href="javascript:void(0)" onclick="customer_login(this)"  class="btn login_btn">Login</a>
                          </div>
   </form>
                          </div>
                      </div>
                  </div>
              </div>
                  </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->
  