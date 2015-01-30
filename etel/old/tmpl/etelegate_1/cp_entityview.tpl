<script language="javascript">

var rootdir = '{$rootdir}';
var tempdir = '{$tempdir}';
var en_search_options = {$en_search_options};
var row = 1;
var en_search_option_inc = 1;
{literal}
function en_build_search_option()
{
	var tr = document.createElement('tr');
	var td1 = document.createElement('td');
	var sel = document.createElement('select');
	var curid = en_search_option_inc++;
	sel.setAttribute('id','en_search_by_'+curid);
	sel.setAttribute('curid',curid);
	tr.setAttribute('id','en_search_tr_'+curid);
	tr.appendChild(td1); 
	td1.appendChild(sel); 
	var len1 = en_search_options['search_options'].length;
	for(i=0;i<len1;i++)
	{
		var gopts = en_search_options['search_options'][i];
		var group = document.createElement('optgroup');
		sel.appendChild(group);
		group.setAttribute('label',gopts['g']);
		var len2 = gopts['o'].length;
		for(j=0;j<len2;j++)
		{
			var option = new Option(gopts['o'][j]['n'],gopts['o'][j]['k']);
			if(gopts['o'][j]['t']) option.setAttribute('type',gopts['o'][j]['t']);
			option.onclick = function() {en_update_search_by(curid,this)} ;	
			group.appendChild(option);
		}
	}
	
	var td2 = document.createElement('td');
	tr.appendChild(td2); 
	td2.setAttribute('id','en_search_td2_'+curid);
	var input = document.createElement('input');
	input.onkeyup = function() {en_search_submit_delay()} ;	
	input.setAttribute('id','en_search_'+curid);
	td2.appendChild(input); 
	
	var td3 = document.createElement('td');
	var btnsubmit = document.createElement('input');
	var btnadd = document.createElement('input');
	tr.appendChild(td3); 
	td3.appendChild(btnsubmit); 
	td3.appendChild(btnadd); 
	btnsubmit.setAttribute('id','en_search_submit_'+curid);
	btnsubmit.setAttribute('type','submit');
	btnsubmit.setAttribute('value','Search');
	btnsubmit.onclick = function() {en_search_submit()} ;	
	btnadd.setAttribute('id','en_search_add_'+curid);
	btnadd.setAttribute('type','button');
	btnadd.setAttribute('value','Add');
	btnadd.onclick = function() {en_add_search_option(curid)} ;	
	
	return tr;
}

var search_timeout;
function en_search_submit_delay(delay)
{
	if(!delay) delay = 1;
	clearTimeout(search_timeout);
	search_timeout = setTimeout('en_search_submit()',delay*1000);
}

function en_update_search_by(curid,obj)
{
	var td2 = $('en_search_td2_'+curid);
	var oldval = '';
	if($('en_search_'+curid) && $('en_search_'+curid).type=='text') oldval = $('en_search_'+curid).value;
	var type = obj.getAttribute('type');
	if(!type) type = 'text';
	opts = type.split('|');
	en_clear_children(td2);
	switch(opts[0])
	{
		default:
			var input = document.createElement('input');
			input.onkeyup = function() {en_search_submit_delay()} ;	
			input.setAttribute('id','en_search_'+curid);
			input.setAttribute('value',oldval);
			td2.appendChild(input); 
			break;
		case 'select':
			var selopts = opts[1].split(',');
			var input = document.createElement('select');
			input.setAttribute('id','en_search_'+curid);
			input.setAttribute('value',oldval);
			for(var i = 0;i< selopts.length;i++)
				input.appendChild(new Option(selopts[i],selopts[i]));			
			td2.appendChild(input); 
			break;
	
	}
}

function en_add_search_option(curid)
{
	tbody = $('en_search_tbody');
	btnadd = $('en_search_add_'+(curid));
	btnadd.setAttribute('value','Remove');
	btnadd.onclick = function() {en_remove_search_option(curid)} ;	
	tbody.appendChild(en_build_search_option());
}

function en_remove_search_option(curid)
{
	tbody = $('en_search_tbody');
	tr = $('en_search_tr_'+curid);
	tbody.removeChild(tr);
}

function en_build_search()
{
	var en_search = $('en_search');
	var table = document.createElement('table');
	applyClass(table,'report');
	var tbody = document.createElement('tbody');
	tbody.setAttribute('id','en_search_tbody');
	en_search.appendChild(table);
	table.appendChild(tbody);
	tbody.appendChild(en_build_search_option());
}
{/literal}
</script>
<script language="javascript" src="{$rootdir}/scripts/dynosearch.js"></script>

<div id="en_search"></div>
<div id="en_status" class="report"></div>
<div id="en_results"></div>

<script language="javascript">
en_build_search();
</script>
