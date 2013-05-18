<?php

class Emzee_Dev_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Returns information on the desired variable in HTML format.
     * 
     * @param mixed $variable Variable to debug
     * @return string HTML
     */
    public function info($variable)
    {
        return Mage::getSingleton('emzee_dev/debug')->info($variable);
    }
}
