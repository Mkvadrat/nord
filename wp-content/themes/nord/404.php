<?php
/*
Theme Name: Nord
Theme URI: http://nord.ru/
Author: M2
Author URI: http://mkvadrat.com/
Description: Тема для сайта http://nord.ru/
Version: 1.0
*/

get_header(); 
?>
     <main class="main-page-404">

        <!-- start top slider -->

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

        <!-- end top slider -->

        <div class="text-block">
            <h1 class="h1-title-center">Ошибка 404!</h1>
            <p>Страница не найдена</p>
            <p><a class="get-more" href="<?php echo esc_url( home_url( '/' ) ); ?>">На главную</a></p>
        </div>

        <!-- start footer-form-block -->

    </main>

    

<?php get_footer(); ?>