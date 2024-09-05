<div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Payment Confirmation</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="<?=base_url();?>">Home</a>
                        <span>Payment Cancelled!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
    <div class="wishlist-area pt-5 pb-75">
            <div class="container">
              <div class="row">
            <div class="col-md-12 text-center">
                <img class="img-fluid img-thumbnail mb-5" style="width:200px" src="<?= base_url('assets/img/paymentfailed.png');?>" alt="404" id="error-img">
                <h4 class="mt-2 mb-2 text-danger">Payment Cancelled!</h4>
                <p class="mb-5">Payment process has been cancelled. Kindly go to my orders and initiate payment again.</p>
                <a class="btn btn-primary btn-lg" href="<?= base_url('profile'); ?>">View Order :)</a>
            </div>
        </div>
            </div>
        </div>
    </section>