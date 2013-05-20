<?php

class Emzee_Dev_Model_Debug_Object
{
    protected $_publicMethods = null;
    
    /**
     * @var ReflectionClass
     */
    protected $_reflectionClass = null;
    
    /**
     * @var mixed
     */
    protected $_variable = null;
    
    /**
     * Calculates the hex color for the class. We fade from
     * red to green, so we have a maximum of 512 shades to play with
     * (from 255,0,0 to 255,255,0 and from 255,255,0 to 0,255,0).
     * 
     * @param  int $shades
     * @param  int $index
     * @return string
     */
    protected function _getColorHex($shades, $index)
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
     * @var mixed $variable
     */
    public function __construct($variable)
    {
        $this->_variable = $variable;
    }
    
    /**
     * Returns the class hierarchy.
     * 
     * @return array
     */
    public function getClassHierarchy()
    {
        $class = new ReflectionClass($this->_variable);
        
        $hierarchy = array(array(
            'file' => $class->getFileName(),
            'name' => $class->getName()
        ));
        
        while ($parent = $class->getParentClass()) {
            array_unshift($hierarchy, array(
                'file' => $parent->getFileName(),
                'name' => $parent->getName()
            ));
            $class = $parent;
        }
        
        $count = count($hierarchy);
        $i = 0;
        foreach ($hierarchy as &$level) {
            $level['color'] = $this->_getColorHex($count, $i++);
        }
        
        return $hierarchy;
    }
    
    /**
     * @return array
     */
    public function getPublicMethods()
    {
        if ($this->_publicMethods === null) {
            $methods = array();
            foreach ($this->getReflectionClass()->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $methods[$method->getName()] = $method;
            }
            ksort($methods);
            $this->_publicMethods = $methods;
        }
        
        return $this->_publicMethods;
    }
    
    /**
     * Returns a reflection of the class.
     * 
     * @return ReflectionClass
     */
    public function getReflectionClass()
    {
        if (is_null($this->_reflectionClass)) {
            $this->_reflectionClass = new ReflectionClass($this->_variable); 
        }
        return $this->_reflectionClass;
    }
    
    /**
     * Returns the original variable.
     * 
     * @return mixed
     */
    public function getVariable()
    {
        return $this->_variable;
    }
    
    /*
     * Returns whether the variable has public methods.
     * 
     * @return boolean
     */
    public function hasPublicMethods()
    {
        return (count($this->getPublicMethods()) > 0);
    }
    
    public function isBlockClass()
    {
        return $this->_variable instanceof Mage_Core_Block_Abstract;
    }
}
