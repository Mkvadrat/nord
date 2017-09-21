<?php
/*
Template name:Contacts page
Theme Name: Nord
Theme URI: http://nord.ru/
Author: M2
Author URI: http://mkvadrat.com/
Description: Тема для сайта http://nord.ru/
Version: 1.0
*/

get_header(); 
?>
    
	 <main class="main-contacts">

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
		
			<?php echo get_post_meta( get_the_ID(), 'code_block_contacts_page', $single = true ); ?>
			
			<?php echo get_post_meta( get_the_ID(), 'main_text_contacts_page', $single = true ); ?>

        <div class="block-for-half contacts-block">
            <div class="left-side">
                <!--<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A1ebe5bd35d8e6c7e7e3cdfa0c99df1a5b054b91c37b3e718a6812c32b58fb17a&amp;width=100%25&amp;height=600&amp;lang=ru_RU&amp;scroll=false"></script>-->
				
				<div class="map" id="maps" style="width:100%; height:600px"></div>
				<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&load=package.full" type="text/javascript"> </script>
				<script type="text/javascript">
						var myMap;
						ymaps.ready(init);
						function init()
						{
							ymaps.geocode('<?php echo get_post_meta( get_the_ID(), 'address_for_map_contacts_page', $single = true ); ?>', {
								results: 1
							}).then
							(
								function (res)
								{
									var firstGeoObject = res.geoObjects.get(0),
										myMap = new ymaps.Map
										("maps",
											{
												center: firstGeoObject.geometry.getCoordinates(),
												zoom: 15,
							controls: ["zoomControl", "fullscreenControl"]
											}
										);
									var myPlacemark = new ymaps.Placemark
									(
										firstGeoObject.geometry.getCoordinates(),
										{
											iconContent: ''
										},
										{
											preset: 'twirl#blueStretchyIcon'
										}
									);
									myMap.geoObjects.add(myPlacemark);
									myMap.controls.add('typeSelector');
									myMap.behaviors.disable('scrollZoom');
								},
								function (err)
								{
									alert(err.message);
								}
							);
						}
				</script>
				
            </div>
            
			<?php echo get_post_meta( get_the_ID(), 'contacts_inf_contacts_page', $single = true ); ?>
			
        </div>

    </main>
 
<?php get_footer(); ?>