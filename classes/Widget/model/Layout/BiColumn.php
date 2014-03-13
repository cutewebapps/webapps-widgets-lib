<?php

class Widget_Layout_BiColumn extends Widget_Abstract
{
    public function getCategory()
    {
        return 'Layout';
    }
    
    public function getName()
    {
        return '2-Columns';
    }
    
    public function getContext()
    {
        return '';
    }    
    
    public function getPlaceholders()
    {
        return array(
            'column-left',
            'column-right',
        );
    }
    
  public function getOptions()
    {
        return array(
            array( 'name' => 'column-left-width', 'type' => 'text', 'value' => '50%', 
                    'caption' => 'Left Column Width' )
            ,
            array( 'name' => 'column-right-width', 'type' => 'text', 'value' => '50%',
                    'caption' => 'Right Column Width' )
        );
    }
    
    public function render( App_View $view, $bPreview = false )
    {
        //$objForm = $this->getPaymentForm();
        $strEx = '';
        $strWidthL = 'width:50%';
        $strWidthR = 'width:50%';
        
        if ( $this->get( 'column-left-width' ) )
            $strWidthL = 'width: '.$this->get( 'column-left-width' );
        if ( $this->get( 'column-right-width' ) )
            $strWidthR = 'width: '.$this->get( 'column-right-width' );
        
       // if ( $bPreview ) $strEx = 'border: 4px #bbc dashed;';
        
        $strOut =
             '<div class="form-horizontal" style="float:left;'.$strWidthL.'">'
                .'<div style="'.$strEx.'">'
                    . $this->getChild( 'column-left', $bPreview )
                . '</div>'
            .'</div>'
            .'<div class="form-horizontal" style="float:left;'.$strWidthR.'">'
                .'<div style="'.$strEx.'">'
                    . $this->getChild( 'column-right', $bPreview )
                .'</div>'
            . '</div>'
            . '<div class="clearfix" style="clear:both"></div>';
        $strOut = '<div class="element-layout-wrapper">'.$strOut.'</div>';
        
        return $strOut;
    }    
}