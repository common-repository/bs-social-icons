<?php
if (!defined('BSOFT_SOCIAL_VERSION')) die('No direct access allowed');

/**
 * The proper way to obtain access to the instance is via Bsoft_Social_Icon()->get_options().
 */
class Bsoft_Social_Options {
	
	private  $row;
	private  $rows;
	
	/**
	 * Performs save action on passed data 
	 * @param  Array 	$settings an array of data  
	 * @return String 	returns message
	 */
	public function save_settings($settings) {
		$new_bsoft_redirect_name = $settings['bsoft_redirect_name'];
		$new_bsoft_redirect_link = $settings['bsoft_redirect_link'];
		$new_bsoft_image_attachment_id=$settings['upload_image_button'];

		// Separate and convert to an array
		$bsoft_redirect_name_val = implode(" ",$new_bsoft_redirect_name);
		$bsoft_redirect_link_val = implode(" ",$new_bsoft_redirect_link);
		$bsoft_image_attachment_id_val = implode(" ",$new_bsoft_image_attachment_id);

		if ($bsoft_redirect_name_val == "") {
			return "Please Enter name";
		}else if ($bsoft_redirect_link_val == "") {
			return "Please Enter Link";
		}else if ($bsoft_image_attachment_id_val == "") {
			return "Please Select Image";
		}else
		{
			// Get old already exists
			$old_bsoft_redirect_name = get_option('bsoft_redirect_name');
			$old_bsoft_redirect_link = get_option('bsoft_redirect_link');
			$old_bsoft_image_attachment_id = get_option('bsoft_image_attachment_id');

			// Merge old and new
			if ($old_bsoft_redirect_link != "") {
				$upd_new_bsoft_redirect_name = array_merge( $old_bsoft_redirect_name, $new_bsoft_redirect_name );
				$upd_new_bsoft_redirect_link = array_merge( $old_bsoft_redirect_link, $new_bsoft_redirect_link );
				$upd_new_bsoft_image_attachment_id = array_merge( $old_bsoft_image_attachment_id, $new_bsoft_image_attachment_id );

				// Save
				update_option('bsoft_redirect_name',$upd_new_bsoft_redirect_name);
				update_option('bsoft_redirect_link',$upd_new_bsoft_redirect_link );
				update_option('bsoft_image_attachment_id',$upd_new_bsoft_image_attachment_id);
			}else{
				update_option('bsoft_redirect_name',$new_bsoft_redirect_name);
				update_option('bsoft_redirect_link',$new_bsoft_redirect_link );
				update_option('bsoft_image_attachment_id',$new_bsoft_image_attachment_id);
			}
			return "Success";
		}
	}

	/**
	 * Performs update action on passed data 
	 * @param  Array 	$settings an array of data  
	 * @return String 	returns message
	 */
	public function update_settings($settings) {
		$new_bsoft_redirect_name = implode(" ",$settings['bsoft_redirect_name']);
		$new_bsoft_redirect_link = implode(" ",$settings['bsoft_redirect_link']);
		$new_bsoft_image_attachment_id=implode(" ",$settings['upload_image_button']);
		
		if ($new_bsoft_redirect_name == "") {
			return "Please Enter name";
		}else if ($new_bsoft_redirect_link == "") {
			return "Please Enter Link";
		}else if ($new_bsoft_image_attachment_id == "") {
			return "Please Select Image";
		}else{
			$row_index=$settings['row_id'];
			// Get old already exists
			$old_bsoft_redirect_name = get_option('bsoft_redirect_name');
			$old_bsoft_redirect_link = get_option('bsoft_redirect_link');
			$old_bsoft_image_attachment_id = get_option('bsoft_image_attachment_id');

			// Add new into old
			$old_bsoft_redirect_name[$row_index] = $new_bsoft_redirect_name;
			$old_bsoft_redirect_link[$row_index] = $new_bsoft_redirect_link;
			$old_bsoft_image_attachment_id[$row_index] = $new_bsoft_image_attachment_id;

			// Save
			update_option('bsoft_redirect_name',$old_bsoft_redirect_name);
			update_option('bsoft_redirect_link',$old_bsoft_redirect_link);
			update_option('bsoft_image_attachment_id',$old_bsoft_image_attachment_id);
			return "Success";
		}
	}
	/**
	 * Performs delete action on passed data 
	 * @param  Array 	$settings an array of data  
	 * @return String 	returns message
	 */
	public function delete_settings($settings)
	{
		// Get old already exists
		$old_bsoft_redirect_name = get_option('bsoft_redirect_name');
		$old_bsoft_redirect_link = get_option('bsoft_redirect_link');
		$old_bsoft_image_attachment_id = get_option('bsoft_image_attachment_id');

		// Remove from old
		unset($old_bsoft_redirect_name[$settings]);
		unset($old_bsoft_redirect_link[$settings]);
		unset($old_bsoft_image_attachment_id[$settings]);

		// Get array values and re-index
		$bsoft_redirect_name = array_values($old_bsoft_redirect_name);
		$bsoft_redirect_link = array_values($old_bsoft_redirect_link);
		$bsoft_image_attachment_id = array_values($old_bsoft_image_attachment_id);
		// Save
		update_option('bsoft_redirect_name',$bsoft_redirect_name);
		update_option('bsoft_redirect_link',$bsoft_redirect_link );
		update_option('bsoft_image_attachment_id',$bsoft_image_attachment_id);
		return "Success";
	}

	/**
	 * Performs edit action on passed data 
	 * @param  string 	$which_row as a data-id of icon row  
	 * @return array 	returns icon row
	 */
	public function edit_settings($which_row)
	{
		$bsoft_redirect_name = get_option('bsoft_redirect_name');
		$bsoft_redirect_link = get_option('bsoft_redirect_link');
		$bsoft_image_attachment_id = get_option('bsoft_image_attachment_id');
		$row  = array(
			'bsoft_redirect_name'=>$bsoft_redirect_name[$which_row],
			'bsoft_redirect_link'=>$bsoft_redirect_link[$which_row],
			'image_url'=>wp_get_attachment_url( $bsoft_image_attachment_id[$which_row] )
			);
		return $row;
	}

	/**
	 * Set default options when plugin activate
	 * 
	 */
	public function set_default_options(){
		update_option('bsoft_admin_notices', "Thanks for using bsoft social plugin");
		if(!get_option('bsoft_redirect_name') && !get_option('bsoft_redirect_link') && !get_option('bsoft_image_attachment_id'))
		{
			update_option('bsoft_redirect_name', '');
			update_option('bsoft_redirect_link', '');
			update_option('bsoft_image_attachment_id', '');
		}

	}

	/**
	 * Render icons table or data  
	 * @return array 	returns icon rows
	 */
	public function bsoft_social_data(){
		$bsoft_redirect_name =  $bsoft_redirect_link = $bsoft_image_attachment_id = array();
		$bsoft_redirect_name = get_option('bsoft_redirect_name');
		$bsoft_redirect_link = get_option('bsoft_redirect_link');
		$bsoft_image_attachment_id = get_option('bsoft_image_attachment_id');
		$rows  = array(
			'name'=>$bsoft_redirect_name,
			'link'=>$bsoft_redirect_link,
			'icon'=>$bsoft_image_attachment_id
			);
		return $rows;
	}
}
?>