<?php

namespace App\Libraries\Importer;

// defined('BASEPATH') or exit('No direct script access allowed');

class CustomerImporter implements AbstractImporter
{
    private $type = AbstractImporter::CSV;
    private $table = "customers";
    private $id_name = "cust_id";
    private $ci;
    private $db;

    private $columns = [
        "Name" => ["col" => "name", "pos" => 0, "req" => true, "uniq" => false, "sample" => "data"],
        "Position" => ["col" => "position", "pos" => 1, "req" => true, "uniq" => false, "sample" => "data"],
        "Address" => ["col" => "address", "pos" => 2, "req" => true, "uniq" => false, "sample" => "data"],
        "City" => ["col" => "city", "pos" => 3, "req" => true, "uniq" => false, "sample" => "data"],
        "State" => ["col" => "state", "pos" => 4, "req" => true, "uniq" => false, "sample" => "data"],
        "Country" => ["col" => "country", "pos" => 5, "req" => true, "uniq" => false, "sample" => "data"],
        "Zipcode" => ["col" => "zip", "pos" => 6, "req" => true, "uniq" => false, "sample" => "data"],
        "Phone" => ["col" => "phone", "pos" => 7, "req" => true, "uniq" => false, "sample" => "0000000000"],
        "Fax Number" => ["col" => "fax_num", "pos" => 8, "req" => false, "uniq" => false, "sample" => "0000000000"],
        "Office Number" => ["col" => "office_num", "pos" => 9, "req" => false, "uniq" => false, "sample" => "0000000000"],
        "Email" => ["col" => "email", "pos" => 10, "req" => true, "uniq" => true, "sample" => "data@data.com"],
        "Website" => ["col" => "website", "pos" => 11, "req" => false, "uniq" => false, "sample" => "data"],
        "Company" => ["col" => "company", "pos" => 12, "req" => true, "uniq" => false, "sample" => "data"],
        "GST" => ["col" => "gst", "pos" => 13, "req" => true, "uniq" => false, "sample" => "data"],
        "Description" => ["col" => "description", "pos" => 14, "req" => false, "uniq" => false, "sample" => "data"]
    ];

    private $common_columns = [];
    private $groups_query = [];
    private $custom_fields_query = [];

    public function __construct($type)
    {
        $this->type = $type;
        $this->ci = \Config\Services::request();
        $this->db = \Config\Database::connect();
    }

    public function get_columns()
    {
        return $this->columns;
    }

    private function setup_group_query()
    {
        $groups = explode(",", $_POST["groups"]);
        $query = "INSERT INTO erp_groups_map(group_id,related_id) VALUES({f1},?) ";
        foreach ($groups as $group) {
            $tmp_query = $query;
            $tmp_query = str_replace("{f1}", $group, $tmp_query);
            array_push($this->groups_query, $tmp_query);
        }
    }

    private function setup_custom_fields_query()
    {
        $checkbox = [];
        $query = "INSERT INTO custom_field_values(cf_id,related_id,field_value) VALUES({f1},?,'{f2}') ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } else if (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $tmp_query = $query;
                $tmp_query = str_replace("{f1}", $cf_id, $tmp_query);
                $tmp_query = str_replace("{f2}", $value, $tmp_query);
                array_push($this->custom_fields_query, $tmp_query);
            }
        }
        $checkbox_counter = intval($_POST["customfield_chkbx_counter"]);
        for ($i = 0; $i <= $checkbox_counter; $i++) {
            $values = [];
            $cf_id = 0;
            foreach ($checkbox as $key => $value) {
                if (stripos($key, "cf_checkbox_" . $i . "_") === 0) {
                    array_push($values, $value);
                    $cf_id = explode("_", $key)[3];
                }
            }
            if (!empty($cf_id)) {
                $tmp_query = $query;
                $tmp_query = str_replace("{f1}", $cf_id, $tmp_query);
                $tmp_query = str_replace("{f2}", implode(",", $values), $tmp_query);
                array_push($this->custom_fields_query, $tmp_query);
            }
        }
    }

    public function import($filepath, $common_columns = [])
    {
        $imported = -1;
        $this->common_columns = $common_columns;
        $this->setup_group_query();
        $this->setup_custom_fields_query();
        switch ($this->type) {
            case AbstractImporter::CSV:
                $imported = $this->import_from_csv($filepath);
                break;
            default:
                break;
        }
        return $imported;
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
        $query = substr($query, 0, strlen($query) - 1);
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
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ")";
        return $query;
    }

    private function import_from_csv($filepath)
    {
        $ci = \Config\Services::db();
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
                    $this->db->transBegin();
                    if (count($tmp_array) > 0) {
                        $this->db->query($query, $tmp_array);
                    } else {
                        $imported = 0;
                    }
                    $insert_id = $this->db->insertID();
                    if (empty($insert_id)) {
                        $imported = 0;
                    } else {
                        $op_broken = false;
                        foreach ($this->groups_query as $q) {
                            $this->db->query($q, [$insert_id]);
                            if (empty($this->db->insertID())) {
                                $this->db->transRollback();
                                $imported = 0;
                                $op_broken = true;
                                break;
                            }
                        }
                        if (!$op_broken) {
                            foreach ($this->custom_fields_query as $q) {
                                $this->db->query($q, [$insert_id]);
                                if (empty($this->db->insertID())) {
                                    $this->db->transRollback();
                                    $imported = 0;
                                    $op_broken = true;
                                    break;
                                }
                            }
                        }
                        $this->db->transComplete();
                        if ($this->db->transStatus() === false) {
                            $imported = 0;
                            $this->db->transRollback();
                        } else {
                            $this->db->transCommit();
                        }
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

    public function download_template()
    {
        $csv_array = [];
        foreach ($this->columns as $key => $col) {
            array_push($csv_array, $key);
        }
        header("Cache-Control:no-cache");
        header("Expires:0");
        header("Content-Disposition:attachment;filename=CustomerImport.csv");
        $out = fopen("php://output", "w");
        foreach ($this->columns as $key => $col) {
            $data = '"' . $key . '",';
            fwrite($out, $data);
        }
        fclose($out);
        exit();
    }
}
