<?php
class Magify_MstCore_Helper_Code extends Mage_Core_Helper_Data
{
    const LICENSE_URL = 'http://magify.com/lc/check/';
    const STATUS_APPROVED = 'APPROVED';
    const EE_EDITION = 'EE';
    const PE_EDITION = 'PE';
    const CE_EDITION = 'CE';

    protected static $_edition = false;
    protected $o;
    protected $k;
    protected $s;

    public static function getEdition()
    {
        if (!self::$_edition) {
            $pathToClaim = BP . DS . "app" . DS . "etc" . DS . "modules" . DS . 'Enterprise'. "_" . 'Enterprise' .  ".xml";
            $pathToEEConfig = BP . DS . "app" . DS . "code" . DS . "core" . DS . 'Enterprise' . DS . 'Enterprise' . DS . "etc" . DS . "config.xml";
            $isCommunity = !file_exists($pathToClaim) || !file_exists($pathToEEConfig);
            if ($isCommunity) {
                 self::$_edition = self::CE_EDITION;
            } else {
                $_xml = @simplexml_load_file($pathToEEConfig,'SimpleXMLElement', LIBXML_NOCDATA);
                if(!$_xml===FALSE) {
                    $package = (string)$_xml->default->design->package->name;
                    $theme = (string)$_xml->install->design->theme->default;
                    $skin = (string)$_xml->stores->admin->design->theme->skin;
                    $isProffessional = ($package == "pro") && ($theme == "pro") && ($skin == "pro");
                    if ($isProffessional) {
                        self::$_edition = self::PE_EDITION;
                        return self::$_edition;
                    }
                }
                self::$_edition = self::EE_EDITION;
            }
        }
        return self::$_edition;
    }

    private function check()
    {
        if (time() - Mage::app()->loadCache(md5(self::LICENSE_URL)) > 24 * 60 * 60) {
            $this->refresh();
        }
    }

    public function getCodeHelper($extensionName) {
        $file = Mage::getBaseDir().'/app/code/local/Magify/'.$extensionName.'/Helper/Code.php';
        if (file_exists($file)) {
            $helper = Mage::helper(strtolower($extensionName).'/code');
            return $helper;
        }
        return false;
    }

    public function getCodeHelper2($extensionName) {
        foreach (Mage::getConfig()->getNode('modules')->children() as $name => $module) {
            if (strtolower('Magify_'.$extensionName) === strtolower($name)) {
                $parts = explode('_', $name);
                $helper = $this->getCodeHelper($parts[1]);
                return $helper;
            }
        }
        return false;
    }

    private function refresh()
    {
        $params       = array();
        $params['v']  = 1;
        $params['d']  = Mage::getBaseUrl();
        $params['ip'] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
        $params['mv'] = Mage::getVersion();
        $params['me'] = self::getEdition();
        $orderIds     = array();
        $keys         = array();
        foreach (Mage::getConfig()->getNode('modules')->children() as $name => $module) {
            if (strpos($name,'Magify_') === 0) {
                if ($name == 'Magify_MstCore') {
                    continue;
                }
                $parts = explode('_', $name);
                if ($helper = $this->getCodeHelper($parts[1])) {
                    $orderId = (int)$helper->getOrderId();
                    if ($orderId > 0) {
                        $orderIds[] = $orderId.'@'.$helper->getKey().'@'.$helper->getSku();
                    }
                }
            }
        }
        $params['p'] = implode($orderIds, '_');
        try {
            Mage::app()->saveCache(time(), md5(self::LICENSE_URL));

            $result = $this->getResponce(self::LICENSE_URL, $params);
            if (!$result || $result == '') {
                return $this;
            }
            $result = base64_decode($result);
            $xml = simplexml_load_string($result);
            $products = array();
            try {
                if ($record = Mage::getStoreConfig('mstcore/system/status')) {
                   $products = str_rot13(base64_decode(unserialize($record)));
                }
            } catch (Exception $ex) {}
            foreach ($xml->products->product as $product) {
                $products[(string)$product->sku] = array(
                		'status' => (string)$product->status,
                        'message' => (string)$product->message
                );
            }
            $this->saveConfig('mstcore/system/status', base64_encode(str_rot13(serialize($products))));
        } catch (Exception $ex) {}
        return $this;
    }

    private function checkLicense() {
        $sku = $this->getSku();
        $this->check();
        if (!$record = Mage::getStoreConfig('mstcore/system/status')) {
            return self::STATUS_APPROVED;
        }
        try {
            $products = unserialize(str_rot13(base64_decode($record)));
        } catch (Exception $ex) {}
        if(isset($products[$sku])) {
            $record = $products[$sku];
            if ($record['status'] == 'BANNED') {
                return $record['message'];
            }
        }
        return self::STATUS_APPROVED;
    }

    private function saveConfig($path, $value, $scope = 'default', $scopeId = 0)
    {
        $resource = Mage::getResourceModel('core/config');
        $resource->saveConfig(rtrim($path, '/'), $value, $scope, $scopeId);
        return $this;
    }

    private function getResponce($url, $params)
    {
        $curl = new Varien_Http_Adapter_Curl();
        $curl->write(Zend_Http_Client::POST, $url, '1.1', array(), http_build_query($params, '', '&'));
        $data = $curl->read();
        $data = preg_split('/^\r?$/m', $data, 2);
        $data = trim($data[1]);
        return $data;
    }


    /**
     * вызов только из наследуемого класса
     */
    public function checkLicenseKeyAdmin($ob) {
        $result = $this->checkLicense();
        if ($result === self::STATUS_APPROVED) {
            return true;
        }
        $session = Mage::getSingleton('adminhtml/session');
        $session->addError(Mage::helper('mstcore')->__($result));
        $url = Mage::helper('adminhtml')->getUrl('adminhtml');
        Mage::app()->getResponse()->setRedirect($url);
        return false;
    }

    /**
     * вызов только из наследуемого класса
     */
    public function checkLicenseKey() {
        $result = $this->checkLicense();
        if ($result === self::STATUS_APPROVED) {
            return true;
        }
        return false;
    }

    /**
     * вызов только из наследуемого класса
     */
    public function checkConfig() {
        $result = $this->checkLicense();
        if ($result === self::STATUS_APPROVED) {
            return true;
        }
        $session = Mage::getSingleton('adminhtml/session');
        $session->addError(Mage::helper('mstcore')->__($result));
        return true;
    }

    protected function getKey() {
        return $this->k;
    }

    protected function getSku() {
        return $this->s;
    }

    protected function getOrderId() {
        return $this->o;
    }
}
