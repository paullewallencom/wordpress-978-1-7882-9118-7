<?php

/*
  Plugin Name: Chapter 4 - Book Reviews V2
  Plugin URI: 
  Description: Companion to recipe 'Adding a new section to the custom post type editor'
  Author: ylefebvre
  Version: 1.0
  Author URI: http://ylefebvre.ca/
 */

/****************************************************************************
 * Code from recipe 'Creating a custom post type'
 ****************************************************************************/

add_action( 'init', 'ch4_br_create_book_post_type' );

function ch4_br_create_book_post_type() {
	register_post_type( 'book_reviews',
		array(
				'labels' => array(
				'name' => 'Book Reviews',
				'singular_name' => 'Book Review',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Book Review',
				'edit' => 'Edit',
				'edit_item' => 'Edit Book Review',
				'new_item' => 'New Book Review',
				'view' => 'View',
				'view_item' => 'View Book Review',
				'search_items' => 'Search Book Reviews',
				'not_found' => 'No Book Reviews found',
				'not_found_in_trash' => 'No Book Reviews found in Trash',
				'parent' => 'Parent Book Review'
			),
		'public' => true,
		'menu_position' => 20,
		'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
		'taxonomies' => array( '' ),
		'menu_icon' => plugins_url( 'book-reviews.png', __FILE__ ),
		'has_archive' => false,
		'exclude_from_search' => true
		)
	);
}

/****************************************************************************
 * Code from recipe 'Adding a new section to the custom post type editor'
 ****************************************************************************/

// Register function to be called when admin interface is visited
add_action( 'admin_init', 'ch4_br_admin_init' );

// Function to register new meta box for book review post editor
function ch4_br_admin_init() {
	add_meta_box( 'ch4_br_review_details_meta_box', 'Book Review Details', 'ch4_br_display_review_details_meta_box', 'book_reviews', 'normal', 'high' );
}

// Function to display meta box contents
function ch4_br_display_review_details_meta_box( $book_review ) { 
	// Retrieve current author and rating based on book review ID
	$book_author = esc_html( get_post_meta( $book_review->ID, 'book_author', true ) );
	$book_rating = intval( get_post_meta( $book_review->ID, 'book_rating', true ) );
	?>
	<table>
		<tr>
			<td style="width: 150px">Book Author</td>
			<td><input type='text' size='80' name='book_review_author_name' value='<?php echo $book_author; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 150px">Book Rating</td>
			<td>
				<select style="width: 100px" name="book_review_rating">
					<!-- Loop to generate all items in dropdown list -->
					<?php for ( $rating = 5; $rating >= 1; $rating -- ) { ?>
					<option value="<?php echo $rating; ?>" <?php echo selected( $rating, $book_rating ); ?>><?php echo $rating; ?> stars
					<?php } ?>
				</select>
			</td>
		</tr>
	</table>

<?php }

// Register function to be called when posts are saved
// The function will receive 2 arguments
add_action( 'save_post', 'ch4_br_add_book_review_fields', 10, 2 );

function ch4_br_add_book_review_fields( $post_id = false, $post = false ) {
	// Check post type for book reviews
	if ( 'book_reviews' == $post->post_type ) {
		// Store data in post meta table if present in post data
		if ( isset( $_POST['book_review_author_name'] ) ) {
			update_post_meta( $post_id, 'book_author', sanitize_text_field( $_POST['book_review_author_name'] ) );
		}
		
		if ( isset( $_POST['book_review_rating'] ) && !empty( $_POST['book_review_rating'] ) ) {
			update_post_meta( $post_id, 'book_rating', intval( $_POST['book_review_rating'] ) );
		}
	}
}