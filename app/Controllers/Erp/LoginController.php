<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\UserModel;
use Config\Cookie;

class LoginController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'erp', 'Cookie']);
    }
    public function index()
    {
        if (session()->has("erp_userid")) {
            return $this->response->redirect(url_to('erp.dashboard'));
        }
        return view('erp/login');
    }

    public function Authenticate()
    {
        // Retrieve input data
        $session = \Config\Services::session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rememberMe = $this->request->getPost('remember_me') ? true : false;

        // Validate input data (you may want to add more validation)
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Validation failed, return to login with errors
            $session->setFlashdata("error", "Invalid email ID");
            return redirect()->to('erp/login')->withInput()->with('validation', $validation);
        }

        // Authenticate user
        $userModel = new UserModel(); // Make sure you have a UserModel
        $authenticationResult = $userModel->authenticate($email, $password, $rememberMe);

        // Check the result and handle accordingly
        if (!empty($authenticationResult['success'])) {
            return redirect()->to('erp/dashboard');
            // Authentication successful, redirect to the dashboard or home page
        } else {
            // Authentication failed, redirect back to login with an error message
            return redirect()->to('erp/login')->withInput()->with('error', $authenticationResult['error']);
        }
        // return view('erp.login');
    }

    public function Logout()
    {
        $session = \Config\Services::session();
        $config['title'] = "Logout";
        $config['log_text'] = "[ User successfully logout ]";
        $config['ref_link'] = "";
        log_activity($config);
        set_cookie("rhash", "", time() - 7 * 24 * 60 * 60);
        $session->destroy();
        return $this->response->redirect(url_to("erp.login"));
    }

    public function ForgotPassword()
    {
        return view('erp/forgotpassword');
    }
}
