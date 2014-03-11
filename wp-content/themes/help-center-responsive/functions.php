<?php 
// add featured image
add_theme_support ( 'post-thumbnails' );
if (function_exists ( 'add_theme_support' )) {
	add_theme_support ( 'post-thumbnails' );
	set_post_thumbnail_size ( 55, 55 ); // default Post Thumbnail dimensions
}

// enables tags on pages
function tags_support_all() {
	register_taxonomy_for_object_type ( 'post_tag', 'page' );
}
add_action ( 'init', 'tags_support_all' );


$includes_path = TEMPLATEPATH . '/lib';

$currUrl = curPageURL ();
if (strpos ( $currUrl, ACTIVE_CONTESTS_PERMALINK ) !== false || strpos ( $currUrl, PAST_CONTESTS_PERMALINK ) !== false || strpos ( $currUrl, REVIEW_OPPORTUNITIES_PERMALINK ) !== false) {
	if (strpos ( $currUrl, "%20" ) !== false) {
		$redirectUrl = str_replace ( "%20", "_", $currUrl );
		$redirectString = "Location: $redirectUrl";
		print_r ( $redirectString );
		header ( $redirectString );
		exit ();
	}
}
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER ["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER ["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
	}
	return $pageURL;
}

/**
 * wrap content to $len length content, and add '...' to end of wrapped conent
 */
function wrap_content_strip_html($content, $len, $strip_html = false, $sp = '\n\r', $ending = '...') {
	if ($strip_html) {
		$content = strip_tags($content);
		$content = strip_shortcodes($content);
	}
	$c_title_wrapped = wordwrap($content, $len, $sp);
	$w_title = explode($sp, $c_title_wrapped);
    if (strlen($content) <= $len) { $ending = ''; }
	return $w_title[0].$ending;
}

/**
 * add filter for query_vars
 */
function tcapi_query_vars($query_vars) {
	$query_vars [] = 'category';
	$query_vars [] = 'contestID';
	$query_vars [] = 'page';
	$query_vars [] = 'pages';
	$query_vars [] = 'post_per_page';
	$query_vars [] = 'handle';
	$query_vars [] = 'show_all';
	$query_vars [] = 'type';
	return $query_vars;
}
add_filter ( 'query_vars', 'tcapi_query_vars' );

add_rewrite_rule ( '^faqs/([^/]*)/?$', 'index.php?pagename=faqs&category=$matches[1]', 'top' );
add_rewrite_rule ( '^faqs/([^/]*)/page/([^/]*)/?$', 'index.php?pagename=faqs&category=$matches[1]&paged=$matches[2]', 'top' );
add_rewrite_rule ( '^faqs/page/([^/]*)/?$', 'index.php?pagename=faqs&paged=$matches[1]', 'top' );

flush_rewrite_rules ();

/*
 * -----------------------AJAX handler ----------------------
 * */
add_action( 'wp_ajax_searchHints', 'search_bar_hints' );
add_action( 'wp_ajax_nopriv_searchHints', 'search_bar_hints' );

function search_bar_hints() {
	// Handle request then generate response using WP_Ajax_Response
	$url = get_bloginfo('stylesheet_directory').'/data/searchList.json';
	$response = wp_remote_get($url);
	echo $response['body'];
	die();
}



/*
 * commonly used functions -----------------------------------
 */
/*function get_root_parent_name( $page_id ) {
		global $wpdb;
		$parent = $wpdb->get_var( "SELECT post_parent FROM $wpdb->posts WHERE ID = '$page_id'" );
		$page_name = $wpdb->get_var( "SELECT post_name FROM $wpdb->posts WHERE ID = '$page_id'" );
		//return $parent;
		if( $parent == 0 ) return $page_name;
		else return get_root_parent_name( $parent );
	}*/
function wpbeginner_numeric_posts_nav() {

	if( is_singular() )
		return;

	global $wp_query;

	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="navigation"><ul>' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link() );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>…</li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", get_next_posts_link() );

	echo '</ul></div>' . "\n";

}

