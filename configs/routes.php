<?php

use App\Admin\Controller\Common\AdditionsController;
use App\Admin\Controller\Common\ConditionsController;
use App\Admin\Controller\Common\CustomerController;
use App\Admin\Controller\Common\DocumentFlowController;
use App\Admin\Controller\Common\TermsPaymentsController;
use App\Admin\Controller\Common\TransportController;
use App\Admin\Controller\Common\UsersController;
use App\Admin\Middleware\AdminMiddleware;
use App\Router\Route;
use App\User\Contoller\Common\AnalyticsController;
use App\User\Contoller\Common\ApplicationController;
use App\User\Contoller\Common\ApplicationDocumentController;
use App\User\Contoller\Common\BaseController;
use App\User\Contoller\Common\BonusController;
use App\User\Contoller\Common\CalculatorController;
use App\User\Contoller\Common\HistoryController;
use App\User\Contoller\Common\HomeController;
use App\Admin\Controller\Common\AdminMainController;
use App\User\Contoller\Common\JournalController;
use App\User\Contoller\Common\NotificationsController;
use App\User\Contoller\Common\PlanController;
use App\User\Contoller\Common\PRRApplicationDocumentController;
use App\User\Contoller\Common\PRRController;
use App\User\Contoller\Common\RegisterPaymentController;
use App\User\Contoller\Common\RegistryController;
use App\User\Contoller\Common\SearchController;
use App\User\Contoller\Common\SupervisorController;
use App\User\Contoller\Common\TSApplicationController;
use App\User\Contoller\Common\TSApplicationDocumentController;
use App\User\Contoller\Common\UserController;
use App\User\Contoller\Common\ChatController;
use App\User\Contoller\Common\SalaryController;
use App\User\Middleware\AuthMiddleware;
use App\User\Middleware\FullCRMMiddleware;

