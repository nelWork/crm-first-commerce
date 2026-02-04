<?php

namespace App\Admin\Model\TermsPayment;

use App\Admin\Model\Model;
use App\Database\DatabaseInterface;
use App\Model\TermsPayment\TermsPayment;

class TermsPaymentList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listTermsPayment(): array
    {
        $termsPayments = $this->database->select('terms_payment',[],[],-1,['id']);
        $listTermsPayment = array();

        foreach ($termsPayments as $item) {
            $listTermsPayment[] = new TermsPayment(['id' => $item['id']]);
        }

        return $listTermsPayment;
    }
}