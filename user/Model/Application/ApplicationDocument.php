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
use App\Model\Carrier\Carrier;
use App\Model\Marshrut\Marshrut;
use App\Model\User\User;
use App\User\Model\Model;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use TCPDF;
use TCPDF_FONTS;
use PhpOffice\PhpWord\TemplateProcessor;


class ApplicationDocument extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private Application $application;

    private int $idApplication = 0;

    private string $pathDocTemplate = APP_PATH .'/public/doc/';

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
            $this->application = new Application(['id' => $idApplication]);

        }

    }

    public function AttorneyM2(string $materialValues = '')
    {
        if(! $this->application->id())
            return false;

        $application = $this->application->get();

        $attorney_title = $application['application_number'];

        $dateStart = date('d.m.Y', strtotime($application['date']));

        $dateEnd = date('d.m.Y', strtotime($application['date'] . '+14 days'));


        $driverData = $this->getDriverData($application['driver_id_Client']);

        $driverInfo = 'водитель 
        ' .$driverData['name'];

        $clientData = $this->getClientData($application['client_id_Client']);


        $driverPassport = explode(' ',$driverData['passport_serial_number']);



        $spreadsheet = new Spreadsheet();

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->pathDocTemplate .'attorney-m2.xlsx');


        $worksheet = $spreadsheet->getActiveSheet();


        $worksheet->getCell('A4')->setValue($attorney_title);
        $worksheet->getCell('M17')->setValue($attorney_title);
        $worksheet->getCell('C4')->setValue($dateStart);
        $worksheet->getCell('G19')->setValue(
            date('d', strtotime($dateStart)) .' ' .$this->months[(int)date('m', strtotime($dateStart)) - 1] . ' '
            .date('Y', strtotime($dateStart)) .' г.'
        );
        $worksheet->getCell('G20')->setValue(
            date('d', strtotime($dateEnd)) .' ' .$this->months[(int)date('m', strtotime($dateEnd)) - 1] . ' '
            .date('Y', strtotime($dateEnd)) .' г.'
        );
        $worksheet->getCell('F4')->setValue($dateEnd);
        $worksheet->getCell('L4')->setValue($driverInfo);
        $worksheet->getCell('A7')->setValue($clientData['name']);
        $worksheet->getCell('E37')->setValue($clientData['name']);
        $worksheet->getCell('O31')->setValue($driverData['name']);
        $worksheet->getCell('E33')->setValue($driverPassport[0] . ' '  . $driverPassport[1]);
        $worksheet->getCell('M33')->setValue($driverPassport[2]);
        $worksheet->getCell('E34')->setValue($driverData['issued_by']);
        $worksheet->getCell('E35')->setValue(date('d.m.Y', strtotime($driverData['issued_date'])));

        $worksheet->getCell('B48')->setValue($materialValues);






        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->pathDocTemplate .'attorneyM2.xlsx');


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

        $customer = new Customer(['id' => $application['customer_id_Client']]);

        $customerData = $customer->get();

        $client = new Client(['id' => $application['client_id_Client']]);
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

    private function getArrayTextLoading(array $arrayLoading): array
    {
        $textLoading = [
            'path' => '',
            'date' => '',
            'time' => ''
        ];


        if(count($arrayLoading) > 1){
            for($i = 0; $i < count($arrayLoading); $i++){
                $textLoading['path'] .= ($i + 1) .')' .$arrayLoading[$i]['path'];
                $textLoading['date'] .= ($i + 1) .') ' .$arrayLoading[$i]['date'];
                $textLoading['time'] .= ($i + 1) .') ' .trim($arrayLoading[$i]['time'], ' - __:__');
                if($i < count($arrayLoading) - 1) {
                    $textLoading['path'] .= '<br>';
                    $textLoading['date'] .= '<br>';
                    $textLoading['time'] .= '<br>';
                }
            }
        }
        else {
            $textLoading['path'] = $arrayLoading[0]['path'];
            $textLoading['date'] = $arrayLoading[0]['date'];
            $textLoading['time'] = trim($arrayLoading[0]['time'], ' - __:__');
        }

        return $textLoading;
    }


    public function AgreementApplicationClient(bool $isSeal = false): bool
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
        $dataCustomer = $this->getCustomerData($application['customer_id_Client']);
        $dataManager = $this->getManagerData($application['id_user']);
        $dataClient = $this->getClientData($application['client_id_Client']);
        $dataDriver = $this->getDriverData($application['driver_id_Client']);

        $showDriverPhone = $application['show_driver_number'];

        $seal = $dataCustomer['link_seal'];
        $signature = $dataCustomer['link_signature'];

        if($application['customer_id_Client'] == 2)
            $pdf->setFooterHTML($this->getFooterHTML($isSeal, $seal, $signature,100));
        else
            $pdf->setFooterHTML($this->getFooterHTML($isSeal, $seal, $signature));

        $dataDriver['issued_date'] = date('d.m.Y', strtotime($dataDriver['issued_date']));

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
            'telefon_fakc' =>  $application['client_chosen_info'],

        ];

        $specialText = 'Взаимоотношения и ответственность сторон определяются договором на транспортно-экспедиционное обслуживание, ГК РФ, Уставом автомобильного транспорта РФ.';

        if($application['client_id_Client'] == 324)
            $specialText = 'Взаимоотношения и ответственность сторон определяются ДОГОВОРОМ ПЕРЕВОЗКИ № 513, ГК РФ, Уставом автомобильного транспорта РФ.';

        $title_applications = $application['application_number'] .' ' .$application['application_title_Client'];

        if($application['application_number'] < 500)
            $title_applications = $application['application_number'] .'-Т' .' ' .$application['application_title_Client'];


