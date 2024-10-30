<?php
	// Render icons using shortcode
	function display_Bsoft_Social_Icon() {
		ob_start();
		$bsoft_redirect_name =  $bsoft_redirect_link = $bsoft_image_attachment_id = array();
		$bsoft_redirect_name = get_option('bsoft_redirect_name');
		$bsoft_redirect_link = get_option('bsoft_redirect_link');
		$bsoft_image_attachment_id = get_option('bsoft_image_attachment_id');
		$array_length = sizeof($bsoft_redirect_link);?>
		<ul class="bsoft-icon-wrap">
			<?php
			if (!empty($bsoft_redirect_link))
			{
				for($i=0;$i<$array_length;$i++){
					$key = array_search($bsoft_redirect_name[$i], $bsoft_redirect_name);?>
					<li><a href="<?php echo $bsoft_redirect_link[$i]; ?>" target="_blank"><?php echo "<img src=".wp_get_attachment_url( $bsoft_image_attachment_id[$i] )." >";?></a></li>
				<?php } 
			}	?>
		</ul>
		<?php 
		return ob_get_clean();
	}
	add_shortcode('Bsoft_Social_Icon', 'display_Bsoft_Social_Icon');
				