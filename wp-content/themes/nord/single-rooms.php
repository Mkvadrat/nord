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
	<main class="main-price-in">
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
		
		<!-- start offers -->
		<?php
			$get_cat_inf = get_the_terms( get_the_ID(), 'rooms-list' );
			$cat_id = $get_cat_inf[0]->term_id;
			$cat_code = get_option('rooms-list_'.$cat_id.'_code_block_category_rooms');
			echo $cat_code;
		?>
        <!-- end offers -->
				
		<?php echo get_post_meta( get_the_ID(), 'first_text_block_single_page_rooms', $single = true ) ?>
		
		<div class="text-block-underline">
            <div class="text-block">
                <div class="block-galery">
                    <p class="h3-title">Фотографии номера</p>
                    <ul class="galery-2">
						<?php
							global $nggdb;
							$gallery_id = getNextGallery(get_the_ID(), 'gallery_single_page_rooms');
							$gallery_image = $nggdb->get_gallery($gallery_id[0]["ngg_id"], 'sortorder', 'ASC', false, 0, 0);
							if($gallery_image){
								foreach($gallery_image as $image) {
							?>
								<li><a class="gallery" rel="group" href="<?php echo nextgen_esc_url($image->imageURL); ?>"><img src="<?php echo nextgen_esc_url($image->thumbnailURL); ?>" alt="<?php echo esc_attr($image->alttext); ?>" <?php echo $image->size; ?>></a></li>
							<?php
								}
							}
						?>
                    </ul>
                </div>
            </div>
        </div>
				
		<?php echo get_post_meta( get_the_ID(), 'second_text_block_single_page_rooms', $single = true ) ?>
	
	</main>
	
<?php get_footer(); ?>
