<?php	
	// Acquire company name
	require_once('includes/name.php');
	
	// Add the page header
	$pageTitle = "Selectors";
	require_once('includes/head.php');
?>
<style>
th {
	background: #E9E7E7;
}
tr:nth-child(2n+1) {
	background: #EEF4B2;
}
</style>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <table class="reference notranslate">
  <tr>
    <th style="width:22%">Selector</th>
    <th style="width:17%">Example</th>
    <th style="width:56%">Example description</th>
    <th>CSS</th>
  </tr>
	<tr>
    <td>.class</td>
    <td class="notranslate">.intro</td>
    <td>Selects all elements with class=&quot;intro&quot;</td>
    <td>1</td>
  </tr>
	<tr>
    <td>#id</td>
    <td class="notranslate">#firstname</td>
    <td>Selects the element with id=&quot;firstname&quot;</td>
    <td>1</td>
  </tr>  <tr>
    <td>*</td>
    <td class="code notranslate">*</td>
    <td>Selects all elements</td>
    <td>2</td>
  </tr>
  <tr>
    <td>element</td>
    <td class="notranslate">p</td>
    <td>Selects all &lt;p&gt; elements</td>
    <td>1</td>
  </tr>
  <tr>
    <td>element,element</td>
    <td class="notranslate">div,p</td>
    <td>Selects all &lt;div&gt; elements and all &lt;p&gt; elements</td>
    <td>1</td>
  </tr>
  <tr>
    <td>element element</td>
    <td class="notranslate">div p</td>
    <td>Selects all &lt;p&gt; elements inside &lt;div&gt; elements</td>
    <td>1</td>
  </tr>
  <tr>
    <td>element&gt;element</td>
    <td class="notranslate">div&gt;p</td>
    <td>Selects all &lt;p&gt; elements where the parent is a &lt;div&gt; element</td>
    <td>2</td>
  </tr>
  <tr>
    <td>element+element</td>
    <td class="notranslate">div+p</td>
    <td>Selects all &lt;p&gt; elements that are placed immediately after &lt;div&gt; elements</td>
    <td>2</td>
  </tr>
	<tr>
    <td>[attribute]</td>
    <td class="notranslate">[target]</td>
    <td>Selects all elements with a target attribute</td>
    <td>2</td>
  </tr>
	<tr>
    <td>[attribute=value]</td>
    <td class="notranslate">[target=_blank]</td>
    <td>Selects all elements with target=&quot;_blank&quot;</td>
    <td>2</td>
  </tr>
	<tr>
    <td>[attribute~=value]</td>
    <td class="notranslate">[title~=flower]</td>
    <td>Selects all elements with a title attribute containing the word &quot;flower&quot;</td>
    <td>2</td>
  </tr>
	<tr>
    <td>[attribute|=value]</td>
    <td class="notranslate">[lang|=en]</td>
    <td>Selects all elements with a lang attribute value starting with &quot;en&quot;</td>
    <td>2</td>
  </tr>
  <tr>
    <td>:link</td>
    <td class="notranslate">a:link</td>
    <td>Selects all unvisited links</td>
    <td>1</td>
  </tr>
	<tr>
    <td>:visited</td>
    <td class="notranslate">a:visited</td>
    <td>Selects all visited links</td>
    <td>1</td>
  </tr>
	<tr>
    <td>:active</td>
    <td class="notranslate">a:active</td>
    <td>Selects the active link</td>
    <td>1</td>
  </tr>
	<tr>
    <td>:hover</td>
    <td class="notranslate">a:hover</td>
    <td>Selects links on mouse over</td>
    <td>1</td>
  </tr>
	<tr>
    <td>:focus</td>
    <td class="notranslate">input:focus</td>
    <td>Selects the input element which has focus</td>
    <td>2</td>
  </tr>
	<tr>
    <td>:first-letter</td>
    <td class="notranslate">p:first-letter</td>
    <td>Selects the first letter of every &lt;p&gt; element</td>
    <td>1</td>
  </tr>
	<tr>
    <td>:first-line</td>
    <td class="notranslate">p:first-line</td>
    <td>Selects the first line of every &lt;p&gt; element</td>
    <td>1</td>
  </tr>
  <tr>
    <td>:first-child</td>
    <td class="notranslate">p:first-child</td>
    <td>Selects every &lt;p&gt; element that is the first child of its parent</td>
    <td>2</td>
  </tr>
	<tr>
    <td>:before</td>
    <td class="notranslate">p:before</td>
    <td>Insert content before&nbsp; the content of every &lt;p&gt; element</td>
    <td>2</td>
  </tr>
	<tr>
    <td>:after</td>
    <td class="notranslate">p:after</td>
    <td>Insert content after every &lt;p&gt; element</td>
    <td>2</td>
  </tr>
	<tr>
    <td>:lang(language)</td>
    <td class="notranslate">p:lang(it)</td>
    <td>Selects every &lt;p&gt; element with a lang attribute equal to &quot;it&quot; 
	(Italian)</td>
    <td>2</td>
  </tr>
	<tr>
    <td>element1~element2</td>
    <td>p~ul</td>
    <td>Selects every &lt;ul&gt; element that are preceded by a &lt;p&gt; element</td>
    <td>3</td>
  </tr>
	<tr>
    <td>[attribute^=value]</td>
    <td>a[src^=&quot;https&quot;]</td>
    <td>Selects every &lt;a&gt; element whose src attribute value begins with &quot;https&quot;</td>
    <td>3</td>
  </tr>
	<tr>
    <td>[attribute$=value]</td>
    <td>a[src$=&quot;.pdf&quot;]</td>
    <td>Selects every &lt;a&gt; element whose src attribute value ends with &quot;.pdf&quot;</td>
    <td>3</td>
  </tr>
	<tr>
    <td>[attribute*=value]</td>
    <td>a[src*=&quot;w3schools&quot;]</td>
    <td>Selects every &lt;a&gt; element whose src attribute value contains the substring 
	&quot;w3schools&quot;</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:first-of-type</td>
    <td>p:first-of-type</td>
    <td>Selects every &lt;p&gt; element that is the first &lt;p&gt; element of its parent</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:last-of-type</td>
    <td>p:last-of-type</td>
    <td>Selects every &lt;p&gt; element that is the last &lt;p&gt; element of its parent</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:only-of-type</td>
    <td>p:only-of-type</td>
    <td>Selects every &lt;p&gt; element that is the only &lt;p&gt; element of its 
	parent</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:only-child</td>
    <td>p:only-child</td>
    <td>Selects every &lt;p&gt; element that is the only child of its parent</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:nth-child(n)</td>
    <td>p:nth-child(2)</td>
    <td>Selects every &lt;p&gt; element that is the second child of its parent</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:nth-last-child(n)</td>
    <td>p:nth-last-child(2)</td>
    <td>Selects every &lt;p&gt; element that is the second child of its parent, counting 
	from the last child</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:nth-of-type(n)</td>
    <td>p:nth-of-type(2)</td>
    <td>Selects every &lt;p&gt; element that is the second &lt;p&gt; element of its parent</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:nth-last-of-type(n)</td>
    <td>p:nth-last-of-type(2)</td>
    <td>Selects every &lt;p&gt; element that is the second &lt;p&gt; element of its parent, counting 
	from the last child</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:last-child</td>
    <td>p:last-child</td>
    <td>Selects every &lt;p&gt; element that is the last child of its parent</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:root</td>
    <td>:root</td>
    <td>Selects the document's root element</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:empty</td>
    <td>p:empty</td>
    <td>Selects every &lt;p&gt; element that has no children (including text nodes)</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:target</td>
    <td>#news:target </td>
    <td>Selects the current active #news element (clicked on a URL containing that anchor name)</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:enabled</td>
    <td>input:enabled</td>
    <td>Selects every enabled &lt;input&gt; element</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:disabled</td>
    <td>input:disabled</td>
    <td>Selects every disabled &lt;input&gt; element</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:checked</td>
    <td>input:checked</td>
    <td>Selects every checked &lt;input&gt; element</td>
    <td>3</td>
  </tr>
	<tr>
    <td>:not(selector)</td>
    <td>:not(p)</td>
    <td>Selects every element that is not a &lt;p&gt; element</td>
    <td>3</td>
  </tr>
	<tr>
    <td>::selection</td>
    <td>::selection</td>
    <td>Selects the portion of an element that is selected by a user</td>
    <td>3</td>
  </tr>
	</table>

    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>