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
    
    public function getCdnFolder()
    {
        return CWA_APPLICATION_DIR.'/cdn/upload';
    }
    
    public function getCdnPath()
    {
        return '/cdn/upload';
    }
    
    public function getExistingFiles()
    {
        $dir = new Sys_Dir( $this->getCdnFolder() );
        
        $arrResources = array();
        foreach ( $dir->getFiles( "@(png|jpeg|jpg|gif)@" ) as $strFile ) {
            $strValue = str_replace( CWA_APPLICATION_DIR, '', $strFile );
            $strFile = str_replace( $this->getCdnFolder(), '', $strFile );
            $arrResources[ $strValue ] = $strFile;
        }
        return $arrResources;
    }
    
    public function getOptions()
    {
        return array(
            array( 'name' => 'text-align', 
                   'type' => 'dropdown', 
                   'value' => 'center', 
                   'options' => array( 'left' => 'left','center' => 'center','right' => 'right' ),
                   'caption' => Lang_Hash::get('Text Align'),
                   'onkeyup'  => 'winstance.image.external()',
                   'onchange' => 'winstance.image.external()',
            )
            ,
            array( 'name' => 'src', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => Lang_Hash::get('Type Image URLs') )
            ,
            array( 'name' => 'resource', 
                   'type' => 'dropdown', 
                   'value' => '', 
                   'options' => array( '' => '- '.Lang_Hash::get('Please Select').' -' ) + $this->getExistingFiles(),
                   'caption' => Lang_Hash::get('Selected Image'),
                   'onchange' => 'winstance.image.selected( this )' )
            ,
            array( 'name' => 'link', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => Lang_Hash::get('Link') )
            ,
            array( 'name' => 'image-css', 'type' => 'css', 'value' => '', 
                    'caption' => Lang_Hash::get('Image CSS') )
            ,
            array( 'name' => 'css', 'type' => 'css', 'value' => '', 
                    'caption' => Lang_Hash::get('Additional CSS') )
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
        
        if ( $this->get( 'link' ) == '' ) {
            $strElem = $strWrapStart . '<img id="src-'.$this->get('wiid').'" '
                            .' rel="'.$this->get('wiid').'" '
                            .' style="'.$this->get('image-css').'" '
                            .' src="'.$this->get('src').'" alt="'.$this->get('alt').'" />'.$strWrapEnd;
        } if ( $this->get( 'link' ) != '' ) {
            $strElem = $strWrapStart . '<a href="'. $this->get( 'link' ) .'"><img id="src-'.$this->get('wiid').'" '
                            .' rel="'.$this->get('wiid').'" '
                            .' style="'.$this->get('image-css').'" '
                            .' src="'.$this->get('src').'" alt="'.$this->get('alt').'" /></a>'.$strWrapEnd;
        }
        
        if ( $bPreview ) return $this->getConstructorHtml( $strElem );
        return $strWrapStart.$strElem.$strWrapEnd;
    }
    
    public function renderBackend( App_View $view, Widget_Instance $objWidgetInstance )
    {
        $strOut = parent::renderBackend( $view, $objWidgetInstance );
        
        $sHide = '';
        if ( $objWidgetInstance->get('src') == '' ) $sHide = " style='display:none' ";
        // Sys_Debug::dump( $objWidgetInstance->getPropertiesArray() );
        
        $strOut .= "\n\n<div class='control-group  wi-preview'>"
                        . "<label class='control-label'>".Lang_Hash::get('Image Preview').":</label><div class='controls'>"
                        . "<img class='preview' src='".$objWidgetInstance->get('src')."' alt='' $sHide />"
                        . "</div></div>\n\n";
        return $strOut;
    }
}