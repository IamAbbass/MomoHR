<?php

		require_once('../class_function/language.php');



		$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where access_level = ?;");

        $result  		= $STH->execute(array("admin"));

        $count_admins	= $STH->fetchColumn();



		$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where access_level = ? AND company_id = ?;");

        $result  		= $STH->execute(array("user", $SESS_ID));

        $count_users	= $STH->fetchColumn();



		$STH 			= $DBH->prepare("select count(*) FROM tbl_message where msg_to = ? and mark_read_by_receiver = ?");

		$result 	   	= $STH->execute(array($SESS_ID,"false"));

		$message_count  = $STH->fetchColumn();



		$dashboard             = $xml->left_menu->dashboard;

        $admininstrators       = "Admininstrators"; //$xml->left_menu->admininstrators;

        $my_users              = $xml->left_menu->my_users;

        $contacts              = $xml->left_menu->contacts;

        $call_logs             = $xml->left_menu->call_logs;

        $text_messages         = $xml->left_menu->text_messages;

        $location              = $xml->left_menu->location;

        $browser_history       = $xml->left_menu->browser_history;

        $installed_apps        = $xml->left_menu->installed_apps;

        $attendance            = $xml->left_menu->attendance;

        $block_website         = $xml->left_menu->block_website;

        $videos                = $xml->left_menu->videos;

        $photos                = $xml->left_menu->photos;

        $viber                 = $xml->left_menu->viber;

        $device_management     = $xml->left_menu->device_management;

        $message_section       = $xml->left_menu->message_section;

        $remote_device_lock    = $xml->left_menu->remote_device_lock;





		$menu_head = "<div class='page-sidebar-wrapper'>

                    <div class='page-sidebar navbar-collapse collapse'>

                        <ul class='page-sidebar-menu  page-header-fixed ' data-keep-expanded='false' data-auto-scroll='true' data-slide-speed='200'>

                            <li class='sidebar-toggler-wrapper hide'>

                                <div class='sidebar-toggler'>

                                    <span></span>

                                </div>

                            </li>

							<li class='nav-item start'>

                                <a href='index.php' class='nav-link nav-toggle'>

                                    <i class='icon-home'></i>

                                    <span class='title'>$dashboard</span>

                                </a>

                            </li>";





							if($SESS_ACCESS_LEVEL == "root admin"){

								$menu_body .= "<li class='nav-item'>

								<a href='list_admins.php' class='nav-link nav-toggle'><i class='icon-user-following'></i>

								<span class='title'>$admininstrators <span class='badge badge-default'>$count_admins</span> </span></a></li>";

				            }


                            /*
							if($SESS_ACCESS_LEVEL == "root admin" || $SESS_ACCESS_LEVEL == "admin"){

								$menu_body .= "<li class='nav-item'>

								<a href='list_users.php' class='nav-link nav-toggle'><i class='icon-user'></i>

								<span class='title'>$my_users <span class='badge badge-default'>$count_users</span> </span></a></li>";

							}
                            */



                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_calendar'] == "true")){

							 $menu_body .= "<li class='nav-item'><a href='calendar.php' class='nav-link nav-toggle'>

							 <i class='fa fa-calendar'></i><span class='title'>Calendar</span></a></li>";

							}



                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_company'] == "true")){

							 $menu_body .= "<li class='nav-item'><a href='company.php' class='nav-link nav-toggle'>

							 <i class='fa fa-briefcase'></i><span class='title'>Company</span></a></li>";

							}



                             if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_reports'] == "true")){

							 $menu_body .= "<li class='nav-item'><a href='reports.php' class='nav-link nav-toggle'>

							 <i class='fa fa-file'></i><span class='title'>Reports</span></a>
                             </li>";
                               //<ul class='sub-menu'>";
                               // if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_contacts'] == "true")){

							//$menu_body .= "<li class='nav-item'><a href='reports.php' class='nav-link'>

							//<i class='fa fa-briefcase'></i><span class='title'> Compensation</span></a></li>";

							}



						/*	if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_call_logs'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='reports.php#tab_time_off' class='nav-link'>

							<i class='fa fa-folder-open-o'></i><span class='title'> Time Off</span></a></li>";

							}



							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_text_msgs'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='reports.php#tab_expense' class='nav-link'>

							<i class='fa fa-money'></i><span class='title'> Expense</span></a></li>";

							}*/



							/*if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_location'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='location.php' class='nav-link'>

							<i class='fa fa-map-marker'></i><span class='title'> $location</span></a></li>";

							}*/


                            /*
							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_app_list'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='reports.php#tab_screenshots' class='nav-link'>

							<i class='fa fa-picture-o'></i><span class='title'> Screenshots</span></a></li>";

                            }*/


                            /*
							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_attendance'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='attendance.php' class='nav-link'>

							<i class='fa fa-thumbs-up'></i><span class='title'> $attendance</span></a></li>";

                            }*/

                            /*

                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_videos'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='reports.php#tab_attendance' class='nav-link'>

							<i class='fa fa-clock-o'></i><span class='title'> Attendance</span></a></li>";

                            }



                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_photos'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='reports.php#tab_locations' class='nav-link'>

							<i class='fa fa-map-marker'></i><span class='title'> Location</span></a></li>";

                            }


                            //phone sub menu foot start

                            $menu_body .= "</ul>

                            </li>";*/


							//}



                            //phone sub menu head start

                            $menu_body .= "<li class='nav-item hidden'>

                                <a href='javascript:;' class='nav-link nav-toggle'>

                                    <i class='fa fa-phone'></i><span class='title'>Phone</span>

                                    <span class='arrow'></span>

                                </a>

                                <ul class='sub-menu'>";

                             //phone sub menu head end



							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_contacts'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='contacts.php' class='nav-link'>

							<i class='fa fa-book'></i><span class='title'> $contacts</span></a></li>";

							}



							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_call_logs'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='call_logs.php' class='nav-link'>

							<i class='fa fa-phone'></i><span class='title'> $call_logs</span></a></li>";

							}



							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_text_msgs'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='text_msgs.php' class='nav-link'>

							<i class='fa fa-envelope'></i><span class='title'> $text_messages</span></a></li>";

							}



							/*if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_location'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='location.php' class='nav-link'>

							<i class='fa fa-map-marker'></i><span class='title'> $location</span></a></li>";

							}*/



							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_app_list'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='installed_apps.php' class='nav-link'>

							<i class='fa fa-android'></i><span class='title'> $installed_apps</span></a></li>";

                            }


                            /*
							if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_attendance'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='attendance.php' class='nav-link'>

							<i class='fa fa-thumbs-up'></i><span class='title'> $attendance</span></a></li>";

                            }*/



                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_videos'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='videos.php' class='nav-link'>

							<i class='fa fa-video-camera'></i><span class='title'> $videos</span></a></li>";

                            }



                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_photos'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='photos.php' class='nav-link'>

							<i class='fa fa-camera'></i><span class='title'> $photos</span></a></li>";

                            }



                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_device_management'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='device_manage.php' class='nav-link'>

							<i class='fa fa-cog'></i><span class='title'> $device_management</span></a></li>";

                            }



                            //phone sub menu foot start

                            $menu_body .= "</ul>

                            </li>";

                            //phone sub menu foot end



                            //if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_message_section'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='message_sec.php' class='nav-link nav-toggle'>

							<i class='fa fa-envelope-square'></i><span class='title'>$message_section <span id='message_count' style='display:none' class='badge badge-danger'>$message_count</span></span></a></li>";

                            //}
                            
                            $menu_body .= "<li class='nav-item'><a href='setting.php' class='nav-link nav-toggle'>

							<i class='fa fa-gear'></i><span class='title'>Settings </span></a></li>";



                            /*
                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_expense_refund'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='expense_refund.php' class='nav-link nav-toggle'>

							<i class='fa fa-money'></i><span class='title'>Expense</span></a></li>";

                            }   */


                            /*
                            if($SESS_ACCESS_LEVEL == "root admin" || ($SESS_ACCESS_LEVEL == "admin" && $perm['perm_screenshot'] == "true")){

							$menu_body .= "<li class='nav-item'><a href='screenshots.php' class='nav-link nav-toggle'>

							<i class='fa fa-picture-o'></i><span class='title'>Screenshots</span></a></li>";

                            } */

                            /*
							if($SESS_ACCESS_LEVEL == "root admin" || $SESS_ACCESS_LEVEL == "admin" ){

							$menu_body .= "<li class='nav-item'><a href='leaves.php' class='nav-link nav-toggle'>

							<i class='fa fa-suitcase'></i><span class='title'>Manage leaves and Vacations</span></a></li>";

                            }
                            */

                            /*

                            if($SESS_ACCESS_LEVEL == "root admin" || $SESS_ACCESS_LEVEL == "admin" ){

							$menu_body .= "<li class='nav-item'><a href='upcoming-salary.php' class='nav-link nav-toggle'>

							<i class='fa fa-money'></i><span class='title'>Upcoming Salary</span></a></li>";

                            }
                            */





                        $menu_foot ="</ul>

                    </div>

                </div>";



    //user access now



    $menu .= $menu_head;

    $menu .= $menu_body;

    $menu .= $menu_foot;

