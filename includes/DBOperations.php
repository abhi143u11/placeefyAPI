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
	    	$get_supervisor_name = "SELECT Concat(Ifnull(`salutation`,' '),' ',Ifnull(`first_name`,' ') ,' ', Ifnull(`last_name`,' ')) as name FROM `ply_kitchen_supervisor` WHERE `id`='$id' and `deleted`=0"; 
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
                        '$kitchen_type'";
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
	                       $resArr1['kitchen_type'] = $row13['kitchen_type'];
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
	                       $resArr1['kitchen_serving_time'] = $row13['kitchen_serving_time'];

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
	                	$resArr1['vendor_name'] = $vendor_arr['name'];
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

	                    $price_arr = $this->calculate_monthly_tiffin_price($kitchen_id);
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
	    public function calculate_monthly_tiffin_price($kitchen_id)
	    {
	    	$resArr = array();
	    	$get_min_max_kitchen_price = "SELECT `regular_meal_min_price_c`,`regular_meal_max_price_c` FROM `ply_kitchen` A join `ply_kitchen_cstm` B on A.id=B.id_c WHERE `id`='$kitchen_id'";
	    	$mysql_query =  $this->mysqli->query($get_min_max_kitchen_price);
		    $row11 = $mysql_query->fetch_assoc();				
		    $regular_meal_min_price = (isset($row11['regular_meal_min_price_c'])?$row11['regular_meal_min_price_c']:0.00);
		    $regular_meal_max_price = (isset($row11['regular_meal_max_price_c'])?$row11['regular_meal_max_price_c']:0.00);
		    $values_arr =  array($regular_meal_min_price,$regular_meal_max_price);
		    $average = array_sum($values_arr)/count(array_filter($values_arr));
            $resArr = $this->get_monthly_cost_incurred(30,60,'lunch_and_Dinner',$pure_veg_flag=null);
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
	    	$get_monthly_cost = "SELECT `total_cost_incurred_c`,`profit_margin_per_tiffin`,`no_of_tiffins_to_deliver` FROM `ply_package_rate_finder` A join `ply_package_rate_finder_cstm` B on A.id=B.id_c WHERE A.deleted=0 and `no_of_days_deliveries`=$no_of_days_deliveries and `no_of_tiffins_to_deliver`=$no_of_tiffins_to_delivers and `delivery_time`='$delivery_time' limit 1";
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
	    public function get_all_vendors()
	    {
	    	$vendor_arr =  array();$vendor_arr1 =  array();
	    	$parent_type = 'ply_Vendors';
	    	$get_vendor_details= "SELECT A.`id`,Concat(Ifnull(`salutation`,' '),' ',Ifnull(`first_name`,' ') ,' ', Ifnull(`last_name`,' ')) as name,`vendor_id`,`date_entered`,`description`,`website`,`team_size`,`city_name_c`,`phone_mobile`,`phone_work`,`primary_address_street`,`primary_address_city`,`primary_address_state`,`primary_address_postalcode`,`primary_address_country`,`about_vendor` FROM `ply_vendors` A join `ply_vendors_cstm` B on A.id=B.id_c where A.deleted=0";
		    $mysql_query =  $this->mysqli->query($get_vendor_details);			
		    while($row11 = $mysql_query->fetch_assoc())
		    {
		    	$vendor_arr['row_id'] = $row11['id'];
				$vendor_arr['name'] = $row11['name'];
				$vendor_arr['vendor_id'] = $row11['vendor_id'];
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
				$vendor_arr['image'] = $this->get_image_from_notes_module($row11['id'],$parent_type);

				if(!empty($vendor_arr))
                {
                	array_push($vendor_arr1,$vendor_arr);
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
	                $resArr1['description'] = $row13['description'];
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
	               $resArr1['vendor_name'] = $vendor_arr['name'];
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
					$price_arr = $this->calculate_monthly_tiffin_price($kitchen_id);
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
	                $get_kitchen_ratings = "SELECT `rating`,`review_c`,`account_id_c` FROM `ply_rating_given_by_cust_2_kitchen` A join `ply_rating_given_by_cust_2_kitchen_cstm` B on A.id=B.id_C join ply_kitchen_ply_rating_given_by_cust_2_kitchen_1_c C on A. `ply_kitchen_id_c` = C.ply_kitchen_ply_rating_given_by_cust_2_kitchen_1ply_kitchen_ida WHERE A.deleted=0 and C.deleted=0 and `ply_kitchen_id_c`='$kitchen_id'";
	                $mysql_query_1_2 =  $this->mysqli->query($get_kitchen_ratings);
	                while($row16 = mysqli_fetch_assoc($mysql_query_1_2))
	                { 
	                	$get_cust_name = "SELECT `name` FROM `accounts` WHERE deleted=0 and `id`='".$row16['account_id_c']."'";
	                	$mysql_query_3_1 =  $this->mysqli->query($get_cust_name);
	                	$row17 = mysqli_fetch_assoc($mysql_query_3_1);
	                    $cust_nm = (!empty($row17['name'])?$row17['name']:'NA');

	                   $rating[] = $row16['rating'];
	                   $review[$cust_nm] = $row16['review_c'];
	                }

	                $rating_avg = array_sum($rating) / count(array_filter($rating));
	                if(is_nan($rating_avg))
	                {
	                	$rating_avg = 0;
	                }

	                $resArr1['rating'] = number_format($rating_avg, 1, '.', '');
	                $resArr1['reviews'] = $review;
   					
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
    public function get_categorywiseitems($query,$category_type="all")
    {
      $mysql_query =  $this->mysqli->query($query);//mysqli_query($this->mysqli,$query); //query the db
      $resArr = array(); //create the result array
      $j=0; $result = array();$data=array();$k=0; $arr=array();
      while($row = $mysql_query->fetch_assoc()) 
      { 
          $cat_id = $row['category_id'];
          $mer_id = $row['merchant_id'];
          $cat_name =  $this->mysqli->query("SELECT * FROM `mt_category` WHERE `id`='$cat_id'");
          $row = $cat_name->fetch_assoc();
          $resArr['cat_id'] =  $row['id'];
          $resArr['category_name'] =  $row['category_name'];
          $resArr['category_description'] =  w1250_to_utf8($row['category_description']);
          $resArr['photo'] =  $row['photo'];
          $resArr['status'] =  $row['status'];
          $resArr['sequence'] =  $row['sequence'];
          $resArr['date_created'] =  $row['date_created'];
          $resArr['date_modified'] =  $row['date_modified'];
          $resArr['spicydish'] =  $row['spicydish'];
          $resArr['spicydish_notes'] =  $row['spicydish_notes'];
          $resArr['dish'] =  $row['dish'];
          $resArr['category_name_trans'] =  $row['category_name_trans'];
          $resArr['category_description_trans'] =  $row['category_description_trans'];
          $resArr['parent_cat_id'] =  $row['parent_cat_id'];


          
			$i=0;
			$item_ids =  $this->mysqli->query("SELECT `item_id` FROM `mt_merchant_categories` where `category_id`='$cat_id' and `merchant_id`='$mer_id'");  
          while($result = mysqli_fetch_assoc($item_ids))
          {
              $item_id = $result['item_id'];
			  if($category_type=='all')
			  {
				$item_query = "SELECT `item_name`,`item_description`,`status`,`price`,`addon_item`,`cooking_ref`,`discount`,`is_featured`,`date_created`,`date_modified`,`ingredients`,`spicydish`,`two_flavors`,`two_flavors_position`,`require_addon`,`dish`,`price_type`,`item_name_trans`,`item_description_trans`,`not_available`,`points_earned`,`points_disabled`,`is_veg_nonveg`,`stock_status`,`gallery_photo`,`photo`,`price_type` from mt_item WHERE  `id`= '$item_id' and LOWER(`status`) = 'active' ORDER BY `item_name` ASC";
			  }else{
				  $item_query = "SELECT `item_name`,`item_description`,`status`,`price`,`addon_item`,`cooking_ref`,`discount`,`is_featured`,`date_created`,`date_modified`,`ingredients`,`spicydish`,`two_flavors`,`two_flavors_position`,`require_addon`,`dish`,`price_type`,`item_name_trans`,`item_description_trans`,`not_available`,`points_earned`,`points_disabled`,`is_veg_nonveg`,`stock_status`,`gallery_photo`,`photo` from mt_item WHERE  `id`= '$item_id' and LOWER(`status`) = 'active' and LOWER(`is_veg_nonveg`)=LOWER('$category_type') ORDER BY item_name ASC";  
			  }
              $item_name =  $this->mysqli->query($item_query);
              $record = mysqli_fetch_assoc($item_name);
			  if(!empty($record))
			  {  
				  $resArr['item'][$i]['item_id'] = $item_id; 
				  $resArr['item'][$i]['item_name'] = utf8_encode($record['item_name']); 
				  $resArr['item'][$i]['item_description'] = w1250_to_utf8($record['item_description']); 
				  $resArr['item'][$i]['status'] = $record['status']; 
				  $resArr['item'][$i]['addon_item'] = $record['addon_item']; 
				  $resArr['item'][$i]['cooking_ref'] = $record['cooking_ref']; 
				  $resArr['item'][$i]['discount'] = $record['discount']; 
				  $resArr['item'][$i]['is_featured'] = $record['is_featured']; 
				  $resArr['item'][$i]['date_created'] = $record['date_created']; 
				  $resArr['item'][$i]['date_modified'] = $record['date_modified']; 
				  $resArr['item'][$i]['ingredients'] = $record['ingredients']; 
				  $resArr['item'][$i]['spicydish'] = $record['spicydish'];
				  $resArr['item'][$i]['two_flavors'] = $record['two_flavors']; 
				  $resArr['item'][$i]['two_flavors_position'] = $record['two_flavors_position'];
				  $resArr['item'][$i]['require_addon'] = $record['require_addon'];
				  $resArr['item'][$i]['dish'] = $record['dish'];
				  $resArr['item'][$i]['item_name_trans'] = $record['item_name_trans'];
				  $resArr['item'][$i]['not_available'] = $record['not_available'];
				  $resArr['item'][$i]['points_earned'] = $record['points_earned'];
				  $resArr['item'][$i]['points_disabled'] = $record['points_disabled'];
				  $resArr['item'][$i]['is_veg_nonveg'] = $record['is_veg_nonveg'];
				  $resArr['item'][$i]['images'] = $record['photo'];
				  $resArr['item'][$i]['stock_status'] = $record['stock_status'];
				  $resArr['item'][$i]['price_type'] = $record['price_type'];
				  $arr = unserialize($record['price']);
				  if(is_array($arr)){
					  foreach((array)$arr as $key=>$value)
					  {
					    if(!empty($record['price_type']) && strtolower($record['price_type'])== strtolower('Single'))
						{
							$resArr['item'][$i][$key]= $value;
							
						}else{
								$query12 = "SELECT `size_name` FROM `mt_size` WHERE `id`='$key'";
								$result1 = $this->mysqli->query($query12); 
								$record11 = mysqli_fetch_assoc($result1);
								$size_name = $record11['size_name'];
								$resArr['item'][$i][$size_name]= $value;
							   // $k++;
						}
					  }
					 } 
				
				  $i++;
			  }
			  			
          }
		  
		  
		  
	
          array_push($data,$resArr);
          $j++;
          $resArr=array();
      }
	  
       //print_r($data);exit;
      return $data;

    }

    public function insert_execute($query,$client_id= null,$merchant_id= null,$item_id= null,$quantity_flag= null,$item_price= null) 
    {
      if(isset($client_id) && isset($merchant_id) && isset($item_id) && isset($quantity_flag) && isset($item_price))
      {
       
        $check_cart_item = "SELECT * FROM `mt_cart` WHERE `client_id`=TRIM('$client_id') and `merchant_id`=TRIM('$merchant_id') and `item_id`=TRIM('$item_id') and `quantity_flag`=TRIM('$quantity_flag')";
        if($this->db_num($check_cart_item))
        {
			
          $result1 = $this->mysqli->query($check_cart_item);
          $row1 = $result1->fetch_assoc();
		 
			  $quantity = $row1['quantity'];
			  $actual_item_price = $row1['item_price'];
			  $updated_qty =  $quantity + 1;    
			  $updated_price =  $actual_item_price + $item_price;
			  $date_modified = date('Y-m-d H:i:s');   
			  $update_qty_price = "UPDATE `mt_cart` SET `item_price`='$updated_price',`quantity`='$updated_qty',`datetime_modified`='$date_modified' WHERE `client_id`='$client_id' and `merchant_id`='$merchant_id' and `item_id`='$item_id' and `quantity_flag`='$quantity_flag'"; 
			  $result = $this->mysqli->query($update_qty_price); 
			  if ($result != false) {
			  return 'qty_updated';
			  }
		  
        }
      else{
		   
		    $get_client_mid =  "SELECT `merchant_id` FROM `mt_cart` WHERE `client_id`='$client_id'";
			if($this->db_num($get_client_mid))
			{
				
				//echo $client_id;exit;
				$result2 = $this->mysqli->query($get_client_mid);
				$row2 = $result2->fetch_assoc();
				if($merchant_id != $row2['merchant_id'])
				{
					$get_client_id =  "SELECT `client_id` FROM `mt_cart` WHERE `client_id`='$client_id'";
					if($this->db_num($get_client_id))
					{
							$delete_previous_merchant = "DELETE FROM `mt_cart` WHERE `client_id`='$client_id'";
							$this->mysqli->query($delete_previous_merchant);
					}
					$check_merchant_id = "SELECT `id` FROM `mt_merchant` WHERE `id`= TRIM('$merchant_id') and LOWER(`status`) = LOWER('Active')";
					if($this->db_num($check_merchant_id))
					{
						$result = $this->mysqli->query($query);
						
						if ($result == false) {
							
							return false;
						} else {
									return $this->mysqli->insert_id;
									 
						}  
					}else{
					  return 'invalid_merchant_id';
				  }
				}else{
					$check_merchant_id = "SELECT `id` FROM `mt_merchant` WHERE `id`= TRIM('$merchant_id') and LOWER(`status`) = LOWER('Active')";				
					if($this->db_num($check_merchant_id))
					{
						$result = $this->mysqli->query($query);
						
						if ($result == false) {
							
							return false;
						} else {
									return $this->mysqli->insert_id;
									 
						}  
					}
				}
				  
			}else{
						
						$check_merchant_id = "SELECT `id` FROM `mt_merchant` WHERE `id`= TRIM('$merchant_id') and LOWER(`status`) = LOWER('Active')";
						if($this->db_num($check_merchant_id))
						{
							
							$result = $this->mysqli->query($query);
							
							if ($result == false) {
								
								return false;
							} else {
									return $this->mysqli->insert_id;
									
									
							}  
						}else
						{
						  return 'invalid_merchant_id';
						}

			}
		}
	  }
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
  public function get_delivery_boy_meta_keyvalue($delivery_boy_id,$meta_key) 
    { 
      $query = "SELECT * FROM `mt_delivery_boy_meta` WHERE `meta_key`='$meta_key' and `delivery_boy_id`='$delivery_boy_id'";
      
      $result = $this->mysqli->query($query);
      $row = $result->fetch_assoc();
       if(isset($row['meta_value']))
        return $row['meta_value'];
        else
        return '';
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
    public function update_cart_quantity($cart_id,$quantity,$item_price)
	    {
        $date_modified = date('Y-m-d H:i:s'); 
        $get_cart_details = "SELECT * FROM `mt_cart` WHERE `id`='$cart_id'";
        $result = $this->mysqli->query($get_cart_details);
        $row = $result->fetch_assoc();
        $quantity = ($row['quantity']>0)?$row['quantity']-1:$row['quantity']-1;
        $item_price = ($row['item_price']>0)?$row['item_price']-$item_price:$row['item_price']-$item_price;
	
        if($quantity>=0 && $item_price>=0)
        { 
			//echo $quantity;exit;
	      if($quantity==0)
		  {
			  $delete_quantity_zero_row = "DELETE FROM `mt_cart` WHERE `id`='$cart_id'";
			  $result1 = $this->mysqli->query($delete_quantity_zero_row);
			  if ($result1 != false) {
					return 'qty_removed';
					}
		  }else{
				  $update_qty_price = "UPDATE `mt_cart` SET `item_price`='$item_price',`quantity`='$quantity',`datetime_modified`='$date_modified' WHERE `id`='$cart_id'"; 
					$result = $this->mysqli->query($update_qty_price); 
					if ($result != false) {
					return 'qty_removed';
					}
		  }
        }
	       
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
            //print_r($order_info);exit; 
              $dayname = strtolower(date('l'));
              $sql = "SELECT * FROM `mt_merchant_open_close` WHERE `merchant_id`='".$order_info['merchant_id']."' and LOWER(`day`) = LOWER('$dayname')";  
              $mysql_query =  $this->mysqli->query($sql);
              $row11 = $mysql_query->fetch_assoc();

              $get_time = "SELECT SUBSTRING_INDEX(ADDTIME(now(), '04:30:00') ,' ',-1) as time";  
              $mysql_query1 =  $this->mysqli->query($get_time);
              $row12 = $mysql_query1->fetch_assoc();
			  $endTime = trim($row12['time']);

				// user defined function available in functions.php
              $check_merchant_available =  check_merchant_available($endTime,$row11['start_time'],$row11['end_time'],$row11['is_open_close']); 
			  $merchant_login_status = $this->get_merchant_meta_keyvalue($order_info['merchant_id'],'merchant_login_status');
             if($check_merchant_available && strtolower($merchant_login_status)== strtolower('On'))
             {         
                    $flag =0; $get_lat_long=array();$cart_ids=array();
                    $getmerchantdetails = "SELECT `delivery_charges`,`latitude`,`lontitude` FROM `mt_merchant` WHERE `id`='".$order_info['merchant_id']."'";
                    $result1 = $this->mysqli->query($getmerchantdetails);
                    $row1 = $result1->fetch_assoc();
                    //print_r($row1);exit;
                    if(isset($order_info['voucher_code']))
                    {
                      $getvoucherdetails = "SELECT `amount`,`voucher_type` FROM `mt_voucher` WHERE `voucher_name`='".$order_info['voucher_code']."'";
                      $result2 = $this->mysqli->query($getvoucherdetails);
                      $row2 = $result1->fetch_assoc();
                    }
                    //print_r($row2);exit;
                    $voucher_amount = (isset($row2['amount'])?$row2['amount']:'');
                    $voucher_type = (isset($row2['voucher_type'])?$row2['voucher_type']:'');;
        
                    $user_ip = getUserIP();
                    $datetime = date("Y-m-d H:i:s"); 
                    $delivery_date = date("Y-m-d", strtotime($order_info['delivery_date']) );
                    $insert_order_details ="INSERT INTO `mt_order`(`merchant_id`,`client_id`,`json_details`,`payment_type`,`delivery_date`,`delivery_time`,`voucher_code`,`voucher_amount`,`voucher_type`,`date_created`,`ip_address`,`discounted_amount`,`request_from`) VALUES ('".$order_info['merchant_id']."','".$order_info['client_id']."','".$order_info['json_details']."','".$order_info['payment_type']."','$delivery_date','".$row12['time']."','".$order_info['voucher_code']."','$voucher_amount','$voucher_type','$datetime','$user_ip','$voucher_amount','android')";
                    $success =  $this->mysqli->query($insert_order_details);   
                    if($success) 
                    {             
                        $order_id =  $this->mysqli->insert_id;
                        $cart_ids = explode(",",$order_info['cart_ids']);
                        //print_r($cart_ids);exit;
                        $item_price = array();
                        foreach($cart_ids as $cart_id)
                        {
                              
                              $item_price[] = $this->insert_order_details($cart_id,$order_id,$order_info['client_id'],$order_info['merchant_id']);
                              
                        }
                        if(!empty($item_price))
                        {
                            //print_r($item_price);exit;
                            $sub_total = sum_array($item_price); // user defined function available in functions.php
                            $percentage = 18;
                            $tax = ($percentage / 100) * $sub_total;
                            $get_lat_long = get_details_from_address($order_info['delivery_address']);
                            if(isset($row1['latitude']) && isset($row1['lontitude']))
                            {
                                $delivery_distance = get_distance_between_points($row1['latitude'],$row1['lontitude'],$get_lat_long['lat'],$get_lat_long['long']);
                              // print_r($delivery_distance);exit;
                              if($delivery_distance['kilometers']!='')
                              {
                                $getshippingrate = "SELECT `distance_from`,`distance_to`,`shipping_units`,`distance_price` FROM `mt_shipping_rate`";
                                $result11 = $this->mysqli->query($getshippingrate);
                                while ($record = mysqli_fetch_assoc($result11))
                                {
                                      $dilevery_price = calculate_delivery_charger($delivery_distance['kilometers'],$record['shipping_units'],$record['distance_price'],$record['distance_from'],$record['distance_to']);
                                      if($dilevery_price !='' && !empty($dilevery_price))
                                      {
                                        $final_del_price = $dilevery_price;
                                        break;
                                      }
                                }
                              }
                            }
                            $discounted_amount = ($order_info['discounted_amount']!='')?number_format((float)$order_info['discounted_amount'], 2, '.', ''):number_format((float)0, 2, '.', '');
                            $final_del_price = (isset($final_del_price)?number_format((float)$final_del_price, 2, '.', ''):number_format((float)30, 2, '.', ''));
                            //$grand_total = number_format((float)($sub_total + $tax + $final_del_price) - $discounted_amount, 2, '.', '');
							$grand_total = number_format((float)($sub_total + $final_del_price) - $discounted_amount, 2, '.', '');
                            //$taxable_total = number_format((float) ($sub_total + $tax), 2, '.', '');
							$taxable_total = number_format((float) ($sub_total), 2, '.', '');
                            $total_w_tax = number_format((float)(float) ($sub_total + $final_del_price), 2, '.', '');
                            $this->execute("UPDATE `mt_order` SET `sub_total`='$sub_total',`tax`='$tax', `taxable_total`='$taxable_total',`total_w_tax`='$total_w_tax',`grand_total`='$grand_total',`delivery_charge`='$final_del_price',`discounted_amount`='$discounted_amount',`date_modified`='$datetime' WHERE `order_id`='$order_id'");
							
                            $inert_address_details = "INSERT INTO `mt_order_delivery_address`(`order_id`, `client_id`, `street`, `city`, `state`, `zipcode`, `location_name`, `country`, `date_created`, `ip_address`, `contact_phone`, `formatted_address`, `google_lat`, `google_lng`, `area_name`) VALUES ('$order_id','".$order_info['client_id']."','".$get_lat_long['street']."','".$get_lat_long['city']."','','".$get_lat_long['postal_code']."','".$order_info['delivery_address']."','".$get_lat_long['country']."','$datetime','$user_ip','','".$get_lat_long['formatted_address']."','".$get_lat_long['lat']."','".$get_lat_long['long']."','')";
                            $this->execute($inert_address_details);
                            $flag =1;
                        }  
        
                    }
                    if($flag){
						       $log_array = array('order_id'=>$order_id,'subtotal'=>$sub_total,'tax'=>number_format((float)$tax, 2, '.', ''),'taxable_total'=>$taxable_total,'total_without_tax'=>$total_w_tax,'delivery_charge'=>$final_del_price,'discounted_amount'=>$discounted_amount,'grand_total'=>$grand_total);
						        //send push notification to merchant start
								
								$get_client_name = "SELECT CONCAT_WS (' ', first_name, last_name) as client_name FROM `mt_order` as A inner join `mt_client` as B on A.`client_id`=B.`client_id` WHERE `order_id`='$order_id'";
								$mysql_query17 =  $this->mysqli->query($get_client_name);
								$row17 = $mysql_query17->fetch_assoc();
								$client_name = trim($row17['client_name']); 
								$Merchant_Server_key= self::$MERCHANT_API_SERVER_KEY;
								$message = "New Order has been placed.Order Id is-".$order_id." .Order from-".$client_name." .Subtotal of order is-".$sub_total;
								$res = $this->fetchFirebaseTokenUsers('merchant',$order_info['merchant_id'],$message, $Merchant_Server_key); //merchant_server_api_key defined in constant .php
									if(!is_null($res))
									{
										//****************LOG Creation*********************
											$APILogFile = 'order_placed_response.txt';
											$handle = fopen($APILogFile, 'a');
											$timestamp = date('Y-m-d H:i:s');
											$logArray1 = print_r($res, true);
											$logMessage = "\npush notification sent response  Result at $timestamp :-\n$logArray1";
											$logArray2 = print_r($log_array, true);
											$logMessage1 = "\n\nOrder Placed response Result at $timestamp :-\n$logArray2";
											fwrite($handle, $logMessage);
											fwrite($handle, $logMessage1);									
											fclose($handle);
										//****************ENd OF Code*****************
									//send push notification to merchant End
									}else{
										//****************LOG Creation*********************
											$APILogFile = 'order_placed_response.txt';
											$handle = fopen($APILogFile, 'a');
											$timestamp = date('Y-m-d H:i:s');
											$logMessage = "\npush notification sent response  Result at $timestamp :-\n NULL";
											fwrite($handle, $logMessage);							
											fclose($handle);
									}
								
							//****************Response LOG Creation*********************
								$APILogFile = 'placedOrder.txt';
								$handle = fopen($APILogFile, 'a');
								$timestamp = date('Y-m-d H:i:s');
								$logArray = array('order_id'=>$order_id,'subtotal'=>$sub_total,'tax'=>number_format((float)$tax, 2, '.', ''),'taxable_total'=>$taxable_total,'total_without_tax'=>$total_w_tax,'delivery_charge'=>$final_del_price,'discounted_amount'=>$discounted_amount,'grand_total'=>$grand_total);
								$logArray1 = print_r($logArray, true);
								$logMessage = "\nplacedOrder Result at $timestamp :-\n$logArray1";
								fwrite($handle, $logMessage);				
								fclose($handle);
							//****************ENd OF Response Code*****************
								return ['order_id'=>$order_id,'subtotal'=>$sub_total,'tax'=>number_format((float)$tax, 2, '.', ''),'taxable_total'=>$taxable_total,'total_without_tax'=>$total_w_tax,'delivery_charge'=>$final_del_price,'discounted_amount'=>$discounted_amount,'grand_total'=>$grand_total];
						} 
                    else{
							return false;
						}   
                }else{
						echo json_encode(array('statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'Sorry Merchant is not available, Please contact admin'));
                            exit;
                }          
                  
       }
    public function insert_order_details($cart_id,$order_id,$client_id,$merchant_id)
    {
       $run_query = "SELECT * FROM `mt_cart` WHERE id='$cart_id' and `quantity` > 0 and merchant_id='$merchant_id' and `client_id`='$client_id'";
       $getcartcount = $this->db_num($run_query);
       if($getcartcount == 1)
       {
        
           $getcartdetails = "SELECT * FROM `mt_cart` WHERE id='$cart_id' and `quantity` > 0 and merchant_id='$merchant_id' and `client_id`='$client_id'";
           $result = $this->mysqli->query($getcartdetails);
           $row = $result->fetch_assoc();
 
           $getitemprice = "SELECT `price` FROM `mt_item` WHERE `id`='".$row['item_id']."'";
           $result1 = $this->mysqli->query($getitemprice);
           $row1 = $result1->fetch_assoc();
           $arr = unserialize($row1['price']);
           $price='';
           if(is_array($arr)){
             foreach($arr as $key=>$value)
             {
               $size_id = $key;
               $query = "SELECT `size_name` FROM `mt_size` WHERE `id`='$size_id'";
               $result = $this->mysqli->query($query); 
               $record = mysqli_fetch_assoc($result);
               $size_name = $record['size_name'];
              
               if($row['quantity_flag']=='H' && $size_name=='Half')
                $price = $value;
               if($row['quantity_flag']=='F' && $size_name=='Full')
               $price = $value; 
             }
             
           }
 
           $insert_order_details = $this->execute("INSERT INTO `mt_order_details`(`order_id`, `client_id`, `item_id`, `item_name`, `size`, `qty`,`normal_price`) VALUES ('$order_id','$client_id','".$row['item_id']."','".$row['item_name']."','".$row['quantity_flag']."','".$row['quantity']."','$price')");
           if($insert_order_details)
           {
             return $row['item_price'];
           }
     }else{
              echo json_encode(array('statuscode' => NO_CONTENT, 'responseMessage' => 'false','result'=>'For mentioned cart id and client id, merchant id is incorrect'));
                            exit;
     }
 
      
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
	 public function get_order_reports($merchant_id,$from_date,$to_date)
	 {
			$query = "SELECT * FROM `mt_order` as A inner join mt_order_delivery_address as C on A.`order_id` = C.`order_id` WHERE `merchant_id`='$merchant_id' and LOWER(`status`) ='delivered' and DATE(DATE_FORMAT(A.date_created, '%Y-%m-%d')) >= '$from_date' and  DATE(DATE_FORMAT(A.date_created, '%Y-%m-%d')) <= '$to_date' group by A.`order_id`";
			$sql = $this->mysqli->query($query); 
        	$result = array();$data=array();$final_data=array();
        	while ($result = mysqli_fetch_assoc($sql)) 
       		{
				
				$j=0;
				$order_id = $result['order_id'];
				$client_id = $result['client_id'];
				$get_client_details ="SELECT CONCAT_WS(' ', `first_name`, `last_name`) AS fullname,`contact_phone`  FROM `mt_client` WHERE client_id='$client_id'";
				$sql4 = $this->mysqli->query($get_client_details);
				$clientdata = mysqli_fetch_assoc($sql4);
				$result['client_name'] = $clientdata['fullname'];
				$result['client_phone'] = $clientdata['contact_phone'];
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
	 public function get_featured_restro($query,$lat1,$long1)
	 {
		    $query=$this->mysqli->query($query); 
        	$result = array();$j=0;$data=array();
        	while ($record = mysqli_fetch_assoc($query)) 
       		{
		            $latitude2 = $record['latitude'];
		            $logitude2 = $record['lontitude'];
		            $distance = get_distance_between_points($lat1, $long1, $latitude2, $logitude2);
		            $kilometers = (float)($distance['kilometers']!='')?number_format($distance['kilometers'], 2, '.', ''):'0';
		            $merchant_area_covered = (float)($this->get_meta_value_from_metakey($record['id'],'area_covered_in_km')!='')?trim($this->get_meta_value_from_metakey($record['id'],'area_covered_in_km')):'0';
					 
				if($kilometers>0 && $merchant_area_covered>0)
				{
		            if($kilometers <= $merchant_area_covered)
					{
						
								  $result['merchant_id'] = $record['id']; 
								  
								  //fetch merchant open or close time					  
									  $dayname = strtolower(date('l'));					  
									  $get_merc_openclosestatus = "SELECT * FROM `mt_merchant_open_close` WHERE `merchant_id`='".$record['id']."' and LOWER(`day`) = LOWER('$dayname') and LOWER(`status`) = 'active'"; 
									  $mysql_query =  $this->mysqli->query($get_merc_openclosestatus);			
									  $row11 = $mysql_query->fetch_assoc();				
									  $restro_starttime = (isset($row11['start_time'])?$row11['start_time']:'NA');			
									  $restro_endtime = (isset($row11['end_time'])?$row11['end_time']:'NA');				
									  $restro_open_close_status = (isset($row11['is_open_close'])?$row11['is_open_close']:'close');
									//end 
									
								  $offer_available = "SELECT `offer_percentage`,`offer_price`,`offer_image`,`valid_to`,`valid_from` FROM `mt_offers` as A inner join mt_merchant_offers as B on A.id = B.offer_id where B.merchant_id = '".$record['id']."' and LOWER(A.`status`) = LOWER('Active')";
								  
								  $getofferdetails = $this->mysqli->query($offer_available);
								  $offer_values = mysqli_fetch_assoc($getofferdetails);
								  $valid_to = $offer_values['valid_to'];
								  $valid_from =  $offer_values['valid_from'];
								  $today = date('Y-m-d');
								  if($valid_from <= $today && $valid_to >= $today)
								   {
										$result['offer_percentage'] = number_format($offer_values['offer_percentage'], 2, '.', '');
										$result['offer_price'] = number_format($offer_values['offer_price'], 2, '.', '');
										$result['offer_image'] = $offer_values['offer_image'];
										$result['offer_valid_upto'] = $valid_to;
								 }else
								 {
									 $result['offer_percentage'] = number_format(0.00, 2, '.', '');
								 }
								$rating = "SELECT `rating` FROM `mt_review` WHERE `merchant_id`='".$record['id']."'";
								  // $rating = "SELECT * FROM `mt_rating` WHERE `merchant_id` = '".$record['id']."'";
								  $getmerchantratings = $this->mysqli->query($rating);
								  $numberOfReviews = 0; $totalStars = 0; $average=0;
								  while ($values1 = mysqli_fetch_assoc($getmerchantratings)) 
								  {
									$totalStars += $values1['rating'];
									$numberOfReviews++;
								  }
								  if($numberOfReviews!=0.0)
									$average = number_format((float) $totalStars/$numberOfReviews, 1, '.', ''); 
								  else
									$average = number_format((float) 0, 1, '.', ''); 
								  
								  $result['cuisine'] = array();  
								  $j=0;
								  $query1 = "SELECT cuisine_id,cuisine_name FROM `mt_merchant_cuisine` as A inner join mt_cuisine as B on A.cuisine_id = B.id where A.merchant_id = '".$record['id']."'";
								  $getcuisine = $this->mysqli->query($query1); 
								  
								  foreach($getcuisine as $record1) {
									
									$result['cuisine'][$j]['cuisine_id']   =  $record1['cuisine_id'];
									$result['cuisine'][$j]['cuisine_name'] = $record1['cuisine_name'];
									$j++;
								  }
								  if(empty($result['cuisine'])){$result['cuisine'] = '{}';}
								  
								  $result['restaurant_slug'] = $record['restaurant_slug'];  
								  $result['restaurant_name'] = $record['restaurant_name'];  
								  $result['rating'] = $average;  
								  $result['rating_given_count'] = ($average!=0)?'Based on '.$numberOfReviews.' Voters':'No Reviews given by any Voters';
								  $result['owner_name'] = $record['owner_name'];  
								  $result['restaurant_phone'] = $record['restaurant_phone'];  
								  $result['contact_name'] = $record['contact_name'];  
								  $result['contact_phone'] = $record['contact_phone'];  
								  $result['contact_email'] = $record['contact_email'];  
								  $result['country_code'] = $record['country_code'];  
								  $result['address'] = $record['address'];  
								  $result['street'] = $record['street'];  
								  $result['city'] = $record['city'];  
								  $result['state'] = $record['state'];  
								  $result['post_code'] = $record['post_code'];  
								  $result['service'] = $record['service'];  
								  $result['free_delivery'] = $record['free_delivery'];  
								  $result['delivery_estimation'] = $record['delivery_estimation'];  
								  $result['username'] = $record['username'];  
								  $result['password'] = $record['password'];
								  $result['activation_key'] = $record['activation_key'];  
								  $result['activation_token'] = $record['activation_token'];  
								  $result['status'] = $record['status'];  
								  $result['date_created'] = $record['date_created'];  
								  $result['date_modified'] = $record['date_modified'];  
								  $result['date_activated'] = $record['date_activated'];  
								  $result['last_login'] = $record['last_login'];  
								  $result['ip_address'] = $record['ip_address'];  
								  $result['package_id'] = $record['package_id'];  
								  $result['package_price'] = $record['package_price'];  
								  $result['membership_expired'] = $record['membership_expired']; 
								  $result['is_featured'] = $record['is_featured']; 
								  $result['is_ready'] = $record['is_ready']; 
								  $result['is_sponsored'] = $record['is_sponsored']; 
								  $result['sponsored_expiration'] = $record['sponsored_expiration']; 
								  $result['membership_purchase_date'] = $record['membership_purchase_date']; 
								  $result['sort_featured'] = $record['sort_featured']; 
								  $result['is_commission'] = $record['is_commission']; 
								  $result['percent_commision'] = $record['percent_commision']; 
								  $result['session_token'] = $record['session_token']; 
								  $result['commision_type'] = $record['commision_type']; 
								  $result['mobile_session_token'] = $record['mobile_session_token']; 
								  $result['merchant_key'] = $record['merchant_key']; 
								  $result['latitude'] = $record['latitude']; 
								  $result['lontitude'] = $record['lontitude']; 
								  $result['delivery_charges'] = $record['delivery_charges']; 
								  $result['minimum_order'] = $record['minimum_order']; 
								  $result['delivery_minimum_order'] = $record['delivery_minimum_order']; 
								  $result['delivery_maximum_order'] = $record['delivery_maximum_order']; 
								  $result['pickup_minimum_order'] = $record['pickup_minimum_order']; 
								  $result['country_name'] = $record['country_name']; 
								  $result['country_id'] = $record['country_id']; 
								  $result['state_id'] = $record['state_id']; 
								  $result['city_id'] = $record['city_id']; 
								  $result['area_id'] = $record['area_id']; 
								  $result['logo'] = $record['logo'];
								  $result['merchant_type'] = $record['merchant_type']; 
								  $result['invoice_terms'] = $record['invoice_terms'];
								  $result['distance'] = $kilometers."Km";
								  $result['approx_served_for_2'] = (!empty($this->get_merchant_meta_keyvalue($record['id'],'merchant_served_for_two_people')))?$this->get_merchant_meta_keyvalue($record['id'],'merchant_served_for_two_people'):'';
								  $result['merchant_login_status'] = (!empty($this->get_merchant_meta_keyvalue($record['id'],'merchant_login_status')))?strtolower($this->get_merchant_meta_keyvalue($record['id'],'merchant_login_status')):'off';
								  
								  $result['order_limit'] = (!empty($this->get_merchant_meta_keyvalue($record['id'],'order_limit')))?strtolower($this->get_merchant_meta_keyvalue($record['id'],'order_limit')):'off';
								  
								  $result['area_name'] = (!empty($this->get_merchant_meta_keyvalue($record['id'],'area_name')))?ucfirst($this->get_merchant_meta_keyvalue($record['id'],'area_name')):'';
						
								  $result['restro_start_time'] =  $restro_starttime;					  
								  $result['restro_end_time'] =  $restro_endtime;					
								  $result['restro_is_open_close'] =  $restro_open_close_status;
								  array_push($data,$result);
						}
					
				}
			 }
			 return $data;
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
	 public function get_categorywiseitemsfortesting($query,$category_type="all",$merchant_id)
    {
		$resArr = array(); //create the result array
        $j=0; $result = array();$data=array();$k=0; $arr=array();$result11=array();
		
	   $query11 = "SELECT * FROM `mt_item` WHERE `merchant_id`='$merchant_id' and `is_featured`='1' and LOWER(`status`) = LOWER('Active')";
       $result11 = $this->sidebar_query($query11);
               
               if(!empty($result11)){
				   for($k=0;$k<count($result11);$k++)
				   {
					   //print_r($result11);exit;
					   $arr = unserialize($result11[$k]['price']);
					   if(is_array($arr)){
						  foreach((array)$arr as $key=>$value)
						  {
							if(!empty($result11[$k]['price_type']) && strtolower($result11[$k]['price_type'])== strtolower('Single'))
							{
								$result11[$k][$key]= $value;
								
							}else{
									$query12 = "SELECT `size_name` FROM `mt_size` WHERE `id`='$key'";
									$result1 = $this->mysqli->query($query12); 
									$record11 = mysqli_fetch_assoc($result1);
									$size_name = $record11['size_name'];
									$result11[$k][$size_name]= $value;
								   // $k++;
							}
						  }
						 } 
						 unset($result[$k]['price']);
						 array_push($data,$result11);
						 $result11=array();
				
				   }
			   }else{
						 array_push($data,$result11);
						 //$result11=array();
			   }
      $mysql_query =  $this->mysqli->query($query);//mysqli_query($this->mysqli,$query); //query the db
      
      while($row = $mysql_query->fetch_assoc()) 
      { 
          $cat_id = $row['category_id'];
          $mer_id = $row['merchant_id'];
          $cat_name =  $this->mysqli->query("SELECT * FROM `mt_category` WHERE `id`='$cat_id'");
          $row = $cat_name->fetch_assoc();
          $resArr['cat_id'] =  $row['id'];
          $resArr['category_name'] =  $row['category_name'];
          $resArr['category_description'] =  w1250_to_utf8($row['category_description']);
          $resArr['photo'] =  $row['photo'];
          $resArr['status'] =  $row['status'];
          $resArr['sequence'] =  $row['sequence'];
          $resArr['date_created'] =  $row['date_created'];
          $resArr['date_modified'] =  $row['date_modified'];
          $resArr['spicydish'] =  $row['spicydish'];
          $resArr['spicydish_notes'] =  $row['spicydish_notes'];
          $resArr['dish'] =  $row['dish'];
          $resArr['category_name_trans'] =  $row['category_name_trans'];
          $resArr['category_description_trans'] =  $row['category_description_trans'];
          $resArr['parent_cat_id'] =  $row['parent_cat_id'];


          
			$i=0;
			$item_ids =  $this->mysqli->query("SELECT `item_id` FROM `mt_merchant_categories` where `category_id`='$cat_id' and `merchant_id`='$mer_id'");  
          while($result = mysqli_fetch_assoc($item_ids))
          {
              $item_id = $result['item_id'];
			  if($category_type=='all')
			  {
				$item_query = "SELECT `item_name`,`item_description`,`status`,`price`,`addon_item`,`cooking_ref`,`discount`,`is_featured`,`date_created`,`date_modified`,`ingredients`,`spicydish`,`two_flavors`,`two_flavors_position`,`require_addon`,`dish`,`price_type`,`item_name_trans`,`item_description_trans`,`not_available`,`points_earned`,`points_disabled`,`is_veg_nonveg`,`stock_status`,`gallery_photo`,`photo`,`price_type` from mt_item WHERE  `id`= '$item_id' and LOWER(`status`) = 'active' ORDER BY `item_name` ASC";
			  }else{
				  $item_query = "SELECT `item_name`,`item_description`,`status`,`price`,`addon_item`,`cooking_ref`,`discount`,`is_featured`,`date_created`,`date_modified`,`ingredients`,`spicydish`,`two_flavors`,`two_flavors_position`,`require_addon`,`dish`,`price_type`,`item_name_trans`,`item_description_trans`,`not_available`,`points_earned`,`points_disabled`,`is_veg_nonveg`,`stock_status`,`gallery_photo`,`photo` from mt_item WHERE  `id`= '$item_id' and LOWER(`status`) = 'active' and LOWER(`is_veg_nonveg`)=LOWER('$category_type') ORDER BY item_name ASC";  
			  }
              $item_name =  $this->mysqli->query($item_query);
              $record = mysqli_fetch_assoc($item_name);
			  if(!empty($record))
			  {  
				  $resArr['item'][$i]['item_id'] = $item_id; 
				  $resArr['item'][$i]['item_name'] = $record['item_name']; 
				  $resArr['item'][$i]['item_description'] = w1250_to_utf8($record['item_description']); 
				  $resArr['item'][$i]['status'] = $record['status']; 
				  $resArr['item'][$i]['addon_item'] = $record['addon_item']; 
				  $resArr['item'][$i]['cooking_ref'] = $record['cooking_ref']; 
				  $resArr['item'][$i]['discount'] = $record['discount']; 
				  $resArr['item'][$i]['is_featured'] = $record['is_featured']; 
				  $resArr['item'][$i]['date_created'] = $record['date_created']; 
				  $resArr['item'][$i]['date_modified'] = $record['date_modified']; 
				  $resArr['item'][$i]['ingredients'] = $record['ingredients']; 
				  $resArr['item'][$i]['spicydish'] = $record['spicydish'];
				  $resArr['item'][$i]['two_flavors'] = $record['two_flavors']; 
				  $resArr['item'][$i]['two_flavors_position'] = $record['two_flavors_position'];
				  $resArr['item'][$i]['require_addon'] = $record['require_addon'];
				  $resArr['item'][$i]['dish'] = $record['dish'];
				  $resArr['item'][$i]['item_name_trans'] = $record['item_name_trans'];
				  $resArr['item'][$i]['not_available'] = $record['not_available'];
				  $resArr['item'][$i]['points_earned'] = $record['points_earned'];
				  $resArr['item'][$i]['points_disabled'] = $record['points_disabled'];
				  $resArr['item'][$i]['is_veg_nonveg'] = $record['is_veg_nonveg'];
				  $resArr['item'][$i]['images'] = $record['photo'];
				  $resArr['item'][$i]['stock_status'] = $record['stock_status'];
				  $resArr['item'][$i]['price_type'] = $record['price_type'];
				  $arr = unserialize($record['price']);
				  if(is_array($arr)){
					  foreach((array)$arr as $key=>$value)
					  {
					    if(!empty($record['price_type']) && strtolower($record['price_type'])== strtolower('Single'))
						{
							$resArr['item'][$i][$key]= $value;
							
						}else{
								$query12 = "SELECT `size_name` FROM `mt_size` WHERE `id`='$key'";
								$result1 = $this->mysqli->query($query12); 
								$record11 = mysqli_fetch_assoc($result1);
								$size_name = $record11['size_name'];
								$resArr['item'][$i][$size_name]= $value;
							   // $k++;
						}
					  }
					 } 
				
				  $i++;
			  }
			  			
          }
		  
		  
		  
	      array_push($data,$resArr);
          $j++;
          $resArr=array();
         
      }
	  
       //print_r($data);exit;
      return $data;

    }
}
$db = new DB();
  //$db->db_num("SELECT * FROM mt_client");
 // UPDATE mt_merchant SET `last_login` = '1970-01-01 08:00:00' WHERE CAST(`last_login` AS CHAR(20)) = '0000-00-00 00:00:00'
 //SET GLOBAL sql_mode = ''
?>