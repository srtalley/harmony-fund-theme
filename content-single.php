<?php
/**
 * The template for displaying single posts.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_do_microdata( 'article' ); ?>>
	<div class="inside-article">
		<?php
		/**
		 * generate_before_content hook.
		 *
		 * @since 0.1
		 *
		 * @hooked generate_featured_page_header_inside_single - 10
		 */
		do_action( 'generate_before_content' );
		?>

		<header class="entry-header">
			<?php
			/**
			 * generate_before_entry_title hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_before_entry_title' );

			if ( generate_show_title() ) {
				the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );
			}

			/**
			 * generate_after_entry_title hook.
			 *
			 * @since 0.1
			 *
			 * @hooked generate_post_meta - 10
			 */
			do_action( 'generate_after_entry_title' );
			?>
		</header><!-- .entry-header -->

		<!-- Social Network Share Button on Top -->
		<div class="sns-share-top">
			<!-- Donate -->
			<a class="btn--donate" href="https://harmonyfund.org/donate/" title="Click to donate!">
			<i class="fas fa-heart"></i>&nbsp;&nbsp;Please Give</a>

			<!-- Twitter -->
			<a class="btn--twitter" href="http://twitter.com/share?url=<?php the_permalink();?>&text=<?php echo get_the_title(); ?>&via=harmonyfund&tw_p=tweetbutton&related=harmonyfund" target="_blank">
			<i class="fab fa-twitter"></i>&nbsp;&nbsp;Twitter</a>
			
			<!-- Facebook -->
			<a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&t=<?php echo get_the_title(); ?>" target="_blank" class="btn--facebook">
			<i class="fab fa-facebook-f"></i>&nbsp;&nbsp;Facebook</a>
		
			<!-- Copy URL -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
			<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.13/clipboard.min.js"></script>
			<a class="btn--copy">
			<span id="share_btn" data-clipboard-text="<?php echo get_permalink(); ?>"><i class="fas fa-link"></i>&nbsp;&nbsp;Copy URL</span></a>
			
		</div><!-- END sns-share-top -->

		<div class="sns-share-top-mobile">
			<!-- Donate -->
			<a class="btn--donate" href="https://harmonyfund.org/donate/" title="Click to donate!">
			<i class="fas fa-heart"></i>&nbsp;Donate</a>

			<!-- Twitter -->
			<a class="btn--twitter" href="http://twitter.com/share?url=<?php the_permalink();?>&text=<?php echo get_the_title(); ?>&via=harmonyfund&tw_p=tweetbutton&related=harmonyfund" target="_blank">
			<i class="fab fa-twitter"></i>&nbsp;Twitter</a>
			
			<!-- Facebook -->
			<a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&t=<?php echo get_the_title(); ?>" target="_blank" class="btn--facebook">
			<i class="fab fa-facebook-f"></i>&nbsp;Facebook</a>
			
		</div><!-- END sns-share-top -->

		<?php
		/**
		 * generate_after_entry_header hook.
		 *
		 * @since 0.1
		 *
		 * @hooked generate_post_image - 10
		 */
		do_action( 'generate_after_entry_header' );
		?>

		<div class="entry-content" itemprop="text">
			<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'generatepress' ),
				'after'  => '</div>',
			) );
			?>

		<!-- Social Network Share Button -->
			<div class="sns-share">
				<h5 id="share">❤️&nbsp;Share This Article!</h5>
				<!-- Donate -->
				<a class="btn--donate" href="https://harmonyfund.org/donate/" title="Click to donate!">
				<i class="fas fa-heart"></i>&nbsp;&nbsp;Please Give</a>
	
				<!-- Twitter -->
				<a class="btn--twitter" href="http://twitter.com/share?url=<?php the_permalink();?>&text=<?php echo get_the_title(); ?>&via=harmonyfund&tw_p=tweetbutton&related=harmonyfund" target="_blank">
				<i class="fab fa-twitter"></i>&nbsp;&nbsp;Twitter</a>
				
				<!-- Facebook -->
				<a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&t=<?php echo get_the_title(); ?>" target="_blank" class="btn--facebook">
				<i class="fab fa-facebook-f"></i>&nbsp;&nbsp;Facebook</a>
			
				<!-- Copy URL -->
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
				<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.13/clipboard.min.js"></script>
				<a class="btn--copy">
				<span id="share_btn" data-clipboard-text="<?php echo get_permalink(); ?>"><i class="fas fa-link"></i>&nbsp;&nbsp;Copy URL</span></a>
				<script>
				var clipboard = new Clipboard('#share_btn');
				    clipboard.on('success', function(e) {
				    //コピー成功時
				    $("#share_btn").addClass('is-copied').text('Copied!');
				});
				clipboard.on('error', function(e) {
				    //エラー時
				    $("#share_btn").addClass('is-copied').text('This does not work on your computer.');
				});
				</script>
			</div><!-- END sns-share-->

		</div><!-- .entry-content -->

		<?php
		/**
		 * generate_after_entry_content hook.
		 *
		 * @since 0.1
		 *
		 * @hooked generate_footer_meta - 10
		 */
		do_action( 'generate_after_entry_content' );

		/**
		 * generate_after_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'generate_after_content' );
		?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
