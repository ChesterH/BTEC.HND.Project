<?php	
	// Acquire company name
	require_once('includes/name.php');
	
	// Start the session
	session_start();
	
	// Add the page header
	$pageTitle = "Notes";
	require_once('includes/head.php');
?>
<style>
article {
	margin-bottom: 50px;
}
a {
	color: blue;
}
ol {
	margin-bottom: 50px;
}
section {
	padding: 5px 50px 0;
	border: 1px solid black;
	margin-bottom: 25px;
}
section > h2 {
	text-decoration:underline;
}
h2 + article {
	border-top: none;
}
article + article {
	border-top: 1px dashed black;
}
</style>
<body>
    <?php require_once('includes/header.php'); ?>
	<div id="mainContent">
		<h1>Notes</h1>
        <a href="selectors.php" class="button">Selectors</a>
        <h2 id="ToC">Table of Contents</h2>
        <ol>
        	<li>
                <h4><a href="#Accessibility">Accessibility</a></h4>
                <ol>
                    <li><a href="#SkipLinks">Skip Links</a></li>
                </ol>
            </li>
            <li>
                <h4><a href="#FluidLayouts">Fluid Layouts</a></h4>
                <ol>
                    <li><a href="#ScalingImages">Scaling Images</a></li>
                </ol>
            </li>
            <li>
                <h4><a href="#LinkStyling">Link Styling</a></h4>
                <ol>
                    <li><a href="#ExternalLinks">External Links</a></li>
                    <li><a href="#ImageLinks">Image Links</a></li>
                    <li><a href="#Cursor">Cursor</a></li>
                </ol>
            </li>
            <li>
                <h4><a href="#TextStyling">Text Styling</a></h4>
                <ol>
                    <li><a href="#VerticalTextAlignment">Vertical Text Alignment</a></li>
                </ol>
            </li>
        </ol>
        <section id="Accessibility">
        	<h2>Accessibility</h2>
            <article id="SkipLinks">
                <h3>Skip Links</h3>
                <p>To accommodate screen readers, use a skip navigation link at the top of the website and hide it with CSS.</p>
                <p>The anchor tag should not be empty because most screen readers will ignore empty elements.</p>
                <p>Example:</p>
                &lt;a href="#SkipLink"&gt;Skip to Content&lt;/a&gt;
            </article>
        </section>
        <section id="FluidLayouts">
        	<h2>Fluid Layouts</h2>
            <article id="ScalingImages">
                <h3>1. Scaling Images</h3>
                <p>When using a responsive layout and responsive images, IE's scaling factors can really damage image quality.</p>
                <p>When using the following rule to make images responsive...</p>
                <p>img {<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; max-width: 100%;<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; height: auto;<br>
                }</p>
        
                <p>...use this rule to improve the quality of images when they are scaled in IE:</p>
                <p>img {<br>
                     &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; -ms-interpolation-mode: bicubic;<br>
                }</p><br>
                
                <p>Also, with IE8, the width scales but the height doesn't when a definitive width is not applied as well as max-width: 100%.</p>
                <p>To fix this, use...</p>
                <p>img {<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; width: auto;<br>
                }</p>
                <p>Target IE with a conditional comment...</p>
                <p>&lt;!-- [if IE 8]&gt; &lt;link rel="stylesheet" href="IE8styles.css"&gt; &lt;![endif] --&gt;</p>
            </article>
        </section>
        <section id="LinkStyling">
        	<h2>Link Styling</h2>
            <article id="ExternalLinks">
                <h3>External Links</h3>
                <p>To indicate external links and to generate content after them, use the following rules:</p>
                <p>When checking for a string at the beginning:</p>
                <p>a[href^="http"]:after {<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; content: " o";<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; font-family: Modern, sans-serif;<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; font-size: 1.1em;<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; position: relative;<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; top: -.2em;<br>
                }</p>
                <p>When checking for a string at the end:</p>
                <p>a[href$="doc"]:after {<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ...<br>
                }</p>
                <p>Font:</p>
                <p>Link: fontsquirrel.com/fonts/modern-pictograms</p>
                <p>Code:</p>
                <p>@font-face {<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; font-family: 'Modern';<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; src: url('fonts/modernpics-webfont.eot');<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; src: url('fonts/modernpics-webfont.eot?#iefix') format('embedded-opentype'),<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; url('fonts/modernpics-webfont.woff') format('woff'),<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; url('fonts/modernpics-webfont.ttf') format('truetype'),<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; url('fonts/modernpics-webfont.svg#ModernPictogramsNormal') format('svg');<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; font-weight: normal;<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; font-style: normal;<br>
                }</p>
            </article>
            <article id="ImageLinks">
                <h3>Image Links</h3>
                <p>To style image links use the following rules:</p>
                <p>Images:</p>
                <p>a img {<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; border: none;<br>
                }</p>
                <p>Hovering over image links:</p>
                <p>a:hover img {<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ...<br>
                }</p>
            </article>
            <article id="Cursor">
                <h3>Cursor</h3>
                <p>To change the cursor, use the cursor attribute.</p>
            </article>
        </section>
        <section id="TextStyling">
        	<h2>Text Styling</h2>
            <article id="VerticalTextAlignment">
                <h3>Vertical Text Alignment</h3>
                <p>Use line-height to vertically centre link text. It draws an invisible box around the line and if the value of the line-height is larger than the size of the text, the space is split evenly above and below the text.</p>
            </article>
        </section>
        <a href="#ToC">Back to Top</a>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>