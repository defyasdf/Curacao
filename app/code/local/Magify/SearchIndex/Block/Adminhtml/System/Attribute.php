<?php
/**
 * Magify
 *
 * This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Magify
 * @package   Magify_SearchIndex
 * @copyright Copyright (C) 2013 Magify (http://magify.com)
 */


/**
 * Abstract block for render attribute list
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
abstract class Magify_SearchIndex_Block_Adminhtml_System_Attribute extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected abstract function _getAttributes();

    public function __construct()
    {
        $this->addColumn('attribute', array(
            'label' => Mage::helper('adminhtml')->__('Attribute'),
            'style' => 'width:120px',
        ));
        $this->addColumn('value', array(
            'label' => Mage::helper('adminhtml')->__('Weight'),
            'style' => 'width:120px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Attribute');
        parent::__construct();
    }

    /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     */
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }

        $column    = $this->_columns[$columnName];
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($columnName == 'attribute') {
            $attributes = $this->_getAttributes();

            $html = '<select class="select" name="' . $inputName . '">';
            foreach ($attributes as $attribute) {
                $html .= '<option value="'.$attribute['code'].'" #{option_'.$attribute['code'].'}>'
                    .addslashes($attribute['label']).' ['.$attribute['code'].']'
                    .'</option>';
            }
            $html .= '</select>';
            return $html;

        } else {
            return '<input type="text" name="' . $inputName . '" value="#{' . $columnName . '}"'.
               ' class="input-text" style="width:50px;" />';
        }
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row['option_'.$row['attribute']] = 'selected';
    }
}