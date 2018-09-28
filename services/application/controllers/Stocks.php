<?php 

    class Stocks extends CI_Controller{
        public function GetStock(){
            $stock = $this->stock_model->get_stock();
            $this->alert_response(1,$stock);
        }

        // public function AddToCart(){
        //     $post = $this->input->post();
        //     $result = $this->stock_model->add_to_cart($post['item']);
        // }

        public function Proceed(){
            $post = $this->input->post();
            $result = $this->stock_model->proceed($post['item']);
            if($result){
                $this->alert_response(1,'Proceed complete');
            }else{
                $this->alert_response(1,'Error while proceeding');
            }
        }    
        

        public function alert_response($status,$msg){
            $response = array(
                'status' => $status,
                'msg' => $msg
            );
            echo json_encode($response);
        }

    

        public function alert($data){
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    }