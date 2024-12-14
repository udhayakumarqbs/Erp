<?php

namespace App\Libraries;

use App\Libraries\Mailer\Code_mailer;
use App\Models\Erp\ErpSettingsModel;
use App\Libraries\Mailer\Php_mailer;

class MailerFactory
{

    private static $mailer = null;
   

    public static function getMailer()
    {
        $erpSettingsModel = new ErpSettingsModel();
        $engine =  $erpSettingsModel->get_setting("mail_engine");
        switch ($engine) {
            case "PHPMailer":
                self::$mailer = new Php_mailer();
                break;
            case "CodeIgniter":
                self::$mailer = new Code_mailer();
                break;
            default:
                self::$mailer = null;
                break;
        }
        return self::$mailer;
    }
}
