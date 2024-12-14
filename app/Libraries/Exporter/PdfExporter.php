<?php
namespace App\Libraries\Exporter;

use TCPDF;

    class PdfExporter{

        private $pdf;
        private $title="PDF EXPORT";
        private $columns=array();
        private $data=array();
        private $dy;
        private $width;
        private $height;

        public function __construct($config=array()){
            $this->columns=$config['column'];
            $this->data=$config['data'];
            if(!empty($config['title'])){
                $this->title=$config['title'];
            }
            $this->pdf=new TCPDF('P','mm','A4',true,'UTF-8',false,false);
            $this->pdf->SetMargins(15,10,10);
            $this->pdf->SetAutoPageBreak(true,15);
            $this->pdf->setPrintFooter(false);
            $this->pdf->setPrintHeader(false);
            $this->dy=10;
            $this->width=$this->pdf->getPageWidth();
            $this->height=$this->pdf->getPageHeight();
            $this->pdf->AddPage();
        }

        private function write_title(){
            $this->pdf->setCellPaddings(2,2,2,2);
            $this->pdf->SetTextColor(255,255,255);
            $this->pdf->SetFillColor(15,92,172);
            $this->pdf->SetFont("Helvetica","B",14);
            $this->pdf->MultiCell(0,0,$this->title,0,"C",1,1,10,$this->dy);
            $this->dy+=$this->pdf->getStringHeight($this->width,$this->title);
            $this->pdf->ln(6);
        }

        private function write_table(){
            $this->pdf->SetFont("Helvetica","",10);
            $this->pdf->SetTextColor(119,119,119);
            $table='
                <table width="100%" cellpadding="5"  >
                    <tbody>
                    <tr>
            ';
            foreach($this->columns as $col){
                $table.='<td style="text-align:center;border:1px solid rgb(119,119,119);" > <b>'.$col.'</b> </td>';
            }
            $table.='</tr>';
            foreach($this->data as $array){
                $table.='<tr>';
                foreach($array as $key=>$value){
                    $table.='<td style="text-align:center;color:black;border:1px solid rgb(119,119,119);" >'.$value.' </td>';
                }
                $table.='</tr>';
            }
            $table.='
                    </tbody>
                    </table>
                    ';
            $this->pdf->writeHTML($table,true,true);
        }

        public function export_as_pdf(){
            $this->write_title();
            $this->write_table();

            $base64=$this->pdf->Output("export.pdf","S");
            header("Content-Disposition:attachment;filename=export.pdf");
            file_put_contents("php://output",$base64);
            exit();
        }
    }
?>