<?php

namespace App\Libraries\Mailer;

use CodeIgniter\Email\Email;
use App\Libraries\Mailer\Mailer;

class Code_mailer extends Mailer
{
    private $configs;
    private $tos;
    private $ccs;
    private $bccs;
    private $email;
    private $from;
    private $fromname = '';

    public function __construct()
    {
        parent::__construct();

        $this->email = new Email();
        $this->config();
        $this->configs = [];
        $this->tos = [];
        $this->ccs = [];
        $this->bccs = [];
        $this->constructBCC();
        $this->constructCC();
    }

    protected function config()
    {
        $this->configs['SMTPUser'] = $this->settings['smtp_username'];
        $this->configs['SMTPPass'] = $this->settings['smtp_password'];
        $this->configs['SMTPHost'] = $this->settings['smtp_host'];
        $this->configs['SMTPPort'] = $this->settings['smtp_port'];
        $this->configs['protocol'] = 'smtp';
        $this->configs['wordWrap'] = true;
        $this->configs['charset'] = 'iso-8859-1';
        $this->configs['mailType'] = 'html';

        if ($this->settings['email_encryption'] == 'tls') {
            $this->configs['SMTPCrypto'] = 'tls';
        } elseif ($this->settings['email_encryption'] == 'ssl') {
            $this->configs['SMTPCrypto'] = 'ssl';
        }

        $this->email->initialize($this->configs);

        parent::config();
    }

    public function addTo($email, $name = '')
    {
        $this->tos[] = [
            'email' => $email,
            'name' => $name,
        ];
    }

    public function addCc($email, $name = '')
    {
        $this->ccs[] = [
            'email' => $email,
            'name' => $name,
        ];
    }

    public function addBcc($email, $name = '')
    {
        $this->bccs[] = [
            'email' => $email,
            'name' => $name,
        ];
    }

    public function addFrom($email, $name)
    {
        $this->email->setFrom($email, $name);
        $this->from = $email;
        $this->fromname = $name;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getFromName()
    {
        return $this->fromname;
    }

    public function addReplyto($email, $name = '')
    {
        $this->email->setReplyTo($email, $name);
    }

    public function attachFileAsString($str, $filename, $contenttype)
    {
        $this->email->attach($str, 'inline', $filename, $contenttype);
    }

    public function attachFile($filepath, $filename, $contenttype)
    {
        $this->email->attach($filepath, 'attachment', $filename, $contenttype);
    }

    public function clearAll($attach = true)
    {
        if ($attach) {
            $this->email->attach('');
        }

        $this->tos = [];
        $this->ccs = [];
        $this->bccs = [];

        // Reconstructing
        $this->constructBCC();
        $this->constructCC();
    }

    public function addSubject($subject)
    {
        $this->email->setSubject($subject);
    }

    public function addBody($body)
    {
        $this->email->setMessage($body);
    }

    public function send()
    {
        foreach ($this->tos as $to) {
            $this->email->setTo($to['email'], $to['name']);
        }

        foreach ($this->ccs as $cc) {
            $this->email->setCC($cc['email'], $cc['name']);
        }

        foreach ($this->bccs as $bcc) {
            $this->email->setBCC($bcc['email'], $bcc['name']);
        }

        return $this->email->send();
    }


    public function printDebugger()
    {
        return $this->email->printDebugger();
    }
}
