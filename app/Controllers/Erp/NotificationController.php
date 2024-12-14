<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Job\Notify;
use App\Libraries\Scheduler;
use App\Models\Erp\NotificationModel;
use DateTime;

class NotificationController extends BaseController
{
    protected $NotifyModel;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->NotifyModel = new NotificationModel;
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper('erp');
    }
    
    public function index()
    {
        $user_id = get_user_id();
        $data['notifications'] = $this->NotifyModel->getNotifyForUser($user_id);
        $data['page'] = "notification/notification_center";
        $data['menu'] = "notification";
        $data['submenu'] = "notification";
        return view("erp/index", $data);
    }

    public function getNotifyByUser()
    {
        $loggedIn_user = get_user_id();
        get_username_by_id(1);
        if (!empty($loggedIn_user)) {
            $result = $this->NotifyModel->getNotifyForUser($loggedIn_user);

            // var_dump($result);
            // exit();
            if (!empty($result)) {
                foreach ($result as $item) {
                    // var_dump($item);
                    // exit();
                    $notifyItem['title'] = $item['title'];
                    $notifyItem['created_by'] = get_username_by_id($item['created_by']);
                    $notifyItem['created_at'] = date("Y-m-d H:i", $item['created_at']);

                    // Add each notification item to the main array
                    $notify[] = $notifyItem;
                }
            }
        }

        // var_dump($notify);
        header('Content-Type: application/json');
        return json_encode($notify);
    }



}
