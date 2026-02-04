<?php

namespace App\User\Model\PRR;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Client\Client;
use App\Model\Customer\Customer;
use App\Model\Marshrut\Marshrut;
use App\Model\PRR\PRRApplication;
use App\Model\User\User;
use App\User\Model\Application\MYPDF;
use App\User\Model\Model;
use PhpOffice\PhpWord\PhpWord;
use TCPDF;
use TCPDF_FONTS;

class PRRApplicationDocument extends Model
{

    private DatabaseInterface $database;

    private ConfigInterface $config;

    private PRRApplication $application;

    private int $idApplication = 0;

    private string $pathDocTemplate = APP_PATH .'/public/doc/prr/';

    private array $months = [
        'января','февраля','марта',
        'апреля','мая','июня',
        'июля','августа','сентября',
        'октября','ноября','декабря'
    ];


    private array $days = [
        'первое','второе','третье','четвертое','пятое',
        'шестое','седьмое','восьмое','девятое','десятое',
        'одиннадцатое','двенадцатое','тринадцатое','четырнадцатое',
        'пятнадцатое','шестнадцатое','семнадцатое','восемнадцатое',
        'девятнадцатое','двадцатое','двадцать первое','двадцать второе',
        'двадцать третье','двадцать четвертое','двадцать пятое','двадцать шестое',
        'двадцать седьмое','двадцать восьмое','двадцать девятое',
        'тридцатое','тридцать первое'
    ];

    private array $years = [
        24 => 'двадцать четвертого',
        25 => 'двадцать пятого'
    ];


    public function __construct(int $idApplication = 0){

        $this->idApplication = $idApplication;

        $this->config = new Config();
        $this->database = new Database($this->config);

        if($idApplication > 0){
            $this->application = new PRRApplication(['id' => $idApplication]);

        }

    }
    private function getCustomerData(int $idCustomer)
    {
        $customer = new Customer(['id' => $idCustomer]);

        return $customer->get();
    }

    private function getManagerData(int $idUser): array
    {
        $manager = new User(['id' => $idUser]);

        return $manager->get();

    }

    private function getClientData(int $idClient): array
    {
        $client = new Client(['id' => $idClient]);

        return $client->get();
    }

    private function getFooterHTML(bool $isSeal = false, string $seal = '', string $signature = '', int $sizeSignature = 60): string
    {
        $html = '';

        if($isSeal){
            $html = '
                <table class="iksweb" style="padding-top: 5px;height: 100px;">
                    <tbody>
                        <tr>
                            <td style="text-align: left; font-size: 12px;">                
                                <table>
                                    <tr>
                                        <td style="width: 35%;">ИСПОЛНИТЕЛЬ: </td>
                                        <td style="width: 65%;">
                                            <table style="">
                                                <tr>
                                                    <td style="width:' .$sizeSignature - 10 .'px; height:' .$sizeSignature - 10 .'px;">
                                                        <img style="width:' .$sizeSignature .'px; height:' .$sizeSignature .'px;" src="' . $signature . '">
                                                    </td>
                                                    <td style="width:100px; height: 100px;">
                                                        <img style="width:125px; height: 125px;" src="' . $seal . '">
                                                    </td>
                                                </tr>                
                                            </table>                
                                        </td>
                                    </tr>                
                                </table>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                              ЗАКАЗЧИК:
                            </td>
                        </tr>
                    </tbody>
                </table>';
        }
        else {
            $html = '
                <table class="iksweb" style="padding-top: 5px;height: 100px;">
                    <tbody>
                        <tr>
                            <td style="text-align: left; font-size: 12px;">                
                                <table>
                                    <tr>
                                        <td style="width: 35%;">ИСПОЛНИТЕЛЬ: </td>
                                        <td style="width: 65%;">
                                            <table style="">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                </tr>                
                                            </table>
                                        </td>
                                    </tr>                
                                </table>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                ЗАКАЗЧИК:
                            </td>
                        </tr>
                    </tbody>
                </table>';
        }

        return $html;
    }

    private function getRoutes(int $idApplication, int $typeFor = 0): array
    {
        $routesId = $this->database->select('routes',['id_application' => $idApplication, 'type_for' => $typeFor]);

        $routesArray = [];

        foreach ($routesId as $routeId) {
            $tempRoute = new Marshrut(['id' => $routeId['id']]);
            $routesArray[] = $tempRoute->get();
        }

        return $routesArray;
    }

