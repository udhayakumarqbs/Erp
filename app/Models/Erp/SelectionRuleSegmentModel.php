<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SelectionRuleSegmentModel extends Model
{
    protected $table = 'selection_rule_segment';
    protected $primaryKey = 'rule_seg_id';
    protected $allowedFields = [
        'rule_id',
        'segment_id',
        'segment_value_idx',
        'above_below',
        'exclude'
    ];

    protected $session;
    protected $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    public function get_selectionrule_segment($rule_id)
    {
        $query = "SELECT segment_id, segment_value_idx, above_below, exclude FROM selection_rule_segment WHERE rule_id = ?";
        $result = $this->db->query($query, [$rule_id])->getResultArray();
        $m_result = [];
        foreach ($result as $row) {
            $m_result[$row['segment_id']] = [
                "segment_value_idx" => $row['segment_value_idx'],
                "above_below"       => $row['above_below'],
                "exclude"           => $row['exclude']
            ];
        }
        return $m_result;
    }


    public function delete_selectionrule($rule_id)
    {
        $deleted = false;

        $this->db->transBegin();

        try {
            // Delete from selection_rule_segment
            $this->db->table('selection_rule_segment')->where('rule_id', $rule_id)->delete();

            // Delete from selection_rule
            $this->db->table('selection_rule')->where('rule_id', $rule_id)->delete();

            $this->db->transCommit();
            $deleted = true;

            $config['title'] = "Selection Rule Delete";
            $config['log_text'] = "[ Selection Rule successfully deleted ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Selection Rule successfully deleted");
        } catch (\Exception $e) {
            $this->db->transRollback();
            $config['title'] = "Selection Rule Delete";
            $config['log_text'] = "[ Selection Rule failed to delete ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Selection Rule failed to delete");
            log_activity($config);
        }

        return $deleted;
    }
}
