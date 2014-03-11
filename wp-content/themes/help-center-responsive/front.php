<?php
/**
 * Template Name: Home
 */
?>
<?php get_header(); ?>
<?php

global $activity;
// $activity = get_activity_summary();

$faqs_nav = array (
		'menu' => 'Faqs Navigation',
		'menu_class' => '',
		'container' => '',
		'menu_class' => 'root',
		'items_wrap' => '%3$s',
		'walker' => new faqs_menu_walker () 
);
?>
<div class="content">
	<div id="main">
		<?php get_template_part('content','search'); ?>
		
		<div class="container grid grid-float helpContainer">
			<div class="grid-3-1">
                   <?php get_template_part('content','help-submenu'); ?>                    
                </div>
			<!-- /.grid-3-1 -->
			<?php
			if (have_posts ()) :
				$pid = $post->ID;
				$thumbId = get_post_thumbnail_id ( $pid );
				$iurl = wp_get_attachment_url ( $thumbId );
				?>
					
			<div class="grid-3-2">
				<!--breadcrumb-->
                 <?php the_breadcrumb(); ?>
				<!--faqs-->
				<div class="faqs">
					<!--banner-->
					<div class="banners">
						<div class="banner home active">
							<img src="<?php echo $iurl;?>" alt="Home" />
						</div>
					</div>
					<!--faq-->
					<div class="faq">
						<h3>
						<?php $faq = get_page_by_path ( 'faqs', OBJECT, 'page' );
						$faqID = $faq->ID;
						?>
							<a href="<?php echo get_permalink($faqID);?>">FAQs</a>
						</h3>

						<ul>
							<?php wp_nav_menu($faqs_nav); ?>							
						</ul>
					</div>
				</div>
				<!--topcoder university and common topics-->
				<div class="uniAndCommonTopics">
					<div class="grid-2-1">
						<table class="dataTable topics left">
							<thead>
								<tr>
									<th colspan="2">Getting Started</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								$posts = get_posts( $args );
								query_posts('category_name=get-starteds-cat&post_type=page&posts_per_page=3');
								while( have_posts() ): the_post();
							?>
							<tr>
								<td><a href="<?php the_permalink();?>"><?php the_title(); ?></a> <?php custom_excerpt(12,'');?></td>
								<td class="details"><a href="<?php the_permalink();?>">Details</a></td>
							</tr>
							<?php endwhile; ?>
							</tbody>
						</table>
						<a class="viewAll" href="<?php echo get_permalink($ctId);?>">View More</a>
					</div>
					<div class="grid-2-1">
			
			<?php
				$ctNav = wp_get_nav_menu_items ( "Help Navigation" );			
				$ctParent = get_page_by_path ( 'general-help', OBJECT, 'page' );
				$ctId = $ctParent->ID;
				$title = "";
				foreach ( ( array ) $ctNav as $key => $menu_item ) {
					if ($menu_item->title == "General Help") {
						//$title = $menu_item->post_excerpt;
						$title = $menu_item->title;
					}
				}
				$ctArgs = array (
						'post_type' => 'page',
						'post_parent' => $ctId,
						'posts_per_page' => 3
				);
				?>
						<table class="dataTable topics">
							<thead>
								<tr>
									<th colspan="2"><?php echo $title; ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
							$ctQuery = new WP_Query ( $ctArgs );
							$count = 0;
							if ($ctQuery->have_posts ()) :
								while ( $ctQuery->have_posts () ) :
									$ctQuery->the_post ();?>
								<tr>
									<td><a href="<?php the_permalink();?>"><?php the_title();?></a> <?php echo custom_excerpt(12,'');?></td>
									<td class="details"><a href="<?php the_permalink();?>">Details</a></td>
								</tr>
							<?php $count+=1; endwhile; ?>							
							<?php endif; wp_reset_query();?>								
							</tbody>
						</table>
						<a class="viewAll leftPadding" href="<?php echo get_permalink($ctId);?>">View More</a>
					</div>
				</div>
				<!--member tips-->
				<!-- <div class="memberTips">
					<h2>Member Tips</h2>
					<div class="grid-2-1">
						<section class="tips">
							<h3>Member Tips Title</h3>
							<div class="userDetails">
								<div class="photo fLeft">
									<img src="wp-content/themes/tcs-responsive/i/img-usr.png" alt="image" width="63" height="63" />
									<a class="coderTextOrange" href="#">membername</a>
								</div>
								<div class="detail">Lorem ipsum dolor sit amet consectetur elit sed do eiusmod tempor incididunt magna aliqua</div>
							</div>
							<div class="likes">
								<a href="javascript:" class="">Like(4)</a>
							</div>
						</section>
					</div>
					<div class="grid-2-1">
						<section class="tips fRight">
							<h3>Member Tips Title</h3>
							<div class="userDetails">
								<div class="photo fLeft">
									<img src="wp-content/themes/tcs-responsive/i/img-usr.png" alt="image" width="63" height="63" />
									<a class="coderTextOrange" href="#">membername</a>
								</div>
								<div class="detail">Lorem ipsum dolor sit amet consectetur elit sed do eiusmod tempor incididunt magna aliqua</div>
							</div>
							<div class="likes">
								<a href="javascript:" class="">Like(4)</a>
							</div>
						</section>
					</div>
					<div class="submitTipBtn hide">
						<a class="btn btnRegister" href="help-member-tip.html">Submit Tip Now</a>
					</div> -->
					<!--view all link-->
					<!-- <a class="viewAll hideMob" href="javascript:;">View All</a>
				</div> -->
			</div>
			<!-- /.grid-3-2 -->
		</div>
		<!-- /.container -->


		<?php endif; wp_reset_query(); ?>

<?php get_footer(); ?>