/* excerpt */
function new_excerpt_more($more) {
	return '...<br/>' . '<a href="' . get_permalink ( get_the_ID () ) . '" class="more">Read More</a>';
}
add_filter ( 'excerpt_more', 'new_excerpt_more' );
function custom_excerpt_length($length) {
	return 27;
}
add_filter ( 'excerpt_length', 'custom_excerpt_length', 999 );
function topic_excerpt($new_length = 20, $new_more = '...') {
	add_filter ( 'excerpt_length', function () use($new_length) {
		return $new_length;
	}, 999 );
	add_filter ( 'excerpt_more', function () use($new_more) {
		return $new_more.'<br/>' . '<a href="' . get_permalink ( get_the_ID () ) . '" class="more">Read More</a>';
	} );
	$output = get_the_excerpt ();
	$output = apply_filters ( 'wptexturize', $output );
	$output = apply_filters ( 'convert_chars', $output );
	$output = $output;
	echo $output;
}

function custom_excerpt($new_length = 20, $new_more = '...') {
	add_filter ( 'excerpt_length', function () use($new_length) {
		return $new_length;
	}, 999 );
	add_filter ( 'excerpt_more', function () use($new_more) {
		return $new_more;
	} );
	$output = get_the_excerpt ();
	$output = apply_filters ( 'wptexturize', $output );
	$output = apply_filters ( 'convert_chars', $output );
	$output = $output;
	echo $output;
}
function custom_content($new_length = 55) {
	$output = get_the_content ();
	$output = apply_filters ( 'wptexturize', $output );
	$output = substr ( $output, 0, $new_length ) . '...';
	return $output;
}
function format_css_class($cssClass) {
	$cls = strtolower ( $cssClass );
	$cls = str_replace ( " ", "-", $cls );
	$cls = str_replace ( "/", "-", $cls );
	$cls = str_replace ( "---", "-", $cls );
	return $cls;
}

/* get page id by slug */
function get_ID_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}

/* singnup function from given theme */
function get_cookie() {
	global $_COOKIE;
	// $_COOKIE['main_user_id_1'] = '22760600|2c3a1c1487520d9aaf15917189d5864';
	$hid = explode ( "|", $_COOKIE ['main_tcsso_1'] );
	$handleName = $_COOKIE ['handleName'];
	// print_r($hid);
	$hname = explode ( "|", $_COOKIE ['direct_sso_user_id_1'] );
	$meta = new stdclass ();
	$meta->handle_id = $hid [0];
	$meta->handle_name = $handleName;
	return $meta;
}

/* breadcrumb */
function the_breadcrumb() {
	echo '<div class="breadcrumb">';
	if (is_front_page ()) {
		echo '<a href="javascript:;" class="home curr">Help Center</a><a href="javascript:" class=""><i></i></a>';
	} elseif (! is_front_page ()) {
		echo '<a class="home" href="' . get_option ( 'home' ) . '">Help Center</a>';
		
		$ancestors = get_post_ancestors(get_the_id());
		$ancestors = array_reverse($ancestors);
		foreach ( $ancestors as $key => $item ) {
			$title = get_the_title($item);
			echo '<a href="'.get_permalink($item).'"><i></i>' . $title . '</a>';
			
		}
		echo '<span class="curr"><i></i>' . get_the_title() . '</span>';
	} elseif (is_tag ()) {
		single_tag_title ();
	} elseif (is_search ()) {
		echo "<a href='#'>Search Results</a>";
	}
	echo '</div>';
}

// add menu support
add_theme_support ( 'menus' );

remove_filter ( 'the_content', 'wpautop' );

