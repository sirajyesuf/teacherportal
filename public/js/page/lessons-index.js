$( document ).ready(function() {
    
    $('.datepicker1').datepicker({
        changeYear: 'true',
        changeMonth: 'true',
        dateFormat: 'dd-mm-yy',
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