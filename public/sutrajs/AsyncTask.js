class AsyncTask {
  constructor() {}
  static execute(url,method,data,contentType){
    var promise = $.Deferred();
      if(contentType=='json'){
        $.ajax({
          url: url,
          method: method,
          data: this.jsonData(data),
          crossDomain: true,
          contentType: 'application/json',
          processData: false,
          beforeSend: function(xhr) {
            //xhr.setRequestHeader('Authorization', 'Basic username:password');
          },
          success: function(response) {
            promise.resolve(response)
          },
          error: function(error) {
            promise.reject(error);
          }
        });
      }else if(contentType=='sjson'){
        if(method!="GET")
        {
          data=JSON.stringify(data);
        }else{
          data
        }
        $.ajax({
          url: url,
          method: method,
          data: data,
          crossDomain: true,
          contentType: 'application/json',
          processData: false,
          beforeSend: function(xhr) {
            //xhr.setRequestHeader('Authorization', 'Basic username:password');
          },
          success: function(response) {
            promise.resolve(response)
          },
          error: function(error) {
            promise.reject(error);
          }
        });
      }else{
        $.ajax({
          url: url,
          method: method,
          data: new FormData(data),
          crossDomain: true,
          enctype: 'multipart/form-data',
          contentType: 'false',
          processData: false,
          contentType: false,
          xhr:function(){
            var xhr=new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress',function(content){
              if(content.lengthComputable){
                  var percentage=Math.round((content.loaded/content.total)*100);
                  $("#progress-bar").text(percentage+"%").attr("aria-valuenow",percentage).css("width",percentage+"%");
              }
            });
            return xhr;
          },
          beforeSend: function(xhr) {
            //xhr.setRequestHeader('Authorization', 'Basic username:password');
            $("#progress-bar").text("0").attr("aria-valuenow","0").css("width","0%");
          },
          success: function(response) {
            promise.resolve(response);
          },
          error: function(error) {
            promise.reject(error);
          }
        });
      }
      return promise;
  }
  static jsonData(form) {
    var arrData = form.serializeArray(),
      objData = {};
    $.each(arrData, function(index, elem) {
      objData[elem.name] = elem.value;
    });
    console.log(JSON.stringify(objData));
    return JSON.stringify(objData);
  }

}