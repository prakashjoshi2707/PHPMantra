<?php

        /**
     * Front controller
     *
     * PHP version 7.1
     */

    require_once __DIR__.'/vendor/autoload.php';
    define("URL", 'http://'.$_SERVER['SERVER_NAME'].'/');
    define("URL_UPLOAD", __DIR__.'/public/');
    define("URL_SERVER", __DIR__.'/');
    define('URL_CACHE', __DIR__.'/cache');
    
    // error reporting (this is a demo, after all!)
   // ini_set('display_errors',1);error_reporting(E_ALL);
    date_default_timezone_set('Asia/Kolkata');

    /**
     * Autoloader to load class
     * from parent directory
     */
    spl_autoload_register(function ($class) {
        $root = __DIR__;   // get the parent directory
        $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
        if (is_readable($file)) {
            require $root . '/' . str_replace('\\', '/', $class) . '.php';
        }
    });

    /**
     * Error and Exception handling
     *
     */
     error_reporting(E_ALL);
     set_error_handler('Core\Error::errorHandler');
     set_exception_handler('Core\Error::exceptionHandler');


     $router = new Core\Router();

     // Add the routes
     $router->add('', ['controller' => 'Starter', 'action' => 'show']);
     $router->add('donation', ['controller' => 'Donation', 'action' => 'index']);
     $router->add('{controller}/{action}');
     $router->add('{controller}/{id:\d+}/{action}');
     $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
     
     $router->dispatch($_SERVER['QUERY_STRING']);
    //  echo "<pre>";
    //  var_dump($router);
    //  echo "</pre>";
