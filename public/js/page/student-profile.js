$( document ).ready(function() {
    var userId;

    function initDatePicker(){        
        $('.datePicker').datepicker({
            dateFormat: "dd M yy",
            altFormat: "dd M yy",            
        });
    }
    initDatePicker();

    CKEDITOR.replace('description', {
      on: {
        instanceReady: function(event) {
          event.editor.element.removeClass('cke-hidden');
        }
      }
    });

    CKEDITOR.config.contentsCss = [
      cssUrl
    ];

    $('body').on('click','.delete-student',function(){
        $('#delete_modal').modal('show');
    });

    // student name update script
    $(document).on('click', '.editable', function() {
        var studentId = $(this).data('student-id');
        var name = $(this).text();
        $(this).html('<input type="text" name="name" id="nameUpdate" value="' + name + '">');
        $('input[name="name"]').focus();
    });

    $(document).on('click','.checked', function(){
        var checkId = $(this).attr('data-check-id');
                         
        $.ajax({
            url: checkDateUrl,
            type: 'POST',
            data: { id:checkId,_token: $('meta[name=csrf-token]').attr('content')},
            dataType: 'json',
            success: function(result) {                    
                           
                showMessage('success',result.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }
        }); 

    return;               

    });

    var clicked = false;
    $(document).on('click', 'input[name="name"]', function() {
        clicked = true;
    }).on('blur', 'input[name="name"]', function() {
        if (clicked) {
            clicked = false;
            return;
        }
        var name = $(this).val();
        var studentId = $(this).closest('.editable').data('student-id');
        var url = $('.editable[data-student-id="' + studentId + '"]').data('url');
        $.ajax({
            url: url,
            method: 'PUT',
            data: {
                'name': name
            },
            success: function(response) {
                $('.editable[data-student-id="' + studentId + '"]').html(response);
            },
            error: function(response) {
                alert('Error updating name.');
            }
        });
    });
    // student name update script Ends

    $(document).on('click','.edit_add_hour',function(){
        
        var dataId = $(this).attr('data-id');
        $('#edit_log_hour_id').val(dataId);

        $.ajax({
            url: logHourDetailUrl+'?id='+dataId,
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                if(result.status){                            
                    $('#edit_add_hour').val(result.detail.hours)
                    $('#edit_note').val(result.detail.notes)

                    $('#edit_hour_log_modal').modal('show');

                }else{                    
                    showMessage('error',result.message);
                    setTimeout(function() {
                        $('#edit_hour_log_modal').modal('hide');
                    }, 1500);
                    $('.error').html("");
                }
            }
        });
    })

    $(document).on('click','.edit_lesson_hour',function(){
        
        var dataId = $(this).attr('data-id');

        $('#edit_lesson_log_id').val(dataId);

        $.ajax({
            url: lessonHourDetailUrl+'?id='+dataId,
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                if(result.status){                            
                    $('#edit_lesson_log_hour').val(result.detail.hours);
                    $('#edit_lesson_date').val(result.date);
                    $('#edit_program').val(result.detail.program);

                    var $newOption = $("<option selected='selected'></option>").val(result.id).text( result.username); 
                    $("#edit_trainer_name").append($newOption).trigger('change');                    

                    $('#edit_lesson_hour_modal').modal('show');

                }else{                    
                    showMessage('error',result.message);
                    setTimeout(function() {
                        $('#edit_lesson_hour_modal').modal('hide');
                    }, 1500);
                    $('.error').html("");
                }
            },
            error: function(error) {                         
                showMessage('error',error.responseJSON.message);                
            }
        });
    })

    $("#trainer_name,#edit_trainer_name").select2({    
        dropdownCssClass: "category-member",
        minimumInputLength: 2,
        ajax: {
        url: getTrainerName,
        data: function (params) {
            var query = {
                search: params.term,        
            }      
            return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }    
    });

    $(document).on('click','.delete_add_hour',function(){
        
        var delDataId = $(this).attr('data-id');        

        if(confirm('Are you sure want to delete?')){
            $.ajax({
                url: addHourDeleteUrl+'?id='+delDataId,
                type: 'POST',
                dataType: 'json',
                success: function(result) {                            
                    showMessage('success',result.message);
                    setTimeout(function() {                    
                        window.location.reload();                    
                    }, 1500);
                }
            });
        }
    })

    $(document).on('click','.delete_lesson_hour',function(){
        
        var delDataId = $(this).attr('data-id');    
        var dataType = $(this).attr('data-type');
        if(dataType)
        {
            $msg = 'Do You want to delete this item? there is a lesson note created.'
        }else{
            $msg = 'Do You want to delete this item?'
        }    
        
        if(confirm($msg)){
            $.ajax({
                url: lessonLogDeleteUrl+'?id='+delDataId,
                type: 'POST',
                dataType: 'json',
                success: function(result) {                            
                    showMessage('success',result.message);
                    setTimeout(function() {                    
                        window.location.reload();                    
                    }, 1500);
                },
                error: function(error) {                            
                    showMessage('error',error.responseJSON.message);                    
                }
            });
        }
    })

    $('body').on('click','.del-student',function(){
        var delId = $('#delete_id').val();

        $.ajax({
            url: deleteUrl+'?id='+delId,
            type: 'POST',
            dataType: 'json',
            success: function(result) {        
                $('#delete_modal').modal('hide');
                showMessage('success',result.message);
                setTimeout(function() {                    
                    window.location.href = homeUrl;                    
                }, 1500);
            }
        });

    });
    
    $('#appointment_date').datepicker({
        changeYear: 'true',
        changeMonth: 'true',
        startDate: '1989-01-25',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        onClose: function(selectedDate,object) {
            
            if(selectedDate)
            {
                $.ajax({
                    url: changeDateUrl,
                    type: 'POST',                            
                    data: { id:userId, date:selectedDate, _token: $('meta[name=csrf-token]').attr('content')},
                    dataType: 'json',                            
                    success: function(result) {
                        if (result.status == true) {
                            var dt = '';
                            dt += moment(selectedDate).format('DD MMM YY');                            
                            var t = $( "a[data-id^="+userId+"]" ).html( dt );
                            
                            showMessage('success','Date update success');
                            
                            setTimeout(function() {
                                window.location.reload(true);                                
                                
                            }, 1500);

                        } else {

                        }
                    },
                    error: function(error) {
                        alert('Something went wrong!', 'error');                            
                    }
                }); // Ajax ends
            }                    
        } // onClose Ends
    });    

    $('body').on('click','.home-picker-profile',function (e) {
        var id = $(this).attr('data-id');
        userId = id;
        $('#appointment_date').datepicker("show");                
    });

    // create/add hours form submit
    $('#log-hours-add-form').submit(function(event) {
        event.preventDefault();        
        var $this = $(this);
        $.ajax({
            url: addLogHoursUrl,
            type: 'POST',
            data: $('#log-hours-add-form').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status == true) {
                    $this[0].reset();
                    showMessage('success',result.message);
                    $('#add_hour_log_modal').modal('hide');
                    setTimeout(function() {                        
                        location.reload();
                    }, 2500);

                    $('.error').html("");                        

                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.message, function(key) {
                        if(first_input=="") first_input=key;
                        $('#'+key).closest('.form-group').find('.error').html(result.message[key]);
                    });
                    $('#log-hours-add-form').find("#"+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');                
            }
        });
    });

    // update hours form submit
    $('#log-hours-update-form').submit(function(event) {
        event.preventDefault();        
        var $this = $(this);
        $.ajax({
            url: updateLogHoursUrl,
            type: 'POST',
            data: $('#log-hours-update-form').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status == true) {
                    $this[0].reset();
                    showMessage('success',result.message);
                    $('#edit_hour_log_modal').modal('hide');
                    setTimeout(function() {                        
                        location.reload();
                    }, 2500);

                    $('.error').html("");                        

                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.message, function(key) {
                        if(first_input=="") first_input=key;
                        $('#'+key).closest('.form-group').find('.error').html(result.message[key]);
                    });
                    $('#log-hours-update-form').find("#"+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');                
            }
        });
    });

    // completed hours form submit
    $('#hours-completed-log-form').submit(function(event) {
        event.preventDefault();        
        var $this = $(this);
        $.ajax({
            url: hoursCompletedLogUrl,
            type: 'POST',
            data: $('#hours-completed-log-form').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status == true) {
                    if (result.match) {
                        if (confirm('attendance has been created at dashboard, would you like to overwrite this?')) {
                            $('#duplicate').val(1);
                        // Override values and submit form
                        $('#hours-completed-log-form').submit();
                      }
                    $('#duplicate').val(0);
                    return;
                    }
                    $this[0].reset();
                    showMessage('success',result.message);
                    $('#add_lesson_hour_modal').modal('hide');
                    setTimeout(function() {                        
                        location.reload();
                    }, 2500);

                    $('.error').html("");                        

                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.message, function(key) {
                        if(first_input=="") first_input=key;
                        $('#'+key).closest('.form-group').find('.error').html(result.message[key]);
                    });
                    $('#hours-completed-log-form').find("#"+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');                
            }
        });
    });

    // completed hours update form submit
    $('#update-lesson-log-form').submit(function(event) {
        event.preventDefault();        
        var $this = $(this);
        $.ajax({
            url: hoursCompletedLogUpdateUrl,
            type: 'POST',
            data: $('#update-lesson-log-form').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status == true) {
                    if (result.match) {
                        if (confirm('attendance has been created at dashboard, would you like to overwrite this?')) {
                            $('#edit_duplicate').val(1);
                        // Override values and submit form
                        $('#update-lesson-log-form').submit();
                      }
                    $('#edit_duplicate').val(0);
                    return;
                    }
                    $this[0].reset();
                    showMessage('success',result.message);
                    $('#edit_lesson_hour_modal').modal('hide');
                    setTimeout(function() {                        
                        location.reload();
                    }, 2500);

                    $('.error').html("");                        

                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.message, function(key) {
                        if(first_input=="") first_input=key;
                        $('#'+key).closest('.form-group').find('.error').html(result.message[key]);
                    });
                    $('#hours-completed-log-form').find("#"+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');                
            }
        });
    });

    var count = 0;
    $('#add_tls').on('click',function(e){
        if(count >= 1)
        {
            alert('Save existing tls first!');
            return;
        }
        $('#tls_form').attr('action',tlsAddUrl);
        tlsHtml = '';        
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" name="date"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" name="program"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" name="music_day"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" name="music_prog"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" name="duration"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr><td class="border-none save-btn pl-0 text-left" colspan="6"><button type="submit">Save</button></td><tr>';        
        $('#tls_table').append(tlsHtml);
        count++;
    });

    $('#add_tls_13').on('click',function(e){
        if(count >= 1)
        {
            alert('Save existing tls first!');
            return;
        }
        $('#tls_form').attr('action',tlsMultiAddUrl);
        tlsHtml = '';        
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_1" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_1" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_1" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_1" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_1" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_2" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_2" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_2" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_2" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_2" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_3" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_3" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_3" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_3" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_3" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_4" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_4" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_4" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_4" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_4" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_5" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_5" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_5" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_5" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_5" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_6" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_6" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_6" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_6" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_6" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_7" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_7" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_7" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_7" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_7" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_8"name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_8" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_8" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_8" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_8" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_9" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_9" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_9" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_9" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_9" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_10" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_10" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_10" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_10" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_10" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_11" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_11" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_11" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_11" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_11" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_12" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_12" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_12" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_12" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_12" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr>';
        tlsHtml += '<td><input type="date" placeholder="date" id="date_13" name="date[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Program" id="program_13" name="program[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Day" id="music_day_13" name="music_day[]"></td>';
        tlsHtml += '<td><input type="text" placeholder="Music Program" id="music_prog_13" name="music_prog[]"></td>';
        tlsHtml += '<td><input type="number" step="0.5" placeholder="Duration" id="duration_13" name="duration[]"></td>';
        tlsHtml += '<td></td></tr>';
        tlsHtml += '<tr><td class="border-none save-btn pl-0 text-left" colspan="6"><button type="submit">Save</button></td><tr>';        
        $('#tls_table').append(tlsHtml);
        count++;
    });

    $(document).on('change','#date_1',function(e){
        e.preventDefault();
        var newDate = $(this).val();

        for (let i = 2; i < 14; i++) {  
            var date = new Date(newDate);

            date.setDate(date.getDate() + 1);
            convertedDate = moment(date,'ddd MMM DD YYYY HH:mm:ss Z').format('YYYY-MM-DD');
            $('#date_'+i).val(convertedDate);

            newDate = date;
        }
    });

    $(document).on('change','#music_day_1',function(e){
        e.preventDefault();
        var musicDay = $('#music_day_1').val();
        
        for (let i = 2; i < 14; i++) {  
            musicDay = parseInt(musicDay) + 1;            
            $('#music_day_'+i).val(musicDay);            

        }
    });

    $(document).on('change','#duration_1',function(e){
        e.preventDefault();
        var duration = $('#duration_1').val();
        
        for (let i = 2; i < 14; i++) {              
            $('#duration_'+i).val(duration);
        }
    });

    $(document).on('change','#program_1',function(e){
        e.preventDefault();
        var program = $('#program_1').val();
        
        for (let i = 2; i < 14; i++) {              
            $('#program_'+i).val(program);
        }
    });

    $(document).on('change','#music_prog_1',function(e){
        e.preventDefault();
        var mu_program = $('#music_prog_1').val();
        
        for (let i = 2; i < 14; i++) {              
            $('#music_prog_'+i).val(mu_program);
        }
    });

    $('body').on('click','.delete_tls',function(event) {
        var id = $(this).attr('data-id');
        var $this = $(this);
        
        if(confirm('Are you sure want to delete?')){
            $.ajax({
                url: tlsDeleteUrl+'?id='+id,
                type: 'POST',
                data: { id:id,_token: $('meta[name=csrf-token]').attr('content')},
                dataType: 'json',
                success: function(result) {                    
                    $this.closest("tr").remove();                    
                    showMessage('success',result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);


                }
            });    
        }

    });

    $('body').on('click','.edit_tls',function(event) {
        var id = $(this).attr('data-id');

        $('#update_id').val(id);            

        $.ajax({
            url: tlsDetailUrl+'?id='+id,
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                if(result.status){                            
                    $.each(result.detail,function(key){
                    
                    $('#edit_tls_form').find('#'+key).val(result.detail[key]);
                    });  

                    $("#date").val( moment().format('DD MMM YY') );

                    $('#edit_tls_modal').modal('show');

                }else{
                    showFlash(editmsgElement, result.message, 'error');
                    setTimeout(function() {
                        $('#edit_tls_modal').modal('hide');
                    }, 1500);
                    $('.error').html("");
                }
            }
        });
    });

    $('#edit_tls_form').submit(function(event) {
        event.preventDefault();
        var $this = $(this);            
        var id = $('#update-id').val();

        $.ajax({
            url: tlsUpdateUrl,
            type: 'POST',
            data: $('#edit_tls_form').serialize(),                
            dataType: 'json',
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status == true) {

                    $('#edit_tls_modal').modal('hide');
                    showMessage('success',result.message)
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                    $('.error').html("");
                }
                else if(result.status == false && result.error == 'true')
                {
                    showMessage('error',result.message);
                    
                }else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.message, function(key) {
                        if(first_input=="") first_input=key;
                        $('#edit_tls_form').find('#'+key).closest('.form-group').find('.error').html(result.message[key]);
                    });
                    $('#edit_tls_form').find("."+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something want wrong!', 'error');                
            }
        });
    });
});