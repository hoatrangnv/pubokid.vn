$(document).ready(function(){
    /*     jQuery
    ==========================*/
    
    $('.tooltip').exp_tooltip();
    $('.tbl-list tr').click(function(){
        if($(this).hasClass('selected')) $(this).removeClass('selected');
        else $(this).addClass('selected');
        if($('.tbl-list tr').size() == $('.tbl-list tr.selected').size()) $('.btn-checkbox').addClass('btn-checkbox-select').removeClass('btn-checkbox-some');
        else if($('.tbl-list tr.selected').size() == 0) $('.btn-checkbox').removeClass('btn-checkbox-select').removeClass('btn-checkbox-some');
        else $('.btn-checkbox').addClass('btn-checkbox-some').removeClass('btn-checkbox-select');
    });
    $('.btn-checkbox').click(function(){
        if($(this).hasClass('btn-checkbox-select')) {
            $(this).removeClass('btn-checkbox-select').removeClass('btn-checkbox-some');
            $('.tbl-list tr').removeClass('selected');
        } else {
            $(this).addClass('btn-checkbox-select');
            $('.tbl-list tr').addClass('selected');
        }
    });
    
    $('input.iToggle').iToggle({
		easing: 'easeOutExpo',
		onClickOn: function(){
            if($(this).attr("for") == 'is_boxchuyengia') $(".boxchuyengia").show();
		},
		onSlideOn: function(){},
		onClickOff: function(){
            if($(this).attr("for") == 'is_boxchuyengia') $(".boxchuyengia").hide();
		},
		onSlideOff: function(){
		  
		}
	});
    
    function alertMsg() {
        var mes_get = $('#wrap-message').text();
        if(mes_get!='') {
            $('#wrap-message').animate({bottom: '-5px'}, 1000, 'easeOutBounce');
            setTimeout(function(){
                $('#wrap-message').animate({bottom: '-50px'}, 1000, 'easeInOutBack');
            }, 3000);
        }
    }
    alertMsg();
    
    $('.frmSearch .submit').click(function(){
        $(this).parents('form').submit();
    });
    
    $('.field_photo').each(function(){
        var obj = $(this).parents('div.OneField');
        obj.find('input:file').hide();
        obj.append('<span class="filename"></span>');
        $(this).click(function(){
            obj.find('input:file').click();
        });
        
        obj.find('input[type=file]').change(function(e){
            obj.find('.filename').html($(this).val()+' | <a href="#" class="cancelfile">Cancel</a>');
        });
    });
    $('.cancelfile').live('click', function(){
        $(this).parents('div.OneField').find('input:file').val('');
        $(this).parents('div.OneField').find('.filename').html('');
        return false;
    });
    
    $('.act_ajax').click(function(){
        var status;
        var obj = $(this);
        var iHref = obj.attr('href');
        if($(this).hasClass('js_check_on')) status=1;
        else status=0;
        if(status==1) obj.removeClass('js_check_on').addClass('js_check_off');
        else obj.removeClass('js_check_off').addClass('js_check_on');
        $.ajax({
            type: "POST",
            url: iHref,
            dataType: "html",
            success: function(msg){
                if(!parseInt(msg)==1) {
                    if(status==1) obj.removeClass('js_check_off').addClass('js_check_on');
                    else obj.removeClass('js_check_on').addClass('js_check_off');
                    $('#wrap-message').text(msg); alertMsg();
                }
            }
        });
        return false;
    });
    
    /* STACK */
    $('.stack_arr .text_input').keyup(function(e){
        var title = $(this).val();
        var obj = $(this).parents('.stack_arr');
        if(!title) {
            obj.find('.show_data').hide();
        }
        var act = $(this).attr('rel');
        var iClass = $(this).attr('alt');
        obj.find('.show_data').show();
        $.ajax({
            type: "POST",
            url: 'admin.php?act='+act+'&title='+title+'&class='+iClass,
            dataType: "html",
            success: function(msg){
                if(parseInt(msg)!='') {
                    obj.find('.show_data').html(msg);
                }
            }
        });
    });
    $('.stack_arr .show_data li a').live('click', function(){
        var type = $(this).parents('.show_data').attr('type');
        if(type==1) {
            var obj = $(this).parents('.stack_arr');
            var ival = $(this).attr('rel');
            var itext = $(this).text();
            obj.find('.input_data ul').html('<li><span class="title">'+itext+'</span><span class="remove" rel="'+$(this).attr('rel')+'"></span></li>');
            obj.find('.curent_input').val(ival);
            if(!obj.find('.text_input').hasClass('noreset')) {
                obj.find('.text_input').val('');
                $(this).parents('.show_data').html('').hide();
            }
        } else {
            var obj = $(this).parents('.stack_arr');
            var ival = obj.find('.curent_input').val()+$(this).attr('rel')+'|';
            var itext = $(this).text();
            obj.find('.input_data ul').append('<li><span class="title">'+itext+'</span><span class="remove" rel="'+$(this).attr('rel')+'"></span></li>');
            obj.find('.curent_input').val(ival);
            if(!obj.find('.text_input').hasClass('noreset')) {
                obj.find('.text_input').val('');
                $(this).parents('.show_data').html('').hide();
            }
        }
        return false;
    });
    $('.stack_arr .remove').live('click', function(){
        var obj = $(this).parents('.stack_arr');
        $(this).parents('li').remove();
        var ival = obj.find('.curent_input').val();
        ival = ival.replace("|"+$(this).attr('rel')+"|", "|");
        obj.find('.curent_input').val(ival);
    });
    $('.stack_arr').click(function(){
        $(this).find('.text_input').focus();
    });
            
    /* SAVE CTRL S */
    var ctrl_down = false;
    var ctrl_key = 17;
    var s_key = 83;
    
    $(document).keydown(function(e) {
        if (e.keyCode == ctrl_key) ctrl_down = true;
    }).keyup(function(e) {
        if (e.keyCode == ctrl_key) ctrl_down = false;
    });
    
    $(document).keydown(function(e) {
        if (ctrl_down && (e.keyCode == s_key)) {
            $('form.frm_submit').submit();
            event.preventDefault();
        }
    });
    
    
    $('.tags input').keyup(function(e){
        var obj = $(this).parents('.tags');
        var title = $(this).val();
        if(!title) {
            obj.find('.show_data').hide();
        }
        obj.find('.show_data').show();
        $.ajax({
            type: "POST",
            url: 'admin.php?act=load_ajax&title='+title+'&class=Tags',
            dataType: "html",
            success: function(msg){
                if(parseInt(msg)!='') {
                    obj.find('.show_data').html(msg);
                }
            }
        });
        
    });
    
    $('.tags input').keyup(function(e){
        if (event.keyCode == '188') {
            var obj = $(this).parents('.tags');
            var itext = $(this).val().replace(",","");
            var ival = obj.find('.curent_input').val()+itext+',';
            
            obj.find('.input_data ul').append('<li><span class="title">'+itext+'</span><span class="remove" rel="'+$(this).attr('rel')+'"></span></li>');
            obj.find('.curent_input').val(ival);
            if(!obj.find('.text_input').hasClass('noreset')) {
                obj.find('.text_input').val('');
                $(this).parents('.show_data').html('').hide();
            }
            return false;
        }
    });
    
    $('.tags .show_data li a').live('click', function(){
        var obj = $(this).parents('.tags');
        var ival = obj.find('.curent_input').val()+$(this).attr('rel')+',';
        var itext = $(this).text();
        obj.find('.input_data ul').append('<li><span class="title">'+itext+'</span><span class="remove" rel="'+$(this).attr('rel')+'"></span></li>');
        obj.find('.curent_input').val(ival);
        if(!obj.find('.text_input').hasClass('noreset')) {
            obj.find('.text_input').val('');
            $(this).parents('.show_data').html('').hide();
        }
        return false;
    });
    $('.tags .remove').live('click', function(){
        var obj = $(this).parents('.tags');
        $(this).parents('li').remove();
        var ival = obj.find('.curent_input').val();
        ival = ival.replace(","+$(this).attr('rel')+",", ",");
        obj.find('.curent_input').val(ival);
    });
    $('.tags').click(function(){
        $(this).find('.text_input').focus();
    });
    $(".tags input").bind({
        paste : function(){
            setTimeout(function () { 
                var tags = $('.tags input').val().split(","); 
                var ival = $(".tags").find('.curent_input').val();
                for(i=0;i<tags.length;i++) {
                    ival += tags[i]+',';
                    $(".tags").find('.input_data ul').append('<li><span class="title">'+tags[i]+'</span><span class="remove" rel="'+tags[i]+'"></span></li>');
                }
                $(".tags").find('.curent_input').val(ival);
                $(".tags .text_input").val('');
            }, 100);  
        }
        
     });
    
    
});