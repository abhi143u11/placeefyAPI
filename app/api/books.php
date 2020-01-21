<?php
 $app->get('/api/merchant', function() use ($app){
echo "welcome my first api";

	//('../app/api/dbconnect.php');
    $query="SELECT * from mt_merchant";
    $result=$this->get('mysqli')->query($query);
    while($row=$result->fetch_assoc())
    {
    	$post[]=$row;
    }
    if(isset($post))
    {
	    echo Response("false",200,$post);
	    // 

	    //$app->stop();
	}
	else{
			echo Response("false",404,"Not Found");
			//$app->stop();
	}	

});

//display single records
$app->get('/api/merchant/{id}', function(\Slim\Http\Request $request,\Slim\Http\Response $response,$args) use ($app){
try {
			/*$nameKey = $this->csrf->getTokenNameKey();
		    $valueKey = $this->csrf->getTokenValueKey();
		    $name = $req->getAttribute($nameKey);
		    $value = $req->getAttribute($valueKey);*/


			$id = $request->getAttribute('id');
			if(isset($id))
			{
				
				$query="SELECT * from tblbooks where id='$id'";
					//$db = new Database;
				  // $post=$db->query($query);
				   //$post=$this->get('mysqli')->query($query);
				   $post=$this->get('mysqli')->query($query);
				    if(isset($post))
				    {
					   //$newResponse = $response->withJson($post, 201);
				    	
				    	
				    	//$response->withHeader('Content-type', 'application/json');
				        //echo $response->withJson($post, 201);
				        echo Response("false",200,$post);
				       // $app->stop();
				    }
					
					else{
						echo Response("false",404,"Not Found");
					}
			}else
			{
				echo Response("true",1000,"Invalid ID");
			}
	} catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	



});

$app->post('/api/books/{id}', function ($request, $response, $args) {
    // Show book identified by $args['id']

try {
			$id = $request->getAttribute('id');
			if(isset($id))
			{
					//require_once('../app/api/dbconnect.php');
				    $query="SELECT * from tblbooks where id='$id'";
				    $result=$this->get('mysqli')->query($query);
				    while($row=$result->fetch_assoc())
				    {
				    	$post[]=$row;
				    }
				    if(isset($post))
				    {
					    
				        echo Response("false",200,$post);
				       // $app->stop();
				    }
					
					else{
							echo Response("false",404,"Not Found");
					}
			}else
			{
				echo Response("true",1000,"Invalid ID");
			}
	} catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	



});

// $container = $app->getContainer();
//   $container['csrf'] = function ($c) {
// 	  return new \Slim\Csrf\Guard;
//   };
//$app->group('/api', function(\Slim\App $app) {

$app->map(['GET','POST'],'/api/create', function( $request,$response,$args) {
	try {

       
		//$settings = $this->get('settings');
		//$token = JWT::encode(['id' => '1', 'email' => 'pratik@gmail.com'], $settings['jwt']['secret'], "HS256");
 
    		//return $this->response->withJson(['token' => $token]);
		//$input = $request->getParsedBody();
		//print_r($input);
		//echo $username1 = $allPostPutVars['pratik'];
		//echo $username = $request->getParam('pratik');
		//$method = $request->getMethod();
		//$request->isPost()
		//return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
			  //print_r($request);
			//$value = json_decode($request->getBody());
			//print_r($value);
			//$headers = $app->$request->getHeaders();
			//print_r($headers);
			  //echo $session = $request->getAttribute('pratik');
			  //echo $name = $args['pratik'];

			  //$data = json_decode($request->getParsedBody());
			 // var_dump($data);
			  //$data = json_decode($allPostPutVars, true);
			  //echo $username1 = $allPostPutVars['pratik'];
			 
			  // Generate new tokens
			  session_start();
				$slimGuard = new \Slim\Csrf\Guard;
				print_r($slimGuard);
				$slimGuard->validateStorage();
			    $nameKey = $slimGuard->getTokenNameKey();
			    $valueKey = $slimGuard->getTokenValueKey();
			    $name1 = $request->getAttribute($nameKey);//$request->getParam($nameKey);//$request->getAttribute($nameKey);
			    $value1 = $request->getAttribute($valueKey);//$request->getParam($valueKey);//$request->getAttribute($valueKey);
			  
			  //$keyPair = $slimGuard->generateToken();
			  //print_r($keyPair);
			   if($slimGuard->validateToken($name1,$value1)){
			   echo 'jjj';}
				 
		
	} catch (ResourceNotFoundException $e) { 
		$app->response()->status(404);
  }	
});
//});
$app->group('/api', function(\Slim\App $app) {
	$app->map(['GET'],'/token', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) use ($app) {
		session_start();
		$slimGuard = new \Slim\Csrf\Guard;
		$slimGuard->validateStorage();
		$container = $app->getContainer();
	
		$container["csrf"] = function ($container) {
		$guard = new \Slim\Csrf\Guard();
		$guard->setPersistentTokenMode(true);
		return $guard;
	};
	// Generate new token and update request
	$request = $container->csrf->generateNewToken($request);
	// Build Header Token
	$nameKey = $container->csrf->getTokenNameKey();
	$valueKey = $container->csrf->getTokenValueKey();
	$name = $request->getAttribute($nameKey);
	$value = $request->getAttribute($valueKey);
	$tokenArray = [
		$nameKey => $name,
		$valueKey => $value
	];
	
	$respCSRF["success"] = false;
	if (!empty($tokenArray)) {
		$respCSRF["success"] = true;
		$respCSRF["csrf"] = $tokenArray;
	}
	
	return $response->withJson($respCSRF);

});
});
function Response($errortype,$statuscode,$data=null)
{
			
		header('Content-Type:application/json');
		header('Content-type: application/x-www-form-urlencoded');
	    $response["error"] = $errortype;
        $response["message"] = "OK";
        $response["statuscode"] = $statuscode;
        $response["response"] = $data;
        return json_encode($response);

}