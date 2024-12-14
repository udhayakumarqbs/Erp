<?php

namespace App\Libraries\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Libraries\Mailer\Mailer;

class Php_mailer extends Mailer
{

    public function __construct()
    {
        parent::__construct();
        $this->mailer = new PHPMailer(true);

        $this->config();
        $this->constructBCC();
        $this->constructCC();
    }

    protected function config()
    {
        $this->mailer->Username = $this->settings['smtp_username'];
        $this->mailer->Password = $this->settings['smtp_password'];
        $this->mailer->isHTML(true);
        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug = 0;
        $this->mailer->Host = $this->settings['smtp_host'];
        $this->mailer->Port = $this->settings['smtp_port'];
        $this->mailer->SMTPAuth = true;
        if ($this->settings['email_encryption'] == "tls") {
            $this->mailer->SMTPSecure = "tls";
            $this->mailer->SMTPOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            );
        } else if ($this->settings['email_encryption'] == "ssl") {
            $this->mailer->SMTPSecure = "ssl";
        }
        parent::config();
    }

    public function addTo($email, $name = "")
    {
        $this->mailer->addAddress($email, $name);
    }

    public function addCc($email, $name = "")
    {
        $this->mailer->addCC($email, $name);
    }

    public function addBcc($email, $name = "")
    {
        $this->mailer->addBCC($email, $name);
    }

    public function addFrom($email, $name)
    {
        $this->mailer->setFrom($email, $name);
    }

    public function getFrom()
    {
        return $this->mailer->From;
    }

    public function getFromName()
    {
        return $this->mailer->FromName;
    }

    public function addReplyto($email, $name = "")
    {
        $this->mailer->addReplyTo($email, $name);
    }

    public function attachFileAsString($str, $filename, $contenttype)
    {
        $this->mailer->addStringAttachment($str, $filename);
    }

    public function attachFile($filepath, $filename, $contenttype)
    {
        $this->mailer->addAttachment($filepath, $filename);
    }

    public function clearAll($attach = true)
    {
        if ($attach) {
            $this->mailer->clearAttachments();
        }
        $this->mailer->clearAddresses();
        $this->mailer->clearBCCs();
        $this->mailer->clearCCs();
        $this->mailer->clearReplyTos();
        //Reconstructing
        $this->constructBCC();
        $this->constructCC();
    }

    public function addSubject($subject)
    {
        $this->mailer->Subject = $subject;
    }

    public function addBody($body)
    {
        $this->mailer->Body = $body;
    }

    public function send()
    {
        $send = false;
        try {
            $this->mailer->send();
            $send = true;
        } catch (Exception $ex) {
            print_r($ex);
            $send = false;
        }
        return $send;
    }


    public function printDebugger($include = ['headers', 'subject', 'body'])
    {
        // Get the raw debug information from PHPMailer
        $this->mailer->SMTPDebug = 2;
        if (!is_array($this->mailer->ErrorInfo)) {
            $msg = $this->mailer->ErrorInfo;
        } else {
            $msg = implode('', $this->mailer->ErrorInfo);
        }

        // Initialize rawData to store parts of the email
        $rawData = '';

        // Check if $include is an array, if not, convert to array
        if (!is_array($include)) {
            $include = [$include];
        }

        // Check if headers should be included in the output
        if (in_array('headers', $include, true)) {
            $rawData .= htmlspecialchars($this->mailer->getSentMIMEMessage()) . "\n";
        }

        // Check if subject should be included in the output
        if (in_array('subject', $include, true)) {
            $rawData .= htmlspecialchars($this->mailer->Subject) . "\n";
        }

        // Check if body should be included in the output
        if (in_array('body', $include, true)) {
            $rawData .= htmlspecialchars($this->mailer->Body);
        }

        // Return debug output along with raw data (if included)
        return $msg . ($rawData === '' ? '' : '<pre>' . $rawData . '</pre>');
    }
}
?>