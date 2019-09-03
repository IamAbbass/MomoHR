<?php    
    if(!isset($_COOKIE["lang"])) {		
		setcookie("lang", $lang_arr[0], time() + (86400 * 30 * 30), "/"); // 86400 = 30 day		
	}
	$selected_lang = $_COOKIE["lang"];
	if (!in_array($lang, $lang_arr)){
		$lang = $lang_arr[0];		
	}    
    if(strlen($selected_lang) == 0){
        $selected_lang = "en"; //default
    }	
	$xml = simplexml_load_file($website_url."class_function/$selected_lang/lang.xml") or die("Language file not found");
?>
