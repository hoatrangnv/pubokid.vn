<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="<?= PCMS_URL ?>/favicon.png" />
    <title><?php echo $title_page ?></title>
    <meta name="description" content="<?php echo $description_page ?>" />
    <meta name="keywords" content="<?php echo $keyword_page ?>" />
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.min.js?v=1"></script>
    
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/tooltip.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jHtmlArea-0.7.0.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/engage.itoggle.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.color.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.fancybox-1.3.4.pack.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/script.js?v2"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.slimscroll.js"></script>
    
    <script type="text/javascript" src="lib/tinymce/jquery.tinymce.js?v3"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery.timeago.js"></script>	
    <script type="text/javascript">
    jQuery(document).ready(function() {
        //jQuery("time").timeago();
    });
    </script>
    <script type="text/javascript" src="/lib/jwplayer/jwplayer.js" ></script>
    <script type="text/javascript">jwplayer.key="N8zhkmYvvRwOhz4aTGkySoEri4x+9pQwR7GHIQ==";</script>
    <link href="<?php echo URL_CSS ?>/engage.itoggle.css" rel="stylesheet" />
    <link href="<?php echo URL_CSS ?>/style.css?v3" rel="stylesheet" />
    <link href="<?php echo URL_CSS ?>/jquery.fancybox-1.3.4.css" rel="stylesheet" />
    <link rel="stylesheet" media="all" type="text/css" href="<?php echo URL_CSS ?>/jquery-ui.css" />
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo URL_JS ?>/jquery-ui-timepicker-addon.js"></script>
    <style>
        .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
        .ui-timepicker-div dl { text-align: left; }
        .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
        .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
        .ui-timepicker-div td { font-size: 90%; }
        .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
    </style>

<script type="text/javascript" src="<?= URL_JS ?>/pixlr.js?v=2"></script>
    
