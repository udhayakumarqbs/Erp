<?php

namespace App\Libraries;
use App\Libraries\Mailer\Mailer;
use App\Libraries\Mailer\Code_mailer;
use App\Libraries\Mailer\Php_mailer;
use App\Libraries\Mailer\Sendgrid_mailer;
use App\Libraries\Mailer\Swift_mailer;

class SendMailer
{
    protected $mailer;

    function __construct()
    {
        $this->mailer = MailerFactory::getMailer();
        helper('erp');
    }

    public function sendEmail($to, $subject, $message, $optoins = array(), $convert_message_to_html = true)
    {

        if (config('Logger')->threshold >= 6) {
            log_message('notice', 'Email: ' . $to . ' Subject: ' . $subject);
        }

        $this->mailer->clearAll();
        $this->mailer->addTo($to);
        $this->mailer->addSubject($subject);


        if ($convert_message_to_html) {
            $message = htmlspecialchars_decode($message);
        }


        $this->mailer->addBody($message);


        //add attachment
        $attachments = get_array_value($optoins, "attachments");
        if (is_array($attachments)) {
            foreach ($attachments as $value) {
                $file_path = get_array_value($value, "file_path");
                $file_name = get_array_value($value, "file_name");
                $this->mailer->attachFile(trim($file_path), $file_name, "attachment");
            }
        }

        //check reply-to
        $reply_to = get_array_value($optoins, "reply_to");
        if ($reply_to) {
            $this->mailer->addReplyto($reply_to);
        }

        //check cc
        $cc = get_array_value($optoins, "cc");
        if ($cc) {
            $this->mailer->addCc($cc);
        }

        //check bcc
        $bcc = get_array_value($optoins, "bcc");
        if ($bcc) {
            $this->mailer->addBcc($bcc);
        }

        $logger = \Config\services::logger();

        $logger->error($this->mailer->send());

        //send email
        if ($this->mailer->send()) {
            return true;
        } else {
            //show error message in none production version
            if (ENVIRONMENT !== 'production') {
                throw new \Exception($this->mailer->printDebugger());
            }
            return false;
        }
    }
}