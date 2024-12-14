<?php
namespace App\Libraries\Requests;
use App\Libraries\Requests\AbstractRequest;
    //defined('BASEPATH') or exit('No direct script access allowed');

    class DummyRequest extends AbstractRequest{

        public function __construct($attachments=false){
            parent::__construct();
            $this->attachments=$attachments;
            $this->from_m="Dummy";
            $this->to_m="Dummy";
            $this->purpose="Dummy";
        }

        public function createRequest(){
            return false;
        }
    }
?>