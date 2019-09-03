<?php
    require_once('../class_function/session.php');
    require_once('../class_function/error.php');
    require_once('../class_function/dbconfig.php');
    require_once('../class_function/function.php');
    require_once('../class_function/language.php');
    require_once('../class_function/validate.php');

    //Ali Cloud
    require_once('../class_function/alibaba_cloud/autoload.php');
	  use  OSS \ OssClient ;
	  use OSS\Core\OSSException;

    $accessKeyId        = "LTAI6JHBV2B8jdXc";
    $accessKeySecret    = "h7qZ6X9dG0l8qisSIFH7u01voBLPDP";
    $endpoint           = "oss-us-east-1.aliyuncs.com"; //oss-cn-beijing.aliyuncs.com
    //$bucket             = "sample-images";

    try {
		    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
		} catch (OssException $e) {
		    print $e->getMessage();
		}

    $bucketListInfo = $ossClient->listBuckets();
		$bucketList = $bucketListInfo->getBucketList();
		foreach($bucketList as $bucket) {
			$signedUrl = $ossClient->signUrl($bucket->getName(), "attachments/1554461760.png", 3600);
			echo $signedUrl;
		}
?>
