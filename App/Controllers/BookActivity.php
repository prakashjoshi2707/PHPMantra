<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Book;
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
class BookActivity extends Controller
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
        View::renderTemplate('activity_book/index.html', [
            'title'    => 'Book',
            'javascript' => 'activity_book/index.js',
            'style'=>'activity_book/index.css',
            'records'=>1
        ]);
    }

    public function showAction()
    {
        View::renderTemplate('activity_book/show.html', [
            'title'    => 'Book',
            'javascript' => 'activity_book/show.js',
            'style'=>'activity_book/show.css',
            'records'=>1
        ]);
    }

    public function detailAction()
    {
        View::renderTemplate('activity_book/detail.html', [
            'title'    => 'Book',
            'javascript' => 'activity_book/detail.js',
            'style'=>'activity_book/detail.css',
            'records'=>1
        ]);
    }

    public function api(Request $request)
    {
        $book=new Book();
        if(Request::GET()){
            if (URL::hasQuery()) {
                $response=$book->get(URL::toAndOperator());
            } else {
                $response=$book->all(true);
            }
            echo $response->toJSON();
        }
        if(Request::POST()){
           Validation::check($request,[
              //================REMOVE=================
               'name'=>[
                   'required'=>true,
                   'max'=>100
               ],
               'address'=>[
                   'required'=>true,
                   'max'=>100
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
                'name'=>[
                    'required'=>true,
                    'max'=>100
                ],
                'address'=>[
                    'required'=>true,
                    'max'=>100
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
        $book=new Book();
        echo  $book->generateContent();
    }
    public function datatable()
    {
        $book=new Book();
        echo  $book->datatable();
    }
    public function store(Request $request)
    {
        $book=new Book();
        //Please execute the generate action then copy paste the class definition 
        //================REMOVE=================
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$book->save(); 
        echo $response->toJSON();
        //=================EXAMPLE=================
    }
    public function find(Request $request)
    {
        $book=new Book();
        $response=$book->get("id=".$request->input["id"]);
        print_r($response->toJSON());
    }

    public function edit(Request $request)
    {
        $book=new Book();
        $starter->get("id=".$request->input["id"]);
    }

    public function update(Request $request)
    {
        $book=new Book();
        //Please execute the generate action then copy paste the class definition 
        //================REMOVE=================
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$book->update("id=".$request->input["id"]);
        echo $response->toJSON();
        //=================EXAMPLE=================
    }

    public function delete(Request $request)
    {
       $book=new Book();
       $response=$book->delete("id=".$request->input["id"]);
       echo($response->toJSON());
        
    }
}
