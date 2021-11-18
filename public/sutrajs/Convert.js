class Convert
{
    constructor() {}
    
    static toJSONString(form ) {
        console.log(form);
        var obj = {};
        var elements = form.querySelectorAll( "input, select, textarea, hidden" );
        console.log(elements);
        for( var i = 0; i < elements.length; ++i ) {
          var element = elements[i];
          var name = element.name;
          console.log(name);
          var value = element.value;
          console.log(value);
          if(name) {
            obj[name] = value;
          }
        }
        return  obj ;
      }
}