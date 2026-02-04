<?php

namespace App\User\Model\Application;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Application\Application;
use App\Model\Client\Client;
use App\Model\Customer\Customer;
use App\Model\Driver\Driver;
use App\Model\User\User;
use App\User\Model\Model;
use TCPDF;
use TCPDF_FONTS;


class AgreementApplicationClient extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private Application $application;

    private int $idApplication = 0;

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
        'традцатое','тридцать первое'
    ];


    public function __construct(int $idApplication = 0){

        $this->idApplication = $idApplication;

        $this->config = new Config();
        $this->database = new Database($this->config);

        if($idApplication > 0){
            $this->application = new Application(['id' => $idApplication]);

        }

    }

    public function getFooterDocument(bool $haveSeal = false,string $seal = '',string $signature = ''): string
    {
        if($haveSeal){
            $tableSeal = '
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
                                                    <td style="width:45px; height:45px;">
                                                        <img style="width:60px; height:60px;" src="' . $signature . '">
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
            $tableSeal = '
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
        return $tableSeal;
    }

    public function AgreementApplicationClient($seal = false)
    {
        $pdf = new MYPDF('', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->SetAutoPageBreak(TRUE, 40);



        $fontname = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/FontsFree-Net-calibri-regular.ttf', 'TrueTypeUnicode', '', 96);
        $fontnameBold = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/CalibriBold.TTF', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 14, '', false);

        $application = $this->application->get();
        $dataCustomer = $this->getCustomerData($application['customer_id_Client']);
        $dataManager = $this->getManagerData($application['id_user']);
        $dataClient = $this->getClientData($application['client_id_Client']);
        $dataDriver = $this->getDriverData($application['driver_id_Client']);

        $dataDriver['issued_date'] = date('d.m.Y', strtotime($dataDriver['issued_date']));

        $adress = $dataCustomer['mailing_address'];
        $INN = $dataCustomer['inn'];
        $isplonitel = $dataCustomer['name'];

        $logoOOO = '<img src="' .APP_PATH .'/public/assets/img/logo.png" alt="1" style="object-fit: cover">';
        $contact = "{$dataManager['surname']} {$dataManager['name']} {$dataManager['lastname']}";
        $telefon = $dataManager['phone'];


        $client = [
            'ispolnitel' => $dataClient['name'],
            'data_zayavka' => '11.06.2024',
            'pochtovyj_adres' => $dataClient['legal_address'],
            'inn' => $dataClient['inn'],
            'telefon_fakc' => $dataClient['phone'],

        ];

        $marshrut = '';
        $nature_cargo = $application['nature_cargo_Client'];
        $weight = $application['weight_Client'];
        $ref_mode = $application['ref_mode_Client'];
        $place = $application['place_Client'];
        $cost_cargo = $application['cost_cargo_Client'];
        $type_transport = $application['type_transport_id_Client'] .', ' .$application['type_carcase_id_Client']; ;
        $address_pogruzki = '';
        $date_pogruzki = '';
        $time_pogruzki = '';

        $address_razgruzki = '';
        $date_razgruzki = '';
        $time_razgruzki = '';

        $transportation_cost = $application['transportation_cost_Client'];
        $terms_payment = $application['terms_payment_Client'];
        $special_conditions = $application['special_conditions_Client'];
        $car = $application['car_brand_id_Client'] .' ' .$application['government_number_Client'];
        $driver_fio = $dataDriver['name'];
        $driver_passport = "{$dataDriver['passport_serial_number']}, выдан {$dataDriver['issued_by']}, 
                дата выдачи {$dataDriver['issued_date']}, код подразделения {$dataDriver['department_code']}";
        $driver_license = $dataDriver['driver_license'];
        $driver_phone = $dataDriver['phone'];



        $pdf->AddPage();


        $header = '
            <table class="iksweb" style="font-size: 11.04px;"  >
                <tbody>
                    <tr>
                        <td rowspan="4" style="width: 30%; height: 30%;">
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
                            </span> ' . $contact
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


        $pdf->writeHTMLCell(0, 0, '0', '5', $header, 0, 1, 0, true, '', true);

        $sub_header = '
            <table class="iksweb" >
                <tbody style="text-align:right ;">
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;width: 75%;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                Заказчик:
                            </span>  ' . str_replace(array('&#171;', '&#187;'), array('«', '»'), $client['ispolnitel'])
                        . '</td>
                        <td style="font-size: 11px; line-height: 15px; width: 25%;text-align:right;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                Дата заявки:
                            </span>  ' . $client['data_zayavka']
                        . '</td>                       
                    </tr>
                    <tr>
                        <td style="font-size: 11px; line-height: 15px;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                Юр. адрес:
                            </span>  ' . $client['pochtovyj_adres']
                        . '</td>                        
                    </tr>
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                ИНН:
                            </span>  ' . $client['inn']
                        . '</td>                        
                    </tr>
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;">
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                Контакт:
                            </span>  ' . $client['telefon_fakc']
                        . '</td>
                    </tr>
                </tbody>
            </table>
            ';
        $pdf->writeHTMLCell(0, 0, '10', '40', $sub_header, 0, 1, 0, true, '', true);

        $pod_zagolovok = 'ДОГОВОР ЗАЯВКА № 1 <br>
            <span style="font-size: 9px; font-weight: normal">Согласно предварительной договоренности просим  Вас осуществить следующую перевозку груза</span>';


        $main_table = '
            <div class="" style="text-align: center; line-height: 15px;">
            '.$pod_zagolovok.'
            
            </div>
            
            <table class="table-info" style="border: 1px solid black; font-size: 11px;"> 
            
                <tbody>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black; width: 30%;">Маршрут перевозки:</td>
                        <td style="border: 1px solid black; width: 70%;">' . $marshrut . '</td>
                    </tr>
                    <tr>
                        <td style="line-height: 13px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">
                            Характер груза,<br> способ погрузки/выгрузки
                        </td>
                        <td style="border: 1px solid black">'
                            . $nature_cargo
                        . '</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: center; border: 1px solid black; ">Вес</td>
                        <td style="border: 1px solid black; ">
                            <table>
                                <tr>
                                    <td style="">' .$weight . ' </td>
                                    <td> 
                                        <table>
                                            <tr>
                                                <td style="border-left: 1px solid black; border-right: 1px solid black; 
                                                    text-align: center; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                                    Реф.режим
                                                </td>
                                                <td>' .$ref_mode . '</td>
                                            </tr>                             
                                        </table>                
                                    </td>
                                </tr>                      
                            </table>                      
                        </td>
                    </tr>
                    <tr style="">
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center; border: 1px solid black">Мест</td>
                        <td style="border: 1px solid black">
                        <table>
                        <tr>
                                <td>' .$place .' </td>
                                <td> 
                                <table>
                                <tr>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; 
                                    text-align: center; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Стоимость груза</td>
                                <td>' . $cost_cargo . '</td>
            </tr>
                             
                                </table>
                         
                                </td>
                            </tr>
                      
                        </table>
                        
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Тип транспорта:</td>
                        <td style="border: 1px solid black">' . $type_transport . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Адрес погрузки:</td>
                        <td style="border: 1px solid black">' . str_replace('\\', '', $body[3]['value']) . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Дата погрузки:</td>
                        <td style="border: 1px solid black">
                        <table>
                        <tr>
                                <td style="width: 40%;">' . substr($body[4]['value'], 0, -4) . ' </td>
                                <td style="width: 60%;"> 
                                <table>
                                <tr>
                                <td style=" text-align: center;width: 50%;  font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Время погрузки</td>
                                <td> ' . substr($body[16]['value'], 0, -30) . '</td>
            </tr>
                             
                                </table>
                         
                                </td>
                            </tr>
                      
                        </table>
                        
                        </td>
                        
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Адрес разгрузки:
            </td>
                        <td style="border: 1px solid black">' . $body[5]['value'] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Дата выгрузки:</td>
                        <td style="border: 1px solid black">
                        <table>
                        <tr>
                                <td style="width: 40%;">' . $body[6]['value'] . ' </td>
                                <td style="width: 60%;"> 
                                <table>
                                <tr>
                                <td style=" text-align: center;width: 50%;  font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Время выгрузки</td>
                                <td>' . $body[17]['value'] . '</td>
            </tr>
                             
                                </table>
                         
                                </td>
                            </tr>
                      
                        </table>
                        
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black; line-height: 13px;">Стоимость перевозки (в 
            рублях):</td>
                        <td style="border: 1px solid black">' . $transportation_cost . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: center ;border: 1px solid black;">Условия оплаты: </td>
                        <td style="border: 1px solid black">' . $terms_payment . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center">Особые условия:</td>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;" style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;border: 1px solid black; text-align: center">
                        ' . $special_conditions . '</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center; border: 1px solid black">Марка, гос.номер машины:</td>
                        <td style="border: 1px solid black">
                        <table>
                        <tr>
                                <td>' . $car . ' </td>
                                <td> 
                                <table>
                                <tr>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center; width: 20%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">П/П</td>
                                <td> ' . $body[11]['value'] . '</td>
            </tr>
                             
                                </table>
                         
                                </td>
                            </tr>
                      
                        </table>
                        
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right ;border: 1px solid black">ФИО водителя:</td>
                        <td style="border: 1px solid black">' . $driver_fio . '</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: left;border: 1px solid black;">Серия и № паспорта, кем выдан, дата выдачи, код подразделения:</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="min-height: 50px;text-align: left;border: 1px solid black; line-height: 16px;">' . $driver_passport . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black">Вод. удостоверение:</td>
                        <td style="border: 1px solid black">' .  $driver_license . '</td>
                    </tr>
                    ' . (!empty([1]) ? '<tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black">Телефон водителя:</td>
                        <td style="border: 1px solid black">' . $driver_phone . '</td>
                    </tr>' : '') . '
                </tbody>
            </table>
            <span style="text-align: center; font-size: 9px;line-height: 0px;">Обязательные условия:</span>
            <div style="text-align: left; font-size: 9px; " >
            Взаимоотношения и ответственность сторон определяются договором на транспортно-экспедиционное обслуживание, ГК РФ, Уставом автомобильного транспорта РФ. 
                                        
            <div class="" style=" font-family: ' . $fontnameBold . '">
            Убедительная просьба всю связь по ЛЮБЫМ вопросом держать с Вашим менеджером. Если будут какие-то новые пожелания, условия или дополнительные действия, то эту информацию доносить не до водителя, а до менеджера . В этом случае мы все обязательно проконтролируем и исполним всё в точности, отталкиваясь от Ваших слов!
            Если информация была донесена только до водителя, то мы не можем гарантировать исполнения всех Ваших пожеланий, по причине того, что водитель может забыть/не правильно понять и так далее. Спасибо за понимание, Ваш груз в надежных руках!)
            </div>
            </div>
            <style>
            .table-info {
            
            line-height: 15px;
            }
            </style>
            ';
        $pdf->writeHTMLCell(0, 0, '', '', $main_table, 0, 0, 0, true, '', true);


        $footer = $this->getFooterDocument();

        $pdf->setFooterData('test');

//        // Position at 15 mm from bottom
//        $pdf->SetY(-40);
//        // Page number
//        $pdf->writeHTMLCell(0, 0, '', '', $footer, 0, 1, 0, true, '', true);

        $filename = 'client_doc.pdf';
        $result = $pdf->Output(APP_PATH .'/public/doc/' . $filename, 'F');

    }


    private function getCustomerData(int $idCustomer)
    {
        $customer = new Customer(['id' => $idCustomer]);

        return $customer->get();
    }

    private function getManagerData(int $idUser)
    {
        $manager = new User(['id' => $idUser]);

        return $manager->get();

    }

    private function getClientData(int $idClient)
    {
        $client = new Client(['id' => $idClient]);

        return $client->get();
    }

    private function getDriverData(int $idDriver)
    {
        $driver = new Driver(['id' => $idDriver]);

        return $driver->get();
    }

}