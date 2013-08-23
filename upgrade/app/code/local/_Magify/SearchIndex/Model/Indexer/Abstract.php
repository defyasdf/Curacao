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
 * Represent Indexer model
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
abstract class Magify_SearchIndex_Model_Indexer_Abstract extends Mage_Core_Model_Abstract
{
    const TABLE_PREFIX     = 'm_searchindex_';
    protected $_connection = null;

    abstract public function registerEvent(Mage_Index_Model_Event $event);
    abstract public function processEvent(Mage_Index_Model_Event $event);
    abstract public function reindexAll();
    abstract public function getIndexModel();

    public function getTableName()
    {
        return $this->_getTableName();
    }

    public function getIndexCode()
    {
        return $this->getIndexModel()->getCode();
    }

    public function getPrimaryKey()
    {
        return $this->getIndexModel()->getPrimaryKey();
    }

    protected function _getConnection()
    {
        if ($this->_connection === null) {
            $this->_connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        }
        return $this->_connection;
    }

    protected function _getTableName()
    {
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
        return $tablePrefix.self::TABLE_PREFIX.$this->getIndexCode();
    }

    protected function _isTableExists()
    {
        $tables = $this->_getConnection()->listTables();
        if (is_array($tables) && in_array($this->_getTableName(), $tables)) {
            return true;
        }
        return false;
    }

    protected function _getColumns($real = false)
    {
        $columns = array(
            $this->getPrimaryKey() => array(
                'type'     => 'int(11)',
                'unsigned' => true,
                'is_null'  => false,
                'default'  => null,
            ),
            'store_id' => array(
                'type'     => 'int(11)',
                'unsigned' => true,
                'is_null'  => false,
                'default'  => null,
            ),
            'updated' => array(
                'type'     => 'int(1)',
                'unsigned' => true,
                'is_null'  => false,
                'default'  => '1',
            )
        );

        $attributes = $this->getIndexModel()->getAttributes();

        foreach ($attributes as $code => $weight) {
            $columns[$code] = array(
                'type'     => 'text',
                'unsigned' => false,
                'is_null'  => true,
                'default'  => '',
            );
        }

        if ($real) {
            $realColumns = $this->_getTableColumns();
            foreach ($columns as $column => $descr) {
                if (!in_array($column, $realColumns)) {
                    unset($columns[$column]);
                }
            }
        }

        return $columns;
    }

    protected function _getTableColumns()
    {
        $columns = array_keys($this->_getConnection()->describeTable($this->_getTableName()));

        return $columns;
    }

    protected function _createTable()
    {
        $columnSql = array();
        foreach ($this->_getColumns() as $name => $column) {
            $columnSql[] = $this->_getColumnSql($name, $column);
        }

        if (count($columnSql)) {
            $queryString = "CREATE TABLE ".$this->_getTableName()." ("
                .implode(", ", $columnSql)
                .', PRIMARY KEY (`'.$this->getPrimaryKey().'`,`store_id`)'
                .")\n"
                ."ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->_getConnection()->raw_query($queryString);

            $this->_getConnection()->resetDdlCache($this->_getTableName());

            return true;
        }

        return false;
    }

    protected function _dropTable()
    {
        $queryString = sprintf("DROP TABLE IF EXISTS %s", $this->_getConnection()->quoteIdentifier($this->_getTableName()));
        $this->_getConnection()->raw_query($queryString);
        return $this;
    }

    protected function _getColumnSql($name, $column)
    {
        $queryString = '';
        $queryString .= "`{$name}` {$column['type']}";
        if ($column['unsigned']) {
            $queryString .= " unsigned";
        }
        $queryString .= " ".($column['is_null'] ? 'NULL' : 'NOT NULL');
        if ($column['default']) {
            $queryString .= " DEFAULT ".$this->_getConnection()->quoteInto('?', $column['default']);
        }
        return $queryString;
    }
}