//        if($application['id'] == 2870){
//            $title_applications =  '8175/2 ' .$application['application_title_Client'];
//        }

        $nature_cargo = str_replace(array('<p>', '</p>'), array('', ''), $application['nature_cargo_Client']);
        $weight = $application['weight_Client'];
        $ref_mode = $application['ref_mode_Client'];
        $place = $application['place_Client'];
        if($application['cost_cargo_Client'] == 0){
            $cost_cargo = '';
        }
        else {
            $cost_cargo = number_format(
                $application['cost_cargo_Client'],
                0,
                '.',
                ' '
            );
        }
        $type_transport = $application['type_transport_id_Client'] .', ' .$application['type_carcase_id_Client'];

        $transportation_cost = number_format(
            $application['transportation_cost_Client'],
            0,
            '.',
            ' '
        );
        $terms_payment = $application['terms_payment_Client'];
        $special_conditions = $application['special_conditions_Client'];
        $car = $application['car_brand_id_Client'] .' ' . trim($application['government_number_Client'], '_');
        $driver_fio = $dataDriver['name'];
        $driver_passport = mb_strtoupper("{$dataDriver['passport_serial_number']}, выдан {$dataDriver['issued_by']}, 
                дата выдачи {$dataDriver['issued_date']}, код подразделения {$dataDriver['department_code']}");
        $driver_license = $dataDriver['driver_license'];
        $driver_phone = $dataDriver['phone'];

        $semitrailer = trim($application['semitrailer_Client'], '_');

        $routesArray = $this->getRoutes($this->idApplication, 1);

        $routes = '';

        $arrayLoading = [];
        $arrayUnloading = [];

        $arrayLoadingMethod = [];
        $arrayUnloadingMethod = [];

        if(empty($routesArray)){
            $arrayLoading = [['path' => '', 'date' => '', 'time' => '']];
            $arrayUnloading = [['path' => '', 'date' => '', 'time' => '']];
        }


        for($i = 0; $i < count($routesArray); $i++){
            $routes .= $routesArray[$i]['city'];

            if($i < count($routesArray) - 1)
                $routes .= ' - ';

            if($routesArray[$i]['direction'] == 1){
                $contact = '';

                if($routesArray[$i]['contact'] != '' OR $routesArray[$i]['phone'] != ''){
                    $contact = ', Контакт: ' .$routesArray[$i]['contact'] .' ' .$routesArray[$i]['phone'];
                }

                $arrayLoading[] = [
                    'path' => $routesArray[$i]['city'] .', ' .$routesArray[$i]['address'] . $contact,
                    'date' => $routesArray[$i]['date'],
                    'time' => $routesArray[$i]['time']
                ];
                $arrayLoadingMethod[] = $routesArray[$i]['loading_method'];
            }
            else{
                $contact = '';

                if($routesArray[$i]['contact'] != '' OR $routesArray[$i]['phone'] != ''){
                    $contact = ', Контакт: ' .$routesArray[$i]['contact'] .' ' .$routesArray[$i]['phone'];
                }
                $arrayUnloading[] = [
                    'path' => $routesArray[$i]['city'] .', ' .$routesArray[$i]['address'] . $contact,
                    'date' => $routesArray[$i]['date'],
                    'time' => $routesArray[$i]['time']
                ];

                $arrayUnloadingMethod[] = $routesArray[$i]['loading_method'];
            }

        }

        $methodLoading = '';

        foreach (array_unique($arrayLoadingMethod) as $loadingMethod){
            $methodLoading .= $loadingMethod . ', ';
        }

        $methodLoading = trim($methodLoading, ', ') .'/';

        foreach (array_unique($arrayUnloadingMethod) as $loadingMethod){
            $methodLoading .= $loadingMethod . ', ';
        }

        $methodLoading = trim($methodLoading, ', ');

        $nature_cargo .= ', ' .$methodLoading;


        $textLoading = $this->getArrayTextLoading($arrayLoading);

        $textUnloading = $this->getArrayTextLoading($arrayUnloading);
        $pdf->SetAutoPageBreak(TRUE, 40);
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
            <span style="font-size: 9px; font-weight: normal">Согласно предварительной договоренности просим  Вас осуществить следующую перевозку груза</span>';

        if($application['hide_title']){
            $pod_zagolovok = 'ДОГОВОР ЗАЯВКА № '.$title_applications;
        }

        $main_table = '
            <div class="" style="text-align: center; line-height: 15px;">
            '.$pod_zagolovok.'
            
            </div>
            
            <table class="table-info" style="border: 1px solid black; font-size: 11px;"> 
            
                <tbody>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black; width: 30%;">Маршрут перевозки:</td>
                        <td style="border: 1px solid black; width: 70%;">' . $routes . '</td>
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
                        <td style="border: 1px solid black; "><table><tr><td style="">' .$weight . ' </td>
                                    <td> 
                                        <table>
                                            <tr>
                                                <td style="border-left: 1px solid black; border-right: 1px solid black; 
                                                    text-align: center; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">
                                                    Реф.режим
                                                </td>
                                                <td> ' .$ref_mode . '</td>
                                            </tr>                             
                                        </table>                
                                    </td>
                                </tr>                      
                            </table>                      
                        </td>
                    </tr>
                    <tr style="">
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center; border: 1px solid black">Мест</td>
                        <td style="border: 1px solid black"><table><tr><td>' .$place .' </td>
                                <td> 
                                <table>
                                <tr>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; 
                                    text-align: center; width: 45%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Стоимость груза</td>
                                <td> ' . $cost_cargo . '</td>
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
                        <td style="border: 1px solid black">' . $textLoading['path'] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Дата погрузки:</td>
                        <td style="border: 1px solid black"><table>
                        <tr>
                                <td style="width: 40%;">' . $textLoading['date'] . ' </td>
                                <td style="width: 60%;"> 
                                <table>
                                <tr>
                                <td style=" text-align: center;width: 50%;  font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Время погрузки</td>
                                <td> ' . $textLoading['time'] . '</td>
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
                        <td style="border: 1px solid black">' . $textUnloading['path'] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Дата выгрузки:</td>
                        <td style="border: 1px solid black"><table>
                        <tr>
                                <td style="width: 40%;">' . $textUnloading['date'] . ' </td>
                                <td style="width: 60%;"> 
                                <table>
                                <tr>
                                <td style=" text-align: center;width: 50%;  font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Время выгрузки</td>
                                <td>' . $textUnloading['time'] . '</td>
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
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;" style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;border: 1px solid black; text-align: center">' . $special_conditions . '</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center; border: 1px solid black">Марка, гос.номер машины:</td>
                        <td style="border: 1px solid black"><table>
                        <tr>
                                <td>' . $car . ' </td>
                                <td> 
                                <table>
                                <tr>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center; width: 20%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">П/П</td>
                                <td> ' . $semitrailer . '</td>
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
                    ' . ($showDriverPhone ? '<tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black">Телефон водителя:</td>
                        <td style="border: 1px solid black">' . $driver_phone . '</td>
                    </tr>' : '') . '
                </tbody>
            </table>
            <span style="text-align: center; font-size: 9px;line-height: 0px;">Обязательные условия:</span>
            <div style="text-align: left; font-size: 9px; " >'
            .$specialText
                                        
            .'<div class="" style=" font-family: ' . $fontnameBold . '">
            Убедительная просьба всю связь по ЛЮБЫМ вопросом держать с Вашим менеджером. Если будут какие-то новые пожелания, условия или дополнительные действия, то эту информацию доносить не до водителя, а до менеджера . В этом случае мы все обязательно проконтролируем и исполним всё в точности, отталкиваясь от Ваших слов!
            Если информация была донесена только до водителя, то мы не можем гарантировать исполнения всех Ваших пожеланий, по причине того, что водитель может забыть/не правильно понять и так далее. Спасибо за понимание, Ваш груз в надежных руках!)
            </div>
            </div>
            <style>
            .table-info {
            
            line-height: 15px;
            }
            </style>';
        $pdf->writeHTMLCell(0, 0, '', '', $main_table, 0, 0, 0, true, '', true);




        $filename = 'client_doc.pdf';
        $result = $pdf->Output(APP_PATH .'/public/doc/' . $filename, 'F');

