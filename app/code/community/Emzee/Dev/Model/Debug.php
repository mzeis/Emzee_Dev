<?php
/**
 * @copyright (c) 2010 Matthias Zeis (admin@matthias-zeis.com)
 * @license   OSL - Open Software Licence 3.0 (http://opensource.org/licenses/osl-3.0.php)
 */
class Emzee_Dev_Model_Debug
{
    
    static protected $_allowedIps = array();
    
    /**
     * Calculates the hex color for the class. We fade from
     * red to green, so we have a maximum of 512 shades to play with
     * (from 255,0,0 to 255,255,0 and from 255,255,0 to 0,255,0).
     * 
     * @param  int $shades
     * @param  int $index
     * @return string
     */
    static protected function _getColorHex($shades, $index)
    {
        $shades = (int)$shades;
        $index  = (int)$index;
        
        if ($index > $shades) {
            throw new Exception('Index must not be higher than count of shades.');
        }
        
        // special case: with one shade, we return the value for green immediately
        if ($shades == 1) {
            return '#00FF00';
        }
        
        $part = 512 / ($shades - 1);
        
        $value = round($part * $index);
        
        /**
         * @refactor: workaround: value 256 has to be 255 actually.
         */
        if ($value == 256) {
            $value = 255;
        }
        
        if ($value <= 255) {
            return '#FF' . ($value < 16 ? '0' : '') . dechex($value) . '00';
        } else {
            $value = 512 - $value;
            return '#' . ($value < 16 ? '0' : '') . dechex($value) . 'FF00';
        }
        
    }

    /**
     * Returns the CSS code for the debug container.
     * 
     * @param  string $id Identifier for the container
     * @return string
     */
    static protected function _getCssCode($id)
    {
        return "<style type='text/css'>
        .emzee-dev-debug-container {
            background-color:   #fff;
            border:             1px solid black;
            padding:            0;
            position:           absolute;
            text-align:         left;
            width:              960px;
            z-index:            10000;
        }
        
        #$id h1.handle {
            background-color:   #e6e6e6;
            border-bottom:      1px solid #999;
            margin:             0;
            padding:            3px;
        }
        
