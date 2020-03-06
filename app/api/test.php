<?php

$app->group('/api', function(\Slim\App $app) {
$app->map(['POST'],'/homeScreen1', function( $request,$response,$args) {
  try {
                require '../includes/DBOperations.php';
                $latitude =  $request->getParam('latitude');
                $longitude =  $request->getParam('longitude');
                $client_id =  $request->getParam('client_id');
                
                
                //validate parameters
                $latitude = $rest->validateParameter('latitude', $latitude, INTEGER);
                $longitude = $rest->validateParameter('longitude', $longitude, INTEGER);
                 
                $datetime = date("Y-m-d H:i:s");
                $user_ip = getUserIP();
                $homescreen = array();$blank_arr =  array();
                $dynamic_ui_code = array();
                $childarr = array();
                
                // Root Code start here
                $fetch_root_code = "SELECT `section_card_ui_code` FROM `ply_app_management` WHERE `section_type`='Root' and deleted=0 and status='active'";
                if($db->db_num($fetch_root_code))
                 {
                    $app_mgmt_query =  $db->mysqli->query($fetch_root_code);
                    $row11 = mysqli_fetch_assoc($app_mgmt_query);
                    $root_code = $db->convert_jsonObject_into_arrray($row11['section_card_ui_code']);
                    foreach ($root_code as $key => $value) {
                        $homescreen['dashboard_view'][$key] = $value;
                       
                    }
                    
                    
                 }

                // Root code end here
                 $children = '    
                        {
                          "type": "LinearLayout",
                          "layout_width": "match_parent",
                          "layout_height": "wrap_content",
                          "orientation": "vertical"
                        }
                        ';
                    
                $childarr = json_decode($children,TRUE);

                $fetch_seq_wise_code = "SELECT sequence_no,section_used_for_c,section_type FROM `ply_app_management` a join `ply_app_management_cstm` b on a.id=b.id_c WHERE a.deleted=0  and `sequence_no`!=0 and status='active' order by `sequence_no`";
                if($db->db_num($fetch_seq_wise_code))
                 {
                        
                   
                        $mysql_query =  $db->mysqli->query($fetch_seq_wise_code);
                        while($row = mysqli_fetch_assoc($mysql_query)) 
                        {
                            $section_used_type = $row['section_used_for_c'];
                            $ui_code = $db->convert_jsonObject_into_arrray($db->get_sequence_wise_code($row['sequence_no']));
                            foreach ($ui_code as $key => $value) 
                            {
                                $dynamic_ui_code[][$key] = $value;
                            } 
                        }
                }
                    
               
                    // First code for offer start
                     $offer_qry = "SELECT `id`,`name`,`date_entered`,`description`,`offer_from`,`offer_to`,`offer_type`,`offer_amount_discount`,`offer_given_by`,`offer_status`,`offer_avail_count`,`offer_expenditure`,`offer_for`,`offer_applicable_over_amount`,`offer_display_type`,`offer_upto_c` FROM `ply_offer` as A inner join `ply_offer_cstm` as B on A.id=B.id_c WHERE A.deleted=0 and `offer_status`='active'";
                            //end
                        if($db->db_num($offer_qry))
                        {
                            $parent_type = 'ply_Offer';
                            $mysql_query =  $db->mysqli->query($offer_qry);
                            $resArr = array(); 
                            while($row1 = mysqli_fetch_assoc($mysql_query)) { 
                                $resArr[] = $row1; 
                            }
                             for($i=0;$i<count($resArr);$i++)
                            {
                                $resArr[$i]['image'] = $db->get_image_from_notes_module($resArr[$i]['id'],$parent_type);
                            }
                            //$homescreen['dashboard_data']['offer']=$resArr;
                           
                        }
                   
                   $a = array(
                                
                               "type"=>$homescreen['dashboard_view']['type'],
                               "layout_width"=>$homescreen['dashboard_view']['layout_width'],
                               "layout_height"=>$homescreen['dashboard_view']['layout_height'],
                            	'children'=> array(
					                               array(
						                                        
						                                 "type"=>$childarr['type'],
						                                  "layout_width"=>$childarr['layout_width'],
						                                  "layout_height"=>$childarr['layout_height'],
						                                   "orientation"=>$childarr['orientation'],
							                                "children"=>$dynamic_ui_code
							                               )
					                                )
                        	);
                                    		
                        $output = array(
                        'dashboard_view' => $a,
                        'dashboard_data' => array(
							        "offer"=>$resArr,
							        'sponsored_kitchens'=>$db->get_kitchen('Sponsered','yes',$latitude,$longitude),
							        'newest_kitchen'=>$db->get_kitchen('new','no',$latitude,$longitude),
							        'meal_of_the_day'=>$db->get_all_todays_special_menus($latitude,$longitude),
							        'top_rated_kitchens'=>$db->get_kitchen('top_rated','no',$latitude,$longitude),
							        'trending_chef'=>$db->get_all_vendors()
                                			)
                        				);

                    // $res = $db->get_all_todays_special_menus($latitude,$longitude);
                    // print_r($res);
                    // exit; 
 
            if(!empty($homescreen))    
                return $this->response->withJson(['statuscode' => SUCCESS_RESPONSE, 'responseMessage' => true, 'result'=>$homescreen]);
            else
                return $this->response->withJson(['statuscode' => NO_CONTENT, 'responseMessage' => false, 'result'=>'Sorry somthing goes wrong']);

  } catch (ResourceNotFoundException $e) { 
  $app->response()->status(404);
} 
});


});
