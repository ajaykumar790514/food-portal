<div class="slider-<?=$header_id?>">
<!-- <hr> -->

<div class="product__item_all">
                <div class="row">
                <?php
		$cart_data = cart_data();
		$cart_items = $cart_data ? array_column($cart_data, 'product_id') : array();

		foreach($header_slider as $result):
			$flag = 0;
			$input = '';
            
	        //calculate selling rate
	        if($result['discount_type']=='0') //0->rupee
	        {
	            $selling_rate = $result['selling_rate'] - $result['offer_upto'];
	        }
	        else if($result['discount_type']=='1') //1->%
	        {
	            $selling_per = ($result['selling_rate'] * $result['offer_upto'])/100;
	            $selling_rate = $result['selling_rate'] - $selling_per;
	        }else{
	            $selling_rate = $result['selling_rate'];
	        }
	        $discount_price = $result['mrp'] - $selling_rate;
	        $discount_percentage = ($discount_price == 0) ? 0 : (($discount_price/$result['mrp'])*100);

	        $offer_type = ($result['discount_type'] =='1') ? $result['offer_upto'].'%' : '₹'.$result['offer_upto'];
	            ?>   
                   <?php
                $offerss = $this->product_model->get_data('shops_coupons_offers','product_id',$result['product_id']);
                foreach($offerss as $offer)
                {
                if($offer->discount_type==1)
                {
                $deatailoffervalue=   $offer->offer_associated.' % OFF';
                $deatailoffertype=$offer->discount_type;
                $deatailfinalper = $result['selling_rate']*$offer->offer_associated/100;
                $deatailfinalamount = $result['selling_rate']-$deatailfinalper;
                }else
                {
                $deatailoffervalue ='Only '.$shop_detail->currency.'  '.$result['selling_rate']-$offer->offer_associated;
                $deatailoffertype=$offer->discount_type;
                $deatailfinalamount = ($result['selling_rate']-$offer->offer_associated);
                }    
                }  
                ?> 
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="<?=IMGS_URL.$result['thumbnail'];?>">
                        <a href="<?=base_url('product/')?><?php if($result['url'] !=''){echo $result['url'];}else{echo 'null';} ;?>">
                        <?php if(!empty($offerss)){?>
                        <span class="notify-badge-product"><?=@$deatailoffervalue;?></span>
                        <?php }?>
                        <img src="<?=IMGS_URL.$result['thumbnail'];?>" alt="">
                        </a>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#"><?=$result['product_name'];?></a></h6>
                            <div class="product__item__price">
                            <?php if(!empty($offerss))
                            { ?>
                                <del class="text-danger">Rs <?php echo number_format((float)($result['selling_rate']), 2, '.', ''); ?></del>  
                                <span class="text-success"> Rs <?php echo number_format((float)($deatailfinalamount), 2, '.', ''); ?>
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