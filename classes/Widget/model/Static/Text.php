<?php

class Widget_Static_Text extends Widget_Tag
{
    public function getCategory()
    {
        return 'Static';
    }
    
    public function getName()
    {
        return 'Text';
    }
    
    public function getContext()
    {
        return '';
    }    
    
    /**
     * 
     * @return array
     */
    public function getTemplates()
    {
        $arrTemplates = array();
        if ( is_object( App_Application::getInstance()->getConfig()->widgets->contexts ) ) {
            $lstContexts = App_Application::getInstance()->getConfig()->widgets->contexts;
            foreach ( $lstContexts as $strNameContext => $strSlugContext ) {
                if ( $strSlugContext == 'header' || $strSlugContext == 'sidebar-right' || $strSlugContext == 'footer' ) {
                    $arrTemplates[ $strSlugContext ] = $strNameContext;
                }
            }
        }
        
        return $arrTemplates;
    }
    
    /**
     * 
     * @param string $strName
     * @return string
     */
    public function getTemplateContent( $strName, $bPreview )
    {
        $strContent = '';
        $view = new App_View();
        $objContext = Widget_Context::Table()->fetchByName( $strName );
        if (is_object($objContext) ) {
            $strContent = $objContext->render( $view, $bPreview );
        }
        return $strContent;
    }
    /**
     * 
     * @return array
     */
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
                   'value' => '12', 
                   'width' => '80px',
                   'options' => array( '8' => '8', '9' => '9', '10' => '10', '11' => '11', 
                       '12' => '12', '13' => '13', '14' => '14', '16' => '16', '18' => '18', 
                       '20' => '20', '22' => '22', '24' => '24', '26' => '26', '28' => '28',  
                       '30' => '30', '32' => '32', '36' => '36', '40' => '40', '48' => '48', 
                       '64' => '64', '72' => '72' ),
                   'caption' => 'Font size' )
            ,
            array( 'name' => 'content', 
                   'type' => 'html', 
                   'value' => 'Static Text', 
                   'caption' => 'Content' )
            ,
            array( 'name' => 'tpl', 
                   'type' => 'dropdown', 
                   'value' => '', 
                   'options' => array( '' => 'No Template' ) + $this->getTemplates(),
                   'caption' => 'Template' )
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
        
        // if ( $this->get( 'text-align') )
        $this->arrStyles['text-align'] = $this->get( 'text-align', 'center');
        
        if ( $this->get( 'font-size') ) {
            $this->arrStyles['font-size'] = $this->get( 'font-size').'px';
            $this->arrStyles['line-height'] = ($this->get( 'font-size') + 4 ).'px';
        }
        
        $this->arrClasses[ $this->get( 'cssclass' ) ] = $this->get( 'cssclass' );
        
        $strContent = $this->get( 'content', '' );
        if ( $this->get('tpl') != '' ) {
            $strContent = $this->getTemplateContent( $this->get('tpl'), $bPreview );
        }
        
        $strOut = $this->getTagHtml( 'div', ' '.$strContent.'  ', $this->get('css') );
        if ( $bPreview ) {
            $strOut = $this->getConstructorHtml ( $strOut );
        }
        
        return $strOut;
    }
}