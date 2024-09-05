<div class="add_to_cart">
	<h3 class="pl-5" style="font-size: 27px;color: black;font-weight: 700;">My Cart</h3>
	<hr style="height: 2px;color: black">
<?php
		$cart_data = cart_data();
		if( $cart_data ):
	?>
 <ul class="cart_product">
		<?php
		 $delivery_charges = 0.00; 
         $kms = 0; 
         if($this->session->userdata('logged_in')){
            $user_id = $this->session->userdata('user_id');
        }
        else{
            $user_id = get_cookie("user_id");
        }
        $address = $this->user_model->get_data1('customers_address','customer_id',$user_id);
        foreach($address as $row){
            if ($row->is_default==1) {
                $query = $this->db->get_where('pincodes_criteria',['pincode'=>$row->pincode])->row();
                $delivery_charges=$query->price;
                $kms=$query->kilometer;
            }
        } 
			$totalsaveoffer =  $subtotaloffer=  	$total_savings =  $totalovervalue = 	$afernotsaving=$subtotalofferall =	$subtotal = $total_cutting_price = 0;
			foreach( $cart_data as $cart ):
				$product_id = $cart->product_id;
	            $cart_items = $this->product_model->product_details($product_id);
	            $cutting_price = $cart->qty * @$cart_items->selling_rate;
	            if(@$cart_items->discount_type=='0') //0->rupee
	            {
	                $selling_rate = ($cart->qty*$cart_items->selling_rate) - $cart_items->offer_upto;
	            }
	            else if(@$cart_items->discount_type=='1') //1->%
	            {
	                $selling_per = ($cart->qty*$cart_items->selling_rate * $cart_items->offer_upto)/100;
	                $selling_rate = ($cart->qty*$cart_items->selling_rate) - $selling_per;
	            }else{
	                $selling_rate = $cart->qty*@$cart_items->selling_rate;
	            }
	            //end of calculate selling rate
	            $total_cutting_price = $total_cutting_price + $cutting_price;

	            $offer_type = (@$cart_items->discount_type =='1') ? @$cart_items->offer_upto.'%' : '$'.@$cart_items->offer_upto;

	          
                              // if offer iplicable
							  if(!empty($cart_items->offer_upto))
							  {
								 if($cart_items->discount_type=='1')
								 {
									 $subtotaloffer = $selling_rate;
									 $subtotal = $subtotal + bcdiv(($selling_rate),1,2);
									   $totalsaveoffer = $total_cutting_price-$subtotal ;
									   $total_savings = $total_cutting_price - $subtotal;
								 }else{
									   $subtotaloffer= ($cart_items->selling_rate-$cart_items->offer_upto)*$cart->qty ;
									  $subtotal = $subtotal + $subtotaloffer;
									   $totalsaveoffer = $total_cutting_price-$subtotal;
									   $total_savings = $total_cutting_price - $subtotal;
								 }
							 
							  }else{
								   $afernotsaving = $afernotsaving + $cart_items->selling_rate*$cart->qty;     
								   $subtotal = $subtotal + ($selling_rate) ;
								   $total_savings = $total_cutting_price - $subtotal;
							  }
								 $totalsave= $totalovervalue = $subtotaloffer+$afernotsaving;

		?>
		      <?php $offers = $this->product_model->get_data('shops_coupons_offers','product_id',$product_id);
                                    // echo $this->db->last_query();
                                    foreach($offers as $offer)
                                    {
                                    if($offer->discount_type==1)
                                    {
                                        $offervalue=   $offer->offer_associated.' % ';
                                        $offertype=$offer->discount_type;
                                         $finalperlist = $cart_items->selling_rate*$offer->offer_associated/100;
                                          $finalamountlist = $cart_items->selling_rate-$finalperlist;
                                      
                                    }else
                                    {
                                        $offervalue =$shop_detail->currency.'  '.$offer->offer_associated;
                                        $offertype=$offer->discount_type;
                                        $finalamountlist = ($cart_items->selling_rate-$offer->offer_associated);
                                        // $finalamountlist = $cart_items->selling_rate-$finalperlist;
                                    }    
                                    
                                    }?>
			<li>
                <div class="media">
				<?php $rs = $this->product_model->get_cart_url($product_id);
				$url = $rs->url  ? $rs->url : 'null';?>
				
					<table width="100%">
						<tr>
							<td style="width: 33%;padding: 19px;">
							<a href="<?= base_url('product/'.$url) ?>">  <img alt="<?=$cart_items->pro_name?>" src="<?php echo displayPhoto($cart_items->thumbnail); ?>" class="img-fluid "></a>
							</td>
							<td style="width:80%">
							<div class="media-body h3">
                    	<a class="h2" href="<?= base_url('product/'.$url) ?>" title="<?= $cart_items->pro_name; ?>"><h6 class="text-uppercase pt-3"></h6></a>
                        <a class="h2" href="<?= base_url('product/'.$url) ?>" title="<?= $cart_items->pro_name; ?>">
                        	<h4 class="mobile-cart-name" style="color:black;font-size: 1.2rem;
    font-weight: 600;" ><?= strip_tags( $cart_items->pro_name) ?></h4>
                        </a>
                        <br>
						<?php if(!empty($offers))
                        {?>
                        <span class="text-success pl-2" style="font-size:1.4rem">
                            <del class="text-danger" style="font-size:1.2rem">Rs <?php echo bcdiv($cart_items->selling_rate, 1, 2);?></del>
                            Rs <?php echo bcdiv($finalamountlist, 1, 2);?>
                        </span>
                        <?php }else{?>
							<span class="pl-2 text-success" style="font-size:1.4rem">Rs <?php echo bcdiv($cart_items->selling_rate, 1, 2);?></span>
                          <?php }?>
                      
                    </div>
					<div class="container">
						<div class="row">
							<div class="col-9">
							<span class="count-number<?=$product_id?> float-left">
							<?php if($this->session->userdata('logged_in') || get_cookie("logged_in"))
							{ ?>
							<a  aria-label="-" class="action-btn hover-up me-1  add-cart plusminus"  href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="decrease_quantity(<?= $cart_items->cart_id ?>,<?= $product_id ?>,this)"><i style="font-size:8px" class="fa fa-minus" ></i></a> 
							<?php  } 
							else
							{ ?>
							<a  aria-label="-" class="action-btn hover-up me-1 add-cart plusminus" href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="cookie_decrease_quantity(<?= $product_id ?>,this)"><i style="font-size:8px" class="fa fa-minus" ></i></a> 
							<?php  } ?>

							<?php if($this->session->userdata('logged_in') || get_cookie("logged_in")){ ?>
							<input class="text-center count-number-input qty-val<?= $product_id ?>" type="number" value="<?= $cart->qty ?>" min="0" onchange="quantity_by_input(<?= $cart_items->cart_id?>,<?= $product_id ?>, this)">
						<?php }else{ ?>
							<input class="text-center count-number-input qty-val<?= $product_id ?>" type="number" value="<?= $cart->qty ?>" min="0" onchange="cookie_quantity_by_input(<?= $product_id ?>, this)">
							<?php  } ?>

							<?php if($this->session->userdata('logged_in') || get_cookie("logged_in"))
							{ ?>
							<a  aria-label="+" class="action-btn hover-up ms-1 add-cart plusminus"  href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="increase_quantity(<?= $cart_items->cart_id?>,<?=$product_id ?>, this)"><i style="font-size:8px" class="fa fa-plus"></i></a>
							<?php  } 
							else
							{ ?>
							<a  aria-label="+" class="action-btn hover-up ms-1 add-cart plusminus" href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="cookie_increase_quantity(<?= $product_id ?>, this)"><i style="font-size:8px" class="fa fa-plus" ></i></a>
							<?php  } ?>
						    </span>
				   		
								</div>
								<div class="col-3">
							<div class="close-circle">  
							<a href="javascript:void(0)" 
							onclick="<?php if( is_logged_in() ) :
								echo 'delete_cart('.$cart_items->cart_id.','.$product_id.',this)';
								else:
								echo 'delete_cookie_cart('.$product_id.',this)';
								endif; ?> "><i class="fa fa-trash text-danger" ></i></a>
						</div>
							</div>
						    </div>
					      </div>
			
						</td>
						</tr>
					</table>
                </div>
                 
        </li>
			
			
		
		<?php
				endforeach;
		?>
    </ul>
	<!-- end body -->
	<ul class="cart_total">
		<table class="table table-striped table-bordered mt-2">
			<tr>
				 <?php  $delivery_charge =  delivery_charge($subtotal);?>
				<th>Item Total</th>
				<th style="text-align:right">
				<?php if(!empty($offers))
            {?>Rs <?php echo bcdiv($subtotal, 1, 2);?>
          <?php }else{?>Rs <?php echo bcdiv($subtotal, 1, 2);?>
          <?php }?>
				</th>
			</tr>
			<tr>
				<th>Delivery Fee | <?=$kms;?> kms</th>
				<th style="text-align:right">Rs <?php echo bcdiv($delivery_charge, 1, 2);?>
				</th>
			</tr>
			<tr>
				<th>Item Discount </th>
				<th style="text-align:right" class="text-success">Rs <?php echo bcdiv(($total_savings ? $total_savings : '0'), 1, 2); ?>
				</th>
			</tr>
			<tr>
				<th style="font-size: large;">To Pay </th>
				<th style="font-size: large;text-align:right">	<?php if(!empty($offers))
           {?>Rs<?php echo bcdiv(($subtotal+$delivery_charge), 1, 2);?>
           <?php }else{?>Rs<?php echo bcdiv(($subtotal+$delivery_charge), 1, 2);?>
           <?php }?>
				</th>
			</tr>
		</table>
        <?php
			if( is_logged_in() ):
                $href = base_url('checkout');
                $data_target = '';
                $data_toggle = '';
                $onclick = '';
            else:
                $href = 'javascript:void(0);';
                $data_target = '';
                $data_toggle = '';
                $onclick = 'onclick="openAccount()"';
            endif;
		?>
        <li>
			<div class="container">
				<div class="row">
					<div class="col-6">
					<a href="<?=base_url('cart')?>" class="btn btn-solid btn-block btn-solid-sm view-cart">view cart</a>
					</div>
					<div class="col-6">
					<a href="<?= $href ?>" data-bs-target="<?=$data_target ?>" data-bs-toggle="<?=$data_toggle?>" class="btn btn-solid btn-solid-sm btn-block checkout" <?= $onclick ?>  style="color:#ffff !important" >checkout</a>
					</div>
					<div class="col-12 mt-3">
					<?php
					if (is_logged_in()):
					?>
					<div class="buttons">
							<a href="javascript:void(0)" onclick="delete_cart_all(this)" class="  btn btn-solid btn-block btn-solid-sm view-cart">Clear Cart</a>
							
						</div>
					<?php else: ?>
					<div class="buttons">
							<a href="javascript:void(0)" onclick="delete_cookie_cart_all()" class=" btn btn-solid btn-block btn-solid-sm view-cart">Clear Cart</a>
							
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
        </li>
    </ul>
      

	
	<?php else: ?>
	<div class="cart-sidebar-body">
		<h3 class="text-center text-danger">Cart is empty</h3>
	</div>
	<?php endif; ?>
	</div>