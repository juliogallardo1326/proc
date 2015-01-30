// DynoSearch - Written by Ari Asulin 11-12-06
try {
	window.captureEvents(Event.MOUSEDOWN | Event.MOUSEUP);
	window.onmousedown= startDrag;
	window.onmouseup= endDrag;
} catch (e) {}
var mousedrag = 0;
var dragmode = -1;
var en_row = 1;
var en_search_option_inc = 1;
var en_search_options = null;

function removeClass(obj,cl){ return obj && (obj.className=obj.className.replace(new RegExp("^"+cl+"\\b\\s*|\\s*\\b"+cl+"\\b",'g'),'')); }
function applyClass(obj,cl){ removeClass(obj,cl); return obj && (obj.className+=(obj.className.length>0?' ':'')+cl); }

function applyStyle(obj,rule)
{
	var st = obj.getAttribute('style');
	obj.setAttribute('style',(st?st:'')+rule);
}

function startDrag(e) {
  window.captureEvents(Event.MOUSEMOVE);
  if(!mousedrag) mousedrag=1;
}

function endDrag(e) {
  window.releaseEvents(Event.MOUSEMOVE);
  mousedrag=0;
  dragmode=-1;
}

function en_toggle_row(id,drag)
{
	if(mousedrag!=1 && drag) return;
	
	var chk = $('chk_'+id);
	var selected = chk.checked;
	
	if(drag)
	{
		if(dragmode == -1) dragmode = !selected;
		chk.checked = dragmode;   
	}
	else
		chk.checked = !selected;   
}

function poplink(txt,hlink)
{
	win = window.open('', "poplink", 'toolbar=no,width=500,height=300,scrollbars=yes,titlebar=no,resizable=yes,statusbar=yes');
	win.document.open();
	win.document.write(txt);
	win.document.write("<iframe src='"+hlink+"' width='110%' height='110%' frameborder='0' ><iframe>");
	win.document.close();
	win.document.bgColor="lightblue";
}

function en_get_poplink(data,opts)
{
	//if(!data[opts['pl'][0]]) return document.createTextNode('No '+opts['n']);
	var txt = "Link for ("+opts['n']+")<BR>";
	var hlink = data[opts['pl'][0]];	
	txt += "Link: <a target=\"_blank\" href=\""+hlink+"\">Click Here</a><BR>";
	if(data[opts['pl'][1]]) txt += "Username: "+data[opts['pl'][1]]+"<BR>";
	if(data[opts['pl'][2]]) txt += "Password: "+data[opts['pl'][2]]+"<BR>";
	
	var src = 'en_opt_'+opts['k']+'.png';
		
	if(!hlink)
	{
		hlink = 'about:blank';
		txt = "No Link Available";
		var src = 'en_opt_disabled.png';
	}
	var href = en_get_button(src,"javascript:poplink('"+txt+"','"+hlink+"')",opts['n'],true);
	return href;
}

function en_get_button_rollover(obj,src)
{
	obj.setAttribute('src',tempdir+'/images/'+src);
}

function en_get_button(src,lnk,title,rollover)
{
	var href = document.createElement("a");
	href.setAttribute("href", lnk);
	var icon = document.createElement("img");
	icon.src = tempdir+'/images/'+src;
	icon.setAttribute('border',0);
	icon.alt = title;
	icon.title = title;
	icon.setAttribute('hspace','2px');
	href.appendChild(icon);
	if(rollover)
	{
		//icon.onmouseover = function() {en_get_button_rollover(this,'en_opt_down.png')};	
		//icon.onmouseout = function() {en_get_button_rollover(this,src)};	
		//icon.onmousedown = function() {en_get_button_rollover(this,'en_opt_down.png')};	
		//icon.onmouseup = function() {en_get_button_rollover(this,src)};	
	}
	return href;	
}

