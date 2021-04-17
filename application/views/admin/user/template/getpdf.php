<?php

$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('Cinemaflix');
$pdf->SetHeaderMargin(30);
$pdf->SetTopMargin(20);
$pdf->setFooterMargin(20);
$pdf->SetAutoPageBreak(true);
$pdf->SetAuthor('Author');
$pdf->SetDisplayMode('real', 'default');

$pdf->AddPage();

// $pdf->Write(5, 'Some sample text');

$html = <<<EOD
EOD;
$html .='<table id="example1" border="1">
<thead><tr  style=" color:#483d8b; font-weight:bold;">
<th>Sr.</th>
<th>Username</th>
<th>Email</th>
<th>Phone</th>
</tr></thead><tbody>';
$i = 1; foreach($details as $data){
$html .='<tr>
<td>'.$i.'</td>
<td> '.$data['username'].'</td>
<td> '.$data['email'].'</td>
<td>'.$data['phone'].'</td></tr>';
$i++; }
$html .=' </tbody></table>';


$pdf->writeHTML($html, true, false, false, false, '');

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_048.pdf', 'I');
// $pdf->Output('My-File-Name.pdf', 'I');

?>
