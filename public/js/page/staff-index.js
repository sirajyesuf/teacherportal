$( document ).ready(function() {

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
                    
                } else {
                    console.error('read error');
                }
            },
            error: function(error) {
                console.error('read error');
            }
        }); // Ajax ends
    });

    $('body').on('click','.delUser',function(e){
        e.preventDefault();

        var id = $(this).attr('data-id');
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
})
