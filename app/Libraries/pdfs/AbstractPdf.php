<?php

namespace App\Libraries\pdfs;
use TCPDF;

class AbstractPdf extends TCPDF
{
    const FMT_STRING = 1;
    const FMT_MIME = 2;
    const FMT_INLINE = 3;
    const FMT_DOWNLOAD = 4;
    const PURCHASE_ORDER = 5;

    protected $ci;
    protected $data;

    protected $dogerblue_c = array(33, 150, 243);
    protected $success_c = array(86, 202, 0);
    protected $text_c = array(94, 86, 105);

    protected $left_m;
    protected $right_m;
    protected $top_m;
    protected $bottom_m;
    protected $width;

    public function __construct($data = array())
    {
        parent::__construct("P", "mm", "A4", true, "UTF-8");
        $this->ci = \Config\Services::get('renderer');
        $this->SetAuthor("QBS ERP");
        $this->setMargins(10, 10, 10);
        $this->setAutoPageBreak(FALSE);
        $this->AddPage();
        $this->left_m = 10;
        $this->right_m = 10;
        $this->top_m = 10;
        $this->bottom_m = 20;
        $this->width = $this->getPageWidth() - $this->left_m - $this->right_m;
        $this->data = $data;
    }

    protected function setTitleSection()
    {
        // Implementation of setTitleSection
    }

    protected function setBodySection()
    {
        // Implementation of setBodySection
    }

    protected function setEndSection()
    {
        // Implementation of setEndSection
    }

    public function createPdf($format)
    {
        $this->setTitleSection();
        $this->setBodySection();
        $this->setEndSection();

        if ($format == self::FMT_STRING) {
            $buffer = $this->Output("", "S");
            return $buffer;
        } else if ($format == self::FMT_MIME) {
            $filename = $this->data['filename'] ?? "file.pdf";
            $base64 = $this->Output($filename, "E");
            return $base64;
        } else if ($format == self::FMT_INLINE) {
            $filename = $this->data['filename'] ?? "file.pdf";
            $this->Output($filename, "I");
        } else if ($format == self::FMT_DOWNLOAD) {
            $filename = $this->data['filename'] ?? "file.pdf";
            $this->Output($filename, "D");
        }
        
    }
}

?>