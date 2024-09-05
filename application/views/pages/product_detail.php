    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Product detail</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="<?=base_url();?>">Home</a>
                        <span>
                        <a href="<?= base_url('product/'.$prourl) ?>">
                                <?= $cat_detail->name; ?>
                            </a>
                        </span>
                        <?php if( $name ): ?>
                        <span class="active"> <?= $name; ?> </span>
                        <?php endif; ?>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
    <style>
        #spinner-div {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 50%;
        left: 0;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 2;
        }
        </style>
    <main>        
        <div class="product-details1">
            
        </div>
        <div id="spinner-div" class="pt-5">
            <div class="spinner-border text-primary" role="status">
            </div>
        </div>
        <script>
            $(".product-details1").load('<?=base_url()?>home/single_product_detail/<?= $inventory_id ?>/<?= $cat_id ?>/<?= $sub_cat_id ?>');
        </script>


        </main>

