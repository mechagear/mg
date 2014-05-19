<?php
define('PATH_TO_PAGES', $_SERVER['DOCUMENT_ROOT'] . '/gb/pages');
define('PROD', true);

$sPage = !empty($_REQUEST['page'])? $_REQUEST['page'] : 'main';
$sPage = strtolower(preg_replace('/[^0-9a-z]/i', '', $sPage));

$sInclude = PATH_TO_PAGES . '/' . $sPage . '.php';
if (!file_exists($sInclude)) {
    $sInclude = PATH_TO_PAGES . '/404.php';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Главбух - АКЦИЯ</title>
    
    <!-- Bootstrap -->
    <link href="/gb/css/bootstrap.min.css" rel="stylesheet">
    <link href="/gb/css/style.css" rel="stylesheet">
    <link rel="icon" href="/gb/favicon.ico" type="image/x-icon"> 
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
      <div class="container">
          <div class="row bg-white">
                  <img src="/gb/gbbannerwide.png" />
          </div>
          <div class="row">
            <div class="col-lg-3">
                <div class="row bg-white" style="margin-right: -7px; padding-top: 10px;">
                    <div class="col-lg-12">
                        <ul class="nav nav-stacked"> 
                            <li class="<?=($sPage == 'main')?'active':''?>"><a href="./index.php"><?=($sPage == 'main')?'<i class="glyphicon glyphicon-play" style="margin-left: -19px;"></i> ':''?>Популярные системы</a></li>
                            <li class="<?=($sPage == 'buhgalter')?'active':''?>"><a href="./index.php?page=buhgalter"><?=($sPage == 'buhgalter')?'<i class="glyphicon glyphicon-play" style="margin-left: -19px;"></i> ':''?>Для бухгалтера</a></li>
                            <li class="<?=($sPage == 'kadrovik')?'active':''?>"><a href="./index.php?page=kadrovik"><?=($sPage == 'kadrovik')?'<i class="glyphicon glyphicon-play" style="margin-left: -19px;"></i> ':''?>Для кадровика</a></li>
                            <li class="<?=($sPage == 'findir')?'active':''?>"><a href="./index.php?page=findir"><?=($sPage == 'findir')?'<i class="glyphicon glyphicon-play" style="margin-left: -19px;"></i> ':''?>Для финансового директора</a></li>
                            <li class="<?=($sPage == 'main')?'jurist':''?>"><a href="./index.php?page=jurist"><?=($sPage == 'jurist')?'<i class="glyphicon glyphicon-play" style="margin-left: -19px;"></i> ':''?>Для юриста</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="row" style="margin-right: -7px; padding-top: 90px;">
                    
                        <div class="timer">
                            <p class="title">До конца акции осталось:</p>
                            <div id="countdown"></div>
                        </div>
                    
                </div>
                
                <div class="row" style="margin-right: -7px; margin-top: 40px; background-color: #e5455f; color: #ffffff;">
                    <div class="col-lg-12">
                        <h4 style="color: #ffffff;"><i class="glyphicon glyphicon-play" style="color: #ffffff;"></i> За подписку на 6 месяцев</h4>
                        <p><b>+1 месяц в подарок</b></p>
                        <h4 style="color: #ffffff;"><i class="glyphicon glyphicon-play" style="color: #ffffff;"></i> За подписку на 12 месяцев</h4>
                        <p><b>+2 месяца в подарок</b></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 bg-white" style="padding-top: 10px;">
                <?php include($sInclude); ?>
            </div>
          </div>
          <div class="row bg-white" style="margin-top: 15px; padding-top: 10px;">
              <div class="col-lg-4"></div>
              <div class="col-lg-4">
                <div class="img-thumbnail">
                    Акция действует только для новых клиентов
                </div>
              </div>
              <div class="col-lg-4"></div>
          </div>
          <div class="row bg-white" style="padding-top: 15px;">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                  <address>
                      ООО "Корпоративные решения" 
                      <i class="glyphicon glyphicon-phone-alt"></i> 8 (800) 775-21-63, круглосуточно (звонок бесплатный)
                  </address>
              </div>
              <div class="col-lg-2"></div>
          </div>
      </div>
    
      
      
      
      
      <div class="modal fade" id="request" data-header="Получить прайс" data-btn="Отправить">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form>
                      <div class="form-group">
                        <label for="fPhone">Номер телефона</label>
                        <input type="phone" class="form-control" id="fPhone" placeholder="Введите номер телефона для связи">
                      </div>
                      <div class="form-group">
                        <label for="fMail">Электронная почта</label>
                        <input type="email" class="form-control" id="fMail" placeholder="Введите адрес электронной почты">
                      </div>
                      <div class="form-group">
                        <label for="fName">Ваше имя</label>
                        <input type="text" class="form-control" id="fName" placeholder="Представьтесь, пожалуйста">
                      </div>
                </form>
                <i class="glyphicon glyphicon-exclamation-sign"></i> Мы гарантируем конфиденциальность ваших данных, они никогда не будут переданы третьим лицам.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="requestbtn"></button>
            </div>
          </div>
        </div>
      </div>
      
      
      
      
      
      
      
      
      
      
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/gb/js/bootstrap.min.js"></script>
    <script src="/gb/js/jquery.countdown.js"></script>
    <script>
    $(document).ready(function() {
	/* Timer */
	var ts = new Date(2014, 4, 1);
	
	if ((new Date()) > ts){
		ts = (new Date()).getTime() + 2*60*60;
	}
	$('#countdown').countdown({
		timestamp	: ts
	});
        
        
        /* Modal */
        $('.modal-call').click(function(){
            if ( $(this).data('ftype') == 2 ) {
                $('#request').data('header', 'Попробовать бесплатно');
                $('#request').data('btn', 'Попробовать');
                $('#request').data('ftype', 2);
            } else {
                $('#request').data('header', 'Получить прайс');
                $('#request').data('btn', 'Получить');
                $('#request').data('ftype', 1);
            }
            $('#request').data('fproduct',$(this).parents('div.media-body').find('.media-heading').text());
            
            $('#request').modal();
            return false;
        });
        
        $('#request').on('show.bs.modal', function(e){
            $('#request').find('.modal-title').text($('#request').data('header'));
            $('#request').find('button.btn').text($('#request').data('btn'));
        });
        
        $('#requestbtn').click(function(){
            
            if ($('#fName').val() == '' || $('#fPhone').val() == '') {
                alert('Введите ваше имя и телефон, пожалуйста.');
                return false;
            }
            
            $.post(
                    '/gb/ajax.php',
                    {
                        ftype: $('#request').data('ftype'),
                        fname: $('#fName').val(),
                        fphone: $('#fPhone').val(),
                        fmail: $('#fMail').val(),
                        fproduct: $('#request').data('fproduct')
                    },
                    function(data){
                        alert('Заявка успешно отправлена! Наш менеджер свяжется с вами в ближайшее время.');
                        $('#request').modal('hide');
                    },
                    'json'
            );
        });
    });
    </script>
    
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
var yaParams = {/*Здесь параметры визита*/};
</script>

<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter22703278 = new Ya.Metrika({id:22703278,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    trackHash:true,params:window.yaParams||{ }});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/22703278" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter --> 
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = _tmr || [];
_tmr.push({id: "2504293", type: "pageView", start: (new Date()).getTime()});
(function (d, w) {
   var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
   ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
   var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
   if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window);
</script><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2504293;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->
  </body>
</html>
