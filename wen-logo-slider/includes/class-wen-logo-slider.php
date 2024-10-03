<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wensolutions.com
 * @since      1.0.0
 *
 * @package    WEN_Logo_Slider
 * @subpackage WEN_Logo_Slider/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WEN_Logo_Slider
 * @subpackage WEN_Logo_Slider/includes
 * @author     WEN Solutions <info@wensolutions.com>
 */
class WEN_Logo_Slider {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WEN_Logo_Slider_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'wen-logo-slider';
		$this->version = '3.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WEN_Logo_Slider_Loader. Orchestrates the hooks of the plugin.
	 * - WEN_Logo_Slider_i18n. Defines internationalization functionality.
	 * - WEN_Logo_Slider_Admin. Defines all hooks for the dashboard.
	 * - WEN_Logo_Slider_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wen-logo-slider-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wen-logo-slider-i18n.php';

		/**
		 * The class responsible for defining shortcodes of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wen-logo-slider-shortcode.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wen-logo-slider-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wen-logo-slider-public.php';

		$this->loader = new WEN_Logo_Slider_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WEN_Logo_Slider_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WEN_Logo_Slider_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	public function init_shortcodes(){

		$plugin_shortcode = new WEN_Logo_Slider_Shortcode();
		$plugin_shortcode->init();


	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WEN_Logo_Slider_Admin( $this->get_plugin_name(), $this->get_version() );

		// Load styles and scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		//$this->loader->add_action( 'wp_head', $plugin_admin,'display_meta_box' );

		// Add Admin column
		$this->loader->add_filter( "manage_".WEN_LOGO_SLIDER_POST_TYPE_LOGO_SLIDER."_posts_columns", $plugin_admin, 'usage_column_head' );
		$this->loader->add_action( "manage_".WEN_LOGO_SLIDER_POST_TYPE_LOGO_SLIDER."_posts_custom_column", $plugin_admin, 'usage_column_content', 10, 2 );

		// Add metaboxes
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slides_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_settings_meta_box' );

		// Hide publishing actions
		$this->loader->add_action( 'admin_head-post.php', $plugin_admin, 'hide_publishing_actions' );
		$this->loader->add_action( 'admin_head-post-new.php', $plugin_admin, 'hide_publishing_actions' );

		// Row action
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'customize_row_actions', 10, 2 );

		// Button in toolbar
		$this->loader->add_action( 'admin_init', $plugin_admin, 'tinymce_button' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'tinymce_popup' );

		// Templates
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'html_templates' );

		// Post messages
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'updated_messages' );

		// Tinymce language
		$this->loader->add_filter( 'mce_external_languages', $plugin_admin, 'tinymce_external_language' );	

		//wen-logo-slider settings 
		$this->loader->add_action('admin_menu', $plugin_admin, 'wen_logo_slider_setting_menu'); 
		$this->loader->add_action('admin_init', $plugin_admin, 'register_logo_slider_settings');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WEN_Logo_Slider_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'init', $plugin_public, 'custom_post_types' );

		// Enable shortcode in Text widget
		add_filter( 'widget_text', 'shortcode_unautop');
		add_filter( 'widget_text', 'do_shortcode');


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WEN_Logo_Slider_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
