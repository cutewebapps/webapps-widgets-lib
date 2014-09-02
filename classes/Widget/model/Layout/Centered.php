<?php

class Widget_Layout_Centered extends Widget_Abstract
{
    public function getCategory()
    {
        return 'Layout';
    }
    
    public function getName()
    {
        return 'Centered';
    }
    
    public function getContext()
    {
        return '';
    }    
    
    public function getPlaceholders()
    {
        return array(
            'central'
        );
    }
    
    public function getOptions()
    {
        return array(
            array( 'name' => 'width', 'type' => 'text', 'value' => '480px', 
                    'caption' => 'Width of Centered' ),
            array( 'name' => 'css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Inner CSS' ),
            array( 'name' => 'outer-css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Outer CSS' )
        );
    }
    
    public function render( App_View $view, $bPreview = false )
    {
        //$objForm = $this->getPaymentForm();
        $strEx = '';
        
        // margin:0px auto;'.$strEx.'width:'.$this->get('width', '480px').'"
        
        $strOut = '<style>'
                .' #'.$this->_strId.' { margin:0px auto; '.$strEx.' width: '.$this->get('width', '480px').'} '
                . ' @media(max-width:640px) { #'.$this->_strId.' { width: 100%; display: block; } }'
                .'</style>'
              . '<div id="'.$this->_strId.'" class="'.$this->getHorizontalForm().'" style="'.$this->get('css').'">'
            . $this->getChild( 'central', $bPreview )
            . '</div>';
        
        
        if ( $this->get('outer-css','') ) {
            $strOut = '<div style="'.$this->get('outer-css').'">'.$strOut.'</div>';
        }
        $strOut = '<div class="element-layout-wrapper" rel="' . $this->get('wiid').'">'.$strOut.'</div>';
        
        return $strOut;
    }
}