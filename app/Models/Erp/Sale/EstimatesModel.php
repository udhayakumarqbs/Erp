<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Model;
use App\libraries\Scheduler;
use DateTime;

class EstimatesModel extends Model
{
    protected $table            = 'estimates';
    protected $primaryKey       = 'estimate_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'estimate_id',
        'code',
        'cust_id',
        'estimate_date',
        'terms_condition',
        'shippingaddr_id',
        'created_by',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $logger;
    protected $db;
    public function __construct()
    {
        $this->logger = \Config\Services::logger();
        $this->db = \Config\Database::connect();
    }

    public function getDtconfigEstimates()
    {
        $config = array(
            "columnNames" => ["sno", "code", "customer", "estimate date", "total amount", "action"],
            "sortable" => [0, 1, 0, 1, 1, 0]
        );
        return json_encode($config);
    }

    public function get_all_equipments()
    {
        $query = "SELECT equip_id,CONCAT(name,' ',code) AS equip_name FROM equipments WHERE status=0";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    // Estimates Data Table
    public function getEstimateDatatableold()
    {
        $columns = array(
            "code" => "code",
            "estimate date" => "estimate_date",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT estimates.estimate_id,code,customers.name,estimate_date,SUM(amount + (amount * (estimate_items.tax1 + estimate_items.tax2)) / 100) AS total_amount FROM estimates JOIN customers ON estimates.cust_id=customers.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id "; 
        $count = "SELECT COUNT(estimates.estimate_id) AS total FROM estimates JOIN customers ON estimates.cust_id=customers.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id  ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( customers.name LIKE '%" . $search . "%' OR  code LIKE '%" . $search . "%' )";
                $count .= " WHERE ( customers.name LIKE '%" . $search . "%' OR  code LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( customers.name LIKE '%" . $search . "%' OR  code LIKE '%" . $search . "%' )";
                $count .= " AND ( customers.name LIKE '%" . $search . "%' OR  code LIKE '%" . $search . "%' )";
            }
        }
        $query .= " GROUP BY estimates.estimate_id ";
        $count .= " GROUP BY estimates.estimate_id ";
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
                                <li><a href="' . url_to('erp.sale.estimates.view', $r['estimate_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.sale.estimates.edit', $r['estimate_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.sale.estimates.delete', $r['estimate_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['estimate_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $r['estimate_date'],
                    $r['total_amount'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow();
        $response['data'] = $m_result;
        return $response;
    }

    public function getEstimateDatatable(){
        $columns=array(
            "code"=>"code",
            "estimate date"=>"estimate_date",
            "total amount"=>"total_amount"
        );
        $limit=$_GET['limit'];
        $offset=$_GET['offset'];
        $search=$_GET['search']??"";

        $orderby=isset($_GET['orderby'])?strtoupper($_GET['orderby']):"";
        $ordercol=isset($_GET['ordercol'])?$columns[$_GET['ordercol']]:"";

        $query="SELECT estimates.estimate_id,code,customers.name,estimate_date,SUM(amount + (amount * (estimate_items.tax1 + estimate_items.tax2)) / 100) AS total_amount FROM estimates JOIN customers ON estimates.cust_id=customers.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id ";
        $count="SELECT COUNT(estimates.estimate_id) AS total FROM estimates JOIN customers ON estimates.cust_id=customers.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id  ";
        $where=true;

        if(!empty($search)){
            if(!$where){
                $query.=" WHERE ( customers.name LIKE '%".$search."%' OR  code LIKE '%".$search."%' )";
                $count.=" WHERE ( customers.name LIKE '%".$search."%' OR  code LIKE '%".$search."%' )";
                $where=true;
            }else{
                $query.=" AND ( customers.name LIKE '%".$search."%' OR  code LIKE '%".$search."%' )";
                $count.=" AND ( customers.name LIKE '%".$search."%' OR  code LIKE '%".$search."%' )";
            }
        }
        $query.=" GROUP BY estimates.estimate_id ";
        $count.=" GROUP BY estimates.estimate_id ";
        if(!empty($ordercol) && !empty($orderby)){
            $query.=" ORDER BY ".$ordercol." ".$orderby;
        }
        $query.=" LIMIT $offset,$limit ";
        $result=$this->db->query($query)->getResultArray();
        $m_result=array();
        $sno=$offset+1;
        foreach($result as $r){
            $action='<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                            <li><a href="' . url_to('erp.sale.estimates.view', $r['estimate_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                            <li><a href="' . url_to('erp.sale.estimates.edit', $r['estimate_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                            <li><a href="' . url_to('erp.sale.estimates.delete', $r['estimate_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['estimate_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $r['estimate_date'],
                    number_format($r['total_amount'],2,'.',','),
                    $action
                )
            );
            $sno++;
        }
        $this->logger->error($query);
        $response=array();
        $response['total_rows']=$this->db->query($count)->getRow()->total??0;
        $response['data']=$m_result;
        return $response;
    }

    public function getMaximumEstimateNumber(){
        $query = "SELECT MAX(estimate_id) as max_code  FROM estimates ";
        return $this->db->query($query)->getResultArray()[0];
    }

    // Insertion
    public function insertEstimate($data)
    {
        $inserted = false;
        $this->db->transBegin();
        
        $this->insert($data);    
        $estimate_id = $this->insertId();

    //    var_dump($estimate_id);
    //    exit();
        
        if (empty($estimate_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Estimate failed to create");
            return $inserted;
        }

        // Assuming you have a method named 'updateEstimateItems' in the same model
        if (!$this->updateEstimateItems($estimate_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Estimate failed to create");
            return $inserted;
        }

        $this->db->transComplete();

        $config = [
            'title' => "Estimate Insert",
            'log_text' => "",
            'ref_link' => "",
        ];

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            $config['log_text'] = "[ Estimate failed to create ]";
            session()->setFlashdata("op_success", "Estimate failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Estimate successfully created ]";
            $config['ref_link'] = "erp/sale/estimateview/" . $estimate_id;
            session()->setFlashdata("op_success", "Estimate successfully created");
        }

        log_activity($config);

        return $inserted;
    }

    // Edit Estimate Data main
    public function updateEstimates($estimate_id, $data)
    {
        $updated = false;
        $code = $_POST["code"];
        $cust_id = $_POST["cust_id"];
        $estimate_date = $_POST["estimate_date"];
        $shippingaddr_id = $_POST["shippingaddr_id"];
        $terms_condition = $_POST["terms_condition"];
        $updated_at = time();
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "UPDATE estimates SET code=? , cust_id=? , estimate_date=? , shippingaddr_id=? , terms_condition=?, updated_at=? WHERE estimate_id=$estimate_id ";
        $this->db->query($query, array($code, $cust_id, $estimate_date, $shippingaddr_id, $terms_condition, $updated_at));

        if (!$this->updateEstimateItems($estimate_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Estimate failed to update");
            return $updated;
        }

        $this->db->transComplete();
        $config['title'] = "Estimate Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Estimate failed to update ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_success", "Estimate failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Estimate successfully updated ]";
            $config['ref_link'] = "erp/sale/estimateview/" . $estimate_id;
            session()->setFlashdata("op_success", "Estimate successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_m_estimate_by_id($estimate_id)
    {
        $query = "SELECT estimate_id,code,estimate_date,terms_condition,customers.name,customers.cust_id,shippingaddr_id FROM estimates JOIN customers ON estimates.cust_id=customers.cust_id WHERE estimate_id=$estimate_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_m_shipping_addr($cust_id)
    {
        $query = "SELECT shippingaddr_id,CONCAT(address,',',city) AS addr FROM customer_shippingaddr WHERE cust_id=$cust_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_m_estimate_items($estimate_id)
    {
        $query = "SELECT price_list.price_id,related_id,quantity,CONCAT(finished_goods.name,' ',finished_goods.code) AS product, price_list.amount AS unit_price, price_list.tax1 as tax1, price_list.tax2 as tax2,(quantity * price_list.amount) AS amount,estimate_items.tax1 as tax1_amount, estimate_items.tax2 as tax2_amount, taxes1.tax_name AS tax1_rate, taxes2.tax_name AS tax2_rate FROM estimate_items JOIN finished_goods ON related_id=finished_goods.finished_good_id JOIN price_list ON estimate_items.price_id=price_list.price_id
        LEFT JOIN 
            taxes AS taxes1 ON price_list.tax1 = taxes1.tax_id
        LEFT JOIN 
            taxes AS taxes2 ON price_list.tax2 = taxes2.tax_id WHERE estimate_id=$estimate_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    // Update Estimate Items after Insertion or Updation
    private function updateEstimateItems($estimate_id){
        $updated=true;
        $product_ids=$_POST["product_id"];
        $quantities=$_POST["quantity"];
        $price_ids=$_POST["price_id"];

        if(empty($product_ids)){
            return false;
        }
        $result=$this->db->query("SELECT related_id FROM estimate_items WHERE estimate_id=$estimate_id")->getResultArray();
        $old_product_ids=array();
        foreach($result as $row){
            array_push($old_product_ids,$row['related_id']);
        }
        $del_product_ids=array();
        foreach($old_product_ids as $old){
            $mark=true;
            foreach($product_ids as $new){
                if($old==$new){
                    $mark=false;
                    break;
                }
            }
            if($mark){
                array_push($del_product_ids,$old);
            }
        }
        if(!empty($del_product_ids)){
            $query="DELETE FROM estimate_items WHERE related_id IN (".implode(",",$del_product_ids).") AND estimate_id=$estimate_id";
            $this->db->query($query);
        }
        $query="SELECT estimate_m_items(?,?,?,?) AS error ";
        foreach($product_ids as $key=>$value){
            $error=$this->db->query($query,array($estimate_id,$value,$quantities[$key],$price_ids[$key]))->getRow()->error;
            $this->logger->error($this->db->error());
            if($error==1){
                $updated=false;
                break;
            }
        }
        return $updated;
    }

 
    // Delete Estimate
    public function DeleteEstimate($estimate_id)
    {
        $deleted = false;

        $this->db->transBegin();

        // Delete estimate items
        $this->deleteEstimateItems($estimate_id);

        $estimate = $this->get_m_estimate_by_id($estimate_id);

        // Delete estimate
        $this->db->table('estimates')
            ->where('estimate_id', $estimate_id)
            ->delete();

        if ($this->db->affectedRows() > 0) {
            $this->db->transCommit();
            $deleted = true;
            $config = [
                'title' => 'Estimate Deletion',
                'log_text' => "[ Estimate successfully deleted ]",
                'ref_link' => 'erp/sale/delete_estimate/' . $estimate_id,
                'additional_info' => json_encode($estimate), // Log equipment details before deletion
            ];
        } else {
            $this->db->transRollback();
            $config = [
                'title' => 'Estimate Deletion',
                'log_text' => "[ Estimate Deletion Failed ]",
                'ref_link' => 'erp/sale/delete_estimate/' . $estimate_id,
                'additional_info' => json_encode($estimate), // Log equipment details before deletion
            ];
        }

        log_activity($config);
        return $deleted;
    }

    // Delete Estimate Items
    private function deleteEstimateItems($estimate_id)
    {
        $this->db->table('estimate_items')->where('estimate_id', $estimate_id)
            ->delete();
    }

    // View
    public function get_m_estimate_for_view($estimate_id)
    {
        $query = "SELECT estimates.estimate_id,code,customers.name,customers.email,CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr, CONCAT(customer_billingaddr.address,',',customer_billingaddr.city,',',customer_billingaddr.state,',',customer_billingaddr.country,',',customer_billingaddr.zipcode) AS billing_addr,estimate_date,(SUM(amount + (amount * (estimate_items.tax1 + estimate_items.tax2)) / 100)) AS total_amount,terms_condition FROM estimates JOIN customers ON estimates.cust_id=customers.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id LEFT JOIN customer_shippingaddr ON estimates.cust_id=customer_shippingaddr.cust_id
        LEFT JOIN customer_billingaddr ON estimates.cust_id=customer_billingaddr.cust_id WHERE estimates.estimate_id=$estimate_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_estimate_items_for_view($estimate_id)
    {
        $query = "SELECT quantity,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,unit_price,amount FROM estimate_items JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE estimate_id=$estimate_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_notify()
    {
        $config = array(
            "columnNames" => ["sno", "title", "description", "notify at", "notify to", "notified", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_attachments($type, $related_id)
    {
        $query = "SELECT filename,attach_id FROM attachments WHERE related_to='$type' AND related_id=$related_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
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
                $query = "INSERT INTO attachments(filename,related_to,related_id) VALUES(?,?,?) ";
                $id = $_GET["id"] ?? 0;
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

    public function getCustomerContactById($estimate_id){
        $query = "SELECT customer_contacts.email FROM estimates JOIN customer_contacts ON estimates.cust_id = customer_contacts.cust_id WHERE estimate_id = $estimate_id";
        return $this->db->query($query)->getResultArray();
    }   

    public function getCustomerMailById($estimate_id){
        $query = "SELECT customers.email FROM estimates JOIN customers ON estimates.cust_id = customers.cust_id WHERE estimate_id = $estimate_id";
        return $this->db->query($query)->getResultArray();
    }

    public function delete_attachment($type)
    {
        $attach_id = $_GET["id"] ?? 0;
        $response = array();
        if (!empty($attach_id)) {
            $filename = $this->db->query("SELECT filename FROM attachments WHERE attach_id=$attach_id")->getRow();
            if (!empty($filename)) {
                $filepath = get_attachment_path($type) . $filename->filename;
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

    public function get_estimate_export($type)
    {
        $columns = array(
            "code" => "code",
            "estimate date" => "estimate_date",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT code,customers.name,CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS addr,estimate_date,SUM(amount) AS total_amount,terms_condition FROM estimates JOIN customers ON estimates.cust_id=customers.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id LEFT JOIN customer_shippingaddr ON estimates.shippingaddr_id=customer_shippingaddr.shippingaddr_id GROUP BY estimates.estimate_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( customers.name LIKE '%" . $search . "%' OR  code LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( customers.name LIKE '%" . $search . "%' OR  code LIKE '%" . $search . "%' )";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    // Fetch Data for datatable of notification
    public function getEstimatenotifyDatatable()
    {
        $estimate_id = $_GET["estid"];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT notify_id,title,notify_at,status,erp_users.name AS notify_to,notify_text FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='estimate' AND related_id=$estimate_id ";
        $count = "SELECT COUNT(notify_id) AS total FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='estimate' AND related_id=$estimate_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.sale.ajaxFetchNotify', $r['notify_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('sale.estimatenotify.order.delete', $estimate_id, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $notify_at = date("Y-m-d H:i:s", $r['notify_at']);
            $notified = '<span class="st  ';
            if ($r['status'] == 1) {
                $notified .= 'st_violet" >Yes</span>';
            } else {
                $notified .= 'st_dark" >No</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['notify_id'],
                    $sno,
                    $r['title'],
                    $r['notify_text'],
                    $notify_at,
                    $r['notify_to'],
                    $notified,
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

    public function delete_estimate_notify($estimate_id, $notify_id)
    {
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $config['title'] = "Estimate Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;
            $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
            $this->db->query($query);
            Scheduler::safeDelete($jobid);
            $config['log_text'] = "[ Estimate Notification successfully deleted ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            session()->setFlashdata("op_success", "Estimate Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Estimate Notification failed to delete ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            session()->setFlashdata("op_success", "Estimate Notification failed to delete");
        }
        log_activity($config);
    }

    // Notify Export
    public function get_estimatenotify_export($type)
    {
        $estimate_id = $_GET["estid"];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT title,notify_text,FROM_UNIXTIME(notify_at,'%Y-%m-%d %h:%i:%s') AS notify_at,erp_users.name AS notify_to,CASE WHEN status=1 THEN 'Yes' ELSE 'No' END AS notified FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='estimate' AND related_id=$estimate_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getEstimates()
    {
        return $this->builder()->select("estimate_id,code as estimatescode")->get()->getResultArray();
    }
    
    public function getEstimatesName($relatedOptionValue)
    {
        return $this->builder()->select("code as name")->where('estimate_id',$relatedOptionValue)->get()->getRow();
    }

    public function getAllTime(){
        return $this->builder()->select('created_at')->get()->getResultArray();
    }

}
