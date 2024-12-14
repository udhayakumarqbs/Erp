<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class AttachmentModel extends Model
{
    protected $table = 'attachments';
    protected $primaryKey = 'attach_id';
    protected $allowedFields = ['filename', 'related_to', 'related_id'];


    public function get_attachments($type, $related_id)
    {
        $builder = $this->db->table('attachments');
        $builder->select('filename, attach_id');
        $builder->where('related_to', $type);
        $builder->where('related_id', $related_id);
        $result = $builder->get()->getResultArray();

        return $result;
    }
    public function get_attachments_by_id($type, $related_id)
    {
        $builder = $this->db->table('attachments');
        $builder->select('filename, attach_id');
        $builder->where('related_to', $type);
        $builder->where('related_id', $related_id);
        $result = $builder->get()->getRow();

        return $result;
    }
    //this is that function
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

    public function delete_attachment($type, $attach_id)
    {
        $response = [];
        if (!empty($attach_id)) {
            $filename = $this->db->query("SELECT filename FROM attachments WHERE attach_id = $attach_id")->getRow();
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
