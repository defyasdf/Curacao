<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Reward_Observer extends Enterprise_Reward_Model_Observer
{
    /**
     * Revert authorized reward points amount for order
     *
     * @param   Mage_Sales_Model_Order $order
     * @return  Enterprise_Reward_Model_Observer
     */
    protected function _revertRewardPointsForOrder(Mage_Sales_Model_Order $order)
    {
        if (!$order->getCustomer()->getId()) {
            return $this;
        }
        Mage::getModel('enterprise_reward/reward')
            ->setCustomerId($order->getCustomer()->getId())
            ->setWebsiteId(Mage::app()->getStore($order->getStoreId())->getWebsiteId())
            ->setPointsDelta($order->getRewardPointsBalance())
            ->setAction(Enterprise_Reward_Model_Reward::REWARD_ACTION_REVERT)
            ->setActionEntity($order)
            ->updateRewardPoints();

        return $this;
    }

}
