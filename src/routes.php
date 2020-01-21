<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->map(['GET', 'POST'],'/login', function (Request $request, Response $response, array $args) {
  
        return $response->withJson(["hi"=>"hello"]);
    //     $input = $request->getParsedBody();
    //     $sql = "SELECT * FROM login WHERE username = :email";
    //     $sth = $this->db->prepare($sql);
    //     $sth->bindParam("username", $input['email']);
    //     $sth->execute();
    //     $user = $sth->fetchObject();
      
    //     // verify email address.
    //     if(!$user) {
    //         return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
    //     }
      
    //     // verify password.
    //     if (!password_verify($input['password'],$user->password)) {
    //         return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
    //     }
      
    //     $settings = $this->get('settings'); // get settings array.
      
    //     $token = JWT::encode(['id' => $user->id, 'email' => $user->email], $settings['jwt']['secret'], "HS256");
      
    //     return $this->response->withJson(['token' => $token]);
      
  });
  
};