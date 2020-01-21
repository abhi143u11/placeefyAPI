<?php

$app->group('/api', function(\Slim\App $app) {
$app->map(['POST'],'/clientlogin', function( $request,$response,$args) {
	try {
                
                require '../includes/DBOperations.php';
               
                $cust_phone =  $request->getParam('cust_phone');
                $password = $request->getParam('password');

                //validate parameters
                $cust_phone = $rest->validateParameter('cust_phone', $cust_phone, STRING);
                $password = $rest->validateParameter('password',$password, STRING);
                $mypassword = md5($password);
                $condition='';
                // if(valid_email($username))
                // {
                //     $condition = "`email_address`='$username' and `password`='$mypassword'";
                   
                // }
                 if(validate_mobile($cust_phone))
                {
                    $condition = "`phone_office`='$cust_phone' and `password_c`='$mypassword' and A.deleted=0";
        
                }else{

                    return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => false,'result'=>'Invalid Phone Number']);
                }
                
                // //****************LOG Creation*********************
                
                $APILogFile = $config['api_log_file_path'].'clientlogin.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('cust_phone'=>$cust_phone,'password'=>$password,'condition'=>$condition);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\nclientlogin Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);				
                fclose($handle);
                // //****************ENd OF Code*****************
				
                if(!empty($condition))
                {
                    //echo $query = "SELECT * FROM `accounts` as A inner join `accounts_cstm` as B on A.id=B.id_c where $condition";

                    $result = $db->sidebar_query("SELECT * FROM `accounts` as A inner join `accounts_cstm` as B on A.id=B.id_c where $condition");
                    
                    if(!empty($result))
                    {
                      $datetime = date("Y-m-d H:i:s");
                      $client_id=$result[0]['id'];
                      unset($result[0]['id_c']);
                      $db->execute("UPDATE `accounts_cstm` SET `last_login_c` ='$datetime' WHERE `id_c`='$client_id'");
                     return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>$result]);
                    }else{
                            return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => false,'result'=>'Please check userphone and password']);
                        }
                 }else{
                        return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => false,'result'=>NULL]);
                    }
              

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});


