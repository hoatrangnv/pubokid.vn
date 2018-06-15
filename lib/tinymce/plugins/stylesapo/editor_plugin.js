(function () {
    tinymce.create("tinymce.plugins.StyleSapo", {
        init: function (a) {
            var c = this;
            c.editor = a;
            
            a.addCommand("stylesapo", function () {
                var select = a.selection.getContent({format : 'html'}); 
                if (select != '' && tinyMCE.activeEditor.selection.getNode().outerHTML.indexOf('class="styleSapo"') < 0) select = '<div class="styleSapo">'+select+'</div>';
                var d = select;
                a.execCommand("mceInsertContent", false, d)
                
            });
            
            a.addButton("stylesapo", {
                title: "Tiêu đề ảnh",
                cmd: "stylesapo",
                //image : url + '/img/stylesapo.png'
                image : 'http://www.majux.com/wp-content/uploads/2013/11/Pen-icon.png'
            });
        },
    });
    tinymce.PluginManager.add("stylesapo", tinymce.plugins.StyleSapo)
})();