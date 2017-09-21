<?php
/*
Template name: Card page
Theme Name: Nord
Theme URI: http://nord.ru/
Author: M2
Author URI: http://mkvadrat.com/
Description: Тема для сайта http://nord.ru/
Version: 1.0
*/

get_header(); 
?>
	
	<main class="main-card">

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
		
		<?php echo get_post_meta( get_the_ID(), 'code_block_card_page', $single = true ); ?>
		 
		<?php echo get_post_meta( get_the_ID(), 'text_card_page', $single = true ); ?>

        <div class="block-for-half card-slider">
			<div class="owl-carousel owl-theme owl-carousel-half">
				<?php
					global $nggdb;
					$gallery_id = getNextGallery(get_the_ID(), 'gallery_card_page');
					$gallery_image = $nggdb->get_gallery($gallery_id[0]["ngg_id"], 'sortorder', 'ASC', false, 0, 0);
					if($gallery_image){
						foreach($gallery_image as $image) {
				?>
				<div class="flex-box">
					<div class="left-side">
						<div class="description-half">
							<?php echo $image->description;?>
						</div>
					</div>
					<div class="right-side">
						<img src="<?php echo $image->imageURL; ?>" alt="<?php echo $image->alttext; ?>">
					</div>
				</div>
				<?php
						}
					}
				?>
			</div>
        </div>

		<div class="block-for-half card-slider mobile-block">
			<div class="owl-carousel owl-theme owl-carousel-half">
				<?php
					global $nggdb;
					$gallery_id = getNextGallery(get_the_ID(), 'gallery_card_page');
					$gallery_image = $nggdb->get_gallery($gallery_id[0]["ngg_id"], 'sortorder', 'ASC', false, 0, 0);
					if($gallery_image){
						foreach($gallery_image as $image) {
				?>
				<div class="flex-box">
					<div class="left-side">
						<div class="description-half">
							<?php echo $image->description;?>
						</div>
					</div>
					<div class="right-side">
						<img src="<?php echo $image->imageURL; ?>" alt="<?php echo $image->alttext; ?>">
					</div>
				</div>
				<?php
						}
					}
				?>
			</div>
		</div>

		<?php echo get_post_meta( get_the_ID(), 'note_card_page', $single = true ); ?>
		
		<div class="reviews-block">
	
			<p class="white-title">Отзывы гостей отеля Норд</p>
	
			<ul class="reviews-list">
				<?php
					$args = array(
						'status' => 'approve',
						'number' => '4',
						'post_id' => 363,
					);
				
					$comments = get_comments( $args );
				
					if(!empty($comments)){
					  foreach ($comments as $comment) {
				?>
				<li>
					<p class="white-paragraph"><strong><?php echo $comment->comment_author; ?></strong>
					<?php echo mb_substr( strip_tags( $comment->comment_content ), 0, 152 ); ?>
					<strong><?php comment_date( 'd.m.y', $comment->comment_ID ); ?></strong></p>
				</li>
					<?php } ?>
				<?php } ?>
			</ul>
			
			<div class="owl-carousel owl-theme owl-carousel-half reviews-slider">
				<?php
					if(!empty($comments)){
					  foreach ($comments as $comment) {
				?>
                <div>
                    <p class="white-paragraph"><strong><?php echo $comment->comment_author; ?></strong><?php echo mb_substr( strip_tags( $comment->comment_content ), 0, 152 ); ?></strong></p>
                </div>
					<?php } ?>
				<?php } ?>
            </div>
            
			<p class="paragraph-button"><a class="get-more ancLinks" href="<?php echo get_permalink( 363 ); ?>">Читать все отзывы</a></p>
		</div>
	
		<!-- end reviews-block -->

    </main>
		
<?php get_footer(); ?>