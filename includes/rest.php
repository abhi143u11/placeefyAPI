
<?php
class Rest
{
    
          public $dbconn;
          public $mobileno;
          public $message;
          public $number;
          public $arr1 = array();
          public $att_phone = array();
          public $n_phone = '';

         
         // function getPostId(){ if(isset($_GET['post_id'])){return $_GET['post_id'];}}
         public function __construct()
         {
                             if($_SERVER['REQUEST_METHOD'] !=='POST'){
                                       $this->throwError(REQUEST_METHOD_NOT_VALID,"Request method is not valid");
                             }

                    //          $handler = fopen('php://input','r');
                    //          $this->request = stream_get_contents($handler); //how to access request 
                    //          $this->ValidateRequest($this->request);

         }

          
          function returnResponse($code,$data)
          {
                    header("Access-Control-Allow-Origin: *");
                    header("Content-Type: application/json; charset=UTF-8");
                    header("Content-type: application/x-www-form-urlencoded");
                    header("Access-Control-Allow-Methods: POST");
                    header("Access-Control-Max-Age: 3600");
                    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
                    //$response = json_encode(['response'=>['status'=>$code, "result"=>$data]]);
                    $postvalue['responseStatus'] = $code;
		          //$postvalue['responseMessage'] = "OK";
		          $postvalue['result'] = $data;
                    echo json_encode($postvalue,JSON_PRETTY_PRINT|JSON_FORCE_OBJECT);
                    exit;

          }
          
          function validateParameter($feildName, $value, $datatype, $required = true){ 
		if($required == true && empty($value) == true){

			$this->throwError(VALIDATE_PARAMETER_REQUIRED,$feildName." parameter is required");

		}
		switch($datatype)
		{
			case BOOLEAN:
						if(!is_bool($value)){
							$this->throwError(VALIDATE_PARAMETER_DATATYPE," Datatype is not valid for ".$feildName.'.It shoud be boolean');
						}
			case INTEGER:
						if(!is_numeric($value)){
							$this->throwError(VALIDATE_PARAMETER_DATATYPE," Datatype is not valid for ".$feildName.'.It shoud be numeric');
						}
			case STRING:
						if(!is_string($value)){
							$this->throwError(VALIDATE_PARAMETER_DATATYPE," Datatype is not valid for ".$feildName.'.It shoud be string');
						}
			break;

			default:
			#code
			break;

			

		}
		return $value;
          }
          function throwError($code,$message)
          {
          
                    echo json_encode(['statuscode'=>$code,'responseMessage'=>$message]);
                    exit;
            
          }

}
$rest = new Rest;
?>