<?

class smart_colors
{
	var $hex_dec;
	
	function smart_colors()
	{
		$this->hex_dec = "0123456789ABCDEF";
	}
	
	function hex_to_dec($hex)
	{
		return strpos($this->hex_dec,strtoupper($hex));
	}
	
	function dec_to_hex($dec)
	{
		return $this->hex_dec[$dec];
	}
	
	function blend($col_a,$col_b,$per = 0.5)
	{
		$red_a = $this->hex_to_dec($col_a[0]) * 16 + $this->hex_to_dec($col_a[1]);
		$red_b = $this->hex_to_dec($col_b[0]) * 16 + $this->hex_to_dec($col_b[1]);

		$green_a = $this->hex_to_dec($col_a[2]) * 16 + $this->hex_to_dec($col_a[3]);
		$green_b = $this->hex_to_dec($col_b[2]) * 16 + $this->hex_to_dec($col_b[3]);
	
		$blue_a = $this->hex_to_dec($col_a[4]) * 16 + $this->hex_to_dec($col_a[5]);
		$blue_b = $this->hex_to_dec($col_b[4]) * 16 + $this->hex_to_dec($col_b[5]);
		
		$red = $red_a * $per + $red_b * (1-$per);
		$green = $green_a * $per + $green_b * (1-$per);
		$blue = $blue_a * $per + $blue_b * (1-$per);
		
		$red_c = $this->dec_to_hex(floor($red/16)) . $this->dec_to_hex(floor($red%16));
		$green_c = $this->dec_to_hex(floor($green/16)) . $this->dec_to_hex(floor($green%16));
		$blue_c = $this->dec_to_hex(floor($blue/16)) . $this->dec_to_hex(floor($blue%16));
		
		return $red_c . $green_c . $blue_c;
	}
}

?>