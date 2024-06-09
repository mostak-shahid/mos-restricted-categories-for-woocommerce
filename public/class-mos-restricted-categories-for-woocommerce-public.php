<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.mdmostakshahid.com/
 * @since      1.0.0
 *
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/public
 * @author     Md. Mostak Shahid <mostak.shahid@gmail.com>
 */
class Mos_Restricted_Categories_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mos_Restricted_Categories_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mos_Restricted_Categories_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mos-restricted-categories-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mos_Restricted_Categories_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mos_Restricted_Categories_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mos-restricted-categories-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );

	}
	public function mos_restricted_categories_for_woocommerce_woo_product_query( $q ){ 
		global $wpdb;
		//SELECT term_id FROM wp_term_taxonomy WHERE taxonomy = 'product_cat'
		$all_cats_raw = $wpdb->get_results($wpdb->prepare("SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = %s;", ['product_cat']),ARRAY_A);
		$all_cats = array_column($all_cats_raw, 'term_id');	


		//SELECT term_id FROM {$wpdb->prefix}termmeta WHERE meta_key = 'mos_product_cat_visibility' AND meta_value != 'public'
		$restricted_cats_raw = $wpdb->get_results($wpdb->prepare("SELECT term_id FROM {$wpdb->prefix}termmeta WHERE meta_key = %s AND meta_value != %s;", ['mos_product_cat_visibility', 'public']),ARRAY_A);

		$restricted_cats = array_column($restricted_cats_raw, 'term_id');

		$allowed_cats = array_diff($all_cats,$restricted_cats);

		// echo '<pre>';
		// var_dump($all_cats);
		// var_dump($restricted_cats);
		// var_dump($result);
		// echo '</pre>';

		// $wpdb->get_results(
        //     $wpdb->prepare(
        //         "SELECT term_id FROM {$wpdb->prefix}termmeta WHERE meta_key = 'mos_product_cat_visibility' AND meta_value != 'public';",
        //         array(
        //             $args['meta_key']
        //         )
        //     ),
        // );
		$args = array(
		//   array(
		// 	'key'       => '_price',
		// 	'value'     => array( 10 , 20 ),
		// 	'compare'   => 'BETWEEN',
		// 	'type'      => 'numeric'  
		//   ),
		  array(
			'taxonomy' => 'product_cat',
			'field' => 'id',
			'terms' => $allowed_cats,
			//'compare' => '!=', //'=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'NOT EXISTS', 'REGEXP', 'NOT REGEXP' or 'RLIKE'. Default value is '='.
		  ),
		);	

		//$q->set( 'meta_query', $args );	
		$q->set( 'tax_query', $args );	
	}

	public function filter_is_purchasable($is_purchasable, $product ) {
		//global $product;
		$_product = wc_get_product( $product->get_id() );
		$category_ids = $_product->get_category_ids();//23
		//check product categories is inside restriction or not
			$user = wp_get_current_user();
			//check user has access to any of these restricted category
		// if( $product->get_id() == 57)  {
		// if( $user->roles[0] == 'administrator')  {
		if( in_array(24, $category_ids))  {
			return false;
		}
		return $is_purchasable;
	}
	//https://www.cloudways.com/blog/how-to-remove-hide-or-disable-add-to-cart-button-in-woocommerce/
	//https://github.com/wolffe/wp-primary-category/tree/master

}
