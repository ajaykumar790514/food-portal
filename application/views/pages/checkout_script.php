<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">

	$(document).ready(function(){

		$('body').on('click', '.time-slot li', function(){
			$('.time-slot li').removeClass('bg-success text-light active');
			$(this).addClass('bg-success text-light active');
		})

		$('body').on('change', 'input[name=cod_coin], input[name=online_coin]', function(){

			if( $(this).is(':checked') ){

				$('input[name=cod_coin]').prop('checked', true);
				$('input[name=online_coin]').prop('checked', true);

				let coin = $('input[name=online_coin]').is(':checked') ? parseFloat($('input[name=online_coin]').val()) : 0;

				let payable = parseFloat($('.total-payable').text());

               
        		// checkout buttons
	    		let cod_limit = $('input[name=cod_limit]').val();
	    		$('button.pay-btn-cod').text(`Continue ₹${(payable-coin).toFixed(2)}`).attr('onclick', `make_cod_payment(${cod_limit},${(payable-coin).toFixed(2)})`);
	    		$('button.make-online-payment').text(`Continue ₹${(payable-coin).toFixed(2)}`);
			}else{
				$('input[name=cod_coin]').prop('checked', false);
				$('input[name=online_coin]').prop('checked', false);

				let payable = parseFloat($('.total-payable').text());

        		// checkout buttons
	    		let cod_limit = $('input[name=cod_limit]').val();
	    		$('button.pay-btn-cod').text(`Continue ₹${payable.toFixed(2)}`).attr('onclick', `make_cod_payment(${cod_limit},${payable})`);
	    		$('button.make-online-payment').text(`Continue ₹${payable.toFixed(2)}`);
			}
		})

		$('#coupon-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) 
            var recipient = button.data('whatever') 
            var data_url  = button.data('url') 
            var modal = $(this)
            $('#coupon-modal .modal-title').text(recipient)
            $('#coupon-modal .modal-body').load(data_url);
        });

        $('body').on('click', '.delete-coupon', function(){
        	$('.coupon-head').remove();
        	let payable = parseFloat( $('input[name=total_value]').val() );

        	let coin = $('input[name=online_coin]').is(':checked') ? parseFloat($('input[name=online_coin]').val()) : 0;

        	$('.total-payable').text(payable);

        	// checkout buttons
    		let cod_limit = $('input[name=cod_limit]').val();
    		$('button.pay-btn-cod').text(`Continue ₹${(payable-coin).toFixed(2)}`).attr('onclick', `make_cod_payment(${cod_limit},${(payable-coin).toFixed(2)})`);
    		$('button.make-online-payment').text(`Continue ₹${(payable-coin).toFixed(2)}`);

    		$('input[name=coupon_code]').val('');
    		toastr.success('Coupon Deleted Successfully!');
        });

        $('body').on('click', '.apply-coupon', function(){
        	let code = $(this).data('code');
        	$.get(`${base_url}checkout/coupon?code=${code}`, function(data){
        		data = JSON.parse(data);
        		let amt = parseFloat( $('input[name=total_value]').val() );
        		let coin = $('input[name=online_coin]').is(':checked') ? parseFloat($('input[name=online_coin]').val()) : 0;
        		let payable = 0;
        		let dis = 0;
        		if( amt >= data.minimum_coupan_amount ){
    				if( data.discount_type == 1 || data.discount_type == '1'){
    					dis = (amt*data.value)/100;
	        		}else{
	        			dis = data.value;
	        		}

	        		dis = dis > data.maximum_coupan_discount_value ? data.maximum_coupan_discount_value : dis;
	        		payable = amt - dis;
	        		payable = (payable>0) ? payable : 0;

	        		$('.total-payable').text(payable);

	        		let html = `<h6 class="coupon-head text-warning">Coupon Applied (${data.code}) <strong class="float-right text-warning">€ ${dis} <a href="javascript:void(0)" class="delete-coupon text-warning"><i class="fa fa-trash"></i> </a> </strong></h6>`;

	        		if( $('.cart-store-details .coupon-head').length <= 0 ){
	        			$('.cart-store-details').append(html);
	        		}else{
	        			$('.cart-store-details .coupon-head').replaceWith(html);
	        		}
	        		

	        		// checkout buttons
	        		let cod_limit = $('input[name=cod_limit]').val();
	        		$('button.pay-btn-cod').text(`Continue ₹${payable-coin}`).attr('onclick', `make_cod_payment(${cod_limit},${payable-coin})`);
	        		$('button.make-online-payment').text(`Continue ₹${payable-coin}`);

	        		$('input[name=coupon_code]').val(code);
	        		toastr.success('Coupon Applied Successfully!');

	        		$('#coupon-modal').modal('toggle');
        		}else{
        			toastr.error(`Cart Amout should be ${data.minimum_coupan_amount} or above.`);
        		}
        		

        	});
        })
	})

	$('body').on('click', '.delivery-btn', function(){
		$('.delivery-btn').removeClass('btn-success').addClass('bg-dark');
		$(this).removeClass('bg-dark').addClass('btn-success');
		let id = $(this).val();
		$("input[name='address_id_default']").val(id);
		// checkpincode(id);
		check_address(id);
	});

	$('body').on('submit', '#check-pincode', function(e){
        e.preventDefault();
        let dataString = $("#check-pincode").serialize();
        $.ajax({
            url:"<?= base_url('home/check_delivery_area'); ?>",
            method:"POST",
            data:dataString,
            success:function(data){
                    $("#available-msg").text(data);              
            }
        });
    });
    
    $('body').on('click', '.timeslot-btn', function(){
		$('.timeslot-btn').removeClass('btn-success').addClass('btn-secondary');
		$(this).addClass('btn-success').removeClass('btn-secondary');
	});


	
	$(document).on('click', '.make-online-payment', function () {
    $('.loader-container').show();
    let id = $("input[name='address_id_default']").val();
    let t_value = $("#subamount").val();
    let button = $(this);
    
    $.ajax({
        url: "<?= base_url('home/check_delivery_area'); ?>",
        method: "POST",
        data: { aid: id },
        success: function (data) {
            if (data.trim() === 'SUCCESS') {
                $.ajax({
                    url: "<?= base_url('home/check_cart_data'); ?>",
                    method: "POST",
                    data: { aid: id },
                    success: function (data) {
                        if (data.trim() === 'SUCCESS') {
                            makeOnlineDirectPayment(id, button);
                        } else {
                            $('.loader-container').hide();
                            alert("Sorry, please check your cart!");
                            $(".cart-div").load('<?=base_url()?>home/cart_view/');
                            $.ajax({
                                url: '<?=base_url()?>home/update_cookie_cart_count',
                                method: "POST",
                                data: { aid: id },
                                success: function (data2) {
                                    $('.loader-container').hide();
                                    $("#cart_count").html(data2 + ' item');
                                    $("#cart_countmobile").html(data2);
                                },
                            });
                        }
                    }
                });
            } else {
                $('.loader-container').hide();
                alert("Delivery not available in this pincode. Kindly check your pincode.");
            }
        }
    });
});

	
	//below function writeen by techfi
	//used when no need to check delivery criteria.so it will directly execute online payment
	function makeOnlineDirectPayment(id,btnref)
	{
	    let btn = $(btnref);
        let btn_text = btn.text();
	    let aid = $('.address-div').find('button.delivery-btn.btn-success').val();
        let coupan_code = $('input[name=coupon_code]').val();
        let coin_pay = 0;
        let remark = $("#remark").val();
		if(aid !=''){
        $.ajax({
			url:"<?=base_url('home/check_address');?>",
			method:"POST",
			data:{
				id:id
			},
			dataType:"JSON",
			success:function(data){
				
                 $.ajax({
                                url:"<?=base_url('checkout/checkout_items/place_order');?>",
                                method: "POST",
                                dataType:"JSON",
                                data: {
                                    aid : aid,
                                    coupon_code : coupan_code,
                                    coin_pay : coin_pay,
                                    remark:remark,
                                    //slot_id : slot
                                },
                                beforeSend: function() {
                                    btn.attr('disabled', true).text('Please Wait').css('padding','6px 8px');
                                },
                                success: function(res){
									$('.loader-container').hide();
                                    if(res.flag == 'out_of_stock')
                                    {
                                        var ele = '';
                                        $.each(res.out_of_stock_data, function(index,value){
                                            ele = ele + '<h6>Product name => ' + value.product_name + ' <span style="color:red">(Available Quantity - '+value.qty+')</span></h6><br>';
                                        });
                                        $('#pdetail').html(ele);
                                        $("#stock_data").modal('show');
                                        btn.attr('disabled', false).text(btn_text);
                                        return false;
                                    }               
                    	            var obj = JSON.parse(res.data);
                    	            var options = {
                    	                "key": obj.secret_key,
                    	                "amount": parseFloat(obj.total)*100,
                    	                "currency": "INR",
                    	                "name": "Prashansha Bakery",
                    	                "description": "Prashansha Bakery online payments",
                    	                // "image": "<?//= IMGS_URL.$shop_detail->logo ?>",
                    	                "account_id": null,
                    	                "order_id": obj.order_id_razor, 
                    	                "handler": function (response){
                    	                    $.ajax({
												url:"<?=base_url('checkout/checkout_items/verify_payment');?>",
                    	                        method: "POST",
                    	                        dataType:"JSON",
                    	                        data: {
                    	                            razorpay_payment_id : response.razorpay_payment_id,
                    	                            razorpay_order_id : response.razorpay_order_id,
                    	                            razorpay_signature : response.razorpay_signature,
                    	                            order_idrazor : obj.order_id_razor,
                    	                        },
                    	                        success: function(data)
                    	                        {
                    	                            if(data == 'true' || data == true)
                    	                            {
                    	                                $.ajax({
															url:"<?=base_url('checkout/checkout_items/update_order_status');?>",
                    	                                    method: "POST",
                    	                                    data: {
                    	                                        payment_method : 'Razorpay',
                    	                                        order_id : res.order_id,
                    	                                        payment_id : response.razorpay_payment_id,
                    	                                        signature : response.razorpay_signature,
                    	                                        razorpay_ord_id : response.razorpay_order_id
                    	                                    },
                    	                                    success: function(data)
                    	                                    {
																if (data.trim() === 'success')
                    	                                        {
                    	                                           window.location = "thanks";
                    	                                        }else{
                    	                                            alert('Status Not Updated');
                    	                                        }
                    	                                    },
                    	                                });
                    	                                //end update order status
                    	                            }else{
                    	                                alert('Payment failed');
                    	                            }
                    	                        },
                    	                    });
                    	                },
                    	                "modal": {
                    	                    "ondismiss": function(){
                    	                        window.location = "error";
                    	                    }
                    	                },
                    	                "prefill": {
                    	                    "name": res.user_name,
                    	                    "email": res.user_email,
                    	                    "contact": res.user_mobile
                    	                },
                    	                "notes": {
                    	                    "address": "Razorpay Corporate Office"
                    	                },
                    	                "theme": {
                    	                    "color": "#3399cc"
                    	                }
                    	            };
                    	            var rzp1 = new Razorpay(options);
                    	            rzp1.on('payment.failed', function (response){
                    	                    alert(response.error.code);
                    	                    alert(response.error.description);
                    	                    alert(response.error.source);
                    	                    alert(response.error.step);
                    	                    alert(response.error.reason);
                    	                    alert(response.error.metadata.order_id);
                    	                    alert(response.error.metadata.payment_id);
                    	            });
                    	            rzp1.open();
                                },
                                error: function (response) {
                                    toastr.error('Something went wrong. Please try again!');
                                    btn.attr('disabled', false).text(btn_text);
                                }
                            });
			}
		});

	   }else
	   {
	        toastr.error(' Kindly select address first to continue');
	   }
	  
	}
	


	function make_cod_payment() {
    let id = $("input[name='address_id_default']").val();
    check_address(id);
    $(".pay-btn-cod").attr('disabled', true);
    $(".pay-btn-cod").html('Please Wait...');
    let aid = $('.address-div').find('button.delivery-btn.btn-success').val();
    if (!aid) {
        toastr.error('Please choose the delivery address.');
        return false;
    }
    let confirmation = confirm("Are you sure you want to make cash on delivery payment?");
    if (confirmation) {
        $.ajax({
            url: "<?php echo base_url('checkout/checkout_items/make_cod_payment'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                aid: aid,
            },
            success: function (res) {
                if (res.flag == 'success') {
                    window.location = "thanks";
                }
            }
        });
    }else{
		$(".pay-btn-cod").attr('disabled', false);
        $(".pay-btn-cod").html('CONTINUE');
	}
}

    
    
    
    function check_address(id){
    	$('.loader-container').show();
		let total = $("input[name='sub_total']").val();
		let d_charge = 0.00;
		$.ajax({
			url:"<?=base_url('home/check_address');?>",
			method:"POST",
			data:{
				id:id,total:total
			},
			dataType:"JSON",
			success:function(data){
				$('.loader-container').hide();
				if (data.charge) {
					d_charge = parseFloat(data.charge); 
                    subTotal = total + d_charge;
				}

				$(".delivery-charge").text(d_charge.toFixed(2));
				$(".sub-total").text(subTotal.toFixed(2));
				$(".to-pay").text(subTotal.toFixed(2));
				$(".eligible-text").hide();

				if (data.status == "FAIL_PINCODE") {
					
					$("#address-pincode-modal").modal('show');
					$("#available-msg").text('Service not available in this area. Kindly check your address.');
					$("#collapseThree button").attr("disabled", true);
					
					return false;
				}
				
				if (data.status == "FAIL_DISTANCE") {
					$("#address-pincode-modal").modal('show');
					$("#available-msg").text('Delivery service criteria exceeded. Only '+data.distance+' km support available.');
					$("#collapseThree button").attr("disabled", true);

					return false;
				}else{
					$("#collapseThree button").removeAttr("disabled");
                    
                    return true;
				}

			}
		});
	}
    
	
    

	function check_delivery_charge(id){
		let total = $("input[name='sub_total']").val();
		$.ajax({
			url:"<?=base_url('home/check_address');?>",
			method:"POST",
			data:{
				id:id
			},
			dataType:"JSON",
			success:function(data){
					if (data.charge) {
						let d_charge = data.charge;
					}else{
						let d_charge = 0.00;
					}
					$(".delivery-charge").text(d_charge);
					total = parseInt(total)+parseInt(d_charge);
					$(".sub-total").text(total).toFixed(2);
					$(".total-payable").text(total).toFixed(2);
							
			}
		});
	}

</script>