<?php

/*

Theme Name: Nord

Theme URI: http://nord.ru/

Author: M2

Author URI: http://mkvadrat.com/

Description: Тема для сайта http://nord.ru/

Version: 1.0

*/

?>



       <!-- start footer-form-block -->



        <div class="footer-form-block" id="sendForm">

            <p class="white-title">Свяжитесь с нами</p>

            <p class="white-paragraph">Свяжитесь с нами и мы будем рады<br>ответить на все Ваши вопросы!</p>

            <div class="form">

                <input type="text" class="clear" id="name_full_form" placeholder="Ваше имя">

                <input type="text" class="clear" id="phone_full_form" placeholder="Ваш телефон">

                <input type="text" class="clear" id="email_full_form" placeholder="Ваш Email">

                <textarea  class="clear" id="comment_full_form" placeholder="Ваше сообщение"></textarea>

                <div class="i-take-block">
                	<input id="i-take" type="checkbox">
                	<label for="i-take">Я принимаю условия соглашения на обработку персональных</label>
                </div>
				
                <input type="submit" class="agree no-active" value="Отправить">

            </div>

        </div>
		
		<script type="text/javascript">
			$(document).ready(function() {
				if($(window).load()){
					$(".clear").val('');
					$('#i-take').removeAttr('checked');
					$('#i-take-form').removeAttr('checked');
					$(".agree").replaceWith('<input type="submit" class="agree no-active" value="Отправить">');
					$(".agree-booking").replaceWith('<input type="submit" class="agree-booking no-active" value="Отправить">');
				}
				
				var checkbox = $("#i-take");
				
				checkbox.change(function(event) {
					var checkbox = event.target;
					if (checkbox.checked) {
						$(".agree").replaceWith('<input type="submit" class="agree active" onclick="sendForm(); yaCounter45482307.reachGoal(\'footer_done\'); return true;" value="Отправить">');
					}else{
						$(".agree").replaceWith('<input type="submit" class="agree no-active" value="Отправить">');
					}
				});
			});
		</script>
		
		<script type="text/javascript">

			//форма обратной связи

			function sendForm() {

			  var data = {

				'action': 'sendForm',

				'name' : $('#name_full_form').val(),

				'phone' : $('#phone_full_form').val(),

				'email' : $('#email_full_form').val(),

				'comment' : $('#comment_full_form').val(),

			  };

			  $.ajax({

				url:'http://' + location.host + '/wp-admin/admin-ajax.php',

				data:data, // данные

				type:'POST', // тип запроса

				success:function(data){

					swal({

						title: data.message,

						text: "",

						timer: 1000,

						showConfirmButton: false

					});
					
					if(data.status == 200) {
						$('#i-take').removeAttr('checked');
						$( ".agree" ).replaceWith('<input type="submit" class="agree no-active" value="Отправить">');
						dataLayer.push({'event' : 'footerSendMessage'});
					}

					$.fancybox.close();
				
				}

			  });
			  
			};

		</script>

        <!-- end footer-form-block -->






    <!-- start footer-block -->



    <footer class="footer-block">

    	<ul class="socials socials-mobile">

	        <li><a title="Карта" class="ancLinks" href="<?php echo getMeta('map_sl_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-1.png" alt=""></a></li>

	        <li><a title="Обратная связь" class="ancLinks" href="<?php echo getMeta('send_form_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-2.png" alt=""></a></li>

	        <li><a title="Facebook" href="<?php echo getMeta('fb_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-3.png" alt=""></a></li>

	        <li><a title="Вконтакте" href="<?php echo getMeta('vk_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-4.png" alt=""></a></li>

	        <li><a title="Одноклассники" href="<?php echo getMeta('ok_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-5.png" alt=""></a></li>

	        <li><a title="TripAdvisor" href="<?php echo getMeta('tripadvisor_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-7.png" alt=""></a></li>

	        <li><a title="Booking" href="<?php echo getMeta('booking_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-8.png" alt=""></a></li>

	        <li><a title="TopHotels" href="<?php echo getMeta('tophotels_main_page'); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/soc-9.png" alt=""></a></li>

	    </ul>

		<div class="contacts-block">
	    	<dl>
	    		<dt>E-mail:</dt>
	    		<dd><a href="mailto:reserv@hotelnord.ru">reserv@hotelnord.ru</a></dd>
	    		<dt>E-mail:</dt>
	    		<dd><a href="mailto:market@hotelnord.ru">market@hotelnord.ru</a></dd>
	    	</dl>
	    	<dl>
	    		<dt>Телефоны:</dt>
	    		<dd><a href="tel:+73656021455">+7 (36560) 214-55</a><a href="tel:+73656021462">+7 (36560) 214-62 (факс)</a><a href="tel:+79788052591">+7 (978) 805-25-91 (моб.)</a><a href="tel:+79788052591">+7 (978) 805 25 91 (Viber)</a></dd>
	    	</dl>
	    	<dl>
	    		<dt>Адрес:</dt>
	    		<dd><address>пгт. Партенит, г. Алушта, ул. Партенитская, 1-Б, Республика Крым, Россия, 298542</address></dd>
	    	</dl>
	    </div>

		<p class="map-cite"><a href="/karta-sayta/">Карта сайта</a></p>

        <p class="make-in"><a href="http://mkvadrat.com/">сайт разработан в <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/m2-logo.jpg" alt=""></a></p>

        <p class="tm">NORD © 2017 все права защищены</p>

        <p class="tm-mobile"><span>Торговая марка NORD</span>© 2017 все права защищены</p>

    </footer>



    <!-- end footer-block -->



    <!-- start modal-form -->



    <div class="hidden">

        <div class="recall" id="recall">

            <div class="modal-form">

                <p class="tittle-modal-form">Заказать обратный звонок</p>

                <p>Оставьте свой номер телефона и мы<br>перезвоним в течении 20 минут</p>

                <input type="text" id="name_mini_form" placeholder="Ваше имя">

                <input type="text" id="phone_mini_form" placeholder="Ваш телефон">

                <input type="submit" onclick="sendMiniForm();" value="Перезвоните мне">

            </div>

        </div>

    </div>

	<script type="text/javascript">

		//форма обратной связи

		function sendMiniForm() {

		  var data = {

			'action': 'sendMiniForm',

			'name' : $('#name_mini_form').val(),

			'phone' : $('#phone_mini_form').val()

		  };

		  $.ajax({

			url:'http://' + location.host + '/wp-admin/admin-ajax.php',

			data:data, // данные

			type:'POST', // тип запроса

			success:function(data){

			  //swal(data.message);

				swal({

					title: data.message,

					text: "",

					timer: 1000,

					showConfirmButton: false

				});

				

				$.fancybox.close();

			}

		  });

		};

	</script>

    <!-- end modal-form -->



