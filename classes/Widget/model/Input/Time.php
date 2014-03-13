<?php

class Widget_Input_Time extends Widget_Input_Text
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
        return 'Time';
    }
    
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        $this->set( 'width', '50px' );
        return parent::getControlHtml();
    }
}