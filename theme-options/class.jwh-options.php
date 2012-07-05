<?php

/**
 * Master theme class
 * 
 * @package Bolts
 * @since 1.0
 */
class My_Theme_Options {
	
	private $sections;
	private $checkboxes;
	private $settings;
	
	/**
	 * Construct
	 *
	 * @since 1.0
	 */
	public function __construct() {
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();
		
		$this->sections['general']      = __( 'General Settings' );
		$this->sections['appearance']   = __( 'Appearance' );
		$this->sections['reset']        = __( 'Reset to Defaults' );
		$this->sections['about']        = __( 'About' );
		$this->sections['styles']        = __( 'Styles' );
		
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'mytheme_options' ) )
			$this->initialize_settings();
		
	}
	
	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {
		
		$admin_page = add_menu_page( __( 'Theme Options' ), __( 'Theme Options' ), 'manage_options', 'mytheme-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
		
	}
	
	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field' ),
			'desc'    => __( 'This is a default description.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'mytheme-options', $section, $field_args );
	}
	
	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {
		
		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>' . __( 'Theme Options' ) . '</h2>';
	
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.' ) . '</p></div>';
		
		echo '<form action="options.php" method="post">';
	
		settings_fields( 'mytheme_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a id="' . $section . '"href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>';
		
	
		

		echo '<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p></form>';
	
	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
	
		$("a").click(function(){
			$(".submit").show();
		});        
	
		$("#About").click(function(){
			$(".submit").hide();
		}); 
		
		
			var sections = [];';
			
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
			
			echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			$(".wrap h3, .wrap table").show();
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			
			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
			         
		});
		
	</script>
</div>';
		
	}
	
	
	/**
	 * Description for section
	 *
	 * @since 1.0
	 */
	public function display_section() {
	}
	
	/**
	 * Description for About section
	 *
	 * @since 1.0
	 */
	public function display_about_section() {
			echo('
		<table>
	        <tbody>
	                <tr>
	                        <td>SlidingTiles was developed by <a href="http://justinwhall.com" target="_blank" >Justin W Hall</a></td>
	                </tr>
	                <tr>
	                        <td>You can find a FAQ and further documentation on the <a href="#" target="" >Sliding Tiles Wordpress Theme Documentation</a> page.</td>
	                </tr>
	        </tbody>
	</table>');

		// This displays on the "About" tab. Echo regular HTML here, like so:
		 echo '<p>Copyright 2011 justin@justinwhall.com</p>';
		
	}
	/**
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 'mytheme_options' );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="mytheme_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="mytheme_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="mytheme_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="mytheme_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="mytheme_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="mytheme_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;

			case 'date':
			default:
		 		echo '<input class="date-pick' . $field_class . '" type="text" id="' . $id . '" name="mytheme_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<span class="description">' . $desc . '</span>';
		 		
		 		break;

			case 'upload':
			default:
		 		echo '<input class="upload-url' . $field_class . '" type="text" id="' . $id . '" name="mytheme_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		echo '<input class="st_upload_button" type="button" id="st_upload_button" name="upload_button" placeholder="' . $std . '" value="Upload" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
		
	}
	
	/**
	 * Settings and defaults
	 * 
	 * @since 1.0
	 */
	public function get_settings() {
		
		/* General Settings
		===========================================*/
		
		$this->settings['date1'] = array(
			'title'   => __( 'Example DatePicker Input' ),
			'desc'    => __( 'This is a description for the date picker.' ),
			'std'     => 'My date picker',
			'type'    => 'date',
			'section' => 'general'
		);

		$this->settings['st_upload'] = array(
			'title'   => __( 'Example Upload Input' ),
			'desc'    => __( 'This is a description for the upload input.' ),
			'std'     => 'My logo',
			'type'    => 'upload',
			'section' => 'general'
		);

		
		$this->settings['st_upload2'] = array(
			'title'   => __( 'Example Text Input' ),
			'desc'    => __( 'This is a description for the text input.' ),
			'std'     => 'My logo',
			'type'    => 'upload',
			'section' => 'general'
		);

		$this->settings['example_text'] = array(
			'title'   => __( 'Example Text Input' ),
			'desc'    => __( 'This is a description for the text input.' ),
			'std'     => 'Default value',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['example_textarea'] = array(
			'title'   => __( 'Example Textarea Input' ),
			'desc'    => __( 'This is a description for the textarea input.' ),
			'std'     => 'Default value',
			'type'    => 'textarea',
			'section' => 'general'
		);
		
		$this->settings['example_checkbox'] = array(
			'section' => 'general',
			'title'   => __( 'Example Checkbox' ),
			'desc'    => __( 'This is a description for the checkbox.' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['example_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'    => 'Example Heading',
			'type'    => 'heading'
		);
		
		$this->settings['example_radio'] = array(
			'section' => 'general',
			'title'   => __( 'Example Radio' ),
			'desc'    => __( 'This is a description for the radio buttons.' ),
			'type'    => 'radio',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Choice 1',
				'choice2' => 'Choice 2',
				'choice3' => 'Choice 3'
			)
		);
		
		$this->settings['example_select'] = array(
			'section' => 'general',
			'title'   => __( 'Example Select' ),
			'desc'    => __( 'This is a description for the drop-down.' ),
			'type'    => 'select',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Other Choice 1',
				'choice2' => 'Other Choice 2',
				'choice3' => 'Other Choice 3'
			)
		);
		
		/* Appearance
		===========================================*/
		
		$this->settings['header_logo'] = array(
			'section' => 'appearance',
			'title'   => __( 'Header Logo' ),
			'desc'    => __( 'Enter the URL to your logo for the theme header.' ),
			'type'    => 'text',
			'std'     => ''
		);
		
		$this->settings['favicon'] = array(
			'section' => 'appearance',
			'title'   => __( 'Favicon' ),
			'desc'    => __( 'Enter the URL to your custom favicon. It should be 16x16 pixels in size.' ),
			'type'    => 'text',
			'std'     => ''
		);
		
		$this->settings['custom_css'] = array(
			'title'   => __( 'Custom Styles' ),
			'desc'    => __( 'Enter any custom CSS here to apply it to your theme.' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'appearance',
			'class'   => 'code'
		);
				
		/* Reset
		===========================================*/
		
		$this->settings['reset_theme'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset theme' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset theme options to their defaults.' )
		);
		
		/* Styles
		===========================================*/
		
		$this->settings['body_color'] = array(
			'section' => 'styles',
			'title'   => __( 'Body' ),
			'desc'    => __( 'Select a body color' ),
			'class'   => 'color', // Custom class for CSS
			'type'    => 'text',
			'std'     => 'fff',
		);
		
		$this->settings['h1_color'] = array(
			'section' => 'styles',
			'title'   => __( 'H1' ),
			'desc'    => __( 'Select a H1 color' ),
			'class'   => 'color', // Custom class for CSS
			'type'    => 'text',
			'std'     => '333',
		);
		
		$this->settings['h2_color'] = array(
			'section' => 'styles',
			'title'   => __( 'h2' ),
			'desc'    => __( 'Select a H2 color' ),
			'class'   => 'color', // Custom class for CSS
			'type'    => 'text',
			'std'     => '666',
		);
		
		$this->settings['h3_color'] = array(
			'section' => 'styles',
			'title'   => __( 'h3' ),
			'desc'    => __( 'Select a H3 color' ),
			'class'   => 'color', // Custom class for CSS
			'type'    => 'text',
			'std'     => '333',
		);
		
		$this->settings['p_color'] = array(
			'section' => 'styles',
			'title'   => __( 'P' ),
			'desc'    => __( 'Select a color for paragraph text' ),
			'class'   => 'color', // Custom class for CSS
			'type'    => 'text',
			'std'     => '333',
		);
		
		$this->settings['a_color'] = array(
			'section' => 'styles',
			'title'   => __( 'Links' ),
			'desc'    => __( 'Select a color for links' ),
			'class'   => 'color', // Custom class for CSS
			'type'    => 'text',
			'std'     => '666',
		);
		
		$this->settings['a_hover_color'] = array(
			'section' => 'styles',
			'title'   => __( 'Links:hover' ),
			'desc'    => __( 'Select a color for the hover state of links' ),
			'class'   => 'color', // Custom class for CSS
			'type'    => 'text',
			'std'     => 'fff',
		);

	}
	
	/**
	 * Initialize settings to their default values
	 * 
	 * @since 1.0
	 */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( 'mytheme_options', $default_settings );
		
	}
	
	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		register_setting( 'mytheme_options', 'mytheme_options', array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'mytheme-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'mytheme-options' );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	/**
	* jQuery Tabs
	*
	* @since 1.0
	*/
	public function scripts() {
		
		wp_print_scripts( 'jquery-ui-tabs' );
		wp_enqueue_script('jscolor.js', get_bloginfo( 'stylesheet_directory' ) . '/theme-options/js/jscolor.js');	//color picker for '#' value fields
		wp_enqueue_script('jquery.datePicker.js', get_bloginfo( 'stylesheet_directory' ) . '/theme-options/js/jquery.datePicker.js');	//date picker. duh.
		wp_enqueue_script('myDatePicker.js', get_bloginfo( 'stylesheet_directory' ) . '/theme-options/js/myDatePicker.js');	//date picker. duh.
		wp_enqueue_script('date.js', get_bloginfo( 'stylesheet_directory' ) . '/theme-options/js/date.js');	//date picker. duh.
		
		//Media Uploader scripts
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', get_bloginfo( 'stylesheet_directory' ) . '/theme-options/js/uploader.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('my-upload');
	}
	
	/**
	* Styling for the theme options page
	*
	* @since 1.0
	*/
	public function styles() {
		
		wp_register_style( 'mytheme-admin', get_bloginfo( 'stylesheet_directory' ) . '/theme-options/css/jwh-theme-options.css' );
		wp_register_style( 'mytheme-datepicker', get_bloginfo( 'stylesheet_directory' ) . '/theme-options/css/datePicker.css' );
		wp_enqueue_style( 'mytheme-admin' );
		wp_enqueue_style( 'mytheme-datepicker' );
		wp_enqueue_style('thickbox');
		
	}
	
	/**
	* Validate settings
	*
	* @since 1.0
	*/
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'mytheme_options' );
			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			
			return $input;
		}
		return false;
		
	}
	
}

$theme_options = new My_Theme_Options();

function mytheme_option( $option ) {
	$options = get_option( 'mytheme_options' );
	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}
?>