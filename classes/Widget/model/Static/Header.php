<?php

class Widget_Static_Header extends Widget_Abstract
{
    public function getCategory()
    {
        return 'Static';
    }
    
    public function getName()
    {
        return 'Header';
    }
    
    public function getContext()
    {
        return '';
    }
    
    public function getOptions()
    {
        return array(
            array( 'name' => 'text-align', 
                   'type' => 'dropdown', 
                   'value' => 'center', 
                   'width' => '120px',
                   'options' => array( 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' ),
                   'caption' => 'Text Align' )
            ,
            array( 'name' => 'font-size', 
                   'type' => 'dropdown', 
                   'value' => '24', 
                   'width' => '80px',
                   'options' => array( '8' => '8', '9' => '9', '10' => '10', '11' => '11', 
                       '12' => '12', '13' => '13', '14' => '14', '16' => '16', '18' => '18', 
                       '20' => '20', '22' => '22', '24' => '24', '26' => '26', '28' => '28',  
                       '30' => '30', '32' => '32', '36' => '36', '40' => '40', '48' => '48', 
                       '64' => '64', '72' => '72' ),
                   'caption' => 'Font size' )
            ,
            array( 'name' => 'title', 
                   'type' => 'text', 
                   'value' => 'Default Text', 
                   'caption' => 'Header Text' )
            ,
            array( 'name' => 'cssclass',
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'CSS Class' )
            ,
            array( 'name' => 'css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Additional CSS' )
        );
    } 
    /**
     * Routine to render a widget
     * @param App_View $view
     * @param boolean $bPreview
     * @return string
     */
    public function render( App_View $view, $bPreview = false )
    {    
        // $objForm = $this->getPaymentForm();
        $strEx = $this->get('css');
        $strEx .= ';text-align:'.$this->get( 'text-align', 'center');
        if ( $this->get( 'font-size') ) {
            $strEx .= ';font-size:'.$this->get( 'font-size').'px';
        } else {
            $strEx .= ';font-size:24px';
        }
        
        // Sys_Debug::dump( $this->_arrProperties );
        
        $strElem = '<div class="legend bold '.$this->get('cssclass').'" style="margin-bottom:5px'.$strEx.'">'.$this->get('title', 'Default Text').'</div>';
        if ( $bPreview ) return $this->getConstructorHtml( $strElem );
        return $strElem;
    }
}