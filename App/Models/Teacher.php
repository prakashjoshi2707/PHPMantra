<?php
namespace App\Models;

use Core\Model;

class Teacher extends Model 
{
    public  function datatable()
    {
        try {
            $db = static::getDB();
            $query = '';
            $output = array();
            $query .= "SELECT * FROM tblteacher ";
            if (isset($_POST["search"]["value"])) {
                // Please provide the search field instead of id
                $query .= ' WHERE id LIKE "%' . $_POST["search"]["value"] . '%" ';
            }
            if (isset($_POST["order"])) {
                $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
            } else {
              // Please provide the sort field instead of id
                $query .= 'ORDER BY id DESC ';
            }
            if ($_POST["length"] != - 1) {
                $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }
            
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $data = array();
            $filtered_rows = $statement->rowCount();
            $slno=1;
            foreach ($result as $row) {
                $sub_array = array();
                $sub_array[]=$slno+ $_POST['start'];
               
                $sub_array[] = $row["name"];
                $sub_array[] = $row["address"];
                $sub_array[] = $row["phone"];
  
                $sub_array[] ='<a class="btn btn-sm btn-icon btn-primary btn-edit" edit-row='.$row["id"].'><i class="fa fa-pencil-alt"></i></a>
                  <a class="btn btn-sm btn-icon btn-danger btn-delete" delete-row='.$row["id"].'><i class="far fa-trash-alt"></i></a> 
                  <a class="btn btn-sm btn-icon btn-info btn-detail" get-row='.$row["id"].'><i class="fas fa-info-circle"></i></a> ';
                $data[] = $sub_array;
                $slno++;  
            }
            $output = array(
              "draw" => intval($_POST["draw"]),
              "recordsTotal" => $filtered_rows,
              "recordsFiltered" => self::getTotalTeacher(),
              "data" => $data
          );
          return json_encode($output);
      } catch (PDOException $e) {
          echo $e->getMessage();
      }
    }
        
  public static function getTotalTeacher()
  {
    $db = static::getDB();
    $statement = $db->prepare("SELECT * FROM tblteacher " );
    $statement->execute();
    return $statement->rowCount();
  }
   
}