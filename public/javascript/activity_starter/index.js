/*
    Javascript for activity_starter
    */
"use strict";
var starter = {
  init: function init() {
    this.bindListener();
  },
  bindListener: function bindListener() {
    this.handleListener();
  },
  import: function(){
    Application.import('Validation');
    Application.import('Validate');
    Application.import('FileUpload');
    Application.import('AsyncTask',function(){
            //for editing records in a form
            let id=Application.getParameterByName('id'); //URL parameter id
            $("#id").val(id); //id field for editing 
            if(id!=undefined){
                AsyncTask.execute(Application.getPath()+'starter/api?id='+id, 'GET', null, 'sjson').
                done(function(result){
                console.log(result);
                    for (const field of Object.keys(result["records"][0])) {
                        $("#"+field).val(result["records"][0][field]);
                    }
                }).fail(function(error){
                console.log(error);
                });
            }
    });
  },
  
  validate:  function(){
       $("#form-starter").on("submit", async function(event) {
            event.preventDefault();
            let result=Validation.handleCheck({
                name:{
                isRequired: true,
                caption: 'Title'
              },
              address:{
                isRequired: true,
                caption: 'Description'
              },
              // file:{
              //   isRequired: true,
              //   caption: 'File'
              // },
            });
            console.log(result);
            // For removing existing validation class and setting form control border to normal
            $(".validate").remove();
            $(".form-control").css("border-color","#D4D5D7");
            
            //Apply form validation in the fileds based on given rules 
            for(let i=0;i<result["field"].length;i++){
                $("#"+result["field"][i]).css("border-color","red").after( "<span class='validate'><i class='fa fa-exclamation-circle fa-fw'></i>"+result["message"][i]+"</span>");
            }

            if(result["error"]==false){
                var $form=$("#form-starter");
                console.log(toJSONString(this));
                let $data=toJSONString(this);
                let id=Application.getParameterByName('id');
                if(id==undefined){
                    console.log("add");
                    // AsyncTask.execute(Application.getPath()+'starter/api', 'POST', $form, 'json').
                    // done(function(response){
                    // console.log(response);
                    // //window.location.href = Application.getPath()+'starter/apii';
                    // })
                    // .fail(function(error){
                    // console.log(error);
                    // });

                    //Getting response;
                    let response = await fetch(Application.getPath()+'starter/api?id=36');

                    if (response.ok) { // if HTTP-status is 200-299
                      // get the response body (the method explained below)
                      let json = await response.json();
                      console.log(json);
                    } else {
                      alert("HTTP-Error: " + response.status);
                    }
                    //Posting value;
                    // let user = {
                    //   input:$data
                    // };
                   
                    // let response1 = await fetch(Application.getPath()+'starter/api', {
                    //   method: 'POST',
                    //   headers: {
                    //     'Content-Type': 'application/json',
                    //     'Accept': 'application/json'
                    //   },
                    //   body: JSON.stringify(user)
                    // });
                    
                    // let result = await response1.json();
                    // alert(result.message);


                }else{
                    console.log("add");
                    // AsyncTask.execute(Application.getPath()+'starter/api', 'PUT', $form, 'json').
                    // done(function(response){
                    // console.log(response);
                    // window.location.href = Application.getPath()+'starter/apii';
                    // })
                    // .fail(function(error){
                    // console.log(error);
                    // });
                }
            }
       });
  },
  removeValidate:function(){
        $(".form-control").on("blur", function(event) {
            $(this).siblings(".validate").remove();
            $(this).css("border-color","#D4D5D7");   
        });
  },
  // displayPhoto:function(){
  //   $(document).on("change","#file",function() {
  //     FileUpload.show(this,'photo-show');
  //   });
  // },
  // fileUpload:function(){
  //   $(document).on("change","#file",function() { 
  //     var form = $('form').get(1);
  //     console.log(form);
  //     AsyncTask.execute(Application.getPath()+'starter/uploadPhoto', 'POST', form, null).
  //     done(function(response){
  //       console.log("Upload"+response);
  //       $("#filename").val(response);
  //       //window.location.href = Application.getPath()+'participant/show';
  //     }).fail(function(error){
  //       console.log(error);
  //     });
  //   });
  // },
  file:function(){
    $(document).on("change","#file",function() {
      uploadFile();
    });
    function uploadFile() {
  var file = $("$file").files[0];
  // alert(file.name+" | "+file.size+" | "+file.type);
  var formdata = new FormData();
  formdata.append("file", file);
  var ajax = new XMLHttpRequest();
  ajax.upload.addEventListener("progress", progressHandler, false);
  ajax.addEventListener("load", completeHandler, false);
  ajax.addEventListener("error", errorHandler, false);
  ajax.addEventListener("abort", abortHandler, false);
  ajax.open("POST", "file_upload_parser.php"); // http://www.developphp.com/video/JavaScript/File-Upload-Progress-Bar-Meter-Tutorial-Ajax-PHP
  //use file_upload_parser.php from above url
  ajax.send(formdata);
}

function progressHandler(event) {
  $("#loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
  var percent = (event.loaded / event.total) * 100;
  $("#progressBar").value = Math.round(percent);
  $("#status").innerHTML = Math.round(percent) + "% uploaded... please wait";
}

function completeHandler(event) {
  $("#status").innerHTML = event.target.responseText;
  $("#progressBar").value = 0; //wil clear progress bar after successful upload
}

function errorHandler(event) {
  $("#status").innerHTML = "Upload Failed";
}

function abortHandler(event) {
  $("#status").innerHTML = "Upload Aborted";
}
  },
  handleListener:function handleListener(){
    this.import();
    this.validate();
    this.removeValidate();
    // this.displayPhoto();
    this.file();
  }
};
starter.init();

function toJSONString( form ) {
  var obj = {};
  var elements = form.querySelectorAll( "input, select, textarea" );
  for( var i = 0; i < elements.length; ++i ) {
    var element = elements[i];
    var name = element.name;
    var value = element.value;

    if( name ) {
      obj[ name ] = value;
    }
  }

  return  obj ;
};
