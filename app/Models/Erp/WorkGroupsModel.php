<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class WorkGroupsModel extends Model
{
    protected $table      = 'work_groups';
    protected $primaryKey = 'wgroup_id';

    protected $allowedFields = [
        'name',
        'approx_days',
        'description'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $session;
    protected $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function get_dtconfig_workgroups()
    {
        $config = array(
            "columnNames" => ["sno", "name", "approx days", "description", "action"],
            "sortable" => [0, 1, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_workgroup_datatable()
    {
        $columns = array(
            "name" => "name",
            "approx days" => "approx_days"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT * FROM work_groups ";
        $count = "SELECT COUNT(wgroup_id) AS total FROM work_groups ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $count .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
                $count .= " AND name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.project.workgroupedit', $r['wgroup_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.project.workgroupdelete', $r['wgroup_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['wgroup_id'],
                    $sno,
                    $r['name'],
                    $r['approx_days'],
                    $r['description'],
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

    public function get_workgroup_export($type)
    {
        $columns = array(
            "name" => "name",
            "approx days" => "approx_days"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT name,approx_days,description FROM work_groups ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_workgroup($post, $equipments, $qtys, $related_ids)
    {
        $inserted = false;
        $name = $post["name"];
        $approx_days = $post["approx_days"];
        $description = $post["description"];

        $this->db->transBegin();
        $query = "INSERT INTO work_groups(name,approx_days,description) VALUES(?,?,?) ";
        $this->db->query($query, array($name, $approx_days, $description));
        $wgroup_id = $this->db->insertId();
        if (empty($wgroup_id)) {
            $this->session->setFlashdata("op_error", "Workgroup failed to create");
            return $inserted;
        }

        if (!$this->update_workgroup_equipments($wgroup_id, $equipments)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Workgroup failed to create");
            return $inserted;
        }

        if (!$this->update_workgroup_items($wgroup_id, $qtys, $related_ids)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Workgroup failed to create");
            return $inserted;
        }
        $this->db->transComplete();
        $config['title'] = "Workgroup Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Workgroup failed to create ]";
            $config['ref_link'] = 'erp/project/workgroups/';
            $this->session->setFlashdata("op_error", "Workgroup failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Workgroup successfully created ]";
            $config['ref_link'] = 'erp/project/workgroups/';
            $this->session->setFlashdata("op_success", "Workgroup successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_workgroup_equipments($wgroup_id, $equipments)
    {
        $updated = true;
        $old_equip_ids = array();
        $query = "SELECT equip_id FROM work_group_equip WHERE wgroup_id=$wgroup_id ";
        $result = $this->db->query($query)->getResultArray();
        foreach ($result as $row) {
            array_push($old_equip_ids, $row['equip_id']);
        }
        $new_equip_ids = explode(",", $equipments);
        $ins = array();
        $dels = array();
        foreach ($new_equip_ids as $new) {
            if (!in_array($new, $old_equip_ids)) {
                array_push($ins, $new);
            }
        }
        foreach ($old_equip_ids as $old) {
            if (!in_array($old, $new_equip_ids)) {
                array_push($dels, $old);
            }
        }
        if (!empty($dels)) {
            $query = "DELETE FROM work_group_equip WHERE wgroup_id=$wgroup_id AND equip_id IN (" . implode(",", $dels) . ")";
            $this->db->query($query);
        }
        $query = "INSERT INTO work_group_equip(wgroup_id,equip_id) VALUES(?,?) ";
        foreach ($ins as $equip_id) {
            $this->db->query($query, array($wgroup_id, $equip_id));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    private function update_workgroup_items($wgroup_id, $qtys, $related_ids)
    {
        $updated = true;

        if (empty($related_ids)) {
            return false;
        }
        $old_related_ids = array();
        $query = "SELECT related_id FROM work_group_items WHERE wgroup_id=$wgroup_id";
        $result = $this->db->query($query)->getResultArray();
        foreach ($result as $row) {
            array_push($old_related_ids, $row['related_id']);
        }
        $dels = array();
        $ins = array();
        foreach ($old_related_ids as $old) {
            if (!in_array($old, $related_ids)) {
                array_push($dels, $old);
            }
        }
        if (!empty($dels)) {
            $query = "DELETE FROM work_group_items WHERE wgroup_id=$wgroup_id AND related_id IN (" . implode($dels) . ") ";
            $this->db->query($query);
        }
        foreach ($related_ids as $related_id) {
            if (in_array($related_id, $old_related_ids)) {
                $this->db->query("UPDATE work_group_items SET qty=? WHERE wgroup_id=$wgroup_id AND related_id=? ", array($qtys[$related_id], $related_id));
            } else {
                $this->db->query(
                    "INSERT INTO work_group_items(wgroup_id,related_to,related_id,qty) VALUES(?,?,?,?) ",
                    array($wgroup_id, 'raw_material', $related_id, $qtys[$related_id])
                );
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
        }
        return $updated;
    }

    public function update_workgroup($wgroup_id, $post, $equipments, $qtys, $related_ids)
    {
        $updated = false;
        $name = $post["name"];
        $approx_days = $post["approx_days"];
        $description = $post["description"];

        $this->db->transBegin();
        $query = "UPDATE work_groups SET name=? , approx_days=? , description=? WHERE wgroup_id=$wgroup_id";
        $this->db->query($query, array($name, $approx_days, $description));

        if (!$this->update_workgroup_equipments($wgroup_id, $equipments)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Workgroup failed to update");
            return $updated;
        }

        if (!$this->update_workgroup_items($wgroup_id, $qtys, $related_ids)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Workgroup failed to update");
            return $updated;
        }
        $this->db->transComplete();
        $config['title'] = "Workgroup Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Workgroup failed to update ]";
            $config['ref_link'] = url_to('erp.project.workgroups');
            $this->session->setFlashdata("op_error", "Workgroup failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Workgroup successfully updated ]";
            $config['ref_link'] = url_to('erp.project.workgroups');
            $this->session->setFlashdata("op_success", "Workgroup successfully updated");
        }
        log_activity($config);
        return $updated;
    }
    public function get_workgroup_by_id($wgroup_id)
    {
        $query = "SELECT work_groups.wgroup_id,name,approx_days,description,GROUP_CONCAT(equip_id) AS equipments FROM work_groups JOIN work_group_equip ON work_groups.wgroup_id=work_group_equip.wgroup_id WHERE work_groups.wgroup_id=$wgroup_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_workgroup_items($wgroup_id)
    {
        $query = "SELECT related_id,qty,CONCAT(raw_materials.name,' ',raw_materials.code) AS product FROM work_group_items JOIN raw_materials ON related_id=raw_materials.raw_material_id WHERE wgroup_id=$wgroup_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_all_equipments()
    {
        $query = "SELECT equip_id,CONCAT(name,' ',code) AS equip_name FROM equipments WHERE status=0";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_all_workgroups()
    {
        $query = "SELECT wgroup_id,name FROM work_groups ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function delete_workgroups($wgroup_id)
    {
        $query = "DELETE FROM work_groups WHERE wgroup_id=$wgroup_id";
        $this->db->query($query);
        $config['title'] = "Workgroup Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Workgroup successfully deleted ]";
            $config['ref_link'] = url_to('erp.project.workgroups');
            $this->session->setFlashdata("op_success", "Workgroup successfully deleted");
        } else {
            $config['log_text'] = "[ Workgroup failed to delete ]";
            $config['ref_link'] =  url_to('erp.project.workgroups');
            $this->session->setFlashdata("op_error", "Workgroup failed to delete");
        }
        log_activity($config);
    }
}
