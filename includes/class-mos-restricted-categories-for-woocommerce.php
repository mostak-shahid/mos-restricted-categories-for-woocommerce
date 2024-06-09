<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.mdmostakshahid.com/
 * @since      1.0.0
 *
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/includes
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
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/includes
 * @author     Md. Mostak Shahid <mostak.shahid@gmail.com>
 */
class Mos_Restricted_Categories_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mos_Restricted_Categories_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mos-restricted-categories-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mos_Restricted_Categories_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Mos_Restricted_Categories_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Mos_Restricted_Categories_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Mos_Restricted_Categories_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once(ABSPATH . 'wp-admin/includes/plugin.php');

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mos-restricted-categories-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mos-restricted-categories-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mos-restricted-categories-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mos-restricted-categories-for-woocommerce-public.php';

		$this->loader = new Mos_Restricted_Categories_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mos_Restricted_Categories_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mos_Restricted_Categories_For_Woocommerce_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mos_Restricted_Categories_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			$this->loader->add_action('admin_notices', $plugin_admin, 'mos_restricted_categories_for_woocommerce_woo_check');
			add_action("wp_ajax_mos_restricted_categories_for_woocommerce_ajax_install_plugin", "wp_ajax_install_plugin");
		} else {
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			// add_action('product_cat_add_form_fields', 'mos_restricted_categories_for_woocommerce_add_new_meta_field', 10, 1);
			// add_action('product_cat_edit_form_fields', 'mos_restricted_categories_for_woocommerce_edit_meta_field', 10, 1);			
			$this->loader->add_action( 'product_cat_add_form_fields', $plugin_admin, 'mos_restricted_categories_for_woocommerce_add_new_meta_field', 10, 1 );
			$this->loader->add_action( 'product_cat_edit_form_fields', $plugin_admin, 'mos_restricted_categories_for_woocommerce_edit_meta_field', 10, 1 );

			
			// add_action('edited_product_cat', 'mos_restricted_categories_for_woocommerce_save_meta_field', 10, 1);
			// add_action('create_product_cat', 'mos_restricted_categories_for_woocommerce_save_meta_field', 10, 1);
			$this->loader->add_action( 'edited_product_cat', $plugin_admin, 'mos_restricted_categories_for_woocommerce_save_meta_field', 10, 1 );
			$this->loader->add_action( 'create_product_cat', $plugin_admin, 'mos_restricted_categories_for_woocommerce_save_meta_field', 10, 1 );

			// Displaying Additional Columns
			// add_filter( 'manage_edit-product_cat_columns', 'mos_restricted_categories_for_woocommerce_cat_list_title' );
			// add_action( 'manage_product_cat_custom_column', 'mos_restricted_categories_for_woocommerce_cat_list_display' , 10, 3);			
			$this->loader->add_action( 'manage_edit-product_cat_columns', $plugin_admin, 'mos_restricted_categories_for_woocommerce_cat_list_title');
			$this->loader->add_action( 'manage_product_cat_custom_column', $plugin_admin, 'mos_restricted_categories_for_woocommerce_cat_list_display', 10, 3);
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mos_Restricted_Categories_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		if (is_plugin_active('woocommerce/woocommerce.php')) {
			//add_action( 'woocommerce_product_query', 'mos_restricted_categories_for_woocommerce_woo_product_query' );
			$this->loader->add_action( 'woocommerce_product_query', $plugin_public, 'mos_restricted_categories_for_woocommerce_woo_product_query' );
			//
			// add_filter( 'woocommerce_is_purchasable', 'wpcustom_is_purchasable', 10, 2 );
			// $this->loader->add_filter( 'woocommerce_is_purchasable', $plugin_public, 'wpcustom_is_purchasable', 10, 2 );
			/* REMOVE ADD TO CART BUTTON ON SPECIFIC PRODUCT IDs*/
			$this->loader->add_filter( 'woocommerce_is_purchasable', $plugin_public, 'filter_is_purchasable', 10, 2);
		}

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
	 * @return    Mos_Restricted_Categories_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
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
