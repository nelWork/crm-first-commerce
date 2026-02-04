<?php

namespace App\User\Contoller\Common;

use App\Config\Config as ConfigConfig;
use App\Database\Database;
use App\User\Contoller\Controller;
use App\User\Model\Application\ApplicationList;
use App\User\Model\Base\Base;
use App\User\Model\Carrier\CarrierList;
use App\User\Model\Client\ClientList;
use App\User\Model\PRR\PRRList;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;
use PSpell\Config;

class SearchController extends Controller
{
    public function index()
    {
        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Поиск'
        ]);

        $this->view('Search/index');
    }

    private function detectSearchType(string $search): string{
        $search = trim($search);

        // только цифры
        if (ctype_digit($search)) {
            return 'application_number';
        }

        // дата дд.мм.гггг или дд.мм.гг
        if (preg_match('/^\d{2}\.\d{2}\.\d{2,4}$/', $search)) {
            return 'date';
        }

        // иначе текст
        return 'text';
    }

    private function searchApplicationsByNumber($search = '', $filterIds = null): array{
        $listId = [];
        $conditions = [
            'application_number' => '%' .$search .'%',
            // 'account_number_Client' => '%' .$search .'%'
        ];
        $conditionUser = [];

        $user = $this->auth->user();  

        if(!$user->fullCRM()){
            if(count($user->getSubordinatesList()) > 0){
                $conditions['id_user'] = [$user->id()];
                
                foreach ($user->getSubordinatesList() as $subordinate){
                    $conditions['id_user'][] = $subordinate;
                }

            }
            else{
                $conditions['id_user'] = $user->id();
            }

            $conditionUser = $conditions['id_user'];
        }

        // if (!empty($filterIds)) {
        //     $conditions['id'] = $filterIds;
        // }

        // dump($conditions);

        $searchApplicationId = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'AND',
            'LIKE'
        );

        foreach($searchApplicationId as $application){
            $listId[] = $application['id'];
        }
        

        $searchClient = $this->database->superSelect(
            'clients',
            [
                'inn' => '%' .$search .'%'
            ],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );


        $listClient = [];

        foreach($searchClient as $client){
            $listClient[] = $client['id'];
        }

        $conditions = [
            'client_id_Client' => $listClient
        ];

        if (!empty($filterIds)) {
            $conditions['id'] = $filterIds;
        }

        if(!$user->fullCRM()){
            $conditions['id_user'] = $conditionUser;
        }

        $searchApplicationClient = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id'],
        );

        foreach($searchApplicationClient as $application){
            $listId[] = $application['id'];
        }

        $searchCarrier = $this->database->superSelect(
            'carriers',
            [
                'inn' => '%' .$search .'%'
            ],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );


        $listCarrier = [];

        foreach($searchCarrier as $carrier){
            $listCarrier[] = $carrier['id'];
        }

        $conditions = [
            'carrier_id_Carrier' => $listCarrier
        ];

        if (!empty($filterIds)) {
            $conditions['id'] = $filterIds;
        }

        if(!$user->fullCRM()){
            $conditions['id_user'] = $conditionUser;
        }

        $searchApplicationCarrier = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id'],
        );

        foreach($searchApplicationCarrier as $application){
            $listId[] = $application['id'];
        }
        
        return $listId;
    }

    // private function searchApplicationsByDate($search = '', $filterIds = null): array{
    //     $listId = [];
    //     $data = [];
        
    //     $conditions = [
    //         'date' => '%' .$search .'%',
    //     ];
        
    //     if (!empty($filterIds)) {
    //         $conditions['id_application'] = $filterIds;
    //     }

    //     $searchRouteApplication = $this->database->superSelect(
    //         'routes',
    //         $conditions,
    //         ['id' => 'DESC'],
    //         -1,
    //         ['id_application','direction'],
    //         0,
    //         'AND',
    //         'LIKE'
    //     );
    //     foreach($searchRouteApplication as $route){
    //         $listId[] = $route['id_application'];
    //         $data[] = $route;
    //     }

    //     dump($data);

    //     return $listId;
    // }

    private function searchApplicationsByDate($search = '', $filterIds = null): array{
        $result = [
            'listId' => [],
            'routes' => []
        ];

        // Проверяем формат dd.mm.yy
        if (preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $search)) {
            // Преобразуем в dd.mm.yyyy
            $search = preg_replace('/\.(\d{2})$/', '.20$1', $search);
        }

        $conditions = [
            'date' => '%' .$search .'%',
        ];

        if (!empty($filterIds)) {
            $conditions['id_application'] = $filterIds;
        }

        $searchRouteApplication = $this->database->superSelect(
            'routes',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id_application','direction'],
            0,
            'AND',
            'LIKE'
        );

        foreach($searchRouteApplication as $route){
            $result['listId'][] = $route['id_application'];
            $result['routes'][] = $route;
        }

        return $result;
    }


    private function searchApplicationsByText($search = '', $filterIds = null, $direction = -1): array{
        $listId = [];
        $conditions = [
            'account_number_Client' => '%' .$search .'%'
        ];

        // if (!empty($filterIds)) {
        //     $conditions['id'] = $filterIds;
        // }

        // dump($conditions);

        $conditionUser = [];

        $user = $this->auth->user();  

        if(!$user->fullCRM()){
            if(count($user->getSubordinatesList()) > 0){
                $conditions['id_user'] = [$user->id()];
                
                foreach ($user->getSubordinatesList() as $subordinate){
                    $conditions['id_user'][] = $subordinate;
                }

            }
            else{
                $conditions['id_user'] = $user->id();
            }

            $conditionUser = $conditions['id_user'];
        }

        $searchApplicationId = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'AND',
            'LIKE'
        );

        foreach($searchApplicationId as $application){
            $listId[] = $application['id'];
        }

        $conditions = [
            'city' => '%' .$search .'%'
        ];

        if($direction >= 0){
            $conditions['direction'] = $direction;
        }

        if (!empty($filterIds)) {
            $conditions['id_application'] = $filterIds;
        }

        // dump($conditions,$direction);

        $searchRouteApplication = $this->database->superSelect(
            'routes',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id_application'],
            0,
            'AND',
            'LIKE'
        );

        foreach($searchRouteApplication as $route){
            $listId[] = $route['id_application'];
        }
        

        $searchDriverApplication = $this->database->superSelect(
            'drivers',
            [
                'name' => '%' .$search .'%'
            ],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );

        $listDriver = [];

        foreach($searchDriverApplication as $driver){
            $listDriver[] = $driver['id'];
        }

        $conditions = [
            'driver_id_Client' => $listDriver
        ];

        if (!empty($filterIds)) {
            $conditions['id'] = $filterIds;
        }

        if(!$user->fullCRM()){
            $conditions['id_user'] = $conditionUser;
        }

        $searchApplicationDriver = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id'],
        );

        foreach($searchApplicationDriver as $application){
            $listId[] = $application['id'];
        }


        $searchClient = $this->database->superSelect(
            'clients',
            [
                'name' => '%' .$search .'%',
                'inn' => '%' .$search .'%'
            ],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );


        $listClient = [];

        foreach($searchClient as $client){
            $listClient[] = $client['id'];
        }

        $conditions = [
            'client_id_Client' => $listClient
        ];

        if (!empty($filterIds)) {
            $conditions['id'] = $filterIds;
        }

        if(!$user->fullCRM()){
            $conditions['id_user'] = $conditionUser;
        }

        $searchApplicationClient = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id'],
        );

        foreach($searchApplicationClient as $application){
            $listId[] = $application['id'];
        }

        $searchCarrier = $this->database->superSelect(
            'carriers',
            [
                'name' => '%' .$search .'%',
                'inn' => '%' .$search .'%'
            ],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );


        $listCarrier = [];

        foreach($searchCarrier as $carrier){
            $listCarrier[] = $carrier['id'];
        }

        $conditions = [
            'carrier_id_Carrier' => $listCarrier
        ];

        if (!empty($filterIds)) {
            $conditions['id'] = $filterIds;
        }

        if(!$user->fullCRM()){
            $conditions['id_user'] = $conditionUser;
        }

        $searchApplicationCarrier = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            ['id'],
        );

        foreach($searchApplicationCarrier as $application){
            $listId[] = $application['id'];
        }

        return $listId;
    }

    
    public function ajaxSearch(){
        $search = $this->request->input('search');
        if($search == '')
            return false;

        $listId = [];

        // Разделяем по пробелам
        $words = preg_split('/\s+/', $search);

        if (count($words) === 1) {
            $word = $words[0];

            $search = $word;

            $typeSearch = $this->detectSearchType($word);
            // dump($typeSearch);

            switch($typeSearch){
                case 'application_number':
                    $ids = $this->searchApplicationsByNumber($search);
                    break;
                case 'date':
                    $ids = $this->searchApplicationsByDate($search);
                    break;
                case 'text':
                    $ids = $this->searchApplicationsByText($search);
                    break;
            }

            $listId = array_merge($listId, $ids);

            // если только цифры то тогда может быть номер заявки
            // если в формате 99.99.9999 или 99.99.99 то может быть дата загрузки разгрузки
            // если текст то может быть ФИО водителя, город
        
        } else {
            $words = explode(' ', $search);
            $wordsWithType = [];

            // Определяем тип каждого слова
            foreach ($words as $word) {
                $wordsWithType[] = [
                    'word' => $word,
                    'type' => $this->detectSearchType($word)
                ];
            }

            // Приоритет: application_number > date > text
            usort($wordsWithType, function($a, $b) {
                $priority = ['application_number' => 1, 'date' => 2, 'text' => 3];
                return $priority[$a['type']] <=> $priority[$b['type']];
            });
            $listId = null; // пока ничего не найдено
            $routesByDirection = null;

            foreach ($wordsWithType as $item) {
                $word = $item['word'];
                $typeSearch = $item['type'];
                // dump($typeSearch);

                $ids = [];
                
                switch ($typeSearch) {
                    case 'application_number':
                        $ids = $this->searchApplicationsByNumber($word, $listId);
                        break;

                    case 'date':
                        $dateResult = $this->searchApplicationsByDate($word, $listId);
                        $ids = $dateResult['listId'];

                        // сохраняем маршруты с направлениями
                        $routesByDirection = $dateResult['routes'];
                        
                        break;
                    case 'text':
                        // dd($routesByDirection);
                        if (!empty($routesByDirection)) {
                            $direction0Ids = array_map(fn($r) => $r['id_application'], array_filter($routesByDirection, fn($r) => $r['direction'] == 0));
                            $direction1Ids = array_map(fn($r) => $r['id_application'], array_filter($routesByDirection, fn($r) => $r['direction'] == 1));

                            // dd($direction0Ids,$direction1Ids);

                            // сначала ищем по direction = 0
                            $listId0 = $this->searchApplicationsByText($word, $direction0Ids, 0);
                            // потом по direction = 1
                            $listId1 = $this->searchApplicationsByText($word, $direction1Ids, 1);

                            // dd($listId0, $listId1);

                            $ids = array_unique(array_merge($listId0, $listId1));
                        } else {
                            // если даты нет, ищем просто по тексту и фильтруем предыдущий результат
                            $ids = $this->searchApplicationsByText($word, $listId);
                        }
                        // $ids = $this->searchApplicationsByText($word, $listId);
                        break;
                }

                // 🔹 пересечение массивов
                if ($listId === null) {
                    $listId = array_unique($ids); // первый раз — просто присваиваем
                } else {
                    $listId = array_intersect($listId, $ids);
                }

                // dump($listId);

                // Если пересечение стало пустым → смысла дальше искать нет
                if (empty($listId)) {
                    break;
                }
            }

            // Приводим к уникальному массиву
            $listId = array_unique($listId ?? []);
        }

        $applicationList = new ApplicationList($this->database);

        if($this->auth->user()->manager() AND count($this->auth->user()->getSubordinatesList()) == 0){
            $list = $applicationList->listApplication(1,['id' => $listId,'id_user' => $this->auth->user()->id()],['id' => 'DESC'], -1);

        }
        else{
            $list = $applicationList->listApplication(1,['id' => $listId],['id' => 'DESC'], -1);

        }


        $this->extract([
            'controller' => $this,
            'applicationList' => $list,
            'words' => $words
        ]);

        $this->view('Application/application-list-template');
    }


    public function ajaxSearchPrr(){
        $search = $this->request->input('search');
        if($search == '')
            return false;
        
        $searchApplicationId = $this->database->superSelect(
            'prr_application',
            ['application_number' => '%' .$search .'%'],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );

        $listId = [];

        foreach($searchApplicationId as $application){
            $listId[] = $application['id'];
        }

        $applicationList = new PRRList($this->database);

        $list = $applicationList->listPrr(1,['id' => $listId],['id' => 'DESC'], 5);

        $this->extract([
            'controller' => $this,
            'prrList' => $list
        ]);

        $this->view('PRR/prr-application-list-template');
    }

    public function ajaxSearchCarrier(){
        $search = $this->request->input('search');
        if($search == '')
            return false;
        
        $searchCarrierId = $this->database->superSelect(
            'carriers',
            [
                'name' => '%' .$search .'%',
                'inn' => '%' .$search .'%',
                'info' => '%' .$search .'%',
            ],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );

        $listId = [];

        foreach($searchCarrierId as $carrier){
            $listId[] = $carrier['id'];
        }

        $carrierList = new Base($this->database);

        $list = $carrierList->getCarriersList(1,['id' => $listId],['id' => 'DESC'], 5);

        $this->extract([
            'controller' => $this,
            'listCarriers' => $list
        ]);

        $this->view('Base/carriers-list-template');
    }

    public function ajaxSearchClient(){
        $search = $this->request->input('search');
        if($search == '')
            return false;
        
        $searchClientId = $this->database->superSelect(
            'clients',
            [
                'name' => '%' .$search .'%',
                'inn' => '%' .$search .'%',
            ],
            ['id' => 'DESC'],
            -1,
            ['id'],
            0,
            'OR',
            'LIKE'
        );

        $listId = [];

        foreach($searchClientId as $client){
            $listId[] = $client['id'];
        }

        $clientList = new Base($this->database);

        $list = $clientList->getClientsList(1,['id' => $listId],['id' => 'DESC'], 5);

        $this->extract([
            'controller' => $this,
            'listClients' => $list
        ]);

        $this->view('Base/clients-list-template');
    }
}