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

namespace App\Controller\Buyer;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class DashboardController extends BuyerAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }

    public function index()
    {

        $this->set('headTitle', 'Dashboard');
        $session = $this->getRequest()->getSession();

        //echo '<pre>'; print_r($session->read()); exit;

        if (!$session->check('id')) {
            $this->redirect(array('prefix' => false, 'controller' => 'users', 'action' => 'login'));
        }

        $this->loadModel('PoHeaders');
        $this->loadModel('VendorTemps');
        $this->loadModel('DeliveryDetails');
        $this->loadModel('AsnHeaders');
        $this->loadModel('VendorTypes');
        $this->loadModel('Materials');

        $vendorStatus = $this->VendorTemps->find()
        ->select(['status' => 'VendorStatus.status','count' => 'count(VendorStatus.status)'])
        ->innerJoin(['VendorStatus' => 'vendor_status'], ['VendorStatus.status=VendorTemps.status'])
        ->where(['company_code_id' => $session->read('company_code_id'), 
        'purchasing_organization_id' => $session->read('purchasing_organization_id')])
        ->group('VendorTemps.status')->toArray();


        //echo '<pre>'; print_r($vendorStatus); exit;

        $vendorDashboardCount = [];
        $vendorDashboardCount['total'] = array_sum(array_column($vendorStatus,'count'));
        foreach($vendorStatus as $status) {
            $vendorDashboardCount[$status->status] = $status->count;
        }
        
        // Asn deshbord card

        //$totalAsnCreated =  $this->AsnHeaders->find('all', array('conditions' => array('status' => '1')))->count();
        //$totalAsnIntransit =  $this->AsnHeaders->find('all', array('conditions' => array('status' => '2')))->count();
        //$totalAsnReceived =  $this->AsnHeaders->find('all', array('conditions' => array('status' => '3')))->count();

        $asnCounts = $this->AsnHeaders->find()
        ->select(['status' => 'AsnHeaders.status','count' => 'count(AsnHeaders.status)'])
        ->innerJoin(
            ['PoHeaders' => 'po_headers'],
            ['AsnHeaders.po_header_id = PoHeaders.id']
        )
        ->innerJoin(
            ['VendorTemps' => 'vendor_temps'],
            ['VendorTemps.sap_vendor_code = PoHeaders.sap_vendor_code', 
            'VendorTemps.company_code_id' => $session->read('company_code_id'),
            'VendorTemps.purchasing_organization_id' => $session->read('purchasing_organization_id')]
        )->group('AsnHeaders.status')->toArray();

        $asnDashboardCount = [];
        $asnDashboardCount['total'] = array_sum(array_column($asnCounts,'count'));
        foreach($asnCounts as $status) {
            $asnDashboardCount[$status->status] = $status->count;
        }

        // Purchase order card count view 

        $query = $this->PoHeaders->find();
        $query->innerJoin(
            ['VendorTemps' => 'vendor_temps'],
            ['VendorTemps.sap_vendor_code = PoHeaders.sap_vendor_code', 
            'VendorTemps.company_code_id' => $session->read('company_code_id'),
            'VendorTemps.purchasing_organization_id' => $session->read('purchasing_organization_id')]
        );

        
        $totalPos = $query->count();


        $conn = ConnectionManager::get('default');

        $query = "select count(1) complete from (SELECT sum(pf.pending_qty)
        from po_headers PH	
        join po_footers pf on pf.po_header_id = PH.id
        group by PH.id
        having sum(pf.pending_qty) = 0) a";

        $result = $conn->execute($query)->fetch('assoc');
        $poCompleteCount = $result['complete'];

        $topVendor = $conn->execute("select * from (SELECT PH.sap_vendor_code, sum(PF.net_value) total
        from po_headers PH	
        join po_footers PF on PF.po_header_id = PH.id
        group by PH.sap_vendor_code) a order by total desc limit 5 ");
        $topVendors = $topVendor->fetchAll('assoc');


        $topMaterial = $conn->execute("select * from (SELECT PF.material, sum(PF.po_qty) total, sum(PF.net_value) value
        from po_footers PF
        group by PF.material) a order by total desc limit 5 ");
        $topMaterials = $topMaterial->fetchAll('assoc');

        $topMaterialValue = $conn->execute("select * from (SELECT PF.material, sum(PF.net_value) value
        from po_footers PF
        group by PF.material) a order by value desc limit 5 ");
        $topMaterialValues = $topMaterialValue->fetchAll('assoc');


        $topVendorList = [];
        foreach($topVendors as $vendor) {
            $topVendorList['code'][] = "'$vendor[sap_vendor_code]'";
            $topVendorList['value'][] = $vendor['total'];
        }

        $topMaterialList = [];
        foreach($topMaterials as $material) {
            $topMaterialList['code'][] = "'$material[material]'";
            $topMaterialList['qty'][] = $material['total'];
        }

        $topMaterialValuesList = [];
        foreach($topMaterialValues as $material) {
            $topMaterialValuesList['code'][] = "'$material[material]'";
            $topMaterialValuesList['value'][] = $material['value'];
        }

        
        $orderByPeriod = $conn->execute("select * from (SELECT sum(PF.net_value) total, date_format(PH.created_on, '%b-%y') as month
        from po_headers PH	
        join po_footers PF on PF.po_header_id = PH.id
        group by date_format(PH.created_on, '%b-%y')) a order by month desc limit 6");
        $orderByPeriods = $orderByPeriod->fetchAll('assoc');

        $orderByPeriodList = [];
        foreach($orderByPeriod as $order) {
            $orderByPeriodList['code'][] = "'$order[month]'";
            $orderByPeriodList['order'][] = $order['total'];
        }

        //echo '<pre>'; print_r($topVendorList); exit;
        $segment = $this->Materials->find('all')->select(['segment'])->distinct(['segment'])->where(['segment IS NOT NULL' ])->toArray();
        $vendor = $this->VendorTemps->find('all')->select(['sap_vendor_code'])->distinct(['sap_vendor_code'])->where(['sap_vendor_code IS NOT NULL' ])->toArray();
        $vendortype = $this->VendorTypes->find('all')->toArray();
        $this->set(compact('vendorDashboardCount', 'totalPos', 'asnDashboardCount', 'poCompleteCount', 'topVendorList', 'topMaterialList', 'orderByPeriodList', 'topMaterialValuesList', 'vendor', 'vendortype', 'segment'));
    }

    public function clearMessageCount()
    {
        $session = $this->getRequest()->getSession();
        // $vendorID = $session->read('id');
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
