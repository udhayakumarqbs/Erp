<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\Model;
use App\Libraries\Requests\AbstractRequest;

class RequestModel extends Model
{
    protected $table            = 'request';
    protected $primaryKey       = 'request_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'from_m',
        'to_m',
        'purpose',
        'related_to',
        'related_id',
        'description',
        'mail_request',
        'status',
        'requested_at',
        'requested_by',
        'responded_by',
        'responded_at',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // 
    public function getDatatableConfig()
    {
        $config = array(
            "columnNames" => ["sno", "from", "to", "purpose", "requested for", "status", "requested on", "requested by", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 1, 0, 0],
        );
        return json_encode($config);
    }

    // Get Data For Datatable
    public function getRequestDatatable()
    {
        $columns = [
            "from" => "from_m",
            "to" => "to_m",
            "requested on" => "requested_at",
        ];

        $limit = $_GET['limit'] ?? 10; // Set a default limit if not provided
        $offset = $_GET['offset'] ?? 0; // Set a default offset if not provided
        $search = $_GET['search'] ?? "";

        $orderby = strtoupper($_GET['orderby'] ?? "");
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $user_id = get_user_id();
        $query = $this->db->table('request')
            ->select('request_id, from_m, to_m, purpose, CONCAT(customers.name, " - ", customers.company) AS customer, status, FROM_UNIXTIME(requested_at, "%Y-%m-%d") AS requested_at, erp_users.name AS requested')
            ->join('erp_users', 'request.requested_by = erp_users.user_id')
            ->join('customers', 'request.related_id = customers.cust_id')
            ->where("(responded_by=0 OR responded_by=$user_id)")
            ->where("(from_m='Sales' OR to_m='Sales')");

        $countQuery = clone $query;
        $count = $countQuery->countAllResults();

        if (!empty($search)) {
            $query->like('customers.name', $search)->orLike('customers.company', $search);
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query->orderBy($ordercol, $orderby);
        }

        $query->limit($limit, $offset);

        $result = $query->get()->getResultArray();
        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown_container">
                                <ul class="BB_UL flex">
                                    <li><a href="' . base_url('erp/sale/requestview/' . $r['request_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                    <li><a href="' . base_url('erp/sale/requestdelete/' . $r['request_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                </ul>
                            </div>
                        </div>';

            $status = '<span class="st ';
            if ($r['status'] == AbstractRequest::PENDING) {
                $status .= ' st_dark"> Pending</span>';
            } else if ($r['status'] == AbstractRequest::READ) {
                $status .= ' st_violet"> Read</span>';
            } else if ($r['status'] == AbstractRequest::PROCESSING) {
                $status .= ' st_primary"> Processing</span>';
            } else if ($r['status'] == AbstractRequest::RESPONDED) {
                $status .= ' st_success"> Responded</span>';
            } else if ($r['status'] == AbstractRequest::CLOSED) {
                $status .= ' st_danger"> Closed</span>';
            }

            array_push(
                $m_result,
                [
                    $r['request_id'],
                    $sno,
                    $r['from_m'],
                    $r['to_m'],
                    $r['purpose'],
                    $r['customer'],
                    $status,
                    $r['requested_at'],
                    $r['requested'],
                    $action,
                ]
            );
            $sno++;
        }

        $response = [
            'total_rows' => $count,
            'data' => $m_result,
        ];

