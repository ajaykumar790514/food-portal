<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
    //fetch products for product list page
    public function get_products($id,$shop_id,$subcat_id=null,$brand_id=null)
    {
        $this->db
        ->select('t1.*,t1.name as prod_name,t4.img,t4.id as cover_id,t3.mrp,t3.selling_rate,t5.name as cat_name,t3.qty as product_qty,t6.offer_upto,t6.discount_type,t7.is_featured')
        ->from('products_subcategory t1')  
        ->join('shops_inventory t3', 't1.id=t3.product_id AND t3.is_deleted="NOT_DELETED" AND t3.status="1"','left')
        ->join('products_photo t4', 't4.item_id=t1.id AND t4.active="1" AND t4.is_cover="1"','left')
        ->join('products_category t5', 't1.parent_cat_id = t5.id')
        ->join('shops_coupons_offers t6', 't1.id=t6.product_id','left')
		->join('product_flags t7', 't1.id=t7.product_id','left')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.active'=>'1','t1.parent_cat_id' => $id,'t3.shop_id' =>$shop_id]);
        if($subcat_id!='')
        {
            $this->db->where('t1.sub_cat_id', $subcat_id);
        }
        if($brand_id!='')
        {
            $this->db->group_start();
            $this->db->where('t1.brand_id', $brand_id);
            $this->db->group_end();
        }
        $this->db->order_by('t1.added','desc');

        return $this->db->get()->result();
    }
     public function getBundleProduct($id)
    {
        $this->db
        ->select('t2.*,t2.id as prod_id,t1.mrp,t1.amount,t1.qty')
        ->from('bundle_products_mapping t1')  
         ->join('products_subcategory t2', 't2.id=t1.pro_id ','left')
        ->where(['t2.is_deleted' => 'NOT_DELETED','t2.active'=>'1','t1.bundle_id' => $id]);
        return $this->db->get()->result();
    }

    

   //copied from admin panel to get selectable product props based on product id
   public function get_value($id)
    {
          $this->db->select('*, t2.value, t2.id as select_id')
        ->from('product_props t1')
        ->join('product_props_master t3', 't3.id = t1.props_id')
        ->join('product_props_value t2', 't2.id = t1.value_id')
        ->where('t1.is_deleted','NOT_DELETED')
        ->where('t1.product_id',$id)
        ->where('t3.is_selectable','3');
        //$this->db->order_by('t1.value_id','asc');
        //->group_by('t1.value_id');
        $query=$this->db->get();
        //echo $this->db->last_query();
        $result=($query->num_rows() > 0) ? $query->row() : false;
        return $result;
    }
    
    public function get_product_by_id($pid)
    {
        $user_id = $this->session->user_mobile ? $this->session->user_mobile : get_cookie("user_mobile");

        $this->db
        ->select('t1.*,t1.pro_name as prod_name,t4.product_id as wishlist_pid, t5.id as cart_id, IFNULL(t5.qty, 0) as cart_qty, t6.discount_type, t6.offer_upto,t6.offer_associated,t1.id as proid')
        ->from('products t1')       
        ->join('wishlist t4', 't4.product_id = t1.id','left')      
        ->join('cart t5', "t1.id=t5.product_id AND t5.user_id='".$user_id."'",'left')
        ->join('shops_coupons_offers t6', 't6.product_id = t1.id','left')
        ->where(['t1.id' => $pid,'t1.is_deleted'=>'NOT_DELETED','t1.status'=>'1'])
        ->order_by('t1.added','desc');

        return $this->db->get()->row();
    }
    public function get_product_props($id)
	{
		$this->db->select('t2.name as prop_name,t2.id as prop_id,t1.value')
        ->from('product_props t1')
		->join('product_props_master t2', 't2.id = t1.props_id')
		->where('t1.product_id',$id)
		->where('t2.active','1')
        ->where('t2.is_deleted','NOT_DELETED')
		->where('t2.is_selectable', 1);
		return $this->db->get()->result();
		
	}
    public function get_selected_prop_detail($pid)
	{
		$this->db->select('t2.name as prop_name,t2.id as prop_id,t1.product_id,t1.value,t1.id,t1.props_id,t1.id as prod_prop_id')
        ->from('product_props t1')
		->join('product_props_master t2', 't2.id = t1.props_id')
		->where('t1.product_id',$pid)
		->where('t2.is_selectable','1')
		->where('t2.active','1')
		->where('t2.is_deleted','NOT_DELETED');
		return $this->db->get()->result();
		
	}
    public function selected_prop_detail($pid,$prop_val)
	{
		// $this->db->select('t2.name as prop_name,t2.id as prop_id,t1.product_id,t1.value,t1.id,t1.props_id,t1.id as prod_prop_id')
		$this->db->select('t1.product_id')
        ->from('product_props t1')
		->join('product_props_master t2', 't2.id = t1.props_id')
		->where(['t1.product_id'=>$pid])
		->where('t2.is_selectable','1')
		->where('t1.value',$prop_val)
		// ->where('t1.value',$selected_prop_val)
		->where('t2.active','1')
		->where('t2.is_deleted','NOT_DELETED');
		return $this->db->get()->result();
		
	}

    
	//product details for cart items
	public function product_details($pid)
	{
		$query = $this->db
        ->select('t1.*, t4.offer_upto, t4.discount_type, t5.id as cart_id')
        ->from('products t1')
        ->join('shops_coupons_offers t4', 't1.id=t4.product_id','left')
        ->join('cart t5', 't1.id=t5.product_id','left')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.status'=>'1','t1.id' =>$pid])
		->get();
		return $query->row();
	}
    
    public function product_props($pid)
    {
       $query=$this->db->select('product_props_master.name,product_props_value.value,t5.name as flavour')
       ->from('product_props')
       ->join('product_props_master','product_props.props_id=product_props_master.id') 
       ->join('product_props_value','product_props.value_id=product_props_value.id')
       ->join('products_subcategory t4', 't4.id = product_props.product_id','left')  
        ->join('flavour_master t5', 't5.id = t4.flavour_id','left') 
       ->where(['product_props.product_id'=>$pid,'product_props.is_deleted'=>'NOT_DELETED','product_props_master.is_selectable'=>'3'])
       ->get();
       //echo $this->db->last_query();
       if($query)
       {
       $output=$query->result();
       
       return $output;
       }
       else
       {
        return false;
       }
    }

     public function product_props_flavour($pid)
    {       
        $query=$this->db->select('t5.name as flavour')
       ->from('products_subcategory t4')  
        ->join('flavour_master t5', 't5.id = t4.flavour_id','left') 
        ->where(['t4.is_deleted' => 'NOT_DELETED','t4.id'=>$pid]) 
       ->get();  
        return $query->row();
    }

   
 
