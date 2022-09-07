$(function(){
    var fileInput = $('.custom-file-input');
    var maxSize = fileInput.data('max-size');
    $('.uploadForm').submit(function(e)
    {
        if(fileInput.get(0).files.length)
        {
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if(fileSize>maxSize)
            {
                alert('File size is too large, please upload a file less than 5 MB');
                return false;
            }
        }
        else
        {
            alert('Please select a file to upload');
            return false;
        }

    });
});