$app->map(['POST'],'/clientregistration', function( $request,$response,$args) {
	try {
                require '../includes/DBOperations.php';
                $customer_name =  $request->getParam('customer_name');
                $mobile = $request->getParam('mobile');
                //$password = $request->getParam('password');
				
				//****************LOG Creation*********************
				$APILogFile = $config['api_log_file_path'].'clientregistration.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('customer_name'=>$customer_name,'mobile'=>$mobile);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nclientregistration Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
                //validate parameters
                $customer_name = $rest->validateParameter('customer_name', $customer_name, STRING);
                $mobile = $rest->validateParameter('mobile',$mobile, STRING);
                //$password = $rest->validateParameter('password',$password, STRING);
              
                //$mypassword = md5($password);
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();

                $uuid = getGuid();

                    $query = "INSERT INTO `accounts`(`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `assigned_user_id`,`phone_office`) VALUES ('$uuid','$customer_name','$datetime','$datetime','1','1','1','$mobile')";
					
                    $status = $db->insert_query($query,'accounts','phone_office',trim($mobile));
                    if($status=='exist')
                    {
                        return $this->response->withJson(['statuscode' => CONFLICT, 'responseMessage' => false,'result'=>'Client Already exist']);
                        exit;
                    
                    }elseif($status=='insert'){

                        $insert_custom = "INSERT INTO `accounts_cstm`(`id_c`, `customer_status_c`, `gateway_c`, `ip_address_c`) VALUES ('$uuid','active','android','$user_ip')";
                        if($db->execute($insert_custom))
                        {
                            return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'user_id'=>$uuid]);
                        }
                        
                           
                        }else{
                            return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => false,'result'=>"Insertion failed"]);
                        }
           
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/socialmediaregistration', function( $request,$response,$args) {
	try {
                header('Content-Type: application/json; charset=utf-8');
                header("Access-Control-Allow-Origin: *");
                 require '../includes/DBOperations.php';
                 $directory = __DIR__ . '/images';
                $client_name =  $request->getParam('client_name');
                $email_address = $request->getParam('email_address');
                $social_unique_id = $request->getParam('social_unique_id');
                $social_type = $request->getParam('social_type');
                $uploadedFiles = $request->getUploadedFiles();
                $uploadedFile = $uploadedFiles['profile_image'];
                
				$mypassword = md5($password);
				//****************LOG Creation*********************
				$APILogFile = $config['api_log_file_path'].'socialmediaregistration.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('client_name'=>$client_name,'email_address'=>$email_address,'social_unique_id'=>$social_unique_id,'social_type'=>$social_type,'mypassword'=>$mypassword,'uploadedFile'=>$uploadedFile);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nsocialmediaregistration Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
                //validate parameters
                $client_name = $rest->validateParameter('client_name', $client_name, STRING);
                $email_address = $rest->validateParameter('email_address',$email_address, STRING);
                $social_unique_id = $rest->validateParameter('social_unique_id',$social_unique_id, STRING);
                $social_type= $rest->validateParameter('social_type',$social_type, STRING);
                $password = randomPassword();
                
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();

                    

                 $uuid = getGuid();

                    $query = "INSERT INTO `accounts`(`id`, `name`, `date_entered`, `created_by`, `assigned_user_id`) VALUES ('$uuid','$client_name','$datetime','1','1')";
                   // exit;
                    $status = $db->insert_query($query,'email_addresses','email_address',$email_address);
                
                    if($status=='exist')
                    {
                        return $this->response->withJson(['statuscode' => CONFLICT, 'responseMessage' => 'false','result'=>'Client Already exist']);
                        exit;
                    
                    }elseif($status=='insert'){
                        if ($uploadedFile->getError() === UPLOAD_ERR_OK) 
                        {

                             $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                            $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
                            $filename = sprintf('%s.%0.8s', $basename, $extension);

                            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

                            //$filename = moveUploadedFile($directory, $uploadedFile);
                            $response->write('uploaded ' . $filename . '<br/>');
                        }
                        
                        $custom_query = "INSERT INTO `accounts_cstm`(`id_c`, `password_c`, `gateway_c`, `social_footprint_c`, `last_login_c`, `ip_address_c`, `social_unique_id_c`, `image_path_c`) VALUES ('$uuid','$mypassword','" . GATEWAY . "','$social_type','$datetime','$user_ip',' $social_unique_id','$directory')";
                        $db->execute( $custom_query);
                        $db->get_update_entity_email_address($uuid,$email_address,'update');

                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','user_id'=>$uuid]);
                           
                        }else{
                            return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>"Insertion failed"]);
                        }


           
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/addClientFCMToken', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                $fcm_id =  $request->getParam('fcm_id');
                $client_id =  $request->getParam('client_id');
                $device_id =  $request->getParam('device_id');
                $fcm_token =  $request->getParam('fcm_token');
				
				//****************LOG Creation*********************
				$APILogFile = 'addClientFCMToken.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('fcm_id'=>$fcm_id,'client_id'=>$client_id,'device_id'=>$device_id,'fcm_token'=>$fcm_token);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddClientFCMToken Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $fcm_id = $rest->validateParameter('fcm_id', $fcm_id, STRING , false);
                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                $device_id = $rest->validateParameter('device_id', $device_id, STRING);
                $fcm_token = $rest->validateParameter('fcm_token', $fcm_token, STRING);
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
                $query = "INSERT INTO `mt_client_fcm_token`(`client_id`, `device_id`, `fcm_token`, `date_created`, `ip_address`, `source`) VALUES ('$client_id','$device_id','$fcm_token','$datetime','$user_ip','android')";
                $insert_id = $db->insert_FCM_token($query,'Client',$fcm_token,$fcm_id);
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
$app->map(['POST'],'/addClientAddresses', function( $request,$response,$args) {
	try {
           
                require '../includes/DBOperations.php';
                $client_id =  $request->getParam('client_id');
                $mobile_no =  $request->getParam('mobile_no');
                $pincode =  $request->getParam('pincode');
                $house_no =  $request->getParam('house_no');
                $street =  $request->getParam('street');
                $landmark =  $request->getParam('landmark');
                $city =  $request->getParam('city');
                $state =  $request->getParam('state');
                $address_type =  $request->getParam('address_type');
                $latitude =  $request->getParam('latitude');
                $longitude =  $request->getParam('longitude');
                
                //****************LOG Creation*********************
                $APILogFile = $config['api_log_file_path'].'addClientAddresses.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('client_id'=>$client_id,'mobile_no'=>$mobile_no,'pincode'=>$pincode,'house_no'=>$house_no,'street'=>$street,'landmark'=>$landmark,'city'=>$city,'state'=>$state,'address_type'=>$address_type,'latitude'=>$latitude,'longitude'=>$longitude);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\naddClientAddresses Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);               
                fclose($handle);
                //****************ENd OF Code*****************
                
                //validate parameters
                $client_id = $rest->validateParameter('client_id', $client_id, STRING);
                $mobile_no = $rest->validateParameter('mobile_no', $mobile_no, STRING,false);
                $pincode = $rest->validateParameter('pincode', $pincode, STRING);
                $house_no = $rest->validateParameter('house_no', $house_no, STRING,false);
                $street = $rest->validateParameter('street', $street, STRING);
                $landmark = $rest->validateParameter('landmark', $landmark, STRING,false);
                $city = $rest->validateParameter('city', $city, STRING);
                $state = $rest->validateParameter('state', $state, STRING);
                $address_type = $rest->validateParameter('address_type', $address_type, STRING);
                $latitude = $rest->validateParameter('latitude', $latitude, INTEGER);
                $longitude = $rest->validateParameter('longitude', $longitude, INTEGER);
                $formatted_address = $street." ".$landmark." ".$city;
                $combined_address = $house_no." ".$street." ".$landmark." ".$city." ".$state; 
                //$get_lat_long = get_details_from_address($formatted_address);
                // $latitude = $longitude ='';
                // if(!empty($get_lat_long))
                // {
                //     $latitude = (isset($get_lat_long['lat'])?$get_lat_long['lat']:'');
                //     $longitude = (isset($get_lat_long['long'])?$get_lat_long['long']:'');
                // }
                $user_ip = getUserIP();
                $uuid = getGuid();
                $datetime = date("Y-m-d H:i:s");
                $query = "INSERT INTO `ply_customer_addresses`(`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `assigned_user_id`, `formatted_address`, `city`, `state`, `pincode`, `house_number`, `street`, `landmark`, `gateway`, `address_type`, `latitude`, `longitude`, `ip_address`) VALUES ('$uuid','','$datetime','$datetime','1','1','1','$formatted_address','$city','$state','$pincode','$house_no','$combined_address','$landmark','android','$address_type','$latitude','$longitude','$user_ip')";
             
                if($db->execute($query)) 
                {
                    $uuid1 = getGuid();
                    $insert_relation_table = "INSERT INTO `accounts_ply_customer_addresses_1_c`(`id`, `date_modified`,`accounts_ply_customer_addresses_1accounts_ida`, `accounts_ply_customer_addresses_1ply_customer_addresses_idb`) VALUES ('$uuid1','$datetime','$client_id','$uuid')";
                    if($db->execute($insert_relation_table))
                    {
                         return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>'Address Added Successfully']);
                    }
                 
                }else{
                  return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'sorry address not inserted']);
                }
           
                

    } catch (ResourceNotFoundException $e) { 
        $app->response()->status(404);
  } 
});

