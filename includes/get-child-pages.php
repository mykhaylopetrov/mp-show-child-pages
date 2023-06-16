<?php

/**
 * Get Child Pages List for Current Page:
 * 
    [0] => WP_Post Object
        (
            [ID] => 15744
            [post_author] => 15
            [post_date] => 2021-10-22 11:59:33
            [post_date_gmt] => 2021-10-22 08:59:33
            [post_content] => 
            [post_title] => Case: SEO promotion of one-page restaurant site
            [post_excerpt] => 
            [post_status] => publish
            [comment_status] => closed
            [ping_status] => closed
            [post_password] => 
            [post_name] => case-yak-zbilshyty-orhanichnyi-trafik-na-sait-restorannoho-kompleksu
            [to_ping] => 
            [pinged] => 
            [post_modified] => 2022-04-07 16:07:29
            [post_modified_gmt] => 2022-04-07 13:07:29
            [post_content_filtered] => 
            [post_parent] => 15884
            [guid] => http://click.local/case-yak-zbilshyty-orhanichnyi-trafik-na-sait-restorannoho-kompleksu/
            [menu_order] => 0
            [post_type] => page
            [post_mime_type] => 
            [comment_count] => 0
            [filter] => raw
        )

    [1] => WP_Post Object
        (...)

 */
if ( ! function_exists( 'mp_get_child_pages_list' ) ) {
	function mp_get_child_pages_list() {
		if ( ! is_page() ) {
			return;
		}
		
		$childPages = '';
		$currentPageId = get_the_ID();
		$allPages = ( new WP_Query() )->query( 
			array ( 
				'post_type' => 'page', 
				'posts_per_page' => -1 
			) 
		);
		wp_reset_postdata();
		
		if ( ! empty( get_the_ID() ) ) {
			$childPages = get_page_children( $currentPageId, $allPages );
		}

		if ( ! empty( $childPages ) ) {
			return $childPages;
		}
	}
}

if ( ! function_exists( 'mp_get_current_page_locale' ) ) {
	function mp_get_current_page_locale() {
		// uk_UA, ru_RU, en_US
		return get_locale();
	}
}

/**
 * Get Home page URL with current languages (with WPML plugin)
 * 
 * https://site.com     - if default language
 * https://site.com/ru/ - if selected Russian language
 * https://site.com/en/ - if selected English language
 * 
 * https://wpml.org/wpml-hook/wpml_home_url/
 */
if ( ! function_exists( 'mp_get_homepage_url' ) ) {
    function mp_get_homepage_url() {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
			return apply_filters( 'wpml_home_url', get_option( 'home' ) );
		}
    }
} 


/**
 * Show Child Pages Shortcode:
 * 
 * [mp_get_child_pages title="Заголовок секції"]
 * [mp_get_child_pages title="Заголовок секции"]
 * [mp_get_child_pages title="Section Title"]
 * 
 * https://wp-kama.ru/handbook/codex/shortcodes
 * https://developer.wordpress.org/reference/functions/get_page_children/
 * https://wp-kama.ru/function/get_page_children
 * 
 */
