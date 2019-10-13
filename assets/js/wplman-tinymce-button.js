(function() {
    tinymce.create("tinymce.plugins.wplman_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(editor, url) {

            //add new button
            editor.addButton("wplman", {
                title : "Add link from link manager",
                cmd : "wplman_command",
                image : url + '/../img/wplman-tinymce-button.svg'
            });

            //button functionality.
            editor.addCommand("wplman_command", function() {

                tb_show('Inser link from link manager', 'admin-ajax.php?action=insert_shortlink')


            });

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "link manager button",
                author : "Hamed Movasaqpoor",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("wplman_button_plugin", tinymce.plugins.wplman_button_plugin);
})();