<?php

namespace App\Libraries\Importer;


class GlaccountImporter implements AbstractImporter
{
    private $type = AbstractImporter::CSV;
    private $table = "gl_accounts";
    private $id_name = "gl_acc_id";
    private $builder;

    private $columns = [
        "Account Name" => ["col" => "account_name", "pos" => 0, "req" => true, "uniq" => false, "sample" => "data"],
        "Account Code" => ["col" => "account_code", "pos" => 1, "req" => true, "uniq" => true, "sample" => "1000"],
        "Order" => ["col" => "order_num", "pos" => 2, "req" => false, "uniq" => false, "sample" => "1"]
    ];

    private $outer_table_start_1 = 3;
    private $outer_table_end_1 = 3;
    private $outer_table_columns_1 = [
        "Opening Balance" => ["col" => "city", "pos" => 3, "req" => true, "uniq" => false, "money" => true, "sample" => "12.34"]
    ];

    private $common_columns = [];

    public function __construct($type)
    {
        $this->type = $type;
        $this->builder = \Config\Database::connect();
    }

    public function get_columns()
    {
        return array_merge($this->columns, $this->outer_table_columns_1);
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
        $tmp_columns = array_merge($this->columns, $this->outer_table_columns_1);
        header("Cache-Control:no-cache");
        header("Expires:0");
        header("Content-Disposition:attachment;filename=GLAccountImport.csv");
        $out = fopen("php://output", "w");

        foreach ($tmp_columns as $key => $col) {
            $data = '"' . $key . '",';
            fwrite($out, $data);
        }

        fclose($out);
        exit();
    }

    private function sanitize_field(array &$fields): array
    {
        $field_valid = true;
        $tmp_array = [];
        $tmp_columns = array_merge($this->columns, $this->outer_table_columns_1);

        foreach ($tmp_columns as $key => $column) {
            $pos = $column["pos"];
            $value = isset($fields[$pos]) ? trim($fields[$pos], '"') : "";

            if ($column['req'] && $value === "") {
                $field_valid = false;
                break;
            }

            if ($column['uniq']) {
                $check = $this->builder
                    ->table($this->table)
                    ->where($column["col"], $value)
                    ->get()
                    ->getRow();

                if (!empty($check)) {
                    $field_valid = false;
                    break;
                }
            }

            if (isset($column['money']) && $column['money']) {
                if ($value !== "") {
                    $value = number_format(floatval($value), 2, '.', '');
                }
            }

            $tmp_array[] = $value;
        }

        if ($field_valid) {
            return $tmp_array;
        } else {
            return [];
        }
    }

    private function get_query(): string
    {
        $query = "INSERT INTO {$this->table}(";

        foreach ($this->columns as $column) {
            $query .= $column['col'] . ",";
        }

        foreach ($this->common_columns as $key => $value) {
            $query .= $key . ",";
        }

        $query = rtrim($query, ',') . ") VALUES(";

        foreach ($this->columns as $column) {
            $query .= "?,";
        }

        foreach ($this->common_columns as $key => $value) {
            if (is_string($value)) {
                $query .= "'{$value}',";
            } else {
                $query .= $value . ",";
            }
        }

        $query = rtrim($query, ',') . ")";
        return $query;
    }

    private function outer_table_query_1($gl_acc_id, $values): bool
    {
        $inserted = false;
        $period = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $query = "INSERT INTO general_ledger(gl_acc_id,period,actual_amt,balance_fwd) VALUES(?,?,0.00,{f1}) ";

        $field_count = 1;

        for ($i = $this->outer_table_start_1; $i <= $this->outer_table_end_1; $i++) {
            $query = str_replace("{f{$field_count}}", $this->builder->escape($values[$i]), $query);
            $field_count++;
        }

        $this->builder->query($query, [$gl_acc_id, $period]);

        if (!empty($this->builder->insertID())) {
            $inserted = true;
        }

        return $inserted;
    }

    private function import_from_csv($filepath): int
    {
        $query = $this->get_query();
        $avoid_first_row = true;
        $imported = -1;

        if (file_exists($filepath) && strtolower(pathinfo($filepath, PATHINFO_EXTENSION)) == "csv") {
            $handle = fopen($filepath, "r");

            if ($handle !== false) {
                $imported = 1;

                while (($fields = fgetcsv($handle)) !== false) {
                    if (!$avoid_first_row) {
                        $tmp_array = $this->sanitize_field($fields);

                        $this->builder->transStart();

                        if (count($tmp_array) > 0) {
                            $new_tmp_array = array_slice($tmp_array, 0, $this->outer_table_start_1);
                            $this->builder->query($query, $new_tmp_array);
                        } else {
                            $imported = 0;
                        }

                        $gl_acc_id = $this->builder->insertID();

                        if (empty($gl_acc_id)) {
                            $imported = 0;
                        } else {
                            if (!$this->outer_table_query_1($gl_acc_id, $tmp_array)) {
                                $this->builder->transRollback();
                                continue;
                            }
                        }

                        $this->builder->transComplete();

                        if ($this->builder->transStatus() === false) {
                            $this->builder->transRollback();
                        } else {
                            $this->builder->transCommit();
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
}
