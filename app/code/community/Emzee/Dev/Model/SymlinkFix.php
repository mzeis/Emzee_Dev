<?php

class Emzee_Dev_Model_SymlinkFix
{
    /**
     * Original value of the 'allow symlinks' setting.
     * 
     * @var string|false
     */
    protected $_originalValue = null;
    
    /**
     * Resets the 'allow symlinks' setting for templates after debug HTML has
     * been generated.
     * 
     * @return void
     */
    public function disableSymlinksForTemplates()
    {
        if (Mage::app()->getUpdateMode() === true) {
            Mage::getConfig()->setNode($this->getAllowSymlinkPath(), $this->_originalValue);
        }
    }
    
    /**
     * Tells Magento to allow symlinks for templates if Magento is in upgrade
     * mode (where the database settings are not loaded yet).
     * 
     * @return void
     */
    public function enableSymlinksForTemplates()
    {
        if (Mage::app()->getUpdateMode() === true) {
            $this->_originalValue = Mage::getConfig()->getNode($this->getAllowSymlinkPath());
            Mage::getConfig()->setNode($this->getAllowSymlinkPath(), 1);
        }
    }
    
    /**
     * Returns the xpath for the default store 'allow symlinks' setting.
     * 
     * @return string|false
     */
    public function getAllowSymlinkPath()
    {
        return 'stores/' . Mage_Core_Model_App::DISTRO_STORE_CODE . '/' . Mage_Core_Block_Template::XML_PATH_TEMPLATE_ALLOW_SYMLINK;
    }    
}
