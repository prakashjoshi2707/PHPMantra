   "use strict";
   var Teacher = {
     url: Application.getPath()+'teacher/api',
     teacherTable:null,
     init: function init() {
       this.bindListener();
     },
     bindListener: function bindListener() {
       this.handleListener();
     },
     import: function(){
     
     },
     datatable: function(){
         Teacher.teacherTable=$('#teacher').DataTable({
          "processing": true,
          "responsive":true,
          "serverSide": true,
          "searching":true,
          "order":[],
          dom: 'lBfrtip',
         // Configure the drop down options.
         lengthMenu: [
           [ 10, 25, 50, -1 ],
           [ '10 rows', '25 rows', '50 rows', 'Show all' ]
         ] ,
          buttons: [
            {
              extend:    'copyHtml5',
              text:      '<i class="fa fa-copy"></i> Copy',
              titleAttr: 'Copy'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel'
            },
            {
                extend:    'csvHtml5',
                text:      '<i class="fas fa-file"></i> CSV',
                titleAttr: 'CSV'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF'
            },
            {
              extend:    'print',
              text:      '<i class="fas fa-print"></i> Print',
              titleAttr: 'Print'
            }
          ],
          "ajax" : {
            url : Application.getPath()+'teacher/datatable',
            method : "POST"
          },
          //====================NEED TO MODIFY==============
          "columnDefs" : [ 
            {
              "targets": [0], // your case first column
              "className": "text-left",
              "width": "5%",
              "searchable": false,
              "orderable" : false
            },{
              "targets": [1], // your case last column
              "className": "text-left",
              "width": "20%",
              "orderable" : true
            },
          ]
          //====================NEED TO MODIFY==============
      })
    }
     ,
     delete:function(){
      $(document).on("click", ".btn-delete", async function(event){
        event.preventDefault();
        var id=$(this).attr('delete-row');
        let Request = {
          input:{
            id:id
          }
        };

        let Response = await fetch(Teacher.url,{

          method: 'DELETE',
          headers:{
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(Request)

        });
        let result = await Response.json();
             
        Teacher.teacherTable.ajax.reload();
        console.log(result);
        
      });
      },
      edit:function(){
        $(document).on("click", ".btn-edit", async function(event){
          event.preventDefault();
          var id=$(this).attr('edit-row');
          window.location.href=Application.getPath()+'teacher/index?id='+id;
        });
      },
      detail:function(){
        $(document).on("click", ".btn-detail", async function(event){
          event.preventDefault();
          var id=$(this).attr('get-row');
          window.location.href=Application.getPath()+'teacher/detail?id='+id;
        });
      },
      handleListener:function handleListener(){
        this.import();
        this.datatable();
        this.delete();
        this.edit();
        this.detail();
     }
   };
   Teacher.init();

   