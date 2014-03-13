<?php

class Widget_Input_Number extends Widget_Input_Text
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
        return 'Number';
    }
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        if ( $this->get('id') == 'days' && $this->get('cssclass') == 'bike-rentals' ) {
            $this->_arrProperties['value'] = "1";
        }
        $this->set( 'width', '50px' );
        return parent::getControlHtml();
    }
}