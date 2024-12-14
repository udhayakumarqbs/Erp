<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SupplierLocationModel extends Model
{
    protected $table = 'supplier_locations';
    protected $primaryKey = 'location_id';
    protected $allowedFields = [
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'supplier_id',
        'created_at',
        'created_by',
    ];

    public function insert_update_supplier_location($supplier_id, $location_id, $post)
    {
        // $location_id = $this->request->getPost("location_id") ?? 0;
        if (empty($location_id)) {
            $this->insert_supplier_location($supplier_id, $post);
        } else {
            $this->update_supplier_location($supplier_id, $location_id, $post);
        }
    }


    public function locationDelete($location_id, $supplier_id)
    {
        if (!empty($supplier_id)) {
            $this->where('location_id', $location_id)->delete();
        }
    }

    private function insert_supplier_location($supplier_id, $post)
    {
        $session = \Config\Services::session();
        // $address = $this->request->getPost("address");
        // $city = $this->request->getPost("city");
        // $state = $this->request->getPost("state");
        // $country = $this->request->getPost("country");
        // $zip = $this->request->getPost("zip");
        $created_by = get_user_id();

        $data = [
            'address' => $post['address'],
            'city' => $post['city'],
            'state' => $post['state'],
            'country' => $post['country'],
            'zipcode' => $post['zip'],
            'supplier_id' => $supplier_id,
            'created_at' => time(),
            'created_by' => $created_by,
        ];

        $this->db->table('supplier_locations')->insert($data);
        $location_id = $this->db->insertID();

        $config = [
            'title' => "Supplier Location Insert",
            'ref_link' => 'erp/supplier/supplier-view/' . $supplier_id,
        ];

        if (!empty($location_id)) {
            $config['log_text'] = "[ Supplier Location successfully created ]";
            $session->setFlashdata("op_success", "Supplier Location successfully created");
        } else {
            $config['log_text'] = "[ Supplier Location failed to create ]";
            $session->setFlashdata("op_error", "Supplier Location failed to create");
        }

        log_activity($config);
    }

    private function update_supplier_location($supplier_id, $location_id, $post)
    {
        $session = \Config\Services::session();
        // $address = $this->request->getPost("address");
        // $city = $this->request->getPost("city");
        // $state = $this->request->getPost("state");
        // $country = $this->request->getPost("country");
        // $zip = $this->request->getPost("zip");

        $data = [
            'address' => $post['address'],
            'city' => $post['city'],
            'state' => $post['state'],
            'country' => $post['country'],
            'zipcode' => $post['zip'],
            'supplier_id' => $supplier_id,
        ];

        $this->db->table('supplier_locations')->where('location_id', $location_id)->update($data);

        $config = [
            'title' => "Supplier Location Update",
            'ref_link' => 'erp/supplier/supplier-view/' . $supplier_id,
        ];

        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Supplier Location successfully updated ]";
            $session->setFlashdata("op_success", "Supplier Location successfully updated");
        } else {
            $config['log_text'] = "[ Supplier Location failed to update ]";
            $session->setFlashdata("op_error", "Supplier Location failed to update");
        }

        log_activity($config);
    }


    public function get_supplierlocation_datatable($supplier_id)
    {
        // $supplier_id = $this->input->get("supplierid");
        $columns = array(
            "city" => "city",
            "state" => "state",
            "country" => "country"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT location_id,address,city,state,country,zipcode FROM supplier_locations WHERE supplier_id=$supplier_id ";
        $count = "SELECT COUNT(location_id) AS total FROM supplier_locations WHERE supplier_id=$supplier_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
                $count .= " AND ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/supplier/ajax-fetch-location/' . $r['location_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/location-delete/' . $supplier_id . '/' . $r['location_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['location_id'],
                    $sno,
                    $r['address'],
                    $r['city'],
                    $r['state'],
                    $r['country'],
                    $r['zipcode'],
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

    public function get_supplier_location_export($type, $supplier_id)
    {
        // $supplier_id = $this->request->getGet("supplierid");
        $columns = [
            "city" => "city",
            "state" => "state",
            "country" => "country"
        ];
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $builder = $this->db->table('supplier_locations');
        $builder->select('address, city, state, country, zipcode');
        $builder->where('supplier_id', $supplier_id);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('city', $search)
                ->orLike('state', $search)
                ->orLike('country', $search)
                ->groupEnd();
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $builder->orderBy($ordercol, $orderby);
        }

        $builder->limit($limit, $offset);
        $result = $builder->get()->getResultArray();

        return $result;
    }
}
