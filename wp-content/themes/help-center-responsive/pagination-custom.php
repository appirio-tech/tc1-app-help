<?php global $paged, $ppr, $the_query;?>
<div class="dataChanges">
	<?php if($ppr != -1 && $ppr < $the_query->found_posts):?>
	<div class="lt">
	<?php if (get_query_var('s')==null):?>		
		<a class="viewAll" href="?show_all=ture">View All</a>
	<?php else:?>		
		<a class="viewAll" href="?s=<?php echo get_query_var('s');?>&show_all=ture">View All</a>
	<?php endif?>
	</div>
	<div class="rt">
	<?php if($paged > 1):?>
		<a class="prevLink" href="<?php echo get_previous_posts_page_link(); ?>">
			<i></i> Prev
		</a>
		<?php endif;?>
		<?php if($paged < $the_query->max_num_pages):?>
		<a class="nextLink" href="<?php  echo get_next_posts_page_link();?>">
			Next <i></i>
		</a>
		<?php endif;?>
	</div>								
	<?php endif;?>
</div>