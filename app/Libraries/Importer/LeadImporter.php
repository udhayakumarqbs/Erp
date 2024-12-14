<?php

namespace App\Libraries\Importer;

class LeadImporter implements AbstractImporter
{
    private $type = AbstractImporter::CSV;
    private $table = "leads";
    protected $db;
    private $id_name = "lead_id";

    private $columns = [
        "Name" => array("col" => "name", "pos" => 0, "req" => true, "uniq" => false, "sample" => "data"),
        "Position" => array("col" => "position", "pos" => 1, "req" => true, "uniq" => false, "sample" => "data"),
        "Address" => array("col" => "address", "pos" => 2, "req" => true, "uniq" => false, "sample" => "data"),
        "City" => array("col" => "city", "pos" => 3, "req" => true, "uniq" => false, "sample" => "data"),
        "State" => array("col" => "state", "pos" => 4, "req" => true, "uniq" => false, "sample" => "data"),
        "Country" => array("col" => "country", "pos" => 5, "req" => true, "uniq" => false, "sample" => "data"),
        "Zipcode" => array("col" => "zip", "pos" => 6, "req" => true, "uniq" => false, "sample" => "data"),
        "Phone" => array("col" => "phone", "pos" => 7, "req" => true, "uniq" => false, "sample" => "0000000000"),
        "Email" => array("col" => "email", "pos" => 8, "req" => true, "uniq" => true, "sample" => "data@data.com"),
        "Website" => array("col" => "website", "pos" => 9, "req" => false, "uniq" => false, "sample" => "data"),
        "Company" => array("col" => "company", "pos" => 10, "req" => true, "uniq" => false, "sample" => "data"),
        "Description" => array("col" => "description", "pos" => 11, "req" => false, "uniq" => false, "sample" => "data")
    ];
    private $common_columns = [];

    public function __construct($type)
    {
        $this->type = $type;
        $this->db = \Config\Database::connect();
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
        $csv_array = [];
        foreach ($this->columns as $key => $col) {
            array_push($csv_array, $key);
        }

        header("Cache-Control:no-cache");
        header("Expires:0");
        header("Content-Disposition:attachment;filename=LeadImport.csv");
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

        foreach ($this->columns as $key => $column) {
            $pos = $column["pos"];
            $value = isset($fields[$pos]) ? trim($fields[$pos], '"') : "";

            if ($column['req'] == true) {
                if ($value == "") {
                    $field_valid = false;
                    break;
                }
            }

            if ($column['uniq'] == true) {
                $query = "SELECT " . $this->id_name . " FROM " . $this->table . " WHERE " . $column["col"] . "=" . $this->db->escape($value);
                $check = $this->db->query($query)->getRow();

                if (!empty($check)) {
                    $field_valid = false;
                    break;
                }
            }

            array_push($tmp_array, $value);
        }

        if ($field_valid) {
            return $tmp_array;
        } else {
            return [];
        }
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

        $query = rtrim($query, ',');
        $query .= ") VALUES(";

        foreach ($this->columns as $column) {
            $query .= "?,";
        }

        foreach ($this->common_columns as $key => $value) {
            if (is_string($value)) {
                $query .= "'" . $value . "',";
            } else {
                $query .= $value . ",";
            }
        }

        $query = rtrim($query, ',');
        $query .= ")";

        return $query;
    }

    private function import_from_csv($filepath)
    {
        $query = $this->get_query();
        $avoid_first_row = true;
        $imported = -1;

        if (file_exists($filepath) && strtolower(pathinfo($filepath, PATHINFO_EXTENSION)) == "csv") {
            $handle = fopen($filepath, "r");
            $imported = 1;

            while (!feof($handle)) {
                $line = fgets($handle);
                $idx = stripos($line, "\n");

                if ($idx !== FALSE) {
                    $line = substr($line, 0, $idx);
                } else {
                    $idx = stripos($line, "\r\n");

                    if ($idx !== FALSE) {
                        $line = substr($line, 0, $idx);
                    }
                }

                $fields = explode(",", $line);

                if (!$avoid_first_row) {
                    $tmp_array = $this->sanitize_field($fields);

                    if (count($tmp_array) > 0) {
                        $result = $this->db->query($query, $tmp_array);

                        if ($result === false) {
                           
                            log_message('error', 'Query error: ' . $this->db->error());
                            log_message('error', 'Query: ' . $this->db->getLastQuery());
                            log_message('error', 'Data: ' . implode(', ', $tmp_array));
                            $imported = 0;
                        } else {
                            
                            log_message('info', 'Query successful: ' . $this->db->getLastQuery());

                            
                            if (is_bool($result)) {
                                log_message('info', 'Query result is a boolean: ' . ($result ? 'true' : 'false'));
                            } else {
                                log_message('info', 'Query result is an object: ' . get_class($result));
                            }

                            $insert_id = $this->db->insertID();

                            if (empty($insert_id) && $insert_id !== 0) {
                                log_message('error', 'Insert ID not found.');
                                $imported = 0;
                            }
                        }
                    } else {
                        $imported = 0;
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
