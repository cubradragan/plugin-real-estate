<?php
function post_ajax_submit() {
$the_post = wp_is_post_revision( $post_id );
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty($_POST['post_id']) && ! empty($_POST['post_title']) && isset($_POST['update_post_nonce'])  )
{
    $post_id   = $_POST['post_id'];
    $post_type = get_post_type($post_id);
    $capability = ( 'page' == $post_type ) ? 'edit_page' : 'edit_post';
    if ( current_user_can($capability, $post_id) && wp_verify_nonce( $_POST['update_post_nonce'], 'update_post_'. $post_id ) )
    {
        $post = array(
            'ID'             => esc_sql($post_id),
            'sub_title'      => esc_sql($_POST['sub_title']),
            'post_title'     => esc_sql($_POST['post_title']),

        );
        wp_update_post($post);
        $location = trim($_POST['location']);
        $type = trim($_POST['type']);
        if ( isset($_POST['sub_title']) ) update_post_meta($post_id, 'sub_title', esc_sql($_POST['sub_title']) );
        if ( isset($_POST['post_title']) ) update_post_meta($post_id, 'post_title', esc_sql($_POST['post_title']) );
        wp_set_object_terms($post_id, $location, 'location', true);
        wp_set_object_terms($post_id, $type, 'type', false);

    }
    else
    {
        wp_die("You can't do that");
    }
}
}

add_action('wp_ajax_SubmitReservation', 'post_ajax_submit');
add_action('wp_ajax_nopriv_SubmitReservation', 'post_ajax_submit');
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();
?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
      $realEstate = new WP_Query(array(
              'posts_per_page' => -1,
              'post_type' => 'real_estate',
            ));
			/* Start the Loop */
			while ( $realEstate->have_posts() ) :
				$realEstate->the_post();

				get_template_part( 'template-parts/content/content', 'single' );

				if ( is_singular( 'attachment' ) ) {
					// Parent post navigation.
					the_post_navigation(
						array(
							/* translators: %s: parent post link */
							'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentynineteen' ), '%title' ),
						)
					);
				} elseif ( is_singular( 'post' ) ) {
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next Post', 'twentynineteen' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Next post:', 'twentynineteen' ) . '</span> <br/>' .
								'<span class="post-title">%title</span>',
							'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous Post', 'twentynineteen' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Previous post:', 'twentynineteen' ) . '</span> <br/>' .
								'<span class="post-title">%title</span>',
						)
					);
				}

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}


			?>
      <form id="post" class="post-edit front-end-form" method="post" enctype="multipart/form-data">

    <input type="hidden" id="post_id" name="post_id" value="<?php the_ID(); ?>" />
    <?php wp_nonce_field( 'update_post_'. get_the_ID(), 'update_post_nonce' ); ?>

    <p><label for="post_title">Title</label>
    <input type="text" id="post_title" name="post_title" value="<?php echo $post->post_title; ?>" /></p>
    <p><label for="post_title">Subtitle</label>
    <?php $value = get_post_meta($post->ID, 'sub_title', true); ?>
    <input type="text" id="subtitle" name="sub_title" value="<?php  the_field('sub_title'); ?>" /></p>

    <p><label for="post_title">Location</label>
    <?php $location = get_terms( array(
    'taxonomy' => 'location',
    'hide_empty' => false,
  ) );
$locationName = $location[0]->name;
//var_dump($terms);
?>
    <input type="text" id="location" name="location" value="<?php echo $locationName; ?>" /></p>

    <p><label for="post_title">Type</label>
    <?php $type = get_terms( array(
    'taxonomy' => 'type',
    'hide_empty' => false,
) );
//var_dump($terms);
?>
    <input type="text" id="type" name="type" value="<?php echo $type[1]->name; ?>" /></p>

    <input type="submit" id="submit" value="Update" />

</form>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
endwhile; // End of the loop.?>
<script>
jQuery('document').ready(function($) {

$('.post-edit').on('submit', function(e) {
e.preventDefault();
var ourUpdatedPost = {
 'title' : $(this).find('#post_title').val(),
 //subtitle = $(this).find('#subtitle').val();
// location = $(this).find('#location').val();
 //type = $(this).find('#type').val();
}
//var post_id = $(this).find('#post_id').val();

//console.log(title);
// ajax codes for submission

$.ajax({
type: 'POST',
context: this,
url: "/wp-admin/admin-ajax.php",
data: ourUpdatedPost,

success: function(data) {

 console.log(response);


//$(".tr_" + post_id.toString()).find('td:nth-child(6) a').html(email);
$.magnificPopup.close();
}
});

});

});

</script>
<?php get_footer();
