$( document ).ready(function() {

    $(document).on('click','#navbarDropdown', function(e){
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
                // alert('Something went wrong!', 'error');                            
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
