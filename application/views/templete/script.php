
<script type="text/javascript">

  var spinner = `<div class="text-left"><div class="spinner-border spinner-border-sm" role="status">
			  <span class="sr-only"></span>
			</div> </div>`;

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$(document).ready(function(){
    $('body').on('submit', '#check-pincode-new', function (e) {
        e.preventDefault();
        let dataString = $("#check-pincode-new").serialize();
        $.ajax({
            url: "<?= base_url('home/check_delivery_area'); ?>",
            method: "POST",
            data: dataString,
            success: function (data) {
                if (data.trim() == 'SUCCESS') {
                    $("#available-msg").html("<h5 class='text-success mt-1'>Delivery available here in this pincode.</h5>");
                } else {
                    $("#available-msg").html("<h5 class='text-danger mt-1'>Delivery not available in this pincode. Kindly check your pincode.</h5>");
                }
            }
        });
    });

	    $('.custom-menu').on('mouseenter', function() { $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(500); });
		$('.custom-menu').on('mouseleave', function() { $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(500); });
	});
 // Add to Cart

 const cart_count_increment = (item_qty) => {
	let total_cart=0;
			$.ajax({
				url: '<?=base_url();?>Home/get_cart_qty_increase',
				type: 'POST',
				dataType: 'json',
				success: function(response) {
					 total_cart = response.total;
					$(".cart_count_inner_mobile").html(parseInt(total_cart)+parseInt(item_qty));
					$(".cart__price__new").html('Cart <span> Rs ' +response.price+ '</span>');
				},
			});
		
		}

		const cart_count_decrement = (item_qty) => {
			let total_cart=0;
			$.ajax({
				url: '<?=base_url();?>Home/get_cart_qty_decrease',
				type: 'POST',
				dataType: 'json',
				success: function(response) {
					 total_cart = response.total;
					$(".cart_count_inner_mobile").html(parseInt(total_cart)-parseInt(item_qty));
					$(".cart__price__new").html('Cart <span>Rs ' +response.price+ '</span>');
				},
			});
		}

		const empty_cart = () => {
			setTimeout( () => {
			let total_cart = $('.cart-sidebar .cart-list-product.count-cart-item').length;
				if( parseInt(total_cart) <= 0 ){
					$('.cart-empty').html(`<h6 class="mb-3 mt-0 mb-3">Your cart is empty. There are no items left in cart.</h6>`);
				}
			}, 500);
		}

        
		const add_to_cart = (pid,btn,type=1) => {
   
			$(btn).attr('disabled', true);
			let qty = $(`.qty-val${pid}`).val();
			let unit = $(btn).parents('.product').find('.pro_unit').text();
			let item_qty = qty;
		    $.ajax({
			    url: '<?=base_url()?>home/add_to_cart',
			    method: "POST",
			    data: {
			        product_id:pid,
			        qty : qty,
			        unit: unit,
                    type:type
			    },
			    success: function(response){
			    	cart_count_increment(item_qty);
			    	$(`.add-to-cart-div-${pid}`).html(response);
			        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                    if(type==2)
                        {
                        $(`.add-to-cart-div-${pid} a`).addClass('plusminus');
                        }
                       
			        $(btn).attr('disabled', false);
			        toastr.success('Item added to cart');			        
			    },
			});
		}

		const add_to_cart_by_btn = (pid,btn,type=1) => {
			let qty = $('.newcart_btn').find('.count-number-input').val();
			if(qty=='' || qty==undefined)
			{
				final_qty = '1';
			}else
			{
				final_qty = qty;
			}
			let item_qty = final_qty;
		    $.ajax({
			    url: '<?=base_url()?>home/add_to_cart_by_btn',
			    method: "POST",
			    data: {
			        product_id:pid,
			        qty : final_qty,
			    },
			    success: function(response){
			    	cart_count_increment(item_qty);
			        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                    if(type==2)
                        {
                        $(`.add-to-cart-div-${pid} a`).addClass('plusminus');
                        }
                       
			        toastr.success('Item added to cart');			        
			    },
			});
		}

		function increase_quantity_by_btn(btn){
	        let value = $(btn).parents('.newcart_btn').find('.count-number-input').val();
	        value++;
	        $(btn).parents('.newcart_btn').find('.count-number-input').val(value);
	    }

	    function decrease_quantity_by_btn(btn){
	        let value = $(btn).parents('.newcart_btn').find('.count-number-input').val();
	        if (value > 1) {
	            value--;
	            $(btn).parents('.newcart_btn').find('.count-number-input').val(value);
	        }
	    }

		var timer1;
        var timeout = 500;
		const cookie_increase_quantity = (pid, btn) => {
			$('.loader-button').show();
		    let input = $(btn).data('target');
		    let total = parseInt( $(input).val() );
		    total++;
		    $(input).val(total);
		    clearTimeout(timer1);
            timer1 = setTimeout(function(){
			    $.ajax({
				    url: '<?=base_url()?>home/update_cookie_cart',
				    method: "POST",
				    data: {
				        qty:total,
				        pid:pid,
				    },
				    success: function(data){
				    	let item_qty = 1;
				    	cart_count_increment(item_qty);
				        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                        $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
				    },
				});
				$('.loader-button').hide();
			}, timeout);
		}

		const cookie_decrease_quantity = (pid,btn,type=1) => {
          	$('.loader-button').show();
		    let input = $(btn).data('target');
		    let total = parseInt( $(input).val() );
		    total--;
		    if( total == 0 ){
		    	$(`.add-to-cart-div-${pid}`).html(spinner);
		    	$(input).val(1);
		    }else{
		    	$(input).val( total );
		    }
		    clearTimeout(timer1);
            timer1 = setTimeout(function(){
			    $.ajax({
				    url: '<?=base_url()?>home/update_cookie_cart',
				    method: "POST",
				    data: {
				        qty:total,
				        pid:pid,
                        type:type
				    },
				    success: function(data){
				    	let item_qty = 1;
				    	cart_count_decrement(item_qty);
				    	if( total <=0 ){
				    		$(`.add-to-cart-div-${pid}`).html(data);
                            
				    		//$(`#cart_btn${pid}`).attr('onclick', `add_to_cart(${pid},this)`).fadeIn('slow');				    		

				    	}
				        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
				    },
				});
				$('.loader-button').hide();
			}, timeout);
		}

		const cookie_quantity_by_input = (pid, btn) => {
			$('.loader-button').show();
		    let input = $('.qty-val'+pid);
		    let total = parseInt( $(btn).val() );
		    if( total == 0 ){
		    	$(`.add-to-cart-div-${pid}`).html(spinner);
		    	$(input).val(1);
		    }else{
		    	$(input).val( total );
		    }
		    clearTimeout(timer1);
            timer1 = setTimeout(function(){
			    $.ajax({
				    url: '<?=base_url()?>home/update_cookie_cart',
				    method: "POST",
				    data: {
				        qty:total,
				        pid:pid,
				    },
				    success: function(data){
				        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                        $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
                        $.ajax({
						    url: '<?=base_url()?>home/update_cookie_cart_count',
						    method: "POST",
						    data: {
						        qty:total,
						    },
						    success: function(data2){
						        //console.log(data2);		        
						        $("#cart_count").html(data2+' item');
                             $("#cart_countmobile").html(data2);
						    },
						});
				    },
				});
				$('.loader-button').hide();
			}, timeout);
		}

        const delete_cookie_cart_all=()=>{
         $.ajax({
			    url: '<?=base_url()?>home/delete_cookie_cart_all',
			    method: "POST",
			    data: {
			       
			    },
			    success: function(data){
			        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                    $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
                    $("#cart_count").html('0 item');
                    $("#cart_countmobile").html('0');
                   toastr.success('Cart Cleared Successfully');
			    },
			});
        }
        
		const delete_cart_all=(btn)=> {
		    $.ajax({
			    url: '<?=base_url()?>home/delete_cart_all/',
			    method: "POST",
			    data: {
			    },
			    beforeSend: function() {
                    $(btn).attr("disabled", true);
                },
			    success: function(data){
			    	$(btn).attr("disabled", false);
			    	$(".cart-div").load('<?=base_url()?>home/cart_view/');
                    $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
                    $("#cart_count").html('0 item');
                    $("#cart_countmobile").html('0');
                    toastr.success('Cart Cleared Successfully');
			    },
			});
		}
        
		const delete_cookie_cart = (pid) => {
			let item_qty = $('.qty-val'+pid).val();
		    $.ajax({
			    url: '<?=base_url()?>home/delete_cookie_cart',
			    method: "POST",
			    data: {
			        pid:pid,
			    },
			    success: function(data){
			    	cart_count_decrement(item_qty);
//                    $(`#cart_btn${pid}`).attr('onclick', `add_to_cart(${pid},this)`).html(`<i class="fi-rs-shopping-bag-add"></i>`).fadeIn('slow');
			        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                    $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
                    //$(`.add-to-cart-div-${pid}`).html("<a aria-label='Add To Cart' class='action-btn hover-up' id='cart_btn"+pid+"' onclick='add_to_cart("+pid+",this)' href='javascript:void(0)'><i class='fi-rs-shopping-bag-add'></i> add to cart</a>");
			    },
			});
		}


		// const increase_quantity

		function increase_quantity(cart_id,pid,btn) {
			$('.loader-button').show();
		    let input = $(btn).data('target');
		    let total = parseInt( $(input).val() );
		    total++;
		    $(input).val(total);
		    clearTimeout(timer1);
            timer1 = setTimeout(function(){
			    if( cart_id ){
				    $.ajax({
					    url: '<?=base_url()?>home/update_cart/',
					    method: "POST",
					    data: {
					        qty:total,
					        pid:pid,
					        cart_id:cart_id,
					    },
					    success: function(data){
					        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                            $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
					        // for checkout page
					        if( $('.checkout_cart').length ){
					        	$(".checkout_cart").load('<?=base_url()?>checkout/checkout_cart', function(){
					        		let total_payable = $('.sub-total').text();
					       // 		let cod_limit = $('input[name=cod_limit]').val();
					       // 		let coin = $('input[name=online_coin]').is(':checked') ? parseFloat($('input[name=online_coin]').val()) : 0;
					       // 		$('button.pay-btn-cod').text(`Continue ${total_payable-coin}`).attr('onclick', `make_cod_payment(${cod_limit},${total_payable-coin})`);
					        		$('button.make-online-payment').text(`PAY NOW`);
									$("#subamount").val(total_payable);
					        	});
					        }

					        let item_qty = 1;
				    		cart_count_increment(item_qty);
					    },
					});
				}
				$('.loader-button').hide();
			}, timeout);
		}

		function quantity_by_input(cart_id, pid, btn) {
			$('.loader-button').show();
		    let input = $('.qty-val'+pid);
		    let total = parseInt( $(btn).val() );
		    if( total == 0 ){
		    	$(`.add-to-cart-div-${pid}`).html(spinner);
		    	$(input).val(1);
		    }else{
		    	$(input).val( total );
		    }
		    clearTimeout(timer1);
            timer1 = setTimeout(function(){
			    $.ajax({
				    url: '<?=base_url()?>home/update_cart',
				    method: "POST",
				    data: {
				        qty:total,
				        pid:pid,
				        cart_id:cart_id,
				    },
				    success: function(data){
				        $(".cart-div").load('<?=base_url()?>home/cart_view/');
                        $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
                        // for checkout page
                        if( $('.checkout_cart').length ){
				        	$(".checkout_cart").load('<?=base_url()?>checkout/checkout_cart', function(){
				        		let total_payable = $('.total-payable').text();
				        		let cod_limit = $('input[name=cod_limit]').val();
				        		let coin = $('input[name=online_coin]').is(':checked') ? parseFloat($('input[name=online_coin]').val()) : 0;
				        		$('button.pay-btn-cod').text(`Continue ${total_payable-coin}`).attr('onclick', `make_cod_payment(${cod_limit},${total_payable-coin})`);
				        		$('button.make-online-payment').text(`PAY NOW`);
								$("#subamount").val(total_payable-coin);
				        	});
				        }

				        $.ajax({
						    url: '<?=base_url()?>home/update_cookie_cart_count',
						    method: "POST",
						    data: {
						        qty:total,
						    },
						    success: function(data2){
						        //console.log(data2);		        
						        $("#cart_count").html(data2+' item');  
                             $("#cart_countmobile").html(data2);
						    },
						});
				    },
				});
				$('.loader-button').hide();
			}, timeout);
		}

		function decrease_quantity(cart_id,pid,btn,type=1) {
           
           	$('.loader-button').show();
		    let input = $(btn).data('target');
		    let total = parseInt( $(input).val() );
		    total--;
		    if( total == 0 ){
		    	$(input).val(1);
		    	$(`.add-to-cart-div-${pid}`).html(spinner);
		    }else{
		    	$(input).val( total );
		    }
		    clearTimeout(timer1);
            timer1 = setTimeout(function(){
			    if( cart_id ) {
				    $.ajax({
					    url: '<?=base_url()?>home/update_cart/',
					    method: "POST",
					    data: {
					        qty:total,
					        pid:pid,
					        cart_id:cart_id,
                            type:type
					    },
					    beforeSend: function() {
	                        $(btn).attr("disabled", true);
	                    },
					    success: function(data){
					    	$(btn).attr("disabled", false);
					    	$(".cart-div").load('<?=base_url()?>home/cart_view/');
                            $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
					        if( total <=0 ){
					        	$(`.add-to-cart-div-${pid}`).html(data);
					        	//$(`#cart_btn${pid}`).attr('onclick', `add_to_cart(${pid},this)`).html(`<i class="fi-rs-shopping-bag-add"></i>`).fadeIn('slow');
					    		//cart_count_decrement();
					    	}
					        // for checkout page
					        if( $('.checkout_cart').length ){
					        	// empty_cart();
					        	$(".checkout_cart").load('<?=base_url()?>checkout/checkout_cart', function(){
					        		let total_payable = $('.sub-total').text();
					       // 		let cod_limit = $('input[name=cod_limit]').val();
					       // 		let coin = $('input[name=online_coin]').is(':checked') ? parseFloat($('input[name=online_coin]').val()) : 0;
					       // 		$('button.pay-btn-cod').text(`Continue ${total_payable-coin}`).attr('onclick', `make_cod_payment(${cod_limit},${total_payable-coin})`);
					        		$('button.make-online-payment').text(`PAY NOW`);
									$("#subamount").val(total_payable);
					        	});
					        }

					        let item_qty = 1;
				    		cart_count_decrement(item_qty);
					    },
					});
				}
				$('.loader-button').hide();
			}, timeout);
		}
    
		function delete_cart(cart_id,pid,btn) {
           	let item_qty = $('.qty-val'+pid).val();
		    $.ajax({
			    url: '<?=base_url()?>home/delete_cart/',
			    method: "POST",
			    data: {
			        pid:pid,
			        cart_id:cart_id
			    },
			    beforeSend: function() {
                    $(btn).attr("disabled", true);
                },
			    success: function(data){
			    	$(btn).attr("disabled", false);
			    	$(".cart-div").load('<?=base_url()?>home/cart_view/');
                    $(".cart-div-main").load('<?=base_url()?>home/cart_main_view/');
			        cart_count_decrement(item_qty);
                    $(`.add-to-cart-div-${pid}`).html("<a aria-label='Add To Cart' class='action-btn hover-up' id='cart_btn"+pid+"' onclick='add_to_cart("+pid+",this)' href='javascript:void(0)'><i class='fi-rs-shopping-bag-add'></i></a>");
//			    	$(`#cart_btn${pid}`).attr('onclick', `add_to_cart(${pid},this)`).html(`<i class="fi-rs-shopping-bag-add"></i>`);
			        
			        // for checkout page
			        if( $('.checkout_cart').length ){
			        	empty_cart();
			        	$(".checkout_cart").load('<?=base_url()?>checkout/checkout_cart', function(){
			        		let total_payable = $('.total-payable').text();
			        		let cod_limit = $('input[name=cod_limit]').val();
			        		let coin = $('input[name=online_coin]').is(':checked') ? parseFloat($('input[name=online_coin]').val()) : 0;
			        		$('button.pay-btn-cod').text(`Continue ₹${total_payable-coin}`).attr('onclick', `make_cod_payment(${cod_limit},${total_payable-coin})`);
			        		$('button.make-online-payment').text(`PAY NOW`);
							$("#subamount").val(total_payable-coin);
			        	});
			        }
			        toastr.success('Item Deleted');
			    },
			});
		}

	function add_to_wishlist(pid){
		$.ajax({
            url: '<?=base_url()?>home/add_to_wishlist',
            method: "POST",
            data: {
                pid:pid,
            },
            success: function(data){
            	// console.log(data);
            	$('.wishlist').addClass('text-danger').attr('onclick', '').attr('title', 'Already added');
                toastr.success('Item added to wishlist');
            },
        });
	}

	function remove_to_wishlist(btn,pid){		
		$.ajax({
            url: '<?=base_url()?>home/remove_to_wishlist',
            method: "POST",
            data: {
                pid:pid,
            },
            success: function(data){
            	let rowCount = $('#wishlist-table tr').length;
            	console.log(rowCount);
            	$(btn).parents('tbody').remove();
                toastr.success('Item Removed from wishlist');
                if (rowCount < 3) {
                	$('#wishlist-table tr').remove();
                	$('#wishlist-table').append('<h3 class="text-center">Not Found Any Products</h3>');
                }
            },
        });
	}

	const remove_wishlist = (id) => {
        toastr.warning("<br /><button type='button' value='yes'>Yes</button><button type='button' value='no' >No</button>",'Are you sure you want to remove this item?',
        {
            allowHtml: true,
            // closeButton : true,
            onclick: function (toast) {
                value = toast.target.value
                if (value == 'yes') {
                    $.ajax({
                        url: '<?=base_url()?>user/users/remove_wishlist',
                        method: "POST",
                        data: {
                            id:id,
                        },
                        success: function(data){
                        	toastr.remove();
                            toastr.success('Item Removed from wishlist');
                            $(`#wishlist_${id}`).remove();
                        },
                    });
                }else{
                    toastr.remove();
                }
            }

        });
    };


	function user_login(btn) {
		dataString = $("#login-form").serialize();
        $.ajax({
            type: "POST",
            url: "<?=base_url();?>Login/user_login",
            data: dataString,
            dataType: 'json',
            beforeSend: function() {
                $(btn).attr("disabled", true);
                $(btn).text("Process...");
            },
            success: function(data){ 
            // console.log(data);             
              if (data.status == false) {
              	$(btn).text("Login").removeAttr("disabled");
                $("#error-login-form").html('');
                $("#error-login-form").html(data.error);
              }

              if (data.status == true) {
                $("#error-login-form").html('');
               // window.location.href = base_url+'profile';   
                 window.location.href = '<?=base_url();?>';               
              }
            }
        });
        return false;  //stop the actual form post !important!
	}
	function customer_login(btn) {
		dataString = $("#customer_login").serialize();
        $.ajax({
            type: "POST",
            url: "<?=base_url();?>Login/user_login",
            data: dataString,
            dataType: 'json',
            beforeSend: function() {
                $(btn).attr("disabled", true);
                $(btn).text("Process...");
            },
            success: function(data){ 
            // console.log(data);             
              if (data.status == false) {
              	$(btn).text("Login").removeAttr("disabled");
                $("#error-login-formcustomer_login").html('');
                $("#error-login-formcustomer_login").html(data.error);
              }

              if (data.status == true) {
                $("#error-login-formcustomer_login").html('');
               // window.location.href = base_url+'profile';   
                 window.location.href = '<?=base_url();?>';               
              }
            }
        });
        return false;  //stop the actual form post !important!
	}
	function openAccount() {
		toastr.error("Please login here...");
    }
</script>