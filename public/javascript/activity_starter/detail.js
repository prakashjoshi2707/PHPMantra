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
        get: async function () {
            let id = Application.getParameterByName('id'); //URL parameter id
            if (id != undefined) {
              let Response = await fetch(Starter.url + '?id=' + id);
        
              if (Response.ok) { // if HTTP-status is 200-299
                // get the response body (the method explained below)
                let result = await Response.json();
                console.log(result.records[0]);
                for (const field of Object.keys(result.records[0])) {
                  $("#" + field).text(result.records[0][field]);
                }
              } else {
                alert("HTTP-Error: " + response.status);
              }
            }
          },
        handleListener: function handleListener() {
            this.import();
            this.get();
        }
    };
    Starter.init();