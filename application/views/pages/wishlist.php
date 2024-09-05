  <!-- Breadcrumb Begin -->
  <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Wishlist</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="./index.html">Home</a>
                        <span>Wishlist</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Wishlist Section Begin -->
    <section class="wishlist spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                <?php
                    $wishlist_data = wishlist_data();
                    if (empty($wishlist_data)) {
                        echo '<h3 class="text-center text-danger">Not Found Any Products</h3>';
                      }else{
                      ?>
                    <div class="wishlist__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Offer</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php                        
                                    foreach ($wishlist_data as $row) {
                                        $product_id = $row->product_id;
                                        $wishlist_items = $this->product_model->wishlist_product_details($product_id);
                                ?>
                                <tr>
                                    <td class="product__cart__item">
                                        <div class="product__cart__item__pic">
                                            <a href="<?= base_url('product-detail/'.@$wishlist_items->url); ?>">
                                            <img src="<?=base_url();?>assets/img/shop/cart/cart-1.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="product__cart__item__text">
                                            <h6><?=@$wishlist_items->pro_name;?></h6>
                                        </div>
                                    </td>
                                    <td class="cart__stock">Available</td>
                                    <td class="cart__price">$ 15.00</td>
                                   
                                    <td class="cart__btn"><a id="cart_btn<?=@$wishlist_items->id?>" onclick="add_to_cart_by_btn(<?=@$wishlist_items->id?>,this)" href="javascript:void(0)" aria-label="Add To Cart"  class="primary-btn btn-sm">Add to cart</a></td>
                                    <td class="cart__close"><a href="javascript:void(0)" onclick="remove_to_wishlist(this,<?= $product_id; ?>)"><span class="icon_close"></span></a></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Wishlist Section End -->