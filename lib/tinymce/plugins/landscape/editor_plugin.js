(function () {
    tinymce.create("tinymce.plugins.Landscape", {
        init: function (a) {
            var c = this;
            c.editor = a;
            
            a.addCommand("landscape", function () {
                var select = a.selection.getContent({format : 'html'}); 
                if (select != '' && tinyMCE.activeEditor.selection.getNode().outerHTML.indexOf('class="landscape"') < 0) select = '<span class="landscape">'+select+'</span>';
                var d = select;
                a.execCommand("mceInsertContent", false, d)
                
            });
            
            a.addButton("landscape", {
                title: "Ảnh ngang",
                cmd: "landscape",
                image : '/lib/tinymce/plugins/landscape/img/landscape.png'
            });
        },
    });
    tinymce.PluginManager.add("landscape", tinymce.plugins.Landscape)
})();