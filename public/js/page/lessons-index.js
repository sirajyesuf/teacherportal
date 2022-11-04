$( document ).ready(function() {
    
    $('.datepicker1').datepicker({
                    changeYear: 'true',
                    changeMonth: 'true',
                    dateFormat: 'dd-mm-yy',
        });
    showMessage('error',errorLesson)
});