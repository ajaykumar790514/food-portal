<?php
            if($product->discount_type=='0') //0->rupee
            {
                 $selling_rate = $product->selling_rate - $product->offer_upto;
            }
            else if($product->discount_type=='1') //1->%
            {
                $selling_per = ($product->selling_rate * $product->offer_upto)/100;
                $selling_rate = $product->selling_rate - $selling_per;
            }else
            {
                $selling_rate = $product->selling_rate;
            }
            $discount_price = $product->mrp - $selling_rate;
            $offer_type = ($product->discount_type =='1') ? $product->offer_upto.'%' : '₹'.$product->offer_upto;
            
            
            $offerss = $this->product_model->get_data('shops_coupons_offers','product_id',$product->id);
            foreach($offerss as $offer)
             {
              if($offer->discount_type==1)
               {
               $deatailoffervalue=   $offer->offer_associated.' % OFF';
               $deatailoffertype=$offer->discount_type;
               $deatailfinalper = $product->selling_rate*$offer->offer_associated/100;
               $deatailfinalamount = $product->selling_rate-$deatailfinalper;
               }else
               {
               $deatailoffervalue ='Only '.$shop_detail->currency.'  '.$product->selling_rate-$offer->offer_associated;
               $deatailoffertype=$offer->discount_type;
               $deatailfinalamount = ($product->selling_rate-$offer->offer_associated);
            }    
         }
         ?>
    <!-- Shop Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product__details__img">
                        <div class="product__details__big__img">
                            <img class="big_img" src="<?=IMGS_URL.$product->thumbnail;?>" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product__details__text">
                    <?php if(!empty($offerss)){?>
                        <div class="product__label_green"><?=@$deatailoffervalue;?></div>
                        <?php }?>
                        <h4><?=$product->pro_name;?></h4>
                        <?php if(!empty($offerss))
                        { ?>
                         <del class="text-danger">Rs <?php echo number_format((float)($product->selling_rate), 2, '.', ''); ?></del>  
                         <b><span class="text-success"> Rs <?php echo number_format((float)($deatailfinalamount), 2, '.', ''); ?></b>
                        <?php }else{?>
                            <span class="text-success"> Rs <?php echo number_format((float)($selling_rate), 2, '.', '') ;?>
                             </span>
                        <?php };?>
                        <hr>
                        <p><?=$product->description;?></p>
                        <div class="product__details__option">
                        <button class="primary-btn" id="cart_btn<?=$inventory_id?>" onclick="add_to_cart_by_btn(<?=$inventory_id?>,this)" href="javascript:void(0)"><i class="fa fa-shopping-bag"></i> Add to cart</button>
                        <?php
                       $wishlist_btn = ' <a  href="javascript:void(0)" title="Add to Wishlist" class="wishlist heart__btn" onclick="add_to_wishlist('.$product->id.')" aria-label="Add To Wishlist" class="heart__btn"><span class="icon_heart_alt"></span></a>';
                         $wishlist_data = wishlist_data();
                         foreach ($wishlist_data as $row) {
                         $product_id = $row->product_id;            
                         if ($product_id == $product->id) {
                         $wishlist_btn = ' <a  href="javascript:void(0)" title="Add to Wishlist" class="wishlist heart__btn" onclick="add_to_wishlist('.$product->id.')" aria-label="Add To Wishlist" class="heart__btn"><span class="icon_heart_alt"></span></a>';
                         }
                         }
                         echo $wishlist_btn;
                         ?>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details Section End -->

    <?php if($similer_products): ?>
       <!-- Related Products Section Begin -->
       <section class="related-products spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Related Products</h2>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="related__products__slider owl-carousel">
                <?php
                    foreach($similer_products as $result):
                    $flag = 0;
                    $input = '';
                    $discount_price = $result['mrp'] - $result['selling_rate'];
                    $discount_percentage = ($discount_price == 0) ? 0 : (($discount_price/$result['mrp'])*100);

                    //calculate selling rate

                    if($result['discount_type']=='0') //0->rupee
                    {
                        $selling_rate = $result['selling_rate'] - $result['offer_upto'];
                    }
                    else if($result['discount_type']=='1') //1->%
                    {
                        $selling_per = ($result['selling_rate'] * $result['offer_upto'])/100;
                        $selling_rate = $result['selling_rate'] - $selling_per;
                    }
                    else
                    {
                        $selling_rate = $result['selling_rate'];
                    }

                    $offer_type = ($result['discount_type'] =='1') ? $result['offer_upto'].'%' : '₹'.$result['offer_upto'];
                    $cart_data = cart_data();
                    $cart_items = $cart_data ? array_column($cart_data, 'product_id') : array();
                    $flag = in_array($result['product_id'], $cart_items) ? 1 : 0;
                    $cart_style = 'btn-secondary';
                    $cart_onclick = 'add_to_cart('.$result['product_id'].',this,2)';
                    $cart_title = 'Add to cart';
                    if( $flag == 1 ):
                        $cart_qty = $cart_id = 0;
                        foreach( $cart_data as $cd ):
                            if( $cd->product_id==$result['product_id'] ):
                                $cart_qty = $cd->qty;
                                $cart_id = @$cd->id;
                                break;
                            endif;
                        endforeach;
                    if( is_logged_in() ):
                        $input = '<a aria-label="-" class="action-btn hover-up me-1 add-cart"  href="javascript:void(0)" data-target=".qty-val'.$inventory_id.'" onclick="decrease_quantity('.$cart_id.','.$inventory_id.',this,2)"><i style="font-size:8px" class="fi-rs-minus" ></i></a> ';
                        $input .= '<input class="count-number-input qty-val'.$inventory_id.'" type="text" value="'.$cart_qty.'" readonly />';
                        $input .= '<a aria-label="+" class="action-btn hover-up ms-1 add-cart"  href="javascript:void(0)" data-target=".qty-val'.$inventory_id.'" onclick="increase_quantity('.$cart_id.','.$inventory_id.', this)"><i style="font-size:8px" class="fi-rs-plus">';
                    else:
                        $input = '<a aria-label="-" class="action-btn hover-up me-1 add-cart" href="javascript:void(0)" data-target=".qty-val'.$inventory_id.'" onclick="cookie_decrease_quantity('.$inventory_id.',this,2)"><i style="font-size:8px" class="fi-rs-minus" ></i></a> ';
                        $input .= '<input class="count-number-input qty-val'.$inventory_id.'" type="text" value="'.$cart_qty.'" readonly />';
                        $input .= '<a aria-label="+" class="action-btn hover-up ms-1 add-cart" href="javascript:void(0)" data-target=".qty-val'.$inventory_id.'" onclick="cookie_increase_quantity('.$inventory_id.',this)"><i style="font-size:8px" class="fi-rs-plus" ></i></a> ';
                    endif;
                    endif;
                    ?>
                       <?php
                                    $offers = $this->product_model->get_data('shops_coupons_offers','product_id',$result['product_id']);
                                    foreach($offers as $offer)
                                    {
                                        if($offer->discount_type==1)
                                        {
                                            $offervalue=   $offer->offer_associated.' % OFF';
                                            $offertype=$offer->discount_type;
                                             $finalperlist = $result['selling_rate']*$offer->offer_associated/100;
                                             $finalamountlist = $result['selling_rate']-$finalperlist;
                                          
                                        }else
                                        {
                                            $offervalue ='Only '.$shop_detail->currency.'  '.$result['selling_rate']-$offer->offer_associated;
                                            $offertype=$offer->discount_type;
                                            $finalamountlist = ($result['selling_rate']-$offer->offer_associated);
                                            //$finalamountlist = $result['selling_rate']-$finalperlist;
                                        }     
                                    
                                    }?>
                    <div class="col-lg-3">
                        <div class="product__item">
                        <a href="<?=base_url('product/')?><?php if($result['url'] !=''){echo $result['url'];}else{echo 'null';} ;?>">
                        <?php if(!empty($offerss)){?>
                        <span class="notify-badge-product"><?=@$deatailoffervalue;?></span>
                        <?php }?>
                        
                            <div class="product__item__pic set-bg">
                            <img src="<?=IMGS_URL.$result['thumbnail'];?>" alt="">
                        </a>
                            </div>
                            <div class="product__item__text">
                            <h6><a href="<?=base_url('product/')?><?php if($result['url'] !=''){echo $result['url'];}else{echo 'null';} ;?>"><?=$result['pro_name'];?></a></h6>
                            <div class="product__item__price">
                            <?php if(!empty($offers))
                            { ?>
                                <del class="text-danger">Rs <?php echo number_format((float)($result['selling_rate']), 2, '.', ''); ?></del>  
                                <span class="text-success"> Rs <?php echo number_format((float)($finalamountlist), 2, '.', ''); ?>
                             </span>
                             <br>
                             <a id="cart_btn<?=$result["product_id"]?>" onclick="add_to_cart_by_btn(<?=$result['product_id']?>,this)" href="javascript:void(0)" aria-label="Add To Cart" class="btn btn-sm btn-warning">Add to cart</a>
                             <?php  }else{?>
                                <span class="text-success"> Rs <?php echo number_format((float)($selling_rate), 2, '.', '') ;?>
                             </span>
                             <br>
                             <a id="cart_btn<?=$result["product_id"]?>" onclick="add_to_cart_by_btn(<?=$result['product_id']?>,this)" href="javascript:void(0)" aria-label="Add To Cart" class="btn btn-sm btn-warning">Add to cart</a>
                                <?php }?>
                            </div>
                            <div class="cart_add">
                                <a  id="cart_btn<?=$result["product_id"]?>" onclick="add_to_cart_by_btn(<?=$result['product_id']?>,this)" href="javascript:void(0)" aria-label="Add To Cart" class="btn btn-sm btn-success">Add to cart</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <?php endforeach; ?> 
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?><!--End Related Product Dynamic by AJAY KUMAR -->
    <!-- Related Products Section End -->
    <script src="<?=base_url();?>assets/js/owl.carousel.min.js"></script>
    <script>
          $(".related__products__slider").owlCarousel({
        loop: true,
        margin: 0,
        items: 4,
        dots: false,
        nav: true,
        navText: ["<span class='arrow_carrot-left'><span/>", "<span class='arrow_carrot-right'><span/>"],
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 4    
            },
        }
    });
    </script>