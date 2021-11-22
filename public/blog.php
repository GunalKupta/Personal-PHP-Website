<html>
<head>
<title>Blog</title>
<link href="styles.css" rel="stylesheet" type="text/css">

<style>
	.content {
		line-height: 1.3;
	}
	code {
		background-color: #eee;
		border-radius: 3px;
		font-family: monospace;
		padding: 0 3px;
		font-size: 16px;
	}
</style>

</head>

<script type="text/javascript" src="highlight.js"></script>

<body>

<div class="nav">
<a href="index.php">Home</a>
<a href="blog.php" class="active">Blog</a>
<a href="gallery.php">Gallery</a>
<a href="csv-test.php">CSV Test</a>
<a href="search.php">Search</a>
</div>

<div class="container">

<h1>Blog</h1>

<div class="content">
<?php
require_once('proc_wiki.php');
echo proc_wikitext('data/blog.wiki');

echo "<br><i>- Generated wikitext from <a href='data/blog.wiki' target='_blank'><b>blog.wiki</b></a></i>";

if ($_GET['highlight']) {
	echo '<script type="text/javascript">highlightText("'.$_GET['highlight'].'");</script>';
}
?>
</div>

</div>

</body>
</html>