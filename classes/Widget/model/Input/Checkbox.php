<?php

class Widget_Input_Checkbox extends Widget_Input_Text
{
    /**
     * @return string
     */
    public function getCategory()
    {
        return 'Input';
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'Checkbox';
    }
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        if ( isset( $this->_arrProperties['value'] ) 
                && $this->_arrProperties['value'] == 1 )
            $this->arrAttributes['checked'] = 'checked';
        
        $this->arrAttributes['type'] = 'checkbox';
        
        return parent::getControlHtml();
    }    
}
    