/* Faqs Module Post Type */
add_action ( 'init', 'faq_register' );
function faq_register() {
	$strPostName = 'FAQ';
	
	$labels = array (
			'name' => _x ( $strPostName . 's', 'post type general name' ),
			'singular_name' => _x ( $strPostName, 'post type singular name' ),
			'add_new' => _x ( 'Add New', $strPostName . ' Post' ),
			'add_new_item' => __ ( 'Add New ' . $strPostName . ' Post' ),
			'edit_item' => __ ( 'Edit ' . $strPostName . ' Post' ),
			'new_item' => __ ( 'New ' . $strPostName . ' Post' ),
			'view_item' => __ ( 'View ' . $strPostName . ' Post' ),
			'search_items' => __ ( 'Search ' . $strPostName ),
			'not_found' => __ ( 'Nothing found' ),
			'not_found_in_trash' => __ ( 'Nothing found in Trash' ),
			'parent_item_colon' => '' 
	);
	
	$args = array (
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'exclude_from_search' => false,
			'show_in_nav_menus' => true,
			'taxonomies' => array (
					'category'
			),
			'supports' => array (
					'title',
					'editor',
					'thumbnail',
					'page-attributes',
					'excerpt'
			) 
	);
	
	register_post_type ( 'faqs', $args );
	flush_rewrite_rules ( false );
}
$supports =  array (
					
					'excerpt'
			) ;

add_action('init', 'my_custom_init');
function my_custom_init() {
	add_post_type_support( 'page', 'excerpt' );
	add_post_type_support( 'post', 'excerpt' );
	register_taxonomy_for_object_type('category', 'page');
}


/*
 * shortcodes
 */
function get_video($atts, $vid = "") {
	$vid = clean_pre ( $vid );
	$title = isset ( $atts ['title'] ) ? $atts ['link'] : ' ';
	$video = '<article>
	<div class="content">
		<h3>' . $atts ['title'] . '</h3>
		<a href="http://www.youtube.com/embed/' . $vid . '" class="ytVid">
			<img width="179" height="99" src="http://img.youtube.com/vi/' . $vid . '/0.jpg" alt="youtube">
		</a>
	</div>
	<div class="date">' . get_the_time ( 'M j,Y' ) . '</div>
</article>';
	return $video;
}

add_shortcode ( 'video', 'get_video' );
function get_faq_que($atts, $que = "") {
	$que = clean_pre ( $que );
	$link = isset ( $atts ['link'] ) ? $atts ['link'] : 'javascript:;';
	$html = '<article>
	<div class="content">
		<h3><a href="' . $link . '">' . $que . '</a></h3>';
	return $html;
}

add_shortcode ( 'que', 'get_faq_que' );
function get_faq_ans($atts, $ans = "") {
	$que = clean_pre ( $ans );
	$html = ' <p>' . $ans . '</p>
		</div>
		<div class="like"></div>
	</article>';
	return $html;
}

add_shortcode ( 'ans', 'get_faq_ans' );

/**
 * Start of Theme Options Support
 */
