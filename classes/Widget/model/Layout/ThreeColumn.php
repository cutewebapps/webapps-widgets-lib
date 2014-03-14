<?php

class Widget_Layout_ThreeColumn extends Widget_Abstract
{
    public function getCategory()
    {
        return 'Layout';
    }
    
    public function getName()
    {
        return '3-Columns';
    }
    
    public function getContext()
    {
        return '';
    }    
    
    public function getPlaceholders()
    {
        return array(
            'column-left',
            'column-central',
            'column-right',
        );
    }
    
  public function getOptions()
    {
        return array(
            array( 'name' => 'column-left-width', 'type' => 'text', 'value' => '33%', 
                    'caption' => 'Left Column Width' )
            ,
            array( 'name' => 'column-left-css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Left Column CSS' )
            ,
            array( 'name' => 'column-central-width', 'type' => 'text', 'value' => '33%', 
                    'caption' => 'Central Column Width' )
            ,
            array( 'name' => 'column-central-css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Central Column CSS' )
            ,
            array( 'name' => 'column-right-width', 'type' => 'text', 'value' => '33%',
                    'caption' => 'Right Column Width' )
            ,
            array( 'name' => 'column-right-css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Right Column CSS' )
            ,
            array( 'name' => 'outer-css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Outer CSS' )
        );
    }
    
    
    public function render( App_View $view, $bPreview = false )
    {
        //$objForm = $this->getPaymentForm();
        $strEx = '';
        
        $strCssL = $this->get('column-left-css','');
        $strCssC = $this->get('column-central-css','');
        $strCssR = $this->get('column-right-css','');
        
        $strWidthL = 'width:50%';
        $strWidthC = 'width:50%';
        $strWidthR = 'width:50%';
        
        if ( $this->get( 'column-left-width' ) )
            $strWidthL = 'width: '.$this->get( 'column-left-width' ).';';
        if ( $this->get( 'column-central-width' ) )
            $strWidthC = 'width: '.$this->get( 'column-central-width' ).';';
        if ( $this->get( 'column-right-width' ) )
            $strWidthR = 'width: '.$this->get( 'column-right-width' ).';';
        
        $strOut =
             '<div class="form-horizontal" style="float:left;'.$strWidthL.$strCssL.'">'
                .'<div style="'.$strEx.'">'
                    . $this->getChild( 'column-left', $bPreview )
                . '</div>'
            .'</div>'
            .'<div class="form-horizontal" style="float:left;'.$strWidthC.$strCssC.'">'
                .'<div style="'.$strEx.'">'
                    . $this->getChild( 'column-central', $bPreview )
                .'</div>'
            . '</div>'
            .'<div class="form-horizontal" style="float:left;'.$strWidthR.$strCssR.'">'
                .'<div style="'.$strEx.'">'
                    . $this->getChild( 'column-right', $bPreview )
                .'</div>'
            . '</div>'
            . '<div class="clearfix" style="clear:both"></div>';
        
        if ( $this->get('outer-css','') ) {
            $strOut = '<div style="'.$this->get('outer-css').'">'.$strOut.'</div>';
        }
        $strOut = '<div class="element-layout-wrapper">'.$strOut.'</div>';
        
        return $strOut;
    }    
}