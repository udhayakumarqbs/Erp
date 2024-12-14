<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Erp\CustomerContactsModel;

class DashboardController extends BaseController
{

    protected $CustomerContactsModel;
    protected $data;
    public function __construct()
    {
        $this->CustomerContactsModel = new CustomerContactsModel();
        helper(['erp', 'form']);
    }
    public function index()
    {
        $data['pageFolder'] = "dashboard/index";
        return view('client/index', $data);
    }
    public function profile($id)
    {
        $clientdetail = $this->CustomerContactsModel->where('contact_id', $id)->where('primary_contact', 1)->get()->getRowArray();
        // return var_dump($clientdetail);
        $data['clientdetail'] = $clientdetail;
        $data['pageFolder'] = "profile/profile";
        return view('client/index', $data);
    }
    public function profile_update()
    {

        // var_dump($this->request->getPost());
        // exit;
        $contact_id = $this->request->getPost("contact_id");

        $image = $this->request->getFile('profile_image');

        $data = [
            "firstname" => $this->request->getPost("firstname"),
            "lastname" => $this->request->getPost("lastname"),
            "position" => $this->request->getPost("position"),
            "email" => $this->request->getPost("email"),
            "phone" => $this->request->getPost("phone")
        ];

        if (!empty($image) && $image->isValid() && !$image->hasMoved()) {

            $name = $image->getName();

            $path = get_attachment_path("customer") . $name;

            if (file_exists($path)) {
                $this->session->setFlashdata("error", "Image is already exist");
                return $this->response->redirect(url_to('front.profile', $contact_id));
            }

            $exist = $this->CustomerContactsModel->where('contact_id', $contact_id)->get()->getRowArray();

            $exist_image = $exist["profile_image"];

            if (file_exists(get_attachment_path("customer") . $exist_image)) {
                unlink(get_attachment_path("customer") . $exist_image);
            }

            if ($image->move(FCPATH . 'uploads/customer', $name)) {
                $data["profile_image"] = $name;

                $result = $this->CustomerContactsModel->update($contact_id, $data);

                if ($result) {
                    $this->session->setFlashdata("success", "Profile updated Successfully");
                    return $this->response->redirect(url_to('front.profile', $contact_id));
                } else {
                    $this->session->setFlashdata("error", "Something went wrong !");
                    return $this->response->redirect(url_to('front.profile', $contact_id));
                }
            }
        } else {
            $result = $this->CustomerContactsModel->update($contact_id, $data);

            if ($result) {
                $this->session->setFlashdata("success", "Profile updated Successfully");
                return $this->response->redirect(url_to('front.profile', $contact_id));
            } else {
                $this->session->setFlashdata("error", "Something went wrong !");
                return $this->response->redirect(url_to('front.profile', $contact_id));
            }
        }

    }

    public function password_change($contact_id)
    {
        $exist_password = $this->CustomerContactsModel->where('contact_id', $contact_id)->get()->getRowArray();

        $old_password = $this->request->getPost("old_password");
        if (!password_verify($old_password, $exist_password['password'])) {
            $this->session->setFlashdata("error", "Enter correct old Password");
            return $this->response->redirect(url_to('front.profile', $contact_id));
        }

        $new_password = $this->request->getPost('new_password');
        $repeat_password = $this->request->getPost('repeat_password');

        if ($new_password == $repeat_password) {
            $data["password"] = password_hash($new_password, PASSWORD_DEFAULT);
            $data["password_updated_at"] = date('Y-m-d H:i:s');

            // var_dump($data);
            // exit;

            $result = $this->CustomerContactsModel->update($contact_id,$data);
            if ($result) {
                $this->session->setFlashdata("success", "Password Updated Successfully");
                return $this->response->redirect(url_to('front.profile', $contact_id));
            } else {
                $this->session->setFlashdata("error", "Error Occured !");
                return $this->response->redirect(url_to('front.profile', $contact_id));
            }
        } else {
            $this->session->setFlashdata("error", "Both New Passwords are Not same");
            return $this->response->redirect(url_to('front.profile', $contact_id));
        }

    }


}