function themeoptions_admin_menu() {
	add_theme_page ( "Theme Options", "Theme Options", 'edit_themes', basename ( __FILE__ ), 'themeoptions_page' );
}
add_action ( 'admin_menu', 'themeoptions_admin_menu' );
function themeoptions_page() {
	if ($_POST ['update_themeoptions'] == 'true') {
		themeoptions_update ();
	} // check options update
	  // here's the main function that will generate our options page
	?>

<div class="wrap">
	<div id="icon-themes" class="icon32">
		<br />
	</div>
	<h2>TCS Theme Options</h2>

	<form method="POST" action="" enctype="multipart/form-data">
		<input type="hidden" name="update_themeoptions" value="true" />
		<h3>TopCoder API settings</h3>
		<table width="100%">
			<tr>
				<?php $field = 'ppr_section'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Post per page for <b>Section</b> Landing page/template:
				</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'ppr_topic'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Post per page for <b>Topic</b> page/template:
				</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'faq_per_page'; ?>
				<td width="150"><label for="<?php echo $field; ?>">FAQ per page: </label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'ppr_front_faq'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Post per page for Faq front:
				</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'ppr_search'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Searches per page: </label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />
		<h3>Social Media Links</h3>
		<table width="100%">
			<tr>
				<?php $field = 'facebookURL'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Facebook URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twitterURL'; ?>
				<td><label for="<?php echo $field; ?>">Twitter URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'linkedInURL'; ?>
				<td><label for="<?php echo $field; ?>">LinkedIn URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'gPlusURL'; ?>
				<td><label for="<?php echo $field; ?>">Google Plus URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />
		<h3>Twitter OAuth Tokens</h3>
		<table width="100%">
			<tr>
				<?php $field = 'twConsumerKey'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Consumer key:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twConsumerSecret'; ?>
				<td><label for="<?php echo $field; ?>">Consumer secret:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twAccessToken'; ?>
				<td><label for="<?php echo $field; ?>">Access token:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twAccessTokenSecret'; ?>
				<td><label for="<?php echo $field; ?>">Access token secret:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />

		<p>
			<input type="submit" name="submit" value="Update Options" class="button button-primary" />
		</p>
	</form>

</div>
<?php
}

// Set default options
if (is_admin () && isset ( $_GET ['activated'] ) && $pagenow == 'themes.php') {

	// Other Options
	update_option ( 'forumPostPerPage', '3' );	
	
	// Social Media
	update_option ( 'facebookURL', 'http://www.facebook.com/topcoderinc' );
	update_option ( 'twitterURL', 'http://www.twitter.com/topcoder' );
	update_option ( 'linkedInURL', 'http://www.youtube.com/topcoderinc' );
	update_option ( 'gPlusURL', 'https://plus.google.com/u/0/b/104268008777050019973/104268008777050019973/posts' );
}

// Update options function
function themeoptions_update() {
	// Other Options
	update_option ( 'api_user_key', $_POST ['api_user_key'] );
	update_option ( 'case_studies_per_page', $_POST ['case_studies_per_page'] );
	update_option ( 'forumPostPerPage', $_POST ['forumPostPerPage'] );
	
	// blog 
	update_option ( 'blog_page_title', $_POST ['blog_page_title'] );

	// Social Media
	update_option ( 'facebookURL', $_POST ['facebookURL'] );
	update_option ( 'twitterURL', $_POST ['twitterURL'] );
	update_option ( 'linkedInURL', $_POST ['linkedInURL'] );
	update_option ( 'gPlusURL', $_POST ['gPlusURL'] );
	
	// Twitter OAuth Tokens
	update_option ( 'twConsumerKey', $_POST ['twConsumerKey'] );
	update_option ( 'twConsumerSecret', $_POST ['twConsumerSecret'] );
	update_option ( 'twAccessToken', $_POST ['twAccessToken'] );
	update_option ( 'twAccessTokenSecret', $_POST ['twAccessTokenSecret'] );
}
// END OF THEME OPTIONS SUPPORT

if (function_exists ( 'register_sidebar' )) {
	
	/*
	 * Sidebar community
	 */
	register_sidebar ( array (
			'name' => 'Sidebar Community',
			'id' => 'community_sidebar',
			'description' => 'Sidebar widget on community page',
			'before_widget' => '',
			'after_widget' => '' 
	) );
	
	register_sidebar ( array (
			'name' => 'BottomBar Community',
			'id' => 'community_bottombar',
			'description' => 'Bottom bar widget on community page',
			'before_widget' => '',
			'after_widget' => '' 
	) );
	
	// overview template sidebar
	register_sidebar ( array (
			'name' => 'Case studies sidebar',
			'id' => 'case_studies_sidebar',
			'description' => 'Sidebar widget on Case studies single page',
			'before_widget' => '',
			'after_widget' => '' 
	) );
}

// header menu walker
class nav_menu_walker extends Walker_Nav_Menu {
	
