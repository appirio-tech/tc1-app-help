<?php
/**
 * Template Name: Sections Landing Page
 */
?>
<?php get_header(); ?>
<?php

global $ppr, $the_query;
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
				$guideLink = get_post_meta($pid, 'guideLink', true);
				?>
					
			<div class="grid-3-2 page<?php echo format_css_class( get_the_title() ); ?>">
				<!--breadcrumb-->
                 <?php the_breadcrumb(); ?>
				
				 <!--section banner-->
				<section class="sectionBanner">
					<div class="banner">
						<img alt="<?php the_title();?>" src="<?php echo $iurl;?>" />
					</div>
					<div class="latest">
					<?php
				
				$ltArgs = array (
						'post_type' => 'page',
						'post_parent' => $pid,
						'posts_per_page' => 2 
				);
				?>

						<?php $categories = get_the_category();  $parent_slug = $categories[0]->slug; ?>
						<table class="dataTable">
							<thead>
								<tr>
									<th>Get Started</th>
								</tr>
							</thead>
							<tbody>
								<?php 	
								//String Parsing
			 					$str = get_post_meta($pid, 'Getting Started Fields', true);
								list($post1, $post2) = split(';',$str);
								list($title1, $url1) = split('=',$post1);
								list($title2, $url2) = split('=',$post2);
								?>	
								<tr>
									<td><a href="<?php echo $url1;?>" class="<?php echo strtolower($postTitle);?>"><?php echo $title1;?></a></td>		
								</tr>
								<tr>
									<td><a href="<?php echo $url2;?>" class="<?php echo strtolower($postTitle);?>"><?php echo $title2;?></a></td>		
								</tr>
							</tbody>
						</table>
					</div>
				</section>
				<p>
				</p>

				<!--most popular-->
				<section class="subSection designPage">
					<header>
						<h1>Most Popular</h1>
						<aside class="rt">
							<span class="views"> <a class="gridView isActive" href="#gridView"></a> <a class="listView" href="#tableView"></a>
							</span>
						</aside>
					</header>
					<!--table view-->
					<?php
				$paged = get_query_var ( 'paged' ) ? absint ( get_query_var ( 'paged' ) ) : 1;
				if ($paged == 0) {
					$paged = 1;
				}
				$ppr = get_option ( 'ppr_section' );
				if (get_query_var ( 'show_all' ) == true) {
					$ppr = - 1;
				}
				$ltArgs = array (
						'post_type' => 'page',
						'post_parent' => $pid,
						'posts_per_page' => - 1 
				);
				?>
					
					<?php
				$idsIn = array ();
				$the_query = new WP_Query ( $ltArgs );
				if ($the_query->have_posts ()) {
					while ( $the_query->have_posts () ) {
						$the_query->the_post ();
						array_push ( $idsIn, $post->ID );
					}
				}
				wp_reset_query ();
				?>
					
					
					<div id="tableView" class="viewTab videoView hide">
						<div class="grid-3-2">
						<?php
				$ltArgs = array (
						'post_type' => 'page',
						'post_parent__in' => $idsIn,
						'posts_per_page' => $ppr,
						'paged' => $paged 
				);
				?>
						<?php
				$the_query = new WP_Query ( $ltArgs );
				if ($the_query->have_posts ()) :
					while ( $the_query->have_posts () ) :
						$the_query->the_post ();
						?>
							<article>
								<div class="content">
									<h3>
										<a href="<?php the_permalink();?>"><?php the_title();?></a>
									</h3>
									<p><?php custom_excerpt(11,'');echo " ...";?></p>
								</div>
								<div class="date"><?php the_time('M j, Y');?></div>
							</article>
							<?php endwhile; ?>							
							<?php endif; wp_reset_query();?>							
							<!--prev next-->
							<?php get_template_part('pagination','custom'); ?>
						</div>
						<div class="grid-3-1 rightCol">
						
						<?php echo do_shortcode(get_post_meta($pid,'Videos',true) ); ?>
							
							<!--prev next-->
							<div class="dataChanges onMobi">
								<div class="rt">
									<a class="prevLink" href="#0">
										<i></i> Prev
									</a>
									<a class="nextLink" href="#2">
										Next <i></i>
									</a>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<!--grid view-->
					<div id="gridView" class="viewTab ">
						<div class="grid-3-1">
								<?php
				$the_query = new WP_Query ( $ltArgs );
				if ($the_query->have_posts ()) :
					while ( $the_query->have_posts () ) :
						$the_query->the_post ();
						?>
							<article>
								<div class="content">
									<h3>
										<a href="<?php the_permalink();?>"><?php the_title();?></a>
									</h3>
									<p><?php custom_excerpt(11,'');echo " ...";?></p>
								</div>
								<div class="date"><?php the_time('M j,Y');?></div>
							</article>
							
							<?php endwhile; ?>							
							<?php endif; wp_reset_query();?>								
						</div>
						<div class="clear"></div>
						<!--prev next-->
						<?php get_template_part('pagination','custom'); ?>
					</div>
				</section>
				<!-- /.subSection -->
			</div>
			<!-- /.grid-3-2 -->
		</div>
		<!-- /.container -->


		<?php endif; wp_reset_query(); ?>

<?php get_footer(); ?>