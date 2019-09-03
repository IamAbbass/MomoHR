<?php


    require_once('../class_function/session.php');



    //require_once('../class_function/error.php');



    require_once('../class_function/dbconfig.php');



    require_once('../class_function/function.php');



    require_once('../class_function/validate.php');



    require_once('../class_function/language.php');



    require_once('../page/title.php');



    require_once('../page/meta.php');



    require_once('../page/header.php');



    require_once('../page/menu.php');



    require_once('../page/footer.php');

    //alibaba start

    require_once('../class_function/alibaba_cloud/autoload.php');

    use OSS\OssClient;

    use OSS\Core\OssException;  
    //alibaba end   

    



    



    $users_contacts_xml          = $xml->contacts->users_contacts;



    $last_synced_xml             = $xml->contacts->last_synced;



    $never_synced_xml            = $xml->contacts->never_synced;



    $search_colon_xml            = $xml->contacts->search_colon;



    $display_name_xml            = $xml->contacts->display_name;



    $phone_number_xml            = $xml->contacts->phone_number;



    $no_data_xml                 = $xml->contacts->no_data;



    $display_name_xml            = $xml->contacts->display_name;



    $display_name_xml            = $xml->contacts->display_name;



    $no_number_xml               = $xml->contacts->no_number;



    $access_denied_xml           = $xml->contacts->access_denied;



    $no_access_xml               = $xml->contacts->no_access;



        



    



    if($SESS_ACCESS_LEVEL == "root admin"){



        //allow



    }else if($SESS_ACCESS_LEVEL == "admin"){



        if($perm['perm_company'] != "true"){



            $_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";



            redirect('index.php');



        }



    }else if($SESS_ACCESS_LEVEL == "user"){



        $_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";



        redirect('index.php');



    }else{



        $_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";



        redirect('index.php');



    }

    $admin_id = $_SESSION['SESS_ID'];

    $employee_id = $_GET['id'];

    $current_page_full_path = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // EMPLOYEE PROFILE

    /*
    functi on get_employee_login_info($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_login where id = ?",  array($employee_id), "rows");  
        return $data;
    }

    function get_employee_profile_info($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_employee_profile where employee_id = ?",  array($employee_id), "rows");  
        return $data;
    }
    */
