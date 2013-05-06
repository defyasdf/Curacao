<?php
class Magify_SearchIndex_Model_Resource_Catalog_Fulltext extends Mage_CatalogSearch_Model_Mysql4_Fulltext
{
    protected $_columns = null;

    protected function _construct()
    {
        $this->_init('catalogsearch/fulltext', 'product_id');
        $this->_engine = Mage::getResourceSingleton('searchindex/catalog_fulltext_engine');
    }

    public function rebuildTable()
    {
        $tableName = $this->getMainTable();
        $adapter   = $this->_getWriteAdapter();

        $adapter->resetDdlCache($tableName);

        $describe = $adapter->describeTable($tableName);
        $columns  = $this->getColumns();

        $addColumns  = array_diff_key($columns, $describe);
        $dropColumns = array_diff_key($describe, $columns);

        // Drop columns
        foreach (array_keys($dropColumns) as $columnName) {
            if (!in_array($columnName, array('product_id', 'store_id', 'data_index', 'fulltext_id', 'updated'))) {
                $adapter->dropColumn($tableName, $columnName);
            }
        }

        // Add columns
        foreach ($addColumns as $columnName => $columnProp) {
            $adapter->addColumn($tableName, $columnName, $columnProp);
        }

        return $this;
    }

    protected function getColumns()
    {
        if ($this->_columns === null) {
            $this->_columns = array();
            $this->_columns['updated'] = "int(1) NOT NULL default '1'";
            $columns        = array();

            foreach ($this->_getSearchableAttributes() as $attribute) {
                $cols = $attribute->getFlatColumns();

                if (!count($cols)) {
                    continue;
                }

                if (isset($cols[$attribute->getAttributeCode().'_value'])) {
                    $columns[$attribute->getAttributeCode()] = $cols[$attribute->getAttributeCode().'_value']['type'].' NULL';
                } else {
                    $columns[$attribute->getAttributeCode()] = $cols[$attribute->getAttributeCode()]['type'].' NULL';
                }
            }

            $attributes = unserialize(Mage::getStoreConfig('searchindex/catalog/attribute'));
            if (!is_array($attributes)) {
                $attributes = array();
            }
            foreach ($attributes as $attribute) {
                if (isset($columns[$attribute['attribute']])) {
                    $this->_columns[$attribute['attribute']] = $columns[$attribute['attribute']];
                }
            }
        }


        return $this->_columns;
    }

    protected function _getProductChildIds($productId, $typeId)
    {
        if (!Mage::getStoreConfig('searchindex/catalog/bundled')) {
            return null;
        }

        return parent::_getProductChildIds($productId, $typeId);
    }
}
