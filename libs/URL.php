<?php
  namespace libs;

  /**
   * URL
   */
  class URL
  {
      public function __construct()
      {
          // code...
      }
      public static function hasQuery()
      {
          $url= $_SERVER["REQUEST_URI"];
          return isset(parse_url($url)['query']);
      }

      public static function toAndOperator()
      {
          $url= $_SERVER["REQUEST_URI"];
          if (isset(parse_url($url)['query'])) {
              parse_str(parse_url($url)['query'], $params);
              $condition="";
              foreach ($params as $key => $value) {
                  $condition .="{$key} ='{$value}' AND ";
              }
              return rtrim($condition, " AND ");
          } else {
              return null;
          }
      }

      public static function toOrOperator()
      {
          $url= $_SERVER["REQUEST_URI"];
          if (isset(parse_url($url)['query'])) {
              parse_str(parse_url($url)['query'], $params);
              $condition="";
              foreach ($params as $key => $value) {
                  $condition .="{$key} ='{$value}' OR ";
              }
              return rtrim($condition, " OR ");
          } else {
              return null;
          }
      }
      public static function toLikeOperator()
      {
          $url= $_SERVER["REQUEST_URI"];
          if (isset(parse_url($url)['query'])) {
              parse_str(parse_url($url)['query'], $params);
              $condition="";
              foreach ($params as $key => $value) {
                  $condition .="{$key} LIKE '%{$value}%' AND ";
              }
              return rtrim($condition, " AND ");
          } else {
              return null;
          }
      }
      public static function hasPagination()
      {
          $url= $_SERVER["REQUEST_URI"];
          if (isset(parse_url($url)['query'])) {
              parse_str(parse_url($url)['query'], $params);
              if (array_key_exists("page", $params)) {
                  return true;
              } else {
                  return false;
              }
          }
      }
      public static function query()
      {
          $url= $_SERVER["REQUEST_URI"];
          if (isset(parse_url($url)['query'])) {
              parse_str(parse_url($url)['query'], $params);
              //print_r($params);
              return($params);
              // if (array_key_exists("page",$params))
        // {
        //   return $params["page"];
        // }
        // else
        // {
        //   return false;
        // }
          }
      }
  }
