<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Search;

class AssetModel extends Model
{
    protected $table            = 'equipments';
    protected $primaryKey       = 'equip_id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'name',
        'code',
        'model',
        'maker',
        'bought_date',
        'age',
        'work_type',
        'consump_type',
        'consumption',
        'status',
        'description',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Data sets
    private $equip_status = array(
        0 => "Working",
        1 => "Under Service",
        2 => "Dump"
    );

    private $equip_status_bg = array(
        0 => "st_success",
        1 => "st_warning",
        2 => "st_danger"
    );

    public function get_equip_status()
    {
        return $this->equip_status;
    }

    public function get_equip_status_bg()
    {
        return $this->equip_status_bg;
    }

    public function get_dtconfig_equipments()
    {
        $config = array(
            "columnNames" => ["sno", "code", "name", "model", "maker", "bought date", "age", "status", "action"],
            "sortable" => [0, 1, 1, 0, 0, 1, 0, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }
    
    // Data for the data table
    public function getEquipmentDatatable($limit, $offset, $search, $orderby, $ordercolPost, $status)
    {
        $columns = [
            "code" => "code",
            "name" => "name",
            "bought_date" => "bought_date",
            "status" => "status"
        ];

        // $limit = $this->request->getGet('limit');
        // $offset = $this->request->getGet('offset');
        // $search = $this->request->getGet('search') ?? "";

        // // Handle 'orderby' possibly being null
        // $orderby = $this->request->getGet('orderby');
        // $orderby = $orderby !== null ? strtoupper($orderby) : "";

        $ordercol = $ordercolPost ? $columns[$ordercolPost] : "";

        $query = "SELECT equip_id, code, name, model, maker, bought_date, age, status FROM equipments ";
        $count = "SELECT COUNT(equip_id) AS total FROM equipments ";
        $where = false;

        // $filter_1_col = (!empty($status)) ? $columns['status'] : "";
        // //$filter_1_col = $columns['status'];
        // $filter_1_val = $status;

        // if ($filter_1_col !== "" && null !== $filter_1_val) {
        //     $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
        //     $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
        //     $where = true;
        // }

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
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
        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown_container">
                                <ul class="BB_UL flex">
                                    <li><a href="' . url_to('erp.equipment.view', $r['equip_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                    <li><a href="' . url_to('erp.equipment.edit', $r['equip_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                    <li><a href="' . url_to('erp.equipment.delete', $r['equip_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                </ul>
                            </div>
                        </div>';

            $status = "<span class='st " . $this->equip_status_bg[$r['status']] . "' >" . $this->equip_status[$r['status']] . "</span>";

            $m_result[] = [
                $r['equip_id'],
                $sno,
                $r['code'],
                $r['name'],
                $r['model'],
                $r['maker'],
                $r['bought_date'],
                $r['age'],
                $status,
                $action
            ];

            $sno++;
        }

        $response = [
            'total_rows' => $this->db->query($count)->getRow()->total,
            'data' => $m_result
        ];

        return $response;
    }
    // 
    public function get_equipment_by_id($equip_id)
    {
        return $this->builder()->select()->where('equip_id', $equip_id)->get()->getRow();
    }

    public function get_attachments($type, $related_id)
    {
        $query = $this->db->table('attachments')
            ->select('filename, attach_id')
            ->where('related_to', $type)
            ->where('related_id', $related_id)
            ->get();

        return $query->getResultArray();
    }

    public function insertEquipment($data)
    {
        $inserted = false;

        $this->insert($data);
        $equipId = $this->insertID();

        $config = ['title' => 'Equipment Insert'];

        if (!empty($equipId)) {
            $inserted = true;
            $config['log_text'] = "[ Equipment successfully created ]";
            $config['ref_link'] = 'erp/assets/add_equipment/' . $equipId;
            session()->setFlashdata("op_success", "Equipment successfully created");
        } else {
            $config['log_text'] = "[ Equipment failed to create ]";
            $config['ref_link'] = 'erp/assets/add_equipment';
            session()->setFlashdata("op_error", "Equipment failed to create");
        }

        log_activity($config);
        return $inserted;
    }


    public function upload_attachment($type, $file, $id)
    {
        $path = get_attachment_path($type);
        // $file = $this->request->getFile('attachment');
        $ext = $file->getClientExtension();
        $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $file->getName());
        $filepath = $path . $filename;

        $response = [];

        if (file_exists($filepath)) {
            $response['error'] = 1;
            $response['reason'] = "File already exists";
        } else {
            if ($file->move($path, $filename)) {
                $query = "INSERT INTO attachments(filename, related_to, related_id) VALUES(?,?,?) ";
                // $id = $this->request->getGet('id') ?? 0;

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

    public function delete_attachment($type, $attach_id)
    {
        $response = [];

        $filename = $this->db->query("SELECT filename FROM attachments WHERE attach_id = ?", [$attach_id])->getRowArray();

        if (!empty($filename)) {
            $filepath = get_attachment_path($type) . $filename['filename'];

            $this->db->query("DELETE FROM attachments WHERE attach_id = ?", [$attach_id]);

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

        return $response;
    }

    // Edit / Update
    public function updateEquipment($equip_id, $data)
    {
        $updated = false;

        $this->builder()->set($data);

        $config['title'] = "Equipment Update";
        if ($this->builder()->where('equip_id', $equip_id)->update()) {
            $updated = true;
            $config['log_text'] = "[ Equipment successfully updated ]";
            $config['ref_link'] = 'erp/asset/edit_equipment/' . $equip_id;
            session()->setFlashdata("op_success", "Equipment successfully updated");
        } else {
            $config['log_text'] = "[ Equipment failed to update ]";
            $config['ref_link'] = 'erp/asset/edit_equipment/' . $equip_id;
            session()->setFlashdata("op_error", "Equipment failed to update");
        }

        log_activity($config);

        return $updated;
    }

    // Delete
    public function delete_equipment($equip_id)
    {
        $deleted = false;

        // Retrieve equipment details before deletion for logging purposes
        $equipment = $this->get_equipment_by_id($equip_id);

        // Delete the equipment
        $this->builder()->where('equip_id', $equip_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;

            // Log the deletion
            $config = [
                'title' => 'Equipment Deletion',
                'log_text' => "[ Equipment successfully deleted ]",
                'ref_link' => 'erp/assets/delete_equipment/' . $equip_id,
                'additional_info' => json_encode($equipment), // Log equipment details before deletion
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Equipment Deletion',
                'log_text' => "[ Equipment failed to delete ]",
                'ref_link' => 'erp/assets/delete_equipment/' . $equip_id,
            ];
            log_activity($config);
        }

        return $deleted;
    }

    // export
    public function get_equipment_export($type, $limit, $offset, $search, $orderby, $ordercolPost, $status)
    {
        $columns = [
            "code" => "code",
            "name" => "name",
            "bought_date" => "bought_date",
            "status" => "status"
        ];

        // $limit = $this->request->getGet('limit');
        // $offset = $this->request->getGet('offset');
        // $search = $this->request->getGet('search') ?? "";
        // $orderby = strtoupper($this->request->getGet('orderby')) ?? "";
        $ordercol = $ordercolPost ? $columns[$ordercolPost] : "";

        $query = "";

        if ($type == "pdf") {
            $query = "SELECT code, name, model, maker, bought_date, age,
                CASE ";
            foreach ($this->equip_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status FROM equipments ";
        } else {
            $query = "SELECT code, name, model, maker, bought_date, age,
                CASE ";
            foreach ($this->equip_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status, work_type, consump_type, consumption, description FROM equipments ";
        }

        $where = false;

        // $filter_1_col = $status ? $columns['status'] : "";
        // $filter_1_val = $status;

        // if ($filter_1_col !== "" && null !== $filter_1_val) {
        //     $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
        //     $where = true;
        // }

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            //$count.=" WHERE ".$filter_1_col."=".$filter_1_val." ";
            $where = true;
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

        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
