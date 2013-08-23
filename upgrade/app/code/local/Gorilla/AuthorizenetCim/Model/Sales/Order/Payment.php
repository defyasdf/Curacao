<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Sales_Order_Payment extends Mage_Sales_Model_Order_Payment
{
    /**
     * Create transaction,
     * prepare its insertion into hierarchy and add its information to payment and comments
     *
     * To add transactions and related information,
     * the following information should be set to payment before processing:
     * - transaction_id
     * - is_transaction_closed (optional) - whether transaction should be closed or open (closed by default)
     * - parent_transaction_id (optional)
     * - should_close_parent_transaction (optional) - whether to close parent transaction (closed by default)
     *
     * If the sales document is specified, it will be linked to the transaction as related for future usage.
     * Currently transaction ID is set into the sales object
     * This method writes the added transaction ID into last_trans_id field of the payment object
     *
     * To make sure transaction object won't cause trouble before saving, use $failsafe = true
     *
     * @param string $type
     * @param Mage_Sales_Model_Abstract $salesDocument
     * @param bool $failsafe
     * @return null|Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _addTransaction($type, $salesDocument = null, $failsafe = false)
    {
        if ($this->getSkipTransactionCreation()) {
            $this->unsTransactionId();
            return null;
        }
        return parent::_addTransaction($type, $salesDocument, $failsafe);
    }

    /**
     * Reset transaction additional info property
     *
     * @return Mage_Sales_Model_Order_Payment
     */
    public function resetTransactionAdditionalInfo()
    {
        $this->_transactionAdditionalInfo = array();
        return $this;
    }
}