
(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('Block1');

    tinymce.create('tinymce.plugins.Block1', {

        init : function(ed, url) {
            ed.addCommand('mceBlock1', function() {
                               
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'custom arg', // Custom argument                    
                });
            });

            // Register example button
            ed.addButton('Block1', {
                title: 'Chèn block tin liên quan',
                cmd : 'mceBlock1',
                image : url + '/img/block.png'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('Block1', n.nodeName == 'IMG');
            });
        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : 'Block1 Code plugin',
                author : 'Rtur.net',
                authorurl : 'http://rtur.net',
                infourl: 'http://rtur.net/blog/post/2009/11/26/Building-custom-plugin-for-TinyMCE.aspx',
                version : "1.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('Block1', tinymce.plugins.Block1);
})();
