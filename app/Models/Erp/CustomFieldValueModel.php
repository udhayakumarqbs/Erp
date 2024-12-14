<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class CustomFieldValueModel extends Model
{
    protected $table = 'custom_field_values';
    protected $primaryKey = 'cfv_id';
    protected $allowedFields = ['cf_id', 'related_id', 'field_value'];

    private $checkbox_counter = 0;

    public function get_checkbox_counter()
    {
        return $this->checkbox_counter;
    }

    public function get_custom_fields_for_view($related, $related_id)
    {
        $html = '';
        $builder = $this->db->table('custom_field_values');
        $builder->select('custom_fields.field_name, GROUP_CONCAT(custom_field_values.field_value SEPARATOR \',\') AS field_value');
        $builder->join('custom_fields', 'custom_field_values.cf_id = custom_fields.cf_id');
        $builder->where('can_be_purged', 0);
        $builder->where('field_related_to', $related);
        $builder->where('related_id', $related_id);
        $builder->groupBy('custom_field_values.cf_id');
        $builder->orderBy('order_num', 'ASC');
        $fields = $builder->get()->getResultArray();

        foreach ($fields as $field) {
            $html .= '
            <tr>
                <th>' . $field['field_name'] . '</th>
                <td>' . $field['field_value'] . '</td>
            </tr>
        ';
        }

        return $html;
    }

    public function get_custom_fields_for_edit($related, $related_id)
    {
        $html = '';
        $fields = $this->db->query("SELECT custom_fields.cf_id,IFNULL(custom_field_values.field_value,'') AS field_value,custom_fields.field_name,custom_fields.field_type,custom_fields.required,custom_fields.field_options FROM custom_field_values RIGHT JOIN custom_fields ON custom_field_values.cf_id=custom_fields.cf_id WHERE can_be_purged=0 AND field_related_to='$related' AND ( ISNULL(related_id) OR related_id=$related_id  ) ORDER BY order_num ASC")->getResultArray();
        foreach ($fields as $field) {
            switch ($field['field_type']) {
                case "input":
                    $html .= $this->html_input_field($field, "input");
                    break;
                case "date":
                    $html .= $this->html_input_field($field, "date");
                    break;
                case "number":
                    $html .= $this->html_input_field($field, "number");
                    break;
                case "checkbox":
                    $html .= $this->html_checkbox_field($field);
                    break;
                case "radio":
                    $html .= $this->html_radio_field($field);
                    break;
                default:
                    break;
            }
        }
        return $html;
    }

    private function html_input_field($field, $type)
    {
        $html = '
            <div class="form-width-2" >
                <div class="form-group 
        ';
        if ($field['required'] == 1) {
            $html .= ' field-required ';
        }
        $html .= '" >';
        $html .= '
            <label class="form-label" >' . $field['field_name'] . ' </label>
            <input type="' . $type . '" name="cf_' . $field['cf_id'] . '" ';
        if (!empty($field['field_value'])) {
            $html .= ' value="' . $field['field_value'] . '" ';
        }
        $html .= ' class="form_control field-check" />
            <p class="error-text" ></p>
            </div>
        </div>
        ';
        return $html;
    }

    private function html_checkbox_field($field)
    {
        $html = '
            <div class="form-width-2" >
                <div class="form-group 
        ';
        if ($field['required'] == 1) {
            $html .= ' field-checked ';
        }
        $html .= '" >';
        $html .= '
            <label class="form-label" >' . $field['field_name'] . ' </label>
            <div>';

        $checkboxes = explode(",", $field['field_options']);
        $i = 0;
        foreach ($checkboxes as $chkbx) {
            $html .= '<label class="form-check-label"><input type="checkbox" ';
            if (!empty($field['field_value'])) {
                $s_options = explode(",", $field['field_value']);
                foreach ($s_options as $option) {
                    if ($option == $chkbx) {
                        $html .= ' checked ';
                        break;
                    }
                }
            }
            $html .= ' value="' . $chkbx . '" class="field-check" name="cf_checkbox_' . $this->checkbox_counter . '_' . $field['cf_id'] . '_' . $i . '" />' . $chkbx . '</label>';
            $i++;
        }
        $html .= '</div>
            <p class="error-text" ></p>
            </div>
        </div>
        ';
        $this->checkbox_counter++;
        return $html;
    }

    private function html_radio_field($field)
    {
        $html = '
            <div class="form-width-2" >
                <div class="form-group 
        ';
        if ($field['required'] == 1) {
            $html .= ' field-checked-any ';
        }
        $html .= '" >';
        $html .= '
            <label class="form-label" >' . $field['field_name'] . ' </label>
            <div>';
        $radios = explode(",", $field['field_options']);
        foreach ($radios as $radio) {
            $html .= '<label class="form-check-label"><input class="field-check" ';
            if (!empty($field['field_value']) && $field['field_value'] == $radio) {
                $html .= ' checked ';
            }
            $html .= ' value="' . $radio . '" type="radio" name="cf_' . $field['cf_id'] . '" />' . $radio . '</label>';
        }
        $html .= '</div>
            <p class="error-text" ></p>
            </div>
        </div>
        ';
        return $html;
    }
}
