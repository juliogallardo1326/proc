<html>
<head>
<title>AJAX Spell 2.7</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="spell_checker.css">
<script src="cpaint2.inc.compressed.js" type="text/javascript"></script>
<!-- You can use either one of the files below, but the compressed one
     will be faster and a lot smaller to download -->
<script src="spell_checker_compressed.js" type="text/javascript"></script>
<!--<script src="spell_checker.js" type="text/javascript"></script>-->

</head>
<body>
<p>Current version - 2.7</p>
<p><strong>  If you're interested in using this code, you can <a href="http://www.broken-notebook.com/download_spell_checker.php">download Version 2.7 source here</a>.</strong><br />
  <br>
Keep checking this page for updates!! - <a href="#changelog">Changelog</a></p>
<p>  If you have any questions, comments, or find any problems  please <a href="http://www.broken-notebook.com/index.php?p=contact.php">email me here</a>.</p>
<hr>

This has also only been tested in Firefox and Internet Explorer for right now. I'm told Safari works fine as well.<br />
The people at Opera don't like to implement stuff for some reason, so most Opera distributions won't work.
<br /><br />
<form name="form1" action="#">
<textarea title="spellcheck" accesskey="spell_checker.php" id="spell_checker1" name="comment1" style="width: 400px; height: 200px;" />This is a tëst of non-ascii characters<br /><br />andd a testt of the spelll checker.  Javascript is an unregonized wordd!!</textarea>
<br />
<textarea id="spell_checker2" name="comment2" style="width: 400px; height: 200px;" />This text area does not have a spell checker.</textarea>
<br />
<textarea title="spellcheck" accesskey="spell_checker.php" id="spell_checker3" name="comment3" style="width: 400px; height: 200px;" />This is anotherr testt wiht a thrid spell checkker!!</textarea>
<br />
These html tags are allowed:  &lt;p&gt;, &lt;br&gt;, &lt;a&gt;, &lt;b&gt;, &lt;strong&gt;, &lt;i&gt;, &lt;small&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;img&gt;
<br />
</form>
<br />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
"> 
- Any donations are greatly appreciated and will go towards further development. 
</form>
<br />
<br />
<a name="changelog"></a><strong>Change Log</strong><br />
<hr>
<p>Version 2.7 - 23.January.2006</p>
<ul>
  <li>Reworked how the spell checker is added to a page.  It no longer has the problem of not degrading nicely.  <br />
  The text areas will still work fine if you don't have Javascript enabled.  You only need to set the title attribute of <br />
  the text area to "spellcheck" and the accesskey attribute to the location of the spell_checker.php file on the text areas <br />
  you want to have a spell checker on and include the spell checker files, and it will automatically go through and add spell <br />
  checking capabilities to the text areas.  See the example and Readme.txt for more info.</li>
  <li>Added the ability to add words to a custom dictionary.  Just make sure the personal_dictionary directory is publicly writable.</li>
  <li>Added a fix in the pspell_comp.php file that was not allowing people using aspell to change the language. (Thanks to <br />
  Thomas Rabe for this fix).</li>
  <li>Removed the setText functions.  These are no longer needed since you can just stick the text to be loaded in the textarea tags.</li>
  <li>Added a class in spell_checker.css to style the Add to Dictionary link.</li>
</ul>
<p>Version 2.6 - 14.December.2005</p>
<ul>
  <li>Added the strip_tags_advanced fuction so that disallowed tags are converted to escaped html, <br />
  rather than being removed. This function also escapes tags with unsupported attributes (such as onmouseover).</li>
</ul>
<p>Version 2.5 - 21.October.2005</p>
<ul>
  <li>Added the setText() function so that you can set the preloaded text in the textarea. <br />
  See the example.php file for an example of how to use it.</li>
</ul>
<p>Version 2.4 - 21.October.2005</p>
<ul>
  <li>Upgraded to CPAINT 2.0.</li>
</ul>
<p>Version 2.3 - 20.October.2005</p>
<ul>
  <li>Spell checker is now object oriented so you just have to make one call to add a spell checker to your site.</li>
  <li>Rewrote how the spell checker is generated.  It's all done dynamically so there's little work for the developer.</li>
  <li>Made it super easy to have multiple spell checkers on one page.</li>
  <li>Increased the speed of suggestion return a little bit by cutting out extra crap that didn't really need to be there.</li>
</ul>
<p>Version 2.2 - 24.June.2005</p>
<ul>
  <li>Fixed a problem where it wouldn't strip the html tags properly.</li>
  <li>Non-standard unicode characters are no longer used.</li>
  <li>The compressed Javascript file wasn't being compressed properly, <br>
  so I used a different one and that fixed the problem.</li>
</ul>
<p>Version 2.1 - 23.June.2005</p>
<ul>
  <li>Fixed a bug where it wouldn't resume if the box was empty.</li>
</ul>
<p>Version 2.0 - 23.June.2005</p>
<ul>
  <li>Fixed a bug where words with apostrophes wouldn't be replaced properly if your server 
