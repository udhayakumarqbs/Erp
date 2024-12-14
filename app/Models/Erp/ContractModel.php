<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ContractModel extends Model
{
    protected $db;
    protected $logger;
    protected $table = "erpcontract";
    protected $primarykey = "contract_id";
    protected $allowedField = [
        "content",
        "description",
        "subject",
        "client",
        "datestart",
        "dateend",
        "contract_type",
        "project_id",
        "addedfrom",
        "isexpirynotified",
        "contract_value",
        "trash",
        "not_visible_to_client",
        "hash",
        "signed",
        "signature",
        "marked_as_signed",
        "acceptance_firstname",
        "acceptance_lastname",
        "acceptance_email",
        "acceptance_date",
        "acceptance_ip",
        "short_link",
        "last_sent_at",
        "contacts_sent_to",
        "last_sign_reminder_at"
    ];
    protected $useTimestamps = false; // Enable automatic timestamps

    // Specify the fields to be used for automatic timestamps
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'dateadded';


    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \config\Services::logger();
    }

    public function fetch_coloumn_dtconfig()
    {
        $config = array(
            "columnNames" => ["sno", "Subject", "Customer", "Contract Type", "Contract Value", "Start Date", "End Date", "Project", "signature", "options"],
            "sortable" => [0, 1, 1, 1, 1, 1, 1, 0, 0]
        );

        return json_encode($config);
    }

    public function get_datatable_header()
    {
        $config = array(
            "columnNames" => ["sno", "Name", "Status", "Start Date", "Due Date", "Priority", "Assigned To", "Action"],
            "sortable" => [0, 1, 0, 1, 0, 1, 1, 0]
        );

        return json_encode($config);
    }
    //add contract
    public function contractadd($data)
    {
        if ($this->builder()->insert($data)) {
            return true;
        } else {
            return false;
        }
    }
    //contract update
    public function Contract_Existing_data($id)
    {
        return $this->builder()->select()->where("contract_id", $id)->get()->getRow();
    }

    public function ajaxContractDataTable()
    {
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];
        $search = $_GET['search'] ?? "";

        $query = "SELECT a.contract_id as c_id,
        a.subject as c_sub,
        b.name as cust_name,
        c.cont_name as cont_name,
        a.contract_value as c_value,
        a.datestart as s_date,
        a.dateend as e_date,
        p.project_id as p_name,
        a.signed as sign
        FROM  erpcontract AS a 
        JOIN customers AS b ON a.client = b.cust_id
        JOIN contracttype AS c ON a.contract_type = c.cont_id
        LEFT JOIN projects AS p ON a.project_id = p.project_id ";

        $count = "SELECT * FROM  erpcontract AS a JOIN customers AS b ON a.client = b.cust_id
        JOIN contracttype AS c ON a.contract_type = c.cont_id LEFT JOIN projects AS p ON a.project_id = p.project_id ";

        if (!empty($search)) {
            $query .= " WHERE ( a.subject LIKE '%" . $search . "%' OR b.name LIKE '%" . $search . "%' OR c.cont_name LIKE '%" . $search . "%' ) ";
            $count .= " WHERE ( a.subject LIKE '%" . $search . "%' OR b.name LIKE '%" . $search . "%' OR c.cont_name LIKE '%" . $search . "%' ) ";
        }

        $query .= "ORDER BY dateadded DESC LIMIT $offset,$limit";

        $ajax_data = array();

        $sno = $offset + 1;

        $result = $this->db->query($query)->getResultArray();

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex justify-content-around">
                    <li><a href="' . url_to('erp.contract.view', $r["c_id"]) . '" title="view" class="bg-warning" target="_blank"><i class="fa fa-eye"></i> </a></li>
                    <li><a href="' . url_to('erp.contract.update', $r["c_id"]) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                    <li><a href="' . url_to('erp.contract.delete', $r["c_id"]) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
           </div>';

            if ($r["sign"] == 0) {
                $r["sign"] = "<p class='sign-tr-null'>Not Signed</p>";
            } else {
                $r["sign"] = "<p class='sign-tr-value'>Signed</p>";
            }

            if ($r["p_name"] == null) {
                $r["p_name"] = "None";
            }

            array_push($ajax_data, array(
                "",
                $sno,
                $r["c_sub"],
                $r["cust_name"],
                $r["cont_name"],
                "₹" . number_format($r["c_value"], 2, '.', ','),
                $r["s_date"],
                $r["e_date"],
                $r["p_name"],
                $r["sign"],
                $action
            ));
            $sno++;
        }
        $response["total_rows"] = count($this->db->query($count)->getResultArray());
        $response["data"] = $ajax_data;

        return $response;
    }

    public function fetch_contract_view($id)
    {
        $query = "SELECT a.contract_id as id,
            a.content as c_content,
            a.subject as c_sub,
            a.signed  as signed,
            b.name as cust_name,
            c.cont_name as cont_name,
            a.contract_value as c_value,
            a.acceptance_firstname as first_name,
            a.acceptance_lastname as last_name,
            a.acceptance_ip as ip,
            a.acceptance_date as sign_date,
            a.datestart as s_date,
            a.dateend as e_date,p.project_id as p_name
            FROM  erpcontract AS a 
            JOIN customers AS b ON a.client = b.cust_id
            JOIN contracttype AS c ON a.contract_type = c.cont_id
            LEFT JOIN projects AS p ON a.project_id = p.project_id
            WHERE a.contract_id = $id";

        return $this->db->query($query)->getrow();
    }
    public function contract_update($id, $data)
    {
        if ($this->builder()->where("contract_id", $id)->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function contract_customer_email($id)
    {
        $query = "SELECT b.email as email,a.client as id FROM erpcontract as a
                JOIN customers as b ON a.client = b.cust_id
                WHERE a.contract_id = $id";
        $result = $this->db->query($query)->getRow();

        return $result;
    }
    //get attachments
    public function get_attachments($type, $id)
    {
        $builder = $this->db->table('attachments');
        $builder->select('filename, attach_id');
        $builder->where('related_to', $type);
        $builder->where('related_id', $id);
        $result = $builder->get()->getResultArray();

        return $result;
    }

    public function get_attachments_count($type, $id)
    {
        $builder = $this->db->table('attachments');
        $builder->where('related_to', $type);
        $builder->where('related_id', $id);
        $result = $builder->countAllResults();

        return $result;
    }
    public function upload_attachment($type, $id)
    {
        $path = get_attachment_path($type);

        // Ensure the directory exists
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $_FILES['attachment']['name']);
        $filepath = $path . $filename;
        $response = [];

        if (file_exists($filepath)) {
            $response['error'] = 1;
            $response['reason'] = "File already exists";
        } else {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
                $query = "INSERT INTO attachments(filename, related_to, related_id) VALUES(?, ?, ?) ";

                if (empty($id)) {
                    $response['error'] = 1;
                    $response['reason'] = "Internal Error";
                } else {
                    $this->db->query($query, [$filename, $type, $id]);
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

    public function delete_attachment($type, $id)
    {
        $response = [];
        if (!empty($id)) {
            $filename = $this->db->query("SELECT filename FROM attachments WHERE attach_id = $id")->getRow();
            if (!empty($filename)) {
                $filepath = get_attachment_path($type) . $filename->filename;

                $this->db->query("DELETE FROM attachments WHERE attach_id=$id");

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

    //renewal update
    public function renewal_update($id, $data)
    {
        if ($this->builder()->where("contract_id", $id)->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function delete_contract($id)
    {
        return $this->builder()->where("contract_id ", $id)->delete();
    }

    //old data get
    public function get_old_data($id)
    {
        $query = "SELECT datestart,dateend,contract_value FROM erpcontract WHERE contract_id  = $id";
        $result = $this->db->query($query)->getRowArray();

        return $result;
    }

    public function getContract()
    {
        return $this->builder()->select("contract_id ,subject")->get()->getResultArray();
    }
    public function getContractById($id)
    {
        return $this->builder()->select()->where("contract_id ", $id)->get()->getRowArray();
    }

    public function get_data_export()
    {
        $query = "SELECT a.contract_id as c_id,
        a.subject as c_sub,
        b.name as cust_name,
        c.cont_name as cont_name,
        a.contract_value as c_value,
        a.datestart as s_date,
        a.dateend as e_date,
        p.project_id as p_name,
        a.signed as sign
        FROM  erpcontract AS a 
        JOIN customers AS b ON a.client = b.cust_id
        JOIN contracttype AS c ON a.contract_type = c.cont_id
        LEFT JOIN projects AS p ON a.project_id = p.project_id";

        $result = $this->db->query($query)->getResultArray();
        $contract = array();
        foreach ($result as $r) {
            if ($r["sign"] == 0) {
                $r["sign"] = "Not signed";
            } else {
                $r["sign"] = "signed";
            }

            $r["c_value"] = number_format($r["c_value"],2,'.',',');

            array_push($contract ,array(
                $r["c_sub"],
                $r["cust_name"],
                $r["cont_name"],
                $r["c_value"],
                $r["s_date"],
                $r["e_date"],
                $r["sign"],
            ));
        }

        return $contract;
    }

    public function get_contractor_data_for_finance($year = "")
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

}
