<?php


namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class InventoryServicesModel extends Model
{
    protected $table      = 'inventory_services';
    protected $primaryKey = 'invent_service_id';

    protected $allowedFields = ['name', 'short_desc', 'long_desc', 'group_id', 'code', 'price', 'tax1', 'tax2', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';


    public $session;
    public $db;
    public $attachmentModel;
    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function get_dtconfig_services()
    {
        $config = array(
            "columnNames" => ["sno", "name", "code", "group", "price", "tax 1", "tax 2", "action"],
            "sortable" => [0, 1, 1, 0, 1, 0, 0],
            "filters" => ["group"]
        );
        return json_encode($config);
    }

    public function get_services_datatable()
    {
        $columns = array(
            "name" => "name",
            "code" => "code",
            "group" => "erp_groups.group_id",
            "price" => "price"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT invent_service_id,name,code,erp_groups.group_name,price,CONCAT(t1.tax_name,' ',t1.percent,'%') AS tax_1,CONCAT(t2.tax_name,' ',t2.percent,'%') AS tax_2 FROM inventory_services JOIN erp_groups ON inventory_services.group_id=erp_groups.group_id JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id ";
        $count = "SELECT COUNT(invent_service_id) AS total FROM inventory_services JOIN erp_groups ON inventory_services.group_id=erp_groups.group_id JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id ";
        $where = false;

        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            } else {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $count .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
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
                                <li><a href="' . url_to('erp.inventory.serviceview', $r['invent_service_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.serviceedit', $r['invent_service_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.servicedelete', $r['invent_service_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['invent_service_id'],
                    $sno,
                    $r['name'],
                    $r['code'],
                    $r['group_name'],
                    $r['price'],
                    $r['tax_1'],
                    $r['tax_2'],
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

    public function insert_service($post, $customfield_chkbx_counter)
    {
        $inserted = false;
        $name = $post["name"];
        $code = $post["code"];
        $group = $post["group"];
        $price = $post["price"];
        $tax1 = $post["tax1"];
        $tax2 = $post["tax2"];
        $short_desc = $post["short_desc"];
        $long_desc = $post["long_desc"];
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO inventory_services(name,code,group_id,price,tax1,tax2,short_desc,long_desc,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $code, $group, $price, $tax1, $tax2, $short_desc, $long_desc, time(), $created_by));
        $id = $this->db->insertId();

        if (empty($id)) {
            $this->session->setFlashdata("op_error", "Service failed to create");
            return $inserted;
        }

        if (!$this->update_service_cfv($id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Service failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        $config['title'] = " Service Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Service failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Service failed to create");
        } else {
            $this->db->transCommit();
            $inserted = true;
            $config['log_text'] = "[ Service successfully created ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Service successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_service_cfv($id, $customfield_chkbx_counter)
    {
        $updated = true;
        $checkbox = array();
        $query = "SELECT erp_custom_field_update(?,?,?) AS retcode ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } else if (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $retcode = $this->db->query($query, array($cf_id, $id, $value))->getRow()->retcode;
                if (empty($retcode)) {
                    $updated = false;
                    break;
                }
            }
        }
        $checkbox_counter = intval($customfield_chkbx_counter);
        for ($i = 0; $i <= $checkbox_counter; $i++) {
            $values = array();
            $cf_id = 0;
            foreach ($checkbox as $key => $value) {
                if (stripos($key, "cf_checkbox_" . $i . "_") === 0) {
                    array_push($values, $value);
                    $cf_id = explode("_", $key)[3];
                    //$cf_id=substr($key,strlen("cf_checkbox_".$i."_"),stripos($key,"_",strlen("cf_checkbox_".$i."_")+1));
                }
            }
            if (!empty($cf_id)) {
                $retcode = $this->db->query($query, array($cf_id, $id, implode(",", $values)))->getRow()->retcode;
                if (empty($retcode)) {
                    $updated = false;
                    break;
                }
            }
        }
        return $updated;
    }

    public function get_service_by_id($invent_service_id)
    {
        $query = "SELECT * FROM inventory_services WHERE invent_service_id=$invent_service_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function update_service($invent_service_id, $post, $customfield_chkbx_counter)
    {
        $updated = false;
        $name = $post["name"];
        $code = $post["code"];
        $group = $post["group"];
        $price = $post["price"];
        $tax1 = $post["tax1"];
        $tax2 = $post["tax2"];
        $short_desc = $post["short_desc"];
        $long_desc = $post["long_desc"];

        $this->db->transBegin();
        $query = "UPDATE inventory_services SET name=? , code=? , group_id=? , price=? , tax1=? , tax2=? , short_desc=? , long_desc=? WHERE invent_service_id=$invent_service_id ";
        $this->db->query($query, array($name, $code, $group, $price, $tax1, $tax2, $short_desc, $long_desc));

        if (!$this->update_service_cfv($invent_service_id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Service failed to create");
            $this->db->transRollback();
            return $updated;
        }

        $config['title'] = " Service Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Service failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Service failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            $config['log_text'] = "[ Service successfully updated ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Service successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_service_for_view($invent_service_id)
    {
        $query = "SELECT invent_service_id,name,code,erp_groups.group_name,price,
        CONCAT(t1.tax_name,' ',t1.percent,'%') AS tax_1,CONCAT(t2.tax_name,' ',t2.percent,'%') AS tax_2,
        short_desc,long_desc FROM inventory_services JOIN erp_groups ON inventory_services.group_id=erp_groups.group_id
        JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id WHERE invent_service_id=$invent_service_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_service_export($type)
    {
        $columns = array(
            "name" => "name",
            "code" => "code",
            "group" => "erp_groups.group_id",
            "price" => "price"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "";
        if ($type == "pdf") {
            $query = "SELECT name,code,erp_groups.group_name,price,CONCAT(t1.tax_name,' ',t1.percent,'%') AS tax_1,CONCAT(t2.tax_name,' ',t2.percent,'%') AS tax_2,short_desc FROM inventory_services JOIN erp_groups ON inventory_services.group_id=erp_groups.group_id JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id ";
        } else {
            $query = "SELECT name,code,erp_groups.group_name,price,CONCAT(t1.tax_name,' ',t1.percent,'%') AS tax_1,CONCAT(t2.tax_name,' ',t2.percent,'%') AS tax_2,short_desc,long_desc FROM inventory_services JOIN erp_groups ON inventory_services.group_id=erp_groups.group_id JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id ";
        }

        $where = false;

        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            } else {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function serviceDelete($invent_service_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('inventory_services')
                ->where('invent_service_id', $invent_service_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $invent_service_id)
                ->where('related_to','inventory_service')
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("inventory_service", $attach_id);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            return $this->session->setFlashdata("op_success", "Service and related data deleted successfully");
        } catch (Exception $e) {
            // echo $e;
            log_message('error', 'Error deleting service: ' . $e->getMessage());
            return $this->session->setFlashdata("op_error", "Error deleting Service and related data");
        }
    }
}
