<html>

<!-- HEAD section ............................................................................ -->
<head>
  <title>Kunal Gupta</title>
  <link rel="stylesheet" href="styles.css" type="text/css">

  <style>
	.headshot {
		float: right;
		height: 150px;
		position: sticky;
		top: 20px;
	}
	.resume {
		margin: 10px;
	}
	.resume h2 {
		padding-bottom: 1px;
		border-bottom: 2px solid blue;
	}
	.resume h3 {
		color: #00A;
	}
	.resume a {
		font-weight: bold;
		color: #13e6a0;
	}
	.resume ul li {
		padding-bottom: 3px;
	}
  </style>
</head>

<script type="text/javascript" src="highlight.js"></script>

<!-- BODY section ............................................................................. -->
<body>

<div class="nav">
<a href="index.php" class="active">Home</a>
<a href="blog.php">Blog</a>
<a href="gallery.php">Gallery</a>
<a href="csv-test.php">CSV Test</a>
<a href="search.php">Search</a>
</div>

<div class="container">

<img class="headshot" src="images/headshot.png" href="index.php">

<h1>Kunal Gupta's PHP Website</h1>

<div class="content">
<div class="resume">
<?php
    require_once("proc_wiki.php");

	// Build resume from wikitext
    echo proc_wikitext("data/resume.wiki");

	if ($_GET['highlight']) {
		echo '<script type="text/javascript">highlightText("'.$_GET['highlight'].'");</script>';
	}

?>

</div>
<br><i>- Generated wikitext from <a href='data/resume.wiki' target='_blank'><b>resume.wiki</b></a></i>

</div>
</div>
</body>
</html>
