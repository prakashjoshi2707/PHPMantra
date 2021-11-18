<?php

namespace Core;

use App\Config;
use libs\Session;

/**
 * View
 *
 * PHP version 5.4
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     *
     * @return void
     */
    public static $twig = null;
    public static function render($view, $args=[])
    {
        extract($args, EXTR_SKIP);
        $file = "App/Views/$view";  // relative to Core directory
       
        if (is_readable($file)) {
            require $file;
        } else {
            //echo "$file not found";
            throw new \Exception("$file not found");
        }
    }
    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        Session::init();
        $user=Session::get('USERNAME');
        $userType=Session::get('USERTYPE');
       
        if (self::$twig === null) {
            $loader = new \Twig_Loader_Filesystem('App/Views');
            self::$twig = new \Twig_Environment($loader);
            self::$twig->addGlobal("URL", URL);
            self::$twig->addGlobal("USERNAME", $user);
            self::$twig->addGlobal("USERTYPE", $userType);
        }
        
        echo self::$twig->render($template, $args);
    }
    public static function addGlobal($key, $value)
    {
        self::$twig->addGlobal($key, $value);
    }
}