    public function AgreementApplication(bool $isSeal = false): bool
    {
        $pdf = new MYPDF('', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->SetAutoPageBreak(TRUE, 40);

        $fontname = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/FontsFree-Net-calibri-regular.ttf', 'TrueTypeUnicode', '', 96);
        $fontnameBold = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/CalibriBold.TTF', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 14, '', false);

        if(! $this->application)
            return false;
        $application = $this->application->get();

        $dataCustomer = $this->getCustomerData($application['customer_id_client']);
        $dataManager = $this->getManagerData($application['id_user']);
        $dataClient = $this->getClientData($application['client_id_client']);

        $seal = $dataCustomer['link_seal'];
        $signature = $dataCustomer['link_signature'];

        if($application['customer_id_client'] == 2)
            $pdf->setFooterHTML($this->getFooterHTML($isSeal, $seal, $signature,100));
        else
            $pdf->setFooterHTML($this->getFooterHTML($isSeal, $seal, $signature));


        $adress = $dataCustomer['mailing_address'];
        $INN = $dataCustomer['inn'];
        $isplonitel = $dataCustomer['name'];

        $logoOOO = '<img src="' .APP_PATH .'/public/assets/img/logo.png" alt="1" style="object-fit: cover">';

        if($dataCustomer['id'] != 1){
            $logoOOO = '';
        }

        $contactFaceFIO = "{$dataManager['surname']} {$dataManager['name']} {$dataManager['lastname']}";
        $telefon = $dataManager['phone'];

        $client = [
            'ispolnitel' => $dataClient['name'],
            'data_zayavka' => date('d.m.Y', strtotime($application['date'])),
            'pochtovyj_adres' => $dataClient['legal_address'],
            'inn' => $dataClient['inn'],
            'telefon_fakc' =>  $application['chosen_contact_client'],
         ];

        $title_applications = $application['application_number'];

        $nature_cargo = str_replace(array('<p>', '</p>'), array('', ''), $application['nature_cargo_client']);
        $weight = $application['weight_client'];

        $place = $application['place_client'];



        $cost = number_format(
            $application['cost_client'],
            0,
            '.',
            ' '
        );
        $terms_payment = $application['terms_payment_client'];
        $special_conditions = $application['special_condition_client'];
//        $special_conditions = '';


        $pdf->SetAutoPageBreak(TRUE, 40);
        $pdf->AddPage();
        $header = '
            <table class="iksweb" style="font-size: 11.04px;"  >
                <tbody>
                    <tr>
                        <td rowspan="4" style="width: 25%; height: 30%;">
                         ' .$logoOOO .'
                        </td>
                        <td style=" font-size: 11px; width: auto; line-height: 15px;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;font-family: ' . $fontnameBold . ', serif;">
                                Исполнитель:
                            </span> 
                            ' . str_replace(array('&#171;', '&#187;'), array('«', '»'), $isplonitel) . ',  
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;font-family: ' . $fontnameBold . ', serif;">
                            ИНН:
                            </span> '
            . $INN
            . '</td> 
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: auto;line-height: 15px;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                Почтовый адрес:
                            </span> ' . $adress
            . '</td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: auto;line-height: 15px;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                Конт.лицо:
                            </span> ' . $contactFaceFIO
            . ',</td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: auto;line-height: 15px;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                Телефон, факс:
                            </span> ' . $telefon
            . ', </td>
                    </tr>
                </tbody>
            </table>
        ';


        $loading = '';
        $unloading = '';

        $placePrrList = $this->application->getPRRPlaceList();

        $numberLoaders = $application['number_loaders_client'];

        foreach ($placePrrList as $placePrr) {
            if(!$placePrr['type_for'] AND $placePrr['direction']){
                $loading = '<tr>
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black; width: 25%;">
                        Адрес погрузки:</td>
                        <td style="line-height: 24px;border: 1px solid black; width: 70%;">' . $placePrr['address'] . '</td>
                    </tr>
                    <tr style="">
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: right; border: 1px solid black; ">Дата погрузки:</td>
                        <td style="line-height: 24px;border: 1px solid black; "><table><tr><td style="">' .$placePrr['date'] . ' </td>
                                    <td> 
                                        <table>
                                            <tr>
                                                <td style="line-height: 24px;border-left: 1px solid black; border-right: 1px solid black; 
                                                    text-align: right; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                                    Время: <span> </span>
                                                </td>
                                                <td style="line-height: 24px;"> ' . $placePrr['time'] . '</td>
                                            </tr>                             
                                        </table>                
                                    </td>
                                </tr>                      
                            </table>                      
                        </td>
                    </tr>
                    <tr>
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black; width: 25%;">
                        Кол-во рабочих:</td>
                        <td style="line-height: 24px;border: 1px solid black; width: 70%;">' . $numberLoaders . '</td>
                    </tr>
                    <tr style="">
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: right; border: 1px solid black; ">Контакт (на месте):</td>
                        <td style="line-height: 24px;border: 1px solid black; "><table><tr><td style="">' .$placePrr['contact'] . ' </td>
                                    <td> 
                                        <table>
                                            <tr>
                                                <td style="line-height: 24px;border-left: 1px solid black; border-right: 1px solid black; 
                                                    text-align: right; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                                    Тел.: <span> </span>
                                                </td>
                                                <td style="line-height: 24px;"> ' . $placePrr['phone'] . '</td>
                                            </tr>                             
                                        </table>                
                                    </td>
                                </tr>                      
                            </table>                      
                        </td>
                    </tr>';
            }

            if(!$placePrr['type_for'] AND !$placePrr['direction']){
                $unloading = '<tr>
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black; width: 25%;">
                        Адрес разгрузки:</td>
                        <td style="line-height: 24px;border: 1px solid black; width: 70%;">' . $placePrr['city'] .' ' .$placePrr['address'] . '</td>
                    </tr>
                    <tr style="">
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: right; border: 1px solid black; ">Дата разгрузки:</td>
                        <td style="line-height: 24px;border: 1px solid black; "><table><tr><td style="">' .$placePrr['date'] . ' </td>
                                    <td> 
                                        <table>
                                            <tr>
                                                <td style="line-height: 24px;border-left: 1px solid black; border-right: 1px solid black; 
                                                    text-align: right; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;" >
                                                    Время: <span> </span>
                                                </td>
                                                <td style="line-height: 24px;"> ' . $placePrr['time'] . '</td>
                                            </tr>                             
                                        </table>                
                                    </td>
                                </tr>                      
                            </table>                      
                        </td>
                    </tr>
                    <tr>
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black; width: 25%;">
                        Кол-во рабочих:</td>
                        <td style="line-height: 24px;border: 1px solid black; width: 70%;">' . $numberLoaders . '</td>
                    </tr>
                    <tr style="">
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: right; border: 1px solid black; ">Контакт (на месте):</td>
                        <td style="line-height: 24px;border: 1px solid black; "><table><tr><td style="">' .$placePrr['contact'] . ' </td>
                                    <td> 
                                        <table>
                                            <tr>
                                                <td style="line-height: 24px;border-left: 1px solid black; border-right: 1px solid black; 
                                                    text-align: right; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                                    Тел.: <span> </span>
                                                </td>
                                                <td style="line-height: 24px;"> ' . $placePrr['phone'] . '</td>
                                            </tr>                             
                                        </table>                
                                    </td>
                                </tr>                      
                            </table>                      
                        </td>
                    </tr>';
            }
        }







        $pdf->writeHTMLCell(0, 0, '0', '5', $header, 0, 1, 0, true, '', true);

        $sub_header = '
            <table class="iksweb" >
                <tbody style="text-align:right ;">
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;width: 75%;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Заказчик:</span> ' . str_replace(array('&#171;', '&#187;'), array('«', '»'), $client['ispolnitel'])
            . '</td>
                        <td style="font-size: 11px; line-height: 15px; width: 25%;text-align:right;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Дата заявки:</span> ' . $client['data_zayavka']
            . '</td>                       
                    </tr>
                    <tr>
                        <td style="font-size: 11px; line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Юр. адрес:</span> ' . $client['pochtovyj_adres']
            . '</td>                        
                    </tr>
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">ИНН:</span> ' . $client['inn']
            . '</td>                        
                    </tr>
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Контакт:</span> ' . $client['telefon_fakc']
            . '</td>
                    </tr>
                </tbody>
            </table>
            ';
        $pdf->writeHTMLCell(0, 0, '10', '40', $sub_header, 0, 1, 0, true, '', true);

        $pod_zagolovok = 'ДОГОВОР ЗАЯВКА № '.$title_applications  .' <br>
            <span style="font-size: 11px; font-weight: bold">на выполнение погрузочно-разгрузочных работ</span>';

        $main_table = '
            <div class="" style="text-align: center; line-height: 15px;font-weight:bold">
            '.$pod_zagolovok.'
            
            </div>
            
            <table class="table-info" style="border: 1px solid black; font-size: 11px;"> 
            
                <tbody>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;
                        text-align: right;border: 1px solid black; width: 25%;line-height: 24px">
                        Груз:</td>
                        <td style="border: 1px solid black; width: 70%;line-height: 24px">' . $nature_cargo . '</td>
                    </tr>
                    <tr style="">
                        <td style="line-height: 24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: right; border: 1px solid black; ">Вес: </td>
                        <td style="border: 1px solid black; "><table><tr><td style="line-height: 24px">' .$weight . ' </td>
                                    <td> 
                                        <table>
                                            <tr>
                                                <td style="border-left: 1px solid black; border-right: 1px solid black; 
                                                    text-align: right; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;line-height: 24px">
                                                    Мест: <span> </span>
                                                </td>
                                                <td style="line-height: 24px"> 
                                                ' . $place . '</td>
                                            </tr>                             
                                        </table>                
                                    </td>
                                </tr>                      
                            </table>                      
                        </td>
                    </tr>
                   ' . $loading .$unloading .'
                    
                    
                   <tr>
                        <td style="line-height:24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: right;border: 1px solid black; width: 25%;">
                        Стоимость (руб.):</td>
                        <td style="line-height:24px;border: 1px solid black; width: 70%;">' . $cost . '</td>
                    </tr>
                    <tr>
                        <td style="line-height:24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black; width: 25%;">
                        Условия оплаты:</td>
                        <td style="line-height:24px;border: 1px solid black; width: 70%;">' . $terms_payment . '</td>
                    </tr>
                    <tr>
                        <td style="line-height:24px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black; width: 25%;">
                        Особые условия:</td>
                        <td style="line-height:24px;border: 1px solid black; width: 70%;">' . $special_conditions . '</td>
                    </tr>
                </tbody>
            </table>
            
            <div style="text-align: left; font-size: 9px; " >'
            .''

            .'<div class="" style=" font-family: normal">
            Убедительная просьба всю связь по ЛЮБЫМ вопросом держать с Вашим менеджером. Если будут какие-то новые
                пожелания, условия или дополнительные действия, то эту информацию доносить не до водителя/рабочего, а до менеджера.
                В этом случае мы все обязательно проконтролируем и исполним всё в точности, отталкиваясь от Ваших слов! Если
                информация была донесена только до водителя/рабочего, то мы не можем гарантировать исполнения всех Ваших
                пожеланий, по причине того, что водитель/рабочий может забыть/неправильно понять и так далее.
                Спасибо за понимание, Ваш груз в надежных руках!
            </div>
            </div>
            <style>
            .table-info {
            
            line-height: 15px;
            }
            td{
            padding: 10px;
            }
            </style>';
        $pdf->writeHTMLCell(0, 0, '', '', $main_table, 0, 0, 0, true, '', true);




        $filename = 'client_prr_doc.pdf';
        $result = $pdf->Output(APP_PATH .'/public/doc/' . $filename, 'F');

//        if(! $result)
//            return false;

        return true;
    }

    public function ReceiptServicesDOCX()
    {
        if(! $this->application->id())
            return false;

        $application = $this->application->get();

        $numberReceiptServices = $application['receipt_services_num'];

        if(! $numberReceiptServices)
            $numberReceiptServices = $this->setReceiptServices($this->application->id());

        $phpWord = new PhpWord();

        $customer = new Customer(['id' => $application['customer_id_client']]);

        $customerData = $customer->get();

        $client = new Client(['id' => $application['client_id_client']]);
        $clientData = $client->get();

        $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'receiptServices_template.docx');


        $_doc->setValue('date', date('d.m.Y'));

        $_doc->setValue('dateReceipt', date('d.m.Y'));
        $_doc->setValue('numberReceipt', $numberReceiptServices);

        $_doc->setValue('clientName', $clientData['name']);
        $_doc->setValue('customerName', $customerData['name']);
        $_doc->setValue('numberApp', $application['application_number']);
        $_doc->setValue('dateAppD', date('d', strtotime($application['date'])));
        $_doc->setValue('dateAppM', date('m', strtotime($application['date'])));
        $_doc->setValue('dateAppY', date('Y', strtotime($application['date'])));


        @mkdir($this->pathDocTemplate, 0777);
        $file = str_replace("/", "-", "receiptServices.docx");
        $_doc->saveAs($this->pathDocTemplate . $file);

        return true;
    }

    public function ReceiptServices()
    {
    }

    private function setReceiptServices(int $id): int
    {
        $documentFlow = $this->database->first('document_flow');
        $newReceiptServicesNum = $documentFlow['prr_receipt_services_num'] + 1;

        $this->database->update('document_flow', ['prr_receipt_services_num' => $newReceiptServicesNum]);

        $this->database->update('prr_application', ['receipt_services_num' => $newReceiptServicesNum], ['id' => $id]);

        return $newReceiptServicesNum;
    }

    private function getPRRCompanyData(int $prr_id)
    {
    }
}