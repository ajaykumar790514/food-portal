<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function fetch_city($sid)
    {
        $data = $this->db->get_where('cities',['state_id' => $sid , 'is_deleted' => 'NOT_DELETED'])->result();
        echo "<option value=''>Select City</option>";
        foreach($data as $val)
        {
            echo "<option value='" . $val->id . "'>" . $val->name . "</option>";
        }
    }
    public function fetch_state($cid)
    {
        $data = $this->db->get_where('states',['country_id' => $cid])->result();
        echo "<option value=''>Select County</option>";
        foreach($data as $val)
        {
            echo "<option value='" . $val->name . "'>" . $val->name . "</option>";
        }
    }
    public function orders($uid,$shop_id)
    {
        return $this->db->select("t1.id as oid,t1.house_no,t1.address_line_2,t1.address_line_3,t1.pincode,t1.city,t1.country,t1.email, t1.added, t1.total_value, t1.orderid, t1.status, t1.updated,t1.added as order_date, SUM(t2.qty) as item_qty, t3.name as status_name, t3.id as status_id")
            ->from('orders as t1')
            ->join('order_items t2', 't2.order_id = t1.id', 'left')
            ->join('order_status_master t3', 't3.id = t1.status','left')
            ->where('t1.user_id', $uid)
            ->where('t1.shop_id', $shop_id)
            ->where('t1.status !=','1')
            ->group_by('t1.id')
            ->order_by('t1.added', 'DESC')
            ->get()->result();
    }
    
    
    
    
    public function order_details($oid)
    {
        $order = $this->db
        ->select('t1.id as oid,t1.*,t1.added as order_date,t3.name as status_name, t6.name, t6.mobile, t7.address_line_1, t7.contact_person_name, t7.mobile, t6.email as cust_email, db.full_name as delivery_boy_name, db.contact_number as delivery_boy_contact,t2.price_per_unit,t2.total_price,t2.qty as item_qty,t2.product_id as ProductID,t1.delivery_charges')
        ->from('orders t1')
        ->join('order_items t2', 't2.order_id = t1.id','left')
        ->join('order_status_master t3', 't3.id = t1.status','left')
        ->join('customers t6', 't6.id = t1.user_id','left')
        ->join('customers_address t7', 't7.id = t1.address_id','left')
        ->join('order_assign_deliver osd', 'osd.order_id = t1.id', 'left')
        ->join('delivery_boys db', 'db.id = osd.delivery_boy_id', 'left')
        ->where('t1.id', $oid)
        ->get()->row();

        $order_items = $this->db->select('t2.qty as item_qty, t2.*, t4.id as product_id, t4.parent_cat_id, t1.is_parent, t4.name as product_name, t4.unit_value, t4.unit_type, t5.img,t4.url as pro_url,t4.flag,t2.id as item_id')
            ->from('order_items t2')
            ->join('shops_inventory si', 'si.id = t2.inventory_id', 'left')
            ->join('products_subcategory t4', 't4.id = si.product_id','left')
            ->join('products_category t1', 't1.id = t4.parent_cat_id', 'left')
            ->join('products_photo t5', 't5.item_id = t4.id AND t5.is_cover="1" ','left')
            ->where('order_id', $oid)
            ->where('t4.is_deleted', 'NOT_DELETED')
            //->where('t5.is_cover', 1)
            ->get()->result();
        //$offer_pay = $this->db->get_where('order_coupons', array('order_id'=>$oid))->result();
		$offer_pay=array();
        return array(
            'order' => $order,
            'order_items' => $order_items,
            'offers' => $offer_pay
        );
    }
    
    //copied from admin panel both below functions techfi
    	public function invoice_details($oid)
    {
        $query = $this->db
        ->select('t1.id as oid,t1.*,t1.added as order_date,t1.tax as order_tax,t2.qty as item_qty,t2.purchase_rate,t2.tax_value,t3.name as status_name,t4.id as product_id,t4.name as product_name,t4.unit_value,t4.unit_type,t5.img,t6.fname,t6.lname,t6.mobile,t6.email as cust_email,t7.address as cust_address,t7.contact_name,t7.contact as cust_contact,t8.*,t9.name as city_name,t10.name state_name,t1.address_line_2 as address2,t1.address_line_3 as address3,t1.house_no as cust_house_no,t1.state as cust_state,t1.city as cust_city,t1.pincode as cust_pincode,t1.remark as instructions,t6.id as customer_id')
        ->from('orders t1')
        ->join('order_items t2', 't2.order_id = t1.id','left')        
        ->join('order_status_master t3', 't3.id = t1.status','left')        
        ->join('products_subcategory t4', 't4.id = t2.product_id','left')        
		->join('products_photo t5', 't5.item_id = t4.id','left')  
		->join('customers t6', 't6.id = t1.user_id','left')  
		->join('customers_address t7', 't7.id = t1.address_id','left')  
		->join('shops t8', 't8.id = t1.shop_id','left')  
		->join('cities t9', 't9.id = t8.city','left')  
		->join('states t10', 't10.id = t8.state','left')  
        ->where(['t4.is_deleted' => 'NOT_DELETED','t1.id'=>$oid,'t5.is_cover' =>'1'])  
		->get();   
		return $query->row();
    }


