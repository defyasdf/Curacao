<?php
/*******************************************
Magify

@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com)
*******************************************/

class Magify_MstCore_Helper_Date extends Mage_Core_Helper_Data
{
    public function formatDateForSave($object, $field, $format = false) {
        $_formated     = $object->getData($field . '_is_formated');
        if (!$_formated && $object->hasData($field)) {
            try {
                $value = $this->_formatDateForSave($object->getData($field), $format);
            } catch (Exception $e) {
                throw Mage::exception('Magify_MstCore', Mage::helper('mstcore')->__('Invalid date'));
            }

            if (is_null($value)) {
                $value = $object->getData($field);
            }

            $object->setData($field, $value);
            $object->setData($field . '_is_formated', true);
        }
    }

    /**
     * Prepare date for save in DB
     *
     * string format used from input fields (all date input fields need apply locale settings)
     * int value can be declared in code (this meen whot we use valid date)
     *
     * @param   string | int $date
     * @return  string
     */
    protected function _formatDateForSave($date, $format)
    {
        if (empty($date)) {
            return null;
        }
        if ($format) {
            $date = Mage::app()->getLocale()->date($date,
               $format,
               null, false
            );
        }
        // unix timestamp given - simply instantiate date object
        elseif (preg_match('/^[0-9]+$/', $date)) {
            $date = new Zend_Date((int)$date);
        }
        // international format
        else if (preg_match('#^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$#', $date)) {
            $zendDate = new Zend_Date();
            $date = $zendDate->setIso($date);
        }
        // parse this date in current locale, do not apply GMT offset
        else {
            $date = Mage::app()->getLocale()->date($date,
               Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
               null, false
            );
        }
        return $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    }
}