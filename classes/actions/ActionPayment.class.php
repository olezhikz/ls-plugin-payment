<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Oleg Demidov 
 *
 */

/**
 * Экшен обработки ajax запросов
 * Ответ отдает в JSON фомате
 *
 * @package actions
 * @since 1.0
 */
class PluginPayment_ActionPayment extends ActionPlugin{
    
    public function Init()
    {
        
    }
    protected function RegisterEvent() {
        
        $this->AddEventPreg( '/^[\w_-]+$/i', '/^bills$/i', '/^(paid|not_paid)?$/i', ['EventBills', 'settings']);
    }
    
    public function EventBills() {
        if(!$oUserProfile = $this->User_GetUserByLogin($this->sCurrentEvent)){
            return $this->EventNotFound();
        }
        
        $aFilter = [
            "date_payment" => null
        ];
        
        $aFilter['user_id'] = $oUserProfile->getId();
        
        $aBills = $this->PluginPayment_Payment_GetBillByFilter($aFilter);
        
        $iCountPaid = $this->PluginPayment_Payment_GetCountFromBillByFilter([
            '#where' => [
                't.date_payment IS NOT NULL AND 1=?d' => [1]
            ]
        ]);
        $iCountNotPaid = $this->PluginPayment_Payment_GetCountFromBillByFilter(['date_payment' => null]);
        
        $this->Menu_Get('profile')->setActiveItem('bills');
        $this->SetTemplateAction('bills');
        $this->Viewer_Assign('iCountPaid', $iCountPaid);
        $this->Viewer_Assign('iCountNotPaid', $iCountNotPaid);
        $this->Viewer_Assign('aBills', $aBills);
        $this->Viewer_Assign('oUserProfile', $oUserProfile);
    }
    
}