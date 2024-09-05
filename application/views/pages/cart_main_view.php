
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Shopping cart</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="./index.html">Home</a>
                        <span>Shopping cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shopping Cart Section Begin -->
    <?php
	$cart_data = cart_data();
	if( $cart_data ):
?>
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
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

                            $totalsaveoffer =  $subtotaloffer=  $total_savings =  $totalovervalue =  $afernotsaving = $subtotalofferall = $subtotal = $total_cutting_price = 0;
                            foreach( $cart_data as $cart ):
                                $product_id = $cart->product_id;
                                $cart_items = $this->product_model->product_details($product_id);
                                 $cutting_price = $cart->qty * $cart_items->selling_rate;
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
                                //end of calculate selling rate
                                 $total_cutting_price = $total_cutting_price + $cutting_price;

                                $offer_type = ($cart_items->discount_type =='1') ? $cart_items->offer_upto.'%' : '₹'.$cart_items->offer_upto;
                                if( is_logged_in() ):
                                    $input = '<a aria-label="-" class="action-btn hover-up me-1 add-cart plusminus"  href="javascript:void(0)" data-target=".qty-val'.$product_id.'" onclick="decrease_quantity('.$cart->id.','.$product_id.',this)"><i style="font-size:8px" class="fa fa-minus" ></i></a> ';
                                    $input .= '<input class="count-number-input qty-val'.$product_id.'" type="number" value="'.$cart->qty.'" onchange="quantity_by_input('.$cart->id.','.$product_id.', this)">';
                                    $input .= '<a aria-label="+" class="action-btn hover-up ms-1 add-cart plusminus"  href="javascript:void(0)" data-target=".qty-val'.$product_id.'" onclick="increase_quantity('.$cart->id.','.$product_id.', this)"><i style="font-size:8px" class="fa fa-plus"></i></a>';
            	               else:
                                    $input = '<a aria-label="-" class="action-btn hover-up me-1 add-cart plusminus" href="javascript:void(0)" data-target=".qty-val'.$product_id.'" onclick="cookie_decrease_quantity('.$product_id.',this)"><i style="font-size:8px" class="fa fa-minus" ></i></a> ';
                                    $input .= '<input class="count-number-input qty-val'.$product_id.'" type="number" value="'.$cart->qty.'" onchange="cookie_quantity_by_input('.$product_id.',this)">';
                                    $input .= '<a aria-label="+" class="action-btn hover-up ms-1 add-cart plusminus" href="javascript:void(0)" data-target=".qty-val'.$product_id.'" onclick="cookie_increase_quantity('.$product_id.',this)"><i style="font-size:8px" class="fa fa-plus" ></i></a> ';
            	               endif;
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
                                    }
                                
                                 }else{
                                    $afernotsaving = $afernotsaving + $cart_items->selling_rate*$cart->qty;     
                                    $subtotal = $subtotal + ($selling_rate) ;
                                     $total_savings = $total_cutting_price - $subtotal;
                                
                                 }
                                     $totalovervalue = $subtotaloffer+$afernotsaving;
               
                                ?>
                              <?php $offers = $this->product_model->get_data('shops_coupons_offers','product_id',$cart_items->id);
                                    foreach($offers as $offer)
                                    {
                                    if($offer->discount_type==1)
                                    {
                                        $offervalue=   $offer->offer_associated.' % OFF ';
                                        $offertype=$offer->discount_type;
                                         $finalperlist = $cart_items->selling_rate*$offer->offer_associated/100;
                                          $finalamountlist = $cart_items->selling_rate-$finalperlist;
                                      
                                    }else
                                    {
                                        $offervalue ='Only '.$shop_detail->currency.'  '.$cart_items->selling_rate-$offer->offer_associated;
                                        $offertype=$offer->discount_type;
                                        $finalamountlist = ($cart_items->selling_rate-$offer->offer_associated);
                                       
                                    }    
                                    
                                    }?> 
                                <tr>
                                    <td class="product__cart__item">
                                        <div class="product__cart__item__pic">
                                            <img width="100px" height="100px" src="<?=IMGS_URL.$cart_items->thumbnail; ?>" alt="">
                                        </div>
                                        <div class="product__cart__item__text">
                                            <h6><?=$cart_items->pro_name;?></h6>
                                         <?php if(!empty($offers))
                                         {?>
                                         <del class="text-danger">Rs <?php echo bcdiv(($cart_items->selling_rate), 1, 2); ?> </del>
                                            <span class="text-success"><b>  Rs <?php echo bcdiv(($finalamountlist), 1, 2); ?></b></span>
                                            <?php }
                                          else{?>
                                         <h5 class="text-success">Rs <?=_round($cart_items->selling_rate,2); ?></h5>
                                      <?php }?>
                                        </div>
                                    </td>
                                    <td class="quantity__item text-center" data-title="Stock" add-to-cart-div-<?=$product_id ?>>
                                            <div style="display:flex;">
                                            <?=$input; ?></div>
                                    </td>
                                    <?php if(!empty($offers))
                                         {?>
                                        <td class="text-success cart__price">Rs <?php echo bcdiv(($subtotaloffer), 1, 2); ?>
                                        <?php }else{?>
                                            <td class="text-success cart__price">Rs <?php echo bcdiv(($selling_rate), 1, 2); ?>
                                        </td>
                                            <?php }?>
                                    
                                    <td class="product-remove action cart__close" data-title="Remove"><span class="icon_close" href="javascript:void(0)" onclick="removeFromCart('<?=$product_id?>')"></span></td>
                                </tr>
                                <?php
                                    endforeach;
                                    ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="continue__btn update__btn">
                                <a href="<?=base_url();?>">Continue Shopping</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="continue__btn update__btn">
                                <a href="<?=base_url('cart');?>"><i class="fa fa-spinner"></i> Update cart</a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="continue__btn update__btn">
                            <?php
                             if (is_logged_in()):
                             ?>
                            <a href="javascript:void(0)" onclick="delete_cart_all(this)" class="view-cart"><i class="fa fa-spinner"></i> Clear cart</a>
                            <?php else: ?>
                            <a href="javascript:void(0)" onclick="delete_cookie_cart_all()" class="view-cart"><i class="fa fa-spinner"></i> Clear cart</a>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart__discount">
                        <h6>Discount codes</h6>
                        <form action="#">
                            <input type="text" placeholder="Coupon code">
                            <button type="submit">Apply</button>
                        </form>
                    </div>
                    <div class="cart__total">
                        <h6>Bill  total Details</h6>
                        <ul>
                            <li>Item Total
                            <?php $delivery_charges =delivery_charge($subtotal);
                             if(!empty($offers))
                            {?>
                            <span>Rs <?php echo bcdiv(($subtotal+$total_savings), 1, 2); ?></span>
                            <?php }else{?>
                            <span>Rs <?php echo bcdiv(($subtotal+$total_savings), 1, 2); ?></span>
                            <?php }?>
                            </li>
                            <li>Delivery Fee | <?=$kms;?> kms <span>Rs <?php echo bcdiv(($delivery_charges), 1, 2); ?></span><h6 style="color:dark;font-size:8px">This fee fairly goes to our delivery partners for delivering your food </h6> </li>
                            <li>Item Discount <span class="text-success">Rs <?php echo bcdiv(($total_savings ? $total_savings : '0'), 1, 2); ?></span></li>
                            <li>To Pay 
                            <?php if(!empty($offers))
                            {?>
                            <span class="cart_total_amount"><strong><span class="font-xl fw-900 text-brand">Rs  
                            <?php echo bcdiv(($subtotal+$delivery_charges), 1, 2); ?> </span></strong></span>
                            <?php }else{?>
                            <span class="cart_total_amount"><strong><span class="font-xl fw-900 text-brand">Rs 
                            <?php echo bcdiv(($subtotal+$delivery_charges), 1, 2); ?>
                            </span></strong></span>
                            <?php }?>
                        </ul>
                        <?php
                        if( is_logged_in() ):
                            $href = base_url('checkout');
                            $onclick = '';
                        else:
                            $href = 'javascript:void(0);';
                            $onclick = 'onclick="openAccount()"';
                        endif;
                    ?>
                        <a <?= $href ?>  <?= $onclick ?>  class="primary-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php else: ?>
    <section class="shopping-cart spad">
        <h4 class="text-center text-danger">Cart is empty.</h4>
    </section>
    <?php endif; ?>
    <!-- Shopping Cart Section End -->
    <script>

     function removeFromCart(pid){
    
        <?php if( is_logged_in() ): ?>
                delete_cart(<?=$cart_items->cart_id ?>,pid,this);
        <?php else: ?>
                delete_cookie_cart(pid,this);
        <?php endif; ?>
     }

</script>