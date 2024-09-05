<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home_model extends CI_Model
{

	 // BY AJAY KUMAR
      /*
     *  Select Records From Table
     */
    public function Select($Table, $Fields = '*', $Where = 1)
    {
        /*
         *  Select Fields
         */
        if ($Fields != '*') {
            $this->db->select($Fields);
        }
        /*
         *  IF Found Any Condition
         */
        if ($Where != 1) {
            $this->db->where($Where);
        }
        /*
         * Select Table
         */
        $query = $this->db->get($Table);

        /*
         * Fetch Records
         */

        return $query->result();
    }
   /*
     * Count No Rows in Table
     */
    public function Counter($Table, $Where = 1)
    {
        $rows = $this->Select($Table, '*', $Where);

        return count($rows);
    }
	function Save($tb,$data){
		if($this->db->insert($tb,$data)){
			return $this->db->insert_id();
		}
		return false; 
	}
    function Update($tb,$data,$cond) {
		$this->db->where($cond);
	 	if($this->db->update($tb,$data)) {
	 		return true;
	 	}
	 	return false;
	}
	public function get_banners($shop_id)
	{
		$query = $this->db->order_by('seq','asc')->select('*')->get_where('home_banners', ['is_deleted' => 'NOT_DELETED', 'shop_id' => $shop_id, 'banner_type' => '1', 'active' => '1']);
		return $query->result();
	}
	public function get_other_banners($shop_id)
	{
		$query = $this->db->order_by('seq','asc')->limit(10,0)->get_where('home_banners', ['is_deleted' => 'NOT_DELETED', 'shop_id' => $shop_id, 'banner_type' => '0', 'active' => '1']);
		return $query->result();
	}
	public function get_top_banners()
	{
		$query = $this->db->order_by('seq','asc')->limit(10,0)->get_where('home_banners', ['is_deleted' => 'NOT_DELETED', 'banner_type' => '1', 'active' => '1']);
		return $query->result();
	}
	public function get_category_header_title()
    {
        $query = $this->db->select('t1.*')
		->from('home_headers t1')
		->where(['t1.type' => '2','t1.active'=>'1','t1.is_deleted' => 'NOT_DELETED']) ;
		return $this->db->get()->result();
    }
	public function get_header_title()
	{
		$query = $this->db->select('t1.*')
		->from('home_headers t1')
		->where(['t1.type' => '1','t1.active'=>'1','t1.is_deleted' => 'NOT_DELETED'])->order_by('t1.seq','asc') ;
		return $this->db->get()->result();
	}
	public function getHeaderCatMap($header_id)
	{
		$query = $this->db->select('t2.*')
		->from('home_headers_mapping t1')
		->join('category t2 ','t2.id=t1.value')
		->where(['t1.header_id'=>$header_id,'t2.active'=>'1','t2.is_deleted' => 'NOT_DELETED']) ;
		return $this->db->get()->result();
	}
	public function get_header_products($header_id)
	{
        $query = $this->db
		->select('t1.id as header_id, t1.*, t3.*, t3.id as product_id, t3.pro_name as product_name, t3.pro_code as product_code,t10.name as cat_name, t11.cat_id as category_id, t8.product_id as wislist_pid,t6.offer_upto, t6.discount_type')
		->from('home_headers_mapping t1')      
		->join('products t3', 't3.id = t1.value','left') 
		->join('shops_coupons_offers t6', 't3.id=t6.product_id','left') 
		->join('wishlist t8', 't8.product_id=t3.id','left')       
        ->join('cat_pro_maps t11', 't11.pro_id=t1.value','left')
        ->join('category t10', 't10.id=t11.cat_id','left')
		->where(['t3.is_deleted'=>'NOT_DELETED','t3.status'=>'1','t1.header_id'=>$header_id])   
		->group_by('t11.pro_id')   
		->limit(10,0);
		return $this->db->get()->result_array();
	}

	function getRow($tb,$data=0) {

		if ($data==0) {
			if($data=$this->db->get($tb)->row()){
				return $data;
			}else {
				return false;
			}
		}elseif(is_array($data)) {
			if($data=$this->db->get_where($tb, $data)){
				return $data->row();
			}else {
				return false;
			}
		}else {
			if($data=$this->db->get_where($tb,array('id'=>$data))){
				return $data->row();
			}else {
				return false;
			}
		}

	}
	function getData($tb, $data = 0, $order = null, $order_by = null, $limit = null, $start = null)
    {

        if ($order != null) {
            if ($order_by != null) {
                $this->db->order_by($order_by, $order);
            } else {
                $this->db->order_by('id', $order);
            }
        }

        if ($limit != null) {
            $this->db->limit($limit, $start);
        }

        if ($data == 0 or $data == null) {
            return $this->db->get($tb)->result();
        }
        if (@$data['search']) {
            $search = $data['search'];
            unset($data['search']);
        }
        return $this->db->get_where($tb, $data)->result();
    }
    public function get_pincode( $pincode){
    	$this->db
    	->select('t1.*')
        ->from('pincodes_criteria t1')     
        ->where(['t1.pincode' => $pincode,'t1.active' => 1,'is_deleted'=>'NOT_DELETED']);
        return $this->db->get()->result();
    }
	public function check_cart_product_existence($user_id, $pid)
	{
		$query = $this->db->get_where('cart', ['user_id' => $user_id, 'product_id' => $pid]);
		return $query->result();
	}
	
public function mobile_exist($mobile)
{
	//echo $mobile;die();
	$this->db->select("*")
	->from('customers')
	->where(['mobile'=>$mobile, 'isActive'=>'1']);

	return $this->db->get()->num_rows();
	
}
function updateRow($mobile,$data ){
	if($this->db->insert('user_otp',$data)){
		return $this->db->insert_id();
	}
	return false; 
}
public function otp_exist($otp)
	{
		$this->db->select("*")
		->from('user_otp')
		->where(['otp'=>$otp]);
	
		return $this->db->get()->num_rows();
		
}
public function getOfferProduct(){
	$this->db
	->from('super_offer a')
		->select('a.*,a.img as thumbnail,b.selling_rate,b.id as product_id,b.pro_name,b.pro_code, c.offer_upto, c.discount_type')
		->join('products b','b.id=a.product_id','left')
		->join('shops_coupons_offers c', 'b.id=c.product_id','left')
		->where(['a.is_deleted'=>'NOT_DELETED','b.is_deleted'=>'NOT_DELETED'])
		->order_by('a.seq','asc');	
	return $this->db->get()->result();
}
public function getProAllId($prourl)
{
	$query = $this->db
	->select('t1.pro_name,t1.id as pro_id,t3.cat_id as category_id,t4.is_parent as sub_cat_id,t4.is_parent')
	->from('products t1')
	->join('cat_pro_maps t3', 't3.pro_id=t1.id','left')
	->join('category t4', 't4.id=t3.cat_id','left')  
	->where(['t1.is_deleted'=>'NOT_DELETED','t1.status'=>'1','t1.url'=>$prourl]) ;
	return $this->db->get()->row();
}


}