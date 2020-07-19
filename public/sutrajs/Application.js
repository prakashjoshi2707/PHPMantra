class Application
{
    constructor() {}
    static getPath(){
        let domain=window.location.origin;
        let path= domain+'/phpmantra/';
        return path;        
    }
        
    static import(file,callback){
        let url=this.getPath()+'public/sutrajs/';
        this.file=url+file+'.js';
        let node=document.createElement("script");
        node.setAttribute("type","text/javascript");
        node.setAttribute("src",this.file);
        document.getElementsByTagName("body")[0].appendChild(node);
        node.onload = function() { 
            if(callback && typeof callback == "function"){
                callback();
            }
        }
        
    } 

    static getParameterByName(name, url){
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    
}