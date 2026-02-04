<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;

class DocumentFlowController extends Controller
{
    public function index()
    {
        $this->extract([
            'controller' => $this,
            'titlePage' => 'Документооборот',
            'itemActiveMenu' => 4,
            'documentFlow' => $this->database->first('document_flow'),
        ]);

        $this->view('DocumentFlow/index');
    }

    public function editDocumentFlow()
    {
        $this->database->update('document_flow',
            [
                'application_num' => $this->request->input('application-num'),
                'attorney_driver_num' => $this->request->input('attorney-driver-num'),
                'forwarding_receipt_num' => $this->request->input('forwarding-receipt-num'),
            ],
            ['id' => 1]
        );

        $this->redirect->to('/admin/document-flow');
    }
}