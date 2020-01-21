<?php
$app->group('/api', function(\Slim\App $app) {

    $app->map(['POST'],'/dishestextviewsuggestion', function( $request,$response,$args) {
        try {
                require_once('dbconnect.php');
                if(defined('SECRETE_KEY'))
                {
                                       
                   $search_key =  $request->getParam('search_key');
                   $search_key = $rest->validateParameter('search_key', $search_key, STRING);
                   //SELECT * FROM mt_dishes, mt_merchant WHERE `dish_name` = 'some_val' AND restaurant_name = 'some_val'

                   $result = $db->sidebar_query("SELECT * FROM `mt_dishes` WHERE `dish_name` like '$search_key%'");
                   if(!empty($result)){
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                    }else{
                        return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
                    }
                   

                        
                }else{
                    return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
                }



        } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	


});


$app->map(['POST'],'/merchanttextviewsuggestion', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                                   
               $search_key =  $request->getParam('search_key');
               $search_key = $rest->validateParameter('search_key', $search_key, STRING);

               $result = $db->sidebar_query("SELECT * FROM `mt_merchant` WHERE `restaurant_name` like '$search_key%'");
               if(!empty($result)){
                return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                }else{
                    return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
                }
               

                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
}	


});

$app->map(['GET','POST'],'/getallmerchantwithin10km', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                                   
                $lat1 =  $request->getParam('latitude');
                $long1 =  $request->getParam('longitude');
                //$city_name =  $request->getParam('city_name');
                $lat1 = $rest->validateParameter('latitude', $lat1, INTEGER);
                $long1 = $rest->validateParameter('longitude', $long1, INTEGER);
                //$city_name = $rest->validateParameter('city_name', $city_name, STRING);
				
				//****************LOG Creation*********************
				$APILogFile = 'getallmerchantwithin10km.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logMessage = "\ngetallmerchantwithin10km Result at $timestamp :- LAT: $lat1 LONG:$long1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
	
               $result = $db->Fetch($lat1,$long1);
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
$app->map(['GET','POST'],'/getallcuisine', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                                   
               
               $result = $db->fetch_alls('`mt_cuisine`');
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

$app->map(['POST'],'/merchantregistration', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                                   
               
                $restaurant_name =  $request->getParam('restaurant_name');
                $restroname = explode(" ",$restaurant_name);
                $restro_slug = strtolower($restroname[0])."-".strtolower($restroname[1]);
                $GST_number =  $request->getParam('GST_number');
                $PAN_number =  $request->getParam('PAN_number');
                $Aadhar_number =  $request->getParam('Aadhar_number');
                $FSSI_number =  $request->getParam('FSSI_number');
                $restaurant_phone =  $request->getParam('restaurant_phone');
                $contact_name =  $request->getParam('contact_name');
                $contact_email =  $request->getParam('contact_email');
                $street =  $request->getParam('street');
                $city =  $request->getParam('city');
                $state =  $request->getParam('state');
                $country_code =  $request->getParam('country_code');
                $zip_code =  $request->getParam('zip_code');
                //$cuisine =  $request->getParam('cuisine');
                $restaurant_photo =  $request->getParam('restaurant_logo');  //image should be in base64 format
                $password =  $request->getParam('password');
                $latitude =  $request->getParam('latitude');
                $longitude =  $request->getParam('longitude');

                $restaurant_name = $rest->validateParameter('restaurant_name', $restaurant_name, STRING);
                $GST_number = $rest->validateParameter('GST_number', $GST_number, STRING,false);
                $PAN_number = $rest->validateParameter('PAN_number', $PAN_number, STRING);
                $Aadhar_number = $rest->validateParameter('Aadhar_number', $Aadhar_number, STRING,false);
                $FSSI_number = $rest->validateParameter('FSSI_number', $FSSI_number, STRING);
                $restaurant_phone = $rest->validateParameter('restaurant_phone', $restaurant_phone, STRING);
                $contact_name = $rest->validateParameter('contact_name', $contact_name, STRING);
                $contact_email = $rest->validateParameter('contact_email', $contact_email, STRING);
                $street = $rest->validateParameter('street', $street, STRING);
                $city = $rest->validateParameter('city', $city, STRING);
                $state = $rest->validateParameter('state', $state, STRING);
                $country_code = $rest->validateParameter('country_code', $country_code, STRING);
                $zip_code = $rest->validateParameter('zip_code', $zip_code, STRING);
                //$cuisine = $rest->validateParameter('cuisine', $cuisine, STRING);
                $restaurant_photo = $rest->validateParameter('restaurant_logo', $restaurant_photo, STRING);
                $password = $rest->validateParameter('password', $password, STRING);
                $latitude = $rest->validateParameter('latitude', $latitude, INTEGER);
                $longitude = $rest->validateParameter('longitude', $longitude, INTEGER);
                $mypassword = md5($password);
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();

                $folderPath = "/../../../../uploads";
                $image_parts = explode(";base64,", $restaurant_photo);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $file = $folderPath . uniqid()."_".date('Ymd').'.png';
                $img_name = uniqid()."_".date('Ymd').'.png';
                file_put_contents($file, $image_base64);
                 
                $query = "INSERT INTO `mt_merchant`(`restaurant_slug`, `restaurant_name`, `restaurant_phone`, `contact_name`, `contact_email`, `country_code`, `street`, `city`, `state`, `post_code`, `logo`,`password`,`date_created`,`ip_address`,`latitude`,`lontitude`,`source`) VALUES ('$restro_slug','$restaurant_name','$restaurant_phone','$contact_name','$contact_email','$country_code','$street','$city','$state','$zip_code','$img_name','$mypassword','$datetime','$user_ip','$latitude','$longitude','android')";
                $table_name = 'mt_merchant';
                $insert_id = $db->saveRecords($query,$table_name,'`contact_email`',$contact_email);
                
                //$insert_id = $db->saveRecords($query);
               if(is_numeric($insert_id) && $insert_id>0)
               {
                    $db->insert_merchant_meta($insert_id,'merchant_gst_number',$GST_number);
                    $db->insert_merchant_meta($insert_id,'merchant_pan_number',$PAN_number);
                    $db->insert_merchant_meta($insert_id,'merchant_aadhar_number',$Aadhar_number);
                    $db->insert_merchant_meta($insert_id,'merchant_fssi_number',$FSSI_number);
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'record inserted']);
                    
                }else{
                        return $this->response->withJson(['statuscode' => CONFLICT, 'responseMessage' => 'false','result'=>'alredy exists']);
                    }
                    
        }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>'secret key not defined']);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/merchantlogin', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $username =  $request->getParam('uname');
                $password = $request->getParam('password');
                //validate parameters
                $username = $rest->validateParameter('uname', $username, STRING);
                $password = $rest->validateParameter('password',$password, STRING);
                $mypassword = md5($password);
                $condition='';
                if(valid_email($username))
                {
                    $condition = "`contact_email`='$username' and `password`='$mypassword'";
                   
                }else if(validate_mobile($username))
                {
                    $condition = "`restaurant_phone`='$username' and `password`='$mypassword'";
        
                }
				
				//****************LOG Creation*********************
				$APILogFile = 'merchantlogin.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('uname'=>$username,'password'=>$password,'condition'=>$condition);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nmerchantlogin Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
			
                if(!empty($condition))
                {
                    //$query = "SELECT * FROM `mt_client` WHERE $condition";
                    $result = $db->sidebar_query("SELECT * FROM `mt_merchant` WHERE $condition");
                    
                    if(!empty($result))
                    {
                        unset($result[0]['cuisine']);
                        $result[0]['merchant_fssi_number'] = $db->get_meta_value_from_metakey($result[0]['id'],'merchant_fssi_number');
                        $result[0]['merchant_aadhar_number'] = $db->get_meta_value_from_metakey($result[0]['id'],'merchant_aadhar_number');
                        $result[0]['merchant_pan_number'] = $db->get_meta_value_from_metakey($result[0]['id'],'merchant_pan_number');
                        $result[0]['merchant_gst_number'] = $db->get_meta_value_from_metakey($result[0]['id'],'merchant_gst_number');
                        $datetime = date("Y-m-d H:i:s");
                        $merchant_id=$result[0]['id'];
                        $db->execute("UPDATE `mt_merchant` SET `last_login`='$datetime' where `id`='$merchant_id'");
                     return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                    }else{
                            return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
                        }
                 }else{
                        return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
                    }
                }else{
                    return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
                }

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/addmerchantcuisine', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $merchant_id =  $request->getParam('merchant_id');
                $cuisine_id = $request->getParam('cuisine_id');
                //validate parameters
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $cuisine_id = $rest->validateParameter('cuisine_id',$cuisine_id, INTEGER);
             
                $table_name = 'mt_merchant_cuisine';
                $feild_value_arr = array('cuisine_id'=>$cuisine_id,'merchant_id'=>$merchant_id);
               
                    $query = "INSERT INTO `mt_merchant_cuisine`(`merchant_id`, `cuisine_id`,`source`) VALUES ('$merchant_id','$cuisine_id','android')";
                    $result = $db->insert_multiple_records($query,$table_name,$feild_value_arr);
                    
                    if($result)
                    {
                        
                     return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'cuisine has been added.']);
                    }else{
                            return $this->response->withJson(['statuscode' => CONFLICT, 'responseMessage' => 'false','result'=>'already exist']);
                        }
               
                }else{
                    return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
                }

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/getmerchantpackages', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                                   
               
              $result = $db->fetch_alls('`mt_packages`');
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

