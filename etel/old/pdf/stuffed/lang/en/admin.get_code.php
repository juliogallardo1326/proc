<?
$Lang['CodeGen']="Tracking code";







$Lang['CodeType1']="Javascript code";
$Lang['CodeType2']="Javascript (document.write)";
$Lang['CodeType3']="HTML code";
$Lang['CodeType4']="PHP code";

$Lang['ForSite']="Track visitors";
$Lang['ForShop']="Track sales";
$Lang['ForAction']="Track action";

$Lang['Refresh']="Generate";
$Lang['Code']="Code";

$Lang['SSL']="SSL";
$Lang['NoActionForSite']="No actions that are caught with tracking code were configured for the selected site.";

$Lang['TestItemName']="product";
$Lang['TestItemCnt']="1";
$Lang['TestItemValue']="10";



//////////////////////////////////////////////////////////////////////

$Lang['CodeHelpJs']=" Generally, the higher you place the code on the page, the more accurate 
statistics you get.";

$Lang['CodeHelpPhp']="<br><br>Please note, that the PHP tracking code should be placed 
inside of a PHP page BEFORE any content was sent to the browser.";


$Lang['CodeHelpHtml']=" Generally, the higher you place the code on the page, the more accurate 
statistics you get.<br><br>

Please note that the HTML tracking code can only be placed inside the &lt;BODY&gt; of the page.";


//////////////////////////////////////////////////////////////////////



$Lang['CodeHelpVis']="You should place the code presented below inside of all the pages of 
the site. ";

$Lang['CodeHelpAction']="You should place the code presented below inside the 
page of your site which if requested by a visitor should trigger an action.";

$Lang['CodeHelpSale']="You should place the code presented below inside a
\"thank you\" page, which is displayed at the last step of the ordering process.";



//////////////////////////////////////////////////////////////////////



$Lang['SaleCommentJs']="Please note, that the code above only tracks an event of the sale, which is
enough to calculate conversion ratio. However:<br><br>

<ol>
<li>If you also want to track the <b>total value of the sale</b>, you would need 
to use the nsCost variable in the tracking code like this:  

<blockquote style=\"color: #777;\">
var nsCost='total_value';
</blockquote>

and replace \"total_value\" with an actual value of the sale.<br><br></li>

<li>If you want to additionally track information about <b>particular products</b> 
that were sold, you would need to use the nsOrderItems variable in the tracking 
code like this: 

<blockquote style=\"color: #777;\">
nsOrderItems.push('{{product_name}}{{value}}{{quantity}}'); 
</blockquote>

and replace \"product_name\" with an actual name of the product, \"value\"
with an actual value of the product and \"quantity\" with an actual 
quantity that was sold. You can use the nsOrderItems variable in the same way
several times to specify several different products that were sold.<br><br></li>

<li>You can also track an <b>id of the order</b> from your own shopping system. 
For this you would need to use the nsOrderId variable in the tracking code like
this: 

<blockquote style=\"color: #777;\"> 
var nsOrderId='your_order_id';
</blockquote>

and replace \"your_order_id\" with an actual id of the order in your shopping
system. The maximum length of the id is 64 symbols and it can contain any 
alpha-numerical characters.<br><br></li>

<li>Along with the sale, you can also log any <b>additional information</b> that
would be visible in the Sales log. For this you would need to use the nsOrderInfo
variable in the tracking code like this: 

<blockquote style=\"color: #777;\"> 
var nsOrderInfo='some_data';
</blockquote>

and replace \"some_data\" with any data that you want to store along with the 
sale.</li>

</ol>";

$Lang['SaleCommentPhp']="Please note, that the code above only tracks an event of the sale, which is
enough to calculate conversion ratio. However:<br><br>

<ol>
<li>If you also want to track the <b>total value of the sale</b>, you would need 
to use the \$nsSTcost variable in the tracking code like this:  

<blockquote style=\"color: #777;\">
\$nsSTcost='total_value';
</blockquote>

and replace \"total_value\" with an actual value of the sale.<br><br></li>

<li>If you want to additionally track information about <b>particular products</b> 
that were sold, you would need to use the \$nsSTItems variable in the tracking 
code like this: 

<blockquote style=\"color: #777;\">
\$nsSTItems[0]['Name']='product_name';<br>
\$nsSTItems[0]['Value']='value';<br>
\$nsSTItems[0]['Cnt']='quantity'; 
</blockquote>

and replace \"product_name\" with an actual name of the product, \"value\"
with an actual value of the product and \"quantity\" with an actual 
quantity that was sold. You can use the nsOrderItems variable in the same way
several times to specify several different products that were sold.<br><br></li>

<li>You can also track an <b>id of the order</b> from your own shopping system. 
For this you would need to use the \$nsSToid variable in the tracking code like
this: 

<blockquote style=\"color: #777;\"> 
\$nsSToid='your_order_id';
</blockquote>

and replace \"your_order_id\" with an actual id of the order in your shopping
system. The maximum length of the id is 64 symbols and it can contain any 
alpha-numerical characters.<br><br></li>

<li>Along with the sale, you can also log any <b>additional information</b> that
would be visible in the Sales log. For this you would need to use the \$nsSToinfo
variable in the tracking code like this: 

<blockquote style=\"color: #777;\"> 
\$nsSToinfo='some_data';
</blockquote>

and replace \"some_data\" with any data that you want to store along with the 
sale.</li>

</ol>";

$Lang['SaleCommentHtml']="Please note, that the code above only tracks an event of the sale, which is
enough to calculate conversion ratio. However:<br><br>

<ol>
<li>If you also want to track the <b>total value of the sale</b>, you would need 
to add the following parameter to the image source URL in the tracking code above:<br> 

<blockquote style=\"color: #777;\">
&cs=total_value
</blockquote>

and replace \"total_value\" with an actual value of the sale.<br><br></li>

<li>If you want to additionally track information about <b>particular products</b> 
that were sold, you would need to add the following parameter to the image 
source URL in the tracking code above:<br>

<blockquote style=\"color: #777;\"> 
&itm[]={{product_name}}{{value}}{{quantity}}
</blockquote>

and replace \"product_name\" with an actual name of the product, \"value\"
with an actual value of the product and \"quantity\" with an actual 
quantity that was sold. You can use this \"itm[]\" parameter several times 
to specify several different products that were sold.<br><br></li>

<li>You can also track an <b>id of the order</b> from your own shopping system. For this you
would need to add the following parameter to the image source URL in the tracking 
code above:<br>

<blockquote style=\"color: #777;\"> 
&oid=your_order_id
</blockquote>

and replace \"your_order_id\" with an actual id of the order in your shopping
system. The maximum length of the id is 64 symbols and it can contain any 
alpha-numerical characters.<br><br></li>

<li>Along with the sale, you can also log any <b>additional information</b> that
would be visible in the Sales log. For this you would need to add the following 
parameter to the image source URL in the tracking code above:<br>

<blockquote style=\"color: #777;\"> 
&oinfo=some_data
</blockquote>

and replace \"some_data\" with any data that you want to store along with the 
sale.</li>

</ol>";

?>