$app->map(['POST'],'/getClientAddresses', function( $request,$response,$args) {
	try {
           
                require '../includes/DBOperations.php';
                $client_id =  $request->getParam('client_id');
                //validate parameters
                $client_id = $rest->validateParameter('client_id', $client_id, STRING);
               
                $query = "SELECT B.* FROM `accounts_ply_customer_addresses_1_c` as A inner join `ply_customer_addresses` as B on A.`accounts_ply_customer_addresses_1ply_customer_addresses_idb`=B.id WHERE A.deleted=0 and B.deleted=0 and A.`accounts_ply_customer_addresses_1accounts_ida`='$client_id'";
                
                $result = $db->sidebar_query($query);
                if(!empty($result)) 
                {
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>$result]);
                }else{
                  return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'sorry address not found']);
                }
            
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/getClientOrders', function( $request,$response,$args) {
  try {
          require_once('dbconnect.php');
          if(defined('SECRETE_KEY'))
          {
              $client_id =  $request->getParam('client_id');
              $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
              $query = "SELECT *,restaurant_name FROM `mt_order` as A inner join mt_order_details as B on A.`order_id`=B.order_id inner join mt_order_delivery_address as C on A.`order_id` = C.`order_id` inner join mt_merchant as D on A.merchant_id = D.id WHERE A.`client_id`='$client_id' and LOWER(A.`status`) = LOWER('delivered') group by A.`order_id`";
              $result = $db->sidebar_query($query);
             
             if(!empty($result)){
              return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
              
                  }else{
                     return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'true','result'=>NULL]);
                }
                  
          }else{
              return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
          }



  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 


});
$app->map(['POST'],'/getMerchantAcceptanceStatus', function( $request,$response,$args) {
  try {
          require_once('dbconnect.php');
          if(defined('SECRETE_KEY'))
          {
              $order_id =  $request->getParam('order_id');
              $merchant_id =  $request->getParam('merchant_id');

              $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
              $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);

              $query = "SELECT `status` FROM `mt_order` WHERE `order_id`='$order_id' and `merchant_id`='$merchant_id'";
              $result = $db->sidebar_query($query);
             
             if(!empty($result)){
              return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
              
                  }else{
                     return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'true','result'=>NULL]);
                }
                  
          }else{
              return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
          }



  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 


});
$app->map(['POST'],'/getAllActiveOffers', function( $request,$response,$args) {
  try {
          require_once('dbconnect.php');
          if(defined('SECRETE_KEY'))
          {
              
              $query = "SELECT * FROM `mt_offers` WHERE  `status`='active'";
              $result = $db->sidebar_query($query);
              $flag =0;
              if(!empty($result))
              {
               
                for($i=0;$i<count($result);$i++)
                {
                  
                  $from =  $result[$i]['valid_from'];
                  $to =  $result[$i]['valid_to'];
                  $today = date('Y-m-d');
                  
                  if($from <= $today && $to >= $today)
                  {
                    $flag=1;
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                  }

                }
                if($flag ==0)
                {
                  return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No Offer Available']);

                }
              
              
             }else{
                     return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No Offer Available']);
                }
                  
          }else{
              return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
          }



  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 


});
$app->map(['POST'],'/clientForgotPassword', function( $request,$response,$args) {
  try {
          require '../includes/DBOperations.php';
                $phone_number =  $request->getParam('phone_number');
                //validate parameters
                $phone_number = $rest->validateParameter('phone_number', $phone_number, STRING);
 
                $condition='';
                // if(valid_email($username))
                // {
                //     $condition = "`email_address`='$username'";
                   
                // }else 
                if(validate_mobile($phone_number))
                {
                    $condition = "`phone_office`='$phone_number' and deleted=0";
        
                }
                
                //****************LOG Creation*********************
                $APILogFile = $config['api_log_file_path'].'clientForgotPassword.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('uname'=>$username,'condition'=>$condition);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\nclientForgotPassword Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);               
                fclose($handle);
                //****************ENd OF Code*****************
                if(!empty($condition))
                {
                    $result = $db->forgot_password("SELECT * FROM `accounts` WHERE $condition",$condition);
                    
                    if(!empty($result))
                    {
                        
                      if(isset($result['updated_password']))
                      {
                        $updated_pwd = $result['updated_password'];
                        $message = 'Hi, your updated password is: ';
                        $message .= $updated_pwd;
                        $message .= '.From Placeefy Team';
                        sendsms($phone_number, $message);
                      }
                     return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>$result]);
                    }else{
                            return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => false,'result'=>'Invalid Phone Number.please contact to admin']);
                        }
                 }else{
                        return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => false,'result'=>'Invalid Phone Number.please contact to admin']);
                    }
				



  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 


});

