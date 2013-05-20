<?php
/**
 * @copyright (c) 2010 Matthias Zeis (admin@matthias-zeis.com)
 * @license   OSL - Open Software Licence 3.0 (http://opensource.org/licenses/osl-3.0.php)
 */
class Emzee_Dev_Model_Debug
{
    
    /**
     * Prints information that can be gathered from the Magento configuration.
     */
    public function configInfo()
    {
        $html = '';
        
        $config = Mage::getConfig();
        $options = $config->getOptions();
        
        $html .= '<h2>Verzeichnisse</h2>';
        $html .= '<p>Basis-Verzeichnis: ' . $options->getBaseDir() . '</p>'; 
        $html .= '<p>App-Verzeichnis: ' . $options->getAppDir() . '</p>'; 
        $html .= '<p>Code-Verzeichnis: ' . $options->getCodeDir() . '</p>'; 
        $html .= '<p>Design-Verzeichnis: ' . $options->getDesignDir() . '</p>'; 
        $html .= '<p>etc-Verzeichnis: ' . $options->getEtcDir() . '</p>'; 
        $html .= '<p>lib-Verzeichnis: ' . $options->getLibDir() . '</p>';
        $html .= '<p>local-Verzeichnis: ' . $options->getLocaleDir() . '</p>';
        $html .= '<p>media-Verzeichnis: ' . $options->getMediaDir() . '</p>';
        $html .= '<p>skin-Verzeichnis: ' . $options->getSkinDir() . '</p>';
        $html .= '<p>Temporäres System-Verzeichnis: ' . $options->getSysTmpDir(). '</p>';
        
        $html .= "<h2>Module</h2>";
        $html .= "<table>
                    <thead>
                       <tr>
                          <th>Modul</th>
                          <th>Status</th>
                          <th>Pool</th>
                          <th>Abhängigkeiten</th>
                          <th>Version</th>
                       </tr>
                    </thead>
                    <tbody>";
        $modules = $config->getModuleConfig();
        
        $moduleStatusClassActive = "active";
        $moduleStatusClassInactive = "inactive";
        foreach ($modules->asCanonicalArray() as $moduleName => $information) {
            $html .= "<tr class='" . ($information['active'] == 'true' ? $moduleStatusClassActive : $moduleStatusClassInactive) . "'>";
            $html .= "<td>$moduleName</td>
                      <td>" . ($information['active'] == 'true' ? 'Aktiv' : 'Inaktiv')  .  "</td>
                      <td class='{$information['codePool']}'>{$information['codePool']}</td>
                      <td>" . ((array_key_exists('depends', $information) && is_array($information['depends'])) ? implode(', ', array_keys($information['depends'])) : '&nbsp;') . "</td>
                      <td>{$information['version']}</td>";
            $html .= "</tr>";
        }
        
        $html .= "</tbody></table>";
        
        /**
         * Complete XML as array
         * 
         * Array-Keys:
         *    [0] => global
         *    [1] => default
         *    [2] => admin
         *    [3] => modules
         *    [4] => frontend
         *    [5] => adminhtml
         *    [6] => install
         *    [7] => stores
         *    [8] => websites
         *    [9] => crontab
         *    [10] => find_feed_attributes
         *    [11] => phoenix
         * 
         * @var array
         */
        $configArray = $config->getNode()->asArray();
        
        /**
         * Entries within area "global":
         *    [0] => install
         *    [1] => resources
         *    [2] => resource
         *    [3] => models
         *    [4] => crypt
         *    [5] => blocks
         *    [6] => helpers
         *    [7] => template
         *    [8] => cache
         *    [9] => session
         *    [10] => request
         *    [11] => log
         *    [12] => eav_frontendclasses
         *    [13] => eav_attributes
         *    [14] => page
         *    [15] => events
         *    [16] => currency
         *    [17] => cms
         *    [18] => index
         *    [19] => fieldsets
         *    [20] => customer
         *    [21] => catalog
         *    [22] => catalogrule
         *    [23] => catalogindex
         *    [24] => payment
         *    [25] => sales
         *    [26] => pdf
         *    [27] => ignoredModules
         *    [28] => ignore_user_agents
         *    [29] => widget
         *    [30] => wishlist
         *    [31] => importexport
         *    [32] => external_cache
         *    [33] => newsletter
         *    [34] => disable_local_modules
         *    [35] => session_save
         */
        
        /**
         * Entries within area "frontend":
         *    [0] => routers
         *    [1] => translate
         *    [2] => layout
         *    [3] => secure_url
         *    [4] => events
         *    [5] => default
         *    [6] => catalog
         *    [7] => product
         *    [8] => category
         *    [9] => cache
         */
        
        echo $html;
    }
    
