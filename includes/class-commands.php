<?php 

if (!defined('BSOFT_SOCIAL_PLUGIN_MAIN_PATH')) die('No direct access allowed');

/*

All commands that are intended to be available for calling from any sort of control interface (e.g. wp-admin) go in here.

All public methods should either return the data to be returned, or a WP_Error with associated error code, message and error data.

*/

class Bsoft_Social_Commands {

	private $options;

	public function __construct() {
		$this->options = Bsoft_Social_Icon()->get_options();
	}

	/**
	 * This sends the passed data value over to the save function 
	 * @param  Array 	$data an array of data UI form 
	 * @return Array 	returns message as status
	 */
	public function bsoft_save_settings($data) {
		parse_str(stripslashes($data), $posted_settings);
		return array(
			'status' => $this->options->save_settings($posted_settings),
			);
	}

	/**
	 * This sends the passed data value over to the update function 
	 * @param  Array 	$data an array of data UI form 
	 * @return Array 	returns message as status
	 */
	public function bsoft_update_settings($data) {		
		parse_str(stripslashes($data), $posted_settings);		
		return array(
			'status' => $this->options->update_settings($posted_settings),
			);
	}

	/**
	 * This sends the passed data value over to the delete function 
	 * @param  Array 	$data an array of data UI form 
	 * @return Array 	returns message as status
	 */
	public function bsoft_delete_settings($data) {
		parse_str(stripslashes($data), $posted_settings);
		return array(
			'status' => $this->options->delete_settings($data),
			);
	}

	/**
	 * This sends the passed data value over to the edit function 
	 * @param  Array 	$data an array of data UI form 
	 * @return Array 	returns icon details to update through edit
	 */
	public function bsoft_edit_settings($data) {
		parse_str(stripslashes($data), $posted_settings);
		return array(
			'results' => $this->options->edit_settings($data),
			);
	}
}