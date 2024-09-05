
    <!-- Hero Section Begin -->
  <section class="hero">
    <div class="hero__slider owl-carousel">
        <?php $i=0;foreach($top_banner as $top):?>
        <div class="hero__item set-bg" data-setbg="<?=IMGS_URL.$top->img?>">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="hero__text">
                            <h2><?=$top->banner_title;?></h2>
                            <h2><?=$top->text_line1;?></h2>
                            <h2><?=$top->text_line2;?></h2>
                            <h2><?=$top->text_line3;?></h2>
                            <h2><?=$top->text_line4;?></h2>
                            <a target="_blank" href="<?=$top->link_type.'/'.$top->link_id;?>" class="primary-btn mt-2"><?=$top->banner_offer;?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $i++;endforeach;?>
    </div>
</section>

    <!-- Hero Section End -->
    <section class="about pt-5">
    <div class="categories">
        <div class="container">
    <div class="widgets__Container-sc-1aj45no-0 dFWLgY">
        <div type="66" class="widgets__WidgetContainer-sc-1aj45no-1 iNSmkc">
            <div class="SaleCouponList__Wrapper-sc-ezdcvs-0 RWWyb">
                <div class="SaleCouponList__Container-sc-ezdcvs-1 iSokNd">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-lg-4">
                        <div width="335.40000000000003" height="195" class="Imagestyles__ImageContainer-sc-1u3ccmn-0 fKsEdv">
                        <img src="<?=base_url('assets/img/food/pizza.jpg');?>" width="335.40000000000003" height="195" loading="lazy" style="border-radius: 16px; object-fit: fill; cursor: default;">
                       </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                        <div width="335.40000000000003" height="195" class="Imagestyles__ImageContainer-sc-1u3ccmn-0 fKsEdv">
                        <img src="<?=base_url('assets/img/food/burger.avif');?>" width="335.40000000000003" height="195" loading="lazy" style="border-radius: 16px; object-fit: fill; cursor: default;">
                        </div>  
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                        <div width="335.40000000000003" height="195" class="Imagestyles__ImageContainer-sc-1u3ccmn-0 fKsEdv">
                        <img src="<?=base_url('assets/img/food/noodel.jpg');?>" alt="masthead_web_baby_care" width="335.40000000000003" height="195" loading="lazy" style="border-radius: 16px; object-fit: fill; cursor: default;">
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

    <!-- Categories Section Begin -->
    <?php 
    $i=0;
    foreach($category_header_title as $row): 
     if($i==0): 
     if($row->seq==1 && $row->type==2):       
     $rs1 = $this->home_model->getHeaderCatMap($row->id);  
    ?>
    <section class="about pt-5">
    <div class="categories">
        <div class="container">
            <div class="row">
            <div class="tab-head">
                    <h2 class="title" id="header_title"><?=$row->title;?></h2>
                </div>
                <div class="categories__slider owl-carousel">
                <?php foreach($rs1  as $r1):?>
                    <div class="categories__item">
                      <a href="<?=base_url('category/'.$r1->url);?>">
                        <div class="categories__item__icon">
                        <?php  
                    $catoffer = $this->db->get_where('shops_coupons_offers',['category_id'=>$r1->id])->row();
                     if(!empty($catoffer)){
                    if($catoffer->discount_type==1)
                    {
                        $price = $catoffer->offer_upto."% OFF";
                    }else{
                        $price = $catoffer->offer_upto." FLAT OFF";
                    } ?>    
                        <span class="notify-badge-cateogry1"><?=@$price;?></span>
                        <?php }?>
                            <img src="<?=IMGS_URL.$r1->thumbnail;?>" alt="">
                            <h5><?=$r1->name;?></h5>
                        </div>
                        </a>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
    </section>
    <?php endif; endif; $i++; endforeach;?>
    <!-- Categories Section End -->


    <?php 
    $i=0;
    foreach($category_header_title as $row): 
     if($i==1): 
     if($row->seq==2 && $row->type==2):       
     $rs1 = $this->home_model->getHeaderCatMap($row->id);  
    
    ?>
    <section class="new-category">
    <div class="container">
  <div class="row">
  <div class="tab-head">
     <h2 class="title" id="new_header_title"><?=$row->title;?></h2>
     </div>
    <div class="col-md-12 col-sm-6">
        <div class="new_product__item_all">
      <div id="news-slider" class="categories__slider owl-carousel">
      <?php foreach($rs1  as $r1): ?>
        <div class="post-slide">
          <div class="post-img">
            <img src="<?=IMGS_URL.$r1->thumbnail;?>" alt="">
            <a href="<?=base_url('category/'.$r1->url);?>" class="over-layer"><i class="fa fa-link"></i></a>
          </div>
          <div class="post-content">
            <h3 class="post-title">
              <a href="<?=base_url('category/'.$r1->url);?>"><?=$r1->name;?>.</a>
            </h3>
            <p class="post-description"><?=$r1->description;?></p>
            <center><a href="<?=base_url('category/'.$r1->url);?>" class="read-more text-center align-item-center mb-4">Order Now</a></center>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    </div>
  </div>
