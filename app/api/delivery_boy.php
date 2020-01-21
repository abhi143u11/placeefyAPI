<?php
$app->group('/api', function(\Slim\App $app) {
    
$app->map(['POST'],'/deliveryBoyLogin', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $username =  $request->getParam('uname');
                $password = $request->getParam('password');
				
				//****************LOG Creation*********************
				$APILogFile = 'deliveryBoyLogin.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('uname'=>$username,'password'=>$password);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ndeliveryBoyLogin Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
                //validate parameters
                $username = $rest->validateParameter('uname', $username, STRING);
                $password = $rest->validateParameter('password',$password, STRING);
                $mypassword = md5($password);
               
                    //$query = "SELECT * FROM `mt_client` WHERE $condition";
                    $result = $db->sidebar_query("SELECT * FROM `mt_delivery_boy` WHERE username='$username' and password='$mypassword'");
                    
                    if(!empty($result))
                    {
                       $datetime = date("Y-m-d H:i:s");
                       $deliveryboy_id=$result[0]['id'];
                       unset($result[0]['source']);
                       unset($result[0]['ip_address']);
                       unset($result[0]['date_modified']);
                        
                       $result[0]['mobile_number'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_mobile_number');
                       $result[0]['email_id'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_email_id');
                       $result[0]['Name'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_name');
                       $result[0]['DeliveryArea'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_delivery_area');
                       $result[0]['DeliveryBikeNo'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_bike_number');
                       $result[0]['BoyResidencyAddress'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_address');
                       $result[0]['DeliveryAreaPincode'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_delivery_pincode');
                       $result[0]['DOB'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_date_of_birth');
                       $result[0]['Age'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_age');
                       $result[0]['boy_pan_card_number'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_pan_card_number');
                       $result[0]['boy_aadhar_card_number'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_aadhar_card_number');
                       $result[0]['boy_license_card_number'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_license_card_number');
                       $result[0]['boy_login_status'] = $db->get_delivery_boy_meta_keyvalue($deliveryboy_id,'boy_login_status');

                     $db->execute("UPDATE `mt_delivery_boy` SET `last_login`='$datetime' where `id`='$deliveryboy_id'");
                     return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                    }else{
                            return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>'Invalid Username or password']);
                        }
                 }else{
                        return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
                    }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/updateDeliveryBoyLoginStatus', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
                $online_offline_flag =  $request->getParam('online_offline_flag');
				
				$online_offline_flag_arr = array('Online','online','Offline','offline');
				if(!in_array($online_offline_flag,$online_offline_flag_arr))
				{
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Invalid online_offline_flag it should be Online or Offline']); 
					exit;
				}
				
				//****************LOG Creation*********************
				$APILogFile = 'updateDeliveryBoyLoginStatus.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('delivery_boy_id'=>$delivery_boy_id,'online_offline_flag'=>$online_offline_flag);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nupdateDeliveryBoyLoginStatus Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
                $online_offline_flag = $rest->validateParameter('delivery_boy_id', $online_offline_flag, STRING);
				
                $query = "SELECT * FROM `mt_delivery_boy_meta` WHERE `meta_key`='boy_login_status' and `delivery_boy_id`='$delivery_boy_id'";
                $response = $db->update_or_insert_loginStatus($query,'DeliveryBoy',$online_offline_flag,$delivery_boy_id);
                if($response) 
                {
					$user_ip = getUserIP();
					$date = date('Y-m-d H:i:s');//,strtotime("+5 hours, +30 minutes", strtotime(date('Y-m-d H:i:s'))));
					if(strtolower($online_offline_flag) == 'online')
					{
						$get_first_login = "SELECT `date`,`first_login` FROM `mt_delivery_boy_online_status` WHERE 
						`delivery_boy_id`='$delivery_boy_id' and date = CURRENT_DATE()";
						$count = $db->db_num($get_first_login);
						if($count==0)
						{
							$insert_first_login = "INSERT INTO `mt_delivery_boy_online_status`(`delivery_boy_id`, `date`, `first_login`, `flag`, `ip_address`, `source`) VALUES ('$delivery_boy_id',CURDATE(),'$date',LOWER('$online_offline_flag'),'$user_ip','android')";
							$db->execute($insert_first_login);
							
						}else{
							
							$update_latest_login = "UPDATE `mt_delivery_boy_online_status` SET `latest_login` ='$date',`flag`='$online_offline_flag' where `delivery_boy_id`='$delivery_boy_id' and date = CURRENT_DATE()";
							$db->execute($update_latest_login);
						}
						
					}else if(strtolower($online_offline_flag) == 'offline')
					{
						$get_first_login = "SELECT `date`,TIME(`first_logout`) as first_logout FROM `mt_delivery_boy_online_status` WHERE 
						`delivery_boy_id`='$delivery_boy_id' and date = CURRENT_DATE()";
						$result11 = $db->mysqli->query($get_first_login);
						$row11 = mysqli_fetch_assoc($result11);
						//print_r($row11);
						if($row11['first_logout']=='00:00:00')
						{
							
							$update_first_logout = "UPDATE `mt_delivery_boy_online_status` SET `first_logout` ='$date',`flag`='$online_offline_flag' where `delivery_boy_id`='$delivery_boy_id' and date = CURRENT_DATE()";
							$db->execute($update_first_logout);
							
						}else{
							
							  $count1 = $db->db_num($get_first_login);
							 if($count1 == 0)
							 {
								 $insert_latest_logout = "INSERT INTO `mt_delivery_boy_online_status`(`delivery_boy_id`, `date`, `latest_logout`, `flag`, `ip_address`, `source`) VALUES ('$delivery_boy_id',CURDATE(),'$date',LOWER('$online_offline_flag'),'$user_ip','android')";
							     $db->execute($insert_latest_logout);
								 
							 }
							$update_latest_logout = "UPDATE `mt_delivery_boy_online_status` SET `latest_logout` ='$date',`flag`='$online_offline_flag' where `delivery_boy_id`='$delivery_boy_id' and date = CURRENT_DATE()";
							$db->execute($update_latest_logout);
						}
						
					}
					
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'delivery boy login status has been updated']);
                }else{
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'sorry unable to update delivery boy login status']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/addDeliveryBoyFCMToken', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                $fcm_id =  $request->getParam('fcm_id');
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
                $device_id =  $request->getParam('device_id');
                $fcm_token =  $request->getParam('fcm_token');
				
				//****************LOG Creation*********************
				$APILogFile = 'addDeliveryBoyFCMToken.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('fcm_id'=>$fcm_id,'delivery_boy_id'=>$delivery_boy_id,'device_id'=>$device_id,'fcm_token'=>$fcm_token);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddDeliveryBoyFCMToken Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $fcm_id = $rest->validateParameter('fcm_id', $fcm_id, STRING,false);
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
                $device_id = $rest->validateParameter('device_id', $device_id, STRING);
                $fcm_token = $rest->validateParameter('fcm_token', $fcm_token, STRING);
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
                $query = "INSERT INTO `mt_delivery_boy_fcm_token`(`delivery_boy_id`, `device_id`, `fcm_token`, `ip_address`, `source`,`datetime`) VALUES ('$delivery_boy_id','$device_id','$fcm_token','$user_ip','android','$datetime')";
                $insert_id = $db->insert_FCM_token($query,'DeliveryBoy',$fcm_token,$fcm_id);
                if($insert_id>0) 
                {
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$insert_id]);
                }else if($insert_id =='updated'){
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>"FCM token has been updated successfully"]);
              }else{
                      return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry FCM token has not been inserted successfully']);
               }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/addDeliveryBoyOrderStatus', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                $order_id =  $request->getParam('order_id');
                $client_id =  $request->getParam('client_id');
                $driver_id =  $request->getParam('driver_id');
                $status =  $request->getParam('status');
				
				//****************LOG Creation*********************
				$APILogFile = 'addDeliveryBoyOrderStatus.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('order_id'=>$order_id,'client_id'=>$client_id,'driver_id'=>$driver_id,'status'=>$status);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddDeliveryBoyOrderStatus Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                $driver_id = $rest->validateParameter('driver_id', $driver_id, INTEGER);
                $status = $rest->validateParameter('status', $status, STRING);
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
                $query = "INSERT INTO `mt_driver_order_status`(`order_id`, `client_id`, `driver_id`, `status`, `date_created`, `ip_address`, `source`) VALUES ('$order_id','$client_id','$driver_id','$status','$datetime','$user_ip','android')";
                $status = $db->insert_driver_order_status($query,$order_id,$driver_id,$status);
                if($status == 'inserted') 
                {
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Driver Order status inserted successfully']);
                }else if($status == 'already_exist'){
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'false','result'=>"Order status already exist"]);
                }else{
                        return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry Driver Order status not inserted successfully']);
                 }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/updateDeliveryBoyOrderStatus', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                $order_id =  $request->getParam('order_id');
                $driver_id =  $request->getParam('driver_id');
                $status =  trim($request->getParam('status'));
				
				$status_arr = array('Delivered','Assigned','Accepted','Cancelled','delivered','assigned','accepted','cancelled');
				if(!in_array($status,$status_arr))
				{
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Invalid Status it should be Delivered, Assigned, Accepted or Cancelled']);
					exit;
				}
				//****************LOG Creation*********************
				$APILogFile = 'updateDeliveryBoyOrderStatus.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('order_id'=>$order_id,'driver_id'=>$driver_id,'status'=>$status);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nupdateDeliveryBoyOrderStatus Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
                $driver_id = $rest->validateParameter('driver_id', $driver_id, INTEGER);
                $status = $rest->validateParameter('status', $status, STRING);
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
				
				$get_order_id = "SELECT `order_id` FROM `mt_driver_order_status` WHERE `order_id`='$order_id' and `driver_id`='$driver_id'";
				$count = $db->db_num($get_order_id);
				if($count>=1)
				{
					 if(strtolower($status) == 'delivered')
					 {
						 
						 $query = "UPDATE `mt_driver_order_status` SET `status` ='$status',`source`='android',`ip_address`='$user_ip',`when_delivered`='$datetime' where `driver_id`='$driver_id' and `order_id`='$order_id'";
						 $status = $db->execute($query);
						 
						 //update same Delivered status in main order table also
						 $delivery_date = date("Y-m-d");
						 $gettime_arr = explode(" ",$datetime);
						 $delivery_time = $gettime_arr[1];
						$update_order_status = "UPDATE `mt_order` SET `status`='Delivered',
						`delivery_date`='$delivery_date',`delivery_time`='$delivery_time',`date_modified`='$datetime' where 
						`order_id`='$order_id'";
						$update_status = $db->execute($update_order_status);
						
					 }else{
							$query = "UPDATE `mt_driver_order_status` SET `status` ='$status', 
							`date_modified`='$datetime',`source`='android',`ip_address`='$user_ip' where `driver_id`='$driver_id' and `order_id`='$order_id'";
							$status = $db->execute($query);
					 }
					
					
					if($status) 
					{
						
						
						return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Driver Order status updated successfully']);
						exit;
					}else{
							return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry Driver Order status not updated successfully']);
							exit;
					 }
				}else{
					
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'For mentioned delivery boy id, no order id is exist in DB']);
							exit;
				}
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/getDeliveryBoyOrderStatus', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $order_id =  $request->getParam('order_id');
                $driver_id =  $request->getParam('driver_id');
				
				//****************LOG Creation*********************
				$APILogFile = 'getDeliveryBoyOrderStatus.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('order_id'=>$order_id,'driver_id'=>$driver_id);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetDeliveryBoyOrderStatus Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
                $driver_id = $rest->validateParameter('driver_id', $driver_id, INTEGER);
  
                $query = "SELECT * FROM `mt_driver_order_status` WHERE `order_id`='$order_id' and driver_id='$driver_id'";
                $response = $db->sidebar_query($query);
                if(!empty($response)) 
                {
					$client_id = $response[0]['client_id'];
					$get_client_name = "SELECT CONCAT(`first_name`, ' ', `last_name`) as cust_name,`contact_phone`  FROM `mt_client` WHERE `client_id`='$client_id'";
					$result11 = $db->mysqli->query($get_client_name);
					$row11 = mysqli_fetch_assoc($result11);
					
					$order_id = $response[0]['order_id'];
					$get_order_grand_amount = "SELECT `grand_total`,`payment_type` FROM `mt_order` WHERE `order_id`='$order_id'";
					$result12 = $db->mysqli->query($get_order_grand_amount);
					$row12 = mysqli_fetch_assoc($result12);
					
					
					$get_order_no_of_items = "SELECT count(`item_id`) as no_of_items FROM `mt_order_details` WHERE `order_id`='$order_id'";
					$result13 = $db->mysqli->query($get_order_no_of_items);
					$row13 = mysqli_fetch_assoc($result13);
					
					$get_all_items_names = "SELECT id,GROUP_CONCAT(`item_name` SEPARATOR ',') AS item_names FROM mt_order_details where `order_id` = '$order_id' group by `order_id`";
					$result14 = $db->mysqli->query($get_all_items_names);
					$row14 = mysqli_fetch_assoc($result14);
					
                  $response[0]['boy_name'] = (!empty($db->get_delivery_boy_meta_keyvalue($driver_id,'boy_name')))?$db->get_delivery_boy_meta_keyvalue($driver_id,'boy_name'):'';
                  $response[0]['deliver_boy_id'] = $driver_id ;
                  $response[0]['boy_delivery_area'] = (!empty($db->get_delivery_boy_meta_keyvalue($driver_id,'boy_delivery_area')))?$db->get_delivery_boy_meta_keyvalue($driver_id,'boy_delivery_area'):'';
				  $response[0]['customer_name'] = $row11['cust_name'];
				  $response[0]['customer_phone'] = $row11['contact_phone'];
				  $response[0]['grand_total'] = number_format((float) ($row12['grand_total']), 2, '.', '');
				  $response[0]['payment_type'] = $row12['payment_type'];
				  $response[0]['no_of_items'] = $row13['no_of_items'];
				  $response[0]['items_name'] = $row14['item_names'];
				  
				  unset($response[0]['ip_address']);
				   unset($response[0]['source']);
				    unset($response[0]['driver_id']);
					unset($response[0]['date_modified']);
				  
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$response]);
                }else{
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Wrong order or delivery Id']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/getTotalOrderDeliveredByDboy', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
               
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
				$from_date =  $request->getParam('from_date');
				$to_date =  $request->getParam('to_date');
				
				//****************LOG Creation*********************
				$APILogFile = 'getTotalOrderDeliveredByDboy.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('delivery_boy_id'=>$delivery_boy_id,'from_date'=>$from_date,'to_date'=>$to_date);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetTotalOrderDeliveredByDboy Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
				$from_date = date("Y-m-d", strtotime($rest->validateParameter('from_date', $from_date, STRING)));
				$to_date = date("Y-m-d", strtotime($rest->validateParameter('to_date', $to_date, STRING)));
  
                $query = "SELECT * FROM `mt_driver_order_status` WHERE LOWER(`status`) ='delivered' and `driver_id`='$delivery_boy_id' and DATE(DATE_FORMAT(when_delivered, '%Y-%m-%d')) >= '$from_date' and  DATE(DATE_FORMAT(when_delivered, '%Y-%m-%d')) <= '$to_date'";
				
                $response = $db->sidebar_query($query);
                if(!empty($response)) 
                {
					for($i=0;$i<count($response);$i++)
					{
						$order_id = $response[$i]['order_id'];
						$client_id = $response[$i]['client_id'];
						$get_order_grand_amount = "SELECT `sub_total`,`delivery_charge`,`grand_total`,`payment_type`,`date_created` as order_date FROM `mt_order` WHERE `order_id`='$order_id'";
						$result12 = $db->mysqli->query($get_order_grand_amount);
						$row12 = mysqli_fetch_assoc($result12);
						
						
						$get_client_name = "SELECT CONCAT(`first_name`, ' ', `last_name`) as cust_name,`contact_phone`  FROM `mt_client` WHERE `client_id`='$client_id'";
					    $result11 = $db->mysqli->query($get_client_name);
					    $row11 = mysqli_fetch_assoc($result11);
						
						$response[$i]['order_date'] = $row12['order_date'];
						$response[$i]['sub_total'] = number_format((float) ($row12['sub_total']), 2, '.', '');
						$response[$i]['delivery_charge'] = number_format((float) ($row12['delivery_charge']), 2, '.', '');
						$response[$i]['grand_total'] = number_format((float) ($row12['grand_total']), 2, '.', '');
						
						$response[$i]['customer_name'] = $row11['cust_name'];
						$response[$i]['customer_phone'] = $row11['contact_phone'];
						
					  
					  
					  unset($response[$i]['ip_address']);
					  unset($response[$i]['date_modified']);
					  unset($response[$i]['date_created']);

					}
				  
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$response]);
                }else{
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Invalid delivery Id']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/getTotalCashFloatAvailableWithDboy', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
               
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
				
				
				//****************LOG Creation*********************
				$APILogFile = 'getTotalCashFloatAvailableWithDboy.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('delivery_boy_id'=>$delivery_boy_id);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetTotalCashFloatAvailableWithDboy Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
				
  
                $query = "SELECT sum(`grand_total`) as total_cash_available,`driver_id` FROM `mt_driver_order_status` as A inner join mt_order as B on A.order_id = B.order_id WHERE LOWER(A.`status`) ='delivered'   and `driver_id`='$delivery_boy_id' and LOWER(`payment_type`) ='cod' group by driver_id";
                $response = $db->sidebar_query($query);
                if(!empty($response)) 
                {

                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$response]);
                }else{
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Invalid delivery Id']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/getAmountPaidToAdmin', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
               
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
				
				
				//****************LOG Creation*********************
				$APILogFile = 'getAmountPaidToAdmin.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('delivery_boy_id'=>$delivery_boy_id);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetAmountPaidToAdmin Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
				
  
                $query = "SELECT sum(`grand_total`) as total_cash_available,`driver_id` FROM `mt_driver_order_status` as A inner join mt_order as B on A.order_id = B.order_id WHERE LOWER(A.`status`) ='delivered'   and `driver_id`='$delivery_boy_id' and LOWER(`payment_type`) ='cod' group by driver_id";
                $response = $db->sidebar_query($query);
                if(!empty($response)) 
                { 
			        
					$total_cash_available_with_Dboy = number_format((float) ($response[0]['total_cash_available']), 2, '.', '');
					$payment_paid_to_admin = "SELECT `payment_type`,`amount`,`payment_date`,`status`,`comment` from `mt_delivery_boy_payment` where `delivery_boy_id`='$delivery_boy_id' and LOWER(`status`) = 'paid' order by `payment_date` DESC";
					$response1 = $db->sidebar_query($payment_paid_to_admin);
					
					$get_total_amount_paid_tilldate = "SELECT sum(`amount`) as amount_paid_to_admin FROM `mt_delivery_boy_payment` WHERE `delivery_boy_id`='$delivery_boy_id' and LOWER(`status`) = 'paid' group by `delivery_boy_id`";
					$result11 = $db->mysqli->query($get_total_amount_paid_tilldate);
					$row11 = mysqli_fetch_assoc($result11);
					$paid_amount = number_format((float) ($row11['amount_paid_to_admin']), 2, '.', '');

                   $final_amount_with_Dboy = number_format((float) ceil($total_cash_available_with_Dboy - $paid_amount), 2, '.', '');
				   
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$response1,'total_cash_available_with_Dboy'=>$total_cash_available_with_Dboy,'amount_paid_to_admin'=>$paid_amount,'final_amount_balance_with_Dboy'=>$final_amount_with_Dboy]);
                }else{
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Invalid delivery Id']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/createDboySupportTicket', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
               
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
				$priority =  $request->getParam('priority');
				$message =  $request->getParam('message');
				
				$ticket = array();
				$ticket_number = rand(1000, 9999) . rand(1000, 9999);
				$user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
				
				//****************LOG Creation*********************
				$APILogFile = 'createDboySupportTicket.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('delivery_boy_id'=>$delivery_boy_id,'priority'=>$priority,'message'=>$message);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ncreateDboySupportTicket Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
				$priority = $rest->validateParameter('priority', $priority, STRING);
				$message = $rest->validateParameter('message', $message, STRING);
				
  
                $query = "INSERT INTO `mt_delivery_boy_support_ticket`(`assigned_ticket_no`, `delivery_boy_id`, `date_created`,`priority`, `assigned_to`, `status`,`description`, `ip_address`) VALUES ('$ticket_number','$delivery_boy_id','$datetime','$priority','admin','open','$message','$user_ip')";
                $response = $db->execute($query);
                if($response) 
                { 
			        $get_ticket_number = "select `assigned_ticket_no` from mt_delivery_boy_support_ticket order by id desc limit 1";
					$result11 = $db->mysqli->query($get_ticket_number);
					$row11 = mysqli_fetch_assoc($result11);
					$ticket['support_ticket_number'] = $row11['assigned_ticket_no'];
					
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$ticket]);
				  
                }else{
                  return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry support ticket has not been created']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/updateDeliveryLatAndLong', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
               
                $latitude =  trim($request->getParam('latitude'));
				$longitude =  $request->getParam('longitude');
				$delivery_boy_id =  $request->getParam('delivery_boy_id');
				
			
				
				//****************LOG Creation*********************
				$APILogFile = 'updateDeliveryLatAndLong.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('delivery_boy_id'=>$delivery_boy_id,'latitude'=>$latitude,'longitude'=>$longitude);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nupdateDeliveryLatAndLong Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
				$latitude = $rest->validateParameter('latitude', $latitude, INTEGER);
				$longitude = $rest->validateParameter('longitude', $longitude, INTEGER);
				
  
                 $query = "SELECT * FROM `mt_delivery_boy` WHERE `id`='$delivery_boy_id' and LOWER(`status`)='active'";
				 $count1 = $db->db_num($query);
				 if($count1 ==1)
				 {
					 $user_ip = getUserIP();
                     $datetime = date("Y-m-d H:i:s");
					 $update_lat_long = "UPDATE `mt_delivery_boy` SET `latitude`='$latitude',`longitude`='$longitude',`ip_address`='$user_ip',`date_modified`='$datetime',`source`='android' WHERE id='$delivery_boy_id'";
					 $response = $db->execute($update_lat_long);
					 
					 if($response) 
					 { 
						
					  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Latitude and longitude has been updated successfully']);
				  
					 }else{
						return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Latitude and longitude has not been updated successfully']);
					}
					 
				 }else{
					 
						return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Delivery boy id does not exist or delivery boy is not active']);
					}
                 
                
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/addDeliveryBoyReview', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                $order_id =  $request->getParam('order_id');
                $client_id =  $request->getParam('client_id');
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
                $review =  $request->getParam('review');
				$rating =  $request->getParam('rating');
				
				//****************LOG Creation*********************
				$APILogFile = 'addDeliveryBoyReview.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('order_id'=>$order_id,'client_id'=>$client_id,'delivery_boy_id'=>$delivery_boy_id,'review'=>$review,'rating'=>$rating);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddDeliveryBoyReview Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
                $review = $rest->validateParameter('review', $review, STRING);
				$rating = (float)$rest->validateParameter('rating', $rating, INTEGER);
				
				if($rating<0 || $rating>5)
				{
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Rating should be in between 1 and 5']);
					exit;
				}
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
				
				 $query = "SELECT * FROM `mt_delivery_boy` WHERE `id`='$delivery_boy_id' and LOWER(`status`)='active'";
				 $count1 = $db->db_num($query);
				 if($count1 == 1)
				 {
				
					$insert_dboy_review = "INSERT INTO `mt_delivery_boy_review`(`delivery_boy_id`, `client_id`, `review`, `rating`, `date_created`, `ip_address`, `order_id`) VALUES ('$delivery_boy_id','$client_id','$review','$rating','$datetime','$user_ip','$order_id')";
					$response = $db->execute($insert_dboy_review);
					if($response) 
					{
						return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Review of delivery boy has been inserted successfully']);
					}
					else{
							return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry Review of delivery boy has not been inserted successfully']);
					 }
				 }else{
					 
						return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Delivery boy id does not exist or delivery boy is not active']);
					}
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/getDelivereyBoyEarning', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
               
                $delivery_boy_id =  $request->getParam('delivery_boy_id');
				$from_date =  $request->getParam('from_date');
				$to_date =  $request->getParam('to_date');
				
				//****************LOG Creation*********************
				$APILogFile = 'getDelivereyBoyEarning.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('delivery_boy_id'=>$delivery_boy_id,'from_date'=>$from_date,'to_date'=>$to_date);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetDelivereyBoyEarning Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $delivery_boy_id = $rest->validateParameter('delivery_boy_id', $delivery_boy_id, INTEGER);
				$from_date = date("Y-m-d", strtotime($rest->validateParameter('from_date', $from_date, STRING)));
				$to_date = date("Y-m-d", strtotime($rest->validateParameter('to_date', $to_date, STRING)));
  
				 $query = "SELECT * FROM `mt_delivery_boy` WHERE `id`='$delivery_boy_id' and LOWER(`status`)='active'";
				 $count1 = $db->db_num($query);
				 if($count1 == 1)
				 {
					$query = "SELECT sum(`final_total_amount`) as total_earning FROM `mt_delivery_boy_earning` WHERE LOWER(`status`) ='active' and `delivery_boy_id`='$delivery_boy_id' and DATE(DATE_FORMAT(`date_created`, '%Y-%m-%d')) >= '$from_date' and  DATE(DATE_FORMAT(`date_created`, '%Y-%m-%d')) <= '$to_date' group by `delivery_boy_id`";
				
					$response = $db->sidebar_query($query);
					if(!empty($response)) 
					{
						$Dboytotal_earning = "Rs. ".number_format((float) ($response[0]['total_earning']), 2, '.', '');
						//****************LOG Creation*********************
						$APILogFile = 'getDelivereyBoyEarning.txt';
						$handle = fopen($APILogFile, 'a');
						$timestamp = date('Y-m-d H:i:s');
						$logMessage = "\ngetDelivereyBoyEarning Result at $timestamp :-\n$Dboytotal_earning";
						fwrite($handle, $logMessage);				
						fclose($handle);
						//****************ENd OF Code*****************
					  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$Dboytotal_earning]);
					}else{
						//****************LOG Creation*********************
						$APILogFile = 'getDelivereyBoyEarning.txt';
						$handle = fopen($APILogFile, 'a');
						$timestamp = date('Y-m-d H:i:s');
						$logMessage = "\ngetDelivereyBoyEarning Result at $timestamp :-\nInvalid delivery Id";
						fwrite($handle, $logMessage);				
						fclose($handle);
						//****************ENd OF Code*****************
					  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Invalid delivery Id']);
					}
				 }else{
						//****************LOG Creation*********************
						$APILogFile = 'getDelivereyBoyEarning.txt';
						$handle = fopen($APILogFile, 'a');
						$timestamp = date('Y-m-d H:i:s');
						$logMessage = "\ngetDelivereyBoyEarning Result at $timestamp :-\nDelivery boy id does not exist or delivery boy is not active";
						fwrite($handle, $logMessage);				
						fclose($handle);
						//****************ENd OF Code*****************
						return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Delivery boy id does not exist or delivery boy is not active']);
					}
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});


});
