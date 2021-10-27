"use strict";
var Starter = {
  url: Application.getPath() + 'starter/api',
  init: function init() {
    this.bindListener();
  },
  bindListener: function bindListener() {
    this.handleListener();
  },
  import: function () {
    Application.import('Validation');
    Application.import('Validate');
    Application.import('Convert');
  },
  edit: async function () {
    let id = Application.getParameterByName('id'); //URL parameter id
    if (id != undefined) {
      $("#submit").text("Update Starter");
      let Response = await fetch(Starter.url + '?id=' + id);

      if (Response.ok) { // if HTTP-status is 200-299
        // get the response body (the method explained below)
        let result = await Response.json();
        console.log(result.records[0]);
        for (const field of Object.keys(result.records[0])) {
          $("#" + field).val(result.records[0][field]);
        }
      } else {
        alert("HTTP-Error: " + response.status);
      }
    }
  },
  validate: function () {
    $(document).on("submit", "#form-starter", async function (event) {
      event.preventDefault();
      let result = Validation.handleCheck({
        name: {
          isRequired: true,
          caption: 'Name',
        },
        
        address: {
          isRequired: true,
          caption: 'Address',
        },
        
        phone: {
          isRequired: true,
          caption: 'Phone',
        },
        
        email: {
          isRequired: true,
          caption: 'Email',
        },
      });
      // For removing existing validation class and setting form control border to normal
      $(".validate").remove();
      $(".form-control").css("border-color", "#D4D5D7");

      //Apply form validation in the fileds based on given rules 
      for (let i = 0; i < result["field"].length; i++) {
        $("#" + result["field"][i]).css("border-color", "red").after("<span class='validate'><i class='fa fa-exclamation-circle fa-fw'></i>" + result["message"][i] + "</span>");
      }

      if (result["error"] == false) {
        let $data = Convert.toJSONString(this);
        let id = Application.getParameterByName('id');
        if (id == undefined) {
          console.log("Add New Record");
          //Posting value;
          let Request = {
            input: $data
          };
          let Response = await fetch(Starter.url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify(Request)
          });
          $("#submit").text("Please Wait...").prop("disabled",true);
          let result = await Response.json();
          console.log(result);
          //alert(result.message);
          if (result.success == 1) {
            window.location.href = Application.getPath() + 'starter/show';
          }
        } else {
          console.log("Update");
          //Posting value;
          let Request = {
            input: $data
          };
          let Response = await fetch(Starter.url, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify(Request)
          });
          $("#submit").text("Please Wait...").prop("disabled",true);
          let result = await Response.json();
          alert(result.message);
          if (result.success == 1) {           
            window.location.href = Application.getPath() + 'starter/show';
          }
        }
      }
    });
  },
  removeValidate: function () {
    $(".form-control").on("blur", function (event) {
      $(this).siblings(".validate").remove();
      $(this).css("border-color", "#D4D5D7");
    });
  },
  
  handleListener: function handleListener() {
    this.import();
    this.edit();
    this.validate();
    this.removeValidate();
  }
};
Starter.init();