public function invoice_loop_details($oid)
    {
        $query = $this->db
        ->select('t1.id as oid,t1.*,t1.added as order_date,t1.tax as order_tax,t2.qty as item_qty,t2.purchase_rate,t2.price_per_unit,t2.tax_value,t3.name as status_name,t4.id as product_id,t4.name as product_name,t4.unit_value,t4.unit_type,t2.discount_type,t2.offer_applied,t2.total_price,t5.name as flavour')
        ->from('orders t1')
        ->join('order_items t2', 't2.order_id = t1.id','left')        
        ->join('order_status_master t3', 't3.id = t1.status','left')        
        ->join('products_subcategory t4', 't4.id = t2.product_id','left')  
        ->join('flavour_master t5', 't5.id = t4.flavour_id','left') 
        ->where(['t4.is_deleted' => 'NOT_DELETED','t1.id'=>$oid])  
		->get();   
		return $query->result();
    }
    
    public function get_address_by_id($id)
    {
        $query = $this->db
        ->select('t1.*,t1.id as aid')
        ->from('customers_address t1')       
        ->where(['t1.id'=>$id])  
		->get();   
		return $query->row();
    }
    public function get_ordered_product_detail($pid)
    {
        $query = $this->db
        ->select('t1.id as product_id,t1.*,t2.offer_upto,t2.discount_type,t1.pro_tax as product_tax')
        ->from('products t1')
        ->join('shops_coupons_offers t2', 't2.product_id =t1.id','left')   
        ->where(['t1.id'=>$pid])  
		->get();   
		return $query->row();
    }
    public function get_wishlist_data($user_id)
    {
        $query = $this->db
        ->select('t1.*,t1.id as wid,t2.mrp,t2.selling_rate,t2.qty as product_qty, t3.offer_upto, t3.discount_type, t4.*, t4.id as product_id, t4.name as product_name, t4.active as product_status, t5.thumbnail, t6.is_featured, t9.is_parent, t4.parent_cat_id')
        ->from('wishlist t1')
        ->join('products_subcategory t4', 't4.id = t1.product_id','left')
        ->join('products_category t9', 't9.id=t4.parent_cat_id','left')
        ->join('shops_inventory t2', 't2.product_id =t4.id AND t2.is_deleted="NOT_DELETED" AND t2.status="1"','left')
        ->join('shops_coupons_offers t3', 't3.product_id =t4.id','left')         
		->join('products_photo t5', 't5.item_id = t4.id','left')  
        ->join('product_flags t6', 't4.id=t6.product_id','left')
        ->where(['t1.user_id'=>$user_id,'t5.is_cover' =>'1'])  
		->get();   
		return $query->result();
    }
    public function check_user_existence($user_mobile)
	{
		$query = $this->db->get_where('customers', ['mobile' => $user_mobile]);
		return ($query->num_rows()>0)?true:false;
	}
    public function check_product_stock($product_id,$qty)
	{
        $query = $this->db
        ->select('t1.id as inventory_id, t1.product_id, t1.qty, t2.name as product_name')
        ->from('shops_inventory t1') 
        ->join('products_subcategory t2', 't2.id =t1.product_id','left')
        ->where(['t1.id' => $product_id,'qty <' => $qty])  
		->get();   
		return $query->result();
    }
    
    //by ajay for reset password
    
    public function get_user_by_email($email)
    {
        return $this->db->select("t1.*")
            ->from('customers as t1')
            ->where('t1.email', $email)
            ->get()->row();
    }
    
    function update_reset_token($id,$token,$email) {
        $data = array(
           'customer_id'=>$id,
           'email'=>$email,
           'unique_code'=>$token,

        );
        $this->db->where(['customer_id'=>$id]);

         if($this->db->update('customers_pass_forgot',$data)) {

             return true;

         }

         return false;

    }
    public function checkpass($token)
    {
        return $this->db->select("t1.*")
            ->from('customers_pass_forgot as t1')
            ->where('t1.unique_code', $token)
            ->get()->row();
    }
    public function checkemail($email)
    {
        return $this->db->select("t1.*")
            ->from('customers_pass_forgot as t1')
            ->where('t1.email', $email)
            ->get()->row();
    }
    public function customer_update_password($id,$data)
    {
        return $this->db->where('id', $id)->update('customers', $data);
    }
    public function fetch_shop_inventory($oid)
	{
        $query = $this->db
        ->select('t1.*')
        ->from('order_items t1') 
        ->where_in('t1.order_id',$oid)  
		->get();   
		//echo $this->db->last_query();
		return $query->result();
    }
    public function get_user_id($oid)
	{
        $query = $this->db
        ->select('t2.*')
        ->from('orders t1') 
        ->join('customers t2', 't2.id =t1.user_id','left')
        ->where(['t1.id' => $oid])  
		->get();   
		return $query->row();
    }
    public function getAddress($id)
	{
        $query = $this->db
        ->select('t2.*,t1.*,t3.name as state_name,t4.name as city_name')
        ->from('customers_address t1') 
        ->join('customers t2', 't1.customer_id =t2.id','left')
        ->join('states t3', 't3.id =t1.state','left')
        ->join('cities t4', 't4.id =t1.city','left')
        ->where(['t1.customer_id' => $id])  
		->get();   
		return $query->result();
    }
    
    //end section
}