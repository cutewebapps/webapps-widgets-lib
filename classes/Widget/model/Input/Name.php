<?php

class Widget_Input_Name extends Widget_Input_Text
{
    public $tag1 = null;
    public $tag2 = null;
    
    
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
        return 'Name';
    }
    
    
    public function __construct(PaymentForm $objForm = null, array $arrProperties = array()) {
        parent::__construct($objForm, $arrProperties);
        
        $this->tag1 = new Widget_Input_Text();
        $this->tag2 = new Widget_Input_Text();

        $this->tag1->set( 'class', 'Widget_Input_Text' );
        $this->tag2->set( 'class', 'Widget_Input_Text' );
        
        $this->tag1->set( 'type', 'text' );
        $this->tag2->set( 'type', 'text' );
        
        $this->tag1->set( 'width', '40%' );
        $this->tag2->set( 'width', '40%' );
    }
    
    public function getOptions()
    {
        return array(
            
            array( 'name' => 'id', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'ID:' )
            ,
            array( 'name' => 'name', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Name:' )
            ,
            array( 'name' => 'sync', 
                   'type' => 'checkbox', 
                   'value' => '1', 
                   'caption' => 'Save in transaction:' )
            ,
            array( 'name' => 'label', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Label:' )
            ,
            array( 'name' => 'readonly', 
                   'type' => 'dropdown', 
                   'width' => '80px',
                   'options' => array( 0 => 'No', 1 => 'Yes'), 
                   'caption' => 'Readonly:' )
            ,
            array( 'name' => 'first_value', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Default First Name:' )
            ,
            array( 'name' => 'last_value', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Default Last Name:' )
            ,
            array( 'name' => 'cssclass', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'CSS Class:' )
            ,
            array( 'name' => 'css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Additional CSS:' )
        );
    }  
/**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        
        if ( $this->get('readonly')) {
            $this->tag1->set( 'readonly', 'readonly' );
            $this->tag2->set( 'readonly', 'readonly' );
        }
        
        if ( $this->get('disabled')) {
            $this->tag1->set( 'disabled', 'disabled' );
            $this->tag2->set( 'disabled', 'disabled' );
        }
        
        if ( $this->get('id') ) {
            
            $this->tag1->set( 'id', $this->get('id').'_first');
            $this->tag2->set( 'id', $this->get('id').'_last');
            
            if ( $this->get('id') != '' && $this->get('sync') ) {
                 $this->tag1->set( 'id', 'trans_'.$this->tag1->get('id') );
                 $this->tag2->set( 'id', 'trans_'.$this->tag2->get('id') );
            }

        }
        if ( $this->get('name') ) {
            
            $this->tag1->set( 'name', $this->get('name').'_first');
            $this->tag2->set( 'name', $this->get('name').'_last');
            
            if ( $this->get('name') != '' && $this->get('sync') ) {
                 $this->tag1->set( 'name', 'trans_'.$this->tag1->get('name') );
                 $this->tag2->set( 'name', 'trans_'.$this->tag2->get('name') );
            }

        }
        $this->tag1->set( 'value', $this->tag1->getDefaultValue() );
        $this->tag2->set( 'value', $this->tag2->getDefaultValue() );
        
        return  $this->tag1->getTagHtml('input', '', $this->get('css'))
             . ' '
             . $this->tag2->getTagHtml('input', '', $this->get('css'));
    }  
    
     /**
     * Routine to render a widget
     * @param App_View $view
     * @param boolean $bPreview
     * @return string
     */
    public function render( App_View $view, $bPreview = false )
    {    
        if ( $bPreview ) {
            $this->tag1->set( 'disabled', 'disabled' );
            $this->tag2->set( 'disabled', 'disabled' );
        }
        return parent::render( $view, $bPreview );
    }
    
    
  /**
     * Get html contents for displaying in receipt
     * @return string
     */
    public function getHtmlContents( $objTransaction )
    {
        $strFieldName = 'trans_'.$this->get('id');
        $strFieldName = preg_replace( "@^trans_trans_@", 'trans_', $strFieldName );

        $strFirst = $strFieldName.'_first';
        $strLast = $strFieldName.'_last';
        return htmlspecialchars( $objTransaction->$strFirst.' '.$objTransaction->$strLast );
    }
    
    
    /**
     * 
     * @return array string => string
     */
    public function getSyncFields()
    {
        $strHtml = $this->getControlHtml(); // do not use the result!
        
        $arrResults = array();
        foreach ( $this->tag1->getSyncFields() as $strField => $strSqlDef )
            $arrResults[ $strField ]  = $strSqlDef;
        foreach ( $this->tag2->getSyncFields() as $strField => $strSqlDef )
            $arrResults[ $strField ]  = $strSqlDef;
        
        // Sys_Debug::alert( $arrResults );
        return $arrResults;
    }
}
    