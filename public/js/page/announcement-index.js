$( document ).ready(function() {

	function initDatePicker(){        
        $('.datePicker').datepicker({
            dateFormat: "dd M yy",
            altFormat: "dd M yy",   

        });
        $('.datePicker').datepicker('setDate', new Date());
    }

    initDatePicker();

	$("#recipients").select2({
        placeholder: "select recipients",
        minimumInputLength: 2,
        width: "resolve",
        allowClear: true,
        multiple: true,
        ajax: {
            url: getRecipientName,
            data: function (params) {
                var query = {
                    search: params.term,
                };
                return query;
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
        },
    });  

    $('#announcementall').on('hidden.bs.modal', function(e) {
        $('.error').html("");      
        $('#announcementallForm')[0].reset();
        $('.datePicker').datepicker('setDate', new Date());
        CKEDITOR.instances.content.setData('');
    })

    $('#announcementInd').on('hidden.bs.modal', function(e) {
        $('.error').html("");      
        $('#announcementindiForm')[0].reset();
        $("#recipients").val(null).trigger('change');
        CKEDITOR.instances.content1.setData('');
        $('.datePicker').datepicker('setDate', new Date());
    })

    $(document).on('click','#notificationDropdown', function(e){
        e.preventDefault();

        $.ajax({
            url: readNotiUrl,
            type: 'GET',                                        
            success: function(result) {
                if (result.status == true) {

                } else {

                }
            },
            error: function(error) {
                console.log(error)                
            }
        }); // Ajax ends
    });

    $(document).on('click','#announcementDropdown', function(e){                
        e.preventDefault();

        $.ajax({
            url: readAnnNotiUrl,
            type: 'GET',                                                
            success: function(result) {
                if (result.status == true) {                            
                    console.log('read success');
                } else {
                    console.error('read error');
                }
            },
            error: function(error) {
                console.error('read error');
            }
        }); // Ajax ends
    });    

    $('#announcementallForm').submit(function(event) {
        event.preventDefault();        
        var $this = $(this);

        for ( instance in CKEDITOR.instances ) {        	
            CKEDITOR.instances[instance].updateElement();
        }

        $.ajax({
            url: $this.attr('action'),
            type: 'POST',
            data: $('#announcementallForm').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status == true) {
                    $this[0].reset();
                    showMessage('success',result.message);
                    $('#announcementall').modal('hide');
                    $('.error').html("");                        
                    setTimeout(function() {
                        location.reload();
                    }, 1500);

                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.message, function(key) {
                        if(first_input=="") first_input=key;                        
                        $('#'+key).closest('.fg').find('.error').html(result.message[key]);
                        $('#announcementallForm').find("#"+first_input).focus();                        
                    });
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');                
            }
        });
    });

    $('#announcementindiForm').submit(function(event) {
        event.preventDefault();        
        var $this = $(this);

        for ( instance in CKEDITOR.instances ) {            
            CKEDITOR.instances[instance].updateElement();
        }

        $.ajax({
            url: $this.attr('action'),
            type: 'POST',
            data: $('#announcementindiForm').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status == true) {
                    $this[0].reset();
                    showMessage('success',result.message);
                    $('#announcementInd').modal('hide');
                    $('.error').html(""); 
                    setTimeout(function() {
                        location.reload();
                    }, 1500);                       

                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.message, function(key) {
                        if(first_input=="") first_input=key;
                        if(key == 'date')
                        {
                            $('#date1').closest('.fg').find('.error').html(result.message[key]);
                            $('#announcementindiForm').find("#date1").focus();                            
                        } else if(key == 'trainer') {
                            $('#trainer1').closest('.fg').find('.error').html(result.message[key]);
                            $('#announcementindiForm').find("#trainer1").focus();                            
                        } else if(key == 'title') {
                            $('#title1').closest('.fg').find('.error').html(result.message[key]);
                            $('#announcementindiForm').find("#title1").focus();                            
                        } else if(key == 'content') {
                            $('#content1').closest('.fg').find('.error').html(result.message[key]);
                            $('#announcementindiForm').find("#content1").focus();                            
                        } else {
                            $('#'+key).closest('.fg').find('.error').html(result.message[key]);
                            $('#announcementindiForm').find("#"+first_input).focus();
                        }
                    });
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');                
            }
        });
    });


});