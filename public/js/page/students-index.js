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

            $(document).on('click','#notificationDropdown', function(e){                
                e.preventDefault();

                $.ajax({
                    url: readNotiUrl,
                    type: 'GET',                                                
                    success: function(result) {                        
                    },
                    error: function(error) {
                        console.error('Something went wrong!');                        
                    }
                }); // Ajax ends
            });

            $(document).on('click','#announcementDropdown', function(e){                
                e.preventDefault();

                $.ajax({
                    url: readAnnNotiUrl,
                    type: 'GET',                                                
                    success: function(result) {                        
                    },
                    error: function(error) {
                        console.error('read error');
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
                                    
                                    var t = $( "a[data-id^="+userId+"]" ).html( dt );                                    
                                    showMessage('success','Date update success');
                                    
                                    setTimeout(function() {
                                        window.location.reload(true);                                                                        
                                    }, 1500);

                                } else {

                                }
                            },
                            error: function(error) {
                                console.error('Something went wrong!');                                
                            }
                        }); // Ajax ends
                    }                    
                } // onClose Ends
            });
        }