
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
                   <?php get_template_part('content','help-submenu'); ?>                    
                </div>
			<!-- /.grid-3-1 -->

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
			
			?>
				<span class="curr"><i></i>Search Result</span>
				</div>
				<section class="subSection searchResult">
					<header>
						<h1>Search Result</h1>
					</header>
					<?php 
						$searchText = get_query_var ( 's' );
						$ppr = get_option ( 'ppr_search' );
						$nResults = $wp_the_query->post_count;
					?>
					<!--option buttons-->
					<div class="optionContainer">
						
						<a href="?s=<?php echo $searchText;?>" class="btn optionBtn check">All</a>
						<a href="?s=<?php echo $searchText;?>&type=community-members" class="btn optionBtn type-community-members">Community</a>
						<a href="?s=<?php echo $searchText;?>&type=design" class="btn optionBtn type-design">Design</a>
						<a href="?s=<?php echo $searchText;?>&type=development" class="btn optionBtn type-development">Development</a>
						<a href="?s=<?php echo $searchText;?>&type=data-algorithms" class="btn optionBtn type-data-algorithms">Algorithm</a>
						<a href="?s=<?php echo $searchText;?>&type=copilots-reviewers" class="btn optionBtn type-copilots-reviewers">Copilot</a>
						<a href="?s=<?php echo $searchText;?>&type=topcoder-university" class="btn optionBtn type-topcoder-university">TC university</a>
					</div>

				<script type="text/javascript">
					var cType = "<?php echo get_query_var('type');?>";
				</script>
					<?php					
					
					if (get_query_var ( 'show_all' ) == true) {
						$ppr = - 1;
					}
					
					wp_reset_query ();
					$paged = get_query_var ( 'paged' ) ? absint ( get_query_var ( 'paged' ) ) : 1;
					if($paged==0){
						$paged = 1;
					}
					$postType = '';
					if(get_query_var('type')!=null && get_query_var('type') !=""){
						$postType = get_query_var('type');												
					}
					$pageType =  get_page_by_path ( $postType, OBJECT, 'page' );
					$searchArgs = array (
							's' => $searchText,
							'posts_per_page' => $ppr,
							'paged' => $paged 
					);

					if($postType != null && $postType !=""){
						$catID =  get_category_by_slug($postType);
						$catID = $catID->cat_ID;
							$searchArgs = array (
									'cat'=> $catID,
									's' => $searchText,
									'posts_per_page' => $ppr,
									'paged' => $paged
							);
					}

					$the_query = new WP_Query ( $searchArgs );
						$st = ($paged-1)* $ppr+1;
						$end = $paged* $ppr;
						$nResults = $the_query->found_posts;
						if ($nResults < $ppr) {
							$end = $nResults;
						}
						if ($nResults <= 0) {
							$st = 0;
							$end = 0;
						}
						
					?>
					<!--table view-->
					<div id="tableView" class="viewTab">
						<div class="grid-1">
							<article class="searchMsg  alt">
								<div class="content">
									<p>
										Search result for "<strong><?php echo get_query_var('s');?></strong>" (<?php echo $st. " - " .$end?> of about <?php echo $nResults;?> results)
									</p>
								</div>
								<div class="like"></div>
							</article>
				
						<?php
						if ($the_query->have_posts ()) :
							while ( $the_query->have_posts () ) :
								$the_query->the_post ();
								?>
							<article>
								<div class="content">
									<h3>
										<a href="<?php the_permalink();?>"><?php the_title();?></a>
									</h3>

									<p><?php custom_excerpt(25,"...");?></p>
								</div>
								<div class="like"></div>
							</article>
							
							<?php endwhile; endif; ?>
							
							<!--prev next-->
							<?php get_template_part('pagination','custom'); ?>
							<?php wp_reset_query ();?>

						</div>
					</div>
				</section>
			</div>
			<!-- /.grid-3-2 -->
		</div>
		<!-- /.container -->


<?php get_footer(); ?>