<?php

class Emzee_Dev_Block_Info_ClassHierarchy extends Mage_Core_Block_Template
{
    /**
     * Returns the method parameters as a string.
     * 
     * @param ReflectionMethod $method
     * @return string
     */
    public function getMethodParameterString(ReflectionMethod $method)
    {
        $parameters = array();
        foreach ($method->getParameters() as $rParameter) {
            $parameters[] =
                $rParameter->getName() .
                ($rParameter->isOptional() ? ' = ' . $rParameter->getDefaultValue() : '');
        }
        return implode(', ', $parameters);
    }
}
