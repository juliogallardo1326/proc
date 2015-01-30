<?
class log_class
{
	var $actors;
	var $actions;
	var $text;
	var $date_from;
	var $date_to;
	var $limit_start;
	var $limit_num;
	var $logs;
	var $log_count;
	
	function log_class()
	{
	}

	function request_params($limit=NULL)
	{
		$params = "";
		foreach($_REQUEST as $var => $val)
		if($limit == NULL || stristr($var,$limit)!== FALSE)
			if(!is_array($val))
				$params .= ($params == "" ? "" : "&") . $var . "=" . urlencode($val);
			else
				foreach($val as $index => $v)
					$params .= ($params == "" ? "" : "&") . $var . "[]=" . urlencode($v);
		return $params;
	}

	function request_form($limit=NULL)
	{
		$params = "";
		foreach($_REQUEST as $var => $val)
		if($limit == NULL || stristr($var,$limit)!== FALSE)
			if(!is_array($val))
				$params .= "<input type='hidden' name='$var' value='" . $val . "'>";
			else
				foreach($val as $index => $v)
					$params .= "<input type='hidden' name='" . $var. "[]' value='" . $v . "'>";
		return $params;
	}
		
	function set_actors($actors)
	{
		if(!is_array($actors))
			$this->actors = array($actors);
		else
			$this->actors = $actors;
	}

	function set_actions($actions)
	{
		if(!is_array($actions))
			$this->actions = array($actions);
		else
			$this->actions = $actions;
	}
	
	function set_text($text)
	{
		$this->text = $text;
	}
	
	function set_date_range($from,$to)
	{
		$this->date_from = strtotime($from);
		$this->date_to = strtotime($to);
	}
	
	function set_limit($start,$number)
	{
		if($number > 500) $number = 500;
		$this->limit_start = $start;
		$this->limit_num = $number;
	}
	
	function get_logs()
	{
		$sql_text = "";
		if($this->text) $sql_text = "AND lg_txt LIKE ('%" . $this->text . "%')";
		$sql_actors = "";
		if($this->actors[0] != NULL) $sql_actors = "AND lg_actor IN ('" . implode("','",$this->actors) . "')";
		$sql_actions = "";
		if($this->actions[0] != NULL) $sql_actions = "AND lg_action IN ('" . implode("','",$this->actions) . "')";
		$sql_date = "";
		if($this->date_from && $this->date_to) $sql_date = "AND lg_timestamp BETWEEN " . $this->date_from . " AND " . $this->date_to;
		$sql_limit = "";
		if($this->limit_start || $this->limit_num)
		$sql_limit = "LIMIT " . intval($this->limit_start) . "," . $this->limit_num;

		$sql = "
			SELECT 
				COUNT(lg_id) AS log_count
			FROM
				cs_log
			WHERE
				1
				$sql_actors
				$sql_actions
				$sql_text
				$sql_date
		";
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$r = mysql_fetch_assoc($res);
		$this->log_count = $r['log_count'];
		
		$sql = "
			SELECT 
				*
			FROM
				cs_log
			WHERE
				1
				$sql_actors
				$sql_actions
				$sql_text
				$sql_date
			ORDER BY
				lg_timestamp DESC
			$sql_limit
			
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$list = array();
		while($r = mysql_fetch_assoc($res))
			$list[] = $r;
		$this->logs=$list;
	}
	
	function render_logs()
	{
		$total_trans = $this->log_count;
		$start_trans = $this->limit_start;
		$end_trans = $this->limit_start + $this->limit_num;
		
		$show_next = true;
		$show_prev = true;
		
		if($end_trans > $total_trans)
			$show_next = false;
		
		if($start_trans == 0)
			$show_prev = false;
			
		if($show_prev || $show_next)
		{
			$page_links = "";
			$num_pages = ceil($total_trans / $this->limit_num);
			$cur_page = floor($this->limit_start / $this->limit_num);
			
			$start_page = $cur_page - 10 > 0 ? $cur_page - 10 : 0;
			$end_page = $cur_page + 10 < $num_pages ? $cur_page + 10 : $num_pages;
			
			for($j=$start_page;$j<$end_page;$j++)
			{
				if($j==$cur_page)
					$page_link = "<b>" . ($j+1) . "</b>";
				else
				{
					$_REQUEST['frm_page_offset'] = $j * $this->limit_num;
					$params = $this->request_params("frm_");
					$page_link = "<a href='$PHP_SELF?$params'>" . ($j+1) . "</a>";
				}
			
				$page_links .= ($page_links == "" ? "" : " | " ) . $page_link;
			}
			
			$html .= "<p>$page_links</p>";
		}
		else
			$html .= "<p><b>All Records Displayed</b></p>";
		
		
		$html .= "<table width='100%'>";

		foreach($this->logs as $log_index => $log_info)
		{
			$html .= "
				<tr>
				<td>ID</td>
				<td>Date</td>
				<td>Actor</td>
				<td>Action</td>
				<td>Item ID</td>
				</tr>
			";
			$html .= "
				<tr>
				<td valign='top'>" . $log_info['lg_id'] . "</td>
				<td valign='top'>" . date("F jS Y",$log_info['lg_timestamp']) . "</td>
				<td valign='top'>" . $log_info['lg_actor'] . "</td>
				<td valign='top'>" . $log_info['lg_action'] . "</td>
				<td valign='top'>" . $log_info['lg_item_id'] . "</td>
				</tr>
				<td colspan=5 valign='top'>";
				
			if($log_info['lg_txt'])
				$html .= "<textarea rows=10 cols=80>" . $log_info['lg_txt'] . "</textarea>";
			$html .= "
				</td>
				</tr>
			";
		}
		
		$html .= "</table>";
		
		return $html;
	}
}
?>