//        if(! $result)
//            return false;

        return true;
    }

    public function AgreementApplicationCarrier(bool $isSeal = false): bool
    {
        $pdf = new MYPDF('', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->SetAutoPageBreak(TRUE, 40);


        $fontname = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/FontsFree-Net-calibri-regular.ttf', 'TrueTypeUnicode', '', 96);
        $fontnameBold = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/CalibriBold.TTF', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 14, '', false);

        $application = $this->application->get();
        $dataCustomer = $this->getCustomerData($application['customer_id_Carrier']);
        $dataManager = $this->getManagerData($application['id_user']);
        $dataCarrier = $this->getCarrierData($application['carrier_id_Carrier']);
        $dataDriver = $this->getDriverData($application['driver_id_Carrier']);

        $seal = $dataCustomer['link_seal'];
        $signature = $dataCustomer['link_signature'];

        $dataDriver['issued_date'] = date('d.m.Y', strtotime($dataDriver['issued_date']));

        $adress = $dataCustomer['mailing_address'];
        $INN = $dataCustomer['inn'];
        $isplonitel = $dataCustomer['name'];

        $logoOOO = '<img src="' .APP_PATH .'/public/assets/img/logo.png" alt="1" style="object-fit: cover">';
        if($dataCustomer['id'] != 1){
            $logoOOO = '';
        }

        $contactFaceFIO = "{$dataManager['surname']} {$dataManager['name']} {$dataManager['lastname']}";
        $telefon = $dataManager['phone'];

        $carrier = [
            'name' => $dataCarrier['name'],
            'legal-address' => $dataCarrier['legal_address'],
            'INN' => $dataCarrier['inn'],
            'contact' => $application['carrier_chosen_info'],
            'date-application' => date('d.m.Y', strtotime($application['date'])),
        ];

        $title_applications = $application['application_number'] .' ' .$application['application_title_Carrier'];

        if($application['application_number'] < 500)
            $title_applications = $application['application_number'] .'-Т' .' ' .$application['application_title_Carrier'];
        $routesArray = $this->getRoutes($this->idApplication);

        if($application['id'] == 2870){
            $title_applications =  '8175/2 ' .$application['application_title_Client'];
        }

        $nature_cargo = str_replace(array('<p>', '</p>'), array('', ''), $application['nature_cargo_Carrier']);
        $weight = $application['weight_Carrier'];
        $ref_mode = $application['ref_mode_Carrier'];
        $place = $application['place_Carrier'];

        $type_transport = $application['type_transport_id_Carrier'] .', ' .$application['type_carcase_id_Carrier'];

        $transportation_cost = number_format(
            $application['transportation_cost_Carrier'],
            0,
            '.',
            ' '
        );
        $terms_payment = $application['terms_payment_Carrier'];
        $special_conditions = $application['special_conditions_Carrier'];

        $condition = $application['prerequisites_Carrier'];

        $addition_id = $application['addition'];

        $addition = $this->database->first('addition', ['id' => $addition_id])['description'];

        $car = $application['car_brand_id_Carrier'] .' ' .trim($application['government_number_Carrier'], '_');

        $semitrailer = trim($application['semitrailer_Carrier'], '_');

        $driver_fio = $dataDriver['name'];
        $driver_passport = mb_strtoupper("{$dataDriver['passport_serial_number']}, выдан {$dataDriver['issued_by']}, 
                дата выдачи {$dataDriver['issued_date']}, код подразделения {$dataDriver['department_code']}");
        $driver_license = $dataDriver['driver_license'];
        $driver_phone = $dataDriver['phone'];

        $routes = '';
        $arrayLoading = [];
        $arrayUnloading = [];

        $arrayLoadingMethod = [];
        $arrayUnloadingMethod = [];

        if(empty($routesArray)){
            $arrayLoading = [['path' => '', 'date' => '', 'time' => '']];
            $arrayUnloading = [['path' => '', 'date' => '', 'time' => '']];
        }

        for($i = 0; $i < count($routesArray); $i++){
            $routes .= $routesArray[$i]['city'];

            if($i < count($routesArray) - 1)
                $routes .= ' - ';

            if($routesArray[$i]['direction'] == 1){
                $contact = '';

                if($routesArray[$i]['contact'] != '' OR $routesArray[$i]['phone'] != ''){
                    $contact = ', Контакт: ' .$routesArray[$i]['contact'] .' ' .$routesArray[$i]['phone'];
                }

                $arrayLoading[] = [
                    'path' => $routesArray[$i]['city'] .', ' .$routesArray[$i]['address'] . $contact,
                    'date' => $routesArray[$i]['date'],
                    'time' => $routesArray[$i]['time']
                ];

                $arrayLoadingMethod[] = $routesArray[$i]['loading_method'];
            }
            else{
                $contact = '';

                if($routesArray[$i]['contact'] != '' OR $routesArray[$i]['phone'] != ''){
                    $contact = ', Контакт: ' .$routesArray[$i]['contact'] .' ' .$routesArray[$i]['phone'];
                }
                $arrayUnloading[] = [
                    'path' => $routesArray[$i]['city'] .', ' .$routesArray[$i]['address'] . $contact,
                    'date' => $routesArray[$i]['date'],
                    'time' => $routesArray[$i]['time']
                ];
                $arrayUnloadingMethod[] = $routesArray[$i]['loading_method'];
            }

        }


        $textLoading = $this->getArrayTextLoading($arrayLoading);

        $textUnloading = $this->getArrayTextLoading($arrayUnloading);

        $methodLoading = '';

        foreach (array_unique($arrayLoadingMethod) as $loadingMethod){
            $methodLoading .= $loadingMethod . ', ';
        }

        $methodLoading = trim($methodLoading, ', ') .'/';

        foreach (array_unique($arrayUnloadingMethod) as $loadingMethod){
            $methodLoading .= $loadingMethod . ', ';
        }

        $methodLoading = trim($methodLoading, ', ');

        $nature_cargo .= ', ' .$methodLoading;

        if($application['customer_id_Carrier'] == 2) {
            $pdf->setFooterHTML($this->getFooterCarrierHTML($isSeal, $seal, $signature, 100));
        }
        else
            $pdf->setFooterHTML($this->getFooterCarrierHTML($isSeal, $seal, $signature));
        $pdf->AddPage();
        $header = '
            <table class="iksweb" style="font-size: 11.04px;"  >
                <tbody>
                    <tr>
                        <td rowspan="5" style="width: 30%; height: 30%;">
                         ' .$logoOOO .'
                        </td>
                        <td style=" font-size: 11px; width: auto; line-height: 15px;"
                        ><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;font-family: ' . $fontnameBold . ', serif;">Заказчик:</span> '
                                . str_replace(array('&#171;', '&#187;'), array('«', '»'), $isplonitel) . ',  
                            <span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;font-family: ' . $fontnameBold . ', serif;">
                            ИНН:</span> '
            . $INN
            . '</td> 
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: auto;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Почтовый адрес:</span> ' . $adress
            . '</td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: auto;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Конт.лицо:</span> ' . $contactFaceFIO
            . ',</td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: auto;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Телефон, факс:</span> ' . $telefon
            . ', </td>
                        
                    </tr>
                    <tr>
                    <td style="font-size: 11px; width: auto;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">БУХГАЛТЕРИЯ:</span> +7 (922) 028-37-22, buh@pegas.best</td>
                    </tr>
                </tbody>
            </table>
        ';


        $pdf->writeHTMLCell(0, 0, '0', '5', $header, 0, 1, 0, true, '', true);

        $sub_header = '
            <table class="iksweb" >
                <tbody style="text-align:right ;">
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;width: 75%;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Исполнитель:
                            </span>  ' . str_replace(array('&#171;', '&#187;'), array('«', '»'), $carrier['name'])
            . '</td>
                        <td style="font-size: 11px; line-height: 15px; width: 25%;text-align:right;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Дата заявки:</span> ' . $carrier['date-application']
            . '</td>                       
                    </tr>
                    <tr>
                        <td style="font-size: 11px; line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Юр. адрес: </span>' . $carrier['legal-address']
            . '</td>                        
                    </tr>
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">ИНН:
                            </span>  ' . $carrier['INN']
            . '</td>                        
                    </tr>
                    <tr>
                        <td style="font-size: 11px;line-height: 15px;"><span style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Контакт:
                            </span>  ' . $carrier['contact']
            . '</td>
                    </tr>
                </tbody>
            </table>';

        $pdf->writeHTMLCell(0, 0, '10', '40', $sub_header, 0, 1, 0, true, '', true);

        $main_table = '
            <div class="" style="text-align: center; line-height: 15px;">
            
            ДОГОВОР ЗАЯВКА № '.$title_applications .' <br>
            <span style="font-size: 9px; font-weight: normal">Согласно предварительной договоренности просим Вас осуществить следующую перевозку груза</span>
            
            
            </div>
            
            <table class="table-info" style="border: 1px solid black; font-size: 11px;"> 
            
                <tbody>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black; width: 30%;">Маршрут перевозки:</td>
                        <td style="border: 1px solid black; width: 70%;">' .  $routes . '</td>
                    </tr>
                    <tr>
                        <td style="line-height: 13px;font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Характер груза,<br> способ 
            погрузки/выгрузки</td>
                        <td style="border: 1px solid black">' .  $nature_cargo . '</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center; border: 1px solid black">Вес</td>
                        <td style="border: 1px solid black"><table>
                        <tr>
                                <td>' .  $weight . ' </td>
                                <td> 
                                <table>
                                <tr>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center; width: 35%; font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Реф.режим</td>
                                <td> ' .  $ref_mode . '</td>
            </tr>
                             
                                </table>
                         
                                </td>
                            </tr>
                      
                        </table>
                        
                        </td>
                    </tr>
                    <tr style="">
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center; border: 1px solid black">Мест</td>
                        <td style="border: 1px solid black">' .  $place . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Тип транспорта:</td>
                        <td style="border: 1px solid black">' .  $type_transport . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Адрес погрузки:</td>
                        <td style="border: 1px solid black">' . $textLoading['path'] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Дата погрузки:</td>
                        <td style="border: 1px solid black"><table>
                        <tr>
                                <td style="width: 40%;">' .  $textLoading['date'] . ' </td>
                                <td style="width: 60%;"><table>
                                <tr>
                                <td style=" text-align: center;width: 50%;  font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Время погрузки</td>
                                <td>' . $textLoading['time'] . '</td>
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
                        <td style="border: 1px solid black">' .  $textUnloading['path'] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center;border: 1px solid black">Дата выгрузки:</td>
                        <td style="border: 1px solid black"><table><tr><td style="width: 40%;">' .  $textUnloading['date'] . ' </td>
                                <td style="width: 60%;"> 
                                <table>
                                <tr>
                                <td style=" text-align: center;width: 50%;  font-weight:bold;font-family: ' . $fontnameBold . ', serif;">Время выгрузки</td>
                                <td>' .  $textUnloading['time'] . '</td>
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
                        <td style="border: 1px solid black">' . $transportation_cost  . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;text-align: center ;border: 1px solid black;">Условия оплаты: </td>
                        <td style="border: 1px solid black">' . $terms_payment . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: center">Особые условия:</td>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;" style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;border: 1px solid black; text-align: center">' . $special_conditions  . '</td>
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
                                <td> ' . $semitrailer . '</td>
            </tr>
                             
                                </table>
                         
                                </td>
                            </tr>
                      
                        </table>
                        
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right ;border: 1px solid black">ФИО водителя:</td>
                        <td style="border: 1px solid black">' .  $driver_fio . '</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: left;border: 1px solid black;">Серия и № паспорта, кем выдан, дата выдачи, код подразделения:</td>
                    </tr>
                    ' .'
                    <tr>
                        <td colspan="2" style="text-align: left;border: 1px solid black; line-height: 16px;">' .  $driver_passport. '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black">Вод. удостоверение:</td>
                        <td style="border: 1px solid black">' .  $driver_license . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;font-family: ' . $fontnameBold . ', serif;;text-align: right;border: 1px solid black">Телефон водителя:</td>
                        <td style="border: 1px solid black">' .  $driver_phone . '</td>
                    </tr>
                </tbody>
            </table>
            
            <style>
            .table-info {
            
            line-height: 15px;
            }
            
            </style>
            ';


        $pdf->writeHTMLCell(0, 0, '', '', $main_table, 0, 1, 0, true, '', true);

        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 14, '', false);
        $conditionPage = '
            <span style="text-align: center; font-size: 9px;line-height: 18px;">Обязательные условия:</span>
            <div style="text-align: left; font-size: 8px; " >
            '. $condition .'
            </div>';
        $pdf->writeHTMLCell(0, 0, '10', '0', $conditionPage, 0, 1, 0, true, '', true);

        $pdf->AddPage();

        $additionPage = '            
            <div style="text-align: left; font-size: 8px; " >
            '. $addition .'
            </div>
            
            ';
        $pdf->SetFont('dejavusans', '', 14, '', false);
        $pdf->writeHTMLCell(0, 0, '10', '0', $additionPage, 0, 1, 0, true, '', true);
        $pdf->SetFont($fontname, '', 11, '', false);


        $result = $pdf->Output(APP_PATH . '/public/doc/carrier_doc.pdf', 'F');
        return true;
    }


    public function DriverAttorney(bool $isSeal = false, bool $isSignatureDriver = false): bool
    {
        $pdf = new TCPDF('', 'mm', 'A4', true, 'UTF-8', true);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $fontname = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/FontsFree-Net-calibri-regular.ttf', 'TrueTypeUnicode', '', 96);
        $fontnameBold = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/CalibriBold.TTF', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 14, '', false);
        $pdf->AddPage();

        if(! $this->application)
            return false;

        $application = $this->application->get();

        $attorneyTFBI = false;
        $pdf->rollbackTransaction(true);
        if($application['client_id_Client'] == 181 OR $application['client_id_Client'] == 254 OR $application['client_id_Client'] == 271){
            $attorneyTFBI = true;
        }

        if(! $attorneyTFBI) {

            if ($application['attorney_number'] > 0)
                $attorney_title = $application['attorney_number'];
            else
                $attorney_title = $this->setAttorneyNumber($this->application->id());

            $date_today = date('d ') . $this->months[date('m') - 1] . date(' Y ');
            $dataCustomer = $this->getCustomerData($application['customer_id_Carrier']);

            $dataDriver = $this->getDriverData($application['driver_id_Carrier']);

            $dataDriver['issued_date'] = date('d.m.Y', strtotime($dataDriver['issued_date']));

            $zagolovok = $application['proxy_Client'];

            if($zagolovok == '') {
                $zagolovok = '№' . $application['application_number'];

                if($application['application_number'] < 500)
                    $zagolovok = '№' . $application['application_number'] . '-T';

                if($application['application_number'] == 8267){
                    $zagolovok = ' Лесосибирск-Новосибирск 3';
                }
            }
            $date = date('Y-m-d');
            $monthNum = date('n', strtotime($date . '+10 days',)) - 1;

            $date_attorney_end = date('d ', strtotime($date . '+10 days')) . $this->months[$monthNum] . date(' Y', strtotime($date . '+10 days',));

            $initcial = $dataCustomer['initials'];

            if ($dataCustomer['id'] == 1) {
                $text = 'Настоящей доверенностью ' . $dataCustomer['name'] . ', ИНН ' . $dataCustomer['inn']
                    . ', юридический адрес: ' . $dataCustomer['legal_address']
                    . ', в лице директора Часовникова Александра Вадимовича, действующего на основании Устава, доверяет водителю – экспедитору';
            } else
                $text = 'Настоящей доверенностью ' . $dataCustomer['name']
                    . ', ИНН ' . $dataCustomer['inn'] . ', юридический адрес: ' . $dataCustomer['legal_address']
                    . ',  действующего на основании Устава, доверяет водителю – экспедитору';


            $title = '<h1 style="text-align: center">Доверенность № ' . $attorney_title . '</h1>';
            $pdf->writeHTMLCell(0, 0, '25', '10', $title, 0, 1, 0, true, '', false);
            $html = '<br><br>
                <table class="iksweb" style="padding-top: 80px; padding-left: 80px;">
                    <tbody>
                        <tr>
                            <td style="text-align: left; font-size: 12px;">г. Екатеринбург</td>
                            <td style="text-align: right; font-size: 12px;">
                            ' . $date_today . 'г.
                    
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="iksweb" style="text-align: left; padding-left: 80px; padding-top: 10px; font-size: 12px;">
                    <tbody>
                        <tr>
                            <td>' . $text . ', ФИО: ' . $dataDriver['name'] . ', паспортные данные: ' . $dataDriver['passport_serial_number'] . ' ,
                                выдан ' . $dataDriver['issued_by'] . ', дата выдачи:
                                ' . $dataDriver['issued_date'] . ', код подразделения ' . $dataDriver['department_code'] . '. Забор груза и перевозку товара по заявке ' . $zagolovok . '.
                                <br><br>
                                Настоящая доверенность выдана без права передоверия сроком на 10 дней и
                                действительна до ' . $date_attorney_end . ' года включительно.
                            </td>                         
                        </tr>
                    </tbody>
                </table>
    
                <table class="iksweb" style="padding-top: 50px; padding-left: 80px;">
                    <tbody>
                        <tr>
                            <td style="text-align: left; font-size: 12px; width: 70%;">' . $dataCustomer['name'] . '</td>                
                            <td style="text-align: right; font-size: 12px; width: 30%;">
                                ' . $initcial . '
                            </td>
                        </tr>
                    </tbody>
                </table>';

            if ($isSignatureDriver)
                $html .= '<br><br><br><br><br><br>        
                    <table class="iksweb" style="padding-top: 50px; padding-left: 80px;">
                        <tbody>
                            <tr>
                                <td style="text-align: left; font-size: 12px; width: 100%;">
                                    ' . $dataDriver['name'] . ' _____________________________ (подпись)                        
                                </td>
                            </tr>
                        </tbody>
                    </table>';

            $pdf->writeHTMLCell(0, 0, '0', '0', $html, 0, 1, 0, true, '', false);


            if ($isSeal) {
                if($application['customer_id_Carrier'] == 2){
                    $signatureHTML = '<img style="width:100px; height:100px;" src="' . $dataCustomer['link_signature'] . '">';
                }
                else{
                    $signatureHTML = '<img style="width:45px; height:45px;" src="' . $dataCustomer['link_signature'] . '">';
                }
                $sealHTML = '<img style="width:100px; height: 100px;" src="' . $dataCustomer['link_seal'] . '">';

                $pdf->writeHTMLCell(0, 0, '100', '120', $signatureHTML, 0, 1, 0, true, '', true);
                $pdf->writeHTMLCell(0, 0, '125', '120', $sealHTML, 0, 1, 0, true, '', true);
            }

//            $result = $pdf->Output(APP_PATH . '/public/doc/doverenost.pdf', 'F');

//            if ($result)
//                return false;
        }
        else{
            $isplonitel = "ИП Часовников Александр  Вадимович";
            $dataDriver = $this->getDriverData($application['driver_id_Carrier']);

            $dataCustomer = $this->getCustomerData($application['customer_id_Carrier']);

            $dataDriver['issued_date'] = date('d.m.Y', strtotime($dataDriver['issued_date']));

            $dataApp = date('Y-m-d' ,strtotime($application['date']));

            $textClient = ' ООО «ТФБИ» (ИНН 7202251472, КПП 720301001)';

            if($application['client_id_Client'] == 271){
                $textClient = ' ООО «ДЕФИС» (ИНН7203387860,КПП 720301001)';
            }

            if ($application['attorney_number'] > 0)
                $attorney_title = $application['attorney_number'];
            else
                $attorney_title = $this->setAttorneyNumber($this->application->id());

            $zagolovok = $application['proxy_Client'];
            $legalAddress1 = '620023, г. Екатеринбург,';
            $legalAddress2 = 'мкр. Светлый, д.6, кв. 242';

            if(strtotime($dataApp) > strtotime('2025-08-07')) {
                $legalAddress1 = '620142, Свердловская область,';
                $legalAddress2 = 'г. Екатеринбург, ул. Белинского, д. 156, кв. 96';
            }

            $header = '
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                ИП Часовников Александр Вадимович
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                Юридический адрес: ' .$legalAddress1 . '
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                ' .$legalAddress2 .'
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                Телефон/факс:+7 (982) 301 – 18 – 52
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                ИНН:661221186335
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                ОГРНИП:321665800199349
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                Расчётный счет: 40802810338230004775
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                Корреспондентский счет:30101810100000000964
                            </td>
                        </tr>
                        <tr style="height: 12px">
                            <td style="width: 45%;line-height: 12px;"></td>
                            <td style="text-align: right; font-size: 10px;line-height: 12px;">
                                БИК банка: 046577964
                            </td>
                        </tr>
                    </tbody>  
                </table>
            ';

            $pdf->writeHTMLCell(0,0,'25','20', $header, 0, 1, 0, true, '', false);

            $topLine = "<hr>";
            $pdf->writeHTMLCell(0, 0, '25', '60', $topLine, 0, 1, 0, true, '', false);
            $date_full = $this->days[date('d', strtotime($dataApp.'+10days')) - 1] ." " . $this->months[date('m', strtotime($dataApp.'+10days')) - 1]
                . " две тысячи ". $this->years[date('y', strtotime($dataApp.'+10days'))] ." года";


            $title = '<h1 style="text-align: center; font-size: 14px"><b>Доверенность № ' . $attorney_title
                . '</b></h1><p style="text-align:center; font-style: italic; font-size: 14px"> <i> город Екатеринбург, '
                .$this->days[date('d', strtotime($dataApp)) - 1] ." " .$this->months[date('m',strtotime($dataApp)) - 1]
                .' две тысячи ' .$this->years[date('y', strtotime($dataApp))] .' года </i> </p>';
            $pdf->writeHTMLCell(0, 0, '25', '65', $title, 0, 1, 0, true, '', false);

            $html = '<p>Настоящей доверенностью Индивидуальный предприниматель Часовников Александр Вадимович в лице 
                Часовникова Александра Вадимовича, действующий на основании Уведомления о постановке на учет физического лица в налоговом органе
                 № 626181993 от 12.11.2021 г., выданного ИФНС России по Верх-Исетскому району г. Екатеринбурга, уполномочивает</p>
                <div>водителя-экспедитора ' .$dataDriver['name'] .', паспортные данные: ' .$dataDriver['passport_serial_number']
                .', выдан ' .$dataDriver['issued_by'] .', дата выдачи: ' .$dataDriver['issued_date'] .', код подразделения ' . $dataDriver['department_code'] .'</div>
                <div style="padding-bottom: 30px">на получение и транспортировку грузов от ' .$textClient .' по Заявке ' .$zagolovok
                .' подписание документов, связанных с получением грузов, а также получать на руки и передавать 
                    сопроводительные документы на груз, получать необходимую информацию о грузе.
                </div>
                <div>Доверенность выдана сроком по ' .$date_full .' без права передоверия полномочий по настоящей доверенности другим лицам.</div>
        
                <table class="iksweb" style="padding-top: 25px;">
                <tbody>
                    <tr>
                        <td style="width: 3%"> </td>
                        <td style="text-align: left; font-size: 14px; width: 70%;">' . $isplonitel . '</td>
                        
                    </tr>
                </tbody>
            </table>
                ';

            if ($isSignatureDriver)
                $html .= '<br><br><br><br><br><br>        
                    <table class="iksweb" style="padding-top: 50px; padding-left: 80px;">
                        <tbody>
                            <tr>
                                <td style="text-align: left; font-size: 12px; width: 100%;">
                                    ' . $dataDriver['name'] . ' _____________________________ (подпись)                        
                                </td>
                            </tr>-
                        </tbody>
                    </table>';
            $pdf->writeHTMLCell(0, 0, '25', '90', $html, 0, 1, 0, true, '', false);

            if ($isSeal) {
                $signatureHTML = '<img style="width:45px; height:45px;" src="' . $dataCustomer['link_signature'] . '">';
                $sealHTML = '<img style="width:100px; height: 100px;" src="' . $dataCustomer['link_seal'] . '">';

                $pdf->writeHTMLCell(0, 0, '120', '225', $signatureHTML, 0, 1, 0, true, '', true);
                $pdf->writeHTMLCell(0, 0, '145', '215', $sealHTML, 0, 1, 0, true, '', true);
            }
        }



        $result = $pdf->Output(APP_PATH . '/public/doc/doverenost.pdf', 'F');

        return true;
    }


    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function DriverAttorneyDOCX(bool $isSeal = false, bool $isSignatureDriver = false)
    {
        $phpWord = new PhpWord();

        if(! $this->application)
            return false;

        $application = $this->application->get();


        $dataCustomer = $this->getCustomerData($application['customer_id_Carrier']);
        $dataDriver = $this->getDriverData($application['driver_id_Carrier']);

        $attorneyTFBI = false;

        if($application['client_id_Client'] == 181 OR $application['client_id_Client'] == 254
            OR $application['client_id_Client'] == 271){
            $attorneyTFBI = true;
        }


        if($isSignatureDriver){
            if($isSeal):
                switch ($dataCustomer['id']):
                    case 1:
                        try {
                            $_doc = new TemplateProcessor($this->pathDocTemplate . 'attorney_podpis.docx');
                        } catch (CopyFileException $e) {
                            return $e;
                        } catch (CreateTemporaryFileException $e) {
                            return $e;
                        }
                        break;
                    case 3:
                    case 2:
                        $_doc = new TemplateProcessor($this->pathDocTemplate .'attorney_IP_podpis.docx');
                        break;
                endswitch;
            else:
                $_doc = new TemplateProcessor($this->pathDocTemplate .'attorney_not_podpis.docx');
            endif;
        }
        else{
            if($isSeal):
                switch ($dataCustomer['id']):
                    case 1:
                        $_doc = new TemplateProcessor($this->pathDocTemplate .'attorney.docx');
                        break;
                    case 2:
                        $_doc = new TemplateProcessor($this->pathDocTemplate .'attorney_IP.docx');
                        break;
                    case 3:
                        $_doc = new TemplateProcessor($this->pathDocTemplate .'attorney_IP2.docx');
                        break;
                endswitch;
            else:
                $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'attorney_not.docx');
            endif;
        }

        if($application['attorney_number'] > 0)
            $attorney_title = $application['attorney_number'];
        else
            $attorney_title = $this->setAttorneyNumber($this->application->id());

        if($attorneyTFBI){
            $postfix = '';
            if(strtotime($application['date']) > strtotime('2025-08-07'))
                $postfix = '_new';
            if($isSeal):
                $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'attorney_tfbi_IP2_podpis'.$postfix .'.docx');
            else:
                $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'attorney_tfbi_IP2'.$postfix .'.docx');
            endif;
        }

        $data = date('Y-m-d');
        $monthNum = date('n', strtotime($data . '+10 days')) - 1;
        $date = date('d ') . $this->months[date('m') - 1] . date(' Y ');
        $full_date = $this->days[date('d', strtotime($data)) - 1] ." ".$this->months[$monthNum];
        $full_date_year = $this->years[date('y', strtotime($data))];
        $date_today = date('d ') . $this->months[date('m') - 1] . date(' Y ');
        $date_dover = $this->days[date('d', strtotime($data.'+10 days')) - 1] .' ' .$this->months[$monthNum] .' ';
        $date_dover_year = $this->years[date('y', strtotime($data.'+10 days'))];
        $date_dmy = date('d.m.Y', strtotime($data));

        $textClient = 'ООО «ТФБИ» (ИНН 7202251472, КПП 720301001)';

        if($application['client_id_Client'] == 271){
            $textClient = 'ООО «ДЕФИС» (ИНН 7203387860, КПП 720301001)';
        }

        $date_attorney_end = date('d ', strtotime($data.'+10 days')) . $this->months[$monthNum] . date(' Y', strtotime($data.'+10 days',));
        $initcial = $dataCustomer['initials'];

        $text = 'Настоящей доверенностью ' . $dataCustomer['name'] . ', ИНН ' . $dataCustomer['inn']
            . ', юридический адрес: ' . $dataCustomer['legal_address'] . ',  действующего на основании Устава, доверяет водителю – экспедитору';

        if($dataCustomer['id'] == 1) {
            $text = 'Настоящей доверенностью ' . $dataCustomer['name'] . ', ИНН ' . $dataCustomer['inn']
                . ', юридический адрес: ' . $dataCustomer['legal_address']
                . ', в лице директора Часовникова Александра Вадимовича, действующего на основании Устава, доверяет водителю – экспедитору';
        }

        if($attorneyTFBI){
            $text = 'Настоящей доверенностью Индивидуальный предприниматель Часовников Александр Вадимович в лице '
            .'Часовникова Александра Вадимовича, действующий на основании Уведомления о постановке на учет физического лица в'
            .'налоговом органе № 626181993 от 12.11.2021 г., выданного ИФНС России по Верх-Исетскому району г. Екатеринбурга, уполномочивает ';

        }

        $zagolovok = $application['proxy_Client'];

        if($attorneyTFBI){
            $dataApp = date('Y-m-d' ,strtotime($application['date']));
            $monthApp = date('n', strtotime($dataApp . '+10 days',)) - 1;


            $_doc->setValue('text_client', $textClient);
        }

        $_doc->setValue('number', $attorney_title);

        $_doc->setValue('date', $date);

        if($attorneyTFBI) {
            $_doc->setValue('full_date', $this->days[date('d', strtotime($dataApp)) - 1] . " " . $this->months[date('n', strtotime($dataApp))-1]);

            $_doc->setValue('full_date_year', $full_date_year);
        }
        else {
            $_doc->setValue('full_date', $full_date);
        }
        $_doc->setValue('date_dover', $date_dover);
        $_doc->setValue('date_dover_year', $date_dover_year);
        $_doc->setValue('date_dmy', $date_dmy);
        $dataDriver['issued_date'] = date('d.m.Y', strtotime($dataDriver['issued_date']));
        $_doc->setValue('text', $text);
        $_doc->setValue('FIO',$dataDriver['name']);
        $_doc->setValue('seriyanomer', $dataDriver['passport_serial_number']);
        $_doc->setValue('kemvydan',$dataDriver['issued_by']);
        $_doc->setValue('datavidachi',$dataDriver['issued_date']);
        $_doc->setValue('kod', $dataDriver['department_code']);
        $_doc->setValue('zagolovok',$zagolovok);
        $_doc->setValue('dokakogo',$date_attorney_end);
        $_doc->setValue('isplonitel',$dataCustomer['name']);
        $_doc->setValue('initcial',$initcial);
        $_doc->setValue('podpis', 'test');
        $file = str_replace("/", "-", "doverenost.docx");

        $_doc->saveAs($this->pathDocTemplate . $file);

        return true;
    }

    // todo доделать номер экспедиторской расписки и инициалы Customer
    public function ForwardingReceipt(bool $isSeal = false): bool
    {
        if(! $this->application->id())
            return false;

        $application = $this->application->get();

        $numberForwardingReceipt = $application['forwarding_receipt'];

        if(! $numberForwardingReceipt)
            $numberForwardingReceipt = $this->setForwardingReceipt($this->application->id());

        $phpWord = new PhpWord();

        $customer = new Customer(['id' => $application['customer_id_Client']]);

        $customerData = $customer->get();

        $client = new Client(['id' => $application['client_id_Client']]);
        $clientData = $client->get();

        if($isSeal){
            if($customerData['id'] == 1){
                $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'template.docx');
            }
            elseif ($customerData['id'] == 2){
                $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'template_IP.docx');
            }
            else{
                $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'template_IP2.docx');
            }
        }
        else{
            $_doc = new \PhpOffice\PhpWord\TemplateProcessor($this->pathDocTemplate .'template_not.docx');
        }



        $_doc->setValue('date', date('d.m.Y'));

        $_doc->setValue('client_title', $clientData['name']);
        $_doc->setValue('client_inn', $clientData['inn']);
        $_doc->setValue('client_kpp', 'На разработке');
        $_doc->setValue('client_adress', $clientData['legal_address']);


        $_doc->setValue('mest', $application['place_Client']);
        $_doc->setValue('ves', $application['weight_Client']);
        $_doc->setValue(
            'price',
            number_format($application['transportation_cost_Client'],0,'.',
            ' ') .' '
        );

        $_doc->setValue(
            'OOO/IP',
            $customerData['name'] . ', ИНН ' . $customerData['inn'] . ', ' . $customerData['mailing_address']);
        if($clientData['id'] > 1)
            $_doc->setValue('OOO/IP-footer', 'Директор');
        else
            $_doc->setValue('OOO/IP-footer', 'Генеральный директор '. $customerData['contact_face']);

        $signature = $customerData['link_signature'];
        $seal = $customerData['link_seal'];
        $_doc->setValue('initcial', $customerData['initials']);
        $_doc->setValue('nomer', $numberForwardingReceipt);

        @mkdir($this->pathDocTemplate, 0777);
        $file = str_replace("/", "-", "raspiska.docx");
        $_doc->saveAs($this->pathDocTemplate . $file);

        return true;
    }


    public function Insurance(): bool
    {

        if(! $this->application->id())
            return false;

        $application = $this->application->get();

        $name_application = 'Заявка №' .$application['application_number'];


        if($application['application_number'] < 500)
            $name_application = 'Заявка №' .$application['application_number'] .'-Т';

        $carrier  = new Carrier(['id' => $application['carrier_id_Carrier']]);

        $client = new Client(['id' => $application['client_id_Client']]);

        $driverClient = new Driver(['id' => $application['driver_id_Client']]);

        $routesDB = $this->database->select('routes',['id_application' => $application['id'], 'type_for' => 1] , ['sort' => 'ASC'], -1, ['id'] );
        $textRoutes = '';

        $data_pogruzki = '';
        $data_vigruzki = '';

        foreach ($routesDB as $route) {
            $temp = new Marshrut(['id' => $route['id']]);
            $data = $temp->get();

            $textRoutes .= $data['city'] . ' - ';

            if($data_pogruzki == '' AND $data['direction'] == 1)
                $data_pogruzki = $data['date'];

            if($data['direction'] == 0)
                $data_vigruzki = $data['date'];
        }

        $textRoutes = trim($textRoutes);

        $name_carrier = $carrier->get()['name'];
        $inn_carrier = $carrier->get()['inn'];

        $stoimost_perevozki = number_format(
            $application['cost_cargo_Client'],
            0,
            '.',
            ' '
        );
        $harakter_gruza = $application['nature_cargo_Client'];
        $vid_nalogooblozheni = $application['taxation_type_Carrier'];
        $vid_nalogooblozheni_options = 'vid_nalogooblozheni_options';
        $mest = $application['place_Client'];
        $ves = $application['weight_Client'];
        $ref_rezhim = $application['ref_mode_Client'];


        $marka = $application['car_brand_id_Client'] ;
        $gos_nomer = trim($application['government_number_Client'], '_');
        $tip_transporta = $application['type_transport_id_Client'];
        $polupriczep = trim($application['semitrailer_Client'] , '_');



        $spreadsheet = new Spreadsheet();

        switch ($application['customer_id_Client']):
            case 1:
                $title = '№ ';
                $title_type = 'ООО ';

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->pathDocTemplate .'template.xlsx');
                break;
            case 2:
                $title = '№ ';
                $title_type = 'ИП ';

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->pathDocTemplate .'template_IP.xlsx');
                break;
            case 3:
                $title = '№ ';
                $title_type = 'ИП';

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->pathDocTemplate .'template_IP_2.xlsx');
                break;

        endswitch;

        $worksheet = $spreadsheet->getActiveSheet();

        $dateArray = explode(' - ',$data_vigruzki);

        if(count($dateArray) > 1){
            $data_vigruzki = $dateArray[1];
        }


        $worksheet->getCell('A9')->setValue(str_replace(array('&#171;', '&#187;', '&nbsp;'), array('«', '»', ''), $title));
        $worksheet->getCell('B9')->setValue(str_replace(array('&#171;', '&#187;', '&nbsp;'), array('«', '»', ''), $title_type));
        $worksheet->getCell('C9')->setValue(str_replace(array('&#171;', '&#187;', '&nbsp;'), array('«', '»', ''), $client->get()['name']));
        $worksheet->getCell('D9')->setValue(date('d.m.Y'));
        $worksheet->getCell('E9')->setValue($data_pogruzki);
        $worksheet->getCell('F9')->setValue(date('d.m.Y', strtotime($data_vigruzki . '+10 days')));
        $worksheet->getCell('G9')->setValue('Авто');
        $worksheet->getCell('H9')->setValue(str_replace(array('&#171;', '&#187;', '&nbsp;'), array('«', '»', ''), $textRoutes));
        $worksheet->getCell('I9')->setValue($stoimost_perevozki);
        $worksheet->getCell('J9')->setValue('Рубли');
        $worksheet->getCell('K9')->setValue('Нет');
        $worksheet->getCell('L9')->setValue(str_replace(array('&#171;', '&#187;', '&nbsp;','<p>','</p>'), array('«', '»', '', '', ''), $harakter_gruza));
        $worksheet->getCell('M9')->setValue($mest);
        $worksheet->getCell('N9')->setValue($ves);
        $worksheet->getCell('O9')->setValue($name_application);
        $worksheet->getCell('P9')->setValue($ref_rezhim);
        $worksheet->getCell('Q9')->setValue($gos_nomer. ' ' . str_replace(array('&#171;', '&#187;', '&nbsp;'), array('«', '»', ''), $driverClient->get()['name']));
        $worksheet->getCell('R9')->setValue(str_replace(array('&#171;', '&#187;', '&nbsp;'), array('«', '»', ''), $name_carrier). '. ИНН: ' .$inn_carrier);



        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->pathDocTemplate .'books.xlsx');

        return true;
    }

    public function InfoDriver(): bool
    {
        if(! $this->application->id())
            return false;

        $application = $this->application->get();

        $phpWord = new  PhpWord();
        $_doc = new \PhpOffice\PhpWord\TemplateProcessor(
            $this->pathDocTemplate .'template_info-driver.docx'
        );

        $driver = new Driver(['id' => $application['driver_id_Client']]);
        $driverData = $driver->get();

        $gos_nomer = trim($application['government_number_Client'], '_');
        $polupriczep = trim($application['semitrailer_Client'] , '_');
        $marka = $application['car_brand_id_Client'];

        $dataOtkuda = $this->database->first(
            'routes',
            [
                'id_application' => $application['id'],
                'type_for' => 1,
                'direction' => 1
            ],
            ['date']
        )['date'];

        $dataKuda = $this->database->select(
            'routes',
            [
                'id_application' => $application['id'],
                'type_for' => 1,
                'direction' => 0
            ],
            ['sort' => 'DESC'],
            1,
            ['date']
        )[0]['date'];
        $telefon = false;
        if($application['show_driver_number'])
            $telefon = $driverData['phone'];

        $_doc->setValue('fio', $driverData['name']);
        $_doc->setValue('passport', 'Серия/Номер: ' . $driverData['passport_serial_number']
            .'. Дата выдачи: ' . date('d.m.Y', strtotime($driverData['issued_date'])) . '. Кем выдан: ' . $driverData['issued_by']
            . '. Код подразделения ' . $driverData['department_code']);
        $_doc->setValue('vod_ud', $driverData['driver_license']);
        $_doc->setValue('marka', $marka);
        $_doc->setValue('nomer_avto', $gos_nomer);
        $_doc->setValue('nomer_pp', $polupriczep);
        $_doc->setValue('data_zagruzki', $dataOtkuda);
        $_doc->setValue('data_vigruzki', $dataKuda);
        if($telefon) {
            $_doc->setValue('telefon', 'Номер водителя: '. $telefon);
        }else{
            $_doc->setValue('telefon', '');
        }

        @mkdir($this->pathDocTemplate, 0777);
        $file = str_replace("/", "-", "info-driver.docx");
        $_doc->saveAs($this->pathDocTemplate . $file);

        return true;
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


    private function getFooterCarrierHTML(bool $isSeal = false, string $seal = '', string $signature = '', int $sizeSignature = 60): string
    {
        $html = '';

        if($isSeal){
            $html = '
                <table class="iksweb" style="padding-top: 5px;height: 100px;">
                    <tbody>
                        <tr>
                        <td style="text-align: left; font-size: 11px;">ИСПОЛНИТЕЛЬ:</td>
                        <td style="text-align: center; font-size: 11px;">
                        <table>
                        <tr>
                                <td style="width: 25%;">ЗАКАЗЧИК: </td>
                                <td style="width: 75%;"> 
                                <table>
                                <tr>
                                <td style="width:' .$sizeSignature .'px; height:' .$sizeSignature .'px;"><img style="width:' .$sizeSignature .'px; height:' .$sizeSignature .'px;" src="' . $signature . '"></td>
                                <td style="width:100px; height: 100px;"> <img style="width:125px; height: 125px;" src="' . $seal . '"></td>
                        </tr>
                             
                                </table>
                         
                                </td>
                            </tr>
                      
                        </table>
                        
                        
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

    private function getCustomerData(int $idCustomer)
    {
        $customer = new Customer(['id' => $idCustomer]);

        return $customer->get();
    }

    // если $typeFor = 0 тогда берутся маршруты со стороны перевозчика, если 1, то со стороны клиента
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

    private function getManagerData(int $idUser): array
    {
        $manager = new User(['id' => $idUser]);

        return $manager->get();

    }

    private function getCarrierData(int $idCarrier)
    {
        $carrier = new Carrier(['id' => $idCarrier]);

        return $carrier->get();
    }

    private function getClientData(int $idClient): array
    {
        $client = new Client(['id' => $idClient]);

        return $client->get();
    }

    private function getDriverData(int $idDriver): array
    {
        $driver = new Driver(['id' => $idDriver]);

        return $driver->get();
    }

    private function setAttorneyNumber(int $id): int
    {
        $documentFlow = $this->database->first('document_flow');
        $newAttorneyDriverNum = $documentFlow['attorney_driver_num'] + 1;

        $this->database->update('document_flow', ['attorney_driver_num' => $newAttorneyDriverNum]);

        $this->database->update('applications', ['attorney_number' => $newAttorneyDriverNum], ['id' => $id]);

        return $newAttorneyDriverNum;
    }

    private function setForwardingReceipt(int $id): int
    {
        $documentFlow = $this->database->first('document_flow');
        $newForwardingReceiptNum = $documentFlow['forwarding_receipt_num'] + 1;

        $this->database->update('document_flow', ['forwarding_receipt_num' => $newForwardingReceiptNum]);

        $this->database->update('applications', ['forwarding_receipt' => $newForwardingReceiptNum], ['id' => $id]);

        return $newForwardingReceiptNum;
    }

    private function setReceiptServices(int $id): int
    {
        $documentFlow = $this->database->first('document_flow');
        $newReceiptServicesNum = $documentFlow['receipt_services_num'] + 1;

        $this->database->update('document_flow', ['receipt_services_num' => $newReceiptServicesNum]);

        $this->database->update('applications', ['receipt_services_num' => $newReceiptServicesNum], ['id' => $id]);

        return $newReceiptServicesNum;
    }



}