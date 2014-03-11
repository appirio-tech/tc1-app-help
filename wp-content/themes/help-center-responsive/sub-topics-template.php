<?php
/**
 * Template Name: Sub-Topics Template
 */
?>
<?php get_header(); ?>
<?php

global $activity;
// $activity = get_activity_summary();

?>
<div class="content">
	<div id="main">
		<?php get_template_part('content','search'); ?>
		
		<div class="container grid grid-float helpContainer">
			<div class="grid-3-1">
                   <?php get_template_part('content','help-menu'); ?>                    
                </div>
			<!-- /.grid-3-1 -->
			<?php
			if (have_posts ()) :
				$pid = $post->ID;
				$postTitle = get_the_title ();
				$thumbId = get_post_thumbnail_id ( $pid );
				$iurl = wp_get_attachment_url ( $thumbId );
				?>
					
			<div class="grid-3-2">
				<!--breadcrumb-->
			<?php the_breadcrumb ();?>
				
				<section class="subSection">
					<header>
						<h1><?php the_title(); ?></h1>
					</header>
					<div class="subTypes">
           <?php echo wpautop($post->post_content);
           
           $res = get_post_meta($pid, 'Resources', true);
           ?></div>

        </section>
				<!-- /.subSection -->
				<section class="relatedSection">
					<article class="related">
						<h4>Related</h4>
				<ul class="relatedContentList">
				
                <?php echo get_post_meta($pid, 'Related', true)?>
							<?php
							// for use in the loop, list 4 post titles related to first tag on current post
							$tags = wp_get_post_tags ( $post->ID );
							#print_r($tags);
							if ($tags) {
								$first_tag = $tags [0]->term_id;
								$args = array (
										'tag__in' => array (
												$first_tag 
										),
										'post__not_in' => array (
												$post->ID 
										),
										'post_type' => array (
												'post',
												'page' 
										),
										'posts_per_page' => 4,
										'ignore_sticky_posts' => 1 
								);
								$related_query = new WP_Query ( $args );
								if ($related_query->have_posts ()) {
									while ( $related_query->have_posts () ) :
										$related_query->the_post ();
										
										$pid = $post->ID;
										$thumbId = get_post_thumbnail_id ( $pid );
										$iurl = wp_get_attachment_url ( $thumbId );
										?>
									<li><a class="contentLink" href="<?php the_permalink() ?>">
										
										<?php the_title(); ?>
									</a> </li>
									
							<?php
									endwhile
									;
								}
								wp_reset_query ();
							}
							?>
								</ul>
            </article>
					<article class="resource">
						<h4>Resources</h4>
                <?php echo $res; ?>
            </article>
				</section>
			</div>
			<!-- /.grid-3-2 -->
		</div>
		<!-- /.container -->


		<?php endif; wp_reset_query(); ?>

<?php get_footer(); ?>