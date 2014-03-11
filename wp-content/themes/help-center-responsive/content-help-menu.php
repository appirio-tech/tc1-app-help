<?php
$help_nav = array (
		'menu' => 'Help Navigation',
		'menu_class' => '',
		'container' => '',
		'menu_class' => 'root',
		'items_wrap' => '%3$s',
		'walker' => new help_menu_walker () 
);

$help_sectionMenu = array (
		'menu' => 'Help Navigation',
		'menu_class' => '',
		'container' => '',
		'menu_class' => 'root',
		'items_wrap' => '%3$s',
		'walker' => new help_sectionMenu_walker () 
);

$help_subnav = array (
		'menu' => 'Help Navigation',
		'menu_class' => '',
		'container' => '',
		'menu_class' => 'root',
		'items_wrap' => '%3$s',
		'walker' => new help_submenu_walker () 
);
?>
<!-- h-m -->
<nav class="leftMenu">
	<!--section with topics and subTopics-->
	<ul class="sectionMenu">
	<?php wp_nav_menu($help_sectionMenu); ?>
	</ul>
	<!-- /.sectionMenu -->
	<?php
	$post_id = $post->ID;
	$ancestors = get_post_ancestors ( $post_id );
	$root_id = (! empty ( $ancestors ) ? array_pop ( $ancestors ) : $post_id);
	$cls = get_the_title ( $root_id );
	
	$cls = strtolower ( $cls );
	$cls = str_replace ( " ", "", $cls );
	$cls = str_replace ( "/", "-", $cls );
	$attributes .= ' class="' . $cls . '"';
	?>
	<ul class="landingMenu section <?php echo $cls;?>">
		
	<?php wp_nav_menu($help_subnav); ?>
	<?php
	$args = array (
			'post_type' => 'page',
			'post_parent' => get_ID_by_slug ( 'general-help' ) 
	);
	
	$the_query = new WP_Query ( $args );
	$count = 0;
	if ($the_query->have_posts ()) :
		while ( $the_query->have_posts () ) :
			$the_query->the_post ();
			?>
	<li class="menu-item <?php if($count==0){ echo "splitted";}?>">
		<a href="<?php the_permalink();?>"><?php the_title();?></a>
	</li>
	<?php $count+=1; endwhile;  endif; wp_reset_query();?>	
	
	
	</ul>
	<!--design-->
	<ul class="section design onMobi">

		<li class="sectionName"><a href="help-section-design.html">Design</a></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Competition Types</a>
			<ul>
				<li class="subTopics last"><a href="help-sub-topics.html">Contest Types</a></li>
			</ul></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Tournaments</a>
			<ul>
				<li class="subTopics "><a href="help-sub-topics.html">Studio Cup</a></li>
				<li class="subTopics last"><a href="help-sub-topics.html">TCO</a></li>
			</ul></li>
		<li class="topics"><a href="help-topics.html">Submitting to a Contest</a></li>
		<li class="topics"><a href="help-topics.html">Winner Selection</a></li>
		<li class="topics"><a href="help-topics.html">Final Fixes</a></li>
		<li class="topics"><a href="help-topics.html">Screening</a></li>
		<li class="topics"><a href="help-topics.html">Copyright Policies</a></li>
		<li class="topics"><a href="help-topics.html">Tournaments</a></li>
		<li class="topics"><a href="help-topics.html">Font Policy</a></li>
		<li class="topics"><a href="help-topics.html">New Member Start Guide (Getting Started)</a></li>
		<li class="topics"><a href="help-topics.html">Member Profile and Account Settings</a></li>
		<li class="topics"><a href="help-topics.html">Achievement Badges</a></li>
		<li class="topics"><a href="help-topics.html">Competitor Bonuses</a></li>
		<li class="topics"><a href="help-topics.html">Member Tips</a></li>
		<li class="topics"><a href="help-topics.html">FAQ's</a></li>
	</ul>
	<!--development-->
	<ul class="section development onMobi">
		<li class="sectionName"><a href="javascript:">development</a></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Conceptualization</a>
			<ul>
				<li class="subTopics last"><a href="help-sub-topics.html">Sub-topic</a></li>
			</ul></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Specification </a>
			<ul>
				<li class="subTopics "><a href="help-sub-topics.html">Sub-topic</a></li>
				<li class="subTopics last"><a href="help-sub-topics.html">Sub-topic</a></li>
			</ul></li>
		<li class="topics"><a href="help-topics.html">Architecture </a></li>
		<li class="topics"><a href="help-topics.html">Component Design </a></li>
		<li class="topics"><a href="help-topics.html">Component Development </a></li>
		<li class="topics"><a href="help-topics.html">Assembly </a></li>
		<li class="topics"><a href="help-topics.html">Test Suites </a></li>
		<li class="topics"><a href="help-topics.html">Reporting </a></li>
		<li class="topics"><a href="help-topics.html">UI Prototype </a></li>
		<li class="topics"><a href="help-topics.html">RIA Build</a></li>
		<li class="topics"><a href="help-topics.html">Content Creation </a></li>
		<li class="topics"><a href="help-topics.html">Test Scenarios </a></li>
		<li class="topics"><a href="help-topics.html">Bug Hunt </a></li>
		<li class="topics"><a href="help-topics.html">Bug-Races </a></li>
		<li class="topics"><a href="help-topics.html">New Member Start Guide (Getting Started)</a></li>
		<li class="topics"><a href="help-topics.html">Member Profile and Account Settings</a></li>
		<li class="topics"><a href="help-topics.html">Achievement Badges</a></li>
		<li class="topics"><a href="help-topics.html">Competitor Bonuses</a></li>
		<li class="topics"><a href="help-topics.html">Reliability </a></li>
		<li class="topics"><a href="help-topics.html">Ratings </a></li>
		<li class="topics"><a href="help-topics.html">Member Tips</a></li>
		<li class="topics"><a href="help-topics.html">FAQ's</a></li>
	</ul>
	<!--university-->
	<ul class="section university onMobi">
		<li class="sectionName"><a href="javascript:">TopCoder University</a></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Getting Started </a>
			<ul>
				<li class="subTopics last"><a href="help-sub-topics.html">Sub Topic</a></li>
			</ul></li>
		<li class="topics"><a href="help-topics.html">Training </a></li>
		<li class="topics"><a href="help-topics.html">Platform Announcements </a></li>
		<li class="topics"><a href="help-topics.html">FAQ's </a></li>
	</ul>
	<!--coplilot-->
	<ul class="section copilot onMobi">
		<li class="sectionName"><a href="help-section-copilot.html">Copilots / Reviewers</a></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Overview</a>
			<ul>
				<li class="subTopics last"><a href="help-sub-topics.html">Special Member Roles</a></li>
			</ul></li>
		<li class="topics"><a href="help-topics.html">FAQ's</a></li>
		<li class="topics"><a href="help-topics.html">Member Tips</a></li>
	</ul>
	<!--coplilot-->
	<ul class="section algorithm onMobi">
		<li class="sectionName"><a href="javascript:">Data / Algorithms</a></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Overview</a>
			<ul>
				<li class="subTopics last"><a href="help-sub-topics.html">Special Member Roles</a></li>
			</ul></li>
		<li class="topics"><a href="help-topics.html">FAQ's</a></li>
		<li class="topics"><a href="help-topics.html">Member Tips</a></li>
	</ul>
</nav>
<!--left menu-->