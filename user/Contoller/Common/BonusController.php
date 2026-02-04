<?php

namespace App\User\Contoller\Common;

use App\Model\Application\Application;
use App\Model\Client\Client;
use App\User\Contoller\Controller;

class BonusController extends Controller
{
    public function index()
    {

        $condition = ['cancelled' => 0];

        $startOfWeek = '';
        $endOfWeek = '';

        if($this->request->input('period') !== null AND $this->request->input('period') !== '') {
            $period = $this->request->input('period');

            $date = explode(' - ', $period);


            $startOfWeek = date('Y-m-d', strtotime($date[0]));
            $endOfWeek = date('Y-m-d 23:59:59', strtotime($date[1]));

            $condition['dateField']  = [
                'name' => 'full_payment_date_Client',
                'start' => date('Y-m-d', strtotime($date[0])),
                'end' => date('Y-m-d 23:59:59', strtotime($date[1])),
            ];


        }
        else{
            $today = date('Y-m-d');

            // Номер текущего дня недели (1 - понедельник, 7 - воскресенье)
            $dayOfWeek = date('N');

            // Вычисляем дату начала (суббота)
            $startOfWeek = date('Y-m-d', strtotime($today . ' -' . (($dayOfWeek % 7) + 1) . ' days'));

            // Вычисляем дату конца (пятница)
            $endOfWeek = date('Y-m-d 23:59:59', strtotime($startOfWeek . ' +6 days'));

            $period = date('d.m.Y', strtotime($startOfWeek)) . ' - ' . date('d.m.Y', strtotime($endOfWeek));

            $condition['dateField']  = [
                'name' => 'full_payment_date_Client',
                'start' => $startOfWeek,
                'end' => $endOfWeek,
            ];
        }

        $applicationDB = $this->database->superSelect(
            'applications',
            $condition,
            [],
            -1,
            ['id','date','client_id_Client']
        ) ?? [];



        $listApplication = [];


        foreach ($applicationDB as $application) {

            if($application['client_id_Client'] == 251)
                continue;


//            if(strtotime($application['date']) < strtotime('2025-03-29')) {
//                continue;
//            }

            $tempApplication = new Application(['id' => $application['id']]);

            if($tempApplication->isDeduction() AND $tempApplication->isFullPaymentClient()){

                $tempData = $tempApplication->get(['id','idUser','date','applicationNumber','applicationNumberClient','clientIdClient']);

                $tempData['deduction'] = $tempApplication->getDeductionList();

                $tempData['fullPaymentDateClient'] = $this->database->first(
                    'applications',
                    ['id' => $application['id']],
                    ['full_payment_date_Client']
                )['full_payment_date_Client'];

                $listApplication[] = $tempData;
            }

        }


        // Отдельно по ТДС

        $applicationTDSDB = $this->database->superSelect(
            'applications',
            [
                'dateField' => [
                    'name' => 'date',
                    'start' => $startOfWeek,
                    'end' => $endOfWeek,
                ],
                'client_id_Client' => 251,
                'cancelled' => 0,
            ]
        );

        foreach ($applicationTDSDB as $application) {
            $tempApplication = new Application(['id' => $application['id']]);

            if($tempApplication->isDeduction()){

                $tempData = $tempApplication->get(['id','idUser','date','applicationNumber','applicationNumberClient','clientIdClient']);

                $tempData['deduction'] = $tempApplication->getDeductionList();

                $tempData['fullPaymentDateClient'] = '';

                $listApplication[] = $tempData;
            }

        }

        // Сначала группируем по client_id_Client
        $result = [];

        foreach ($listApplication as $item) {
            $clientId = $item['client_id_Client'];
            $userId = $item['id_user'];
            $deductionSum = 0;

            // Суммируем вычеты для текущей заявки
            if (!empty($item['deduction'])) {
                foreach ($item['deduction'] as $deduction) {
                    $deductionSum += (float)$deduction['sum'];
                }
            }
            $routesArray = $this->database->select('routes', ['id_application' => $item['id'],'type_for' => 1]);


            $routeText = '';
            foreach ($routesArray as $route) {
                $city = explode(',',$route['city']);
                $routeText .= $city[count($city) - 1] .' - ';
            }

            $routeText = trim($routeText, ' - ');


            // Инициализируем подмассив для клиента
            if (!isset($result[$clientId])) {
                $result[$clientId] = [
                    'client_id_Client' => $clientId,
                    'client_data' => $this->database->first('clients',['id'=>$clientId]),
                    'application_count' => 0,
                    'total_deduction_sum' => 0, // Общая сумма вычетов
                    'users' => []
                ];
            }

            // Группируем внутри клиента по id_user
            if (!isset($result[$clientId]['users'][$userId])) {
                $userData = $this->database->first('users',['id' => $userId]);

                $userName = $userData['surname'] .' ' .$userData['name'] ;
                $result[$clientId]['users'][$userId] = [
                    'id_user' => $userId,
                    'name' => $userName,
                    'application_count' => 0,
                    'user_total_deduction_sum' => 0,
                    'applications' => []
                ];
            }



            $fullPaymentDateClient = date('d.m.Y', strtotime($item['fullPaymentDateClient']));

            if($item['fullPaymentDateClient'] == ''){
                $fullPaymentDateClient = '-';
//                dd($result[$clientId]['users'][$userId]['applications']);
            }

            // Добавляем заявку
            $result[$clientId]['users'][$userId]['applications'][] = [
                'id' => $item['id'],
                'date' => date('d.m.Y', strtotime($item['date'])),
                'routes' => $routeText,
                'application_number' => $item['application_number'],
                'application_number_Client' => $item['application_number_Client'],
                'full_payment_date_Client' => $fullPaymentDateClient,
                'deduction' => $item['deduction'],
                'deduction_sum' => number_format($deductionSum,2,'.',' ')
            ];


            // Условие для ТДС




            // Увеличиваем счетчики
            $result[$clientId]['users'][$userId]['application_count']++;
            $result[$clientId]['users'][$userId]['user_total_deduction_sum'] += $deductionSum;

            $result[$clientId]['application_count']++;
            $result[$clientId]['total_deduction_sum'] += $deductionSum;
        }

// Приводим к массиву для удобного чтения
        $result = array_values($result);

        foreach ($result as $key => $value) {
            $result[$key]['total_deduction_sum'] = number_format($value['total_deduction_sum'],2,'.',' ');
        }

//        dd($result);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Бонусы',
            'period' => $period,
            'activeHeaderItem' => 0,
            'resultList' => $result,
        ]);

        $this->view('Bonus/index');
    }
}