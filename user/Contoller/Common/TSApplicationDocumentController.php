<?php

namespace App\User\Contoller\Common;

use App\User\Contoller\Controller;
use App\User\Model\TS\TSApplicationDocument;

class TSApplicationDocumentController extends Controller
{
    public function agreementApplicationClient()
    {
        if($this->request->input('id_application')){
            $isSeal = $this->request->input('seal');
            if($isSeal == 'false')
                $isSeal = false;
            else
                $isSeal = true;


            $doc = new TSApplicationDocument($this->request->input('id_application'));

            $result = $doc->AgreementApplicationClient($isSeal);
//            echo $result;
            print json_encode(['result' => $result,'link_file' => '/doc/ts_client_doc.pdf', 'extension' => 'pdf','seal' => $isSeal]);
        }
        else
            print json_encode(['result' => false]);
    }
}