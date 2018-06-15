sticky = function(selector,bottom){
    setTimeout(function(){
        if (!!$(selector).offset()) {
            var stop_top = $(selector).offset().top;
            var ads_height = $(selector).innerHeight();
            var ads_width = $(selector).innerWidth();
            var display = $(bottom).offset().top - stop_top;
            
            if(display > ads_height + 50){
                $(window).scroll(function(){
                    var window_top = $(window).scrollTop();
                    var stop_bottom = $(bottom).offset().top;

                    if(window_top + 30 < stop_top){
                        /*chặn trên*/
                        $(selector).css({width: ads_width, position : 'absolute', top : stop_top});
                    }else if((window_top + 30 > stop_top ) && (window_top + ads_height + 40 < stop_bottom)){
                        /*slide*/
                        $(selector).css({width: ads_width, position: 'fixed', top: 10});
                    }else{
                        /*chặn dưới*/
                        $(selector).css({width: ads_width, position : 'absolute', top : stop_bottom - ads_height - 30});
                    } 
                });
            }else{
                $(selector).css("display", "block");
            }
        }
    },3000);
};

// JavaScript Document
$(document).ready(function(){
    var dayNames = [
        "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
    ];
    function myTimer() {
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var hour = date.getHours();
        var hourFormatted = hour % 12 || 12;
        if (hourFormatted < 10) hourFormatted = "0" + hourFormatted;
        var minute = date.getMinutes();
        var minuteFormatted = minute < 10 ? "0" + minute : minute;
        var second = date.getSeconds();
        if (second < 10) second = "0" + second;
        var morning = hour < 12 ? "AM" : "PM";
        var dow = date.getDay();
        dow = dayNames[dow];
        var datetime = dow + " " + day + "/" + month + "/" + year + " " + hourFormatted + ":" + minuteFormatted + ":" + second + " " + morning;
        document.getElementById("time_detail").innerHTML = datetime;
        setTimeout(function(){ myTimer() }, 1000);
    }
    myTimer();

    $('#left-menu').sidr();
    
    $(".search-btn").click(function(){
        var e=window.event||e;
        $('.search-btn').toggleClass('active');
        $('.form_search').toggleClass('active');
        e.stopPropagation();
    });

    $('.form_search').click(function(event){
        event.stopPropagation();
    });
    
    $(document).click(function(e){
        $('.search-btn').removeClass('active');
        $('.form_search').removeClass('active');
        e.stopPropagation();
    });
})