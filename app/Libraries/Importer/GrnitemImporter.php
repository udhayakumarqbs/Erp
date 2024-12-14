<?php

namespace App\Libraries\Importer;

use CodeIgniter\Config\Services;

class GrnitemImporter
{
    private $ci;

    public function __construct()
    {
        $this->ci = Services::db();
    }

    public function download_template($grn_id)
    {
        $query = "SELECT related_to, related_id, COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product, received_qty FROM grn JOIN purchase_order_items ON grn.order_id=purchase_order_items.order_id LEFT JOIN raw_materials ON related_id=raw_material_id LEFT JOIN semi_finished ON related_id=semi_finished_id WHERE grn_id=$grn_id";
        $result = $this->ci->query($query)->getResultArray();
        
        header("Cache-Control:no-cache");
        header("Expires:0");
        header("Content-Disposition:attachment;filename=GrnItemsImport.csv");
        $out = fopen("php://output", "w");
        $csv_row = 'Related To,Related ID,Product Name,SKU,Manufacture Date,Bin Name,Batch No,Lot No,';
        fwrite($out, $csv_row);
        fwrite($out, "\r\n");

        foreach ($result as $row) {
            for ($i = 0; $i < $row['received_qty']; $i++) {
                $csv_row = $row['related_to'] . "," . $row['related_id'] . "," . $row['product'] . ",,,,,,\r\n";
                fwrite($out, $csv_row);
            }
        }

        fclose($out);
        exit();
    }

    private function is_row_valid(&$row)
    {
        if ($row[0] == "" || $row[1] == "" || $row[3] == "" || $row[4] == "") {
            return false;
        }

        $sku = $row[3];
        $dup = $this->ci->query("SELECT sku FROM pack_unit WHERE sku=" . $sku . " LIMIT 1")->getRow();

        if (!empty($dup)) {
            return false;
        }

        return true;
    }

    public function import($filepath, $grn_id)
    {
        $rows = 0;

        if (file_exists($filepath)) {
            $query = "INSERT INTO pack_unit(related_to, related_id, sku, mfg_date, bin_name, batch_no, lot_no) VALUES(?,?,?,?,?,?,?) ";
            $handle = fopen($filepath, "r");
            $first_row_skipped = false;

            while (!feof($handle)) {
                $line = fgets($handle);
                $fields = explode(",", $line);

                if ($first_row_skipped) {
                    if ($this->is_row_valid($fields)) {
                        $this->ci->query($query, array($fields[0], $fields[1], $fields[3], $fields[4], $fields[5], $fields[6], $fields[7]));

                        if (!empty($this->ci->insertID())) {
                            ++$rows;
                        }
                    }
                } else {
                    $first_row_skipped = true;
                }
            }
        }

        return $rows;
    }
}
