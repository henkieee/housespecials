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
		    var uploadedFile = localStorage.getItem('uploaded_file');
		    if(uploadedFile) showUploadedFile(uploadedFile)
		});


	</script>
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data">
		<label>Upload een afbeelding</label>
		<br>
		<p id="succes" style="color: lightgreen;"><?php if(isset($success)) echo $success; ?></p>
		<p id="error" style="color: red;"><?php if(isset($error)) echo $error; ?></p>
		<input type="file" name="upfile" onchange="readImg(this)"/>
		<img id="upload-img" src="" style="display: none;width: 200px;" />
		<input type="submit" name="submit" value="submit" />
	</form>
</body>
</html>