<?php wp_footer(); ?>



<script type="text/javascript">

	var top_show = 150; // В каком положении полосы прокрутки начинать показ кнопки "Наверх"

	var delay = 1000; // Задержка прокрутки

	$(document).ready(function() {

	  $(window).scroll(function () { // При прокрутке попадаем в эту функцию

		/* В зависимости от положения полосы прокрукти и значения top_show, скрываем или открываем кнопку "Наверх" */

		if ($(this).scrollTop() > top_show) $('#top').fadeIn();

		else $('#top').fadeOut();

	  });

	  $('#top').click(function () { // При клике по кнопке "Наверх" попадаем в эту функцию

		/* Плавная прокрутка наверх */

		$('body, html').animate({

			scrollTop: 0

		}, delay);

	  });

	});

</script>



<script>

	$(document).ready(function(){

		$('.owl-carousel-half').owlCarousel({

			items : 1,

			loop : true,

			touchDrag : true,

			nav : true,

			navText : "",

			dots : false

		});

		$(function() {
	        $('nav#menu').mmenu({
	        	extensions	: [ 'fx-menu-slide', 'shadow-page', 'shadow-panels', 'listview-large', 'pagedim-black' ]
	        });
	    });

	});

</script>



<script>

	$(document).ready(function(){

		$('.header-slider').owlCarousel({

			items : 1,

			loop : true,

			touchDrag : true,

			nav : true,

			navText : "",

			autoplay: true,

			autoplayTimeout: 5000,

			dots : true

		});

	});

</script>



<script type="text/javascript">

    $(document).ready(function() {

		$(".fancybox").fancybox();

    });

</script>



<script type="text/javascript">

	$(document).ready(function() {

			$("a.gallery").fancybox();

	});

</script>



<script type="text/javascript">

	$(document).ready(function() {

		$("a.ancLinks").click(function () { 

			var elementClick = $(this).attr("href");

			var destination = $(elementClick).offset().top;

			$('html,body').animate( { scrollTop: destination }, 1100 );

			return false;

		});

	});

	

$('.slimmenu').slimmenu({

    resizeWidth: '2000',

    collapserTitle: '',

    animSpeed:'medium',

    indentChildren: true,

    childrenIndenter: '&raquo;'

});

</script>




<!-- Yandex.Metrika counter -->

<script type="text/javascript" >

    (function (d, w, c) {

        (w[c] = w[c] || []).push(function() {

            try {

                w.yaCounter45482307 = new Ya.Metrika({

                    id:45482307,

                    clickmap:true,

                    trackLinks:true,

                    accurateTrackBounce:true,

                    webvisor:true,

                    trackHash:true

                });

            } catch(e) { }

        });



        var n = d.getElementsByTagName("script")[0],

            s = d.createElement("script"),

            f = function () { n.parentNode.insertBefore(s, n); };

        s.type = "text/javascript";

        s.async = true;

        s.src = "https://mc.yandex.ru/metrika/watch.js";



        if (w.opera == "[object Opera]") {

            d.addEventListener("DOMContentLoaded", f, false);

        } else { f(); }

    })(document, window, "yandex_metrika_callbacks");

</script>

<noscript><div><img src="https://mc.yandex.ru/watch/45482307" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

<!-- /Yandex.Metrika counter -->



</body>

</html>