<script type="text/javascript">
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : 'lib/tinymce/tiny_mce.js?v3',
            relative_urls : false,
            remove_script_host : false,
            
            table_styles : "Caption=caption;Table Thethao24=table_thethao24",

			// General options
			theme : "advanced",
			plugins : "jqueryimage,insertlink,visualblocks,youtubeIframe,embed,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager,netlinkImage,Block,insertvideo",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,selectall|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,youtubeIframe,fullscreen",
			theme_advanced_buttons4 : "insertlink,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,embed,visualblocks, Block,jqueryimage",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false, width: 917, height: 600,
            extended_valid_elements : "video[id|controls|src|width|height|preload|class|poster],source[src|type],object[width|height|type|data],param[name|value],"
            + "iframe[src|frameborder|style|scrolling|class|width|height|name|align|allowfullscreen],div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|data|style|title|allowfullscreen]" + ",figure[video|figcaption],figcaption[class]",
            
            //extended_valid_elements : "input[accept|alt|checked|disabled|maxlength|name|readonly|size|style|type|value]",
            
            // Example content CSS (should be your site CSS)
			content_css : "<?= URL_CSS  ?>/content.css?v31",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js", 
            
			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
        $('textarea.tinymcemini').tinymce({
			script_url : 'lib/tinymce/tiny_mce.js', relative_urls : false, remove_script_host : false, theme : "advanced",
	        plugins : "tinyautosave,insertlink,filemanagerout,visualblocks,youtubeIframe,embed,jqueryvideo,vote,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager,netlinkImage,Block,insertvideo",
            theme_advanced_buttons1 : "filemanagerout,code,link,unlink,image,media,preview,fullscreen,jqueryvideo,visualblocks,removeformat,bold,italic,underline,strikethrough,",
			theme_advanced_buttons2 : "", theme_advanced_buttons3 : "", theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "bottom", theme_advanced_toolbar_align : "left", theme_advanced_statusbar_location : "",
			theme_advanced_resizing : false, width: 917, height: 350,
            
            extended_valid_elements : "video[id|controls|src|width|height|preload|class|poster],source[src|type],object[width|height|type|data],param[name|value],"
            + "iframe[src|frameborder|style|scrolling|class|width|height|name|align|allowfullscreen],div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|data|style|title|allowfullscreen]" + ",figure[video|figcaption],figcaption[class]",
            
            content_css : "<?= URL_CSS  ?>/content.css?v23",
		});
        $('textarea.tinymcemini2').tinymce({
			script_url : 'lib/tinymce/tiny_mce.js', relative_urls : false, remove_script_host : false, theme : "advanced",
			plugins : "jqueryimage,tinyautosave,insertlink,filemanagerout,visualblocks,youtubeIframe,embed,jqueryvideo,vote,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager,netlinkImage,Block,insertvideo",
            theme_advanced_buttons1 : "filemanagerout,jqueryimage,code,link,unlink,image,media,preview,fullscreen,jqueryvideo,visualblocks,removeformat,bold,italic,underline,strikethrough,",
			theme_advanced_buttons2 : "", theme_advanced_buttons3 : "", theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "bottom", theme_advanced_toolbar_align : "left", theme_advanced_statusbar_location : "",
			theme_advanced_resizing : false, width: 700, height: 200,
            
            extended_valid_elements : "video[id|controls|src|width|height|preload|class|poster],source[src|type],object[width|height|type|data],param[name|value],"
            + "iframe[src|frameborder|style|scrolling|class|width|height|name|align|allowfullscreen],div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|data|style|title|allowfullscreen]",
            
            content_css : "<?= URL_CSS  ?>/content.css?v23",
		});
        $('textarea.tinymcemini3').tinymce({
			script_url : 'lib/tinymce/tiny_mce.js', relative_urls : false, remove_script_host : false, theme : "advanced",
			plugins : "tinyautosave,insertlink,filemanagerout,visualblocks,youtubeIframe,embed,jqueryvideo,vote,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager,netlinkImage,Block,insertvideo",
            theme_advanced_buttons1 : "filemanagerout,code,link,unlink,image,media,preview,fullscreen,jqueryvideo,visualblocks,removeformat,bold,italic,underline,strikethrough,",
			theme_advanced_buttons2 : "", theme_advanced_buttons3 : "", theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "bottom", theme_advanced_toolbar_align : "left", theme_advanced_statusbar_location : "",
			theme_advanced_resizing : false, width: 135, height: 100,
            
            extended_valid_elements : "video[id|controls|src|width|height|preload|class|poster],source[src|type],object[width|height|type|data],param[name|value],"
            + "iframe[src|frameborder|style|scrolling|class|width|height|name|align|allowfullscreen],div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|data|style|title|allowfullscreen]",
            
            content_css : "<?= URL_CSS  ?>/content.css?v23",
		});
	});
    
</script>

    
</head>
<body>
<div class="container">

<?php $cls_header->display() ?>
<?php $cls_module->display() ?>
<?php $cls_footer->display() ?>

<script>
    $("#editor_image_show").click(function(){
        pixlr.overlay.show({
                service:'editor',
                locktitle: false, 
                method: "GET", 
                referrer: "Instructables", 
                locktarget: false, 
                target: "<?= PCMS_URL ?>/admin.php?mod=home&act=save_pixlr", 
                copy: true,
            });
        $("#close_pixlr").show();
        return false;
    });
    $(".close_qla").live("click",function(){
        $("#qla").hide();
    });
    $("#editor_image_edit").click(function(){
        pixlr.overlay.show({
                service:'express',
                locktitle: false, 
                method: "GET", 
                referrer: "Instructables", 
                locktarget: true, 
                target: "<?= PCMS_URL ?>/admin.php?mod=home&act=save_pixlr", 
                copy: true,
            });
        $("#close_pixlr").show();
        return false;
    });
    $("#close_pixlr").click(function(){
        pixlr.overlay.hide();
        $(this).hide();
        $.ajax({
            type: "POST",
            url: "<?= PCMS_URL ?>/admin.php?mod=home&act=qla",
            dataType: "html",
            success: function(msg){
                $("#qla").html(msg);
                $("#qla").show('slow');
            }
        });
        
    });
</script>
</div>
</body>
</html>