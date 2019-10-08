(function($) {
jQuery(document).ready(function() {


    function get_form_data(form_element) {
        var formDataArr = form_element.serializeArray();

        var formData = {};
        $.each(formDataArr, function (i, field) {

            var value;

            if (field.value === 'true') {
                value = true;
            } else if (field.value === 'false') {
                value = false;
            } else if (!isNaN(field.value)) {
                value = parseInt(field.value);
            } else {
                value = field.value
            }


            formData[field.name] = value
        });

        return formData
    }


    function get_list_shortlinks(formData) {
        var Wrap = $('#wplman-list-shortlinks-frontend')

        var ajax_data = {
            'action': 'wplman_shortlink_list_frontend_action',
            'query_data': formData
        }

        $.ajax({
            type: "GET",
            url: frontendObj.ajaxurl,
            data: ajax_data,
            dataType: "html",
            success: function (response) {
                Wrap.find('tbody').html(response)
            },
            error: function (e) {
                console.log(e)
            },
            beforeSend: function () {
                Wrap.addClass('busy')

            },
            complete: function () {
                Wrap.removeClass('busy')
            }
        })
    }
    get_list_shortlinks(false);


    function get_pagination_shortlinks(formData) {
        var Wrap = $('#wplman-list-shortlinks-frontend').find('#pagination-shortlinks')

        var ajax_data = {
            'action': 'wplman_pagnation_form_frontend_action',
            'query_data': formData
        }

        $.ajax({
            type: "GET",
            url: frontendObj.ajaxurl,
            data: ajax_data,
            success: function (response) {
                Wrap.html(response)
            },
            error: function (e) {
                console.log(e)
            }
        })
    }

    get_pagination_shortlinks(false)


    $('#shortlink-posts-filter').on('submit', function (event) {
        event.preventDefault();

        formData = get_form_data($(this))

        get_list_shortlinks(formData);
        get_pagination_shortlinks(formData)

    })


    $('#pagination-shortlinks').on('click', '.button', function (event) {
        event.preventDefault();
        $button = $(this)
        $page_input = $button.siblings('.paging-input').find('input')
        $page_input_val = parseInt($page_input.val())

        if (!$button.hasClass('disabled')) {
            if ($button.hasClass('prev-page')) {
                $page_input.val($page_input_val - 1)
            } else {
                $page_input.val($page_input_val + 1)
            }
            $('#shortlink-posts-filter').trigger('submit')
        }
    })

})
})(jQuery);