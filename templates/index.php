<!DOCTYPE html>
<html lang="vn">
<head>
<title><?=$title_page ?></title>
<meta name="description" content="<?php echo str_replace("\"","'",$description_page) ?>" />
<meta name="keywords" content="<?php echo str_replace("\"","'",$keyword_page) ?>" />
<?= $header_page  ?>
<meta name="google-site-verification" content="LES4YjI_mFxqE8bdik8F3Gg9qCMedL1U5jbZBzHK3Uc" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="<?= URL_JS ?>/jquery-1.12.4.min.js"></script>
<link rel="shortcut icon" href="https://pubokid.vn/favicon.ico?v=102014" />
<?= $head_page ?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MGPG8PR');</script>
<!-- End Google Tag Manager -->
</head>
<body data-rsssl=1 itemscope="itemscope" itemtype="https://schema.org/WebPage">
<noscript id="deferred-styles">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700,700itatlic,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Anton:300,400,400italic,500,700,700itatlic,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Pacifico:300,400,400italic,500,700,700itatlic,900" rel="stylesheet" />
</noscript>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGPG8PR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php $cls_header->display(); ?>
<?php $cls_module->display() ?>
<?php $cls_footer->display() ?>
<!--Call to action-->
<div class="ppocta-ft-fix">
    <div id="messengerButton">
        <a href="http://fb.com/msg/pubokid" target="_blank" onclick="_gaq.push(['_trackEvent', 'Call To Action', 'Messenger', 'Mobile']);"><i></i></a>
    </div>
    <div id="zaloButton">
        <a href="http://zalo.me/0945518888" target="_blank" onclick="_gaq.push(['_trackEvent', 'Call To Action', 'Zalo', 'Mobile']);"><i></i></a>
    </div>
    <a id="registerNowButton" href="https://pubokid.vn/datmua-pubokid.html" onclick="_gaq.push(['_trackEvent', 'Call To Action', 'Register', 'Mobile']);"><i></i></a>
    <div id="callNowButton">
        <a href="tel:0945518888" onclick="_gaq.push(['_trackEvent', 'Call To Action', 'Call', 'Mobile']);"><i></i></a>
        <a href="tel:0945518888" class="txt" onclick="_gaq.push(['_trackEvent', 'Call To Action', 'Call', 'Mobile']);"><span>Gọi ngay</span></a>
    </div>
</div>
<!--END: Call to action-->
<link href="<?= URL_CSS ?>/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= URL_CSS ?>/style.css?<?= time() ?>" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= URL_JS ?>/bootstrap.min.js"></script>
<script>
var loadDeferredStyles = function() {
  var addStylesNode = document.getElementById("deferred-styles");
  var replacement = document.createElement("div");
  replacement.innerHTML = addStylesNode.textContent;
  document.body.appendChild(replacement);
  addStylesNode.parentElement.removeChild(addStylesNode);
};
var raf = requestAnimationFrame || mozRequestAnimationFrame ||
    webkitRequestAnimationFrame || msRequestAnimationFrame;
if (raf){ raf(function() { window.setTimeout(loadDeferredStyles, 0); });}
else{ window.addEventListener('load', loadDeferredStyles);}
</script>
<?php if(!ppo_is_mobile()): ?>
<div class="hotro"><script type='text/javascript'>window._sbzq||function(e){e._sbzq=[];var t=e._sbzq;t.push(["_setAccount",59692]);var n=e.location.protocol=="https:"?"https:":"http:";var r=document.createElement("script");r.type="text/javascript";r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)}(window);</script></div>
<?php endif; ?>
<style type="text/css">
#webid-pubokid,#webid-pubokid-page,#webid-pubokid-nhathuoc{width: 100px!important;height: 100px!important;position: fixed;bottom: 0px;left: -100px;}
</style>
<script>
function stripTrailingSlash(str) {
    if(str.substr(-1) === '/') {
        return str.substr(0, str.length - 1);
    }
    return str;
}
// Khach xem trang
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName('body')[0];
  var ppo_location = stripTrailingSlash(window.location.href);
  var ppo_valid = true;
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "";
  switch(ppo_location) {
    case "https://pubokid.vn/y-kien-khach-hang":
        js.src = "//pubokid.xyz/y-kien-khach-hang/";
        break;
    case "https://pubokid.vn/san-pham":
        js.src = "//pubokid.xyz/san-pham/";
        break;
    case "https://pubokid.vn/diem-ban":
        js.src = "//pubokid.xyz/diem-ban/";
        break;
    case "https://pubokid.vn/datmua-pubokid.html":
        js.src = "//pubokid.xyz/dat-mua/";
        break;
    case "https://pubokid.vn/success.html":
        js.src = "//pubokid.xyz/success/";
        break;
    case "https://pubokid.vn/vi-sao-pubokid-gold-giai-quyet-tan-goc-van-de-tao-bon-cua-tre-d27.html":
        js.src = "//pubokid.xyz/vi-sao/";
        break;
    case "https://pubokid.vn/pubo-kid--tao-bon-gau-con--an-ngon-het-tao-d4.html":
        js.src = "//pubokid.xyz/pubo-kid-tao-bon-gau-con-an-ngon-het-tao/";
        break;
    default:
        ppo_valid = false;
  }
  js.style="width: 100px!important;height: 100px!important;position: fixed;bottom: 0px;left: -100px;";
  js.frameborder="0";
  js.marginheight="0";
  js.marginwidth="0";
  if(ppo_valid){
    fjs.appendChild(js);
  }
}(document, 'iframe', 'webid-pubokid-page'));
// Khach xem nha thuoc
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName('body')[0];
  var ppo_location = stripTrailingSlash(window.location.href);
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//pubokid.xyz/nhathuoc/";
  js.style="width: 100px!important;height: 100px!important;position: fixed;bottom: 0px;left: -100px;";
  js.frameborder="0";
  js.marginheight="0";
  js.marginwidth="0";
  if(ppo_location.lastIndexOf('pubokid.vn/nha-thuoc') !== -1){
    fjs.appendChild(js);
  }
}(document, 'iframe', 'webid-pubokid-nhathuoc'));
// Tat ca khach hang
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName('body')[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//pubokid.xyz/";
  js.style="width: 100px!important;height: 100px!important;position: fixed;bottom: 0px;left: -100px;";
  js.frameborder="0";
  js.marginheight="0";
  js.marginwidth="0";
  fjs.appendChild(js);
}(document, 'iframe', 'webid-pubokid'));
</script>
</body>
</html>