?>


<script>

window.onload = function() {

    var current_url = "<?php echo basename($_SERVER['PHP_SELF']); ?>";
		if(current_url == "employee-profile.php"){
			  current_url = "company.php";
		}


    try{
    if($("a[href='"+current_url+"']").parent().parent().hasClass("sub-menu")){

        //add color on parent

        $("a[href='"+current_url+"']").parent().parent().parent().addClass("active");

        //arrow down

        $("a[href='"+current_url+"']").parent().parent().siblings("a").children(".arrow").addClass("open");

        //cut triangle

        $("a[href='"+current_url+"']").parent().parent().siblings("a").append("<span class='selected'></span>");

        //highlight child

        $("a[href='"+current_url+"']").parent().addClass("active");

    }
    }catch(e){}


    try{
    if($("a[href='"+current_url+"']").parent().parent().hasClass("page-sidebar-menu")){

        //is parent

        $("a[href='"+current_url+"']").parent().addClass("active");

        $("a[href='"+current_url+"']").append("<span class='selected'></span>");

    }
    }catch(e){}



	function count_noti(){
        try{
		$.get("../admin/count_noti.php",function(data){

			json   				= $.parseJSON(data);

			var message_count   = json.message_count;

			if(message_count > 0){

				$("#message_count").text(message_count).slideDown();

			}else{

				$("#message_count").slideUp();

			}

			setTimeout(function(){

				count_noti();

			},4000);

		});
        }catch(e){}
	}

	count_noti();


    try{
	if($("#message_count").text() == "0"){

		$("#message_count").hide();

	}else{

		$("#message_count").show();

	}
    }catch(e){}


}



</script>
