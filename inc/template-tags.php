<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package pine-alpha
 */

 function pine_alpha_get_category_list($ID, $url) {
	$categories = get_the_category($ID);
	$i = 0;
	$length = count($categories);

	if ( $categories  ) {
		echo '<span class="categories-wrapper">';
		foreach  ($categories as $category) {
			if ( $i != $length - 1 ) {
				if ( $url == true ) {
					echo '<span class="category-label"><a href="' . get_category_link( $category->term_taxonomy_id ) . '">' . $category->name . '</a><span class="separator">,</span></span>';
				} else {
					echo '<span class="category-label">' . $category->name . ',</span>';
				}
			} else {
				if ( $url == true ) {
					echo '<span class="category-label"><a href="' . get_category_link( $category->term_taxonomy_id ) . '">' . $category->name . '</a></span>';
				} else {
					echo '<span class="category-label">' . $category->name . '</span>';
				}
			}
			$i++;
		}
		echo '</span>';
	}
 }

if ( ! function_exists( 'pine_alpha_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function pine_alpha_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( is_singular() && !is_front_page() ) {
			$time_string = __( 'Posted on ', 'pine-alpha') . $time_string;
		}

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$update_string = '<time class="updated" datetime="%3$s">%4$s</time>';

			if ( is_singular() && !is_front_page() ) {
				$time_string .= ', ' . __( 'Updated on ', 'pine-alpha') . $update_string;
			}
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		echo '<span class="posted-on">' . $time_string . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'pine_alpha_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function pine_alpha_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'pine-alpha' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'pine_alpha_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function pine_alpha_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'pine-alpha' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in: %1$s', 'pine-alpha' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'pine-alpha' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged: %1$s', 'pine-alpha' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'pine-alpha' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'pine_alpha_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function pine_alpha_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;