/*
    function get_employee_default_img($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT photo FROM tbl_login where id = ?",  array($employee_id), "rows");  
        return $data[0]['photo'];
    }
*/

    //$employee_login_data =  get_employee_login_info($employee_id)[0];

    //$employee_profile_data = get_employee_profile_info($employee_id)[0];

    //$employee_default_img = get_employee_default_img($employee_id);

    $rows = sql($DBH, "SELECT * FROM tbl_employee_profile where employee_id = ?",  array($employee_id), "rows");  

    if (empty($rows)) {
        sql($DBH, "insert into tbl_employee_profile (parent_id,employee_id) values (?,?);",array($admin_id,$employee_id), "rows");          
    }

    $radio_male_checked = '';
    $radio_female_checked = '';
    if ($employee_profile_data['gender'] == 'Male') {
        $radio_male_checked = 'checked';
    }
    else{
        $radio_female_checked = 'checked';
    }



    // ---------------------------------- DOCUMENTS ------------------------------------ //


    function get_employee_documents($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_documents where employee_id = ? And status = '1' ",  array($employee_id), "rows");  
        return $data;
    }

    // function add_employee_document($employee_id,$file_url,$admin_id,$file_name,$notes)
    // {
    //     global $DBH;
    //     $dataArray = array($admin_id,$employee_id,$file_name,$notes,$file_url,strtotime(date('m/d/Y')),'1');
    //     $data =  sql($DBH, "insert into tbl_documents (parent_id,employee_id,filename,notes,file_path,uploaded_at,status) values (?,?,?,?,?,?,?);",$dataArray, "rows");        
    //     return $data;
        
    // }

    // function update_documents($document_id)
    // {    
       //  global $DBH,$accessKeyId,$accessKeySecret,$endpoint,$bucket;
    //  if ($_FILES['file_edited_document']['size'] !== 0) {
    //      $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

    //         $object = "attachments/".$_FILES['file_edited_document']['name'];         

    //         try{

    //             $result = $ossClient->uploadFile($bucket, $object, $_FILES['file_edited_document']['tmp_name']);
    //             $file_url            = $result['info']['url'];
    //             // die(json_encode($arr));
    //         } catch(OssException $e) {
    //             $arr['error']   = print_r($e->getMessage(),true);
    //             die(json_encode($arr));
    //         }   
    //  }
    //  else{
    //      $file_url = $_POST['txt_file_pervious_path'];
    //  }
    //  // echo $file_url;
    //  $dataArray =  array($_POST['txt_edited_document_file_name'],$_POST['txt_edited_document_notes'],strtotime(date('m/d/Y')),$file_url,$document_id);
    //   $data =   sql($DBH, "UPDATE tbl_documents set filename = ?,notes = ?,updated_at =?, file_path = ?  where id =? ",$dataArray, "rows");  
    //   if ($data) {
    //      return true;
    //   }
    // }

    
    $employee_documents = get_employee_documents($employee_id);
    // if (isset($_POST['btn_add_document'])) {   

    //    $file_name = $_POST['txt_document_file_name'];
    //     $notes = $_POST['txt_document_notes'];

    //      $arr= array();
    //     if($_FILES["file_document"]["tmp_name"]){

    //         $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

    //         $object = "attachments/".$_FILES['file_document']['name'];         

    //         try{

    //             $result = $ossClient->uploadFile($bucket, $object, $_FILES['file_document']['tmp_name']);
    //             $file_url            = $result['info']['url'];
    //             // print_r($file_url);
    //             $asd =  add_employee_document($employee_id,$file_url,$admin_id,$file_name,$notes);
    //             // die(json_encode($arr));
    //         } catch(OssException $e) {
    //             $arr['error']   = print_r($e->getMessage(),true);
    //             die(json_encode($arr));
    //         }       

    //     }
    //     //echo "<pre>";
    //     //print_r($asd);
    //     //echo "</pre>";
    //     // $result = add_employee_document($employee_id,'txt_document_notes');
      
    // }
        
    // if (isset($_POST['btn_edit_document'])) {
    //  update_documents($_POST['txt_file_document_id']);
    //   }  

    // ---------------------------------- ASSETS ------------------------------------ //

    function get_employee_assets($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_assets where employee_id = ? ",  array($employee_id), "rows");  
        return $data;
    }

    function add_employee_assets($employee_id,$admin_id)
    {
        global $DBH;
        $assets_name = $_POST['txt_assets_name'];
        $assets_category = $_POST['txt_assets_category'];
        $assets_textarea = $_POST['txt_assets_textarea'];
        $assets_serial = $_POST['txt_assets_serial'];
        $assets_given_date = strtotime($_POST['txt_assets_given_date']);

        $dataArray = array($admin_id,$employee_id,$assets_name,$assets_category,$assets_textarea,$assets_serial,$assets_given_date,"0");
        $data =  sql($DBH, "insert into tbl_assets (parent_id,employee_id,assets_name,category,description,serial,date_given,assets_return) values (?,?,?,?,?,?,?,?);",$dataArray, "rows");        
        return $data;
    }

    if (isset($_POST['btn_add_assets'])) {
        $asd =  add_employee_assets($employee_id,$admin_id);
        if ($asd) {
            echo "YES";
        }
    }


    // ---------------------------------- COMPENSATION ------------------------------------ //

    function add_compensation($employee_id,$admin_id)
    {
        global $DBH;    
        $full_name =    $_POST['txt_compensation_full_name'];
        $amount     =   $_POST['txt_compensation_amount'];
        $category  =    $_POST['txt_compensation_category'];

        $dataArray = array($admin_id,$employee_id,$full_name,$amount,$category,strtotime(date('m/d/Y')),'1');
        $data =  sql($DBH, "insert into tbl_compensation (parent_id,employee_id,full_name,amount,category,date,status) values (?,?,?,?,?,?,?);",$dataArray, "rows"); 
        if ($data) {
            return true;
         } 
    }

    function get_employee_compensation($employee_id)
    {
        global $DBH;
        $dataArray =  array($employee_id);
        $data = sql($DBH,'select * from tbl_compensation where employee_id = ? and status = 1 ',$dataArray,'rows');
        if ($data) {
            return $data;
        }
    }

    function update_employee_compensation($compensation_id)
    {
        global $DBH;
        $full_name =  $_POST['txt_edit_compensation_full_name'];
        $amount =  $_POST['txt_edit_compensation_amount'];
        $category =  $_POST['txt_edit_compensation_category'];

        $dataArray = array($full_name,$amount,$category,$compensation_id);
        $data = sql($DBH,'UPDATE tbl_compensation set full_name = ?, amount =?, category = ? where id = ? ',$dataArray,'rows');
        if ($data) {
            return true;
        }
    }
    if (isset($_POST['btn_add_compensation'])) {
        $result = add_compensation($employee_id,$admin_id);
    }
    
    if (isset($_POST['btn_edit_compensation'])) {

        $result =  update_employee_compensation($_POST['txt_edit_compensation_id']);
    }

   

