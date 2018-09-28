<?php 

    class Users extends CI_Controller{
       

        public function login(){
            $post = $this->input->post();
            $username = $post['username'];
            $password = $post['password'];
            $login = $this->user_model->login($username,$password);
            if($login){
                $this->alert_response(1,'Login Successfully');
            }else{
                $this->alert_response(0,'Failed to Login, Please check your username or password');
            }
        }

        public function register(){
            $post = $this->input->post();
            $username = $post['username'];
            $password = $post['password'];
            $register = $this->user_model->register($username,$password);
            if($register){
                $this->alert_response(1,'Register Successfully');
            }else{
                $this->alert_response(0,'Username is existed, Please another username');
            }
        }

        public function delete(){
            $post = $this->input->post();
            $result = $this->user_model->delete($post['id']);
            if($result){
                $this->alert_response(1,'Delete successfully');
            }else{
                $this->alert_response(0,'Something wrong while deleting a data');
            }
        }

        public function edit(){
            $post = $this->input->post();
            $result = $this->user_model->edit($post);
            if($result){
                $this->alert_response(1,'Update Successfully');
            }else{
                $this->alert_response(0,'Error while updating a data');
            }
        }

        public function all_user(){
            if(!$this->input->post()){
                $users = $this->user_model->get_user();
                echo  json_encode($users);
            }else{
                $post = $this->input->post();
                $users = $this->user_model->get_user($post['id']);
                echo  json_encode($users);
            }
            
        }

        public function alert_response($status,$message){
            $response = array(
                'status' => $status,
                'msg' => $message
            );
            echo json_encode($response);
        }
        public function alert($data){
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    
    }