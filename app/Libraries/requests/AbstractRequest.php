<?php

namespace App\Libraries\Requests;

// defined('BASEPATH') or exit('No direct access allowed');

class AbstractRequest
{
    protected $ci;
    protected $attachments;
    protected $from_m;
    protected $to_m;
    protected $purpose;
    private $allowed_files;

    const PENDING = 0;
    const READ = 1;
    const PROCESSING = 2;
    const RESPONDED = 3;
    const CLOSED = 4;

    // each file
    private $max_file_size;

    public function __construct()
    {
        $this->ci = service('request');
        $this->allowed_files = [
            'csv' => 'text/csv',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'gif' => 'image/gif',
            'jpg-jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
        // 5Mb
        $this->max_file_size = 5 * 1024 * 1024;
        $this->attachments = false;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    public function getAllowedFiles()
    {
        return array_keys($this->allowed_files);
    }

    public function getAllowedFileTypes()
    {
        return array_values($this->allowed_files);
    }

    public function getMaxFileSize()
    {
        return $this->max_file_size;
    }

    protected function createRequest()
    {
        $request_created = false;
        $files = [];
        $path = get_attachment_path('request');
        if ($this->attachments) {
            $files = $this->handleAttachments();
            if (empty($files)) {
                session()->setFlashdata('op_error', 'File uploading error');
                return $request_created;
            } else {
                $description = $this->ci->getVar('description');
                $related_to = $this->ci->getVar('related_to');
                $related_id = $this->ci->getVar('related_id');
                $mail_request = $this->ci->getVar('mail_request') ?? 0;
                $requested_by = get_user_id();
                $this->ci->db->transBegin();
                $query = "INSERT INTO request(from_m, to_m, purpose, related_to, related_id, description, mail_request, status, requested_at, requested_by) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->ci->db->query($query, [$this->from_m, $this->to_m, $this->purpose, $related_to, $related_id, $description, $mail_request, self::PENDING, time(), $requested_by]);
                $request_id = $this->ci->db->insertID();
                if (empty($request_id)) {
                    session()->setFlashdata('op_error', 'Request Failed to create');
                    foreach ($files as $file) {
                        unlink($path . $file);
                    }
                    return $request_created;
                } else {
                    $query = "INSERT INTO attachments(filename, related_to, related_id) VALUES(?, ?, ?)";
                    $op_broken = false;
                    foreach ($files as $file) {
                        $this->ci->db->query($query, [$file, 'request', $request_id]);
                        if (empty($this->ci->db->insertID())) {
                            $op_broken = true;
                            break;
                        }
                    }
                    if (!$op_broken) {
                        $this->ci->db->transComplete();
                        if ($this->ci->db->transStatus() === false) {
                            $this->ci->db->transRollback();
                            foreach ($files as $file) {
                                unlink($path . $file);
                            }
                        } else {
                            $request_created = true;
                            $this->ci->db->transCommit();
                        }
                    } else {
                        $this->ci->db->transRollback();
                        foreach ($files as $file) {
                            unlink($path . $file);
                        }
                    }
                }
            }
        }
        return $request_created;
    }

    protected function handleAttachments()
    {
        $uploaded_files = [];
        $path = get_attachment_path('request');
        if (!empty($_FILES['attachments']['name'])) {
            if (is_array($_FILES['attachments']['name'])) {
                $len = count($_FILES['attachments']['name']);
                for ($i = 0; $i < $len; $i++) {
                    $type = $_FILES['attachments']['type'][$i];
                    $error = $_FILES['attachments']['error'][$i];
                    $size = $_FILES['attachments']['size'][$i];
                    if ($error == 0 && in_array($type, array_values($this->allowed_files)) && $size <= $this->max_file_size) {
                        $file_name = preg_replace('/[\/:"*?<>| ]+/i', '', $_FILES['attachments']['name'][$i]);
                        $filename = $path . $file_name;
                        if (move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $filename)) {
                            array_push($uploaded_files, $file_name);
                        }
                    }
                }
            } else {
                $type = $_FILES['attachments']['type'];
                $error = $_FILES['attachments']['error'];
                $size = $_FILES['attachments']['size'];
                if ($error == 0 && in_array($type, array_values($this->allowed_files)) && $size <= $this->max_file_size) {
                    $file_name = preg_replace('/[-\/:"*?<>| ]+/i', '', $_FILES['attachments']['name']);
                    $filename = $path . $file_name;
                    if (move_uploaded_file($_FILES['attachments']['tmp_name'], $filename)) {
                        array_push($uploaded_files, $file_name);
                    }
                }
            }
        }
        return $uploaded_files;
    }
}
