<?php

class Widget_Input_Ace extends Widget_Input_Text
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
        return 'Ace';
    }

    public function getContext()
    {
        return '-';
    }
    
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        $this->arrAttributes['type'] = 'textarea';
        
        $this->_arrProperties['rows'] = 5;
        
        if ( $this->get('type') == 'css' || $this->get('type') == 'html' ) {
            $this->arrClasses['ace-'.$this->get('type')] = 'ace-'.$this->get('type');
        }
        return parent::getControlHtml();
    }

}
