<?php
/**
 * Magify Commerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magify.com so we can send you a copy immediately.
 *
 * @category    Magify
 * @package     Magify_BannerManager
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Groups_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
     protected function _prepareForm()
	 {
	 	
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getFormActionUrl(), 'method' => 'POST', 'enctype' => 'multipart/form-data'));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
	 }
	 
	 //Form Action URL Fix
	 public function getFormActionUrl()
	 {
        return $this->getUrl('*/magifybannermanager_groups/save');
	 }
}