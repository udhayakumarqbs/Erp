<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ErpGroupsModel extends Model
{
    protected $table = 'erp_groups';
    protected $primaryKey = 'group_id';
    protected $allowedFields = ['group_name', 'related_to'];
    protected $db;
    protected $session;
    public function __construct()
    {
        
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function get_rawmaterials_groups()
    {
        $query = "SELECT group_id,group_name FROM erp_groups WHERE related_to='raw_material' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_supplier_groups()
    {
        $relatedTo = 'supplier';
        return $this->builder()->select("group_id,group_name")->where("related_to", $relatedTo)->get()->getResultArray();
    }

    public function get_semifinished_groups()
    {
        $query = "SELECT group_id,group_name FROM erp_groups WHERE related_to='semi_finished' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_finished_goods_groups()
    {
        $query = "SELECT group_id,group_name FROM erp_groups WHERE related_to='finished_good' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_services_groups()
    {
        $query = "SELECT group_id,group_name FROM erp_groups WHERE related_to='inventory_service' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_customer_groups()
    {
        $query = "SELECT group_id,group_name FROM erp_groups WHERE related_to='customer' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    private $related_to = array(
        "customer" => "Customer",
        "expense" => "Expense",
        "inventory" => "Inventory",
        "raw_material" => "Raw Material",
        "semi_finished" => "Semi Finished",
        "finished_good" => "Finished Good",
        "supplier" => "Supplier",
        "inventory_service" => "Service"
    );

    public function get_related_to()
    {
        return $this->related_to;
    }

    public function insert_update_group($post)
    {
        $group_id = $post["group_id"];
        if (empty($group_id)) {
            $this->insert_group($post);
        } else {
            $this->update_group($group_id,$post);
        }
    }

    private function insert_group($post)
    {
        $group_name = $post["group_name"];
        $related_to = $post["related_to"];
        $check = $this->db->query("SELECT group_name FROM erp_groups WHERE group_name='$group_name' AND related_to='$related_to' LIMIT 1")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate Group name not allowed");
            return;
        }
        $query = "INSERT INTO erp_groups(group_name,related_to) VALUES(?,?) ";
        $this->db->query($query, array($group_name, $related_to));

        $config['title'] = "Group Insert";
        $group_id = $this->db->insertId();
        $config['ref_link'] = "";
        if (!empty($group_id)) {
            $config['log_text'] = "[ Group successfully inserted ]";
            $this->session->setFlashdata("op_success", "Group successfully inserted");
        } else {
            $config['log_text'] = "[ Group failed to insert ]";
            $this->session->setFlashdata("op_error", "Group failed to insert");
        }
        log_activity($config);
    }

    private function update_group($id,$post)
    {
        $group_name = $post["group_name"];
        $related_to = $post["related_to"];
        $check = $this->db->query("SELECT group_name FROM erp_groups WHERE group_name='$group_name' AND related_to='$related_to' AND group_id<>$id LIMIT 1")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate Group name not allowed");
            return;
        }
        $query = "UPDATE erp_groups SET group_name=? , related_to=? WHERE group_id=$id ";
        $this->db->query($query, array($group_name, $related_to));

        $config['title'] = "Group Update";
        $config['ref_link'] = "";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Group successfully updated ]";
            $this->session->setFlashdata("op_success", "Group successfully updated");
        } else {
            $config['log_text'] = "[ Group failed to update ]";
            $this->session->setFlashdata("op_error", "Group failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_groups()
    {
        $config = array(
            "columnNames" => ["sno", "group name", "related to", "action"],
            "sortable" => [0, 1, 1, 0]
        );
        return json_encode($config);
    }

    public function get_datatable_groups()
    {
        $columns = array(
            "group name" => "group_name",
            "related to" => "related_to",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT group_id,group_name,related_to FROM erp_groups ";
        $count = "SELECT COUNT(group_id) AS total FROM erp_groups ";

        $where = false;

        $filter_1_col = isset($_GET['role']) ? $columns['role'] : "";
        $filter_1_val = $_GET['role'] ?? "";

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if ($where) {
                $query .= " AND ( group_name LIKE '%" . $search . "%' OR related_to LIKE '%" . $search . "%' ) ";
                $count .= " AND ( group_name LIKE '%" . $search . "%' OR related_to LIKE '%" . $search . "%' ) ";
            } else {
                $query .= " WHERE ( group_name LIKE '%" . $search . "%' OR related_to LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( group_name LIKE '%" . $search . "%' OR related_to LIKE '%" . $search . "%' ) ";
                $where = true;
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $query .= "  LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="#" data-ajax-url="' . url_to('erp.setting.ajaxfetchgroup' , $r['group_id']) . '" title="Edit" class="bg-success modalBtn "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.setting.groupdelete' , $r['group_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['group_id'],
                    $sno,
                    $r['group_name'],
                    $this->related_to[$r['related_to']],
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

    public function delete_group($id)
    {
        $check1 = $this->db->query("SELECT COUNT(group_id) AS total FROM erp_groups_map WHERE group_id=$id")->getRow()->total;
        if ($check1 > 0) {
            $this->session->setFlashdata("op_error", "Group name is already in use");
        } else {
            $check2 = $this->db->query("SELECT COUNT(group_id) AS total FROM raw_materials WHERE group_id=$id")->getRow()->total;
            if ($check2 > 0) {
                $this->session->setFlashdata("op_error", "Group name is already in use");
            } else {
                $check3 = $this->db->query("SELECT COUNT(group_id) AS total FROM semi_finished WHERE group_id=$id")->getRow()->total;
                if ($check3 > 0) {
                    $this->session->setFlashdata("op_error", "Group name is already in use");
                } else {
                    $check4 = $this->db->query("SELECT COUNT(group_id) AS total FROM finished_goods WHERE group_id=$id")->getRow()->total;
                    if ($check4 > 0) {
                        $this->session->setFlashdata("op_error", "Group name is already in use");
                    } else {
                        $query = "DELETE FROM erp_groups WHERE group_id=$id";
                        $this->db->query($query);

                        $config['title'] = "Group Delete";
                        $config['ref_link'] = "";
                        if ($this->db->affectedRows() > 0) {
                            $config['log_text'] = "[ Group successfully deleted ]";
                            $this->session->setFlashdata("op_success", "Group successfully deleted");
                        } else {
                            $config['log_text'] = "[ Group failed to delete ]";
                            $this->session->setFlashdata("op_error", "Group failed to delete");
                        }
                        log_activity($config);
                    }
                }
            }
        }
    }
}
