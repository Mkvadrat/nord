<?php
/*
Theme Name: Nord
Theme URI: http://nord.ru/
Author: M2
Author URI: http://mkvadrat.com/
Description: Тема для сайта http://nord.ru/
Version: 1.0
*/

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
****************************************************************************НАСТРОЙКИ ТЕМЫ*****************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/

//Регистрируем название сайта
function nord_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	$title .= get_bloginfo( 'name', 'display' );

	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'ug' ), max( $paged, $page ) );
	}

	if ( is_404() ) {
        $title = '404';
    }

	return $title;
}
add_filter( 'wp_title', 'nord_wp_title', 10, 2 );

//Регистрируем меню
if(function_exists('register_nav_menus')){
	register_nav_menus(
		array(
		  'primary_menu' => 'Главное меню (Уровень 1)',
		  'second_menu'  => 'Главное меню (Уровень 2)',
		  'mobile_menu'  => 'Мобильное меню'
		)
	);
}

//Изображение в шапке сайта
$args = array(
	'width'         => 118,
	'height'        => 67,
	'default-image' => get_template_directory_uri() . '/images/logo.jpg',
	'uploads'       => true,
);
add_theme_support( 'custom-header', $args );

//Добавление в тему миниатюры записи и страницы
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}

//Вывод id категории
function getCurrentCatID(){
	global $wp_query;
	if(is_category()){
		$cat_ID = get_query_var('cat');
	}
	return $cat_ID;
}

//отключение wpautop
/*remove_filter( 'the_content', 'wpautop' ); // Отключаем автоформатирование в полном посте
remove_filter( 'the_excerpt', 'wpautop' ); // Отключаем автоформатирование в кратком(анонсе) посте
remove_filter('comment_text', 'wpautop'); // Отключаем автоформатирование в комментариях*/
add_filter('user_can_richedit' , create_function ('' , 'return false;') , 50 );

//запрет вывода версии
function fjarrett_remove_wp_version_strings( $src ) {
	global $wp_version;
	parse_str(parse_url($src, PHP_URL_QUERY), $query);
	if ( !empty($query['ver']) && $query['ver'] === $wp_version ) {
	$src = remove_query_arg('ver', $src);
}
	return $src;
}
add_filter( 'script_loader_src', 'fjarrett_remove_wp_version_strings' );
add_filter( 'style_loader_src', 'fjarrett_remove_wp_version_strings' );

/* Hide WP version strings from generator meta tag */
function wpmudev_remove_version() {
	return;
}
add_filter('the_generator', 'wpmudev_remove_version');


/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
****************************************************************************МЕНЮ САЙТА*********************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
// Добавляем свой класс для пунктов меню:
class primary_menu extends Walker_Nav_Menu {

	// Добавляем классы к вложенным ul
	function start_lvl( &$output, $depth ) {
		// Глубина вложенных ul
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'drop-menu',
			( $display_depth % 2  ? 'dropdown' : '' ),
			( $display_depth >=2 ? 'dropdown' : '' ),
			'second-drop-menu'
			);
		$class_names = implode( ' ', $classes );
	
		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}

	// Добавляем классы к вложенным li
	function start_el( &$output, $item, $depth, $args ) {
		global $wpdb;
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
	
		// depth dependent classes
		$depth_classes = array(
			( $depth == 0 ? 'has-sub' : '' ),
			( $depth >=2 ? '' : '' ),
			( $depth % 2 ? '' : '' ),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
	
		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
	
		$mycurrent = ( $item->current == 1 ) ? ' active' : '';
	
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
	
		$output .= $indent . '<li>';
	
		// Добавляем атрибуты и классы к элементу a (ссылки)
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
		$attributes .= ' class="menu-link ' . ( $depth == 0 ? 'parent' : '' ) . ( $depth == 1 ? 'child' : '' ) . ( $depth >= 2 ? 'sub-child' : '' ) . '"';
	
		if($depth == 0){
			$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
				$args->before,
				$attributes,
				$args->link_before,
				apply_filters( 'the_title', $item->title, $item->ID ),
				$args->link_after,
				$args->after
			);
		}else if($depth == 1){
			
			$link  =  $item->url;
			$title = apply_filters( 'the_title', $item->title, $item->ID );

			$item_output = '<a class="ancLinks" href="'. $link .'">' . $title . '</a>';

		}else if($depth >= 2){
			$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
				$args->before,
				$attributes,
				$args->link_before,
				apply_filters( 'the_title', $item->title, $item->ID ),
				$args->link_after,
				$args->after
			);
		}
	
		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
*********************************************************************РАБОТА С METAПОЛЯМИ*******************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Вывод данных из произвольных полей для всех страниц сайта
function getMeta($meta_key){
	global $wpdb;
	
	$value = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_id DESC LIMIT 1", $meta_key) );
	
	return $value;
}

function getAttachment($post_id){
	global $wpdb;
	
	$value = $wpdb->get_var( $wpdb->prepare("SELECT guid FROM $wpdb->posts WHERE ID = %s AND post_type = 'attachment'", $post_id) );
	
	return $value;
}

function getNextGallery($post_id, $meta_key){
	global $wpdb;
	
	$value = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta AS pm JOIN $wpdb->posts AS p ON (pm.post_id = p.ID) AND (pm.post_id = %s) AND meta_key = %s ORDER BY pm.post_id DESC LIMIT 1", $post_id, $meta_key) );
	
	$unserialize_value = unserialize($value);
	
	return $unserialize_value;
	
}
/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
*******************************************************************SEO PATH FOR IMAGE**********************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
function getAltImage($meta_key){
	global $wpdb;

	$post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_id DESC LIMIT 1" , $meta_key));

	$attachment = get_post( $post_id );

	return get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );

	/*return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);*/
}

function getTitleImage($meta_key){
	global $wpdb;

	$post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_id DESC LIMIT 1" , $meta_key));

	$attachment = get_post( $post_id );

	return $attachment->post_title;
}

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
**********************************************************************"РАЗДЕЛ НОМЕРА"**********************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Вывод в админке раздела номера
function register_post_type_rooms() {
	$labels = array(
	 'name' => 'Номера',
	 'singular_name' => 'Номера',
	 'add_new' => 'Добавить статью',
	 'add_new_item' => 'Добавить новую статью',
	 'edit_item' => 'Редактировать статью',
	 'new_item' => 'Новая статью',
	 'all_items' => 'Все статьи',
	 'view_item' => 'Просмотр статей на сайте',
	 'search_items' => 'Искать статью',
	 'not_found' => 'Статья не найден.',
	 'not_found_in_trash' => 'В корзине нет статьи.',
	 'menu_name' => 'Номера'
	 );
	 $args = array(
		 'labels' => $labels,
		 'public' => true,
		 'exclude_from_search' => false,
		 'show_ui' => true,
		 'has_archive' => false,
		 'menu_icon' => 'dashicons-book', // иконка в меню
		 'menu_position' => 20,
		 'supports' =>  array('title','editor', 'thumbnail'),
	 );
 	register_post_type('rooms', $args);
}
add_action( 'init', 'register_post_type_rooms' );