function en_get_function(rowdata,opts)
{ 
	var len = opts['f'].length;
	var func ='';
	for(var i = 0;i<len;i++)
	{
		var optinfo = opts['f'][i].split('|');
		if(optinfo[0]=='k')
			func += (i>0?',':'')+"'s|"+optinfo[1]+'|'+rowdata[optinfo[2]]+"'";
		else
			func += (i>0?',':'')+"'"+opts['f'][i]+"'";
	}
	return "en_set_func(Array("+func+"),'"+rowdata['id']+"','"+rowdata['sid']+"')";
}

function en_get_options(id,opts)
{
	var div = document.createElement("div");
	div.setAttribute("width", "100%");
	div.setAttribute("border", "1");
	div.setAttribute("align", "right");
	var len = opts.length;
	if(id=='all') div.appendChild(document.createTextNode('With Selected: '));
	for(var i = 0;i<len;i++)
	{
		if(opts[i])
		{
			var type = opts[i].split('|');  
			var href = en_get_button( 'en_opt_'+type[0]+'.png',"javascript:en_get_info({'id':'"+id+"','type':'"+type[0]+"'})",type[1],true);
			div.appendChild(href);
			div.appendChild(document.createTextNode(' '));
		}
	}
	return div;
}

function en_remove_info(id,type)
{
	en_clear_children($('tdinfo_'+id),"table_"+type+"_"+id);	
}

function en_build_info(tdinfo,data)
{
	var id = data['id'];
	var table_id = "table_"+data['type']+"_"+id;
	var table = en_get_tdinfo_child(tdinfo,table_id);
	if(table)
		en_clear_children(table);
	else
	{
		table = document.createElement("table");
		tdinfo.appendChild(table);
	}
	applyClass(table,"invoice");
	table.setAttribute("width", "90%");
	table.setAttribute("cellspacing", "0");
	table.setAttribute("border", "1");
	table.setAttribute("id", table_id);
	var tbody = document.createElement("tbody");
	table.appendChild(tbody);
	var len = data['info'].length;
	if(len || data['stats'])
	{
		if(data['stats']) data['use_tdcol'] = len+1;
		en_build_row(tbody,{'id':'all','rowid':'all'},data);
		for (var i = 0;i<len;i++)
		{
			data['info'][i]['id'] = id;
			data['info'][i]['rowid']=data['type']+'_'+i;
			en_build_row(tbody,data['info'][i],data);
		}
	}
	else
	{
		tbody.appendChild(document.createElement('tr'));
		tbody.childNodes[0].appendChild(document.createElement('td'));
		tbody.childNodes[0].childNodes[0].appendChild(en_get_button('en_opt_close.png',"javascript:en_remove_info('"+data['id']+"','"+data['type']+"')",'(Close)'),true);
		tbody.childNodes[0].childNodes[0].appendChild(document.createTextNode(data['msg']));
	}
	tdinfo.setAttribute("align", 'right'); 

}

function en_build_infoheader(tbody,fields)
{
	en_row = 1;
	var tr = document.createElement("tr");   
	applyClass(tr,"infoSubSection");
	tr.setAttribute("style", "font-weight:bold");   
	applyClass(tr,"row"+en_row);
	var len =fields.length;
	if(len)
	{     
		var width = Math.round(100/len);
		for(var i=0;i<len;i++)
		{
			var opts = fields[i];
			var td = document.createElement("td");  
			td.setAttribute("width", "%"+width);
			var txt = document.createTextNode(opts['n']);
			td.appendChild(txt);
			
			tr.appendChild(td);
		}
	}	
	tbody.appendChild(tr);
}