$app->map(['POST'],'/changeClientPassword', function( $request,$response,$args) {
  try {
                require '../includes/DBOperations.php';
                $client_id =  $request->getParam('client_id');
                $password =  $request->getParam('password');
                //validate parameters
                $client_id = trim($rest->validateParameter('client_id', $client_id, STRING));
                $password = trim($rest->validateParameter('password', $password, STRING));

                // //****************LOG Creation*********************
                
                $APILogFile = $config['api_log_file_path'].'changeClientPassword.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('client_id'=>$client_id,'password'=>$password);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\nclientlogin Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);               
                fclose($handle);
                // //****************ENd OF Code*****************

                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
                
                $sql = "SELECT * FROM `accounts` as a inner join `accounts_cstm` as b on a.id=b.id_c WHERE `id`='$client_id' and deleted=0";
                if($db->db_num($sql))
                {
                    $query = "UPDATE `accounts_cstm` as t1 inner join `accounts` as t2 on t1.id_c=t2.id SET `password_c`=MD5('$password'),`date_modified`='$datetime',`ip_address_c`='$user_ip' WHERE `id_c`='$client_id'";
                    $result = $db->execute($query);
                    if($result)
                    {
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>'Password has been changed successfully']);
                    }else{
                            return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'Sorry password has not been changed.']);
                        }
                }else{
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'Inavlid Client ID.']);
                }


  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 


});

