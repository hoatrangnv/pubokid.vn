var core = {
    _showNotiPopup : function (content,redirect) {
        if(!redirect) var redirect = '';
        var d= new Date(),
        n = d.getTime(),
        temp = '<div id="alert-overlay-' + n + '"><div class="login_photo"></div>'+
                '<div class="box_loged_photo">'+
                '<a href="javascript:void(0)" id="close-btn-' + n + '" class="icon_close"><img src="https://chotoc.vn/templates/themes/images/photo/icon_close_photo.png"/> </a>'+content+
                '</div><div>';
        $("body").append(temp);
        $('#close-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
        });

        $('#accept-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
        });
        return false;
    },
    
    _showConfirmPopup : function (content) {
        var d= new Date(),
        n = d.getTime(),
        temp = '<div id="alert-overlay-' + n + '" style="z-index: 9999" ><div class="login_photo"></div>'+
                '<div class="box_loged_photo">'+
                '<a href="javascript:void(0)" id="close-btn-' + n + '" class="icon_close"><img src="/templates/themes/images/photo/icon_close_photo.png"/> </a>'+content+
                '<div><input type="button" value="OK" class="accept-btn btn_success" style="float:left;margin-left: 10px;" id="accept-btn-' + n + '">'+
                '<input type="button" value="Cancel" class="accept-btn btn_success" style="float:left;margin-left: 10px;" id="cancel-btn-' + n + '"></div>'+
                '</div></div>';
        $("body").append(temp);
        $('#close-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
            return false;
        });
        $('#cancel-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
            $('.btn_post').attr('type','button');
            return false;
        });
        $('#accept-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
        });
        return false;
    },
    
    _showAlertPopup : function (title,content) {
        if(!content) var content = '';
        var d = new Date(),
        n = d.getTime(),
        temp = '<div id="alert-overlay-' + n + '" class="alert-overlay" style="width: 100%; height: 100%; position: fixed; background-color: rgba(0,0,0,0.8); z-index: 9999; top: 0; left: 0;" ></div>' +
                '<div id="alert-' + n + '" class="alert-popup" style="width: 400px; height: 160px; background-color: #ffffff; z-index: 10000; position: fixed; top: 50%; left: 50%; margin-left: -200px; margin-top: -80px; border-radius: 4px;" >' +
                    '<div class="alert-header" style="background: #f5f5f5; border-top-left-radius: 4px; border-top-right-radius: 4px; -moz-border-top-left-radius: 4px; -moz-border-top-right-radius: 4px; font-family: Arial; color: #555555; font-weight: bold; font-size: 16px; text-align: center; height: 45px; padding-top: 18px; background: #f5f5f5 20px center no-repeat;" >' +
                        '<img src="/templates/themes/js/core/alert-icon.png" style="float:left;width: 29px;margin-left: 14PX;">' +
                        '<div>' + title + '</div>' +
                        '<span id="close-btn-' + n + '" class="close-btn" style="position: absolute; width: 30px; height: 30px; display: block; background: transparent url(templates/themes/js/core/close-icon.png); top: -15px; right: -15px; cursor: pointer;"></span>' +
                    '</div>' +
                    '<div class="alert-body" style="text-align: center;margin: 5px 15px 0px 15px;font-size: 15px;">'+content+'</div>' +
                    '<div class="alert-footer" style="text-align: center;"><input type="button" value="OK" class="accept-btn" id="accept-btn-' + n + '" style="background: #cb380e; margin-top: 20px; font-weight: bold; font-family: Arial; font-size: 12px; color: #fff; border: none; border-radius: 2px; cursor: pointer; padding: 5px 20px;" /></div>' +
                '</div>';
        $("body").append(temp);

        $('#close-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
            $('#alert-' + n).remove();
        });

        $('#accept-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
            $('#alert-' + n).remove();
        });
    },
    
    
    
    _showLoginPopup : function(title,link,info,redirect) {
        var self = this;
        if(!info) var info = '';
        
        var d = new Date(),
        n = d.getTime();
        
        var content = '<table class="tbl_mlogin" cellpadding="0" cellspacing="0"><tbody><tr>'+
            '<td colspan="2"><input type="text" class="txt_mlogin user_name_js" placeholder="Email"></td></tr><tr>'+
            '<td colspan="2"><input type="password" class="txt_mlogin user_pass_js" placeholder="Mật khẩu"></td></tr><tr>'+
            '<td align="center"><a href="javascript:void()" id="accept-btn-' + n + '" class="mbtn_login">Đăng nhập</a></td><td align="center">'+
            '<a href="https://chotoc.vn/reset_pass.html" class="clblue2">Quên mật khẩu</a></td></tr><tr><td colspan="2" align="center">'+
            '<span style="border-top:1px solid #ccc;display:block;width:100%;margin:10px 0 0;padding:10px 0 0"><a href="javascript:void(0)" class="btn_loginfb"><img src="https://beansurvey.vn/templates/themes/images/login_fb1.png" style="float: left;"></a><span style="display: block;float: left;margin-left: 169px;margin-top: 7px;">Hoặc đăng ký bằng Email</span></span></td></tr><tr><td colspan="2" align="center">'+
            '<a href="https://chotoc.vn/register.html" class="mbtn_reg">Đăng ký</a></td></tr></tbody></table>';
       
        

        var temp = '<div class="alert-popup"><div class="mlogin" id="alert-overlay-' + n + '">'+
                '<center><div class="box_mlogin">'+
                '<div class="head_box_mlogin">' + title + '<span id="close-btn-' + n + '" class="close_mlogin fl-right"><img src="https://beansurvey.vn/templates/themes/images/mimages/icon_close.png"> </span> </div>' + content + '</div></center></div></div>';
        
        $("body").append(temp);

        $('#close-btn-' + n).on("click", function () {
            $('#alert-overlay-' + n).remove();
        });

        $('#accept-btn-' + n).on("click", function () {
            var user_name = $('.user_name_js').val();
            var user_pass = $('.user_pass_js').val();
            $.ajax({
    			type: 'POST',
    			url: link,
    			cache: false,
    			data: {user_name: user_name,user_pass: user_pass},
            }).success(function (msg) {
                if(msg == '1') {
                    window.location = redirect;
                } else if(msg == '0') {
                    $('#alert-overlay-' + n).remove();
                    self._showAlertPopup('Thông tin đăng nhập không chính xác hoặc tài khoản chưa được xác nhận');
                }
            });
        });
    },
    
    _stringToSlug : function (str) {
          str = str.replace(/^\s+|\s+$/g, ''); 
          str = str.toLowerCase();
          
          var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
          var to   = "aaaaeeeeiiiioooouuuunc------";
          for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
          }
        
          str = str.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-'); 
            alert('a');
          return str;
    },
    
    _oauthpopup : function (options) {
        options.windowName = options.windowName || 'ConnectWithOAuth';
        options.windowOptions = options.windowOptions || 'location=0,status=0,width='+options.width+',height='+options.height+',scrollbars=1';
        options.callback = options.callback || function () {
            window.location.reload();
        };
        var that = this;
        that._oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
        that._oauthInterval = window.setInterval(function () {
            if (that._oauthWindow.closed) {
                window.clearInterval(that._oauthInterval);
                options.callback();
            }
        }, 1000);
    },
    
    _quickViewPopup : function (info,domain) {
        var d= new Date(),
        n = d.getTime(),
        temp = '<div id="quickview"><div style="width: 100%; height: 100%; position: fixed; background-color: rgba(0,0,0,0.8); z-index: 9999; top: 0; left: 0;" ></div>'+
                '<div class="detail_content pkg" style="height: 430px; background-color: #ffffff; z-index: 10000; position: fixed; top: 10%; left: 10%;padding: 17px; border-radius: 4px;"><div class="col450 fl"><div class="slide_detail"><div id="surround">'+
                '<span id="close-btn" class="close-btn" style="position: absolute; width: 30px; height: 30px; display: block; background: transparent url(../templates/themes/js/core/close-icon.png); top: -14px; right: -14px; cursor: pointer;z-index: 9999999;"></span>'+
                
                '<img src="'+info['image']+'"></div></div></div><div class="col520 fr">'+
                '<div class="title_detail">'+info['title']+'</div><div class="line_detail"><div class="info_sapo">'+info['intro']+'</div>'+      
                '<div class="line_detail"><table width="100%" cellpadding="0" cellspacing="0"><tbody><tr><td><strong class="f16">Còn hàng</strong></td><td>Giao hàng: 2 - 3 ngày <br>Được bán &amp; giao hàng bởi chotoc</td></tr></tbody></table></div>'+
                '<div class="line_detail"><table width="100%" cellpadding="0" cellspacing="0"><tbody><tr>'+
                '<td><div class="price_old_detail">'+info['price_old']+' vnđ</div><div class="price_news_detail">'+info['price_new']+' vnđ</div></td>'+
                '<td align="right"><a id="invoice_"  data = "'+info['product_id']+'" href="javascript:void(0)" class="btn_buy_detail">Mua ngay</a></td></tr></tbody></table></div></div></div></div></div>';
        
        $("body").append(temp);
        $('#close-btn').on("click", function () {
            $('#quickview').remove();
            return false;
        });
        $('#invoice_').on("click", function () {
            var data = $(this).attr('data');   
            $.ajax({
                type: "POST",
                url: domain+'index.php?mod=product&act=addProduct',
                data: { product_id: data },
                success: function(msg){
                    if(parseInt(msg)!='') {
                    } 
                }
            });
            window.open(domain+'/gio-hang.html'); return false;
        });
        return false;
    },
    
}
