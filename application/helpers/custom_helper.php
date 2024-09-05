<?php

function is_logged_in()
{
    $CI = &get_instance();
    if( $CI->session->userdata('logged_in') )
    {
        return true;
    }elseif( get_cookie("logged_in") ){
        return true;
    }
    return false;
}

function cart_data()
{
    $CI = &get_instance();
    $cart_data = get_cookie('shopping_cart') ? json_decode(get_cookie('shopping_cart')) : array();
    if( is_logged_in() ):
        $user_id = $CI->session->user_id ? $CI->session->user_id : get_cookie("user_id");
        $cart_data = $CI->home_model->get_data1('cart','user_id',$user_id);
    endif;
    foreach ($cart_data as $key => $value) {
        $product_id =  $value->product_id;
        $existpro = $CI->home_model->getRow('products',['id'=>@$product_id,'is_deleted'=>'DELETED']);
        $CI->home_model->delete_data1('cart','product_id',@$existpro->id);
    }
    return $cart_data ? $cart_data : array();
}

function wishlist_data()
{
    $CI = &get_instance();
    $wishlist_data = get_cookie('wishlist_cart') ? json_decode(get_cookie('wishlist_cart')) : array();
    if( is_logged_in() ):
        $user_id = $CI->session->user_id ? $CI->session->user_id : get_cookie("user_id");
        $wishlist_data = $CI->home_model->get_data1('wishlist','user_id',$user_id);
    endif;
    foreach ($wishlist_data as $key => $value) {
        $product_id =  $value->product_id;
        $existpro = $CI->home_model->getRow('products',['id'=>@$product_id,'is_deleted'=>'DELETED']);
        $CI->home_model->delete_data1('wishlist','product_id',@$existpro->id);
    }
    return $wishlist_data ? $wishlist_data : array();
}

function cart_price()
{
    $CI = &get_instance();
    $cart_data = get_cookie('shopping_cart') ? json_decode(get_cookie('shopping_cart')) : array();
    if( is_logged_in() ):
        $user_id = $CI->session->user_id ? $CI->session->user_id : get_cookie("user_id");
        $cart_data = $CI->home_model->get_data1('cart','user_id',$user_id);
        $address = $CI->user_model->get_data1('customers_address','customer_id',$user_id);
    endif;
    
  
    $total_price = 0; // Initialize total price
    foreach ($cart_data as $key => $value) {
        $product_id =  $value->product_id;
          // Delete the product from the cart if it is marked as deleted
          $existpro = $CI->home_model->getRow('products',['id'=>@$product_id,'is_deleted'=>'DELETED']);
          if($existpro) {
              $CI->home_model->delete_data1('cart','product_id',@$existpro->id);
          }
        $pro = $CI->home_model->getRow('products',['id'=>@$product_id,'is_deleted'=>'NOT_DELETED']);
        $offer= $CI->home_model->getRow('shops_coupons_offers',['product_id'=>@$product_id,'is_deleted'=>'NOT_DELETED']);
        if($pro) { 
            if(@$offer->discount_type == 1) {
                $price_per = ($value->qty*$pro->selling_rate * @$offer->offer_upto)/100;
                $price = ($value->qty*$pro->selling_rate) - $price_per;
            } elseif(@$offer->discount_type == 0) {
               $price = ($value->qty*$pro->selling_rate) - @$offer->offer_upto;
            } else {
                $price = $pro->selling_rate*$value->qty;
            }
            $total_price += $price; // Add price to total price
        }
      
    }
    if(!empty($cart_data)){
    return number_format($total_price+delivery_charge($total_price),2);
    }else{
        return 0.00;
    }
}

function validateOffer($startDateTime, $endDateTime) {
    $currentDateTime = new DateTime();
    $startDateTimeObj = new DateTime($startDateTime);
    $endDateTimeObj = new DateTime($endDateTime);

    if ($currentDateTime < $startDateTimeObj) {
        $remainingTime = $startDateTimeObj->diff($currentDateTime);
        return "<p>Offer starts in: " . formatInterval($remainingTime)."</p>";
    } elseif ($currentDateTime > $endDateTimeObj) {
        return "<p>Offer has expired.</p>";
    } else {
        $remainingTime = $currentDateTime->diff($endDateTimeObj);
        return "<p>Offer ends in: " . formatInterval($remainingTime)."</p>";
    }
}

