<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ErpSettingsModel extends Model
{
    protected $table = 'erp_settings';
    protected $primaryKey = 'setting_id';
    protected $allowedFields = ['s_name', 's_value'];
   
    protected $db;
    protected $session;
    public function __construct()
    {
        
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_setting($key)
    {
        $query = "SELECT s_value FROM erp_settings WHERE s_name=?";
        $result = $this->db->query($query, array($key))->getRow();
        return $result->s_value ?? "";
    }

    public function set_setting($key, $value)
    {
        $query = "UPDATE erp_settings SET s_value=? WHERE s_name=?";
        $this->db->query($query, array($value, $key));
    }

    public function update_smtp()
    {
        foreach ($_POST as $key => $value) {
            if ($key == "smtp_password") {
                if (!empty(trim($value))) {
                    $this->set_setting($key, $value);
                }
            } else {
                $this->set_setting($key, $value);
            }
        }
        $config['title'] = "Mail Settings Update";
        $config['log_text'] = "[ Mail Settings successfully updated ]";
        $config['ref_link'] = "";
        $this->session->setFlashdata("op_success", "Mail Settings successfully updated");
        log_activity($config);
    }

    public function get_smtp_settings()
    {
        $query = "SELECT s_name,s_value FROM erp_settings WHERE s_name IN ('company_name','mail_engine','email_encryption','smtp_host','smtp_port','smtp_username','smtp_password','bcc_list','cc_list','track_quota') ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            $m_result[$r['s_name']] = $r['s_value'] ?? "";
        }
        return $m_result;
    }

    public function update_finance()
    {
        foreach ($_POST as $key => $value) {
            $this->set_setting($key, $value);
        }
        $config['title'] = "Finance Settings Update";
        $config['log_text'] = "[ Finance Settings successfully updated ]";
        $config['ref_link'] = "";
        $this->session->setFlashdata("op_success", "Finance Settings successfully updated");
        log_activity($config);
    }

    public function get_finance_settings()
    {
        $query = "SELECT s_name,s_value FROM erp_settings WHERE s_name IN ('close_account_book','finance_capital') ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            $m_result[$r['s_name']] = $r['s_value'] ?? "";
        }
        return $m_result;
    }

    public function get_general_settings()
    {
        $query = "SELECT s_name,s_value FROM erp_settings WHERE s_name IN ('company_name','address','city','state','country','zip','phone','gst') ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            $m_result[$r['s_name']] = $r['s_value'] ?? "";
        }
        return $m_result;
    }

    public function getEmail(){
        $result = $this->builder()->whereIn("s_name",["smtp_host","smtp_port","smtp_username",
        "smtp_password","email_encryption"])->get()->getResultArray();
        return $this->doMapping($result);
    }

    private function doMapping($array){
        $mArray = [];
        foreach($array as $value){
            $mArray[$value['s_name']] = $value['s_value'];
        }
        return $mArray;
    }


}
