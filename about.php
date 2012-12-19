<!DOCTYPE html>
<html>
  <head>
    <title>About SimplePhotosContest</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
		<link rel="icon" type="image/png" href="favicon.png" />
		<style type="text/css">
			h1, h2{
				color: #ff0056;
				margin: 6px;
			}
			h1{
				font-size: 60px;
			}
			dt{
				color: #999;
			}
			dd, dl{
				margin-left: 10px;
			}
			p{
				margin-left: 10px;
			}
		</style>
	</head>
	<body>
		<div id="wrap">
		<?php
		require("markdown.php");
		$text = '';
		$file_handle = fopen("README.md", "r");
		while (!feof($file_handle)) {
		   $text .= fgets($file_handle);
		}
		fclose($file_handle);
		echo Markdown($text);
		?>
		</div>
	</body>
</html>
<?php

?>
