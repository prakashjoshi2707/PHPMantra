<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Student;
use libs\URL;
use libs\Json;
use libs\Header;
use libs\Request;
use libs\Response;
use libs\Validation;

/**
 * Home controller
 *
 * PHP version 7.3
 */
class StudentActivity extends Controller
{
    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
            
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
       
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('activity_student/index.html', [
            'title'    => 'Student',
            'javascript' => 'activity_student/index.js',
            'style'=>'activity_student/index.css',
            'records'=>1
        ]);
    }

    public function showAction()
    {
        View::renderTemplate('activity_student/show.html', [
            'title'    => 'Student',
            'javascript' => 'activity_student/show.js',
            'style'=>'activity_student/show.css',
            'records'=>1
        ]);
    }

    public function detailAction()
    {
        View::renderTemplate('activity_student/detail.html', [
            'title'    => 'Student',
            'javascript' => 'activity_student/detail.js',
            'style'=>'activity_student/detail.css',
            'records'=>1
        ]);
    }

    public function api(Request $request)
    {
        $student=new Student();
        if(Request::GET()){
            if (URL::hasQuery()) {
                $response=$student->get(URL::toAndOperator());
            } else {
                $response=$student->all(true);
            }
            echo $response->toJSON();
        }
        if(Request::POST()){
           Validation::check($request,[
               'name'=>[
                   'required'=>true,
                   'max'=>100
               ],
               'address'=>[
                   'required'=>true,
                   'max'=>100
               ],
           ]);

           if (Validation::isPassed()) {
               $this->store($request);
           } else {
               print_r(Validation::getErrors());
           }
           
        }
        if(Request::PUT()){
            //echo "PUT";
            Validation::check($request,[
                'name'=>[
                    'required'=>true,
                    'max'=>100
                ],
                'address'=>[
                    'required'=>true,
                    'max'=>100
                ],
            ]);
 
            if (Validation::isPassed()) {
                $this->update($request);
            } else {
                print_r(Validation::getErrors());
            }
            
        }
        if(Request::DELETE()){
            //echo "DELETE";
            $this->delete($request);
            //print_r($response->toJSON());
        }
       
    }


    public function create()
    {
        
    }
    public function generate()
    {
        $student=new Student();
        echo $student->generateContent();
    }
    public function datatable()
    {
        $student=new Student();
        echo $student->datatable();
    }
    
    public function store(Request $request)
    {
        $student=new Student();
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$student->save(); 
        echo $response->toJSON();
    }
    public function find(Request $request)
    {
        $student=new Student();
        $response=$student->get("id=".$request->input["id"]);
        print_r($response->toJSON());
    }

    public function edit(Request $request)
    {
        $student=new Student();
        $starter->get("id=".$request->input["id"]);
    }

    public function update(Request $request)
    {
        $student=new Student();
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$student->update("id=".$request->input["id"]);
        echo $response->toJSON();
    }

    public function delete(Request $request)
    {
       $student=new Student();
       $response=$student->delete("id=".$request->input["id"]);
       echo($response->toJSON());
        
    }
}
