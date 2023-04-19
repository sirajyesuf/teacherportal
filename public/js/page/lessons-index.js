$( document ).ready(function() {
    var stuId = $('#student_id').val();
    
    $('.datepicker1').datepicker({
        changeYear: 'true',
        changeMonth: 'true',
        dateFormat: 'dd-mm-yy',
    });

    // Generate a unique ID for each textarea element
    var textareas = document.getElementsByTagName('textarea');
    for (var i = 0; i < textareas.length; i++) {
        var textarea = textareas[i];
        textarea.id = 'editor' + (i + 1);
    }

    // Initialize a CKEditor instance for each textarea element
    for (var i = 0; i < textareas.length; i++) {
        var textarea = textareas[i];
        CKEDITOR.replace(textarea.id);
    }

    CKEDITOR.config.mentions = [ { 
        feed: url+'/users?name={encodedQuery}',        
        minChars: 2,         
        outputTemplate: '<a href="{userId}">{name}</a>',
        
    } ];    

    CKEDITOR.config.contentsCss = [
      cssUrl
    ];

    $('body').on('click','.new_sift',function(e){
        e.preventDefault();        
        
        $.ajax({
            url: newsiftUrl+'?id='+stuId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            dataType: 'json',
            success: function(result) {  
                showMessage('success','Added');

                setTimeout(function() {                    
                    window.location.href = lessonUrl;
                }, 1500);
            }
        });
    });

    $('body').on('click','.new_btlang',function(e){
        e.preventDefault();        
        
        $.ajax({
            url: newBtUrl+'?id='+stuId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            dataType: 'json',
            success: function(result) {  
                showMessage('success','Added');

                setTimeout(function() {                    
                    window.location.href = lessonBtUrl;
                }, 1500);
            }
        });
    });

    $('body').on('click','.new_im',function(e){
        e.preventDefault();        
        
        $.ajax({
            url: newImUrl+'?id='+stuId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            dataType: 'json',
            success: function(result) {  
                showMessage('success','Added');

                setTimeout(function() {                    
                    window.location.href = lessonImUrl;
                }, 1500);
            }
        });
    });

    $('body').on('click','.new_sand',function(e){
        e.preventDefault();        
        
        $.ajax({
            url: newSandUrl+'?id='+stuId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            dataType: 'json',
            success: function(result) {  
                showMessage('success','Added');

                setTimeout(function() {                    
                    window.location.href = lessonSandUrl;
                }, 1500);
            }
        });
    });

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

    // add click event listener to each submit button
    $('.forms button[type="submit"]').on('click', function(event) {
        // prevent form from submitting automatically
        event.preventDefault();
        // get the form element associated with the clicked button
        var form = $(this).closest('form');

        // Ckeditor read data
        // for (instance in CKEDITOR.instances) {        
        //     CKEDITOR.instances[instance].updateElement();
        // }

        // Update the value of each textarea element with the data from its corresponding CKEditor instance
        for (var i = 0; i < textareas.length; i++) {
            var textarea = textareas[i];
            CKEDITOR.instances[textarea.id].updateElement();
        }
        // serialize the form data
        var formData = form.serialize();
        
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            context: form,            
            success: function(result) {
                if (result.status == true) {       
                    if (result.match) {
                        if (confirm('attendance has been created at dashboard, would you like to keep this?')) {
                            form.find('.duplicate').val(1);
                            // Override values and submit form
                            form.find('button[type="submit"]').click();                            
                        }
                        form.find('.duplicate').val(0);
                        return;
                    }             
                    showMessage('success',result.message);   
                    setTimeout(function() {
                        location.reload();
                    }, 1500);                                                        
                } else {
                    showMessage('error',result.message);
                }
            },
            error: function(error) {      
                if(typeof(error.responseJSON.errors) !== 'undefined' && error.responseJSON.errors.duration.length)
                {
                    showMessage('error',error.responseJSON.errors.duration[0]);
                    return;
                }                
                showMessage('error',error.responseJSON.message);
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