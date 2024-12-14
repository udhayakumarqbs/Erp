<?php
namespace App\Config\MyRoutes;
use CodeIgniter\Router\RouteCollection;



/**
 * @var RouteCollection $routes
 */
$routes->group('erp/client', ['namespace' => 'App\Controllers\Client','filter' => 'my_filter'], static function ($routes) {
    $routes->get('dashboard','DashboardController::index',['as'=>'front.dashboard']);
    $routes->get('veiwprofile/(:any)','DashboardController::profile/$1',['as'=>'front.profile']);
    $routes->post('update-profile','DashboardController::profile_update',['as'=>'fornt.profile.update']);
    $routes->post('password-change/(:any)','DashboardController::password_change/$1',['as'=>'front.profile.password.change']);
});
$routes->group('estimate', ['namespace' => 'App\Controllers\Client','filter' => 'my_filter'], static function ($routes) {
    $routes->get('/','OverallClientController::estimateList',['as'=>'estimate.index']);
});
$routes->group('contract', ['namespace' => 'App\Controllers\Client','filter' => 'my_filter'], static function ($routes) {
    $routes->get('/','OverallClientController::contractList',['as'=>'contract.index']);
});
$routes->group('project', ['namespace' => 'App\Controllers\Client','filter' => 'my_filter'], static function ($routes) {
    $routes->get('/','OverallClientController::projectList',['as'=>'project.index']);
    $routes->get('projects-view','OverallClientController::projectListView',['as'=>'project.view.list']);

});
$routes->group('invoice', ['namespace' => 'App\Controllers\Client','filter' => 'my_filter'], static function ($routes) {
    $routes->get('/','OverallClientController::invoiceList',['as'=>'invoice.index']);
});
$routes->group('support', ['namespace' => 'App\Controllers\Client','filter' => 'my_filter'], static function ($routes) {
    $routes->get('/','SupportController::index',['as'=>'front.supports.view']);
    $routes->get('ticket-view/(:any)','SupportController::ticketview/$1',['as'=>'front.ticket.detail.view']);
    $routes->post('ticket-comment-add','SupportController::addcomment',['as'=>'front.ticket.add.comment']);
    $routes->post('ticket-fetch-comments','SupportController::fetchallcomment',['as'=>'front.ticket.comment.fetch']);
    $routes->get('ticket-add-view','SupportController::ticketaddview',['as'=>'front.ticket.add.view']);
    $routes->post('tickets-create','SupportController::ticketadd',['as'=>'fornt.ticket.add']);
});
$routes->group('KnowledgeBase', ['namespace' => 'App\Controllers\Client','filter' => 'my_filter'], static function ($routes) {
    $routes->get('/','KnowledgeBaseController::index',['as'=>'front.Knowledgebase.view']);
    $routes->post('knowledgebase-search','KnowledgeBaseController::search',['as'=>'front.knowledgebase.search']);
    $routes->post('knowledgebase-feedback','KnowledgeBaseController::feedback',['as'=>'front.submit.feedback']);
});