?>



<!DOCTYPE html>



<html lang="en">



    <!--<![endif]-->



    <!-- BEGIN HEAD -->



    <head>



        <?php



            echo $title;



            echo $meta;



            echo $favicon;



        ?>



        <!-- BEGIN GLOBAL MANDATORY STYLES -->



        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />



        <!-- END GLOBAL MANDATORY STYLES -->



        <!-- BEGIN PAGE LEVEL PLUGINS -->



        <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />



        



        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />



        <!-- END PAGE LEVEL PLUGINS -->



        <!-- BEGIN THEME GLOBAL STYLES -->



        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />



        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />



        <!-- END THEME GLOBAL STYLES -->



        <!-- BEGIN THEME LAYOUT STYLES -->



        <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />



        <link href="assets/layouts/layout/css/themes/grey.min.css" rel="stylesheet" type="text/css" id="style_color" />



        <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />



        <!-- END THEME LAYOUT STYLES -->



        <style>



            .small_pic{



                width:100%;



                border-radius:100%;



            }



            .refresh_btn{



                padding: 22px !important;



                font-size: 18px !important;



            }     



            .list-title{



                



            }



            .no-margin{



                margin: 0 !important;



            }  



            .store_logo, .mt-element-list .list-news.ext-1 .list-thumb{



                width:60px !important;



                height:60px !important;



            } 



            .mt-widget-1{



                border:none;



            }



            .user-dp{



                width:50px; 



                border-radius:100% !important;



                padding:3px;



                background:#eee;



                border:1px solid #ccc;



            }



            #reportrange span{



                display:none;



            }

            .company-employee{

                margin: auto;

                display: block;

                color:black;

                margin-top: 20px

            }

            .company-employee:hover{

               background-color: #f9f9f9 !important;

               text-decoration: none;

               color: black

                /*display: inline-block;*/

                /*padding: 15px;*/

            }

            .company-employee img{

                border-radius: 100% !important;

                width:130px;

            }

            .company-employee .name-text{

                font-size: 13px;

                font-weight: bold;

            }

            .company-employee .position-text{

                font-size: 13px

            }
            ul.nav.nav-tabs.tabs-left li a{
                color: black !important
            }
            ul.nav.nav-tabs.tabs-left li a:hover{
                background-color: transparent  !important;
                border-right: none !important;
            }
            ul.nav.nav-tabs.tabs-left li a:focus{
                background-color: white !important     ;
                border: none !important;
            }
            .employee-picture{
                position: relative;
            }
            .employee-picture img{
                border-radius: 100% !important;
            }
            .file-btn-main{
                background-color: #31c774;
                color: white;
                padding: 10px 11px;
                margin-bottom: 10px;
                cursor: pointer;
                border-radius: 100% !important;
                position: absolute;
                right: 0px;
                top: 0px;
                width: 35px;
                height: 35px;
            }
            .form-group.form-md-line-input{
                margin: 0 0 20px !important
            }
            .tab-employee-img img{
                border-radius: 100% !important;
                width: 60px;
                height: 60px;
                margin-left: 10px;
                margin-bottom: 15px;
            }
            .tab-employee-img span{
                font-weight: bold;
            }
            table.table-assets tbody a{

            padding: 2px 10px;
            color: gray;
            border: 1px solid gray;
            }
            input#file-input{
                display: none !important;
            }
            span.sick-orange{
                    color: #d5761b;
                    display: block;
            }
            span.sick-blue{
                color: #3a7dd8;
                display: block;
            }
            span.sick-green{
                color: #1A9211;
                display: block;
            }

             .document-upload > input
            {
                display: none;
            }

            .document-upload img
            {
                width: 80px;
                cursor: pointer;
            }
            .file-btn-documents{
                background-color: #31c774;
                color: white;
                padding: 8px 12px;
                margin-bottom: 10px;
                cursor: pointer;
            }
            .modal-loading {
                background: white;
                position: absolute;
                z-index: 999;
                height: 100%;
                width: 100%;
                justify-content: center;
                vertical-align: middle;
                display: flex;
                align-items: center;
            }
            .modal-loading i{
                display: inline-table;
                font-size: 35px;
            }
            .table-documents-actions button{
                background: transparent;
                border: none;
                color: #e82d2d
            }
        </style>
    </head>



    <!-- END HEAD -->







    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">



        <div class="page-wrapper">



            <!-- BEGIN HEADER -->



