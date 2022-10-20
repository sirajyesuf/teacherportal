$("input[type='radio']").click(function(){
	var id = $(this).val();
	var uId = $('#student_id').val();

	$.ajax({
        url: templateSelectionUrl,
        method: 'POST',
        data: {
            id: id,
            student_id: uId,
            _token: $('meta[name=csrf-token]').attr('content'),
        },
        dataType: 'json',
        success: function (result) {
            if (result.status) {
                window.location.href = lessonUrl;
            } else {
                
            }
        },
        error: function (error) {
            
        }
    })
})