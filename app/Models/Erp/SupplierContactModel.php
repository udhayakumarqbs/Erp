<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Offset;

class SupplierContactModel extends Model
{
    protected $table = 'supplier_contacts';
    protected $primaryKey = 'contact_id';
    protected $allowedFields = ['firstname', 'lastname', 'email', 'phone', 'active', 'position', 'supplier_id', 'created_at', 'created_by'];


    public function insert_update_supplier_contact($supplier_id, $contact_id, $data, $data1)
    {
        if (!empty($contact_id)) {
            $this->update_supplier_contact($supplier_id, $contact_id, $data);
        } else {
            $this->insert_supplier_contact($supplier_id, $data1);
        }
    }

    public function contentDelete($contact_id,$supplier_id)
    {
        if (!empty($supplier_id)) {
            $this->where('contact_id', $contact_id)->delete();
        }
    }

    public function update_supplier_contact($supplier_id, $contact_id, $data)
    {

        $session = \Config\Services::session();
        $this->db->table('supplier_contacts')->where('contact_id', $contact_id)->update($data);

        $config['title'] = "Supplier Contact Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Supplier Contact successfully updated ]";
            $config['ref_link'] = 'erp/supplier/supplier-view/' . $supplier_id;
            $session->setFlashdata("op_success", "Supplier Contact successfully updated");
        } else {
            $config['log_text'] = "[ Supplier Contact failed to update ]";
            $config['ref_link'] = 'erp/supplier/supplier-view/' . $supplier_id;
            $session->setFlashdata("op_error", "Supplier Contact failed to update");
        }
        log_activity($config);
    }

    public function insert_supplier_contact($supplier_id, $data1)
    {
        $session = \Config\Services::session();
        $this->db->table('supplier_contacts')->insert($data1);
        $contact_id = $this->db->insertID();

        $config['title'] = "Supplier Contact Insert";
        if (!empty($contact_id)) {
            $config['log_text'] = "[ Supplier Contact successfully created ]";
            $config['ref_link'] = 'erp/supplier/supplier-view/' . $supplier_id;
            $session->setFlashdata("op_success", "Supplier Contact successfully created");
        } else {
            $config['log_text'] = "[ Supplier Contact failed to create ]";
            $config['ref_link'] = 'erp/supplier/supplier-view/' . $supplier_id;
            $session->setFlashdata("op_error", "Supplier Contact failed to create");
        }
        log_activity($config);
    }

    public function get_suppliercontact_datatable($supplier_id)
    {
        // $supplier_id = $this->input->get("supplierid");
        $columns = array(
            "name" => "firstname",
            "email" => "email"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT contact_id,firstname,lastname,email,phone,position,active FROM supplier_contacts WHERE supplier_id=$supplier_id";
        $count = "SELECT COUNT(contact_id) AS total FROM supplier_contacts WHERE supplier_id=$supplier_id";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " AND ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/supplier/ajax-fetch-contact/' . $r['contact_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/contact-delete/' . $supplier_id . '/' . $r['contact_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . base_url() . 'erp/supplier/contact-active/' . $r['contact_id'] . '" >
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
                    $r['contact_id'],
                    $sno,
                    $r['firstname'] . ' ' . $r['lastname'],
                    $r['email'],
                    $r['position'],
                    $r['phone'],
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






    // public function get_supplier_contacts_export($type, $supplier_id, $limit, $offset, $search, $orderby, $ordercol)
    // {
    //     // $supplier_id = $this->request->getGet("supplierid");
    //     $columns = [
    //         "name" => "firstname",
    //         "email" => "email"
    //     ];

    //     // $limit = $this->request->getGet('limit');
    //     // $offset = $this->request->getGet('offset');
    //     // $search = $this->request->getGet('search') ?? '';
    //     // $orderby = strtoupper($this->request->getGet('orderby')) ?? '';
    //     // $ordercol = $columns[$this->request->getGet('ordercol')] ?? '';

    //     $query = "SELECT firstname,lastname,email,phone,position,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM supplier_contacts WHERE supplier_id=$supplier_id";
    //     $where = true;

    //     if (!empty($search)) {
    //         if (!$where) {
    //             $query .= " WHERE ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
    //             $where = true;
    //         } else {
    //             $query .= " AND ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
    //         }
    //     }

    //     if (!empty($ordercol) && !empty($orderby)) {
    //         $query .= " ORDER BY " . $ordercol . " " . $orderby;
    //     }

    //     $result = $this->db->query($query)->getResultArray();
    //     return $result;
    // }


    public function get_supplier_contacts_export($type, $supplier_id)
    {
        // $supplier_id = $this->request->getGet("supplierid");
        $columns = [
            "name" => "firstname",
            "email" => "email"
        ];
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT firstname,lastname,email,phone,position,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM supplier_contacts WHERE supplier_id=$supplier_id";
            $where = true;
    
            if (!empty($search)) {
                if (!$where) {
                    $query .= " WHERE ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                    $where = true;
                } else {
                    $query .= " AND ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                }
            }
    
            if (!empty($ordercol) && !empty($orderby)) {
                $query .= " ORDER BY " . $ordercol . " " . $orderby;
            }
    
            $result = $this->db->query($query)->getResultArray();
            return $result;
    }
}
