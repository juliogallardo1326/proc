<?php /* Smarty version 2.6.2, created on 2006-10-21 11:03:44
         compiled from help/link_filters.tpl.html */ ?>

<h4>Link Filters</h4>
<span class="default">
Link filters are used to replace text such as 'Bug #42' with an automatic 
link to some external resource. It uses regular expressions to replace the text.
Specify the search pattern in the pattern field without delimiters. Specify the entire 
string you would like to use as a replacement with $x to insert the matched text. For example:
<br /><br />
Pattern: "bug #(\d+)"<br />
Replacement: "&lt;a href=http://example.com/bug.php?id=$1&gt;Bug #$1&lt;/a&gt;"<br />
</span>