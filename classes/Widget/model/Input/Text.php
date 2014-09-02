<?php

class Widget_Input_Text extends Widget_Tag
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
        return 'Text';
    }
    /**
     * 
     * @return string
     */
    public function getDefaultValue()
    {
        $strValue  = $this->get('value', '');
        $strField  = $this->get('id');
        if ( isset( $_REQUEST[ $strField ] ) ) {
            $strValue = htmlspecialchars( $_REQUEST[ $strField ] );
        }
        return $strValue;
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
            array( 'name' => 'rows', 
                   'type' => 'text', 
                   'width' => '65px',
                   'value' => '1', 
                   'caption' => 'Rows' )
            ,
            array( 'name' => 'value', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Default Value:' )
            ,
            array( 'name' => 'placeholder', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Placeholder:' )
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
        if ( !isset( $this->arrAttributes['type'] ) )
            $this->arrAttributes['type'] = 'text';
        
        if ( $this->get( 'type' ) == 'css' || 
             $this->get( 'type' ) == 'html' ) {

            // ACE-editor modification
            //$this->arrAttributes['type'] = 'text';
        }

        $this->arrClasses['form-control'] = 'form-control';
       
        if ( $this->get('readonly'))
            $this->arrAttributes['readonly'] = 'readonly';
        
        if ( isset( $this->_arrProperties['rows'] ) && $this->_arrProperties['rows'] > 1 ) {
            
            $this->arrAttributes['rows'] = $this->_arrProperties['rows'];
            
            $strValue = $this->getDefaultValue();
            unset( $this->arrAttributes['value'] );
            unset( $this->_arrProperties['value'] );
            return $this->getTagHtml('textarea',  $strValue.' ', $this->get('css') );
        } else {
            
            $this->arrAttributes['value'] = $this->getDefaultValue();
            unset( $this->_arrProperties['value'] );
            
            return $this->getTagHtml('input', '', $this->get('css'));
        }
        
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
            $this->arrAttributes['disabled'] = 'disabled';
        }
        // die( 'preview: '.$bPreview );
        
        $sId = preg_match( '@^\d@', $this->get('id')) ? '' : $this->get('id');
        
        $strOut = '<div class=" '.( $this->isBootstrap2() ? 'control-group' : 'form-group' )." ".$sId.'">'
                    .'<label class="control-label">'.$this->get('label').'</label>'
                    .'<div class="controls">'
                        .$this->getControlHtml()
                    .'</div>'
                .'</div>';
        if ( $bPreview ) { $strOut = $this->getConstructorHtml ( $strOut ); }
        return $strOut;
    }
    
    /**
     * Get label for displaying in receipt
     * @return string
     */
    public function getLabel( $objTransaction )
    {
        return $this->get('label');
    }
    
 /**
     * Get html contents for displaying in receipt
     * @return string
     */
    public function getHtmlContents( $objTransaction )
    {
        $strFieldName = 'trans_'.$this->get('id');
        $strFieldName = preg_replace( "@^trans_trans_@", 'trans_', $strFieldName );

        return htmlspecialchars( $objTransaction->$strFieldName );
    }
    
    public function getSyncFields()
    {
        $strFieldName = 'trans_'.$this->get('id');
        $strFieldName = preg_replace( "@^trans_trans_@", 'trans_', $strFieldName );
        
        $strClass = isset( $this->_arrProperties['class'] ) ? $this->_arrProperties['class'] : ''; 
                
        $arrFields = array();
        if ( !$this->get('id') ) {
            return array();
        }
        
        $strSqlDefinition = ' VARCHAR(255) NOT NULL DEFAULT \'\' ';

        if ( $strClass == 'Widget_Input_Checkbox' ) {
            $strSqlDefinition = 'INT NOT NULL DEFAULT 0';
        } else if ( $strClass == 'Widget_Input_Number' ) {
            $strSqlDefinition = 'INT NOT NULL DEFAULT 0';
        } else if ( $strClass == 'Widget_Input_Money' ) {
            $strSqlDefinition = 'DECIMAL(10,2) NOT NULL DEFAULT 0';
        } else if ( $strClass == 'Widget_Input_DateTime' ) {
            $strSqlDefinition = 'DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\'';
        } else if ( $strClass == 'Widget_Input_Date' ) {
            $strSqlDefinition = 'DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\'';
        } else if ( $strClass == 'Widget_Input_Uploader' ) {
            $strSqlDefinition = 'VARCHAR(255) NOT NULL DEFAULT \'\'';
        }
        
        if ( isset(  $this->_arrProperties['rows'] ) &&  $this->_arrProperties['rows'] > 1 ) {
            $strSqlDefinition = 'MEDIUMTEXT';
        }

        $arrFields[ $strFieldName ] = $strSqlDefinition;
        return $arrFields;
    }
}