if ( ! function_exists( 'mp_get_child_pages_shortcode' ) ) {
	function mp_get_child_pages_shortcode( $atts ) {
		ob_start();

		$atts = shortcode_atts( [
			'title' => '', // Заголовок секції
		], $atts );

		$allChildPages = mp_get_child_pages_list();

		// switch ( mp_get_current_page_locale() ) {
		// 	case "uk_UA":
		// 		echo '<h1>Заголовок секції</h1>';
		// 		break;
		// 	case "ru_RU":
		// 		echo '<h1>Заголовок секции</h1>';
		// 		break;
		// 	case "en_US":
		// 		echo '<h1>Section Title</h1>';
		// 		break;	
		// }

		if ( ! empty( $allChildPages ) ) {
			echo '<section class="mp-show-child-pages">';
				echo '<div class="container">';
					echo '<h1 class="mp-show-child-pages__title">' . $atts['title'] . '</h1>';
					echo '<div class="mp-show-child-pages__list">';
						foreach ( $allChildPages as $page ) {	
							// Заголовок картки
							// $pageTitle = $page->post_title;
							if ( ! empty( get_field( 'single_post_card_title_field', $page->ID ) ) ) {
								$pageTitle = get_field( 'single_post_card_title_field', $page->ID );
							} else $pageTitle = $page->post_title;
							
							// Короткий опис картки 
							// $pageExcerpt = $page->post_excerpt;
							if ( ! empty( get_field( 'single_post_card_excerpt_field', $page->ID ) ) ) {
								$pageExcerpt = get_field( 'single_post_card_excerpt_field', $page->ID );
							} else $pageExcerpt = 'Короткий опис картки...';

							// Іконка картки (повний URL картинки)
							if ( ! empty( get_field( 'single_post_card_icon_field', $page->ID ) ) ) {
								$pageCardIcon = get_field( 'single_post_card_icon_field', $page->ID );
							} else $pageCardIcon = '';

							// Основне зображення картки (повний URL картинки)
							if ( ! empty( get_field( 'single_post_card_thumbnail_field', $page->ID ) ) ) {
								$pageThumbnail = get_field( 'single_post_card_thumbnail_field', $page->ID );
							} else $pageThumbnail = MP_SHOW_CHILD_PAGES_URL . '/assets/images/no-image.png';

							echo '<div class="mp-show-child-pages__item mp-show-child-pages-item">';
								echo '<div class="mp-show-child-pages-item__image">';
									echo '<div class="mp-show-child-pages-item__thumb"><a href="'. get_page_link( $page->ID ) .'"><img src="' . $pageThumbnail . '"></a></div>';
									echo '<div class="mp-show-child-pages-item__icon"><img src="' . $pageCardIcon . '"></div>';
								echo '</div>';
								echo '<div class="mp-show-child-pages-item__title"><a href="' . get_page_link( $page->ID ) . '">'. $pageTitle .'</a></div>';
								echo '<div class="mp-show-child-pages-item__excerpt">'. $pageExcerpt .'</div>';
							echo '</div>';

						}
					echo '</div>';
				echo '</div>';
			echo '</section>';
		}

		return ob_get_clean();
	}
}
add_shortcode( 'mp_get_child_pages', 'mp_get_child_pages_shortcode' );

/**
 * Get all Child Pages Tags 
 */
if ( ! function_exists( 'mp_get_all_child_pages_tags' ) ) {
	function mp_get_all_child_pages_tags() {
		$allChildPages = mp_get_child_pages_list();
		$childPagesTags = array();

		if ( ! empty( $allChildPages ) ) {
			foreach ( $allChildPages as $page ) {
				if ( ! empty( get_the_tags( $page->ID )[0]->slug ) && ! empty( get_the_tags( $page->ID )[0]->name ) ) {
					$childPagesTags[] = array(
						'tag_slug' => get_the_tags( $page->ID )[0]->slug,
						'tag_name' => get_the_tags( $page->ID )[0]->name
					);
				}
			}

			return array_unique( $childPagesTags, SORT_REGULAR );
		}
	}
}

/**
 * Get all Child Pages by Tag
 */
if ( ! function_exists( 'mp_get_child_pages_by_tag' ) ) {
	function mp_get_child_pages_by_tag( $tagSlug = array() ) {
		if ( ! is_page() ) {
			return;
		}

		$childPages = '';
		$currentPageId = get_the_ID();
		$allPages = get_posts(
			array(
				'post_type' 	 => 'page',
				'posts_per_page' => -1,
				// 'tag' 			 => array( 'tag-1', 'tag-2', 'tag-3' ) 
				'tag' 			 => $tagSlug,
				'orderby'		 => 'date'
			)
		);
		wp_reset_postdata();

		if ( ! empty( get_the_ID() ) ) {
			$childPages = get_page_children( $currentPageId, $allPages );
		}

		if ( ! empty( $childPages ) ) {
			return $childPages;
		}
	}
}

/**
 * Show Child Pages with Tags Shortcode:
 * 
 * [mp_get_child_pages_with_tags title="Заголовок секції"]
 * [mp_get_child_pages_with_tags title="Заголовок секции"]
 * [mp_get_child_pages_with_tags title="Section Title"]
 * 
 */
