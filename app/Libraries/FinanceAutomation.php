<?php
// include_once APPPATH . 'libraries/finance/MarketingAutomation.php';

namespace App\Libraries;

use App\Libraries\Finance\MarketingAutomation;

class FinanceAutomation
{

    private function __construct()
    {
    }

    private static function getAutomation($rel_id, $rel_to)
    {
        $automater = null;
        switch ($rel_to) {
            case "marketing":
                $automater = new MarketingAutomation($rel_id, $rel_to);
                break;
            default:
                break;
        }
        return $automater;
    }


    public static function doTransaction($rel_id, $rel_to, $type)
    {
        $automater = self::getAutomation($rel_id, $rel_to);
        $transaction_done = false;
        if (isset($automater)) {
            $transaction_done = $automater->doTransaction($type);
        }
        return $transaction_done;
    }
}