function en_get_header_field(opts,data)
{
	var href1 = document.createElement("a"); 
	href1.innerHTML = '&darr;';
	href1.title = 'Sort Descending';
	href1.setAttribute("href", "javascript:en_search_submit({'sortby':'"+opts['k']+"','sortdir':'DESC'})");
	href1.setAttribute("style", "text-decoration:none");
	
	var href2 = document.createElement("a"); 
	href2.innerHTML = '&uarr;';
	href2.title = 'Sort Ascending';
	href2.setAttribute("href", "javascript:en_search_submit({'sortby':'"+opts['k']+"','sortdir':'ASC'})");
	href2.setAttribute("style", "text-decoration:none");
	
	var node = document.createElement("div"); 
	if(opts['k'] && data['is_top']) node.appendChild(href1);
	node.appendChild(document.createTextNode(' '+opts['n']+' '));
	if(opts['k'] && data['is_top']) node.appendChild(href2);
	return node;
}

function en_get_chk_box(data,opts)
{ 
	var chk = document.createElement('input');
	chk.setAttribute("id", 'chk_'+data['rowid']);    
	chk.setAttribute("en_ID", data['id']); 
	chk.setAttribute("type", "checkbox");
	if(data['rowid']!='all')
		chk.onmouseover = function() {en_toggle_row(data['rowid'],true)} ;	
	else
		chk.onchange = function() {en_set_all_chk(this.checked)} ;
	return chk;   
}

function en_get_label(txt)
{
	var label = document.createElement('label');
	label.appendChild(document.createTextNode(txt));
	return label;
}

function en_get_pre(txt)
{
	pre=document.createElement("pre");
	pre.appendChild(txt);
	return pre;
}

function en_get_link(rowdata,opts)
{
	var href = document.createElement("a"); 
	var hlink = rowdata[opts['dl'][0]];
	if(!hlink) hlink = 'about:blank';	
	href.appendChild(document.createTextNode(rowdata[opts['k']]));
	href.setAttribute("href", hlink);
	if(opts['dl'][1])href.setAttribute("title", opts['dl'][1]);
	return href;
}

function en_get_edit_opts(rowdata,opts)
{
	var input;
	var func = '';
	if(opts['f']) func = en_get_function(rowdata,opts);
	switch(opts['edit'])
	{
		case 'button':
			input = en_get_button( 'en_opt_'+opts['k']+'.png','javascript:'+func,opts['n'],true);
		
			break;
		case 'textarea':
			input = document.createElement(opts['edit']);
			input.setAttribute('style','width:100%;height:100px;');
			input.setAttribute('rows',10);
			input.setAttribute('cols',90);
			if(opts['tstamp']) input.onclick = function() {addElementNotes(this)};
			break;
		case 'select':
			input = document.createElement(opts['edit']);
			if(opts['selopts'])
				for(var i=0;i<opts['selopts'].length;i++)
				{
					var val = opts['selopts'][i].split('|');
					input.appendChild(new Option((val[1]?val[1]:val[0]),val[0]),(rowdata[opts['k']]==val[0]?true:false));
				}
			break;
		case 'checkbox':
			input = document.createElement('input');
			input.setAttribute('type','checkbox');
			input.checked = (rowdata[opts['k']]?1:0);
			input.onclick = function() {eval(func)};
			input.value = 1;
			break;
		default:
			input = document.createElement('input');
			break;			
	}
	if(!input.getAttribute('value') && opts['k']) input.value = (rowdata[opts['k']]?rowdata[opts['k']]:'');
	input.setAttribute('id','en_edit_'+rowdata['id']+'_'+rowdata['sid']+'_'+opts['k']);
	if(opts['n'] && opts['edit']!='button')
	{
		var label = en_get_label(opts['n']);	
		label.appendChild(input);
		input.setAttribute('title',opts['n']);
		return label;
	}
	return input;
}

function en_get_image(rowdata,opts)
{
	var img = document.createElement('img');
	img.src = tempdir+'/images/'+opts['img'];
	return img;
}

