<?php

namespace App\Admin\Model\Сustomer;

use App\Admin\Model\Model;
use App\Database\DatabaseInterface;
use App\Model\Customer\Customer;

class CustomerList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listCustomer(): array
    {
        $customers = $this->database->select('customers');
        $listCustomers= array();

        foreach ($customers as $customer) {
            $listCustomers[] = new Customer(['id' => $customer['id']]);

        }

        return $listCustomers;
    }
}