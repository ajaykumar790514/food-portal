<?php

class Login extends CI_Controller {

    public function templete($page, $data)
    {
        $this->load->view('templete/header',$data);
        $this->load->view('templete/script',$data);
        $this->load->view($page);
        $this->load->view('templete/footer',$data);
    }

public function user_login(){
    $this->form_validation->set_rules('mobile', 'Mobile', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

    if ($this->form_validation->run() == FALSE)
    {
        $datAjax = array('status'=>false, 'error'=>validation_errors());
        echo json_encode($datAjax);
    }
    else
    {
        $mobile = $this->input->post('mobile');
        $pwd = md5($this->input->post('password'));
        $signed = $this->input->post('signed_in');
        $query = $this->db->where(['mobile'=>$mobile, 'password'=>$pwd])->get('customers')->result();
        if ($query == TRUE) {
            $check['mobile'] = $mobile;
            $check_existing_record = $this->home_model->getRow('customers',$check);
            if($signed == '1')
            {
                $cookie_array = array(
                    'user_table'=>'customers',
                    'user_data'=>$check_existing_record,
                    'logged_in'=>TRUE
                );
                $name = $check_existing_record->fname.' '.$check_existing_record->lname;
                set_cookie('logged_in',TRUE,2147483647);
                set_cookie('user_id',$check_existing_record->id,2147483647);
                set_cookie('user_mobile',$check_existing_record->mobile,2147483647);
                set_cookie('user_name',$name,2147483647);
                if (!empty($check_existing_record->photo)) {
                    set_cookie('user_photo', $check_existing_record->photo, 2147483647);
                }
                set_cookie('user_email',$check_existing_record->email,2147483647);
            }else{
                $session_array = array(
                    'user_id'=>$check_existing_record->id,
                    'user_name'=>$check_existing_record->fname.' '.$check_existing_record->lname,
                    'user_mobile'=>$check_existing_record->mobile,
                    'user_photo'=>$check_existing_record->photo,
                    'user_email'=>$check_existing_record->email,
                    'logged_in'=>TRUE
                );
                $this->session->set_userdata($session_array);
            }

            //add cookie cart data to cart database with user_id
            if(!empty(get_cookie('shopping_cart')))
            {
                $cart_data = json_decode(get_cookie('shopping_cart'));

                $db_cart_data = $this->home_model->get_data1('cart','user_id',$check_existing_record->id);
                foreach($cart_data as $cart)
                {
                    $item_array2  = array(
                        'product_id' => $cart->product_id,
                        'qty' => $cart->qty,
                        'user_id' => $check_existing_record->id,
                    );

                    $product_existence = $this->home_model->check_cart_product_existence($check_existing_record->id, $cart->product_id);
                    //print_r($product_existence[0]->qty);
                    if(!$product_existence)
                    {
                        if($this->home_model->Save('cart',$item_array2))
                        {
                            delete_cookie("shopping_cart");
                        }
                    }else{
                        $item_array3  = array(
                            'qty' => $cart->qty + $product_existence[0]->qty,
                        );
                        if($this->db->where(['user_id'=>$check_existing_record->id, 'product_id'=>$cart->product_id])->update('cart', $item_array3))
                        {
                            delete_cookie("shopping_cart");
                        }
                    }
                }
            }
            if (isset($_COOKIE["wishlist_cart"])) {
                $cookie_data = stripslashes($_COOKIE['wishlist_cart']);
                foreach (json_decode($cookie_data) as $row) {
                    $data = array(
                        'user_id' => $check_existing_record->id,
                        'product_id' => $row->product_id,
                    );

                    $this->user_model->add_data('wishlist',$data);              
                } 
                delete_cookie("wishlist_cart");                                       
            }

            $datAjax = array('status'=>true);
            echo json_encode($datAjax);
        }
        else{
            $datAjax = array(
                'status' => false,
                'error' => '<h4>Password is invalid.</h4><br>'
            );
            echo json_encode($datAjax);
        }
        
    }
}
public function check_admission()
{
    $admission = $this->input->post('admission');
    $school_id = $this->input->post('school_id');
    
    $admissionExists = $this->home_model->check_admission_exists($admission,$school_id);

    $response = ['exists' => $admissionExists];
    $this->output->set_content_type('application/json')->set_output(json_encode($response));
}


    public function mobile_otp()
	{  
		$mobile=$_POST['mobile'];
		$this->db->delete('user_otp', array('mobile' => $mobile));
		if(isset($_POST['mobile']) && $_POST['mobile']!==''){
			$check_existing_record = $this->home_model->mobile_exist($_POST['mobile']);
			if($check_existing_record){
                $return['res'] = 'error';
			    $return['msg'] =  "Mobile number  already exist.";
			}
			else
			{
                $otp=mt_rand(100000, 999999);
				$_SESSION['otp']  = $otp;
				$data =array(
				      'otp'=>$otp,
					  'mobile'=>$_POST['mobile'],
				);

				if($this->home_model->updateRow($mobile,$data))
				{
					//code to send the otp to the mobile number will be placed here
					if(TRUE)
					{
						$return['res'] = 'success';
                        $return['number'] = $mobile;
                        $return['otp'] = "OTP is <b class='text-primary'>".$otp."</b>";
						$return['msg'] = 'Otp Send Your Mobile Number';
                       // $this->db->delete('user_otp', array('mobile' => $mobile));
                        // $msg =$otp.' is your login OTP. Treat this as confidential. Techfi Zone will never call you to verify your OTP. Techfi Zone Pvt Ltd.';
                        // $conditions = array(
                        //     'returnType' => 'single',
                        //     'conditions' => array(
                        //         'id'=>'1'
                        //         )
                        // );
                        // $smsData = $this->ManageOrderOtpModel->getSmsRows($conditions);
                        // $smsData['mobileNos'] = $mobile;
                        // $smsData["message"] = $msg;
                        // $this->ManageOrderOtpModel->send_sms($smsData);
					}
					else
					{
						$return['res'] = 'error';
						$return['msg'] = "Message could not be sent.";	
					}
				}
				else
				{
					$return['res'] = 'error';
						$return['msg'] = "Otp could not be generated.";	
				}
			}
		}
		else
		{
			$return['res'] = 'error';
	    	$return['msg'] =  "Mobile number not received.";
		}
		echo json_encode($return);
		return TRUE;
	}
    public function check_otp()
	{
		$otp=$_POST['otp'];
		if(isset($_POST['otp']) && $_POST['otp']!==''){
			
			  $check_existing_otp = $this->home_model->otp_exist($_POST['otp']); 
			  if($check_existing_otp)
			  {
				$return= 1;
			  }else{
				$return= 0;
			  }

		}else
		{
			$return['res'] = 'error';
	    	$return['msg'] =  "OTP not received.";
		}
		echo json_encode($return);
		return TRUE;
		
}

public function select_new_account()
{
	$return['res'] = 'error';
				$return['msg'] = 'Not Saved!';
				$saved = 0;
				if ($this->input->server('REQUEST_METHOD')=='POST') {
                    $data = array(
                      'group_id'=>$_POST['group_id'],
                      'school_id'=>$_POST['school_id'],
                      'class_id'=>$_POST['class_id'],
                      'mobile'=>$_POST['mobile'],
                    );
                    $count= $this->model->Counter('customers', array('mobile'=> $_POST['mobile'] ));
                    if($count==0){
				     if($this->model->Save('customers',$data)){
							$saved = 1;
						}
                    }else
                    {
                        $this->model->Update('customers',$data,['mobile'=>$_POST['mobile']]);
                        $saved = 1;
                    }
                
				}
					
					if ($saved == 1 ) {
						$return['res'] = 'success';
						$return['msg'] = 'Successfully Select Group , School and Class.';
					}
					echo json_encode($return);
					return TRUE;
}

public function new_account_page()
{
    $data['title']='New Account Prashansha Bakery';
    $page = 'pages/new_account';
    $this->templete($page, $data);
}
public function new_account()
{
	$return['res'] = 'error';
				$return['msg'] = 'Not Saved!';
				$saved = 0;
				if ($this->input->server('REQUEST_METHOD')=='POST') {
                if(($_POST['password']) == ($_POST['cpassword']))
				  {
                    $config['file_name'] = rand(10000, 10000000000);
                    $config['upload_path'] = './uploads/photo/user/'; // Use the local file system path instead of the URL
                    $config['allowed_types'] = 'jpg|jpeg|png|webp';
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    
                    if (!empty($_FILES['image']['name'])) {
                        //upload images
                        $_FILES['images']['name'] = $_FILES['image']['name'];
                        $_FILES['images']['type'] = $_FILES['image']['type'];
                        $_FILES['images']['tmp_name'] = $_FILES['image']['tmp_name'];
                        $_FILES['images']['size'] = $_FILES['image']['size'];
                        $_FILES['images']['error'] = $_FILES['image']['error'];
                    
                        if ($this->upload->do_upload('images')) {
                            $image_data = $this->upload->data();
                            $fileName = "user/" . $image_data['file_name'];
                        } else {
                            $return['res'] = 'error';
                            $return['msg'] = $this->upload->display_errors(); // Display the error message
                        }
                        $photo = $fileName;
                    } else {
                        $photo = "";
                    }
                    $data = array(
                      'fname'=>$_POST['fname'],
                      'lname'=>$_POST['lname'],
                      'email'=>$_POST['email'],
                      'mobile'=>$_POST['mobile'],
                      'dob'=>$_POST['dob'],
                      'gender'=>$_POST['gender'],
                      'photo'=>$photo,
                      'password'=>md5($_POST['password']),
                      'isActive'=>'1',
                    );
                    $count= $this->home_model->Counter('customers', array('isActive'=>'1','mobile'=> $_POST['mobile'] ));
                    if($count==0){
				     if($this->home_model->Save('customers',$data)){
							$saved = 1;
                            $this->db->delete('user_otp', array('mobile' => $_POST['mobile']));
						}
                    }else
                    {
                        $this->home_model->Update('customers',$data,['mobile'=>$_POST['mobile']]);
                        $this->db->delete('user_otp', array('mobile' => $_POST['mobile']));
                        $saved = 1;
                    }
                }else
                {
                    $return['res'] = 'error';
                    $return['msg'] = 'Password and Comfirm Password does not matched.';
                }

				}
					
					if ($saved == 1 ) {
						$return['res'] = 'success';
						$return['msg'] = 'Your Account has been created  successfully.';
					}
					echo json_encode($return);
					return TRUE;
}
public function logout(){
    if($this->session->userdata('logged_in'))
    {
        $this->session->unset_userdata(array('user_table','user_data','logged_in','user_id'));
    }
    else
    {
        delete_cookie('logged_in');	
        delete_cookie('user_id');	
        delete_cookie('user_name');	
        delete_cookie('user_mobile');	
        delete_cookie('user_photo');	
    }
    redirect(base_url());
} 


public function submit() {
    // Form validation rules
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric');
    $this->form_validation->set_rules('message', 'Message', 'required');

    if ($this->form_validation->run() === FALSE) {
        $response['success'] = false;
        $response['errors'] = $data= array(
            'name' => form_error('name'),
            'email' => form_error('email'),
            'message' => form_error('message'),
            'mobile' => form_error('mobile')
        );
    } else {
         $data= array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'message' => $this->input->post('message'),
            'mobile' => $this->input->post('mobile')
        );
        $this->db->insert('enquiry',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id)
        {
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['errors'] = $data= array(
                'name' => form_error('name'),
                'email' => form_error('email'),
                'message' => form_error('message'),
                'mobile' => form_error('mobile')
            );
        }
       
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
public function mobile_login()
{
    $data['title']='Login Prashansha Bakery';
    $page = 'pages/mobile_login';
    $this->templete($page, $data);
}

 
}
?>