function formatInterval($interval) {
    $days = $interval->d;
    $hours = $interval->h;
    $minutes = $interval->i;
    $seconds = $interval->s;
    
    $format = '';
    if ($days > 0) {
        $format .= "$days days ";
    }
    if ($hours > 0) {
        $format .= "$hours hours ";
    }
    if ($minutes > 0) {
        $format .= "$minutes minutes ";
    }
    if ($seconds > 0) {
        $format .= "$seconds seconds ";
    }
    
    return trim($format);
}

if (!function_exists('delivery_charge')) {
    function delivery_charge($amount)
    {
        $CI = &get_instance();
        $return = false;
        $query =  "SELECT * FROM `delivery_charges` WHERE `min` <= $amount AND `max` >= $amount AND active = 1 AND  is_deleted = 'NOT_DELETED'";
        if ($get = $CI->db->query($query)->row()) {
            $return = $get->price;
        }else
        {
            $return='0.00';
        }
        return $return;
    }
}

if (!function_exists('date_format_func')) {
    function date_format_func($date)
    {
            if($date == NULL)
        	{
        		return "";
        	}
            else if($date == '0000-00-00')
            {
                return "";
            }
        	else
        	{
        		return date('d-m-Y',strtotime($date));
		    }
    }
}

if (!function_exists('sendMail'))
{
   function sendMail($message,$email,$subject,$attatchment="",$filename="")
    {
        
          $ch = curl_init();
          $fields = array( 'message'=>$message, 'email'=>$email,'subject'=>$subject,'attatchment'=>$attatchment,'filename'=>$filename);
          $postvars = '';
          foreach($fields as $key=>$value) {
            $postvars .= $key . "=" . $value . "&";
          }
          $url = "https://www.techfizone.com/techfiprojects/email_master/sheffieldsvapeoutlet/mailApi.php";
          curl_setopt($ch,CURLOPT_URL,$url);
          curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
          curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
          curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
          curl_setopt($ch,CURLOPT_TIMEOUT, 20);
          $response = curl_exec($ch);
    
          curl_close ($ch);

        //use curl for mail sending
        
    }    
}

if (!function_exists('number_to_word')) {
    function number_to_word($number){
        $CI = &get_instance();
        $shop_id = '6';
        $shop_detail = $CI->home_model->get_shop_detail($shop_id);

        $no = (int)floor($number);
        $point = (int)round(($number - $no) * 100);
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
         '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
         '7' => 'seven', '8' => 'eight', '9' => 'nine',
         '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
         '13' => 'thirteen', '14' => 'fourteen',
         '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
         '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
         '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
         '60' => 'sixty', '70' => 'seventy',
         '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
          $divider = ($i == 2) ? 10 : 100;
          $number = floor($no % $divider);
          $no = floor($no / $divider);
          $i += ($divider == 10) ? 1 : 2;
     
     
          if ($number) {
             $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
             $hundred = ($counter == 1 && $str[0]) ? null : null;
             $str [] = ($number < 21) ? $words[$number] .
                 " " . $digits[$counter] . $plural . " " . $hundred
                 :
                 $words[floor($number / 10) * 10]
                 . " " . $words[$number % 10] . " "
                 . $digits[$counter] . $plural . " " . $hundred;
          } else $str[] = null;
       }
       $str = array_reverse($str);
       $result = implode('', $str);
     
     
       if ($point > 20) {
         $points = ($point) ?
           "" . $words[floor($point / 10) * 10] . " " . 
               $words[$point = $point % 10] : ''; 
       } else {
           $points = $words[$point];
       }
       if($points != ''){        
           echo ucwords($result .$shop_detail->currency_name. " and " . $points ." ".$shop_detail->currency_fraction. " Only");
       } else {
     
           echo ucwords($result .$shop_detail->currency_name. " Only");
       }
     
     }
}

function _prx( $data )
{
    return '<pre>'.print_r($data, true).'</pre>';
}

function _encode($data){
    return $data;
    return base64_encode(urlencode($data));
}
function _decode($data){
    return $data;
    return $data ? urldecode(base64_decode($data)) : '';
}


function _round($data, $place=2)
{
    return $data ? round($data, $place) : $data;
}