function true_post_type_rooms( $rooms ) {
	global $post, $post_ID;

	$rooms['rooms'] = array(
			0 => '',
			1 => sprintf( 'Статьи обновлены. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			2 => 'Статья обновлёна.',
			3 => 'Статья удалёна.',
			4 => 'Статья обновлена.',
			5 => isset($_GET['revision']) ? sprintf( 'Статья восстановлена из редакции: %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( 'Статья опубликована на сайте. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			7 => 'Статья сохранена.',
			8 => sprintf( 'Отправлена на проверку. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( 'Запланирована на публикацию: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Просмотр</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( 'Черновик обновлён. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $rooms;
}
add_filter( 'post_updated_messages', 'true_post_type_rooms' );
	
//Категории для пользовательских записей "Номера"
function create_taxonomies_rooms()
{
    // Cats Categories
    register_taxonomy('rooms-list',array('rooms'),array(
        'hierarchical' => true,
        'label' => 'Рубрики',
        'singular_name' => 'Рубрика',
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'rooms-list' )
    ));
}
add_action( 'init', 'create_taxonomies_rooms', 0 );

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
**********************************************************************РАЗДЕЛ "АКЦИИ"***********************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Вывод в админке раздела номера
function register_post_type_action() {
	$labels = array(
	 'name' => 'Акции',
	 'singular_name' => 'Акции',
	 'add_new' => 'Добавить статью',
	 'add_new_item' => 'Добавить новую статью',
	 'edit_item' => 'Редактировать статью',
	 'new_item' => 'Новая статью',
	 'all_items' => 'Все статьи',
	 'view_item' => 'Просмотр статей на сайте',
	 'search_items' => 'Искать статью',
	 'not_found' => 'Статья не найден.',
	 'not_found_in_trash' => 'В корзине нет статьи.',
	 'menu_name' => 'Акции'
	 );
	 $args = array(
		 'labels' => $labels,
		 'public' => true,
		 'exclude_from_search' => false,
		 'show_ui' => true,
		 'has_archive' => false,
		 'menu_icon' => 'dashicons-megaphone', // иконка в меню
		 'menu_position' => 20,
		 'supports' =>  array('title','editor', 'thumbnail'),
	 );
 	register_post_type('action', $args);
}
add_action( 'init', 'register_post_type_action' );

function true_post_type_action( $action ) {
	global $post, $post_ID;

	$action['action'] = array(
			0 => '',
			1 => sprintf( 'Статьи обновлены. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			2 => 'Статья обновлёна.',
			3 => 'Статья удалёна.',
			4 => 'Статья обновлена.',
			5 => isset($_GET['revision']) ? sprintf( 'Статья восстановлена из редакции: %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( 'Статья опубликована на сайте. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			7 => 'Статья сохранена.',
			8 => sprintf( 'Отправлена на проверку. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( 'Запланирована на публикацию: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Просмотр</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( 'Черновик обновлён. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $action;
}
add_filter( 'post_updated_messages', 'true_post_type_action' );
	
//Категории для пользовательских записей "Номера"
function create_taxonomies_action()
{
    // Cats Categories
    register_taxonomy('action-list',array('action'),array(
        'hierarchical' => true,
        'label' => 'Рубрики',
        'singular_name' => 'Рубрика',
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'action-list' )
    ));
}
add_action( 'init', 'create_taxonomies_action', 0 );

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
**********************************************************************"РАЗДЕЛ НОВОСТИ"*********************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Вывод в админке раздела новости
function register_post_type_news() {
	$labels = array(
	 'name' => 'Новости',
	 'singular_name' => 'Новости',
	 'add_new' => 'Добавить статью',
	 'add_new_item' => 'Добавить новую статью',
	 'edit_item' => 'Редактировать статью',
	 'new_item' => 'Новая статью',
	 'all_items' => 'Все статьи',
	 'view_item' => 'Просмотр статей на сайте',
	 'search_items' => 'Искать статью',
	 'not_found' => 'Статья не найден.',
	 'not_found_in_trash' => 'В корзине нет статьи.',
	 'menu_name' => 'Новости'
	 );
	 $args = array(
		 'labels' => $labels,
		 'public' => true,
		 'exclude_from_search' => false,
		 'show_ui' => true,
		 'has_archive' => false,
		 'menu_icon' => 'dashicons-editor-paragraph', // иконка в меню
		 'menu_position' => 20,
		 'supports' =>  array('title','editor', 'thumbnail'),
	 );
 	register_post_type('news', $args);
}
add_action( 'init', 'register_post_type_news' );

function true_post_type_news( $news ) {
	global $post, $post_ID;

	$news['news'] = array(
			0 => '',
			1 => sprintf( 'Статьи обновлены. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			2 => 'Статья обновлёна.',
			3 => 'Статья удалёна.',
			4 => 'Статья обновлена.',
			5 => isset($_GET['revision']) ? sprintf( 'Статья восстановлена из редакции: %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( 'Статья опубликована на сайте. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			7 => 'Статья сохранена.',
			8 => sprintf( 'Отправлена на проверку. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( 'Запланирована на публикацию: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Просмотр</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( 'Черновик обновлён. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $news;
}
add_filter( 'post_updated_messages', 'true_post_type_news' );
	
//Категории для пользовательских записей "Новости"
function create_taxonomies_news()
{
    // Cats Categories
    register_taxonomy('news-list',array('news'),array(
        'hierarchical' => true,
        'label' => 'Рубрики',
        'singular_name' => 'Рубрика',
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news-list' )
    ));
}
add_action( 'init', 'create_taxonomies_news', 0 );

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
************************************************************ПЕРЕИМЕНОВАВАНИЕ ЗАПИСЕЙ В УСЛУГИ**************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
function change_post_menu_label() {
    global $menu, $submenu;
    $menu[5][0] = 'Услуги';
    $submenu['edit.php'][5][0] = 'Услуги';
    $submenu['edit.php'][10][0] = 'Добавить статью';
    $submenu['edit.php'][16][0] = 'Метки';
    echo '';
}
add_action( 'admin_menu', 'change_post_menu_label' );

function change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Услуги';
    $labels->singular_name = 'Услуги';
    $labels->add_new = 'Добавить статью';
    $labels->add_new_item = 'Добавить статью';
    $labels->edit_item = 'Редактировать статью';
    $labels->new_item = 'Добавить статью';
    $labels->view_item = 'Посмотреть статью';
    $labels->search_items = 'Найти статью';
    $labels->not_found = 'Не найдено';
    $labels->not_found_in_trash = 'Корзина пуста';
}
add_action( 'init', 'change_post_object_label' );

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
*****************************************************************REMOVE CATEGORY_TYPE SLUG*********************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Удаление  из url таксономии
function true_remove_slug_from_category_rooms( $url, $term, $taxonomy ){

	$taxonomia_name = 'rooms-list';
	$taxonomia_slug = 'rooms-list';

	if ( strpos($url, $taxonomia_slug) === FALSE || $taxonomy != $taxonomia_name ) return $url;

	$url = str_replace('/' . $taxonomia_slug, '', $url);

	return $url;
}
add_filter( 'term_link', 'true_remove_slug_from_category_rooms', 10, 3 );

//Перенаправление url в случае удаления rooms-list
function parse_request_url_category_rooms( $query ){

	$taxonomia_name = 'rooms-list';

	if( $query['attachment'] ) :
		$condition = true;
		$main_url = $query['attachment'];
	else:
		$condition = false;
		$main_url = $query['name'];
	endif;

	$termin = get_term_by('slug', $main_url, $taxonomia_name);

	if ( isset( $main_url ) && $termin && !is_wp_error( $termin )):

		if( $condition ) {
			unset( $query['attachment'] );
			$parent = $termin->parent;
			while( $parent ) {
				$parent_term = get_term( $parent, $taxonomia_name);
				$main_url = $parent_term->slug . '/' . $main_url;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}

		switch( $taxonomia_name ):
			case 'category':{
				$query['category_name'] = $main_url;
				break;
			}
			case 'post_tag':{
				$query['tag'] = $main_url;
				break;
			}
			default:{
				$query[$taxonomia_name] = $main_url;
				break;
			}
		endswitch;

	endif;

	return $query;

}
add_filter('request', 'parse_request_url_category_rooms', 1, 1 );

//Удаление  из url таксономии Акции
function true_remove_slug_from_category_action( $url, $term, $taxonomy ){

	$taxonomia_name = 'action-list';
	$taxonomia_slug = 'action-list';

	if ( strpos($url, $taxonomia_slug) === FALSE || $taxonomy != $taxonomia_name ) return $url;

	$url = str_replace('/' . $taxonomia_slug, '', $url);

	return $url;
}
add_filter( 'term_link', 'true_remove_slug_from_category_action', 10, 3 );

//Перенаправление url в случае удаления action-list
function parse_request_url_category_action( $query ){

	$taxonomia_name = 'action-list';

	if( $query['attachment'] ) :
		$condition = true;
		$main_url = $query['attachment'];
	else:
		$condition = false;
		$main_url = $query['name'];
	endif;

	$termin = get_term_by('slug', $main_url, $taxonomia_name);

	if ( isset( $main_url ) && $termin && !is_wp_error( $termin )):

		if( $condition ) {
			unset( $query['attachment'] );
			$parent = $termin->parent;
			while( $parent ) {
				$parent_term = get_term( $parent, $taxonomia_name);
				$main_url = $parent_term->slug . '/' . $main_url;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}

		switch( $taxonomia_name ):
			case 'category':{
				$query['category_name'] = $main_url;
				break;
			}
			case 'post_tag':{
				$query['tag'] = $main_url;
				break;
			}
			default:{
				$query[$taxonomia_name] = $main_url;
				break;
			}
		endswitch;

	endif;

	return $query;

}
add_filter('request', 'parse_request_url_category_action', 1, 1 );

//Удаление  из url таксономии Новости
function true_remove_slug_from_category_news( $url, $term, $taxonomy ){

	$taxonomia_name = 'news-list';
	$taxonomia_slug = 'news-list';

	if ( strpos($url, $taxonomia_slug) === FALSE || $taxonomy != $taxonomia_name ) return $url;

	$url = str_replace('/' . $taxonomia_slug, '', $url);

	return $url;
}
add_filter( 'term_link', 'true_remove_slug_from_category_news', 10, 3 );

//Перенаправление url в случае удаления news-list
function parse_request_url_category_news( $query ){

	$taxonomia_name = 'news-list';

	if( $query['attachment'] ) :
		$condition = true;
		$main_url = $query['attachment'];
	else:
		$condition = false;
		$main_url = $query['name'];
	endif;

	$termin = get_term_by('slug', $main_url, $taxonomia_name);

	if ( isset( $main_url ) && $termin && !is_wp_error( $termin )):

		if( $condition ) {
			unset( $query['attachment'] );
			$parent = $termin->parent;
			while( $parent ) {
				$parent_term = get_term( $parent, $taxonomia_name);
				$main_url = $parent_term->slug . '/' . $main_url;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}

		switch( $taxonomia_name ):
			case 'category':{
				$query['category_name'] = $main_url;
				break;
			}
			case 'post_tag':{
				$query['tag'] = $main_url;
				break;
			}
			default:{
				$query[$taxonomia_name] = $main_url;
				break;
			}
		endswitch;

	endif;

	return $query;

}
add_filter('request', 'parse_request_url_category_news', 1, 1 );

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
*****************************************************************REMOVE POST_TYPE SLUG*********************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Удаление sluga из url таксономии 
function remove_slug_from_post( $post_link, $post, $leavename ) {
	if ( 'rooms' != $post->post_type && 'news' != $post->post_type && 'action' != $post->post_type || 'publish' != $post->post_status ) {
		return $post_link;
	}
		$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
	return $post_link;
}
add_filter( 'post_type_link', 'remove_slug_from_post', 10, 3 );

function parse_request_url_post( $query ) {
	if ( ! $query->is_main_query() )
		return;

	if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
		return;
	}

	if ( ! empty( $query->query['name'] ) ) {
		$query->set( 'post_type', array( 'post', 'rooms', 'news', 'action', 'page' ) );
	}
}
add_action( 'pre_get_posts', 'parse_request_url_post' );

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
***********************************************************************КОММЕНТАРИИ*************************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Ajax функция добавления комментариев
function true_add_ajax_comment(){
	global $wpdb;
	$comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;

	$post = get_post($comment_post_ID);

	if ( empty($post->comment_status) ) {
		do_action('comment_id_not_found', $comment_post_ID);
		exit;
	}

	$status = get_post_status($post);

	$status_obj = get_post_status_object($status);

	/*
	 * различные проверки комментария
	 */
	if ( !comments_open($comment_post_ID) ) {
		do_action('comment_closed', $comment_post_ID);
		wp_die( __('Sorry, comments are closed for this item.') );
	} elseif ( 'trash' == $status ) {
		do_action('comment_on_trash', $comment_post_ID);
		exit;
	} elseif ( !$status_obj->public && !$status_obj->private ) {
		do_action('comment_on_draft', $comment_post_ID);
		exit;
	} elseif ( post_password_required($comment_post_ID) ) {
		do_action('comment_on_password_protected', $comment_post_ID);
		exit;
	} else {
		do_action('pre_comment_on_post', $comment_post_ID);
	}

	$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
	$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
	$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;

	/*
	 * проверяем, залогинен ли пользователь
	 */
	$error_comment = array();

	$user = wp_get_current_user();
	if ( $user->exists() ) {
		if ( empty( $user->display_name ) )
			$user->display_name=$user->user_login;
		$comment_author       = $wpdb->escape($user->display_name);
		$comment_author_email = $wpdb->escape($user->user_email);
		$user_ID = get_current_user_id();
		if ( current_user_can('unfiltered_html') ) {
			if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
				kses_remove_filters(); // start with a clean slate
				kses_init_filters(); // set up the filters
			}
		}
	} else {
		if ( get_option('comment_registration') || 'private' == $status )
			$error_comment['error'] = wp_die( 'Ошибка: Вы должны зарегистрироваться или войти, чтобы оставлять комментарии.' );
	}

	$comment_type = '';

	/*
	 * проверяем, заполнил ли пользователь все необходимые поля
 	 */
	if ( get_option('require_name_email') && !$user->exists() ) {
		if ( 6 > strlen($comment_author_email) || '' == $comment_author ){
			$error_comment['error'] = wp_die( 'Ошибка: заполните необходимые поля (Имя, Email).' );
		}elseif ( !is_email($comment_author_email)){
			$error_comment['error'] = wp_die( 'Ошибка: введенный вами email некорректный.' );
		}
	}

	if ( '' == trim($comment_content) ||  '<p><br></p>' == $comment_content ){
		$error_comment['error'] = wp_die( 'Ошибка: Вы забыли про комментарий.' );
	}

	wp_json_encode($error_comment);

	/*
	 * добавляем новый коммент и сразу же обращаемся к нему
	 */
	$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
	$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
	$comment_id = wp_new_comment( $commentdata );
	$comment = get_comment($comment_id);

	die();
}
add_action('wp_ajax_ajaxcomments', 'true_add_ajax_comment'); // wp_ajax_{значение параметра action}
add_action('wp_ajax_nopriv_ajaxcomments', 'true_add_ajax_comment'); // wp_ajax_nopriv_{значение параметра action}

/**********************************************************************************************************************************************************
***********************************************************************************************************************************************************
********************************************************************ФОРМЫ ОБРАТНОЙ СВЯЗИ*******************************************************************
***********************************************************************************************************************************************************
***********************************************************************************************************************************************************/
//Форма обратной связи
function callBackMini(){

	$form_adress = get_option('admin_email');
	
	$site_url = $_SERVER['SERVER_NAME'];

	$alert = array(
		'status' => 0,
		'message' => ''
	);

	if (isset($_POST['name'])) {$name = $_POST['name']; if ($name == '') {unset($name);}}
	if (isset($_POST['phone'])) {$phone = $_POST['phone']; if ($phone == '') {unset($phone);}}

	if (isset($name) && isset($phone)){

		$address = $form_adress;

		$headers  = "Content-type: text/html; charset=UTF-8 \r\n";
		$headers .= "From: $site_url\r\n";
		$headers .= "Bcc: birthday-archive@example.com\r\n";
		
		//$mes = "Имя: $name \nEmail: $email \nСтрана: $country \nСообщение: $comment \nТелефон: $phone \nПодписка на новости: $subscribe";
		
		$mes = '<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>ZURBemails</title>
			<style>
			img {
			max-width: 100%;
			}
			.collapse {
			margin:0;
			padding:0;
			}
			body {
			-webkit-font-smoothing:antialiased;
			-webkit-text-size-adjust:none;
			width: 100%!important;
			height: 100%;
			}
	
			a { color: #2BA6CB;}
	
			.btn {
			text-decoration:none;
			color: #FFF;
			background-color: #666;
			padding:10px 16px;
			font-weight:bold;
			margin-right:10px;
			text-align:center;
			cursor:pointer;
			display: inline-block;
			}
	
			p.callout {
			padding:15px;
			background-color:#ECF8FF;
			margin-bottom: 15px;
			}
			.callout a {
			font-weight:bold;
			color: #2BA6CB;
			}
	
			table.social {
			background-color: #ebebeb;
	
			}
			.social .soc-btn {
			padding: 3px 7px;
			font-size:12px;
			margin-bottom:10px;
			text-decoration:none;
			color: #FFF;font-weight:bold;
			display:block;
			text-align:center;
			}
			a.fb { background-color: #3B5998!important; }
			a.tw { background-color: #1daced!important; }
			a.gp { background-color: #DB4A39!important; }
			a.ms { background-color: #000!important; }
	
			.sidebar .soc-btn {
			display:block;
			width:100%;
			}
	
			table.head-wrap { width: 100%;}
	
			.header.container table td.logo { padding: 15px; }
			.header.container table td.label { padding: 15px; padding-left:0px;}
	
			table.body-wrap { width: 100%;}
	
			table.footer-wrap { width: 100%;	clear:both!important;
			}
			.footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
			.footer-wrap .container td.content p {
			font-size:10px;
			font-weight: bold;
	
			}
	
			h1,h2,h3,h4,h5,h6 {
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
			}
			h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
	
			h1 { font-weight:200; font-size: 44px;}
			h2 { font-weight:200; font-size: 37px;}
			h3 { font-weight:500; font-size: 27px;}
			h4 { font-weight:500; font-size: 23px;}
			h5 { font-weight:900; font-size: 17px;}
			h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#ffffff;}
	
			.collapse { margin:0!important;}
	
			p, ul {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-bottom: 10px;
			font-weight: normal;
			font-size:14px;
			line-height:1.6;
			}
			p.lead { font-size:17px; }
			p.last { margin-bottom:0px;}
	
			ul li {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-left:5px;
			list-style-position: inside;
			}
	
			ul.sidebar {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			background:#ebebeb;
			display:block;
			list-style-type: none;
			}
			ul.sidebar li { display: block; margin:0;}
			ul.sidebar li a {
			text-decoration:none;
			color: #666;
			padding:10px 16px;
			margin-right:10px;
			cursor:pointer;
			border-bottom: 1px solid #777777;
			border-top: 1px solid #FFFFFF;
			display:block;
			margin:0;
			}
			ul.sidebar li a.last { border-bottom-width:0px;}
			ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
	
			.container {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			display:block!important;
			max-width:600px!important;
			margin:0 auto!important;
			clear:both!important;
			}
	
			.content {
			padding:15px;
			max-width:600px;
			margin:0 auto;
			display:block;
			}
	
			.content table { width: 100%; }
	
			.column {
			width: 300px;
			float:left;
			}
			.column tr td { padding: 15px; }
			.column-wrap {
			padding:0!important;
			margin:0 auto;
			max-width:600px!important;
			}
			.column table { width:100%;}
			.social .column {
			width: 280px;
			min-width: 279px;
			float:left;
			}
	
	
			.clear { display: block; clear: both; }
	
			@media only screen and (max-width: 600px) {
	
			a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
	
			div[class="column"] { width: auto!important; float:none!important;}
	
			table.social div[class="column"] {
			width:auto!important;
			}
	
			}
			</style>
	
			</head>
	
			<body bgcolor="#FFFFFF">
	
			<!-- HEADER -->
			<table class="head-wrap" bgcolor="#003576">
			<tr>
			<td></td>
			<td class="header container" >
	
			<div class="content">
			<table>
			<tr>
	
			<td align="left"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Отель "Nord"</h6></td>
			<td align="right"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Обратная связь</h6></td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="body-wrap">
			<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF">
	
			<div class="content">
			<table>
			<tr>
			<td>
			<h3>Сообщение от '.$name.'</h3>
			<!-- Callout Panel -->
			<!-- social & contact -->
			<table class="social" width="100%">
			<tr>
			<td>
			<table align="left" class="column">
			<tr>
			<td>
	
			<h5 class="">Контактная информация:</h5>
			<br/>
			<p>Имя: <strong>'.$name.'</strong></p>
			<p>Телефон: <strong>'.$phone.'</strong></p>
			</td>
			</tr>
			</table>
	
			<span class="clear"></span>
	
			</td>
			</tr>
			</table>
	
			</td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="footer-wrap">
			<tr>
			<td></td>
			<td class="container"></td>
			<td></td>
			</tr>
			</table>
	
			</body>
			</html>';
		
		$send = mail($address, $phone, $mes, $headers);

		if ($send == 'true'){
			$alert = array(
				'status' => 1,
				'message' => 'Ваше сообщение отправлено'
			);
		}else{
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено!'
			);
		}
	}
	
	if (isset($_POST['name']) && isset($_POST['phone'])){
		$name = $_POST['name'];
		$phone = $_POST['phone'];

		if ($name == '' || $phone == '') {
			unset($name);
			unset($phone);
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено! Заполните все поля!'
			);
		}
	}

	echo wp_send_json($alert);

	wp_die();
}
add_action('wp_ajax_callBackMini', 'callBackMini');
add_action('wp_ajax_nopriv_callBackMini', 'callBackMini');

//Форма легкого бронирования
function lightBooking(){

	$form_adress = get_option('admin_email');
	
	$site_url = $_SERVER['SERVER_NAME'];

	$alert = array(
		'status' => 0,
		'message' => ''
	);

	if (isset($_POST['name'])) {$name = $_POST['name']; if ($name == '') {unset($name);}}
	if (isset($_POST['email'])) {$email = $_POST['email']; if ($email == '') {unset($email);}}
	if (isset($_POST['phone'])) {$phone = $_POST['phone']; if ($phone == '') {unset($phone);}}
	if (isset($_POST['arrival'])) {$arrival = $_POST['arrival']; if ($arrival == '') {unset($arrival);}}
	if (isset($_POST['departure'])) {$departure = $_POST['departure']; if ($departure == '') {unset($departure);}}

	if (isset($name) && isset($email) && isset($phone) && isset($arrival) && isset($departure)){

		$address = $form_adress;

		$headers  = "Content-type: text/html; charset=UTF-8 \r\n";
		$headers .= "From: $site_url\r\n";
		$headers .= "Bcc: birthday-archive@example.com\r\n";
		
		//$mes = "Имя: $name \nEmail: $email \nСтрана: $country \nСообщение: $comment \nТелефон: $phone \nПодписка на новости: $subscribe";
		
		$mes = '<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>ZURBemails</title>
			<style>
			img {
			max-width: 100%;
			}
			.collapse {
			margin:0;
			padding:0;
			}
			body {
			-webkit-font-smoothing:antialiased;
			-webkit-text-size-adjust:none;
			width: 100%!important;
			height: 100%;
			}
	
			a { color: #2BA6CB;}
	
			.btn {
			text-decoration:none;
			color: #FFF;
			background-color: #666;
			padding:10px 16px;
			font-weight:bold;
			margin-right:10px;
			text-align:center;
			cursor:pointer;
			display: inline-block;
			}
	
			p.callout {
			padding:15px;
			background-color:#ECF8FF;
			margin-bottom: 15px;
			}
			.callout a {
			font-weight:bold;
			color: #2BA6CB;
			}
	
			table.social {
			background-color: #ebebeb;
	
			}
			.social .soc-btn {
			padding: 3px 7px;
			font-size:12px;
			margin-bottom:10px;
			text-decoration:none;
			color: #FFF;font-weight:bold;
			display:block;
			text-align:center;
			}
			a.fb { background-color: #3B5998!important; }
			a.tw { background-color: #1daced!important; }
			a.gp { background-color: #DB4A39!important; }
			a.ms { background-color: #000!important; }
	
			.sidebar .soc-btn {
			display:block;
			width:100%;
			}
	
			table.head-wrap { width: 100%;}
	
			.header.container table td.logo { padding: 15px; }
			.header.container table td.label { padding: 15px; padding-left:0px;}
	
			table.body-wrap { width: 100%;}
	
			table.footer-wrap { width: 100%;	clear:both!important;
			}
			.footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
			.footer-wrap .container td.content p {
			font-size:10px;
			font-weight: bold;
	
			}
	
			h1,h2,h3,h4,h5,h6 {
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
			}
			h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
	
			h1 { font-weight:200; font-size: 44px;}
			h2 { font-weight:200; font-size: 37px;}
			h3 { font-weight:500; font-size: 27px;}
			h4 { font-weight:500; font-size: 23px;}
			h5 { font-weight:900; font-size: 17px;}
			h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#ffffff;}
	
			.collapse { margin:0!important;}
	
			p, ul {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-bottom: 10px;
			font-weight: normal;
			font-size:14px;
			line-height:1.6;
			}
			p.lead { font-size:17px; }
			p.last { margin-bottom:0px;}
	
			ul li {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-left:5px;
			list-style-position: inside;
			}
	
			ul.sidebar {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			background:#ebebeb;
			display:block;
			list-style-type: none;
			}
			ul.sidebar li { display: block; margin:0;}
			ul.sidebar li a {
			text-decoration:none;
			color: #666;
			padding:10px 16px;
			margin-right:10px;
			cursor:pointer;
			border-bottom: 1px solid #777777;
			border-top: 1px solid #FFFFFF;
			display:block;
			margin:0;
			}
			ul.sidebar li a.last { border-bottom-width:0px;}
			ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
	
			.container {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			display:block!important;
			max-width:600px!important;
			margin:0 auto!important;
			clear:both!important;
			}
	
			.content {
			padding:15px;
			max-width:600px;
			margin:0 auto;
			display:block;
			}
	
			.content table { width: 100%; }
	
			.column {
			width: 300px;
			float:left;
			}
			.column tr td { padding: 15px; }
			.column-wrap {
			padding:0!important;
			margin:0 auto;
			max-width:600px!important;
			}
			.column table { width:100%;}
			.social .column {
			width: 280px;
			min-width: 279px;
			float:left;
			}
	
	
			.clear { display: block; clear: both; }
	
			@media only screen and (max-width: 600px) {
	
			a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
	
			div[class="column"] { width: auto!important; float:none!important;}
	
			table.social div[class="column"] {
			width:auto!important;
			}
	
			}
			</style>
	
			</head>
	
			<body bgcolor="#FFFFFF">
	
			<!-- HEADER -->
			<table class="head-wrap" bgcolor="#003576">
			<tr>
			<td></td>
			<td class="header container" >
	
			<div class="content">
			<table>
			<tr>
	
			<td align="left"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Отель "Nord"</h6></td>
			<td align="right"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Обратная связь</h6></td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="body-wrap">
			<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF">
	
			<div class="content">
			<table>
			<tr>
			<td>
			<h3>Сообщение от '.$name.'</h3>
			<p><strong>Дата заезда:</strong> '.$arrival.'</p>
			<p><strong>Дата выезда:</strong> '.$departure.'</p>
			<!-- Callout Panel -->
			<!-- social & contact -->
			<table class="social" width="100%">
			<tr>
			<td>
			<table align="left" class="column">
			<tr>
			<td>
	
			<h5 class="">Контактная информация:</h5>
			<br/>
			<p>Имя: <strong>'.$name.'</strong></p>
			<p>Email: <strong>'.$email.'</strong></p>
			<p>Телефон: <strong>'.$phone.'</strong></p>
			</td>
			</tr>
			</table>
	
			<span class="clear"></span>
	
			</td>
			</tr>
			</table>
	
			</td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="footer-wrap">
			<tr>
			<td></td>
			<td class="container"></td>
			<td></td>
			</tr>
			</table>
	
			</body>
			</html>';
		
		$send = mail($address, $email, $mes, $headers);

		if ($send == 'true'){
			$alert = array(
				'status' => 200,
				'message' => 'Ваше сообщение отправлено'
			);
		}else{
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено!'
			);
		}
	}
	
	if (isset($name) && isset($email) && isset($phone) && isset($arrival) && isset($departure)){
	}
	
	if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['arrival']) && isset($_POST['departure'])){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$arrival = $_POST['arrival'];
		$departure = $_POST['departure'];
		
		if(!is_email($email)) {
			$alert = array(
				'status' => 1,
				'message' => 'Email введен не верно, проверте внимательно поле!'
			);
		}

		if ($name == '' || $email == '' || $phone == '' || $arrival == '' || $departure == '') {
			unset($name);
			unset($email);
			unset($phone);
			unset($arrival);
			unset($departure);
			
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено! Заполните все поля!'
			);
		}
	}

	echo wp_send_json($alert);

	wp_die();
}
add_action('wp_ajax_lightBooking', 'lightBooking');
add_action('wp_ajax_nopriv_lightBooking', 'lightBooking');

//Полная форма обратной связи
function sendForm(){

	$form_adress = get_option('admin_email');
	
	$site_url = $_SERVER['SERVER_NAME'];

	$alert = array(
		'status' => 0,
		'message' => ''
	);

	if (isset($_POST['name'])) {$name = $_POST['name']; if ($name == '') {unset($name);}}
	if (isset($_POST['email'])) {$email = $_POST['email']; if ($email == '') {unset($email);}}
	if (isset($_POST['phone'])) {$phone = $_POST['phone']; if ($phone == '') {unset($phone);}}
	if (isset($_POST['comment'])) {$comment = $_POST['comment']; if ($comment == '') {unset($comment);}}

	if (isset($name) && isset($email) && isset($phone) && isset($comment)){

		$address = $form_adress;

		$headers  = "Content-type: text/html; charset=UTF-8 \r\n";
		$headers .= "From: $site_url\r\n";
		$headers .= "Bcc: birthday-archive@example.com\r\n";
		
		//$mes = "Имя: $name \nEmail: $email \nСтрана: $country \nСообщение: $comment \nТелефон: $phone \nПодписка на новости: $subscribe";
		
		$mes = '<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>ZURBemails</title>
			<style>
			img {
			max-width: 100%;
			}
			.collapse {
			margin:0;
			padding:0;
			}
			body {
			-webkit-font-smoothing:antialiased;
			-webkit-text-size-adjust:none;
			width: 100%!important;
			height: 100%;
			}
	
			a { color: #2BA6CB;}
	
			.btn {
			text-decoration:none;
			color: #FFF;
			background-color: #666;
			padding:10px 16px;
			font-weight:bold;
			margin-right:10px;
			text-align:center;
			cursor:pointer;
			display: inline-block;
			}
	
			p.callout {
			padding:15px;
			background-color:#ECF8FF;
			margin-bottom: 15px;
			}
			.callout a {
			font-weight:bold;
			color: #2BA6CB;
			}
	
			table.social {
			background-color: #ebebeb;
	
			}
			.social .soc-btn {
			padding: 3px 7px;
			font-size:12px;
			margin-bottom:10px;
			text-decoration:none;
			color: #FFF;font-weight:bold;
			display:block;
			text-align:center;
			}
			a.fb { background-color: #3B5998!important; }
			a.tw { background-color: #1daced!important; }
			a.gp { background-color: #DB4A39!important; }
			a.ms { background-color: #000!important; }
	
			.sidebar .soc-btn {
			display:block;
			width:100%;
			}
	
			table.head-wrap { width: 100%;}
	
			.header.container table td.logo { padding: 15px; }
			.header.container table td.label { padding: 15px; padding-left:0px;}
	
			table.body-wrap { width: 100%;}
	
			table.footer-wrap { width: 100%;	clear:both!important;
			}
			.footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
			.footer-wrap .container td.content p {
			font-size:10px;
			font-weight: bold;
	
			}
	
			h1,h2,h3,h4,h5,h6 {
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
			}
			h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
	
			h1 { font-weight:200; font-size: 44px;}
			h2 { font-weight:200; font-size: 37px;}
			h3 { font-weight:500; font-size: 27px;}
			h4 { font-weight:500; font-size: 23px;}
			h5 { font-weight:900; font-size: 17px;}
			h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#ffffff;}
	
			.collapse { margin:0!important;}
	
			p, ul {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-bottom: 10px;
			font-weight: normal;
			font-size:14px;
			line-height:1.6;
			}
			p.lead { font-size:17px; }
			p.last { margin-bottom:0px;}
	
			ul li {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-left:5px;
			list-style-position: inside;
			}
	
			ul.sidebar {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			background:#ebebeb;
			display:block;
			list-style-type: none;
			}
			ul.sidebar li { display: block; margin:0;}
			ul.sidebar li a {
			text-decoration:none;
			color: #666;
			padding:10px 16px;
			margin-right:10px;
			cursor:pointer;
			border-bottom: 1px solid #777777;
			border-top: 1px solid #FFFFFF;
			display:block;
			margin:0;
			}
			ul.sidebar li a.last { border-bottom-width:0px;}
			ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
	
			.container {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			display:block!important;
			max-width:600px!important;
			margin:0 auto!important;
			clear:both!important;
			}
	
			.content {
			padding:15px;
			max-width:600px;
			margin:0 auto;
			display:block;
			}
	
			.content table { width: 100%; }
	
			.column {
			width: 300px;
			float:left;
			}
			.column tr td { padding: 15px; }
			.column-wrap {
			padding:0!important;
			margin:0 auto;
			max-width:600px!important;
			}
			.column table { width:100%;}
			.social .column {
			width: 280px;
			min-width: 279px;
			float:left;
			}
	
	
			.clear { display: block; clear: both; }
	
			@media only screen and (max-width: 600px) {
	
			a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
	
			div[class="column"] { width: auto!important; float:none!important;}
	
			table.social div[class="column"] {
			width:auto!important;
			}
	
			}
			</style>
	
			</head>
	
			<body bgcolor="#FFFFFF">
	
			<!-- HEADER -->
			<table class="head-wrap" bgcolor="#003576">
			<tr>
			<td></td>
			<td class="header container" >
	
			<div class="content">
			<table>
			<tr>
	
			<td align="left"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Отель "Nord"</h6></td>
			<td align="right"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Обратная связь</h6></td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="body-wrap">
			<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF">
	
			<div class="content">
			<table>
			<tr>
			<td>
			<h3>Сообщение от '.$name.'</h3>
			<p><strong>Сообщение:</strong> '.$comment.'</p>

			<!-- Callout Panel -->
			<!-- social & contact -->
			<table class="social" width="100%">
			<tr>
			<td>
			<table align="left" class="column">
			<tr>
			<td>
	
			<h5 class="">Контактная информация:</h5>
			<br/>
			<p>Имя: <strong>'.$name.'</strong></p>
			<p>Email: <strong>'.$email.'</strong></p>
			<p>Телефон: <strong>'.$phone.'</strong></p>
			</td>
			</tr>
			</table>
	
			<span class="clear"></span>
	
			</td>
			</tr>
			</table>
	
			</td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="footer-wrap">
			<tr>
			<td></td>
			<td class="container"></td>
			<td></td>
			</tr>
			</table>
	
			</body>
			</html>';
		
		$send = mail($address, $email, $mes, $headers);

		if ($send == 'true'){
			$alert = array(
				'status' => 200,
				'message' => 'Ваше сообщение отправлено'
			);
		}else{
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено!'
			);
		}
	}
	
	if (isset($name) && isset($email) && isset($phone) && isset($comment)){
	}
	
	if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['comment'])){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$comment = $_POST['comment'];
		
		if(!is_email($email)) {
			$alert = array(
				'status' => 1,
				'message' => 'Email введен не верно, проверте внимательно поле!'
			);
		}

		if ($name == '' || $email == '' || $phone == '' || $comment == '') {
			unset($name);
			unset($email);
			unset($phone);
			unset($comment);
			
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено! Заполните все поля!'
			);
		}
	}

	echo wp_send_json($alert);

	wp_die();
}
add_action('wp_ajax_sendForm', 'sendForm');
add_action('wp_ajax_nopriv_sendForm', 'sendForm');

//Полная форма обратной связи
function sendMiniForm(){

	$form_adress = get_option('admin_email');
	
	$site_url = $_SERVER['SERVER_NAME'];

	$alert = array(
		'status' => 0,
		'message' => ''
	);

	if (isset($_POST['name'])) {$name = $_POST['name']; if ($name == '') {unset($name);}}
	if (isset($_POST['phone'])) {$phone = $_POST['phone']; if ($phone == '') {unset($phone);}}

	if (isset($name) && isset($phone)){

		$address = $form_adress;

		$headers  = "Content-type: text/html; charset=UTF-8 \r\n";
		$headers .= "From: $site_url\r\n";
		$headers .= "Bcc: birthday-archive@example.com\r\n";
		
		//$mes = "Имя: $name \nEmail: $email \nСтрана: $country \nСообщение: $comment \nТелефон: $phone \nПодписка на новости: $subscribe";
		
		$mes = '<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>ZURBemails</title>
			<style>
			img {
			max-width: 100%;
			}
			.collapse {
			margin:0;
			padding:0;
			}
			body {
			-webkit-font-smoothing:antialiased;
			-webkit-text-size-adjust:none;
			width: 100%!important;
			height: 100%;
			}
	
			a { color: #2BA6CB;}
	
			.btn {
			text-decoration:none;
			color: #FFF;
			background-color: #666;
			padding:10px 16px;
			font-weight:bold;
			margin-right:10px;
			text-align:center;
			cursor:pointer;
			display: inline-block;
			}
	
			p.callout {
			padding:15px;
			background-color:#ECF8FF;
			margin-bottom: 15px;
			}
			.callout a {
			font-weight:bold;
			color: #2BA6CB;
			}
	
			table.social {
			background-color: #ebebeb;
	
			}
			.social .soc-btn {
			padding: 3px 7px;
			font-size:12px;
			margin-bottom:10px;
			text-decoration:none;
			color: #FFF;font-weight:bold;
			display:block;
			text-align:center;
			}
			a.fb { background-color: #3B5998!important; }
			a.tw { background-color: #1daced!important; }
			a.gp { background-color: #DB4A39!important; }
			a.ms { background-color: #000!important; }
	
			.sidebar .soc-btn {
			display:block;
			width:100%;
			}
	
			table.head-wrap { width: 100%;}
	
			.header.container table td.logo { padding: 15px; }
			.header.container table td.label { padding: 15px; padding-left:0px;}
	
			table.body-wrap { width: 100%;}
	
			table.footer-wrap { width: 100%;	clear:both!important;
			}
			.footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
			.footer-wrap .container td.content p {
			font-size:10px;
			font-weight: bold;
	
			}
	
			h1,h2,h3,h4,h5,h6 {
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
			}
			h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
	
			h1 { font-weight:200; font-size: 44px;}
			h2 { font-weight:200; font-size: 37px;}
			h3 { font-weight:500; font-size: 27px;}
			h4 { font-weight:500; font-size: 23px;}
			h5 { font-weight:900; font-size: 17px;}
			h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#ffffff;}
	
			.collapse { margin:0!important;}
	
			p, ul {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-bottom: 10px;
			font-weight: normal;
			font-size:14px;
			line-height:1.6;
			}
			p.lead { font-size:17px; }
			p.last { margin-bottom:0px;}
	
			ul li {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			margin-left:5px;
			list-style-position: inside;
			}
	
			ul.sidebar {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			background:#ebebeb;
			display:block;
			list-style-type: none;
			}
			ul.sidebar li { display: block; margin:0;}
			ul.sidebar li a {
			text-decoration:none;
			color: #666;
			padding:10px 16px;
			margin-right:10px;
			cursor:pointer;
			border-bottom: 1px solid #777777;
			border-top: 1px solid #FFFFFF;
			display:block;
			margin:0;
			}
			ul.sidebar li a.last { border-bottom-width:0px;}
			ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
	
			.container {
			font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
			display:block!important;
			max-width:600px!important;
			margin:0 auto!important;
			clear:both!important;
			}
	
			.content {
			padding:15px;
			max-width:600px;
			margin:0 auto;
			display:block;
			}
	
			.content table { width: 100%; }
	
			.column {
			width: 300px;
			float:left;
			}
			.column tr td { padding: 15px; }
			.column-wrap {
			padding:0!important;
			margin:0 auto;
			max-width:600px!important;
			}
			.column table { width:100%;}
			.social .column {
			width: 280px;
			min-width: 279px;
			float:left;
			}
	
	
			.clear { display: block; clear: both; }
	
			@media only screen and (max-width: 600px) {
	
			a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
	
			div[class="column"] { width: auto!important; float:none!important;}
	
			table.social div[class="column"] {
			width:auto!important;
			}
	
			}
			</style>
	
			</head>
	
			<body bgcolor="#FFFFFF">
	
			<!-- HEADER -->
			<table class="head-wrap" bgcolor="#003576">
			<tr>
			<td></td>
			<td class="header container" >
	
			<div class="content">
			<table>
			<tr>
	
			<td align="left"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Отель "Nord"</h6></td>
			<td align="right"><h6 class="collapse" style="font-weight: 900; font-size: 14px; text-transform: uppercase; color: #ffffff;">Обратная связь</h6></td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="body-wrap">
			<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF">
	
			<div class="content">
			<table>
			<tr>
			<td>
			<h3>Сообщение от '.$name.'</h3>
			<!-- Callout Panel -->
			<!-- social & contact -->
			<table class="social" width="100%">
			<tr>
			<td>
			<table align="left" class="column">
			<tr>
			<td>
	
			<h5 class="">Контактная информация:</h5>
			<br/>
			<p>Имя: <strong>'.$name.'</strong></p>
			<p>Телефон: <strong>'.$phone.'</strong></p>
			</td>
			</tr>
			</table>
	
			<span class="clear"></span>
	
			</td>
			</tr>
			</table>
	
			</td>
			</tr>
			</table>
			</div>
	
			</td>
			<td></td>
			</tr>
			</table>
	
			<table class="footer-wrap">
			<tr>
			<td></td>
			<td class="container"></td>
			<td></td>
			</tr>
			</table>
	
			</body>
			</html>';
		
		$send = mail($address, $phone, $mes, $headers);

		if ($send == 'true'){
			$alert = array(
				'status' => 200,
				'message' => 'Ваше сообщение отправлено'
			);
		}else{
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено!'
			);
		}
	}
	
	if (isset($name) && isset($phone)){
	}
	
	if (isset($_POST['name']) && isset($_POST['phone'])){
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		
		if ($name == '' || $phone == '') {
			unset($name);
			unset($phone);
			
			$alert = array(
				'status' => 1,
				'message' => 'Ошибка, сообщение не отправлено! Заполните все поля!'
			);
		}
	}

	echo wp_send_json($alert);

	wp_die();
}
add_action('wp_ajax_sendMiniForm', 'sendMiniForm');
add_action('wp_ajax_nopriv_sendMiniForm', 'sendMiniForm');