$app->map(['POST'],'/getmerchantcategory', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT * FROM `mt_merchant_categories` as A inner join mt_category as B on A.category_id = B.id where merchant_id='$merchant_id'";
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

$app->map(['POST'],'/getmerchantcategorywisemenu', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $category_id =  $request->getParam('category_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $category_id = $rest->validateParameter('category_id', $category_id, INTEGER);

                $query = "SELECT `item_name`,`item_description`,`status`,`price`,`addon_item`,`cooking_ref`,`discount`,`is_featured`,`date_created`,`date_modified`,`ingredients`,`spicydish`,`two_flavors`,`two_flavors_position`,`require_addon`,`dish`,`item_name_trans`,`item_description_trans`,`not_available`,`points_earned`,`points_disabled`,GROUP_CONCAT( B.image_name SEPARATOR ',') as images FROM `mt_item` as A left join mt_item_images as B on A.id=B.item_id WHERE `merchant_id`='$merchant_id' and `category`='$category_id' group by item_name ORDER BY item_name ASC";
                $result = $db->get_data($query);
               
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

$app->map(['POST'],'/addmerchantoffer', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                //$data = array('key1'=>60,"key2"=>120);
                //  +echo serialize($data); exit;
                $merchant_id =  $request->getParam('merchant_id');
                $offer_percentage =  $request->getParam('offer_percentage');
                $orders_over =  $request->getParam('orders_over');  //offer price
                $valid_from =  $request->getParam('valid_from');
                $valid_to =  $request->getParam('valid_to');
                $applicable_to =  $request->getParam('applicable_to'); //Delivery Pickup Dienin

                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $offer_percentage = $rest->validateParameter('offer_percentage', $offer_percentage, INTEGER);
                $orders_over = $rest->validateParameter('orders_over', $orders_over, INTEGER);
                $valid_from = $rest->validateParameter('valid_from', $valid_from, STRING);
                $valid_to = $rest->validateParameter('valid_to', $valid_to, STRING);
                $applicable_to = $rest->validateParameter('applicable_to', $applicable_to, STRING);
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();

                $from = date('Y-m-d', strtotime($valid_from));
                $to = date('Y-m-d', strtotime($valid_to));
                $query = "INSERT INTO `mt_offers`( `merchant_id`, `offer_percentage`, `offer_price`, `valid_from`, `valid_to`, `date_created`, `ip_address`, `applicable_to`) VALUES ('$merchant_id','$offer_percentage','$orders_over','$from','$to','$datetime','$user_ip','$applicable_to')";

               if($db->execute($query)){
                return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'New Offer Added successfully!']);
                
                    }else{
                       return $this->response->withJson(['statuscode' => BAD_REQUEST, 'responseMessage' => 'false','result'=>'Offer not created']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/getmerchantoffers', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $flag = 0;
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT * FROM `mt_offers` WHERE `merchant_id`='$merchant_id' and `status`='active'";
                $result = $db->sidebar_query($query);
               if(!empty($result)){
                for($i=0;$i<count($result);$i++)
                {
                   $from =  $result[$i]['valid_from'];
                   $to =  $result[$i]['valid_to'];
                   $today = date('Y-m-d');
                   if($from <= $today && $to >= $today)
                   {
                    $flag = 1;
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                   }

                }
                if($flag == 0)
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

$app->map(['POST'],'/addmerchantvoucher', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $voucher_name =  $request->getParam('voucher_name');
                $merchant_id =  $request->getParam('merchant_id');
                $number_of_voucher =  $request->getParam('number_of_voucher');
                $amount =  $request->getParam('amount');
                $voucher_type =  $request->getParam('voucher_type');  //fixed amount or percentage
                $status =  $request->getParam('status'); 
				$expiration =  $request->getParam('expiration'); 

                $voucher_name = $rest->validateParameter('voucher_name', $voucher_name, STRING);
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $number_of_voucher = $rest->validateParameter('number_of_voucher', $number_of_voucher, INTEGER);
                $amount = $rest->validateParameter('amount', $amount, INTEGER);
                $voucher_type = $rest->validateParameter('voucher_type', $voucher_type, STRING);
                $status = $rest->validateParameter('status', $status, STRING);
				$expiration = $rest->validateParameter('expiration', $expiration, STRING);
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();

                //$query = "INSERT INTO `mt_voucher_new`(`voucher_name`, `merchant_id`, `number_of_voucher`, `amount`, `voucher_type`, `status`, `date_created`,`ip_address`,`source`) VALUES ('$voucher_name','$merchant_id','$number_of_voucher','$amount','$voucher_type','$status','$datetime','$user_ip','android')";
				
				$query = "INSERT INTO `mt_voucher_new`( `merchant_id`, `voucher_name`, `voucher_type`, `amount`, `expiration`, `status`, `date_created`, `ip_address`, `source`) VALUES ('$merchant_id','$voucher_name','$voucher_type','$amount','$expiration','$status','$datetime','$user_ip','android')";

               if($db->execute($query)){
                
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Voucher has been created successfully']);  

                }else{
                       return $this->response->withJson(['statuscode' => BAD_REQUEST, 'responseMessage' => 'false','result'=>'Voucher not created']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/getmerchantvouchers', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

              
                $merchant_id =  $request->getParam('merchant_id');
				$voucher_amount =  $request->getParam('voucher_amount');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
				$voucher_amount = $rest->validateParameter('voucher_amount', $voucher_amount, INTEGER);
				//$today_date = date("Y-m-d");
                $query = "SELECT * FROM `mt_voucher_new` WHERE `merchant_id`='$merchant_id' and LOWER(`status`)=LOWER('Active') and CURDATE() <= `expiration`";
                $result = $db->sidebar_query($query);
				
                if(!empty($result))
				{
					 $voucher = array();$flag  =0;
					 
                     for($i=0;$i<count($result);$i++)
					 {
						
							
							if($result[$i]['order_over'] <= $voucher_amount)
							{
								$flag = 1;
								$result[$i]['amount'] = number_format((float)$result[$i]['amount'], 2, '.', '');
								array_push($voucher , $result);
							}
						 
						 
						 
						 
					 }
					 if($flag==1)
						return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$voucher]);
					else
						return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>"sorry currently no voucher is available"]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No Voucher Available']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/getmerchantcuisines', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT `cuisine_id`,`cuisine_name`,`date_created` from mt_merchant_cuisine as A inner join mt_cuisine as B on A.cuisine_id = B.id where A.`merchant_id` = $merchant_id ORDER BY `cuisine_name` ASC";
                $result = $db->sidebar_query($query);
                if(!empty($result)){
              
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No Voucher Available']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/getmerchantcategorywiseitems', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
				
                $merchant_id =  $request->getParam('merchant_id');
				$category_type =  $request->getParam('category_type');
				$category_type = (empty($category_type))?"all":$category_type;
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
				$category_type = $rest->validateParameter('category_type', $category_type, STRING);  //all / veg /nonveg
				
				//****************LOG Creation*********************
				$APILogFile = 'getmerchantcategorywiseitems.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('merchant_id'=>$merchant_id,'category_type'=>$category_type);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetmerchantcategorywiseitems Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
                $query = "SELECT distinct `category_id`,`merchant_id` FROM `mt_merchant_categories` where `merchant_id`='$merchant_id'";
                $result = $db->get_categorywiseitems($query,$category_type);
                if(!empty($result)){
					
                      $pan_number = (!empty($db->get_merchant_meta_keyvalue($merchant_id,'merchant_pan_number')))?ucfirst($db->get_merchant_meta_keyvalue($merchant_id,'merchant_pan_number')):'';
					  $aadhar_number = (!empty($db->get_merchant_meta_keyvalue($merchant_id,'merchant_aadhar_number')))?ucfirst($db->get_merchant_meta_keyvalue($merchant_id,'merchant_aadhar_number')):'';
					  $fassi_number = (!empty($db->get_merchant_meta_keyvalue($merchant_id,'merchant_fssi_number')))?ucfirst($db->get_merchant_meta_keyvalue($merchant_id,'merchant_fssi_number')):'';
					  $gst_number = (!empty($db->get_merchant_meta_keyvalue($merchant_id,'merchant_gst_number')))?ucfirst($db->get_merchant_meta_keyvalue($merchant_id,'merchant_gst_number')):'';
					  $merchant_login_status = (!empty($db->get_merchant_meta_keyvalue($merchant_id,'merchant_login_status')))?ucfirst($db->get_merchant_meta_keyvalue($merchant_id,'merchant_login_status')):'Off';
                    
					
					return $response->withStatus(200)->withHeader("Content-Type", "application/json;charset=utf-8")->write(json_encode(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result,'merchant_pan_number'=>$pan_number,'merchant_aadhar_number'=>$aadhar_number,'merchant_fassi_number'=>$fassi_number,'merchant_gst_number'=>$gst_number,'merchant_login_status'=>$merchant_login_status], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT |  JSON_UNESCAPED_UNICODE));  // add JSON_UNESCAPED_UNICODE flag
					
					//return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result,'merchant_pan_number'=>$pan_number,'merchant_aadhar_number'=>$aadhar_number,'merchant_fassi_number'=>$fassi_number,'merchant_gst_number'=>$gst_number,'merchant_login_status'=>$merchant_login_status]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry No items available for this merchant']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/additemtocart', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $client_id =  $request->getParam('client_id');
                $merchant_id =  $request->getParam('merchant_id');
                $item_id =  $request->getParam('item_id');
                $item_price =  $request->getParam('item_price');
                $quantity =  $request->getParam('quantity');
                $item_name =  $request->getParam('item_name');
                $quantity_flag =  $request->getParam('quantity_flag');

                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $item_id = $rest->validateParameter('item_id', $item_id, INTEGER);
                $item_price = $rest->validateParameter('item_price', $item_price, INTEGER);
                $quantity = $rest->validateParameter('quantity', $quantity, INTEGER);
                $item_name = $rest->validateParameter('item_name', $item_name, STRING);
                $quantity_flag = $rest->validateParameter('quantity_flag', $quantity_flag, STRING);
                $user_ip = getUserIP();
				
				//****************LOG Creation*********************
				$APILogFile = 'additemtocart.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('client ID'=>$client_id,'Merchant ID'=>$merchant_id,'Item ID'=>$item_id,'item_price'=>$item_price,'quantity'=>$quantity,'item_name'=>$item_name,'quantity_flag'=>$quantity_flag);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nadditemtocart Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
				if($item_price<=0)
				{
					return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Item Price should be greater than zero']);
					exit;
				}
                $query = "INSERT INTO `mt_cart`( `client_id`, `merchant_id`, `item_id`, `item_name`, `item_price`, `quantity`, `ip_address`, `source`,`quantity_flag`) VALUES ('$client_id','$merchant_id','$item_id','$item_name','$item_price','$quantity','$user_ip','android','$quantity_flag')";
                $cart_id = $db->insert_execute($query,$client_id,$merchant_id,$item_id,$quantity_flag,$item_price);
                if(is_numeric($cart_id) && $cart_id>0){
									
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$cart_id]);
                
                    }else if($cart_id == 'qty_updated'){
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Quantity updated successfully']);
                    }
					else if($cart_id == 'invalid_merchant_id')
					{
						
                        return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'please Enter valid Merchant ID']);
					}
                    else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No item inserted into cart']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/deleteitemfromcart', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $cart_id =  $request->getParam('cart_id');
                $quantity =  $request->getParam('quantity');
                $item_price =  $request->getParam('item_price');

                $cart_id = $rest->validateParameter('cart_id', $cart_id, INTEGER);
				
				//****************LOG Creation*********************
				$APILogFile = 'deleteitemfromcart.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('cart_id'=>$cart_id,'quantity'=>$quantity,'item_price'=>$item_price);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ndeleteitemfromcart Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                 
                if(isset($cart_id) && !isset($quantity))
                {
                    $query = "DELETE FROM `mt_cart` WHERE `id`='$cart_id'";
                    $response = $db->execute($query);
                    if(!empty($response)){
              
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Item has been deleted successfully from cart']);
                    
                        }else{
                           return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'item has not been deleted from cart']);
                      }
                }else{
                      $quantity = $rest->validateParameter('quantity', $quantity, INTEGER);
                      $item_price = $rest->validateParameter('item_price', $item_price, INTEGER);
                      $response = $db->update_cart_quantity($cart_id,$quantity,$item_price);
                      if($response == 'qty_removed'){
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'quantity has been deleted successfully from cart']);
                      }else{
                        return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No Item in cart for remove']);
                       }
                    
                    }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});
