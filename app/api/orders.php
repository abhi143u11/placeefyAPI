<?php
$app->group('/api', function(\Slim\App $app) {


    
    $app->map(['POST'],'/placedOrder', function( $request,$response,$args) {
        try {
                   require '../includes/DBOperations.php';
                                   
                   $order_object =  $request->getParam('order_object');
                   $Order_data = json_decode($order_object,true);
                   
                   if(is_array($Order_data) && empty($Order_data))
                   {
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'order_object should not be blank.']);
                    exit;
                   }
                   //echo "dddd";

        					//****************LOG Creation*********************
        					$APILogFile = $config['api_log_file_path'].'placedOrder.txt';
        					$handle = fopen($APILogFile, 'a');
        					$timestamp = date('Y-m-d H:i:s');
        					$logArray1 = print_r($Order_data, true);
        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
        					fwrite($handle, $logMessage);				
        					fclose($handle);
        				   //****************ENd OF Code*****************

                   $result = $db->placed_order($Order_data);
                   if(!empty($result)){
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=> $result]);
                    }else{
                        return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry No order has been placed']);
                    }
                   

        } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	


});
$app->map(['POST'],'/changeOrderStatus', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                                   
               $order_id =  $request->getParam('order_id');
               $status_flag =  $request->getParam('status_flag');
               $user_id =  $request->getParam('user_id');             //separated by commas
              


               $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
               $status_flag = $rest->validateParameter('status_flag', $status_flag, STRING);
               $user_id = $rest->validateParameter('user_id', $user_id, INTEGER);
              
            
                $query = "UPDATE `mt_order` SET  `status`='$status_flag' where `order_id`='$order_id' and  `client_id`='$user_id'";
               $result = $db->execute($query);
               if($result){
                return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=> 'status updated successfully']);
                }else{
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry status not updated']);
                }
               

                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
}	


});
$app->map(['POST'],'/getOrderCurrentStatus', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $order_id =  $request->getParam('order_id');
                $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
                $query = "SELECT `status` FROM `mt_order` WHERE `order_id`='$order_id'";
                $result = $db->sidebar_query($query);
               
               if(!empty($result)){
                return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>NULL]);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/getMostSellingItems', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $from_date =  $request->getParam('from_date');
				$to_date =  $request->getParam('to_date');
                $from_date = $rest->validateParameter('from_date', $from_date, STRING);
				$to_date = $rest->validateParameter('to_date', $to_date, STRING);
				
				//****************LOG Creation*********************
					$APILogFile = 'getMostSellingItems.txt';
					$logArray = array('from_date'=>$from_date,'to_date'=>$to_date);
					$handle = fopen($APILogFile, 'a');
					$timestamp = date('Y-m-d H:i:s');
					$logArray1 = print_r($logArray, true);
					$logMessage = "\ngetMostSellingItems Result at $timestamp :-\n$logArray1";
					fwrite($handle, $logMessage);				
					fclose($handle);
				   //****************ENd OF Code*****************
				   
                $query = "SELECT `item_id`,`item_name`,COUNT(`item_id`) as count,ROUND(sum(`normal_price`),2) as total_sale FROM `mt_order_details` as A inner join `mt_order` as B on B.`order_id` = A.`order_id`  where date(B.`date_created`) >= '$from_date' and date(B.`date_created`) <= '$to_date' GROUP BY A.`item_id` ORDER BY count DESC limit 1";
                $result = $db->sidebar_query($query);
               
               if(!empty($result)){
                return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>NULL]);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

});