function uk_date($date){
    // Assuming you have a datetime string in IST
    $indianDatetimeString = $date;  // Replace this with your datetime string
    
    // Create a DateTime object for the Indian datetime
    $indianDatetime = new DateTime($indianDatetimeString, new DateTimeZone('America/Denver'));
    
    // Convert to Europe/London timezone
    $londonTimezone = new DateTimeZone('Europe/London');
    $indianDatetime->setTimezone($londonTimezone);
    
    // Get the result as a string
    $londonDatetimeString = $indianDatetime->format('d-m-Y');
    
   // echo "Indian datetime: " . $indianDatetimeString . PHP_EOL;
   return $londonDatetimeString . PHP_EOL;

}
function uk_time($date){
    // Assuming you have a datetime string in IST
    $indianDatetimeString = $date;  // Replace this with your datetime string
    
    // Create a DateTime object for the Indian datetime
    $indianDatetime = new DateTime($indianDatetimeString, new DateTimeZone('America/Denver'));
    
    // Convert to Europe/London timezone
    $londonTimezone = new DateTimeZone('Europe/London');
    $indianDatetime->setTimezone($londonTimezone);
    
    // Get the result as a string
    $londonDatetimeString = $indianDatetime->format('H:i:s');
    
   // echo "Indian datetime: " . $indianDatetimeString . PHP_EOL;
   //return $londonDatetimeString . PHP_EOL;
    return (@$londonDatetimeString) ? date('h:i A',strtotime($londonDatetimeString)) : ''; 
}
function getOrdinal($number) {
    $suffix = '';
    if (in_array(($number % 100), range(11, 13))) {
        $suffix = 'th';
    } else {
        switch ($number % 10) {
            case 1: $suffix = 'st'; break;
            case 2: $suffix = 'nd'; break;
            case 3: $suffix = 'rd'; break;
            default: $suffix = 'th'; break;
        }
    }

    return $number . $suffix;
}

function getClass($class_id, $group_id) {
    $CI = &get_instance();
    $CI->load->database();
    $query = $CI->db->get_where('class_master', array('id' => $class_id));

    if ($query->num_rows() > 0) {
        $row = $query->row();
        $class_name = $row->name;

        $ordinal_class_id = $class_name;

        return "$ordinal_class_id";
    } else {
        return 'Unknown Class'; 
    }
}

if (!function_exists('displayPhoto')) {
    function displayPhoto($photo_path) {
        if (!empty($photo_path)) {
            return IMGS_URL.$photo_path;
        } else {
            return base_url('assets\images\noimg\new.png');
        }
    }
}

if (!function_exists('round_price')) {
    function round_price($price) {
        $rounded_price = round($price);

        return $rounded_price;
    }
}

if (!function_exists('delivery_charge')) {
    function delivery_charge($amount)
    {
        $CI = &get_instance();
        $return = false;
        $query =  "SELECT * FROM `delivery_charges` WHERE `min` <= $amount AND `max` >= $amount AND active = 1 AND  is_deleted = 'NOT_DELETED'";
        if ($get = $CI->db->query($query)->row()) {
            $return = $get->price;
        }else
        {
            $return='0.00';
        }
        return $return;
    }
}

if (!function_exists('findreturndays')) {
    function findreturndays($product_id) {
        $CI = &get_instance();
        $allCat = $CI->home_model->get_data1('cat_pro_maps', 'pro_id', $product_id);
        $totalDays = 0;
        foreach ($allCat as $cat) {
            $category = $CI->home_model->get_row_data('products_category', 'id', @$cat->cat_id);
            if ($category) {
               if($category->level==1)
               {
               $catexist = $CI->db->where('cat_id',$category->id)->get('cat_return_policy_map');
               if ($catexist && $catexist->num_rows() > 0) {
                // Check if there are rows in the result set
                  $catRow = $catexist->row();
                  $totalDays = $catRow->days;
                 }
                // if($catexist){
                //   $totalDays = $catexist->days;
                // }              
              }elseif($category->level==2)
              {
              $catexist = $CI->db->where('cat_id',$category->id)->get('cat_return_policy_map');
              if ($catexist && $catexist->num_rows() > 0) {
                // Check if there are rows in the result set
                  $catRow = $catexist->row();
                  $totalDays = $catRow->days;
                 }
            //    if($catexist){
            //      $totalDays = $catexist->days;
            //    }              
             }elseif($category->level==3)
             {
             $catexist = $CI->db->where('cat_id',$category->id)->get('cat_return_policy_map');
             if ($catexist && $catexist->num_rows() > 0) {
                // Check if there are rows in the result set
                  $catRow = $catexist->row();
                  $totalDays = $catRow->days;
                 }
            //   if($catexist){
            //     $totalDays = $catexist->days;
            //   }              
            }
            }
        }
        
        return  $totalDays;
    }
}