$app->map(['POST'],'/deleteClientitemfromcart', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $client_id =  $request->getParam('client_id');

                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                 
               
                    $query = "DELETE FROM `mt_cart` WHERE `client_id`='$client_id'";
                    $response = $db->execute($query);
                    if(!empty($response)){
              
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Client cart details has been deleted']);
                    
                        }else{
                           return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry cart has not been deleted']);
                      }
                
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});


$app->map(['POST'],'/applyvouchercode', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $voucher_code =  $request->getParam('voucher_code');
                $status =  $request->getParam('status');    // used 
                $client_id =  $request->getParam('client_id'); 
                $order_id =  $request->getParam('order_id'); 

                $voucher_code = $rest->validateParameter('voucher_code', $voucher_code, STRING);
                $status = $rest->validateParameter('status', $status, STRING);
                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);

                $date_used = date('Y-m-d');
                $user_ip = getUserIP();
                $query = "INSERT INTO `mt_voucher_list`( `voucher_code`, `status`, `client_id`, `date_used`, `order_id`, `ip_address`, `source`) VALUES ('$voucher_code','$status','$client_id','$date_used','$order_id','$user_ip','android')";
                $response = $db->apply_voucher($voucher_code,$client_id,$query);
                switch($response)
                {

                    case 'voucher_not_applied': 
                                                return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry voucher not applied']);
                                                break;
                    
                    case 'voucher_applied': 
                                            return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'voucher applied successfully']);
                                            break;
                    
                    case 'already_used': 
                                        return $this->response->withJson(['statuscode' => CONFLICT, 'responseMessage' => 'false','result'=>'voucher already used']);
                                        break;

                    case 'invalid_vouchercode':
                                                return $this->response->withJson(['statuscode' => BAD_REQUEST, 'responseMessage' => 'false','result'=>'Invalid voucher code']);
                                                break;
                    default:
                                    return $this->response->withJson(['statuscode' => BAD_REQUEST, 'responseMessage' => 'false','result'=>'Invalid voucher code']);
                                                break;
                               
                }
                
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/addcustomerfavdish', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $dish_id =  $request->getParam('dish_id');
                $cust_id =  $request->getParam('cust_id');

                $dish_id = $rest->validateParameter('dish_id', $dish_id, INTEGER);
                $cust_id = $rest->validateParameter('cust_id', $cust_id, INTEGER);
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
                $query = "INSERT INTO `mt_cust_fav_dish`( `dish_id`, `client_id`,`ip_address`, `source`,`datetime`) VALUES ('$dish_id','$cust_id','$user_ip','android','$datetime')";
                $params = array('dish_id'=>$dish_id,'client_id'=>$cust_id);
                $insertid = $db->save($query,'mt_cust_fav_dish',$params);
                if($insertid > 0 && is_numeric($insertid)){
              
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Customer Favorate dish has been added successfully.']);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'For this cutomer favorate dish is already exist.']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});
$app->map(['POST'],'/getAllFavCustomerDishes', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $cust_id =  $request->getParam('cust_id');

                $cust_id = $rest->validateParameter('cust_id', $cust_id, INTEGER);
            
                $query = "SELECT `dish_id`,`dish_name`,`photo` FROM `mt_cust_fav_dish` as A inner join mt_dishes as B on A.dish_id = B.id where A.client_id = '$cust_id'";
                $result = $db->sidebar_query($query);
                if(!empty($result)){
              
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No Favorate dishes available for this customer']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});
$app->map(['POST'],'/addFavoriteMerchant', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $client_id =  $request->getParam('client_id');
                $merchant_id =  $request->getParam('merchant_id');
                $is_favorite =  $request->getParam('is_favorite');  // 1 or 0

                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $is_favorite = $rest->validateParameter('is_favorite', $is_favorite, INTEGER);
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
                $query = "INSERT INTO `mt_favorite_merchant`(`client_id`, `merchant_id`, `is_favorite`, `date_created`, `ip_address`, `source`) VALUES ('$client_id','$merchant_id','$is_favorite','$datetime','$user_ip','android')";
                $result = $db->save($query,'mt_favorite_merchant',['client_id'=>$client_id,'merchant_id'=>$merchant_id,'is_favorite'=>1]);
                if($result!='exist'){
              
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Thank you for set Merchant as favorite']);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry You have Already added Merchant as Favorite ']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 

});
$app->map(['POST'],'/getClientFavoriteMerchants', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $client_id =  $request->getParam('client_id');
                $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
               
                $query = "SELECT `merchant_id` FROM `mt_favorite_merchant` where `client_id`='$client_id' and `is_favorite`='1'";
                $result = $db->sidebar_query($query);
                if(!empty($result)){
              
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'No Favorate dishes available for this customer']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});
$app->map(['POST'],'/getClientCartItems', function( $request,$response,$args) {
    try {
        
        require_once('dbconnect.php');
        if(defined('SECRETE_KEY'))
        {

            $client_id =  $request->getParam('client_id');
            $lat1 =  $request->getParam('latitude');
            $long1 =  $request->getParam('longitude');
            
            $lat1 = $rest->validateParameter('latitude', $lat1, INTEGER);
            $long1 = $rest->validateParameter('longitude', $long1, INTEGER);
            $client_id = $rest->validateParameter('client_id', $client_id, INTEGER);
			
			//****************LOG Creation*********************
				$APILogFile = 'getClientCartItems.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('client_id'=>$client_id,'latitude'=>$lat1,'longitude'=>$long1);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetClientCartItems Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
			//****************ENd OF Code*****************
           
            $query = "SELECT A.`id`,`client_id`,`merchant_id`,`item_id`,`item_name`,`item_price` as total_price,`quantity`,`quantity_flag`,`datetime`,`datetime_modified`,`restaurant_name`,`logo` FROM `mt_cart` as A inner join mt_merchant as B on A.merchant_id = B.id WHERE `client_id`='$client_id' and A.`quantity` > 0 and LOWER(B.`status`)=LOWER('active')";
            $db->get_cart_items_based_on_clientId($query, $client_id,$lat1,$long1);
                
        }else{
            return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
        }





    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/updateMerchantLoginStatus', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                
                $merchant_id =  $request->getParam('merchant_id');
                $online_offline_flag =  $request->getParam('online_offline_flag');
                
                //validate parameters
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $online_offline_flag = $rest->validateParameter('online_offline_flag', $online_offline_flag, STRING);
                $query = "SELECT * FROM `mt_merchant_meta` WHERE `merchant_key`='merchant_login_status' and `merchant_id`='$merchant_id'";
                $response = $db->update_or_insert_loginStatus($query,'Merchant',$online_offline_flag,$merchant_id);
                if($response) 
                {
                  return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Merchant login status has been updated']);
                }else{
                  return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry unable to update merchant login status']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/addMerchantFCMToken', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
                $fcm_id = $rest->validateParameter('fcm_id', $fcm_id, STRING, false);
                $merchant_id =  $request->getParam('merchant_id');
                $device_id =  $request->getParam('device_id');
                $fcm_token =  $request->getParam('fcm_token');
				
				//****************LOG Creation*********************
				$APILogFile = 'addMerchantFCMToken.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('fcm_id'=>$fcm_id,'merchant_id'=>$merchant_id,'device_id'=>$device_id,'fcm_token'=>$fcm_token);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddMerchantFCMToken Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                //validate parameters
                $fcm_id = $rest->validateParameter('fcm_id', $fcm_id, STRING,false);
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $device_id = $rest->validateParameter('device_id', $device_id, STRING);
                $fcm_token = $rest->validateParameter('fcm_token', $fcm_token, STRING);
                $user_ip = getUserIP();
                $datetime = date("Y-m-d H:i:s");
               $query = "INSERT INTO `mt_merchant_fcm_token`(`merchant_id`, `device_id`, `fcm_token`, `ip_address`, `source`,`datetime`) VALUES ('$merchant_id','$device_id','$fcm_token','$user_ip','android','$datetime')";
               $insert_id = $db->insert_FCM_token($query,'Merchant',$fcm_token,$fcm_id);
               
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
$app->map(['POST'],'/merchantAppViewed', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
               
                $order_id =  $request->getParam('order_id');
                $is_viewed =  $request->getParam('is_viewed');      // 1 or 0 
				$cancel_reason =  $request->getParam('cancel_reason');     
				
				//****************LOG Creation*********************
				$APILogFile = 'merchantAppViewed.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('order_id'=>$order_id,'is_viewed'=>$is_viewed);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nmerchantAppViewed Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                 
                //validate parameters
                $order_id = $rest->validateParameter('order_id', $order_id, INTEGER);
                $is_viewed = $rest->validateParameter('is_viewed', $is_viewed, INTEGER);
                $user_ip = getUserIP();
                $datetime_modified = date("Y-m-d H:i:s");
                $sql = "SELECT `order_id`,`date_created`,`status` FROM `mt_order` WHERE `order_id`='$order_id'";
                if($db->db_num($sql))
                {
					// order cancel code from android side start
					
					if(trim($is_viewed) == '0')
					{
						$cancel_reason = $rest->validateParameter('cancel_reason', $cancel_reason, STRING);
								//****************LOG Creation*********************
										$APILogFile = 'merchantAppViewed.txt';
										$handle = fopen($APILogFile, 'a');
										$timestamp = date('Y-m-d H:i:s');
										$logMessage = "\nmerchantAppViewed Result at $timestamp :-\n response : $cancel_reason";
										fwrite($handle, $logMessage);				
										fclose($handle);
								 //****************ENd OF Code*****************
										
										$update_order_status = "UPDATE `mt_order` SET `status`='Cancelled',
										`date_modified`='$timestamp',`merchantapp_viewed`='1',`cancelled_reason` = '$cancel_reason' WHERE `order_id` = '$order_id'";
										$res1 = $db->execute($update_order_status);
										
										return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'order has been cancelled by merchant from android APP.']);
										exit;
					
					
					}
					// end of the order cancel code
					//check wheather the order has been accepted before 3 minutes start
					$result17 = $db->mysqli->query($sql);
					 $row15 = mysqli_fetch_assoc($result17);
					 $order_status = $row15['status'];
					 $order_created_time = $row15['date_created'];

					 //$current_order_acceptance_time = date('Y-m-d H:i:s');
					  $get_time = "SELECT ADDTIME(now(), '04:30:00') as time";  
					  $mysql_query1 =  $db->mysqli->query($get_time);
					  $row122 = mysqli_fetch_assoc($mysql_query1);
					  $current_order_acceptance_time = trim($row122['time']);
					 
					 $order_accept_time_qry = "SELECT TIME_FORMAT(TIMEDIFF('$current_order_acceptance_time','$order_created_time'), '%H@%i@%s') as order_accept_time from `mt_order` where `order_id`='$order_id' and LOWER(`status`)='pending' and `merchantapp_viewed`=0";
					 $result_of_qry = $db->mysqli->query($order_accept_time_qry);
					 $row16 = mysqli_fetch_assoc($result_of_qry);
					  $order_acceptance_time = $row16['order_accept_time'];
					 
					 $accepting_timeofOrder = explode("@",$order_acceptance_time);
					 $hour = $accepting_timeofOrder[0];
					 $minutes = $accepting_timeofOrder[1];
					 $seconds = $accepting_timeofOrder[2];
					 
					 if($hour == '00' && $minutes <= $order_acceptance_minutes)  //$order_acceptance_minutes avaialble in constant.php
					 {
					
						//check wheather the order has been accepted before 3 minutes End
					
							$query = "UPDATE `mt_order` SET `status` = 'Accepted',`merchantapp_viewed`='$is_viewed',`date_modified`='$datetime_modified',`ip_address`='$user_ip' WHERE `order_id` = '$order_id'";
							$response = $db->execute($query);
						
							if($response) 
							{
								/* assigned order to delivery boy */
								$get_merchant_id = "SELECT `merchant_id`,`client_id` FROM `mt_order` WHERE `order_id`='$order_id'";
								$count = $db->db_num($get_merchant_id);
								if($count>=1)
								{
									$result11 = $db->mysqli->query($get_merchant_id);
									$row11 = mysqli_fetch_assoc($result11);
									$merchnat_id = $row11['merchant_id'];
									$client_id = $row11['client_id'];
									$get_merchant_lat_lng = "SELECT `latitude`,`lontitude` FROM `mt_merchant` WHERE `id`='$merchnat_id' and LOWER(`status`)='active'";
									$result12 = $db->mysqli->query($get_merchant_lat_lng);
									$row12 = mysqli_fetch_assoc($result12);
									
									 if(!empty($row12['latitude']) && !empty($row12['lontitude']))
									 {
										 $key_arr = array();$value_arr=array();
										 $get_delivery_lat_lng = "SELECT `id`,`latitude`,`longitude` FROM `mt_delivery_boy` WHERE LOWER(`status`)='active'";
										 $result13 = $db->mysqli->query($get_delivery_lat_lng);
										 while($row13 = mysqli_fetch_assoc($result13))
										 {
											 
										   $distance = get_distance_between_points($row12['latitude'], $row12['lontitude'], $row13['latitude'], $row13['longitude']);
										   $kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):'0';
										   $key_arr[] = $row13['id'];
										   $value_arr[] = $kilometers;
										 }
										 $key_value_arr = array_combine( $key_arr, $value_arr );
										 
										 //to get min distance from merchant driver id
										 $assigned_delivery_boy_id = array_keys($key_value_arr, min($key_value_arr)); 
										 $final_assigned_delivery_boy_id =  $assigned_delivery_boy_id[0];
										 if(!empty($final_assigned_delivery_boy_id))
										 {
											 $assigned_order_to_driver = "SELECT id FROM `mt_driver_order_status` WHERE `order_id`='$order_id'";
											 $Ordercount = $db->db_num($assigned_order_to_driver);
											 if($Ordercount == 0)
											 {
												 
												 $assigned_to_driver = "INSERT INTO `mt_driver_order_status`(`order_id`, `client_id`, `driver_id`, `status`, `date_created`,`ip_address`, `source`) VALUES ('$order_id','$client_id','$final_assigned_delivery_boy_id','Assigned','$datetime_modified','$user_ip','android')";
												 $result15 = $db->mysqli->query($assigned_to_driver);
												 
												 /*push notification code for sending notification to delivery boy start */
												 $message = "New Order has been placed.Please confirm Order. Order Id is-".$order_id;
												 $res = $db->fetchFirebaseTokenUsers('deliveryboy',$final_assigned_delivery_boy_id,$message, $deliveryboy_server_api_key);
												 if(!is_null($res))
												 {
													 //****************LOG Creation*********************
													$APILogFile = 'merchantAppViewed.txt';
													$handle = fopen($APILogFile, 'a');
													$timestamp = date('Y-m-d H:i:s');
													$logArray1 = print_r($res, true);
													$logMessage = "\npush notification sent response  Result at $timestamp :-\n$logArray1";
													$logArray2 = print_r($log_array, true);
													$logMessage1 = "\nmerchantAppViewed response Result at $timestamp :-\n$logArray2";
													fwrite($handle, $logMessage);
													fwrite($handle, $logMessage1);									
													fclose($handle);
												 }else{
													  //****************LOG Creation*********************
													$APILogFile = 'merchantAppViewed.txt';
													$handle = fopen($APILogFile, 'a');
													$timestamp = date('Y-m-d H:i:s');
													$logMessage = "\npush notification sent response  Result at $timestamp :-\n NULL";
													fwrite($handle, $logMessage);							
													fclose($handle);
													 
												 }
												//****************ENd OF Code*****************
												/*push notification code for sending notification to delivery boy End */
											 
											 
											 }else{
												 
													  //****************LOG Creation*********************
													$APILogFile = 'merchantAppViewed.txt';
													$handle = fopen($APILogFile, 'a');
													$timestamp = date('Y-m-d H:i:s');
													$logMessage = "\nmerchantAppViewed Result at $timestamp :-\n response : order is already assignedd to driver";
													fwrite($handle, $logMessage);				
													fclose($handle);
													//****************ENd OF Code*****************
											 }
										 }else{
											 
											 //****************LOG Creation*********************
													$APILogFile = 'merchantAppViewed.txt';
													$handle = fopen($APILogFile, 'a');
													$timestamp = date('Y-m-d H:i:s');
													$logMessage = "\nmerchantAppViewed Result at $timestamp :-\n response : delivery boy is not available";
													fwrite($handle, $logMessage);				
													fclose($handle);
													//****************ENd OF Code*****************
											 
										 }
									 }else{
												 
												 //****************LOG Creation*********************
												$APILogFile = 'merchantAppViewed.txt';
												$handle = fopen($APILogFile, 'a');
												$timestamp = date('Y-m-d H:i:s');
												$logMessage = "\nmerchantAppViewed Result at $timestamp :-\n response : merchnat latitude/longitude should not be blank or merchant must be active";
												fwrite($handle, $logMessage);				
												fclose($handle);
												
												//****************ENd OF Code*****************
											
										 
									 }
								}
								
								/* End of the code */
								return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Merchant has viewed the order']);
								exit;
							}else{
								// response if closing
								return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry FCM token has not been inserted successfully']);
								exit;
							}
					 }else{
						 
						   /*if order accepatance time is greater than 3 minutes and order is not accepted then it comes to else block
						         if order is already accepted from web panel then only it comes to else block
						   */
						   
						   
										//****************LOG Creation*********************
										$APILogFile = 'merchantAppViewed.txt';
										$handle = fopen($APILogFile, 'a');
										$timestamp = date('Y-m-d H:i:s');
										$logMessage = "\nmerchantAppViewed Result at $timestamp :-\n response : order created at $order_created_time and accepted time $order_acceptance_time with accepetd on the actual $hour : $minutes for order $status";
										fwrite($handle, $logMessage);				
										fclose($handle);
										//****************ENd OF Code*****************
										
										/*$update_delivery_instruction = "UPDATE `mt_order` SET `cancelled_reason` = 'sorry for regret we are unable to accept this order. Because (a) You are trying to accept order after 3 minutes (b) May be order has been accepted already from web panel.' WHERE 
										`order_id` = '$order_id'";
										$res= $db->execute($update_delivery_instruction);*/
										
										$check_order_status= "SELECT LOWER(`status`) as status FROM `mt_order` WHERE `order_id`='$order_id'";
										$result_of_check_order_status = $db->mysqli->query($check_order_status);
										$row17 = mysqli_fetch_assoc($result_of_check_order_status);
										$order_curr_status = $row17['status'];
										if($order_curr_status!='pending')
										{
											return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Order status is not pending. May be order already accepted from admin panel.']);
											exit;
											
										}else{
										
											return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry for regret we are unable to accept this order. Because You are trying to accept order after 3 minutes.']);
											exit;
										}
						   
						 
						 
					 }
                }else
                {
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Invalid Order ID']);
					exit;
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
			  exit;
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});

$app->map(['POST'],'/getmerchantAcceptedOrders', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT * FROM `mt_order` as A inner join mt_order_details as B on A.`order_id`=B.order_id inner join mt_order_delivery_address as C on A.`order_id` = C.`order_id` WHERE `merchant_id`='$merchant_id' and DATE(DATE_FORMAT(A.date_created, '%Y-%m-%d')) = CURRENT_DATE and `status` ='delivered' group by A.`order_id`";
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
$app->map(['POST'],'/getmerchantPendingOrders', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                
                $result = $db->get_order_details($merchant_id,'pending');
               
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
$app->map(['POST'],'/getmerchantCurrentOrders', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $result = $db->get_order_details($merchant_id,'accepted');
               
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
$app->map(['POST'],'/getOfferForMerchant', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $flag=0; $offer = array();
                $offer_id =  $request->getParam('offer_id');
				$client_id =  $request->getParam('client_id');
				$lat1 =  $request->getParam('lat1');
				$long1 =  $request->getParam('long1');
                $offer_id = $rest->validateParameter('offer_id', $offer_id, INTEGER);
				$lat1 = $rest->validateParameter('lat1', $lat1, INTEGER);
				$long1 = $rest->validateParameter('long1', $long1, INTEGER);
				$client_id = $rest->validateParameter('client_id', $client_id, STRING,false);  //option for future Plan
				
				//****************LOG Creation*********************
				$APILogFile = 'getOfferForMerchant.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('offer_id'=>$offer_id,'client_id'=>$client_id,'lat1'=>$lat1,'long1'=>$long1);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\ngetOfferForMerchant Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
                $query = "SELECT  C.*,B.`id` as offer_id,`offer_percentage`,`offer_price`,`order_over`,`valid_from`,`valid_to`,`discount_upto`,`applicable_to`,`offer_image` FROM `mt_merchant_offers` as A inner join mt_offers as B on A.offer_id = B.id inner join mt_merchant as C on A.merchant_id = C.id WHERE A.status = 'active' and B.status ='active' and C.status='active' and B.id = '$offer_id'";
                $result = $db->sidebar_query($query);
                if(!empty($result))
                {
				
                    for($i=0;$i<count($result);$i++)
                    {
						
                       $from =  $result[$i]['valid_from'];
                       $to =  $result[$i]['valid_to'];
                       $today = date('Y-m-d');
                       if($from <= $today && $to >= $today)
                       {
						   
						   $merchant_id = $result[$i]['id'];
						   
						 //check offer is available within restaurant covered area in km  start
						$query=$db->mysqli->query("SELECT * FROM `mt_merchant` where LOWER(`status`)=LOWER('Active') and id='$merchant_id'"); 
						$record = mysqli_fetch_assoc($query);
						$latitude2 = $record['latitude'];
						$logitude2 = $record['lontitude'];
						$distance = get_distance_between_points($lat1, $long1, $latitude2, $logitude2);
						$kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):'0';
						$merchant_area_covered = (float)($db->get_meta_value_from_metakey($record['id'],'area_covered_in_km')!='')?trim($db->get_meta_value_from_metakey($record['id'],'area_covered_in_km')):'0';
								
						//****************LOG Creation*********************
						$APILogFile = 'getOfferForMerchant.txt';
						$handle = fopen($APILogFile, 'a');
						$timestamp = date('Y-m-d H:i:s');
						$logArray1 = array('merchant_id'=>$merchant_id,'kilometers'=>$kilometers,'merchant_area_covered'=>$merchant_area_covered);
						$logArray2 = print_r($logArray1, true);
						$logMessage = "\ngetOfferForMerchant Result at $timestamp :-\n$logArray2";
						fwrite($handle, $logMessage);				
						fclose($handle);
						//****************ENd OF Code*****************
								 
						if($kilometers>0 && $merchant_area_covered>0)
						{
							if($kilometers <= $merchant_area_covered)
							{
								$flag=1;
								//echo "km=".$kilometers." ".$merchant_area_covered;exit;
							  $rating = "SELECT `rating` FROM `mt_review` WHERE `merchant_id`='$merchant_id'";
							  $getmerchantratings = $db->mysqli->query($rating);
							  $numberOfReviews = 0; $totalStars = 0; $average=0;
							  while ($values1 = mysqli_fetch_assoc($getmerchantratings)) 
							  {
								$totalStars += $values1['rating'];
								$numberOfReviews++;
							  }
							  if($numberOfReviews!=0.0)
							  {
								$average = number_format((float) $totalStars/$numberOfReviews, 1, '.', ''); 
							  }
							  else
							  {
								$average = number_format((float) 0, 1, '.', '');  
							  }
						  
							  $result[$i]['rating'] = $average; 
							  
							  
							  unset($result[$i]['cuisine']);
							  unset($result[$i]['gst_number']);
							  unset($result[$i]['adhar_number']);
							  unset($result[$i]['pan_number']);
							  unset($result[$i]['fssi_number']);
							  
							  
							  $get_merchant_cuisines = "SELECT GROUP_CONCAT(DISTINCT cuisine_name SEPARATOR ',') as cuisine_name FROM `mt_cuisine` WHERE id IN(select `cuisine_id` from mt_merchant_cuisine where `merchant_id`='$merchant_id')";
							  $getcuisinesresult = $db->sidebar_query($get_merchant_cuisines);
							  $result[$i]['cuisine'] = $getcuisinesresult[0]['cuisine_name'];
							  array_push($offer,$result[$i]);
							}
						}
					  }
					  
                    }
					 if($flag == 1)
					   {
						   //****************LOG Creation*********************
								$APILogFile = 'getOfferForMerchant.txt';
								$handle = fopen($APILogFile, 'a');
								$timestamp = date('Y-m-d H:i:s');
								$logArray3 = print_r($offer, true);
								$logMessage = "\ngetOfferForMerchant Result at $timestamp :-\n$logArray3";
								fwrite($handle, $logMessage);				
								fclose($handle);
							//****************ENd OF Code*****************
						
						return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$offer]);
						exit;
					   }
                    if($flag==0)
                    {
                        return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>NULL]);
                    }
                 } else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>NULL]);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});

