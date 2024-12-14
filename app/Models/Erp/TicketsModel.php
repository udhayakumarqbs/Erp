<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class TicketsModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'ticket_id';
    protected $allowedFields = [
        'subject', 'priority', 'cust_id','project_id', 'assigned_to', 'problem',
        'status', 'remarks', 'created_at', 'created_by'
    ];
    protected $useTimestamps = false;
    protected $db;
    protected $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }
    private $ticket_status = array(
        0 => "OPEN",
        1 => "IN PROGRESS",
        2 => "SOLVED",
        3 => "CLOSED"
    );
    private $ticket_priority = array(
        0 => "LOW",
        1 => "MEDIUM",
        2 => "HIGH",
        3 => "URGENT"
    );

    private $ticket_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_success",
        3 => "st_danger"
    );

    private $ticket_priority_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_violet",
        3 => "st_danger"
    );

    public function get_ticket_priority_bg()
    {
        return $this->ticket_priority_bg;
    }

    public function get_ticket_status_bg()
    {
        return $this->ticket_status_bg;
    }
    public function get_ticket_priority()
    {
        return $this->ticket_priority;
    }
    public function get_ticket_status()
    {
        return $this->ticket_status;
    }

    public function get_dtconfig_ticket()
    {
        $config = array(
            "columnNames" => ["sno", "subject", "priority", "Contact", "Project","assigned to", "status", "created on", "action"],
            "sortable" => [0, 1, 0, 1, 0, 1, 0, 1, 0],
            "filters" => ["priority", "status"]
        );
        return json_encode($config);
    }

    public function get_ticket_datatable()
    {
        $columns = array(
            "subject" => "subject",
            "priority" => "priority",
            "status" => "tickets.status",
            "customer" => "CONCAT(customer_contacts.firstname,' ',customer_contacts.lastname)",
            "assigned to" => "erp_users.name",
            "created on" => "tickets.created_at"
        );
        
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT ticket_id,subject,priority,CONCAT(customer_contacts.firstname,' ',customer_contacts.lastname) AS customer,projects.name as project_name,erp_users.name AS assigned,tickets.status,FROM_UNIXTIME(tickets.created_at,'%Y-%m-%d') AS created_at FROM tickets JOIN customer_contacts ON tickets.cust_id=customer_contacts.contact_id LEFT JOIN projects ON projects.project_id = tickets.project_id LEFT JOIN erp_users ON tickets.assigned_to=erp_users.user_id ";
        $count = "SELECT COUNT(ticket_id) AS total FROM tickets JOIN customer_contacts ON tickets.cust_id=customer_contacts.contact_id LEFT JOIN projects ON projects.project_id = tickets.project_id JOIN erp_users ON tickets.assigned_to=erp_users.user_id ";
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
                $query .= " WHERE ( subject LIKE '%" . $search . "%' OR  customer_contacts.firstname LIKE '%" . $search . "%' OR customer_contacts.lastname LIKE '%" . $search . "%' OR  projects.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " WHERE ( subject LIKE '%" . $search . "%' OR  customer_contacts.firstname LIKE '%" . $search . "%' OR customer_contacts.lastname LIKE '%" . $search . "%' OR  projects.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( subject LIKE '%" . $search . "%' OR  customer_contacts.firstname LIKE '%" . $search . "%' OR customer_contacts.lastname LIKE '%" . $search . "%' OR  projects.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " AND ( subject LIKE '%" . $search . "%' OR  customer_contacts.firstname LIKE'%" . $search . "%'  OR customer_contacts.lastname LIKE '%" . $search . "%' OR  projects.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
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
                                <li><a href="' . url_to('erp.crm.ticketview', $r['ticket_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.ticketedit', $r['ticket_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.ticketdelete', $r['ticket_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $priority = "<span class='st " . $this->ticket_priority_bg[$r['priority']] . "'>" . $this->ticket_priority[$r['priority']] . " </span>";
            $status = "<span class='st " . $this->ticket_status_bg[$r['status']] . "'>" . $this->ticket_status[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['ticket_id'],
                    $sno,
                    $r['subject'],
                    $priority,
                    $r['customer'],
                    $r['project_name'] ?? '-',
                    $r['assigned'] ?? '-',
                    $status,
                    $r['created_at'],
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

    public function export(){
        $query = "SELECT subject,priority,CONCAT(customer_contacts.firstname,' ',customer_contacts.lastname) AS customer,projects.name as project_name,erp_users.name AS assigned,tickets.status,FROM_UNIXTIME(tickets.created_at,'%Y-%m-%d') AS created_at FROM tickets JOIN customer_contacts ON tickets.cust_id=customer_contacts.contact_id LEFT JOIN projects ON projects.project_id = tickets.project_id LEFT JOIN erp_users ON tickets.assigned_to=erp_users.user_id ";
        $result = $this->db->query($query)->getResultArray();
        $final_result = array();
        foreach($result as $r){
            foreach($this->ticket_priority as $k => $v){
                if($k == $r["priority"]){
                    $r["priority"] = $v;
                }
            }
            foreach($this->ticket_status as $t => $tv){
                if($t == $r["status"]){
                    $r["status"] = $tv;
                }
            }

            array_push($final_result,$r);
        }
        return $final_result;
    }

    public function get_ticket_by_id($ticket_id)
    {
        $query = "SELECT ticket_id,subject,priority,CONCAT(customers.name,' - ',customers.company,' ( ',customer_contacts.firstname,' ',customer_contacts.lastname,' ) ') AS customer,tickets.cust_id,erp_users.name AS assigned,
         tickets.assigned_to,tickets.status as status,projects.name as project_name,projects.project_id as project_id,problem FROM tickets JOIN customer_contacts ON tickets.cust_id=customer_contacts.contact_id AND customer_contacts.primary_contact = 1
         JOIN customers ON customers.cust_id = customer_contacts.cust_id
         LEFT JOIN projects ON projects.project_id = tickets.project_id
         LEFT JOIN erp_users ON tickets.assigned_to=erp_users.user_id WHERE ticket_id=$ticket_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_ticket_for_view($ticket_id)
    {
        $query = "SELECT ticket_id,subject,priority,CONCAT(customer_contacts.firstname,' ',customer_contacts.lastname) AS contact,customers.name AS customer,projects.name AS project_name,erp_users.name AS assigned,tickets.assigned_to,tickets.status,problem,FROM_UNIXTIME(tickets.created_at,'%Y-%m-%d') AS created_at,tickets.remarks,tickets.created_by FROM tickets JOIN customer_contacts ON tickets.cust_id=customer_contacts.contact_id JOIN customers ON customers.cust_id = customer_contacts.cust_id LEFT JOIN projects ON projects.project_id = tickets.project_id LEFT JOIN erp_users ON tickets.assigned_to=erp_users.user_id WHERE ticket_id=$ticket_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function handle_ticket($ticket_id)
    {
        $user_id = get_user_id();
        $query = "UPDATE tickets SET status=1 WHERE status=0 AND assigned_to=$user_id AND ticket_id=$ticket_id";
        $this->db->query($query);

        $config['title'] = "Handle Ticket";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Handle Ticket successfully done ]";
            $config['ref_link'] = url_to('erp.crm.ticketview', $ticket_id);
            $this->session->setFlashdata("op_success", "Handle Ticket successfully done");
        } else {
            $config['log_text'] = "[ Handle Ticket failed ]";
            $config['ref_link'] = url_to('erp.crm.ticketview', $ticket_id);
            $this->session->setFlashdata("op_error", "Handle Ticket failed ");
        }
        log_activity($config);
        return $updated;
    }

    public function submit_ticket($ticket_id, $post)
    {
        $remarks = $post["remarks"];
        $status = $post["tick_status"];
        $user_id = get_user_id();
        $query = "UPDATE tickets SET status=? , remarks=? WHERE status=1 AND assigned_to=$user_id AND ticket_id=$ticket_id";
        $this->db->query($query, array($status, $remarks));

        $config['title'] = "Ticket Submit";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Ticket Submit successfully done ]";
            $config['ref_link'] = url_to('erp.crm.ticketview', $ticket_id);
            $this->session->setFlashdata("op_success", "Ticket Submit successfully done");
        } else {
            $config['log_text'] = "[ Ticket Submit failed ]";
            $config['ref_link'] = url_to('erp.crm.ticketview', $ticket_id);
            $this->session->setFlashdata("op_error", "Ticket Submit failed ");
        }
        log_activity($config);
        return $updated;
    }

    public function insert_ticket($post)
    {
        $inserted = false;
        $subject = $post["subject"];
        $priority = $post["priority"];
        $customer = $post["customer"];
        $project_id = $post["project_id"];
        $assigned_to = $post["assigned_to"];
        $problem = $post["problem"];
        $status = $post["status"];
        $created_by = get_user_id();

        $data = [
            "subject" => $subject,
            "priority" => $priority,
            "cust_id" => $customer,
            "project_id" => $project_id,
            "assigned_to" => $assigned_to,
            "problem" => $problem,
            "status" => $status,
            "created_at" => time(),
            "created_by" => $created_by,
        ];

        $this->db->table('tickets')->Insert($data);

        $config['title'] = "Ticket Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Ticket successfully created ]";
            $config['ref_link'] = 'erp/crm/tickets';
            $this->session->setFlashdata("op_success", "Ticket successfully created");
        } else {
            $config['log_text'] = "[ Ticket failed to create ]";
            $config['ref_link'] = 'erp/crm/tickets';
            $this->session->setFlashdata("op_error", "Ticket failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_ticket($ticket_id, $post)
    {
        $updated = false;
        $subject = $post["subject"];
        $priority = $post["priority"];
        $customer = $post["customer"];
        $project_id = $post["project_id"] ?? 0;
        $assigned_to = $post["assigned_to"];
        $problem = $post["problem"];
        $status = $post["status"];

        $query = "UPDATE tickets SET subject=? , priority=? , cust_id=? , project_id=? , assigned_to=? , problem=?,status =? WHERE ticket_id=$ticket_id";
        $result= $this->db->query($query, array($subject, $priority, $customer, $project_id ,$assigned_to, $problem, $status));

        $config['title'] = "Ticket Update";
        if ($result) {
            $updated = true;
            $config['log_text'] = "[ Ticket successfully updated ]";
            $config['ref_link'] = 'erp/crm/tickets';
            $this->session->setFlashdata("op_success", "Ticket successfully updated");
        } else {
            $config['log_text'] = "[ Ticket failed to update ]";
            $config['ref_link'] = 'erp/crm/tickets';
            $this->session->setFlashdata("op_error", "Ticket failed to update");
        }
        log_activity($config);
        return $updated;
    }


    public function delete_ticket($ticket_id)
    {
        $jobresult = $this->builder()->where('ticket_id', $ticket_id)->get()->getRow();

        $config['title'] = "Tickets Delete";

        if (!empty($jobresult)) {

            $this->db->table('tickets')->where('ticket_id', $ticket_id)->delete();
            $config['log_text'] = "[ Tickets successfully deleted ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_success", "Tickets successfully deleted");
        } else {
            $config['log_text'] = "[ Tickets failed to delete ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_success", "Tickets failed to delete");
        }

        log_activity($config);
    }

    public function getTicket()
    {
        return $this->builder()->select("ticket_id,subject")->get()->getResultArray();
    }
    public function getTicketName($relatedOptionValue)
    {
        return $this->builder()->select("subject as name")->where('ticket_id', $relatedOptionValue)->get()->getRow();
    }

    public function ticketReportBox()
    {
        $ticketTotal = $this->ticketTotal();
        $ticketSoledCount = $this->ticketSoledCount();
        $totalRecode = [
            "total" => $ticketTotal,
            "soled_total" => $ticketSoledCount,
        ];
        return $totalRecode;
    }


    private function ticketTotal()
    {
        $query = "SELECT COUNT(ticket_id) as total FROM `tickets`";
        $rusult = $this->db->query($query)->getResult();
        return $rusult;
    }

    private function ticketSoledCount()
    {
        $query = "SELECT COUNT(status) as count FROM `tickets` WHERE status=2";
        $result = $this->db->query($query)->getResult();
        return $result;
    }
}
