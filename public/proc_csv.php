<?php

// proc_csv turns a csv file into a two-dimensional array
function proc_csv($filename, $quote, $delimiter, $columns_to_show) {

    $handle = fopen($filename ,"r") or die("Cannot open ".$filename);

    $out = array();

    // Determine which columns to include
    $show_all_cols = $columns_to_show == "ALL";
    $cols_list = array_map('intval', explode(":", $columns_to_show));

    $regexString = '/(^|(?<='.$delimiter.'))((\d+|('.$quote.'[^'.$quote.']*'.$quote.'))?((?='.$delimiter.')|$))/';

    // Build 2D array containing selected columns one row at a time
    while ($data = trim(fgets($handle))) {
        $newRow = array();
        preg_match_all($regexString, $data, $data_cols);
        for ($k=0; $k<count($data_cols[0]); ++$k) {
            if ($show_all_cols || in_array($k, $cols_list)) {
                $newRow[] = trim($data_cols[0][$k], $quote);
            }
        }
        array_push($out, $newRow);
    }

    fclose($handle);

    return $out;
}

?>