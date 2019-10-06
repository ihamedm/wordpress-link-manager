jQuery(function($){

    // Set all variables to be used in scope
    var frame,
        wrap = $('.minapp-field-image-wrap'),
        addImgLink = wrap.find('.upload-image'),
        delImgLink = wrap.find( '.delete-image');

    // ADD IMAGE LINK
    addImgLink.on( 'click', function( event ){
        $this = $(this)
        imgContainer = $this.parent('.minapp-field-image-wrap').find( '.image-preview')
        imgIdInput = $this.parent('.minapp-field-image-wrap').find( 'input[type=hidden]' )
        delImgLink = $this.next( '.delete-image')


        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: i18n.mediamodal_title,
            button: {
                text: i18n.choose_image_btn
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });


        // When an image is selected in the media frame...
        frame.on( 'select', function() {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom image input field
            imgContainer.empty().append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' ).slideDown(50);

            // Send the attachment id to our hidden input
            imgIdInput.val( attachment.id ).trigger('change').parents('form').trigger('change');
            imgIdInput.attr( 'data-src' , attachment.url );


            // Hide the add image link
            $this.addClass( 'hidden' );

            // Unhide the remove image link
            delImgLink.removeClass( 'hidden' );
        });

        // Finally, open the modal on click
        frame.open();
    });


    // DELETE IMAGE LINK
    delImgLink.on( 'click', function( event ){
        $this = $(this)
        imgContainer = $this.parent('.minapp-field-image-wrap').find( '.image-preview')
        addImgLink = $this.parent('.minapp-field-image-wrap').find( '.upload-image')
        imgIdInput = $this.parent('.minapp-field-image-wrap').find( 'input.image' )

        event.preventDefault();

        // Clear out the preview image
        imgContainer.slideUp(50).html( '' );

        // Un-hide the add image link

        addImgLink.removeClass( 'hidden' );

        // Hide the delete image link
        $this.addClass( 'hidden' );

        // Delete the image id from the hidden input
        imgIdInput.val( '' );

    });

});