<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.mdmostakshahid.com/
 * @since             1.0.0
 * @package           Mos_Restricted_Categories_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Mos Restricted Categories for Woocommerce
 * Plugin URI:        https://www.mdmostakshahid.com/mos-restricted-categories-for-woocommerce
 * Description:       Lock entire WooCommerce categories - restrict access to certain users, and user roles, or create a secret password. All products are completely hidden.
 * Version:           1.0.0
 * Author:            Md. Mostak Shahid
 * Author URI:        https://www.mdmostakshahid.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mos-restricted-categories-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mos-restricted-categories-for-woocommerce-activator.php
 */
function mos_restricted_categories_for_woocommerce_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mos-restricted-categories-for-woocommerce-activator.php';
	Mos_Restricted_Categories_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mos-restricted-categories-for-woocommerce-deactivator.php
 */
function mos_restricted_categories_for_woocommerce_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mos-restricted-categories-for-woocommerce-deactivator.php';
	Mos_Restricted_Categories_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'mos_restricted_categories_for_woocommerce_activate' );
register_deactivation_hook( __FILE__, 'mos_restricted_categories_for_woocommerce_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mos-restricted-categories-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function mos_restricted_categories_for_woocommerce_run() {

	$plugin = new Mos_Restricted_Categories_For_Woocommerce();
	$plugin->run();

}
mos_restricted_categories_for_woocommerce_run();



add_action('product_cat_add_form_fields', 'mos_restricted_categories_for_woocommerce_add_new_meta_field', 10, 1);
add_action('product_cat_edit_form_fields', 'mos_restricted_categories_for_woocommerce_edit_meta_field', 10, 1);
//Product Cat Create page
function mos_restricted_categories_for_woocommerce_add_new_meta_field() {
	wp_nonce_field('mos_restricted_categories_for_woocommerce_action', 'mos_restricted_categories_for_woocommerce_field');
	$allusers = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
	$role_names = wp_roles()->role_names;
    ?>   
  <div class="form-field">
  	<label for="mos_restricted_categories_for_woocommerce_custom_meta_data">Visibility</label>
    
			<fieldset id="mos_product_cat_visibility" class="mos-cat-visibility">
				<legend class="screen-reader-text">Visibility</legend>
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_public_visibility" value="public" checked> Public</label>
				
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_private_visibility" value="private"> Private</label>					
				
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_pass" value="pass_protected"> Protected by Password </label>	
				
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_user_roles" value="user_role_protected">Protected by User roles</label>	

				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_users" value="user_protected">Protected by User</label>
				<div class="mos-cat-protection-type mos-cat-protection-type-password">
					<input class="mos-cat-password-field mos_product_cat_password" name="mos_product_cat_password" id="mos_product_cat_password" type="text" placeholder="Enter password…" value="">
				</div>				

				<div class="mos-cat-protection-type mos-cat-protection-type-user-roles">
					<select name="mos_product_cat_user_roles[]" id="mos_product_cat_user_roles" class="select2 mos-cat-protection-select mos_product_cat_user_roles" multiple>
						<?php foreach($role_names as $key=>$value) : ?>
							<option value="<?php echo esc_html($key) ?>"><?php echo esc_html($value) ?></option>
						<?php endforeach?>
					</select>
				</div>
				<div class="mos-cat-protection-type mos-cat-protection-type-user">
					<select name="mos_product_cat_users[]" id="mos_product_cat_users" class="select2 mos-cat-protection-select mos_product_cat_users" multiple>
						<?php foreach ( $allusers as $user ) : ?>
							<option value="<?php echo esc_html($user->ID) ?>"><?php echo esc_html($user->display_name) ?></option>
						<?php endforeach?>					
					</select>
				</div>
			</fieldset>
  </div>
    <?php
}

