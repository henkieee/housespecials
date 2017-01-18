<?php
function emptyUploadDir()
{
	$files = glob('upload/*');
	foreach($files as $file){  if(is_file($file)) unlink($file); }
}

function upload()
{
	
	if(! empty($_FILES['upfile']['name']))
	{
		$error = '';
		$succes = '';
		$finfo = new finfo(FILEINFO_MIME_TYPE);
	   	if ($_FILES['upfile']['size'] > 2097152) $error = 'De geuploade file mag niet groter zijn dan 2MB';
	    if (false === $ext = array_search(
	        $finfo->file($_FILES['upfile']['tmp_name']),
	        array(
	            'jpg' => 'image/jpeg',
	            'png' => 'image/png'
	        ),
	        true
	    )) $error = 'De geuploade file moet van het type PNG of JPG zijn';
		else if (!move_uploaded_file(
		        $_FILES['upfile']['tmp_name'],
		        sprintf('./upload/%s.%s',
		            sha1_file($_FILES['upfile']['tmp_name']),
		            $ext
		        )
		    )) {
		        $error = 'De file kon niet geupload worden';
		    }
	    else $success = 'File is geupload';
	}
}

//emptyUploadDir();
if(isset($_POST['submit'])) upload();
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript">

		function showUploadedFile(file)
        {
        	var uploadImgEl = document.querySelector('#upload-img');

        	localStorage.setItem('uploaded_file', file);
            uploadImgEl.style.display = "block";
            uploadImgEl.src = localStorage.getItem('uploaded_file');
            document.getElementById('removeUpload').style.display = 'block';
        }

		function readImg(input) 
		{	
            if (! input.files.length) return; 

            var reader = new FileReader(),
                	file = input.files[0],
                	fileTypes = ["image/png", "image/jpeg"], 
                	error = '';

            reader.onload = function (e) {
            	showUploadedFile(e.target.result);
            };

            if(file.size > 2097152) error = 'De geuploade file mag niet groter zijn dan 2MB';
            if(fileTypes.indexOf(file.type)  === -1) error = 'De geuploade file moet van het type PNG of JPG zijn';
            if(error) document.querySelector('#error').textContent = error;
            else reader.readAsDataURL(file);
        }

		document.addEventListener("DOMContentLoaded", function(event) {
		    var uploadedFile = localStorage.getItem('uploaded_file'),
		    	removeUploadBtn = document.getElementById('removeUpload');
		    if(uploadedFile) showUploadedFile(uploadedFile);
		    else removeUploadBtn.style.display = 'none';

		    document.getElementById('uploadButton').addEventListener('click', function openDialog() {	   
	            document.getElementById('uploadFile').click();
	        });

	        document.getElementById('removeUpload').addEventListener('click', function(ev) {
	        	ev.target.style.display = 'none';
	        	document.getElementById('upload-img').style.display = 'none';
	        	localStorage.clear();
	        });
		});
	</script>
	<link rel="stylesheet" type="text/css" href="offerte.css">
	<style type="text/css">
		#uploadButton {
			background: blue;
		    color: white;
		    padding: 10px;
		    border-radius: 7px;
		    border-style: none;
		    border: none;
		    cursor: pointer;
		    outline: none;
		    font-weight: bold;
		    font-size: 12px;
		    margin-bottom: 5px;
		}

		#uploadButton + span {
			display: block;
			color: #777;
		}

		#uploadWrap {
			border: dotted black 2px;
			width: 200px;
			margin-bottom: 10px;
			text-align: center;
			padding: 10px;
			position: relative;
		}

		#uploadWrap img {
			display: none;
			margin-bottom: 10px;
		}

		#removeUpload {
			background: red;
		    outline: none;
		    color: white;
		    border-radius: 30px;
		    border: none;
		    font-weight: bold;
		    position: absolute;
		    top: 3px;
		    right: 5px;
		    cursor: pointer;
		}
	</style>
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data">
	
		<p id="succes" style="color: lightgreen;"><?php if(isset($success)) echo $success; ?></p>
		<p id="error" style="color: red;"><?php if(isset($error)) echo $error; ?></p>
		<input type="file" id="uploadFile" name="upfile" onchange="readImg(this)"/ style="display:none">
		<div id="uploadWrap">
			<img id="upload-img" src="" style="width: 200px;" />
			<button title="Verwijder upload" id="removeUpload">X</button>
			<div id="uploadArea">
				<input type="button" id="uploadButton" name="" value="Selecteer upload">
				<span>Alleen Png, JPG, DOC(X) en PDF toegestaan</span>
			</div>
		</div>
		<br />
		<input type="submit" name="submit" value="submit" />
	</form>
</body>
</html>