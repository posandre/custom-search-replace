<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://index.html
 * @since      1.0.0
 *
 * @package    Custom_Search_Replace
 * @subpackage Custom_Search_Replace/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Custom_Search_Replace
 * @subpackage Custom_Search_Replace/includes
 * @author     Andrii Postoliuk <an.postoliuk@gmail.com>
 */
class Custom_Search_Replace {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Custom_Search_Replace_Loader    $loader    Maintains and registers all hooks for the plugin.
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
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $text_domain   The current version of the plugin.
     */
    protected $text_domain;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CUSTOM_SEARCH_REPLACE_VERSION' ) ) {
			$this->version = CUSTOM_SEARCH_REPLACE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'custom-search-replace';
		$this->text_domain = 'custom-search-replace';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Custom_Search_Replace_Loader. Orchestrates the hooks of the plugin.
	 * - Custom_Search_Replace_i18n. Defines internationalization functionality.
	 * - Custom_Search_Replace_Admin. Defines all hooks for the admin area.
	 * - Custom_Search_Replace_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-search-replace-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-search-replace-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custom-search-replace-admin.php';

        $this->loader = new Custom_Search_Replace_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Custom_Search_Replace_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Custom_Search_Replace_i18n();

		//$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Custom_Search_Replace_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_text_domain() );

        //Register styles and scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        //Added new page to the admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu_item' );

        //Register AJAX callback function
        $this->loader->add_action( 'wp_ajax_search_keyword', $plugin_admin, 'search_keyword_callback' );
        $this->loader->add_action( 'wp_ajax_nopriv_search_keyword', $plugin_admin, 'search_keyword_callback' );

        $this->loader->add_action( 'wp_ajax_replace_keyword_in_title', $plugin_admin, 'replace_keyword_in_title_callback' );
        $this->loader->add_action( 'wp_ajax_nopriv_replace_keyword_in_title', $plugin_admin, 'replace_keyword_in_title_callback' );

        $this->loader->add_action( 'wp_ajax_replace_keyword_in_content', $plugin_admin, 'replace_keyword_in_content_callback' );
        $this->loader->add_action( 'wp_ajax_nopriv_replace_keyword_in_content', $plugin_admin, 'replace_keyword_in_content_callback' );

        $this->loader->add_action( 'wp_ajax_replace_keyword_in_meta_title', $plugin_admin, 'replace_keyword_in_meta_title_callback' );
        $this->loader->add_action( 'wp_ajax_nopriv_replace_keyword_in_meta_title', $plugin_admin, 'replace_keyword_in_meta_title_callback' );

        $this->loader->add_action( 'wp_ajax_replace_keyword_in_meta_description', $plugin_admin, 'replace_keyword_in_meta_description_callback' );
        $this->loader->add_action( 'wp_ajax_nopriv_replace_keyword_in_meta_description', $plugin_admin, 'replace_keyword_in_meta_description_callback' );

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
	 * @return    Custom_Search_Replace_Loader    Orchestrates the hooks of the plugin.
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

    /**
     * Retrieve the text domain of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_text_domain() {
        return $this->text_domain;
    }

}
