<?php

class Widget_Input_Date extends Widget_Input_Text
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
        return 'Date';
    }
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        $this->arrAttributes['data-date-format'] = 'dd/mm/yyyy';
            
        
        //$this->arrAttributes['data-date'] = $this->get('value')!=='' ? $this->get('value') : date("d/m/Y") ;
        
        $this->arrClasses['datepicker'] = 'datepicker';
        $this->arrStyles[ 'width' ] = '90px';
        
        if ( ! $this->isBootstrap2() ) {
            return '<div class="input-append date" >'
                    . parent::getControlHtml()
                    . '<span class="add-on"><i class="icon-calendar"></i></span>'
                . '</div>';
        } else {
            return '<div class="input-group date" >'
                    . parent::getControlHtml()
                    . '<span class="input-group addon"><i class="fa fa-calendar"></i></span>'
                . '</div>';
        }
        
    }
    
    public function render( App_View $view, $bPreview = false )
    {
        $view->HeadScript()->append( $view->base(). '/static/bs2/js/bootstrap-datepicker.js', 'jquery' );
        $view->HeadLink()->append( $view->base(). '/static/bs2/css/datepicker.css' );

        return parent::render( $view, $bPreview );
    }
}