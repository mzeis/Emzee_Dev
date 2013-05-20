<?php

class Emzee_Dev_Block_Info_Backtrace extends Mage_Core_Block_Template
{
    /**
     * Number of rows before and after the line in question to be displayed.
     * 
     * @var int
     */
    protected $_excerptRows = 3;
    
    /**
     * Generates an excerpt of the code in the file.
     * 
     * @param  string $path  Path to the file (absolute path preferred)
     * @param  int    $line  Main code line number
     * @return string
     */
    protected function _getExcerptHtml($file, $line)
    {
        if ($file == '') {
            return '<p>' . $this->__('File not exists') .'</p>';
        }
         
        if ($line < 1) {
            return '';
        }
        
        // SplFileObject::seek() is zero-based, so we subtract 1.
        $from = $line - $this->_excerptRows - 1;
        if ($from < 0) {
            $from = 0;
        }
        $main = $line - 1;
        $to   = $line + $this->_excerptRows - 1;
        
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
} 