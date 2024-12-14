<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class TeamsModel extends Model
{
    protected $table      = 'teams';
    protected $primaryKey = 'team_id';

    protected $allowedFields = [
        'name',
        'description',
        'team_count',
        'lead_by',
        'created_at',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $session;
    protected $db;
    protected $attachmentModel;
    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function get_dtconfig_teams()
    {
        $config = array(
            "columnNames" => ["sno", "name", "team count", "lead by", "description", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function get_team_datatable()
    {
        $columns = array(
            "name" => "teams.name",
            "team count" => "team_count"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT team_id,teams.name AS team_name,team_count,teams.description,erp_users.name AS leadby FROM teams JOIN erp_users ON teams.lead_by=erp_users.user_id ";
        $count = "SELECT COUNT(team_id) AS total FROM teams JOIN erp_users ON teams.lead_by=erp_users.user_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE teams.name LIKE '%" . $search . "%' ";
                $count .= " WHERE teams.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND teams.name LIKE '%" . $search . "%' ";
                $count .= " AND teams.name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.project.teamview', $r['team_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.project.teamedit', $r['team_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.project.teamdelete', $r['team_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['team_id'],
                    $sno,
                    $r['team_name'],
                    $r['team_count'],
                    $r['leadby'],
                    $r['description'],
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

    public function insert_team($post)
    {
        $inserted = false;
        $name = $post["name"];
        $team_count = $post["team_count"];
        $lead_by = $post["lead_by"];
        $description = $post["description"];
        $created_by = get_user_id();

        $query = "INSERT INTO teams(name,team_count,lead_by,description,created_at,created_by) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $team_count, $lead_by, $description, time(), $created_by));
        $team_id = $this->db->insertId();
        $config['title'] = "Team Insert";
        if (!empty($team_id)) {
            $inserted = true;
            $config['log_text'] = "[ Team successfully created ]";
            $config['ref_link'] = url_to('erp.project.teamview', $team_id);
            $this->session->setFlashdata("op_success", "Team successfully created");
        } else {
            $config['log_text'] = "[ Team failed to create ]";
            $config['ref_link'] = url_to('erp.project.teams');
            $this->session->setFlashdata("op_error", "Team failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_team($team_id, $post)
    {
        $updated = false;
        $name = $post["name"];
        $team_count = $post["team_count"];
        $lead_by = $post["lead_by"];
        $description = $post["description"];

        $query = "UPDATE teams SET name=? , team_count=? , lead_by=? , description=? WHERE team_id=$team_id ";
        $this->db->query($query, array($name, $team_count, $lead_by, $description));

        $config['title'] = "Team Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Team successfully updated ]";
            $config['ref_link'] = url_to('erp.project.teamview', $team_id);
            $this->session->setFlashdata("op_success", "Team successfully updated");
        } else {
            $config['log_text'] = "[ Team failed to update ]";
            $config['ref_link'] = url_to('erp.project.teamview', $team_id);
            $this->session->setFlashdata("op_error", "Team failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_team_by_id($team_id)
    {
        $query = "SELECT team_id,teams.name AS team_name,team_count,teams.description,erp_users.name AS leadby,erp_users.user_id FROM teams JOIN erp_users ON teams.lead_by=erp_users.user_id WHERE team_id=$team_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }
    public function get_attachments($type, $related_id)
    {
        $query = "SELECT filename,attach_id FROM attachments WHERE related_to='$type' AND related_id=$related_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    public function get_team_export($type)
    {
        $columns = array(
            "name" => "teams.name",
            "team count" => "team_count"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT teams.name AS team_name,team_count,erp_users.name AS leadby,teams.description FROM teams JOIN erp_users ON teams.lead_by=erp_users.user_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE teams.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND teams.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function get_all_teams()
    {
        $query = "SELECT team_id,name FROM teams ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function delete_teams($team_id)
    {
        $query = "DELETE FROM teams WHERE team_id=$team_id";
        $this->db->query($query);
        $config['title'] = "Team Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Team successfully deleted ]";
            $config['ref_link'] = url_to('erp.project.teamview', $team_id);
            $this->session->setFlashdata("op_success", "Team successfully deleted");
        } else {
            $config['log_text'] = "[ Team failed to delete ]";
            $config['ref_link'] = url_to('erp.project.teamview', $team_id);
            $this->session->setFlashdata("op_error", "Team failed to delete");
        }
        log_activity($config);
    }

    public function teamsDelete($team_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('teams')
                ->where('team_id', $team_id)
                ->delete();

            $this->db->table('team_members')
                ->where('team_id', $team_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $team_id)
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("team", $attach_id);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            return $this->session->setFlashdata("op_success", "Team and related data deleted successfully");
        } catch (\Exception $e) {
            return $this->session->setFlashdata("op_error", "Error deleting Team and related data");
        }
    }


    public function upload_attachment($type, $id)
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
                $query = "INSERT INTO attachments (filename, related_to, related_id) VALUES (?, ?, ?)";
                if (empty($id)) {
                    $response['error'] = 1;
                    $response['reason'] = "Internal Error";
                } else {
                    $this->db->query($query, array($filename, $type, $id));
                    $attach_id = $this->db->insertId();
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
}
