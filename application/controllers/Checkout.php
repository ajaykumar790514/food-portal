<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'third_party/razorpay-php-2.9.0/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
require_once(APPPATH . 'third_party/tcpdf/TCPDF/tcpdf.php');

class Checkout extends CI_Controller {

    public function templete($page, $data)
    {
        $this->load->view('templete/header',$data);
        $this->load->view($page);
        $this->load->view('templete/footer',$data);
        $this->load->view('templete/script',$data);
    }
    public function checkout_items($action=null,$id='null')
    {
        switch ($action) {
            case null:
                $user_id = $this->session->user_id ? $this->session->user_id : get_cookie("user_id");
                $user_code = $this->session->user_id ? $this->session->user_id : get_cookie("user_id");
                $data['title'] = 'Checkout';
                $data['country']  = $this->db->get('countries')->result();
                $data['add'] = $this->home_model->getRow('customers',['id'=>$user_id]);
                $data['addresses'] = $this->user_model->get_data1('customers_address','customer_id',$user_id);
                $data['edit_addr_url'] = base_url().'user/users/edit_address/';
                $data['add_url'] = base_url('checkout/checkout_items/add_address/');
                $data['coupon_url'] = base_url('checkout/coupon');
                $data['remote']     = base_url().'user_remote/pincode/';
                $data['cart_data'] = $this->home_model->get_data1('cart','user_id',$user_id);
                $subtotaloffer=  $total_savings= $subtotal=0;
                foreach ($data['cart_data'] as $cart) {
                    $product_id = $cart->product_id;                                                    
                }               
                $page = 'pages/checkout';
                   $this->templete($page, $data);
                break;
                case 'add_address':
                    if($this->session->userdata('logged_in'))
                    {
                        $customer_id = $this->session->userdata('user_id');
                    }
                    else
                    {
                        $customer_id = get_cookie("user_id");
                    }
                    $count = count($this->user_model->get_data1('customers_address','customer_id',$customer_id));
                    
                    $post = $this->input->post();
    
                       $data = array(
                        'customer_id'    => $customer_id,
                        'address_line_1'    => $post['address_line_1'],
                        'address_line_2'    => $post['address_line_2'],
                        'address_line_3'    => $post['address_line_3'],
                        'landmark' => $post['landmark'],
                        'pincode'    => $post['pincode'],
                        'contact_person_name'    => $post['contact_person_name'],
                        'contact'    => $post['mobile'],
                        'apartment_name'    => $post['apartment_name'],
                        'floor'    => $post['floor'],
                        'state'=>$post['state'],
                        'city'    => $post['city'],    
                        'country'=>'Indian'                   
                    );
                    if( $post['id'] ):
                        $this->user_model->Update('customers_address',$data,['id'=>$post['id']]);
                    else:
                        if($count == 0)
                        {
                            $data['is_default'] ='1';
                        }else{
                            $data['is_default'] ='0';  
                        }
                        $this->user_model->add_data('customers_address',$data);
                    endif;
    
                    echo true;
                    break;
            case 'place_order':
                $user_id = $this->session->user_id ? $this->session->user_id : get_cookie("user_id");
                $user_mobile = $this->session->user_mobile ? $this->session->user_mobile : get_cookie("user_mobile");
                $aid = $this->input->post('aid');
                $coin_pay = $this->input->post('coin_pay');
                $coupon_id = $this->input->post('coupon_code');
                $slot_id = $this->input->post('slot_id');
                $cart_data = $this->user_model->get_data1('cart','user_id',$user_id);
                $subtotal=$total_cutting_price=$total_savings=$total_tax=0.00;
                $out_of_stock_data = array();
                $insert_ids = $item_data = array();
                $remark = $this->input->post('remark');
                $shop_id = '6';
                $offer_type_new=2;
                foreach ($cart_data as $cart) {
                    // product_id = inventory_id
                    $product_id = $cart->product_id;
                        $cart_items = $this->product_model->product_details($product_id);
                        //calculate selling rate
                      
                        if($cart_items->discount_type=='0') //0->rupee
                        {
                            $offer_type_new = 0;
                            $selling_rate = ($cart_items->selling_rate - $cart_items->offer_upto)*$cart->qty;
                            
                        }else if($cart_items->discount_type=='1') //1->%
                        {
                            $selling_per = (($cart_items->selling_rate * $cart_items->offer_upto)/100);
                            $selling_rate = ($cart_items->selling_rate - $selling_per)*$cart->qty;
                            $offer_type_new=1;
                        }else{
                            $selling_rate = $cart->qty*$cart_items->selling_rate;
                            $offer_type_new=2;
                        }
                        
                        //end of calculate selling rate
                        
                        $subtotal = $subtotal + bcdiv(($selling_rate),1,2);
                        
                        
                        $product_detail = $this->user_model->get_ordered_product_detail($cart->product_id);
                        $cutting_price = $cart->qty *$cart_items->selling_rate;
                        $total_cutting_price += $cutting_price;
                        $total_savings = $total_cutting_price - $subtotal;
                        $inclusive_tax = $selling_rate - ($selling_rate * (100/ (100 + $product_detail->product_tax)));
                        $total_tax += $inclusive_tax;

                        $total_price =  $selling_rate*$cart->qty;

                        $offer_apply = $product_detail->offer_upto;
                        $item_array = array(
                            'product_id' => $product_detail->product_id,
                            'qty' => $cart->qty,
                            'price_per_unit' =>bcdiv($product_detail->selling_rate, 1, 2),
                            'mrp' => $product_detail->mrp,
                            'total_price' => bcdiv($selling_rate, 1, 2),
                            'tax_value' =>  bcdiv($product_detail->product_tax, 1, 2),
                            'offer_applied' =>$offer_apply,
                            'discount_type' =>$offer_type_new,
                        );

                        array_push($item_data,$item_array);

                    
                }
                    $address = $this->db->get_where('customers_address', array('id'=>$aid))->row();
                    $cus_email = $this->db->get_where('customers', array('id'=>$address->customer_id))->row();
                    $delivery_charge =  delivery_charge($subtotal);
                    $data = array(
                        'orderid'    => '',
                        'user_id'    => $user_id,
                        'address_id'    => $aid,
                        'status'    => '1',
                        'total_value'    =>  bcdiv($subtotal, 1, 2),
                        'payment_method' => 'Razorpay',
                        'payment_mode' => 'Online',
                        'total_savings' => bcdiv($total_savings, 1, 2),
                        'tax' => bcdiv($total_tax,1,2),
                        'booking_name' => $address->contact_person_name,
                        'booking_contact' => $address->contact,
                        'direction' => $address->landmark,
                        'delivery_charges'=>$delivery_charge,
                        'state'    =>$address->state,
                        'city'    =>$address->city,
                        'pincode'    =>$address->pincode,
                        'address_line_1'    =>$address->address_line_1,
                        'address_line_2'    =>$address->address_line_2,
                        'address_line_3'    =>$address->address_line_3,
                        'apartment_name'    =>$address->apartment_name,
                        'floor'              =>$address->floor,
                        'email'           =>$cus_email->email,
                        'country'         =>'India',
                    );
                    $proFlg=FALSE;
                    if($this->db->insert('orders', $data))
                    {
                        $proFlg=TRUE;
                    }
                    if($proFlg)
                   {
                    $insert_id = $this->db->insert_id();
                    $orderIds= $insert_id;
                    $logdata1 = array('order_id'=>$insert_id,'status_id'=>'1');
                    $this->db->insert('order_status_log', $logdata1);
                    $date = strtotime("now");
                    $mon=date('M', $date);
                    $num_padded = sprintf("%05d", $insert_id);
                    $code="PRA".strtoupper($mon).$num_padded;
                    $oid['orderid'] = $code;
                    $this->user_model->Update('orders',$oid,['id'=>$insert_id]);

                    foreach( $item_data as $itm ):
                        $itm['order_id'] = $insert_id;
                        $product_id =  $itm['product_id'];
                        $this->db->insert('order_items', $itm);
                        $itm_id = $this->db->insert_id();
                    endforeach;
                }
                if($proFlg)
                {
                 $razorpay_data = $this->db->select('key_id, key_secret')->get_where('settings', array('id'=>'1'))->row();
                 $payable_final_amt = $subtotal+$delivery_charge;
                 if( $payable_final_amt > 0 ):
                     $api = new Api($razorpay_data->key_id, $razorpay_data->key_secret);
                     $orderData = [
                         'receipt'         => $code,
                         'amount'          => $payable_final_amt*100, 
                         'currency'        => 'INR',
                         'payment_capture' => 1 
                     ];
                     $razorpayOrder = $api->order->create($orderData);
                     $response = json_encode(
                         array(
                             'secret_key'=>$razorpay_data->key_id,
                             'order_id_razor' => $razorpayOrder['id'],
                             'total' => $payable_final_amt
                         )
                     );

                     $user_detail = $this->user_model->get_row_data('customers','id',$user_id);
                     $user_name = $user_detail->fname.' '.$user_detail->lname;
                     $user_mobile = $user_detail->mobile;
                     $user_email = $user_detail->email;
                     echo json_encode(array('error'=>false,'data'=>$response,'user_name'=>$user_name,'user_mobile'=>$user_mobile,'user_email'=>$user_email,'order_id'=>$orderIds, 'total'=>$payable_final_amt));
                 else:
                     $data = [
                         'status'=> '17',
                         'payment_method' => 'Coin Payment',
                     ];

                     $this->db->where('id', $insert_id)->update('orders', $data);
                     echo json_encode(array('flag'=>'success'));
                 endif;
                }
                else
                {
                    echo json_encode(array('error'=>'true','msg'=>'Something Went Wrong'));
                }

                break;
            case 'make_cod_payment':
                $user_id = $this->session->user_id ? $this->session->user_id : get_cookie("user_id");
                $user_mobile = $this->session->user_id ? $this->session->user_id : get_cookie("user_id");
                $aid = $this->input->post('aid');
                $cart_data = $this->user_model->get_data1('cart','user_id',$user_mobile);
                $subtotal=$total_cutting_price=$total_savings=$total_tax=0;
                $out_of_stock_data = array();
                $insert_ids = $item_data = array();
                foreach ($cart_data as $cart) {
                    $product_id = $cart->product_id;
                    $cart_items = $this->product_model->product_details($product_id);
                        
                    //calculate selling rate
                  
                    if($cart_items->discount_type=='0') //0->rupee
                    {
                        
                        $selling_rate = ($cart_items->selling_rate - $cart_items->offer_upto)*$cart->qty;
                        
                    }else if($cart_items->discount_type=='1') //1->%
                    {
                        $selling_per = (($cart_items->selling_rate * $cart_items->offer_upto)/100);
                        $selling_rate = ($cart_items->selling_rate - $selling_per)*$cart->qty;
                    }else{
                        $selling_rate = $cart->qty*$cart_items->selling_rate;
                    }
                    
                    $subtotal = $subtotal + bcdiv(($selling_rate),1,2);
                    $product_detail = $this->user_model->get_ordered_product_detail($cart->product_id);
                    $cutting_price = $cart->qty *$cart_items->mrp;
                    $total_cutting_price += $cutting_price;
                    $total_savings = $total_cutting_price - $subtotal;
                    $inclusive_tax = $selling_rate - ($selling_rate * (100/ (100 + $product_detail->product_tax)));
                    $total_tax += $inclusive_tax;
                    $total_price =  $selling_rate*$cart->qty;
                    
                    $offer_apply = $product_detail->offer_upto;
                    $offer_type_new = $product_detail->discount_type ? $product_detail->discount_type : 1;
    
                    //multi buy deal logic

                        $item_array = array(
                            'product_id' => $product_detail->product_id,
                            'qty' => $cart->qty,
                            'price_per_unit' =>bcdiv($product_detail->selling_rate, 1, 2),
                            'mrp' => $product_detail->mrp,
                            'total_price' => bcdiv($selling_rate, 1, 2),
                            'tax_value' =>  bcdiv($product_detail->product_tax, 1, 2),
                            'offer_applied' =>$offer_apply,
                            'discount_type' =>$offer_type_new,
                        );
                        array_push($item_data,$item_array);
 
                }
              

                $address = $this->db->get_where('customers_address', array('id'=>$aid))->row();
                $cus_email = $this->db->get_where('customers', array('id'=>$address->customer_id))->row();
                $delivery_charge =  delivery_charge($subtotal);
                    $this->user_model->delete_data1('cart','user_id',$user_mobile);

                    $data = array(
                        'orderid'    => '',
                        'user_id'    => $user_id,
                        'address_id'    => $aid,
                        'status'    => '1',
                        'total_value'    =>  bcdiv($subtotal, 1, 2),
                        'payment_method' => 'Cod Payment',
                        'payment_mode' => 'Cod Payment',
                        'total_savings' => bcdiv($total_savings, 1, 2),
                        'tax' => bcdiv($total_tax,1,2),
                        'booking_name' => $address->contact_person_name,
                        'booking_contact' => $address->contact,
                        'direction' => $address->landmark,
                        'delivery_charges'=>$delivery_charge,
                        'state'    =>$address->state,
                        'city'    =>$address->city,
                        'pincode'    =>$address->pincode,
                        'address_line_1'    =>$address->address_line_1,
                        'address_line_2'    =>$address->address_line_2,
                        'address_line_3'    =>$address->address_line_3,
                        'apartment_name'    =>$address->apartment_name,
                        'floor'              =>$address->floor,
                        'email'           =>$cus_email->email,
                        'country'         =>'India',
                    );
                    $proFlg=FALSE;
                    if($this->db->insert('orders', $data))
                    {
                        $proFlg=TRUE;
                    }
                   
                   if($proFlg)
                   {
                    $insert_id = $this->db->insert_id();
                    $orderIds= $insert_id;
                    $logdata1 = array('order_id'=>$insert_id,'status_id'=>'1');
                    $this->db->insert('order_status_log', $logdata1);
                    $date = strtotime("now");
                    $mon=date('M', $date);
                    $num_padded = sprintf("%05d", $insert_id);
                    $code="PRA".strtoupper($mon).$num_padded;
                    $oid['orderid'] = $code;
                    $this->user_model->Update('orders',$oid,['id'=>$insert_id]);

                    foreach( $item_data as $itm ):
                        $itm['order_id'] = $insert_id;
                        $this->db->insert('order_items', $itm);
                    endforeach;
                   }
                   if($proFlg)
                   {
                    $data = [
                        'status'=> '17',
                        'payment_method'=>'Cod Payment',
                    ];
                    if($this->db->where_in('id',$orderIds)->update('orders',$data))
                    {
                        $this->user_model->delete_data1('cart','user_id',$address->customer_id);
                        echo json_encode(array('flag'=>'success'));
                    }
                       
                   }
                   else
                   {
                       echo json_encode(array('error'=>'true','msg'=>'Something Went Wrong'));
                   }
                
                break;
            case 'repayment':
                $user_id = $this->session->user_id ? $this->session->user_id : get_cookie('user_id');
                $order_id = _decode($this->input->post('order_id'));
                $order = $this->db->select('total_value')->get_where('orders', array('id'=>$order_id))->row();
                $total_value = $order->total_value;

                $pay_options = $this->db->select('SUM(coupon_value) as coupon_value')->get_where('order_coupons', array('order_id'=>$order_id))->row();
                if( $pay_options ):
                    $total_value = $total_value - $pay_options->coupon_value; 
                endif;
                $shop_id = '6';

//                $razorpay_data = $this->db->select('key_id, key_secret')->get_where('shops', array('id'=>$shop_id))->row();

//                // razorpay api 
//                $api = new Api($razorpay_data->key_id, $razorpay_data->key_secret);
//                $orderData = [
//                    'receipt'         => $order_id,
//                    'amount'          => $total_value*100, // 2000 rupees in paise
//                    'currency'        => 'INR',
//                    'payment_capture' => 1 // auto capture
//                ];
//                $razorpayOrder = $api->order->create($orderData);
//                // end razorpay api

                $response = json_encode(
                    array(
//                        'secret_key'=>$razorpay_data->key_id,
//                        'order_id_razor' => $razorpayOrder['id'],
                        'total' => $total_value
                    )
                );
                $user_detail = $this->user_model->get_row_data('customers','id',$user_id);
                $user_name = $user_detail->fname.' '.$user_detail->lname;
                $user_mobile = $user_detail->mobile;
                $user_email = $user_detail->email;

                echo json_encode(array('data'=>$response,'user_name'=>$user_name,'user_mobile'=>$user_mobile,'user_email'=>$user_email,'order_id'=>$order_id));
                break;
                case 'verify_payment':

                    $razorpay_data = $this->db->select('key_id, key_secret')->get_where('settings', array('id'=>'1'))->row();
    
                    $razorpay_payment_id = $this->input->post('razorpay_payment_id');
                    $razorpay_order_id = $this->input->post('razorpay_order_id');
                    $razorpay_signature = $this->input->post('razorpay_signature');
                    $order_idrazor = $this->input->post('order_idrazor');
                     $post = [
                        'order_idrazor'=>$order_idrazor,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_payment_id' => $razorpay_payment_id,
                        'razorpay_signature' => $razorpay_signature,
                        'shopid' => '6',
                    ];
                    $success = 'true';
                    $api = new Api($razorpay_data->key_id, $razorpay_data->key_secret);
                    try{
                        $api->utility->verifyPaymentSignature($post);
                    }catch(SignatureVerificationError $e){
                        $success = 'Razorpay Error : ' . $e->getMessage();
                    }
                    echo $success;
                    break;
                case 'update_order_status':
    
                    $payment_method = $this->input->post('payment_method');
                    $payment_id = $this->input->post('payment_id');
                    $signature = $this->input->post('signature');
                    $razorpay_ord_id = $this->input->post('razorpay_ord_id');
                    $order_id = $this->input->post('order_id');
                    
                    $data = [
                        'status'=> '17',
                        'razorpay_order_id' => $razorpay_ord_id,
                        'razorpay_payment_id' => $payment_id,
                        'razorpay_signature' => $signature,
                        'payment_method' => $payment_method,
                    ];
                    
                    //update inventory
                    if($this->db->where('id',$order_id)->update('orders',$data))
                    {
                        // Log Insertion
                        $status_id = '17';
                        $logdata = array('order_id' => $order_id, 'status_id' => $status_id);
                        $this->db->insert('order_status_log', $logdata);
                        $rs = $this->user_model->get_user_id($order_id[0]);
                        $this->user_model->delete_data1('cart','user_id',$rs->id);
                        
                        echo "success";
                    }
                    else
                    {
                        echo "failed";
                    }
                    break;
            case 'user_profile':
                if($this->session->userdata('logged_in'))
                {
                    $user_id = $this->session->userdata('user_id');
                }
                else
                {
                    $user_id = get_cookie("user_id");
                }
                $data['user_details'] = $this->user_model->get_row_data('customers','id',$user_id);
                $this->load->view('user/my_profile',$data);
            break;
            
            case 'delievery_address':
                $user_id = $this->session->user_id ? $this->session->user_id : get_cookie("user_id");
                $data['addresses'] = $this->user_model->get_data1('customers_address','customer_id',$user_id);
                $data['edit_addr_url'] = base_url().'user/users/edit_address/';
                $this->load->view('pages/delievery_address',$data);
            break;
            case 'order_details':
                $oid = $this->uri->segment(2);
                $data['title'] = 'Order Details';
                $data['order_details'] = $this->user_model->order_details($oid);
                $page = 'user/order_details';
                $this->header_and_footer($page, $data);
            break;
            case 'delete_cart':
                $cart_id = $this->input->post('cart_id');
                if($this->home_model->delete_data1('cart','id',$cart_id))
                {
                    echo "success";
                }
            break;
        }
    }


    public function checkout_cart()
    {
        $data['coupon_url'] = base_url('checkout/coupon');
        $this->load->view('pages/checkout_cart', $data);
    }

    public function coupon()
    {
        // discount type 0 for fixed and 1 for percentage
        $shop_id = 6;
        $this->db->select('*')
            ->from('coupons_and_offers')
            ->where('coupan_or_offer', 0)
            ->where("'".date('Y-m-d')."' BETWEEN start_date AND expiry_date", NULL)
            ->where('active', 1);
        if( @$_GET['code'] ):
            $this->db->where('id', _decode($_GET['code']));
            $result = $this->db->get()->row();
            echo json_encode($result);
            die();
        endif;
        $this->data['rows'] = $this->db->get()->result();
        $this->load->view('coupon', $this->data);
    }

}
