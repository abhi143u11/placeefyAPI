<?php

function valid_email($str) {
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }
    
function validate_mobile($mobile)
    {
        return (!preg_match('/^[0-9]{10}+$/', $mobile)) ? FALSE : TRUE;
    }    
function validateToken()
{
    if(defined('SECRETE_KEY')) {
        return true;
    }
    else {
      return false;
    }
}

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


function escape_string($value)
{
    return $this->connection->real_escape_string($value);
}
function is_age_valid($age)
{
        //if (is_numeric($age)) {
        if (preg_match("/^[0-9]+$/", $age)) {    
            return true;
        } 
        return false;
}
function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
}
function get_meters_between_points($latitude1, $longitude1, $latitude2, $longitude2) {
    if (($latitude1 == $latitude2) && ($longitude1 == $longitude2)) { return 0; } // distance is zero because they're the same point
    $p1 = (float)deg2rad($latitude1);
    $p2 = (float)deg2rad($latitude2); 
    $dp = (float)deg2rad($latitude2 - $latitude1);
    $dl = (float)deg2rad($longitude2 - $longitude1);
    $a = (float)(sin($dp/2) * sin($dp/2)) + (cos($p1) * cos($p2) * sin($dl/2) * sin($dl/2));
    $c = (float)2 * atan2(sqrt($a),sqrt(1-$a));
    $r = 6371008; // Earth's average radius, in meters
    $d = (float)$r * $c;
    return $d; // distance, in meters
}

function get_distance_between_points($latitude1, $longitude1, $latitude2, $longitude2) {
    $meters = get_meters_between_points($latitude1, $longitude1, $latitude2, $longitude2);
    $kilometers = $meters / 1000;
    $miles = $meters / 1609.34;
    $yards = $miles * 1760;
    $feet = $miles * 5280;
    return compact('miles','feet','yards','kilometers','meters');
}
function push_elements($ele=array())
{
    $cart = array();
    array_push($cart, $ele);
    return $cart;
}
function w1250_to_utf8($text) {
   
    $map = array(
        chr(0x8A) => chr(0xA9),
        chr(0x8C) => chr(0xA6),
        chr(0x8D) => chr(0xAB),
        chr(0x8E) => chr(0xAE),
        chr(0x8F) => chr(0xAC),
        chr(0x9C) => chr(0xB6),
        chr(0x9D) => chr(0xBB),
        chr(0xA1) => chr(0xB7),
        chr(0xA5) => chr(0xA1),
        chr(0xBC) => chr(0xA5),
        chr(0x9F) => chr(0xBC),
        chr(0xB9) => chr(0xB1),
        chr(0x9A) => chr(0xB9),
        chr(0xBE) => chr(0xB5),
        chr(0x9E) => chr(0xBE),
        chr(0x80) => '&euro;',
        chr(0x82) => '&sbquo;',
        chr(0x84) => '&bdquo;',
        chr(0x85) => '&hellip;',
        chr(0x86) => '&dagger;',
        chr(0x87) => '&Dagger;',
        chr(0x89) => '&permil;',
        chr(0x8B) => '&lsaquo;',
        chr(0x91) => '&lsquo;',
        chr(0x92) => '&rsquo;',
        chr(0x93) => '&ldquo;',
        chr(0x94) => '&rdquo;',
        chr(0x95) => '&bull;',
        chr(0x96) => '&ndash;',
        chr(0x97) => '&mdash;',
        chr(0x99) => '&trade;',
        chr(0x9B) => '&rsquo;',
        chr(0xA6) => '&brvbar;',
        chr(0xA9) => '&copy;',
        chr(0xAB) => '&laquo;',
        chr(0xAE) => '&reg;',
        chr(0xB1) => '&plusmn;',
        chr(0xB5) => '&micro;',
        chr(0xB6) => '&para;',
        chr(0xB7) => '&middot;',
        chr(0xBB) => '&raquo;',
    );
    return html_entity_decode(mb_convert_encoding(strtr($text, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
}  
function replaceKeys($oldKey, $newKey, array $input){
    $return = array(); 
    foreach ($input as $key => $value) {
        if ($key===$oldKey)
            $key = $newKey;

        if (is_array($value))
            $value = replaceKeys( $oldKey, $newKey, $value);

        $return[$key] = $value;
    }
    return $return; 
}

function sum_array($arr){
    $count = 0;
     foreach ($arr as $val){
       if (!is_numeric($val)) // neglect any non numeric values
         {
           $error = true;
           continue;
         }
         else{
           $count = $count + ($val*1); //casting to numeric if the value supplied as string
         }
     }
     return $count;
   }

   function get_details_from_address($address)
   {
    $geo = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key=AIzaSyACOEs_MkENA0eOeUQonJsWmjeik2caw7M');
    $jsondata = json_decode($geo, true); // Convert the JSON to an array
    
    // if (isset($geo['status']) && ($geo['status'] == 'OK')) {
    //   $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
    //   $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
    // }
    // return (['Lat'=>$latitude,'Long'=>$longitude]);
    if (!check_status($jsondata))   return array();

$address = array(
    'country' => google_getCountry($jsondata),
    'province' => google_getProvince($jsondata),
    'city' => google_getCity($jsondata),
    'street' => google_getStreet($jsondata),
    'postal_code' => google_getPostalCode($jsondata),
    'country_code' => google_getCountryCode($jsondata),
    'formatted_address' => google_getAddress($jsondata),
    'lat' => $jsondata['results'][0]['geometry']['location']['lat'],
    'long' => $jsondata['results'][0]['geometry']['location']['lng']
);

return $address;
}

/* 
* Check if the json data from Google Geo is valid 
*/

function check_status($jsondata) {
    if ($jsondata["status"] == "OK") return true;
    return false;
}

/*
* Given Google Geocode json, return the value in the specified element of the array
*/

function google_getCountry($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"]);
}
function google_getProvince($jsondata) {
    return Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["results"][0]["address_components"], true);
}
function google_getCity($jsondata) {
    return Find_Long_Name_Given_Type("locality", $jsondata["results"][0]["address_components"]);
}
function google_getStreet($jsondata) {
    return Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]) . ' ' . Find_Long_Name_Given_Type("route", $jsondata["results"][0]["address_components"]);
}
function google_getPostalCode($jsondata) {
    return Find_Long_Name_Given_Type("postal_code", $jsondata["results"][0]["address_components"]);
}
function google_getCountryCode($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"], true);
}
function google_getAddress($jsondata) {
    return $jsondata["results"][0]["formatted_address"];
}

