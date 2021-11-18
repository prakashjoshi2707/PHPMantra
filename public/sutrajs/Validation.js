class Validation {

  constructor() {}
  static handleCheck(rules) {
    //console.log(rules);
    //console.log(rules["address"]);
    var errorStatus={
      field:[],
      message:[],
      error:false
    }
    for (const field of Object.keys(rules)) {
      if (rules.hasOwnProperty(field)) {
      console.log(field, rules[field]);
        for (const rule of Object.keys(rules[field])) {
          if (rules[field].hasOwnProperty(rule)) {
            console.log(rule, rules[field][rule]); 
            var $value=$("#"+field).val();
            if(rule=="isRequired" && $value==""){
              errorStatus.field.push(field.toString());
              errorStatus.message.push(rules[field]["caption"]+" is required");
              errorStatus.error=true;
            }
            else if(rule=="isSelected" && $value=="default"){
              errorStatus.field.push(field.toString());
              errorStatus.message.push(rules[field]["caption"]+" is required");
              errorStatus.error=true;
            }
            else if(rule=="isChecked"){
              var check=true;
              
              $("input:radio").each(function(){
                var name = $(this).attr("name");
                if($("input:radio[name="+name+"]:checked").length == 0){
                       check=false;     
                }
              });
              $("input:checkbox").each(function(){
                var name = $(this).attr("name");
                if(name==field){
                if($("input:checkbox[name="+name+"]:checked").length == 0){
                       check=false;    
                }
              }
              });
              if(check==false){
                errorStatus.field.push(field.toString());
                errorStatus.message.push(rules[field]["caption"]+" options need to be checked!");
                errorStatus.error=true;   
              }
            }
            else if($value!=""){
              switch(rule){
                case "maxChar":
                  var $lenOfValue=$value.length;
                  var $maxValue=rules[field][rule];
                  if($lenOfValue>$maxValue){
                    errorStatus.field.push(field.toString());
                    errorStatus.message.push("Please enter a less than "+$maxValue+" chracter");
                    errorStatus.error=true;
                  }
                break;
                case "minChar":
                  var $lenOfValue=$value.length;
                  var $minValue=rules[field][rule];
                  if($lenOfValue<$minValue){
                    errorStatus.field.push(field.toString());
                    errorStatus.message.push("Please enter a more than "+$minValue+" chracter");
                    errorStatus.error=true;
                  }
                  break;
                case "length":
                  var $lenOfValue=$value.length;
                  var $maxValue=rules[field][rule];
                  if($lenOfValue!=$maxValue){
                    errorStatus.field.push(field.toString());
                    errorStatus.message.push("Please enter "+$maxValue+" chracter");
                    errorStatus.error=true;
                  }
                  break;
                case "email":
                    if(!Validate.isValidEmail($value)){
                      errorStatus.field.push(field.toString());
                      errorStatus.message.push("Please enter a valid email address");
                      errorStatus.error=true;
                    }
                  break;
                case "phone":
                    if(!Validate.isValidPhone($value)){
                      errorStatus.field.push(field.toString());
                      errorStatus.message.push("Please enter a valid phone number");
                      errorStatus.error=true;
                    }
                  break;
                case "matches":
                    var $matchFieldWith=rules[field][rule];
                    var $valueOfMatchFieldWith=$("#"+$matchFieldWith).val();
                    if($value!=$valueOfMatchFieldWith){
                      console.log("Confirm password does not match with password");
                      errorStatus.error=true;
                    }
                  break;
                case "unique":
                  if(this.$errorCount==0){
                  var $url=rules[field][rule];
                  var $data={[field]:$value};
                  this.$errorCount++;
                  var data;
                  $.when(AsyncTask.execute("post",$url,$data)).done(function(value) {
                    data=value;
                  });
                  this.$message=data;
                  }
                  break;
                case "date":
                    if(!Validate.isValidDate($value)){
                      console.log("Please enter a valid date");
                    }
    
                  break;
                case "time":
                    if(!Validate.isValidTime($value)){
                      console.log("Please enter a valid time");
                    }
    
                  break;
                case "number":
                    if(!Validate.isValidNumber($value)){
                      errorStatus.field.push(field.toString());
                      errorStatus.message.push("Please enter a valid number");
                      errorStatus.error=true;
                    }
                  break;
                case "rollno":
                  if(!Validate.isValidRollNo($value)){
                    errorStatus.field.push(field.toString());
                    errorStatus.message.push("Please enter a valid Roll No");
                    errorStatus.error=true;
                  }
                break;
              default:
    
              break;
              }
            }     
          } 
        }
      }
    }
    return errorStatus;
  }
}