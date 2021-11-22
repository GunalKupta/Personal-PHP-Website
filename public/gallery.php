<html>
<head>
  <title>Gallery</title>

  <style>
    .sidebar {
        display: inline-block;
        width: 150px;
        vertical-align: top;
    }
    .sidebar p, .sidebar label {
        margin-bottom: 0px;
        font-family: Helvetica, Arial, sans-serif;
    }

    .gallery {
        display: inline-block;
        width: calc(100% - 200px);
        margin: 20px 0px;
        vertical-align: top;
        float: right;
    }
    .gallery p {
        margin: 5px 0px;
        font-family: Helvetica, Arial, sans-serif;
    }

    /* Use a CSS grid for the matrix gallery view */
    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-gap: 20px;
        align-items: start;
    }
    .grid-item > img {
        object-fit: contain;
        object-position: left bottom;
        height: calc(100% - 25px);
        max-width: 100%;
    }

  </style>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>

<script type="text/javascript" src="highlight.js"></script>

<body>

<div class="nav">
<a href="index.php">Home</a>
<a href="blog.php">Blog</a>
<a href="gallery.php" class="active">Gallery</a>
<a href="csv-test.php">CSV Test</a>
<a href="search.php">Search</a>
</div>

<div class="container">

<div class="sidebar">
<h1>Gallery</h1>

<form method="get">
    <p>View</p>
    <input type="radio" name="mode" id="list" value="list">
    <label for="list">List</label><br>
    <input type="radio" name="mode" id="matrix" value="matrix">
    <label for="matrix">Matrix</label><br>
    <input type="radio" name="mode" id="details" value="details">
    <label for="details">Details</label><br>

    <p>Sort by</p>
    <input type="radio" name="sort_mode" id="orig" value="orig">
    <label for="orig">Original ordering</label><br>
    <input type="radio" name="sort_mode" id="date_newest" value="date_newest">
    <label for="date_newest">Date newest</label><br>
    <input type="radio" name="sort_mode" id="date_oldest" value="date_oldest">
    <label for="date_oldest">Date oldest</label><br>
    <input type="radio" name="sort_mode" id="size_largest" value="size_largest">
    <label for="size_largest">Size largest</label><br>
    <input type="radio" name="sort_mode" id="size_smallest" value="size_smallest">
    <label for="size_smallest">Size smallest</label><br>
    <input type="radio" name="sort_mode" id="rand" value="rand">
    <label for="rand">Random</label><br>
    <br>
    <input type="submit" value="Go">
</form>
</div>
<div class="gallery">
<?php
    require_once('proc_gallery.php');

    if ($_GET['mode'] || $_GET['sort_mode']) {
        proc_gallery("data/images.csv", $_GET['mode'], $_GET['sort_mode']);
    }

    if ($_GET['highlight']) {
		echo '<script type="text/javascript">highlightText("'.$_GET['highlight'].'");</script>';
	}

?>

</div>

</div>

</body>
</html>