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
                if(id == 1)
                    window.location.href = lessonUrl;
                else if(id == 2)
                    window.location.href = lessonBtUrl;
                else if(id == 3)
                    window.location.href = lessonImUrl;
            } else {
                
            }
        },
        error: function (error) {
            
        }
    })
})