//t2.is_parent
    public function filter_data($search)
    {
        $this->db
        ->select('t1.*,t1.id as pid,t1.parent_cat_id,t3.id as inventory_id, t4.thumbnail')
        ->from('products_subcategory t1')
        //->join('flavour_master t5','t5.id=t1.flavour_id')
        ->join('shops_inventory t3', 't1.id=t3.product_id AND t3.is_deleted="NOT_DELETED" AND t3.status="1"','left')
        //->join('products_category t2', 't2.id = t1.parent_cat_id AND t2.is_deleted="NOT_DELETED" AND t2.active="1"')  
        ->join('products_photo t4', 't4.item_id = t1.id AND t4.active="1"')  
        ->where(['t1.is_deleted'=>'NOT_DELETED', 't1.active'=>'1'])      
        ->like('t1.name' ,$search,'both')
        ->or_like('t1.search_keywords' ,$search,'both');
        // ->or_like('t2.name' ,$search);

        return $this->db->get()->result_array();
    }
    public function filter_data2($search)
    {
        $this->db
        ->select('t1.id as cid,t1.name as cat_name,t1.is_parent, t1.thumbnail')
        ->from('products_category t1')     
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.is_parent!=' => '0','t1.active'=>1])     
        ->like('t1.name' ,$search,'both');
        

        return $this->db->get()->result_array();
    }
    public function child_categories_by_cat_id($cat_id,$cids,$shop_id)
    {
        $this->db
        ->select('t2.name,t2.id as cat_id')
        ->from('products_subcategory t1')     
         ->join('products_category t2', 't2.id = t1.sub_cat_id')  
         ->join('shops_inventory t3', 't1.id=t3.product_id AND t3.is_deleted="NOT_DELETED" AND t3.status="1"','left')
         ->group_by('t2.id')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t3.shop_id' =>$shop_id]);   
        if(!empty($cids))
		{
			$this->db->where_in('t1.parent_cat_id' , $cids);
		}else{
			$this->db->where(['t1.parent_cat_id' => $cat_id]);
		}
        return $this->db->get()->result();
    }
    public function brands_by_cat_id($cat_id,$cids,$shop_id)
    {
        $this->db
        ->select('t2.name,t2.id as brand_id')
        ->from('products_subcategory t1')     
         ->join('brand_master t2', 't2.id = t1.brand_id')  
         ->join('shops_inventory t3', 't1.id=t3.product_id AND t3.is_deleted="NOT_DELETED" AND t3.status="1"','left')
         ->group_by('t2.id')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t3.shop_id' =>$shop_id]);   
        if(!empty($cids))
		{
			$this->db->where_in('t1.sub_cat_id' , $cids);
		}else{
			$this->db->where(['t1.sub_cat_id' => $cat_id]);
		}
        return $this->db->get()->result();
        
    }
    ////////// props filter zahid
    public function get_props($cat_id)
    {
        $this->db->where('id',$cat_id);
        $out=$this->db->get('products_category')->row();
        if($out->is_parent=='0')
        {
           $res=$this->db->query('select * from products_category_props as t1 INNER JOIN product_props_master as t2 on t2.id = t1.prop_master_id where t2.is_deleted="NOT_DELETED" and t2.active ="1" and t2.is_selectable ="2" and t1.cat_id in (select id from products_category where is_parent='.$cat_id.')');
           return $res->result();
       
        }
        else
        {
        $this->db
        ->select('*')
        ->from('products_category_props t1')   
        ->join('product_props_master t2', 't2.id = t1.prop_master_id')      
        ->where(['t1.cat_id' => $cat_id, 't2.is_deleted' => 'NOT_DELETED', 't2.active' =>1, 't2.is_selectable' =>2]);   
        return $this->db->get()->result();    
        }
    }
    public function get_props_by_cat_id($cat_id)
    {
        $this->db
        ->select('*, t3.id as product_props_id,t3.value_id')
        ->from('products_category_props t1')     
         ->join('product_props_master t2', 't2.id = t1.prop_master_id','left')  
         ->join('product_props t3', 't2.id=t3.props_id','left')
         ->join('product_props_value t4', 't3.value_id=t4.id','left')
         // ->group_by('t2.id')
        ->where(['t1.cat_id' => $cat_id, 't2.is_deleted' => 'NOT_DELETED', 't2.active' =>1, 't2.is_selectable' =>2, 't3.is_deleted'=>'NOT_DELETED','t4.is_deleted'=>'NOT_DELETED']);   
        // ->where(['t1.cat_id' => $cat_id]);
        // echo $this->db->last_query();die();
        return $this->db->get()->result();
        
    }

    public function get_product_map($id)
    {
        $this->db->select('*')
        ->from('products_mapping t1')
        ->where('t1.pro_id',$id);
        return $this->db->get()->result();
        
    }
    public function get_props_id($id)
    {
        $this->db->select('t1.props_id')
        ->from('product_props t1')
        ->where('t1.product_id',$id)
        ->where('t1.is_deleted','NOT_DELETED')
        ->group_by('t1.props_id');
        return $this->db->get()->result();
        
    }
    public function get_props_master_id($id)
    {
        $this->db->select('t1.id, t1.name, t1.display_type')
        ->from('product_props_master t1')
        ->where_in('t1.id',$id)
        ->where('t1.is_deleted','NOT_DELETED')
        ->where('t1.active',1)
        ->where('t1.is_selectable',3);
        return $this->db->get()->result();
        
    }
    public function get_product_selectable_props($product_id)
    {
        $this->db->select('*, t2.value as select_value, t2.id as select_id')
        ->from('product_props t1')
        ->join('product_props_value t2', 't2.id = t1.value_id')
        ->where('t1.is_deleted','NOT_DELETED')
        ->where('t1.product_id',$product_id);
        //$this->db->order_by('t1.value_id','asc');
        //->group_by('t1.value_id');
        $query=$this->db->get();
        //echo $this->db->last_query();
        $result=($query->num_rows() > 0) ? $query->result() : false;
        return $result;
        
    }
    
    ////////// end props filter
	public function product_photos($id)
	{
		return $this->db->where(['active'=>'1','item_id' =>$id])->get('products_photo')->result();

	}
    //product filter functions
    // t5.name as cat_name, 
   function fetch_data($category_pro_id,$pro_id,$id,$cids,$shop_id,$brand,$prop_filter,$sort_by,$limit,$start,$is_count,$isAll)
    {

        $this->db
        // ->select('t1.*, t1.name as prod_name, t4.img,t4.thumbnail, t4.id as cover_id, t3.id as inventory_id, t3.mrp, t3.selling_rate, t3.qty as product_qty, t6.offer_upto, t6.discount_type, t7.is_featured, t8.product_id as wislist_pid, t10.name as flavour_name')
        ->select('t1.*, t1.name as prod_name, t4.img,t4.thumbnail, t4.id as cover_id, t3.id as inventory_id, t3.mrp, t3.selling_rate, t3.qty as product_qty, t6.offer_upto, t6.discount_type,t1.flag as ProductFlag,t1.id as proid')
        ->from('products_subcategory t1')  
        ->join('shops_inventory t3','t1.id=t3.product_id AND t3.is_deleted="NOT_DELETED" AND t3.status="1"','left')
        ->join('products_photo t4', 't4.item_id=t1.id AND t4.active="1" AND t4.is_cover="1"','left')
        //->join('products_category t5', 't1.parent_cat_id = t5.id')
        ->join('shops_coupons_offers t6', 't1.id=t6.product_id','left');
		//->join('product_flags t7', 't1.id=t7.product_id','left')
        //->join('wishlist t8', 't8.product_id=t1.id','left')
        //->join('wishlist t9', 't8.product_id=t1.id','left')
        //->join('flavour_master t10', 't10.id = t1.flavour_id AND t10.is_deleted="NOT_DELETED" AND t10.active="1"','left');
        if(!empty($prop_filter))
        {   
            $this->db->join('class_pro_maps as t11','t1.id=t11.pro_id','left'); 
        //$this->db->join('product_props as t11','t1.id=t11.product_id','left'); 
        }
        $this->db->where(['t1.is_deleted' => 'NOT_DELETED','t1.active'=>'1','t3.shop_id' =>$shop_id]);
       
        //->limit($start,0);
        // if(!empty($cids))
		// {
		// 	$this->db->where_in('t1.sub_cat_id' , $cids);
		// }else{
		// 	$this->db->where(['t1.parent_cat_id' => $id]);
		// }
        // $this->db->where(['t1.sub_cat_id' => '60']);
        
//        if(!empty($category_pro_id))
//        {
//            // $category_filter = implode("','", $category);
//            // $this->db->where_in('t1.sub_cat_id', $category_filter);
//            $this->db->where_in('t1.id', $category_pro_id);
//        }else{
//            $this->db->where_in('t1.id' , $pro_id);
//        }
//        if(isset($brand))
//        {
//            $brand_filter = implode("','", $brand);
//            // $this->db->where_in('t1.brand_id', $brand_filter);
//            $this->db->where_in('t1.brand_id', $brand);
//        }
        
        if(!empty($prop_filter))
        {   
            $this->db->where_in('t1.id' , $pro_id);
            $this->db->group_start();
            $this->db->where_in('t11.class', $prop_filter);
            $this->db->group_end();
             
        }
        else
        {
          $this->db->where_in('t1.id' , $pro_id);
        }
        if(!empty($sort_by) && !empty($pro_id))
        {
            if($sort_by == '1')
            {
                $this->db->order_by('t3.selling_rate', 'asc');
            }
            else if($sort_by == '2')
            {
                $this->db->order_by('t3.selling_rate', 'desc');
            }
            else if($sort_by == '3')
            {
                $this->db->order_by('t1.added','desc');
            }
        }
        if(!$is_count && $isAll){
            $this->db->limit($limit, $start);
        }
        
        if(!empty($prop_filter))
            $this->db->group_by('t11.pro_id');
            
        $data =  ($is_count)?$this->db->get()->num_rows():$this->db->get()->result_array();
        //echo $this->db->last_query();
        return $data;
    }

    function fetch_data_search($search_val,$shop_id,$sort_by,$limit,$start,$is_count)
    {

        $this->db
        ->select('t1.*, t1.name as prod_name, t4.img,t4.thumbnail, t4.id as cover_id, t3.id as inventory_id, t3.mrp, t3.selling_rate, t3.qty as product_qty, t6.offer_upto, t6.discount_type, t7.is_featured, t8.product_id as wislist_pid,t1.id as propid,t1.flag as ProductFlag')
        ->from('products_subcategory t1')  
        ->join('shops_inventory t3', 't1.id=t3.product_id AND t3.is_deleted="NOT_DELETED" AND t3.status="1"')
        ->join('products_photo t4', 't4.item_id=t1.id AND t4.active="1" AND t4.is_cover="1"','left')
        //->join('products_category t5', 't1.parent_cat_id = t5.id')
        ->join('shops_coupons_offers t6', 't1.id=t6.product_id','left')
        ->join('product_flags t7', 't1.id=t7.product_id','left')
        ->join('wishlist t8', 't8.product_id=t1.id','left')
        // ->join('wishlist t9', 't8.product_id=t1.id','left')
//->join('flavour_master t10', 't10.id = t1.flavour_id AND t10.is_deleted="NOT_DELETED" AND t10.active="1"','left')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.active'=>'1','t3.shop_id' =>$shop_id]);
        if(!$is_count){
            $this->db->limit($limit, $start);
        }
        //->limit($start,0);
        // if(!empty($cids))
        // {
        //  $this->db->where_in('t1.sub_cat_id' , $cids);
        // }else{
        //  $this->db->where(['t1.parent_cat_id' => $id]);
        // }
        // $this->db->where(['t1.sub_cat_id' => '60']);
        
        // if(!empty($category_pro_id))
        // {
        //     // $category_filter = implode("','", $category);
        //     // $this->db->where_in('t1.sub_cat_id', $category_filter);
        //     $this->db->where_in('t1.id', $category_pro_id);
        // }else{
        //     $this->db->where_in('t1.id' , $pro_id);
        // }
        // if(isset($brand))
        // {
        //     $brand_filter = implode("','", $brand);
        //     // $this->db->where_in('t1.brand_id', $brand_filter);
        //     $this->db->where_in('t1.brand_id', $brand);
        // }
        
        // if(!empty($prop_filter))
        // {            
        //     $this->db->where_in('t1.id', $prop_filter);
        // }
        if(!empty($sort_by))
        {
            if($sort_by == '1')
            {
                $this->db->order_by('t3.selling_rate', 'asc');
            }
            else if($sort_by == '2')
            {
                $this->db->order_by('t3.selling_rate', 'desc');
            }
            else if($sort_by == '3')
            {
                $this->db->order_by('t1.added','desc');
            }
        }
        $this->db->group_start();
        $this->db->like('t1.name' ,$search_val,'both');
        $this->db->or_like('t1.search_keywords' ,$search_val,'both');
       $this->db->group_end();

        $data =  ($is_count)?$this->db->get()->num_rows():$this->db->get()->result_array();
        
        return $data;
    }

    function get_category_products($cids,$shop_id)
    {
        $this->db->limit(9,0)
        ->select('t1.*, t1.name as prod_name, t4.img,t4.id as cover_id, t3.id as inventory_id, t3.mrp, t3.selling_rate, t5.name as cat_name,t3.qty as product_qty, t6.offer_upto, t6.discount_type,t7.is_featured, t8.product_id as wislist_pid')
        ->from('products_subcategory t1')  
        ->join('shops_inventory t3', 't1.id=t3.product_id AND t3.is_deleted="NOT_DELETED" AND t3.status="1"','left')
        ->join('products_photo t4', 't4.item_id=t1.id AND t4.active="1" AND t4.is_cover="1"','left')
        ->join('products_category t5', 't1.parent_cat_id = t5.id')
        ->join('shops_coupons_offers t6', 't1.id=t6.product_id','left')
        ->join('product_flags t7', 't1.id=t7.product_id','left')
        ->join('wishlist t8', 't8.product_id=t1.id','left')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t3.shop_id' =>$shop_id]);

        if(!empty($cids))
        {
            $this->db->where_in('t1.parent_cat_id' , $cids);
        }
        $data =  $this->db->order_by('rand()')->get()->result_array();
        return $data;
    }

    function get_similer_products($pro_id)
    {
        $this->db
        ->select('t1.*, t1.id as product_id, t1.pro_name as prod_name, t6.offer_upto, t6.discount_type, t8.product_id as wislist_pid')
        ->from('products t1')  
        ->join('shops_coupons_offers t6', 't1.id=t6.product_id','left')
        ->join('wishlist t8', 't8.product_id=t1.id','left')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.status'=>'1'])
        ->where_in('t1.id', $pro_id);
        $data =  $this->db->get()->result_array();
        return $data;
    }

    public function get_mapped_items($product_id, $shop_id)
    {
        return $this->db->distinct()->select('si.mrp, si.selling_rate, si.id as inventory_id, si.qty, ps.unit_value, ps.unit_type, sco.offer_upto, sco.discount_type, pc.is_parent, ps.parent_cat_id, pp.img, ps.name,ps.product_code as product_code, ps.id as product_id')
            ->from('products_subcategory ps')
            ->join('products_mapping pm', 'pm.map_pro_id=ps.id', 'left')
            ->join('shops_inventory si', 'si.product_id=ps.id', 'left')
            ->join('products_photo pp', 'pp.item_id=ps.id AND pp.active=1 AND pp.is_cover=1', 'left')
            ->join('products_category pc', 'pc.id = ps.parent_cat_id', 'left')
            ->join('shops_coupons_offers sco', 'sco.product_id=ps.id', 'left')
            ->group_start()
            ->where('pm.pro_id', $product_id)
            ->or_where('ps.id', $product_id)
            ->group_end()
            ->where('ps.is_deleted', 'NOT_DELETED')
            ->where('si.shop_id', $shop_id)
            ->where('si.status', 1)
            ->get()->result();
    }

    public function wishlist_product_details($pid)
    {
        $query = $this->db
        ->select('*')
        ->from('products t1')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.id' =>$pid])
        ->get();
        return $query->row();
    }
    public function get_cart_url($pid)
    {
        $query = $this->db
        ->select('t1.*,t2.cat_id,t1.url')
        ->from('products t1')
        ->join('cat_pro_maps t2', 't2.pro_id = t1.id','left')
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.id' =>$pid])
        ->get();
        return $query->row();
    }


}