had magic quotes turned on.</li>  
  <li>Fixed a bug where "No Misspellings" would be returned if you clicked Check Spelling twice.</li>
  <li>Fixed a bug where if you clicked "Resume Editing" twice it would delete the contents of the text box.</li>
  <li>Made it so you can still preview the HTML even if there are no misspellings.</li>
  <li> Fixed the &quot;flashing&quot; problem where the text was shown before the spelling updates were made.</li>
  <li> Added a feature that makes the spell_checker.php include optional. You can call setSpellUrl with the<br>
    url to the spell_checker.php file if you don't want to include it in every page. It defaults to SELF if you don't set it,<br>
    so you'll need to include the spell_checker.php page if you don't set the url to it yourself with the setSpellUrl call.</li>
  <li>Thanks to Jake Olefsky of <a href="http://www.jakeo.com" target="_blank">www.jakeo.com</a> for most of these updates except for the optional include update.<br>
    Thanks to Ir8 Prim8 of <a href="http://www.prim8.net" target="_blank">www.prim8.net</a> for that update. Thanks to Justin Greer for testing the magic quotes crap. </li>
</ul>
<p>Version 1.9 - 16.June.2005</p>
<ul>
  <li>The fix from 1.8 to strip out extra stuff in html tags had a bug. That's now fixed.</li>
  <li>The results and suggestions divs are now auto generated so you don't have to have them hardcoded on the page.<br>
    This allows for multiple spell checkers on the same page. You currently still need to have a hardcoded action and status<br>
    divs for each spell checker. </li>
  <li> Added a fix in spell_checker.php for the people who don't have magic_quotes_gpc enabled on their server...I think this works ok, but it's not been verified as of yet.<br>
    It basically only affects words with apostrophe's and them not being replaced.</li>
  <li>Added a check so it will say &quot;No Suggestions&quot; if there aren't any suggestions for the misspelled word.</li>
  <li>Added check to the onClick handler in spell_checker.js so that it doesn't interfere with any onClick handlers that may already exist on the page.</li>
  <li>Modified the findPosX and findPosY functions in spell_checker.js so that it will find the correct position if the spellchecker is inside any other divs.</li>
  <li>Consolidated the switchText() spell_checker.js code a bit.</li>
  <li>Many thanks to Justin Greer for helping find these bugs.  </li>
</ul>
<p>Version 1.8 - 16.June.2005</p>
<ul>
  <li>Code will now strip out all arbitrary text the user might have added inside html<br>
tags. (i.e. &lt;b onMouseover=&quot;document.location='something';&quot;&gt; will now be shortened to just &lt;b&gt;).<br>
Thanks to Jake Olefsky for noticing that potential security issue.</li>
  <li>Added support for the img tag.  The image will be shown while in the (preview) spell checking mode.</li>
  <li>Fixed a bug where strings would be stripped of slashes all the time.  I changed it so it only does
  stripslashes if magic quotes are on.</li>
  <li>Upgraded to CPAINT 1.01</li>
</ul>
<p>Version 1.7 - 08.June.2005</p>
<ul>
  <li>Back to CPAINT.  Bugs have been worked out and it works very well and very quickly. <br>
  It's also much more efficient than Sajax.</li>
  <li>Added a Beta pspell wrapper for aspell. I've never actually tested it for myself so let me know if<br>
    you use and if it works or doesn't work or whatever. Thanks to Andreas Gohr &lt;andi@splitbrain.org&gt;<br>
    for that addition.</li>
  <li>Also added a thing to increase or decrease the size of the text box...some dude asked for it, so there<br>
    it is if you want to use it. </li>
</ul>
<p>Version 1.6 - 06.June.2005</p>
<ul>
  <li>Reverted back to Sajax instead of CPAINT.  I had reports of it not working well with some <br />
      browsers.  Will wait for later version.</li>
</ul>
<p>Version 1.5 - 03.June.2005</p>
<ul>
  <li>Code no longer uses Sajax cause it was slow and very inefficient. I now use a new homebrew <br />
  AJAX library called CPAINT by Paul Sullivan of BooleanSystems, and now the code is hella fast.</li>
  <li>Separated Javascript and CSS from the file.  Also added a compressed version of the Javascript so it's faster and smaller.</li>
</ul>
<p>Version 1.4 - 31.May.2005</p>
<ul>
  <li>Fixed a little bug where it wouldn't convert html entites back to their applicable characters.</li>
</ul>
<p>Version 1.3 - 26.May.2005</p>
<ul>
  <li>Fixed the bug where it would add breaks for no good reason in Firefox 
    if you had a lot of text. Firefox tries to pretty up<br>
  the formatting of your html, which I didn't want it to do.</li>
</ul>
<p>Version 1.2 - 26.May.2005</p>
<ul>
  <li>Added fix so that POST is used instead of GET so that large pieces of text will go through properly.</li>
</ul>
<p>Version 1.1 - 25.May.2005</p>
<ul>
  <li>Added BSD open source license.</li>
</ul>
</body>
</html>