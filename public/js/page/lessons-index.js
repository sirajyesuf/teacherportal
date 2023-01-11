$( document ).ready(function() {
    
    $('.datepicker1').datepicker({
        changeYear: 'true',
        changeMonth: 'true',
        dateFormat: 'dd-mm-yy',
    });

    CKEDITOR.replaceAll('vestibular');
    CKEDITOR.replaceAll('proprioception');
    CKEDITOR.replaceAll('muscle_tone');
    CKEDITOR.replaceAll('reflex');
    CKEDITOR.replaceAll('kinestesia');
    CKEDITOR.replaceAll('massage');
    CKEDITOR.replaceAll('tactile');
    CKEDITOR.replaceAll('emotions');
    CKEDITOR.replaceAll('vp');
    CKEDITOR.replaceAll('ep');
    CKEDITOR.replaceAll('others');
    CKEDITOR.replaceAll('ft');

    CKEDITOR.config.mentions = [ { 
        feed: url+'/users?name={encodedQuery}',        
        minChars: 2,         
        outputTemplate: '<a href="{userId}">{name}</a>',
        
    } ];    

    $('body').on('click','.del-lesson',function(e){
        e.preventDefault();

        var id = $(this).attr('data-del-id');
        $('#delete_id').val(id);

        $('#delete_modal').modal('show');
    });

    $('body').on('click','.del-confirm',function(e) {
        e.preventDefault();

        var delId = $('#delete_id').val();

        $.ajax({
            url: deleteUrl+'?id='+delId,
            type: 'POST',
            dataType: 'json',
            success: function(result) {        
                $('#delete_modal').modal('hide');
                showMessage('success',result.message);
                setTimeout(function() {                    
                    window.location.reload();
                }, 1500);
            }
        });
    });

    if(errorLesson)
    {
        showMessage('error',errorLesson);
    }

    if(lessonCreated)
    {
        showMessage('success',lessonCreated);
    }

    if(lessonUpdated)
    {
        showMessage('success',lessonUpdated);
    }
    
});