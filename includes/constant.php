<?php

define('SECRETE_KEY','placcefy'.md5(uniqid(rand(), true)));
define('BOOLEAN', '1');
define('INTEGER', '2');
define('STRING', '3');

define('REQUEST_METHOD_NOT_VALID',                100);
define('REQUEST_CONTENTTYPE_NOT_VALID',		101);
define('REQUEST_NOT_VALID',			102);
define('VALIDATE_PARAMETER_REQUIRED',	          103);
define('VALIDATE_PARAMETER_DATATYPE',		104);
define('API_NAME_REQUIRED',			105);
define('API_PARAM_REQUIRED',			106);
define('API_DOES_NOT_EXIXT',			107);
define('INVALID_USER_PASS',			108);
define('USER_NOT_ACTIVE',			109);
define('SUCCESS_RESPONSE',			200);
define('NO_CONTENT',			204);
define('BAD_REQUEST',			          400);
define('CONFLICT',			          409);


/*Servers Errors*/
define('ATHORIZATION_HEADER_NOT_FOUND',300);
define('ACCESS_TOKEN_ERRORS',301);
define('JWT_PROCESSING_ERROR',302);
define('Delivery_Boy_Check_Count',15);
define('default_delivery_boy_id','cae497f8-6c52-f3df-85d8-5e788d272a47');
define('LOG_PATH','placeefyAPI/../../API_Logs/');
define('GATEWAY','android');
define('SITE_URL','http://www.vrcompanies.in/placeefy/');
define('UPLOAD_URL','http://www.vrcompanies.in/placeefy/uploads/');
define('CUSTOMER_IMAGE_PATH','http://vrcompanies.in/placeefyAPI/app/api/images/');
//set all API keys 
$config = array();
$config['api_log_file_path'] = basename(dirname(__DIR__)) . '/../../API_Logs/';

?>