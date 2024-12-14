<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Erp\CustomerContactsModel;


class ClFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {   
        if (empty(session('client_cust_id')) || empty(session('contact_id'))) {
            return view('client/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}