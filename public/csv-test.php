<html>
<head>
    <title>CSV Test</title>
    <link href="styles.css" rel="stylesheet" type="text/css">
</head>

<script type="text/javascript" src="highlight.js"></script>

<body>

<div class="nav">
<a href="index.php">Home</a>
<a href="blog.php">Blog</a>
<a href="gallery.php">Gallery</a>
<a href="csv-test.php" class="active">CSV Test</a>
<a href="search.php">Search</a>
</div>

<div class="container">

<h1>CSV File Processing</h1>

<?php
    require_once("proc_csv.php");

    echo "<h4>dat2-doublequote-comma.csv</h4>\n";
    echo array_to_table(proc_csv("data/dat2-doublequote-comma.csv", '"',",","ALL"));

    echo "<h4>dat2-singlequote-tab.csv</h4>\n";
    echo array_to_table(proc_csv("data/dat2-singlequote-tab.csv", "'","\t", "ALL"));

    echo "<h4>dat2-doublequote-tab.csv <i>(columns 0 & 2)</i></h4>\n";
    echo array_to_table(proc_csv("data/dat2-doublequote-tab.csv", '"',"\t", "0:2"));

    echo "<br>CSV file processor works with all quote and delimeter types<br>";

	// Use the 2D array output from proc_csv() to build an HTML table
    function array_to_table($array) {
		$out = "<table border=\"1\">\n";
		$isHeaderRow = true;

		foreach ($array as $row) {
			$out .= "<tr>\n";
			foreach ($row as $cell) {
				if ($isHeaderRow) {
					$out .= "<th>$cell</th>\n";
				} else {
					$out .= "<td>$cell</td>\n";
				}
			}
			$out .= "</tr>\n";
			$isHeaderRow = false;
		}
		$out .= "</table>\n";
		return $out;
    }

?>

</div>

</body>

<?php
if ($_GET['highlight']) {
    echo '<script type="text/javascript">highlightText("'.$_GET['highlight'].'");</script>';
}
?>

</html>
