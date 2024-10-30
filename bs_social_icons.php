<?php
/*
Plugin Name: BS Social Icons 
Description: Add a Social media icon to your settings page.
Version: 0.0.1
Author: bsquaresoft team
Author URI: http://www.bsquaresoft.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, 3031, Third Floor,  The Palladium,Yogi Chowk, Surat, INDIA.
Copyright © 2017 bsquaresoft.com.
*/

if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('Bsoft_Social_Icon')) :

define('BSOFT_SOCIAL_VERSION', '0.0.1');
define('BSOFT_SOCIAL_PLUGIN_URL', plugins_url('', __FILE__));
define('BSOFT_SOCIAL_PLUGIN_MAIN_PATH', plugin_dir_path(__FILE__));

require_once("templates/display_icon.php");

class Bsoft_Social_Icon {

	protected static $_instance = null;
	protected static $_options_instance = null;

	public function __construct() {

		register_activation_hook(__FILE__, array($this, 'bsoft_social_activation_actions'));
		register_deactivation_hook(__FILE__, array($this, 'bsoft_social_deactivation_actions'));

		add_action('bsoft_social_set_default_options', array($this->get_options(), 'set_default_options'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action( 'init', array($this, 'bsoft_social_enqueuer'));
		add_action('wp_ajax_bsoft_social_ajax', array($this, 'bsoft_social_ajax_handler'));
		add_action( 'admin_notices', array($this,'bsoft_admin_welcome_notice'));
	}


	/**
	 * Return instance of Bsoft_Social_Icon
	 * @return null|Bsoft_Social_Icon
	 */
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function bsoft_social_enqueuer() {
		wp_register_script( "bsoft-social-script", BSOFT_SOCIAL_PLUGIN_URL.'/js/customescript.js', array('jquery') );
		wp_enqueue_style('bsoft-icon-css', BSOFT_SOCIAL_PLUGIN_URL.'css/bsoft_social.css', array());   
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bsoft-social-script' );

	}

	/**
	 * Return instance of Bsoft_Social_Options
	 * @return null|Bsoft_Social_Options
	 */
	public static function get_options() {
		if (empty(self::$_options_instance)) {
			if (!class_exists('Bsoft_Social_Options')) include_once(BSOFT_SOCIAL_PLUGIN_MAIN_PATH.'/includes/class-bsoft-social-options.php');
			self::$_options_instance = new Bsoft_Social_Options();
		}
		return self::$_options_instance;
	}
	/**
	 * Return capibility
	 * @return string
	 */
	public function capability_required() {
		return apply_filters('bsoft_social_capability_required', 'manage_options');
	}


	public function admin_menu() {
		$capability_required = $this->capability_required();
		if (!current_user_can($capability_required)) return;
		$icon = BSOFT_SOCIAL_PLUGIN_URL."/images/small_icon.png";
		add_menu_page('Bsoft Social', 'BS Social', $capability_required, 'bs-social', array($this, 'bsoft_social_menu'),$icon);
		add_submenu_page('bs-social', 'All Icons', 'All Icons', $capability_required, 'bs-social', array($this, 'bsoft_social_menu'));
		add_submenu_page('bs-social', 'Settings &amp; Instructions', 'Settings &amp; Instructions',$capability_required, 'bsoft_social_option', array($this, 'bsoft_spcial_option_render'));
	}

	/**
	 * Renders the dashboard
	 */
	public function bsoft_social_menu(){
		$capability_required = $this->capability_required();
		if (!current_user_can($capability_required)) {
			echo "Permission denied.";
			return;
		}
		$this->include_template('bsoft-social-form-add.php');
		$this->include_template('table.php',false, array('bsoft_social_option' => $this->get_options()->bsoft_social_data()));	?>
		<h4>Please visit <a href="admin.php?page=bsoft_social_option#shortcode">How to use</a> or <a href="admin.php?page=bsoft_social_option#shortcode">Settings</a> page</h4>
		<?php		
	}

	/**
	 * Renders the instruction
	 */
	public function bsoft_spcial_option_render() { ?>
		<h2 id="shortcode">How to use</h2>
		<fieldset class="bsoft-esi-shadow">
			<p><strong>bsoft Social icons</strong> is a simple plugin which allows you to easily add different <strong>social icons, Link and Name</strong> on your site. You can easily put the social icons to your<strong> post/page/sidebar/header/footer etc.</strong> bsoft Social icons allow you to modify social icons. Display social icon link connect visitor to your social site link.</p>
		</fieldset>
		<fieldset class="bsoft-esi-shadow">
			<legend><h4 class="bsoft-sec-title">Using Shortcode</h4></legend>
			<p>Copy and paste following shortcode to any <strong>Page</strong> or <strong>Post</strong>.</p>
			<p><input onclick="this.select();" readonly="readonly" type="text" value="<?php echo "[Bsoft_Social_Icon]" ?>" class="large-text" /></p>
		</fieldset>
	<?php
	}

	
	public function bsoft_social_ajax_handler() {
		$nonce = empty($_POST['nonce']) ? '' : $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'bsoft-social-ajax-nonce') || empty($_POST['subaction'])) die('Security check');
		$subaction = $_POST['subaction'];
		$data = isset($_POST['data']) ? $_POST['data'] : null;
		$results = array();
	
		if (!class_exists('Bsoft_Social_Commands')) include_once(BSOFT_SOCIAL_PLUGIN_MAIN_PATH.'includes/class-commands.php');		
		$commands = new Bsoft_Social_Commands();		
		if (!method_exists($commands, $subaction)) {
			error_log("Bsoft-Social-Icons: ajax_handler: no such command (".$command.")");
			die('No such command');
		} else {
			$results = call_user_func(array($commands, $subaction), $data);			
			if (is_wp_error($results)) {
				$results = array(
					'result' => false,
					'error_code' => $results->get_error_code(),
					'error_message' => $results->get_error_message(),
					'error_data' => $results->get_error_data(),
					);
			}
		}
		echo json_encode($results);
		die;
	}

	
	public function include_template($path, $return_instead_of_echo = false, $extract_these = array()) {
		if ($return_instead_of_echo) ob_start();
		if (preg_match('#^([^/]+)/(.*)$#', $path, $matches)) {
			$prefix = $matches[1];
			$suffix = $matches[2];
			if (isset($this->template_directories[$prefix])) {
				$template_file = $this->template_directories[$prefix].'/'.$suffix;
			}
		}
		if (!isset($template_file)) {
			$template_file = BSOFT_SOCIAL_PLUGIN_MAIN_PATH.'/templates/'.$path;
		}

		$template_file = apply_filters('bs_social_template', $template_file, $path);

		do_action('bsoft_social_before_template', $path, $template_file, $return_instead_of_echo, $extract_these);

		if (!file_exists($template_file)) {
			error_log("BS Social: template not found: ".$template_file);
		} else {
			extract($extract_these);
			$options = $this->get_options();
			include $template_file;
		}

		do_action('bs_social_after_template', $path, $template_file, $return_instead_of_echo, $extract_these);

		if ($return_instead_of_echo) return ob_get_clean();
	}

	// plugin activation actions
	public function bsoft_social_activation_actions(){
		do_action('bsoft_social_set_default_options');
		add_action( 'admin_notices', array($this,'bsoft_admin_welcome_notice'));
	}

	// plugin deactivation actions
	public function bsoft_social_deactivation_actions(){
		remove_shortcode("Bsoft_Social_Icon");
	}

	/**
 	* Set and delete admin notice
 	*/
	public function bsoft_admin_welcome_notice(){
		$notices= get_option('bsoft_admin_notices');
		if ($notices != "") {
			echo "<div class='notice notice-success is-dismissible updated'><p>".$notices."</p></div>";
			delete_option('bsoft_admin_notices');
		}
	}
}

register_uninstall_hook(__FILE__,'bsoft_social_uninstall_option');

/**
* Delete data when uninstall
*/
function bsoft_social_uninstall_option()
{
	delete_option("bsoft_redirect_link");
	delete_option("bsoft_redirect_name");
	delete_option("bsoft_image_attachment_id");
}


function Bsoft_Social_Icon() {
	return Bsoft_Social_Icon::instance();
}
endif;

$GLOBALS['bsoft_social_icon'] = Bsoft_Social_Icon();








