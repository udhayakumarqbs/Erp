<?php

namespace App\Libraries\Mailer;

use App\Models\Erp\ErpSettingsModel;

abstract class Mailer
{

    protected $mailer;
    protected $settings;
    protected $ErpSettingsModel;

    public function __construct()
    {
        $this->ErpSettingsModel = new ErpSettingsModel();
        $this->settings = $this->ErpSettingsModel->get_smtp_settings();
    }

    protected function config()
    {
        $this->addFrom($this->settings['smtp_username'], $this->settings['company_name']);
    }

    protected function constructBCC()
    {
        $bccs = $this->settings['bcc_list'];
        if (!empty($bccs)) {
            $bccs = explode(",", $bccs);
            foreach ($bccs as $bcc) {
                $this->addBcc($bcc);
            }
        }
    }

    protected function constructCC()
    {
        $ccs = $this->settings['cc_list'];
        if (!empty($ccs)) {
            $ccs = explode(",", $ccs);
            foreach ($ccs as $cc) {
                $this->addCc($cc);
            }
        }
    }

    public abstract function addTo($email, $name = "");

    public abstract function addCc($email, $name = "");

    public abstract function addBcc($email, $name = "");

    public abstract function addFrom($email, $name);

    public abstract function addReplyto($email, $name = "");

    public abstract function attachFileAsString($str, $filename, $contenttype);

    public abstract function attachFile($filepath, $filename, $contenttype);

    public abstract function clearAll($attach = true);

    public abstract function addSubject($subject);

    public abstract function addBody($body);

    public abstract function send();

    public abstract function getFrom();

    public abstract function getFromName();

    public abstract function printDebugger();
}
