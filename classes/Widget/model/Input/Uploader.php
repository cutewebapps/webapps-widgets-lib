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
    
    /**
     * Routine to render a widget
     * @param App_View $view
     * @param boolean $bPreview
     * @return string
     */
    public function render( App_View $view, $bPreview = false )
    {   
        $strOut = '';
        $objForm = $view->form;
        if ( $bPreview ) {
            $this->arrAttributes['disabled'] = 'disabled';
            $view->headLink()->append( $view->base() . '/static/trusted/css/fileuploader.css');
            $view->headScript()->alias('fileuploader')->append( $view->base() . '/static/trusted/js/fileuploader.js', 'jquery' );
        } else {
            if ( $this->get( 'id' ) == 'for_upload' )
                $strOut .= '<script type="text/Javascript" src="https://' . $objForm->getSlug() . '.trustedgateway.com/js/upload-files/"></script>';
            
            $strOut .= '<link type="text/css" rel="stylesheet" href="/static/trusted/css/fileuploader.css" />'
                    .'<script type="text/Javascript" src="/static/trusted/js/fileuploader.js"></script>';
        }
        
        $strOut .= '<div class="control-group '.$this->get('wiid').'">'
                    .'<label class="control-label">'.$this->get('label').'</label>'
                    .'<div class="controls '.$this->get('cssclass').'">'
                        . $this->getControlHtml()
                        .'<div id="file_uploader"></div>'
                    .'</div>'
                .'</div>';

        if ( !$bPreview ) {
            ob_start();
            ?>
            <script type="text/Javascript">
                var idInputFile = '<?php echo $this->get('id') ?>';
                $('#' + idInputFile ).val('');
                insetUpload = new qq.FileUploader({
                    multiple:   <?php  echo ( $this->get('multiple-file-upload') ) ? 'true' : 'false' ?>
                    , element:  $("#file_uploader").get(0)
                    , action:   app.base + '/json/<?php echo $this->_objForm->getSlug() ?>/transaction/resource-upload/'
                    , uploadButtonText: '<i class="icon-picture icon-white"></i><?php echo ( $this->get('upload-button-name') != '' ) ? $this->get('upload-button-name') : 'Upload File' ?>'
                    , debug:    true 
                    , minSizeLimit: 1
                    , sizeLimit: <?php echo (int)$this->get('file-size-limit') * 1024 * 1024 ?>
                    , allowedExtensions: [<?php echo "'" . str_replace(',', "','", $this->get('allowed-extensions')) . "'" ?>]
                    , onSubmit : function( id, val ) {
                        insetUpload.setParams({
                            'type_upload' : 'file_transaction'
                        });
                    }
                    , onComplete: function( id,fn,response ) {
                        if ( response.success ) {
                            if ( $('#' + idInputFile ).val() === '' ) {
                                $('#' + idInputFile).val( response.image );
                            } else {
                                $('#' + idInputFile ).val( $('#' + idInputFile ).val() + '*#*' + response.image );
                            } 
                        }
                    }
                    , onError: function(id, fileName, reason) {
                        $('.qq-upload-failed-text').html( '<br />' + reason );
                    }
                });
            </script>
            <?php
            $strOut .= ob_get_contents();
            ob_clean();
        } else {
            $strOut .= '<script type="text/Javascript">'
                        . 'insetUpload = new qq.FileUploader({'
                        . 'ultiple:   false'
                        . ', element:  $("#file_uploader").get(0)'
                        . ', action:   app.base + "/json/' . $this->_objForm->getSlug() . '/transaction/resource-upload/"'
                        . ', uploadButtonText: \'<i class="icon-picture icon-white"></i>Upload File\''
                        . '});'
                    .'</script>';
        }
        if ( $bPreview ) $strOut = $this->getConstructorHtml ( $strOut );
        
        
        return $strOut;
    }
    
    public function getSyncFields()
    {
        $strFieldName = $this->get('id');
        $strFieldName = preg_replace( "@^trans_trans_@", 'trans_', $strFieldName );
                
        $arrFields = array();     
        if ( !$this->get('id') ) {
            return array();
        }
        
        $strSqlDefinition = ' TEXT NOT NULL DEFAULT \'\' ';

        $arrFields[ $strFieldName ] = $strSqlDefinition;
        return $arrFields;
    }
    
    public function getHtmlContents( $objTransaction )
    {
        $strFieldName = 'trans_'.$this->get('id');
        $strFieldName = preg_replace( "@^trans_trans_@", 'trans_', $strFieldName );

        return htmlspecialchars( $objTransaction->$strFieldName );
    }
    
    /**
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        if ( $this->get('id') != '' && $this->get('sync') ) {
            $this->_arrProperties['id'] = 'trans_'.$this->get('id');
        }
        $strValue = $this->getDefaultValue();
        return '<input type="hidden" id="' . $this->get('id') . '" value="' . $strValue . '">';
    }
}