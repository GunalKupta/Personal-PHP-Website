<?php

    // these variables are used to determine if and how to end a list or sub-list
    $endPrevList = "";
    $prevListIndent = 0;

    // process the wiki text one line at a time
    function proc_wikitext($filename) {

        global $endPrevList;

        $handle = fopen($filename ,"r") or die("Cannot open ".$filename);
        $out = "<p>";

        $headerRegex = "/^(=+)(.+)(=+)/";
        $indentRegex = "/^(:+)(.+)/";
        $unorderedListRegex = "/^(\*+)(.+)/";
        $orderedListRegex = "/^(\#+)(.+)/";

        while ($line = fgets($handle)) {
            $line = trim($line);

            // Use regex to determine the line style (header, list, paragraph, empty, etc.)
            // and process accordingly.
            if (preg_match($headerRegex, $line)) {
                $out .= $endPrevList;
                $headingType = countBeginningChars('=', $line);
                $out .= "<h".$headingType.">".trim($line,"=")."</h".$headingType.">\n";
                // $out .= "<hr>\n";

            } else if (preg_match($indentRegex, $line)) {
                $out .= $endPrevList;
                $indentAmount = countBeginningChars(':', $line);
                $out .= "</p><p>".str_repeat("&nbsp", $indentAmount*3).trim($line,":")."\n</p><p>";

            } else if (preg_match($unorderedListRegex, $line)) {
                $out .= proc_list($line, "<ul>", "</ul>", '*');

            } else if (preg_match($orderedListRegex, $line)) {
                $out .= proc_list($line, "<ol>", "</ol>", '#');

            } else if ($line == "----") {
                $out .= $endPrevList."<hr />\n";

            } else if ($line == '') {
                $out .= end_list();
                $out .= "</p><p>";

            } else {
                $out .= proc_lineFormatting($line)."\n";

            }
        }
        $out .= end_list();
        $out .= "</p>";
        return $out;
        
    }

    // process a single line of wiki text, which could contain formatting, URLs, and/or images
    function proc_lineFormatting($line) {
        $out = $line;

        $italicBoldRegex = "/'''''(.+?)'''''/";
        $boldRegex = "/'''(.+?)'''/";
        $italicRegex = "/''(.+?)''/";
        $codeRegex = "/`(.+?)`/";
        $pictureSizeRegex = "/\[\[File:([^\|]+)\|px=(\d+)\]\]/";
        $pictureRegex = "/\[\[File:([^\|]+)\]\]/";
        $url = "(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?\S*";
        $namedUrlRegex = "/\[(".$url.")\s(.+)\]/i";
        $urlRegex = "/".$url."/i";

        // Replace each of the above regex matches with the appropriate HTML
        $out = preg_replace($italicBoldRegex, "<i><b>$1</b></i>", $out);
        $out = preg_replace($boldRegex, "<b>$1</b>", $out);
        $out = preg_replace($italicRegex, "<i>$1</i>", $out);
        $out = preg_replace($codeRegex, "<code>$1</code>", $out);
        $out = preg_replace($pictureRegex, "<img src=\"images/$1\" alt=\"$1\">", $out);
        $out = preg_replace($pictureSizeRegex, "<img src=\"images/$1\" alt=\"$1\" width=\"$2\">", $out);

        // don't want a URL to be matched twice, so use if/else if
        if (preg_match($namedUrlRegex, $out)) {
            $out = preg_replace($namedUrlRegex, "<a href=\"$1\" target=\"_blank\">$5</a>", $out);
        } else if (preg_match($urlRegex, $out)) {
            $out = preg_replace($urlRegex, "<a href=\"$0\" target=\"_blank\">$0</a>", $out);
        }
        
        return $out;
    }

    // Determine the appropriate HTML output for the current list item using information
    // from previous lines ($prevListIndent, $endPrevList).
    function proc_list($line, $startList, $endList, $listChar) {
        global $prevListIndent, $endPrevList;
        $out = "";

        if ($endPrevList != $endList) {
            $out .= $endPrevList;
            $prevListIndent = 0;
            $endPrevList = $endList;
        }
        $listIndent = countBeginningChars($listChar, $line);
        $listText = proc_lineFormatting(trim($line, $listChar));

        // Compare the current list indent to the previous list indent to determine
        // whether to start a new sub/list, end a sub/list, or add a normal list item.
        if ($listIndent > $prevListIndent) {
            $out .= $startList."<li>".$listText."</li>";
        } else if ($listIndent < $prevListIndent) {
            $out .= str_repeat($endPrevList, $prevListIndent - $listIndent)."</li><li>".$listText."</li>";
        } else {
            $out .= "<li>".$listText."</li>";
        }
        $prevListIndent = $listIndent;
        $out .= "\n";
        return $out;
    }

    function end_list() {
        global $prevListIndent, $endPrevList;
        $out = "";
        while ($prevListIndent > 0) {
            $out .= $endPrevList;
            $prevListIndent--;
        }
        $endPrevList = "";
        return $out."\n";
    }
        
    // Determines heading type or level of indent for lists in wikitext.
    function countBeginningChars($char, $string) {
        for ($i = 0; $i < strlen($string); $i++) {
            if ($string[$i] != $char) {
                return $i;
            }
        }
        return strlen($string);
    }
?>
