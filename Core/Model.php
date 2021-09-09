<?php

namespace Core;

use App\Config;
use PDO;
use libs\Database;
use libs\Response;
use libs\FileUpload;

/**
 * Base model
 *
 * PHP version 5.4
 */
  class Model
{
    protected static function getDB(){
        static $db = null;
        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' .
            Config::DB_NAME . ';charset=utf8';
            $db=new Database($dsn,Config::DB_USER, Config::DB_PASSWORD);
            
            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }
           return $db;
    }

    public function beginTransaction()
    {
      $db=self::getDB();
      $db->beginTransaction();
    }
    public function rollback()
    {
      $db=self::getDB();
      $db->rollback();
    }
    public function commit()
    {
      $db=self::getDB();
      $db->commit();
    }
    
    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
   protected static function getDB1()
    {
        static $db = null;
        
        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' .
                Config::DB_NAME . ';charset=utf8';
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                
                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        return $db;
    }
    
    public function save()
    {
        //echo(substr(strrchr(get_class($this),"\\"),1));
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        //print_r((array)$this);
        $db=self::getDB();
        $dataArray=(array)$this;
        $result=$db->insert($table,$dataArray);
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->message = "Successfully Added";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function update($condition)
    {
        //echo(substr(strrchr(get_class($this),"\\"),1));
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        //print_r((array)$this);
        $db=self::getDB();
        $dataArray=(array)$this;
        $result=$db->update($table,$dataArray,$condition);
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->message = "Successfully Updated";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function delete($condition)
    {
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        $result=$db->delete("DELETE FROM $table WHERE $condition");
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->message = "Successfully Deleted";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function get($condition)
    {
      
        //return ($this);
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($condition==null){
          $condition=1;
        }
        $result=$db->select("SELECT * FROM $table WHERE $condition");
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->records = $result;
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function getDistinct($column,$condition)
    {
      
        //return ($this);
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($condition==null){
          $condition=1;
        }
        $result=$db->select("SELECT DISTINCT($column) FROM $table WHERE $condition");
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->records = $result;
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    // public function all($columns=array(),$pagination=false,$page=null,$dtsearch=null,$filterColumn,$records=null)
    // {
    //      // print_r($columns);
    //     $columnsList= implode(",",$columns);
          
    //     $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
    //     $db=self::getDB();
    //     if($pagination==false and $records==null){
    //       $result=$db->select("SELECT $columnsList FROM $table WHERE $filterColumn LIKE %".$dtsearch."%"); 
    //       $total=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE %".$dtsearch."%");  
    //       $response=new Response();
    //       if($result){
    //           $response->success = 1;
    //           $response->records = $result;
    //           $response->first=1;
    //           $response->last=$total;
    //           $response->total=$total;
    //           $response->code=201;
    //           return $response;
    //         }else{
    //           $response->success = 0;
    //           $response->message= "Something went wrong";
    //           $response->code=400;
    //           return $response;
    //         }
    //     }else{
    //       $showRecordPerPage = 10;
    //       if(isset($page) && !empty($page)){
    //       $currentPage = $page;
    //       }else{
    //       $currentPage = 1;
    //       }
    //       $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
    //       $total=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE '%".$dtsearch."%'");  
    //       $lastPage = ceil($total/$showRecordPerPage);

    //       $firstPage = 1;
    //       $nextPage = $currentPage + 1;
    //       $previousPage = $currentPage - 1;
    //       $totalFilter=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE '%".$dtsearch."%' LIMIT $startFrom,$showRecordPerPage"); 

    //       $result=$db->select("SELECT $columnsList FROM $table  WHERE $filterColumn LIKE '%".$dtsearch."%' LIMIT $startFrom,$showRecordPerPage"); 
          
    //       $response=new Response();
    //       if($result){
    //           $response->success = 1;
    //           $response->records = $result;
    //           $response->first=$startFrom+1;
    //           $response->last=$page==$lastPage?$total:$totalFilter*$page;
    //           $response->total=$total;
    //           $response->code=201;
    //           return $response;
    //         }else{
    //           $response->success = 0;
    //           $response->message= "Something went wrong";
    //           $response->code=400;
    //           return $response;
    //         }
    //     }
    // }
    public function all($pagination=false,$records=null)
    {
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($pagination==false and $records==null){
          echo json_encode($result=$db->select("SELECT * FROM $table"));
        }else{
          $result=$db->select("SELECT * FROM $table LIMIT 0,10"); 
          $total=$db->selectCount("SELECT * FROM $table");  
          $response=new Response();
          if($result){
              $response->success = 1;
              $response->records = $result;
              $response->first=1;
              $response->last=5;
              $response->total=$total;
              $response->code=201;
              return $response;
            }else{
              $response->success = 0;
              $response->message= "Something went wrong";
              $response->code=400;
              return $response;
            }
        }
    }
    public function total($condition=null)
    {
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($condition==null){
         $total=$db->selectCount("SELECT * FROM $table");
        }else{
          $total=$db->selectCount("SELECT * FROM $table WHERE $condition");
        } 
        $response=new Response();
        if($total){
            $response->success = 1;
            $response->total=$total;
            $response->code=200;
            return $response;
          }else{
            $response->success = 0;
            $response->total=0;
            $response->code=200;
            return $response;
        }
    }

    // To check the unique reocrds
    public function check($condition)
    {
      
        //return ($this);
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($condition==null){
          $condition=1;
        }
        $result=$db->select("SELECT * FROM $table WHERE $condition");
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->message= "Available";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Not Available";
            $response->code=400;
            return $response;
          }
    }

    // public function upload($location=null)
    // {
      
    //   $dataArray=(array)$this;
    //   $db=self::getDB();
    //   $result=$db->insert('tbltmpstorage',$dataArray);
    //   $fileUpload=new FileUpload();
    //   $fileUpload->setLocation($location==null?'tmp':'photo/'.$location);
    //   $result=$fileUpload->uploadSingleFile($dataArray);
    //   $response=new Response();
    //   if($result){
    //     $response->success = 1;
    //     $response->message = "uploaded";
    //     $response->filename = $result;
    //     $response->code=201;
    //     return $response;
    //   }else{
    //     $response->success = 0;
    //     $response->message= "Something went wrong";
    //     $response->code=400;
    //     return $response;
    //   }
       
    // }
    public function upload($location=null)
    {
      
      // echo json_encode((array)$this);
      $dataArray=(array)$this;     
      $fileUpload=new FileUpload();
      $fileUpload->setLocation($location==null?'tmp':$location);
      $filename=$fileUpload->uploadSingleFile($dataArray);     
      $response=new Response();
      if($response){
        $response->success = 1;
        $response->message = "uploaded";
        $response->filename = $filename;
        $response->code=201;
        return $response;
      }else{
        $response->success = 0;
        $response->message= "Something went wrong";
        $response->code=400;
        return $response;
      }
       
    }
    public function deleteFile($location=null)
    {
      
      $dataArray=(array)$this;
      $fileUpload=new FileUpload();
      $fileUpload->setLocation($location==null?'tmp':$location);
      $result=$fileUpload->delete($dataArray);
      $response=new Response();
      if($result){
        $response->success = 1;
        $response->message = "deleted";
        $response->filename = $result;
        $response->code=201;
        return $response;
      }else{
        $response->success = 0;
        $response->message= "Something went wrong";
        $response->code=400;
        return $response;
      }
       
    }
    public function uploadMultiple($location=null)
    {
      $dataArray=(array)$this;
      $fileUpload=new FileUpload();
      $fileUpload->setLocation($location==null?'tmp':$location);
      echo $fileUpload->uploadMultipleFile($dataArray);
       
    }
    public function lastInsertID()
    {
      $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
      $db=self::getDB();
      $result=$db->lastInsertId();
      return $result;
    }
    public function toJSON()
    {
        return json_encode($this);
    }

    public function json()
    {
        echo json_encode();
    }
    // To Create PHP Class Object Based on Table, Validation, HTML Form and JS validation
    public function generateContent()
    {
       //return ($this);
       $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
       $className=(substr(strrchr(get_class($this),"\\"),1));
       $db=self::getDB();
       $result=$db->select("SELECT COLUMN_NAME, IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH,DATA_TYPE, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'$table' and TABLE_SCHEMA = '".Config::DB_NAME."' ORDER BY `ORDINAL_POSITION` ");
      //  print_r($result);

      // ==============================FROM===================================================
     
       echo "<h1>HTML FORM</h1>";
       echo "<h4>Note: Please make ID column hidden for editing purpose because it is autogenerated field</h4>";
       echo htmlspecialchars('<form method="POST" action="'.lcfirst($className).'/api" enctype="multipart/form-data" id="form-'.strtolower($className).'" name="form-'.strtolower($className).'">');
       foreach ($result as $value){
        //  if ($value['COLUMN_NAME']!="createdAt" || $value['COLUMN_NAME']!="createdBy" || $value['COLUMN_NAME']!="createdFrom"||
        //  $value['COLUMN_NAME']!="modifiedAt" || $value['COLUMN_NAME']!="modifiedBy" || $value['COLUMN_NAME']!="modifiedFrom"||
        //  $value['COLUMN_NAME']!="deletedAt" || $value['COLUMN_NAME']!="deletedBy" || $value['COLUMN_NAME']!="deletedFrom"
         
        //  ) {
          
             $max=$value['DATA_TYPE']=="varchar" || $value['DATA_TYPE']=="text" || $value['DATA_TYPE']=="mediumtext" || $value['DATA_TYPE']=="longtext"?"maxlength=\"".$value['CHARACTER_MAXIMUM_LENGTH']."\"":false;
             $formFiled=$value['DATA_TYPE']=="text"?
        '<textarea class="form-control"  id="'.$value['COLUMN_NAME'].'" name="'.$value['COLUMN_NAME'].'" '.$max.' placeholder="Enter '.ucwords($value['COLUMN_COMMENT']).'"  rows="2"></textarea>':
          $formFiled=$value['DATA_TYPE']=="int"? '<input type="number" class="form-control" id="'.$value['COLUMN_NAME'].'" name="'.$value['COLUMN_NAME'].'" '.$max.' placeholder="Enter '.ucwords($value['COLUMN_COMMENT']).'">':
            '<input type="text" class="form-control" id="'.$value['COLUMN_NAME'].'" name="'.$value['COLUMN_NAME'].'" '.$max.' placeholder="Enter '.ucwords($value['COLUMN_COMMENT']).'">';

             echo "<pre>";
             echo htmlspecialchars('
  <label for="'.$value['COLUMN_NAME'].'" class="form-label">'.ucwords($value['COLUMN_COMMENT']).'</label>
  '.$formFiled);
             
             echo "</pre>";
            
        //  }
       }
       echo htmlspecialchars('<button type="submit" id="submit" name="submit" class="btn btn-primary">SAVE</button>')."<br>";
       echo htmlspecialchars("</form>");
       //====================================JAVASCRIPT====================================================
       echo "<hr>";
       echo "<h1>Javascript Validation</h1>";
       foreach ($result as $value){
        $required=$value['IS_NULLABLE']=="NO"?"true":"false";
        echo "<pre>";
        echo "
        ".$value['COLUMN_NAME'].": {
          isRequired: ".$required.",
          caption: '".ucwords($value['COLUMN_COMMENT'])."',
        },
        ";
        echo "</pre>";
       }

      //  ================================================================================================

      echo "<hr>";
      echo "<h1>PHP Validation</h1>";
      foreach ($result as $value){
       $required=$value['IS_NULLABLE']=="NO"?"true":"false";
       $max=$value['DATA_TYPE']=="varchar" || $value['DATA_TYPE']=="text"?"'max'=>".$value['CHARACTER_MAXIMUM_LENGTH'].",":false;
       echo "<pre>";
       echo " '".$value['COLUMN_NAME']."'=>[
         'required'=>$required,
         $max
       ],";
       echo "</pre>";
      }
      
     
      //===================================================================================================

       echo "<h1>Class Definition To Store</h1>";
       echo "<h4>Note: Please remove ID column for storing because it is autogenerated field</h4>";
       echo "\$".lcfirst($className)."=new ".$className."();<br>";
       foreach ($result as $value){
         if($value['COLUMN_NAME']=="createdAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=GlobalSession::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }else{
           echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$request->input[\"".$value['COLUMN_NAME']."\"];";
           echo "<br>";
         }
       }
       echo "\$response=\$".lcfirst($className)."->save();";
       echo "<br>";

       echo " echo \$response->toJSON();";
       echo "<br>";
       echo "<hr>";
       echo "<h1>Class Definition To Update</h1>";
       echo "\$".lcfirst($className)."=new ".$className."();<br>";
       foreach ($result as $value){
       
         if($value['COLUMN_NAME']=="createdAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=GlobalSession::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }else{
           echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$request->input[\"".$value['COLUMN_NAME']."\"];";
           echo "<br>";
         }
       }
       echo "\$response=\$".lcfirst($className)."->update(\"id=\".\$request->input[\"id\"]);";
       echo "<br>";

       echo " echo \$response->toJSON();";
       echo "<br>";

      
       echo "<hr>";
      //  Datatable
      echo "<h1>Datatable Definition</h1>";
      echo "<h1>Datatable Definition: Table Definition for Show View</h1>";
      echo "<pre>";
      echo "<h4>Note: Please remove ID column because it is autogenerated field</h4>";
       echo htmlspecialchars('
<table id="'.strtolower($className).'"  class="table w-100">
    <thead>
      <tr>');
    echo "<br>";
    foreach ($result as $value){
    
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlspecialchars('<th scope="col">'.ucwords(strtolower($value['COLUMN_NAME'])).'</th>');
     echo "<br>";
  }
  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlspecialchars('<th scope="col">Action</th>');
    echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlspecialchars('</tr>
  </thead>
  <tbody>
  </tbody>
</table>
       ');

      echo "</pre>";
      
      echo "<h1>Datatable Definition: Table Definition for Show Model</h1>";
      echo "<pre>";
  echo" public  function datatable()
  {
      try {
          \$db = static::getDB();
          \$query = '';
          \$output = array();
          \$query .= \"SELECT * FROM $table \";
          if (isset(\$_POST[\"search\"][\"value\"])) {
              // Please provide the search field instead of id
              \$query .= ' WHERE id LIKE \"%' . \$_POST[\"search\"][\"value\"] . '%\" ';
          }
          if (isset(\$_POST[\"order\"])) {
              \$query .= ' ORDER BY ' . \$_POST['order']['0']['column'] . ' ' . \$_POST['order']['0']['dir'] . ' ';
          } else {
            // Please provide the sort field instead of id
              \$query .= 'ORDER BY id DESC ';
          }
          if (\$_POST[\"length\"] != - 1) {
              \$query .= 'LIMIT ' . \$_POST['start'] . ', ' . \$_POST['length'];
          }
          
          \$statement = \$db->prepare(\$query);
          \$statement->execute();
          \$result = \$statement->fetchAll();
          \$data = array();
          \$filtered_rows = \$statement->rowCount();
          \$slno=1;
          foreach (\$result as \$row) {
              \$sub_array = array();
              \$sub_array[]=\$slno+ \$_POST['start'];<br>";
            foreach ($result as $value){
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$sub_array[] = \$row[\"".$value['COLUMN_NAME']."\"];";
                  echo "<br>";
              }
              echo"
              \$sub_array[] =".htmlspecialchars("'<a class=\"btn btn-sm btn-icon btn-primary btn-edit\" edit-row='.\$row[\"id\"].'><i class=\"fa fa-pencil-alt\"></i></a>
                <a class=\"btn btn-sm btn-icon btn-danger btn-delete\" delete-row='.\$row[\"id\"].'><i class=\"far fa-trash-alt\"></i></a> 
                <a class=\"btn btn-sm btn-icon btn-info btn-detail\" get-row='.\$row[\"id\"].'><i class=\"fas fa-info-circle\"></i></a> ';").
              "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$data[] = \$sub_array;
              \$slno++;  
          }
          \$output = array(
            \"draw\" => intval(\$_POST[\"draw\"]),
            \"recordsTotal\" => \$filtered_rows,
            \"recordsFiltered\" => self::getTotal".($className)."(),
            \"data\" => \$data
        );
        return json_encode(\$output);
    } catch (PDOException \$e) {
        echo \$e->getMessage();
    }
  }
      
public static function getTotal".($className)."()
{
  \$db = static::getDB();
  \$statement = \$db->prepare(\"SELECT * FROM $table \" );
  \$statement->execute();
  return \$statement->rowCount();
}";
     
     
      echo "</pre>";

      echo "<hr>";
      echo "<h1>DETAIL</h1>";
      foreach ($result as $value){
       echo "<pre>";
       echo htmlspecialchars("<span id=".$value['COLUMN_NAME']."></span><br>");
       echo "</pre>";
      }
    }
}
