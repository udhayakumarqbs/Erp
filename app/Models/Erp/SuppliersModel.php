<?php

namespace App\Models\Erp;

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Model;
use Exception;

class SuppliersModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    protected $allowedFields = [
        'source_id',
        'name',
        'code',
        'position',
        'email',
        'phone',
        'office_number',
        'fax_number',
        'company',
        'gst',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'website',
        'payment_terms',
        'description',
        'active',
        'created_at',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;
    public $session;
    public $db;
    public $attachmentModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->attachmentModel = new AttachmentModel;

    }

    public function suplierDelete($supplier_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('suppliers')
                ->where('supplier_id', $supplier_id)
                ->delete();

            $this->db->table('supplier_contacts')
                ->where('supplier_id', $supplier_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $supplier_id)
                ->where('related_to', 'supplier')
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("supplier", $attach_id);
            }

            $this->db->table('supplier_locations')
                ->where('supplier_id', $supplier_id)
                ->delete();

            $this->db->table('supply_list')
                ->where('supplier_id', $supplier_id)
                ->delete();

            $this->db->table('supplier_segment_map')
                ->where('supplier_id', $supplier_id)
                ->delete();

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            session()->setFlashdata('op_success', 'Supplier and related data deleted successfully');
            return ['op_success' => true, 'message' => 'Supplier and related data deleted successfully'];
        } catch (Exception $e) {
            session()->setFlashdata('op_error', 'Error deleting supplier and related data: Contact Support');
            return ['op_Error' => false, 'message' => 'Error deleting supplier and related data: Contact Support'];
        }
    }


    private $supplylist_products = array(
        "raw_material" => "Raw Materials",
        "semi_finished" => "Semi Finished"
    );

    private $supplylist_products_link = array(
        "raw_material" => 'erp/supplier/raw-materials',
        "semi_finished" => 'erp/supplier/semi-finished'
    );

    public function get_dtconfig_suppliers()
    {
        $config = array(
            "columnNames" => ["sno", "name", "position", "email id", "phone", "company", "source", "groups", "active", "action"],
            "sortable" => [0, 1, 0, 1, 0, 1, 1, 0, 0],
            "filters" => ["source_id", "groups"]
        );
        return json_encode($config);
    }

    public function get_datatable_suppliers()
    {
        $columns = array(
            "name" => "name",
            "email id" => "email",
            "company" => "company",
            "source" => "supplier_sources.source_name",
            "source_id" => "supplier_sources.source_id",
            "groups" => "erp_groups_map.group_id",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT supplier_id,CONCAT(name,' ',code) AS name,position,email,phone,company,supplier_sources.source_name,GROUP_CONCAT(erp_groups.group_name SEPARATOR ' , ' ) AS groups, active FROM suppliers JOIN supplier_sources ON suppliers.source_id=supplier_sources.source_id JOIN erp_groups_map ON suppliers.supplier_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='supplier' ";
        $count = "SELECT COUNT(supplier_id) AS total FROM suppliers JOIN supplier_sources ON suppliers.source_id=supplier_sources.source_id JOIN erp_groups_map ON suppliers.supplier_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='supplier' ";
        $where = true;

        $filter_1_col = isset($_GET['source_id']) ? $columns['source_id'] : "";
        $filter_1_val = $_GET['source_id'];

        $filter_2_col = isset($_GET['groups']) ? $columns['groups'] : "";
        $filter_2_val = $_GET['groups'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . " IN (" . $filter_2_val . ") ";
                $count .= " WHERE " . $filter_2_col . " IN (" . $filter_2_val . ") ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . " IN (" . $filter_2_val . ") ";
                $count .= " AND " . $filter_2_col . " IN (" . $filter_2_val . ") ";
            }
        }

        if (!empty($search)) {
            if ($where) {
                $query .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' )";
                $count .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' )";
            } else {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' )";
                $count .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' )";
                $where = true;
            }
        }
        $query .= " GROUP BY supplier_id ";
        $count .= " GROUP BY supplier_id ";
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
                                <li><a href="' . base_url() . 'erp/supplier/supplier-view/' . $r['supplier_id'] . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/supplier-edit/' . $r['supplier_id'] . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/supplier-delete/' . $r['supplier_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . base_url() . 'erp/supplier/active/' . $r['supplier_id'] . '" >
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
                    $r['supplier_id'],
                    $sno,
                    $r['name'],
                    $r['position'],
                    $r['email'],
                    $r['phone'],
                    $r['company'],
                    $r['source_name'],
                    $r['groups'],
                    $active,
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = count($this->db->query($count)->getResultArray());
        $response['data'] = $m_result;
        return $response;
    }

    public function insert_supplier($post, $customfield_chkbx_counter)
    {
        $inserted = false;
        $supplier_source = $_POST['supplier_source'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $address = $_POST['address'];
        $position = $_POST['position'];
        $city = $_POST['city'];
        $email = $_POST['email'];
        $state = $_POST['state'];
        $phone = $_POST['phone'];
        $country = $_POST['country'];
        $fax_number = $_POST['fax_number'];
        $zipcode = $_POST['zipcode'];
        $office_number = $_POST['office_number'];
        $website = $_POST['website'];
        $company = $_POST['company'];
        $gst = $_POST['gst'];
        $payment_terms = $_POST['payment_terms'];
        $description = $_POST['description'];
        $groups = $_POST['groups'];
        $created_by=get_user_id();


        $this->db->transBegin();
        $query = "INSERT INTO suppliers(source_id,name,code,address,position,city,email,state,phone,country,fax_number,zipcode,office_number,website,company,gst,payment_terms,description,created_at,created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($supplier_source, $name, $code, $address, $position, $city, $email, $state, $phone, $country, $fax_number, $zipcode, $office_number, $website, $company, $gst, $payment_terms, $description, time(), $created_by));
        $supplier_id = $this->db->insertId();

        if (empty($supplier_id)) {
            session()->setFlashdata("op_error", "Supplier failed to create");
            return $inserted;
        }

        if (!$this->update_supplier_groups($supplier_id, $groups)) {
            session()->setFlashdata("op_error", "Supplier failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        if (!$this->update_supplier_cfv($supplier_id, $customfield_chkbx_counter)) {
            session()->setFlashdata("op_error", "Supplier failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Supplier Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Supplier failed to create ]";
            $config['ref_link'] = "erp/supplier/supplier-view/" . $supplier_id;
            session()->setFlashdata("op_error", "Supplier failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Supplier successfully created ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_success", "Supplier successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_supplier_groups($supplier_id, $groups)
    {
        $updated = true;
        $groups_csv = $groups;
        $groups = explode(",", $groups_csv);
        $query = "DELETE g1 FROM erp_groups_map g1 JOIN erp_groups ON g1.group_id=erp_groups.group_id WHERE erp_groups.related_to='supplier' AND g1.related_id=$supplier_id AND g1.group_id NOT IN (" . $groups_csv . ")";
        $this->db->query($query);
        $query = "SELECT g1.group_id FROM erp_groups_map g1 JOIN erp_groups ON g1.group_id=erp_groups.group_id WHERE erp_groups.related_to='supplier' AND g1.related_id=$supplier_id ";
        $result = $this->db->query($query)->getResultArray();
        $new_group = array();
        foreach ($groups as $group) {
            $dup = false;
            foreach ($result as $row) {
                if ($group == $row['group_id']) {
                    $dup = true;
                    break;
                }
            }
            if (!$dup) {
                array_push($new_group, $group);
            }
        }
        $query = "INSERT INTO erp_groups_map(group_id,related_id) VALUES(?,?) ";
        foreach ($new_group as $group) {
            $this->db->query($query, array($group, $supplier_id));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    private function update_supplier_cfv($supplier_id, $customfield_chkbx_counter)
    {
        $updated = true;
        $checkbox = array();

        // cf_id, related_id, field_value --> param order
        $query = "SELECT erp_custom_field_update(?,?,?) AS ret_code ";

        // Use $this->request->getPost() instead of $_POST
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } elseif (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $ret_code = $this->db->query($query, [$cf_id, $supplier_id, $value])->getRow()->ret_code;

                if (empty($ret_code)) {
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
                }
            }

            if (!empty($cf_id)) {
                $ret_code = $this->db->query($query, [$cf_id, $supplier_id, implode(",", $values)])->getRow()->ret_code;

                if (empty($ret_code)) {
                    $updated = false;
                    break;
                }
            }
        }

        return $updated;
    }


    public function get_supplier_by_id($supplier_id)
    {
        $builder = $this->db->table('suppliers');
        $builder->select('name, code, email, phone, fax_number, office_number, position, company, gst, source_id, GROUP_CONCAT(erp_groups_map.group_id SEPARATOR \',\' ) AS groups, address, city, state, country, zipcode, website, payment_terms, description');
        $builder->join('erp_groups_map', 'suppliers.supplier_id = erp_groups_map.related_id');
        $builder->join('erp_groups', 'erp_groups_map.group_id = erp_groups.group_id');
        $builder->where('erp_groups.related_to', 'supplier');
        $builder->where('supplier_id', $supplier_id);
        $result = $builder->get()->getRow();

        return $result;
    }

    public function update_supplier($supplier_id, $post, $customfield_chkbx_counter)
    {
        $session = \Config\Services::session();
        $updated = false;
        $this->db->transBegin();
        $groups = $post['groups'];
        $builder = $this->db->table('suppliers');
        $builder->set('source_id', $post['supplier_source']);
        $builder->set('name', $post['name']);
        $builder->set('code', $post['code']);
        $builder->set('address', $post['address']);
        $builder->set('position', $post['position']);
        $builder->set('city', $post['city']);
        $builder->set('email', $post['email']);
        $builder->set('state', $post['state']);
        $builder->set('phone', $post['phone']);
        $builder->set('country', $post['country']);
        $builder->set('fax_number', $post['fax_number']);
        $builder->set('zipcode', $post['zipcode']);
        $builder->set('office_number', $post['office_number']);
        $builder->set('website', $post['website']);
        $builder->set('company', $post['company']);
        $builder->set('gst', $post['gst']);
        $builder->set('payment_terms', $post['payment_terms']);
        $builder->set('description', $post['description']);
        $builder->where('supplier_id', $supplier_id);
        $builder->update();

        if (!$this->update_supplier_groups($supplier_id, $groups)) {
            $session->setFlashdata("op_error", "Supplier failed to update");
            $this->db->transRollback();
            return $updated;
        }

        if (!$this->update_supplier_cfv($supplier_id, $customfield_chkbx_counter)) {
            $session->setFlashdata("op_error", "Supplier failed to update");
            $this->db->transRollback();
            return $updated;
        }

        $this->db->transComplete();

        $config['title'] = "Supplier Update";

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            $config['log_text'] = "[ Supplier failed to update ]";
            $config['ref_link'] = "erp/supplier/supplier-view/{$supplier_id}";
            $session->setFlashdata("op_error", "Supplier failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Supplier successfully updated ]";
            $config['ref_link'] = "erp/supplier/supplier-view/{$supplier_id}";
            $session->setFlashdata("op_success", "Supplier successfully updated");
        }

        log_activity($config);
        return $updated;
    }


    public function get_supplier_for_view($supplier_id)
    {
        $builder = $this->db->table('suppliers');
        $builder->select('name, code, email, phone, fax_number, office_number, position, company, gst, supplier_sources.source_name, GROUP_CONCAT(erp_groups.group_name SEPARATOR \',\' ) AS groups, address, city, state, country, zipcode, website, payment_terms, description, active');
        $builder->join('supplier_sources', 'suppliers.source_id = supplier_sources.source_id');
        $builder->join('erp_groups_map', 'suppliers.supplier_id = erp_groups_map.related_id');
        $builder->join('erp_groups', 'erp_groups_map.group_id = erp_groups.group_id');
        $builder->where('erp_groups.related_to', 'supplier');
        $builder->where('supplier_id', $supplier_id);
        $result = $builder->get()->getRow();

        return $result;
    }

    public function get_supplylist_products()
    {
        return $this->supplylist_products;
    }

    public function get_supplylist_products_link()
    {
        return $this->supplylist_products_link;
    }

    public function get_dtconfig_contacts()
    {
        $config = [
            "columnNames" => ["sno", "name", "email", "position", "phone", "active", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0],
        ];

        return json_encode($config);
    }
    public function get_dtconfig_location()
    {
        $config = array(
            "columnNames" => ["sno", "address", "city", "state", "country", "zipcode", "action"],
            "sortable" => [0, 0, 1, 1, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_dtconfig_supplylist()
    {
        $config = array(
            "columnNames" => ["sno", "product type", "product", "action"],
            "sortable" => [0, 1, 1, 0],
        );
        return json_encode($config);
    }

    public function get_suppliers_export($type)
    {
        $columns = array(
            "name" => "name",
            "email id" => "email",
            "company" => "company",
            "source" => "supplier_sources.source_name",
            "source_id" => "supplier_sources.source_id",
            "groups" => "erp_groups_map.group_id",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT CONCAT(name,' ',code) AS name,email,phone,position,company,supplier_sources.source_name,GROUP_CONCAT(erp_groups.group_name SEPARATOR ' , ' ) AS groups FROM suppliers JOIN supplier_sources ON suppliers.source_id=supplier_sources.source_id JOIN erp_groups_map ON suppliers.supplier_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='supplier' ";
        } else {
            $query = "SELECT CONCAT(name,' ',code) AS name,email,phone,fax_number,office_number,position,company,gst,supplier_sources.source_name,GROUP_CONCAT(erp_groups.group_name SEPARATOR ' , ' ) AS groups,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active,address,city,state,country,zipcode,website,payment_terms,description FROM suppliers JOIN supplier_sources ON suppliers.source_id=supplier_sources.source_id JOIN erp_groups_map ON suppliers.supplier_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='supplier' ";
        }
        $where = true;

        $filter_1_col = isset($_GET['source_id']) ? $columns['source_id'] : "";
        $filter_1_val = $_GET['source_id'];

        $filter_2_col = isset($_GET['groups']) ? $columns['groups'] : "";
        $filter_2_val = $_GET['groups'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . " IN (" . $filter_2_val . ") ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . " IN (" . $filter_2_val . ") ";
            }
        }

        if (!empty($search)) {
            if ($where) {
                $query .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' )";
            } else {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' )";
                $where = true;
            }
        }
        $query .= " GROUP BY supplier_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    private function applyWhereClause(&$where)
    {
        $clause = $where ? " WHERE " : " AND ";
        $where = false;
        return $clause;
    }

    public function delete_source($source_id)
    {
        $check = $this->db->query("SELECT COUNT(source_id) AS total FROM suppliers WHERE source_id=$source_id")->getRow();
        if ($check->total > 0) {
            session()->setFlashdata("op_error", "Supplier Source is used in Suppliers");
        } else {
            $query = "DELETE FROM supplier_sources WHERE source_id=$source_id";
            $this->db->query($query);
            $config['title'] = "Supplier Source Delete";
            if ($this->db->affectedRows() > 0) {
                $config['log_text'] = "[ Supplier Source successfully deleted ]";
                $config['ref_link'] = '';
                session()->setFlashdata("op_success", "Supplier Source successfully deleted");
            } else {
                $config['log_text'] = "[ Supplier Source failed to delete ]";
                $config['ref_link'] = '';
                session()->setFlashdata("op_error", "Supplier Source failed to delete");
            }
            log_activity($config);
        }
    }
}










