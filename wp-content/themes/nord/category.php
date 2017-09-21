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
    
    <main class="main-services">

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
			$term = get_queried_object();
			$category_id = $term->term_id;
			$category_code = get_option('category_'.$category_id.'_code_block_category_services');
			echo $category_code;
		?>
        <!-- end offers -->
        
        <?php				   
			$category_descr = get_option('category_'.$category_id.'_text_category_services');
			echo $category_descr;
		?>

		<?php				   
			$category_descr = get_option('category_'.$category_id.'_text_mobile_category_services');
			echo $category_descr;
		?>
    </main>
    
<?php get_footer(); ?>