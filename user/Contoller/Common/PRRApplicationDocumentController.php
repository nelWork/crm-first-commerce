<?php

namespace App\User\Contoller\Common;

use App\User\Contoller\Controller;
use App\User\Model\PRR\PRRApplicationDocument;

class PRRApplicationDocumentController extends Controller
{
    public function AgreementApplication()
    {
        if($this->request->input('id_application')){
            $isSeal = $this->request->input('seal');
            if($isSeal == 'false')
                $isSeal = false;
            else
                $isSeal = true;


            $doc = new PRRApplicationDocument($this->request->input('id_application'));

            $result = $doc->AgreementApplication($isSeal);
//            echo $result;
            print json_encode(['result' => $result,'link_file' => '/doc/client_prr_doc.pdf', 'extension' => 'pdf','seal' => $isSeal]);
        }
        else
            print json_encode(['result' => false]);
    }
    public function receiptServices()
    {
        if($this->request->input('id_application')){

            $doc = new PRRApplicationDocument($this->request->input('id_application'));

            $fileName = 'receiptServices.docx';
            $extension = 'docx';

            if($this->request->input('docx') == 'true'){

                $result = $doc->ReceiptServicesDOCX();
            }

            else{
                $result = $doc->ReceiptServices();
                $fileName = 'receiptServices.pdf';
                $extension = 'pdf';
            }

            $numberReceiptServices =  $this->database->select(
                'prr_application',
                ['id' => $this->request->input('id_application')],
                [],
                1,
                ['receipt_services_num']
            )[0]['receipt_services_num'];

            print json_encode(['result' => $result,'link_file' => '/doc/prr/' . $fileName,
                'extension' => $extension, 'number_receipt_services' => $numberReceiptServices]);
        }
        else
            print json_encode(['result' => false]);
    }
}