        #$id div#emzee-dev-debug-main-toggle {
            background-color:   #686868;
            color:              #fff;
            float:              right;
            font-size:          16px;
            padding:            5px;
        }
        
        #$id .handle:hover {
            cursor:             move;
        }
        
        #$id h2 {
            height:             14px;
            line-height:        17px;
            margin:             16px 0 8px;
            padding:            0 5px;
        }
        
        #$id ul {
            /*list-style-type:  disc;*/
            margin-left:        15px;
            padding:            0 3px;
        }
        
        #$id .class-color {
            border-left:        14px solid #fff;
            padding-left:       5px;
        }
        
        #$id .expandable:hover {
            cursor:             pointer;
        }
        
        #$id table {
            border:             1px solid #ddd;
            border-width:       1px 0;
            border-collapse:    collapse;
            width:              100%;
        }
        
        #$id table#backtrace-list div.filename {
            background-color:   #cccccf;
            font-weight:        bold;
            padding:            2px 5px;
        }
        
        #$id th, #$id td {
            border:             1px solid #ddd;
            padding:            4px 6px;
        }
        
        #$id th {
            background-color:   #eee;
        }
        
        #$id th.index, #$id td.index {
            text-align:         center;
        }
        
        #$id td.backtrace-info {
            padding:            0;
        }
        
        #$id ol.codeExcerpt {
            border:             0 solid #ddd;
            border-width:       1px 0 0;
            list-style:         decimal-leading-zero none inside;
            overflow:           auto; 
        }
        
        #$id ol.codeExcerpt li {
            border-bottom:      1px solid #ddd;
            padding:            2px;
        }
        
        #$id ol.codeExcerpt li.current-line {
            background-color:   #dfd;
            cursor:             pointer;
            font-weight:        bold;
        }
        
        #$id ol.codeExcerpt pre {
            display:            inline;
        }
        
        </style>";
    }
    
    /**
     * Returns the IP of the current user.
     *
     * @return string
     */
    static protected function _getIp()
    {

        if (isset($_SERVER)) {

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
    
            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];
    
            return $_SERVER["REMOTE_ADDR"];
        }
    
        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');

        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');

        return getenv('REMOTE_ADDR');

    }
    
    /**
     * Generates an excerpt of the code in the file.
     * 
     * @param  string $path  Path to the file (absolute path preferred)
     * @param  int    $line  Main code line number
     * @param  int    $range Number of lines before and after the main code line
     *                       that should be included 
     * @return string
     */
    static protected function _getExcerptHtml($file, $line, $range = 2)
    { 
        if ($line < 1) {
            return '';
        }
        
        // SplFileObject::seek() is zero-based, so we subtract 1.
        $from = $line - $range - 1;
        if ($from < 0) {
            $from = 0;
        }
        $main = $line - 1;
        $to   = $line + $range - 1;
        
        /**
         * @todo:
         * - check $range and $line
         * - check file existence and read permissions
         */
        $result = "<ol class='codeExcerpt' start='" . ($from + 1)  . "'>\n";
        $file = new SplFileObject($file);
        
        
        
        $file->seek($from);
        do {
            if ($file->key() == $main) {
                $result .= "<li class='current-line'><pre>" . htmlentities($file->current()) . "</pre></li>\n";
            } else {
                $result .= "<li><pre>" . htmlentities($file->current()) . "</pre></li>\n";
            }
            $file->next();
        } while ($file->key() <= $to);
        
        $result .= "</ol>";
        
        return $result;
    }
    
    /**
     * Returns the JavaScript code for the debug container.
     * 
     * @param  string $id Identifier for the container
     * @return string
     */
    static protected function _getJsCode($id)
    {
        return "<script type='text/javascript'>
        new Draggable('$id', {'handle': 'handle', 'starteffect': '', 'endeffect': ''});
        Event.observe(window, 'load', function() {
            Event.observe('emzee-dev-debug-main-toggle', 'click', function(event) {
                $('emzee-dev-debug-container-content').toggle();
            });
            Event.observe('hierarchy-header', 'click', function(event) {
                $('hierarchy-list').toggle();
            });
            Event.observe('methods-header', 'click', function(event) {
                $('methods-list').toggle();
            });
            Event.observe('children-header', 'click', function(event) {
                $('children-list').toggle();
            });
            Event.observe('backtrace-header', 'click', function(event) {
                $('backtrace-list').toggle();
            });
            
        });
        
        </script>";
    }
    
    /**
     * Prints information that can be gathered from the Magento configuration.
     */
    static public function configInfo()
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
     * Gives information on the provided variable.
     * You can choose if you want the HTML to be echoed directly or to be returned.
     * 
     * @param  mixed  $var
     * @param  string $label    Bezeichnung
     * @param  bool   $echo     If true, information will be echoed directly
     * @param  string $cssClass CSS-Klassen
     * @param  string $cssStyle CSS-Styles
     * @return string
     */
    static public function info($var, $label = '', $echo = false, $cssClass = '', $cssStyle = '')
    {
        if (!Mage::helper('core')->isDevAllowed()) {
            return '';
        }

        $id = 'debug-info-' . self::normalise(microtime());
        $result = '';
        
        // Normal print_r for non-objects.
        if (!is_object($var)) {
            $result .= '<pre class="emzee-dev-debug-container ' . $cssClass . '" style="' . $cssStyle . '"><strong>' . $label . '</strong><br>' . print_r($var, true) . '</pre>';
            
            if ($echo === true) {
                echo $result;
                return;
            } else {
                return $result;
            }
        
        }
        
        /**
         * Inospection of the object.
         */
        $rClass = new ReflectionClass($var);
        $result .= "<h1 class='handle'>" . $rClass->getName() . "<div id='emzee-dev-debug-main-toggle' class='expandable'>Toggle</div></h1><div id='emzee-dev-debug-container-content'>";
        
        if ($label) {
            $result .= "<div> style='font-weight:bold;'>$label</div>";
        }
        
        /**
         * Find parent classes and build hierarchy.
         */ 
        $child = new ReflectionClass($var);
        $hierarchy = array();
        while ($parent = $child->getParentClass()) {
            $hierarchy[] = array(
                'file' => $parent->getFileName(),
                'name' => $parent->getName()
            );
            $child = $parent;
        }
        unset($child, $parent);
        $hierarchy = array_reverse($hierarchy);
        $hierarchy[] = array(
            'file' => $rClass->getFileName(),
            'name' => $rClass->getName()
        );
        
        $classes = array();
                
        $result .= "<h2 id='hierarchy-header' class='expandable'>Klassenhierarchie</h2>\n<div id='hierarchy-list'>";
        $i = 0;
        if (count($hierarchy) > 0) {
            foreach ($hierarchy as $parent) {
                $color = self::_getColorHex(count($hierarchy), $i);
                $result .= "<ul class='hierarchy'><li><strong class='class-color' style='border-color:$color'>{$parent['name']}</strong><br/>Pfad: {$parent['file']}";
                $classes[$parent['name']]['color'] = $color;
                $i++;
            }
            for (; $i > 0; $i--) {
                $result .= "</li></ul>";
            }
        }
        $result .= '</div>';
        unset ($hierarchy);
        
        /**
         * Read public methods.
         */
        $result .= "<h2 id='methods-header' class='expandable'>Öffentliche Methoden</h2>";
        $rPublicMethods = $rClass->getMethods(ReflectionMethod::IS_PUBLIC);
        if (count($rPublicMethods > 0)) {
            $tmp = array();
            foreach ($rPublicMethods as $rMethod) {
                $tmp[$rMethod->getName()] = $rMethod;
            }
            ksort($tmp);
            $rPublicMethods = $tmp;
            unset($tmp);
        
            $result .= "<ul id='methods-list' class='methods'>";
            foreach ($rPublicMethods as $rMethod) {
                $rDeclaringClass = $rMethod->getDeclaringClass();
                $name = $rMethod->getName();
                $rParameters = $rMethod->getParameters();
                $parameters = array();
                foreach ($rParameters as $rParameter) {
                    $parameters[] =
                        $rParameter->getName() .
                        ($rParameter->isOptional() ? ' = ' . $rParameter->getDefaultValue() : '');
                    unset($rParameter);
                }
                $parameterString = implode(', ', $parameters);
                $result .= "<li><strong onclick=\"$('{$name}-additional').toggle();\" class='class-color expandable' style='border-color: {$classes[$rDeclaringClass->getName()]['color']}' title='{$rDeclaringClass->getName()}'>{$name}</strong>
                                <p id='{$name}-additional' style='display:none; margin:0;'>
                                    Deklariert in: {$rDeclaringClass->getName()}<br>
                                    Aufruf: {$name}({$parameterString})
                                </p>
                            </li>";
            }
            
            $result .= "</ul>";
        }
        unset ($rPublicMethods, $rMethod, $rDeclaringClass);
        
        /*
         * Special information if this is a block class.
         */
        if ($var instanceof Mage_Core_Block_Abstract) {
            $result .= "<h2 class='expandable' id='children-header'>Child-Blöcke</h2><ul id='children-list' class='child-blocks'>";
            if ($var->getParentBlock()) {
                $result .= "<li><strong>Übergeordneter Block</strong>: {$var->getParentBlock()->getNameInLayout()} (alias {$var->getParentBlock()->getBlockAlias()})</li>"; 
            }
            
            foreach ($var->getChild() as $child) {
                $result .= "<li>Name: {$child->getNameInLayout()} (alias {$child->getBlockAlias()})</li>";
            }
            $result .= '</ul>'; 
        }
        
        
        $result .= "<h2 class='expandable' id='backtrace-header'>Backtrace</h2>
        <table id='backtrace-list' class='backtrace'>
          <thead>
            <tr>    
              <th class='index'>#</th>
              <th>Datei (Hover für absoluten Pfad)</th>
            </tr>
          </thead>
          <tbody>";
        foreach (debug_backtrace() as $dbgIndex=>$dbgLine) {
        
             // Build "Aufruf" information
             $aufruf = '';
             if (isset($dbgLine['type']) && ($dbgLine['type'] == '->' || $dbgLine['type'] == '::')) {
                $aufruf .= "{$dbgLine['class']}{$dbgLine['type']}{$dbgLine['function']}()<br>";
             } else {
                $aufruf .= "{$dbgLine['function']}()<br>";
             }

              if (isset($dbgLine['args']) && count($dbgLine['args']) > 0) {
                $aufruf .= "Argumente:<ul>";
                foreach ($dbgLine['args'] as $dbgLineArgKey => $dbgLineArgVal) {
                    $value = gettype($dbgLineArgVal) == 'string' ? '"' . $dbgLineArgVal . '"' : gettype($dbgLineArgVal);
                    $aufruf .= "<li>$dbgLineArgKey: " . $value . "</li>";
                }
                $aufruf .= "</ul>";
             }
             
            // File-Excerpt lesen
            if (isset($dbgLine['file'])) {
                $excerpt = self::_getExcerptHtml($dbgLine['file'], $dbgLine['line'], 3);    
            } else {
                $excerpt = '<p>Keine Datei vorhanden</p>';
            }
            
            // sometimes there is undefined index 'file'    
            @$result .=
                "<tr>
                   <td class='index'>$dbgIndex</td>
                   <td class='backtrace-info'><div class='filename' title='{$dbgLine['file']}'>" . str_replace(Mage::getBaseDir(), '', $dbgLine['file']) . ": " . $dbgLine['line'] . "</div>
                       $excerpt
                       <div>$aufruf</div></td>
                </tr>";    
        }
        
        $result .= "</tbody>
          </table>";
        
        $result .= '</div>'; // closes div#emzee-dev-debug-container-content
        
        $result .= self::_getCssCode($id);
        
        $result .= self::_getJsCode($id);
        
        $result = "<div id='$id' class='emzee-dev-debug-container' style='border: 1px solid #ddd;'>$result</div>";
        
        if ($echo === true) {
            echo $result;   
            return;
        }
        return $result;
    }
    
    /**
     * Returns the amount of allocated RAM in this very moment.
     *
     * @param  boolean $realUsage The same parameter as for memory_get_usage()
     * @param  boolean $rawData Returns the size without "beautified" size (and as integer) 
     * @return string|int
     */
    static public function memoryUsage($realUsage = false, $rawData = false)
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
    
    static public function modelInfo()
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
            self::info($config, '', true, '', 'text-align:left;');  // @todo delete: only used for finding all informations to show
        }
        */
        
    }
    
    /**
     * Normalises the string (transforms special characters etc.).
     *
     * @param  string $string
     * @return string
     */
    static public function normalise($string)
    {
        $searchReplace = array(
          '.' => '-',
          '_' => '-',
          ' ' => '-',
          'ä' => 'ae',
          'ö' => 'oe',
          'ü' => 'ue',
          'ß' => 'sz'
        );
        
        $result = strtolower((string)$string);
        $result = str_replace(array_keys($searchReplace), array_values($searchReplace), $result);
        
        return $result;
    }
}
