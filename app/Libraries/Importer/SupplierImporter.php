<?php

namespace App\Libraries\Importer;

use CodeIgniter\Config\Services;

class SupplierImporter implements AbstractImporter
{
    private $type = AbstractImporter::CSV;
    private $table = "suppliers";
    private $id_name = "supplier_id";
    private $ci;

    private $columns = array(
        "Name" => array("col" => "name", "req" => true, "sample" => "data"),
        "Code" => array("col" => "code", "req" => true, "uniq" => true, "sample" => "data"),
        "Position" => array("col" => "position", "req" => true, "sample" => "data"),
        "Address" => array("col" => "address", "req" => true, "sample" => "data"),
        "City" => array("col" => "city", "req" => true, "sample" => "data"),
        "State" => array("col" => "state", "req" => true, "sample" => "data"),
        "Country" => array("col" => "country", "req" => true, "sample" => "data"),
        "Zipcode" => array("col" => "zipcode", "req" => true, "sample" => "data"),
        "Phone" => array("col" => "phone", "req" => true, "sample" => "0000000000"),
        "Fax Number" => array("col" => "fax_number", "sample" => "0000000000"),
        "Office Number" => array("col" => "office_number", "sample" => "0000000000"),
        "Email" => array("col" => "email", "req" => true, "uniq" => true, "sample" => "data@data.com"),
        "Website" => array("col" => "website", "sample" => "data"),
        "Company" => array("col" => "company", "req" => true, "sample" => "data"),
        "GST" => array("col" => "gst", "req" => true, "sample" => "data"),
        "Payment Terms" => array("col" => "payment_terms", "sample" => "data"),
        "Description" => array("col" => "description", "sample" => "data")
    );

    // Must be in the same table
    private $common_columns = array();

    // Will change in every importer
    private $groups_query = array();
    private $custom_fields_query = array();

    public function __construct($type)
    {
        $this->type = $type;
        $this->ci = \Config\Database::connect();
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
        $checkbox = array();
        $query = "INSERT INTO custom_field_values(cf_id,related_id,field_value) VALUES({f1},?,'{f2}') ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } elseif (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $tmp_query = $query;
                $tmp_query = str_replace("{f1}", $cf_id, $tmp_query);
                $tmp_query = str_replace("{f2}", $value, $tmp_query);
                array_push($this->custom_fields_query, $tmp_query);
            }
        }
        $checkbox_counter = intval($_POST["customfield_chkbx_counter"]);
        for ($i = 0; $i <= $checkbox_counter; $i++) {
            $values = array();
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

    public function import($filepath, $common_columns = array())
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
        $tmp_array = array();
        $index = 0;
        foreach ($this->columns as $key => $column) {
            $value = isset($fields[$index]) ? trim($fields[$index], '"') : "";
            if (isset($column['req'])) {
                if ($value == "") {
                    $field_valid = false;
                    break;
                }
            }
            if (isset($column['uniq'])) {
                $query = "SELECT " . $this->id_name . " FROM " . $this->table . " WHERE " . $column["col"] . "=" . $this->ci->escape($value);
                $check = $this->ci->query($query)->getRow();
                if (!empty($check)) {
                    $field_valid = false;
                    break;
                }
            }
            array_push($tmp_array, $value);
            $index++;
        }
        if ($field_valid) {
            return $tmp_array;
        } else {
            return array();
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
        $query = $this->get_query();
        $ci = \Config\Database::connect();
        $avoid_first_row = true;
        $imported = -1;
    
        if (file_exists($filepath) && strtolower(pathinfo($filepath, PATHINFO_EXTENSION)) == "csv") {
            $handle = fopen($filepath, "r");
    
            if ($handle !== false) {
                $imported = 1;
    
                while (($fields = fgetcsv($handle)) !== false) {
                    if (!$avoid_first_row) {
                        $tmp_array = $this->sanitize_field($fields);
    
                        $ci->transBegin();
    
                        if (count($tmp_array) > 0) {
                            $ci->query($query, $tmp_array);
                        } else {
                            $imported = 0;
                        }
    
                        $insert_id = $ci->insertID();
    
                        if (empty($insert_id)) {
                            $imported = 0;
                        } else {
                            $op_broken = false;
    
                            foreach ($this->groups_query as $q) {
                                $ci->query($q, array($insert_id));
    
                                if (empty($ci->insertID())) {
                                    $ci->transRollback();
                                    $imported = 0;
                                    $op_broken = true;
                                    break;
                                }
                            }
    
                            if (!$op_broken) {
                                foreach ($this->custom_fields_query as $q) {
                                    $ci->query($q, array($insert_id));
    
                                    if (empty($ci->insertID())) {
                                        $ci->transRollback();
                                        $imported = 0;
                                        $op_broken = true;
                                        break;
                                    }
                                }
                            }
    
                            $ci->transComplete();
    
                            if ($ci->transStatus() === FALSE) {
                                $imported = 0;
                                $ci->transRollback();
                            } else {
                                $ci->transCommit();
                            }
                        }
                    } else {
                        $avoid_first_row = false;
                    }
                }
    
                fclose($handle);
                unlink($filepath);
            }
        }
    
        return $imported;
    }

    public function download_template()
    {
        $csv_array = array();
        foreach ($this->columns as $key => $col) {
            array_push($csv_array, $key);
        }
        header("Cache-Control:no-cache");
        header("Expires:0");
        header("Content-Disposition:attachment;filename=SupplierImport.csv");
        $out = fopen("php://output", "w");
        foreach ($this->columns as $key => $col) {
            $data = '"' . $key . '",';
            fwrite($out, $data);
        }
        fclose($out);
        exit();
    }
}
