<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model {

	/**
	 * Class constructor
	 *
	 * @link	https://github.com/bcit-ci/CodeIgniter/issues/5332
	 * @return	void
	 */
	public function __construct() {}

	/**
	 * __get magic
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string	$key
	 */
	public function __get($key)
	{
		// Debugging note:
		//	If you're here because you're getting an error message
		//	saying 'Undefined Property: system/core/Model.php', it's
		//	most likely a typo in your model code.
		return get_instance()->$key;
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

	function Update($tb,$data,$cond) {

		$this->db->where($cond);

	 	if($this->db->update($tb,$data)) {

	 		return true;

	 	}

	 	return false;

	}
	public function add_data($table, $data)
	{
		return $this->db->insert($table, $data);
	}
	public function get_data($table,$col, $id)
	{
		$query = $this->db->get_where($table, ['is_deleted' => 'NOT_DELETED', $col => $id]);
		return $query->result();
	}
	public function get_data1($table,$col, $id)
	{
		$query = $this->db->get_where($table, [$col => $id]);
		return $query->result();
	}
	public function get_row_data($table,$col, $id)
	{
		$query = $this->db->get_where($table, ['is_deleted' => 'NOT_DELETED', $col => $id]);
		return $query->row();
	}
	public function get_row_data1($table,$col,$id)
	{
		$query = $this->db->get_where($table, [$col => $id]);
		return $query->row();
	}
	public function view_data($table)
	{
		$query = $this->db->get_where($table, ['is_deleted' => 'NOT_DELETED']);
		return $query->result();
	}
	public function view_datatest($table)
	{
		$query = $this->db->get_where($table, ['is_deleted' => 'NOT_DELETED']);
		return $query->result_array();
	}
	public function delete_data1($table,$col, $id)
	{
		return $this->db->where($col, $id)->delete($table);
	}
	//edit data 
	public function edit_data($table,$col,$id,$data)
	{
		return $this->db->where($col, $id)->update($table, $data);
	}

}
