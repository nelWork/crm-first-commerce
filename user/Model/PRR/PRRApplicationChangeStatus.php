<?php

namespace App\User\Model\PRR;

use App\Database\DatabaseInterface;
use App\User\Model\Model;

class PRRApplicationChangeStatus extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }

    public function checkPossibleChangeStatus(int $idApplication,string $typeApp = ''): bool
    {
        if($typeApp != '') {
            return false;
        }
        $applicationData = $this->database->select(
            'prr_application',
            ['id' => $idApplication, 'cancelled' => 0],
            [],
            1,
            [
                'id',
                'date',
                'customer_id_Prr',
                'customer_id_Client',
                'actual_payment_Client',
                'actual_payment_Prr',
                'cost_Prr',
                'cost_Client',
                'application_status_journal',
                'application_section_journal'
            ]
        )[0] ?? [];

        if (empty($applicationData)) {
            return false;
        }


        switch ($applicationData['application_section_journal']) :
            case 2:
                if($applicationData['actual_payment_Client'] == $applicationData['cost_Client']){
                    $listIdDocument = [1,10];

                    if(! $this->checkConditionsDocument(
                        ['application_id' => $idApplication, 'document_id' => $listIdDocument],
                        $listIdDocument
                    ))
                        return false;

//                    if(strtotime($applicationData['date']) > strtotime('2025-05-20') AND
//                        $applicationData['customer_id_Carrier'] != $applicationData['customer_id_Client']) {
//                        return false;
//                    }

                }
                else{
                    return false;
                }

                break;
//            case 3:
//                if($applicationData['actual_payment_Carrier'] == $applicationData['transportation_cost_Carrier']){
//                    $listIdDocument = [18,4,5];
//                    if(! $this->checkConditionsDocument(
//                        ['application_id' => $idApplication, 'document_id' => $listIdDocument],
//                        $listIdDocument
//                    ))
//                        return false;
//
//
//                    $listNameCommentDocument = [
//                        'Трек-номер отправки',
//                        'Комментарий об отправленных документах',
//                        'Комментарий о полученных документах для клиента',
//                        'Комментарий о полученных документах'
//                    ];
//
//
//                    if(! $this->checkConditionsCommentsDocument(
//                        ['id_application' => $idApplication, 'name' => $listNameCommentDocument],
//                        $listNameCommentDocument
//                    ))
//                        return false;
//
//
//                    $applicationData['application_section_journal'] = 5;
//                }
//                else{
//                    return false;
//                }
//
//                break;
            default: return false;
        endswitch;


        $this->changeSectionApplication($applicationData['id'],$applicationData['application_section_journal']);

        return true;

    }

    public function getUnfulfilledConditions(): bool
    {

        return false;
    }

    private function changeSectionApplication(int $idApplication, int $sectionApplication)
    {
        $this->database->update('prr_application', ['application_section_journal' => $sectionApplication + 1], ['id' => $idApplication]);

        if($sectionApplication == 2){
            $this->database->update('prr_application', ['application_closed_date' => date('Y-m-d')], ['id' => $idApplication]);
        }

        if($sectionApplication == 5){
            $this->database->update('prr_application', ['application_closed_document_date' => date('Y-m-d')], ['id' => $idApplication]);
        }
    }

    private function checkConditionsDocument(array $tableCondition, array $conditions): bool
    {
        $files = $this->database->superSelect(
            'prr_files',
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
            'prr_application_document_comment',
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