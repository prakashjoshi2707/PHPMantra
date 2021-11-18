class FileUpload
{
    static displayPhoto(input,container) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var size=input.files[0].size;
                var filePath = input.value;
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.pdf|\.txt|\.sql)$/i;
                if(!allowedExtensions.exec(filePath)){
                       alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
                       input.value = '';                       
                 }
                 else{
                     $('#'+container).attr('src', e.target.result);
                   }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    static name(input) {
        return input.files[0].name
    }
    static size(input) {
        return input.files[0].size
    }
}