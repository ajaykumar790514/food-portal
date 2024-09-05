<?php $cart_data=cart_data(); ?>
<div class="card-header" style="background-color: #F8F8F8;">
    <h4 class="text-black mb-0">Cart Summary <span class="text-primary">(<?= count($cart_data) ?> item)</span></h4>
</div>
<div class="card-body">

<ul>
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

        $totalsaveoffer =  $subtotaloffer=  $total_savings =  $totalovervalue =  $afernotsaving = $subtotalofferall =$subtotal = $total_cutting_price = $total_tax = $TotalQty=0;
		foreach( $cart_data as $cart ):
			$product_id = $cart->product_id;
            $cart_items = $this->product_model->product_details($product_id);
            $cutting_price = $cart->qty*$cart_items->selling_rate;
            if($cart_items->discount_type=='0') //0->rupee
            {
                $selling_rate = ($cart->qty*$cart_items->selling_rate) - $cart_items->offer_upto;
            }
            else if($cart_items->discount_type=='1') //1->%
            {
                $selling_per = ($cart->qty*$cart_items->selling_rate * $cart_items->offer_upto)/100;
                $selling_rate = ($cart->qty*$cart_items->selling_rate) - $selling_per;
            }else{
                 $selling_rate = $cart->qty*$cart_items->selling_rate;
            }

            $offer_type = ($cart_items->discount_type =='1') ? $cart_items->offer_upto.'%' : '₹'.$cart_items->offer_upto;
            //end of calculate selling rate
            $total_cutting_price = $total_cutting_price + $cutting_price;


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
                 $totalovervalue = $subtotaloffer+$afernotsaving;
            $tax = $cart_items->pro_tax; 
            $total_value = $selling_rate;
            $inclusive_tax = $total_value - ($total_value * (100/ (100 + $tax)));
            $total_tax += $inclusive_tax;
    $offers = $this->product_model->get_data('shops_coupons_offers','product_id',$product_id);
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
                                    
                                    }
                                    $rs = $this->product_model->get_cart_url($product_id);$url = $rs->url  ? $rs->url : 'null';?>
        <li style="border-bottom: 1px dashed;padding-bottom: 20px;">
            <div class="shopping-cart-img">
                <a href="<?= base_url('product/'.$url) ?>"><img alt="" src="<?php echo displayPhoto($cart_items->thumbnail); ?>" class="img-fluid"></a>
            </div>
            <div class="shopping-cart-title">
                <h4><a href="<?= base_url('product/'.$url) ?>" title="<?= $cart_items->pro_name; ?>"><?= strip_tags( $cart_items->pro_name) ?></a></h4>
                <?php if(!empty($offers)){?>
                 <del class="text-danger" style="font-size:0.9rem">Rs <?php echo bcdiv(($cart_items->selling_rate), 1, 2); ?></del>   
                <h4 class="text-success">Rs <?php echo bcdiv(($finalamountlist), 1, 2); ?> </h4>
                <?php }else{?>
                <h4 class="text-success">Rs <?php echo bcdiv(($cart_items->selling_rate), 1, 2); ?> </h4>
                <?php }?>
                    
                        <span class="count-number<?=$product_id?> float-left">
                            <?php if($this->session->userdata('logged_in') || get_cookie("logged_in"))
                            { ?>
                            <a  aria-label="-" class="action-btn-qty-detail action-btn hover-up me-1  add-cart plusminuss"  href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="decrease_quantity(<?= $cart_items->cart_id ?>,<?= $product_id ?>,this)"><i style="font-size:8px" class="fa fa-minus"></i></a> 
                            <?php  } 
                            else
                            { ?>
                            <a  aria-label="-" class="action-btn-qty-detail action-btn hover-up me-1 add-cart plusminuss" href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="cookie_decrease_quantity(<?= $product_id ?>,this)">
                            <i style="font-size:8px" class="fa fa-minus"></i></a> 
                            <?php  } ?>

                            <?php if($this->session->userdata('logged_in') || get_cookie("logged_in")){ ?>
                            <input class="text-center count-number-input qty-val<?= $product_id ?>" type="number" value="<?= $cart->qty ?>" min="0" onchange="quantity_by_input(<?= $cart_items->cart_id?>,<?= $product_id ?>, this)">
                        <?php }else{ ?>
                            <input class="text-center count-number-input qty-val<?= $product_id ?>" type="number" value="<?= $cart->qty ?>" min="0" onchange="cookie_quantity_by_input(<?= $product_id ?>, this)">
                            <?php  } ?>
                            

                            <?php if($this->session->userdata('logged_in') || get_cookie("logged_in"))
                            { ?>
                            <a  aria-label="+" class="action-btn hover-up ms-1 add-cart plusminuss"  href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="increase_quantity(<?= $cart_items->cart_id?>,<?=$product_id ?>, this)"><i style="font-size:8px" class="fa fa-plus"></i></a>
                            <?php  } 
                            else
                            { ?>
                            <a  aria-label="+" class="action-btn hover-up ms-1 add-cart plusminuss" href="javascript:void(0)" data-target=".qty-val<?= $product_id ?>" onclick="cookie_increase_quantity(<?= $product_id ?>, this)"><i style="font-size:8px" class="fa fa-plus" ></i></a>
                            <?php  } ?>
                        </span>
            </div>
           
            <div class="shopping-cart-delete">  
                <a href="javascript:void(0)" 
                onclick="<?php if( is_logged_in() ) :
                    echo 'delete_cart('.$cart_items->cart_id.','.$product_id.',this)';
                    else:
                    echo 'delete_cookie_cart('.$product_id.',this)';
                    endif; ?> "><i class="ti-trash text-danger" aria-hidden="true"></i></a>
            </div>
                 
        </li>			
		
		<?php
				endforeach;
		?>
    </ul>
</div>

<!-- End Cart Body -->
<div class="card-footer" style="background-color: #F8F8F8;">
     <div class="shopping-cart-footer">
        <div class="shopping-cart-total">
            <input type="hidden" name="sub_total" value="<?php echo bcdiv(($subtotal), 1, 2); ?>">
          <?php  $delivery_charge =  delivery_charge($subtotal);?>
          <h4>Item Total <span>Rs  <span class="sub-total">
            <?php echo bcdiv(($subtotal), 1, 2); ?></span></span></h4>
            <h4>Delivery Fee | <?=$kms;?> kms <span>Rs  <span class="delivery-charge">
            <?php echo bcdiv(($delivery_charge), 1, 2); ?></span></span></h4>
            
            <h4>Item Discount <span class="text-success">Rs <?php echo bcdiv((@$total_savings), 1, 2); ?> </span></h4> 
            
           
            <br/>
           <h4><b>To Pay</b> <span style="font-size: 1.2rem;" class="text-danger"><b>Rs  <span class="to-pay text-danger"><?php echo bcdiv(($subtotal+$delivery_charge), 1, 2); ?></b></span></span></h4>
             
            <div class="cart-store-details"> 
                <div class="coupon-head"></div> 
            </div>
        </div>   
    </div>
</div>
