<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Api_Training
 * @subpackage Api_Training/public
 * @author     Khalid <khalinoid@gmail.com>
 */
class Api_Training_APIs {

    public function __construct()
    {
        // add_shortcode('my_shortcode', [$this, 'api_training_shortcodes']);
        add_shortcode('my_shortcode', [$this, 'run_app']);
    }


    function api_training_shortcodes(){
        // ob_start();

        echo "hello";
        // run_app();

        // return ob_get_clean();
    }

    
    function run_app(){

        if (isset($_POST['text'])){

            $text = sanitize_text_field(isset($_POST['text']) );

            $url = 'http://localhost:5000/predict';

            $data = array(
                'text' => $text,
            );
            $args = array(
                'method' => 'POST',
                'body' => json_encode( $data ), 
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                )
            );

            // echo json_encode($args);
            $response = wp_remote_post($url, $args);


            if(is_wp_error($response)){
                $err_msg = $response->get_error_message();
                echo "Error occured during fetch: $err_msg";
                return $err_msg;
            }
            $sentiment = json_encode($response);
            $responseBody = json_decode(wp_remote_retrieve_body( $response ));
            // echo json_encode($responseBody);
            $return_stmt = "For the text: ". $responseBody->text. ". \nThe sentiment value is: ". $responseBody->sentiment. ". \nWith confidence level: ". $responseBody->confidence;
            return $return_stmt;
     
        }
        
    }
}
