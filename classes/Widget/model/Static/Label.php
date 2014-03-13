<?php

class Widget_Static_Label extends Widget_Tag
{
    /**
     * @return string
     */
    public function getCategory()
    {
        return 'Label';
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'Label';
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
            array( 'name' => 'css', 'type' => 'css', 'value' => '', 
                    'caption' => 'Additional CSS:' )
        );
    }
    
    /**
     * Routine to render a widget
     * @param App_View $view
     * @param boolean $bPreview
     * @return string
     */
    public function render( App_View $view, $bPreview = false )
    {    
        if ( $bPreview )
            $this->arrAttributes['disabled'] = 'disabled';
        // die( 'preview: '.$bPreview );
        
        $sId = preg_match( '@^\d@', $this->get('id')) ? '' : $this->get('id');
        $strOut = '<div class="control-group '.$sId.'">'
                    .'<label class="control-label">'.$this->get('label').'</label>'
                    .'<div class="controls">'
                    .'</div>'
                .'</div>';
        if ( $bPreview ) $strOut = $this->getConstructorHtml ( $strOut );
        return $strOut;
    }
    
    /**
     * Get label for displaying in receipt
     * @return string
     */
    public function getLabel()
    {
        return $this->get('label');
    }
    
 /**
     * Get html contents for displaying in receipt
     * @return string
     */
}