<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        "db" => [            
            "host" => "localhost",             
            "dbname" => "palceefy_crm",             
            "user" => "root",            
            "pass" => ""        
        ],
        // jwt settings
        "jwt" => [
            'secret' => 'supersecretkeyyoushouldnotcommittogithub'
        ],
    ],
];
