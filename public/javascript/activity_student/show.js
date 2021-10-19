   "use strict";
   var Student = {
     url: Application.getPath()+'student/api',
     studentTable:null,
     tab:"showAll",
     init: function init() {
       this.bindListener();
     },
     bindListener: function bindListener() {
       this.handleListener();
     },
     import: function(){
        $("#btn-restore-selected").hide();
        $("#btn-delete-selected").hide();
     },

     datatable: function(deleted=0){
         Student.studentTable=$('#student').DataTable({
          "processing": true,
          "responsive":true,
          "serverSide": true,
          "searching":true,
          destroy: true,
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
            url : Application.getPath()+'student/datatable',
            method : "POST",
            data:{
              deleted:deleted
            }
          },
          drawCallback:function(settings){
            $("#total-show-all").html(`(${settings.json.totalShowAll})`);
            $("#total-show-trash").html(`(${settings.json.totalShowTrash})`);
          },
          //====================NEED TO MODIFY==============
          "columnDefs" : [ 
            {
              "targets": [0,1], // your case first column
              "className": "text-left",
              "width": "5%",
              "searchable": false,
              "orderable" : false
            },{
              "targets": [2], // your case last column
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
        $('#alert').modal('show');

        var id=$(this).attr('delete-row');
        let Request = {
          input:{
            id:id,
            tag:"delete"
          }
        };
        Student.confirmTask(Request,"delete",1);
        
      });
      },
      restore:function(){
        $(document).on("click", ".btn-restore", async function(event){
          event.preventDefault();
          $('#alert').modal('show');
          var id=$(this).attr('restore-row');
          let Request = {
            input:{
              id:id,
              tag:"restore"
            }
          };
          Student.confirmTask(Request,"restore",1);          
        });
        },
      edit:function(){
        $(document).on("click", ".btn-edit", async function(event){
          event.preventDefault();
          var id=$(this).attr('edit-row');
          window.location.href=Application.getPath()+'student/index?id='+id;
        });
      },
      detail:function(){
        $(document).on("click", ".btn-detail", async function(event){
          event.preventDefault();
          var id=$(this).attr('get-row');
          window.location.href=Application.getPath()+'student/detail?id='+id;
        });
      },
      showAll:function(){
        $(document).on("click", "#tab-show-all", async function(event){
          event.preventDefault();
          Student.tab="showAll";
          Student.datatable(0);
         $(".nav-link").removeClass("active");
         $("#btn-delete-selected").text("Delete Selected");
         $("#check-all").prop('checked', false); 
         $(this).addClass("active"); 
         $("#btn-delete-selected").hide();
        $("#btn-restore-selected").hide(); 
        });
      },
      showTrash:function(){
        $(document).on("click", "#tab-show-trash", async function(event){
          event.preventDefault();
          Student.tab="showTrash";
          Student.datatable(1);
         $(".nav-link").removeClass("active");
         $("#btn-restore-selected").text("Restore Selected");
         $("#check-all").prop('checked', false); 
         $(this).addClass("active");
         $("#btn-delete-selected").hide();
         $("#btn-restore-selected").hide();
         
        });
      },
      checkAll:function(){
        $(document).on("change", "#check-all", async function(event){
          event.preventDefault();
          $(".check-item").prop("checked",$(this).prop("checked"));
          var item=$('input:checkbox:checked').length-1;
          if(item>=1){
            if(Student.tab==="showAll"){
              $("#btn-delete-selected").show();
              $("#btn-delete-selected").text("Delete ("+item +") Selected");
            }else{
              $("#btn-restore-selected").show();
              $("#btn-restore-selected").text("Restore ("+item +") Selected");
            }
           
          }else{
            $("#btn-delete-selected").hide();
            $("#btn-restore-selected").hide();
          }          
        });
      },
      checkSingle:function(){
        $(document).on("change", ".check-item", async function(event){
          event.preventDefault();
          $("#check-all").prop('checked', false); 
          var item=$('input:checkbox:checked').length;
          if(item>=1){
            if(Student.tab==="showAll"){
              $("#btn-delete-selected").show();
              $("#btn-delete-selected").text("Delete ("+item +") Selected");
            }else{
              $("#btn-restore-selected").show();
              $("#btn-restore-selected").text("Restore ("+item +") Selected");
            }
           
          }else{
            $("#btn-delete-selected").hide();
            $("#btn-restore-selected").hide();
          }          
        });
      },
      deleteSelected:function(){
        $(document).on("click", "#btn-delete-selected", async function(event){
          event.preventDefault();
            var idArray=[];
            $(".check-item:checked").map(function(){
              idArray.push($(this).val());
            });
            let Request = {
              input:{
                id:idArray,
                tag:"delete"
              }
            };
            if(idArray.length!==0){
            $('#alert').modal('show');
            Student.confirmTask(Request,"delete",idArray.length);
            } 
        });
      },
      restoreSelected:function(){
        $(document).on("click", "#btn-restore-selected", async function(event){
            event.preventDefault();
           
            var idArray=[];
            $(".check-item:checked").map(function(){
              idArray.push($(this).val());
            });
            
            let Request = {
              input:{
                id:idArray,
                tag:"restore"
              }
            };
            if(idArray.length!==0){
              $('#alert').modal('show');
              Student.confirmTask(Request,"restore",idArray.length); 
            }
              
           
        });
      },
      confirmTask:function(Request,tag,noOfItems){
        if(tag==="delete"){
           $("#modal-title").html('<i class="fas fa-exclamation-triangle text-warning mr-1"></i> Delete item ')
           $("#message").html(`Are you sure you want to move ${noOfItems} item(s) to <b>Trash</b>?`);
        }else{
          $("#modal-title").html('<i class="fas fa-exclamation-triangle text-warning mr-1"></i> Restore item ')
           $("#message").html(`Restore  ${noOfItems} item(s) from <b>Trash</b>?`);
        }
       
        $(document).on('click', '#btn-okay', async function() {
            
            let Response = await fetch(Student.url,{

              method: 'DELETE',
              headers:{
                'Content-Type': 'application/json',
                'Accept': 'application/json'
              },
              body: JSON.stringify(Request)

            });
            let result = await Response.json();
                
            Student.studentTable.ajax.reload();            
            $("#check-all").prop('checked', false); 
            $("#btn-delete-selected").hide();
            $("#btn-restore-selected").hide();
            $("#btn-restore-selected").text("Restore Selected");
            $("#btn-delete-selected").text("Delete Selected");
            
            
            $('#alert').modal('hide');
            
        });
      },
      handleListener:function handleListener(){
        this.import();
        this.datatable();
        this.delete();
        this.restore();
        this.edit();
        this.detail();
        this.showAll();
        this.showTrash();
        this.deleteSelected();
        this.restoreSelected();
        this.checkAll();
        this.checkSingle();
     }
   };
   Student.init();

   