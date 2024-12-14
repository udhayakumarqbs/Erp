<?php

namespace App\Controllers;

use App\Models\Erp\NotificationModel;
use App\Models\Erp\TimesheetModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\HTTP\Exceptions\RedirectException;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $notificationModel;
    protected $timesheetModel;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        if($this->session->has("erp_userid")){
            $this->notificationModel = new NotificationModel();
            $this->session->set("UserNotification", $this->notificationModel->getNotifyForUser($this->session->get("erp_userid")));
            $this->timesheetModel = new TimesheetModel();
            $this->session->set("UserTimers", $this->timesheetModel->find_running_timer($this->session->get("erp_userid")));
        }
    }
}
