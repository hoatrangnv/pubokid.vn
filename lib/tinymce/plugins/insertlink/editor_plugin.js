/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('insertlink');

	tinymce.create('tinymce.plugins.insertlinkPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceinsertvideo');
			ed.addCommand('mceinsertlink', function() {
                
			    ed.windowManager.open({
                    file : url + '/dialog.htm',
                    width : 500 + parseInt(ed.getLang('insertlink.delta_width', 0)),
                    height : 300 + parseInt(ed.getLang('insertlink.delta_height', 0)),
                    inline : 1,
                    scrollbars : 1
                }, {
                    plugin_url : url, // Plugin absolute URL   
                    arg1: 42,
                    arg2: "Hello world",                                  
                });
			});

			// Register insertvideo button
			ed.addButton('insertlink', {
				title : 'Chèn link nhân vật',
				cmd : 'mceinsertlink',
				image : url + '/img/link.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('insertlink', n.nodeName == 'IMG');
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

	});

	// Register plugin
	tinymce.PluginManager.add('insertlink', tinymce.plugins.insertlinkPlugin);
})();