        return $response;
    }

    public function get_dtconfig_request()
    {
        $config = array(
            "columnNames" => ["sno", "from", "to", "purpose", "customer", "status", "requested on", "requested by", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_request_datatable()
    {
        $columns = array(
            "from" => "from_m",
            "to" => "to_m",
            "requested on" => "requested_at"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $user_id = get_user_id();
        $query = "SELECT request_id,from_m,to_m,purpose, CONCAT(customers.name,' - ',customers.company) AS customer ,status, FROM_UNIXTIME(requested_at,'%Y-%m-%d') AS requested_at , erp_users.name AS requested FROM request JOIN erp_users ON request.requested_by=erp_users.user_id JOIN customers ON request.related_id=customers.cust_id WHERE ( responded_by=0 OR responded_by=$user_id ) AND ( from_m='CRM' OR to_m='CRM' ) ";
        $count = "SELECT COUNT(request_id) AS total FROM request JOIN erp_users ON request.requested_by=erp_users.user_id JOIN customers ON request.related_id=customers.cust_id WHERE ( responded_by=0 OR responded_by=$user_id ) AND ( from_m='CRM' OR to_m='CRM' ) ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( customers.name LIKE '%" . $search . "%' OR  customers.company LIKE '%" . $search . "%' )";
                $count .= " WHERE ( customers.name LIKE '%" . $search . "%' OR  customers.company LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( customers.name LIKE '%" . $search . "%' OR  customers.company LIKE '%" . $search . "%' )";
                $count .= " AND ( customers.name LIKE '%" . $search . "%' OR  customers.company LIKE '%" . $search . "%' )";
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
                                <li><a href="' . base_url() . 'erp/crm/requestview/' . $r['request_id'] . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/crm/requestdelete/' . $r['request_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = '<span class="st ';
            if ($r['status'] == AbstractRequest::PENDING) {
                $status .= ' st_dark"> Pending</span>';
            } else if ($r['status'] == AbstractRequest::READ) {
                $status .= ' st_violet"> Read</span>';
            } else if ($r['status'] == AbstractRequest::PROCESSING) {
                $status .= ' st_primary"> Read</span>';
            } else if ($r['status'] == AbstractRequest::RESPONDED) {
                $status .= ' st_success"> Responded</span>';
            } else if ($r['status'] == AbstractRequest::CLOSED) {
                $status .= ' st_danger"> Closed</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['request_id'],
                    $sno,
                    $r['from_m'],
                    $r['to_m'],
                    $r['purpose'],
                    $r['customer'],
                    $status,
                    $r['requested_at'],
                    $r['requested'],
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

    public function get_request_export($type)
    {
        $columns = array(
            "from" => "from_m",
            "to" => "to_m",
            "requested on" => "requested_at"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $user_id = get_user_id();
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT from_m,to_m,purpose, CONCAT(customers.name,' - ',customers.company) AS customer , 
                CASE WHEN status=" . AbstractRequest::PENDING . " THEN 'Pending' 
                WHEN status=" . AbstractRequest::READ . " THEN 'Read' 
                WHEN status=" . AbstractRequest::PROCESSING . " THEN 'Processing' 
                WHEN status=" . AbstractRequest::RESPONDED . " THEN 'Responded' 
                WHEN status=" . AbstractRequest::CLOSED . " THEN 'Closed'
                END AS status , FROM_UNIXTIME(requested_at,'%Y-%m-%d') AS requested_at , erp_users.name AS requested  FROM request JOIN erp_users ON request.requested_by=erp_users.user_id JOIN customers ON request.related_id=customers.cust_id WHERE ( responded_by=0 OR responded_by=$user_id ) AND ( from_m='CRM' OR to_m='CRM' ) ";
        } else {
            $query = "SELECT from_m,to_m,purpose, CONCAT(customers.name,' - ',customers.company) AS customer , 
                CASE WHEN status=" . AbstractRequest::PENDING . " THEN 'Pending' 
                WHEN status=" . AbstractRequest::READ . " THEN 'Read' 
                WHEN status=" . AbstractRequest::PROCESSING . " THEN 'Processing' 
                WHEN status=" . AbstractRequest::RESPONDED . " THEN 'Responded' 
                WHEN status=" . AbstractRequest::CLOSED . " THEN 'Closed'
                END AS status , FROM_UNIXTIME(requested_at,'%Y-%m-%d') AS requested_at , erp_users.name AS requested, CASE WHEN mail_request=1 THEN 'Yes' ELSE 'No' END AS mail_request,request.description  FROM request JOIN erp_users ON request.requested_by=erp_users.user_id JOIN customers ON request.related_id=customers.cust_id WHERE ( responded_by=0 OR responded_by=$user_id ) AND ( from_m='CRM' OR to_m='CRM' ) ";
        }
        $where = true;
        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( customers.name LIKE '%" . $search . "%' OR  customers.company LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( customers.name LIKE '%" . $search . "%' OR  customers.company LIKE '%" . $search . "%' )";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function process_requestaction($request_id){
        $action=$_POST["action"];
        if($action=="respond"){
            $responded_by=get_user_id();
            $query="UPDATE request SET responded_by=? , responded_at=? , status=? WHERE request_id=$request_id";
            $this->db->query($query,array($responded_by,time(),AbstractRequest::READ));
            $config['title']="Request Handle";
            if($this->db->affectedrows() > 0){
                $config['log_text']="[ Request successfully handled ]";
                $config['ref_link']="erp/sale/requestview/".$request_id;
                session()->setFlashdata("op_success","Request successfully handled");
            }else{
                $config['log_text']="[ Request failed to handle ]";
                $config['ref_link']="erp/sale/requestview/".$request_id;
                session()->setFlashdata("op_error","Request failed to handle");
            }
            log_activity($config);
        }else if($action=="statuschange"){
            $status=$_POST["status"];
            $query="UPDATE request SET status=? WHERE request_id=$request_id";
            $this->db->query($query,array($status));
            $config['title']="Request Status Change";
            if($this->db->affectedrows() > 0){
                $config['log_text']="[ Request Status successfully changed ]";
                $config['ref_link']="erp/sale/requestview/".$request_id;
                session()->setFlashdata("op_success","Request Status successfully changed");
            }else{
                $config['log_text']="[ Request Status failed to change ]";
                $config['ref_link']="erp/sale/requestview/".$request_id;
                session()->setFlashdata("op_error","Request Status failed to change");
            }
            log_activity($config);
        }
    }
}