</div>
    </section>
    <?php endif; endif; $i++; endforeach;?>
    <!-- Product Section Begin -->

    <?php 
        $i=1;
        foreach($header_title as $row):   
        ?>
    <section class="product spad">
        <div class="container">
            <div class="row">
            <div class="tab-head">
            <h2 class="title" id="product_title"><?=$row->title;?></h2>
           </div>
           <div id="header-home-<?=$row->id?>">
                
                </div>  
                <script>
                $(document).ready(function(){
                    setTimeout(()=> {
                        $("#header-home-<?=$row->id?>").load("<?=base_url()?>Home/header_slider/<?=$row->id?>");
                    }, 100);                                           
                });                       
            </script>
            </div>
        </div>
    </section>
    <?php $i++;   endforeach; ?> 

    <!-- Product Section End -->

    <!-- Class Section Begin -->
    <!-- <section class="class spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="class__form">
                        <div class="section-title">
                            <span>Class cakes</span>
                            <h2>Made from your <br />own hands</h2>
                        </div>
                        <form action="#">
                            <input type="text" placeholder="Name">
                            <input type="text" placeholder="Phone">
                            <select>
                                <option value="">Studying Class</option>
                                <option value="">Writting Class</option>
                                <option value="">Reading Class</option>
                            </select>
                            <input type="text" placeholder="Type your requirements">
                            <button type="submit" class="site-btn">registration</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="class__video set-bg" data-setbg="<?=base_url();?>assets/img/class-video.jpg">
                <a href="https://www.youtube.com/watch?v=8PJ3_p7VqHw&list=RD8PJ3_p7VqHw&start_radio=1"
                class="play-btn video-popup"><i class="fa fa-play"></i></a>
            </div>
        </div>
    </section> -->
    <!-- Class Section End -->

    <!-- Team Section Begin -->
    <!-- <section class="team spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <div class="section-title">
                        <span>Our team</span>
                        <h2>Sweet Baker </h2>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="team__btn">
                        <a href="#" class="primary-btn">Join Us</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item set-bg" data-setbg="<?=base_url();?>assets/img/team/team-1.jpg">
                        <div class="team__item__text">
                            <h6>Randy Butler</h6>
                            <span>Decorater</span>
                            <div class="team__item__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item set-bg" data-setbg="<?=base_url();?>assets/img/team/team-2.jpg">
                        <div class="team__item__text">
                            <h6>Randy Butler</h6>
                            <span>Decorater</span>
                            <div class="team__item__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item set-bg" data-setbg="<?=base_url();?>assets/img/team/team-3.jpg">
                        <div class="team__item__text">
                            <h6>Randy Butler</h6>
                            <span>Decorater</span>
                            <div class="team__item__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item set-bg" data-setbg="<?=base_url();?>assets/img/team/team-4.jpg">
                        <div class="team__item__text">
                            <h6>Randy Butler</h6>
                            <span>Decorater</span>
                            <div class="team__item__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- Team Section End -->

    <!-- Testimonial Section Begin -->
    <section class="testimonial spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <span>SUPER OFFERS</span>
                        <h2>Our New Foods</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="testimonial__slider owl-carousel">
                    <?php $count=0; foreach($offer as $o):
                             $startDateTime = $o->start_date; // Replace with your actual start date/time
                             $endDateTime = $o->end_date;  ?>
                    <div class="col-lg-6">
                        <div class="testimonial__item">
                            <div class="testimonial__author">
                                <div class="testimonial__author__pic">
                                    <img src="<?=IMGS_URL.$o->thumbnail;?>" alt="">
                                </div>
                                <div class="testimonial__author__text">
                                    <h5><?=$o->pro_name;?></h5>
                                    <span><?=$o->pro_code;?></span>
                                </div>
                                 <div class="pricing mt-2">
                                 <div id="offer-container">
                                <div id="offer-message" class="h6"><?= validateOffer($startDateTime, $endDateTime);;?></div>
                                <input type="hidden" name="start_datetime" id="start_datetime" value="<?=$o->start_date;?>">
                                <input type="hidden" name="end_datetime" id="end_datetime" value="<?=$o->start_date;?>">
                                <div id="countdown_<?=$count;?>"></div>
                                </div>
                                <?php 
                                  $offerss = $this->product_model->get_data('shops_coupons_offers','product_id',$o->product_id);
                                  foreach($offerss as $offer)
                                  {
                                  if($offer->discount_type==1)
                                  {
                                  $deatailoffervalue=   $offer->offer_associated.' % OFF';
                                  $deatailoffertype=$offer->discount_type;
                                  $deatailfinalper = $o->selling_rate*$offer->offer_associated/100;
                                  $deatailfinalamount = $o->selling_rate-$deatailfinalper;
                                  }else
                                  {
                                  $deatailoffervalue ='Only '.$shop_detail->currency.'  '.$o->selling_rate-$offer->offer_associated;
                                  $deatailoffertype=$offer->discount_type;
                                  $deatailfinalamount = ($o->selling_rate-$offer->offer_associated);
                                  }    
                                  }
                               
                                ?>
                                    <?php if(!empty($offerss))
                            { ?>
                                <del class="text-danger">Rs <?php echo number_format((float)($o->selling_rate), 2, '.', ''); ?></del>  
                                <span class="text-success"> Rs <?php echo number_format((float)($deatailfinalamount), 2, '.', ''); ?>
                             </span>
                             <br>
                             <a id="cart_btn<?=$o->product_id?>" onclick="add_to_cart_by_btn(<?=$o->product_id?>,this)" href="javascript:void(0)" aria-label="Add To Cart" class="btn btn-sm btn-warning">Add to cart</a>
                             <?php  }else{?>
                                <span class="text-success"> Rs <?php echo number_format((float)($o->selling_rate), 2, '.', '') ;?>
                             </span>
                             <br>
                             <a id="cart_btn<?=$o->product_id?>" onclick="add_to_cart_by_btn(<?=$o->product_id?>,this)" href="javascript:void(0)" aria-label="Add To Cart" class="btn btn-sm btn-warning">Add to cart</a>
                                <?php }?>
                                </div>
                            </div>
                            <div class="rating">
                                <span class="icon_star"></span>
                                <span class="icon_star"></span>
                                <span class="icon_star"></span>
                                <span class="icon_star"></span>
                                <span class="icon_star-half_alt"></span>
                            </div>
                            
                            <p><?=$o->description;?></p>
                        </div>
                    </div>
                    <?php $count++; endforeach; ;?>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section End -->

      <!-- About Section Begin -->
      <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="about__text">
                        <div class="section-title">
                            <span>About Cake shop</span>
                            <h2>Cakes and bakes from the house of Queens!</h2>
                        </div>
                        <p>The "Cake Shop" is a Jordanian Brand that started as a small family business. The owners are
                        Dr. Iyad Sultan and Dr. Sereen Sharabati, supported by a staff of 80 employees.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="about__bar">
                        <div class="about__bar__item">
                            <p>Cake design</p>
                            <div id="bar1" class="barfiller">
                                <div class="tipWrap"><span class="tip"></span></div>
                                <span class="fill" data-percentage="95"></span>
                            </div>
                        </div>
                        <div class="about__bar__item">
                            <p>Cake Class</p>
                            <div id="bar2" class="barfiller">
                                <div class="tipWrap"><span class="tip"></span></div>
                                <span class="fill" data-percentage="80"></span>
                            </div>
                        </div>
                        <div class="about__bar__item">
                            <p>Cake Recipes</p>
                            <div id="bar3" class="barfiller">
                                <div class="tipWrap"><span class="tip"></span></div>
                                <span class="fill" data-percentage="90"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->
    <!-- Instagram Section Begin -->
    <section class="instagram spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 p-0">
                    <div class="instagram__text">
                        <div class="section-title">
                            <span>Follow us on instagram</span>
                            <h2>Sweet moments are saved as memories.</h2>
                        </div>
                        <h5><i class="fa fa-instagram"></i> @prashanshabakeryknp</h5>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <div class="instagram__pic">
                                <img src="<?=base_url();?>assets/img/instagram/instagram-1.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <div class="instagram__pic middle__pic">
                                <img src="<?=base_url();?>assets/img/instagram/instagram-2.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <div class="instagram__pic">
                                <img src="<?=base_url();?>assets/img/instagram/instagram-3.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <div class="instagram__pic">
                                <img src="<?=base_url();?>assets/img/instagram/instagram-4.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <div class="instagram__pic middle__pic">
                                <img src="<?=base_url();?>assets/img/instagram/instagram-5.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <div class="instagram__pic">
                                <img src="<?=base_url();?>assets/img/instagram/instagram-3.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instagram Section End -->
 
   