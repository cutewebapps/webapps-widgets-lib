<?php

abstract class Widget_Tag extends Widget_Abstract
{

    public $arrClasses = array();
    public $arrStyles = array();
    public $arrAttributes  = array();
    
    
    /**
     * for overloading in children
     * @return string
     */
    public function getTagHtml( $strTag, $strContents  = '', $strExStyle = '')
    {
        $arrValidStyles = array(
            'width', 'text-align', 'color', 'maxlength', 'size', 'font-size'
        );
        $arrStyles = array();
        foreach ( $this->arrStyles as $strStyle => $strValue ) {
            $arrStyles[ $strStyle ] = $strStyle.':'.$strValue.'';
        }
            
        foreach ( $arrValidStyles as $strStyle )
             if ( $this->get( $strStyle ) ) {
                $strVal = $this->get( $strStyle );
                if ( in_array( $strStyle, array( 'font-size', 'width' ) )  ) {
                    if ( ! strstr( $strVal, 'px' ) && !strstr( $strVal, 'pt' ) && !strstr( $strVal, '%' ) && 
                         ! strstr( $strVal, 'em' ) && !strstr( $strVal, 'cm' ) ) {
                        $strVal .= 'px';
                    }
                } 
                $arrStyles[ $strStyle ] = $strStyle.':'.$strVal.'';
             }
            
        $this->arrAttributes['style'] = implode( ";", $arrStyles );
        if ( trim( $strExStyle ) != "" )
            $this->arrAttributes['style'] .= ';'.trim($strExStyle);
                

        if ( $this->get('cssclass') != '' ) {
            $this->arrClasses[ $this->get('cssclass') ] = $this->get('cssclass');
        }        
        
        if ( count( $this->arrClasses ))
            $this->arrAttributes['class'] = implode( " ", $this->arrClasses );
        
        $arrValidAttributes = array(
            'type', 'onkeyup', 'onkeydown', 'onchange', 'onfocus',  'checked', 
            'onblur', 'value', 'readonly', 'disabled', 'maxlength', 'name', 'placeholder'
        );
        foreach ( $arrValidAttributes as $strAttr )
            if ( $this->get( $strAttr ) )
                $this->arrAttributes[ $strAttr ] = $this->get( $strAttr );
        
        $strAttr = '';
        foreach ( $this->arrAttributes as $strKey => $strValue ) {
            if ( $strValue == 'money' )
                $strValue = 'text';
            $strAttr .= ' '.$strKey.'="'.$strValue.'" ';
        }

        $sId = preg_match( '@^\d+$@', $this->get('id')) ? '' : 'id="'.$this->get('id').'"';
        if ( $strContents == '' )
            return '<'.$strTag.' '.$sId.' '.$strAttr.' />';
        else 
            return '<'.$strTag.' '.$sId.' '.$strAttr.'>'.$strContents.'</'.$strTag.'>';
    }
}
    