<?php
/*******************************************
Magify
This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com.ua)
*******************************************/

class Magify_Misspell_Model_Misspell extends Varien_Object
{
    protected $_helper = null;
    protected $_diffs = array();
    protected $_keys  = array();

    public function _construct()
    {
        $this->_helper = Mage::helper('misspell/string');

        return parent::_construct();
    }

    public function getSuggest($baseQuery)
    {
        $mktime       = microtime(true);
        $this->_diffs = array();
        $this->_keys  = array();
        $final        = array();

        $queries = $this->_helper->splitWords($baseQuery, false, 0);
        foreach ($queries as $query) {
            $len = $this->_helper->strlen($query);

            if ($len < $this->_helper->getGram() || is_numeric($query)) {
                $final[] = $query;
                continue;
            }

            $result  = $this->getBest($query);
            $keyword = $result['keyword'];

            $this->split($query, '', $query);
            $splitKeyword = '';

            if (count($this->_diffs)) {
                arsort($this->_diffs);
                $keys         = array_keys($this->_diffs);
                $key          = $keys[0];
                $splitKeyword = $this->_keys[$key];
            }

            $basePer  = $this->similarity($query, $keyword);
            $splitPer = $this->similarity($query, $splitKeyword);
            if ($basePer > $splitPer) {
                $final[] = $keyword;
            } else {
                $final[] = $splitKeyword;
            }

        }

        $result = implode(' ', $final);

        if ($this->similarity($result, $baseQuery) < 50) {
            $result = '';
        }

        Mage::log(round(microtime(true) - $mktime, 4).' '.$baseQuery.' == '.$result, null, 'misspell.log');

        return $result;
    }

    protected function split($query, $prefix = '', $base = '')
    {
        $keyword = $query;
        $len = $this->_helper->strlen($query);

        if ($len > 20) {
            return false;
        }

        for ($i = $this->_helper->getGram(); $i <= $len - $this->_helper->getGram() + 1; $i++) {
            $a = $this->_helper->substr($query, 0, $i);
            $b = $this->_helper->substr($query, $i);

            $aa = $this->getBest($a);
            $bb = $this->getBest($b);

            $key = $a.'|'.$b;

            if ($prefix) {
                $key = $prefix.'|'.$key;
            }

            $this->_keys[$key] = '';
            if ($prefix) {
                $this->_keys[$key] = $prefix.' ';
            }
            $this->_keys[$key] .= $aa['keyword'].' '.$bb['keyword'];


            $this->_diffs[$key] = ($this->similarity($base, $this->_keys[$key]) + $aa['diff'] + $bb['diff']) / 3;


            if ($prefix) {
                $kwd = $prefix.'|'.$aa['keyword'];
            } else {
                $kwd = $aa['keyword'];
            }

            if ($aa['diff'] > 50) {
                $this->split($b, $kwd, $query);
            }

        }

        return null;
    }

    public function getBest($query)
    {
        $len     = intval($this->_helper->strlen($query));
        $trigram = $this->_helper->getTrigram($this->_helper->strtolower($query));

        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $tableName  = Mage::getSingleton('core/resource')->getTableName('misspell/misspell');

        $select    = $connection->select();
        $relevancy = new Zend_Db_Expr('(-ABS(LENGTH(keyword) - '.$len.') + MATCH (trigram) AGAINST("'.$trigram.'")) AS relevancy');
        $select->from($tableName, array('keyword', $relevancy, 'freq'))
            ->order('relevancy desc')
            ->limit(5);

        $keywords   = $connection->fetchAll($select);
        $preresults = array();
        foreach ($keywords as $keyword) {
            $preresults[$keyword['keyword']] = $this->similarity($query, $keyword['keyword']) + $keyword['freq'];
        }
        arsort($preresults);
        $keys   = array_keys($preresults);
        $result = array();
        if (count($keys) > 0) {
            $keyword = $keys[0];
            $keyword = $this->_toSameRegister($keyword, $query);
            $diff    = $preresults[$keys[0]];
            $result = array('keyword' => $keyword, 'diff' => $diff);
        } else {
            $result = array('keyword' => $query, 'diff' => 100);
        }
        return $result;
    }

    public function _toSameRegister($str, $base)
    {
        $minLen = min($this->_helper->strlen($base), $this->_helper->strlen($str));
        for ($i = 0; $i < $minLen; $i++) {
            $chr = $this->_helper->substr($base, $i, 1);
            if ($chr != $this->_helper->strtolower($chr)) {
                $nchr = $this->_helper->substr($str, $i, 1);
                $nchr = strtoupper($nchr);
                $str = substr_replace($str, $nchr, $i, 1);
            }
        }
        return $str;
    }

    public function similarity($keyword, $result)
    {
        $levenshtein = Mage::getSingleton('misspell/dameraulevenshtein');
        $percentage  = $levenshtein->similarityi($keyword, $result);

        return $percentage;
    }

    protected function _getMinFtLength()
    {
        $variables  = $this->_getConnection()->fetchPairs('SHOW VARIABLES');
        return (!isset($variables['have_innodb']) || $variables['have_innodb'] != 'YES') ? false : true;
    }
}