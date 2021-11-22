function highlightText(textToReplace) {
    // use regex to highlight all matches in the body using <mark> tag

    var pattern = new RegExp('(?<!</?[^>]*|&[^;]*)('+textToReplace+')', 'ig');
    var body = document.getElementsByTagName("body")[0];
    body.innerHTML = body.innerHTML.replaceAll(pattern, '<mark>$1</mark>');
}