  
  <!-- Breadcrumb Begin -->
  <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Checkout</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="./index.html">Home</a>
                        <span>Checkout</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
 <div class="loader-container">
    <div class="loader"></div>
</div>
<style type="text/css">
	.time-slot li{
		cursor: pointer;
	}
	
   @media only screen and (max-width: 480px){
   	#payment-option
	{
		margin-top: 10px;
	}
	}
    .loader-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
    z-index: 9999; /* Make sure the loader is on top of other elements */
    display: none;
}

.loader {
    position: absolute;
    border: 8px solid #f3f3f3; /* Light grey border */
    border-top: 8px solid #3498db; /* Blue border */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    top: 50%;
    left: 50%;
    animation: spin 1s linear infinite; /* Spinning animation */
  
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="card checkout_cart">

					<?php $this->load->view('pages/checkout_cart'); ?>

				</div>
			</div>
			<div class="col-md-8 bg-light border rounded  mb-3  shadow-sm" id="payment-option">
				<div class="checkout-step">
				  
				<!-- address div-->
			    <div class="row address-div">
									   <?php 
									    foreach($addresses as $address){
									    	if ($address->is_default==1) {
									    		echo '<input type="hidden" name="address_id_default" value="'.$address->id.'">';
									    		$query = $this->db->where(['pincode'=>$address->pincode])->get('pincodes_criteria')->result();
									    		if ($query == TRUE) {
									    			$d_charge = $query[0]->price;
									    		}else{
									    			$d_charge = 0.00;
									    		}
									    		echo '<input type="hidden" name="address_price_default" value="'.$d_charge.'">';
									    	}
									    ?>
                                            <div class="col-lg-6 mt-2" id="<?=$address->id?>">
                                                <div class="card mb-lg-0">
                                                    <div class="card-header">
                                                        <h5 class="mb-0"><?= $address->contact_person_name; ?></h5>
                                                    </div>
                                                    
                                                    <div class="card-body">
                                                         <address><?= $address->address_line_1.' '.$address->address_line_2.' '.$address->address_line_3.' '.$address->city.' '.$address->state.' '.$address->country.' , '.$address->pincode ; ?></address>
                                                        <address><span style="color: #999999 !important;">Landmark: </span><?= $address->landmark ?></address>
                                                        <address><span style="color: #999999 !important;">Phone: </span><?= $address->contact; ?></address>
                                                         <a data-bs-toggle="modal"  onclick="closeAddress()" data-bs-target="#add-address-modal" data-whatever="Edit Delievery Address" href="javascript:void(0)" data-url="<?=$edit_addr_url?><?=$address->id?>" class="btn-small text-danger mr-4 "><i class="fi-rs-edit"></i> Edit</a>
                                                        <hr>
								                         <button type="button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn mb-2 btn-solid2 <?= ($address->is_default==1) ? 'btn-success' : 'bg-dark' ?> delivery-btn" style="color: white;" value="<?= $address->id ?>">Deliver Here</button>
                                                    </div>
                                                </div>
                                            </div>
							        <?php
							            }
							        ?>
								       <div class="col-md-6 pb-4 mt-2">
                        <a data-bs-toggle="modal" data-bs-target="#add-address-modal" data-bs-whatever="Add Delievery Address" data-url="<?=$edit_addr_url?>" href="javascript:void(0);" >
                            <div class="bg-light border rounded  mb-3  shadow-sm text-center h-100 d-flex align-items-center">
                                <h6 class="text-center m-0 w-100"><i class="fa fa-plus mb-5"></i><br><br>Add New Address</h6>
                            </div>
                        </a>
                    </div>           
        
       
									</div>
				</div>
				
				<!-- checkout button-->
                <div class="card-body payment-div">
                    <div class="row">
                        <div class="col-sm-4 p-1">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link text-dark active" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="true"><i class="icofont-bank-alt"></i> Online Payment</a>
                                <a class="nav-link text-dark" id="v-pills-cash-tab" data-toggle="pill" href="#v-pills-cash" role="tab" aria-controls="v-pills-cash" aria-selected="false"><i class="icofont-money"></i> Pay on Delivery</a>
                            </div>
                        </div>
                        
                        <div class="col-sm-8 p-1">
                            <div class="tab-content h-100" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="form-row">
                                        <hr>
                                        <div class="form-group col-md-12 mb-0">                
                                        <div class="form-group col-md-12 mb-0">
                                          <button class="btn btn-solid btn-block btn-lg make-online-payment t-value">
                                          PAY NOW 
                                          <i class="icofont-long-arrow-right"></i>
                                          </button>  
                                          </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-cash" role="tabpanel" aria-labelledby="v-pills-cash-tab">
                                    <div class="form-row">
                                        <hr>
                                        <div class="form-group col-md-12 mb-0 mt-5">
                                        <div class="form-group col-md-12 mb-0">
                                        <button class="btn btn-solid btn-block btn-lg pay-btn-cod" onclick="make_cod_payment()">CONTINUE<i class="icofont-long-arrow-right"></i></button> 
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

			</div>
	</div>
</section>

<!--Add Address modal-->

<div class="modal fade" id="add-address-modal" tabindex="-1" role="dialog" aria-labelledby="add-address" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-address">Add Delivery Address</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?= $add_url ?>" class="address-form">
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer justify-content-between d-flex">
                    <button type="button" class="btn text-center btn-solid bg-dark" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn text-center btn-solid">SUBMIT</button>
                </div>
            </form>
        </div>
        
    </div>
</div>


<div class="modal fade" id="coupon-modal" tabindex="-1" role="dialog" aria-labelledby="coupon" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
<!--                <h5 class="modal-title" id="add-address">Apply Coupon</h5>-->
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
       
        </div>
    </div>
</div>


<div class="modal fade login-modal-main" id="stock_data">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="login-modal">
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="close close-top-right" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                                <div class="p-4 pb-0"> 
                                    <h5 class="heading-design-h5 text-dark">Few products from your cart are out of stock/low stock:</h5>
                                </div>
                                <div id="pdetail" class="p-4 pb-0"> 
                                    
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="address-pincode-modal" tabindex="-1" role="dialog" aria-labelledby="address-pincode" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check Delivery Area By Pincode</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<h5 class="text-center text-dark mb-3" id="available-msg"></h5>
                <form class="row" method="POST" id="check-pincode">
                    <div class="col-8">
                        <input type="text" name="pincode" class="form-control" placeholder="Enter Your Postcode" required>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-solid">Check</button>
                    </div>
                </form>
            </div>
       
        </div>
    </div>
</div>


<script>
	

    $(document).ready(function(){
        $('#add-address-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) 
            var recipient = button.data('whatever') 
            var data_url  = button.data('url') 
            var modal = $(this)
            $('#add-address-modal .modal-title').text(recipient)
            $('#add-address-modal .modal-body').load(data_url);
        });

        $(".address-form").validate({
            rules : {
                mobile :{
                    minlength: 10,
                    maxlength: 10
                },
                pincode: {
                required:true,
                remote:"<?=$remote?>null/pincode"
            },
            },
            messages : {
                mobile:{
                    minlength: 'Number should be 10 digit.',
                    maxlength: 'Number should be 10 digit.'
                },
                pincode: {  
                required : "Please enter pin code!",
                remote : "Delivery not available in this pincode!"
            },
            }, 
        });

        $(document).on('submit', '.address-form', function(e){
            e.preventDefault();
            if( $('.address-form').valid() )
            {
                let frm = $(this);
                let btn = frm.find('button[type=submit]');
                let url = frm.attr('action');
                let formdata = $(frm).serializeArray();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formdata,
                    beforeSend: function() {
                        btn.attr("disabled", true);
                        btn.text("Please wait...");
                    },
                    success: function(response) {
                        toastr.success('Address Added Successfully!');
                        btn.removeAttr("disabled").text("Submit");
                        $('#add-address-modal').modal('toggle');
                        $('.address-div').load("<?=base_url();?>checkout/checkout_items/delievery_address");
                    },
                    error: function (response) {
                        toastr.error('Something went wrong. Please try again!');
                        btn.removeAttr("disabled");
                        btn.text("Submit");
                    }
                });
            }
            return false;
        });
    });
 
</script>
<!--/Add Address modal-->
<?php $this->load->view('pages/checkout_script'); ?>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- Checkout Section End -->