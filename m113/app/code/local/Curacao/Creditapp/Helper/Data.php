<?php

class Curacao_Creditapp_Helper_Data extends Mage_Core_Helper_Abstract {
    //COMMENT: (ec) move these to admin via system > config (I assume these are remnants?)

    const ECOMW = 'credit_app_section/credit_app_group/exchangewebecomm';
    const EAUTH = 'credit_app_section/credit_app_group/exchangewebauth';
    const UNAME = 'credit_app_section/credit_app_group/exchangewebuser';
    const PSSWD = 'credit_app_section/credit_app_group/exchangewebpassword';
    const ECOMM_NS = 'credit_app_section/credit_app_group/lacuracaoecommns';
    const ECOMM_NS_WEB = 'credit_app_section/credit_app_group/lacuracaoecommnswebapp';
    const ECOMM_NS_WEB2 = 'credit_app_section/credit_app_group/lacuracaoecommnswebapp2';
    const LACIMS_NS = 'credit_app_section/credit_app_group/lacuracaolacmis';
    const AUTH_CONTINUE = 'credit_app_section/credit_app_group/lacuracaoauthcontinuens';
    const AUTH_INIT = 'credit_app_section/credit_app_group/lacuracaoauthinitns';

    //$ecommWsdl	= Mage::getStoreConfig(self::ECOMW);
    //$userName	= Mage::getStoreConfig(self::UNAME);
    //$password	= Mage::getStoreConfig(self::PSSWD);

    public function WebCustomerApplication($customer) {
        $proxy = new SoapClient(Mage::getStoreConfig(self::ECOMW));

        $ns = Mage::getStoreConfig(self::ECOMM_NS);         //COMMENT: (ec) move this to system admin

        $headerbody = array('UserName' => Mage::getStoreConfig(self::UNAME), 'Password' => Mage::getStoreConfig(self::PSSWD));

        $header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);

        //set the Headers of Soap Client. 
        $h = $proxy->__setSoapHeaders($header);

        $dobs = explode("-", $customer['dob']);
        $exps = explode("-", $customer['id_expire']);

        $_storeId = Mage::app()->getStore()->getId();
        if ($_storeId == 1) {
            $lang = 'E';
        } else {
            $lang = "S";
        }

