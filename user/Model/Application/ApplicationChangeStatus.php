<?php

namespace App\User\Model\Application;

use App\Database\DatabaseInterface;
use App\Model\Application\Application;
use App\User\Model\Model;

class ApplicationChangeStatus extends Model
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
            'applications',
            ['id' => $idApplication, 'cancelled' => 0],
            [],
            1,
            [
                'id',
                'date',
                'customer_id_Carrier',
                'customer_id_Client',
                'actual_payment_Client',
                'actual_payment_Carrier',
                'transportation_cost_Carrier',
                'transportation_cost_Client',
                'application_status_journal',
                'application_section_journal'
            ]
        )[0] ?? [];

        if (empty($applicationData)) {
            return false;
        }

        $additionalExpenses = $this->database->select('additional_expenses',['id_application' => $idApplication]) ?? [];

        $additionalIdDocument = [];

        $counterAdditional = 1;

        if(strtotime($applicationData['date']) > strtotime('2025-04-01')) {
            foreach ($additionalExpenses as $expenses) {
                if ($expenses['type_expenses'] !== 'Страховка' and $expenses['type_expenses'] !== 'Вычет') {
                    $additionalIdDocument[] = 100 + $counterAdditional;
                    $counterAdditional++;
                }
            }
        }

        switch ($applicationData['application_section_journal']) :
            case 2:
                if($applicationData['actual_payment_Client'] == $applicationData['transportation_cost_Client']){
                    // $listIdDocument = array_merge([1,2,10,13,14,18,4,5], $additionalIdDocument);
                    $listIdDocument = array_merge([1,2,10,13,14,18,4,5], $additionalIdDocument);

                    if(! $this->checkConditionsDocument(
                        ['application_id' => $idApplication, 'document_id' => $listIdDocument],
                        $listIdDocument
                    ))
                        return false;

                    if(strtotime($applicationData['date']) > strtotime('2025-05-20') AND
                    $applicationData['customer_id_Carrier'] != $applicationData['customer_id_Client']) {
                        return false;
                    }

                }
                else{
                    return false;
                }

                break;
            case 3:
                if($applicationData['actual_payment_Carrier'] == $applicationData['transportation_cost_Carrier']){
                    // $listIdDocument = [];
                    // if(! $this->checkConditionsDocument(
                    //     ['application_id' => $idApplication, 'document_id' => $listIdDocument],
                    //     $listIdDocument
                    // ))
                    //     return false;


                    $listNameCommentDocument = [
                        'Трек-номер отправки',
                        'Комментарий об отправленных документах',
                        'Комментарий о полученных документах для клиента',
                        'Комментарий о полученных документах'
                    ];


                    if(! $this->checkConditionsCommentsDocument(
                        ['id_application' => $idApplication, 'name' => $listNameCommentDocument],
                        $listNameCommentDocument
                    ))
                        return false;


                    $applicationData['application_section_journal'] = 5;
                }
                else{
                    return false;
                }

                break;
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
        $this->database->update('applications', ['application_section_journal' => $sectionApplication + 1], ['id' => $idApplication]);

        if($sectionApplication == 2){
            $this->database->update('applications', ['application_closed_date' => date('Y-m-d')], ['id' => $idApplication]);
        }

        if($sectionApplication == 5){
            $this->database->update('applications', ['application_closed_document_date' => date('Y-m-d')], ['id' => $idApplication]);
        }
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