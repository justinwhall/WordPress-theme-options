<?php
	//include options page
	require_once( STYLESHEETPATH . '/class.jwh-theme-options.php' );
	require_once( STYLESHEETPATH . '/inc/generate-animation-css.php' );


		$header_args = array(
		'default-image'          => '',
		'random-default'         => false,
		'width'                  => 400,
		'height'                 => 100,
		'flex-height'            => true,
		'flex-width'             => true,
		'default-text-color'     => '',
		'header-text'            => false,
		'uploads'                => true,
		'wp-head-callback'       => '',
		'admin-head-callback'    => '',
		'admin-preview-callback' => '',
	);
	add_theme_support( 'custom-header', $header_args );

	add_post_type_support( 'page', 'excerpt' );
	function load_dependencies() {
	    // wp_register_script( 'superfish', get_template_directory_uri() . '/js/superfish.js');
	    // wp_enqueue_script( 'superfish' );
	    // wp_register_script( 'hoverintent', get_template_directory_uri() . '/js/hoverintent.js');
	    // wp_enqueue_script( 'hoverintent' );
	    wp_register_script( 'modernizer', get_template_directory_uri() . '/js/modernizr.custom.79639.js');
	    wp_enqueue_script( 'modernizer' );
	}
	add_action('wp_enqueue_scripts', 'load_dependencies');


	if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 650, 500 ); // default Post Thumbnail dimensions
}

	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'mini-thumb', 100, 100, true ); //(cropped)
	}

	// enables wigitized sidebars
	if ( function_exists('register_sidebar') )

	// Sidebar Widget
	// Location: the sidebar
	register_sidebar(array('name'=>'Sidebar',
		'before_widget' => '<div class="widget-area widget-sidebar"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));


	// post thumbnail support
	add_theme_support( 'post-thumbnails' );
	// adds the post thumbnail to the RSS feed
	function cwc_rss_post_thumbnail($content) {
	    global $post;
	    if(has_post_thumbnail($post->ID)) {
	        $content = '<p>' . get_the_post_thumbnail($post->ID) .
	        '</p>' . get_the_content();
	    }
	    return $content;
	}
	add_filter('the_excerpt_rss', 'cwc_rss_post_thumbnail');
	add_filter('the_content_feed', 'cwc_rss_post_thumbnail');

	// custom menu support
	add_theme_support( 'menus' );
	if ( function_exists( 'register_nav_menus' ) ) {
	  	register_nav_menus(
	  		array(
	  		  'primary' => 'Home Page',
	  		  'top-nav' => 'Interior Top Navigation',
	  		  'bottom-nav' => 'Interior Bottom Navigation',
	  		)
	  	);
	}

	//add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// adds Post Format support
	// learn more: http://codex.wordpress.org/Post_Formats
	// add_theme_support( 'post-formats', array( 'aside', 'gallery','link','image','quote','status','video','audio','chat' ) );

	// removes detailed login error information for security
	add_filter('login_errors',create_function('$a', "return null;"));

	// removes the WordPress version from your header for security
	function wb_remove_version() {
		return '<!--built on the Whiteboard Framework-->';
	}
	add_filter('the_generator', 'wb_remove_version');


	// Removes Trackbacks from the comment count
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	}

	// invite rss subscribers to comment
	function rss_comment_footer($content) {
		if (is_feed()) {
			if (comments_open()) {
				$content .= 'Comments are open! <a href="'.get_permalink().'">Add yours!</a>';
			}
		}
		return $content;
	}

	//Customize the excerpt
	function smoothtransition_excerpt_length( $length ) {
		return 40;
	}
	add_filter( 'excerpt_length', 'smoothtransition_excerpt_length' );

	/**
	 * Returns a "Continue Reading" link for excerpts
	 */
	function smoothtransition_continue_reading_link() {
		return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'smoothtransition' ) . '</a>';
	}


	function smoothtransition_custom_excerpt_more( $output ) {
		if ( has_excerpt() && ! is_attachment() ) {
			$output .= '...<br>' . smoothtransition_continue_reading_link();
		}
		return $output;
	}
	add_filter( 'get_the_excerpt', 'smoothtransition_custom_excerpt_more' );

	//no more jumping for read more link
	function no_more_jumping() {
		global $post;
		return '...<a href="'.get_permalink($post->ID).'" class="read-more">'.'&nbsp;Continue Reading &raquo;'.'</a>';
	}
	add_filter('excerpt_more', 'no_more_jumping');

	// begin LifeGuard Assistant
	// learn more about the LifeGuard Assistant: http://wplifeguard.com/lifeguard-plugin/
	// learn more about the affiliate program: http://wplifeguard.com/affiliates/
	add_action('admin_menu', 'lgap_add_pages');
	function lgap_add_pages() {
		add_menu_page(__('Help','menu-test'), __('Help','menu-test'), 'manage_options', 'lifeguard-assistant-plugin', 'lgap_main_page' );
	}
	function lgap_main_page() {
		echo "<h2>" . __( 'Help', 'menu-test' ) . "</h2>";
		// place your affiliate ID between the " on the following line
		$lgap_aff = "?ref=justinwhall-257";
		// get your affiliate ID here: http://wplifeguard.com/wp-admin/profile.php?page=affiliateearnings
		echo '
		<style type="text/css">
			#wplg { font-family: "Varela",Helvetica,Trebuchet MS,Verdana,"DejaVu Sans",sans-serif; }
			#wplg a:link,#wplg a:visited { color: #21759b; text-decoration: none; }
			#wplg a:hover { color: #d54e21; }
			.wplg-video { background: #f6f6f6; border: 1px solid #dadada; padding: 12px; margin: 0 12px 12px 0; float: left; }
			.wplg-clear { clear: both; }
			.wplg-green-button { box-shadow:inset 0 0 3px rgba(0,0,0,.1); font-size: 20px; line-height: 32px; height: 32px; width: 434px; margin: 0 12px 12px 0; text-align: center; display: block; border: 2px solid #9abf89; background: #7da742; color: #f1ffeb !important; text-shadow: 0 0 3px rgba(125,167,66,.75); }
			.wplg-green-button:hover { border: 2px solid #c0e1aa; background: #8ac636; }
			.wplg-green-button:active { border: 2px solid #88a65e; background: #5d822a; }
		</style>
		<link href="http://fonts.googleapis.com/css?family=Varela" rel="stylesheet" type="text/css">
		<div id="wplg">
			<p>Need help with WordPress? Here is a collection of free WordPress video tutorials from <a href="http://wplifeguard.com/'.$lgap_aff.'">wpLifeGuard</a> to help you get going. <a href="http://wplifeguard.com/get-access/'.$lgap_aff.'">Get access to more videos.</a></p>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32852753?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32856785?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32857648?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32860297?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32872861?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32878118?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32881530?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32864178?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32863614?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32862744?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-video"><iframe src="http://player.vimeo.com/video/32857481?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="412" height="309" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<div class="wplg-clear"></div>
			<a class="wplg-green-button" href="http://wplifeguard.com/get-access/'.$lgap_aff.'">Get Full Access Now</a>
		</div>
		';
	}
	// end LifeGuard Assistant

//catch 1st image
function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [1];

if(empty($first_img)){ //Defines a default image
  $first_img = $matches [1] [0];
  }


  if(empty($first_img)){ //Defines a default image
    $first_img = bloginfo('template_directory') . "/images/thumb.png";
  }
  return $first_img;
}

//custom upload fields
add_action('post_edit_form_tag', 'post_edit_form_tag');
function post_edit_form_tag() {
    echo ' enctype="multipart/form-data"';
}

function custom_field_document_upload() {
    global $post;

    $custom         = get_post_custom($post->ID);
    $download_id    = get_post_meta($post->ID, 'document_file_id', true);

    echo '<p><label for="document_file">Upload document:</label><br />';
    echo '<input type="file" name="document_file" id="document_file" /></p>';
    echo '</p>';

    if(!empty($download_id) && $download_id != '0') {
        echo '<p><a href="' . wp_get_attachment_url($download_id) . '">
            View document</a></p>';
    }
}

function custom_field_document_update($post_id) {
    global $post;

    if(strtolower($_POST['post_type']) === 'page') {
        if(!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    }
    else {
        if(!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    if(!empty($_FILES['document_file'])) {
        $file   = $_FILES['document_file'];
        $upload = wp_handle_upload($file, array('test_form' => false));
        if(!isset($upload['error']) && isset($upload['file'])) {
            $filetype   = wp_check_filetype(basename($upload['file']), null);
            $title      = $file['name'];
            $ext        = strrchr($title, '.');
            $title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
            $attachment = array(
                'post_mime_type'    => $wp_filetype['type'],
                'post_title'        => addslashes($title),
                'post_content'      => '',
                'post_status'       => 'inherit',
                'post_parent'       => $post->ID
            );

            $attach_key = 'document_file_id';
            $attach_id  = wp_insert_attachment($attachment, $upload['file']);
            $existing_download = (int) get_post_meta($post->ID, $attach_key, true);

            if(is_numeric($existing_download)) {
                wp_delete_attachment($existing_download);
            }

            update_post_meta($post->ID, $attach_key, $attach_id);
        }
    }
}


function jwh_soc_connect(){
	//get the user entered URLS/usernames from WP backend

	$linkedin   =	jwh_option('twitter');
	$facebook   =	jwh_option('facebook');
	$googleplus =	jwh_option('googleplus');
	?>

	<?php //print the icons ?>
	<ul id="social-share">
		<li class="connect">
			<p>Connect:</p>
		</li>
		<li class="connect-twitter">
			<a href="<?php echo $linkedin; ?>">Twitter</a>
		</li>

		<li class="connect-facebook">
			<a href="<?php echo $facebook ?>">Facebook</a>
		</li>


		<li class="connect-googleplus">
			<a href="<?php echo $googleplus ?>">Googleplus</a>
		</li>
	</ul>

<?php
}

//add "last" class to last menu item in OL/UL for styling
function add_first_and_last($output) {
  $output = preg_replace('/class="(\w*\s)?menu-item/', 'class="$1first-menu-item menu-item', $output, 1);
  $pos=strripos($output, 'class="menu-item');
  $len=strlen('class="menu-item');
  $rep='class="last-menu-item menu-item';
  //double-check for a later entry with menu-item later in the
  //class list
  if(strripos($output, ' menu-item ')>$pos){
	  $pos=strripos($output, ' menu-item ');
	  $len=strlen(' menu-item ');
	  $rep=' last-menu-item menu-item ';
  }
  $output = substr_replace($output, $rep, $pos, $len);
  return $output;
}
add_filter('wp_nav_menu', 'add_first_and_last');