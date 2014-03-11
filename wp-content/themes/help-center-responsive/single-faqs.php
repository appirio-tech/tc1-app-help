<?php
global $catType, $paged, $ppr, $the_query;

$catName = "Design";
/*if ($catType == "topcoder-university"){
	$catName = "Topcoder University";
}else*/
if($catType == "development"){
	$catName = "Development";
}
elseif($catType == "data-science"){
	$catName = "Data Science";
}
elseif($catType == "copilots-reviewers"){
	$catName = "Copilots / Reviewers";
}
elseif($catType == "general-help"){
	$catName = "General Help";
}


?>

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
			$faqs = get_page_by_path ( 'faqs', OBJECT, 'page' );
			$parent_title = get_the_title ( $faqs->ID );
			if ($parent_title != the_title ( ' ', ' ', false )) {
				echo '<a href=' . get_permalink ( $faqs->ID ) . ' ' . 'title=' . get_the_title () . '><i></i>' . get_the_title () . '</a>';
			}
			// then go on to the current page link
			?>
				<span class="curr"><i></i><?php echo $catName; ?></span>
	</div>
				<?php $cls = format_css_class(get_the_title());?>
				<section class="subSection faqDetails faq<?php echo $catType;?>">
		<header>
			<h1><?php echo $catName;?></h1>
		</header>

<?php
$ppr = get_option ( 'faq_per_page' );
if (get_query_var ( 'show_all' ) == true) {
	$ppr = - 1;
}
$catID =  get_category_by_slug($catType);
$catID = $catID->cat_ID;
$args = array (
		'post_type' => 'faqs',
		'posts_per_page' => $ppr,
		'paged' => $paged,
		 cat => $catID
);
?>
		<!--table view-->
		<div id="tableView" class="viewTab">
			<div class="grid-1  ">
			<?php
			
			$the_query = new WP_Query ( $args );
			if ($the_query->have_posts ()) :
				while ( $the_query->have_posts () ) :
					$the_query->the_post ();
					?>
			
                       <article>
					<div class="content">
						<h3>
							<a href="javascript:;"><?php the_title();?></a>
						</h3>
						<p><?php the_content(); ?></p>
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