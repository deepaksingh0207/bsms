<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller\Vendor;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Cake\View\Exception\MissingTemplateException;
use Cake\Datasource\ConnectionManager;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class DashboardController extends VendorAppController
{

    public function initialize(): void
    {
        parent::initialize();
        $flash = [];
        //     $session = $this->getRequest()->getSession();

        //     $permissionsTable = $this->getTableLocator()->get('Permissions');
        //     $query = $permissionsTable->find()
        //         ->select(['permission'])
        //         ->where([
        //             'controller' => ':controller', 
        //             'action' => 'index',
        //             'users' => $session->read('id') 
        //         ])->toArray();

        //    print_r($query);exit;    
        $this->set('flash', $flash);
    }

    public function index()
    {
        $this->set('headTitle', 'Dashboard');
        $session = $this->getRequest()->getSession();
        $conn = ConnectionManager::get('default');

        $this->loadModel('PoHeaders');
        $this->loadModel('VendorTemps');

        $this->loadModel('PoItemSchedules');
        $this->loadModel('DeliveryDetails');
        $this->loadModel('StockUploads');
        $this->loadModel('AsnFooters');

        $totalPos = $this->PoHeaders->find('all')
        ->where(['sap_vendor_code' => $session->read('vendor_code')]);

        //echo '<pre>'; print_r($totalPos); exit;
        $totalPos = $totalPos->count();
        
        $stocks = $this->StockUploads->find()
        ->select(['VendorFactories.factory_code', 'Materials.description', 'opening_stock', 'production_stock', 'current_stock', 'asn_stock', 'closing_stock' => "current_stock"])
        ->contain(['Materials', 'VendorFactories'])
        ->where(['StockUploads.sap_vendor_code' => $session->read('vendor_code')])
        ->toArray();
        

        $intransitMaterials = $this->AsnFooters->find('all')
        ->select(['VendorFactories.factory_code','AsnHeaders.asn_no', 'AsnHeaders.invoice_no', 'AsnHeaders.invoice_date', 'PoHeaders.po_no', 'PoFooters.material', 'AsnFooters.qty', 'AsnHeaders.status'])
        ->contain(['AsnHeaders', 'AsnHeaders.VendorFactories','PoFooters', 'PoFooters.PoHeaders'])
        ->where(['AsnHeaders.status' => '2']);
        $totalIntransit = $intransitMaterials->count();
        
        $this->set(compact('totalPos', 'totalIntransit', 'stocks', 'intransitMaterials'));
    }

    public function getlist()
    {
        $this->autoRender = false;
        $this->loadModel('PoHeaders');
        $this->loadModel('VendorTemps');

        $query = $this->PoHeaders->find();
        $query->join(['PoFooters' => 'po_footers'])
            ->leftJoin(
                ['VendorTemps' => 'vendor_temps'],
                ['VendorTemps.sap_vendor_code = PoHeaders.sap_vendor_code']
            )->toArray();



        print_r($query);
    }

    public function clearMessageCount()
    {
        $session = $this->getRequest()->getSession();
        $vendorID = $session->read('id');
        $response = array();
        $response['status'] = 0;
        $response['message'] = '';

        $id = $this->request->getQuery('id'); 
        if (!empty($id)) {
            $this->loadModel('Notifications');
            $this->Notifications->updateAll(['message_count' => 0], ['id IN' => $id]);
        }
        else{
            $response['status'] = 0;
            $response['message'] = 'Failed';
        }

        $response['status'] = 1;
        $response['message'] = 'Clear Notification';

        echo json_encode($response);
        exit();
    }
}