function en_build_field(rowdata,opts,data,els)
{ 
	if(rowdata['opts'])
	if(rowdata['opts'][opts['k']]) 
	{
		$H(rowdata['opts'][opts['k']]).each(function(pair) {
			opts[pair.key] = pair.value
		});
	}
	if(opts['ar'] && rowdata['id']!='all')
	{
		var div = document.createElement("div");
		var len = opts['ar'].length;
		for(var i=0;i<len;i++)
			div.appendChild(en_build_field(rowdata,$H(opts['ar'][i]),data,els));
		els['obj'] = div;
	}
	else if(opts['edit'] && rowdata['id']!='all')
		els['obj'] = en_get_edit_opts(rowdata,opts);
	else if(opts['pl'] && rowdata['id']!='all')
		els['obj'] = en_get_poplink(rowdata,opts);
	else if(opts['dl'])
		els['obj'] = en_get_link(rowdata,opts);
	else if(opts['btn'])
		els['obj'] = en_get_button('en_opt_'+opts['k']+'.png',rowdata[opts['btn'][0]],opts['n'],true);
	else if(opts['k']=='op')
		els['obj'] = en_get_options(rowdata['id'],opts['opar']);
	else if(opts['chk'])
		els['obj'] = en_get_chk_box(rowdata,opts);
	else if(rowdata['id']=='all')
		els['obj'] = en_get_header_field(opts,data);
	else if(opts['node'])
		els['obj'] = document.createElement(opts['node']);
	else if(opts['txt'])
		els['obj'] = en_get_label(opts['txt']);
	else 
		els['obj'] = en_get_label(rowdata[opts['k']]);
	if(opts['pre'] && rowdata['id']!='all')
		els['obj'] = en_get_pre(els['obj']);
		
	if(opts['attrib'] && rowdata['id']!='all')
		en_apply_attrib(els,opts['attrib']);
		
	if(rowdata['attrib'] && rowdata['id']!='all')
		en_apply_attrib(els,rowdata['attrib']);
		
	return els['obj'];
}

function en_apply_attrib(els,attrib)
{
	if(attrib)
		for(var i=0;i<attrib.length;i++)
		{	
			var attr = attrib[i].split('|');
			if(els[attr[0]]) 
			{
				if(attr[1]=='class') applyClass(els[attr[0]],attr[2]);
				else if(attr[1]=='style') applyStyle(els[attr[0]],attr[2]);
				else els[attr[0]].setAttribute(attr[1],attr[2]); 
			}
		}	
}

function en_build_row(tbody,rowdata,data)
{  
	en_row = 3-en_row;
	var els = Array();
	els['tr'] = document.createElement("tr");  
	els['tr'].setAttribute("style", 'vertical-align:top');
	
	var rlen = (data['info']?data['info'].length:0);
	if(rowdata['rowid']=='all') // Header
	{
		//els['tr'].setAttribute("id", 'tr_all');   
		els['tr'].setAttribute("align", 'center');   
		els['tr'].setAttribute("height", '20px');   
		els['tr'].vAlign = 'top';   
		applyClass(els['tr'],"infoHeader");
		if(data['use_tab'])
		{
			els['tdtab2'] = document.createElement("td");
			els['tdtab2'].rowSpan = rlen+1;
			els['tdtab2'].setAttribute('width','20px');
			els['tdtab2'].appendChild(en_get_button('en_opt_close.png',"javascript:en_remove_info('"+data['id']+"','"+data['type']+"')",'(Close)'),true);
			els['tdtab2'].appendChild(en_get_image(rowdata,{'img':'en_tab_'+data['type']+'.png'}));
			els['tr'].appendChild(els['tdtab2']);
		}
		if(data['use_tdcol'] && data['stats']['opts'])
		{
			els['tdcol'] = document.createElement("td");
			applyClass(els['tdcol'],"Data");
			els['tdcol'].rowSpan = data['use_tdcol'];
			els['tdcol'].setAttribute('width','25%');
			data['stats']['data']['id']=data['id'];
			els['tdcol'].appendChild(en_build_field(data['stats']['data'],$H(data['stats']['opts']),data,els));
			els['tr'].appendChild(els['tdcol']);
		}
	}
	else
	   applyClass(els['tr'],"row"+en_row);
	   
	tbody.appendChild(els['tr']);
	
	var clen =data['display_fields'].length;
	if(clen)
	{     
		for(var i=0;i<clen;i++)
		{
			var opts = data['display_fields'][i];			
			els['td'] = document.createElement("td"); 
			els['td'].appendChild(en_build_field(rowdata,$H(opts),data,els));
			els['tr'].appendChild(els['td']);
		}
	}
	
	if(data['is_top'])
	{
		els['tr2'] = document.createElement("tr");  
		els['tr2'].setAttribute("en_ID", data['id']);  
		
		els['tdinfo'] = document.createElement("td");
		applyClass(els['tdinfo'],"infoSubSection row3");   
		els['tdinfo'].setAttribute("colspan", clen); 
		els['tdinfo'].colSpan = clen;       
		els['tdinfo'].setAttribute("id", 'tdinfo_'+rowdata['id']); 
		els['tr2'].appendChild(els['tdinfo']);
		tbody.appendChild(els['tr2']);
	}
}

