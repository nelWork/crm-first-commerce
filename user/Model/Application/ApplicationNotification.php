<?php

namespace App\User\Model\Application;

use App\Database\DatabaseInterface;
use App\Model\Application\Application;
use App\Model\Notification\Notification;
use App\User\Model\Model;

class ApplicationNotification extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {

    }


    public function needSendNotification(int $idApplication): bool
    {

        $applicationData = $this->database->select(
            'applications',
            ['id' => $idApplication, 'cancelled' => 0],
            [],
            1,
            [
                'id',
                'client_id_Client',
                'application_status_journal',
                'application_section_journal',
                'application_number',
                'id_user'
            ]
        )[0] ?? [];


        if(empty($applicationData)) {
            return false;
        }

        if($applicationData['application_section_journal'] == 1) {
            return false;
        }

        $hasNotification = $this->database->select(
            'notifications',
            ['id_application' => $idApplication]
        )[0] ?? false;



        if($hasNotification) {
            return false;
        }


        $clientData = $this->database->select(
            'clients',
            ['id' => $applicationData['client_id_Client']],
            [],
            1,
            [
                'format_work'
            ]
        )[0] ?? [];


        if($clientData['format_work'] == '') {
            return false;
        }


        $listIdDocument = [1,10];

        if(! $this->checkConditionsDocument(
            ['application_id' => $idApplication, 'document_id' => $listIdDocument],
            $listIdDocument
        ))
            return false;

        $idToUser = [$applicationData['id_user']];
        $message = '';

        switch ($clientData['format_work']):
            case 'Сканы счет/акт':
                $listIdDocument = [13,14];

                if(! $this->checkConditionsDocument(
                    ['application_id' => $idApplication, 'document_id' => $listIdDocument],
                    $listIdDocument
                ))
                    return false;
                break;
            case 'Счет/акт + фото ТСД':
                $listIdDocument = [2];

                if(! $this->checkConditionsDocument(
                    ['application_id' => $idApplication, 'document_id' => $listIdDocument],
                    $listIdDocument
                ))
                    return false;
                break;
            case 'Счет/акт + сканы ТСД':
                $listIdDocument = [18];

                if(! $this->checkConditionsDocument(
                    ['application_id' => $idApplication, 'document_id' => $listIdDocument],
                    $listIdDocument
                ))
                    return false;
                break;
            case 'ЭДО':
                $listIdDocument = [13,14];

                if(! $this->checkConditionsDocument(
                    ['application_id' => $idApplication, 'document_id' => $listIdDocument],
                    $listIdDocument
                ))
                    return false;
                $message = 'Документы отправлены клиенту по  ЭДО (электронный документооборот), при 
                необходимости продублируй клиенту все документы и подсвети что мы передали заявку на оплату';

                break;
            case 'Оригиналы':
                $listNameCommentDocument = [
                    'Комментарий об отправленных документах',
                ];


                if(! $this->checkConditionsCommentsDocument(
                    ['id_application' => $idApplication, 'name' => $listNameCommentDocument],
                    $listNameCommentDocument
                ))
                    return false;

                $idToUser = [11,25];

                break;
            case 'Квиток':
                return false;
                break;

        endswitch;


        foreach ($idToUser as $idUser){
            $notification = new Notification();
            $notification->edit([
                'id_application' => $idApplication,
                'name' => 'Просьба отправить клиенту счёт по заявке ',
                'date' => date('Y-m-d H:i:s'),
                'text' => $message,
                'id_from_user' => 11,
                'id_to_user' => $idUser,
                'application_number' =>  $applicationData['application_number'],
            ]);

            $notification->save();
        }



        return true;

    }


    private function checkConditionsDocument(array $tableCondition, array $conditions): bool
    {
        $files = $this->database->superSelect(
            'files',
            $tableCondition
        ) ?? [];


        if(empty($files)){
            return false;
        }

        foreach ($files as $file) {
            foreach ($conditions as $key => $condition) {
                if($file['document_id'] == $condition){
                    unset($conditions[$key]);
                }
            }
        }

        if(! empty($conditions)){
            return false;
        }

        return true;
    }

    private function checkConditionsCommentsDocument(array $tableCondition, array $listNameCommentDocument): bool
    {

        $commentsDocument = $this->database->superSelect(
            'application_document_comment',
            $tableCondition
        ) ?? [];


        if(empty($commentsDocument)){
            return false;
        }

        foreach ($commentsDocument as $comment) {
            foreach ($listNameCommentDocument as $key => $documentName) {
                if($comment['name'] == $documentName AND $comment['comment'] != ''){
                    unset($listNameCommentDocument[$key]);
                }
            }
        }

        if(! empty($listNameCommentDocument)){
            return false;
        }

        return true;
    }
}