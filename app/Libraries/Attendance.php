<?php

namespace App\Libraries;

use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnCellIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Attendance
{

    private $spreadsheet;
    private $sheet;
    private $title;
    private $types = array(
        0 => "Present",
        1 => "Weekoff",
        2 => "Absent",
        3 => "Paid Leave",
        4 => "Sick Leave",
        5 => "Festival Leave"
    );
    private $months = array(
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December"
    );


    private $columns = array("Employee ID", "Employee Code", "Employee Name", "Work Hours", "OT Hours", "Type");
    protected $db;
    protected $session;
    protected $logger;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger =  \Config\Services::logger();
    }

    private function isTimeFormatValid($time)
    {
        return preg_match('/^\d{2}:\d{2}$/', $time) === 1;
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function getEmptyAttendanceForImport()
    {
        $this->title = "Employee Attendance Import";
        $this->writeTitle();
        $this->writeEmptyAttendance();

        $path = get_tmp_path() . get_rand_str() . ".xlsx";
        $writer = new Writer($this->spreadsheet);
        $writer->save($path);

        header("Content-Disposition:attachment;filename=AttendanceImport.xlsx");
        file_put_contents("php://output", file_get_contents($path));
        unlink($path);
    }

    private function writeTitle()
    {
        $this->sheet->mergeCells("A1:P3");
        $this->sheet->getCell("A1")->setValue($this->title);
        $color = new Color();
        $color->setRGB("FFFFFF");
        $this->sheet->getStyle("A1")->getFont()->setSize(24)->setColor($color)->setBold(true);
        $color->setRGB("0F5CAC");
        $this->sheet->getStyle("A1")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    }

    private function writeEmptyAttendance()
    {
        $data = $this->getEmployeeListForEmptyAttendance();
        $iter = new RowCellIterator($this->sheet, 5, "A");
        for ($i = 0; $i < count($this->columns); $i++) {
            $cell = $iter->current();
            $cell->setValue($this->columns[$i]);
            $cell->getStyle()->getFont()->setBold(true)->setSize(12);
            $iter->next();
        }
        $idx = 6;
        foreach ($data as $row) {
            $iter = new RowCellIterator($this->sheet, $idx, "A");
            foreach ($row as $key => $value) {
                $cell = $iter->current();
                $cell->setValue($value);
                $iter->next();
            }
            $idx++;
        }
        $iter = new ColumnCellIterator($this->sheet, "F", 6);
        $validation = $this->getListType();
        for ($i = 0; $i < count($data); $i++) {
            $cell = $iter->current();
            $cell->setValue('');
            $cell->setDataValidation($validation);
            $iter->next();
        }
    }

    private function getListType()
    {
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $list = '"' . implode(",", $this->types) . '"';
        $validation->setFormula1($list);
        $validation->setAllowBlank(false);
        $validation->setShowDropDown(true);
        $validation->setShowInputMessage(true);
        $validation->setPromptTitle("Type");
        $validation->setPrompt("Select any one type from the list");
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle("Invalid Type");
        $validation->setError("Select only the list, don't type manually");
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        return $validation;
    }

    private function getEmployeeListForEmptyAttendance()
    {
        $query = "SELECT employee_id,emp_code,CONCAT(first_name,last_name) AS emp_name FROM employees WHERE status=0";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function importNewAttendance($path, $rec_date)
    {
        try {
            $rows_imported = 0;
            $created_by = get_user_id();

            if (file_exists($path) && $this->isNewDate($rec_date)) {
                $reader = new Reader();
                $this->spreadsheet = $reader->load($path);
                $this->sheet = $this->spreadsheet->getActiveSheet();
                $end = $this->sheet->getHighestDataRow();

                for ($i = 6; $i <= $end; $i++) {
                    $iter = new RowCellIterator($this->sheet, $i, "A");
                    $row = array();

                    for ($j = 1; $j <= 5; $j++) {
                        $cell = $iter->current();
                        $value = $cell->getValue();
                        array_push($row, $value);
                        $iter->next();
                    }

                       $this->logger->error($row.$rec_date.$created_by);
                    log_message('debug', 'Row data before insertion: ' . print_r($row, true));

                    if (!empty($row) && $this->isRowValidAndPopulate($row, $rec_date, $created_by)) {
                        try {
                            $this->db->table('emp_attendance')->insert($row);
                            $rows_imported++;
                        } catch (\Exception $e) {
                            $this->logger->error($e);
                            log_message('error', 'Exception during database insertion: ' . $e->getMessage());
                            $this->logger->error($e->getMessage());
                        }
                    }
                }
                $this->logger->error($rows_imported);
                return $rows_imported;
            } else {
                // Log an error if the file doesn't exist or date is not new
                $this->logger->error('File does not exist or date is not new');
                log_message('error', 'File does not exist or date is not new');
                return false;
            }
        } catch (\Exception $e) {
            // Log any exceptions that occurred during the import
            $this->logger->error($e->getMessage());
            // log_message('error', 'Exception during import: ' . $e->getMessage());
            return false;
        }
    }





    private function isRowValidAndPopulate(&$row, $rec_date, $created_by)
    {
        if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[5])) {
            return false;
        }
        if (!in_array($row[5], $this->types)) {
            return false;
        }
        if ($row[5] != "Present") {
            $row[3] = 0;
            $row[4] = 0;
        }
        $tmp = array();
        $tmp[0] = $row[0];
        $tmp[1] = $rec_date;
        $tmp[2] = array_search($row[5], $this->types);
        $tmp[3] = $row[3];
        $tmp[4] = $row[4];
        $row = $tmp;
        return true;
    }

    private function isNewDate($date)
    {
        $query = "SELECT attend_id FROM emp_attendance WHERE rec_date=? LIMIT 1";
        $result = $this->db->query($query, $date)->getRow();
        if (!empty($result)) {
            return false;
        }
        return true;
    }


    public function getUpdateAttendanceForImport($date)
    {
        $this->title = "Employee Attendance Update";
        $this->writeTitle();
        $this->writeUpdateAttendance($date);

        $path = get_tmp_path() . get_rand_str() . ".xlsx";
        $writer = new Writer($this->spreadsheet);
        $writer->save($path);

        header("Content-Disposition:attachment;filename=AttendanceImport.xlsx");
        file_put_contents("php://output", file_get_contents($path));
        unlink($path);
    }

    private function writeUpdateAttendance($date)
    {
        $data = $this->getEmployeeListForUpdateAttendance($date);

        $iter = new RowCellIterator($this->sheet, 5, "A");
        for ($i = 0; $i < count($this->columns); $i++) {
            $cell = $iter->current();
            $cell->setValue($this->columns[$i]);
            $cell->getStyle()->getFont()->setBold(true)->setSize(12);
            $iter->next();
        }
        $idx = 6;
        foreach ($data as $row) {
            $iter = new RowCellIterator($this->sheet, $idx, "A");
            foreach ($row as $key => $value) {
                $cell = $iter->current();
                $cell->setValue($value);
                $iter->next();
            }
            $idx++;
        }
        $iter = new ColumnCellIterator($this->sheet, "F", 6);
        $validation = $this->getListType();
        for ($i = 0; $i < count($data); $i++) {
            $cell = $iter->current();
            $cell->setDataValidation($validation);
            $iter->next();
        }
    }

    private function getEmployeeListForUpdateAttendance($date)
    {
        $query = "SELECT employees.employee_id,emp_code,CONCAT(first_name,last_name) AS emp_name,SUBSTRING(in_time,1,LENGTH(in_time)-3) AS in_time,SUBSTRING(out_time,1,LENGTH(out_time)-3) AS out_time,CASE 
            WHEN present=1 THEN 'Present' 
            WHEN weekoff=1 THEN 'Weekoff' 
            WHEN absent=1 THEN 'Absent' 
            WHEN paid_leave=1 THEN 'Paid Leave' 
            WHEN sick_leave=1 THEN 'Sick Leave' 
            WHEN festival_leave=1 THEN 'Festival Leave' 
            END AS type FROM emp_attendance JOIN employees ON emp_attendance.employee_id=employees.employee_id WHERE rec_date=?";
        $result = $this->db->query($query, array($date))->getResultArray();
        return $result;
    }

    public function importUpdateAttendance($path, $rec_date)
    {
        $rows_updated = 0;
        // $rec_date = $this->input->post("rec_date");
        if (file_exists($path)) {
            $reader = new Reader();
            $this->spreadsheet = $reader->load($path);
            $this->sheet = $this->spreadsheet->getActiveSheet();
            $end = $this->sheet->getHighestDataRow();

            $query = "UPDATE emp_attendance SET present=? , weekoff=? , absent=? , paid_leave=? , sick_leave=? , festival_leave=? , in_time=? , out_time=? WHERE employee_id=? AND rec_date='" . $rec_date . "'";
            for ($i = 6; $i <= $end; $i++) {
                $iter = new RowCellIterator($this->sheet, $i, "A");
                $row = array();
                for ($j = 1; $j <= count($this->columns); $j++) {
                    $cell = $iter->current();
                    $value = $cell->getValue();
                    array_push($row, $value);
                    $iter->next();
                }
                if (!empty($row) && $this->isRowValidAndPopulateForUpdate($row)) {
                    $this->db->query($query, $row);
                    ++$rows_updated;
                }
            }
        }
        return $rows_updated;
    }

    public function isRowValidAndPopulateForUpdate(&$row)
    {
        if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[5])) {
            return false;
        }
        if (!in_array($row[5], $this->types)) {
            return false;
        }
        if ($row[5] == "Present") {
            if (!$this->isTimeFormatValid($row[3]) || !$this->isTimeFormatValid($row[4])) {
                return false;
            }
        } else {
            $row[3] = "";
            $row[4] = "";
        }
        $tmp = array();
        if ($row[5] == "Present") {
            $tmp[0] = 1;
        } else {
            $tmp[0] = 0;
        }
        if ($row[5] == "Weekoff") {
            $tmp[1] = 1;
        } else {
            $tmp[1] = 0;
        }
        if ($row[5] == "Absent") {
            $tmp[2] = 1;
        } else {
            $tmp[2] = 0;
        }
        if ($row[5] == "Paid Leave") {
            $tmp[3] = 1;
        } else {
            $tmp[3] = 0;
        }
        if ($row[5] == "Sick Leave") {
            $tmp[4] = 1;
        } else {
            $tmp[4] = 0;
        }
        if ($row[5] == "Festival Leave") {
            $tmp[5] = 1;
        } else {
            $tmp[5] = 0;
        }
        $tmp[6] = $row[3];
        $tmp[7] = $row[4];
        $tmp[8] = $row[0];
        $row = $tmp;
        return true;
    }

    public function exportMonthlyAttendance($post)
    {
        $employee_id = $post["employee_id"];
        $month = $post["select_month"];
        $year = $post["select_year"];
        $this->logger->error($post);
        if (!empty($employee_id)) {

            $this->exportAttendanceForEmployee($employee_id, $month, $year);
        } else {
            $this->exportAttendanceForAllEmployees($month, $year);
        }
    }

    private function exportAttendanceForEmployee($employee_id, $month, $year)
    {
        $attendanceData = $this->getAttendanceForEmployee($employee_id, $month, $year);
        $this->exportSpreadsheet($attendanceData);
    }

    private function exportAttendanceForAllEmployees($month, $year)
    {
        $attendanceData = $this->getAttendanceForAllEmployees($month, $year);

        $this->exportSpreadsheet($attendanceData);
    }


    private function exportSpreadsheet($attendanceData)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write headers
        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'Rec Date');
        $sheet->setCellValue('C1', 'Status');
        $sheet->setCellValue('D1', 'Work Hours');
        $sheet->setCellValue('E1', 'OT Hours');

        $row = 2;
        foreach ($attendanceData as $attendance) {
            $sheet->setCellValue('A' . $row, $attendance['employee_id']);
            $sheet->setCellValue('B' . $row, $attendance['rec_date']);
            $sheet->setCellValue('C' . $row, $attendance['status']);
            $sheet->setCellValue('D' . $row, $attendance['work_hours']);
            $sheet->setCellValue('E' . $row, $attendance['ot_hours']);

            $row++;
        }
        $path = get_tmp_path() . get_rand_str() . ".xlsx";
        $writer = new Writer($spreadsheet);
        $writer->save($path);

        header("Content-Disposition:attachment;filename=AttendanceExport.xlsx");
        file_put_contents("php://output", file_get_contents($path));
        unlink($path);
    }

    private function getAttendanceForEmployee($employee_id, $month, $year)
    {

        $query = "SELECT * FROM emp_attendance WHERE employee_id = ? AND MONTH(rec_date) = ? AND YEAR(rec_date) = ?";
        $result = $this->db->query($query, [$employee_id, $month, $year])->getResultArray();
        return $result;
    }

    private function getAttendanceForAllEmployees($month, $year)
    {

        $query = "SELECT * FROM emp_attendance WHERE MONTH(rec_date) = ? AND YEAR(rec_date) = ?";
        $result = $this->db->query($query, [$month, $year])->getResultArray();
        return $result;
    }

    private function writeHeader1($month, $year)
    {
        //Company Details
        $this->sheet->mergeCells("A5:B5");
        $color = new Color();
        $color->setRGB("56ca00");
        $this->sheet->getStyle("A5")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getCell("A5")->setValue("Name of the Company");
        $color->setRGB("ffffff");
        $this->sheet->getStyle("A5")->getFont()->setBold(true)->setColor($color);
        $this->sheet->getStyle("A5")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->sheet->mergeCells("A6:B6");
        $color->setRGB("ff9800");
        $this->sheet->getStyle("A6")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getCell("A6")->setValue("Address of Company");
        $color->setRGB("ffffff");
        $this->sheet->getStyle("A6")->getFont()->setBold(true)->setColor($color);
        $this->sheet->getStyle("A6")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $query = "SELECT s_name,s_value FROM erp_settings WHERE s_name IN ('company_name','address','city','state','country','zip')";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $row) {
            $m_result[$row['s_name']] = $row['s_value'];
        }
        $this->sheet->mergeCells("C5:L5");
        $this->sheet->getCell("C5")->setValue($m_result['company_name']);
        $this->sheet->getStyle("C5")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->sheet->mergeCells("C6:L6");
        $address = $m_result['address'] . " , " . $m_result['city'] . " , " . $m_result['state'] . " , " . $m_result['country'] . " - " . $m_result['zip'];
        $this->sheet->getCell("C6")->setValue($address);
        $this->sheet->getStyle("C6")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //Month and Year
        $this->sheet->getCell("M5")->setValue("Month");
        $color = new Color();
        $color->setRGB("56ca00");
        $this->sheet->getStyle("M5")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $color->setRGB("ffffff");
        $this->sheet->getStyle("M5")->getFont()->setColor($color)->setBold(true);
        $this->sheet->getCell("N5")->setValue($this->months[$month]);
        $this->sheet->getStyle("N5")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->sheet->getCell("M6")->setValue("Year");
        $color->setRGB("ff9800");
        $this->sheet->getStyle("M6")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $color->setRGB("ffffff");
        $this->sheet->getStyle("M6")->getFont()->setColor($color)->setBold(true);
        $this->sheet->getCell("N6")->setValue($year);
        $this->sheet->getStyle("N6")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function drawFullBorder($cell)
    {
        $color = new Color();
        $color->setRGB("9e9e9e");
        $this->sheet->getStyle($cell)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->setColor($color);
        $this->sheet->getStyle($cell)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN)->setColor($color);
        $this->sheet->getStyle($cell)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor($color);
        $this->sheet->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor($color);
    }

    private function writeHeader2($month, $year)
    {
        $date1 = new DateTime();
        $date1->setDate($year, $month, 1);
        $nxt_index = ($month + 1) % 12;
        $date2 = new DateTime();
        $date2->setDate($year, $nxt_index, 1);
        $diff = $date2->diff($date1);
        $days = $diff->days;

        $white = new Color();
        $white->setRGB("ffffff");
        $color = new Color();
        $color->setRGB("2196f3");
        $this->sheet->getCell("A7")->setValue("SNo");
        $this->sheet->getStyle("A7")->getFont()->setBold(true)->setSize(12)->setColor($white);
        $this->sheet->getStyle("A7")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getStyle("A7")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->drawFullBorder("A7");

        $this->sheet->getCell("B7")->setValue("Name of Employee");
        $this->sheet->getStyle("B7")->getFont()->setBold(true)->setSize(12)->setColor($white);
        $this->sheet->getStyle("B7")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getStyle("B7")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->drawFullBorder("B7");

        $this->sheet->getCell("C7")->setValue("Employee Code");
        $this->sheet->getStyle("C7")->getFont()->setBold(true)->setSize(12)->setColor($white);
        $this->sheet->getStyle("C7")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getStyle("C7")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->drawFullBorder("C7");

        $this->sheet->getCell("D7")->setValue("Day");
        $this->sheet->getStyle("D7")->getFont()->setBold(true)->setSize(12)->setColor($white);
        $this->sheet->getStyle("D7")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
        $this->sheet->getStyle("D7")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->drawFullBorder("D7");

        $row_iter = new RowCellIterator($this->sheet, 7, "E");
        for ($i = 1; $i <= $days; $i++) {
            $cell = $row_iter->current();
            $cell->setValue($i);
            $cell->getStyle()->getFont()->setBold(true)->setSize(12)->setColor($white);
            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
            $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $cell_str = $cell->getColumn() . $cell->getRow();
            $this->drawFullBorder($cell_str);
            $row_iter->next();
        }
        for ($i = 0; $i < count($this->types); $i++) {
            $cell = $row_iter->current();
            $cell->setValue($this->types[$i]);
            $cell->getStyle()->getFont()->setBold(true)->setSize(12)->setColor($white);
            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor($color)->setEndColor($color);
            $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $cell_str = $cell->getColumn() . $cell->getRow();
            $this->drawFullBorder($cell_str);
            $row_iter->next();
        }
    }

    public function test()
    {
        $this->title = "Monthly Employee Attendance";
        $this->writeTitle();
        $this->writeHeader1(2, 2022);
        $this->writeHeader2(2, 2022);

        $path = get_tmp_path() . get_rand_str() . ".xlsx";
        $writer = new Writer($this->spreadsheet);
        $writer->save($path);

        header("Content-Disposition:attachment;filename=AttendanceExport.xlsx");
        file_put_contents("php://output", file_get_contents($path));
        unlink($path);
    }
}
