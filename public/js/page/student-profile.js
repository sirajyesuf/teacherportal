$( document ).ready(function() {
    var userId;

    function initDatePicker(){        
        $('.datePicker').datepicker({
            dateFormat: "dd M yy",
            altFormat: "dd M yy",
        });
    }
    initDatePicker();
    
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
                            // console.log(dt);
                            var t = $( "a[data-id^="+userId+"]" ).html( dt );
                            // toastr.success('date update success');
                            // console.log(t);
                            showMessage('success','Date update success');
                            // $('.jq-toast-wrap').remove();
                            // setTimeout(function() {
                            //     // window.location.reload(true);                                
                            //     $('.jq-toast-wrap').remove();
                            // }, 2500);

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

    $('#add_hour-tab').on('click',function(){
        $('#add_hour_button').attr('data-target','#add_lesson_log_modal');
    });

    // $('#lesson_log-tab').on('click',function(){
    //     $('#add_hour_button').attr('data-target','#add_hour_modal');
    // });

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
                    $('#add_lesson_log_modal').modal('hide');
                    setTimeout(function() {
                        // show_toast(result.message, 'success');
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
                // location.reload();
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
                    $this[0].reset();
                    showMessage('success',result.message);
                    $('#add_hour_modal').modal('hide');
                    setTimeout(function() {
                        // show_toast(result.message, 'success');
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
                // location.reload();
            }
        });
    });

    var count = 0;
    $('#add_tls').on('click',function(e){
        if(count >= 1)
            return;
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
                    console.log($this.closest("tr"));
                    $this.closest("tr").remove();                    
                    showMessage('success',result.message)

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
                // location.reload();
            }
        });
    });
});