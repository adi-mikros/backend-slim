<?php
// Application middleware
 
// e.g: $app->add(new \Slim\Csrf\Guard);
use Tuupola\Middleware\HttpBasicAuthentication;

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container["jwt"] = function ($container) {
    return new StdClass;
};



$app->add(new \Slim\Middleware\JwtAuthentication([
	"path" => "/androapi",
    "logger" => $container['logger'],
    "secret" => "jlkwdfhasljkl324wflqwjwklj234j23423jkljkll",
	"secure" => false,
    "rules" => [
        new \Slim\Middleware\JwtAuthentication\RequestPathRule([
            "path" => "/",
            "passthrough" => ["/token", "/not-secure", "/home"]
        ]),
        new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
            "passthrough" => ["OPTIONS"]
        ]),
    ],
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    },
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/androapi/token",
    "users" => [
        "user" => "password"
    ]
]));


#middleware untuk CORS
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});



/*
// Application middleware
 use \Slim\Middleware\JwtAuthentication;
// e.g: $app->add(new \Slim\Csrf\Guard);
$app->add(new JwtAuthentication([
    "attribute" => "decoded_token_data",
    "secret" => "akuadalahgiantgiantsangjagon1987kaloberjalantoktok",
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));


#middleware untuk CORS
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

*/