function en_build_link(txt,hreflink)
{
	var href = document.createElement('a');
	href.setAttribute('href',hreflink);
	href.innerHTML = txt;
	return href;
}

function en_build_hidden(id,value)
{
	var hid1 = document.createElement("input");
	hid1.setAttribute('type','hidden');
	hid1.setAttribute('id',id);
	hid1.setAttribute('value',value);
	return hid1;
}

function en_build_footer(tbody,data)
{
	var tr = document.createElement("tr");  
	tr.setAttribute("id", 'tr_all');    
	applyClass(tr,"infoHeader");  
	tbody.appendChild(tr);
	
	var td1 = document.createElement("td");     
	var chk = document.createElement('input');
	chk.setAttribute("id", 'chk_all2');
	chk.setAttribute("type", "checkbox");
	chk.onchange = function() {en_set_all_chk(this.checked)} ;
	td1.setAttribute("width", "18px");		
	td1.appendChild(chk);
	tr.appendChild(td1);
	
	var len =data['display_fields'].length;
	var td2 = document.createElement("td");
	td2.setAttribute("align", 'center');	
	td2.colSpan = len-2;  
	tr.appendChild(td2);
	
	var td3 = document.createElement("td");
	td3.setAttribute("align", 'right');	
	tr.appendChild(td3);
	
	var pagenum = data['num_rows']/data['limit'];
	var pagecur = data['limitfrom']/data['limit'];
	var pagestart = pagecur-5;
	if(pagestart<0) pagestart=0;
	var pageend = pagecur+5;
	if(pageend>pagenum) pageend=pagenum;
	td2.appendChild(en_build_link('<<',"javascript:en_search_submit({'limitfrom':'"+((pagecur-1)*data['limit'])+"'})"));
		
	for(var i = pagestart;i<pageend;i++)
	{
		td2.appendChild(document.createTextNode(' | '));
		td2.appendChild(en_build_link(i+1,"javascript:en_search_submit({'limitfrom':'"+((i)*data['limit'])+"'})"));
		
	}
	
	td2.appendChild(document.createTextNode(' | '));
	td2.appendChild(en_build_link('>>',"javascript:en_search_submit({'limitfrom':'"+((pagecur+1)*data['limit'])+"'})"));
	td2.appendChild(document.createTextNode(' '));
	
	var perpage = document.createElement("select");
	perpage.setAttribute('id','en_limit');
	var selopts = Array(10,25,50,100,200,300);
	for(var i = 0;i<selopts.length;i++)
		perpage.add(new Option(selopts[i], selopts[i],(selopts[i]==data['limit']?true:false)),undefined);
	perpage.setAttribute('onchange',"javascript:en_search_submit()");
	
	td3.appendChild(en_build_hidden('en_limitfrom',data['limitfrom']));
	td3.appendChild(perpage);
	 

}

