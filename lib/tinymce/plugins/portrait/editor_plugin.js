(function () {
    tinymce.create("tinymce.plugins.Portrait", {
        init: function (a) {
            var c = this;
            c.editor = a;
            
            a.addCommand("portrait", function () {
                var select = a.selection.getContent({format : 'html'}); 
                if (select != '' && tinyMCE.activeEditor.selection.getNode().outerHTML.indexOf('class="portrait"') < 0) select = '<span class="portrait">'+select+'</span>';
                var d = select;
                a.execCommand("mceInsertContent", false, d)
                
            });
            
            a.addButton("portrait", {
                title: "Ảnh dọc",
                cmd: "portrait",
                image : '/lib/tinymce/plugins/portrait/img/portrait.png'
            });
        },
    });
    tinymce.PluginManager.add("portrait", tinymce.plugins.Portrait)
})();