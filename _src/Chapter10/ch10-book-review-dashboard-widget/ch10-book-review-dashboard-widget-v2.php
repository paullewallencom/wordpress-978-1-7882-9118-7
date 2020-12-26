<?php
/*
  Plugin Name: Chapter 10 - Book Review Dashboard Widget V2
  Plugin URI:
  Description: Companion to recipe 'Adding a custom widget to the network dashboard'
  Author: ylefebvre
  Version: 1.0
  Author URI: http://ylefebvre.ca/
 */

if ( is_multisite() ) {
    add_action( 'wp_network_dashboard_setup', 'ch10brdw_add_dashboard_widget' );
} else {
	add_action( 'wp_dashboard_setup', 'ch10brdw_add_dashboard_widget' );
}

function ch10brdw_add_dashboard_widget() {
	wp_add_dashboard_widget( 'book_reviews_dashboard_widget',
							 'Book Reviews',
							 'ch10brdw_dashboard_widget' );
}

function ch10brdw_dashboard_widget() {
	if ( is_multisite() ) {
        $sites_list = get_sites();
    } else {
        $sites_list = array( 'blog_id' => 1 );
    }
 
    foreach( $sites_list as $site ) {
        if ( is_multisite() ) {
            switch_to_blog( $site->blog_id );
        }
        $site_name = get_bloginfo( 'name' );
        echo '<div>' . $site_name . '</div>'; 
		$book_review_count = wp_count_posts( 'book_reviews' );
		if ( !empty( (array) $book_review_count ) ) {
		?>
		<a href="<?php echo add_query_arg( array( 'post_status' => 'publish',
												  'post_type' => 'book_reviews' ),
												  admin_url( 'edit.php' ) ); ?>">
			<strong><?php echo $book_review_count->publish; ?></strong> Published
		</a>
		<br />
		<a href="<?php echo add_query_arg( array( 'post_status' => 'draft',
												  'post_type' => 'book_reviews' ),
												  admin_url( 'edit.php' ) ); ?>">
			<strong><?php echo $book_review_count->draft; ?></strong> Draft
		</a>
	    <?php }
		}
	if ( is_multisite() ) {
		restore_current_blog();
	}	
}