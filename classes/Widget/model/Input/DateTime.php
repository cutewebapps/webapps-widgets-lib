<?php

class Widget_Input_DateTime extends Widget_Input_Text
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
        return 'Date/Time';
    }
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        $this->set( 'width', '130px' );
        $this->arrAttributes[ 'data-format' ] = "dd/MM/yyyy hh:mm:ss";
        
        
        return //'<div class="datetimepicker">'
                '<div class="input-append date">'
                    . parent::getControlHtml()
                    . '<span class="add-on">'
                        .'<i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i>'
                    . '</span>'
                .'</div>';
                //.'<div class="datetimepicker"></div>'
              //.'</div>';
    }
    
    public function render( App_View $view, $bPreview = false )
    {
        $strOut = '';
        if ( $bPreview ) {
            $this->arrAttributes['disabled'] = 'disabled';
            $view->HeadLink()->append( $view->base().'/static/bs2/css/bootstrap-datetimepicker.min.css' );
            $view->HeadScript()->append( $view->base(). '/static/bs2/js/bootstrap-datetimepicker.min.js', 'jquery' );
        } else {
            $strOut .= '<link type="text/css" rel="stylesheet" href="/static/bs2/css/bootstrap-datetimepicker.min.css">'
                    .'<script type="text/Javascript" src="/static/bs2/js/bootstrap-datetimepicker.min.js"></script>';
        }
        
        $strOut .= '<div class="'.( $this->isBootstrap2() ? 'control-group' : 'form-group' ).' '.$this->get('wiid').'">'
                    .'<label class="control-label">'.$this->get('label').'</label>'
                    .'<div style="position:relative" class="controls '.$this->get('cssclass').'">'
                        . $this->getControlHtml()
                    .'</div>'
                .'</div>';

        if ( !$bPreview ) {
            ob_start();
            ?>
            <script type="text/Javascript">
                $(document).ready( function() {
                    $('#<?php echo $this->get('id')?>').datetimepicker({
                        language: 'en'
                    }).on('changeDate', function(e) {
                        $(".bootstrap-datetimepicker-widget").attr('style', 'display:none');
                    });
                });
            </script>
            <?php
            $strOut .= ob_get_contents();
            ob_clean();
        }
        if ( $bPreview ) $strOut = $this->getConstructorHtml ( $strOut );
        
        
        return $strOut;
    }
        

}