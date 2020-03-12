/*class demoMiddleware
{

    public function __invoke(\Slim\Http\Request $request,\Slim\Http\Response $response,$next)
    {

    		$response->write('<h1>Hiiiiii</h1>');
    		$response= $next($request,$response);
    		$response->write('<h1>Pratik</h1>');
    		return $response;
    }




}*/

/*class Database 
{
	public function __construct(){
            
             $mysqli =new mysqli('localhost','root','','dbstudent');
				if ($mysqli->connect_error)
				{
				    die("Connection failed: " . $mysqli->connect_error);
				} 
				return $mysqli;
			
			
		}
	public function query($sql)
	{
		$result=$this->get('mysqli')->query($sql);
		//$result=$mysqli->query($sql);
				    while($row=$result->fetch_assoc())
				    {
				    	$post[]=$row;
				    }
            return $post;
	}
}
$db = new Database;*/
/*$container['db'] = function ($container) {
return new Database($container->mysqli);
};*/
//$app->add(new demoMiddleware());

$app->get('/abcd/{name}', function ($request, $response,$args) {
	$name = $request->getAttribute('name');
    echo $name = $args['name'];
    print_r($args);
    $anme=$request->getParams();
    //$name=$anme['name'];
    $response->getBody()->write("Hello".$args['name']);
    return $response;
});
/*$app->get('/books/{name}', function ($request, $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello there, $name");

    return $response;
});*/
/*$num = $ratelimiter->calc();

if($num > 60){
    $app->halt(429,'Too Many Requests');
}*/
// $corsOptions = array(
// 		    "origin" => "*",
// 		    "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
// 		    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
// 		);
// $cors = new \CorsSlim\CorsSlim($corsOptions);