$app->map(['POST'],'/updateClientInfo', function( $request,$response,$args) {
  try {
                require '../includes/DBOperations.php';
                $client_id =  $request->getParam('client_id');
                $mobile_no =  $request->getParam('mobile_no');
                $email_id =  $request->getParam('email_id');
                $customer_type =  $request->getParam('customer_type'); //employee, student and Other
                $gender =  $request->getParam('gender');              
                $meal_preference =  $request->getParam('meal_preference'); //veg , nonveg or egg
                $dob =  $request->getParam('dob');
                
                //****************LOG Creation*********************
                $APILogFile = $config['api_log_file_path'].'updateClientInfo.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('client_id'=>$client_id,'mobile_no'=>$mobile_no,'email_id'=>$email_id,'customer_type'=>$customer_type,'gender'=>$gender,'meal_preference'=>$meal_preference,'dob'=>$dob);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\nupdateClientInfo Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);               
                fclose($handle);
                //****************ENd OF Code*****************
                
                
                //validate parameters
                $client_id = $rest->validateParameter('client_id', $client_id, STRING);
                $mobile_no = $rest->validateParameter('mobile_no', $mobile_no, STRING);
                $email_id = $rest->validateParameter('email_id', $email_id, STRING);
                $customer_type = $rest->validateParameter('customer_type', $customer_type, STRING);
                $gender = strtolower($rest->validateParameter('gender', $gender, STRING));
                $meal_preference = strtolower($rest->validateParameter('meal_preference', $meal_preference, STRING));
                $dob = $rest->validateParameter('dob', $dob, STRING);

                $dateofbirth = str_replace('/', '-', $dob);
                $DOB =  date('Y-m-d', strtotime($dateofbirth));

                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();
                $sql = "SELECT * FROM `accounts` WHERE `id`='$client_id' and deleted=0";
                if($db->db_num($sql))
                {
                    $query = "UPDATE `accounts` as t1 inner join `accounts_cstm` as t2 on t1.id=t2.id_c SET `phone_office`='$mobile_no',`account_type`='$customer_type',`gender_c`='$gender',`meal_preference_c`='$meal_preference',`dob_c`='$DOB' where `id_c`='$client_id'";
                    $result = $db->execute($query);
                    
                    if($result)
                    {
                        $db->get_update_entity_email_address($client_id,$email_id,'update');
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>'client details has been updated successfully']);
                    }else{
                            return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'sorry client details has not been updated.please enter a valid details']);
                        }
                }else{
                    
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'Invalid Client Id.please contact admin']);
                }


  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 


});
$app->map(['POST'],'/updateClientAddresse', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $client_id =  $request->getParam('client_id');
                $mobile_no =  $request->getParam('mobile_no');
                $pincode =  $request->getParam('pincode');
                $house_no =  $request->getParam('house_no');
                $street =  $request->getParam('street');
                $landmark =  $request->getParam('landmark');
                $city =  $request->getParam('city');
                $state =  $request->getParam('state');
                $address_type =  $request->getParam('address_type');
				
				//****************LOG Creation*********************
				$APILogFile = 'updateClientAddresse.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('client_id'=>$client_id,'mobile_no'=>$mobile_no,'pincode'=>$pincode,'house_no'=>$house_no,'street'=>$street,'landmark'=>$landmark,'city'=>$city,'state'=>$state,'address_type'=>$address_type);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nupdateClientAddresse Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                $mobile_no = $rest->validateParameter('mobile_no', $mobile_no, STRING,false);
                $pincode = $rest->validateParameter('pincode', $pincode, STRING);
                $house_no = $rest->validateParameter('house_no', $house_no, STRING,false);
                $street = $rest->validateParameter('street', $street, STRING);
                $landmark = $rest->validateParameter('landmark', $landmark, STRING,false);
                $city = $rest->validateParameter('city', $city, STRING);
                $state = $rest->validateParameter('state', $state, STRING);
                $address_type = $rest->validateParameter('address_type', $address_type, STRING);
                $formatted_address = $street." ".$landmark." ".$city;
                $combined_address = $house_no." ".$street." ".$landmark." ".$city." ".$state; 
                $get_lat_long = get_details_from_address($formatted_address);
                $latitude = $longitude ='';
                if(!empty($get_lat_long))
                {
                    $latitude = (isset($get_lat_long['lat'])?$get_lat_long['lat']:'');
                    $longitude = (isset($get_lat_long['long'])?$get_lat_long['long']:'');
                }
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
				$sql = "SELECT `client_id` FROM `mt_client` WHERE `client_id`='$client_id'";
				if($db->db_num($sql))
				{
				    $query = "UPDATE `mt_client_adresses` SET `formatted_address`='$formatted_address',`city`='$city',`state`='$state',`pincode`='$pincode',`house_no`='$house_no',`street`='$street',`landmark`='$landmark',`mobile`='$mobile_no',`date_modified`='$datetime',`ip_address`='$user_ip',`source`='android',`address_type`='$address_type',`lat`='$latitude',`lng`='$longitude' WHERE `client_id`='$client_id'";
				   
					if($db->execute($query)) 
					{
					  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'client address has been updated Successfully']);
					}else{
					  return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry address not updated']);
					}
				}else{
					
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Invalid Client Id.please contact admin']);
				}
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/getClientDetails', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $username =  $request->getParam('uname');
                //validate parameters
                $username = $rest->validateParameter('uname', $username, STRING);
                $condition='';
                if(valid_email($username))
                {
                    $condition = "`email_address`='$username'";
                   
                }else if(validate_mobile($username))
                {
                    $condition = "`contact_phone`='$username'";
        
                }
                if(!empty($condition))
                {
                
               
					$query = "SELECT * FROM `mt_client` WHERE $condition ";
					$result = $db->sidebar_query($query);
					if(!empty($result)) 
					{
					  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
					}else{
					  return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Invalid Details']);
					}
				}else{
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Invalid Details']);
				}
                
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/addClientReview', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $merchant_id =  $request->getParam('merchant_id');
                $client_id = $request->getParam('client_id');
                $review = $request->getParam('review');
                $rating = $request->getParam('rating');
				$order_id = $request->getParam('order_id');
				
				//****************LOG Creation*********************
				$APILogFile = 'addClientReview.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('merchant_id'=>$merchant_id,'client_id'=>$client_id,'review'=>$review,'rating'=>$rating,'order_id'=>$order_id);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddClientReview Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $client_id = $rest->validateParameter('client_id',$client_id, INTEGER);
                $review = $rest->validateParameter('review',$review, STRING);
                $rating = $rest->validateParameter('rating',$rating, INTEGER);
				$order_id = $rest->validateParameter('order_id',$order_id, INTEGER);
 
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();

                if( $rating>0 && $rating<=5)
				{
					$sql = "SELECT `id` FROM `mt_review` WHERE `merchant_id`='$merchant_id' and `client_id`='$client_id' and `order_id`='$order_id'";
					//echo $db->db_num($sql);exit;
					if($db->db_num($sql)==0)
					{
						$query = "INSERT INTO `mt_review`( `merchant_id`, `client_id`, `review`, `rating`,`date_created`, `ip_address`, `order_id`, `reply_from`) VALUES ('$merchant_id','$client_id','$review','$rating','$datetime','$user_ip','$order_id','".$config['reply_from']."')";
						 if($db->execute($query))
						 {
					  
							return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Rating has been inserted']);
							
						
						}else{
								return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry Rating has been not inserted']);
							}
					}else{
							return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry Client has already given the review for this order']);
					}
				}else{
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Rating must be in between 1 or 5']);
				}
            }else{
                        return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
                    }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/addMerchnatReview', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $merchant_id =  $request->getParam('merchant_id');
                $client_id = $request->getParam('client_id');
                $review = $request->getParam('review');
                $rating = $request->getParam('rating');
				
				
				//****************LOG Creation*********************
				$APILogFile = 'addMerchnatReview.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('merchant_id'=>$merchant_id,'client_id'=>$client_id,'review'=>$review,'rating'=>$rating);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddMerchnatReview Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $client_id = $rest->validateParameter('client_id',$client_id, INTEGER);
                $review = $rest->validateParameter('review',$review, STRING);
                $rating = $rest->validateParameter('rating',$rating, INTEGER);
				
 
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();

                if( $rating>0 && $rating<=5)
				{
					$sql = "SELECT `id` FROM `mt_review` WHERE `merchant_id`='$merchant_id' and `client_id`='$client_id'";
					//echo $db->db_num($sql);exit;
					if($db->db_num($sql)==0)
					{
						$query = "INSERT INTO `mt_review`( `merchant_id`, `client_id`, `review`, `rating`,`date_created`, `ip_address`, `reply_from`) VALUES ('$merchant_id','$client_id','$review','$rating','$datetime','$user_ip','".$config['reply_from']."')";
						 if($db->execute($query))
						 {
					  
							return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Rating has been inserted']);
							
						
						}else{
								return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry Rating has been not inserted']);
							}
					}else{
							return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry Client has already given the review to this merchant']);
					}
				}else{
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Rating must be in between 1 or 5']);
				}
            }else{
                        return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
                    }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/updateClientMobileNumber', function( $request,$response,$args) {
  try {
                require '../includes/DBOperations.php';
                $client_id =  $request->getParam('client_id');
                $mobile_no =  $request->getParam('mobile_no');
                
                
                //****************LOG Creation*********************
                $APILogFile = $config['api_log_file_path'].'updateClientMobileNumber.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('client_id'=>$client_id,'mobile_no'=>$mobile_no);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\nupdateClientInfo Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);               
               
                //****************ENd OF Code*****************
                
                
                //validate parameters
                $client_id = $rest->validateParameter('client_id', $client_id, STRING);
                $mobile_no = $rest->validateParameter('mobile_no', $mobile_no, STRING);
                
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();
                $sql = "SELECT * FROM `accounts` WHERE `id`='$client_id' and deleted=0";
                if($db->db_num($sql))
                {
                    $otp = generateNumericOTP(4);
                    $query = "UPDATE `accounts_cstm` as t1 inner join `accounts` as t2 on t1.id_c=t2.id SET `phone_office`='$mobile_no',`date_modified`='$datetime',`ip_address_c`='$user_ip',`otp_c`='$otp' WHERE `id_c`='$client_id'";
                    $result = $db->execute($query);
                    
                    if($result)
                    {
                         if(validate_mobile($mobile_no))
                          {
                            $message = 'Dear user, your OTP is: ';
                            $message .= $otp;
                            $message .= '.From Placeefy Team';
                            sendsms($mobile_no, $message);
                          }else{

                            $logMessage = "\nupdateClientInfo Result at $timestamp :-\nInvalid Mobile number:$mobile_no";
                            fwrite($handle, $logMessage);
                            fclose($handle);    
                          }
                       
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>'client mobile number has been updated successfully']);
                    }else{
                            return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'sorry client details has not been updated.please enter a valid details']);
                        }
                }else{
                    
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'Invalid Client Id.please contact admin']);
                }


  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 
});

