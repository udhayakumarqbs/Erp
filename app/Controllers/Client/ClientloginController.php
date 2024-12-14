<?php 


namespace   App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Erp\CustomerContactsModel;


class ClientloginController extends BaseController{

    protected $CustomerContactsModel;
    protected $logger;
    public function __construct(){
        helper(['form','erp','Cookie']);
        $this->CustomerContactsModel = new CustomerContactsModel();
        $this->logger = \Config\Services::logger();
    }

    public function index(){
        if(session()->has("contact_id")){
            return redirect()->to('erp/client/dashboard');
        }

        return view('client/login');
    }

    public function Authenticate_client(){
        $session = \Config\Services::session();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rememberMe = $this->request->getPost('rememberMe') ? true : false ;

        $validation = \Config\Services::validation();

        $validation->setRules([
            "email" => "required|valid_email",
            "password" => "required"
        ]);

        if(!$validation->withRequest($this->request)->run()){
            $session->setFlashdata("error","Invalid email ID");
            return redirect()->to('client/login')->withInput()->with('validation',$validation);
        }

        $contact = $this->CustomerContactsModel->get_primary_contact($email,$password,$rememberMe);
  
        if(!empty($contact["success"])){
            return redirect()->to('erp/client/dashboard');
        }else{
            $this->session->setFlashdata('op_error',$contact['error']);
            return redirect()->to('erp/auth/login');
        }
    }

    public function Logout(){
        $session = \Config\Services::session();

        $config['title'] = "Logout";
        $config['log_text'] = "[ Client successfully logout ]";
        $config['ref_link'] = "";
        log_activity($config);
        set_cookie('rhash','',time() - 7 * 24 * 60 * 60);
        $session->destroy();
        return $this->response->redirect(url_to('front.login'));
    }
}

?>