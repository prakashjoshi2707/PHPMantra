<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Teacher;
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
class TeacherActivity extends Controller
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
        View::renderTemplate('activity_teacher/index.html', [
            'title'    => 'Teacher',
            'javascript' => 'activity_teacher/index.js',
            'style'=>'activity_teacher/index.css',
            'records'=>1
        ]);
    }

    public function showAction()
    {
        View::renderTemplate('activity_teacher/show.html', [
            'title'    => 'Teacher',
            'javascript' => 'activity_teacher/show.js',
            'style'=>'activity_teacher/show.css',
            'records'=>1
        ]);
    }

    public function detailAction()
    {
        View::renderTemplate('activity_teacher/detail.html', [
            'title'    => 'Teacher',
            'javascript' => 'activity_teacher/detail.js',
            'style'=>'activity_teacher/detail.css',
            'records'=>1
        ]);
    }

    public function api(Request $request)
    {
        $teacher=new Teacher();
        if(Request::GET()){
            if (URL::hasQuery()) {
                $response=$teacher->get(URL::toAndOperator());
            } else {
                $response=$teacher->all(true);
            }
            echo $response->toJSON();
        }
        if(Request::POST()){
           Validation::check($request,[
              //================REMOVE=================
              'name'=>[
                'required'=>true,
                'max'=>250,
              ],
            'address'=>[
                        'required'=>true,
                        'max'=>250,
                    ],
            'phone'=>[
                        'required'=>true,
                        'max'=>10,
                    ],
               //=================EXAMPLE=================
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
                //================REMOVE=================
                'id'=>[
                    'required'=>true,
                    
                  ],
                'name'=>[
                            'required'=>true,
                            'max'=>250,
                        ],
                'address'=>[
                            'required'=>true,
                            'max'=>250,
                        ],
                'phone'=>[
                            'required'=>true,
                            'max'=>10,
                  ],
                //=================EXAMPLE=================
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
        $teacher=new Teacher();
        echo  $teacher->generateContent();
    }
    public function datatable()
    {
        $teacher=new Teacher();
        echo  $teacher->datatable();
    }
    public function store(Request $request)
    {
        $teacher=new Teacher();
        $teacher->name=$request->input["name"];
        $teacher->address=$request->input["address"];
        $teacher->phone=$request->input["phone"];
        $response=$teacher->save();
        echo $response->toJSON();
    }
    public function find(Request $request)
    {
        $teacher=new Teacher();
        $response=$teacher->get("id=".$request->input["id"]);
        print_r($response->toJSON());
    }

    public function edit(Request $request)
    {
        $teacher=new Teacher();
        $starter->get("id=".$request->input["id"]);
    }

    public function update(Request $request)
    {
        $teacher=new Teacher();
        $teacher->id=$request->input["id"];
        $teacher->name=$request->input["name"];
        $teacher->address=$request->input["address"];
        $teacher->phone=$request->input["phone"];
        $response=$teacher->update("id=".$request->input["id"]);
        echo $response->toJSON();
    }

    public function delete(Request $request)
    {
       $teacher=new Teacher();
       $response=$teacher->delete("id=".$request->input["id"]);
       echo($response->toJSON());
        
    }
}
