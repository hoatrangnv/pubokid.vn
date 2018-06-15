/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('caption', 'en,hu');

function TinyMCE_caption_getControlHTML(control_name) {
    switch (control_name) {
        case "caption":
            return '<img id="{$editor_id}_caption" src="{$pluginurl}/images/caption.gif" title="{$lang_insert_caption_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceCaption\');" />';
    }
    return "";
}

/**
 * Executes the caption command.
 */
function TinyMCE_caption_execCommand(editor_id, element, command, user_interface, value) {
    // Handle commands
    switch (command) {
        case "mceCaption":
            var template = new Array();
            template['file']   = '../../plugins/caption/caption.htm'; // Relative to theme
            template['width']  = 550;
            template['height'] = 400;
            var selElem;
            if (tinyMCE.selectedElement != null && tinyMCE.selectedElement.nodeName.toLowerCase() == "img"){
                selElem = tinyMCE.selectedElement;               
                tinyMCE.openWindow(template, {editor_id : editor_id,  selElem : selElem, mceDo : 'update'});
            }                     
       return true;
   }
   // Pass to next handler in chain
   return false;
}

function TinyMCE_caption_handleNodeChange(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
	tinyMCE.switchClassSticky(editor_id + '_caption', 'mceButtonNormal');

	if (node == null)
		return;

	do {
		if (node.nodeName.toLowerCase() == "img")
			tinyMCE.switchClassSticky(editor_id + '_caption', 'mceButtonSelected');
	} while ((node = node.parentNode));
	
	return true;
}