if ( ! function_exists( 'mp_get_child_pages_with_tags_shortcode' ) ) {
	function mp_get_child_pages_with_tags_shortcode( $atts ) {
		ob_start();

		$atts = shortcode_atts( [
			'title' => '', // Section Title
		], $atts );

		$allTags = array();
		$allTags = array_values( mp_get_all_child_pages_tags() );
		$allTagTitle = '';
		$pageExcerpt = '';
		if ( ( get_locale() === 'uk_UA' || get_locale() === 'uk-UA' || get_locale() === 'uk' ) ) {
			$allTagTitle = 'Всі';
			$pageExcerpt = 'Короткий опис картки...';
		} elseif ( ( get_locale() === 'ru_RU' || get_locale() === 'ru-RU' || get_locale() === 'ru' ) ) {
			$allTagTitle = 'Все';
			$pageExcerpt = 'Краткое описание карточки...';
		} elseif ( ( get_locale() ==='en_US' || get_locale() ==='en-US' || get_locale() ==='en' ) ) {
			$allTagTitle = 'All';
			$pageExcerpt = 'Short description of the card...';
		}
		?>
		
		<section class="mptabs">
			<?php if ( $atts['title'] ) {
				?>
				<h1 class="mptabs__title"><?php echo $atts['title']; ?></h1>
				<?php
			} ?>
			<div class="tabs-container">
				<div class="tabs-block">
					<div class="tabs">
						<?php 
							if ( wp_is_mobile() ) {
								?>
								<select class="tabs__select">
									<option value="id-all"><?php echo $allTagTitle; ?></option>
									<?php for ( $i = 0; $i < count( $allTags ); $i++ ) { ?>
										<option value="id-<?php echo $i; ?>"><?php echo $allTags[$i]['tag_name']; ?></option>
									<?php } ?>
								</select>

								<div class="tabs__list-item" data-name="id-all" hidden>
									<?php 
									$allChildPages = mp_get_child_pages_list();
									if ( ! empty( $allChildPages ) ) {
										echo '<section class="mp-show-child-pages">';
											echo '<div class="container">';
												echo '<div class="mp-show-child-pages__list">';
													foreach ( $allChildPages as $page ) {	
														// Заголовок картки
														// $pageTitle = $page->post_title;
														if ( ! empty( get_field( 'single_post_card_title_field', $page->ID ) ) ) {
															$pageTitle = get_field( 'single_post_card_title_field', $page->ID );
														} else $pageTitle = $page->post_title;
														
														// Короткий опис картки 
														// $pageExcerpt = $page->post_excerpt;
														if ( ! empty( get_field( 'single_post_card_excerpt_field', $page->ID ) ) ) {
															$pageExcerpt = get_field( 'single_post_card_excerpt_field', $page->ID );
														} else $pageExcerpt;
							
														// Іконка картки (повний URL картинки)
														if ( ! empty( get_field( 'single_post_card_icon_field', $page->ID ) ) ) {
															$pageCardIcon = get_field( 'single_post_card_icon_field', $page->ID );
														} else $pageCardIcon = '';
							
														// Основне зображення картки (повний URL картинки)
														if ( ! empty( get_field( 'single_post_card_thumbnail_field', $page->ID ) ) ) {
															$pageThumbnail = get_field( 'single_post_card_thumbnail_field', $page->ID );
														} else $pageThumbnail = MP_SHOW_CHILD_PAGES_URL . '/assets/images/no-image.png';
							
														echo '<div class="mp-show-child-pages__item mp-show-child-pages-item">';
															echo '<div class="mp-show-child-pages-item__image">';
																echo '<div class="mp-show-child-pages-item__thumb"><a href="'. get_page_link( $page->ID ) .'"><img src="' . $pageThumbnail . '"></a></div>';
																echo '<div class="mp-show-child-pages-item__icon"><img src="' . $pageCardIcon . '"></div>';
															echo '</div>';
															echo '<div class="mp-show-child-pages-item__title"><a href="' . get_page_link( $page->ID ) . '">'. $pageTitle .'</a></div>';
															echo '<div class="mp-show-child-pages-item__excerpt">'. $pageExcerpt .'</div>';
														echo '</div>';
							
													}
												echo '</div>';
											echo '</div>';
										echo '</section>';
									}
									?>
								</div>

								<?php for ( $i = 0; $i < count( $allTags ); $i++ ) { ?>
									<div class="tabs__list-item" data-name="id-<?php echo $i; ?>" hidden>
										<?php 
										if ( ! empty( mp_get_child_pages_by_tag( $allTags[$i]['tag_slug'] ) ) ) {
											echo '<div class="mp-show-child-pages">';
											echo '<div class="container">';
											echo '<div class="mp-show-child-pages__list">';
											foreach ( mp_get_child_pages_by_tag( $allTags[$i]['tag_slug']) as $page ) {
												// Заголовок картки
												// $pageTitle = $page->post_title;
												if ( ! empty( get_field( 'single_post_card_title_field', $page->ID ) ) ) {
													$pageTitle = get_field('single_post_card_title_field', $page->ID);
												} else $pageTitle = $page->post_title;
	
												// Короткий опис картки 
												// $pageExcerpt = $page->post_excerpt;
												if ( ! empty( get_field( 'single_post_card_excerpt_field', $page->ID ) ) ) {
													$pageExcerpt = get_field( 'single_post_card_excerpt_field', $page->ID );
												} else $pageExcerpt = 'Короткий опис картки...';
	
												// Іконка картки (повний URL картинки)
												if ( ! empty( get_field( 'single_post_card_icon_field', $page->ID ) ) ) {
													$pageCardIcon = get_field( 'single_post_card_icon_field', $page->ID );
												} else $pageCardIcon = '';
	
												// Основне зображення картки (повний URL картинки)
												if ( ! empty( get_field( 'single_post_card_thumbnail_field', $page->ID ) ) ) {
													$pageThumbnail = get_field( 'single_post_card_thumbnail_field', $page->ID );
												} else $pageThumbnail = MP_SHOW_CHILD_PAGES_URL . '/assets/images/no-image.png';
	
												echo '<div class="mp-show-child-pages__item mp-show-child-pages-item">';
												echo '<div class="mp-show-child-pages-item__image">';
												echo '<div class="mp-show-child-pages-item__thumb"><a href="' . get_page_link( $page->ID ) . '"><img src="' . $pageThumbnail . '"></a></div>';
												echo '<div class="mp-show-child-pages-item__icon"><img src="' . $pageCardIcon . '"></div>';
												echo '</div>';
												echo '<div class="mp-show-child-pages-item__title"><a href="' . get_page_link( $page->ID ) . '">' . $pageTitle . '</a></div>';
												echo '<div class="mp-show-child-pages-item__excerpt">' . $pageExcerpt . '</div>';
												echo '</div>';
											}
											echo '</div>';
											echo '</div>';
											echo '</div>';
										}
										?>
									</div>
								<?php } ?>
								
								<?php
							} else {
								// if PC
								echo '<input type="radio" name="tabs" id="' . "tab-all" . '" checked="checked" />';
								echo '<label for="' . "tab-all" . '">' . $allTagTitle . '</label>';
								echo '<div class="tab">';
									$allChildPages = mp_get_child_pages_list();
									if ( ! empty( $allChildPages ) ) {
										echo '<section class="mp-show-child-pages">';
											echo '<div class="container">';
												echo '<div class="mp-show-child-pages__list">';
													foreach ( $allChildPages as $page ) {	
														// Заголовок картки
														// $pageTitle = $page->post_title;
														if ( ! empty( get_field( 'single_post_card_title_field', $page->ID ) ) ) {
															$pageTitle = get_field( 'single_post_card_title_field', $page->ID );
														} else $pageTitle = $page->post_title;
														
														// Короткий опис картки 
														// $pageExcerpt = $page->post_excerpt;
														if ( ! empty( get_field( 'single_post_card_excerpt_field', $page->ID ) ) ) {
															$pageExcerpt = get_field( 'single_post_card_excerpt_field', $page->ID );
														} else $pageExcerpt;
							
														// Іконка картки (повний URL картинки)
														if ( ! empty( get_field( 'single_post_card_icon_field', $page->ID ) ) ) {
															$pageCardIcon = get_field( 'single_post_card_icon_field', $page->ID );
														} else $pageCardIcon = '';
							
														// Основне зображення картки (повний URL картинки)
														if ( ! empty( get_field( 'single_post_card_thumbnail_field', $page->ID ) ) ) {
															$pageThumbnail = get_field( 'single_post_card_thumbnail_field', $page->ID );
														} else $pageThumbnail = MP_SHOW_CHILD_PAGES_URL . '/assets/images/no-image.png';
							
														echo '<div class="mp-show-child-pages__item mp-show-child-pages-item">';
															echo '<div class="mp-show-child-pages-item__image">';
																echo '<div class="mp-show-child-pages-item__thumb"><a href="'. get_page_link( $page->ID ) .'"><img src="' . $pageThumbnail . '"></a></div>';
																echo '<div class="mp-show-child-pages-item__icon"><img src="' . $pageCardIcon . '"></div>';
															echo '</div>';
															echo '<div class="mp-show-child-pages-item__title"><a href="' . get_page_link( $page->ID ) . '">'. $pageTitle .'</a></div>';
															echo '<div class="mp-show-child-pages-item__excerpt">'. $pageExcerpt .'</div>';
														echo '</div>';
							
													}
												echo '</div>';
											echo '</div>';
										echo '</section>';
									}
								echo '</div>';

								for ( $i = 0; $i < count( $allTags ); $i++ ) {
									if ( $i == 0 ) {
										echo '<input type="radio" name="tabs" id="' . "tab$i" . '" />';
									} else {
										echo '<input type="radio" name="tabs" id="' . "tab$i" . '" />';
									}
									echo '<label for="' . "tab$i" . '">' . $allTags[$i]['tag_name']. '</label>';
									echo '<div class="tab">';
										if ( ! empty( mp_get_child_pages_by_tag( $allTags[$i]['tag_slug'] ) ) ) {
											echo '<div class="mp-show-child-pages">';
											echo '<div class="container">';
											// echo '<h1 class="mp-show-child-pages__title">' . $atts['title'] . '</h1>';
											echo '<div class="mp-show-child-pages__list">';
											foreach ( mp_get_child_pages_by_tag( $allTags[$i]['tag_slug']) as $page ) {
												// Заголовок картки
												// $pageTitle = $page->post_title;
												if ( ! empty( get_field( 'single_post_card_title_field', $page->ID ) ) ) {
													$pageTitle = get_field('single_post_card_title_field', $page->ID);
												} else $pageTitle = $page->post_title;
	
												// Короткий опис картки 
												// $pageExcerpt = $page->post_excerpt;
												if ( ! empty( get_field( 'single_post_card_excerpt_field', $page->ID ) ) ) {
													$pageExcerpt = get_field( 'single_post_card_excerpt_field', $page->ID );
												} else $pageExcerpt = 'Короткий опис картки...';
	
												// Іконка картки (повний URL картинки)
												if ( ! empty( get_field( 'single_post_card_icon_field', $page->ID ) ) ) {
													$pageCardIcon = get_field( 'single_post_card_icon_field', $page->ID );
												} else $pageCardIcon = '';
	
												// Основне зображення картки (повний URL картинки)
												if ( ! empty( get_field( 'single_post_card_thumbnail_field', $page->ID ) ) ) {
													$pageThumbnail = get_field( 'single_post_card_thumbnail_field', $page->ID );
												} else $pageThumbnail = MP_SHOW_CHILD_PAGES_URL . '/assets/images/no-image.png';
	
												echo '<div class="mp-show-child-pages__item mp-show-child-pages-item">';
												echo '<div class="mp-show-child-pages-item__image">';
												echo '<div class="mp-show-child-pages-item__thumb"><a href="' . get_page_link( $page->ID ) . '"><img src="' . $pageThumbnail . '"></a></div>';
												echo '<div class="mp-show-child-pages-item__icon"><img src="' . $pageCardIcon . '"></div>';
												echo '</div>';
												echo '<div class="mp-show-child-pages-item__title"><a href="' . get_page_link( $page->ID ) . '">' . $pageTitle . '</a></div>';
												echo '<div class="mp-show-child-pages-item__excerpt">' . $pageExcerpt . '</div>';
												echo '</div>';
											}
											echo '</div>';
											echo '</div>';
											echo '</div>';
										}
									echo '</div>';
								}
							}
						?>
					</div>
				</div>
			</div>
		</section>
		<?php

		return ob_get_clean();
	}
}
add_shortcode( 'mp_get_child_pages_with_tags', 'mp_get_child_pages_with_tags_shortcode' );