<?php



    echo $header;



?>



            <!-- END HEADER -->



            <!-- BEGIN HEADER & CONTENT DIVIDER -->



            <div class="clearfix"> </div>



            <!-- END HEADER & CONTENT DIVIDER -->



            <!-- BEGIN CONTAINER -->



            <div class="page-container">



                <!-- BEGIN SIDEBAR -->



<?php



    echo $menu;



?>



                <!-- END SIDEBAR -->



                <!-- BEGIN CONTENT -->



                <div class="page-content-wrapper">

                    

                    <!-- BEGIN CONTENT BODY -->



                    <div class="page-content">
                        <!-- END PAGE HEADER-->



                        



                        <?php


                            if($_SESSION['msg']){        



                                $error = "<div class='note note-danger remove_after_5'><p>".$_SESSION['msg']."</p></div>";  



                                unset($_SESSION['msg']);



                            }else if($_SESSION['info']){        



                                $error = "<div class='note note-success remove_after_5'><p>".$_SESSION['info']."</p></div>";    



                                unset($_SESSION['info']);



                            }else{



                                $error = "";  



                            }



                            



                            echo $error;



                        ?>
                        <?php 
                        if (isset($success_msg)) {
                         ?>
                        <div class='note note-success remove_after_5'><p><?= $success_msg ?></p></div>
                        <?php
                            }

                            if (isset($error_msg)) {
                                ?>
                                    <div class='note note-success remove_after_5'><p><?= $error_msg ?></p></div>    
                                <?php
                            }
                        ?>

                        <div class="row">                           



                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                <div class="col-md-6">
                                    <h3 class="text-center">Sick - UK</h3>
                                        <div class="portlet-body">
                                            <div style="width: 100%;height: 300px" id="morris_sickday"></div>
                                        </div>
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <form method="post" action="employee_form_submit.php">
                                                <div class="input-group">
                                                    <div class="input-group-control">
                                                        <input name="txt_sick" type="text" class="form-control" value="<?= get_sick_days() ?>">
                                                        <label for="form_control_1">Sick days</label>
                                                    </div>
                                                    <span class="input-group-btn btn-right">
                                                        <button type="submit" name="btn_update_sick" class="btn green-haze"> Update sick leaves 
                                                        </button>
                                                        
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="text-center">Vacation - UK</h3>
                                        <div class="portlet-body">
                                            <div style="width: 100%;height: 300px" id="morris_vacation"></div>
                                        </div>
                                        <div class="form-group form-md-line-input form-md-floating-label">  
                                            <form method="post" action="employee_form_submit.php">
                                                <div class="input-group">
                                                    <div class="input-group-control">
                                                        <input type="text" name="txt_vacations" class="form-control" value="<?= get_vacation_days() ?>">
                                                        <label for="form_control_1">Vacations</label>
                                                    </div>
                                                    <span class="input-group-btn btn-right">
                                                        <button type="submit" name="btn_update_vacations" class="btn green-haze"> Update Vacation leaves 
                                                        </button>
                                                        
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                </div>

                                <!-- profile start -->

                                

                                

                                <div class="portlet-body">
                                  <div class="row">
                   
                                </div>
                    </div>
                </div>
            </div>

                                

                                                                

                                <!-- profile end -->

                            </div>



                    </div>



                    <!-- END CONTENT BODY -->



                </div>



                <!-- END CONTENT -->



            </div>
            <!-- MODALS -->
            <div class="modal fade" id="modal_compensation" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Modal Title</h4>
                        </div>
                        <div class="modal-body"> 
                            <form role="form" method="post">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input class="form-control" name="txt_compensation_full_name" id="form_control_1" placeholder="">
                                        <label for="form_control_1">Full name</label>
                                        <!-- <span class="help-block">Some help goes here...</span> -->
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="number" class="form-control" name="txt_compensation_amount" id="form_control_1" value="0">
                                        <label for="form_control_1">Amount</label>
                                        <!-- <span class="help-block">Some help goes here...</span> -->
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control" name="txt_compensation_category">
                                            <option value="Category 1">Category 1</option>
                                            <option value="Category 2">Category 2</option>
                                            <option value="Category 3">Category 3</option>

                                        </select>
                                        <label for="form_control_1">Category</label>
                                    </div>
                                </div>

                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="submit" name="btn_add_compensation" class="btn green">Add Compensation</button>
                             
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            <!-- END CONTAINER -->



            <!-- BEGIN FOOTER -->



