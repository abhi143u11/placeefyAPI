<?php
require_once('constant.php');
require_once('rest.php');
require_once('functions.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
class DB
{
	    private $host;
	    private $user;
	    private $pass;
	    private $db;
	    public $mysqli;
		private static $MERCHANT_API_SERVER_KEY = 'AAAAIP3bznw:APA91bGM7g2ULRmhP3c9pl6NEIBV-KMDulobb_WrysQH9H6TlJRdrkUsfihda9CqCGkVhre5yFuWc81WibWBJ3czVI5guV1TfCdacv9Ks7cMr8PzY0mUtyBPfIqKusGMILCFRa2I0cvn';
        private static $is_background = "TRUE";
	  
	    public function __construct() {
	      date_default_timezone_set('Asia/Kolkata');
	      $this->db_connect();
	     
	    }
	  
	    private function db_connect(){
			
		
		  /* Hostinger DB credentails */
			  $this->host = 'localhost';
			  $this->user = 'u292623069_placeefy';
			  $this->pass = 'placeefy@456';
			  $this->db = 'u292623069_placeefy';
		 
		 /* Hostinger placeefy DB credentails */
			//  $this->host = 'localhost';
			//  $this->user = 'cdexexch_rab';
			//  $this->pass = 'rabzo@123#';
			//  $this->db = 'cdexexch_rabzo';
	  
	      $this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);
	      return $this->mysqli;
	    }
	  
	    public function db_num($sql){
	          $result = $this->mysqli->query($sql);
	          return $result->num_rows;
	    }
	    public function sidebar_query($query)
	    {
	        
	        $mysql_query =  $this->mysqli->query($query);//mysqli_query($this->mysqli,$query); //query the db
	        $resArr = array(); //create the result array
	        while($row = mysqli_fetch_assoc($mysql_query)) { //loop the rows returned from db
	            $resArr[] = $row; //add row to array
	        }
	        //print_r($resArr);exit;
	        return $resArr;     
	    }
	    public function insert_query($query,$table_name,$feild_name,$feild_val)
	    {
	    	//echo $query;exit;
			$sql ="SELECT `id` FROM $table_name where $feild_name='$feild_val' and deleted=0";

	        $count = $this->db_num($sql);
	        
	        if($count == 0){
	        $mysql_query =  $this->mysqli->query($query);
	        if($mysql_query) {
	          return 'insert';
	        }else{
	          return 'not_insert';
	        }
	      }else{
	        return 'exist';
	      }
	          
	    }
	    public function execute($query) 
	    {
	        $result = $this->mysqli->query($query);
	        
	        if ($result == false) {
	            
	            return false;
	        } else {
	            return true;
	        }        
	    }
	    public function get_update_entity_email_address($customer_id,$update_value,$operation)
	    {
	    	//operation means : update or get address
           if(!empty($customer_id) && $operation=='get')
           {
           		$get_email_add = "SELECT t2.`email_address` FROM `email_addr_bean_rel` as t1 inner join `email_addresses` as t2 on t1.`email_address_id`=t2.`id` WHERE `bean_id`='$customer_id' and t1.`deleted`=0 and t2.`deleted`=0"; 
		         $mysql_query =  $this->mysqli->query($get_email_add);			
		         $row11 = $mysql_query->fetch_assoc();				
		         $email_Address = (isset($row11['email_address'])?$row11['email_address']:'NA');	
		         return $email_Address;
           }else if(!empty($customer_id) && $operation=='update')
           {
           	    $datetime = date("Y-m-d H:i:s");
           		$sql = "SELECT * FROM `email_addr_bean_rel` WHERE `bean_id`='$customer_id' and `deleted`=0";
				if($this->db_num($sql))
				{
	                $update_email_address = "UPDATE `email_addr_bean_rel` as t1 inner join `email_addresses` as t2 on t1.`email_address_id`=t2.id SET t2.email_address='$update_value',`email_address_caps`=UPPER('$update_value') where t1.`bean_id`='$customer_id' and t1.deleted=0";
	                $mysql_query =  $this->mysqli->query($update_email_address);	
	             }else{

	             		$email_address_id = getGuid();   // guid UUID() function of mysql(32 bit ID)
	             		$insert_email_address = "INSERT INTO `email_addresses`(`id`, `email_address`, `email_address_caps`, `confirm_opt_in`, `date_created`, `date_modified`) VALUES ('$email_address_id','$update_value',UPPER('$update_value'),'confirmed-opt-in','$datetime','$datetime')";
	                    $mysql_query11 =  $this->mysqli->query($insert_email_address);

	                    $uuid = getGuid();
	                    $insert_email_address_bean_rel = "INSERT INTO `email_addr_bean_rel`(`id`, `email_address_id`, `bean_id`, `bean_module`, `primary_address`, `reply_to_address`, `date_created`, `date_modified`) VALUES ('$uuid','$email_address_id','$customer_id','Accounts','1','0','$datetime','$datetime')";
	                    $mysql_query12 =  $this->mysqli->query($insert_email_address_bean_rel);

	             }
           }

	    }
	    public function get_image_from_notes_module($parent_id,$parent_type)
	    {
	    	$get_image_id = "SELECT `id` FROM `notes` WHERE `parent_type`='$parent_type' and `parent_id`='$parent_id' and deleted=0 limit 1"; 

		    $mysql_query =  $this->mysqli->query($get_image_id);			
		    $row11 = $mysql_query->fetch_assoc();				
		    $image_id = (isset($row11['id'])?$row11['id']:'NA');
		    if($image_id=='NA')
		    {
		    	return 'Image Not available';
		    }
		    $image_path = UPLOAD_URL .$image_id;
		    return $image_path;
	    }
	    public function get_sequence_wise_code($seq_no=0)
	    {
           $get_seq_wise_code = "SELECT * FROM `ply_app_management` a join `ply_app_management_cstm` b on a.id=b.id_c WHERE a.deleted=0  and `sequence_no`='$seq_no'";
            $mysql_query =  $this->mysqli->query($get_seq_wise_code);			
		    $row11 = $mysql_query->fetch_assoc();				
		    $UI_code = (isset($row11['section_card_ui_code'])?$row11['section_card_ui_code']:'NA');
		    if($UI_code!='NA')
		    {
		    	return $UI_code;
		    }
		   
	    }
	    public function convert_jsonObject_into_arrray($jsonObject)
	    {
	    	$data = json_decode($jsonObject);
	    	return $data;
	    }
	    public function get_kitchen_supervisor_name($id)
	    {
	    	$get_supervisor_name = "SELECT Concat(Ifnull(`first_name`,' ') ,' ', Ifnull(`last_name`,' ')) as name FROM `ply_kitchen_supervisor` WHERE `id`='$id' and `deleted`=0"; 
		    $mysql_query =  $this->mysqli->query($get_supervisor_name);			
		    $row11 = $mysql_query->fetch_assoc();				
		    $supervisor_nm = (isset($row11['name'])?$row11['name']:'NA');
		    if($supervisor_nm!='NA')
		    {
		    	return $supervisor_nm;
		    }

	    }
	    public function get_kitchen($kitchen_type,$is_sponserd='no',$lat1,$long1)
	    {
	    	$resArr1 = array();$vendor_arr=array();$price_arr=array();
	    	$kitchen_arr=array();
	    	$get_sponsored_kitchen = "SELECT `id`,`name`,`date_entered`,`description`,`kitchen_type`,`kitchen_id`,`plot_no`,`street_name`,`area_name`,`landmark`,`city_name`,`state_name`,`country_name`,`fssai_lic_no`,`speciality`,`ready_parties_bulk_order`,`capacity`,`team_size`,`fast_meal_served`,`laltitude`,
                `longitude`,`pincode`,`kitchen_unavail_on_sunday`,
                 `ply_kitchen_supervisor_id_c`,`regular_meal_price_range`,
                 `is_sponsored_c`,`sponsored_amount_c`,`kitchen_business_type_c`,`kitchen_serving_time_c`,`sponsorship_from_c`,
                  `sponsorship_to_c`,`regular_meal_min_price_c`,
                   `regular_meal_max_price_c` FROM `ply_kitchen` a join 
                  `ply_kitchen_cstm` b on a.id=b.id_c WHERE a.deleted=0 and 
                  `is_sponsored_c`='$is_sponserd' and `kitchen_business_type_c`=
                        '$kitchen_type' and `status_c`='active'";
            if($this->db_num($get_sponsored_kitchen))
            {
                    		
	                $parent_type = 'ply_Kitchen';
	                $mysql_query13 =  $this->mysqli->query($get_sponsored_kitchen);
	                $resArr = array(); $k=0;
	                while($row13 = mysqli_fetch_assoc($mysql_query13)) 
	                { 
	                    $latitude2 = trim($row13['laltitude']);
				        $logitude2 = trim($row13['longitude']);
				        $distance = get_distance_between_points($lat1, $long1,$latitude2, $logitude2);
				        $kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):0.00;
				        $delivery_range = $this->get_assumption('DR');
				        if($kilometers<=$delivery_range)
						{
	                       $resArr1['row_id']	= $row13['id']; 
	                       $resArr1['name'] 	= $row13['name'];
	                       $resArr1['date_entered'] = $row13['date_entered'];
	                       $resArr1['description'] = $row13['description'];
	                       switch($row13['kitchen_type'])
	                       {
	                       	 case 'veg' : 
	                       	 				$resArr1['veg'] = true;
	                       	 				$resArr1['nonveg'] = false;
	                       	 				$resArr1['egg'] = false;
	                       	 				break;
	                       	 case 'nonveg' :
	                       	 				$resArr1['veg'] = false;
	                       	 				$resArr1['nonveg'] = true;
	                       	 				$resArr1['egg'] = true;
	                       	 				break;
	                       	 case 'egg' : 
	                       	 				$resArr1['veg'] = false;
	                       					$resArr1['nonveg'] = true;
	                       					$resArr1['egg'] = true;
	                       	 				break;
	                       	 case 'veg_nonveg':
	                       	 					$resArr1['veg'] = true;
	                       	 					$resArr1['nonveg'] = true;
	                       	 					$resArr1['egg'] = true;
	                       	 default:
	                       	  		  $resArr1['veg'] = false;
	                       	  		  $resArr1['nonveg'] = false;
	                       	  		  $resArr1['egg'] = false;
	                    	}

	                       $resArr1['kitchen_id'] = $row13['kitchen_id'];
	                       $resArr1['plot_no'] = $row13['plot_no'];
	                       $resArr1['street_name'] = $row13['street_name'];
	                       $resArr1['area_name'] = $row13['area_name'];
	                       $resArr1['landmark'] = $row13['landmark'];
	                       $resArr1['city_name'] = $row13['city_name'];
	                       $resArr1['state_name'] = $row13['state_name'];
	                       $resArr1['country_name'] = $row13['country_name'];
	                       $resArr1['fssai_lic_no'] = $row13['fssai_lic_no'];
	                       $resArr1['speciality'] = $row13['speciality'];
	                       $resArr1['ready_parties_bulk_order'] = $row13['ready_parties_bulk_order'];
	                       $resArr1['capacity'] = $row13['capacity'];
	                       $resArr1['team_size'] = $row13['team_size'];
	                       $resArr1['fast_meal_served'] = $row13['fast_meal_served'];
	                       $resArr1['laltitude'] = $row13['laltitude'];
	                       $resArr1['longitude'] = $row13['longitude'];
	                       $resArr1['pincode'] = $row13['pincode'];
	                       $resArr1['kitchen_unavail_on_sunday'] = $row13['kitchen_unavail_on_sunday'];
	                       $resArr1['regular_meal_price_range'] = $row13['regular_meal_price_range'];
	                       $resArr1['kitchen_business_type'] = $row13['kitchen_business_type_c'];
	                       $resArr1['kitchen_serving_time'] = ucfirst(str_replace("_"," ",$row13['kitchen_serving_time_c']));

	                       if($is_sponserd=='yes')
	                       {
		                      $resArr1['is_sponsored'] = $row13['is_sponsored_c'];
		                      $resArr1['sponsored_amount'] = number_format($row13['sponsored_amount_c'], 2, '.', '');
		                      $resArr1['sponsorship_from'] = $row13['sponsorship_from_c'];
		                      $resArr1['sponsorship_to'] = $row13['sponsorship_to_c'];
	                       }
	                       $resArr1['regular_meal_min_price'] = number_format($row13['regular_meal_min_price_c'], 2, '.', '');
	                       $resArr1['regular_meal_max_price'] = number_format($row13['regular_meal_max_price_c'], 2, '.', '');
	                       $resArr1['Distance'] = $kilometers." Km";
	                       $resArr1['ply_kitchen_supervisor_id_c'] = $row13['ply_kitchen_supervisor_id_c'];
	                   

	                    $kitchen_id = $row13['id'];
	                	$vendor_arr = $this->get_vendor_name_from_kitchen($kitchen_id);
	                	$resArr1['vendor_name'] = trim($vendor_arr['name']);
	                	$resArr1['vendor_id'] = $vendor_arr['vendor_id'];
	                	$resArr1['vendor_row_id'] = $vendor_arr['vendor_row_id'];
	                	//get all vendor images
                        $get_all_vendor_images = "SELECT `ply_vendors_notes_1notes_idb`,D.`photo_category_c` FROM `ply_vendors` A join `ply_vendors_notes_1_c` B on A.id = B.`ply_vendors_notes_1ply_vendors_ida` join `notes` C on C.id = B.`ply_vendors_notes_1notes_idb` join `notes_cstm` D on C.id=D.id_c where A.deleted=0 and C.deleted=0 and B.deleted=0 and A.`id`='".$vendor_arr['vendor_row_id']."'";
	                    $mysql_query_1_1 =  $this->mysqli->query($get_all_vendor_images);
	                   
	                    while($row15 = mysqli_fetch_assoc($mysql_query_1_1))
	                    { 

	                       $resArr1['vendor_images'][$row15['photo_category_c']] = UPLOAD_URL .$row15['ply_vendors_notes_1notes_idb'];
	                      
	                    }

	                	//end
	                    $kitchen_sup_id = $row13['ply_kitchen_supervisor_id_c'];
	                    $resArr1['kitchen_supervisor_name'] = $this->get_kitchen_supervisor_name($kitchen_sup_id);

	                    $price_arr = $this->calculate_monthly_tiffin_price($kitchen_id,$row13['kitchen_type']);
	                    $resArr1['single_tiffin_cost'] = $price_arr['oneTiffinCost'];
	                    $resArr1['Monthly_tiffin_cost'] = $price_arr['MonthTiffinCost'];
	                    
	                    $get_all_kitchen_images = "SELECT B.ply_kitchen_notes_1notes_idb,D.`photo_category_c` FROM `ply_kitchen` A join `ply_kitchen_notes_1_c` B on A.id = B.`ply_kitchen_notes_1ply_kitchen_ida` join `notes` C on C.id = B.`ply_kitchen_notes_1notes_idb` join `notes_cstm` D on C.id=D.id_c where A.deleted=0 and B.deleted=0 and C.deleted=0 and A.`id`='$kitchen_id'";
	                    $mysql_query_1 =  $this->mysqli->query($get_all_kitchen_images);
	                    
	                    while($row13 = mysqli_fetch_assoc($mysql_query_1))
	                    { 

	                       $resArr1['kitchen_images'][$row13['photo_category_c']] = UPLOAD_URL .$row13['ply_kitchen_notes_1notes_idb'];
	                      
	                    }

	                    $get_all_kitchen_cuisines = "SELECT GROUP_CONCAT(`name` SEPARATOR ',') as cuisines FROM `ply_cuisine` A join `ply_kitchen_ply_cuisine_1_c` B on A.id=B.`ply_kitchen_ply_cuisine_1ply_cuisine_idb` WHERE A.deleted=0 and B.deleted=0 and B.ply_kitchen_ply_cuisine_1ply_kitchen_ida='$kitchen_id'";
	                    $mysql_query_2 =  $this->mysqli->query($get_all_kitchen_cuisines);
	                    $row14 = mysqli_fetch_assoc($mysql_query_2);
	                    $resArr1['cuisine'] = (!empty($row14['cuisines'])?$row14['cuisines']:'NA');

	                    $get_all_kitchen_addons = "SELECT GROUP_CONCAT(`name` SEPARATOR ',') as addOn FROM `ply_add_on` A join `ply_kitchen_ply_add_on_1_c` B on A.id=B.`ply_kitchen_ply_add_on_1ply_add_on_idb` WHERE A.deleted=0 and B.deleted = 0 and B.ply_kitchen_ply_add_on_1ply_kitchen_ida='$kitchen_id'";
	                    $mysql_query_3 =  $this->mysqli->query($get_all_kitchen_addons);
	                    $row15 = mysqli_fetch_assoc($mysql_query_3);
	                    $resArr1['addons'] = (!empty($row15['addOn'])?$row15['addOn']:'NA');

	                $rating = array();
	                $get_kitchen_ratings = "SELECT `rating` FROM `ply_rating_given_by_cust_2_kitchen` A join `ply_rating_given_by_cust_2_kitchen_cstm` B on A.id=B.id_C join ply_kitchen_ply_rating_given_by_cust_2_kitchen_1_c C on A. `ply_kitchen_id_c` = C.ply_kitchen_ply_rating_given_by_cust_2_kitchen_1ply_kitchen_ida WHERE A.deleted=0 and C.deleted=0 and `ply_kitchen_id_c`='$kitchen_id'";
	                $mysql_query_1_2 =  $this->mysqli->query($get_kitchen_ratings);
	                while($row16 = mysqli_fetch_assoc($mysql_query_1_2))
	                { 

	                   $rating[] = $row16['rating'];
	                }

	                $rating_avg = array_sum($rating) / count(array_filter($rating));
	                if(is_nan($rating_avg))
	                {
	                	$rating_avg = 0;
	                }

	                $resArr1['rating'] = number_format($rating_avg, 1, '.', '');
	                       
	                }
	                if(!empty($resArr1))
	                {
	                	array_push($kitchen_arr,$resArr1);
	                }
	                $resArr1 = array();
	                      
	            } //while loop closing
	                	
	         }
	               
	               return $kitchen_arr;
             
	    }
	    public function get_assumption($shortcode)
	    {
	    	$get_assumption_value= "SELECT `name`,ROUND(`current_value`, 2) as value FROM `ply_assumption` WHERE `shortcode`='$shortcode' and deleted=0"; 
		    $mysql_query =  $this->mysqli->query($get_assumption_value);			
		    $row11 = $mysql_query->fetch_assoc();				
		    $value = (isset($row11['value'])?$row11['value']:'NA');
		    if($supervisor_nm!='NA')
		    {
		    	return $value;
		    }
	    }
	    public function get_vendor_name_from_kitchen($kitchen_id)
	    {
	    	$vendor_arr =  array();
	    	$parent_type = 'ply_Vendors';
	    	$get_vendor_details= "SELECT A.`id`,Concat(Ifnull(`salutation`,' '),' ',Ifnull(`first_name`,' ') ,' ', Ifnull(`last_name`,' ')) as name,`vendor_id` FROM `ply_vendors` A join `ply_vendors_cstm` B on A.id=B.id_c join ply_vendors_ply_kitchen_1_c C on A.id=C.ply_vendors_ply_kitchen_1ply_vendors_ida WHERE A.deleted=0 and C.ply_vendors_ply_kitchen_1ply_kitchen_idb='$kitchen_id'";
		    $mysql_query =  $this->mysqli->query($get_vendor_details);			
		    $row11 = $mysql_query->fetch_assoc();				
		    $vendor_name = (isset($row11['name'])?$row11['name']:'NA');
		    $vendor_id = (isset($row11['vendor_id'])?$row11['vendor_id']:'NA');
		    $row_id = (isset($row11['id'])?$row11['id']:'NA');
		    if($vendor_name!='NA' && $vendor_id !='NA')
		    {
		    	//$vendor_img = $this->get_image_from_notes_module($row_id,$parent_type);
		    	return array('vendor_row_id'=>$row_id,'name'=>$vendor_name,'vendor_id'=>$vendor_id);
		    }
	    }
	    public function calculate_monthly_tiffin_price($kitchen_id,$kitchen_type=null)
	    {
	    	$resArr = array();
	    	$get_min_max_kitchen_price = "SELECT `regular_meal_min_price_c`,`regular_meal_max_price_c` FROM `ply_kitchen` A join `ply_kitchen_cstm` B on A.id=B.id_c WHERE `id`='$kitchen_id'";
	    	$mysql_query =  $this->mysqli->query($get_min_max_kitchen_price);
		    $row11 = $mysql_query->fetch_assoc();				
		    $regular_meal_min_price = (isset($row11['regular_meal_min_price_c'])?$row11['regular_meal_min_price_c']:0.00);
		    $regular_meal_max_price = (isset($row11['regular_meal_max_price_c'])?$row11['regular_meal_max_price_c']:0.00);
		    $values_arr =  array($regular_meal_min_price,$regular_meal_max_price);
		    $average = array_sum($values_arr)/count(array_filter($values_arr));
            $resArr = $this->get_monthly_cost_incurred(30,60,'lunch_and_Dinner',$kitchen_type);
            $no_of_tiffins_to_deliver = $resArr['no_of_tiffins_to_deliver'];
            $total_cost_incurred      = $resArr['total_cost_incurred'];
            $profit_margin_per_tiffin = $resArr['profit_margin_per_tiffin'];
            if(!is_nan($average) && !is_infinite($average))
            {
            	$one_tiffin_cost = ceil($average+$total_cost_incurred+$profit_margin_per_tiffin);
                $month_tiffin_cost = ($one_tiffin_cost * $no_of_tiffins_to_deliver);

               return array('oneTiffinCost'=>$one_tiffin_cost,'MonthTiffinCost'=>$month_tiffin_cost);
            }else{

            	return array('oneTiffinCost'=>0.00,'MonthTiffinCost'=>0.00);
            }
            

	    }
	    public function get_monthly_cost_incurred($no_of_days_deliveries,$no_of_tiffins_to_delivers,$delivery_time,$pure_veg_flag=null)
	    {
	    	if($pure_veg_flag=='veg')
	    	{
	    		$pure_veg = ' and `pure_veg_c`=1';
	    	}else if($pure_veg_flag=='nonveg')
	    	{
	    		$pure_veg = ' and `pure_veg_c`=0';
	    	}
	    	else if($pure_veg_flag=='veg_nonveg')
	    	{
	    		$pure_veg = ' and `pure_veg_c`=1';
	    	}
	    	else if($pure_veg_flag=='egg')
	    	{
 				$pure_veg = " and `pure_veg_c`=0";
	    	}else{
	    		$pure_veg = " and `pure_veg_c`=''";
	    	}
	    	
	    	$get_monthly_cost = "SELECT `total_cost_incurred_c`,`profit_margin_per_tiffin`,`no_of_tiffins_to_deliver` FROM `ply_package_rate_finder` A join `ply_package_rate_finder_cstm` B on A.id=B.id_c WHERE A.deleted=0 and `no_of_days_deliveries`=$no_of_days_deliveries and `no_of_tiffins_to_deliver`=$no_of_tiffins_to_delivers and `delivery_time`='$delivery_time' $pure_veg limit 1";
	    	
	    	$mysql_query =  $this->mysqli->query($get_monthly_cost);
		    $row11 = $mysql_query->fetch_assoc();				
		    $total_cost_incurred = (isset($row11['total_cost_incurred_c'])?$row11['total_cost_incurred_c']:0.00);
		    $profit_margin_per_tiffin = (isset($row11['profit_margin_per_tiffin'])?$row11['profit_margin_per_tiffin']:0.00);
		    return array('no_of_tiffins_to_deliver'=>$row11['no_of_tiffins_to_deliver'],'total_cost_incurred'=>$total_cost_incurred,'profit_margin_per_tiffin'=>$profit_margin_per_tiffin);

	    }
	    public function get_all_menus($lat1,$long1,$menu_category,$menu_type)
	    {
	    	
	    	if($menu_type=='veg')
	    	{
	    		$menu_type_cond = "`menu_type`='veg'";
	    	}else if($menu_type=='nonveg')
	    	{
	    		$menu_type_cond = "`menu_type`='nonveg'";
	    	}else if($menu_type=='egg')
	    	{
	    		$menu_type_cond = "`menu_type`='egg'";
	    	}else if($menu_type=='all')
	    	{
	    		$menu_type_cond = "(`menu_type`='veg' OR `menu_type`='nonveg' OR `menu_type`='egg')";
	    	}
	    	$get_categorywiseMenus = "SELECT `id`,`name`,`date_entered`,`description`,`menu_category`,`menu_incentive`,`menu_type`,`ply_cuisine_id_c`,`ply_accompaniments_id_c`,`ply_accompaniments_id1_c`,`ply_accompaniments_id2_c`,`ply_add_on_id_c`,`menu_name_c`,`ply_kitchen_id_c`,`ply_accompaniments_id3_c`,`latitude_c`,`longitude_c`,`ply_accompaniments_id4_c`,`ply_add_on_id1_c`,`ply_add_on_id2_c`,`ply_add_on_id3_c` FROM `ply_menu_master` A join `ply_menu_master_cstm` B on A.id=B.id_c  WHERE `status`='active' and `menu_category`='$menu_category' and $menu_type_cond and A.deleted = 0";
	    	$mysql_query13 =  $this->mysqli->query($get_categorywiseMenus);
	    	$m=0;$menus_arr = array(); $menus_arr1=array();
	    	while($row13 = mysqli_fetch_assoc($mysql_query13)) 
	        { 
	            $latitude2 = trim($row13['latitude_c']);
				$logitude2 = trim($row13['longitude_c']);
				$distance = get_distance_between_points($lat1,$long1,$latitude2,$logitude2);
				$kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):0.00;
				$delivery_range = $this->get_assumption('DR');
				if($kilometers<=$delivery_range)
				{
					$menus_arr['row_id'] = $row13['id'];
					$menus_arr['name'] = $row13['name'];
					$menus_arr['date_entered'] = $row13['date_entered'];
					$menus_arr['description'] = $row13['description'];
					$menus_arr['menu_category'] = $row13['menu_category'];
					$menus_arr['menu_incentive'] = $row13['menu_incentive'];
					$menus_arr['menu_type'] = $row13['menu_type'];
					$menus_arr['menu_name'] = $row13['menu_name_c'];
					
					$menus_arr['accompaniments1'] = $this->get_accompaniments($row13['ply_accompaniments_id_c'])['name'];
					$menus_arr['accompaniments2'] = $this->get_accompaniments($row13['ply_accompaniments_id1_c'])['name'];
					$menus_arr['accompaniments3'] = $this->get_accompaniments($row13['ply_accompaniments_id2_c'])['name'];
					$menus_arr['accompaniments4'] = $this->get_accompaniments($row13['ply_accompaniments_id3_c'])['name'];
					$menus_arr['accompaniments5'] = $this->get_accompaniments($row13['ply_accompaniments_id4_c'])['name'];

					$menus_arr['addon1'] = $this->get_addon($row13['ply_add_on_id_c'])['name'];
					$menus_arr['addon2'] = $this->get_addon($row13['ply_add_on_id1_c'])['name'];
					$menus_arr['addon3'] = $this->get_addon($row13['ply_add_on_id2_c'])['name'];
					$menus_arr['addon4'] = $this->get_addon($row13['ply_add_on_id3_c'])['name'];

					$menus_arr['image'] = $this->get_image_from_notes_module($row13['id'],'ply_Menu_Master');
				}
                if(!empty($menus_arr))
                {
                	array_push($menus_arr1,$menus_arr);
                }
				
				
			}
			//print_r($menus_arr);exit;
			return $menus_arr1;

	    }
	    public function get_all_todays_special_menus($lat1,$long1)
	    {
	    	$special_menus_arr = array();
	    	$get_all_special_menus = "SELECT `id`,`ply_menu_master_id_c`,`ply_kitchen_id_c`,`delivery_time`,`meal_of_day_date` FROM `ply_special_menu` WHERE deleted=0 and `meal_of_day_date` = CURRENT_DATE";
	    	$mysql_query13 =  $this->mysqli->query($get_all_special_menus);
	    	$special_arr1 = array();
	    	while($row13 = mysqli_fetch_assoc($mysql_query13)) 
	        { 
	        	
				
				$kitchen_id =  $row13['ply_kitchen_id_c'];
				$get_kitchen_details = "SELECT `name`,`laltitude`,`longitude`,`regular_meal_min_price_c`,`regular_meal_max_price_c` FROM `ply_kitchen` a join `ply_kitchen_cstm` b on a.id=b.id_c WHERE a.deleted=0 and `id`='$kitchen_id'";
				$mysql_query14 =  $this->mysqli->query($get_kitchen_details);
				$row14 = mysqli_fetch_assoc($mysql_query14);
				$latitude2 =  $row14['laltitude'];
				$longitude2 =  $row14['longitude'];
				$distance = get_distance_between_points($lat1,$long1,$latitude2,$longitude2);
				$kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):0.00;
				$delivery_range = $this->get_assumption('DR');

				if($kilometers<=$delivery_range)
				{
					$special_menus_arr['Distance'] = $kilometers;
					$special_menus_arr['kitchen_name'] = $row14['name'];
					$special_menus_arr['special_menu_row_id'] = $row13['id'];
					$special_menus_arr['delivery_time'] = $row13['delivery_time'];
					$delivery_time = $row13['delivery_time'];

					$regular_meal_min_price = (isset($row14['regular_meal_min_price_c'])?$row14['regular_meal_min_price_c']:0.00);
				    $regular_meal_max_price = (isset($row14['regular_meal_max_price_c'])?$row14['regular_meal_max_price_c']:0.00);
				    $values_arr =  array($regular_meal_min_price,$regular_meal_max_price);
				    $average = array_sum($values_arr)/count(array_filter($values_arr));

				    if($delivery_time=='lunch')
				    	$no_of_tiffins_to_deliver = 1;
				    else if($delivery_time=='dinner')
				    	$no_of_tiffins_to_deliver = 1;
				    else if($delivery_time=='lunch_and_Dinner')
				    	$no_of_tiffins_to_deliver = 2;

		            $resArr = $this->get_monthly_cost_incurred(1,$no_of_tiffins_to_deliver,$delivery_time,$pure_veg_flag=null);
		            $no_of_tiffins_to_deliver = $resArr['no_of_tiffins_to_deliver'];
		            $total_cost_incurred      = $resArr['total_cost_incurred'];
		            $profit_margin_per_tiffin = $resArr['profit_margin_per_tiffin'];
		            if(!is_nan($average) && !is_infinite($average))
		            {
		            	$one_tiffin_cost = ceil($average+$total_cost_incurred+$profit_margin_per_tiffin);
		            	$meal_of_the_day_tiffin_cost = ($one_tiffin_cost * $no_of_tiffins_to_deliver);
		            	$special_menus_arr['meal_of_day_cost'] = number_format($meal_of_the_day_tiffin_cost, 2, '.', '');

		            }else{

		            	$special_menus_arr['meal_of_day_cost'] = 0.00;
		            }

				    $menu_id =  $row13['ply_menu_master_id_c'];
				    $get_categorywiseMenus = "SELECT `id`,`name`,`date_entered`,`description`,`menu_category`,`menu_incentive`,`menu_type`,`ply_cuisine_id_c`,`ply_accompaniments_id_c`,`ply_accompaniments_id1_c`,`ply_accompaniments_id2_c`,`ply_add_on_id_c`,`menu_name_c`,`ply_kitchen_id_c`,`ply_accompaniments_id3_c`,`latitude_c`,`longitude_c`,`ply_accompaniments_id4_c`,`ply_add_on_id1_c`,`ply_add_on_id2_c`,`ply_add_on_id3_c` FROM `ply_menu_master` A join `ply_menu_master_cstm` B on A.id=B.id_c  WHERE `status`='active' and A.deleted = 0 and A.id='$menu_id'";
			    	$mysql_query13 =  $this->mysqli->query($get_categorywiseMenus);
			    	$row15 = mysqli_fetch_assoc($mysql_query13);
			     
					$special_menus_arr['main_menu_row_id'] = $row15['id'];
					$special_menus_arr['menu_name'] = $row15['name'];
					$special_menus_arr['date_entered'] = $row15['date_entered'];
					$special_menus_arr['description'] = $row15['description'];
					$special_menus_arr['menu_category'] = $row15['menu_category'];
					$special_menus_arr['menu_incentive'] = $row15['menu_incentive'];
					$special_menus_arr['menu_type'] = $row15['menu_type'];
					$special_menus_arr['dish_name'] = $row15['menu_name_c'];
							
					$special_menus_arr['accompaniments1'] = $this->get_accompaniments($row15['ply_accompaniments_id_c'])['name'];
					$special_menus_arr['accompaniments1_cost'] = $this->get_accompaniments($row15['ply_accompaniments_id_c'])['cost'];
					$special_menus_arr['accompaniments2'] = $this->get_accompaniments($row15['ply_accompaniments_id1_c'])['name'];
					$special_menus_arr['accompaniments2_cost'] = $this->get_accompaniments($row15['ply_accompaniments_id_c'])['cost'];
					$special_menus_arr['accompaniments3'] = $this->get_accompaniments($row15['ply_accompaniments_id2_c'])['name'];
					$special_menus_arr['accompaniments3_cost'] = $this->get_accompaniments($row15['ply_accompaniments_id_c'])['cost'];
					$special_menus_arr['accompaniments4'] = $this->get_accompaniments($row15['ply_accompaniments_id3_c'])['name'];
					$special_menus_arr['accompaniments4_cost'] = $this->get_accompaniments($row15['ply_accompaniments_id_c'])['cost'];
					$special_menus_arr['accompaniments5'] = $this->get_accompaniments($row15['ply_accompaniments_id4_c'])['name'];
					$special_menus_arr['accompaniments5_cost'] = $this->get_accompaniments($row15['ply_accompaniments_id_c'])['cost'];

					$special_menus_arr['addon1'] = $this->get_addon($row15['ply_add_on_id_c'])['name'];
					$special_menus_arr['addon1_price'] = $this->get_addon($row15['ply_add_on_id_c'])['price'];

					$special_menus_arr['addon2'] = $this->get_addon($row15['ply_add_on_id1_c'])['name'];
					$special_menus_arr['addon2_price'] = $this->get_addon($row15['ply_add_on_id_c'])['price'];

					$special_menus_arr['addon3'] = $this->get_addon($row15['ply_add_on_id2_c'])['name'];
					$special_menus_arr['addon3_price'] = $this->get_addon($row15['ply_add_on_id_c'])['price'];

					$special_menus_arr['addon4'] = $this->get_addon($row15['ply_add_on_id3_c'])['name'];
					$special_menus_arr['addon4_price'] = $this->get_addon($row15['ply_add_on_id_c'])['price'];

					//get menu image
					$get_menu_master_imgs = "SELECT B.ply_menu_master_notes_1notes_idb FROM `ply_menu_master` A join `ply_menu_master_notes_1_c` B on A.id = B.`ply_menu_master_notes_1ply_menu_master_ida` where A.deleted=0 and B.deleted=0 and A.`id`='".$row15['id']."'";
                    $mysql_query_menu_master =  $this->mysqli->query($get_menu_master_imgs); 
                    $row_menu_master = mysqli_fetch_assoc($mysql_query_menu_master);
                    if($row_menu_master['ply_menu_master_notes_1notes_idb']!='')
                         $special_menus_arr['image'] = UPLOAD_URL .$row_menu_master['ply_menu_master_notes_1notes_idb'];
                    else
                       $special_menus_arr['image'] = 'image not available'; 
					// $special_menus_arr['image'] = $this->get_image_from_notes_module($row15['id'],'ply_Menu_Master');
						
		        }
		        if(!empty($special_menus_arr))
                {
                	array_push($special_arr1,$special_menus_arr);
                }
                $special_menus_arr = array();
                return $special_arr1;
		    } // while loop closing

	    }

	    public function get_accompaniments($id)
	    {
	    	$get_accompaniments ="SELECT `name`,`accompaniment_type`,`accompaniment_category`,`ply_kitchen_id_c`,`no_of_servings`,`cost_c` FROM `ply_accompaniments` A join `ply_accompaniments_cstm` B on A.id=B.id_c WHERE A.deleted=0 and `id`='$id'";
	    	$mysql_query13 =  $this->mysqli->query($get_accompaniments);
	    	$row11 = $mysql_query13->fetch_assoc();				
		    $name = (isset($row11['name'])?$row11['name']:'NA');
		    $accompaniment_type = (isset($row11['accompaniment_type'])?$row11['accompaniment_type']:"NA");
		    $accompaniment_category = (isset($row11['accompaniment_category'])?$row11['accompaniment_category']:"NA");
		    $ply_kitchen_id_c = (isset($row11['ply_kitchen_id_c'])?$row11['ply_kitchen_id_c']:"NA");
		    $no_of_servings = (isset($row11['no_of_servings'])?$row11['no_of_servings']:"NA");
		    $cost = (isset($row11['cost_c'])?$row11['cost_c']:0.00);
		    return array('name'=>$name,'accompaniment_type'=>$accompaniment_type,'accompaniment_category'=>$accompaniment_category,'no_of_servings'=>$no_of_servings,'cost'=>$cost);
	    }
	    public function get_addon($id)
	    {
	    	$get_addons ="SELECT `name`,`category`,`quantity`,`price`,`deliverable_time` FROM `ply_add_on` WHERE `deleted`=0 and `id`='$id'";
	    	$mysql_query13 =  $this->mysqli->query($get_addons);
	    	$row11 = $mysql_query13->fetch_assoc();				
		    $name = (isset($row11['name'])?$row11['name']:'NA');
		    $category = (isset($row11['category'])?$row11['category']:"NA");
		    $quantity = (isset($row11['quantity'])?$row11['quantity']:0);
		    $price = (isset($row11['price'])?$row11['price']:0.00);
		    $deliverable_time = (isset($row11['deliverable_time'])?$row11['deliverable_time']:"NA");
		    return array('name'=>$name,'category'=>$category,'quantity'=>$quantity,'price'=>$price,'deliverable_time'=>$deliverable_time);
	    }
	    public function get_all_vendors($lat1,$long1)
	    {
	    	$vendor_arr =  array();$vendor_arr1 =  array();
	    	$parent_type = 'ply_Vendors';
	    	$get_vendor_details= "SELECT A.`id`,Concat(Ifnull(`first_name`,' ') ,' ', Ifnull(`last_name`,' ')) as name,`vendor_id`,`date_entered`,`description`,`website`,`team_size`,`city_name_c`,`phone_mobile`,`phone_work`,`primary_address_street`,`primary_address_city`,`primary_address_state`,`primary_address_postalcode`,`primary_address_country`,`about_vendor` FROM `ply_vendors` A join `ply_vendors_cstm` B on A.`id`=B.`id_c` join `ply_vendors_ply_kitchen_1_c` C on A.`id`=C.`ply_vendors_ply_kitchen_1ply_vendors_ida` where A.`deleted`=0 and C.`deleted`=0 and B.`status_c`='active' group by id";
		    $mysql_query =  $this->mysqli->query($get_vendor_details);			
		    while($row11 = $mysql_query->fetch_assoc())
		    {
		    	$check_vendor_kitchens = "SELECT A.`id`,C.`id` as kitchenID,C.`name`,`kitchen_id`,`regular_meal_min_price_c`,`regular_meal_max_price_c`,`laltitude`,`longitude` FROM `ply_vendors` A join `ply_vendors_ply_kitchen_1_c` B on A.`id` = B.`ply_vendors_ply_kitchen_1ply_vendors_ida` join ply_kitchen C on B.`ply_vendors_ply_kitchen_1ply_kitchen_idb` = C.`id` join `ply_kitchen_cstm` D on C.id=D.id_c WHERE `ply_vendors_ply_kitchen_1ply_vendors_ida`='".$row11['id']."' and A.deleted=0 and B.deleted=0";
		    	$count = $this->db_num($check_vendor_kitchens);
		    	if($count==1)
		    	{
		    		$mysql_query_1 =  $this->mysqli->query($check_vendor_kitchens);
		    		$row12 = $mysql_query_1->fetch_assoc();
		    		$latitude2 = $row12['laltitude'];
		    		$longitude2 = $row12['longitude'];
		    		$distance = get_distance_between_points($lat1,$long1,$latitude2,$longitude2);
					$kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):0.00;
					$delivery_range = $this->get_assumption('DR');

					if($kilometers<=$delivery_range)
					{
				    	$vendor_arr['row_id'] = $row11['id'];
				    	$vendor_arr['distance'] = $kilometers;
						$vendor_arr['name'] = $row11['name'];
						$vendor_arr['kitchen_name'] = $row12['name'];
						$vendor_arr['vendor_id'] = $row11['vendor_id'];
						$vendor_arr['kitchen_id'] = $row12['kitchen_id'];
						$vendor_arr['date_entered'] = $row11['date_entered'];
						$vendor_arr['description'] = $row11['description'];
						$vendor_arr['website'] = $row11['website'];
						$vendor_arr['team_size'] = $row11['team_size'];
						$vendor_arr['city_name'] = $row11['city_name_c'];
						$vendor_arr['phone_mobile'] = $row11['phone_mobile'];
						$vendor_arr['phone_work'] = $row11['phone_work'];
						$vendor_arr['primary_address_street'] = $row11['primary_address_street'];
						$vendor_arr['primary_address_city'] = $row11['primary_address_city'];
						$vendor_arr['primary_address_state'] = $row11['primary_address_state'];
						$vendor_arr['primary_address_postalcode'] = $row11['primary_address_postalcode'];
						$vendor_arr['primary_address_country'] = $row11['primary_address_country'];
						$vendor_arr['about_vendor'] = $row11['about_vendor'];
						$vendor_arr['oneTiffinCost'] = $this->calculate_monthly_tiffin_price($row12['kitchenID'])['oneTiffinCost'];
						$vendor_arr['MonthTiffinCost'] = $this->calculate_monthly_tiffin_price($row12['kitchenID'])['MonthTiffinCost'];

						$rating = array();
		                $get_kitchen_ratings = "SELECT `rating` FROM `ply_rating_given_by_cust_2_kitchen` A join `ply_rating_given_by_cust_2_kitchen_cstm` B on A.id=B.id_C join ply_kitchen_ply_rating_given_by_cust_2_kitchen_1_c C on A. `ply_kitchen_id_c` = C.ply_kitchen_ply_rating_given_by_cust_2_kitchen_1ply_kitchen_ida WHERE A.deleted=0 and C.deleted=0 and `ply_kitchen_id_c`='".$row12['kitchenID']."'";
		                $mysql_query_1_2 =  $this->mysqli->query($get_kitchen_ratings);
		                while($row16 = mysqli_fetch_assoc($mysql_query_1_2))
		                { 

		                   $rating[] = $row16['rating'];
		                }

		                $rating_avg = array_sum($rating) / count(array_filter($rating));
		                if(is_nan($rating_avg))
		                {
		                	$rating_avg = 0;
		                }

		                $vendor_arr['rating'] = number_format($rating_avg, 1, '.', '');
						//$vendor_arr['image'] = $this->get_image_from_notes_module($row11['id'],$parent_type);

						//get all vendor images
	                        $get_all_vendor_images = "SELECT `ply_vendors_notes_1notes_idb`,D.`photo_category_c` FROM `ply_vendors` A join `ply_vendors_notes_1_c` B on A.id = B.`ply_vendors_notes_1ply_vendors_ida` join `notes` C on C.id = B.`ply_vendors_notes_1notes_idb` join `notes_cstm` D on C.id=D.id_c where A.deleted=0 and C.deleted=0 and B.deleted=0 and A.`id`='".$row11['id']."'";
		                    $mysql_query_1_1 =  $this->mysqli->query($get_all_vendor_images);
		                   
		                    while($row15 = mysqli_fetch_assoc($mysql_query_1_1))
		                    { 

		                       $vendor_arr['vendor_images'][$row15['photo_category_c']] = UPLOAD_URL .$row15['ply_vendors_notes_1notes_idb'];
		                      
		                    }
		                // add kitchen best value package code
		                $get_best_value_kit_pack = "SELECT * FROM `ply_package` A join `ply_package_cstm` B on A.id=B.id_c join `ply_kitchen_ply_package_1_c` C on A.`id`=C.`ply_kitchen_ply_package_1ply_package_idb` WHERE `ply_kitchen_ply_package_1ply_kitchen_ida`='".$row12['kitchenID']."' and A.deleted=0 and C.deleted=0 and B.`best_value_c`=1";
		                $mysql_query_1_1_2 =  $this->mysqli->query($get_best_value_kit_pack);
		                $row18 = mysqli_fetch_assoc($mysql_query_1_1_2);
		                //$best_value_pac_nm = $row18['name'];
		                $vendor_arr['best_value_package'] = (!empty($row18['name'])?$row18['name']:'Not available');
						if(!empty($vendor_arr))
		                {
		                	array_push($vendor_arr1,$vendor_arr);
		                }
		            }
	            }
		    }	
		    return $vendor_arr1;

		    
	    }
	    public function get_specific_kitchen_details($kitchen_id)
	    {
	    	$vendor_arr=array();$price_arr=array();
	    	$kitchen_arr=array();
	    	$get_kitchen = "SELECT `id`,`name`,`date_entered`,`description`,`kitchen_type`,`kitchen_id`,`plot_no`,`street_name`,`area_name`,`landmark`,`city_name`,`state_name`,`country_name`,`fssai_lic_no`,`speciality`,`ready_parties_bulk_order`,`capacity`,`team_size`,`fast_meal_served`,`laltitude`,
                `longitude`,`pincode`,`kitchen_unavail_on_sunday`,
                 `ply_kitchen_supervisor_id_c`,`regular_meal_price_range`,
                 `is_sponsored_c`,`sponsored_amount_c`,`kitchen_business_type_c`,`kitchen_serving_time_c`,`sponsorship_from_c`,
                  `sponsorship_to_c`,`regular_meal_min_price_c`,
                   `regular_meal_max_price_c` FROM `ply_kitchen` a join 
                  `ply_kitchen_cstm` b on a.id=b.id_c WHERE a.deleted=0 and 
                  `id`='$kitchen_id'";
                  
            if($this->db_num($get_kitchen))
            {
                    		
	                $parent_type = 'ply_Kitchen';
	                $mysql_query13 =  $this->mysqli->query($get_kitchen);
	                $row13 = mysqli_fetch_assoc($mysql_query13); 
	                $kitchen_id = $row13['id']; 
	                   
	                $resArr1['row_id']	= $row13['id']; 
	                $resArr1['name'] 	= ucfirst($row13['name']);
	                $resArr1['date_entered'] = $row13['date_entered'];
	                $resArr1['description'] = w1250_to_utf8($row13['description']);
	                $resArr1['kitchen_type'] = ucfirst($row13['kitchen_type']);
	                $resArr1['kitchen_id'] = $row13['kitchen_id'];
	                $resArr1['plot_no'] = $row13['plot_no'];
	                $resArr1['street_name'] = $row13['street_name'];
	                $resArr1['area_name'] = $row13['area_name'];
	                $resArr1['landmark'] = $row13['landmark'];
	                $resArr1['city_name'] = ucfirst($row13['city_name']);
	                $resArr1['state_name'] = $row13['state_name'];
	                $resArr1['country_name'] = $row13['country_name'];
	                $resArr1['fssai_lic_no'] = $row13['fssai_lic_no'];
	                $resArr1['speciality'] = $row13['speciality'];
	                $resArr1['ready_parties_bulk_order'] = $row13['ready_parties_bulk_order'];
	                $resArr1['capacity'] = $row13['capacity'];
	                $resArr1['team_size'] = $row13['team_size'];
	                $resArr1['fast_meal_served'] = $row13['fast_meal_served'];
	                $resArr1['laltitude'] = $row13['laltitude'];
	                $resArr1['longitude'] = $row13['longitude'];
	                $resArr1['pincode'] = $row13['pincode'];
	                $resArr1['kitchen_unavail_on_sunday'] = $row13['kitchen_unavail_on_sunday'];
	                $resArr1['kitchen_business_type'] = $row13['kitchen_business_type_c'];
	                $resArr1['kitchen_serving_time'] = $row13['kitchen_serving_time'];

		            $resArr1['is_sponsored'] = $row13['is_sponsored_c'];
		            $resArr1['sponsored_amount'] = number_format($row13['sponsored_amount_c'], 2, '.', '');
		            $resArr1['sponsorship_from'] = $row13['sponsorship_from_c'];
		            $resArr1['sponsorship_to'] = $row13['sponsorship_to_c'];
	                      
	                $resArr1['regular_meal_min_price'] = number_format($row13['regular_meal_min_price_c'], 2, '.', '');
	                $resArr1['regular_meal_max_price'] = number_format($row13['regular_meal_max_price_c'], 2, '.', '');
	                $resArr1['ply_kitchen_supervisor_id_c'] = $row13['ply_kitchen_supervisor_id_c'];
	                   

	                
	               $vendor_arr = $this->get_vendor_name_from_kitchen($kitchen_id);
	               $resArr1['vendor_name'] = trim($vendor_arr['name']);
	               $resArr1['vendor_id'] = $vendor_arr['vendor_id'];
	               $resArr1['vendor_row_id'] = $vendor_arr['vendor_row_id'];
	                	//get all vendor images
                   $get_all_vendor_images = "SELECT `id`,`photo_category_c`,`parent_id` FROM `notes` a join `notes_cstm` b on a.id=b.id_c where a.deleted=0 and `parent_type`='ply_Vendors' and `parent_id`='".$vendor_arr['vendor_row_id']."'";
	                    $mysql_query_1_1 =  $this->mysqli->query($get_all_vendor_images);
	               
	                while($row15 = mysqli_fetch_assoc($mysql_query_1_1))
	                { 

	                       $resArr1['vendor_images'][$row15['photo_category_c']] = UPLOAD_URL .$row15['id'];
	                     
	                }

	                	//end
	                $kitchen_sup_id = $row13['ply_kitchen_supervisor_id_c'];
	                $resArr1['kitchen_supervisor_name'] = $this->get_kitchen_supervisor_name($kitchen_sup_id);
					$price_arr = $this->calculate_monthly_tiffin_price($kitchen_id,$row13['kitchen_type']);
	                $resArr1['single_tiffin_cost'] = $price_arr['oneTiffinCost'];
	                $resArr1['Monthly_tiffin_cost'] = $price_arr['MonthTiffinCost'];
	                $get_all_kitchen_images = "SELECT `id`,`photo_category_c`,`parent_id` FROM `notes` a join `notes_cstm` b on a.id=b.id_c where a.deleted=0 and `parent_type`='ply_Kitchen' and `parent_id`='$kitchen_id'";
	                $mysql_query_1 =  $this->mysqli->query($get_all_kitchen_images);
	               
	               while($row13 = mysqli_fetch_assoc($mysql_query_1))
	                { 

	                   $resArr1['kitchen_images'][$row13['photo_category_c']] = UPLOAD_URL .$row13['id'];
	                      
	                }

	                $get_all_kitchen_cuisines = "SELECT GROUP_CONCAT(`name` SEPARATOR ',') as cuisines FROM `ply_cuisine` A join `ply_kitchen_ply_cuisine_1_c` B on A.id=B.`ply_kitchen_ply_cuisine_1ply_cuisine_idb` WHERE A.deleted=0 and B.deleted=0 and B.ply_kitchen_ply_cuisine_1ply_kitchen_ida='$kitchen_id'";
	                $mysql_query_2 =  $this->mysqli->query($get_all_kitchen_cuisines);
	                $row14 = mysqli_fetch_assoc($mysql_query_2);
	                $resArr1['cuisine'] = (!empty($row14['cuisines'])?$row14['cuisines']:'NA');

	                $get_all_kitchen_addons = "SELECT GROUP_CONCAT(`name` SEPARATOR ',') as addOn FROM `ply_add_on` A join `ply_kitchen_ply_add_on_1_c` B on A.id=B.`ply_kitchen_ply_add_on_1ply_add_on_idb` WHERE A.deleted=0 and B.deleted = 0 and B.ply_kitchen_ply_add_on_1ply_kitchen_ida='$kitchen_id'";
	                    $mysql_query_3 =  $this->mysqli->query($get_all_kitchen_addons);
	                    $row15 = mysqli_fetch_assoc($mysql_query_3);
	                    $resArr1['addons'] = (!empty($row15['addOn'])?$row15['addOn']:'NA');

	                $rating = array();
	                $review = array();
	                $get_kitchen_ratings = "SELECT `rating`,`review_c`,`account_id_c` FROM `ply_rating_given_by_cust_2_kitchen` A join `ply_rating_given_by_cust_2_kitchen_cstm` B on A.id=B.id_C join ply_kitchen_ply_rating_given_by_cust_2_kitchen_1_c C on A. `ply_kitchen_id_c` = C.ply_kitchen_ply_rating_given_by_cust_2_kitchen_1ply_kitchen_ida WHERE A.deleted=0 and C.deleted=0 and `ply_kitchen_id_c`='$kitchen_id' group by `account_id_c`";
	               //exit;
	                $mysql_query_1_2 =  $this->mysqli->query($get_kitchen_ratings);
	                $inc = 0;
	                while($row16 = mysqli_fetch_assoc($mysql_query_1_2))
	                { 
	                	$get_cust_name = "SELECT `name`,`image_path_c` FROM `accounts` A join `accounts_cstm` B on A.`id`=B.`id_c` WHERE deleted=0 and `id`='".$row16['account_id_c']."'";
	                	$mysql_query_3_1 =  $this->mysqli->query($get_cust_name);
	                	$row17 = mysqli_fetch_assoc($mysql_query_3_1);
	                    $cust_nm = (!empty($row17['name'])?$row17['name']:'NA');

	                   $rating[] = $row16['rating'];
	                   $resArr1['reviews'][$inc]['cust_review'] = $row16['review_c'];
	                   $resArr1['reviews'][$inc]['cust_name'] = $cust_nm;
	                   $resArr1['reviews'][$inc]['image'] = CUSTOMER_IMAGE_PATH . $row17['image_path_c'];
	                   $resArr1['reviews'][$inc]['rating'] = $row16['rating'];
	                   $inc++;
	                }

	                $rating_avg = array_sum($rating) / count(array_filter($rating));
	                if(is_nan($rating_avg))
	                {
	                	$rating_avg = 0;
	                }
	                $resArr1['number_of_review_count'] = (int)$inc;
	                $resArr1['rating'] = number_format($rating_avg, 1, '.', '');
	                //get kitchen packages code
	                $get_kitchen_packages = "SELECT `ply_kitchen_ply_package_1ply_package_idb` as package_id,`name`,`date_entered`,`description`,`package_type`,`currency_id`,`package_duration`,`package_time`,`package_status`,`best_value_c`,`package_min_price_c`,`package_max_price_c`,`package_sub_type_c`,`offer_value_c`,`offer_percentage_c`,`offer_duration_on_c`,`offer_upto_rupees_c`,`offer_status_c` FROM `ply_package` A join `ply_package_cstm` B on A.id=B.id_c join `ply_kitchen_ply_package_1_c` C on A.id = C.ply_kitchen_ply_package_1ply_package_idb WHERE A.deleted=0 and C.deleted=0 and `package_status`='active' and `ply_kitchen_ply_package_1ply_kitchen_ida`='$kitchen_id'";
	                $mysql_query_1_3 =  $this->mysqli->query($get_kitchen_packages);
	                $inc1 = 0;$days=30;
	                while($row17 = mysqli_fetch_assoc($mysql_query_1_3))
	                { 
	                    $package_meal_min_price = $row17['package_min_price_c'];
	                	$package_meal_max_price = $row17['package_max_price_c'];
	                	$package_sub_type = $row17['package_sub_type_c'];
	                	 
	                	$values_arr =  array($package_meal_min_price,$package_meal_max_price);
		    			$average = array_sum($values_arr)/count(array_filter($values_arr));
		    			if(is_nan($average) || is_infinite($average))
		    			{
		    				$average = 0.00;
		    			}
		    			
		    			if($row17['package_time']=='lunch')
				    		$no_of_tiffins_to_deliver = 1;
					    else if($row17['package_time']=='dinner')
					    	$no_of_tiffins_to_deliver = 1;
					    else if($row17['package_time']=='lunch_and_dinner')
					    	$no_of_tiffins_to_deliver = 2;

					    

		                if($row17['package_type']!='trial')
	        			{
		                	$resArr1['packages'][$inc1]['package_type'] = $row17['package_type'];
		                	$resArr1['packages'][$inc1]['package_id'] = $row17['package_id'];
		                	$resArr1['packages'][$inc1]['name'] = $row17['name'];
		                	$resArr1['packages'][$inc1]['date_entered'] = $row17['date_entered'];
		                	$resArr1['packages'][$inc1]['description'] = w1250_to_utf8($row17['description']);
		                	$resArr1['packages'][$inc1]['package_duration'] = str_replace('^','', $row17['package_duration']);
		                	$resArr1['packages'][$inc1]['package_time'] = ucfirst(str_replace('_',' ',$row17['package_time']));
		                	$resArr1['packages'][$inc1]['package_status'] = $row17['package_status'];
		                	$resArr1['packages'][$inc1]['best_value_c'] = (($row17['best_value_c']==0)?false:true);
		                	$resArr1['packages'][$inc1]['package_sub_type'] = $package_sub_type;

		                	
		                	$total_cost_incurred = $this->get_monthly_cost_incurred($days,$days * $no_of_tiffins_to_deliver,$row17['package_time'],$package_sub_type)['total_cost_incurred'];
							$profit_margin_per_tiffin = $this->get_monthly_cost_incurred($days,$days * $no_of_tiffins_to_deliver,$row17['package_time'],$package_sub_type)['profit_margin_per_tiffin'];
							
		                	$one_tiffin_cost = ceil($average+$total_cost_incurred+$profit_margin_per_tiffin);
                			$month_tiffin_cost = ($one_tiffin_cost * ($days * $no_of_tiffins_to_deliver));
							$resArr1['packages'][$inc1][$row17['package_time'].'_price'] = (int) $month_tiffin_cost;

		                }else{
		                	$resArr1['packages'][$inc1]['package_type'] = $row17['package_type'];
		                	$resArr1['packages'][$inc1]['offer_value'] = $row17['offer_value_c'];
		                	$resArr1['packages'][$inc1]['package_id'] = $row17['package_id'];
		                	$resArr1['packages'][$inc1]['name'] = $row17['name'];
		                	$resArr1['packages'][$inc1]['date_entered'] = $row17['date_entered'];
		                	$resArr1['packages'][$inc1]['description'] = w1250_to_utf8($row17['description']);
		                	$resArr1['packages'][$inc1]['package_status'] = $row17['package_status'];
		                	$resArr1['packages'][$inc1]['best_value_c'] = (($row17['best_value_c']==0)?false:true);
		                	$resArr1['packages'][$inc1]['package_time'] = ucfirst(str_replace('_',' ',$row17['package_time']));


		                }
		                $get_trial_lunch_cost = "SELECT `total_cost_incurred_c`,`profit_margin_per_tiffin` FROM `ply_package_rate_finder` A join `ply_package_rate_finder_cstm` B on A.id=B.id_c WHERE `name`='Trial Meal Lunch' and deleted=0";
		                	$mysql_query_2_2 =  $this->mysqli->query($get_trial_lunch_cost);
		                	$trial_lunch_row = mysqli_fetch_assoc($mysql_query_2_2);
	               		 	$resArr1['packages'][$inc1]['trial_meal_lunch_cost'] = (int)number_format((float)$trial_lunch_row['total_cost_incurred_c'] + $trial_lunch_row['profit_margin_per_tiffin'] + $average, 2, '.', '');

	               		 	$get_trial_dinner_cost = "SELECT `total_cost_incurred_c`,`profit_margin_per_tiffin` FROM `ply_package_rate_finder` A join `ply_package_rate_finder_cstm` B on A.id=B.id_c WHERE `name`='Trial Meal Dinner' and deleted=0";
		                	$mysql_query_2_3 =  $this->mysqli->query($get_trial_dinner_cost);
		                	$trial_dinner_row = mysqli_fetch_assoc($mysql_query_2_3);
	               		 	$resArr1['packages'][$inc1]['trial_meal_dinner_cost'] = (int)number_format((float)$trial_dinner_row['total_cost_incurred_c'] + $trial_dinner_row['profit_margin_per_tiffin'] + $average, 2, '.', '');

	               		 //send package offer details start
	               		 	if( $row17['offer_status_c']=='active')
	               		 	{
	               		 		$resArr1['packages'][$inc1]['offer_value'] = (int) ceil($row17['offer_value_c']);
			                	$resArr1['packages'][$inc1]['offer_percentage'] = (int) $row17['offer_percentage_c'];
			                	$resArr1['packages'][$inc1]['offer_duration_on'] =  str_replace('^','', $row17['offer_duration_on_c']);
			                	$resArr1['packages'][$inc1]['offer_upto_rupees'] = (int) ceil($row17['offer_upto_rupees_c']);
	               		 	}

	               		 //end

	                	$inc1++;

	                } //end of while

	                //get kitchen weekly menus code start here
				        $get_kitchen_weekly_menus = "SELECT `ply_menu_master_id_c`,`serving_time`,`approval_status_c`,`meal_serving_date_c` FROM `ply_weekly_menu` A join `ply_weekly_menu_cstm` B on A.id = B.id_c join `ply_kitchen_ply_weekly_menu_1_c` C on A.id = C.`ply_kitchen_ply_weekly_menu_1ply_weekly_menu_idb` WHERE `ply_kitchen_ply_weekly_menu_1ply_kitchen_ida` = '$kitchen_id' and A.deleted=0 and C.deleted=0 and `meal_serving_date_c` >= date_sub(curdate(), interval weekday(curdate()) day) and `meal_serving_date_c` <= date_sub(curdate(), interval weekday(curdate()) - 6 day) order by `meal_serving_date_c`";
				        $mysql_query_7 =  $this->mysqli->query($get_kitchen_weekly_menus);
		                $inc6=0;$inc7=0;
					    while($row24 = mysqli_fetch_assoc($mysql_query_7))
					    {
					    	$menu_id = trim($row24['ply_menu_master_id_c']);
					    	$meal_serving_date = trim($row24['meal_serving_date_c']);
					    	$serving_time = trim($row24['serving_time']);
					    	$nameOfDay = date('l', strtotime($meal_serving_date));
					    	$get_menu_details = "SELECT `name`,`menu_category`,`menu_type` FROM `ply_menu_master` A join `ply_menu_master_cstm` B on A.id=B.id_c WHERE `id`='$menu_id' and A.deleted=0 and `status`='active'";
					    	$mysql_query_8 =  $this->mysqli->query($get_menu_details);
				        	$row25 = mysqli_fetch_assoc($mysql_query_8);
				        	$menu_name = $row25['name'];

				        	//get menu image
							$get_menu_master_imgs = "SELECT B.ply_menu_master_notes_1notes_idb FROM `ply_menu_master` A join `ply_menu_master_notes_1_c` B on A.id = B.`ply_menu_master_notes_1ply_menu_master_ida` where A.deleted=0 and B.deleted=0 and A.`id`='$menu_id'";
		                    $mysql_query_menu_master =  $this->mysqli->query($get_menu_master_imgs); 
		                    $row_menu_master = mysqli_fetch_assoc($mysql_query_menu_master);
		                    

				        	if($row25['menu_type'] == 'veg')
				        	{
				        		$resArr1['veg_weekly_menus'][$inc6]['menu_id'] = $menu_id;
				        		$resArr1['veg_weekly_menus'][$inc6]['menu_name'] = $menu_name;
				        		$resArr1['veg_weekly_menus'][$inc6]['serving_time'] = ucfirst(str_replace("-"," ", $serving_time));
				        		$resArr1['veg_weekly_menus'][$inc6]['day_name'] = $nameOfDay;
				        		if($row_menu_master['ply_menu_master_notes_1notes_idb']!='')
		                         	$resArr1['veg_weekly_menus'][$inc6]['image'] = UPLOAD_URL .$row_menu_master['ply_menu_master_notes_1notes_idb'];
		                   		 else
		                       		$resArr1['veg_weekly_menus'][$inc6]['image'] = 'image not available'; 

		                       	$resArr1['veg_weekly_menus'][$inc6]['veg'] = true;
				                $resArr1['veg_weekly_menus'][$inc6]['nonveg'] = false;
				                $resArr1['veg_weekly_menus'][$inc6]['egg'] = false;
		                       
				        		$inc6++;
				        	}else if($row25['menu_type'] == 'nonveg')
				        	{
				        		$resArr1['nonveg_weekly_menus'][$inc7]['menu_id'] = $menu_id;
				        		$resArr1['nonveg_weekly_menus'][$inc7]['menu_name'] = $menu_name;
				        		$resArr1['nonveg_weekly_menus'][$inc7]['serving_time'] = ucfirst(str_replace("-"," ", $serving_time));
				        		$resArr1['nonveg_weekly_menus'][$inc7]['day_name'] = $nameOfDay;
				        		if($row_menu_master['ply_menu_master_notes_1notes_idb']!='')
		                         	$resArr1['nonveg_weekly_menus'][$inc7]['image'] = UPLOAD_URL .$row_menu_master['ply_menu_master_notes_1notes_idb'];
		                   		 else
		                       		$resArr1['nonveg_weekly_menus'][$inc7]['image'] = 'image not available'; 

		                       	$resArr1['nonveg_weekly_menus'][$inc7]['veg'] = false;
				                $resArr1['nonveg_weekly_menus'][$inc7]['nonveg'] = true;
				                $resArr1['nonveg_weekly_menus'][$inc7]['egg'] = true;
				        		$inc7++;
				        	}

					    } //while loop closing


	                	//end of code here

	                //end of code here
   					
	         } //while loop closing
	           
	           return $resArr1;    
	    }
		public function fetch_alls($set_table_name)
		{
		    $query=$this->mysqli->query("SELECT * FROM ".$set_table_name); 
		    $result = array();
		    while ($record = mysqli_fetch_assoc($query)) {
		         $result[] = $record;
		    }
		    return $result;
		}
	
	  public function insert_merchant_meta($mecrhant_id,$meta_key,$meta_value)
	 {
		  if(isset($meta_key) && isset($meta_value) && isset($mecrhant_id))
		  {

		    $query = "INSERT INTO `mt_merchant_meta`( `merchant_id`, `merchant_key`, `merchant_value`) VALUES ('$mecrhant_id','$meta_key','$meta_value')";
		    $mysql_query =  $this->mysqli->query($query);
		        if($mysql_query) {
		          return true;
		        }else{
		          return false;
		        }
		  }

	}
	public function saveRecords($query,$table_name,$feild_name,$feild_val)
	{
	   
	    
	      $count = $this->db_num("SELECT * FROM $table_name where $feild_name='$feild_val'");
	      
	        if($count == 0){
	        
	                          $success =  $this->mysqli->query($query);
	                          if($success) {
	                            
	                                return $this->mysqli->insert_id;
	                              }
	                        }else{
	                        
	                          return 'exist';
	                        }
	 }
	 public function insert_multiple_records($query, $table_name,$feild_attributes=array())
	{
		    $keys = array_keys($feild_attributes);
		    $condition ='';
		    for($i=0;$i<count($keys);$i++)
		    {
		      if(isset($keys[$i]))
		      {
		        $condition .= $keys[$i].'='.$feild_attributes[$keys[$i]];
		        if($i<count($keys)-1)
		        {
		          $condition .=' and ';
		        }
		      }
		    } 
		    
		    $dynamicquery = 'SELECT * FROM '.$table_name.' WHERE '.$condition.'';
		   
		     $count = $this->db_num($dynamicquery);
		     if($count==0){
		      
		                      $mysql_query =  $this->mysqli->query($query);
		                      if($mysql_query) {
		                        return true;
		                      }else{
		                        return false;
		                      }
		                    }else{
		                      return false;
		                    }
  }
   public function get_data($query)
    {
       
        $mysql_query =  $this->mysqli->query($query);//mysqli_query($this->mysqli,$query); //query the db
        $resArr = array(); //create the result array
        while($row = $mysql_query->fetch_assoc()) 
        { 
            $resArr['item_name'] = $row['item_name']; 
            $resArr['item_description'] = w1250_to_utf8($row['item_description']); 
            $resArr['status'] = $row['status']; 
           // $resArr['price'] = $row['price']; 
            $resArr['addon_item'] = $row['addon_item']; 
            $resArr['cooking_ref'] = $row['cooking_ref']; 
            $resArr['discount'] = $row['discount']; 
            $resArr['is_featured'] = $row['is_featured']; 
            $resArr['date_created'] = $row['date_created']; 
            $resArr['date_modified'] = $row['date_modified']; 
            $resArr['ingredients'] = w1250_to_utf8($row['ingredients']); 
            $resArr['spicydish'] = $row['spicydish'];
            $resArr['two_flavors'] = $row['two_flavors']; 
            $resArr['two_flavors_position'] = $row['two_flavors_position'];
            $resArr['require_addon'] = $row['require_addon'];
            $resArr['dish'] = $row['dish'];
            $resArr['item_name_trans'] = $row['item_name_trans'];
            $resArr['not_available'] = $row['not_available'];
            $resArr['points_earned'] = $row['points_earned'];
            $resArr['points_disabled'] = $row['points_disabled'];
            $resArr['images'] = $row['images'];
            $arr = unserialize($row['price']);

            foreach($arr as $key=>$value)
            {
              $size_id = $key;
              $query = "SELECT `size_name` FROM `mt_size` WHERE `id`='$size_id'";
              $result = $this->mysqli->query($query); 
              $record = mysqli_fetch_assoc($result);
              $size_name = $record['size_name'];
              //$price_arr = array($size_name=>$value);
              $price_arr[$size_name] = $value;
                  
            }
            $resArr['price'] =  $price_arr;
            //exit;
            

        }
        
        //print_r($resArr);exit;
        return $resArr;     
    }
    
	
    public function apply_voucher($voucher_code,$client_id,$query) 
    {
        $get_voucher_count = "select `number_of_voucher` from `mt_voucher_new` where voucher_name='$voucher_code' and LOWER(status)=LOWER('Active')";
        $result = $this->mysqli->query($get_voucher_count);
        $row = $result->fetch_assoc();
        $no_of_voucher = (int)$row['number_of_voucher'];
        if($no_of_voucher>0)
        {
          $client_voucher_used_count = "select count(*) as used_count from `mt_voucher_list` where `client_id`='$client_id' and `voucher_code`='$voucher_code'";
          $result1 = $this->mysqli->query($client_voucher_used_count);
          $row1 = $result1->fetch_assoc();
          $used_count = $row1['used_count'];
          if($used_count < $no_of_voucher)
          {
            $result = $this->mysqli->query($query);
        
            if ($result == false) {
                
                return 'voucher_not_applied';
            } else {
              return 'voucher_applied';
            }        
          }else{
                  return 'already_used';     
          }
        }else{
               return 'invalid_vouchercode';
        }


    }
     public function get_meta_value_from_metakey($merchant_id,$meta_key) 
    { 
      $query = "SELECT * FROM `mt_merchant_meta` WHERE `merchant_key`='$meta_key' and `merchant_id`='$merchant_id'";
      $result = $this->mysqli->query($query);
      $row = $result->fetch_assoc();
       if(isset($row['merchant_value']))
        return $row['merchant_value'];
        else
        return '';
    }

      public function save($query,$table_name,$where=array())
	{
      $condition='';$opr = 'and';
      foreach($where as $key=>$value)
      {
        //$galleries[$key] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        $condition .= $key.'='.$value;
        $condition .= ' '.$opr.' ';
      }
      $str= preg_replace('/\W\w+\s*(\W*)$/', '$1', $condition); // remove last word from string
    
        $count = $this->db_num("SELECT * FROM $table_name where $str");
    
          if($count == 0){
          
                            $success =  $this->mysqli->query($query);
                            if($success) {
                              
                                  return $this->mysqli->insert_id;
                                }
                          }else{
                          
                            return 'exist';
                          }
  }
  
    public function get_merchant_meta_keyvalue($merchant_id,$meta_key) 
    { 
      $query = "SELECT `merchant_value` FROM `mt_merchant_meta` WHERE `merchant_key`='$meta_key' and `merchant_id`='$merchant_id'";
      
      $result = $this->mysqli->query($query);
      $row = $result->fetch_assoc();
       if(isset($row['merchant_value']))
        return $row['merchant_value'];
        else
        return '';
    }
    
      public function update_or_insert_loginStatus($query,$flag,$online_offline_flag,$row_id)
	    {
        if($flag=='DeliveryBoy')
        {
              if($this->db_num($query))
              {
                
                  $query ="UPDATE `mt_delivery_boy_meta` SET `meta_value`='$online_offline_flag' WHERE `meta_key`='boy_login_status' and delivery_boy_id='$row_id'";
                  $success =  $this->mysqli->query($query);
                  if($success) {
                    
                        return true;
                      }else{
                        return false;
                      }
              }else{
                      $query ="INSERT INTO `mt_delivery_boy_meta`( `delivery_boy_id`, `meta_key`, `meta_value`) VALUES ('$row_id','boy_login_status','$online_offline_flag')";
                      $success =  $this->mysqli->query($query);
                      if($success) {
                        
                            return true;
                          }else{
                            return false;
                          }
                  }

      }
      if($flag=='Merchant')
      {
        if($this->db_num($query))
        {
          
            $query ="UPDATE `mt_merchant_meta` SET `merchant_value`='$online_offline_flag' WHERE `merchant_key`='merchant_login_status' and merchant_id='$row_id'";
            $success =  $this->mysqli->query($query);
            if($success) {
              
                  return true;
                }else{
                  return false;
                }
        }else{
                $query ="INSERT INTO `mt_merchant_meta`( `merchant_id`, `merchant_key`, `merchant_value`) VALUES ('$row_id','merchant_login_status','$online_offline_flag')";
                $success =  $this->mysqli->query($query);
                if($success) {
                  
                      return true;
                    }else{
                      return false;
                    }
            }
      }
    }
    public function insert_FCM_token($query,$flag,$fcm_token,$insert_id=null)
    {
      $datetime = date("Y-m-d H:i:s");
      if($flag=='Merchant')
      {
        $count = $this->db_num("SELECT `id` FROM `mt_merchant_fcm_token` WHERE `fcm_token`='$fcm_token'");
        if($count == 0 && $insert_id=='')
        {
           $success =  $this->mysqli->query($query);
           if($success) {
           
             return $this->mysqli->insert_id;
               }else{
                 return false;
               }
        }else{
               
                   $update = "UPDATE `mt_merchant_fcm_token` SET `fcm_token`='$fcm_token',`modified_datetime`='$datetime' where `id`='$insert_id'";
                   $success =  $this->mysqli->query($update);
                   return 'updated';
               
         }
         
       }
      if($flag=='DeliveryBoy')
      {
         $count = $this->db_num("SELECT `id` FROM `mt_delivery_boy_fcm_token` WHERE `fcm_token`='$fcm_token'");
         if($count == 0 && $insert_id=='')
         {
             $success =  $this->mysqli->query($query);
             if($success) {
               
               return $this->mysqli->insert_id;
                 }else{
                   return false;
                 }
         }else{
              
                   $update = " UPDATE `mt_delivery_boy_fcm_token` SET `fcm_token`='$fcm_token',`modified_datetime`='$datetime' WHERE `id`='$insert_id'";
                   $success =  $this->mysqli->query($update);
                   return 'updated';
               }
          
       }
      if($flag=='Client')
      {
         $count = $this->db_num("SELECT `id` FROM `mt_client_fcm_token` WHERE `fcm_token`='$fcm_token'");
         if($count == 0 && $insert_id=='')
         {
             $success =  $this->mysqli->query($query);
             if($success) {
               
               return $this->mysqli->insert_id;
                 }else{
                   return false;
                 }
         }else{
                 
                     $update = " UPDATE `mt_client_fcm_token` SET `fcm_token`='$fcm_token',`modified_datetime`='$datetime' WHERE `id`='$insert_id'";
                     $success =  $this->mysqli->query($update);
                     return 'updated';
                 
               }

      }

    }
    public function placed_order($order_info=array())
    {
    		
            $order_id = getGuid();
            $datetime = date("Y-m-d H:i:s");
            $dinnerDeliveryTime = $order_info['dinnerDeliveryTime']['time'];
            $lunchDeliveryTime = $order_info['lunchDeliveryTime']['time'];
            $start_Package_Time = $order_info['mealTime']; //lunch,dinner
            $delivery_time = strtolower($order_info['delivery_time']); //lunch,dinner,lunch_and_Dinner
            if(strtolower($delivery_time)==strtolower('Lunch And Dinner'))
            {
            	$delivery_time = 'lunch_and_Dinner';
            }
            $mealType = strtolower($order_info['mealType']); //nonveg ,veg,egg
            $packageDurationNumber = $order_info['packageDurationNumber'];
            $packaging_id = $order_info['packagingType']['id'];
            $Delivery_on_saturday = $order_info['sendOnSaturday'];
            if($Delivery_on_saturday==1){$Delivery_on_saturday='yes';}else{
            	$Delivery_on_saturday='no';
            }
            $Delivery_on_sunday = $order_info['sendOnSunday'];
            if($Delivery_on_sunday==1){$Delivery_on_sunday='yes';}else{
            	$Delivery_on_sunday='no';
            }
            $order_category = $order_info['order_category']; //subscription,addon,cancel,renew,swap,purchase
            if(empty($order_category)){$order_category='subscription';}
            $offer_id = $order_info['offer_id'];
            $customer_id = $order_info['customer_id'];
            $kitchen_id = $order_info['kitchen_id'];
            $cust_address_id = $order_info['cust_address_id'];
            $package_id = $order_info['package_id'];

            //start date
            $start_date = $order_info['start_date'];
			$startdate = str_replace('/', '-', $start_date);
			$start_date =  date('Y-m-d', strtotime($startdate));
			//end date
            $end_date = $order_info['end_date'];
            $enddate = str_replace('/', '-', $end_date);
			$end_date = date('Y-m-d', strtotime($enddate));

            $city = strtolower($order_info['city']);
            $final_price = $order_info['final_price'];
            $discounted_price = $order_info['discounted_price'];
            $delivery_price = $order_info['delivery_price'];
            $deposition_amount = $order_info['deposition_amount'];
            $packaging_charges = $order_info['packaging_charges'];
            $service_charges = $order_info['service_charges'];
            $online_pay_received_amt = $order_info['online_pay_received_amt'];
            $online_pay_received_status = $order_info['online_pay_received_status'];
            $placeefy_instant_check = $order_info['placeefy_instant_check'];
            $payment_mode = $order_info['payment_mode']; //UPI,net_banking,cash,wallet,RTGS,NEFT
            if($placeefy_instant_check){$placeefy_instant_check=1;}else{$placeefy_instant_check=0;}


            if($kitchen_id!='' and isset($kitchen_id))
            {
            		$delivery_boy_id = $this->get_delivery_boy($kitchen_id);
            }

            //create order name
            $order_name = $this->get_name($customer_id,'accounts').' Booked '.$this->get_name($package_id,'ply_package').' Package from '.$this->get_name($kitchen_id,'ply_kitchen');

            $get_package_rate_finder_id = $this->get_package_rate_finder_id($delivery_time,$packageDurationNumber,$mealType);
            // insert order into orders placed table
            $insert_into_main_table ="INSERT INTO `ply_orders`(`id`, `name`, `date_entered`, `date_modified`,`created_by`, `assigned_user_id`, `account_id_c`, `ply_kitchen_id_c`, `ply_package_id_c`, `ply_offer_id_c`, `order_category`, `start_date`, `expected_end_date`) VALUES ('$order_id','$order_name','$datetime','$datetime','1','1','$customer_id','$kitchen_id','$package_id','$offer_id','$order_category','$start_date','$end_date')";

            $insert_into_custom_table = "INSERT INTO `ply_orders_cstm`(`id_c`, `ply_packaging_id_c`, `delivery_time_c`, `delivery_time_slot_for_lunch_c`, `delivery_timeslot_for_dinner_c`, `payment_mode_c`,`delivery_charge_c`, `order_amout_c`, `deposition_amount_c`, `discounted_price_c`, `service_charge_c`, `packaging_charges_c`, `delivery_on_sunday_c`, `delivery_on_saturday_c`, `ply_package_rate_finder_id_c`, `ply_package_rate_finder_id1_c`, `package_duration_in_days_c`, `order_meal_type_c`, `start_package_time_c`, `ply_delivery_boy_id_c`, `placeefy_instant_delivery_c`, `city_c`, `online_payment_received_amt_c`, `online_pay_received_status_c`, `ply_customer_addresses_id_c`) VALUES ('$order_id','$packaging_id','$delivery_time','$dinnerDeliveryTime','$lunchDeliveryTime','$payment_mode','$delivery_price','$final_price','$deposition_amount','$discounted_price','$service_charges','$packaging_charges','$Delivery_on_sunday','$Delivery_on_saturday','$get_package_rate_finder_id','$get_package_rate_finder_id','$packageDurationNumber','$mealType','$start_Package_Time','$delivery_boy_id','$placeefy_instant_check','$city','$online_pay_received_amt','$online_pay_received_status','$cust_address_id')";

            //update addons in order
            if($this->execute($insert_into_main_table) && $this->execute($insert_into_custom_table))
            {
	            $addons = $order_info['addon'];
	            $add_count=0;$add_on_c=0;
	            if(is_array($addons) && !empty($addons))
	            {
					foreach($addons as $addon)
					{
						$add_count++;
					    $addon_id = $addon['id'];
					    $addon_quantity = $addon['quantity'];
					    if($add_on_c==0)
					   	{
					   		$column_name = 'ply_add_on_id_c';
					   		$column_name_1 = 'addon_'.$add_count.'_qty_c';
					        
					   	}else{
					   		   $column_name = 'ply_add_on_id'.$add_on_c.'_c';
					   		   $column_name_1 = 'addon_'.$add_count.'_qty_c';
					          
					   	}
					    
					    $update_addons = "UPDATE `ply_orders_cstm` SET $column_name='$addon_id',$column_name_1='$addon_quantity' where `id_c`='$order_id'";
					    $this->execute($update_addons);
					   $add_on_c++; 
					}
				}
				$update_addons_count = "UPDATE `ply_orders` SET `no_of_addons`='$add_count' WHERE `id`='$order_id'";
				$this->execute($update_addons_count);
				return trim($order_id);
			}


                  
    }
    public function get_delivery_boy($kitchen_id)
    {
       		$assigned_arr = array(); $assigned_values=array();
 			$get_delivery_boy_from_kith = "SELECT `ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb` as `delivery_boy_id`,`order_delivery_capacity_c`,`type_of_delivery_boy_c`	 FROM `ply_kitchen_ply_delivery_boy_1_c` A join `ply_delivery_boy` B on A.`ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb`= B.`id` join `ply_delivery_boy_cstm` C on B.`id`=C.`id_c` WHERE `ply_kitchen_ply_delivery_boy_1ply_kitchen_ida`=TRIM('$kitchen_id') and A.deleted=0 and B.deleted=0";
 			$mysql_query =  $this->mysqli->query($get_delivery_boy_from_kith);
			while($row = mysqli_fetch_assoc($mysql_query))
			{
				$delivery_boy_type = $row['type_of_delivery_boy_c'];
				if($delivery_boy_type=='main')
				{
					$capacity = $row['order_delivery_capacity_c'];
					$delivery_boy_id = $row['delivery_boy_id'];
					//total order assigned till date
					$get_assigned_count = "SELECT count(B.`ply_delivery_boy_id_c`) as total_assigned_count FROM `ply_orders` A join `ply_orders_cstm` B on A.id=B.id_c WHERE A.ply_kitchen_id_c='$kitchen_id'and `ply_delivery_boy_id_c`='$delivery_boy_id' and A.deleted=0";
					$mysql_query_1 =  $this->mysqli->query($get_assigned_count);
					$row1 = mysqli_fetch_assoc($mysql_query_1);
					$assigned_count = $row1['total_assigned_count'];
					if($assigned_count<=Delivery_Boy_Check_Count && $assigned_count<=$capacity)
					{
						$assigned_keys[] = $row['delivery_boy_id'];
						$assigned_values[] =  $assigned_count;
					}



				}

			} // end of while
			if(!empty($assigned_keys))
			{
				if(sizeof($assigned_keys)>=2)
				{
					if(!empty($assigned_keys) && !empty($assigned_values))
					{
						// combine key and value array
						$final_arr = array_combine($assigned_keys, $assigned_values);
						//****************LOG Creation*********************
        					$APILogFile = LOG_PATH.'placedOrder.txt';
        					$handle = fopen($APILogFile, 'a');
        					$timestamp = date('Y-m-d H:i:s');
        					$logArray1 = print_r($final_arr, true);
        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
        					fwrite($handle, $logMessage);				
        					fclose($handle);
        				   //****************ENd OF Code*****************
													
						//get all values of array
						$arrval = array_values($final_arr);
													
						// check all values of array are same or not (if all are same then return 1 else blank
						$allValuesAreTheSame = (count(array_unique($arrval)) === 1);
						if($allValuesAreTheSame==1)
						{
							//if count of all delivery boy are same then take any one randomly
							$final_assigned_deliveryboy_id = array_rand($final_arr);
							//****************LOG Creation*********************
	        					$APILogFile = LOG_PATH.'placedOrder.txt';
	        					$handle = fopen($APILogFile, 'a');
	        					$timestamp = date('Y-m-d H:i:s');
	        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id);
	        					$logArray1 = print_r($final_arr, true);
	        					$logMessage1 = "\nif count of all delivery boy are same then take any one randomly(load <=15)";
	        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
	        					fwrite($handle, $logMessage1);	
	        					fwrite($handle, $logMessage);				
	        					fclose($handle);
	        		  		 //****************ENd OF Code*****************
						}else{
													
								//find minimum assigned delivery boy count
								$final_assigned_deliveryboy_id = array_keys($final_arr, min($final_arr)); 
								//****************LOG Creation*********************
	        					$APILogFile = LOG_PATH.'placedOrder.txt';
	        					$handle = fopen($APILogFile, 'a');
	        					$timestamp = date('Y-m-d H:i:s');
	        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id);
	        					$logArray1 = print_r($final_arr, true);
	        					$logMessage1 = "\nfind minimum assigned delivery boy count.(load <=15)";
	        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
	        					fwrite($handle, $logMessage1);	
	        					fwrite($handle, $logMessage);				
	        					fclose($handle);
	        		  		 //****************ENd OF Code*****************
															
							}
					}
				}else{

					$final_assigned_deliveryboy_id = $assigned_keys[0];
					//****************LOG Creation*********************
        					$APILogFile = LOG_PATH.'placedOrder.txt';
        					$handle = fopen($APILogFile, 'a');
        					$timestamp = date('Y-m-d H:i:s');
        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id);
        					$logArray1 = print_r($final_arr, true);
        					$logMessage1 = "\nThis kitchen have only one Main delivery boy.(load<=15)";
        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
        					fwrite($handle, $logMessage1);	
        					fwrite($handle, $logMessage);				
        					fclose($handle);
        		   //****************ENd OF Code*****************
				}
			}else{

					//****************LOG Creation*********************
				       $APILogFile = LOG_PATH.'placedOrder.txt';
				        $handle = fopen($APILogFile, 'a');
				        $timestamp = date('Y-m-d H:i:s');
				        $logMessage1 = "\nNo Main delivery boys avaialble in DB";
				        fwrite($handle, $logMessage1);
				        fclose($handle);
			     //****************ENd OF Code*****************
				//if array is empty means condition fails and now order need to assign to alternate delivery boy
				$get_alternate_delivery_boy_id = "SELECT `ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb` as `delivery_boy_id`,`order_delivery_capacity_c` FROM `ply_kitchen_ply_delivery_boy_1_c` A join `ply_delivery_boy` B on A.`ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb`= B.`id` join `ply_delivery_boy_cstm` C on B.`id`=C.`id_c` WHERE `ply_kitchen_ply_delivery_boy_1ply_kitchen_ida`=TRIM('$kitchen_id') and A.deleted=0 and B.deleted=0 and `type_of_delivery_boy_c`='alternate' limit 1";
 				$mysql_query_2 =  $this->mysqli->query($get_alternate_delivery_boy_id);
 				$row3 = mysqli_fetch_assoc($mysql_query_2);
				$capacity_alternate_boy = $row3['order_delivery_capacity_c'];
				$alternate_delivery_boy_id = $row3['delivery_boy_id'];
				$alt_get_assigned_count = "SELECT count(B.`ply_delivery_boy_id_c`) as total_assigned_count FROM `ply_orders` A join `ply_orders_cstm` B on A.id=B.id_c WHERE A.ply_kitchen_id_c='$kitchen_id'and `ply_delivery_boy_id_c`='$alternate_delivery_boy_id' and A.deleted=0";
					$mysql_query_4 =  $this->mysqli->query($alt_get_assigned_count);
					$row4 = mysqli_fetch_assoc($mysql_query_4);
					$alt_DB_assigned_count = $row4['total_assigned_count'];
					if($alt_DB_assigned_count<=Delivery_Boy_Check_Count && $alt_DB_assigned_count<=$capacity_alternate_boy)
					{
						$final_assigned_deliveryboy_id = $row3['delivery_boy_id'];
						//****************LOG Creation*********************
        					$APILogFile = LOG_PATH.'placedOrder.txt';
        					$handle = fopen($APILogFile, 'a');
        					$timestamp = date('Y-m-d H:i:s');
        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id,"alt_DB_assigned_count"=>$alt_DB_assigned_count,"capacity_alternate_boy"=>$capacity_alternate_boy);
        					$logArray1 = print_r($final_arr, true);
        					$logMessage1 = "\nThis kitchen don't have Main delivery boy.(load<=15)";
        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
        					fwrite($handle, $logMessage1);
        					fwrite($handle, $logMessage);				
        					fclose($handle);
        		   //****************ENd OF Code*****************
					}
					else{
							//****************LOG Creation*********************
					       $APILogFile = LOG_PATH.'placedOrder.txt';
					        $handle = fopen($APILogFile, 'a');
					        $timestamp = date('Y-m-d H:i:s');
					        $logMessage1 = "\nNo Alternate delivery boys avaialble in DB";
					        fwrite($handle, $logMessage1);
					        fwrite($handle, $logMessage);				
					        fclose($handle);
				     	//****************ENd OF Code*****************

							$assigned_arr = array(); $assigned_values=array();
				 			$get_delivery_boy_from_kith = "SELECT `ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb` as `delivery_boy_id`,`order_delivery_capacity_c`,`type_of_delivery_boy_c`	 FROM `ply_kitchen_ply_delivery_boy_1_c` A join `ply_delivery_boy` B on A.`ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb`= B.`id` join `ply_delivery_boy_cstm` C on B.`id`=C.`id_c` WHERE `ply_kitchen_ply_delivery_boy_1ply_kitchen_ida`=TRIM('$kitchen_id') and A.deleted=0 and B.deleted=0";
				 			$mysql_query =  $this->mysqli->query($get_delivery_boy_from_kith);
							while($row = mysqli_fetch_assoc($mysql_query))
							{
								$delivery_boy_type = $row['type_of_delivery_boy_c'];
								if($delivery_boy_type=='main')
								{
									$capacity = $row['order_delivery_capacity_c'];
									$delivery_boy_id = $row['delivery_boy_id'];
									//total order assigned till date
									$get_assigned_count = "SELECT count(B.`ply_delivery_boy_id_c`) as total_assigned_count FROM `ply_orders` A join `ply_orders_cstm` B on A.id=B.id_c WHERE A.ply_kitchen_id_c='$kitchen_id'and `ply_delivery_boy_id_c`='$delivery_boy_id' and A.deleted=0";
									$mysql_query_1 =  $this->mysqli->query($get_assigned_count);
									$row1 = mysqli_fetch_assoc($mysql_query_1);
									$assigned_count = $row1['total_assigned_count'];
									if($assigned_count>=Delivery_Boy_Check_Count && $assigned_count<=$capacity)
									{
										$assigned_keys[] = $row['delivery_boy_id'];
										$assigned_values[] =  $assigned_count;
									}



								}

							} // end of while
							if(!empty($assigned_keys))
							{
								if(sizeof($assigned_keys)>=2)
								{
									if(!empty($assigned_keys) && !empty($assigned_values))
									{
										// combine key and value array
										$final_arr = array_combine($assigned_keys, $assigned_values);
																	
										//get all values of array
										$arrval = array_values($final_arr);
																	
										// check all values of array are same or not (if all are same then return 1 else blank
										$allValuesAreTheSame = (count(array_unique($arrval)) === 1);
										if($allValuesAreTheSame==1)
										{
											//if count of all delivery boy are same then take any one randomly(load >=15)
											$final_assigned_deliveryboy_id = array_rand($final_arr);
											//****************LOG Creation*********************
				        					$APILogFile = LOG_PATH.'placedOrder.txt';
				        					$handle = fopen($APILogFile, 'a');
				        					$timestamp = date('Y-m-d H:i:s');
				        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id);
				        					$logArray1 = print_r($final_arr, true);
				        					$logMessage1 = "\nif count of all delivery boy are same then take any one randomly(load >=15)";
				        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
				        					fwrite($handle, $logMessage1);	
				        					fwrite($handle, $logMessage);				
				        					fclose($handle);
				        		  		 //****************ENd OF Code*****************
										}else{
																	
												//find minimum assigned Delivery boy(load>=15) count
												$final_assigned_deliveryboy_id = array_keys($final_arr, min($final_arr)); 
												//****************LOG Creation*********************
				        					$APILogFile = LOG_PATH.'placedOrder.txt';
				        					$handle = fopen($APILogFile, 'a');
				        					$timestamp = date('Y-m-d H:i:s');
				        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id);
				        					$logArray1 = print_r($final_arr, true);
				        					$logMessage1 = "\nfind minimum assigned Delivery boy(load>=15) count";
				        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
				        					fwrite($handle, $logMessage1);	
				        					fwrite($handle, $logMessage);				
				        					fclose($handle);
				        		  		 //****************ENd OF Code*****************
																			
											}
									}
								}else{

									$final_assigned_deliveryboy_id = $assigned_keys[0];
									$final_assigned_deliveryboy_id = $assigned_keys[0];
									//****************LOG Creation*********************
				        					$APILogFile = LOG_PATH.'placedOrder.txt';
				        					$handle = fopen($APILogFile, 'a');
				        					$timestamp = date('Y-m-d H:i:s');
				        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id);
				        					$logArray1 = print_r($final_arr, true);
				        					$logMessage1 = "\nThis kitchen have only one Main delivery boy.(load>=15)";
				        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
				        					fwrite($handle, $logMessage1);	
				        					fwrite($handle, $logMessage);				
				        					fclose($handle);
				        		   //****************ENd OF Code*****************
								}
							}else{
									//if array is empty means condition fails and now order need to assign to alternate delivery boy
									$get_alternate_delivery_boy_id = "SELECT `ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb` as `delivery_boy_id`,`order_delivery_capacity_c` FROM `ply_kitchen_ply_delivery_boy_1_c` A join `ply_delivery_boy` B on A.`ply_kitchen_ply_delivery_boy_1ply_delivery_boy_idb`= B.`id` join `ply_delivery_boy_cstm` C on B.`id`=C.`id_c` WHERE `ply_kitchen_ply_delivery_boy_1ply_kitchen_ida`=TRIM('$kitchen_id') and A.deleted=0 and B.deleted=0 and `type_of_delivery_boy_c`='alternate' limit 1";
					 				$mysql_query_2 =  $this->mysqli->query($get_alternate_delivery_boy_id);
					 				$row3 = mysqli_fetch_assoc($mysql_query_2);
									$capacity_alternate_boy = $row3['order_delivery_capacity_c'];
									$alternate_delivery_boy_id = $row3['delivery_boy_id'];
									$alt_get_assigned_count = "SELECT count(B.`ply_delivery_boy_id_c`) as total_assigned_count FROM `ply_orders` A join `ply_orders_cstm` B on A.id=B.id_c WHERE A.ply_kitchen_id_c='$kitchen_id'and `ply_delivery_boy_id_c`='$alternate_delivery_boy_id' and A.deleted=0";
										$mysql_query_4 =  $this->mysqli->query($alt_get_assigned_count);
										$row4 = mysqli_fetch_assoc($mysql_query_4);
										$alt_DB_assigned_count = $row4['total_assigned_count'];
										if($alt_DB_assigned_count>=Delivery_Boy_Check_Count && $alt_DB_assigned_count<=$capacity_alternate_boy)
										{
											$final_assigned_deliveryboy_id = $row3['delivery_boy_id'];
											//****************LOG Creation*********************
				        					$APILogFile = LOG_PATH.'placedOrder.txt';
				        					$handle = fopen($APILogFile, 'a');
				        					$timestamp = date('Y-m-d H:i:s');
				        					$final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id,"alt_DB_assigned_count"=>$alt_DB_assigned_count,"capacity_alternate_boy"=>$capacity_alternate_boy);
				        					$logArray1 = print_r($final_arr, true);
				        					$logMessage1 = "\nThis kitchen don't have Main delivery boy.(load>=15)";
				        					$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
				        					fwrite($handle, $logMessage1);
				        					fwrite($handle, $logMessage);				
				        					fclose($handle);
				        		  			 //****************ENd OF Code*****************
										}
								}

					}
				}
			if($final_assigned_deliveryboy_id!='' and !empty($final_assigned_deliveryboy_id))
			{
				//****************LOG Creation*********************
				       $APILogFile = LOG_PATH.'placedOrder.txt';
				        $handle = fopen($APILogFile, 'a');
				        $timestamp = date('Y-m-d H:i:s');
				        $final_arr =  array("final_assigned_deliveryboy_id"=>$final_assigned_deliveryboy_id);
				        $logArray1 = print_r($final_arr, true);
				        $logMessage1 = "\nalgorithm executed successfully";
				        $logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
				        fwrite($handle, $logMessage1);
				        fwrite($handle, $logMessage);				
				        fclose($handle);
			 //****************ENd OF Code*****************
				return $final_assigned_deliveryboy_id;
			}else{

				//defined in constant.php
				//****************LOG Creation*********************
				       $APILogFile = LOG_PATH.'placedOrder.txt';
				        $handle = fopen($APILogFile, 'a');
				        $timestamp = date('Y-m-d H:i:s');
				        $final_arr =  array("default_delivery_boy_id"=>"cae497f8-6c52-f3df-85d8-5e788d272a47");
				        $logArray1 = print_r($final_arr, true);
				        $logMessage1 = "\nif all get blank it assigned default delivery boy";
				        $logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
				        fwrite($handle, $logMessage1);
				        fwrite($handle, $logMessage);				
				        fclose($handle);
			 //****************ENd OF Code*****************
				return default_delivery_boy_id;
			}

	}
	public function get_name($id,$table_name)
	{
		$get_name = "SELECT `name` FROM $table_name WHERE deleted=0 and `id`='$id'";
		$mysql_query_2 =  $this->mysqli->query($get_name);
		$row3 = mysqli_fetch_assoc($mysql_query_2);
		return ($row3['name']!='')?$row3['name']:'Name Not Available';
		
	}
	public function get_package_rate_finder_id($delivery_time,$packageDurationNumber,$pure_veg)
	{
			if($pure_veg_flag=='veg')
	    	{
	    		$pure_veg = ' and `pure_veg_c`=1';
	    	}else if($pure_veg_flag=='nonveg')
	    	{
	    		$pure_veg = ' and `pure_veg_c`=0';
	    	}
	    	else if($pure_veg_flag=='veg_nonveg')
	    	{
	    		$pure_veg = ' and `pure_veg_c`=1';
	    	}
	    	else if($pure_veg_flag=='egg')
	    	{
 				$pure_veg = " and `pure_veg_c`=0";
	    	}else{
	    		$pure_veg = " and `pure_veg_c`=''";
	    	}

		$get_package_finder = "SELECT `id` FROM `ply_package_rate_finder` A join `ply_package_rate_finder_cstm` B on A.id=B.id_c WHERE `no_of_days_deliveries`='' and `delivery_time`='' $pure_veg and deleted=0";
		$mysql_query_2 =  $this->mysqli->query($get_package_finder);
		$row3 = mysqli_fetch_assoc($mysql_query_2);
		return ($row3['id']!='')?$row3['id']:'';
	}
    public function get_cart_items_based_on_clientId($query,$clientid,$lat1,$long1)
   {
             $final_del_price =''; $a=array();
            $result = $this->sidebar_query($query);
            $merchant_id = $result[0]['merchant_id'];
			//print_r($result);exit;
			for($i=0;$i<count($result);$i++)
			{
			            $a[] = $result[$i]['total_price'];
                        $item_id = $result[$i]['item_id'];
						
						$getitemprice = "SELECT `price`,`price_type`,`is_veg_nonveg` FROM `mt_item` WHERE `id`='$item_id'";
					    $result1 = $this->mysqli->query($getitemprice);
						$row1 = $result1->fetch_assoc();
						$arr = unserialize($row1['price']);
						$price='';
					   if(is_array($arr)){
						 foreach($arr as $key=>$value)
						 {
							 if(!empty($row1['price_type']) && strtolower($row1['price_type'])== strtolower('Single'))
							{
								$result[$i]['item_price'] = $value;
								
							}
							else{
								   $size_id = $key;
								   $query2 = "SELECT `size_name` FROM `mt_size` WHERE `id`='$size_id'";
								   $result2 = $this->mysqli->query($query2); 
								   $record = mysqli_fetch_assoc($result2);
								   $size_name = $record['size_name'];
								  
								   if($result[$i]['quantity_flag']=='H' || $size_name=='Half')
										$result[$i]['item_price'] = $value;
								   if($result[$i]['quantity_flag']=='F' || $size_name=='Full')
										$result[$i]['item_price'] = $value;
									
							}
					      
						 }
						 
					   }
					   $result[$i]['is_veg_nonveg'] = trim($row1['is_veg_nonveg']);
						
                    
            }
			
						
			if(!empty($merchant_id))
            {
              $getmerchantdetails = "SELECT `latitude`,`lontitude` FROM `mt_merchant` WHERE `id`='$merchant_id'";
              $result1 = $this->mysqli->query($getmerchantdetails);
              $row1 = $result1->fetch_assoc();
              $delivery_distance = get_distance_between_points($row1['latitude'],$row1['lontitude'],$lat1,$long1);
			  //print_r($delivery_distance);exit;
                      // print_r($delivery_distance);exit;
                      if($delivery_distance['kilometers']!='')
                      {
                        $getshippingrate = "SELECT `distance_from`,`distance_to`,`shipping_units`,`distance_price` FROM `mt_shipping_rate`";
                        $result11 = $this->mysqli->query($getshippingrate);
                        while ($record = mysqli_fetch_assoc($result11))
                        {
                          
                              $dilevery_price = calculate_delivery_charger($delivery_distance['kilometers'],$record['shipping_units'],$record['distance_price'],$record['distance_from'],$record['distance_to']);
							  //exit;
                            
                              if($dilevery_price!='' && !empty($dilevery_price))
                              {
                                $final_del_price = $dilevery_price;
                    
                                break;
                              }
                        }
                      }
                      
                      $final_del_price = (isset($final_del_price)?number_format((float)$final_del_price, 2, '.', ''):number_format((float)30, 2, '.', ''));
                     
                       
                        $sub_total = number_format((float)array_sum($a), 2, '.', '');
                        $percentage = 18;
                        $tax = number_format((float)($percentage / 100) * $sub_total, 2, '.', '');
                        $final_total_amount = number_format((float) ($sub_total + $final_del_price), 2, '.', '');
                        $taxable_total = number_format((float) ($sub_total + $tax), 2, '.', '');
                        $total_w_tax = number_format((float) ($sub_total+$final_del_price), 2, '.', '');
                        if(!empty($result)){
							
							//****************Response LOG Creation*********************
								$APILogFile = 'getClientCartItems.txt';
								$handle = fopen($APILogFile, 'a');
								$timestamp = date('Y-m-d H:i:s');
								$logArray = array('statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result,'total_item_price'=>$sub_total,'grand_total'=>ceil($final_total_amount),'taxable_total'=>$taxable_total,'total_without_tax'=>$total_w_tax,'Delivery_charges'=>ceil($final_del_price),'Service_tax'=>$tax,'actual_delivery_distance_in_KM'=>$delivery_distance,'merchant_lat'=>$row1['latitude'],'merchant_long'=>$row1['lontitude']);
								$logArray1 = print_r($logArray, true);
								$logMessage = "\ngetClientCartItems Result at $timestamp :-\n$logArray1";
								fwrite($handle, $logMessage);				
								fclose($handle);
							//****************ENd OF Response Code*****************
							
                        echo json_encode(array('statuscode' => SUCCESS_RESPONSE, 'responseMessage' => 'true','result'=>$result,'total_item_price'=>$sub_total,'grand_total'=>ceil($final_total_amount),'taxable_total'=>$taxable_total,'total_without_tax'=>$total_w_tax,'Delivery_charges'=>ceil($final_del_price),'Service_tax'=>$tax));
                        exit;
                        }else{
                         echo json_encode(array('statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Cart is Empty'));
                         exit;
						}
                  }else{
                         echo json_encode(array('statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Cart is Empty'));
                         exit;
                  }
        }
        public function insert_driver_order_status($query,$order_id,$driver_id=null,$status=null)
		{
          $sql = "SELECT * FROM `mt_driver_order_status` WHERE `order_id`='$order_id'";
         $count = $this->db_num($sql);
        
          if($count==0)
          {
            $result = $this->mysqli->query($query);
	        
            if ($result == false) {
                
                return false;
            } else {
              return 'inserted';
            }        
          }else{
                   return 'already_exist';
          }
      }
	  public function forgot_password($query,$condition)
	 {
        
           $count = $this->db_num($query);
        
          if($count==1)
          {
			  $date_modified = date('Y-m-d H:i:s'); 
			  $user_ip = getUserIP();
			  $sys_generated_password = randomPassword();
			  $update_password = "UPDATE `accounts` as t1 inner join `accounts_cstm` as t2 on t1.id=t2.id_c SET t1.`date_modified`='$date_modified',t2.`ip_address_c`='$user_ip',t2.`gateway_c`='" . GATEWAY . "',t2.`password_c`=MD5('$sys_generated_password') where $condition"; 
			  //exit;
			  $result = $this->mysqli->query($update_password); 
			  if ($result != false) {
			  return array('updated_password'=>$sys_generated_password);
			  }
			
          }else{
                   return false;
          }
      } 
	  public function get_order_details($merchant_id,$status)
	 {
			$query = "SELECT * FROM `mt_order` as A inner join mt_order_delivery_address as C on A.`order_id` = C.`order_id` WHERE `merchant_id`='$merchant_id' and LOWER(`status`) ='$status' and DATE(DATE_FORMAT(A.date_created, '%Y-%m-%d')) = CURRENT_DATE group by A.`order_id`";
			$sql = $this->mysqli->query($query); 
        	$result = array();$data=array();$final_data=array();
        	while ($result = mysqli_fetch_assoc($sql)) 
       		{
				
				$j=0;
				$order_id = $result['order_id'];
				$get_order_details = "SELECT `id`, `order_id`, `client_id`, `item_id`, `item_name`, `order_notes`, `normal_price`, `discounted_price`, `size`, `qty`, `cooking_ref`, `addon`, `ingredients`, `non_taxable` FROM `mt_order_details` WHERE `order_id`='$order_id'";
				$sql2 = $this->mysqli->query($get_order_details);
				foreach($sql2 as $items)
				{
						if($items['size']=='H' || $items['size']=='Half')
						{
							$itemsize = 'Half';
						}elseif($items['size']=='F' || $items['size']=='Full')
						{
							$itemsize = 'Full';
						}else{
							$itemsize = 'Single';
							
						}
						$result['item'][$j]['client_id']   =  $items['client_id'];
		                $result['item'][$j]['item_id'] = $items['item_id'];
						$result['item'][$j]['item_name'] = $items['item_name'];
						$result['item'][$j]['order_notes'] = $items['order_notes'];
						$result['item'][$j]['normal_price'] = $items['normal_price'];
						$result['item'][$j]['discounted_price'] = $items['discounted_price'];
						$result['item'][$j]['size'] = $itemsize;
						$result['item'][$j]['qty'] = $items['qty'];
						$result['item'][$j]['cooking_ref'] = $items['cooking_ref'];
						$result['item'][$j]['addon'] = $items['addon'];
						$result['item'][$j]['ingredients'] = $items['ingredients'];
						$result['item'][$j]['non_taxable'] = $items['non_taxable'];
		                $j++;
				}

				$get_driver_details = "SELECT `driver_id` FROM `mt_driver_order_status` WHERE `order_id`='$order_id'";
				$sql3 = $this->mysqli->query($get_driver_details);
				$data = mysqli_fetch_assoc($sql3);
				$result['driver_id'] = $data['driver_id'];
				$result['driver_name'] = $this->get_delivery_boy_meta_keyvalue($data['driver_id'],'boy_name');
				$result['driver_mobile_number'] = $this->get_delivery_boy_meta_keyvalue($data['driver_id'],'boy_mobile_number');
				unset($result['json_details']);
				array_push($final_data,$result);

				
			}
			return $final_data;
		 
	 }
	 
	 public function fetchFirebaseTokenUsers($user_type,$sender_id,$message, $API_SERVER_KEY) 
	 {       
	    if(strtolower($user_type) == 'merchant')
		{
			$table_name = "`mt_merchant_fcm_token`";
			$column_name = '`merchant_id`';
			
			
		}else if(strtolower($user_type)== 'deliveryboy')
		{
			$table_name = "`mt_delivery_boy_fcm_token`";
			$column_name = '`delivery_boy_id`';
			
		}else if(strtolower($user_type) == 'client')
		{
			$table_name = "`mt_client_fcm_token`";
			$column_name = '`client_id`';
			
		}else{
		}
				
        $query = "SELECT `fcm_token` FROM $table_name WHERE $column_name = '$sender_id'";
        $mysql_query =  $this->mysqli->query($query);//mysqli_query($this->mysqli,$query); //query the db
        $fcmRegIds = array();
        $fcm = array();   
        while($row = mysqli_fetch_assoc($mysql_query)) {
             array_push($fcmRegIds, $row['fcm_token']);
           }
        
        if(isset($fcmRegIds)) 
		{
           /* foreach ($fcmRegIds as $key => $token) {
              $fcm[] = $token; 
              
           }*/
		    if(!empty($fcmRegIds))
			{
				   
			   //$pushStatus = $this->sendPushNotification($fcmRegIds, $message, $API_SERVER_KEY);
			   $pushStatus = $this->sendPush($fcmRegIds,$message,$API_SERVER_KEY);
				//****************LOG Creation*********************
					$APILogFile = $user_type.'_push_notification_log.txt';
					$logArray = array('user_type'=>$user_type,'sender_id'=>$sender_id,'message'=>$message,'API_SERVER_KEY'=>$API_SERVER_KEY,'FCM_TOKEN'=>$fcmRegIds);
					$handle = fopen($APILogFile, 'a');
					$timestamp = date('Y-m-d H:i:s');
					$logArray1 = print_r($logArray, true);
					$logMessage = "\n$user_type push notification log  Result at $timestamp :-\n$logArray1";
					$logArray2 = print_r($fcm, true);
					$logMessage2 = "\n$user_type push notification log  Result at $timestamp :-\n$logArray2";
					$logMessage3 = "\n$user_type push notification log  Result at $timestamp :-\n$pushStatus";
					fwrite($handle, $logMessage);
					fwrite($handle, $logMessage2);
					fwrite($handle, $logMessage3);				
					fclose($handle);
			   //****************ENd OF Code*****************
			}else{
				
				//****************LOG Creation*********************
					$APILogFile = $user_type.'_push_notification_log.txt';
					$logArray = array('user_type'=>$user_type,'sender_id'=>$sender_id,'message'=>$message,'API_SERVER_KEY'=>$API_SERVER_KEY);
					$handle = fopen($APILogFile, 'a');
					$timestamp = date('Y-m-d H:i:s');
					$logArray1 = print_r($logArray, true);
					$logMessage = "\n$user_type push notification log  Result at $timestamp :-\n$logArray1";
					$logArray2 = print_r($fcmRegIds, true);
					$logMessage2 = "\n$user_type push notification log  Result at $timestamp :-\n$logArray2";
					$logMessage3 = "\n$user_type push notification log  Result : FCM token not available for $user_type";
					fwrite($handle, $logMessage);
					fwrite($handle, $logMessage2);
					fwrite($handle, $logMessage3);				
					fclose($handle);
			   //****************ENd OF Code*****************
				
			}
        }
     }
	 public function sendPush($registration_ids, $message, $API_SERVER_KEY) 
	 {
		// ignore_user_abort();
        // ob_start();
		// API access key from Google API's Console
		// replace API
		define( 'API_ACCESS_KEY', $API_SERVER_KEY);
		$registrationIds = $registration_ids;
		$msg = array
		(
			'message' => $message,
			'title' => $title,
			'vibrate' => 1,
			'color' => "#8601af",
			'badge' => '1',
			"sound" => "default",
			"priority" => "high",
			"show_in_foreground" => true,
			"targetScreen" => 'detail'

		// you can also add images, additionalData
		);
		$fields = array
		(
		'registration_ids' => $registrationIds,
		'data' => $msg
		);
		$headers = array
		(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		
		return $result;
       // ob_flush();
     } 
     public function get_specific_package_details($package_id)
	 {
		  $resArr1 = array();
		  $get_package_details = "SELECT `id`,`name`,`description`,`package_type`,`currency_id`,`package_duration`,`package_time`,`package_status`,`best_value_c`,`veg_meal_message_c`,`egg_meal_message_c`,`nonveg_meal_message_c` FROM `ply_package` A join `ply_package_cstm` B on A.`id`=B.`id_c` WHERE A.`deleted`=0 and `id`=TRIM('$package_id')";
	       $mysql_query =  $this->mysqli->query($get_package_details);
	       while($row17 = mysqli_fetch_assoc($mysql_query))
	        { 	
	        			if($row17['package_type']!='trial')
	        			{

	        			
		                	$resArr1['package_type'] = $row17['package_type'];
		                	$resArr1['package_id'] = $row17['id'];
		                	$resArr1['name'] = $row17['name'];
		                	$resArr1['description'] = w1250_to_utf8($row17['description']);
		                	$resArr1['package_duration'] = str_replace('^','', $row17['package_duration']);
		                	$resArr1['package_time'] = str_replace('_',' ',ucfirst($row17['package_time']));
		                	switch ($row17['package_time']) {
		                		case 'lunch':
		                			$resArr1['lunch'] = true;
		                			$resArr1['dinner'] = false;
		                			break;

		                		case 'dinner':
		                			$resArr1['lunch'] = false;
		                			$resArr1['dinner'] = true;
		                			break;

		                		case 'lunch_and_dinner':
		                			$resArr1['lunch'] = true;
		                			$resArr1['dinner'] = true;
		                			$resArr1['lunch_and_dinner'] = true;
		                			break;
		                		
		                		default:
		                			$resArr1['lunch'] = false;
		                			$resArr1['dinner'] = false;
		                			$resArr1['lunch_and_dinner'] = false;
		                			break;
		                	}
		                	$resArr1['package_status'] = $row17['package_status'];
		                	$resArr1['best_value'] = (($row17['best_value_c']==0)?false:true);
		                	
	                	}else{

	                		$resArr1['package_type'] = $row17['package_type'];
		                	$resArr1['package_id'] = $row17['id'];
		                	$resArr1['name'] = $row17['name'];
		                	$resArr1['description'] = w1250_to_utf8($row17['description']);
		                	$resArr1['package_status'] = $row17['package_status'];
		                	$resArr1['lunch'] = true;
		                	$resArr1['dinner'] = true;
		                	$resArr1['best_value'] = (($row17['best_value_c']==0)?false:true);
	                	}
	                	$resArr1['veg_meal_message'] = w1250_to_utf8($row17['veg_meal_message_c']);
	                	$resArr1['egg_meal_message'] = w1250_to_utf8($row17['egg_meal_message_c']);
	                	$resArr1['nonveg_meal_message'] = w1250_to_utf8($row17['nonveg_meal_message_c']);
	                	// get kitchen from package code start here
	                	$get_kitchen_id = "SELECT `ply_kitchen_ply_package_1ply_kitchen_ida` as kitchen_id FROM `ply_kitchen_ply_package_1_c` WHERE `ply_kitchen_ply_package_1ply_package_idb`='".$row17['id']."' and `deleted`=0";
	                	$mysql_query_1 =  $this->mysqli->query($get_kitchen_id);
	                	$row18 = mysqli_fetch_assoc($mysql_query_1);
	                	$kitchen_id = trim($row18['kitchen_id']);

	                	if(empty($kitchen_id))
	                	{
	                		echo json_encode(['statuscode' => NO_CONTENT, 'responseMessage' => false, 'result'=>'this package does not belongs to any kitchen']);
	                		exit;
	                	}
	                	$get_min_max_kitchen_price = "SELECT `kitchen_type`,
	                	`kitchen_unavail_on_sunday` FROM 
	                	`ply_kitchen` A join `ply_kitchen_cstm` B on A.`id`=B.`id_c`  WHERE 
	                	`id`='$kitchen_id' and A.`deleted`=0";
	                	$mysql_query_2 =  $this->mysqli->query($get_min_max_kitchen_price);
	                	$row19 = mysqli_fetch_assoc($mysql_query_2);

	                	$get_package_min_max_price = "SELECT package_min_price_c,package_max_price_c FROM `ply_package` A join ply_package_cstm B on A.id=B.id_c WHERE id='$package_id' and deleted=0";
	                	$mysql_query_2_1 =  $this->mysqli->query($get_package_min_max_price);
	                	$package_row = mysqli_fetch_assoc($mysql_query_2_1);
	                	$package_meal_min_price = $package_row['package_min_price_c'];
	                	$package_meal_max_price = $package_row['package_max_price_c'];
	                	switch($row19['kitchen_type']){
	                       	 case 'veg':
	                       	 	$resArr1['veg'] = 'true';
	                       	 	$resArr1['nonveg'] = 'false';
	                    		$resArr1['egg'] = 'false';
	                       	 	break;

	                       	 case 'nonveg':
	                       	 	$resArr1['veg'] = 'false';
	                       	 	$resArr1['nonveg'] = 'true';
	                       	 	$resArr1['egg'] = 'true';
	                       	 	break;

	                       	 case 'egg': 
	                       	 	$resArr1['veg'] = 'false';
	                       		$resArr1['nonveg'] = 'true';
	                       		$resArr1['egg'] = 'true';
	                       	 	break;

	                       	 case 'veg_nonveg':
	                       	 		$resArr1['veg'] = 'true';
	                       	 		$resArr1['nonveg'] = 'true';
	                       	 		$resArr1['egg'] = 'true';
	                       	 		break;

	                       	 default:
	                       	  	$resArr1['veg'] = 'false';
	                       	  	$resArr1['nonveg'] = 'false';
	                       	  	$resArr1['egg'] = 'false';
	                       	  	break;
	                    }
	                    
	                    switch($row19['kitchen_unavail_on_sunday']){
	                    	case 'lunch': 
	                       	 		$resArr1['sunday_off_for_lunch'] = 'true';
	                       	 		$resArr1['sunday_off_for_dinner'] = 'false';
	                       	 		break;

	                       	case 'dinner':
	                       	 		$resArr1['sunday_off_for_lunch'] = 'true';
	                       	 		$resArr1['sunday_off_for_dinner'] = 'false';
	                       	 		break;

	                       	case 'both': 
	                       	 		$resArr1['sunday_off_for_lunch'] = 'true';
	                       	 		$resArr1['sunday_off_for_dinner'] = 'true';
	                       	 		break;
	                       	
	                       	default:
	                       	  		$resArr1['sunday_off_for_lunch'] = 'false';
	                       	 		$resArr1['sunday_off_for_dinner'] = 'false';
	                       	  		break;
	                    }
	                	$values_arr =  array($package_meal_min_price,$package_meal_max_price);
		    			$average = array_sum($values_arr)/count(array_filter($values_arr));
		    			if(is_nan($average) || is_infinite($average))
		    			{
		    				$average = 0.00;
		    			}
	                	//end of code here
	                	// select duration code
		    			if($row17['package_type']!='trial')
	        			{
		                	$duration = str_replace('^','', $row17['package_duration']);
		                	$str_arr = explode(",", $duration);  
		                	$final_arr = array_reverse($str_arr, FALSE);
							//print_r($final_arr); 
							$inc2 = 0;
		                	foreach($final_arr as $days)
		                	{
		                		$total_cost_incurred = $this->get_monthly_cost_incurred($days,$days * 2,$row17['package_time'],$row19['kitchen_type'])['total_cost_incurred'];
		                		$profit_margin_per_tiffin = $this->get_monthly_cost_incurred($days,$days * 2,$row17['package_time'],$row19['kitchen_type'])['profit_margin_per_tiffin'];
		                		if($days==30)
		                		{
		                		 $resArr1['durations'][$inc2]['days'] = '1 Month-'.$days.' Days';

		                		}
		                		else{
		                			$resArr1['durations'][$inc2]['days'] = $days.' Days';
		                			
		                		}
		                		 $resArr1['durations'][$inc2]['days_in_number'] = (int)$days;
		                		 $resArr1['durations'][$inc2]['price'] = (int)number_format((float)$total_cost_incurred + $profit_margin_per_tiffin + $average, 2, '.', '');
		                		 $inc2++;
		                	}
	               		 }
	               		 // get one meal cost
	               		 	$get_trial_lunch_cost = "SELECT `total_cost_incurred_c`,`profit_margin_per_tiffin` FROM `ply_package_rate_finder` A join `ply_package_rate_finder_cstm` B on A.id=B.id_c WHERE `name`='Trial Meal Lunch' and deleted=0";
		                	$mysql_query_2_2 =  $this->mysqli->query($get_trial_lunch_cost);
		                	$trial_lunch_row = mysqli_fetch_assoc($mysql_query_2_2);
	               		 	$resArr1['trial_meal_lunch_cost'] = (int) ceil(number_format((float)$trial_lunch_row['total_cost_incurred_c'] + $trial_lunch_row['profit_margin_per_tiffin'] + $average, 2, '.', ''));

	               		 	$get_trial_dinner_cost = "SELECT `total_cost_incurred_c`,`profit_margin_per_tiffin` FROM `ply_package_rate_finder` A join `ply_package_rate_finder_cstm` B on A.id=B.id_c WHERE `name`='Trial Meal Dinner' and deleted=0";
		                	$mysql_query_2_3 =  $this->mysqli->query($get_trial_dinner_cost);
		                	$trial_dinner_row = mysqli_fetch_assoc($mysql_query_2_3);
	               		 	$resArr1['trial_meal_dinner_cost'] = (int) ceil(number_format((float)$trial_dinner_row['total_cost_incurred_c'] + $trial_dinner_row['profit_margin_per_tiffin'] + $average, 2, '.', ''));

	               		 //end of code here
	                	
	                	//end of code here
	                	// get kitchen Addons
	                	$get_kitchen_addons = "SELECT `ply_kitchen_ply_add_on_1ply_add_on_idb` as addon_id,`name`,`price`,`deliverable_time`,`category`,`max_allowed_quantity_c` FROM `ply_add_on` A join `ply_add_on_cstm` B on A.`id`=B.`id_c` join
							`ply_kitchen_ply_add_on_1_c` C on A.id = C.`ply_kitchen_ply_add_on_1ply_add_on_idb` WHERE
							C.`ply_kitchen_ply_add_on_1ply_kitchen_ida` =
							'$kitchen_id' and A.deleted=0 and C.deleted=0";
	                   $mysql_query_3 =  $this->mysqli->query($get_kitchen_addons);
	                   $inc3=0;
				       while($row19 = mysqli_fetch_assoc($mysql_query_3))
				        { 
				        	$resArr1['addons'][$inc3]['id'] = $row19['addon_id'];
				        	$resArr1['addons'][$inc3]['name'] = $row19['name'];
				        	$resArr1['addons'][$inc3]['price'] = (int) ceil(number_format($row19['price'], 2, '.', ''));
				        	$resArr1['addons'][$inc3]['deliverable_time'] = ucfirst(str_replace("_", " ", $row19['deliverable_time']));
				        	$resArr1['addons'][$inc3]['category'] = $row19['category'];
				        	$resArr1['addons'][$inc3]['max_allowed_quantity'] = $row19['max_allowed_quantity_c'];

				        	$inc3++;
				        }	
	                	//end of code here

				        //get package delivery time code start here
				        if($resArr1['lunch'] && $resArr1['dinner'])
				        {
				        	$get_lunch_timings = "SELECT `id`,`fromtime_c`,`todate_c`,`delivery_time` FROM `ply_delivery_time` A join `ply_delivery_time_cstm` B on A.id = B.id_c WHERE A.deleted=0 and `status`='active' and `delivery_time`='lunch'";
				        	$mysql_query_4 =  $this->mysqli->query($get_lunch_timings);
		                   	$inc4=0;
					       	while($row20 = mysqli_fetch_assoc($mysql_query_4))
					        {
					        	$fromtime = explode(' ',$row20['fromtime_c']);
					        	$totime = explode(' ',$row20['todate_c']);
					        	$resArr1['lunch_timings'][$inc4]['id'] = trim($row20['id']);
					        	$resArr1['lunch_timings'][$inc4]['type'] = ucfirst($row20['delivery_time']);
					        	$resArr1['lunch_timings'][$inc4]['time'] = date("g:i a", strtotime($fromtime[1]))." to ".date("g:i a", strtotime($totime[1]));
					        	$inc4++;
					        }

					        $get_dinner_timings = "SELECT `id`,`fromtime_c`,`todate_c`,`delivery_time` FROM `ply_delivery_time` A join `ply_delivery_time_cstm` B on A.id = B.id_c WHERE A.deleted=0 and `status`='active' and `delivery_time`='dinner'";
				        	$mysql_query_5 =  $this->mysqli->query($get_dinner_timings);
		                   	$inc5=0;
					       	while($row21 = mysqli_fetch_assoc($mysql_query_5))
					        {
					        	$FROMtime = explode(' ',$row21['fromtime_c']);
					        	$TOtime = explode(' ',$row21['todate_c']);
					        	$resArr1['dinner_timings'][$inc5]['id'] = trim($row21['id']);
					        	$resArr1['dinner_timings'][$inc5]['type'] = ucfirst($row21['delivery_time']);
					        	$resArr1['dinner_timings'][$inc5]['time'] = date("g:i a", strtotime($FROMtime[1])).' to '.date("g:i a", strtotime($TOtime[1]));
					        	$inc5++;
					        }


				        }else if($resArr1['lunch']){
				        	$get_lunch_timings = "SELECT `fromtime_c`,`todate_c`,`delivery_time` FROM `ply_delivery_time` A join `ply_delivery_time_cstm` B on A.id = B.id_c WHERE A.deleted=0 and `status`='active' and `delivery_time`='lunch'";
				        	$mysql_query_4 =  $this->mysqli->query($get_lunch_timings);
		                   	$inc4=0;
					       	while($row20 = mysqli_fetch_assoc($mysql_query_4))
					        {
					        	$fromtime = explode(' ',$row20['fromtime_c']);
					        	$totime = explode(' ',$row20['todate_c']);
					        	$resArr1['lunch_timings'][$inc4]['type'] = ucfirst($row20['delivery_time']);
					        	$resArr1['lunch_timings'][$inc4]['time'] = date("g:i a", strtotime($fromtime[1]))." to ".date("g:i a", strtotime($totime[1]));
					        	$inc4++;
					        }

				        }else if($resArr1['dinner'])
				        {
				        	 $get_dinner_timings = "SELECT `fromtime_c`,`todate_c`,`delivery_time` FROM `ply_delivery_time` A join `ply_delivery_time_cstm` B on A.id = B.id_c WHERE A.deleted=0 and `status`='active' and `delivery_time`='dinner'";
				        	$mysql_query_5 =  $this->mysqli->query($get_dinner_timings);
		                   	$inc5=0;
					       	while($row21 = mysqli_fetch_assoc($mysql_query_5))
					        {
					        	$FROMtime = explode(' ',$row21['fromtime_c']);
					        	$TOtime = explode(' ',$row21['todate_c']);
					        	$resArr1['dinner_timings'][$inc5]['type'] = ucfirst($row21['delivery_time']);
					        	$resArr1['dinner_timings'][$inc5]['time'] = date("g:i a", strtotime($FROMtime[1])).' to '.date("g:i a", strtotime($TOtime[1]));
					        	$inc5++;
					        }
				        }

	                	//end of code here

	                	$get_packaging_types = "SELECT `id`,`name`,`packaging_disposition_amt`,`packaging_price`,`description`,`packaging_discounted_price`,`available_stock`,`packaging_type_c`,`status_c` FROM `ply_packaging` A join `ply_packaging_cstm` B on A.id=B.id_c WHERE A.deleted=0 and status_c='active'";
				        $mysql_query_8 =  $this->mysqli->query($get_packaging_types);
		                   	$inc8=0;
					       	while($row21 = mysqli_fetch_assoc($mysql_query_8))
					        {
					        	$resArr1['packaging_types'][$inc8]['id'] = $row21['id'];
					        	$resArr1['packaging_types'][$inc8]['name'] = $row21['name'];
					        	$resArr1['packaging_types'][$inc8]['packaging_disposition_amt'] = number_format($row21['packaging_disposition_amt'], 2, '.', '');
					        	$resArr1['packaging_types'][$inc8]['packaging_price'] = number_format($row21['packaging_price'], 2, '.', '');
					        	$resArr1['packaging_types'][$inc8]['packaging_discounted_price'] = number_format($row21['packaging_discounted_price'], 2, '.', '');
					        	$resArr1['packaging_types'][$inc8]['available_stock'] = (int)$row21['available_stock'];
					        	$resArr1['packaging_types'][$inc8]['image'] = UPLOAD_URL.$row21['id']."_package_image";
					        	$resArr1['packaging_types'][$inc8]['status'] = $row21['status_c'];
					        	$resArr1['packaging_types'][$inc8]['packaging_note'] = $row21['description'];
					        	$resArr1['packaging_types'][$inc8]['packaging_type'] = $row21['packaging_type_c'];

					        	$inc8++;
					        }

	                	//end of code here

	                	//get terms and conditions code
				        $get_terms_conditions ="SELECT `description` FROM `ply_legal` WHERE `name`='Terms and Conditions' and deleted=0 and `purpose`='order_screen'";
				        $mysql_query_6 =  $this->mysqli->query($get_terms_conditions);
				        $row22 = mysqli_fetch_assoc($mysql_query_6);
				        $resArr1['terms_and_conditions'] = w1250_to_utf8($row22['description']);
	                	//end of code here




	        }
		return array_filter($resArr1);
	}
	public function get_all_kitchens($id=NULL)
	{
		if(!is_null($id) && !empty($id))
		{
			$id = " and `id` IN($id)";
		}else{
			$id = '';
		}
	    	$resArr1 = array();
	    	$vendor_arr=array();
	    	$price_arr=array();
	    	$kitchen_arr=array();
	    	$get_sponsored_kitchen = "SELECT `id`,`name`,`date_entered`,`description`,`kitchen_type`,`kitchen_id`,`plot_no`,`street_name`,`area_name`,`landmark`,`city_name`,`state_name`,`country_name`,`fssai_lic_no`,`speciality`,`ready_parties_bulk_order`,`capacity`,`team_size`,`fast_meal_served`,
	    		`laltitude`,`longitude`,`pincode`,`kitchen_unavail_on_sunday`,
                 `ply_kitchen_supervisor_id_c`,`regular_meal_price_range`,
                 `is_sponsored_c`,`sponsored_amount_c`,`kitchen_business_type_c`,`kitchen_serving_time_c`,`sponsorship_from_c`,
                  `sponsorship_to_c`,`regular_meal_min_price_c`,
                   `regular_meal_max_price_c` FROM `ply_kitchen` A join 
                  `ply_kitchen_cstm` B on A.`id`=B.`id_c` WHERE A.`deleted`=0 and `status_c`='active' $id";
                
	                $parent_type = 'ply_Kitchen';
	                $mysql_query13 =  $this->mysqli->query($get_sponsored_kitchen);
	                $k=0;
	                while($row13 = mysqli_fetch_assoc($mysql_query13)) 
	                { 
	                    $latitude2 = trim($row13['laltitude']);
				        $logitude2 = trim($row13['longitude']);
				        $distance = get_distance_between_points($lat1, $long1,$latitude2, $logitude2);
				        $kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):0.00;
				        $delivery_range = $this->get_assumption('DR');
				        
	                       $resArr1['row_id']	= $row13['id']; 
	                       $resArr1['name'] 	= $row13['name'];
	                       $resArr1['date_entered'] = $row13['date_entered'];
	                       $resArr1['description'] = $row13['description'];
	                       switch($row13['kitchen_type'])
	                       {
	                       	 case 'veg' : 
	                       	 				$resArr1['veg'] = true;
	                       	 				$resArr1['nonveg'] = false;
	                       	 				$resArr1['egg'] = false;
	                       	 				break;
	                       	 case 'nonveg' :
	                       	 				$resArr1['veg'] = false;
	                       	 				$resArr1['nonveg'] = true;
	                       	 				$resArr1['egg'] = true;
	                       	 				break;
	                       	 case 'egg' : 
	                       	 				$resArr1['veg'] = false;
	                       					$resArr1['nonveg'] = true;
	                       					$resArr1['egg'] = true;
	                       	 				break;
	                       	 case 'veg_nonveg':
	                       	 					$resArr1['veg'] = true;
	                       	 					$resArr1['nonveg'] = true;
	                       	 					$resArr1['egg'] = true;
	                       	 default:
	                       	  		  $resArr1['veg'] = false;
	                       	  		  $resArr1['nonveg'] = false;
	                       	  		  $resArr1['egg'] = false;
	                    	}

	                       $resArr1['kitchen_id'] = $row13['kitchen_id'];
	                       $resArr1['plot_no'] = $row13['plot_no'];
	                       $resArr1['street_name'] = $row13['street_name'];
	                       $resArr1['area_name'] = $row13['area_name'];
	                       $resArr1['landmark'] = $row13['landmark'];
	                       $resArr1['city_name'] = $row13['city_name'];
	                       $resArr1['state_name'] = $row13['state_name'];
	                       $resArr1['country_name'] = $row13['country_name'];
	                       $resArr1['fssai_lic_no'] = $row13['fssai_lic_no'];
	                       $resArr1['speciality'] = $row13['speciality'];
	                       $resArr1['ready_parties_bulk_order'] = $row13['ready_parties_bulk_order'];
	                       $resArr1['capacity'] = $row13['capacity'];
	                       $resArr1['team_size'] = $row13['team_size'];
	                       $resArr1['fast_meal_served'] = $row13['fast_meal_served'];
	                       $resArr1['laltitude'] = $row13['laltitude'];
	                       $resArr1['longitude'] = $row13['longitude'];
	                       $resArr1['pincode'] = $row13['pincode'];
	                       $resArr1['kitchen_unavail_on_sunday'] = $row13['kitchen_unavail_on_sunday'];
	                       $resArr1['regular_meal_price_range'] = $row13['regular_meal_price_range'];
	                       $resArr1['kitchen_business_type'] = $row13['kitchen_business_type_c'];
	                       $resArr1['kitchen_serving_time'] = ucfirst(str_replace("_"," ",$row13['kitchen_serving_time_c']));

	                       if($is_sponserd=='yes')
	                       {
		                      $resArr1['is_sponsored'] = $row13['is_sponsored_c'];
		                      $resArr1['sponsored_amount'] = number_format($row13['sponsored_amount_c'], 2, '.', '');
		                      $resArr1['sponsorship_from'] = $row13['sponsorship_from_c'];
		                      $resArr1['sponsorship_to'] = $row13['sponsorship_to_c'];
	                       }
	                       $resArr1['regular_meal_min_price'] = number_format($row13['regular_meal_min_price_c'], 2, '.', '');
	                       $resArr1['regular_meal_max_price'] = number_format($row13['regular_meal_max_price_c'], 2, '.', '');
	                       $resArr1['Distance'] = $kilometers." Km";
	                       $resArr1['ply_kitchen_supervisor_id_c'] = $row13['ply_kitchen_supervisor_id_c'];
	                   

	                    $kitchen_id = $row13['id'];
	                	$vendor_arr = $this->get_vendor_name_from_kitchen($kitchen_id);
	                	$resArr1['vendor_name'] = trim($vendor_arr['name']);
	                	$resArr1['vendor_id'] = $vendor_arr['vendor_id'];
	                	$resArr1['vendor_row_id'] = $vendor_arr['vendor_row_id'];
	                	//get all vendor images
                        $get_all_vendor_images = "SELECT `ply_vendors_notes_1notes_idb`,D.`photo_category_c` FROM `ply_vendors` A join `ply_vendors_notes_1_c` B on A.id = B.`ply_vendors_notes_1ply_vendors_ida` join `notes` C on C.id = B.`ply_vendors_notes_1notes_idb` join `notes_cstm` D on C.id=D.id_c where A.deleted=0 and C.deleted=0 and B.deleted=0 and A.`id`='".$vendor_arr['vendor_row_id']."'";
	                    $mysql_query_1_1 =  $this->mysqli->query($get_all_vendor_images);
	                   
	                    while($row15 = mysqli_fetch_assoc($mysql_query_1_1))
	                    { 

	                       $resArr1['vendor_images'][$row15['photo_category_c']] = UPLOAD_URL .$row15['ply_vendors_notes_1notes_idb'];
	                      
	                    }

	                	//end
	                    $kitchen_sup_id = $row13['ply_kitchen_supervisor_id_c'];
	                    $resArr1['kitchen_supervisor_name'] = $this->get_kitchen_supervisor_name($kitchen_sup_id);

	                    $price_arr = $this->calculate_monthly_tiffin_price($kitchen_id,$row13['kitchen_type']);
	                    $resArr1['single_tiffin_cost'] = $price_arr['oneTiffinCost'];
	                    $resArr1['Monthly_tiffin_cost'] = $price_arr['MonthTiffinCost'];
	                    
	                    $get_all_kitchen_images = "SELECT B.ply_kitchen_notes_1notes_idb,D.`photo_category_c` FROM `ply_kitchen` A join `ply_kitchen_notes_1_c` B on A.id = B.`ply_kitchen_notes_1ply_kitchen_ida` join `notes` C on C.id = B.`ply_kitchen_notes_1notes_idb` join `notes_cstm` D on C.id=D.id_c where A.deleted=0 and B.deleted=0 and C.deleted=0 and A.`id`='$kitchen_id'";
	                    $mysql_query_1 =  $this->mysqli->query($get_all_kitchen_images);
	                    
	                    while($row13 = mysqli_fetch_assoc($mysql_query_1))
	                    { 

	                       $resArr1['kitchen_images'][$row13['photo_category_c']] = UPLOAD_URL .$row13['ply_kitchen_notes_1notes_idb'];
	                      
	                    }

	                    $get_all_kitchen_cuisines = "SELECT GROUP_CONCAT(`name` SEPARATOR ',') as cuisines FROM `ply_cuisine` A join `ply_kitchen_ply_cuisine_1_c` B on A.id=B.`ply_kitchen_ply_cuisine_1ply_cuisine_idb` WHERE A.deleted=0 and B.deleted=0 and B.ply_kitchen_ply_cuisine_1ply_kitchen_ida='$kitchen_id'";
	                    $mysql_query_2 =  $this->mysqli->query($get_all_kitchen_cuisines);
	                    $row14 = mysqli_fetch_assoc($mysql_query_2);
	                    $resArr1['cuisine'] = (!empty($row14['cuisines'])?$row14['cuisines']:'NA');

	                    $get_all_kitchen_addons = "SELECT GROUP_CONCAT(`name` SEPARATOR ',') as addOn FROM `ply_add_on` A join `ply_kitchen_ply_add_on_1_c` B on A.id=B.`ply_kitchen_ply_add_on_1ply_add_on_idb` WHERE A.deleted=0 and B.deleted = 0 and B.ply_kitchen_ply_add_on_1ply_kitchen_ida='$kitchen_id'";
	                    $mysql_query_3 =  $this->mysqli->query($get_all_kitchen_addons);
	                    $row15 = mysqli_fetch_assoc($mysql_query_3);
	                    $resArr1['addons'] = (!empty($row15['addOn'])?$row15['addOn']:'NA');

		                $rating = array();
		                $get_kitchen_ratings = "SELECT `rating` FROM `ply_rating_given_by_cust_2_kitchen` A join `ply_rating_given_by_cust_2_kitchen_cstm` B on A.id=B.id_C join ply_kitchen_ply_rating_given_by_cust_2_kitchen_1_c C on A. `ply_kitchen_id_c` = C.ply_kitchen_ply_rating_given_by_cust_2_kitchen_1ply_kitchen_ida WHERE A.deleted=0 and C.deleted=0 and `ply_kitchen_id_c`='$kitchen_id'";
		                $mysql_query_1_2 =  $this->mysqli->query($get_kitchen_ratings);
		                while($row16 = mysqli_fetch_assoc($mysql_query_1_2))
		                { 

		                   $rating[] = $row16['rating'];
		                }

		                $rating_avg = array_sum($rating) / count(array_filter($rating));
		                if(is_nan($rating_avg))
		                {
		                	$rating_avg = 0;
		                }

		                $resArr1['rating'] = number_format($rating_avg, 1, '.', '');
		                if(!empty($resArr1))
		                {
		                	array_push($kitchen_arr,$resArr1);
		                }
		                $resArr1 = array();
	                      
	            } //while loop closing
	           return $kitchen_arr;  
	}
	public function get_offer_related_kitchens($offer_id)
	{
		$response = array();
		$kitchen_id = array();
		$get_all_offer_related_kitchens = "SELECT `ply_offer_ply_kitchen_1ply_kitchen_idb` as kitchen_id FROM `ply_offer_ply_kitchen_1_c` WHERE `deleted`=0 and `ply_offer_ply_kitchen_1ply_offer_ida`=TRIM('$offer_id')";
		
		$mysql_query =  $this->mysqli->query($get_all_offer_related_kitchens);
	    while($row15 = mysqli_fetch_assoc($mysql_query))
	    {
	    	$kitchen_id[] = $row15['kitchen_id'];
	    	$kitchen_ids = "'" . implode("', '", $kitchen_id) . "'";

	    	//array_push($resArr,$this->get_all_kitchens($kitchen_id));

	    }
	    $response = $this->get_all_kitchens($kitchen_ids);
	    return  array_filter($response);

	}
}
$db = new DB();
?>