<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_SeoSuite
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * SEO Suite extension
 *
 * @category   MageWorx
 * @package    MageWorx_SeoSuite
 * @author     MageWorx Dev Team
 */

class MageWorx_SeoSuite_Model_Catalog_Product_Template_Title extends MageWorx_SeoSuite_Model_Catalog_Product_Template_Abstract
{
    protected $_useDefault = array();
    protected $_defaultProduct = null;

    public function process() {
        if (!$this->_product instanceof Mage_Catalog_Model_Product){
            return;
        }

        try {
            $string = $this->__compile($this->getTemplate());
        } catch (Exception $e){}

        return $string;
    }

//    protected function __compile($template) {
//        $vars = $this->__parse($template);
//        $taxHelper = Mage::helper('tax');
//        if ($taxHelper->displayPriceIncludingTax()) $includingTax = true; else $includingTax = null;
//        foreach ($vars as $key => $params) {
//            foreach ($params['attributes'] as $n => $attribute) {
//                $value = '';
//                switch ($attribute) {
//                    case 'category':
//                        $category = $this->_product->getCategory();
//                        if ($category) {
//                            $value = $this->_product->getCategory()->getName();
//                        } else {
//                            $categoryItems = $this->_product->getCategoryCollection()->load()->getIterator();
//                            $category = current($categoryItems);
//                            if ($category) {
//                                $category = Mage::getModel('catalog/category')->load($category->getId());
//                                $value = $category->getName();
//                            }    
//                        }
//                        break;
////                    case 'categories':
////                        $separator = (string)Mage::getStoreConfig('catalog/seo/title_separator');
////                        $separator = ' ' . $separator . ' ';
////                        $title = array();
////                        $path  = Mage::helper('catalog')->getBreadcrumbPath();
////                        foreach ($path as $name => $breadcrumb) {
////                            $title[] = $breadcrumb['label'];
////                        }
////                        array_pop($title);
////                        $value = join($separator, array_reverse($title));
////                        break;
//                    case 'store_view_name':
//                        $value = Mage::app()->getStore($this->_product->getStoreId())->getName();
//                        break;
//                    case 'store_name':
//                        $value = Mage::app()->getStore($this->_product->getStoreId())->getGroup()->getName();
//                        break;
//                    case 'website_name':
//                        $value = Mage::app()->getStore($this->_product->getStoreId())->getWebsite()->getName();
//                        break;
//                    case 'price':
//                        $product = $this->_product;
//                        if ($product->getTypeId() == 'bundle') {
//                            if($product->getStoreId()!=="") {
//                            list($_minimalPriceTax, $_maximalPriceTax) = $product->getPriceModel()->getTotalPrices($product, null, null, false);
//                         //   echo "$_minimalPriceTax, $_maximalPriceTax"; exit;
//                            $value = Mage::helper('core')->__('From').' '.Mage::helper('core')->currency($_minimalPriceTax,true,FALSE) ." ".Mage::helper('core')->__('To')." ".Mage::helper('core')->currency($_maximalPriceTax,true,FALSE);
//                            }
//                            else {
//                                $value = '';
//                            }
//                        }
//                        elseif ($product->getTypeId() == 'grouped') {
//                            if($product->getMinimalPrice()) {
//                                    $price = Mage::helper('tax')->getPrice($product, $product->getMinimalPrice(), $includingTax);
//                                    $value = Mage::helper('core')->__('Starting at').' '.Mage::helper('core')->currencyByStore($price,$product->getStore(),true,FALSE);
//                                }
//                        }
//                        else {
//                            if ($product->getPrice() > 0){
//                                $price = Mage::helper('tax')->getPrice($product, $product->getPrice(), $includingTax);
//                                $value = Mage::helper('core')->currencyByStore($price,$product->getStore(),true,FALSE);
//                                if($product->getMinimalPrice() && $product->getMinimalPrice()<>$product->getPrice()) {
//                                    $price = Mage::helper('tax')->getPrice($product, $product->getMinimalPrice(), $includingTax);
//                                    $value = Mage::helper('core')->__('Starting at').' '.Mage::helper('core')->currencyByStore($price,$product->getStore(),true,FALSE);
//                                }
//                            }
//                        }
//                        break;
//                    case 'special_price':
//                        $product = $this->_product;
//                        if($product->getSpecialPrice()>0) {
//                            $price = Mage::helper('tax')->getPrice($product, $product->getSpecialPrice(), $includingTax);
//                            $value = Mage::helper('core')->currencyByStore($price,$product->getStore(),true,FALSE);
//                        }
//                        break;
//                    default:
//                        if ($_attr = $this->_product->getResource()->getAttribute($attribute)) {
//                            $value = $_attr->getSource()->getOptionText($this->_product->getData($attribute));
//                        }
//                        if (!$value) {
//                            $value = $this->_product->getData($attribute);
//                        }
//                        if (is_array($value)) {
//                            $value = implode(', ', $value);
//                        }
//                }
//                
//                if ($value) {
//                    $value = $params['prefix'] . $value . $params['suffix'];
//                    break;
//                }
//            }
//            $template = str_replace($key, $value, $template);
//        }
//        return $template;
//    }
}