/*
* Searching in Google Geo json, return the long name given the type. 
* (If short_name is true, return short name)
*/

function Find_Long_Name_Given_Type($type, $array, $short_name = false) {
    foreach( $array as $value) {
        if (in_array($type, $value["types"])) {
            if ($short_name)    
                return $value["short_name"];
            return $value["long_name"];
        }
    }
}

/*
*  Print an array
*/

function d($a) {
    echo "<pre>";
    print_r($a);
    echo "</pre>";
}

// AS per client change request in delivery charges logic has been changed on 15-10-2019
/* function calculate_delivery_charger($distance,$units,$price,$from=null,$to=null)
{
    
   if($distance >=0  && $distance <=5 && $units=='km')
   {
          
         $final_p = $price;
         return $final_p;
   }else if($distance > 5 && $distance <=10 && $units=='km')
   {
   
            $final_p = 30 + $price;
             return $final_p;
   }
   else if($distance > 10)
   {
      
        $km = $distance - 10;
        $final_p = 60 + ($km * 10);
        return $final_p;
   }
   
}
 */
function calculate_delivery_charger($distance,$units,$price,$from=null,$to=null)
{
   $fixed_km = 4;
   if($distance >=0  && $distance <=$fixed_km && $units=='km')
   {
          
         $final_p = $price;
         return $final_p;
   }
   else if($distance > 4)
   {
      
        $km = number_format((float)$distance, 2, '.', '');
		//echo "KM-".$km." "."fixed Km-".$fixed_km;exit;
        $final_p = 30 + (($km - $fixed_km) * 10);
        return $final_p;
   }
   
}
function check_merchant_available($dilevery_time,$start,$end,$is_open_close)
{
	//echo $dilevery_time;echo "<br>"; echo $start; echo "<br>"; echo $end;echo "<br>";echo $is_open_close;exit;
  if($is_open_close=='open')
  {
    $start_time = strtotime($start);
    $end_time = strtotime($end);
    if(strtotime($dilevery_time)>=$start_time && strtotime($dilevery_time)<=$end_time)
    {
        return true;
    }else{
        return false;
    }
  }else{
      return false;
  }
}
function getGuid(){
    $charid = md5(uniqid(rand(),true));
    $hyphen = chr(45);
    $uuid =  substr($charid,0,8).$hyphen
            .substr($charid,8,4).$hyphen
            .substr($charid,12,4).$hyphen
            .substr($charid,16,4).$hyphen
            .substr($charid,20,12);
    return $uuid;
}
function sendsms($mobileno, $message)
          {
                    if(isset($mobileno) && isset($message)){
                              
                              $username = urlencode("8830828911");
                              //$msg_token = urlencode("slon1K");
                              $sender_id = urlencode("PLACFY");
                              $message = urlencode($message);
                              $mobile = urlencode($mobileno);
                              //$baseurl = 'http://103.238.223.66/api/send_transactional_sms.php?username='.$username;
                              
                              //$api = "http://103.238.223.66/api/send_transactional_sms.php?username=".$username."&msg_token=".$msg_token."&sender_id=".$sender_id."&message=".$message."&mobile=".$mobile."";  

                              $api = "http://login.aquasms.com/sendSMS?username=".$username."&message=".$message."&sendername=".$sender_id."&smstype=TRANS&numbers=".$mobile."&apikey=4fb59656-e030-4b9f-96ee-942546929647";
                            
                             
                                // echo $url = $baseurl.'&msg_token='.$msg_token.'&sender_id='.$sender_id.'&message='.$message.'&mobile='.$mobileno;
                               // echo "<br/>";  
                             // echo $response = file_get_contents($api); 
                              //echo "response=".$response;

                              $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $api); 
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                                $output = curl_exec($ch);   

                                // convert response
                                $output = json_decode($output);

                                // handle error; error output
                                if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {

                                  //var_dump($output);
                                     $APILogFile = basename(dirname(__DIR__)) . '/../../API_Logs/SMSSEND_RESPONSE.txt';
                                     $handle = fopen($APILogFile, 'a');
                                     $timestamp = date('Y-m-d H:i:s');
                                     $logArray1 = print_r($output, true);
                                     $logMessage1 = "\nSMSSEND_RESPONSE Result at $timestamp :-for $mobileno is:";
                                     $logMessage = "\nSMSSEND_RESPONSE Result at $timestamp :-\n$logArray1";
                                     fwrite($handle, $logMessage1);    
                                     fwrite($handle, $logMessage);             
                                     fclose($handle);
                                }else{
                                     $APILogFile = basename(dirname(__DIR__)) . '/../../API_Logs/SMSSEND_RESPONSE.txt';
                                     $handle = fopen($APILogFile, 'a');
                                     $timestamp = date('Y-m-d H:i:s');
                                     $logArray1 = print_r($output, true);
                                      $logMessage1 = "\nSMSSEND_RESPONSE Result at $timestamp :-for $mobileno is:";
                                      $logMessage = "\nSMSSEND_RESPONSE Result at $timestamp :-\n$logArray1";
                                     fwrite($handle, $logMessage1);    
                                     fwrite($handle, $logMessage);                 
                                     fclose($handle);

                                }

                                curl_close($ch);
                             
                              return true;
                    } 
          }
// Function to generate OTP 
function generateNumericOTP($n) { 
      
    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468"; 
  
    // Iterate for n-times and pick a single character 
    // from generator and append it to $result 
      
    // Login for generating a random character from generator 
    //     ---generate a random number 
    //     ---take modulus of same with length of generator (say i) 
    //     ---append the character at place (i) from generator to result 
  
    $result = ""; 
  
    for ($i = 1; $i <= $n; $i++) { 
        $result .= substr($generator, (rand()%(strlen($generator))), 1); 
    } 
  
    // Return result 
    return $result; 
} 
?>