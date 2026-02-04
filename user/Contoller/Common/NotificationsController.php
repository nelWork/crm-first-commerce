<?php

namespace App\User\Contoller\Common;

use App\Model\Notification\Notification;
use App\User\Contoller\Controller;
use App\User\Model\NotificationModel\NotificationModel;

class NotificationsController extends Controller
{
    public function index()
    {
        $notificationModel = new NotificationModel($this->database);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Уведомления',
            'notificationsList' => $notificationModel->getListNotifications(['id_to_user' => $this->auth->user()->id()])
        ]);

        $this->view("Notification/index");
    }

    public function ajaxSetCompleteNotification()
    {
        $idNotification = $this->request->input('id');

        $notification = new Notification(['id' => $idNotification]);

        $notificationData = $notification->get();

        if($this->database->update(
            'applications',
            ['account_status_Client' => 1, 'date_invoice_Client'=> date('Y-m-d')],
            ['id' => $notificationData['id_application']]
        )){
            $notification->edit(['status' => 1]);
            if($notification->save()){
                $repeatNotification = new Notification([
                    'id_application' => $notificationData['id_application'],
                    'status' => 0
                ]);

                if($repeatNotification->get()['id'] != 0){
                    $repeatNotification->edit(['status' => 1]);
                    $repeatNotification->save();
                }

                print json_encode(['status' => true]);
            }
            else
                print json_encode(['status' => false]);
        }
        else{
            print json_encode(['status' => false]);
        }

    }

}