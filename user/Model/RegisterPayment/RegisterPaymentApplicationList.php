<?php

namespace App\User\Model\RegisterPayment;

use App\Database\DatabaseInterface;
use App\User\Model\Model;
use DateTime;

class RegisterPaymentApplicationList extends Model
{

    private array $holidays = [
        '2025-01-01','2025-01-02','2025-01-03','2025-01-06','2025-01-07','2025-01-08',
        '2025-05-01','2025-05-02','2025-05-08','2025-05-09',
        '2025-06-12','2025-06-13',
        '2025-11-03','2025-11-04',
        '2025-12-31'
    ];

    private array $customers = [];

    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
        $customers = $this->database->select('customers');

        foreach($customers as $item){
            $this->customers[$item['id']] = $item['name'];
        }
    }

    function addBankDays(DateTime $startDate, int $daysToAdd): string {
        $date = clone $startDate;
        $addedDays = 0;



        while ($addedDays < $daysToAdd) {
            $date->modify('+1 day');
            $weekday = $date->format('N'); // 1 (пн) - 7 (вс)
            $isWeekend = $weekday >= 6;
            $isHoliday = in_array($date->format('Y-m-d'), $this->holidays);

            if (!$isWeekend && !$isHoliday) {
                $addedDays++;
            }
        }

        return $date->format('Y-m-d');
    }

    public function listApplication(array $conditions = [], array $order = ['id' => 'ASC']): array{

        $conditions['application_section_journal'] = [1,2,3];

        $applicationsDB = $this->database->superSelect('applications',$conditions, $order,-1,
            [
                'id',
                'id_user',
                'application_number',
                'transportation_cost_Carrier',
                'actual_payment_Carrier',
                'carrier_id_Carrier',
                'date_receipt_all_documents_Carrier',
                'customer_id_Carrier',
                'register_payment_comment',
                'carrier_chosen_info',
                'terms_payment_Carrier',
                'prepayment_Carrier',
                'ati_claims',
                'pretrials_claims'
            ]
        );

//        dd($applicationsDB);

        $listApplications = [];

        $listApplicationsExclusion = [

        ];

        foreach ($applicationsDB as $application) {

            if($application['actual_payment_Carrier'] == $application['transportation_cost_Carrier']){
                continue;
            }

            $hasComment = $this->database->first('application_document_comment', [
                'id_application' => $application['id'],
                'name' => 'Комментарий о полученных документах'
            ]);

            if(! $hasComment AND !$application['prepayment_Carrier']){
                continue;
            }

            if(!$hasComment)
                $hasComment = ['comment' => ''];

            if($hasComment['comment'] == '' AND !$application['prepayment_Carrier'])
                continue;

            $tempApplication = $application;


            $tempApplication['comment_doc'] = $hasComment['comment'] ?? '';

            $user = $this->database->first(
                'users',
                ['id' => $application['id_user']],
                ['name','surname','lastname']
            );

            $tempApplication['user'] = $user['surname'] . ' ' . $user['name'];

            $tempApplication['carrier'] = $this->database->first(
                'carriers',
                ['id' => $application['carrier_id_Carrier']],
                ['name']
            )['name'];

//            $tempApplication['carrier'] .= '' . $application['carrier_chosen_info'];

            $lastRegisterPayment = $this->database->select(
                'register_payment_history',
                [
                    'id_application' => $application['id'],
                    'date' => date('Y-m-d'),
                ],
                ['id' => 'DESC']
            );

            $tempApplication['last_register_payment_comment'] = '';

            if($lastRegisterPayment){
                $tempApplication['last_register_payment_comment'] = $lastRegisterPayment[0]['comment'];
            }

            $tempApplication['remainder'] = number_format(
                $application['transportation_cost_Carrier'] - $application['actual_payment_Carrier'],
                0,',', ' '
            );

            $tempApplication['date_receipt_all_documents'] = '';

            $tempApplication['date_payment'] = '';

            $tempApplication['customer'] = $this->customers[$application['customer_id_Carrier']];

            $tempApplication['add_day'] = 10;


            if($application['date_receipt_all_documents_Carrier']) {
                $tempApplication['date_receipt_all_documents'] =
                    date(
                        'd.m.Y',
                        strtotime($application['date_receipt_all_documents_Carrier'])
                    );

//                preg_match_all('/\b1-(\d+)\s*(?:бд|БД|банковских дней)/ui', $application['terms_payment_Carrier'], $matches);

//                preg_match_all('/\b([1-5])\s*[-–—]\s*(\d+)\s*(?:бд|банковских дней)/ui', $application['terms_payment_Carrier'], $matches);


                preg_match_all(
                    '/\b([1-5])(?:\s*[-–—]\s*(\d+))?\s*(?:б[.\s]*д[.]?|банковских\s+дней)/ui',
                    $application['terms_payment_Carrier'],
                    $matches
                );


//                $days = [];
//
//                foreach ($matches[1] as $i => $first) {
//                    $second = $matches[2][$i];
//                    // Преобразуем в int и выбираем наибольшее из двух
//                    $days[] = max((int)$first, (int)$second);
//                }
//
//                // Если ничего не найдено, по умолчанию 11
//                $maxDay = !empty($days) ? max($days) : 11;

                $days = [];



                foreach ($matches[1] as $i => $first) {
                    $firstInt = (int)$first;
                    $secondInt = isset($matches[2][$i]) && $matches[2][$i] !== '' ? (int)$matches[2][$i] : null;

                    if ($secondInt !== null) {
                        $days[] = max($firstInt, $secondInt);
                    } else {
                        $days[] = $firstInt;
                    }
                }



                // Если ничего не найдено, по умолчанию 11
                $maxDay = !empty($days) ? max($days) : 11;


                $tempApplication['DAYS'] = $maxDay;
                $tempApplication['add_day'] = $maxDay;



//                var_dump($tempApplication);

                $tempApplication['date_payment'] =
                    $this->addBankDays(
                        new DateTime($application['date_receipt_all_documents_Carrier']),
                        $maxDay - 1
                    );


                if($application['terms_payment_Carrier'] == '<p>1 б.д. по сканам закрывающих документов и квитку</p>'){
//                    dd($matches,$days,$tempApplication['DAYS'],$tempApplication['add_day'],$tempApplication['date_payment']);
                }



                $tempApplication['date_payment'] = date(
                    'd.m.Y',
                    strtotime($tempApplication['date_payment'])
                );
            }



            $listCommentDB = $this->database->select(
                'register_payment_application_comment',
                ['id_application' => $application['id']],
                ['id' => 'DESC']
            );

            $listComment = [];

            if($listCommentDB){
                foreach ($listCommentDB as $comment) {
                    $tempComment = $comment;

                    $tempUser = $this->database->first('users', ['id' => $comment['id_user']]);

                    $tempComment['user'] = $tempUser['name'] . ' ' . $tempUser['surname'];
                    $tempComment['datetime'] = date('d.m.Y', strtotime($comment['datetime_comment']));

                    $listComment[] = $tempComment;
                }
            }

            $tempApplication['list_comment'] = $listComment;
            $tempApplication['type'] = 1;


            $listApplications[] = $tempApplication;
        }
        
        $prrApplicationsDB = $this->database->superSelect(
            'prr_application',$conditions, $order,-1,
            [
                'id',
                'id_user',
                'application_number',
                'cost_Prr',
                'actual_payment_Prr',
                'prr_id_Prr',
//                'date_receipt_all_documents_Prr',
                'customer_id_Prr',
                'chosen_contact_Prr',
//                'register_payment_comment',
                'terms_payment_Prr',
                'application_date_actual_unloading'
            ]
        );
        foreach ($prrApplicationsDB as $application) {

            if($application['actual_payment_Prr'] == $application['cost_Prr']){
                continue;
            }

            if(!$application['application_date_actual_unloading'])
                continue;
//            $hasComment = $this->database->first('prr_application_document_comment', [
//                'id_application' => $application['id'],
//                'name' => 'Комментарий о полученных документах'
//            ]);
//
//            if(! $hasComment AND !$application['prepayment_Carrier']){
//                continue;
//            }
//
//            if(!$hasComment)
//                $hasComment = ['comment' => ''];
//
//            if($hasComment['comment'] == '' AND !$application['prepayment_Carrier'])
//                continue;

            $tempApplication = $application;


//            $tempApplication['comment_doc'] = $hasComment['comment'] ?? '';

            $user = $this->database->first(
                'users',
                ['id' => $application['id_user']],
                ['name','surname','lastname']
            );

            $tempApplication['user'] = $user['surname'] . ' ' . $user['name'];

            $tempApplication['prr'] = $this->database->first(
                'prr_company',
                ['id' => $application['prr_id_Prr']],
                ['name']
            )['name'];

//            $tempApplication['carrier'] .= '' . $application['carrier_chosen_info'];

            $lastRegisterPayment = $this->database->select(
                'prr_register_payment_history',
                [
                    'id_application' => $application['id'],
                    'date' => date('Y-m-d'),
                ],
                ['id' => 'DESC']
            );

            $tempApplication['last_register_payment_comment'] = '';

            if($lastRegisterPayment){
                $tempApplication['last_register_payment_comment'] = $lastRegisterPayment[0]['comment'];
            }

            $tempApplication['remainder'] = number_format(
                $application['cost_Prr'] - $application['actual_payment_Prr'],
                0,',', ' '
            );

            $tempApplication['date_receipt_all_documents'] = '';

            $tempApplication['date_payment'] = '';

            $tempApplication['customer'] = $this->customers[$application['customer_id_Prr']];

            $tempApplication['add_day'] = 0;


            if($application['application_date_actual_unloading']) {
                $tempApplication['application_date_actual_unloading'] =
                    date(
                        'd.m.Y',
                        strtotime($application['application_date_actual_unloading'])
                    );

                preg_match_all('/\b1-(\d+)\s*(?:бд|БД|банковских дней)/ui', $application['terms_payment_Prr'], $matches);

                // Получаем найденные числа
                $lastDays = array_map('intval', $matches[1]);

                // Если не найдено — возвращаем 11 по умолчанию
                $maxDay = !empty($lastDays) ? max($lastDays) : 0;

                $tempApplication['date_payment'] =
                    $this->addBankDays(
                        new DateTime($application['application_date_actual_unloading']),
                        $maxDay
                    );

                $tempApplication['add_day'] = $maxDay;

                $tempApplication['date_payment'] = date(
                    'd.m.Y',
                    strtotime($tempApplication['date_payment'])
                );
            }

            $listCommentDB = $this->database->select(
                'prr_register_payment_application_comment',
                ['id_application' => $application['id']],
                ['id' => 'DESC']
            );

            $listComment = [];

            if($listCommentDB){
                foreach ($listCommentDB as $comment) {
                    $tempComment = $comment;

                    $tempUser = $this->database->first('users', ['id' => $comment['id_user']]);

                    $tempComment['user'] = $tempUser['name'] . ' ' . $tempUser['surname'];
                    $tempComment['datetime'] = date('d.m.Y', strtotime($comment['datetime_comment']));

                    $listComment[] = $tempComment;
                }
            }

            $tempApplication['list_comment'] = $listComment;

            $tempApplication['type'] = 2;


//            dd($tempApplication);
            $listApplications[] = $tempApplication;
        }
//        dd($listApplications);



        return $listApplications;
    }
}