<?php add_thickbox(); ?>
<div class="wrap">
	<h2>Contact Form Advanced Database</h2>
	 <div id="poststuff">
		<select id="cf7-selector">
			<?php
				$cf7Selector = $_GET['id'];
				$args = array(	'post_type'			=> 'wpcf7_contact_form',
								'posts_per_page'	=> -1,
								'orderby'			=> 'title',
								'order'				=> 'ASC');
				$cf7Query = new WP_Query( $args );
				if ( $cf7Query->have_posts() ) {
					$incSel = 0;
					while ( $cf7Query->have_posts() ) {
						$cf7Query->the_post();
						$unread_messages = get_post_meta( get_the_ID(), 'cf7-adb-data-unread', true );
						echo '<option value="'.get_the_ID().'" '.(($cf7Selector==get_the_ID())?'selected':'').'>' . get_the_title() . ' (' . intval( $unread_messages ) . ')</option>';
						if(empty($cf7Selector)){
							if($incSel==0){
								$cf7Selector = get_the_ID();
							}
							$incSel++;
						}
					}
				}
			
				wp_reset_postdata(); 
			?>
		</select>
		<?php 
			$colKeys = array();
			$cf7AdbArr = get_post_meta($cf7Selector,'cf7-adb-data', true);
			if(!empty($cf7AdbArr)):	
			//clear unread messages on this contact form	
			update_post_meta( $cf7Selector, 'cf7-adb-data-unread', 0 );	

		?>
		
		<button class="button" id="adb-delete-button">Delete</button>
		<a target="_blank"  class="button" id="adb-export" href="admin.php?page=cf7-adb-export-xls&id=<?php echo $cf7Selector; ?>">Export XLS</a>
		<table class="wp-list-table widefat fixed media paginated" id="property-lead-table" data-page-length='10'>
			<thead>
				<th class="manage-column column-cb check-column"></th>
				<?php 
				unset($cf7AdbArr['_wpcf7'],$cf7AdbArr['_wpcf7_version'],$cf7AdbArr['_wpcf7_locale'],$cf7AdbArr['_wpcf7_unit_tag'],$cf7AdbArr['_wpnonce'],$cf7AdbArr['_wpcf7_is_ajax_call']);
					foreach(array_keys($cf7AdbArr) as $cf7AdbData) {
					$colKeys[] = $cf7AdbData;
				?>
					<th class="manage-column column-title sorted">
						<span><?php echo str_replace( array( '-','_' ), ' ', $cf7AdbData ); ?></span>				
					</th>
				<?php } ?>
				<th></th>
				<th></th>
			</thead>

			<tfoot>
				<th class="manage-column column-cb check-column"></th>
				<?php foreach(array_keys($cf7AdbArr) as $cf7AdbData) {?>
					<th class="manage-column column-title sorted">
							<span><?php echo $cf7AdbData; ?></span>				
					</th>
				<?php } ?>
				<th></th>
				<th></th>
			</tfoot>
			<tbody>
				<?php 
				foreach(get_post_meta($cf7Selector,'cf7-adb-data') as $leadData){
					$cf7AdbArr = get_post_meta($cf7Selector,'cf7-adb-data',true);
					unset($cf7AdbArr['_wpcf7'],$cf7AdbArr['_wpcf7_version'],$cf7AdbArr['_wpcf7_locale'],$cf7AdbArr['_wpcf7_unit_tag'],$cf7AdbArr['_wpnonce'],$cf7AdbArr['_wpcf7_is_ajax_call']);
					$colKeys = array_keys($cf7AdbArr);
					$dataToPass = $leadData;	
					$thDiv = rand(1,1000).'_'.time().'_'.rand(1000,5000);
					unset($leadData['_wpcf7'],$leadData['_wpcf7_version'],$leadData['_wpcf7_locale'],$leadData['_wpcf7_unit_tag'],$leadData['_wpnonce'],$leadData['_wpcf7_is_ajax_call']);
				?>
					<tr>
						<td><input class="adb-chk" type="checkbox" data-status="0" data-id="<?php echo $cf7Selector; ?>" data-key="cf7-adb-data" value="<?php echo base64_encode(maybe_serialize($dataToPass)); ?>"></td>
						<?php 
							 if(count($leadData) == count($colKeys)){
							foreach($colKeys as $colKeysData){ ?>
							<td><?php 
							$stringToPrint = '';
							if(is_array($leadData[$colKeysData])){	
								foreach($leadData[$colKeysData] as $arrData){
									$stringToPrint .= ((filter_var($arrData, FILTER_VALIDATE_EMAIL))?'<a href="'.$arrData.'">'.$arrData.'</a>':$arrData).', ';	
								}
								echo rtrim($stringToPrint, ", ");
							}else{
								if(filter_var($leadData[$colKeysData], FILTER_VALIDATE_EMAIL)){
									echo '<a href="mailto:'.$leadData[$colKeysData].'">';
								}
								echo (strlen($leadData[$colKeysData]) > 60 )?substr($leadData[$colKeysData],0,60).'...':$leadData[$colKeysData]; 
								if(filter_var($leadData[$colKeysData], FILTER_VALIDATE_EMAIL)){
									echo '</a>';
								}
							}
							?></td>
						<?php }}else{ 
								foreach(array_keys($leadData) as $leadDatas){ 
								$stringToPrint = '';
								if(is_array($leadData[$leadDatas])){	
									foreach($leadData[$leadDatas] as $arrData){
										$stringToPrint .= ((filter_var($arrData, FILTER_VALIDATE_EMAIL))?'<a href="'.$arrData.'">'.$arrData.'</a>':$arrData).', ';	
									}
									echo '<td><span class="edited-entries">'.$leadDatas.'</span><br />'.rtrim($stringToPrint, ", ").'</td>'; 
								}else{
									if(filter_var($leadData[$leadDatas], FILTER_VALIDATE_EMAIL)){
									echo '<td><span class="edited-entries">'.$leadDatas.'</span><br /><a href="mailto:'.$leadData[$leadDatas].'">'.((strlen($leadData[$leadDatas]) > 60 )?substr($leadData[$leadDatas],0,60).'...':$leadData[$leadDatas]).'</a></td>';
									}else{
										echo '<td><span class="edited-entries">'.$leadDatas.'</span><br />'.((strlen($leadData[$leadDatas]) > 60 )?substr($leadData[$leadDatas],0,60).'...':$leadData[$leadDatas]).'</td>';
									}
										
								}
							}}	

						?>
						<td>
						<div id="<?php echo $thDiv; ?>" style="display:none;">
							 <div>
								  <?php 
								  if(count($leadData) == count($colKeys)){
								  foreach($colKeys as $colKeysData){
								  ?>
									<div class="adb-per-line">
										<div class="field-name"><?php echo str_replace( array( '-','_' ), ' ', strtoupper( $colKeysData ) ); ?></div>
										<div class="field-value"><?php 
											if(is_array($leadData[$colKeysData])){
												foreach($leadData[$colKeysData] as $arrData){
													echo ((filter_var($arrData, FILTER_VALIDATE_EMAIL))?'<a href="'.$arrData.'">'.$arrData.'</a>':$arrData).'<br />';
												} 
											}else{
												echo ((filter_var($leadData[$colKeysData], FILTER_VALIDATE_EMAIL))?'<a href="'.$leadData[$colKeysData].'">'.$leadData[$colKeysData].'</a>':$leadData[$colKeysData]).'<br />';
											}
										
										?></div>
									</div>
								 <?php }}else{ ?>
								  <?php
									
									foreach(array_keys($leadData) as $leadDatas){ ?>
										<div class="adb-per-line">
											<div class="field-name"><?php echo strtoupper($leadDatas) ?></div>
											<div class="field-value"><?php 
												if(is_array($leadData[$leadDatas])){
													foreach($leadData[$leadDatas] as $arrData){
														echo ((filter_var($arrData, FILTER_VALIDATE_EMAIL))?'<a href="'.$arrData.'">'.$arrData.'</a>':$arrData).'<br />';
													}
												}else{
													echo ((filter_var($leadData[$leadDatas], FILTER_VALIDATE_EMAIL))?'<a href="'.$leadData[$leadDatas].'">'.$leadData[$leadDatas].'</a>':$leadData[$leadDatas]).'<br />';
													
												}
											?></div>
										</div>
								 
								 <?php }}  ?>
							 </div>

						</div>
						<a href="#TB_inline?width=600&height=550&inlineId=<?php echo $thDiv; ?>" title="Contact Form fields and value" class="view-button thickbox">View</a></td>
						<td>
							<span class="del-button" data-id="<?php echo $cf7Selector; ?>" data-key="cf7-adb-data" data-val="<?php echo base64_encode(maybe_serialize($dataToPass)); ?>">Delete</span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table> 	
		<?php else: ?>
			<div id="no-data">No Data Available</div>
		<?php endif; ?>	
		<div id="loading-panel">
			<img class="loader" src="<?php echo CF7ADBURL.'/lib/images/loader.gif' ?>" />
		</div>
	</div>
</div>

