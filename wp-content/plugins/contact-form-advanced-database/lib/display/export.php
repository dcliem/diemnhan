<?php 
$colKeys = array();
$cf7AdbArrHead = get_post_meta($id,'cf7-adb-data',true);	
$cf7AdbArrContent = get_post_meta($id,'cf7-adb-data');	
if(!empty($cf7AdbArrHead)){
	unset($cf7AdbArrHead['_wpcf7'],$cf7AdbArrHead['_wpcf7_version'],$cf7AdbArrHead['_wpcf7_locale'],$cf7AdbArrHead['_wpcf7_unit_tag'],$cf7AdbArrHead['_wpnonce'],$cf7AdbArrHead['_wpcf7_is_ajax_call']);
	unset($cf7AdbArrContent['_wpcf7'],$cf7AdbArrContent['_wpcf7_version'],$cf7AdbArrContent['_wpcf7_locale'],$cf7AdbArrContent['_wpcf7_unit_tag'],$cf7AdbArrContent['_wpnonce'],$cf7AdbArrContent['_wpcf7_is_ajax_call']);
	echo implode(",",array_keys($cf7AdbArrHead));
	echo "\n";
	foreach(array_values($cf7AdbArrContent) as $cf7AdbArrData){
		unset($cf7AdbArrData['_wpcf7'],$cf7AdbArrData['_wpcf7_version'],$cf7AdbArrData['_wpcf7_locale'],$cf7AdbArrData['_wpcf7_unit_tag'],$cf7AdbArrData['_wpnonce'],$cf7AdbArrData['_wpcf7_is_ajax_call']);
		echo implode(",",array_map(function($dataArr){
			if(is_array($dataArr)){
				$toReturn = '';
				foreach($dataArr as $dataArrs){
					$toReturn .= $dataArrs.' | ';	
				}
				return rtrim($toReturn," | ");
			}else{
				
				return str_replace( array( "\n", "\r", "," ) , ' ' , $dataArr);
			}
		},$cf7AdbArrData));
		echo "\n";
	}
}else{ 
	echo "No Data Available";
} ?>