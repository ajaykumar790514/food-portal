<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model
{
    public function get_category()
    {
        $query = $this->db->get_where('category', ['is_parent' => '0','is_deleted'=>'NOT_DELETED', 'active'=>1,'header_type'=>'YES']);
        return $query->result();
    }
    
    public function get_categoryById($id)
    {
        $query = $this->db->get_where('category', ['is_parent' => $id,'is_deleted'=>'NOT_DELETED', 'active'=>1]);
        return $query->result();
    }
 
    public function get_random_category()
    {
        $query = $this->db->order_by('rand()')->limit(5,0)->get_where('category', ['is_parent' => '0','is_deleted'=>'NOT_DELETED','active'=>1]);
        // $query = $this->db->limit(5,0)->get_where('category', ['is_parent' => '0','is_deleted'=>'NOT_DELETED']);
        return $query->result();
    }
    
    public function get_subcategory()
    {
        $query = $this->db->get_where('category', ['is_parent !=' => '0','is_deleted'=>'NOT_DELETED', 'active'=>1,'header_type'=>'YES']);
        return $query->result();
    }
	public function sub_categories_by_id($id)
	{
		$this->db->select('t1.*')
        ->from('category t1')
		->where(['t1.is_deleted'=>'NOT_DELETED','t1.is_parent' =>$id]);
		return $this->db->get()->result();

	}
    public function get_map_products($pid)
    {
        $query = $this->db

        ->select('t1.id as pm_id,t2.pro_name as product_name,t2.pro_code,t2.id as pid')
        ->from('products_mapping t1')                                         
        ->join('products t2', 't2.id = t1.map_pro_id','left')                      
        ->where('t1.pro_id' , $pid)
        ->get();
        return $query->result();
    }

    public function product_detail($pro_id){
        return $this->db->select('si.*')
            ->from('products si')
            ->where('si.id', $pro_id)
            ->get()->row();
    }
}