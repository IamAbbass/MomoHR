    <?php
    $accessKeyId = "LTAI6JHBV2B8jdXc";
    $accessKeySecret = "h7qZ6X9dG0l8qisSIFH7u01voBLPDP";
    $endpoint = "http://oss-us-east-1.aliyuncs.com";
    $bucket= "sample-images.oss-us-east-1.aliyuncs.com";
    $object = "";
    $content = "Hi, OSS.";
    
    try {
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $ossClient->putObject($bucket, $object, $content);
    } 
    catch (OssException $e) {
        print $e->getMessage();
    }
    
    ?>