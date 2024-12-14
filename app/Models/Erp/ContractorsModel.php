<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class ContractorsModel extends Model
{
    protected $table      = 'contractors';
    protected $primaryKey = 'contractor_id';

    protected $allowedFields = [
        'con_code',
        'name',
        'contact_person',
        'email',
        'phone_1',
        'phone_2',
        'gst_no',
        'pan_no',
        'website',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'active',
        'description',
        'created_at',
        'created_by',
    ];

    protected $useTimestamps = false;
    protected $data;
    protected $db;
    protected $session;
    protected $attachmentModel;

    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    private $contractor_status = array(
        0 => "Created",
        1 => "Partially Paid",
        2 => "Paid"
    );

    private $contractor_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_success"
    );

    public function get_contractor_status()
    {
        return $this->contractor_status;
    }

    public function get_contractor_status_bg()
    {
        return $this->contractor_status_bg;
    }

    public function get_all_contractors()
    {
        $query = "SELECT contractor_id,CONCAT(name,' ',con_code) AS contractor FROM contractors ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_contractors()
    {
        $config = array(
            "columnNames" => ["sno", "code", "name", "contact person", "email", "phone", "gst no", "active", "action"],
            "sortable" => [0, 1, 1, 1, 0, 0, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_contractor_datatable()
    {
        $columns = array(
            "name" => "name",
            "code" => "con_code",
            "contact person" => "contact_person"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT contractor_id,con_code,name,contact_person,email,phone_1,gst_no,active FROM contractors ";
        $count = "SELECT COUNT(contractor_id) AS total FROM contractors ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR contact_person LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( name LIKE '%" . $search . "%' OR contact_person LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( name LIKE '%" . $search . "%' OR contact_person LIKE '%" . $search . "%' ) ";
                $count .= " AND ( name LIKE '%" . $search . "%' OR contact_person LIKE '%" . $search . "%' ) ";
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
                                <li><a target="_blank" href="' . url_to('erp.hr.contractorview', $r['contractor_id']) . '" title="View" class="bg-warning modalBtn"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.contractoredit', $r['contractor_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.contractordelete', $r['contractor_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . url_to('erp.hr.ajaxcontractoractive', $r['contractor_id']) . '" >
                        <input type="checkbox" ';
            if ($r['active'] == 1) {
                $active .= ' checked ';
            }
            $active .= ' />
                        <span class="togglebar"></span>
                    </label>';
            array_push(
                $m_result,
                array(
                    $r['contractor_id'],
                    $sno,
                    $r['con_code'],
                    $r['name'],
                    $r['contact_person'],
                    $r['email'],
                    $r['phone_1'],
                    $r['gst_no'],
                    $active,
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

    public function get_contractor_export($type)
    {
        $columns = array(
            "name" => "name",
            "code" => "con_code",
            "contact person" => "contact_person"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT con_code,name,contact_person,email,phone_1,gst_no,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM contractors ";
        } else {
            $query = "SELECT con_code,name,contact_person,email,phone_1,phone_2,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active,gst_no,pan_no,website,address,city,state,country,zipcode,description FROM contractors ";
        }

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR contact_person LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( name LIKE '%" . $search . "%' OR contact_person LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_contractor($post)
    {
        $inserted = false;
        $code = $post["con_code"];
        $name = $post["name"];
        $contact_person = $post["contact_person"];
        $email = $post["email"];
        $phone_1 = $post["phone_1"];
        $phone_2 = $post["phone_2"];
        $gst_no = $post["gst_no"];
        $pan_no = $post["pan_no"];
        $website = $post["website"];
        $description = $post["description"];
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zipcode = $post["zipcode"];
        $created_by = get_user_id();

        $query = "INSERT INTO contractors(con_code,name,contact_person,email,phone_1,phone_2,gst_no,pan_no,website,description,address,city,state,country,zipcode,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $this->db->query($query, array($code, $name, $contact_person, $email, $phone_1, $phone_2, $gst_no, $pan_no, $website, $description, $address, $city, $state, $country, $zipcode, time(), $created_by));

        $config['title'] = "Contractor Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Contractor successfully created ]";
            $config['ref_link'] = 'erp/hr/contractors/';
            $this->session->setFlashdata("op_success", "Contractor successfully created");
        } else {
            $config['log_text'] = "[ Contractor failed to create ]";
            $config['ref_link'] = 'erp/hr/contractors/';
            $this->session->setFlashdata("op_error", "Contractor failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_contractor($contractor_id, $post)
    {
        $updated = false;
        $code = $post["con_code"];
        $name = $post["name"];
        $contact_person = $post["contact_person"];
        $email = $post["email"];
        $phone_1 = $post["phone_1"];
        $phone_2 = $post["phone_2"];
        $gst_no = $post["gst_no"];
        $pan_no = $post["pan_no"];
        $website = $post["website"];
        $description = $post["description"];
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zipcode = $post["zipcode"];

        $query = "UPDATE contractors SET con_code=? , name=? , contact_person=? , email=? , phone_1=? , phone_2=? , gst_no=? , pan_no=? , website=? , description=? , address=? , city=? , state=? , country=? , zipcode=? WHERE contractor_id=$contractor_id";
        $this->db->query($query, array($code, $name, $contact_person, $email, $phone_1, $phone_2, $gst_no, $pan_no, $website, $description, $address, $city, $state, $country, $zipcode));

        $config['title'] = "Contractor Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Contractor successfully updated ]";
            $config['ref_link'] = 'erp/hr/contractors/';
            $this->session->setFlashdata("op_success", "Contractor successfully updated");
        } else {
            $config['log_text'] = "[ Contractor failed to update ]";
            $config['ref_link'] = 'erp/hr/contractors/';
            $this->session->setFlashdata("op_error", "Contractor failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_contractor_by_id($contractor_id)
    {
        $query = "SELECT * FROM contractors WHERE contractor_id=$contractor_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function deleteContractor($contractor_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('contractors')
                ->where('contractor_id', $contractor_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $contractor_id)
                ->where('related_to','contractor')
                ->get()
                ->getResultArray();

            if (!empty($attach_ids)) {
                foreach ($attach_ids as $attach) {
                    $this->attachmentModel->delete_attachment("contractor", $attach['attach_id']);
                }
            }

            $this->db->transComplete();

            $config['title'] = 'Contractor Delete';
            $config['log_text'] = "[ Contractor and related data deleted successfully ]";
            $config['ref_link'] = 'erp/hr/contractors/';

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }

            log_activity($config);

            return $this->session->setFlashdata("op_success", "Contractor and related data deleted successfully");
        } catch (Exception $e) {
            $config['title'] = 'Contractor Delete';
            $config['log_text'] = "[ Error deleting Contractor and related data ]";
            $config['ref_link'] = 'erp/hr/contractors/';
            log_activity($config);
            log_message('error', 'Error deleting Contractor and related data: ' . $e->getMessage());
            return $this->session->setFlashdata("op_error", "Error deleting Contractor and related data");
        }
    }

    public function getContractors()
    {
        return $this->builder()->select("contractor_id,con_code")->get()->getResultArray();
    }
    public function getContractorsName($relatedOptionValue)
    {
        return $this->builder()->select("con_code as name")->where('contractor_id',$relatedOptionValue)->get()->getRow();
    }
}