$app->map(['POST'],'/getMerchantBestSellerItems', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT * FROM `mt_item` WHERE `merchant_id`='$merchant_id' and `is_featured`='1' and LOWER(`status`) = LOWER('Active')";
                $result = $db->sidebar_query($query);
               
               if(!empty($result)){
				   for($i=0;$i<count($result);$i++)
				   {
					   $arr = unserialize($result[$i]['price']);
					   if(is_array($arr)){
						  foreach((array)$arr as $key=>$value)
						  {
							if(!empty($record['price_type']) && strtolower($record['price_type'])== strtolower('Single'))
							{
								$result[$i][$key]= $value;
								
							}else{
									$query12 = "SELECT `size_name` FROM `mt_size` WHERE `id`='$key'";
									$result1 = $db->mysqli->query($query12); 
									$record11 = mysqli_fetch_assoc($result1);
									$size_name = $record11['size_name'];
									$result[$i][$size_name]= $value;
								   // $k++;
							}
						  }
						 } 
						 unset($result[$i]['price']);
				   }
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

$app->map(['POST'],'/getMerchantRecommendedItems', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT * FROM `mt_item` as A inner join mt_merchant_recommend_items as B on A.id = B.item_recommended_id where LOWER(`status`) = LOWER('Active') and B.`merchant_id`='$merchant_id'";
                $result = $db->sidebar_query($query);
               
               if(!empty($result)){
				   for($i=0;$i<count($result);$i++)
				   {
					   $arr = unserialize($result[$i]['price']);
					   if(is_array($arr)){
						  foreach((array)$arr as $key=>$value)
						  {
							if(!empty($record['price_type']) && strtolower($record['price_type'])== strtolower('Single'))
							{
								$result[$i][$key]= $value;
								
							}else{
									$query12 = "SELECT `size_name` FROM `mt_size` WHERE `id`='$key'";
									$result1 = $db->mysqli->query($query12); 
									$record11 = mysqli_fetch_assoc($result1);
									$size_name = $record11['size_name'];
									$result[$i][$size_name]= $value;
								   // $k++;
							}
						  }
						 } 
						 unset($result[$i]['price']);
				   }
				   
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
$app->map(['POST'],'/getAllItemsOfMerchant', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT * FROM `mt_item` WHERE `merchant_id`='$merchant_id' and LOWER(`status`) = LOWER('Active')";
                $result = $db->sidebar_query($query);
               
               if(!empty($result)){
				   
				   for($i=0;$i<count($result);$i++)
				   {
					  
					   $item_price = $result[$i]['price'];
					   $arr = unserialize($item_price);
						  if(is_array($arr)){
							  foreach((array)$arr as $key=>$value)
							  {
								if(!empty($result[$i]['price_type']) && strtolower($result[$i]['price_type'])== strtolower('Single'))
								{
									$resArr['item'][$i][$key]= $value;
									
								}else{
										$query12 = "SELECT `size_name` FROM `mt_size` WHERE `id`='$key'";
										$result1 = $db->mysqli->query($query12); 
										$record11 = mysqli_fetch_assoc($result1);
										$size_name = $record11['size_name'];
										$result[$i][$size_name."_price"]= $value;
									   // $k++;
								}
							  }
							 } 
						 unset($result[$i]['price']);   
					}
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
$app->map(['POST'],'/updateItemStockStatus', function( $request,$response,$args) {
	try {
           require_once('dbconnect.php');
           if(defined('SECRETE_KEY'))
            {
               
                $item_id =  $request->getParam('item_id');
                $merchant_id =  $request->getParam('merchant_id');      
				$stock_status =  $request->getParam('stock_status');   // yes or not
                 
                //validate parameters
                $item_id = $rest->validateParameter('item_id', $item_id, INTEGER);
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
				$stock_status = $rest->validateParameter('stock_status', $stock_status, STRING);
				$status_arr = array("YES", "yes", "NO", "no","Yes","No");
				if(!in_array($stock_status, $status_arr))
				{
					return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Item Stock Status should be either yes or no']);
					exit;
				}
                $user_ip = getUserIP();
                $datetime_modified = date("Y-m-d H:i:s");
                $sql = "SELECT `id` FROM `mt_item` WHERE `merchant_id`='$merchant_id' and `id`='$item_id' and LOWER(`status`) = LOWER('Active')";
                if($db->db_num($sql))
                {
                    $query = "UPDATE `mt_item` SET `stock_status`=LOWER('$stock_status'),`date_modified`='$datetime_modified',`ip_address`='$user_ip' WHERE  `merchant_id`='$merchant_id' and `id`='$item_id'";
                    $response = $db->execute($query);
                
                    if($response) 
                    {
                        return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Item Stock Status has been updated successfully']);
                    }else{
                        return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry Item Stock Status has not been updated successfully']);
                    }
                }else
                {
                    return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Item is not avialble,plz contact Admin']);
                }
            }else{
              return $this->response->withJson(['statuscode' => INVALID_USER_PASS, 'responseMessage' => 'false','result'=>NULL]);
          }
                

    } catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	

});
$app->map(['POST'],'/getMerchantPaidPaymentDetails', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $query = "SELECT * FROM `mt_merchant_payment` WHERE `merchant_id`='$merchant_id' and LOWER(`status`) = 'active'";
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
$app->map(['POST'],'/getMerchantReport', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
                $merchant_id =  $request->getParam('merchant_id');
				$from_date =  $request->getParam('from_date');
				$to_date =  $request->getParam('to_date');
				
				//Validate Parameters
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
				$from_date = date("Y-m-d", strtotime($rest->validateParameter('from_date', $from_date, STRING)));
				$to_date = date("Y-m-d", strtotime($rest->validateParameter('to_date', $to_date, STRING)));
                
                $result = $db->get_order_reports($merchant_id,$from_date,$to_date);
               
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
$app->map(['POST'],'/getFeaturedMerchants', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
				
				$lat1 =  $request->getParam('latitude');
                $long1 =  $request->getParam('longitude');
                //$city_name =  $request->getParam('city_name');
                $lat1 = $rest->validateParameter('latitude', $lat1, INTEGER);
                $long1 = $rest->validateParameter('longitude', $long1, INTEGER);
                //$city_name = $rest->validateParameter('city_name', $city_name, STRING);
				
				//****************LOG Creation*********************
				$APILogFile = 'getFeaturedMerchants.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logMessage = "\ngetFeaturedMerchants Result at $timestamp :- LAT: $lat1 LONG:$long1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
                
                $query = "SELECT * FROM `mt_merchant` WHERE TRIM(`is_featured`)=1 and LOWER(`status`) = 'active'";
                $result = $db->get_featured_restro($query,$lat1,$long1);
               
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

$app->map(['POST'],'/getmerchantcategorywiseitemsfortesting', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {

                $merchant_id =  $request->getParam('merchant_id');
				$category_type =  $request->getParam('category_type');
				$category_type = (empty($category_type))?"all":$category_type;
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
				$category_type = $rest->validateParameter('category_type', $category_type, STRING);  //all / veg /nonveg
				
				//****************LOG Creation*********************
				$APILogFile = 'get_categorywiseitemsfortesting.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('merchant_id'=>$merchant_id,'category_type'=>$category_type);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\nget_categorywiseitemsfortesting Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
                $query = "SELECT distinct `category_id`,`merchant_id` FROM `mt_merchant_categories` where `merchant_id`='$merchant_id'";
                $result = $db->get_categorywiseitemsfortesting($query,$category_type,$merchant_id);
                if(!empty($result)){
              
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result]);
                
                    }else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry No items available for this merchant']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 
});

$app->map(['POST'],'/addMerchantitem', function( $request,$response,$args) {
    try {
            require_once('dbconnect.php');
            if(defined('SECRETE_KEY'))
            {
				
				$item_name =  $request->getParam('item_name');
                $category_id =  $request->getParam('category_id');
                $merchant_id =  $request->getParam('merchant_id');
                $price_type =  $request->getParam('price_type');
                $single_item_price =  $request->getParam('single_item_price');
                $Half_price =  $request->getParam('Half_price');
                $Full_price =  $request->getParam('Full_price');
                $item_description =  $request->getParam('item_description');
				$is_veg_nonveg =  $request->getParam('is_veg_nonveg');
				$cooking_refrence =  $request->getParam('cooking_refrence');
				$status =  $request->getParam('status');
				$is_item_best_seller =  $request->getParam('is_item_best_seller');
				$stock_status =  $request->getParam('stock_status');
				

				$item_name = $rest->validateParameter('item_name', $item_name, STRING);
                $category_id = $rest->validateParameter('category_id', $category_id, INTEGER);
                $merchant_id = $rest->validateParameter('merchant_id', $merchant_id, INTEGER);
                $price_type = $rest->validateParameter('price_type', $price_type, STRING);
                $single_item_price = $rest->validateParameter('single_item_price', $single_item_price, INTEGER,false);
                $Half_price = $rest->validateParameter('Half_price', $Half_price, INTEGER,false);
                $Full_price = $rest->validateParameter('Full_price', $Full_price, INTEGER,false);
                $item_description = $rest->validateParameter('item_description', $item_description, STRING,false);
				$is_veg_nonveg = $rest->validateParameter('is_veg_nonveg', $is_veg_nonveg, STRING);
				$cooking_refrence = $rest->validateParameter('cooking_refrence', $cooking_refrence, STRING,false);
				$status = $rest->validateParameter('status', $status, STRING);
				$is_item_best_seller = $rest->validateParameter('is_item_best_seller', $is_item_best_seller, STRING,false);
				$stock_status = $rest->validateParameter('stock_status', $stock_status, STRING);
                $user_ip = getUserIP();
				
				//****************LOG Creation*********************
				$APILogFile = 'addMerchantitem.txt';
				$handle = fopen($APILogFile, 'a');
				$timestamp = date('Y-m-d H:i:s');
				$logArray = array('category_id'=>$category_id,'Merchant ID'=>$merchant_id,'price_type'=>$price_type,'single_item_price'=>$single_item_price,'Half_price'=>$Half_price,'Full_price'=>$Full_price,'item_description'=>$item_description,'is_veg_nonveg'=>$is_veg_nonveg,'cooking_refrence'=>$cooking_refrence,'status'=>$status,'is_item_best_seller'=>$is_item_best_seller,'stock_status'=>$stock_status);
				$logArray1 = print_r($logArray, true);
				$logMessage = "\naddMerchantitem Result at $timestamp :-\n$logArray1";
				fwrite($handle, $logMessage);				
				fclose($handle);
				//****************ENd OF Code*****************
				
				if(strtolower($price_type) == 'single')
				{
					$single_arr = serialize(array($price_type=>$single_item_price));
				}else if(strtolower($price_type) == 'sizewise')
				{
					if(isset($Half_price) && isset($Full_price))
					{
						$get_Halfitem_size_id = "SELECT id FROM `mt_size` WHERE `merchant_id`='$merchant_id' and LOWER(`size_name`)='half' and LOWER(`status`) = 'active'";
						$result11 = $db->mysqli->query($get_Halfitem_size_id);
						$row11 = mysqli_fetch_assoc($result11);
						$Half_size_id = $row11['id'];
						
						$get_Fullitem_size_id = "SELECT id FROM `mt_size` WHERE `merchant_id`='$merchant_id' and LOWER(`size_name`)='full' and LOWER(`status`) = 'active'";
						$result12 = $db->mysqli->query($get_Fullitem_size_id);
						$row12 = mysqli_fetch_assoc($result12);
						$Full_size_id = $row12['id'];
						$sizewise_arr = serialize(array($Half_size_id=>$Half_price,$Full_size_id=>$Full_price));
						
					}
					
						
				}
				if(isset($single_arr))
				{
					$itemprice = $single_arr;
				}else{
					$itemprice = $sizewise_arr;
				}
				//echo $itemprice;exit;
                $query = "INSERT INTO `mt_item`(`merchant_id`, `item_name`, `item_description`, `status`, `category`, `price`, `price_type`,`cooking_ref`,`is_featured`, `date_created`, `ip_address`, `is_veg_nonveg`, `stock_status`) VALUES ('$merchant_id','$item_name','$item_description','$status','$category_id','$itemprice','$price_type','$cooking_refrence','$is_item_best_seller','$timestamp','$user_ip','$is_veg_nonveg','$stock_status')";
                $response = $db->execute($query);
                if($response){
					
					//$insert_mer_category = "INSERT INTO `mt_merchant_categories`(`merchant_id`, `category_id`, `item_id`, `datetime`, `source`) VALUES ('$merchant_id','$category_id',)";
                    return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>'Item has been inserted successfuly.']);
                
                    }
                    else{
                       return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'sorry Item has not been inserted successfully.']);
                  }
                    
            }else{
                return $this->response->withJson(['statuscode' => ACCESS_TOKEN_ERRORS, 'responseMessage' => 'false','result'=>NULL]);
            }



    } catch (ResourceNotFoundException $e) { 
    $app->response()->status(404);
} 


});


});
