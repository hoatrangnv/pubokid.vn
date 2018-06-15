

(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('netlinkImage');

    tinymce.create('tinymce.plugins.netlinkImage', {

        init : function(ed, url) {
            /*console.log(url);*/
            ed.addCommand('mceNetlinkImage', function() {              
                ed.windowManager.open({
                    file : url + '/dialog.htm',
                    width : 580 + parseInt(ed.getLang('netlinkImage.delta_width', 0)),
                    height : 320 + parseInt(ed.getLang('netlinkImage.delta_height', 0)),
                    inline : 1,
                    scrollbars : 1
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'Custom argument' // Custom argument                                        
                });
            });

            // Register example button
            ed.addButton('netlinkImage', {
                title: 'Chèn ảnh vào bài viết',
                cmd : 'mceNetlinkImage',
                image : url + '/img/code2.png'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('netlinkImage', n.nodeName == 'IMG');
            });
        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : 'Insert Code plugin',
                author : 'Rtur.net',
                authorurl : 'http://rtur.net',
                infourl: 'http://rtur.net/blog/post/2009/11/26/Building-custom-plugin-for-TinyMCE.aspx',
                version : "1.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('netlinkImage', tinymce.plugins.netlinkImage);
})();