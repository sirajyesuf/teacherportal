jQuery.browser = {};
        var userId;
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();

        if(studentCreated)
        {
            showMessage('success',studentCreated);
        }

        $(document).ready(function(){    
            initDatePicker();        

            $('body').on('click','.home-picker',function (e) {
                var id = $(this).attr('data-id');       
                userId = id;                    
                $('#hiddenDate_'+id).datepicker("show");                
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

            $(document).on('click','#navbarDropdown', function(e){                
                e.preventDefault();

                $.ajax({
                    url: readNotiUrl,
                    type: 'GET',                            
                    // data: { id:userId, date:selectedDate, _token: $('meta[name=csrf-token]').attr('content')},
                    // dataType: 'json',
                    success: function(result) {
                        if (result.status == true) {
                            console.log('clear');
                            // setTimeout(function() {                               
                                
                            // }, 500);

                        } else {

                        }
                    },
                    error: function(error) {
                        alert('Something went wrong!', 'error');                            
                    }
                }); // Ajax ends
            });

        });

        function destroyDatepicker(){
            $('.datePickerInput').datepicker("destroy");
            $(".datePickerInput").removeClass("hasDatepicker");
            $('#ui-datepicker-div').remove();            
        }
        
        function initDatePicker()
        {
            $('.datePickerInput').datepicker({
                changeYear: 'true',
                changeMonth: 'true',
                startDate: '1989-01-25',
                dateFormat: 'yy-mm-dd',
                firstDay: 1,
                onClose: function(selectedDate,object) {
                    
                    if(selectedDate)
                    {
                        // destroyDatepicker();

                        $.ajax({
                            url: changeDateUrl,
                            type: 'POST',                            
                            data: { id:userId, date:selectedDate, _token: $('meta[name=csrf-token]').attr('content')},
                            dataType: 'json',                            
                            success: function(result) {
                                if (result.status == true) {
                                    var dt = '';
                                    dt += '<img src='+assetClock+' alt=""> ';
                                    dt += moment(selectedDate).format('DD MMM');
                                    // console.log(dt);
                                    var t = $( "a[data-id^="+userId+"]" ).html( dt );
                                    // toastr.success('date update success');
                                    // console.log(t);
                                    showMessage('success','Date update success');
                                    // $('.jq-toast-wrap').remove();
                                    setTimeout(function() {
                                        window.location.reload(true);                                
                                        // $('.jq-toast-wrap').remove();
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
        }