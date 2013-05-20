<?php

class Emzee_Dev_Block_Info extends Mage_Core_Block_Template
{
    /**
     * Returns the CSS code for the debug container.
     * The element id is used to raise the CSS specificity of the rules.
     * 
     * @param  string $id Identifier for the container
     * @return string
     */
    protected function _getCssCode($id)
    {
        return "<style type='text/css'>
        .emzee-dev-debug-container {
            background-color:   #fff;
            border:             1px solid #ddd;
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
        
        #$id span#emzee-dev-debug-main-toggle {
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
     * Returns the JavaScript code for the debug container.
     * 
     * @param  string $id Identifier for the container
     * @return string
     */
    protected function _getJsCode($id)
    {
        return "<script type='text/javascript'>
        new Draggable('$id', {'handle': 'handle', 'starteffect': '', 'endeffect': ''});
        Event.observe(window, 'load', function() {
            if ($('emzee-dev-debug-main-toggle') != undefined) {
                Event.observe('emzee-dev-debug-main-toggle', 'click', function(event) {
                    $('emzee-dev-debug-container-content').toggle();
                });
            }
            if ($('hierarchy-header') != undefined) {
                Event.observe('hierarchy-header', 'click', function(event) {
                    $('hierarchy-list').toggle();
                });
            }
            if ($('methods-header') != undefined) {
                Event.observe('methods-header', 'click', function(event) {
                    $('methods-list').toggle();
                });
            }
            if ($('children-header') != undefined) {
                Event.observe('children-header', 'click', function(event) {
                    $('children-list').toggle();
                });
            }                
            if ($('backtrace-header') != undefined) {
                Event.observe('backtrace-header', 'click', function(event) {
                    $('backtrace-list').toggle();
                });
            }
            
        });
        
        </script>";
    }
    
    /**
     * Normalises the string (transforms special characters etc.).
     *
     * @param  string $string
     * @return string
     */
    protected function _normalise($string)
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
    
    /**
     * Returns an unique HTML id.
     * 
     * @return string
     */
    public function getHtmlId()
    {
        return 'debug-info-' . $this->_normalise(microtime());
    }
}
