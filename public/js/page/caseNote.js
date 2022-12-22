$(document).ready(function() {
	var stuId = $('#student_id').val();    

    $('.datepicker').datepicker({
        changeYear: 'true',
        changeMonth: 'true',
        dateFormat: 'yy-mm-dd',
    });    

    CKEDITOR.config.mentions = [ { 
        feed: url+'/users?name={encodedQuery}',        
        minChars: 2,         
        outputTemplate: '<a href="{userId}">{name}</a>',
        
    } ];

    CKEDITOR.replaceAll( 'comments');

    if(successMsg)
    {
        showMessage('success',successMsg);
    }

    if(updateFailed)
    {
        showMessage('error',updateFailed);
    }

	$('body').on('click','.add_cmm',function(e){
		e.preventDefault();
        $('#loader-cmm').show();
		
		$.ajax({
            url: addCmmUrl+'?id='+stuId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            dataType: 'json',
            success: function(result) {  
                $('#loader-cmm').hide();
                showMessage('success','Added');

                setTimeout(function() {                    
                    window.location.reload();
                }, 1500);
            }
        });
	});

	$('body').on('click','.add_prs',function(e){
		e.preventDefault();
        $('#loader-prs').show();

		$.ajax({
            url: addPrsUrl+'?id='+stuId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            dataType: 'json',
            success: function(result) {          
                $('#loader-prs').hide();    
                showMessage('success','Added');

                setTimeout(function() {                    
                    window.location.reload();
                }, 1500);
            }
        });
		
	});

	$('body').on('click','.add_com',function(e){
		e.preventDefault();
        $('#loader-com').show();

		$.ajax({
            url: addComUrl+'?id='+stuId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            dataType: 'json',
            success: function(result) {  
                $('#loader-com').hide();
                showMessage('success','Added');
                setTimeout(function() {                    
                    window.location.reload();
                }, 1500);
            }
        });
		
	});

    $('body').on('click','.del-cmm',function(e){
        e.preventDefault();
        
        var delId = $(this).attr('data-del-id');
        $('#delete_id').val(delId);

        $('#confirm').removeClass();
        $('#confirm').addClass('btn btn-save del-cmm-cnfm');

        $('#delete_modal').modal('show');
    });

    $('body').on('click','.del-prs',function(e){
        e.preventDefault();
        
        var delId = $(this).attr('data-del-id');
        $('#delete_id').val(delId);

        $('#confirm').removeClass();
        $('#confirm').addClass('btn btn-save del-prs-cnfm');

        $('#delete_modal').modal('show');
    });

    $('body').on('click','.del-com',function(e){
        e.preventDefault();
        
        var delId = $(this).attr('data-del-id');
        $('#delete_id').val(delId);

        $('#confirm').removeClass();
        $('#confirm').addClass('btn btn-save del-com-cnfm');

        $('#delete_modal').modal('show');
    });

    $('body').on('click','.del-cmm-cnfm',function(e){
        e.preventDefault();

        var delId = $('#delete_id').val();

        $.ajax({
            url: deleteCmmUrl,
            data: { id:delId, stuId:stuId  },
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

    $('body').on('click','.del-prs-cnfm',function(e){
        e.preventDefault();

        var delId = $('#delete_id').val();

        $.ajax({
            url: deletePrsUrl,
            data: { id:delId, stuId:stuId  },
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

    $('body').on('click','.del-com-cnfm',function(e){
        e.preventDefault();

        var delId = $('#delete_id').val();

        $.ajax({
            url: deleteComUrl,
            data: { id:delId, stuId:stuId  },
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


});