function en_update_status(txt,clear)
{
	var en_status = $('en_status');
	if(!en_status) return;
	if(clear)
		while (en_status.childNodes.length >= 1)
    		en_status.removeChild(en_status.firstChild);
	
	en_status.appendChild(document.createTextNode(txt));
	
}

function en_search_response(response)
{
	var results = $('en_results');
	en_clear_children(results);
	var data = JSON.parse(response.responseText);
	if(!data['silent']) en_update_status("Query Successful...Updating List...",true);
	
	if(en_search_options && data['func'] != en_search_options['search_func'])
	{
		if(!data['silent']) en_update_status("Error Querying Database. Your session may have expired. Please log in again.");
		return 0;
	}
	if(!data['silent']) en_update_status('('+data['num_rows']+') Rows Found in ('+data['duration']+') Seconds.');
	
	var len =data['entity_list'].length;
	var table = document.createElement("table");
	applyClass(table,"invoice");  
	table.setAttribute("width", "100%");
	table.setAttribute("cellspacing", "0");
	var tbody = document.createElement("tbody");
	table.appendChild(tbody);
	results.appendChild(table);
	data['is_top']=true;
	if(len>1) en_build_row(tbody,{'id':'all','rowid':'all'},data);
	en_row = 1;
	if(len)
		for (var i = 0;i<len;i++)
		{
			data['entity_list'][i]['rowid']=i;
			en_build_row(tbody,data['entity_list'][i],data);
		}
	if(len>1) en_build_footer(tbody,data);
	
	if(data['cmd'])
		for (var i = 0;i<data['cmd'].length;i++)
		{
			en_get_info(data['cmd'][i]);
		}
}

function en_search_submit(opts)
{
	tbody = $('en_search_tbody');	
	var searchparams = ''
	var en_search_logic = ($('en_search_logic')?$F('en_search_logic'):'');
	for(var i = 0;i<tbody.childNodes.length;i++)
	{
		curid = tbody.childNodes[i].getAttribute('curid');
		var en_search = ($('en_search_'+curid)?$F('en_search_'+curid):'');
		var en_search_by = ($('en_search_by_'+curid)?$F('en_search_by_'+curid):'');
		searchparams += "&en_search[]="+en_search+"&en_search_by[]="+en_search_by;
	}
	searchparams += "&logic="+en_search_logic;
	//alert(searchparams);
	var pars = 'func='+en_search_options['search_func']+searchparams;
	if(opts && opts['silent']) pars += '&silent=1';
	if(opts && opts['sortby']) pars += '&sortby='+opts['sortby']+'&sortdir='+opts['sortdir'];
	if($('en_limit')) pars += '&limit='+$F('en_limit');
	if($('en_limitfrom') && opts && opts['limitfrom']) $('en_limitfrom').value = opts['limitfrom'];
	if($('en_limitfrom')) pars += '&limitfrom='+$F('en_limitfrom');
	en_submit_ajax(pars,en_search_response);
	en_update_status("Querying Database...",true);

}

function en_submit_ajax(pars,callback)
{
	var url = rootdir+'/admin/admin_JOSN.php';
	//if(etel_debug_mode) 
	//	window.open (url+'?'+pars+'&debug=1','en',"'scrollbars=yes,scrolling=yes,titlebar=no,resizable=yes,width=830, height=630'");
	var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: callback });
}

function en_clear_children(obj,id)
{
	if(!obj) return;
	var len = obj.childNodes.length;
	for(var i = len-1;i>=0;i--)
		if(!id || id==obj.childNodes[i].getAttribute('id')) 
			obj.removeChild(obj.childNodes[i]);
}

function en_get_tdinfo_child(tdinfo,type)
{
	var len = tdinfo.childNodes.length;
	for(var i = len-1;i>=0;i--)
		if(type==tdinfo.childNodes[i].getAttribute('id')) 
			return tdinfo.childNodes[i];
	return false;
}

