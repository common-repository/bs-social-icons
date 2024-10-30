/**
 * Send an action via admin-ajax.php
 * 
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
var Bsoft_Social_Icon_send_command_admin_ajax = function (action, data, callback, json_parse) {
	json_parse = ('undefined' === typeof json_parse) ? true : json_parse;
	var ajax_data = {
		action: 'bsoft_social_ajax',
		subaction: action,
		nonce: bsoft_social_ajax_nonce,
		data: data
	};
	
	jQuery.post(ajaxurl, ajax_data, function (response) {
		if (json_parse) {
			try {
				var resp = JSON.parse(response);
			} catch (e) {
				console.log(e);
				console.log(response);
				return;
			}
			if ('undefined' !== typeof callback) callback(resp);
		}else {
			if ('undefined' !== typeof callback) callback(response);
		}
	});
}

jQuery(document).ready(function ($) {
	Bsoft_Social_Icon = Bsoft_Social_Icon(Bsoft_Social_Icon_send_command_admin_ajax);
});

/**
 * Function for sending communications
 * 
 * @callable sendcommandCallable
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */

/**
 * Main Bsoft_Social_Icon
 * 
 * @param {sendcommandCallable} send_command
 */
var Bsoft_Social_Icon = function (send_command) {
	var $ = jQuery;

	$(".add-socialmedia").click(function(){
	    $(this).text(function(i, v){
	    	if(v === "Cancel"){
	    		$("#bsoft_redirect_name").val("");
	    		$("#bsoft_redirect_link").val("");
	    		$("#img_preview").html("");
	    		$("#bsoft_image_attachment_id[0]").val("");
	    		$("#btnsave").text("Save");
	    	}
	     	return v === 'Add New' ? 'Cancel' : 'Add New';
	 	});
	    $(".bsoft-main-area").toggle(400);
	});

	/**
	 * Gathers the icon details from form
	 * 
	 * @returns (string) - serialized icon row data
	 */
	function gather_icon_row(){
		var form_data = $(".bsoft-main-area form").serialize();
		return form_data;	
	}

	// Send 'save' and 'update' command, Response handler
	$("#btnsave").click(function() {
		$which_button = $(this).text();
		if($which_button == "Save"){
			var form_data = gather_icon_row();
			send_command('bsoft_save_settings', form_data, function (resp) {
				if(resp.status == "Success"){
					location.reload();
				}else{
					$('#bsoft_blank_error').css("display","block").text(resp.status).fadeOut(5000);
				}
			});
		}
		if($which_button == "Update"){
			var form_data = gather_icon_row();
			send_command('bsoft_update_settings', form_data, function (resp) {
				if(resp.status== "Success"){
					location.reload();
				}else{
					$('#bsoft_blank_error').css("display","block").text(resp.status).fadeOut(5000);
				}

			});
		}
	});
	
	// Send 'edit 'command when edit trigger and get icon row and render into form properly
	$(".bsoft-edit-button").click(function(){
		$(".bsoft-main-area").show(400);
		$(".add-socialmedia").text(function(i, v){
			if(v === 'Cancel'){
				return v = 'Cancel';
			}
			return v === 'Add New' ? 'Cancel' : 'Add New';
		});
		var which_row = $(this).attr('data-id');
		var which_img = $(this).parent("div").parent("td").parent("tr").attr('data-image-id');
		send_command('bsoft_edit_settings', which_row, function (resp) {
			$("input[id=bsoft_redirect_link]").val(resp.results.bsoft_redirect_link);
			$("input[id=bsoft_redirect_name]").val(resp.results.bsoft_redirect_name);
			$("input[id=row-id]").val(which_row);
			target_input = $('.bsoft_upload_image_button').attr('id');
			$('input[name="'+target_input+'"]').val(which_img);
			$("#img_preview").html("<img src="+resp.results.image_url +" class='img-preview'>");
			$("#btnsave").text("Update");
		});
	});

	// Send 'delete' command and reload
	$(".bsoft-delete-button").click(function(){
		var form_data = $(this).attr('data-id');
		send_command('bsoft_delete_settings', form_data, function (resp) {
			location.reload();
		});
	});

	// Check to validate redirect name
	$("#bsoft_redirect_name").keyup(function(){
		var letters = /^[A-Za-z]+$/;
		var bsoft_redirect_name=document.getElementById("bsoft_redirect_name").value;
		if(bsoft_redirect_name.match(letters)){
			$('#bsoft_name_error').removeClass('display_error');
			$("#bsoft_name_error").text("");
		}else{
			$('#bsoft_name_error').addClass('display_error');
			$("#bsoft_name_error").text("Enter Only alphabets");
			$("#bsoft_redirect_name").val("");	
		}
	});
	
	// Check to validate redirect link
	$("#bsoft_redirect_link").change(function(){
		var re = /^(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
		var bsoft_redirect_link=document.getElementById("bsoft_redirect_link").value;
		if(bsoft_redirect_link.match(re)){
			$('#bsoft_link_error').removeClass('display_error');
			$("#bsoft_link_error").text("");
		}else{
			$('#bsoft_link_error').addClass('display_error');
			$("#bsoft_link_error").text("Enter Proper URL");
			$("#bsoft_redirect_link").val("");	
		}
	});
}	

// Custom icon uploader through wp media
jQuery(document).ready(function($){
	var custom_uploader;
	var target_input;
	$('.bsoft_upload_image_button').on('click', function( e ) {
		e.preventDefault();
	    //grab the ID of the input field prior to the button where we want the url value stored
	    target_input = $(this).attr('id');
	    //If the uploader object has already been created, reopen the dialog
	    if (custom_uploader){
	    	custom_uploader.open();
	    	return;
	    }
	    //Extend the wp.media object
	    custom_uploader = wp.media.frames.file_frame = wp.media({
	    	title: 'Choose Image',
	    	button: {
	    		text: 'Choose Image'
	    	},
	    	multiple: false
	    });
	    //When a file is selected, grab the URL and set it as the text field's value
	    custom_uploader.on('select', function() {
	    	attachment = custom_uploader.state().get('selection').first().toJSON();
	           //Added target_input variable to grab ID and add URL
	           var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
	           if ($.inArray(attachment.url.split('.').pop().toLowerCase(), fileExtension) == -1){
		           	alert("Only '.jpeg','.jpg', '.png', '.gif', '.bmp' formats are allowed.");
		           	return false;
	           }else{
		           	$('input[name="'+target_input+'"]').val(attachment.id);
		           	$("#img_preview").html("<img src="+attachment.url +" class='img-preview'>");
	           }
	    });
	    //Open the uploader dialog
	    custom_uploader.open();
	});
});