return [
    Route::get('/', [ApplicationController::class, 'applicationsListPage'], [AuthMiddleware::class]),
    Route::get('/test/tg', [ApplicationController::class, 'TestTG'], [AuthMiddleware::class]),
    Route::get('/map', [HomeController::class, 'map'], [FullCRMMiddleware::class]),

    Route::post('/api/main-site/upload-form', [HomeController::class, 'apiMainSiteUploadForm']),
    Route::get('/api/main-site/upload-form', [HomeController::class, 'apiMainSite']),


    Route::post('/ajax/change-count-element-page', [HomeController::class, 'ajaxChangeCountElementPage'], [AuthMiddleware::class]),

    Route::get('/login', [UserController::class, 'loginPage']),
    Route::post('/login/user', [UserController::class, 'login']),

    Route::get('/profile', [UserController::class, 'profilePage'], [FullCRMMiddleware::class]),
    Route::post('/profile', [UserController::class, 'profileEdit'], [FullCRMMiddleware::class]),
    Route::post('/profile/ajax/upload-avatar', [UserController::class, 'usersUploadAvatar'], [FullCRMMiddleware::class]),

    Route::get('/logout', [UserController::class, 'logout'], [AuthMiddleware::class]),

    Route::get('/search', [SearchController::class, 'index'], [AuthMiddleware::class]),

    Route::post('/upload-avatar', [UserController::class, 'uploadAvatar'], [AuthMiddleware::class]),

    Route::get('/add-user', [UserController::class, 'addUserPage']),
    Route::post('/add-user', [UserController::class, 'addUser']),
    Route::get('/users', [UserController::class, 'users']),
    Route::get('/reset-password', [UserController::class, 'resetPasswordPage']),
    Route::post('/reset-password', [UserController::class, 'resetPassword']),
    Route::get('/reset', [UserController::class, 'reset']),
    Route::post('/reset/password', [UserController::class, 'updateResetPassword']),


    Route::get('/admin', [UsersController::class, 'usersListPage'], [AdminMiddleware::class]),

    Route::get('/admin/document-flow', [DocumentFlowController::class, 'index'], [AdminMiddleware::class]),
    Route::post('/admin/document-flow', [DocumentFlowController::class, 'editDocumentFlow'], [AdminMiddleware::class]),

    Route::get('/admin/customer/list', [CustomerController::class, 'customerListPage'], [AdminMiddleware::class]),
    Route::post('/admin/customer/get-list', [CustomerController::class, 'customerGetList'], [AdminMiddleware::class]),
    Route::post('/admin/customer/get', [CustomerController::class, 'customerGet'], [AdminMiddleware::class]),
    Route::post('/admin/customer/delete', [CustomerController::class, 'customerDelete'], [AdminMiddleware::class]),
    Route::post('/admin/customer/add', [CustomerController::class, 'customerAdd'], [AdminMiddleware::class]),
    Route::post('/admin/customer/edit', [CustomerController::class, 'customerEdit'], [AdminMiddleware::class]),
//
//
//
    Route::get('/admin/terms-of-payment/list', [TermsPaymentsController::class, 'termsPaymentListPage'], [AdminMiddleware::class]),
    Route::get('/admin/terms-of-payment/add', [TermsPaymentsController::class, 'termsPaymentAddPage'], [AdminMiddleware::class]),
    Route::post('/admin/terms-of-payment/add', [TermsPaymentsController::class, 'termsPaymentAdd'], [AdminMiddleware::class]),
    Route::get('/admin/terms-of-payment/edit', [TermsPaymentsController::class, 'termsPaymentEditPage'], [AdminMiddleware::class]),
    Route::post('/admin/terms-of-payment/edit', [TermsPaymentsController::class, 'termsPaymentEdit'], [AdminMiddleware::class]),
    Route::post('/admin/terms-of-payment/delete', [TermsPaymentsController::class, 'termsPaymentDelete'], [AdminMiddleware::class]),
    Route::get('/admin/terms-of-payment/copy', [TermsPaymentsController::class, 'termsPaymentCopy'], [AdminMiddleware::class]),
    Route::post('/admin/terms-of-payment/get-list', [TermsPaymentsController::class, 'termsPaymentGetList'], [AdminMiddleware::class]),
//
    Route::get('/admin/condition/list', [ConditionsController::class, 'conditionListPage'], [AdminMiddleware::class]),
    Route::get('/admin/condition/add', [ConditionsController::class, 'conditionAddPage'], [AdminMiddleware::class]),
    Route::post('/admin/condition/add', [ConditionsController::class, 'conditionAdd'], [AdminMiddleware::class]),
    Route::get('/admin/condition/edit', [ConditionsController::class, 'conditionEditPage'], [AdminMiddleware::class]),
    Route::post('/admin/condition/edit', [ConditionsController::class, 'conditionEdit'], [AdminMiddleware::class]),
    Route::post('/admin/condition/delete', [ConditionsController::class, 'conditionDelete'], [AdminMiddleware::class]),
    Route::get('/admin/condition/copy', [ConditionsController::class, 'conditionCopy'], [AdminMiddleware::class]),
    Route::post('/admin/condition/get-list', [ConditionsController::class, 'conditionGetList'], [AdminMiddleware::class]),
//
    Route::get('/admin/addition/list', [AdditionsController::class, 'additionListPage'], [AdminMiddleware::class]),
    Route::get('/admin/addition/add', [AdditionsController::class, 'additionAddPage'], [AdminMiddleware::class]),
    Route::post('/admin/addition/add', [AdditionsController::class, 'additionAdd'], [AdminMiddleware::class]),
    Route::get('/admin/addition/edit', [AdditionsController::class, 'additionEditPage'], [AdminMiddleware::class]),
    Route::post('/admin/addition/edit', [AdditionsController::class, 'additionEdit'], [AdminMiddleware::class]),
    Route::post('/admin/addition/get-list', [AdditionsController::class, 'additionGetList'], [AdminMiddleware::class]),
    Route::post('/admin/addition/delete', [AdditionsController::class, 'additionDelete'], [AdminMiddleware::class]),
    Route::get('/admin/addition/copy', [AdditionsController::class, 'additionCopy'], [AdminMiddleware::class]),

    Route::get('/admin/car-brands/list', [TransportController::class, 'carBrandsListPage'], [AdminMiddleware::class]),
    Route::post('/admin/car-brands/add', [TransportController::class, 'carBrandsAdd'], [AdminMiddleware::class]),
    Route::post('/admin/car-brands/edit', [TransportController::class, 'carBrandsEdit'], [AdminMiddleware::class]),
    Route::post('/admin/car-brands/get-list', [TransportController::class, 'carBrandsGetList'], [AdminMiddleware::class]),
    Route::post('/admin/car-brands/get', [TransportController::class, 'carBrandsGet'], [AdminMiddleware::class]),
    Route::post('/admin/car-brands/delete', [TransportController::class, 'carBrandsDelete'], [AdminMiddleware::class]),


    Route::get('/admin/type-transport/list', [TransportController::class, 'typeTransportListPage'], [AdminMiddleware::class]),
    Route::post('/admin/type-transport/get-list', [TransportController::class, 'typeTransportGetList'], [AdminMiddleware::class]),
    Route::post('/admin/type-transport/get', [TransportController::class, 'typeTransportGet'], [AdminMiddleware::class]),
    Route::post('/admin/type-transport/delete', [TransportController::class, 'typeTransportDelete'], [AdminMiddleware::class]),
    Route::post('/admin/type-transport/add', [TransportController::class, 'typeTransportAdd'], [AdminMiddleware::class]),
    Route::post('/admin/type-transport/edit', [TransportController::class, 'typeTransportEdit'], [AdminMiddleware::class]),


    Route::get('/admin/type-carcase/list', [TransportController::class, 'typeCarcaseListPage'], [AdminMiddleware::class]),
    Route::post('/admin/type-carcase/get-list', [TransportController::class, 'typeCarcaseGetList'], [AdminMiddleware::class]),
    Route::post('/admin/type-carcase/get', [TransportController::class, 'typeCarcaseGet'], [AdminMiddleware::class]),
    Route::post('/admin/type-carcase/delete', [TransportController::class, 'typeCarcaseDelete'], [AdminMiddleware::class]),
    Route::post('/admin/type-carcase/add', [TransportController::class, 'typeCarcaseAdd'], [AdminMiddleware::class]),
    Route::post('/admin/type-carcase/edit', [TransportController::class, 'typeCarcaseEdit'], [AdminMiddleware::class]),



    Route::get('/admin/users/list', [UsersController::class, 'usersListPage'], [AdminMiddleware::class]),
    Route::post('/admin/users/get-list', [UsersController::class, 'usersGetList'], [AdminMiddleware::class]),
    Route::post('/admin/users/get', [UsersController::class, 'usersGet'], [AdminMiddleware::class]),
    Route::post('/admin/users/delete', [UsersController::class, 'usersDelete'], [AdminMiddleware::class]),
    Route::post('/admin/users/add', [UsersController::class, 'usersAdd'], [AdminMiddleware::class]),
    Route::post('/admin/users/edit', [UsersController::class, 'usersEdit'], [AdminMiddleware::class]),
    Route::post('/admin/users/ajax/upload-avatar', [UsersController::class, 'usersUploadAvatar'], [AdminMiddleware::class]),
    Route::post('/admin/ajax/users/reset-password', [UsersController::class, 'ajaxResetPassword'], [AdminMiddleware::class]),




    Route::get('/admin/legal-entities', [AdminMainController::class, ''], [AdminMiddleware::class]),

    Route::post('/application/ajax/get-terms-payment', [ApplicationController::class, 'ajaxGetTermsPayment']),
    Route::post('/application/ajax/get-conditions', [ApplicationController::class, 'ajaxGetConditions']),
    Route::post('/application/ajax/getTermsPaymentDescription', [ApplicationController::class, 'ajaxGetTermsPaymentDescription']),

    Route::post('/application/ajax/document/agreement-application-client',
        [ApplicationDocumentController::class, 'agreementApplicationClient']),

    Route::post('/application/ajax/document/agreement-application-carrier',
        [ApplicationDocumentController::class, 'agreementApplicationCarrier']),
    Route::post('/application/ajax/document/receipt-services',
        [ApplicationDocumentController::class, 'receiptServices']),


    Route::post('/application/ajax/document/driver-attorney',
        [ApplicationDocumentController::class, 'driverAttorney']),

    Route::post('/application/ajax/document/attorney-m2',
        [ApplicationDocumentController::class, 'attorneyM2']),

    Route::post('/application/ajax/document/forwarding-receipt',
        [ApplicationDocumentController::class, 'forwardingReceipt']),

    Route::post('/application/ajax/document/insurance',
        [ApplicationDocumentController::class, 'insurance']),

    Route::post('/application/ajax/document/info-driver',
        [ApplicationDocumentController::class, 'infoDriver']),

    Route::post('/admin/test/upload_file', [AdminMainController::class, 'testUploadFile']),

    Route::get('/applications-list', [ApplicationController::class, 'applicationsListPage'], [AuthMiddleware::class]),

    Route::get('/application/add', [ApplicationController::class, 'add'], [AuthMiddleware::class]),
    Route::get('/application/edit', [ApplicationController::class, 'edit'], [AuthMiddleware::class]),

    Route::get('/application/update-all-walrus', [ApplicationController::class, 'UpdateAllWalrus'], [FullCRMMiddleware::class]),
    Route::get('/application/set-additional-expenses-insurance', [ApplicationController::class, 'setAdditionalExpensesInsurance'], [FullCRMMiddleware::class]),

    Route::get('/application/check-walrus', [ApplicationController::class, 'checkWalrusNew'], [FullCRMMiddleware::class]),

    Route::get('/application/change-status-old-application', [ApplicationController::class, 'changeStatusOldStatus'], [FullCRMMiddleware::class]),

    Route::get('/application/check-profit-period', [ApplicationController::class, 'checkProfitPeriod'], [FullCRMMiddleware::class]),

    Route::get('/application/plan', [ApplicationController::class, 'plan'], [AuthMiddleware::class]),

    Route::get('/plan', [PlanController::class, 'index'], [AuthMiddleware::class]),

    Route::post('/application/ajax/add-carrier', [ApplicationController::class, 'addCarrier'], [AuthMiddleware::class]),
    Route::post('/application/ajax/add-client', [ApplicationController::class, 'addClient'], [AuthMiddleware::class]),
    Route::post('/application/ajax/add-driver', [ApplicationController::class, 'addDriver'], [AuthMiddleware::class]),
    Route::post('/application/ajax/edit-driver', [ApplicationController::class, 'editDriver'], [AuthMiddleware::class]),
    Route::post('/application/ajax/get-carrier', [ApplicationController::class, 'ajaxGetCarrier'], [AuthMiddleware::class]),
    Route::post('/application/ajax/edit-carrier', [ApplicationController::class, 'ajaxEditCarrier'], [AuthMiddleware::class]),
    Route::post('/application/ajax/get-client', [ApplicationController::class, 'ajaxGetClient'], [AuthMiddleware::class]),
    Route::post('/application/ajax/edit-client', [ApplicationController::class, 'ajaxEditClient'], [AuthMiddleware::class]),
    Route::post('/application/ajax/get-driver', [ApplicationController::class, 'ajaxGetDriver'], [AuthMiddleware::class]),
    Route::post('/application/ajax/get-addition', [ApplicationController::class, 'ajaxGetAddition'], [AuthMiddleware::class]),

    Route::post('/application/ajax/change-cancelled', [ApplicationController::class, 'ajaxChangeCancel'], [AuthMiddleware::class]),

    Route::post('/application/ajax/add-application', [ApplicationController::class, 'ajaxAddApplication'], [AuthMiddleware::class]),
    Route::post('/application/ajax/edit-application', [ApplicationController::class, 'ajaxEditApplication'], [AuthMiddleware::class]),

    Route::post('/application/ajax/change-status-application', [ApplicationController::class, 'ajaxChangeStatusApplication'], [AuthMiddleware::class]),

    Route::post('/application/ajax/add-comment', [ApplicationController::class, 'ajaxAddComment'], [AuthMiddleware::class]),
    Route::post('/application/ajax/edit-comment', [ApplicationController::class, 'ajaxEditComment'], [AuthMiddleware::class]),
    Route::post('/application/ajax/delete-comment', [ApplicationController::class, 'ajaxDeleteComment'], [AuthMiddleware::class]),
    Route::post('/application/ajax/load-comment', [ApplicationController::class, 'ajaxLoadComment'], [AuthMiddleware::class]),
    Route::post('/application/ajax/change-important-comment', [ApplicationController::class, 'ajaxChangeImportantComment'], [AuthMiddleware::class]),
    Route::post('/application/ajax/comment-document', [ApplicationController::class, 'ajaxChangeCommentDocument'], [FullCRMMiddleware::class]),

    Route::post('/application/ajax/change-prepayment-carrier', [ApplicationController::class, 'ajaxChangePrepaymentCarrier'], [FullCRMMiddleware::class]),




    Route::post('/application/ajax/change-manager', [ApplicationController::class, 'ajaxChangeManager'], [AuthMiddleware::class]),

    Route::post('/application/ajax/cancel-application', [ApplicationController::class, 'ajaxCancelApplication'], [AuthMiddleware::class]),


    Route::post('/application/ajax/change-date-receipt-all-documents', [ApplicationController::class, 'ajaxChangeDateReceiptAllDocuments'], [FullCRMMiddleware::class]),


    Route::get('/application', [ApplicationController::class, 'index'], [AuthMiddleware::class]),




    Route::get('/prr/edit', [PRRController::class, 'edit'], [AuthMiddleware::class]),
    Route::get('/prr/add', [PRRController::class, 'add'], [AuthMiddleware::class]),
    Route::get('/prr/list', [PRRController::class, 'PRRList'], [AuthMiddleware::class]),
    Route::get('/prr/prr_application', [PRRController::class, 'PRRPage'], [AuthMiddleware::class]),


    Route::post('/prr/ajax/add-application-prr', [PRRController::class, 'ajaxAddApplicationPRR'], [AuthMiddleware::class]),
    Route::post('/prr/ajax/edit-application-prr', [PRRController::class, 'ajaxEditApplicationPRR'], [AuthMiddleware::class]),
    Route::post('/prr/ajax/edit-application-prr', [PRRController::class, 'ajaxEditApplicationPRR'], [AuthMiddleware::class]),
    Route::post('/prr/ajax/get-text-description', [PRRController::class, 'ajaxGetTextDescription'], [AuthMiddleware::class]),
    Route::post('/prr/application/ajax/uploadFiles', [PRRController::class, 'ajaxUploadFiles'], [AuthMiddleware::class]),
    Route::post('/prr/application/ajax/deleteFile', [PRRController::class, 'ajaxDeleteFile'], [AuthMiddleware::class]),
    Route::post('/prr/application/ajax/comment-document', [PRRController::class, 'ajaxChangeCommentDocument'], [AuthMiddleware::class]),
    Route::post('/prr/ajax/add-prr-company', [PRRController::class, 'addPrrCompany'], [AuthMiddleware::class]),
    Route::post('/prr/application/ajax/get-prr-company', [PRRController::class, 'ajaxGetPRRCompany'], [AuthMiddleware::class]),



    Route::post('/prr/ajax/addTask', [PRRController::class, 'ajaxAddTask'], [AuthMiddleware::class]),


    Route::post('/prr/ajax/copy-prr-application', [PRRController::class, 'ajaxCopyPrrApplication'], [AuthMiddleware::class]),


    Route::post('/prr/ajax/document/receipt-services', [PRRApplicationDocumentController::class, 'receiptServices'], [AuthMiddleware::class]),
    Route::post('/prr/ajax/document/get-text-description', [PRRController::class, 'ajaxGetTextDescription'], [AuthMiddleware::class]),

    Route::post('/prr/ajax/document/agreement-application', [PRRApplicationDocumentController::class, 'AgreementApplication'], [AuthMiddleware::class]),


    Route::get('/ts/add', [TSApplicationController::class, 'add'], [AuthMiddleware::class]),
    Route::get('/ts/edit', [TSApplicationController::class, 'edit'], [AuthMiddleware::class]),
    Route::get('/ts/list', [TSApplicationController::class, 'applicationsListPage'], [AuthMiddleware::class]),
    Route::get('/ts/application', [TSApplicationController::class, 'TSPage'], [AuthMiddleware::class]),



    Route::post('/ts/ajax/add-application-ts', [TSApplicationController::class, 'ajaxAddApplicationTS'], [AuthMiddleware::class]),
    Route::post('/ts/ajax/edit-application-ts', [TSApplicationController::class, 'ajaxEditApplicationTS'], [AuthMiddleware::class]),
    Route::post('/ts/ajax/get-data-ts-transport', [TSApplicationController::class, 'ajaxGetDataTsTransport'], [AuthMiddleware::class]),
    Route::post('/ts/ajax/add-forwarder', [TSApplicationController::class, 'addForwarder'], [AuthMiddleware::class]),
    Route::post('/ts/ajax/edit-forwarder', [TSApplicationController::class, 'editForwarder'], [AuthMiddleware::class]),

    Route::post('/ts/ajax/add-transport', [TSApplicationController::class, 'addTransport'], [AuthMiddleware::class]),

    Route::post('/ts/ajax/addTask', [TSApplicationController::class, 'ajaxAddTask'], [AuthMiddleware::class]),
    Route::post('/ts/ajax/document/agreement-application-client', [TSApplicationDocumentController::class, 'agreementApplicationClient'], [AuthMiddleware::class]),

    Route::post('/ts-application/ajax/add-comment', [TSApplicationController::class, 'ajaxAddComment'], [AuthMiddleware::class]),
    Route::post('/ts-application/ajax/edit-comment', [TSApplicationController::class, 'ajaxEditComment'], [AuthMiddleware::class]),
    Route::post('/ts-application/ajax/delete-comment', [TSApplicationController::class, 'ajaxDeleteComment'], [AuthMiddleware::class]),
    Route::post('/ts-application/ajax/load-comment', [TSApplicationController::class, 'ajaxLoadComment'], [AuthMiddleware::class]),


    Route::post('/ts/application/ajax/uploadFiles', [TSApplicationController::class, 'ajaxUploadFiles'], [AuthMiddleware::class]),
    Route::post('/ts/application/ajax/deleteFile', [TSApplicationController::class, 'ajaxDeleteFile'], [AuthMiddleware::class]),

    Route::post('/ts/ajax/copy-ts-application', [TSApplicationController::class, 'ajaxCopyTSApplication'], [AuthMiddleware::class]),
    Route::get('/ts/ajax/copy-ts-application', [TSApplicationController::class, 'ajaxCopyTSApplication'], [AuthMiddleware::class]),

    Route::post('/ts/application/ajax/comment-document', [TSApplicationController::class, 'ajaxChangeCommentDocument'], [AuthMiddleware::class]),


    Route::post('/ts/application/ajax/get-forwarder', [TSApplicationController::class, 'ajaxGetForwarder'], [AuthMiddleware::class]),


    Route::get('/analytics', [AnalyticsController::class, 'index'], [FullCRMMiddleware::class]),
    Route::get('/analytics/net-profit-stat', [AnalyticsController::class, 'netProfitStat'], [FullCRMMiddleware::class]),
    Route::get('/analytics/cron/send-mail-managers-excel', [AnalyticsController::class, 'cronSendMailManagersExcel']),
    Route::get('/analytics/cron/send-mail-weekly-report', [AnalyticsController::class, 'cronSendWeeklyReport']),
    Route::get('/analytics/report', [AnalyticsController::class, 'report'], [FullCRMMiddleware::class]),
    Route::get('/analytics/managers', [AnalyticsController::class, 'managers'], [AuthMiddleware::class]),
    Route::get('/analytics/applications', [AnalyticsController::class, 'applications'], [AuthMiddleware::class]),
    Route::get('/analytics/salary', [AnalyticsController::class, 'salary'], [AuthMiddleware::class]),
    Route::get('/analytics/salary/statistics', [AnalyticsController::class, 'salaryStatistics'], [AuthMiddleware::class]),
    Route::get('/analytics/declaration', [AnalyticsController::class, 'declaration'], [FullCRMMiddleware::class]),
    Route::get('/analytics/carrier-stat', [AnalyticsController::class, 'carrierStat'], [FullCRMMiddleware::class]),
    Route::get('/analytics/debtor-creditor', [AnalyticsController::class, 'debtorCreditor'], [FullCRMMiddleware::class]),
//    Route::get('/analytics/journal-report', [AnalyticsController::class, 'journalReport'], [FullCRMMiddleware::class]),

    Route::post('/analytics/get-list-manager-application-excel', [AnalyticsController::class, 'getListManagerApplicationExcel'], [FullCRMMiddleware::class]),
    Route::post('/analytics/ajax/get-excel-carrier-stat', [AnalyticsController::class, 'ajaxGetExcelCarrierStat'], [FullCRMMiddleware::class]),
    Route::post('/analytics/ajax/download-report-excel', [AnalyticsController::class, 'ajaxDownloadReportExcel'], [FullCRMMiddleware::class]),
    Route::post('/analytics/ajax/download-debtor-creditor-report-excel', [AnalyticsController::class, 'ajaxDownloadDebtorCreditorReportExcel'], [FullCRMMiddleware::class]),

    Route::get('/calculator', [CalculatorController::class, 'calculatorPage'], [AuthMiddleware::class]),

    Route::get('/analytics/update-history-payment', [AnalyticsController::class, 'updateHistoryPayment'], [FullCRMMiddleware::class]),


    Route::post('/analytics/managers/ajax/get-excel', [AnalyticsController::class, 'ajaxManagersGetExcel'], [AuthMiddleware::class]),


    Route::post('/analytics/ajax/add-payments-manager', [AnalyticsController::class, 'ajaxAddPaymentsManager'], [FullCRMMiddleware::class]),

    Route::post('/analytics/ajax/add-fine-manager', [AnalyticsController::class, 'ajaxAddFineManager'], [FullCRMMiddleware::class]),

    Route::get('/history',[HistoryController::class, 'index'], [FullCRMMiddleware::class]),

    Route::get('/base/clients',[BaseController::class, 'clientsPage'], [AuthMiddleware::class]),

    Route::get('/client', [BaseController::class, 'clientPage'], [AuthMiddleware::class]),
    Route::post('/base/client/ajax/load-comment', [BaseController::class, 'ajaxClientLoadComment'], [AuthMiddleware::class]),
    Route::post('/base/client/ajax/add-comment', [BaseController::class, 'ajaxClientAddComment'], [AuthMiddleware::class]),
    Route::post('/base/client/ajax/edit-comment', [BaseController::class, 'ajaxClientEditComment'], [AuthMiddleware::class]),
    Route::post('/base/client/ajax/change-in-work', [BaseController::class, 'ajaxClientChangeInWork'], [AuthMiddleware::class]),
    Route::post('/base/client/ajax/change-important-comment', [BaseController::class, 'ajaxClientChangeImportantComment'], [AuthMiddleware::class]),
    Route::post('/clients/ajax/excel-list-clients', [BaseController::class, 'ajaxExcelClientList'], [FullCRMMiddleware::class]),
    Route::post('/client/ajax/change-client-manager', [BaseController::class, 'ajaxClientChangeManager'], [AuthMiddleware::class]),
    Route::post('/client/ajax/get-rent', [BaseController::class, 'ajaxGetClientRent'], [AuthMiddleware::class]),

    Route::get('/base-call', [BaseController::class, 'baseCallPage'], [AuthMiddleware::class]),



    Route::get('/base/carriers',[BaseController::class, 'carriersPage'], [AuthMiddleware::class]),
    Route::get('/base/call-bases',[BaseController::class, 'callBasesPage'], [AuthMiddleware::class]),
    Route::post('/base/ajax/add-client-base-calls',[BaseController::class, 'ajaxAddClientBaseCalls'], [AuthMiddleware::class]),
    Route::post('/base/ajax/get-info-contact-face',[BaseController::class, 'ajaxGetInfoContactFace'], [AuthMiddleware::class]),
    Route::post('/base/ajax/edit-contact-face',[BaseController::class, 'ajaxEditContactFace'], [AuthMiddleware::class]),
    Route::post('/base/ajax/get-info-client-contact-face',[BaseController::class, 'ajaxGetInfoClientContactFace'], [AuthMiddleware::class]),
    
    


    Route::get('/carrier', [BaseController::class, 'carrierPage'], [AuthMiddleware::class]),
    Route::get('/supervisor', [SupervisorController::class, 'index'], [FullCRMMiddleware::class]),


//     Route::get('/journal',[JournalController::class, 'index']),
    Route::get('/journal',[JournalController::class, 'index'] , [FullCRMMiddleware::class]) ,
    Route::get('/journal-list',[JournalController::class, 'indexList'] , [FullCRMMiddleware::class]) ,
    Route::post('/journal-list',[JournalController::class, 'indexList'] , [FullCRMMiddleware::class]) ,
    Route::get('/journal/manager',[JournalController::class, 'managerNew'],[AuthMiddleware::class]),
    Route::get('/journal/sales',[JournalController::class, 'saleJournal'],[AuthMiddleware::class]),
    Route::post('/journal/ajax/load-application',[JournalController::class, 'ajaxLoadApplicationIndex'],[AuthMiddleware::class]),
    Route::get('/journal',[JournalController::class, 'index'] , [FullCRMMiddleware::class]) ,
    Route::post('/journal/additional-expense-change-paid',[JournalController::class, 'ajaxAdditionalExpenseChangeIsPaid'],[AuthMiddleware::class]),

    Route::post('/journal/ajax/set-session-filter',[JournalController::class, 'ajaxSetSessionFilter'],[AuthMiddleware::class]),

    Route::get('/journal/parse-txt',[JournalController::class, 'parseTXT'] , [FullCRMMiddleware::class]) ,
    Route::post('/journal/parse-txt',[JournalController::class, 'parsePOSTTXT'] , [FullCRMMiddleware::class]) ,
    Route::post('/journal/ajax/add-num-doc-payment-parser',[JournalController::class, 'ajaxAddNumDocPaymentParser'],[FullCRMMiddleware::class]),


    Route::get('/journal/control-payment',[JournalController::class, 'controlPayment'], [FullCRMMiddleware::class]),

    Route::get('/control-clients-payment',[RegisterPaymentController::class, 'index'], [FullCRMMiddleware::class]),

    Route::get('/register-payment',[RegisterPaymentController::class, 'index'], [AuthMiddleware::class]),
    Route::get('/register-application-payment',[JournalController::class, 'registerApplicationPayment'], [FullCRMMiddleware::class]),
    Route::get('/register-additional-expenses',[RegisterPaymentController::class, 'registerAdditionalExpenses'], [FullCRMMiddleware::class]),
    Route::get('/register-application-consideration',[JournalController::class, 'registerApplicationConsideration'], [FullCRMMiddleware::class]),
    Route::post('/register-application-consideration/accepted',[JournalController::class, 'ajaxApplicationConsiderationAccepted'], [FullCRMMiddleware::class]),


    Route::post('/register-payment/ajax/change-application-comment',[RegisterPaymentController::class, 'ajaxChangeApplicationComment'], [AuthMiddleware::class]),
    Route::post('/register-payment/ajax/change-register-payment-history',[RegisterPaymentController::class, 'ajaxChangeRegisterPaymentHistory'], [AuthMiddleware::class]),
    Route::post('/register-payment/ajax/add-comment-application',[RegisterPaymentController::class, 'ajaxAddCommentApplication'], [AuthMiddleware::class]),
    Route::post('/register-payment/ajax/set-color-application',[RegisterPaymentController::class, 'ajaxSetColorApplication'], [AuthMiddleware::class]),
    Route::post('/register-payment/ajax/get-color-application',[RegisterPaymentController::class, 'ajaxGetColorApplication'], [AuthMiddleware::class]),
    Route::post('/register-payment/ajax/change-register-payment-claims',[RegisterPaymentController::class, 'ajaxChangeClaimsApplication'], [AuthMiddleware::class]),

    Route::post('/journal/get-list-application-additional-expenses',[JournalController::class, 'ajaxGetListApplicationAdditionalExpenses'], [FullCRMMiddleware::class]),



    Route::get('/registry', [RegistryController::class, 'index'], [FullCRMMiddleware::class]),
    Route::post('/registry/ajax/get-carrier-detail', [RegistryController::class, 'ajaxGetCarrierDetail'], [FullCRMMiddleware::class]),
    Route::post('/registry/ajax/add-carrier-detail', [RegistryController::class, 'ajaxAddCarrierDetail'], [FullCRMMiddleware::class]),
    Route::post('/registry/ajax/change-main-detail', [RegistryController::class, 'ajaxChangeMainDetail'], [FullCRMMiddleware::class]),

    Route::post('/journal/ajax/excel',[JournalController::class, 'ajaxGetExcel'] , [FullCRMMiddleware::class]) ,
    Route::post('/journal/ajax/excel-list',[JournalController::class, 'ajaxGetExcelList'] , [FullCRMMiddleware::class]) ,
    Route::post('/journal/ajax/register',[JournalController::class, 'ajaxGetRegister'] , [FullCRMMiddleware::class]) ,
    Route::post('/journal/ajax/save-manager-comment',[JournalController::class, 'ajaxSaveManagerComment'] , [AuthMiddleware::class]) ,

    Route::post('/journal/ajax/change-dop-setting',[JournalController::class, 'ajaxChangeDopSetting'] , [AuthMiddleware::class]) ,
    Route::post('/journal/ajax/change-event-status',[JournalController::class, 'ajaxChangeEventStatus'] , [AuthMiddleware::class]) ,
    Route::post('/journal/ajax/change-status-application-journal',[JournalController::class, 'ajaxChangeStatusApplicationJournal'] , [AuthMiddleware::class]) ,
    Route::post('/journal/ajax/change-application-info',[JournalController::class, 'ajaxChangeApplicationInfo'] , [AuthMiddleware::class]) ,
    Route::post('/journal/ajax/change-application-account-number',[JournalController::class, 'ajaxChangeApplicationAccountNumber'] , [AuthMiddleware::class]) ,


    Route::post('/journal/ajax/change-application-additional-expenses',[JournalController::class, 'ajaxChangeApplicationAdditionalExpense'] , [AuthMiddleware::class]) ,


    Route::post('/journal/ajax/search-application-number',[JournalController::class, 'ajaxSearchApplicationNumber'] , [AuthMiddleware::class]) ,
    Route::post('/journal/manager/ajax/search-application-number',[JournalController::class, 'ajaxSearchApplicationNumberManager'] , [AuthMiddleware::class]) ,
    Route::get('/journal/manager/ajax/search-application-number',[JournalController::class, 'ajaxSearchApplicationNumberManager'] , [AuthMiddleware::class]) ,

    Route::post('/journal/ajax/change-payment-date',[JournalController::class, 'ajaxChangePaymentDate'] , [AuthMiddleware::class]) ,
    Route::post('/journal/ajax/change-payment-date-history',[JournalController::class, 'ajaxChangePaymentDateHistory'] , [AuthMiddleware::class]) ,

    Route::get('/notifications', [NotificationsController::class, 'index'], [AuthMiddleware::class]),
    Route::post('/notifications/ajax/set-complete-notification', [NotificationsController::class, 'ajaxSetCompleteNotification'], [AuthMiddleware::class]),


    Route::post('/journal/ajax/change-payment-status-full', [JournalController::class, 'ajaxChangePaymentStatus'] , [AuthMiddleware::class]) ,
    Route::post('/journal/ajax/change-payment-status-cancel', [JournalController::class, 'ajaxChangePaymentStatusCancel'] , [AuthMiddleware::class]) ,
    Route::post('/journal/ajax/load-applications', [JournalController::class, 'ajaxLoadApplications'] , [AuthMiddleware::class]) ,

    Route::get('/bonus', [BonusController::class, 'index'] , [FullCRMMiddleware::class]) ,
    Route::post('/bonus/ajax/change-pay', [BonusController::class, 'ajaxChangeBonusPay'] , [FullCRMMiddleware::class]) ,

    Route::post('/test', [ApplicationController::class, 'test']),
    Route::get('/application/test-new-salary-manager-share', [ApplicationController::class, 'testNewSalaryManagerShare'], [AuthMiddleware::class]),


    Route::post('/application/ajax/get-terms-payment-list', [ApplicationController::class, 'ajaxGetTermsPaymentList'], [AuthMiddleware::class]),
    Route::post('/application/ajax/change-ttn-status', [ApplicationController::class, 'ajaxChangeTTNStatus'], [AuthMiddleware::class]),

    Route::post('/application/ajax/uploadFiles', [ApplicationController::class, 'ajaxUploadFiles'], [AuthMiddleware::class]),
    Route::post('/application/ajax/check-isset-carrier-inn', [ApplicationController::class, 'checkIssetCarrierInn'], [AuthMiddleware::class]),

    Route::get('/application/check-possible-change-status', [ApplicationController::class, 'checkPossibleChangeStatus'], [AuthMiddleware::class]) ,

    Route::post('/application/ajax/deleteFile', [ApplicationController::class, 'ajaxDeleteFile'], [AuthMiddleware::class]),

    Route::post('/application/ajax/complete-application-supervisor', [SupervisorController::class, 'ajaxCompleteApplicationSupervisor'], [FullCRMMiddleware::class]) ,
    Route::post('/application/ajax/get-comment-complete-supervisor-application', [SupervisorController::class, 'ajaxGetCommentCompleteSupervisorApplication'], [FullCRMMiddleware::class]) ,

//         Route::post('/application/ajax/addTask', [ApplicationController::class, 'ajaxAddTask']),
    Route::post('/application/ajax/add-contact-face', [BaseController::class, 'ajaxAddContactFace']),
    Route::post('/application/ajax/delete-contact-face', [BaseController::class, 'ajaxDeleteContactFace']),

    Route::post('/ajax/changeClientInfo', [BaseController::class, 'ajaxChangeClientInfo']),

    Route::post('/application/ajax/addTask', [ApplicationController::class, 'ajaxAddTask'], [AuthMiddleware::class]),
    Route::post('/application/ajax/copy-application', [ApplicationController::class, 'ajaxCopyTest']),

    Route::post('/ajax/editClient', [BaseController::class, 'ajaxEditClient']),
    Route::post('/ajax/deleteClient', [BaseController::class, 'ajaxDeleteClient']),

//    Route::get('/application/check-walrus', [ApplicationController::class, 'checkWalrus'], [AuthMiddleware::class]),
    Route::post('/ajax/search', [SearchController::class, 'ajaxSearch'], [AuthMiddleware::class]),
    Route::get('/ajax/search', [SearchController::class, 'ajaxSearch'], [AuthMiddleware::class]),
    Route::post('/ajax/search/prr', [SearchController::class, 'ajaxSearchPrr'], [AuthMiddleware::class]),
    Route::post('/ajax/search/carrier', [SearchController::class, 'ajaxSearchCarrier'], [AuthMiddleware::class]),
    Route::post('/ajax/search/client', [SearchController::class, 'ajaxSearchClient'], [AuthMiddleware::class]),


    Route::get('/chat', [ChatController::class, 'chatPage'], [AuthMiddleware::class]) ,
    Route::get('/create-zip-applications-files', [HomeController::class, 'createZipApplicationsFiles'], [AuthMiddleware::class]) ,
    Route::get('/download-applications-files', [HomeController::class, 'createZipApplicationsFiles'], [AuthMiddleware::class]) ,
    Route::post('/chat/chating', [ChatController::class, 'ajaxChating'], [AuthMiddleware::class]) ,

    Route::get('/salary/setting', [SalaryController::class, 'salarySetting'], [FullCRMMiddleware::class]) ,
    Route::post('/salary/ajax/save-setting-percent', [SalaryController::class, 'ajaxSaveSettingPercent'], [FullCRMMiddleware::class]),
    Route::post('/salary/ajax/save-setting-salary', [SalaryController::class, 'ajaxSaveSettingSalary'], [FullCRMMiddleware::class]),
    Route::post('/salary/ajax/recalculate-application', [SalaryController::class, 'ajaxRecalculateApplication'], [FullCRMMiddleware::class]),

];