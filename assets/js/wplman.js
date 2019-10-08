jQuery(document).ready(function() {

    /**
     * Init
     */
    jconfirm.defaults = {
        backgroundDismiss: true,
        typeAnimated: true,
        icon: 'fas fa-exclamation-triangle',
        rtl: false,
        theme: 'modern',
        scrollToPreviousElement: false,
        useBootstrap: false,
        boxWidth: '20%',
        draggable: false

    }

    function get_form_data(form_element){
        var formDataArr  = form_element.serializeArray();

        var formData = {};
        $.each(formDataArr, function(i, field){

            var value;

            if (field.value === 'true') {
                value = true;
            } else if (field.value === 'false') {
                value = false;
            } else if (!isNaN(field.value)) {
                value = parseInt(field.value);
            }
            else{
                value = field.value
            }


            formData[field.name] = value
        });

        return formData
    }


    function get_list_shortlinks(formData) {
        var Wrap = $('#list-shortlink')

        var ajax_data = {
            'action': 'wplman_shortlink_list',
            'query_data': formData
        }

        $.ajax({
            type: "GET",
            url: ajaxurl,
            data: ajax_data,
            success: function (response) {
                Wrap.find('tbody').html(response)
            },
            error: function (e) {
                alert(e)
            },
            beforeSend:function(){
                Wrap.addClass('busy')

            },
            complete:function () {
                Wrap.removeClass('busy')
            }
        })
    }
    get_list_shortlinks(false);


    function get_pagination_shortlinks(formData){
        var Wrap = $('#pagination-shortlinks')

        var ajax_data = {
            'action': 'wplman_pagnation_form',
            'query_data': formData
        }

        $.ajax({
            type: "GET",
            url: ajaxurl,
            data: ajax_data,
            success: function (response) {
                Wrap.html(response)
            },
            error: function (e) {
                alert(e)
            }
        })
    }
    get_pagination_shortlinks(false)


    $('#shortlink-posts-filter').on('submit', function(event){
        event.preventDefault();

        formData = get_form_data($(this))

        get_list_shortlinks(formData);
        get_pagination_shortlinks(formData)

    })


    $('#pagination-shortlinks').on('click', '.button' , function(event){
        event.preventDefault();
        $button = $(this)
        $page_input = $button.siblings('.paging-input').find('input')
        $page_input_val = parseInt($page_input.val())

        if(!$button.hasClass('disabled')){
            if($button.hasClass('prev-page')){
                $page_input.val( $page_input_val - 1)
            }else{
                $page_input.val( $page_input_val + 1)
            }
            $('#shortlink-posts-filter').trigger('submit')
        }
    })


    function delete_shortlink(item){
        var ajax_data = {
            'action': 'wplman_delete_shortlink',
            'post_id': item.data('id')

        }

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: ajax_data,
            success: function (response) {
                $(item).css({"background":"#ff572247"});
                setTimeout(function(){
                    $(item).remove()
                }, 900)
            },
            error: function (e) {
                console.log(e)
            }
        })
    }


    /**
     * Delete shortlink
     */
    $('#list-shortlink').on('click', '.wplman-trash>a' , function(event){
        event.preventDefault();
        $post_item = $(this).parents('tr.shortlink')

        $.alert({
            title: i18nStr.__delete,
            content: i18nStr.__sure_delete,
            type: 'red',
            icon: 'fas fa-exclamation-triangle',
            buttons: {
                remove: {
                    text: i18nStr.__delete,
                    btnClass: 'btn-red',
                    keys: ['enter'],
                    action: function(){
                        delete_shortlink($post_item)
                    }
                },
                cancel: {
                    text: i18nStr.__cancel,
                    keys: ['esc'],
                    btnClass: 'btn-default'
                }
            }
        });
    })


    /**
     * Edit shortlink
     */
    $('#list-shortlink').on('click', '.wplman-edit', function(event){
        event.preventDefault();
        $post_item = $(this).parents('tr.shortlink')
        $post_id = $post_item.data('id')

        $.confirm({
            boxWidth:'50%',
            title: i18nStr.__edit_shortlink,
            icon: false,
            buttons:{
                ok: {
                    text: i18nStr.__ok,
                    isHidden: true
                }
            },
            content: function(){
                var self = this;
                return $.ajax({
                    type:'GET',
                    url:ajaxurl,
                    data:{'action' : 'wplman_edit_form_shortlink', 'shortlink_id' : $post_id},
                    success:function(response){
                        self.setContent(response)
                    },
                    error: function(e){
                        console.log(e)
                    }
                });

            },
            contentLoaded:function(){

            },
            onClose:function(){
                get_list_shortlinks(false)
                get_pagination_shortlinks(false)
            }
        })

    })


    /**
     * Detail shortlink
     */
    $('#list-shortlink').on('click', '.wplman-detail', function(event) {
        event.preventDefault();
        $post_item = $(this).parents('tr.shortlink')
        $post_id = $post_item.data('id')

        $.confirm({
            boxWidth: '50%',
            title: i18nStr.__detail_shortlink,
            icon: false,
            buttons: {
                ok: {
                    text: i18nStr.__ok,
                    isHidden: true
                }
            },
            content: function () {
                var self = this;
                return $.ajax({
                    type: 'GET',
                    url: ajaxurl,
                    data: {'action': 'wplman_detail_shortlink', 'shortlink_id': $post_id},
                    success: function (response) {
                        self.setContent(response)
                    },
                    error: function (e) {
                        console.log(e)
                    }
                });

            },
            contentLoaded: function () {

            }
        })

    })


    /**
     * Add shortlink
     */
    $('#add-new-shortlink').on('click', function(event){
        event.preventDefault();

        $.confirm({
            boxWidth:'50%',
            title: i18nStr.__add_shortlink,
            icon: false,
            buttons:{
                ok: {
                    text: i18nStr.__ok,
                    isHidden: true
                }
            },
            content: function(){
                var self = this;
                return $.ajax({
                    type:'GET',
                    url:ajaxurl,
                    data:{'action' : 'wplman_add_form_shortlink'},
                    success:function(response){
                        self.setContent(response)
                    },
                    error: function(e){
                        console.log(e)
                    }
                });

            },
            contentLoaded:function(){

            },
            onClose:function(){
                get_list_shortlinks(false)
                get_pagination_shortlinks(false)
            }
        })

    })


    /**
     * Save shortlink (add, update)
     */
    $('body').on('submit', '#shortlink-edit-form', function(event){
        event.preventDefault();

        $form = $(this)
        $button = $form.find('.button')

        $form.addClass('busy')

        var ajax_data = {
            'action': 'wplman_save_shortlink',
            'form_data' : get_form_data($form)
        }

        console.log(get_form_data($form))

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: ajax_data,
            success: function (response) {
                $form.removeClass('busy').find('.alert-area').html(response)
            },
            error: function (e) {
                console.log(e)
            }
        })

    })
})

