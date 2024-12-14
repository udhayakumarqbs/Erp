<?php


namespace App\Models\Erp\Procurement;

use App\Libraries\MailerFactory;
use CodeIgniter\Model;
use EmailTemplate;
use Exception;
use libraries\emailtemplates\AbstractTemplate;

class ProcurementModel extends Model
{
    protected $table      = 'requisition';
    protected $primaryKey = 'req_id';
    protected $allowedFields = ['req_code', 'assigned_to', 'status', 'priority', 'mail_sent', 'description', 'remarks', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';


    public $session;
    public $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function getNotifyForUser($user_id)
    {
        $thequery = "select title from notification where status = 0 and user_id =".$user_id;
        return $this->db->query($thequery)->getResultArray();
    }

    private $req_priority = array(
        0 => "Low",
        1 => "Medium",
        2 => "High",
        3 => "Urgent"
    );

    private $req_status = array(
        0 => "Pending",
        1 => "Processing",
        2 => "Approved",
        3 => "Rejected"
    );

    private $invoice_status = array(
        0 => "Not Paid",
        1 => "Overdue",
        2 => "Partially Paid",
        3 => "Paid",
    );

    public function get_dtconfig_invoice()
    {
        $config = array(
            "columnNames" => ["sno", "order code", "supplier", "delivered", "status", "amount", "action"],
            "sortable" => [0, 1, 1, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_invoice_status()
    {
        return $this->invoice_status;
    }

    public function get_req_priority()
    {
        return $this->req_priority;
    }

    public function get_req_status()
    {
        return $this->req_status;
    }

    public function get_req_status_bg()
    {
        return $this->req_status_bg;
    }

    private $req_priority_bg = array(
        0 => "st_dark",
        1 => "st_violet",
        2 => "st_primary",
        3 => "st_success"
    );

    private $req_status_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_success",
        3 => "st_danger"
    );

    private $rfq_status = array(
        0 => "Created",
        1 => "Sending",
        2 => "Sent",
        3 => "Partial Response",
        4 => "Full Response"
    );

    private $product_types = array(
        "raw_material" => "Raw Material",
        "semi_finished" => "Semi Finished"
    );

    private $invoice_status_bg = array(
        0 => "st_danger",
        1 => "st_warning",
        2 => "st_violet",
        3 => "st_success"
    );

    private $order_status_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_success",
        3 => "st_danger"
    );

    public function get_rfq_status_bg()
    {
        return $this->rfq_status_bg;
    }

    public function get_product_types()
    {
        return $this->product_types;
    }

    public function get_rfq_status()
    {
        return $this->rfq_status;
    }

    public function get_product_links()
    {
        return $this->product_links;
    }

    private $product_links = array(
        "raw_material" => 'erp/procurement/ajaxfetchrawmaterials',
        "semi_finished" => 'erp/procurement/ajaxfetchsemifinished'
    );

    public function get_req_priority_bg()
    {
        return $this->req_priority_bg;
    }

    private $rfq_status_bg = array(
        0 => "st_dark",
        1 => "st_warning",
        2 => "st_primary",
        3 => "st_violet",
        4 => "st_success"
    );

    private $rfq_mail_status_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_success"
    );

    private $rfq_mail_status = array(
        0 => "Pending",
        1 => "Sending",
        2 => "Sent"
    );

    private $order_status = array(
        0 => "Created",
        1 => "Sent",
        2 => "Approved",
        3 => "Cancelled"
    );

    public function get_order_status_bg()
    {
        return $this->order_status_bg;
    }

    public function get_order_status()
    {
        return $this->order_status;
    }

    public function get_dtconfig_rfq()
    {
        $config = array(
            "columnNames" => ["sno", "rfq code", "req code", "expiry date", "status", "created on", "action"],
            "sortable" => [0, 1, 1, 1, 1, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_dtconfig_requisition()
    {
        $config = array(
            "columnNames" => ["sno", "req code", "assigned to", "status", "priority", "mail sent", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0],
            "filters" => ["priority", "status"]
        );
        return json_encode($config);
    }

    public function get_requisition_datatable()
    {
        $columns = array(
            "req code" => "req_code",
            "assigned to" => "erp_users.name",
            "status" => "requisition.status",
            "priority" => "priority"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT req_id,req_code,erp_users.name,requisition.status,requisition.priority,CASE WHEN mail_sent=1 THEN 'Yes' ELSE 'No' END AS mail_sent FROM requisition JOIN erp_users ON requisition.assigned_to=erp_users.user_id ";
        $count = "SELECT COUNT(req_id) AS total FROM requisition JOIN erp_users ON requisition.assigned_to=erp_users.user_id ";

        $where = false;
        $filter_1_col = isset($_GET['priority']) ? $columns['priority'] : "";
        $filter_1_val = $_GET['priority'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( req_code LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( req_code LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( req_code LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( req_code LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        // return $result;
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a target="_BLANK" href="' . url_to('erp.procurement.requisitionview', $r['req_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>';
            // if ($r['status'] < 2) {
                $action .= '<li><a href="' . url_to('erp.procurement.requisitionedit', $r['req_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>';
                $action .= '<li><a href="' . url_to('erp.procurement.requisitiondelete', $r['req_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>';
            // }
            '</ul>
                        </div>
                    </div>';
            $priority = "<span class='st " . $this->req_priority_bg[$r['priority']] . "' >" . $this->req_priority[$r['priority']] . "</span>";
            $status = "<span class='st " . $this->req_status_bg[$r['status']] . "' >" . $this->req_status[$r['status']] . "</span>";
            array_push(
                $m_result,
                array(
                    $r['req_id'],
                    $sno,
                    $r['req_code'],
                    $r['name'],
                    $status,
                    $priority,
                    $r['mail_sent'],
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

    public function get_requisition_export($type)
    {
        $columns = array(
            "req code" => "req_code",
            "assigned to" => "erp_users.name",
            "status" => "requisition.status",
            "priority" => "priority"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT req_code,erp_users.name,
        CASE ";
        foreach ($this->req_status as $key => $value) {
            $query .= "WHEN requisition.status=" . $key . " THEN '" . $value . "' ";
        }
        $query .= "END AS status ,
        CASE ";
        foreach ($this->req_priority as $key => $value) {
            $query .= "WHEN requisition.priority=" . $key . " THEN '" . $value . "' ";
        }
        $query .= "END AS priority,
        CASE WHEN mail_sent=1 THEN 'Yes' ELSE 'No' END AS mail_sent,
        requisition.description,requisition.remarks FROM requisition JOIN erp_users ON requisition.assigned_to=erp_users.user_id ";

        $where = false;
        $filter_1_col = isset($_GET['priority']) ? $columns['priority'] : "";
        $filter_1_val = $_GET['priority'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }
        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }
        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( req_code LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( req_code LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_requisition()
    {
        $inserted = false;
        $req_code = $_POST["req_code"];
        $assigned_to = $_POST["assigned_to"];
        $priority = $_POST["priority"];
        $mail_sent = $_POST["mail_sent"] ?? 0;
        $description = $_POST["description"];
        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO requisition(req_code,assigned_to,priority,mail_sent,description,remarks,created_at,created_by) VALUES('$req_code',$assigned_to,$priority,$mail_sent,'$description','','" . time() . "',$created_by)";
        $query_result =  $this->db->query($query);
        $req_id = $this->db->insertID();

        //for debugging
        // echo "first query status";
        // echo var_dump($query_result);

        if (empty($req_id)) {
            $this->session->setFlashdata("op_error", "Requisition failed to create");

            //for debugging
            // echo "issues while inserting rolling back !";

            $this->db->transRollback();
            return $inserted;
        }

        // $set = array();
        // // started to add product by loop
        // foreach ($_POST as $key => $value) {
        //     $test_ = stripos($key,"product_type_");
        //     if($test_ != false){
        //         $test_++;
        //         $positionValue = $key[$test_];
        //         $related_id = $_POST["product_id_".$idx];
        //         $related_to = $value;
        //         $qty = $_POST["product_qty_".$idx];
        //         array_push($set, array(
        //             "related_to" => $related_to,
        //             "related_id" => $related_id,
        //             "qty" => $qty
        //         ));
        //     }
        // }

        $set = array();

        foreach ($_POST as $key => $value) {
            if (stripos($key, "product_type_") === 0) {
                $idx = substr($key, strlen("product_type_"));
                $related_id = $_POST["product_id_" . $idx];
                $related_to = $value;
                $qty = $_POST["product_qty_" . $idx];

                $set[] = array(
                    "related_to" => $related_to,
                    "related_id" => $related_id,
                    "qty" => $qty
                );
            }
        }

        //for debugging
        // echo "pushed in array next inserting ! <br>";
        // echo var_dump($set) . "<br>";

        // $product_count = 1;
        // $issue_inserting_products = false;
        foreach ($set as $array) {
            $logger = \Config\Services::logger();
            $queryFind = "SELECT req_id FROM inventory_requisition WHERE req_id=$req_id AND related_to='" . $array['related_to'] . "' AND related_id=" . $array['related_id'];
            $logger->error($array);
            $logger->error($queryFind);

            // $ret_code = $this->db->query($queryFind);

            // $logger->error($ret_code);

            //for debugging
            // echo "searching for product <br>";
            $this->db->query($queryFind);
            if ($this->db->affectedRows() > 0) {

                //for debugging
                // echo "updated " . $product_count++ . "<br>";

                $queryUpdate = "UPDATE inventory_requisition SET qty = " . $array['qty'] . " WHERE related_id =" . $array['related_id'];
                $insert_result = $this->db->query($queryUpdate);

                //for debugging
                // echo var_dump($insert_result)."<br>";
                $logger->error($insert_result);
                $logger->error("with");
                if ($insert_result == false) {
                    $issue_inserting_products = true;
                }
            } else {

                //for debugging
                // echo "inserted " . $product_count++ . "<br>";

                $queryInsert = "INSERT INTO inventory_requisition(req_id, related_to, related_id, qty) VALUES (" . $req_id . ",'" . $array['related_to'] . "'," . $array['related_id'] . "," . $array['qty'] . ")";
                $insert_result = $this->db->query($queryInsert);

                //for debugging
                // echo var_dump($insert_result)."<br>";
                $logger->error($insert_result);
                $logger->error("without");
                if ($insert_result == false) {
                    $issue_inserting_products = true;
                }
            }
        }

        $this->db->transComplete();
        $logger->error($this->db->transStatus());

        $config['title'] = "Requisition Insert";
        if ($this->db->transStatus() != true) {
            //for debugging
            // echo "transStatus is failed rolling back <br>";
            $this->db->transRollback();
            $config['log_text'] = "[ Requisition failed to create ]";
            $config['ref_link'] = 'erp/crm/requisitionview/' . $req_id;
            $this->session->setFlashdata("op_error", "Requisition failed to create");
        } else {
            //for debugging
            // echo "transStatus is Success Commiting <br>";
            $this->db->transCommit();
            $inserted = true;
            if ($mail_sent == 1) {
                $this->send_requisition_mail($req_id);
            }
            $config['log_text'] = "[ Requisition successfully created ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            $this->session->setFlashdata("op_success", "Requisition successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function get_requisition_for_view($req_id)
    {
        $query = "SELECT req_id,req_code,requisition.assigned_to,eu1.name AS assigned,CASE WHEN requisition.mail_sent=1 THEN 'Yes' ELSE 'No' END AS mail_sent,requisition.description,requisition.remarks,requisition.priority,requisition.status,eu2.name AS created,requisition.created_at FROM requisition JOIN erp_users eu1 ON requisition.assigned_to=eu1.user_id JOIN erp_users eu2 ON requisition.created_by=eu2.user_id WHERE req_id=$req_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_requisition_items($req_id)
    {
        $query = "SELECT invent_req_id,related_to,related_id,qty,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product FROM inventory_requisition LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' WHERE req_id=$req_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getrfq_id_By_Req_id($req_id)
    {
        $query1 = "SELECT req_id FROM `rfq` WHERE rfq_id = $req_id";
        $result1 = $this->db->query($query1)->getResultArray();
        return $result1[0]['req_id'];
    }

    public function get_attachments($type, $related_id)
    {
        $query = "SELECT filename,attach_id FROM attachments WHERE related_to='$type' AND related_id=$related_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_rfq_datatable()
    {
        $columns = array(
            "rfq code" => "rfq_code",
            "req code" => "req_code",
            "expiry date" => "expiry_date",
            "status" => "rfq.status",
            "created on" => "rfq.created_at"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT rfq_id,rfq_code,requisition.req_code,expiry_date,rfq.status,rfq.created_at FROM rfq JOIN requisition ON rfq.req_id=requisition.req_id ";
        $count = "SELECT COUNT(rfq_id) AS total FROM rfq JOIN requisition ON rfq.req_id=requisition.req_id ";

        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE rfq_code LIKE '%" . $search . "%' ";
                $count .= " WHERE rfq_code LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND rfq_code LIKE '%" . $search . "%' ";
                $count .= " AND rfq_code LIKE '%" . $search . "%' ";
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
                                <li><a target="_BLANK" href="' . url_to('erp.procurement.rfqview', $r['rfq_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>';
            $action .= '<li><a href="' . base_url() . 'erp/procurement/rfqedit/' . $r['rfq_id'] . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>';
            $action .= '<li><a href="' . base_url() . 'erp/procurement/rfqdelete/' . $r['rfq_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';

            $status = "<span class='st " . $this->rfq_status_bg[$r['status']] . "' >" . $this->rfq_status[$r['status']] . "</span>";
            array_push(
                $m_result,
                array(
                    $r['rfq_id'],
                    $sno,
                    $r['rfq_code'],
                    $r['req_code'],
                    $r['expiry_date'],
                    $status,
                    date("Y-m-d", $r['created_at']),
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

    public function rfqdelete($rfq_id)
    {
        // return false;

        $config['title'] = "RFQ Delete";

        // backingup data before deliting
        $query1 = "select *  FROM rfq WHERE rfq_id=" . $rfq_id;
        $query2 = "select *  FROM supplier_rfq WHERE rfq_id=" . $rfq_id;
        $inventory_requisition = $this->db->query($query1)->getResultArray();
        $requisition = $this->db->query($query2)->getResultArray();
        $deleted_data =  array_merge($inventory_requisition, $requisition);

        $query1 = "DELETE FROM rfq WHERE rfq_id=" . $rfq_id;
        $query2 = "DELETE FROM supplier_rfq WHERE rfq_id=" . $rfq_id;
        $query3 = "DELETE FROM attachments WHERE related_to = 'rfq' AND related_id = $rfq_id";

        $this->db->transBegin();
        if (!$this->db->query($query1)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting RFQ");
            return false;
        }
        if (!$this->db->query($query2)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting RFQ");
            return false;
        }
        if (!$this->db->query($query3)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting RFQ");
            return false;
        }

        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Error while deleting RFQ ]";
            $config['ref_link'] = 'erp/procurement/rfqview/' . $rfq_id;
            $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_error", "Error while Deleting RFQ");
            log_activity($config);
            return false;
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ RFQ Deleted successfully ]";
            $config['ref_link'] = 'erp/procurement/rfqview/' . $rfq_id;
            $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_success", "RFQ successfully Deleted");
            log_activity($config);
            return true;
        }
    }

    public function rfqsupplierdelete($rfq_id, $id)
    {
        // return false;

        $config['title'] = "RFQ Supplier Delete";

        $query1 = "DELETE FROM supplier_rfq WHERE supp_rfq_id=" . $rfq_id;

        $this->db->transBegin();
        if (!$this->db->query($query1)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting Supplier");
            return false;
        }

        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Error while deleting RFQ ]";
            $config['ref_link'] = 'erp/procurement/rfqview/' . $id;
            // $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_error", "Error while Deleting Supplier");
            log_activity($config);
            return false;
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Supplier Deleted successfully ]";
            $config['ref_link'] = 'erp/procurement/rfqview/' . $id;
            // $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_success", "Supplier successfully Deleted");
            log_activity($config);
            return true;
        }
    }

    public function orderdelete($order_id)
    {
        $config['title'] = "Order Delete";

        $query1 = "DELETE FROM purchase_order WHERE order_id=" . $order_id;
        $query2 = "DELETE FROM purchase_order_items WHERE order_id=" . $order_id;

        $this->db->transBegin();
        if (!$this->db->query($query1)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting Order");
            return false;
        }
        if (!$this->db->query($query2)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting Order");
            return false;
        }

        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Error while deleting Order ]";
            $config['ref_link'] = 'erp/procurement/orders/';
            // $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_error", "Error while Deleting Order");
            log_activity($config);
            return false;
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Order Deleted successfully ]";
            $config['ref_link'] = 'erp/procurement/orders/';
            // $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_success", "Order successfully Deleted");
            log_activity($config);
            return true;
        }
    }

    public function update_rfq($rfq_id)
    {
        $updated = false;
        $rfq_code = $_POST["rfq_code"];
        $expiry_date = $_POST["expiry_date"];
        $terms_condition = $_POST["terms_condition"];

        $this->db->transBegin();
        $query = "UPDATE rfq SET rfq_code=? , expiry_date=? , terms_condition=? WHERE rfq_id=$rfq_id ";
        $this->db->query($query, array($rfq_code, $expiry_date, $terms_condition));

        if (!$this->update_supplier_cfv($rfq_id)) {
            $this->session->setFlashdata("op_error", "RFQ failed to update");
            $this->db->transRollback();
            return $updated;
        }

        $this->db->transComplete();
        $config['title'] = "RFQ Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ RFQ failed to update ]";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $this->session()->setFlashdata("op_error", "RFQ failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ RFQ successfully updated ]";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $this->session->setFlashdata("op_success", "RFQ successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    private function update_inventory_requisition($req_id)
    {
        $updated = true;
        $set = array();
        foreach ($_POST as $key => $value) {
            if (stripos($key, "product_type_") === 0) {
                $idx = substr($key, strlen("product_type_"));
                $related_id = $_POST["product_id_" . $idx];
                $related_to = $value;
                $qty = $_POST["product_qty_" . $idx];
                array_push($set, array(
                    "related_to" => $related_to,
                    "related_id" => $related_id,
                    "qty" => $qty
                ));
            }
        }
        $where = "";
        $op_failed = false;
        foreach ($set as $array) {
            $query = "SELECT inventory_requisition_insert_update($req_id,'" . $array['related_to'] . "'," . $array['related_id'] . "," . $array['qty'] . ") AS ret_code";
            $where .= "NOT ( related_to='" . $array['related_to'] . "' AND related_id=" . $array['related_id'] . " ) AND";
            $ret_code = $this->db->query($query);
            if (empty($ret_code)) {
                $op_failed = true;
                $updated = false;
                break;
            }
        }
        if (!$op_failed) {
            $where = substr($where, 0, strlen($where) - 3);
            $query = "DELETE FROM inventory_requisition WHERE req_id = " . $req_id . " AND ( " . $where . " ) ";
            $this->db->query($query);
        }
        return $updated;
    }

    private function send_requisition_mail($req_id)
    {
        $query = "SELECT req_code,requisition.assigned_to,eu1.name AS assigned,eu1.email AS receiver,requisition.description,requisition.priority,requisition.status,eu2.name AS created,eu2.email AS sender,requisition.created_at FROM requisition JOIN erp_users eu1 ON requisition.assigned_to=eu1.user_id JOIN erp_users eu2 ON requisition.created_by=eu2.user_id WHERE req_id=$req_id";
        $data = $this->db->query($query)->getRow();
        if (!empty($data)) {
            $config['data'] = $data;
            $config['status'] = $this->req_status;
            $config['priority'] = $this->req_priority;
            $config['link'] = base_url() . 'erp/procurement/requisitionview/' . $req_id;
            $template = EmailTemplate::getTemplate("requisitionlink", $config);
            $mailer = MailerFactory::getMailer();
            $mailer->addFrom($data->sender, $data->created);
            $mailer->addTo($data->reciever, $data->assigned);
            $mailer->addSubject("Requisition Check");
            $mailer->addBody($template);
            //$mailer->send();
        }
    }

    public function get_rfq_export()
    {
        $columns = array(
            "rfq code" => "rfq_code",
            "req code" => "req_code",
            "expiry date" => "expiry_date",
            "status" => "rfq.status",
            "created on" => "rfq.created_at"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT rfq_code,requisition.req_code,expiry_date,
        CASE ";
        foreach ($this->rfq_status as $key => $value) {
            $query .= " WHEN rfq.status=$key THEN '" . $value . "' ";
        }
        $query .= "END AS status,rfq.terms_condition,FROM_UNIXTIME(rfq.created_at,'%Y-%m-%d') AS created_at FROM rfq JOIN requisition ON rfq.req_id=requisition.req_id ";
        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE rfq_code LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND rfq_code LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function handle_requisition($req_id)
    {
        $user_id = get_user_id();
        echo $user_id . "<br>";
        echo $req_id;
        $query = "UPDATE requisition SET status = 1 WHERE assigned_to = $user_id AND req_id = $req_id";
        $this->db->query($query);


        $config['title'] = "Requisition Status Update";
        echo "<br>" . $this->db->affectedRows() . "<br>";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Requisition Status successfully updated ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            $this->session->setFlashdata("op_success", "Requisition Status successfully updated");
        } else {
            $config['log_text'] = "[ Requisition Status failed to update ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            $this->session->setFlashdata("op_error", "Requisition Status failed to update");
        }
        log_activity($config);
    }

    public function requisitiondelete($req_id)
    {
        $config['title'] = "Requisition Delete";
        // backingup data before deliting
        $query3 = "select *  FROM inventory_requisition WHERE req_id=" . $req_id;
        $query4 = "select *  FROM requisition WHERE req_id=" . $req_id;
        $inventory_requisition = $this->db->query($query3)->getResultArray();
        $requisition = $this->db->query($query4)->getResultArray();
        $deleted_data =  array_merge($inventory_requisition, $requisition);



        $query1 = "DELETE FROM inventory_requisition WHERE req_id=" . $req_id;
        $query2 = "DELETE FROM requisition WHERE req_id=" . $req_id;


        $this->db->transBegin();
        if (!$this->db->query($query1)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting Requisition");
            return false;
        }
        if (!$this->db->query($query2)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Error while Deleting Requisition");
            return false;
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Error while deleting Requisition ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_error", "Error while Deleting Requisition");
            log_activity($config);
            return false;
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Requisition Deleted successfully ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            $config['additional_info'] = json_encode($deleted_data);
            $this->session->setFlashdata("op_success", "Requisition successfully Deleted");
            log_activity($config);
            return true;
        }
    }

    public function get_requisition_by_id($req_id)
    {
        $query = "SELECT req_id,req_code,assigned_to,erp_users.name,mail_sent,requisition.description,priority FROM requisition JOIN erp_users ON requisition.assigned_to=erp_users.user_id WHERE req_id=$req_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function update_requisition($req_id)
    {
        $updated = false;
        // return var_dump($_POST);
        $req_code = $_POST["req_code"];
        $assigned_to = $_POST["assigned_to"];
        $priority = $_POST["priority"];
        $mail_sent = $_POST["mail_sent"] ?? 0;
        $description = $_POST["description"];

        $this->db->transBegin();
        $query = "UPDATE requisition SET status=0 , req_code=? , assigned_to=? , priority=? , mail_sent=? , description=? WHERE req_id=$req_id";
        $this->db->query($query, array($req_code, $assigned_to, $priority, $mail_sent, $description));

        if (!$this->update_inventory_requisition($req_id)) {
            $this->session->setFlashdata("op_error", "Requisition failed to update");
            $this->db->transRollback();
            return $updated;
        }
        $this->db->transComplete();
        $config['title'] = "Requisition Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Requisition failed to update ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            $this->session->setFlashdata("op_error", "Requisition failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            if ($mail_sent == 1) {
                $this->send_requisition_mail($req_id);
            }
            $config['log_text'] = "[ Requisition successfully updated ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            $this->session->setFlashdata("op_success", "Requisition successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function requisition_action($req_id)
    {
        $user_id = get_user_id();
        $status = $_POST["req_status"];
        $remarks = $_POST["remarks"];
        $query = "UPDATE requisition SET status=? , remarks=?  WHERE assigned_to=$user_id AND req_id=$req_id";
        $this->db->query($query, array($status, $remarks));
        $config['title'] = "Requisition Action Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Requisition Action successfully updated ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            session()->setFlashdata("op_success", "Requisition Action successfully updated");
        } else {
            $config['log_text'] = "[ Requisition Action failed to update ]";
            $config['ref_link'] = 'erp/procurement/requisitionview/' . $req_id;
            session()->setFlashdata("op_error", "Requisition Action failed to update");
        }
        log_activity($config);
    }

    public function get_rfq_for_view($rfq_id)
    {
        $query = "SELECT rfq_id,rfq.req_id,rfq_code,req_code,expiry_date,terms_condition,rfq.status,erp_users.name AS creater,rfq.created_at FROM rfq JOIN requisition ON rfq.req_id=requisition.req_id JOIN erp_users ON rfq.created_by=erp_users.user_id WHERE rfq_id=$rfq_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_dtconfig_rfqsupplier()
    {
        $config = array(
            "columnNames" => ["sno", "rfq code", "supplier", "mail status", "send contacts", "responded", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_rfqsupplier_datatable()
    {
        $rfq_id = $_GET["rfqid"];
        $columns = array(
            "rfq code" => "rfq_code",
            "supplier" => "suppliers.name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT supp_rfq_id,rfq_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,mail_status,CASE WHEN send_contacts=1 THEN 'Yes' ELSE 'No' END AS send_contacts,CASE WHEN responded=1 THEN 'Yes' ELSE 'No' END AS responded FROM supplier_rfq JOIN rfq ON supplier_rfq.rfq_id=rfq.rfq_id JOIN suppliers ON supplier_rfq.supplier_id=suppliers.supplier_id ";
        $count = "SELECT COUNT(supp_rfq_id) AS total FROM supplier_rfq JOIN rfq ON supplier_rfq.rfq_id=rfq.rfq_id JOIN suppliers ON supplier_rfq.supplier_id=suppliers.supplier_id ";

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE suppliers.name LIKE '%" . $search . "%'  ";
                $count .= " WHERE suppliers.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND suppliers.name LIKE '%" . $search . "%' ";
                $count .= " AND suppliers.name LIKE '%" . $search . "%' ";
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
                            <li><a href="' . base_url() . 'erp/procurement/createorder_rfq/' . $r['supp_rfq_id'] . '/' . $rfq_id . '" title="Create" class="bg-primary"><i class="fa-solid fa-plus"></i> </a></li>
                            <li><a href="' . base_url() . 'erp/procurement/rfqsupplierresend/' . $rfq_id . '/' . $r['supp_rfq_id'] . '" title="Resend" class="bg-success"><i class="fa-solid fa-paper-plane"></i> </a></li>
                            <li><a href="' . base_url() . 'erp/procurement/rfqsupplierdelete/' . $r['supp_rfq_id'] . '/' . $rfq_id . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';

            $mail_status = "<span class='st " . $this->rfq_mail_status_bg[$r['mail_status']] . "' >" . $this->rfq_mail_status[$r['mail_status']] . "</span>";
            array_push(
                $m_result,
                array(
                    $r['supp_rfq_id'],
                    $sno,
                    $r['rfq_code'],
                    $r['supplier'],
                    $mail_status,
                    $r['send_contacts'],
                    $r['responded'],
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

    public function upload_attachment($type)
    {
        $path = get_attachment_path($type);
        $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $_FILES['attachment']['name']);
        $filepath = $path . $filename;
        $response = array();
        if (file_exists($filepath)) {
            $response['error'] = 1;
            $response['reason'] = "File already exists";
        } else {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
                $id = $_GET['id'] ?? 0;
                $query = "INSERT INTO attachments(filename,related_to,related_id) VALUES('$filename','$type',$id)";
                if (empty($id)) {
                    $response['error'] = 1;
                    $response['reason'] = "Internal Error";
                } else {
                    $this->db->query($query, array($filename, $type, $id));
                    $attach_id = $this->db->insertID();
                    if (!empty($attach_id)) {
                        $response['error'] = 0;
                        $response['reason'] = "File uploaded successfully";
                        $response['filename'] = $filename;
                        $response['insert_id'] = $attach_id;
                        $response['filelink'] = get_attachment_link($type) . $filename;
                    } else {
                        unlink($filepath);
                        $response['error'] = 1;
                        $response['reason'] = "Internal Error";
                    }
                }
            } else {
                $response['error'] = 1;
                $response['reason'] = "File upload Error";
            }
        }
        return $response;
    }

    public function delete_attachment($type)
    {
        $attach_id = $_GET["id"] ?? 0;
        $response = array();
        if (!empty($attach_id)) {
            $filename = $this->db->query("SELECT filename FROM attachments WHERE attach_id=$attach_id")->getrow();
            if (!empty($filename)) {
                $filepath = get_attachment_path($type) . '/' . $filename->filename;
                $this->db->query("DELETE FROM attachments WHERE attach_id=$attach_id");
                if ($this->db->affectedRows() > 0) {
                    unlink($filepath);
                    $response['error'] = 0;
                    $response['reason'] = "File successfully deleted";
                } else {
                    $response['error'] = 1;
                    $response['reason'] = "File delete error";
                }
            } else {
                $response['error'] = 1;
                $response['reason'] = "File delete error";
            }
        } else {
            $response['error'] = 1;
            $response['reason'] = "File delete error";
        }
        return $response;
    }

    public function get_rfqsupplier_export()
    {
        $rfq_id = $_GET["rfqid"];
        $columns = array(
            "rfq code" => "rfq_code",
            "supplier" => "suppliers.name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT rfq_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,
        CASE ";
        foreach ($this->rfq_mail_status as $key => $value) {
            $query .= " WHEN mail_status=" . $key . " THEN '" . $value . "' ";
        }
        $query .= "END AS mail_status,CASE WHEN send_contacts=1 THEN 'Yes' ELSE 'No' END AS send_contacts,CASE WHEN include_attach=1 THEN 'Yes' ELSE 'No' END AS include_attach,CASE WHEN responded=1 THEN 'Yes' ELSE 'No' END AS responded FROM supplier_rfq JOIN rfq ON supplier_rfq.rfq_id=rfq.rfq_id JOIN suppliers ON supplier_rfq.supplier_id=suppliers.supplier_id ";

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE suppliers.name LIKE '%" . $search . "%'  ";
                $where = true;
            } else {
                $query .= " AND suppliers.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_all_selection_rules()
    {
        $query = "SELECT rule_id,rule_name FROM selection_rule";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_all_warehouses()
    {
        $query = "SELECT name,warehouse_id FROM warehouses";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    private function update_supplier_cfv($rfq_id)
    {
        $updated = true;
        $checkbox = array();
        //cf_id,related_id,field_value --> param order
        $query = "SELECT erp_custom_field_update(?,?,?) AS ret_code ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } else if (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $ret_code = $this->db->query($query, array($cf_id, $rfq_id, $value))->getRow()->ret_code;
                if (empty($ret_code)) {
                    $updated = false;
                    break;
                }
            }
        }
        $checkbox_counter = intval($_POST["customfield_chkbx_counter"]);
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
                $ret_code = $this->db->query($query, array($cf_id, $rfq_id, implode(",", $values)))->getRow()->ret_code;
                if (empty($ret_code)) {
                    $updated = false;
                    break;
                }
            }
        }
        return $updated;
    }

    public function insert_rfq($req_id)
    {
        $inserted = false;
        $rfq_code = $_POST["rfq_code"];
        $expiry_date = $_POST["expiry_date"];
        $terms_condition = $_POST["terms_condition"];
        $created_by = get_user_id();

        $check = $this->db->query("SELECT COUNT(rfq_id) AS total FROM rfq WHERE req_id=$req_id ")->getRow()->total;
        if ($check > 0) {
            $this->session->setFlashdata("op_error", "RFQ failed to create for same Requisition");
            return $inserted;
        }
        $this->db->transBegin();
        $query = "INSERT INTO rfq(rfq_code,req_id,expiry_date,terms_condition,created_at,created_by) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($rfq_code, $req_id, $expiry_date, $terms_condition, time(), $created_by));
        $rfq_id = $this->db->insertID();

        if (empty($rfq_id)) {
            $this->session->setFlashdata("op_error", "RFQ failed to create");
            return $inserted;
        }



        if (!$this->update_supplier_cfv($rfq_id)) {
            $this->session->setFlashdata("op_error", "RFQ failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "RFQ Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ RFQ failed to create ]";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $this->session->setFlashdata("op_error", "RFQ failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ RFQ successfully created ]";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $this->session->setFlashdata("op_success", "RFQ successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function insert_rfqsupplier($rfq_id)
    {

        $inserted = false;
        $supplier = $_POST["supplier"];
        $selection_rule = $_POST["selection_rule"];
        $send_contacts = $_POST["send_contacts"] ?? 0;
        $include_attach = $_POST["include_attach"] ?? 0;

        $check = $this->db->query("SELECT supp_rfq_id FROM supplier_rfq WHERE rfq_id=$rfq_id AND supplier_id=$supplier LIMIT 1")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate RFQ Supplier");
            return $inserted;
        }

        $query = "INSERT INTO supplier_rfq(rfq_id,supplier_id,selection_rule,send_contacts,include_attach) VALUES($rfq_id,$supplier,'" . $selection_rule . "',$send_contacts,$include_attach)";
        $this->db->query($query);

        $config['title'] = "RFQ Supplier Insert";
        if (!empty($this->db->insertID())) {
            $inserted = true;
            $config['log_text'] = "[ RFQ Supplier successfully updated ]";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $this->session->setFlashdata("op_success", "RFQ Supplier successfully updated");
        } else {
            $config['log_text'] = "[ RFQ Supplier failed to update ]";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $this->session->setFlashdata("op_error", "RFQ Supplier failed to update");
        }
        log_activity($config);
        return $inserted;
    }

    public function get_selection_basis($basis, $value = "", $title = "")
    {
        $data = array();
        switch ($basis) {
            case "supply_list":
                $data = $this->get_selection_supply_list($value, $title);
                break;
            case "supplier_name":
                $data = $this->get_selection_supply_list($value, $title);
                break;
            default:
                $data = $this->get_selection_selection_rule($basis, $value);
                break;
        }
        return $data;
    }

    private function get_selection_supply_list($value = "", $title = "")
    {
        $_title = "select supplier";
        if (!empty($title)) {
            $_title = $title;
        }
        $data['type'] = "ajaxselectbox";
        $data['html'] = '
        <div class="form-group field-required" >
            <label class="form-label">Supplier</label>
            <div class="ajaxselectBox selectionajaxselectbox poR" data-ajax-url="' . base_url() . 'erp/procurement/ajaxfetchsupplierbyproduct" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="select supplier">' . $_title . '</div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="supplier" value="' . $value . '" >
                </div>
                <div class="ajaxselectBox_Container alldiv">
                    <input type="text" class="ajaxselectBox_Search form_control" />
                    <ul role="listbox" >

                    </ul>
                </div>
            </div>
            <p class="error-text" ></p>
        </div>
        ';
        return $data;
    }

    private function get_selection_selection_rule($basis, $value = "")
    {
        $data['type'] = "selectbox";
        $data['html'] = '
        <div class="form-group field-required">
        <label class="form-label">Supplier</label>
        <div class="selectBox selectionselectbox poR" >
            <div class="selectBoxBtn flex"> 
                <div class="textFlow" data-default="select supplier">select supplier</div>
                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                <input type="hidden" class="selectBox_Value field-check" name="supplier" value="' . $value . '" >
            </div>
            <ul role="listbox" class="selectBox_Container alldiv">
        ';
        $query = "SELECT segment_id,segment_value_idx,above_below FROM selection_rule_segment WHERE exclude=0 AND rule_id=$basis";
        $result = $this->db->query($query)->getResultArray();
        $rules = " WHERE ";
        foreach ($result as $row) {
            $rules .= " JSON_UNQUOTE(JSON_EXTRACT(supplier_segment_map.segment_json,'$." . $row['segment_id'] . "')) ";
            if ($row['above_below'] == 1) {
                $rules .= " >= " . $row['segment_value_idx'] . " ";
            } else {
                $rules .= " <= " . $row['segment_value_idx'] . " ";
            }
            $rules .= " AND";
        }
        $rules = substr($rules, 0, strlen($rules) - 3);
        $query = "SELECT suppliers.supplier_id,CONCAT(suppliers.name,' ',suppliers.code) AS supplier_name FROM suppliers JOIN supplier_segment_map ON suppliers.supplier_id=supplier_segment_map.supplier_id " . $rules . " LIMIT 20";
        $result = $this->db->query($query)->getResultArray();
        foreach ($result as $row) {
            $data['html'] .= ' <li role="option" data-value="' . $row['supplier_id'] . '" >' . $row['supplier_name'] . '</li>';
        }
        $data['html'] .= '
            </ul>
            </div>
            <p class="error-text" ></p>
        </div>
        ';
        return $data;
    }

    public function get_dtconfig_order()
    {
        $config = array(
            "columnNames" => ["sno", "order code", "supplier", "company transport", "delivery date", "status", "action"],
            "sortable" => [0, 1, 1, 0, 1, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    // for orders

    public function get_order_datatable()
    {
        $columns = array(
            "order code" => "order_code",
            "supplier" => "suppliers.name",
            "delivery date" => "delivery_date",
            "status" => "status"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT order_id,order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,CASE WHEN internal_transport=1 THEN 'Yes' ELSE 'No' END AS internal_transport,delivery_date,status FROM purchase_order JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id ";
        $count = "SELECT COUNT(order_id) AS total FROM purchase_order JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id ";

        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
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
                                <li><a target="_BLANK" href="' . url_to('erp.procurement.orderview', $r['order_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>';
            // if ($r['status'] < 2) {
                $action .= '<li><a href="' . base_url() . 'erp/procurement/orderedit/' . $r['order_id'] . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>';
                $action .= '<li><a href="' . base_url() . 'erp/procurement/orderdelete/' . $r['order_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>';
            // }
            '</ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->order_status_bg[$r['status']] . "' >" . $this->order_status[$r['status']] . "</span>";
            array_push(
                $m_result,
                array(
                    $r['order_id'],
                    $sno,
                    $r['order_code'],
                    $r['supplier'],
                    $r['internal_transport'],
                    $r['delivery_date'],
                    $status,
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

    public function get_order_for_view($order_id)
    {
        $query = "SELECT order_id,order_code,CONCAT(suppliers.name,' ',suppliers.code)
        AS supplier,internal_transport,IFNULL(CONCAT(transports.name,' ',transports.code),'')
        AS transport,transport_unit,transport_charge,CONCAT(supplier_locations.address,',',supplier_locations.city)
        AS location,delivery_date,terms_condition,notes,erp_users.name AS created,purchase_order.created_at,purchase_order.status,grn_created,invoice_created,warehouses.name AS warehouse FROM purchase_order JOIN suppliers
        ON purchase_order.supplier_id=suppliers.supplier_id JOIN supplier_locations ON 
        supp_location_id=supplier_locations.location_id LEFT JOIN transports ON 
        purchase_order.transport_id=transports.transport_id JOIN erp_users ON purchase_order.created_by=erp_users.user_id JOIN warehouses ON purchase_order.warehouse_id=warehouses.warehouse_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_order_for_pdf($order_id)
    {
    }

    public function get_order_items($order_id, $grn_update = false)
    {
        $qty_col = "quantity";
        if ($grn_update) {
            $qty_col = "received_qty";
        }
        $query = "SELECT order_item_id," . $qty_col . ",COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product,price_list.price_id,price_list.name,price_list.amount AS unit_price,(" . $qty_col . "*price_list.amount) AS amount, CONCAT(t1.tax_name,' ',t1.percent,' , ',IFNULL(t2.tax_name,''),' ',IFNULL(t2.percent,'')) AS tax FROM purchase_order_items LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' JOIN price_list ON purchase_order_items.price_id=price_list.price_id LEFT JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function update_order($order_id)
    {
        $updated = false;
        $order_code = $_POST["order_code"];
        $selection_rule = $_POST["selection_rule"];
        $supplier = $_POST["supplier"];
        $supplier_location = $_POST["supplier_location"];
        $delivery_date = $_POST["delivery_date"];
        $internal_transport = $_POST["internal_transport"] ?? 0;
        $transport_id = $_POST["transport_id"];
        $transport_unit = $_POST["transport_unit"];
        $transport_charge = $_POST["transport_charge"];
        $terms_condition = $_POST["terms_condition"];
        $warehouse_id = $_POST["warehouse_id"];
        $notes = $_POST["notes"];

        $query2 = "SELECT transport_id FROM purchase_order WHERE order_id=$order_id";
        $the_transport_id = $this->db->query($query2)->getRow();
        // return var_dump($the_transport_id->transport_id);

        $this->db->transBegin();
        $query = "UPDATE purchase_order SET order_code=? ,selection_rule=? , supplier_id=? , supp_location_id=? , delivery_date=? , terms_condition=? , notes=? , warehouse_id=? , internal_transport=? , transport_id=? , transport_unit=? , transport_charge=? WHERE order_id=$order_id";
        $this->db->query($query, array($order_code, $selection_rule, $supplier, $supplier_location, $delivery_date, $terms_condition, $notes, $warehouse_id, $internal_transport, $transport_id, $transport_unit, $transport_charge));


        $query = "UPDATE purchase_order_items SET price_id=? WHERE order_item_id=? ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "price_list_") === 0) {
                $order_item_id = substr($key, strlen("price_list_"));
                $this->db->query($query, array($value, $order_item_id));
            }
        }

        if (!empty($internal_transport)) {
            $query = "UPDATE transports SET status= 1 WHERE transport_id=$transport_id";
            $this->db->query($query);
            $check1 = $this->db->query("SELECT delivery_id FROM delivery_records WHERE related_id=$order_id AND related_to='purchase_order' ")->getRow();
            if (empty($check1)) {
                $query = "INSERT INTO delivery_records(transport_id,related_to,related_id,type) VALUES(?,?,?,?) ";
                $this->db->query($query, array($transport_id, 'purchase_order', $order_id, 0));
            } else {
                $query = "UPDATE delivery_records SET transport_id=? WHERE delivery_id=?";
                $this->db->query($query, array($transport_id, $check1->delivery_id));
            }
        } else {

            $query = "DELETE FROM delivery_records WHERE related_id=$order_id AND related_to='purchase_order' ";
            $this->db->query($query);
            if (!empty($the_transport_id->transport_id)) {
                $query2 = "UPDATE transports SET status= 0 WHERE transport_id = $the_transport_id->transport_id";
                $this->db->query($query2);
            }
        }

        $this->db->transComplete();
        $config['title'] = "Purchase Order Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Purchase Order failed to update ]";
            $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
            $this->session->setFlashdata("op_error", "Purchase Order failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Purchase Order successfully updated ]";
            $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
            $this->session->setFlashdata("op_success", "Purchase Order successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function change_order_status($order_id, $status)
    {
        $query = "UPDATE purchase_order SET status=$status WHERE order_id=$order_id";
        $this->db->query($query);

        $config['title'] = "Order Status Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Order Status successfully updated ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_success", "Order Status successfully updated");
        } else {
            $config['log_text'] = "[ Order Status failed to update ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Order Status failed to update");
        }
        log_activity($config);
    }

    public function insert_order_req($req_id)
    {
        $inserted = false;
        $order_code = $_POST["order_code"];
        $selection_rule = $_POST["selection_rule"];
        $supplier = $_POST["supplier"];
        $supplier_location = $_POST["supplier_location"];
        $delivery_date = $_POST["delivery_date"];
        $internal_transport = $_POST["internal_transport"] ?? 0;
        $transport_id = $_POST["transport_id"];
        $transport_unit = $_POST["transport_unit"];
        $transport_charge = $_POST["transport_charge"];
        $terms_condition = $_POST["terms_condition"];
        $notes = $_POST["notes"];
        $warehouse_id = $_POST["warehouse_id"];
        $created_by = get_user_id();

        $check = $this->db->query("SELECT req_id FROM purchase_order WHERE req_id=$req_id")->getRow();

        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate purchase order for same requisition");
            return $inserted;
        }

        $this->db->transBegin();
        $query = "INSERT INTO purchase_order(order_code,req_id,selection_rule,supplier_id,supp_location_id,delivery_date,terms_condition,notes,warehouse_id,internal_transport,transport_id,transport_unit,transport_charge,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($order_code, $req_id, $selection_rule, $supplier, $supplier_location, $delivery_date, $terms_condition, $notes, $warehouse_id, $internal_transport, $transport_id, $transport_unit, $transport_charge, time(), $created_by));
        $order_id = $this->db->insertID();
        // $this->db->transRollback();

        if (empty($order_id)) {
            $this->session->setFlashdata("op_error", "Purchase Order failed to create");
            return $inserted;
        }

        $req_items = $this->get_requisition_items($req_id);
        $m_result = array();

        foreach ($req_items as $row) {
            $m_result[$row['invent_req_id']] = $row;
        }
        $query = "INSERT INTO purchase_order_items(order_id,related_to,related_id,price_id,quantity) VALUES(?,?,?,?,?) ";

        foreach ($_POST as $key => $value) {
            if (stripos($key, "price_list_") === 0) {
                $invent_req_id = substr($key, strlen("price_list_"));
                $row = $m_result[$invent_req_id];
                $this->db->query($query, array($order_id, $row['related_to'], $row['related_id'], $value, $row['qty']));
                if (empty($this->db->insertID())) {
                    $this->db->transRollback();
                    break;
                }
            }
        }

        if (!empty($internal_transport)) {
            $query = "UPDATE transports SET status=1 WHERE transport_id=$transport_id";
            $this->db->query($query);
            $query = "INSERT INTO delivery_records(transport_id,related_to,related_id,type) VALUES(?,?,?,?) ";
            $this->db->query($query, array($transport_id, 'purchase_order', $order_id, 0));
            if (empty($this->db->insertID())) {
                $this->db->transRollback();
            }
        }
        $this->db->transComplete();
        $config['title'] = "Purchase Order Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Purchase Order failed to create ]";
            $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
            $this->session->setFlashdata("op_error", "Purchase Order failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Purchase Order successfully created ]";
            $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
            $this->session->setFlashdata("op_success", "Purchase Order successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function insert_order_rfq($supp_rfq_id, $req_id)
    {

        // echo $supp_rfq_id."<br>";
        // echo $req_id."<br>";
        // echo var_dump($_POST)."<br>";

        $inserted = false;
        $rfq_id = $_POST["rfq_id"];
        $order_code = $_POST["order_code"];
        $selection_rule = $_POST["selection_rule"];
        $supplier = $_POST["supplier"];
        $supplier_location = $_POST["supplier_location"];
        $delivery_date = $_POST["delivery_date"];
        $internal_transport = $_POST["internal_transport"] ?? 0;
        $transport_id = $_POST["transport_id"];
        $transport_unit = $_POST["transport_unit"];
        $transport_charge = $_POST["transport_charge"];
        $terms_condition = $_POST["terms_condition"];
        $notes = $_POST["notes"];
        $warehouse_id = $_POST["warehouse_id"];
        $created_by = get_user_id();

        $check = $this->db->query("SELECT req_id FROM purchase_order WHERE req_id=$req_id")->getRow();

        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate purchase order for same requisition");
            return $inserted;
        }

        $this->db->transBegin();
        $query = "INSERT INTO purchase_order(order_code,req_id,selection_rule,supplier_id,supp_location_id,delivery_date,terms_condition,notes,warehouse_id,internal_transport,transport_id,transport_unit,transport_charge,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($order_code, $req_id, $rfq_id, $selection_rule, $supplier, $supplier_location, $delivery_date, $terms_condition, $notes, $warehouse_id, $internal_transport, $transport_id, $transport_unit, $transport_charge, time(), $created_by));
        $order_id = $this->db->insertID();
        // $this->db->transRollback();

        if (empty($order_id)) {
            $this->session->setFlashdata("op_error", "Purchase Order failed to create");
            return $inserted;
        }

        $req_items = $this->get_requisition_items($req_id);
        $m_result = array();

        foreach ($req_items as $row) {
            $m_result[$row['invent_req_id']] = $row;
        }
        $query = "INSERT INTO purchase_order_items(order_id,related_to,related_id,price_id,quantity) VALUES(?,?,?,?,?) ";

        foreach ($_POST as $key => $value) {
            if (stripos($key, "price_list_") === 0) {
                $invent_req_id = substr($key, strlen("price_list_"));
                $row = $m_result[$invent_req_id];
                $this->db->query($query, array($order_id, $row['related_to'], $row['related_id'], $value, $row['qty']));
                if (empty($this->db->insertID())) {
                    $this->db->transRollback();
                    break;
                }
            }
        }

        if (!empty($internal_transport)) {
            $query = "UPDATE transports SET status=1 WHERE transport_id=$transport_id";
            $this->db->query($query);
            $query = "INSERT INTO delivery_records(transport_id,related_to,related_id,type) VALUES(?,?,?,?) ";
            $this->db->query($query, array($transport_id, 'purchase_order', $order_id, 0));
            if (empty($this->db->insertID())) {
                $this->db->transRollback();
            }
        }
        $this->db->transComplete();
        $config['title'] = "Purchase Order Insert using RFQ";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Purchase Order failed to create ]";
            $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
            $this->session->setFlashdata("op_error", "Purchase Order failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Purchase Order successfully created ]";
            $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
            $this->session->setFlashdata("op_success", "Purchase Order successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function get_order_by_id($order_id)
    {
        $query = "SELECT order_id,order_code,selection_rule,suppliers.supplier_id,CONCAT(suppliers.name,' ',suppliers.code)
        AS supplier,internal_transport,purchase_order.transport_id,IFNULL(CONCAT(transports.name,' ',transports.code),'')
        AS transport,transport_unit,transport_charge,CONCAT(supplier_locations.address,',',supplier_locations.city)
        AS location,supp_location_id,delivery_date,terms_condition,notes,warehouse_id FROM purchase_order JOIN suppliers
        ON purchase_order.supplier_id=suppliers.supplier_id JOIN supplier_locations ON 
        supp_location_id=supplier_locations.location_id LEFT JOIN transports ON 
        purchase_order.transport_id=transports.transport_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_order_export($type)
    {
        $columns = array(
            "order code" => "order_code",
            "supplier" => "suppliers.name",
            "delivery date" => "delivery_date",
            "status" => "status"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,
            CASE WHEN internal_transport=1 THEN 'Yes' ELSE 'No' END AS internal_transport,delivery_date,
            CASE ";
            foreach ($this->order_status as $key => $value) {
                $query .= " WHEN status=$key THEN '" . $value . "' ";
            }
            $query .= " END AS status FROM purchase_order JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id ";
        } else {
            $query = "SELECT order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,
            CASE WHEN internal_transport=1 THEN 'Yes' ELSE 'No' END AS internal_transport,
            IFNULL(CONCAT(transports.name,' ',transports.code),'') AS transport,transport_unit,transport_charge,
            delivery_date,
            CASE ";
            foreach ($this->order_status as $key => $value) {
                $query .= " WHEN purchase_order.status=$key THEN '" . $value . "' ";
            }
            $query .= " END AS status,terms_condition,notes,req_code,
            CONCAT(supplier_locations.address,',',supplier_locations.city) AS supplier_location FROM purchase_order
            JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id JOIN supplier_locations 
            ON purchase_order.supp_location_id=supplier_locations.location_id LEFT JOIN transports 
            ON purchase_order.transport_id=transports.transport_id JOIN requisition 
            ON purchase_order.req_id=requisition.req_id ";
        }

        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_invoice_datatable()
    {
        $columns = array(
            "order code" => "order_code",
            "supplier" => "suppliers.name",
            "status" => "purchase_invoice.status",
            "amount" => "amount"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT invoice_id,order_code,amount,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,grn.status AS grn_status,purchase_invoice.status AS invoice_status FROM purchase_invoice JOIN purchase_order ON purchase_invoice.order_id=purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id JOIN grn ON purchase_order.order_id=grn.order_id ";
        $count = "SELECT COUNT(invoice_id) AS total FROM purchase_invoice JOIN purchase_order ON purchase_invoice.order_id=purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id JOIN grn ON purchase_order.order_id=grn.order_id ";

        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
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
                                <li><a target="_BLANK" href="' . base_url() . 'erp/procurement/invoiceview/' . $r['invoice_id'] . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->invoice_status_bg[$r['invoice_status']] . "' >" . $this->invoice_status[$r['invoice_status']] . "</span>";
            $delivered = "Yes";
            if ($r['grn_status'] == 0) {
                $delivered = "No";
            }
            array_push(
                $m_result,
                array(
                    $r['invoice_id'],
                    $sno,
                    $r['order_code'],
                    $r['supplier'],
                    $delivered,
                    $status,
                    $r['amount'],
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

    public function create_invoice($order_id)
    {
        $check = $this->db->query("SELECT invoice_created FROM purchase_order WHERE order_id=$order_id")->getRow();
        if (empty($check->grn_created)) {
            $amount = $this->db->query("SELECT SUM(amount*quantity) AS total FROM purchase_order_items JOIN price_list ON purchase_order_items.price_id=price_list.price_id WHERE order_id=$order_id ")->getRow()->total;
            $query = "INSERT INTO purchase_invoice(order_id,amount) VALUES(?,?) ";
            $this->db->query($query, array($order_id, $amount));
            $grn_id = $this->db->insertID();
            $config['title'] = "Purchase Invoice Insert";
            if (!empty($grn_id)) {
                $this->db->query("UPDATE purchase_order SET invoice_created=1 WHERE order_id=$order_id");
                $config['log_text'] = "[ Purchase Invoice successfully created ]";
                $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
                $this->session->setFlashdata("op_success", "Purchase Invoice successfully created");
            } else {
                $config['log_text'] = "[ Purchase Invoice failed to create ]";
                $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
                $this->session->setFlashdata("op_error", "Purchase Invoice failed to create");
            }
            log_activity($config);
        } else {
            $this->session->setFlashdata("op_error", "Purchase Invoice already created for this Purchase Order");
        }
    }

    public function get_invoice_export($type)
    {
        $columns = array(
            "order code" => "order_code",
            "supplier" => "suppliers.name",
            "status" => "purchase_invoice.status",
            "amount" => "amount"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,CASE WHEN grn.status=0 THEN 'No' ELSE 'Yes' END AS grn_status,
        CASE ";
        foreach ($this->invoice_status as $key => $value) {
            $query .= " WHEN purchase_invoice.status=$key THEN '" . $value . "' ";
        }
        $query .= " END AS invoice_status,amount FROM purchase_invoice JOIN purchase_order ON purchase_invoice.order_id=purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id JOIN grn ON purchase_order.order_id=grn.order_id ";

        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_invoice_for_view($invoice_id)
    {
        $query = "SELECT invoice_id,purchase_invoice.order_id,order_code,amount,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,grn.status AS grn_status,purchase_invoice.status AS invoice_status,paid_till,(amount-paid_till) AS due_amount,grn_updated FROM purchase_invoice JOIN purchase_order ON purchase_invoice.order_id=purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id JOIN grn ON purchase_order.order_id=grn.order_id WHERE invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_invoice_status_bg()
    {
        return $this->invoice_status_bg;
    }

    public function get_all_paymentmodes()
    {
        $query = "SELECT name,payment_id FROM payment_modes WHERE active=1";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_payments()
    {
        $config = array(
            "columnNames" => ["sno", "payment mode", "amount", "paid on", "transaction id", "action"],
            "sortable" => [0, 1, 1, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function insert_update_payment($invoice_id)
    {
        $purchase_pay_id = isset($_POST["purchase_pay_id"]) ? $_POST["purchase_pay_id"] : "";
        if (empty($purchase_pay_id)) {
            $this->insert_payment($invoice_id);
        } else {
            $this->update_payment($invoice_id, $purchase_pay_id);
        }
    }

    private function insert_payment($invoice_id)
    {
        $amount = $_POST["amount"];
        $paid_on = $_POST["paid_on"];
        $payment_id = $_POST["payment_id"];
        $transaction_id = $_POST["transaction_id"];
        $notes = $_POST["notes"];

        $this->db->transBegin();
        $query = "INSERT INTO purchase_payments(invoice_id,payment_id,amount,paid_on,transaction_id,notes) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($invoice_id, $payment_id, $amount, $paid_on, $transaction_id, $notes));
        if (empty($this->db->insertID())) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Purchase Payment failed to create");
            return;
        }
        $query = " UPDATE purchase_invoice SET status=
        CASE 
        WHEN (paid_till+?)<=0 THEN 0
        WHEN amount <= (paid_till+?) THEN 3
        WHEN amount > (paid_till+?) THEN 2
        END, paid_till=paid_till+?  WHERE invoice_id=? ";

        $this->db->query($query, array($amount, $amount, $amount, $amount, $invoice_id));
        $this->db->transComplete();

        $config['title'] = "Purchase Payment Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Purchase Payment failed to create ]";
            $config['ref_link'] = "erp/procurement/invoiceview/" . $invoice_id;
            $this->session->setFlashdata("op_error", "Purchase Payment failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Purchase Payment successfully created ]";
            $config['ref_link'] = "erp/procurement/invoiceview/" . $invoice_id;
            $this->session->setFlashdata("op_success", "Purchase Payment successfully created");
        }
        log_activity($config);
    }

    public function get_invoicepayment_export()
    {
        $invoice_id = $_GET["invoiceid"];
        $columns = array(
            "payment mode" => "pyament_modes.name",
            "amount" => "amount",
            "paid on" => "paid_on"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT payment_modes.name,amount,paid_on,transaction_id,notes FROM purchase_payments JOIN payment_modes ON purchase_payments.payment_id=payment_modes.payment_id WHERE invoice_id=$invoice_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND payment_modes.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    private function update_payment($invoice_id, $purchase_pay_id)
    {
        $amount = $this->input->post("amount");
        $paid_on = $this->input->post("paid_on");
        $payment_id = $this->input->post("payment_id");
        $transaction_id = $this->input->post("transaction_id");
        $notes = $this->input->post("notes");

        $this->db->transBegin();
        $old_amount = $this->db->query("SELECT amount FROM purchase_payments WHERE purchase_pay_id=$purchase_pay_id")->getRow()->amount;
        $diff = $amount - $old_amount;
        $query = "UPDATE purchase_payments SET invoice_id=? , payment_id=? , amount=? , paid_on=? , transaction_id=? , notes=? WHERE purchase_pay_id=$purchase_pay_id ";
        $this->db->query($query, array($invoice_id, $payment_id, $amount, $paid_on, $transaction_id, $notes));
        if ($diff != 0) {
            $query = " UPDATE purchase_invoice SET status=
            CASE 
            WHEN (paid_till+?)<=0 THEN 0
            WHEN amount <= (paid_till+?) THEN 3
            WHEN amount > (paid_till+?) THEN 2
            END, paid_till=paid_till+?  WHERE invoice_id=? ";
            $this->db->query($query, array($diff, $diff, $diff, $diff, $invoice_id));
        }
        $this->db->transComplete();

        $config['title'] = "Purchase Payment Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Purchase Payment failed to update ]";
            $config['ref_link'] = "erp/procurement/invoiceview/" . $invoice_id;
            $this->session->setFlashdata("op_error", "Purchase Payment failed to update");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Purchase Payment successfully updated ]";
            $config['ref_link'] = "erp/procurement/invoiceview/" . $invoice_id;
            $this->session->setFlashdata("op_success", "Purchase Payment successfully updated");
        }
        log_activity($config);
    }

    public function get_payment_datatable()
    {
        $invoice_id = $_GET["invoiceid"];
        $columns = array(
            "payment mode" => "pyament_modes.name",
            "amount" => "amount",
            "paid on" => "paid_on"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT purchase_pay_id,payment_modes.name,amount,paid_on,transaction_id FROM purchase_payments JOIN payment_modes ON purchase_payments.payment_id=payment_modes.payment_id WHERE invoice_id=$invoice_id ";
        $count = "SELECT COUNT(purchase_pay_id) AS total FROM purchase_payments JOIN payment_modes ON purchase_payments.payment_id=payment_modes.payment_id WHERE invoice_id=$invoice_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $count .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND payment_modes.name LIKE '%" . $search . "%' ";
                $count .= " AND payment_modes.name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/procurement/ajax_fetch_invoicepayment/' . $r['purchase_pay_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/procurement/invoicepaymentdelete/' . $invoice_id . '/' . $r['purchase_pay_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['purchase_pay_id'],
                    $sno,
                    $r['name'],
                    $r['amount'],
                    $r['paid_on'],
                    $r['transaction_id'],
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

    public function delete_payment($invoice_id, $purchase_pay_id)
    {

        $this->db->transBegin();
        $old_amount = $this->db->query("SELECT amount FROM purchase_payments WHERE purchase_pay_id=$purchase_pay_id")->getRow()->amount;

        $query = "DELETE FROM purchase_payments WHERE purchase_pay_id=$purchase_pay_id ";
        $this->db->query($query);
        $query = " UPDATE purchase_invoice SET status=
            CASE 
            WHEN (paid_till-?)<=0 THEN 0
            WHEN amount <= (paid_till-?) THEN 3
            WHEN amount > (paid_till-?) THEN 2
            END, paid_till=paid_till-?  WHERE invoice_id=? ";
        $this->db->query($query, array($old_amount, $old_amount, $old_amount, $old_amount, $invoice_id));
        $this->db->transComplete();

        $config['title'] = "Purchase Payment Delete";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Purchase Payment failed to delete ]";
            $config['ref_link'] = "erp/procurement/invoiceview/" . $invoice_id;
            $this->session->setFlashdata("op_error", "Purchase Payment failed to delete");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Purchase Payment successfully deleted ]";
            $config['ref_link'] = "erp/procurement/invoiceview/" . $invoice_id;
            $this->session->setFlashdata("op_success", "Purchase Payment successfully deleted");
        }
        log_activity($config);
    }

    public function get_dtconfig_returns()
    {
        $config = array(
            "columnNames" => ["sno", "order code", "supplier", "product", "returned qty"],
            "sortable" => [0, 1, 0, 0, 1, 0],
        );
        return json_encode($config);
    }

    public function get_return_datatable()
    {
        $columns = array(
            "order code" => "purchase_order.order_code",
            "supplier" => "suppliers.name",
            "returned qty" => "purchase_order_items.returned_qty"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT order_item_id,purchase_order.order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product,returned_qty FROM purchase_order_items LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' JOIN purchase_order ON purchase_order_items.order_id=purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id WHERE returned_qty > 0";
        $count = "SELECT COUNT(order_item_id) AS total FROM purchase_order_items LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' JOIN purchase_order ON purchase_order_items.order_id=purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id WHERE returned_qty > 0 ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( purchase_order.order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( purchase_order.order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( purchase_order.order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( purchase_order.order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
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
                                
                            </ul>
                        </div>
                    </div>';

            array_push(
                $m_result,
                array(
                    $r['order_item_id'],
                    $sno,
                    $r['order_code'],
                    $r['supplier'],
                    $r['product'],
                    $r['returned_qty'],
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function get_return_export($type)
    {
        $columns = array(
            "order code" => "purchase_order.order_code",
            "supplier" => "suppliers.name",
            "returned qty" => "purchase_order_items.returned_qty"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT purchase_order.order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product,returned_qty FROM purchase_order_items LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' JOIN purchase_order ON purchase_order_items.order_id=purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id WHERE returned_qty > 0";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( purchase_order.order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( purchase_order.order_code LIKE '%" . $search . "%' OR suppliers.name LIKE '%" . $search . "%' ) ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_rfq_for_edit($rfq_id)
    {
        $query = "SELECT rfq_id,rfq_code,expiry_date,terms_condition FROM rfq WHERE rfq_id=$rfq_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function create_grn($order_id)
    {
        $check = $this->db->query("SELECT grn_created FROM purchase_order WHERE order_id=$order_id")->getRow();
        if (empty($check->grn_created)) {
            $query = "INSERT INTO grn(order_id) VALUES(?) ";
            $this->db->query($query, array($order_id));
            $grn_id = $this->db->insertID();
            $config['title'] = "GRN Insert";
            if (!empty($grn_id)) {
                $this->db->query("UPDATE purchase_order SET grn_created=1 WHERE order_id=$order_id");
                $config['log_text'] = "[ GRN successfully created ]";
                $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
                $this->session->setFlashdata("op_success", "GRN successfully created");
            } else {
                $config['log_text'] = "[ GRN failed to create ]";
                $config['ref_link'] = "erp/procurement/orderview/" . $order_id;
                $this->session->setFlashdata("op_error", "GRN failed to create");
            }
            log_activity($config);
        } else {
            $this->session->setFlashdata("op_error", "GRN already created for this Purchase Order");
        }
    }

    //here comes the report query
    public function report()
    {
        $query = "SELECT status, COUNT(status) as status_count FROM rfq WHERE status IN (0, 1, 2, 3, 4) GROUP BY status";
        $result["RFQresult"] = $this->db->query($query)->getResultArray();
        $query = "SELECT status, COUNT(status) as status_count FROM purchase_order WHERE status IN (0, 1, 2, 3) GROUP BY status";
        $result['purchase_order_result'] = $this->db->query($query)->getResultArray();
        $query = "SELECT status, COUNT(status) as status_count FROM purchase_invoice WHERE status IN (0, 1, 2, 3) GROUP BY status";
        $result['purchase_invoice_result'] = $this->db->query($query)->getResultArray();
        // $query = "SELECT status, COUNT(status) as status_count FROM rfq WHERE status IN (0, 1, 2, 3, 4) GROUP BY status";
        // $RFQresult = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getInvoiceData()
    {
        $query = "SELECT invoice_id, order_code, amount, CONCAT(suppliers.name, ' ', suppliers.code) AS supplier, grn.status AS grn_status, purchase_invoice.status AS invoice_status, DATE(FROM_UNIXTIME(purchase_order.created_at)) AS created_at FROM purchase_invoice JOIN purchase_order ON purchase_invoice.order_id = purchase_order.order_id JOIN suppliers ON purchase_order.supplier_id = suppliers.supplier_id JOIN grn ON purchase_order.order_id = grn.order_id";
        $result = $this->db->query($query)->getResultArray();

        return $result;
    }

    public function get_procurement_data_for_finance($year = "")
    {
        if(empty($year)){
            $year = date('Y');
        }
        $query = "SELECT SUM(contract_value) AS total_expenditure, MONTH(acceptance_date) AS month_number
        FROM erpcontract 
        WHERE YEAR(acceptance_date) = $year 
        GROUP BY YEAR(acceptance_date), MONTH(acceptance_date) 
        ORDER BY month_number";
        return $this->db->query($query)->getResultArray();
    }

    public function get_req_count()
    {
        $query = "SELECT count(req_id) as count FROM requisition";
        return $this->db->query($query)->getRow();
    }
    
    public function get_rfq_count()
    {
        $query = "SELECT count(rfq_id) as count FROM rfq";
        return $this->db->query($query)->getRow();
    }

    public function get_order_count()
    {
        $query = "SELECT count(order_id)as count FROM purchase_order";
        return $this->db->query($query)->getRow();
    }
    
    public function is_rfq_created($req_id){
        $query = "SELECT rfq_id FROM rfq where req_id = ".$req_id.";";
        return $this->db->query($query)->getRow();
    }

    public function is_order_created($req_id){
        $query = "SELECT req_id FROM `purchase_order` WHERE req_id = ".$req_id.";";
        return $this->db->query($query)->getRow();
    }
    
    public function get_rfq_supplier_details($rfq_supplier_id){
        $query = "SELECT supplier_id FROM `supplier_rfq` WHERE supp_rfq_id = ".$rfq_supplier_id;
        return $this->db->query($query)->getRow();
    }

    public function get_supplier_details($supplier_id){
        $query = "SELECT supplier_id, name, code FROM suppliers WHERE supplier_id = ".$supplier_id;
        return $this->db->query($query)->getRow();
    }

}
