<?php

namespace App\Libraries\Importer;

use CodeIgniter\Config\Services;

class FinishedgoodImporter implements AbstractImporter
{
    private $type = AbstractImporter::CSV;
    private $table = "finished_goods";
    private $id_name = "finished_good_id";
    private $columns = [
        "Name" => ["col" => "name", "pos" => 0, "req" => true, "uniq" => false, "sample" => "data"],
        "Code" => ["col" => "code", "pos" => 1, "req" => true, "uniq" => true, "sample" => "ABC123"],
        "Short Description" => ["col" => "short_desc", "pos" => 2, "req" => false, "uniq" => false, "sample" => "data"],
        "Long Description" => ["col" => "long_desc", "pos" => 3, "req" => false, "uniq" => false, "sample" => "data"]
    ];

    private $common_columns = [];
    private $warehouse_query = [];
    private $custom_fields_query = [];
    private $db;

    public function __construct($type)
    {
        $this->type = $type;
        $this->db = \Config\Database::connect();
    }

    public function get_columns()
    {
        return $this->columns;
    }

    private function setup_warehouse_query()
    {
        $warehouses = explode(",", $_POST["warehouses"]);
        $query = "INSERT INTO inventory_warehouse(warehouse_id,related_to,related_id) VALUES({f1}, 'finished_good', ?) ";

        foreach ($warehouses as $warehouse) {
            $tmp_query = $query;
            $tmp_query = str_replace("{f1}", $warehouse, $tmp_query);
            array_push($this->warehouse_query, $tmp_query);
        }
    }

    private function setup_custom_fields_query()
    {
        $checkbox = [];
        $query = "INSERT INTO custom_field_values(cf_id,related_id,field_value) VALUES(?, ?, ?) ";

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
        $this->setup_warehouse_query();
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
        $query = $this->get_query();
        $db = \Config\Database::connect();
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
                    $db->transBegin();

                    if (count($tmp_array) > 0) {
                        $db->query($query, $tmp_array);
                    } else {
                        $imported = 0;
                    }

                    $insert_id = $db->insertID();

                    if (empty($insert_id)) {
                        $imported = 0;
                    } else {
                        $op_broken = false;

                        foreach ($this->warehouse_query as $q) {
                            $db->query($q, [$insert_id]);

                            if (empty($db->insertID())) {
                                $db->transRollback();
                                $imported = 0;
                                $op_broken = true;
                                break;
                            }
                        }

                        if (!$op_broken) {
                            foreach ($this->custom_fields_query as $q) {
                                $db->query($q, [$insert_id]);

                                if (empty($db->insertID())) {
                                    $db->transRollback();
                                    $imported = 0;
                                    $op_broken = true;
                                    break;
                                }
                            }
                        }

                        $db->transComplete();

                        if ($db->transStatus() === false) {
                            $imported = 0;
                            $db->transRollback();
                        } else {
                            $db->transCommit();
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
        header("Content-Disposition:attachment;filename=FinishedgoodImport.csv");
        $out = fopen("php://output", "w");

        foreach ($this->columns as $key => $col) {
            $data = '"' . $key . '",';
            fwrite($out, $data);
        }

        fclose($out);
        exit();
    }
}
