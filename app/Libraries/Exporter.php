<?php

namespace App\Libraries;
use App\Libraries\Exporter\ExcelExporter;
use App\Libraries\Exporter\PdfExporter;

class Exporter
{
    const EXCEL = 'excel';
    const PDF = 'pdf';
    const CSV = 'csv';
    const E_STRING = 'string'; // Define the E_STRING constant

    public static function export($type, $config = [])
    {
        $exporter = null;

        switch ($type) {
            case self::EXCEL:
                $exporter = new ExcelExporter($config);
                $exporter->exportAsExcel();
                break;
            case self::CSV:
                $exporter = new ExcelExporter($config);
                $exporter->exportAsCsv();
                break;
            case self::PDF:
                $exporter = new PdfExporter($config);
                $exporter->export_as_pdf();
                break;
            default:
                $exporter = null;
        }
    }
}
