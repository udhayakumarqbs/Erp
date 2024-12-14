<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class AccountGroupsModel extends Model
{
    protected $table = 'account_groups';
    protected $primaryKey = 'acc_group_id';
    protected $allowedFields = [
        'base_id',
        'group_name',
        'profit_loss',
        'created_at',
        'created_by',
    ];

    public function insert_update_accgroup()
    {
        $accgroup_id = $this->input->post("acc_group_id");
        if (empty($accgroup_id)) {
            $this->insert_accgroup();
        } else {
            $this->update_accgroup($accgroup_id);
        }
    }

    private function insert_accgroup()
    {
        $group_name = $this->input->post("group_name");
        $base_id = trim($this->input->post("base_id"));
        $profit_loss = $this->input->post("profit_loss");
        $created_by = get_user_id();
        $query = "INSERT INTO account_groups(group_name,base_id,profit_loss,created_at,created_by) VALUES(?,?,?,?,?) ";
        $this->db->query($query, array($group_name, $base_id, $profit_loss, time(), $created_by));

        $config['title'] = "Account Group Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Account Group successfully created ]";
            $config['ref_link'] = 'erp/finance/accountgroups/';
            $this->session->set_flashdata("op_success", "Account Group successfully created");
        } else {
            $config['log_text'] = "[ Account Group failed to create ]";
            $config['ref_link'] = "";
            $this->session->set_flashdata("op_error", "Account Group failed to create");
        }
        log_activity($config);
    }

    private function update_accgroup($accgroup_id)
    {
        $group_name = $this->input->post("group_name");
        $base_id = trim($this->input->post("base_id"));
        $profit_loss = $this->input->post("profit_loss");

        $query = "UPDATE account_groups SET group_name=? , base_id=? , profit_loss=? WHERE acc_group_id=$accgroup_id ";
        $this->db->query($query, array($group_name, $base_id, $profit_loss));

        $config['title'] = "Account Group Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Account Group successfully updated ]";
            $config['ref_link'] = 'erp/finance/accountgroups/';
            $this->session->set_flashdata("op_success", "Account Group successfully updated");
        } else {
            $config['log_text'] = "[ Account Group failed to update ]";
            $config['ref_link'] = "";
            $this->session->set_flashdata("op_error", "Account Group failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_accgroups()
    {
        $config = array(
            "columnNames" => ["sno", "group name", "base name", "profit-loss", "action"],
            "sortable" => [0, 1, 0, 1, 0],
        );
        return json_encode($config);
    }

    public function get_accgroups_datatable()
    {
        $columns = array(
            "profit-loss" => "profit_loss",
            "group name" => "group_name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT acc_group_id,group_name,base_name,profit_loss FROM account_groups JOIN accountbase ON account_groups.base_id=accountbase.base_id ";
        $count = "SELECT COUNT(acc_group_id) AS total FROM account_groups JOIN accountbase ON account_groups.base_id=accountbase.base_id ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE group_name LIKE '%" . $search . "%' ";
                $count .= " WHERE group_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND group_name LIKE '%" . $search . "%' ";
                $count .= " AND group_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/finance/ajax_fetch_accgroup/' . $r['acc_group_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/finance/accgroupdelete/' . $r['acc_group_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $profit_loss = '<span class="st ';
            if ($r['profit_loss'] == 1) {
                $profit_loss .= 'st_violet" >Yes</span>';
            } else {
                $profit_loss .= 'st_dark" >No</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['acc_group_id'],
                    $sno,
                    $r['group_name'],
                    $r['base_name'],
                    $profit_loss,
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function get_accgroups_export($type)
    {
        $columns = array(
            "profit-loss" => "profit_loss",
            "group name" => "group_name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT group_name,base_name, CASE WHEN profit_loss=1 THEN 'Yes' ELSE 'No' END AS profit_loss FROM account_groups JOIN accountbase ON account_groups.base_id=accountbase.base_id ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE group_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND group_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function delete_accgroup($accgroup_id)
    {
        $deleted = false;
        $check = $this->db->query("SELECT COUNT(acc_group_id) AS total FROM gl_accounts WHERE acc_group_id=$accgroup_id ")->getRow();
        if ($check->total <= 0) {
            $query = "DELETE FROM account_groups WHERE acc_group_id=$accgroup_id";
            $this->db->query($query);
            if ($this->db->affectedRows() > 0) {
                $deleted = true;
            }
        }
        $config['title'] = "Account Group Delete";
        if ($deleted) {
            $config['log_text'] = "[ Account Group successfully deleted ]";
            $config['ref_link'] = 'erp/finance/accountgroups/';
            $this->session->set_flashdata("op_success", "Account Group successfully deleted");
        } else {
            $config['log_text'] = "[ Account Group failed to delete ]";
            $config['ref_link'] = "erp/finance/accountgroups/";
            $this->session->set_flashdata("op_error", "Account Group failed to delete");
        }
        log_activity($config);
    }

    public function get_account_groups()
    {
        $query = "SELECT acc_group_id,group_name FROM account_groups ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
