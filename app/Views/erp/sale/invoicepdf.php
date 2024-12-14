<?php
require(ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">INVOICE</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $invoice_number . '</b>';

$paidlogo .= '<div></br><img src="'.base_url('assets/images/in1.png').'" style="width:100px" /></div>';
$unpaidlogo .= '<div></br><img src="'.base_url('assets/images/in.png').'" style="width:100px" /></div>';

if($status == "Paid")
{
    $info_right_column .= $paidlogo;
}else{
    $info_right_column .= $unpaidlogo;
}   

$info_left_column .= '<img src="'.base_url('assets/images/logo.png').'" style="width:100px" >';


// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(10);

$organization_info = '<div style="color:#424242;"> ----  </div>';

// Bill to
$invoice_info = '<b>Bill To</b>';
$invoice_info .= '<div style="color:#424242;"> ---- </div>';

// ship to to
    $invoice_info .= '<br /><b>Ship To</b>';
    $invoice_info .= '<div style="color:#424242;"> ---- </div>';

$datetime=DateTime::createFromFormat("Y-m-d", $invoice->date);
$format=$datetime->format("d F Y");
$invoice_info .= '<br />Invocie Date: ' .$format. '<br />';

if (!empty($invoice->duedate)) {
    $datetime=DateTime::createFromFormat("Y-m-d", $invoice->duedate);
    $format=$datetime->format("d F Y");
    $invoice_info .= 'Invoice Due Date' .$format. '<br />';
}

$pdf->Output('filename.pdf', 'I');

?>






