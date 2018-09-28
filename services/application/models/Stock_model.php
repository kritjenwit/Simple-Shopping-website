<?php 

    class Stock_model extends CI_Model{
        public function __construct() {
            $this->load->database();
        }

        public function get_stock($id = FALSE){
            if($id === FALSE){
                $query = $this->db->get('item');
                return $query->result_array();
            }
            $query = $this->db->get_where('item', array('itemcd'=>$id));
            return $query->row_array();
        }

        // public function add_to_cart($item){
        //     $data = array(
        //         'itemcd' => $item['itemcd'],
        //         'itemnm' => $item['itemnm'],
        //         'number' => $item['number'],
        //     );
        //     $this->db->insert();
        // }

        public function proceed($items){
            foreach($items as $item){
                // $data = array(
                //     'itemcd' => $item['itemcd'],
                //     'itemnm' => $item['itemnm'],
                //     'number' => $item['number'],
                // );
                // $this->db->insert('cart',$data);
                $stock = $this->get_stock($item['itemcd']);
                // $this->alert($stock);exit();
                $this->db->set('stock', $stock['stock'] - (int)$item['number'] );
                $this->db->where('itemcd',$item['itemcd']);
                $this->db->update('item');    
            }
            if($this->db->affected_rows()){
                return true;
            }else{
                return false;
            }
        }

        public function alert($data){
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }

        public function check_cart_exist($username){
            $query = $this->db->get_where('users',array('username' => $username));
            if(empty($query->row_array())){
                return true;
            }else{
                return false;
            }
        }
    }