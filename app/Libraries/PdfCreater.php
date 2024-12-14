<?php
namespace App\Libraries;

use App\Libraries\pdfs\AbstractPdf;
use App\Libraries\pdfs\PurchaseOrder;
// use PurchaseOrder;

    class PdfCreater{

        private static function getCreater($type,$data){
            $creater=null;
            switch($type){
                case AbstractPdf::PURCHASE_ORDER:
                    $creater = new PurchaseOrder($data);
                    break;
                default:
                    break;
            }
            return $creater;
        }

        public static function create($type,$format,$data=array()){
            $creater=self::getCreater($type,$data);
            if(isset($creater)){
                return $creater->createPdf($format);
            }
            return FALSE;
        }
    }

?>