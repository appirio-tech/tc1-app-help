<?php
$help_nav = array (
		'menu' => 'Help Navigation',
		'menu_class' => '',
		'container' => '',
		'menu_class' => 'root',
		'items_wrap' => '%3$s',
		'walker' => new help_menu_walker () 
);

?>
<!-- hm -->
<nav class="leftMenu">
	<ul>
		<?php wp_nav_menu($help_nav); ?>
	</ul>
	<!--section with topics and subTopics-->
	<!--design-->
	<?php $cls = get_the_title();
	$cls = strtolower($cls);
	$cls = str_replace(" ","",$cls);
	$cls = str_replace("/","-",$cls);
	$attributes .= ' class="' . $cls . '"';
	?>
	<ul class="section <?php echo $cls;?> onMobi">

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
	<ul class="section data-science onMobi">
		<li class="sectionName"><a href="javascript:">Data Science</a></li>
		<li class="topics hasSubTopics"><a href="help-topics.html">Overview</a>
			<ul>
				<li class="subTopics last"><a href="help-sub-topics.html">Special Member Roles</a></li>
			</ul></li>
		<li class="topics"><a href="help-topics.html">FAQ's</a></li>
		<li class="topics"><a href="help-topics.html">Member Tips</a></li>
	</ul>
</nav>
<!--left menu-->