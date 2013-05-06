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

class Magify_Misspell_Model_Indexer extends Varien_Object
{
    protected $_likeTables = array(
        'catalog',
        'blog',
        'cms',
        'action',
        'news',
        'eav_attribute_option',
    );

    protected $_ignoreTables = array(
        'catalogsearch',
        'index',
        'stock',
        'catalogrule',
        'newsletter',
        'payment'
    );

    public function reindexAll()
    {
        $helper     = Mage::helper('misspell/string');
        $resource   = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('core_write');
        $tables     = $connection->listTables();

        foreach ($tables as $table) {
            $like   = false;
            $ignore = false;

            foreach ($this->_likeTables as $likeTable) {
                if (strpos($table, $likeTable) !== false) {
                    $like = true;
                }
            }
            foreach ($this->_ignoreTables as $ignoreTable) {
                if (strpos($table, $ignoreTable) !== false) {
                    $ignore = true;
                }
            }

            if (!$like || $ignore) {
                continue;
            }

            $columns = $this->_getTextColumns($table);
            if (!count($columns)) {
                continue;
            }

            $select      = $connection->select();
            $fromColumns = new Zend_Db_Expr('CONCAT('.implode(",' ',", $columns).') as data_index');
            $select->from($table, $fromColumns);


            $result = $connection->query($select);
            while ($row = $result->fetch()) {
                $dataindex = $row['data_index'];

                $dataindex = $helper->cleanString($dataindex);
                $words     = $helper->splitWords($dataindex, false, 0);

                foreach ($words as $word) {
                    if ($helper->strlen($word) >= $helper->getGram()
                        && !is_numeric($word)) {
                        $word = $helper->strtolower($word);
                        if (!isset($preresults[$word])) {
                            $preresults[$word] = 1;
                        } else {
                            $preresults[$word] ++;
                        }
                    }
                }
            }
        }

        $tableName = Mage::getSingleton('core/resource')->getTableName('misspell/misspell');
        $connection->delete($tableName);

        foreach ($preresults as $word => $freq) {
            $rows[] = array(
                'keyword' => $word,
                'trigram' => $helper->getTrigram($word),
                'freq'    => $freq / count($preresults),
            );

            if (count($rows) > 1000) {
                $connection->insertArray($tableName, array('keyword', 'trigram', 'freq'), $rows);
                $rows = array();
            }
        }

        if (count($rows) > 0) {
            $connection->insertArray($tableName, array('keyword', 'trigram', 'freq'), $rows);
        }

        $connection->delete(Mage::getSingleton('core/resource')->getTableName('misspell/misspell_suggest'));

        return count($preresults);
    }

    protected function _getTextColumns($table)
    {
        $result = array();
        $types  = array('text', 'varchar', 'mediumtext', 'longtext');
        $columns = Mage::getSingleton('core/resource')->getConnection('core_write')->describeTable($table);
        foreach ($columns as $column => $info) {
            if (in_array($info['DATA_TYPE'], $types)) {
                $result[] = $column;
            }
        }

        return $result;
    }
}