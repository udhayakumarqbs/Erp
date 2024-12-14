<?php

namespace App\Libraries\Importer;

use CodeIgniter\Config\Services;

class ContractorImporter implements AbstractImporter
{
    private $type = AbstractImporter::CSV;
    private $table = "contractors";
    private $id_name = "contractor_id";
    private $columns = [
        // ... your columns definition here
        "Code" => array("col" => "con_code", "pos" => 0, "req" => true, "uniq" => true, "sample" => "data"),
        "Name" => array("col" => "name", "pos" => 1, "req" => true, "uniq" => false, "sample" => "data"),
        "Contact Person" => array("col" => "contact_person", "pos" => 2, "req" => true, "uniq" => false, "sample" => "data"),
        "Email" => array("col" => "email", "pos" => 3, "req" => true, "uniq" => true, "sample" => "0000000000"),
        "Phone 1" => array("col" => "phone_1", "pos" => 4, "req" => true, "uniq" => true, "sample" => "data@data.com"),
        "Phone 2" => array("col" => "phone_2", "pos" => 5, "req" => false, "uniq" => false, "sample" => "000000000"),
        "GST No" => array("col" => "gst_no", "pos" => 6, "req" => true, "uniq" => false, "sample" => "data"),
        "PAN No" => array("col" => "pan_no", "pos" => 7, "req" => false, "uniq" => false, "sample" => "2"),
        "Website" => array("col" => "website", "pos" => 8, "req" => false, "uniq" => false, "sample" => "data"),
        "Address" => array("col" => "address", "pos" => 9, "req" => true, "uniq" => false, "sample" => "data"),
        "City" => array("col" => "city", "pos" => 10, "req" => true, "uniq" => false, "sample" => "data"),
        "State" => array("col" => "state", "pos" => 11, "req" => true, "uniq" => false, "sample" => "data"),
        "Country" => array("col" => "country", "pos" => 12, "req" => true, "uniq" => false, "sample" => "data"),
        "Zipcode" => array("col" => "zipcode", "pos" => 13, "req" => true, "uniq" => false, "sample" => "data"),
    ];
    private $common_columns = [];

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function get_columns()
    {
        return $this->columns;
    }

    public function import($filepath, $common_columns = [])
    {
        $imported = -1;
        $this->common_columns = $common_columns;

        switch ($this->type) {
            case AbstractImporter::CSV:
                $imported = $this->import_from_csv($filepath);
                break;
            default:
                break;
        }

        return $imported;
    }

    public function download_template()
    {
        header("Cache-Control:no-cache");
        header("Expires:0");
        header("Content-Disposition:attachment;filename=ContractorImport.csv");
        $out = fopen("php://output", "w");

        foreach ($this->columns as $key => $col) {
            $data = '"' . $key . '",';
            fwrite($out, $data);
        }

        fclose($out);
        exit();
    }

    private function sanitize_field(&$fields)
    {
        $field_valid = true;
        $tmp_array = [];
        $db = \Config\Database::connect();

        foreach ($this->columns as $key => $column) {
            $pos = $column["pos"];
            $value = isset($fields[$pos]) ? trim($fields[$pos], '"') : "";

            if ($column['req'] == true && $value == "") {
                $field_valid = false;
                break;
            }

            if ($column['uniq'] == true) {
                $query = "SELECT " . $this->id_name . " FROM " . $this->table . " WHERE " . $column["col"] . "=" . $db->escape($value);
                $check = $db->query($query)->getRow();

                if (!empty($check)) {
                    $field_valid = false;
                    break;
                }
            }

            array_push($tmp_array, $value);
        }

        return $field_valid ? $tmp_array : [];
    }

    private function get_query()
    {
        $query = "INSERT INTO " . $this->table . "(";

        foreach ($this->columns as $column) {
            $query .= $column['col'] . ",";
        }

        foreach ($this->common_columns as $key => $value) {
            $query .= $key . ",";
        }

        $query = substr($query, 0, strlen($query) - 1);
        $query .= ") VALUES(";

        foreach ($this->columns as $column) {
            $query .= "?,";
        }

        foreach ($this->common_columns as $key => $value) {
            $query .= is_string($value) ? "'" . $value . "'," : $value . ",";
        }

        $query = substr($query, 0, strlen($query) - 1);
        $query .= ")";

        return $query;
    }

    private function import_from_csv($filepath)
    {
        $db = \Config\Database::connect();
        $query = $this->get_query();
        $avoid_first_row = true;
        $imported = -1;

        if (file_exists($filepath) && strtolower(pathinfo($filepath, PATHINFO_EXTENSION)) == "csv") {
            $handle = fopen($filepath, "r");
            $imported = 1;

            while (!feof($handle)) {
                $line = fgets($handle);
                $idx = stripos($line, "\n");

                if ($idx !== false) {
                    $line = substr($line, 0, $idx);
                } else {
                    $idx = stripos($line, "\r\n");

                    if ($idx !== false) {
                        $line = substr($line, 0, $idx);
                    }
                }

                $fields = explode(",", $line);

                if (!$avoid_first_row) {
                    $tmp_array = $this->sanitize_field($fields);
                    $db->transStart();

                    if (count($tmp_array) > 0) {
                        $db->query($query, $tmp_array);
                    } else {
                        $imported = 0;
                    }

                    $insert_id = $db->insertID();

                    if (empty($insert_id)) {
                        $imported = 0;
                    }

                    $db->transComplete();

                    if ($db->transStatus() === false) {
                        $imported = 0;
                        $db->transRollback();
                    } else {
                        $db->transCommit();
                    }
                } else {
                    $avoid_first_row = false;
                }
            }

            fclose($handle);
            unlink($filepath);
        }

        return $imported;
    }
}
