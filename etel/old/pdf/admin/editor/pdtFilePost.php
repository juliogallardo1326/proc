<? 
//If the Submitbutton was pressed do: 

if ($_FILES['imgfile']['type'] != ""){ 
	copy ($_FILES['imgfile']['tmp_name'], "uploaded_images/".$_FILES['imgfile']['name']) 
    or dieLog("Could not copy"); 
	copy ($_FILES['imgfile']['tmp_name'], "../uploaded_images/".$_FILES['imgfile']['name']) 
    or dieLog("Could not copy"); 
        echo "Name : ".$_FILES['imgfile']['name'].""; 
        echo "<br>Size : ".$_FILES['imgfile']['size'].""; 
   //     echo "<br>Type : ".$_FILES['imgfile']['type'].""; 
        echo "<br>Image Successfully Uploaded...."; 
        } 
     else { 
            echo ""; 
            echo "Could Not Copy, Wrong Filetype (".$_FILES['imgfile']['name'].")"; 
			exit();
        } 
	$FilePath ="uploaded_images/".$_FILES['imgfile']['name'];
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<script>
imgSrc ="<?=$FilePath?>"
window.opener.document.execCommand('insertimage', false, imgSrc); 
alert("Image uploaded successfully.")
window.close()
</script>
</body>
</html> 
