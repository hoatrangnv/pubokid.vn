tinyMCEPopup.requireLangPack();
    var inst = tinyMCEPopup.editor;
    var elm_ = inst.selection.getNode();
    var action = "insert";
    var exista = false;
var ExampleDialog = {

        
	init: function () {
        var href = "";
       elm = inst.dom.getParent(elm_, "A");
       
       if (elm_.nodeName == "EM2") {
            if (elm != null && elm.nodeName == "A") {
                href = inst.dom.getAttrib(elm, 'href');
                exista = true;
            } else {
                href = '';
            }
            action = "update"; 
       }
       
       if(action == "update") {
            $('#url_link').val(href);
       }

    },

	insert : function() {
		// Insert the contents from the input into the document

        var title = elm_.innerHTML;
        console.log(title);
                
        if(action == "update") {
            if(exista)  inst.dom.remove(inst.dom.getParent(elm_, "A")); else
            inst.dom.remove(elm_);
        }
                        
        var href_ = $('#url_link').val();
        if(href_) html_ = '<a href="'+href_+'"><em2>'+title+'</em2></a>'; 
        else html_ = '<em2>'+title+'</em2>';
        
		inst.execCommand('mceInsertContent', false, html_);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(ExampleDialog.init, ExampleDialog);
