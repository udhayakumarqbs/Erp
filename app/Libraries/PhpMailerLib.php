<?php

namespace App\Libraries;


require_once FCPATH . "../vendor/autoload.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class PhpMailerLib
 *  A Library class helps to send emails using PhpMailerLib
 * @author Ashok kumar
 * 
 * @version 1.0
 * @since CI 4
 */
class PhpMailerLib
{
    /**
     * @var object
     */
    protected $mailer;

    /**
     * Public Constructor for the PhpMailerLib
     * @param array $config || The smtp settings like host,username,password, port
     *  Example:
     *                      [
     *                          'host' => 'smtp.example.com',
     *                          'username' => 'your_email@example.com',
     *                          'password' => 'your_password',
     *                          'port' => 587,
     *                          'smtp_secure' => PHPMailer::ENCRYPTION_STARTTLS
     *                      ]
     */
    public function __construct(array $config)
    {

        $this->mailer = new PHPMailer(true);

        
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'] ?? '';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'] ?? '';
        $this->mailer->Password = $config['password'] ?? '';
        $this->mailer->SMTPSecure = $config['smtp_secure'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $config['port'] ?? 587;
    }

    /**
     * Function to set the From Address
     * 
     * @param string $from Email of the sender
     * @param string $name Name of the sender
     */
    public function setFrom($from, $name)
    {
        $this->mailer->setFrom($from, $name);
    }

    /**
     * Function to set the To Address
     * 
     * @param string $to Email of the reciver
     * @param string $name Name of the reciver
     */
    public function setTo($to, $name = '')
    {
        $this->mailer->addAddress($to, $name);
    }

    /**
     * Function to set Corbon Copy Address
     * 
     * @param string $cc Email of the reciver
     * @param string $name Name of the reciver
     */
    public function addCC($cc, $name = '')
    {
        $this->mailer->addCC($cc, $name);
    }

    /**
     * Function to set Blind Corbon Copy Address
     * 
     * @param string $cc Email of the reciver
     * @param string $name Name of the reciver
     */
    public function addBCC($bcc, $name = '')
    {
        $this->mailer->addBCC($bcc, $name);
    }

    /**
     * Function to add attachment to the email
     * @param string $filepath
     * @param string $filename
     */
    public function addAttachment($filePath, $fileName = '')
    {
        $this->mailer->addAttachment($filePath, $fileName);
    }

    /**
     * Function to add attachment to the email
     * @param string $body
     * @param boolean $isHTML default true
     */
    public function setBody($body, $isHTML = true)
    {
        $this->mailer->isHTML($isHTML);
        $this->mailer->Body = $body;
    }

    /**
     * Function to set the subject for the email
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->mailer->Subject = $subject;
    }

    /**
     * Public function to send the email.
     * Before calling this function, ensure you have set all the necessary parameters of the mail.
     * 
     * @return bool True if the email is sent successfully, false otherwise.
     * @throws \Exception if there is an error sending the email.
     * 
     * @see PHPMailer\PHPMailer\PHPMailer::send()
     * 
     * @example
     * $mailer = new PHPMailerLib($config);
     * $mailer->setFrom('from@example.com', 'From Name');
     * $mailer->addAddress('to@example.com');
     * $mailer->setSubject('Subject');
     * $mailer->setBody('Message body');
     * $result = $mailer->sendEmail();
     * 
     * if ($result) {
     *     echo 'Email sent successfully';
     * } else {
     *     echo 'Failed to send email';
     * }
     * 
     * @author Ashok kumar
     */
    public function sendEmail()
    {
        try {
            $this->mailer->send();

            return true;
        } catch (\Exception $e) {

            log_message('error', __METHOD__ . '' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test function to send an email and return the error log if it fails.
     * 
     * @return mixed True if the email is sent successfully, or the error log string if it fails.
     * 
     * @see PHPMailer\PHPMailer\PHPMailer::send()
     * 
     * @example
     * $mailer = new PHPMailerLib($config);
     * $mailer->setFrom('from@example.com', 'From Name');
     * $mailer->addAddress('to@example.com');
     * $mailer->setSubject('Test Subject');
     * $mailer->setBody('Test body');
     * $result = $mailer->testEmail();
     * 
     * if ($result === true) {
     *     echo 'Test email sent successfully';
     * } else {
     *     echo 'Test email failed: ' . $result;
     * }
     * 
     * @author Ashok kumar
     */
    public function testEmail()
    {
        try {
            $this->mailer->send();
            return true;
            return $this->mailer->getSMTPInstance()->getError();
        } catch (Exception $e) {
            $errorLog = "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}\n";
            $errorLog .= "Exception Message: {$e->getMessage()}\n";
            return $errorLog;
        }
    }
}