$app->map(['POST'],'/verifyClientOTP', function( $request,$response,$args) {
  try {
                require '../includes/DBOperations.php';
                $client_id =  $request->getParam('client_id');
                $OTP =  $request->getParam('OTP');
                
                
                //****************LOG Creation*********************
                $APILogFile = $config['api_log_file_path'].'verifyClientOTP.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('client_id'=>$client_id,'OTP'=>$OTP);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\nverifyClientOTP Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);               
               
                //****************ENd OF Code*****************
                
                
                //validate parameters
                $client_id = $rest->validateParameter('client_id', $client_id, STRING);
                $OTP = $rest->validateParameter('OTP', $OTP, STRING);
                
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();
                $sql = "SELECT * FROM `accounts` WHERE `id`='$client_id' and deleted=0";
                if($db->db_num($sql))
                {
                   
                    $query = "SELECT * FROM `accounts_cstm` WHERE `otp_c`=TRIM('$OTP') and `id_c`=
                    TRIM('$client_id')";
                    
                    if($db->db_num($query))
                    {
                       $query1 = "UPDATE `accounts_cstm` as t1 inner join `accounts` as t2 on t1.id_c=t2.id SET `date_modified`='$datetime',`ip_address_c`='$user_ip',`otp_verified_flag_c`='verified' WHERE `id_c`='$client_id'";
                       
                       $result = $db->execute($query1);
                       if($result)
                       {

                         return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>'OTP Verified']);
                       }
                       
                    }else{
                            return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'sorry client details has not been updated.please enter a valid details']);
                        }
                }else{
                    
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'Invalid Client Id.please contact admin']);
                }


  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 


});
$app->map(['POST'],'/generateOTP', function( $request,$response,$args) {
  try {
                require '../includes/DBOperations.php';
                $client_id =  $request->getParam('client_id');
                $mobile_no =  $request->getParam('mobile_no');
                
                
                //****************LOG Creation*********************
                $APILogFile = $config['api_log_file_path'].'generateOTP.txt';
                $handle = fopen($APILogFile, 'a');
                $timestamp = date('Y-m-d H:i:s');
                $logArray = array('client_id'=>$client_id,'mobile_no'=>$mobile_no);
                $logArray1 = print_r($logArray, true);
                $logMessage = "\ngenerateOTP Result at $timestamp :-\n$logArray1";
                fwrite($handle, $logMessage);               
                //****************ENd OF Code*****************
                
                
                //validate parameters
                $client_id = $rest->validateParameter('client_id', $client_id, STRING);
                $mobile_no = $rest->validateParameter('mobile_no', $mobile_no, STRING);
                
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();
                $sql = "SELECT * FROM `accounts` WHERE `id`='$client_id' and deleted=0";
                if($db->db_num($sql))
                {
                    $otp = generateNumericOTP(4);
                    $query = "UPDATE `accounts_cstm` as t1 inner join `accounts` as t2 on t1.id_c=t2.id SET 
                    `date_modified`='$datetime',`ip_address_c`='$user_ip',`otp_c`='$otp' WHERE `id_c`='$client_id'";
                    $result = $db->execute($query);
                    
                    if($result)
                    {
                         if(validate_mobile($mobile_no))
                          {
                            $message = 'Dear user, your OTP is: ';
                            $message .= $otp;
                            $message .= '.From Placeefy Team';
                            sendsms($mobile_no, $message);
                          }else{

                            $logMessage = "\ngenerateOTP Result at $timestamp :-\nInvalid Mobile number:$mobile_no";
                            fwrite($handle, $logMessage);
                            fclose($handle);    
                          }
                       
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true,'result'=>'OTP has been sent successfully on your mobile number']);
                    }else{
                            return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'sorry OTP not genertaed']);
                        }
                }else{
                    
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false,'result'=>'Invalid Client Id.please contact admin']);
                }


  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 
});


});
