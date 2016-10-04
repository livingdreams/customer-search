jQuery(document).ready(function ($) {

    $('form').submit(function (event) {
        $('#loading').show();
        $(this).find('button').attr('disabled', 'disabled');
        //return false;
        $.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: 'json',
            data: {action: 'cs_ajax_action', method: $(this).attr('method'), data: $(this).serialize()}, // serializes the form's elements.
            success: function (data) {
                // show response from the php script.

                $('.errorMessage').html('');
                if (data.status) {
                    if (data.message == 'reload')
                        window.location.reload(true);
                    $('.successMessage').html(data.message);
                } else {
                    $('.errorMessage').html(data.message);
                }
            }, error: function (response) {
                $('.errorMessage').html(response);
            }
        });

        event.preventDefault();
        $('#loading').hide();
        $(this).find('button').removeAttr('disabled');
        return false;
    });

});

/*Start delete issue process*/
function deleteIssue(id){
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {action: "delete_issue", id: id},
            success: function (data) {  
               if(data.status == 'success'){
                 window.location.reload(true);
                 jQuery('.successMessage').html(data.message);
               }
            }
        });
}
/*End delete issue process*/

/*Start delete customer process*/
function deleteCustomer(id){
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {action: "delete_customer", id: id},
            success: function (data) {  
               if(data.status == 'success'){
                 window.location.reload(true);
                 jQuery('.successMessage').html(data.message);
               }
            }
        });
}
/*End delete customer process*/