        $credit = $proxy->WebCustomerApplication(array(
            'LastName' => $customer['lastname'],
            'FirstName' => $customer['firstname'],
            'MiddleInitial' => $customer['middlename'],
            'HomePhone' => $customer['telephone'],
            'eMail' => $customer['email_address'],
            'Street' => trim($customer['address1']) . ' ' . trim($customer['address2']),
            'City' => $customer['city'],
            'State' => $customer['state'],
            'Zip' => $customer['zipcode'],
            'Phone2' => $customer['telephone2'],
            'SSN' => $customer['ssn'],
            'ID' => $customer['id_num'],
            'IDType' => $customer['id_type'],
            'MotherMaidenName' => $customer['maiden_name'],
            'WorkName' => $customer['company'],
            'WorkPhone' => $customer['work_phone'],
            'DOB' => date('Y-m-d\Th:i:s', mktime(0, 0, 0, $dobs[1], $dobs[2], $dobs[0])),
            'IDExpiration' => date('Y-m-d\Th:i:s', mktime(0, 0, 0, $exps[1], $exps[2], $exps[0])),
            'LenghtInCurrAddress' => ($customer['res_year'] * 12 + $customer['res_month']),
            'LenghtInCurrWork' => ($customer['work_year'] * 12 + $customer['work_month']),
            'AnnualIncome' => $customer['salary'],
            'IDState' => empty($customer['id_state']) ? $customer['state'] : $customer['id_state'],
            'IPAddress' => $customer['ip_address'],
            'Language' => $lang,
            'AGPP' => $customer['aggp'] == 1 ? 'Y' : 'N',
            'Submit' => $customer['is_lexis_nexus_complete'] == 1 ? 'Y' : 'N'
                ), Mage::getStoreConfig(self::ECOMM_NS_WEB), //COMMENT: (ec) - pull from admin
                Mage::getStoreConfig(self::ECOMM_NS_WEB2), //COMMENT: (ec) - pull from admin
                false, null, 'rpc', 'literal');
        return $credit;
    }

    public function Auth_Continue($_POST) {
        $proxy = new SoapClient(Mage::getStoreConfig(self::EAUTH));

        $result = $proxy->Auth_Continue(
                array('Params' =>
            array('QuestionSetId' => $_POST['qsetid'],
                'QuestionId' => $_POST['qid'],
                'ChoiceId' => $_POST['choiceid'],
                'TransactNumber' => $_POST['tnum'],
                'AcctTransId' => $_POST['tid']
            )
                ), Mage::getStoreConfig(self::LACIMS_NS), //COMMENT: (ec) - pull from admin
                Mage::getStoreConfig(self::AUTH_CONTINUE), //COMMENT: (ec) - pull from admin
                false, null, 'rpc', 'literal'
        );
        return $result;
    }

    //
    //
	//
	//
	//
	public function Auth_Init($_POST) {
        Mage::log("Curacao/Creditapp/Helper/Data->Auth_Init");

        $proxy = new SoapClient(Mage::getStoreConfig(self::EAUTH));
        //echo $this->authWsdl;echo "<pre>";print_r($_POST);die;

        $result = $proxy->Auth_Init(array('Params' =>
            array('Firstname' => $_POST['fname'],
                'Middlename' => '',
                'Lastname' => $_POST['lname'],
                'HomePhone' => $_POST['area'] . $_POST['local1'] . $_POST['local2'],
                'Ssn' => $_POST['ssn1'] . $_POST['ssn2'] . $_POST['ssn3'],
                'ID_no' => $_POST['id_number'],
                'ID_State' => $_POST['state'],
                'DOB_Month' => $_POST['dobM'],
                'DOB_Day' => $_POST['dobD'],
                'DOB_Year' => $_POST['dobY'],
                'Street' => $_POST['street1'] . ' ' . $_POST['street2'],
                'City' => $_POST['city'],
                'State' => $_POST['state'],
                'Zip' => $_POST['postcode'],
                'AcctTransId' => $_POST['appid'],
                'Language' => 'English',
                'TimeOut' => 0
            )
                ), Mage::getStoreConfig(self::LACIMS_NS), //COMMENT: (ec) - pull from admin
                Mage::getStoreConfig(self::AUTH_INIT), //COMMENT: (ec) - pull from admin
                false, null, 'rpc', 'literal'
        );
        return $result;
    }

    public function ValidateAddress($_POST) {
        $proxy = new SoapClient(Mage::getStoreConfig(self::ECOMW));
        $ns = Mage::getStoreConfig(self::ECOMM_NS);
        $headerbody = array('UserName' => Mage::getStoreConfig(self::UNAME), 'Password' => Mage::getStoreConfig(self::PSSWD));
        $header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);
        $h = $proxy->__setSoapHeaders($header);
        $credit = $proxy->ValidateAddress(array(
            'Street' => $_POST['street'],
            'Zip' => $_POST['zip']
                ), Mage::getStoreConfig(self::ECOMM_NS_WEB), //COMMENT: (ec) - pull from admin
                Mage::getStoreConfig(self::ECOMM_NS_WEB2), //COMMENT: (ec) - pull from admin
                false, null, 'rpc', 'literal'
        );
        return $credit;
    }

    public function insertGwmtracking($hid, $sub_id, $aff_id, $sid) {
        return Mage::getModel('creditapp/creditmodel')->insertGwmtracking($hid, $sub_id, $aff_id, $sid);
    }

    public function updateEmailCampaign($utm_campaign, $current_page) {
        Mage::getModel('creditapp/creditmodel')->updateEmailCampaign($utm_campaign, $current_page);
    }

    public function updateEmailCampaignFromSession($current_page) {
        Mage::getModel('creditapp/creditmodel')->updateEmailCampaignFromSession($current_page);
    }

    public function runQuery($query) {
        return Mage::getModel('creditapp/creditmodel')->runQuery($query);
    }

    public function updateUserActivity($current_page, $_COOKIE) {
        Mage::getModel('creditapp/creditmodel')->updateUserActivity($current_page, $_COOKIE);
    }

    public function createUserActivity($params, $server, $current_page) {
        return Mage::getModel('creditapp/creditmodel')->createUserActivity($params, $server, $current_page);
    }

}

