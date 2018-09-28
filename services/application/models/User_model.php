<?php 

    class User_model extends CI_Model{

        public function __construct()
        {
            parent::__construct();
            //Do your magic here
            $this->load->database();
        }
        

        public function get_user($slug = FALSE){
            if($slug === FALSE){
                $query = $this->db->get('users');
                return $query->result_array();
            }
            $query = $this->db->get_where('users', array('id'=>$slug ));
            return $query->row_array();
        }

        public function login($username,$password){
            $this->db->where('username',$username);
            $this->db->where('password', $password);
            $result = $this->db->get('users');
            if($result->num_rows() == 1){
                return $result->row(0)->id;
            }else{
                return false;
            }
        }

        public function register($username,$password){
            $data = $this->set_data($username,$password);
            $isUserExist = $this->check_username_exist($username);
            if(!$isUserExist){
                return false;
            }else{
                $this->db->insert('users',$data);
                return ($this->db->affected_rows() != 1) ? false : true;
            }
        }

        public function delete($id){
            $this->db->delete('users', array('id' => $id));
            if($this->db->affected_rows()){
                return true;
            }else{
                return false;
            }
        }

        public function edit($post){
            $data = array(
                'username' => $post['username'],
                'password' => $post['password']
            );
            $this->db->where('id',$post['id']);
            $this->db->update('users', $data);
            if($this->db->affected_rows()){
                return true;
            }else{
                return false;
            }
        }

        public function check_username_exist($username){
            $query = $this->db->get_where('users',array('username' => $username));
            if(empty($query->row_array())){
                return true;
            }else{
                return false;
            }
        }

        public function set_data($d1,$d2){
            $data = array(
                'username' => $d1,
                'password' => $d2
            );
            return $data;
        }
    }