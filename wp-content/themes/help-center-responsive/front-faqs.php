<?php
/**
 * Template Name: FAQs Home
 */
?>
<?php get_header(); ?>
<?php

global $ppr, $the_query, $catType, $paged;
global $activity;
// $activity = get_activity_summary();

?>

<div class="content">
	<div id="main">
		<?php
		
		get_template_part ( 'content', 'search' );
		$catType = get_query_var ( 'category' );
		?>
		
		
		<div class="container grid grid-float helpContainer">
			<div class="grid-3-1">
                   <?php get_template_part('content','help-submenu'); ?>                    
                </div>
			<!-- /.grid-3-1 -->
		<?php
		
		if ($catType != "") :
			// faqs sub page content
			?>		
		
		
		 <?php get_template_part('single','faqs'); ?>
		
		
		<?php else:?>
			
			<?php
			// faq front page content
			$paged = get_query_var ( 'paged' ) ? absint ( get_query_var ( 'paged' ) ) : 1;
			if ($paged == 0) {
				$paged = 1;
			}
			
			if (have_posts ()) :
				$pid = $post->ID;
				$thumbId = get_post_thumbnail_id ( $pid );
				$iurl = wp_get_attachment_url ( $thumbId );
				?>
					
			<div class="grid-3-2">
				<!--breadcrumb-->
                 <?php the_breadcrumb(); ?>
				<section class="subSection faqsSection">
					<header>
						<h1><?php the_title();?></h1>
					</header>

					<div class="faqList">
						<div class="grid-3-1">
							<a href="<?php echo get_permalink(get_ID_by_slug('faqs'));?>general-help/" class="generalhelp">General Help</a>
						</div>
						<div class="grid-3-1 centerCol">
							<a href="<?php echo get_permalink(get_ID_by_slug('faqs'));?>design" class="design">Design</a>
						</div>
						<div class="grid-3-1 rightCol">
							<a href="<?php echo get_permalink(get_ID_by_slug('faqs'));?>development" class="development">Development</a>
						</div>
						<div class="grid-3-1">
							<a href="<?php echo get_permalink(get_ID_by_slug('faqs'));?>data-science" class="data-science">Data Science</a>
						</div>
						<div class="grid-3-1 centerCol">
							<a href="<?php echo get_permalink(get_ID_by_slug('faqs'));?>copilots-reviewers" class="copilot">Copilots / Reviewers</a>
						</div>
						<!-- <div class="grid-3-1 rightCol">
							<a href="<?php echo get_permalink(get_ID_by_slug('faqs'));?>topcoder-university" class="university">TopCoder University</a>							
						</div> -->
					</div>
					<!--/.faqList-->
					<!--table view-->
					<div id="tableView" class="viewTab">
						<div class="grid-1">
                    <?php
				$ppr = get_option ( 'ppr_front_faq' );
				if (get_query_var ( 'show_all' ) == true) {
					$ppr = - 1;
				}
				$args = array (
						'post_type' => 'faqs',
						'posts_per_page' => $ppr,
						'paged' => $paged 
				);
				?>
					
                        <article class="searchMsg alt">
								<div class="content">
									<p>Recent Questions</p>
								</div>
								<div class="like"></div>
							</article>
				<?php
				$the_query = new WP_Query ( $args );
				if ($the_query->have_posts ()) :
					while ( $the_query->have_posts () ) :
						$the_query->the_post ();
						?>
							<article>
								<div class="content">
									<h3>
										<a href="javascript:;"><?php the_title(); ?></a>
									</h3>
									<p><?php  custom_excerpt(27,'...');?></p>
								</div>
								<div class="like"></div>
							</article>
                        <?php endwhile; endif; ?>	
                        <!--prev next-->
						<?php get_template_part('pagination','custom'); ?>
					<?php wp_reset_query(); ?>
							</div>
					</div>
				</section>
			</div>
			<!-- /.grid-3-2 -->
			
		<?php endif;?>


<?php endif; wp_reset_query(); ?>
		</div>
		<!-- /.container -->

<?php get_footer(); ?>