	// add classes to ul sub-menus
	function start_lvl(&$output, $depth) {
		// depth dependent classes
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array (
				'child' 
		);
		$class_names = implode ( ' ', $classes );
		
		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		                                                           
		// passed classes
		$classes = empty ( $item->classes ) ? array () : ( array ) $item->classes;
		$class_names = esc_attr ( implode ( ' ', apply_filters ( 'nav_menu_css_class', array_filter ( $classes ), $item ) ) );
		
		// build html
		$output .= $indent . '<li id="nav-menu-item-' . $item->ID . '">';
		
		// link attributes
		$attributes = ! empty ( $item->attr_title ) ? ' title="' . esc_attr ( $item->attr_title ) . '"' : '';
		$attributes .= ! empty ( $item->target ) ? ' target="' . esc_attr ( $item->target ) . '"' : '';
		$attributes .= ! empty ( $item->xfn ) ? ' rel="' . esc_attr ( $item->xfn ) . '"' : '';
		$attributes .= ! empty ( $item->url ) ? ' href="' . esc_attr ( $item->url ) . '"' : '';
		$attributes .= ' class="' . (! empty ( $item->post_name ) ? esc_attr ( $item->post_name ) : '') . '"';
		
		$item_output = sprintf ( '%1$s<a%2$s><i></i>%3$s%4$s%5$s</a>%6$s', $args->before, $attributes, $args->link_before, apply_filters ( 'the_title', $item->title, $item->ID ), $args->link_after, $args->after );
		
		// build html
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// footer menu walker
class footer_menu_walker extends Walker_Nav_Menu {
	
	// add classes to ul sub-menus
	function start_lvl(&$output, $depth) {
		// depth dependent classes
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array (
				'child' 
		);
		$class_names = implode ( ' ', $classes );
		
		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		                                                           
		// passed classes
		$classes = empty ( $item->classes ) ? array () : ( array ) $item->classes;
		$class_names = esc_attr ( implode ( ' ', apply_filters ( 'nav_menu_css_class', array_filter ( $classes ), $item ) ) );
		
		// build html
		$deptClass = "";
		if ($depth == 0) {
			$deptClass = "rootNode";
		}
		$output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" class="' . $deptClass . '">';
		
		// link attributes
		$attributes = ! empty ( $item->attr_title ) ? ' title="' . esc_attr ( $item->attr_title ) . '"' : '';
		$attributes .= ! empty ( $item->target ) ? ' target="' . esc_attr ( $item->target ) . '"' : '';
		$attributes .= ! empty ( $item->xfn ) ? ' rel="' . esc_attr ( $item->xfn ) . '"' : '';
		$attributes .= ! empty ( $item->url ) ? ' href="' . esc_attr ( $item->url ) . '"' : '';
		$attributes .= ' class="' . (! empty ( $item->post_name ) ? esc_attr ( $item->post_name ) : '') . '"';
		
		$item_output = sprintf ( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s', $args->before, $attributes, $args->link_before, apply_filters ( 'the_title', $item->title, $item->ID ), $args->link_after, $args->after );
		
		// build html
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// help menu walker
class help_menu_walker extends Walker_Nav_Menu {
	
	// add classes to ul sub-menus
	function start_lvl(&$output, $depth) {
		// depth dependent classes
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array (
				'child hide' 
		);
		$class_names = implode ( ' ', $classes );
		
		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		                                                           
		// passed classes
		$classes = empty ( $item->classes ) ? array () : ( array ) $item->classes;
		$class_names = esc_attr ( implode ( ' ', apply_filters ( 'nav_menu_css_class', array_filter ( $classes ), $item ) ) );
		
		// build html
		$output .= $indent . '<li>';
		
		// link attributes
		$attributes = ! empty ( $item->attr_title ) ? ' title="' . esc_attr ( $item->attr_title ) . '"' : '';
		$attributes .= ! empty ( $item->target ) ? ' target="' . esc_attr ( $item->target ) . '"' : '';
		$attributes .= ! empty ( $item->xfn ) ? ' rel="' . esc_attr ( $item->xfn ) . '"' : '';
		$attributes .= ! empty ( $item->url ) ? ' href="' . esc_attr ( $item->url ) . '"' : '';
		$cls = (! empty ( $item->title ) ? esc_attr ( $item->title ) : '');
		$cls = strtolower ( $cls );
		$cls = str_replace ( " ", "", $cls );
		$cls = str_replace ( "/", "-", $cls );
		$attributes .= ' class="' . $cls . '"';
		
		$item_output = sprintf ( '%1$s<a%2$s><i></i>%3$s%4$s%5$s</a>%6$s', $args->before, $attributes, $args->link_before, apply_filters ( 'the_title', $item->title, $item->ID ), $args->link_after, $args->after );
		
		// build html
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// help sub-menu walker
class help_sectionMenu_walker extends Walker_Nav_Menu {
	
	// add classes to ul sub-menus
	function start_lvl(&$output, $depth) {
		// depth dependent classes
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		$display_depth = (0); // because it counts the first submenu as 0
		$classes = array (
				'child hide' 
		);
		$class_names = implode ( ' ', $classes );
		
		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		                                                           
		// passed classes
		$classes = empty ( $item->classes ) ? array () : ( array ) $item->classes;
		$class_names = esc_attr ( implode ( ' ', apply_filters ( 'nav_menu_css_class', array_filter ( $classes ), $item ) ) );
		
		// build html
		$liClass = "";
		
		$output .= $indent . '<li class="' . $class_names . '">';
		
		// link attributes
		$attributes = ! empty ( $item->attr_title ) ? ' title="' . esc_attr ( $item->attr_title ) . '"' : '';
		$attributes .= ! empty ( $item->target ) ? ' target="' . esc_attr ( $item->target ) . '"' : '';
		$attributes .= ! empty ( $item->xfn ) ? ' rel="' . esc_attr ( $item->xfn ) . '"' : '';
		$attributes .= ! empty ( $item->url ) ? ' href="' . esc_attr ( $item->url ) . '"' : '';
		$cls = (! empty ( $item->title ) ? esc_attr ( $item->title ) : '');
		$cls = strtolower ( $cls );
		$cls = str_replace ( " ", "", $cls );
		$cls = str_replace ( "/", "-", $cls );
		$attributes .= ' class="' . $cls . '"';
		
		$item_output = sprintf ( '%1$s<a%2$s><i></i></a>%6$s', $args->before, $attributes, $args->link_before, apply_filters ( 'the_title', $item->title, $item->ID ), $args->link_after, $args->after );
		
		// build html
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// help sub-menu walker
class help_submenu_walker extends Walker_Nav_Menu {
	
	// add classes to ul sub-menus
	function start_lvl(&$output, $depth) {
		// depth dependent classes
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		$display_depth = ($depth); // because it counts the first submenu as 0
		$classes = array (
				'child' 
		);
		$class_names = implode ( ' ', $classes );
		
		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		                                                           
		// passed classes
		$classes = empty ( $item->classes ) ? array () : ( array ) $item->classes;
		$class_names = esc_attr ( implode ( ' ', apply_filters ( 'nav_menu_css_class', array_filter ( $classes ), $item ) ) );
		
		// build html
		$class_names .= " general-help-depth".$depth;
		$output .= $indent . '<li class="' . $class_names . '">';
		// link attributes
		$attributes = ! empty ( $item->attr_title ) ? ' title="' . esc_attr ( $item->attr_title ) . '"' : '';
		$attributes .= ! empty ( $item->target ) ? ' target="' . esc_attr ( $item->target ) . '"' : '';
		$attributes .= ! empty ( $item->xfn ) ? ' rel="' . esc_attr ( $item->xfn ) . '"' : '';
		$attributes .= ! empty ( $item->url ) ? ' href="' . esc_attr ( $item->url ) . '"' : '';
		$cls = (! empty ( $item->title ) ? esc_attr ( $item->title ) : '');
		$cls = strtolower ( $cls );
		$cls = str_replace ( " ", "", $cls );
		$cls = str_replace ( "/", "-", $cls );
		if($cls == 'generalhelp') $cls = 'communitymembers';
		$attributes .= ' class="' . $cls . '"';

		$item_output = sprintf ( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s', $args->before, $attributes, $args->link_before, apply_filters ( 'the_title', $item->title, $item->ID ), $args->link_after, $args->after );
		
		// build html
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	
}

// help menu walker
class faqs_menu_walker extends Walker_Nav_Menu {
	
	// add classes to ul sub-menus
	function start_lvl(&$output, $depth) {
		// depth dependent classes
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array (
				'child hide' 
		);
		$class_names = implode ( ' ', $classes );
		
		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	
	// add main/sub classes to li's and links
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ($depth > 0 ? str_repeat ( "\t", $depth ) : ''); // code indent
		                                                           
		// passed classes
		$classes = empty ( $item->classes ) ? array () : ( array ) $item->classes;
		$class_names = esc_attr ( implode ( ' ', apply_filters ( 'nav_menu_css_class', array_filter ( $classes ), $item ) ) );
		
		// build html
		$output .= $indent . '<li>';
		
		// link attributes
		$attributes = ! empty ( $item->attr_title ) ? ' title="' . esc_attr ( $item->attr_title ) . '"' : '';
		$attributes .= ! empty ( $item->target ) ? ' target="' . esc_attr ( $item->target ) . '"' : '';
		$attributes .= ! empty ( $item->xfn ) ? ' rel="' . esc_attr ( $item->xfn ) . '"' : '';
		$attributes .= ! empty ( $item->url ) ? ' href="' . esc_attr ( $item->url ) . '"' : '';
		$cls = (! empty ( $item->title ) ? esc_attr ( $item->title ) : '');
		$cls = strtolower ( $cls );
		$cls = str_replace ( " ", "", $cls );
		$cls = str_replace ( "/", "-", $cls );
		$attributes .= ' class="' . $cls . '"';
		
		$item_output = sprintf ( '%1$s<a%2$s><i></i>%3$s%4$s%5$s</a>%6$s', $args->before, $attributes, $args->link_before, apply_filters ( 'the_title', $item->title, $item->ID ), $args->link_after, $args->after );
		
		// build html
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
?>
<?php
/* comments */
function mytheme_comment($comment, $args, $depth) {
	$GLOBALS ['comment'] = $comment;
	extract ( $args, EXTR_SKIP );
	if ('div' == $args ['style']) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	?>
<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
<?php if ( 'div' != $args['style'] ) : ?>
<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, 90 ); ?>

	</div>
	<div class="commentText">
		<span class="arrow"></span>
		<div class="userRow">
			<a href="<?php get_comment_author_url();?>">
				<?php echo get_comment_author_link();?>
			</a>
			<span class="commentTime"> <?php printf( __('%1$s '), get_comment_date('F j, Y'))?>
			</span>
			<?php
	if ($comment->comment_parent) {
		$parent_comment = get_comment ( $comment->comment_parent );
		echo 'to <a href="' . get_comment_author_url () . '" >' . $parent_comment->comment_author . '</a>';
	}
	?>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?> </em>
		<?php endif; ?>
		<div class="commentData">
			<?php comment_text(); ?>
		</div>
		<!-- /.commentBody -->
		<div class="actionRow">
			<?php if(get_edit_comment_link(__('Edit'),'  ','' ) !=  "" ):?>
			<span class="comment-meta commentmetadata"> <?php edit_comment_link(__('Edit'),'  ','' );?>
			</span>
			<?php endif;?>
			<span class="reply"> <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])))?>
			</span>
		</div>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
</div>




	<?php endif;
}
?>