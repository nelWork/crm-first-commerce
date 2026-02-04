<?php

namespace App\Model\AdditionalExpenses;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\User\Model\Model;

class PaymentHistoryAdditionalExpenses extends Model
{
    private DatabaseInterface $database;
    private ConfigInterface $config;

    static public function addPaymentHistoryItem($sum, $application_id, $additional_expense_id, $taxation_type, $additional_expenses_name, $user_id, $type_app = '')
    {

        $config = new Config();
        $database = new Database($config);

        $datetime = date('Y-m-d H:i:s');

        $name_table = 'payment_history_additional_expenses';

        if($type_app == 'prr'){
            $name_table = 'payment_history_additional_expenses_prr';
        }

        try {
            $database->insert($name_table, [
                'sum' => $sum,
                'application_id' => $application_id,
                'additional_expense_id' => $additional_expense_id,
                'taxation_type' => $taxation_type,
                'additional_expenses_name' => $additional_expenses_name,
                'user_id' => $user_id,
                'datetime' => $datetime
            ]);
        }
        catch (\Exception $e){

        }
    }
}