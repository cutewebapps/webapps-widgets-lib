<?php

class Widget_Static_Image extends Widget_Abstract
{
    public function getCategory()
    {
        return 'Static';
    }
    
    public function getName()
    {
        return 'Image';
    }
    
    public function getContext()
    {
        return '';
    }    
    public function getResources()
    {
        return array();
    }

    
    public function getOptions()
    {
        return array(
            array( 'name' => 'text-align', 
                   'type' => 'dropdown', 
                   'value' => 'center', 
                   'options' => array( 'left' => 'left','center' => 'center','right' => 'right' ),
                   'caption' => 'Text Align' )
            ,
            array( 'name' => 'src', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Type Image URLs' )
            ,
            array( 'name' => 'upload', 
                   'type' => 'upload', 
                   'value' => '', 
                   'caption' => 'Upload From Local' )
            ,
            array( 'name' => 'resource', 
                   'type' => 'dropdown', 
                   'value' => '', 
                   'options' => array( '' => 'No Resource' ) + $this->getResources(),
                   'caption' => 'Select From Resources' )
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
        $strWrapStart = '<div style="text-align:'.$this->get('text-align','center').';'.$this->get('css').'">';
        $strWrapEnd = '</div>';
        
        $strElem = $strWrapStart.'<img src="'.$this->get('src').'" alt="" />'.$strWrapEnd;
        if ( $bPreview ) return $this->getConstructorHtml( $strElem );
        return $strWrapStart.$strElem.$strWrapEnd;
    }
}