<?php

namespace App\User\Contoller\Common;

use App\User\Contoller\Controller;
use App\User\Model\Application\ApplicationDocument;

class ApplicationDocumentController extends Controller
{
    public function agreementApplicationClient()
    {
        if($this->request->input('id_application')){
            $isSeal = $this->request->input('seal');
            if($isSeal == 'false')
                $isSeal = false;
            else
                $isSeal = true;


            $doc = new ApplicationDocument($this->request->input('id_application'));

            $result = $doc->AgreementApplicationClient($isSeal);
//            echo $result;
            print json_encode(['result' => $result,'link_file' => 'doc/client_doc.pdf', 'extension' => 'pdf','seal' => $isSeal]);
        }
        else
            print json_encode(['result' => false]);
    }

    public function receiptServices()
    {
        if($this->request->input('id_application')){

            $doc = new ApplicationDocument($this->request->input('id_application'));

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
                'applications',
                ['id' => $this->request->input('id_application')],
                [],
                1,
                ['receipt_services_num']
            )[0]['receipt_services_num'];

            print json_encode(['result' => $result,'link_file' => 'doc/' . $fileName,
                'extension' => $extension, 'number_receipt_services' => $numberReceiptServices]);
        }
        else
            print json_encode(['result' => false]);
    }

    public function agreementApplicationCarrier()
    {
        if($this->request->input('id_application')){
            $isSeal = $this->request->input('seal');

            if($isSeal == 'false')
                $isSeal = false;
            else
                $isSeal = true;

            $doc = new ApplicationDocument($this->request->input('id_application'));

            $result = $doc->AgreementApplicationCarrier($isSeal);
//            echo  $result;
            print json_encode(['result' => $result,'link_file' => 'doc/carrier_doc.pdf', 'extension' => 'pdf','seal' => $isSeal]);
        }
        else
            print json_encode(['result' => false]);
    }

    public function driverAttorney()
    {
        if($this->request->input('id_application')){


            $isSeal = $this->request->input('seal');

            if($isSeal == 'false')
                $isSeal = false;
            else
                $isSeal = true;

            $isSignatureDriver = $this->request->input('signature');

            if($isSignatureDriver == 'false')
                $isSignatureDriver = false;
            else
                $isSignatureDriver = true;

            $doc = new ApplicationDocument($this->request->input('id_application'));

            $fileName = 'doverenost.docx';
            $extension = 'docx';

            if($this->request->input('docx') == 'true'){

                $result = $doc->DriverAttorneyDOCX($isSeal, $isSignatureDriver);
            }

            else{
                $result = $doc->DriverAttorney($isSeal, $isSignatureDriver);
                $fileName = 'doverenost.pdf';
                $extension = 'pdf';
            }

            $numberAttorney = $this->database->first('applications', ['id' => $this->request->input('id_application')])['attorney_number'];

            print json_encode(['result' => $result,'link_file' => 'doc/' . $fileName,
                'extension' => $extension, 'number_attorney' => $numberAttorney]);
        }
        else
            print json_encode(['result' => false]);
    }

    public function attorneyM2()
    {


        if($this->request->input('id_application')){

            $materialValues = '';

            if($this->request->input('material-values'))
                $materialValues = $this->request->input('material-values');


            $doc = new ApplicationDocument($this->request->input('id_application'));

            $fileName = 'attorneyM2.xlsx';
            $extension = 'xlsx';

            $result = $doc->AttorneyM2($materialValues);

            $numberAttorney = $this->database->first('applications', ['id' => $this->request->input('id_application')])['attorney_number'];

            print json_encode(['result' => $result,'link_file' => 'doc/' . $fileName,
                'extension' => $extension, 'number_attorney' => $numberAttorney]);
        }
        else
            print json_encode(['result' => false]);
    }

    public function forwardingReceipt()
    {
        if($this->request->input('id_application')){
            $doc = new ApplicationDocument($this->request->input('id_application'));

            $isSeal = $this->request->input('seal');

            if($isSeal == 'false')
                $isSeal = false;
            else
                $isSeal = true;

            $result = $doc->ForwardingReceipt($isSeal);
            print json_encode(['result' => $result, 'link_file' => 'doc/raspiska.docx', 'extension' => 'docx']);

        }
        else
            print json_encode(['result' => false]);
    }

    public function insurance()
    {
        if($this->request->input('id_application')){
            $doc = new ApplicationDocument($this->request->input('id_application'));

            $result = $doc->Insurance();
            print json_encode(['result' => $result, 'link_file' => 'doc/books.xlsx', 'extension' => 'xlsx']);

        }
        else
            print json_encode(['result' => false]);
    }

    public function infoDriver()
    {
        if($this->request->input('id_application')){
            $doc = new ApplicationDocument($this->request->input('id_application'));

            $result = $doc->InfoDriver();
            print json_encode(['result' => $result, 'link_file' => 'doc/info-driver.docx', 'extension' => 'docx']);

        }
        else
            print json_encode(['result' => false]);
    }
}