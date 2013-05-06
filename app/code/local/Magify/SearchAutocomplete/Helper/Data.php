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

class Magify_SearchAutocomplete_Helper_Data extends Mage_Core_Helper_Data
{
    public function toSingleRegister ($base, $needle)
    {
        for ($i = 0; $i < strlen($base); $i ++) {
            if (ctype_lower($base[$i])) {
                $needle{$i} = strtolower($needle{$i});
            } else {
                $needle{$i} = strtoupper($needle{$i});
            }
        }
        
        return $needle;
    }

    public function higlight ($text, $query)
    {
        $result = $text;
        
        $query = preg_split("/[,\. ]/", $query);

        foreach ($query as $word) {
            $result = preg_replace("|($word)|Ui", "<strong>$1</strong>", $result);
        }
        
        return $result;
    }
}