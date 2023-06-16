<?php
/**
 * Include in the theme Template:
 * 
	<?php 
	if ( empty( get_field( 'single_post_main_image_field' )) ) {
		?>
		<img src="<?php echo $theme_path; ?>/img/service-page-img-1.jpg">
		<?php
	} else { 
		?>
		<img src="<?php echo get_field( 'single_post_main_image_field' ); ?> ">
		<?php
	}
	?>
 * 
 * 
 * Output in the editor of fields for filling specific pages
 * 
 * https://www.advancedcustomfields.com/resources/register-fields-via-php/
 * https://www.advancedcustomfields.com/resources/custom-location-rules--v5-8/
 * https://www.advancedcustomfields.com/resources/custom-location-rules-v5-8/
 * 
 * The parameters can be viewed in the admin menu Custom Fields -> Field Groups -> Settings -> Location Rules, 
 * where the output logic goes: 
 *
  array(
    'param' => 'page',
    'operator' => '==',
    'value' => '15188',
  ),
 */

if ( ! function_exists( 'mp_acf_fields_for_posts' ) ) { 
    function mp_acf_fields_for_posts() {
        acf_add_local_field_group( array(
            'key' => 'single_posts_options_page',
            'title' => 'Редагування сторінки',
            'fields' => array(),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ),
                ),
            ),
        ));

        acf_add_local_field( array(
            'key' => 'single_post_card_thumbnail_field',
            'label' => 'Головне зображення картки',
            'name' => 'single_post_card_thumbnail',
            'type' => 'image',
            'parent' => 'single_posts_options_page',
            'return_format' => 'url',
        ));

        acf_add_local_field( array(
            'key' => 'single_post_card_icon_field',
            'label' => 'Іконка картки',
            'name' => 'single_post_card_icon',
            'type' => 'image',
            'parent' => 'single_posts_options_page',
            'return_format' => 'url',
        ));

        acf_add_local_field( array(
            'key' => 'single_post_card_title_field',
            'label' => 'Назва картки',
            'name' => 'single_post_card_title',
            'type' => 'text',
            'parent' => 'single_posts_options_page',
            'placeholder' => 'Додайте назву картки...',
        ));

        acf_add_local_field( array(
            'key' => 'single_post_card_excerpt_field',
            'label' => 'Короткий опис картки',
            'name' => 'single_post_card_excerpt',
            'type' => 'textarea',
            'parent' => 'single_posts_options_page',
            'placeholder' => 'Додайте короткий опис картки...',
        ));

    }
}

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
// Створюємо ACF-поля якщо або активований ACF як окремий плагін, або його функціонал знаходиться у підпапці цього плагіну 
if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || class_exists( 'acf' ) ) {
	add_action( 'acf/init', 'mp_acf_fields_for_posts' );
}


