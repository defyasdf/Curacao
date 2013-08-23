<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Helper_Data extends Mage_Paygate_Helper_Data
{
    /**
     * Converts a lot of messages to message
     *
     * @param  array $messages
     * @return string
     */
    public function convertMessagesToMessage($messages)
    {
        return implode(' | ', $messages);
    }

    /**
     * Return credit card add URL
     *
     * @return string
     */
    public function getCustomerAddCreditCardUrl()
    {
        return $this->_getUrl('authorizenetcim/account/add');
    }


    /**
     * Return message for gateway transaction request
     *
     * @param  Mage_Payment_Model_Info $payment
     * @param  string $requestType
     * @param  string $lastTransactionId
     * @param  Varien_Object $card
     * @param float $amount
     * @param string $exception
     * @return bool|string
     */
    public function getTransactionMessage($payment, $requestType, $lastTransactionId, $card, $amount = false, $exception = false)
    {
        $operation = $this->_getGorillaOperation($requestType);

        if (!$operation) {
            return false;
        }

        if ($amount) {
            $amount = $this->__('amount %s', $this->_formatPrice($payment, $amount));
        }

        if ($exception) {
            $result = $this->__('failed');
        } else {
            $result = $this->__('successful');
        }

        $card = $this->__('Credit Card: xxxx-%s', $card->getCcLast4());
        $transaction = $this->__('Authorize.Net CIM Transaction ID %s', $lastTransactionId);

        return $this->__('%s %s %s - %s. %s. %s', $card, $amount, $operation, $result, $transaction, $exception );
    }

    /**
     * Return operation name for request type
     *
     * @param  string $requestType
     * @return bool|string
     */
    protected function _getGorillaOperation($requestType)
    {
        switch ($requestType) {
            case Mage_Paygate_Model_Authorizenet::REQUEST_TYPE_AUTH_ONLY:
                return $this->__('authorize');
            case Mage_Paygate_Model_Authorizenet::REQUEST_TYPE_AUTH_CAPTURE:
                return $this->__('authorize and capture');
            case Mage_Paygate_Model_Authorizenet::REQUEST_TYPE_PRIOR_AUTH_CAPTURE:
                return $this->__('capture');
            case Mage_Paygate_Model_Authorizenet::REQUEST_TYPE_CREDIT:
                return $this->__('refund');
            case Mage_Paygate_Model_Authorizenet::REQUEST_TYPE_VOID:
                return $this->__('void');
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY:
                return $this->__('authorize');
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_CAPTURE:
                return $this->__('authorize and capture');
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP:
                return $this->__('prior auth capture');
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_CAPTURE_ONLY:
                return $this->__('capture');
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_REFUND:
                return $this->__('refund');
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID:
                return $this->__('void');
            default:
                return false;
        }

    }
/*
    protected function _getOperation($requestType)
    {
	    //add check for guest
        Mage::log('Gorilla_AuthorizenetCim_Helper_Data requestType '.$requestType);
        $operation = parent::_getOperation($requestType);
        Mage::log('Gorilla_AuthorizenetCim_Helper_Data operation '.$operation);
        if (!$operation) {
            switch ($requestType) {
                case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY:
                    return $this->__('authorize');
                case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_CAPTURE:
                    return $this->__('authorize and capture');
                case Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP:
                    return $this->__('prior auth capture');
                case Gorilla_AuthorizenetCim_Model_Profile::TRANS_CAPTURE_ONLY:
                    return $this->__('capture');
                case Gorilla_AuthorizenetCim_Model_Profile::TRANS_REFUND:
                    return $this->__('refund');
                case Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID:
                    return $this->__('void');
                default:
                    return false;
            }
        }

        return $operation;
    }
*/

    /**
     * Format price with currency sign
     * @param  Mage_Payment_Model_Info $payment
     * @param float $amount
     * @return string
     */
    protected function _formatPrice($payment, $amount)
    {
        return $payment->getOrder()->getBaseCurrency()->formatTxt($amount);
    }

}