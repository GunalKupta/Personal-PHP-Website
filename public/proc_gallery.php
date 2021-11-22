<?php

require_once('proc_csv.php');

// Image class is used to store relevant information about an image
// to make sorting easier.
class Image {
    public $filename;
    public $datetime;
    public $size;
	public $description;

	function __construct($filename, $datetime, $size, $description) {
		$this->filename = $filename;
		$this->datetime = $datetime;
		$this->size = $size;
		$this->description = $description;
	}
}

function proc_gallery($image_list_filename, $mode, $sort_mode) {

    # $mode == "list"	   	: list of large images view
    # $mode == "matrix"	   	: image matrix view (3 columns)
    # $mode == "details"	: file details view (text)
 
    # $sort_mode == "orig"  : original ordering in the CSV file
    # $sort_mode == "date_newest"  : newest images first
    # $sort_mode == "date_oldest"  : oldest images first
    # $sort_mode == "size_largest" : largest file size first
    # $sort_mode == "size_smallest": smallest file size first
    # $sort_mode == "rand"  : random ordering

	if (is_null($mode)) {
		$mode = "list"; 	// default view mode
	}

    // Get all the images and data from the CSV file, sort by the $sort_mode,
	// and then display the images in the $mode view.
    $image_list = get_images_from_csv($image_list_filename);
	$image_list = sort_images($image_list, $sort_mode);
	display_images($image_list, $mode);
}

// get_images_from_csv returns a list of Image objects from a CSV file.
function get_images_from_csv($filename) {
	$image_list = proc_csv($filename, '"', ",", "ALL");
	$images = array();

	// Create an Image object for each image described in the CSV file.
	// and add it to the list of images.
	foreach ($image_list as $imageRow) {

		$imageFilename = $imageRow[0];
		$description = $imageRow[1];

		// Get the file size and date/time of the image file
		$imageData = exif_read_data("images/".$imageFilename);
		if ($imageData === false) {
			return "Error: exif_read_data() failed.\n";
		}
		$imageSize = $imageData['FileSize'];
		$imageDateTime = $imageData['FileDateTime'];

		$images[] = new Image($imageFilename, $imageDateTime, $imageSize, $description);

	}
	return $images;
}

// sort_images returns a list of Image objects sorted by the $sort_mode.
function sort_images($image_list, $sort_mode) {
	// use usort() function to define the feature to sort by
	switch ($sort_mode) {
		case "date_newest":
			usort($image_list, function($a, $b) {
				return $a->datetime < $b->datetime;
			});
			break;

		case "date_oldest":
			usort($image_list, function($a, $b) {
				return $a->datetime > $b->datetime;
			});
			break;

		case "size_largest":
			usort($image_list, function($a, $b) {
				return $a->size < $b->size;
			});
			break;

		case "size_smallest":
			usort($image_list, function($a, $b) {
				return $a->size > $b->size;
			});
			break;

		case "rand":
			shuffle($image_list);
			break;

		default:
			// default sort mode is original ordering
			break;
	}
	return $image_list;
}

// display_images displays the images in the $mode view.
function display_images($image_list, $mode) {
	
	if ($mode == "list") {
		foreach ($image_list as $image) {
			echo "<img src='images/".$image->filename."' alt=".$image->filename." width='70%'><br>";
			echo "<p><i>".$image->description."</i></p><br><br>\n";
		}

	} else if ($mode == "matrix") {
		// Use a CSS grid to display the images in a matrix view.
		echo "<div class='grid'>\n";
		foreach ($image_list as $image) {
			echo "<div class='grid-item'>\n";
			echo "<img src='images/".$image->filename."' alt=".$image->filename."><br>";
			echo "<p><i>".$image->description."</i></p>";
			echo "</div>\n";
		}
		echo "</div>\n";

	} else if ($mode == "details") {
		foreach ($image_list as $image) {
			echo "Filename: ".$image->filename."<br>\n";
			echo "Date/time modified: ".date('Y-m-d G:i:s', $image->datetime)."<br>\n";
			echo "Filesize: ".$image->size." bytes<br>\n";
			echo "Description: ".$image->description."<br><br>\n";
		}
	}
}
?>