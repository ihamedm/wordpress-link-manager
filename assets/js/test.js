function get_list_shortlinks() {

    var ajax_data = {
        'action': 'myaction',
    }

    jQuery.ajax({
        type: "GET",
        url: frontendObj.ajaxurl,
        data: ajax_data,
        success: function (response) {
            jQuery('body').html(response)
        },
        error: function (e) {
            console.log(e)
        }
    })
}
get_list_shortlinks();