//Product Cat Edit page
function mos_restricted_categories_for_woocommerce_edit_meta_field($term) {
	wp_nonce_field('mos_restricted_categories_for_woocommerce_action', 'mos_restricted_categories_for_woocommerce_field');

	
	$mos_product_cat_visibility = get_term_meta( $term->term_id, 'mos_product_cat_visibility', true )?get_term_meta( $term->term_id, 'mos_product_cat_visibility', true ):"public";
	$mos_product_cat_password = get_term_meta( $term->term_id, 'mos_product_cat_password', true )?get_term_meta( $term->term_id, 'mos_product_cat_password', true ):"";
	$mos_product_cat_users = get_term_meta( $term->term_id, 'mos_product_cat_users', true )?get_term_meta( $term->term_id, 'mos_product_cat_users', true ):[];
	$mos_product_cat_user_roles = get_term_meta( $term->term_id, 'mos_product_cat_user_roles', true )?get_term_meta( $term->term_id, 'mos_product_cat_user_roles', true ):[];
	$allusers = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
	$role_names = wp_roles()->role_names;
    ?>
	<tr class="form-field">
		<th>
			<label for="mos_restricted_categories_for_woocommerce_custom_meta_data">Visibility</label>
		</th>
		<td>
			<fieldset id="mos_product_cat_visibility" class="mos-cat-visibility">
				<legend class="screen-reader-text">Visibility</legend>
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_public_visibility" value="public" <?php checked($mos_product_cat_visibility, 'public', true) ?>> Public</label>
				
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_private_visibility" value="private" <?php checked($mos_product_cat_visibility, 'private', true) ?>> Private</label>					
				
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_pass" value="pass_protected" <?php checked($mos_product_cat_visibility, 'pass_protected', true) ?>> Protected by Password </label>	
				
				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_user_roles" value="user_role_protected" <?php checked($mos_product_cat_visibility, 'user_role_protected', true) ?>>Protected by User roles</label>	

				<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_users" value="user_protected" <?php checked($mos_product_cat_visibility, 'user_protected', true) ?>>Protected by User</label>
				<div class="mos-cat-protection-type mos-cat-protection-type-password">
					<input class="mos-cat-password-field mos_product_cat_password" name="mos_product_cat_password" id="mos_product_cat_password" type="text" placeholder="Enter password…" value="<?php echo esc_html($mos_product_cat_password) ?>">
				</div>				

				<div class="mos-cat-protection-type mos-cat-protection-type-user-roles">
					<select name="mos_product_cat_user_roles[]" id="mos_product_cat_user_roles" class="select2 mos-cat-protection-select mos_product_cat_user_roles" multiple>
						<?php foreach($role_names as $key=>$value) : ?>
							<option value="<?php echo esc_html($key) ?>" <?php echo (in_array($key, $mos_product_cat_user_roles))?'selected':'' ?>><?php echo esc_html($value) ?></option>
						<?php endforeach?>
					</select>
				</div>
				<div class="mos-cat-protection-type mos-cat-protection-type-user">
					<select name="mos_product_cat_users[]" id="mos_product_cat_users" class="select2 mos-cat-protection-select mos_product_cat_users" multiple>
						<?php foreach ( $allusers as $user ) : ?>
							<option value="<?php echo esc_html($user->ID) ?>" <?php echo (in_array($user->ID, $mos_product_cat_users))?'selected':'' ?>><?php echo esc_html($user->display_name) ?></option>
						<?php endforeach?>					
					</select>
				</div>
			</fieldset>
		</td>
	</tr>
    <?php
}

