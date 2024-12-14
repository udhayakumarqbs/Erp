<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SupplierSegmentMapModel extends Model
{
    protected $table = 'supplier_segment_map';
    protected $primaryKey = 'supp_seg_id';
    protected $allowedFields = ['supplier_id', 'segment_json'];

    public function get_segments_for_supplier($supplier_id)
    {
        helper('form');
        $query = "SELECT segment_json FROM supplier_segment_map WHERE supplier_id=$supplier_id";
        $result = $this->db->query($query)->getRow();
        $segments = $this->get_all_segments();
        $saved_seg = array();
        if (!empty($result)) {
            $json = json_decode($result->segment_json, true);
            foreach ($json as $key => $value) {
                $saved_seg[$key] = $value;
            }
        }
        $segment_html = '
                        <div class="form-group textCenter">
                            <h3 class="text-danger">Sorry no segments created </h3>
                            <a href="' . base_url() . 'erp/supplier/segmentadd" class="text-primary" >create segements first</a>
                        </div>';
        if (!empty($segments)) {
            $segment_html = form_open(base_url() . 'erp/supplier/supplier-segment/' . $supplier_id, array(
                "id" => "supplier_segment_form"
            ));
            foreach ($segments as $row) {
                $segment_html .= '
                <input type="hidden" value="1" name="formsubmit" />
                <div class="form-width-1">
                <div class="form-group field-required ">
                    <label class="form-label">' . $row['segment_key'] . '</label>
                    <div class="selectBox poR">
                        <div class="selectBoxBtn flex"> 
                            <div class="textFlow" data-default="select value">select value</div>
                            <button class="close" type="button" ><i class="fa fa-close"></i></button>
                            <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value field-check" name="segment_value_idx_' . $row['segment_id'] . '"';
                if (isset($saved_seg[$row['segment_id']])) {
                    $segment_html .= ' value="' . $saved_seg[$row['segment_id']] . '" ';
                }
                $segment_html .= '>
                        </div>
                        <ul role="listbox" class="selectBox_Container alldiv">';
                $segment_values = json_decode($row['segment_value'], true);
                foreach ($segment_values as $key => $value) {
                    $segment_html .= '<li role="option" data-value="' . $key . '" >' . $value . '</li>';
                }
                $segment_html .= '</ul>
                    </div>
                    <p class="error-text" ></p>
                </div>
                </div>
                ';
            }
            $segment_html .= '
            <div class="form-width-1">
                <div class="form-group textRight">
                    <button class="btn bg-primary" type="button" id="supplier_segment_submit">Update</button>
                </div>
            </div>
            ';
            $segment_html .= form_close();
        }
        return $segment_html;
    }
    // Assuming $db is an instance of the database connection

    public function get_all_segments()
    {
        $builder = $this->db->table('supplier_segments');
        $builder->select('segment_id, segment_key, segment_value');
        $result = $builder->get()->getResultArray();

        return $result;
    }
}
