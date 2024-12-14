<?php

namespace App\Services\Mailer;

trait LastError{
    protected $lastError = "";

    public function getLastError(){
        return $this->lastError;
    }
}

abstract class MailSender
{
    use LastError;

    public abstract function setFrom($email, $name = "");
    public abstract function getFrom();
    public abstract function addTo($email, $name = "");
    public abstract function addBcc($email, $name = "");
    public abstract function addCc($email, $name = "");
    public abstract function addReplyTo($email, $name = "");
    public abstract function setSubject($subject);
    public abstract function setBody($body,$contentType = "text/html");
    public abstract function addFileAttachment($path, $contentType = null);
    public abstract function addStringAttachment($buffer, $filename, $contentType = null);
    public abstract function send();
}
