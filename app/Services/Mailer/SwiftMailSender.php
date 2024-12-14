<?php

namespace App\Services\Mailer;

use App\Models\Erp\ErpSettingsModel;

use Exception;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

include_once(APPPATH . "ThirdParty/SwiftMailer/vendor/autoload.php");

class SwiftMailSender extends MailSender
{

    private $mailer;
    private $message;

    public function __construct()
    {
        $settingModel = new ErpSettingsModel();
        $emailSettings = $settingModel->getEmail();
        $transport = new Swift_SmtpTransport('smtp.gmail.com', '587');
        $transport->setUsername('support@qbrainstorm.com')->setPassword('QBSSupport4cus@2023#1234');
        if ($emailSettings['email_encryption'] != "none") {
            $transport->setEncryption($emailSettings['email_encryption']);
        }
        $this->mailer = new Swift_Mailer($transport);
        $this->message = new Swift_Message();
        $this->message->setFrom($emailSettings['smtp_username']);
    }

    public function setFrom($email, $name = "")
    {
        $this->message->setFrom($email, $name);
        return $this;
    }

    public function getFrom(){
        $from = array_keys($this->message->getFrom())[0];
        return $from;
    }

    public function addTo($email, $name = "")
    {
        $this->message->addTo($email, $name);
        return $this;
    }

    public function addBcc($email, $name = "")
    {
        $this->message->addBcc($email, $name);
        return $this;
    }

    public function addCc($email, $name = "")
    {
        $this->message->addCc($email, $name);
        return $this;
    }

    public function addReplyTo($email, $name = "")
    {
        $this->message->addReplyTo($email, $name);
        return $this;
    }

    public function addFileAttachment($path, $contentType = null)
    {
        $attachment = Swift_Attachment::fromPath($path, $contentType);
        $this->message->attach($attachment);
        return $this;
    }

    public function addStringAttachment($buffer, $filename, $contentType = null)
    {
        $attachment = new Swift_Attachment($buffer, $filename, $contentType);
        $this->message->attach($attachment);
        return $this;
    }

    public function setSubject($subject)
    {
        $this->message->setSubject($subject);
        return $this;
    }

    public function setBody($body, $contentType = "text/html")
    {
        $this->message->setBody($body,$contentType);
        return $this;
    }

    public function send()
    {
        $sent = false;
        try {
            if (!empty($this->mailer->send($this->message))) {
                $sent = true;
            }
        } catch (Exception $ex) {
            #attribute inherited from trait
            $this->lastError = $ex->getMessage();
        } finally {
            $this->message = new Swift_Message();
        }
        return $sent;
    }
}