<?php



    echo $footer;



?>



            <!-- END FOOTER -->



        </div>



        <!--[if lt IE 9]>



<script src="assets/global/plugins/respond.min.js"></script>



<script src="assets/global/plugins/excanvas.min.js"></script> 



<script src="assets/global/plugins/ie8.fix.min.js"></script> 



<![endif]-->



        <!-- BEGIN CORE PLUGINS -->



        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>



        <!-- END CORE PLUGINS -->



        <!-- BEGIN PAGE LEVEL PLUGINS -->



        <script src="assets/global/scripts/datatable.js" type="text/javascript"></script>



        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>



        



        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>



        <!-- END PAGE LEVEL PLUGINS -->



        <!-- BEGIN THEME GLOBAL SCRIPTS -->


        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>

        <!-- <script src="assets/pages/scripts/charts-morris.min.js" type="text/javascript"></script> -->
        <!-- END THEME GLOBAL SCRIPTS -->



        <!-- BEGIN PAGE LEVEL SCRIPTS -->



        <script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>



        <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>



        <!-- END PAGE LEVEL SCRIPTS -->



        <!-- BEGIN THEME LAYOUT SCRIPTS -->



        <script src="assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>



        <script src="assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>



        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>



        <script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                  Morris.Donut({

                     element: 'morris_sickday',
                        data: [
                          { label: "Total sick allowed", value: <?= get_sick_days() ?> },
                          { label: "Total sick allowed", value: <?= get_sick_days() ?> }
                        ]
                     });
                     Morris.Donut({
                        element: 'morris_vacation',
                        data: [
                          { label: "Total Vacations allowed", value: <?= get_vacation_days() ?> },
                          { label: "Total Vacations allowed", value: <?= get_vacation_days() ?> }
                        ]
                     });
             });
        </script>

        <!-- END THEME LAYOUT SCRIPTS -->        



    </body>







</html>