add_action('edited_product_cat', 'wh_save_taxonomy_custom_meta', 10, 1);
add_action('create_product_cat', 'wh_save_taxonomy_custom_meta', 10, 1);
// Save extra taxonomy fields callback function.
function wh_save_taxonomy_custom_meta($term_id) {
	if (!isset($_POST['mos_restricted_categories_for_woocommerce_field']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mos_restricted_categories_for_woocommerce_field'])), 'mos_restricted_categories_for_woocommerce_action')) {
		return;
	}     
	update_term_meta($term_id,'mos_product_cat_visibility',sanitize_text_field($_POST['mos_product_cat_visibility']));

	if (isset($_POST['mos_product_cat_password'])) {
		update_term_meta($term_id,'mos_product_cat_password',$_POST['mos_product_cat_password']);
	} else {
		update_term_meta($term_id,'mos_product_cat_password','');
	}	

	if (isset($_POST['mos_product_cat_users'])) {
		update_term_meta($term_id,'mos_product_cat_users',$_POST['mos_product_cat_users']);
	} else {
		update_term_meta($term_id,'mos_product_cat_users','');
	}

	if (isset($_POST['mos_product_cat_user_roles'])) {
		update_term_meta($term_id,'mos_product_cat_user_roles',$_POST['mos_product_cat_user_roles']);
	} else {
		update_term_meta($term_id,'mos_product_cat_user_roles','');
	}
	// update_term_meta($term_id,'mos_product_cat_user_roles',$_POST['mos_product_cat_user_roles']);
}
//Displaying Additional Columns
add_filter( 'manage_edit-product_cat_columns', 'wh_customFieldsListTitle' ); //Register Function
add_action( 'manage_product_cat_custom_column', 'wh_customFieldsListDisplay' , 10, 3); //Populating the Columns
/**
 * Meta Title and Description column added to category admin screen.
 *
 * @param mixed $columns
 * @return array
 */
function wh_customFieldsListTitle( $columns ) {
    $columns['visibility'] = __( 'Visibility', 'woocommerce' );
    return $columns;
}
/**
 * Meta Title and Description column value added to product category admin screen.
 *
 * @param string $columns
 * @param string $column
 * @param int $id term ID
 *
 * @return string
 */
function wh_customFieldsListDisplay( $columns, $column, $id ) {
    if ( 'visibility' == $column ) {
		$visibility = get_term_meta($id, 'mos_product_cat_visibility', true)?esc_html( get_term_meta($id, 'mos_product_cat_visibility', true) ):esc_html('public');
		if($visibility == 'private') $columns = 'Private';
		elseif($visibility == 'pass_protected') $columns = 'Password Protected';
		elseif($visibility == 'user_role_protected') $columns = 'User roles Protected';
		elseif($visibility == 'user_protected') $columns = 'User Protected';
		else $columns = 'Public';
        
    }
    return $columns;
}



// var_dump(mos_restricted_categories_for_woocommerce_category_protection('21'));
function mos_restricted_categories_for_woocommerce_category_protection($id){
	$output = [];
	// Usage in archive or category (taxonomy) template
	$output['visibility'] = get_term_meta( $id, 'mos_product_cat_visibility', true);
	$output['password'] = get_term_meta( $id, 'mos_product_cat_password', true);
	$output['users'] = get_term_meta( $id, 'mos_product_cat_users', true);
	$output['user_roles'] = get_term_meta( $id, 'mos_product_cat_user_roles', true);
	return $output;
}
add_action('wp_head', 'mos_restricted_categories_for_woocommerce_product_protection');
// var_dump(get_the_ID());
// var_dump(mos_restricted_categories_for_woocommerce_product_protection('75'));
function mos_restricted_categories_for_woocommerce_product_protection($id = 0){
	$output = [];
	if(is_single()){
		$alldata = [];
		$temp = [];
		if(!$id) $id = get_the_ID();
		global $product;

		// $id = $product->get_id();
		// $product = wc_get_product( $id );
		$terms = get_the_terms( $id, 'product_cat' );
		foreach($terms as $term) {
			$alldata[] = mos_restricted_categories_for_woocommerce_category_protection($term->term_id);
		}
		if (sizeof($alldata)) {
			foreach($alldata as $data) {
				foreach($data as $key => $value) {
					if(is_array($value)) {
						foreach($value as $index => $val) {
							// var_dump($val);
							if($val) $temp[$key][$index] = $val;
						}
					} else {
						if($value)
						$temp[$key][] = $value;
					}
				}
			}
			$visibility = array_unique($temp['visibility']);
			$password = array_unique($temp['password']);
			$users = array_unique($temp['users']);
			$user_roles = array_unique($temp['user_roles']);
		}
		// echo '<pre>';
		/*var_dump($visibility);
		var_dump($password);
		var_dump($users);
		var_dump($user_roles);
		$a=array(
			"pass_protected",
			"pass_protected",
			"user_role_protected"
		);*/
		
		$output = [
			'visibility'	=> $visibility,
			'password'		=> $password,
			'users'			=> $users,
			'user_roles'	=> $user_roles
		];
	} 
	// var_dump($output);
	// echo '</pre>';
}