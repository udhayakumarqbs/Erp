<?php

namespace App\Libraries;

use App\Libraries\Importer\AbstractImporter;
use App\Libraries\Importer\LeadImporter;
use App\Libraries\Importer\CustomerImporter;
use App\Libraries\Importer\RawmaterialImporter;
use App\Libraries\Importer\SemifinishedImporter;
use App\Libraries\Importer\FinishedgoodImporter;
use App\Libraries\Importer\GlaccountImporter;
use App\Libraries\Importer\SupplierImporter;
use App\Libraries\Importer\EmployeeImporter;
use App\Libraries\Importer\ContractorImporter;

    //defined("BASEPATH") or exit("No direct script access allowed");

    class Importer{

        private static $importer=null;

        public static function init($table,$format){
            switch($table){
                case AbstractImporter::LEAD:
                    self::$importer=new LeadImporter($format);
                    break;
                case AbstractImporter::CUSTOMER:
                    self::$importer=new CustomerImporter($format);
                    break;
                case AbstractImporter::RAWMATERIAL:
                    self::$importer=new RawmaterialImporter($format);
                    break;
                case AbstractImporter::SEMIFINISHED:
                    self::$importer=new SemifinishedImporter($format);
                    break;
                case AbstractImporter::FINISHEDGOOD:
                    self::$importer=new FinishedgoodImporter($format);
                    break;
                case AbstractImporter::GLACCOUNT:
                    self::$importer=new GlaccountImporter($format);
                    break;
                case AbstractImporter::SUPPLIER:
                    self::$importer=new SupplierImporter($format);
                    break;
                case AbstractImporter::EMPLOYEE:
                    self::$importer=new EmployeeImporter($format);
                    break;
                case AbstractImporter::CONTRACTOR:
                    self::$importer=new ContractorImporter($format);
                    break;
                default:
                    break;
            }
            return self::$importer;
        }

    }
?>