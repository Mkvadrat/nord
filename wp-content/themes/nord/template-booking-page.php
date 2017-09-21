<?php
/*
Template name: Booking page
Theme Name: Nord
Theme URI: http://nord.ru/
Author: M2
Author URI: http://mkvadrat.com/
Description: Тема для сайта http://nord.ru/
Version: 1.0
*/

get_header(); 
?>
	
	<main class="main-booking">

		<div class="owl-carousel owl-theme header-slider narrow-header-slider">
			<?php
				global $nggdb;
				$slider_id = getNextGallery('32', 'slider_for_all_pages');
				$slider_image = $nggdb->get_gallery($slider_id[0]["ngg_id"], 'sortorder', 'ASC', false, 0, 0);
				if($slider_image){
					foreach($slider_image as $image) {
				?>
					<div>
						<img src="<?php echo nextgen_esc_url($image->imageURL); ?>" alt="<?php echo esc_attr($image->alttext); ?>">
					</div>
				<?php
					}
				}
			?>
		</div>
		
		<?php echo get_post_meta( get_the_ID(), 'code_block_booking_page', $single = true ); ?>
	
		<?php echo get_post_meta( get_the_ID(), 'variable_booking_booking_page', $single = true ); ?>
		
		<div class="text-block">
            <?php echo get_post_meta( get_the_ID(), 'title_form_booking_page', $single = true ); ?>
			
            <p class="paragraph-button"><a href="#booking-form-online" class="get-more ancLinks">ОНЛАЙН БРОНИРОВАНИЕ</a>
            <a href="#easy-reservation" class="get-more fancybox">ЛЕГКОЕ БРОНИРОВАНИЕ</a>
            <!--<a href="#recall-booking" class="get-more fancybox">ОБРАТНЫЙ ЗВОНОК</a></p>-->

            <p class="h3-title-center"><a href="http://hotelnord.ru/wp-content/uploads/2017/07/33_-_tip_dogovor_o_pred_gost_uslug_fizlitsa_RF_20171.doc">Договор с физлицами</a></p>
			
			<div id="booking-form-online">
<!--				<p><img style="width: 100%;" src="--><?php //echo esc_url( get_template_directory_uri() ); ?><!--/images/booking.jpg" alt=""></p>-->
                <p id="tl-anchor">С помощью приведенной ниже формы вы можете забронировать наши номера в режиме онлайн и получить гарантированную бронь. Для оплаты вы можете использовать кредитную карту, электронные деньги, безналичный расчет либо <b>оплатить заказ на месте.</b></p>
                <!-- start booking form 2.0 -->
                <div id="tl-booking-form"></div>
			</div>	
            
            <?php echo get_post_meta( get_the_ID(), 'additional_text_booking_page', $single = true ); ?>
        </div>

    </main>
	
	<div class="hidden">
        <div class="recall" id="recall-booking">
            <div class="modal-form">
                <p class="tittle-modal-form">Заказать обратный звонок</p>
                <input type="text" id="name_call_back_mini" placeholder="Ваше имя">
                <input type="text" class="phone-mask" id="phone_call_back_mini" placeholder="Ваш телефон">
                <input type="submit" onclick="callBackMini(); yaCounter45482307.reachGoal('zvonok_goal'); return true;" value="Перезвоните мне">
            </div>
        </div>
		
        <div class="easy-reservation" id="easy-reservation">
            <div class="modal-form">
                <p class="tittle-modal-form">Легкое бронирование offline</p>
                <input type="text" class="reset" id="name_light_booking" placeholder="Имя*">
                <input type="email" class="reset" id="email_light_booking" placeholder="E-mail: *">
                <input type="tel" class="reset phone-mask" id="phone_light_booking" placeholder="Телефон: *">
                <input type="text" id="arrival_light_booking" class="reset datepicker" placeholder="Дата заезда *">
                <input type="text" id="departure_light_booking" class="reset datepicker" placeholder="Дата выезда *">
                <p>Поля отмеченные * обязательны.</p>
                <button onclick="clearFields();">Очистить</button>
                <input type="submit" onclick="lightBooking(); yaCounter45482307.reachGoal('light_done'); return true;" value="Отправить">
            </div>
        </div>
    </div>
	
	<script type="text/javascript">
		//форма обратной связи
		function callBackMini() {
		  var data = {
			'action': 'callBackMini',
			'name' : $('#name_call_back_mini').val(),
			'phone' : $('#phone_call_back_mini').val()
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
				
				$.fancybox.close();
			  //swal(data.message);
			}
		  });
		};
	</script>
	
	<script type="text/javascript">
		//форма обратной связи
		function lightBooking() {
		  var data = {
			'action': 'lightBooking',
			'name' : $('#name_light_booking').val(),
			'email' : $('#email_light_booking').val(),
			'phone' : $('#phone_light_booking').val(),
			'arrival' : $('#arrival_light_booking').val(),
			'departure' : $('#departure_light_booking').val(),
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
				
				$.fancybox.close();
			}
		  });
		};
		
		function clearFields(){
			$('.reset').val('');
		}
		
	</script>
	
	<script type="text/javascript">
		//локализация календаря
		$.datepicker.regional['ru'] = {
			closeText: 'Закрыть',
			prevText: '&#x3c;Пред',
			nextText: 'След&#x3e;',
			currentText: 'Сегодня',
			monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь', 'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
			monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн', 'Июл','Авг','Сен','Окт','Ноя','Дек'],
			dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
			dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
			dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
			dateFormat: 'dd.mm.yy',
			firstDay: 1,
			isRTL: false
		};

		$.datepicker.setDefaults($.datepicker.regional['ru']);

		$('.datepicker').datepicker({
			dateFormat: 'dd.mm.yy',
			minDate:0,
		});
	</script>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$(".phone-mask").mask("+7(999) 999-9999");
		});
	 </script>
	
<?php get_footer(); ?>