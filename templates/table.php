<?php
	//Get all data
	$bsoft_redirect_name = $extract_these['bsoft_social_option']['name'];
	$bsoft_redirect_link = $extract_these['bsoft_social_option']['link'];
	$bsoft_image_attachment_id = $extract_these['bsoft_social_option']['icon'];
	$array_length = sizeof($bsoft_redirect_link);	
	?>
	<!-- Display all icon detail at admin site -->
	<?php
	if (!empty($bsoft_redirect_link)) {?>
		<table class="wp-list-table widefat fixed striped bsoft-social-list">
			<thead>
				<tr>
					<th>Name</th>
					<th>Link</th>
					<th>Icon</th>
				</tr>
			</thead>
				<tbody id="the-list">
				<?php
					for($i=0;$i<$array_length;$i++) {?>
						<tr data-image-id="<?php echo $bsoft_image_attachment_id[$i]; ?>">
							<td class="title column-title has-row-actions column-primary">
								<strong><?php echo $bsoft_redirect_name[$i]; ?></strong>
								<div class="row-actions">
									<span class="bsoft-edit-button" id="edit" data-id="<?php echo $i?>"><a href="javascript:;">Edit</a></span> | 
									<span class="bsoft-delete-button trash" id="delete" data-id="<?php echo $i?>"><a href="javascript:;">Trash</a></span>
								</div>
							</td>
							<td><?php echo $bsoft_redirect_link[$i]; ?></td>
							<td><?php echo "<img src=".wp_get_attachment_url( $bsoft_image_attachment_id[$i] )." height='40'>";?></td>
						</tr>
					<?php } 
	}?>
				</tbody>
		</table>  
<!-- End Wrap -->
</div>