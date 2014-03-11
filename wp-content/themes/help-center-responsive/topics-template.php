<?php
/**
 * Template Name: Topics Template
 */
?>
<?php get_header(); ?>
<?php

global $activity;
// $activity = get_activity_summary();
global  $ppr, $the_query;

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
			global $post;
			if (have_posts ()) :
				$pid = $post->ID;
				$postTitle = get_the_title ();
				$postCon = get_the_content ();
				$thumbId = get_post_thumbnail_id ( $pid );
				$iurl = wp_get_attachment_url ( $thumbId );
				?>
				<script type="text/javascript">var bannerURL = "<?php echo $iurl;?>";</script>	
			<div class="grid-3-2">
				<!--breadcrumb-->
				<div class='breadcrumb'>
			<?php
				// if there is a parent, display the link
				if (is_front_page ()) {
					echo '<a href="javascript:;" class="home curr">Help Center</a><a href="javascript:" class=""><i></i></a>';
				} elseif (! is_front_page ()) {
					echo '<a class="home" href="' . get_option ( 'home' ) . '">Help Center</a>';
				}
				$parent_title = get_the_title ( $post->post_parent );
				if ($parent_title != the_title ( ' ', ' ', false )) {
					echo '<a href=' . get_permalink ( $post->post_parent ) . ' ' . 'title=' . $parent_title . '><i></i>' . $parent_title . '</a>';
				}
				// then go on to the current page link
				?>
				<span class="curr"><i></i><?php the_title(); ?></span>
				</div>

				<?php echo $post->post_content;?>
				
		<?php				
				$paged = get_query_var ( 'paged' ) ? absint ( get_query_var ( 'paged' ) ) : 1;
				if($paged==0){
					$paged = 1;
				}
				$ppr = get_option ( 'ppr_topic' );
				if (get_query_var ( 'show_all' ) == true) {
					$ppr = - 1;
				}
				
				$childArgs = array (
						'post_type' => 'page',
						'post_parent' => $pid,
						'posts_per_page' => $ppr,
						'paged' => $paged 
				);
				
				?>
				<!--sub topic section-->
				<section class="subSection subTopics">
					<header>
						<h1>Sub Topics</h1>
						<aside class="rt">
							<span class="views"> <a class="gridView" href="#gridView"></a> <a class="listView isActive" href="#tableView"></a>
							</span>
						</aside>
					</header>
					<!--table view-->
					<div id="tableView" class="viewTab">
						<div class="grid-1">
						<?php
				$the_query = new WP_Query ( $childArgs );
				$count = 0;
				if ($the_query->have_posts ()) :
					while ( $the_query->have_posts () ) :
						$the_query->the_post ();
						?>
							<article>
								<div class="content">
									<h3>
										<a href="<?php the_permalink();?>"><?php the_title();?></a>
									</h3>

									<p><?php echo topic_excerpt(22,'...');?></p>
								</div>
								<!-- <div class="like">
									<a href="javascript:">46</a>
								</div> -->
							</article>
							<?php $count+=1; endwhile; ?>	
							<!--prev next-->
							<?php get_template_part('pagination','custom'); ?>
							<?php endif; wp_reset_query();?>	
						</div>
					</div>
					<!--grid view-->
					<div id="gridView" class="viewTab  hide">
						<div class="grid-3-1">
						<?php
				$the_query = new WP_Query ( $childArgs );
				$count = 0;
				if ($the_query->have_posts ()) :
					while ( $the_query->have_posts () ) :
						$the_query->the_post ();
						?>
							<article>
								<div class="content">
									<h3>
										<a href="<?php the_permalink();?>"><?php the_title();?></a>
									</h3>

									<p><?php echo topic_excerpt(15,'...');?></p>
								</div>
								<!-- <div class="like">
									<a href="javascript:">46</a>
								</div> -->
							</article>
						<?php $count+=1; endwhile; ?>							
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