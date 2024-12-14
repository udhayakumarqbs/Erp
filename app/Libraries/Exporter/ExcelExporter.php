<?php

namespace App\Libraries\Exporter;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;

class ExcelExporter
{
    private $spreadsheet;
    private $sheet;
    private $title = "EXCEL EXPORT";
    private $columns = [];
    private $datatype = [];
    private $data = [];

    public function __construct($config = [])
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        if (!empty($config['title'])) {
            $this->title = $config['title'];
        }
        $this->columns = $config['column'];
        $this->data = $config['data'];
        $this->datatype = $config['column_data_type'];
    }

    private function writeTitle()
    {
        $this->sheet->mergeCells("A1:P3");
        $this->sheet->getCell('A1')->setValue($this->title);
        $color = new Color();
        $color->setRGB("FFFFFF");
        $this->sheet->getStyle('A1')->getFont()->setSize(24)->setColor($color)->setBold(true);
        $color->setRGB("0F5CAC");
        $this->sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    }

    public function getFormatCode($type)
    {
        $code = NumberFormat::FORMAT_GENERAL;
        switch ($type) {
            case ExporterConst::E_INTEGER:
                $code = NumberFormat::FORMAT_NUMBER;
                break;
            case ExporterConst::E_STRING:
                $code = NumberFormat::FORMAT_GENERAL;
                break;
            case ExporterConst::E_DATE:
                $code = NumberFormat::FORMAT_DATE_DDMMYYYY;
                break;
            case ExporterConst::E_FLOAT:
                $code = NumberFormat::FORMAT_NUMBER_00;
                break;
            case ExporterConst::E_FLOAT_PERCENT:
                $code = NumberFormat::FORMAT_PERCENTAGE_00;
                break;
            case ExporterConst::E_INTEGER_PERCENT:
                $code = NumberFormat::FORMAT_PERCENTAGE;
                break;
            case ExporterConst::E_TIME:
                $code = NumberFormat::FORMAT_DATE_TIME4;
                break;
            case ExporterConst::E_DATETIME:
                $code = NumberFormat::FORMAT_DATE_DATETIME;
                break;
            default:
                $code = NumberFormat::FORMAT_GENERAL;
                break;
        }
        return $code;
    }

    private function writeTable($startIdx = 6)
    {
        $columnCount = count($this->columns);
        $idx = $startIdx;
        $iter = new RowCellIterator($this->sheet, $idx, 'A');
        for ($i = 0; $i < $columnCount; $i++) {
            $cell = $iter->current();
            $cell->setValue($this->columns[$i]);
            $cell->getStyle()->getFont()->setBold(true)->setSize(12);
            $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $iter->next();
        }
        ++$idx;
        foreach ($this->data as $array) {
            $rowIter = new RowCellIterator($this->sheet, $idx, 'A');
            $index = 0;
            foreach ($array as $key => $value) {
                $cell = $rowIter->current();
                $cell->setValue($value);
                $type = $this->datatype[$index] ?? -1;
                $code = $this->getFormatCode($type);
                $cell->getStyle()->getNumberFormat()->setFormatCode($code);
                $rowIter->next();
                $index++;
            }
            $idx++;
        }
    }

    public function exportAsExcel()
    {
        $this->writeTitle();
        $this->writeTable();

        $path = get_tmp_path() . get_rand_str() . ".xlsx";
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($path);

        header("Content-Disposition:attachment;filename=export.xlsx");
        file_put_contents("php://output", file_get_contents($path));
        unlink($path);
        exit();
    }

    public function exportAsCsv()
    {
        $this->writeTable(1);

        $path = get_tmp_path() . get_rand_str() . ".csv";
        $writer = new Csv($this->spreadsheet);
        $writer->save($path);

        header("Content-Disposition:attachment;filename=export.csv");
        file_put_contents("php://output", file_get_contents($path));
        unlink($path);
        exit();
    }
}
