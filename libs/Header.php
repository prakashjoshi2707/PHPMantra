<?php
namespace libs;

class Header
{
    public static function JSONReadable()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
    }
    public static function setLocation($location)
    {
        header("Location:".$location);
    }
}
