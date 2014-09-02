<?php

class Widget_Input_MultiCheck extends Widget_Input_Text
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
        return 'MultiCheck';
    }
    
/**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        // $this->arrAttributes['type'] = 'text';
        
        $view = new App_View();
        $strDefault = $this->get( 'value' );
        $arrValues = array();
        if ( $this->get( 'options' ))  {
        if ( is_array( $this->get( 'options' ) ) ) { $arrValues = $this->get( 'options' ); }
        } else if ( $this->get( 'source' ))  {
            // TODO: get data source
        }
        
        $arrDefault = explode( ',', $strDefault );
        $arrHtml = array();
        foreach ( $arrValues as $key => $value ) {
            $strChecked = '';
            if ( in_array( $key, $arrDefault ) ) { $strChecked = 'checked="checked" '; }
            
            $arrHtml []=  '<div style="line-height:20px">'
                        .'<input class="multicheck" name="'.$this->get('id')
                            .'" style="margin-top:0px" type="checkbox" '.$strChecked.' value="'.$key.'" /> '. $value
                        .'</div>';
        }
        return '<div style="overflow:auto;width:'.$this->get('width','220px').';max-height:'.$this->get('height','100px').'">'
                    .implode( "\n", $arrHtml )
              .'</div>'
              .'<input type="hidden" id="'.$this->get('id').'" value="'.$strDefault.'" />'
              .'<script type="text/JavaScript">'."\n"
                .' $(document).ready( backend.multicheck.init );'."\n"
              .'</script>'."\n";
    }  
}
    