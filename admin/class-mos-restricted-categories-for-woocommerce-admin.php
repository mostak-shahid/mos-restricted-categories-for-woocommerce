<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.mdmostakshahid.com/
 * @since      1.0.0
 *
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/admin
 * @author     Md. Mostak Shahid <mostak.shahid@gmail.com>
 */
class Mos_Restricted_Categories_For_Woocommerce_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		wp_enqueue_style('select2', plugin_dir_url(__FILE__) . 'plugins/select2/css/select2.min.css', array(), $this->version, 'all');

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mos-restricted-categories-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script('select2', plugin_dir_url(__FILE__) . 'plugins/select2/js/select2.min.js', array('jquery'), $this->version, false);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mos-restricted-categories-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function mos_restricted_categories_for_woocommerce_woo_check()
	{

		if (current_user_can('activate_plugins')) {
			if (!is_plugin_active('woocommerce/woocommerce.php') && !file_exists(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')) {
?>
				<div id="message" class="error">
					<?php /* translators: %1$s: WooCommerce plugin url start, %2$s: WooCommerce plugin url end */ ?>
					<p><?php printf(esc_html__('%1$s requires %2$s WooCommerce %3$s to be activated.', 'view-as-customer'), esc_html(MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_NAME), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>'); ?></p>
					<p><a id="mos_restricted_categories_for_woocommerce_wooinstall" class="install-now button" data-plugin-slug="woocommerce"><?php echo esc_html__('Install Now', 'view-as-customer'); ?></a></p>
				</div>

				<script>
					jQuery(document).on('click', '#mos_restricted_categories_for_woocommerce_wooinstall', function(e) {
						e.preventDefault();
						var current = jQuery(this);
						var plugin_slug = current.attr("data-plugin-slug");
						var ajax_url = '<?php echo esc_url(admin_url('admin-ajax.php')) ?>';

						current.addClass('updating-message').text('Installing...');

						var data = {
							action: 'mos_restricted_categories_for_woocommerce_ajax_install_plugin',
							_ajax_nonce: '<?php echo esc_attr(wp_create_nonce('updates')); ?>',
							slug: plugin_slug,
						};

						jQuery.post(ajax_url, data, function(response) {
								current.removeClass('updating-message');
								current.addClass('updated-message').text('Installing...');
								current.attr("href", response.data.activateUrl);
							})
							.fail(function() {
								current.removeClass('updating-message').text('Install Failed');
							})
							.always(function() {
								current.removeClass('install-now updated-message').addClass('activate-now button-primary').text('Activating...');
								current.unbind(e);
								current[0].click();
							});
					});
				</script>

			<?php
			} elseif (!is_plugin_active('woocommerce/woocommerce.php') && file_exists(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')) {
			?>

				<div id="message" class="error">
					<?php /* translators: %1$s: WooCommerce plugin url start, %2$s: WooCommerce plugin url end */ ?>
					<p><?php printf(esc_html__('%1$s requires %2$s WooCommerce %3$s to be activated.', 'view-as-customer'), esc_html(MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_NAME), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>'); ?></p>
					<p><a href="<?php echo esc_url(get_admin_url()); ?>plugins.php?_wpnonce=<?php echo esc_attr(wp_create_nonce('activate-plugin_woocommerce/woocommerce.php')); ?>&action=activate&plugin=woocommerce/woocommerce.php" class="button activate-now button-primary"><?php esc_html_e('Activate', 'view-as-customer'); ?></a></p>
				</div>
			<?php
			} elseif (version_compare(get_option('woocommerce_db_version'), '2.5', '<')) {
			?>

				<div id="message" class="error">
					<?php /* translators: %1$s: strong tag start, %2$s: strong tag end, %3$s: plugin url start, %4$s: plugin url end */ ?>
					<p><?php printf(esc_html__('%1$s %2$s is inactive.%3$s This plugin requires WooCommerce 2.5 or newer. Please %4$supdate WooCommerce to version 2.5 or newer%4$s', 'view-as-customer'), esc_html(MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_NAME), '<strong>', '</strong>', '<a href="' . esc_url(admin_url('plugins.php')) . '">', '&nbsp;&raquo;</a>'); ?></p>
				</div>

<?php
			}
		}
	}
	//Product Cat Create page
	public function mos_restricted_categories_for_woocommerce_add_new_meta_field() {
	wp_nonce_field('mos_restricted_categories_for_woocommerce_action', 'mos_restricted_categories_for_woocommerce_field');
	$allusers = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
	$role_names = wp_roles()->role_names;
    ?>   
		<div class="form-field">
			<label for="mos_restricted_categories_for_woocommerce_custom_meta_data">Visibility</label>
			
					<fieldset id="mos_product_cat_visibility" class="mos-cat-visibility">
						<legend class="screen-reader-text"><?php echo esc_html__('Visibility', 'view-as-customer') ?></legend>

						<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_public_visibility" value="public" checked><?php echo esc_html__('Public', 'view-as-customer') ?></label>
						
						<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_private_visibility" value="private"><?php echo esc_html__('Private', 'view-as-customer') ?></label>					
						
						<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_pass" value="pass_protected"><?php echo esc_html__('Protected by Password', 'view-as-customer') ?></label>	

						<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_pass" value="login_protected"><?php echo esc_html__('Protected by Login', 'view-as-customer') ?></label>	
						
						<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_user_roles" value="user_role_protected"><?php echo esc_html__('Protected by User roles', 'view-as-customer') ?></label>	

						<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_users" value="user_protected"><?php echo esc_html__('Protected by User', 'view-as-customer') ?></label>

						<div class="mos-cat-protection-type mos-cat-protection-type-password">
							<input class="mos-cat-password-field mos_product_cat_password" name="mos_product_cat_password" id="mos_product_cat_password" type="text" placeholder="<?php echo esc_html__('Enter password…', 'view-as-customer') ?>" value="">
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
	public function mos_restricted_categories_for_woocommerce_edit_meta_field($term) {
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
					<legend class="screen-reader-text"><?php echo esc_html__('Visibility', 'view-as-customer') ?></legend>

					<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_public_visibility" value="public" <?php checked($mos_product_cat_visibility, 'public', true) ?>><?php echo esc_html__('Public', 'view-as-customer') ?></label>
					
					<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_private_visibility" value="private" <?php checked($mos_product_cat_visibility, 'private', true) ?>><?php echo esc_html__('Private', 'view-as-customer') ?></label>					
					
					<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_pass" value="pass_protected" <?php checked($mos_product_cat_visibility, 'pass_protected', true) ?>><?php echo esc_html__('Protected by Password', 'view-as-customer') ?></label>

					<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_pass" value="pass_protected" <?php checked($mos_product_cat_visibility, 'login_protected', true) ?>><?php echo esc_html__('Protected by Login', 'view-as-customer') ?></label>	
					
					<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_user_roles" value="user_role_protected" <?php checked($mos_product_cat_visibility, 'user_role_protected', true) ?>><?php echo esc_html__('Protected by User roles', 'view-as-customer') ?></label>	

					<label class="mos-cat-visibility-option"><input type="radio" name="mos_product_cat_visibility" id="mos_protected_visibility_users" value="user_protected" <?php checked($mos_product_cat_visibility, 'user_protected', true) ?>><?php echo esc_html__('Protected by User', 'view-as-customer') ?></label>
					<div class="mos-cat-protection-type mos-cat-protection-type-password">
						<input class="mos-cat-password-field mos_product_cat_password" name="mos_product_cat_password" id="mos_product_cat_password" type="text" placeholder="<?php echo esc_html__('Enter password…', 'view-as-customer') ?>" value="<?php echo esc_html($mos_product_cat_password) ?>">
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
	// Save extra taxonomy fields callback function.
	public function mos_restricted_categories_for_woocommerce_save_meta_field($term_id) {
		if (!isset($_POST['mos_restricted_categories_for_woocommerce_field']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mos_restricted_categories_for_woocommerce_field'])), 'mos_restricted_categories_for_woocommerce_action')) {
			return;
		}     
		update_term_meta($term_id,'mos_product_cat_visibility',sanitize_text_field($_POST['mos_product_cat_visibility']));
	
		if (isset($_POST['mos_product_cat_password']) && sanitize_text_field($_POST['mos_product_cat_visibility']) == 'pass_protected') {
			update_term_meta($term_id,'mos_product_cat_password',$_POST['mos_product_cat_password']);
		} else {
			update_term_meta($term_id,'mos_product_cat_password','');
		}	
	
		if (isset($_POST['mos_product_cat_users']) && sanitize_text_field($_POST['mos_product_cat_visibility']) == 'user_protected') {
			update_term_meta($term_id,'mos_product_cat_users',$_POST['mos_product_cat_users']);
		} else {
			update_term_meta($term_id,'mos_product_cat_users','');
		}
	
		if (isset($_POST['mos_product_cat_user_roles']) && sanitize_text_field($_POST['mos_product_cat_visibility']) == 'user_role_protected') {
			update_term_meta($term_id,'mos_product_cat_user_roles',$_POST['mos_product_cat_user_roles']);
		} else {
			update_term_meta($term_id,'mos_product_cat_user_roles','');
		}
		// update_term_meta($term_id,'mos_product_cat_user_roles',$_POST['mos_product_cat_user_roles']);
	}
	/**
	 * Meta Title and Description column added to category admin screen.
	 *
	 * @param mixed $columns
	 * @return array
	 */
	public function mos_restricted_categories_for_woocommerce_cat_list_title( $columns ) {
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
	public function mos_restricted_categories_for_woocommerce_cat_list_display( $columns, $column, $id ) {
		if ( 'visibility' == $column ) {

			$mos_product_cat_visibility = get_term_meta( $id, 'mos_product_cat_visibility', true )?get_term_meta( $id, 'mos_product_cat_visibility', true ):"public";
			$mos_product_cat_password = get_term_meta( $id, 'mos_product_cat_password', true )?get_term_meta( $id, 'mos_product_cat_password', true ):"";
			$mos_product_cat_users = get_term_meta( $id, 'mos_product_cat_users', true )?get_term_meta( $id, 'mos_product_cat_users', true ):[];
			$mos_product_cat_user_roles = get_term_meta( $id, 'mos_product_cat_user_roles', true )?get_term_meta( $id, 'mos_product_cat_user_roles', true ):[];
			if($mos_product_cat_visibility == 'private') {
				$columns = esc_html__('Private', 'view-as-customer');
			}
			elseif($mos_product_cat_visibility == 'pass_protected') {
				$columns = '<p>'. esc_html__('Password Protected', 'view-as-customer').'</p>';
				$columns .= '<span class="badge secondary">'.esc_html($mos_product_cat_password).'</span>';
			}
			elseif($mos_product_cat_visibility == 'login_protected') {
				$columns = '<p>'.esc_html__('Login Protected', 'view-as-customer').'</p>';
			}
			elseif($mos_product_cat_visibility == 'user_role_protected') {
				$columns = '<p>'.esc_html__('User roles Protected', 'view-as-customer').'</p>';
				if ($mos_product_cat_user_roles) {
					foreach($mos_product_cat_user_roles as $role) {
						$columns .= '<span class="badge secondary">'.esc_html($role).'</span>';
					}
				}
			}
			elseif($mos_product_cat_visibility == 'user_protected') {
				$columns = '<p>'.esc_html__('User Protected', 'view-as-customer').'</p>';
				if ($mos_product_cat_users) {
					foreach($mos_product_cat_users as $user_id) {
						$user = get_user_by( 'ID', $user_id );
						$columns .= '<span class="badge secondary">'.esc_html($user->display_name).'(ID: '.esc_html($user_id).')'.'</span>';
					}
				}
			}
			else $columns = '<p>'.esc_html__('Public', 'view-as-customer').'</p>';
			
		}
		return $columns;
	}
}
