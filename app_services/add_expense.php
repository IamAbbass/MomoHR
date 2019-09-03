<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    //alibaba start
    require_once('../class_function/alibaba_cloud/autoload.php');
    use OSS\OssClient;
    use OSS\Core\OssException;
    //alibaba end

    sql($DBH, "insert into tbl_debug_app(data) values (?);", array(json_encode($_REQUEST)), "rows");

    $SESS_ID        = $_POST['u_id'];
    $title          = $_POST['title'];
    $amount         = $_POST['amount'];

		$company_id = find_company_id($SESS_ID);


    //$attachment

    $arr = array();
    if($_FILES["expense_attachment"]["tmp_name"]){
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $object = "attachments/".$_FILES['expense_attachment']['name'];
        try{
            $result = $ossClient->uploadFile($bucket, $object, $_FILES['expense_attachment']['tmp_name']);

            $date_time      = time($result['date']);
            $url            = $result['info']['url'];
            $size_upload    = $result['info']['size_upload'];
            $primary_ip     = $result['info']['primary_ip'];
            $content_type   = $result['oss-requestheaders']['Content-Type'];
            sql($DBH, "insert into tbl_alibaba
            (company_id,url,content_type,size_upload,primary_ip,date_time)
            values (?,?,?,?,?,?);",
            array($company_id,$url,$content_type,$size_upload,$primary_ip,$date_time), "rows");

            $arr['error']   = "";
            $arr['status']  = true;

            sql($DBH, "INSERT into tbl_expense_refund (company_id,employee_id,title,amount,attachment,date_time) values
            (?,?,?,?,?,?)",
            array($company_id,$SESS_ID,$title,$amount,$url,time()), "rows");


            die(json_encode($arr));
        } catch(OssException $e) {
            $arr['error']   = print_r($e->getMessage(),true);
            $arr['status']  = false;
            die(json_encode($arr));
        }
    }else{//camera

        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $object = "attachments/".$_FILES['camera']['name'];
        try{
            $result = $ossClient->uploadFile($bucket, $object, $_FILES['camera']['tmp_name']);

            $date_time      = time($result['date']);
            $url            = $result['info']['url'];
            $size_upload    = $result['info']['size_upload'];
            $primary_ip     = $result['info']['primary_ip'];
            $content_type   = $result['oss-requestheaders']['Content-Type'];
            sql($DBH, "insert into tbl_alibaba
            (company_id,url,content_type,size_upload,primary_ip,date_time)
            values (?,?,?,?,?,?);",
                array($company_id,$url,$content_type,$size_upload,$primary_ip,$date_time), "rows");

            $arr['error']   = "";
            $arr['status']  = true;

						sql($DBH, "INSERT into tbl_expense_refund (company_id,employee_id,title,amount,attachment,date_time) values
            (?,?,?,?,?,?)",
            array($company_id,$SESS_ID,$title,$amount,$url,time()), "rows");


            die(json_encode($arr));
        } catch(OssException $e) {
            $arr['error']   = print_r($e->getMessage(),true);
            $arr['status']  = false;
            die(json_encode($arr));
        }
    }
?>
