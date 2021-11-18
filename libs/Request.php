<?php
  namespace libs;
  /**
   *
   */
  class Request
  {
    public $contentType;
    public $method;
    public $input;
    public $file;
    public $queryURL;
    public $controller;
    
    public function __construct()
    {
      // $this->queryURL=explode('/',trim($_SERVER['QUERY_STRING']))[1];
      $this->controller=explode('/',trim($_SERVER['QUERY_STRING']))[0];
      $this->contentType=isset($_SERVER["CONTENT_TYPE"])?$_SERVER["CONTENT_TYPE"]:"html";
      if($this->contentType=="application/json"){
        $JSONRequest=json_decode(file_get_contents('php://input'),true);
        $this->input=$JSONRequest["input"];
        $this->file=isset($JSONRequest["file"])?$JSONRequest["file"]:null;
      } 
      else{
        $this->input=$_REQUEST;
        $this->file=$_FILES;
      }
      $this->method=$_SERVER["REQUEST_METHOD"];
      // $this->input=$this->queryURL=="api"?json_decode(file_get_contents('php://input'),true):$_REQUEST;
      // $this->file=$_FILES;
    }

    public static function GET()
    {
      if($_SERVER["REQUEST_METHOD"]=="GET"){
        return true;
      }
    }
    public static function POST()
    {
      if($_SERVER["REQUEST_METHOD"]=="POST"){
        return true;
      }
    }
    public static function PUT()
    {
      if($_SERVER["REQUEST_METHOD"]=="PUT"){
        return true;
      }
    }
    public static function DELETE()
    {
      if($_SERVER["REQUEST_METHOD"]=="DELETE"){
        return true;
      }
    }
  }
?>