function en_build_search_option(tbody)
{
	var tr = document.createElement('tr');
	tbody.appendChild(tr);
	var td1 = document.createElement('td');
	var sel = document.createElement('select');
	var curid = en_search_option_inc++;
	sel.setAttribute('id','en_search_by_'+curid);
	sel.setAttribute('curid',curid);
	sel.onchange = function() {en_update_search_by(curid,this.options[this.selectedIndex]);en_search_submit_delay()} ;
	tr.setAttribute('id','en_search_tr_'+curid);
	tr.setAttribute('curid',curid);
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
			if(!selectoption) var selectoption = option;
			if(gopts['o'][j]['t']) option.setAttribute('type',gopts['o'][j]['t']);
			if(gopts['o'][j]['c']) option.setAttribute('compare',gopts['o'][j]['c']);
			option.onclick = function() {en_update_search_by(curid,this);en_search_submit_delay()} ;
			group.appendChild(option);
		}
	}
	
	var td2 = document.createElement('td');
	td2.setAttribute('id','en_search_td2_'+curid);
	tr.appendChild(td2); 
	
	var td3 = document.createElement('td');
	var btnadd = document.createElement('input');
	btnadd.setAttribute('type','button');
	btnadd.setAttribute('value','+');
	btnadd.onclick = function() {en_add_search_option()} ;	
	var btnrem = btnadd.cloneNode(false);
	btnrem.setAttribute('value','-');
	btnrem.onclick = function() {en_remove_search_option(curid)} ;	
	
	tr.appendChild(td3); 
	td3.appendChild(btnadd); 
	td3.appendChild(btnrem);
	en_update_search_by(curid,selectoption);	
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
	var compare = obj.getAttribute('compare');
	if(!type) type = 'text';
	opts = type.split('|');
	en_clear_children(td2);
	td2.appendChild(document.createTextNode(' Is '+(compare?compare:'Equal To')+' ')); 
	switch(opts[0])
	{
		case 'select':
			var selopts = opts[1].split(',');
			var input = document.createElement('select');
			input.setAttribute('id','en_search_'+curid);
			input.setAttribute('value',oldval);
			for(var i = 0;i< selopts.length;i++)
			{
				var seloptsvals = selopts[i].split(':');
				input.appendChild(new Option(seloptsvals[1]?seloptsvals[1]:seloptsvals[0],seloptsvals[0]));			
			}
			td2.appendChild(input); 
			break;
		default:
			var input = document.createElement('input');
			input.onkeyup = function() {en_search_submit_delay()} ;	
			input.setAttribute('id','en_search_'+curid);
			input.setAttribute('value',oldval);
			td2.appendChild(input); 
			break;
	
	}		
	input.focus();
}

function en_add_search_option()
{
	tbody = $('en_search_tbody');	
	en_build_search_option(tbody);
}

function en_remove_search_option(curid)
{
	tbody = $('en_search_tbody');
	tr = $('en_search_tr_'+curid);
	if(tbody.childNodes.length>1) tbody.removeChild(tr);
}

function en_build_search()
{
	var en_search = $('en_search');
	var table = document.createElement('table');
	var tbody = document.createElement('tbody');
	tbody.setAttribute('id','en_search_tbody');
	en_search.appendChild(table);
	table.appendChild(tbody);
	en_build_search_option(tbody);
	
	var div = document.createElement('div');
	en_search.appendChild(div);
	
	var btnsubmit = document.createElement('input');
	btnsubmit.setAttribute('id','en_search_submit');
	btnsubmit.setAttribute('type','submit');
	btnsubmit.setAttribute('value','Search');
	btnsubmit.onclick = function() {en_search_submit()} ;
	
	var logic = document.createElement('select');
	logic.setAttribute('id','en_search_logic');
	logic.appendChild(new Option('OR','OR')); logic.appendChild(new Option('AND','AND')); 
	logic.onchange = function() {en_search_submit()} ;
	div.appendChild(document.createTextNode("Search Logic: ")); 
	div.appendChild(logic); 
	div.appendChild(document.createTextNode(" ")); 
	div.appendChild(btnsubmit); 
}

