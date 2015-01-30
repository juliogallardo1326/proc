<?php
$svr = $_SERVER["PATH_TRANSLATED"];
$path_parts = pathinfo($svr); 
$str_current_path = $path_parts["dirname"];

$i_top = 750;
$i_left = 50;
$i_offset = 200;
$str_pdf_name = "invoice.pdf";
$pdf = pdf_new(); 
pdf_open_file($pdf, "$str_current_path/Temp_doc/$str_pdf_name"); 
pdf_set_info($pdf, "Author", "etelegate"); 
pdf_set_info($pdf, "Title", "Invoice Form"); 
pdf_set_info($pdf, "Creator", "etelegate"); 
pdf_set_info($pdf, "Subject", "Invoice"); 
pdf_begin_page($pdf, 595, 842); 
//pdf_add_outline($pdf, "Page 5"); 
$font = pdf_findfont($pdf, "Verdana", "winansi", 1); 
pdf_setfont($pdf, $font, 12); 
//pdf_set_value($pdf, "textrendering", 1); 
$jpeg_image = pdf_open_image_file($pdf, "jpeg", "images/logo2os.jpg");
pdf_place_image($pdf, $jpeg_image, 200, $i_top, 1.0);
pdf_close_image($pdf, $jpeg_image);
/*$jpeg_image = pdf_open_image_file($pdf, "jpeg", "images/top1.jpg");
pdf_place_image($pdf, $jpeg_image, 300, $i_top+20, 0.5);
pdf_close_image($pdf, $jpeg_image);
$jpeg_image = pdf_open_image_file($pdf, "jpeg", "images/top4.jpg");
pdf_place_image($pdf, $jpeg_image, 301, $i_top-10, 0.5);
pdf_close_image($pdf, $jpeg_image);*/
$i_top -= 50;
pdf_show_xy($pdf, "Company Name", $i_left, $i_top); 
pdf_show_xy($pdf, "[Company Name]", $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Address", $i_left, $i_top); 
pdf_show_xy($pdf, "[Address]", $i_left+$i_offset, $i_top); 
/*if ($str_city != "") {
	$i_top -= 20;
	pdf_show_xy($pdf, $str_city, $i_left+$i_offset, $i_top); 
}
if ($str_state != "") {
	$i_top -= 20;
	pdf_show_xy($pdf, $str_state, $i_left+$i_offset, $i_top); 
}
if ($str_country != "") {
	$i_top -= 20;
	pdf_show_xy($pdf, $str_country, $i_left+$i_offset, $i_top); 
}
if ($str_zip_code != "") {
	$i_top -= 20;
	pdf_show_xy($pdf, $str_zip_code, $i_left+$i_offset, $i_top); 
}
$i_top -= 30;
pdf_show_xy($pdf, "Billing Date", $i_left, $i_top); 
pdf_show_xy($pdf, $str_billing_date, $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Billing period", $i_left, $i_top); 
pdf_show_xy($pdf, $str_billing_period, $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Total Transactions", $i_left, $i_top); 
pdf_show_xy($pdf, $i_total_transactions, $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Approved Transactions", $i_left, $i_top); 
pdf_show_xy($pdf, $i_approved_transactions, $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Declined Transactions", $i_left, $i_top); 
pdf_show_xy($pdf, $i_declined_transactions, $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Transaction Fee ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_trans_fee), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Discount Rate ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_discount_rate), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Chargeback ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_charge_back_fee), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Credit ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_credit), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Misc. ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_misc_fee), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Rolling Reserve ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_reserve), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Total Amount ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_grand_amt), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Total Deduction ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney($i_grand_deduction), $i_left+$i_offset, $i_top); 
$i_top -= 30;
pdf_show_xy($pdf, "Net Amount ($)", $i_left, $i_top); 
pdf_show_xy($pdf, formatMoney(($i_grand_amt - $i_grand_deduction + $i_misc_fee)), $i_left+$i_offset, $i_top); */
//pdf_moveto($pdf, 50, 740); 
//pdf_lineto($pdf, 330, 740); 
//pdf_stroke($pdf); 
pdf_end_page($pdf); 
pdf_close($pdf); 
pdf_delete($pdf);
?>