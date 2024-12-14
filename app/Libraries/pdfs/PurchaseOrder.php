<?php
namespace App\Libraries\pdfs;
use App\Libraries\pdfs\AbstractPdf;

    class PurchaseOrder extends AbstractPdf{

        public function __construct($data=array()){
            parent::__construct($data);
        }

        public function Header(){

        }

        public function Footer(){
            
        }

        public function setTitleSection(){
            $x=$this->GetX();
            $y=$this->GetY();
            $h=40;

            $w=$this->width/2;
            $x+=$w;
            $this->setFont("Helvetica","B",24);
            $this->setTextColorArray($this->dogerblue_c);
            $cell_h=$this->getStringHeight($w,"Purchase Order");
            $this->MultiCell($w,$cell_h,"Purchase Order",0,"R",0,0,$x,$y);

            $w=$w/2;
            $y+=$cell_h;
            $y+=2;
            $this->setFont("Helvetica","",10);
            $this->setTextColorArray($this->text_c);
            $this->MultiCell($w,0,"Delivery Date",0,"R",0,0,$x,$y);
            $y+=$this->getStringHeight($w,"Delivery Date")+2;
            $this->MultiCell($w,0,"PO Number",0,"R",0,0,$x,$y);
        }
    }
?>