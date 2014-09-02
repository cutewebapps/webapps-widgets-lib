<?php

class Widget_Input_Uploader extends Widget_Tag
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
        return 'Uploader';
    }
    
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
            array( 'name' => 'label', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Label:' )
            ,
            array( 'name' => 'cssclass', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'CSS Class:' )
            ,
            array( 'name' => 'upload-button-name', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Upload Button Name:' )
            ,
            array( 'name' => 'allowed-extensions', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Allowed Extensions:' )
            ,
            array( 'name' => 'multiple-file-upload', 
                   'type' => 'dropdown', 
                   'width' => '80px',
                   'options' => array( 0 => 'No', 1 => 'Yes'), 
                   'caption' => 'Upload Multiple Files:' )
            ,
            array( 'name' => 'file-size-limit', 
                   'type' => 'dropdown', 
                   'width' => '80px',
                   'options' => array( 1 => '1MB', 5 => '5MB', 10 => '10MB', 15 => '15MB', 20 => '20MB'),
                   'caption' => 'File max-size limit:' )
            ,
            array( 'name' => 'css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Additional CSS:' )
        );
    }  
    public function getLabel()
    {
        return $this->get('label');
    }
    
    public function getFineUploaderJavascript()
    {
        ob_start();
        $strContents = ob_get_contents();
        ob_clean();
        return $strContents;
    }
    
    
    public function getUploaderPropreties()
    {
        $arrAttr = array();
        $arrAttr[] = '';
        return $arrAttr;
    }
    /**
     * Routine to render a widget
     * @param App_View $view
     * @param boolean $bPreview
     * @return string
     */
    public function render( App_View $view, $bPreview = false )
    {   
        $strOut = '';
        if ( $bPreview ) {
            $this->arrAttributes['disabled'] = 'disabled';
        } else {
            $view->HeadLink()->append( $view->base() . '/static/fineuploader/css/fineuploader-3.4.1.css');
            $view->FooterScript()->alias('fileuploader')->append( $view->base() . '/static/fineuploader/js/jquery.fineuploader-3.4.1.min.js', 'jquery' );
        }
        
        $strOut .= '<div class="form-group '.$this->get('wiid').'">'
                    .'<label class="control-label">'.$this->get('label').'</label>'
                    .'<div class="controls '.$this->get('cssclass').'">'
                        . $this->getControlHtml()
                        .'<div id="file_uploader"></div>'
                    .'</div>'
                .'</div>';

        if ( !$bPreview ) {
            
        }
        
        if ( $bPreview ) { 
            $strOut = $this->getConstructorHtml ( $strOut );
        }
        return $strOut;
    }
    

    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        $strValue = $this->getDefaultValue();
        return '<input type="hidden" id="' . $this->get('id') . '" value="' . $strValue . '">';
    }
}