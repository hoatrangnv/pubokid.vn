jQuery.fn.exp_tooltip = function(content, striptags){
    if($('#tooltip').length == 0) {
        $('body').append('<div id="tooltip"><div align="center"><div id="tooltip-icon" style="display: inline-block; border: 1px solid transparent; border-width: 0px 5px 5px 5px; border-color: rgba(0, 0, 0, 0.85); border-left-color: transparent; border-right-color: transparent;"></div></div><div id="tooltip-content" style="border: 1px solid #CCC; border-radius: 4px; background-image: linear-gradient(to bottom,#4A4A4A 0,black 100%); box-shadow: 0px 0px 3px #333; padding: 5px 10px; background-image: -moz-linear-gradient(top,#4A4A4A 0,black 100%); background-image: -ms-linear-gradient(top,#4A4A4A 0,black 100%); background-image: -o-linear-gradient(top,#4A4A4A 0,black 100%); background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0,#4A4A4A),color-stop(100%,black)); background-image: -webkit-linear-gradient(top,#4A4A4A 0,black 100%); color: white;"></div></div>');
    }
    var tObj = $('#tooltip').css({ position: "absolute", display: "none"});
    this.each(function(){
        var iContent, iX, iY;
        var offset = $(this).offset();
        iContent = $(this).attr('title');
        $(this).attr('title', '');
        iX = offset.left;
        iY = offset.top+$(this).outerHeight()+2;
        tObj.find('#tooltip-content').text(iContent);
        iX = ($(this).outerWidth()-tObj.outerWidth())/2+iX;
        $(this).mouseover(function(){
            tObj.css({top: iY, left: iX, opacity: 1}).stop().fadeIn();
            tObj.find('#tooltip-content').text(iContent);
        }).mouseout(function(){
            tObj.stop().fadeOut();
        });
    });
}