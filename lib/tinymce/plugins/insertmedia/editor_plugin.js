
(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('Insertmedia');

    tinymce.create('tinymce.plugins.Insertmedia', {

        init : function(ed, url) {
            ed.addCommand('mceInsertmedia', function() {
                ed.windowManager.open({
                    file : url + '/dialog.htm',
                    width : 400 + parseInt(ed.getLang('Insertmedia.delta_width', 0)),
                    height : 240 + parseInt(ed.getLang('Insertmedia.delta_height', 0)),
                    inline : 1                    
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'custom arg', // Custom argument                    
                });
            });

            // Register example button
            ed.addButton('Insertmedia', {
                title: 'Chèn slide ảnh',
                cmd : 'mceInsertmedia',
                image : url + '/img/block.png'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('Insertmedia', n.nodeName == 'IMG');
            });
        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : 'Insertmedia Code plugin',
                author : 'Rtur.net',
                authorurl : 'http://rtur.net',
                infourl: 'http://rtur.net/blog/post/2009/11/26/Building-custom-plugin-for-TinyMCE.aspx',
                version : "1.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('Insertmedia', tinymce.plugins.Insertmediass);
})();