function en_get_info(opts)
{
	id = opts['id'];
	opts['func']='getEntityInfo';
	if(id=='all')
	{
		var idarray = en_get_all_chk(true);
		if(idarray.length==0)
		{
			en_set_all_chk(true);
			idarray = en_get_all_chk(true);
		}
		opts['id']=(idarray).join(',');
	}	
	var pars = ($H(opts).toQueryString());
	en_submit_ajax(pars,en_get_info_response);
	if(!opts['silent']) en_update_status("Querying Database...",true);	
}

function en_get_info_response(response)
{
	var data = JSON.parse(response.responseText);
	if(!data['silent']) en_update_status("Updating List...",true);
	if(data['func'] != "getEntityInfo")
	{
		en_update_status("Error Querying Database. Your session may have expired. Please log in again.");
		return 0;
	}
	var len = data['entity_info'].length;
	en_row = 1;
	for(var i=0;i<len;i++)
	{
		var id = data['entity_info'][i]['id'];
		var tdinfo = $('tdinfo_'+id);
		en_build_info(tdinfo,data['entity_info'][i]);
	}
	if(!data['silent']) en_update_status('('+data['num_rows']+') Rows Updated in ('+data['duration']+') Seconds.');
}

function en_set_func(par,id,sid)
{ 
	var len = par.length;
	var func ='';
	for(var i = 0;i<len;i++)
	{
		var parinfo = par[i].split('|');
		func += '&'+parinfo[1]+'=';
		if(parinfo[0]=='p')
			func += encodeURIComponent(prompt(parinfo[2],(parinfo[3]?parinfo[3]:'')));
		else if(parinfo[0]=='q')
			func += (confirm(parinfo[2])?'1':'0');
		else if(parinfo[0]=='fld')
		{
			if ($('en_edit_'+id+'_'+sid+'_'+parinfo[2]).getAttribute('type')=='checkbox') func += ($('en_edit_'+id+'_'+sid+'_'+parinfo[2]).checked?'1':'0');
			else func += encodeURIComponent($('en_edit_'+id+'_'+sid+'_'+parinfo[2]).value);
		}
		else
			func += encodeURIComponent(parinfo[2]);
	}
	
	var url = rootdir+'/admin/admin_JOSN.php';
	var pars = 'func=setEntityInfo&id='+id+func;
	en_submit_ajax(pars,en_set_func_response);
	en_update_status("Querying Database...",true);	
}

function en_set_func_response(response)
{
	var data = JSON.parse(response.responseText);
	en_update_status("Updating ...",true);
	if(data['func'] != "setEntityInfo")
	{
		en_update_status("Error Querying Database. Your session may have expired. Please log in again.");
		return 0;
	}
	var len = data['result'].length;
	if(len)
		for(var i=0;i<len;i++)
		{
			en_update_status(data['result'][i]['msg']);
			if(data['result'][i]['update'])
			{
				data['result'][i]['update']['silent']=true;
				en_get_info(data['result'][i]['update']);
			}
		}
}

function en_get_all_chk(asarray)
{
	var i=0;
	var j=0;
	var ret = Array();
	while($('chk_'+i))
	{
		if($('chk_'+i).checked)
			ret[j++]=$('chk_'+i).getAttribute('en_ID');
		i++;
	}
	return ret;
}

function en_set_all_chk(val)
{
	var i=0;
	if($('chk_all')) $('chk_all').checked = val;
	if($('chk_all2')) $('chk_all2').checked = val;
	while($('chk_'+i))
	{
		$('chk_'+i).checked = val;
		i++;
	}
}
