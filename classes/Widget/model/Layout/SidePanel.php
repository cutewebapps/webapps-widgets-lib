<?php

class Widget_Layout_SidePanel extends Widget_Abstract
{
    public function getCategory()
    {
        return 'Layout';
    }
    
    public function getName()
    {
        return 'Side Panel';
    }
    
    public function getContext()
    {
        return '';
    }    
    
    public function getPlaceholders()
    {
        return array(
            'left-panel',
            'above-content',
            'under-content',
            'right-panel',
        );
    }
    
    public function getOptions()
    {
        return array(
            array( 'name' => 'left-panel-width', 'type' => 'text', 'value' => '20%', 
                    'caption' => 'Side Panel Width' )
            ,
            array( 'name' => 'central-panel-width', 'type' => 'text', 'value' => '80%',
                    'caption' => 'Content Width' )
            ,
            array( 'name' => 'panel-css', 'type' => 'textarea', 'value' => '', 
                    'caption' => 'Panel CSS' )
            ,
            array( 'name' => 'content-css', 'type' => 'textarea', 'value' => '', 
                    'caption' => 'Content CSS' )
            ,
            array( 'name' => 'outer-css', 'type' => 'textarea', 'value' => '', 
                    'caption' => 'Outer CSS' )
            
        );
    }
    
    
    
    public function render( App_View $view, $bPreview = false )
    {
        //$objForm = $this->getPaymentForm();
        $strEx = '';
        $strTpgContent = '';
        if ( $bPreview ) {
            $strEx = 'margin:2px; min-height:10px;';
            $strTpgContent = '<div style="min-height:500px;margin-top:10px;margin-bottom:10px;background-color:#f0fff0"> </div>';
        }
        
        $strLeftWidth    = $this->get('left-panel-width', '20%' );
        // $strRightWidth   = $this->get('right-panel-width', '40%' );
        $strCentralWidth = $this->get('central-panel-width', '80%' );
        
        $strOut = '';
        
        if ( $strLeftWidth != '' )
            $strOut .=
                 '<div class="form-horizontal" style="float:left;width:'.$strLeftWidth.'">'
                    .'<div style="'.$strEx.';'.$this->get('panel-css').'min-height:20px;">'
                        . $this->getChild( 'left-panel', $bPreview )
                    . '</div>'
                .'</div>';
        
        $strOut .= ''
            .'<div class="form-horizontal" style="float:left;width:'.$strCentralWidth.'">'
                .'<div style="'.$strEx.'">'
                    . $this->getChild( 'above-content', $bPreview )
                .'</div>'
                .'<div class="tpg-content" style="'.$this->get('content-css').'">'.$strTpgContent.'</div>'
                .'<div style="'.$strEx.'">'
                    . $this->getChild( 'under-content', $bPreview )
                .'</div>'
            . '</div>';
        
//        if ( $strRightWidth != '' )
//            $strOut .= ''
//                .'<div class="form-horizontal" style="float:left;width:'.$strRightWidth.'">'
//                    .'<div style="'.$strEx.';min-height:20px;">'
//                        . $this->getChild( 'right-panel' )
//                    . '</div>'
//                .'</div>';
//        
        $strOut .= ''
            .'<div class="clearfix" style="clear:both"></div>';
        
        $strOut = '<div class="element-layout-wrapper" style="'.$this->get('outer-css').'">'.$strOut.'</div>';
        return $strOut;
    }    
}