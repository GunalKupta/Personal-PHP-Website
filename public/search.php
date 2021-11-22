<html>

<head>
    <title>Search</title>
    <link href="styles.css" rel="stylesheet" type="text/css">
</head>

<script type="text/javascript" src="highlight.js"></script>

<body>

<div class="nav">
<a href="index.php">Home</a>
<a href="blog.php">Blog</a>
<a href="gallery.php">Gallery</a>
<a href="csv-test.php">CSV Test</a>
<a href="search.php" class="active">Search</a>
</div>

<div class="container">

    <h1>Search the website</h1>

    <form method='get'>
        Enter text to search: <input type='text' name='search' autofocus="autofocus" onfocus="this.select()">
        <input type='submit'>
    </form>

<?php
    $myip = getenv('MY_IP');
	$contentPages = array(
		"index.php",
        "blog.php",
        "gallery.php",
        "csv-test.php",
        "search.php"
	);

    // Search each content page for the search term and include a link
    // to each page (with 'highlight' querystring) where it is found.
	if ($_GET['search']) {
        $highlight = trim($_GET['search']);
		echo '<p>Searching for "'.$highlight.'"...</p>';
        $count = 0;
        echo "<ul>";
        foreach ($contentPages as $page) {
            $url = "http://".$myip.":5555/".$page;
            $numMatches = 0;

            // use "details" mode in gallery page to search text
            if ($page == "gallery.php") {
                $numMatches = check_match_in_page($url."?mode=details", $highlight);
            } else {
                $numMatches = check_match_in_page($url, $highlight);
            }

            if ($numMatches > 0) {
                // build the URL based on appropriate querystrings
                $querydata = array(
                    'highlight' => preg_replace('/&(?!amp;)/', '&amp;', $highlight)
                );
                if ($page == "gallery.php") {
                    $querydata['mode'] = "details";
                }

                echo "<li><p>".$numMatches." match(es) found in <a href='".$url."?".http_build_query($querydata)."'>".$page."</a>.</p></li>";
                $count += $numMatches;
            }
        }
        echo "</ul>";
        echo "<p>".$count." match(es) found in all pages.</p>";
	}

    // Search a page for a given search term and returns the number of matches.
    function check_match_in_page($url, $text) {
        $count = 0;
        $content = file_get_contents($url);
        $doc = new DOMDocument();
        $doc->loadHTML($content);
        $elements = $doc->getElementsByTagName("*");

        // find all text elements in DOM
        foreach ($elements as $element) {
            $children = $element->childNodes;
            foreach ($children as $child) {
                // check if node is text node and contains a match
                if ($child->nodeType == XML_TEXT_NODE && preg_match_all("/".$text."/i", $child->textContent, $matches)) {
                    $count += count($matches[0]);
                }
            }
        } // end outer foreach
        return $count;
    }

    if ($_GET['highlight']) {
		echo '<script type="text/javascript">highlightText("'.$_GET['highlight'].'");</script>';
	}

?>

</div>

</body>

</html>