<?php    
    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
    require_once('../../class_function/language.php');
    
    $rows = sql($DBH, "SELECT settings_updated, settings_applied FROM tbl_blocking_option where device_id = ?", array($SESS_DEVICE_ID), "rows");							
    foreach($rows as $row){
        $settings_updated   = $row['settings_updated'];
        $settings_applied   = $row['settings_applied'];
        
        if($settings_updated > 0){
            $settings_updated = my_simple_date($settings_updated);
        }else{
            $settings_updated = "Never Updated";
        }
        
        if($settings_applied > 0){
            $settings_applied = "<span><i class='fa fa-check'></i> Settings Applied <small>(".my_simple_date($settings_applied).")</small></span>";
        }else{
            $settings_applied = "<span><i class='fa fa-times'></i> Not Applied Yet</span>";
        }        
    }
?>    

<small class="">
    (Last Updated: <strong><?php echo $settings_updated; ?></strong>
    &bull;
    Settings Applied In App: <strong><?php echo $settings_applied; ?></strong>)
</small>