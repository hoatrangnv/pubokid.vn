
(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('Block');

    tinymce.create('tinymce.plugins.Block', {

        init : function(ed, url) {
            ed.addCommand('mceBlock', function() {
                ed.windowManager.open({
                    file : url + '/blockdialog.htm?4',
                    width : 400 + parseInt(ed.getLang('Block.delta_width', 0)),
                    height : 240 + parseInt(ed.getLang('Block.delta_height', 0)),
                    inline : 1                    
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'custom arg', // Custom argument                    
                });
            });

            // Register example button
            ed.addButton('Block', {
                title: 'Chèn block',
                cmd : 'mceBlock',
                image : url + '/img/block.png'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('Block', n.nodeName == 'IMG');
            });
        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : 'Block Code plugin',
                author : 'Rtur.net',
                authorurl : 'http://rtur.net',
                infourl: 'http://rtur.net/blog/post/2009/11/26/Building-custom-plugin-for-TinyMCE.aspx',
                version : "1.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('Block', tinymce.plugins.Block);
})();
