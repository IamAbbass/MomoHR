<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
</head>
<body>
	<div class="container">
		<h1>TESING 2</h1>
		<div id="curr_version"></div>
		<div class="alert alert-info" id="updates">
			<p><b>Update Available</b></p>
			
		</div>
		<div id="dwn-update"></div>
	</div>
</body>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  <script type="text/javascript" src="crypto.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		var CryptoJSAesJson = {
		    stringify: function (cipherParams) {
		        var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
		        if (cipherParams.iv) j.iv = cipherParams.iv.toString();
		        if (cipherParams.salt) j.s = cipherParams.salt.toString();
		        return JSON.stringify(j);
		    },
		    parse: function (jsonStr) {
		        var j = JSON.parse(jsonStr);
		        var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
		        if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
		        if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)
		        return cipherParams;
		    }
		}
		
		var encrypted = CryptoJS.AES.encrypt(JSON.stringify("true"), "key@zeddevelopers.com", {format: CryptoJSAesJson}).toString();
		 $prev_version_id = localStorage.getItem('version_id');
		 if ($prev_version_id == 'undefined' || $prev_version_id == null) {
		 	$prev_version_id = '0';

		 }
		  $('#curr_version').html('Current Version ' + $prev_version_id);
		 status_to_extract = localStorage.getItem('readyToExtract') || false;

		if (status_to_extract == 'true') {
			$('#dwn-update').append('<a style="cursor:pointer" class="btn-update">Click here to update</a> ');
			updateFiles();
		}

		// alert($prev_version_id);
			// setTimeout(function() {
				$.ajax({
					method:'POST',
					url:'https://zeddevelopers.com/@beta/app/Hamza-Testing/ajax_check_updates.php',
					data:{checkUpdates:true,encrypted_text:encrypted},
					success:function(response) {
					$json_data =	JSON.parse(response);
					console.log($json_data);
					$version_date = $json_data.version_date;
					$file_url 	= $json_data.file_url;
					// alert(response);
					// console.log($json_data);
						if ($json_data != null) {
							$new_version_id = $json_data.id;

							if ($prev_version_id != null || $prev_version_id != undefined) {
								if ($new_version_id  >  $prev_version_id) {
									// alert('updates available if');
									$('#updates').append('<a href="#" class="btn-download-update" data-version-id='+$new_version_id+' data-file-url='+$file_url+'>Click here to Update</a><p>Date: '+$version_date+'</p>');
									downloadUpdate();
								}
							}
							else{	

								$('#updates').append('<a href="#" class="btn-download-update" data-version-id='+$new_version_id+' data-file-url='+$file_url+'>Click here to Update</a><p>Date: '+$version_date+'</p>');
								downloadUpdate();
							}
						}
						else{
							alert("json data null");
						}
					}
				})
			// }, 10000);
			// function updateData() {
			// 	$('a.btn-download-update').click(function () {
			// 		$version_id = $(this).attr('data-version-id');
			// 		$file_url  = $(this).attr('data-file-url');
			// 		x = confirm('Are you sure you want to update?');
			// 		if (x == true) {
			// 			$.ajax({
			// 				method:'POST',
			// 				url:'updates.php',
			// 				data:{updateData:true,file_url:$file_url,version_id:$version_id},
			// 				success:function(response) {
			// 					alert(response);
			// 				}
			// 			});
			// 		}
			// 	})
			// }
			function updateFiles() {
				$('a.btn-update').click(function () {
					$versionId = $(this).attr('data-version-id');
					// alert($versionId);
					$.ajax({
						method:'POST',
						url:'updates.php',
						data:{updateData:true},
						success:function(response) {
							alert('extracted, refreshing your page...');
							document.location.reload();
							$('#dwn-update').append('<a class="btn-update">Click here to update</a> ');
							localStorage.setItem('version_id',$versionId);
							localStorage.setItem('readyToExtract',false);
						}
					})	
				})
				
			}
			function downloadUpdate() {
				$dwnUpdate = $('#dwn-update');
				$('a.btn-download-update').click(function () {
					$version_id = $(this).attr('data-version-id');
					$file_url  = $(this).attr('data-file-url');
					x = confirm('Are you sure you want to update?');
					if (x == true) {
						$dwnUpdate.html('downloading...');
						$.ajax({
							method:'POST',
							url:'updates.php',
							data:{dowloadUpdate:true,file_url:$file_url,version_id:$version_id},
							success:function(response) {
								alert('All files are downloaded and ready to update.');
								// alert($version_id);
								$('#dwn-update').append('<a style="cursor:pointer" class="btn-update" data-asd="asdasd" data-version-id='+$version_id+'>Click here to update</a> ');
								localStorage.setItem('readyToExtract',true);
								updateFiles();
							}
						});
					}
				})
			}

		
	})



</script>
</html>