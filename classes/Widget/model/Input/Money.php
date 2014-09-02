<?php

class Widget_Input_Money extends Widget_Input_Text
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
        return 'Money';
    }
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        $this->arrStyles['width'] = '70px';
        $this->arrStyles['text-align'] = 'right';
        
        $this->arrAttributes['maxlength'] = '20';
        $this->arrAttributes['type'] = 'text';

        if ( $this->isBootstrap2() ) {
            return '<div class="input-prepend"><span class="add-on">$</span>'
                . parent::getControlHtml()
               . '</div>';
        } else {
            return '<div class="input-group"><div class="input-group-addon">$</span>'
                . parent::getControlHtml()
               . '</div>';
        }
    }
}