    /**
     * Returns information on the provided variable.
     * 
     * @param  mixed  $variable
     * @param  string $label    Bezeichnung
     * @return string
     */
    public function info($variable, $label = '')
    {
        if (!Mage::helper('core')->isDevAllowed()) {
            return '';
        }

        switch (gettype($variable)) {
            case 'object':
                $template = 'emzee_dev/info/type/object.phtml';
                $variable      = Mage::getModel('emzee_dev/debug_object', $variable);
                break;
            default:
                $template = 'emzee_dev/info/type/default.phtml'; 
        }
        
        $block = Mage::app()->getLayout()->createBlock('emzee_dev/info')
            ->setTemplate($template)
            ->assign('label', $label)
            ->assign('variable', $variable);
                                         
        $classHierarchy = Mage::app()->getLayout()->createBlock('emzee_dev/info_classHierarchy', 'classHierarchy')
            ->setNameInLayout('classHierarchy')
            ->setTemplate('emzee_dev/info/classHierarchy.phtml')
            ->assign('variable', $variable);
        
        $blockClass = Mage::app()->getLayout()->createBlock('core/template', 'blockClass')
            ->setNameInLayout('blockClass')
            ->setTemplate('emzee_dev/info/blockClass.phtml')
            ->assign('variable', $variable);
        
        $backtrace = Mage::app()->getLayout()->createBlock('emzee_dev/info_backtrace', 'backtrace')
            ->setNameInLayout('backtrace')
            ->setTemplate('emzee_dev/info/backtrace.phtml')
            ->assign('backtrace', debug_backtrace());
                                             
        $block->append($classHierarchy, 'classHierarchy');
        $block->append($blockClass, 'blockClass');
        $block->append($backtrace, 'backtrace');
        
        return $block->toHtml();                               
    }
    
    /**
     * Returns the amount of allocated RAM in this very moment.
     *
     * @param  boolean $realUsage The same parameter as for memory_get_usage()
     * @param  boolean $rawData Returns the size without "beautified" size (and as integer) 
     * @return string|int
     */
    public function memoryUsage($realUsage = false, $rawData = false)
    {
        if (!Mage::helper('core')->isDevAllowed()) {
            return '';
        }
        
        $size = memory_get_usage((bool)$realUsage);
        
        if ((bool)$rawData) {
            return $size;
        } 
        
        $unit = array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    
    public function modelInfo()
    {
        if (!Mage::helper('core')->isDevAllowed()) {
            return '';
        }
        
        $config = Mage::getConfig()->getNode()->global->models->asArray();
        ksort($config);
        
        $groups = array_keys($config);
        
        $html = '
        <style type="text/css">
        #emzee_dev_modelinfo {
            background-color: #fff;
            border: 1px solid #ccc;
            padding:    10px;
            text-align: left;
        }
        
        #emzee_dev_modelinfo li {
            border-bottom:  1px solid #eef;
            margin: 0 0 10px;
            padding: 0 0 5px;
        }
        
        #emzee_dev_modelinfo .error {
            color:  #f00;
            font-weight:    bold;
        }
        </style> 
        
        <ol id="emzee_dev_modelinfo">';
        
        foreach ($config as $group => &$groupData) {
            $html .= '<li id="group-' . $group . '">';
            $html .= "<strong>$group</strong>:<br>Zu finden in: " . $groupData['class'];
            unset($groupData['class']); // @todo delete: only used for finding all informations to show
            
            if (array_key_exists('resourceModel', $groupData)) {
                $html .= "<br>Resource model: ";
                if (in_array($groupData['resourceModel'], $groups)) {
                    $html .= "<a href='#group-{$groupData['resourceModel']}'>{$groupData['resourceModel']}</a>";
                } else {
                    $html .= "<span class='error'>Fehler: Gruppe '{$groupData['resourceModel']}' erwartet, aber nicht definiert</span>";
                }
                unset($groupData['resourceModel']); // @todo delete: only used for finding all informations to show
            }
            
            if (array_key_exists('entities', $groupData)) {
                $html .= "<br>Entitäten: ";
                $entities = array();
                foreach ($groupData['entities'] as $entityName => &$entityData) {
                    if (array_key_exists('table', $entityData)) {
                        $entities[] = "<span title='{$entityData['table']}'>$entityName</span>";
                        unset($entityData['table']); // @todo delete: only used for finding all informations to show
                    }
                    
                    if (empty($entityData)) {
                        unset($groupData['entities'][$entityName]); // @todo delete: only used for finding all informations to show
                    }
                    
                    if (empty($groupData['entities'])) {
                        unset($groupData['entities']); // @todo delete: only used for finding all informations to show
                    }
                }
                $html .= implode(', ', $entities);
            }
            
            $html .= '</li>';
            
            if (empty($groupData)) {
                unset($config[$group]);  // @todo delete: only used for finding all informations to show
            }
        }
        
        $html .= '</ol>';
        
        return $html;
        
        /*
        if (!empty($config)) {
            $this->info($config, '', true, '', 'text-align:left;');  // @todo delete: only used for finding all informations to show
        }
        */
        
    }
}
