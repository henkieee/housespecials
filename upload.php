<?php
function emptyUploadDir()
{
	$files = glob('upload/*');
	foreach($files as $file){  if(is_file($file)) unlink($file); }
}

function upload()
{
	//print_r($_FILES['upfile']);
	
	if(! empty($_FILES['upfile']['name']))
	{
		$error = '';
		$success = '';
		$finfo = new finfo(FILEINFO_MIME_TYPE);
	   	if ($_FILES['upfile']['size'] > 2097152) $error = 'De geuploade file mag niet groter zijn dan 2MB';
	    if (false === $ext = array_search(
	        $finfo->file($_FILES['upfile']['tmp_name']),
	        array(
	            'jpg' 	=> 	'image/jpeg',
	            'png' 	=> 	'image/png',
	            'docx' 	=> 	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        		'doc' 	=> 	'application/msword',
        		'pdf'	=>	'application/pdf'
	        ),
	        true
	    )) $error = 'De geuploade file moet van het type PNG, JPG DOC(X) of PDF zijn';
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
	    echo $error;
	    echo $success;
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
        	var uploadImgEl = document.getElementById('upload-img'),
        		uploadDocEl = document.getElementById('upload-document');

        	if(file.type === "image/png" || file.type === "image/jpeg") 
        	{
        		uploadImgEl.style.display = "block";
            	uploadImgEl.src = file.data;
        	}
        	else uploadDocEl.textContent = file.name;
            document.getElementById('removeUpload').style.display = 'block';
        }

		function readImg(input) 
		{	
            if (! input.files.length) return; 

            var reader = new FileReader(),
                	file = input.files[0],
                	fileTypes = [
	                		'image/png', 
	                		'image/jpeg',
	                		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	                		'application/msword',
	                		'application/pdf'
                		], 
                	error = '';

            reader.onload = function (e) {
            	var currentFile = {
            		name: file.name,
            		type: file.type,
            		data: e.target.result
            	};
            	localStorage.setItem('uploaded_file', JSON.stringify(currentFile));
            	showUploadedFile(currentFile);
            };

            if(file.size > 2097152) error = 'De geuploade file mag niet groter zijn dan 2MB';
            if(fileTypes.indexOf(file.type)  === -1) error = 'De geuploade file moet van het type PNG of JPG zijn';
            if(error) document.querySelector('#error').textContent = error;
            else reader.readAsDataURL(file);
        }

		document.addEventListener("DOMContentLoaded", function(ev) {
		    var uploadedFile = JSON.parse(localStorage.getItem('uploaded_file')),
		    	removeUploadBtn = document.getElementById('removeUpload');
		    if(uploadedFile) showUploadedFile(uploadedFile);
		    else removeUploadBtn.style.display = 'none';

		    document.getElementById('uploadButton').addEventListener('click', function() {	   
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
/*		#uploadButton {
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
			width: 200px;
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

		#upload-document {
			color: green;
		}*/
	</style>
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data">
	
		<p id="succes" style="color: lightgreen;"><?php if(isset($success)) echo $success; ?></p>
		<p id="error" style="color: red;"><?php if(isset($error)) echo $error; ?></p>
		<input type="file" id="uploadFile" name="upfile" onchange="readImg(this)"/ style="display:none">
		<div id="uploadWrap">
			<img id="upload-img" src="" style="width: 200px;"/>
			<p id="upload-document"></p>
			<button title="Verwijder upload" id="removeUpload">X</button>
			<div id="uploadArea">
				<input type="button" id="uploadButton" name="" value="Selecteer upload"
				style="font-size: 16px;
					    background: blue;
					    color: white;
					    border: none;
					    border-radius: 7px;
					    padding: 7px;">
				<span>Alleen Png, JPG, DOC(X) en PDF zijn toegestaan</span>
			</div>
		</div>
		<br />
		<input type="submit" name="submit" value="submit" />
	</form>
</body>
</html>