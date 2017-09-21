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
 
	<main class="service-in">

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

        <!-- start offers -->
		<?php
			$get_cat_inf = get_the_terms( get_the_ID(), 'category' );
			$cat_id = $get_cat_inf[0]->term_id;
			$cat_code = get_option('category_'.$cat_id.'_code_block_category_services');
			echo $cat_code;
		?>
        <!-- end offers -->

		<?php echo get_post_meta( get_the_ID(), 'text_services_page', $single = true ); ?>

        <div class="block-for-half desctop-block">
            <div class="left-side side-description">
				<div class="description-half">
					<?php echo get_post_meta( get_the_ID(), 'scroll_services_services_page', $single = true ); ?>
				</div>
            </div>
            <div class="right-side">
                <div class="owl-carousel owl-theme owl-carousel-half">
					<?php
						global $nggdb;
						$photo_id = getNextGallery(get_the_ID(), 'photo_services_page');
						$services_image = $nggdb->get_gallery($photo_id[0]["ngg_id"], 'sortorder', 'ASC', false, 0, 0);
						if($services_image){
							foreach($services_image as $image) {
						?>
							<div><img src="<?php echo $image->imageURL; ?>" alt="<?php echo $image->alttext; ?>"></div>
						<?php
							}
						}
					?>
                </div>
            </div>
        </div>

        <div class="block-for-half mobile-block">
            <div class="left-side">
                <div class="owl-carousel owl-theme owl-carousel-half">
                    <?php
						global $nggdb;
						$photo_id = getNextGallery(get_the_ID(), 'photo_services_page');
						$services_image = $nggdb->get_gallery($photo_id[0]["ngg_id"], 'sortorder', 'ASC', false, 0, 0);
						if($services_image){
							foreach($services_image as $image) {
						?>
							<div><img src="<?php echo $image->imageURL; ?>" alt="<?php echo $image->alttext; ?>"></div>
						<?php
							}
						}
					?>
                </div>
            </div>
            <div class="right-side side-description">
                <div class="description-half">
                    <?php echo get_post_meta( get_the_ID(), 'scroll_services_mobile_services_page', $single = true ); ?>
                </div>
            </div>
        </div>

        <div class="block-galery">
            <div class="text-block">
                <p class="h3-title">Фотографии:</p>
					
                <ul class="galery-2">
					<?php
						global $nggdb;
						$gallery_id = getNextGallery(get_the_ID(), 'gallery_services_page');
						$gallery_image = $nggdb->get_gallery($gallery_id[0]["ngg_id"], 'sortorder', 'ASC', false, 0, 0);
						if($gallery_image){
							foreach($gallery_image as $image) {
						?>
							<li><a class="gallery" rel="group" href="<?php echo $image->imageURL; ?>"><img src="<?php echo nextgen_esc_url($image->thumbnailURL); ?>" alt="<?php echo $image->alttext; ?>"></a></li>
						<?php
							}
						}
					?>
                </ul>
            </div>
        </div>

    </main>
	
<?php get_footer(); ?>
