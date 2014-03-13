<?php

class Widget_Input_Dropdown extends Widget_Input_Text
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
        return 'Dropdown';
    }
    
    public function getOptions()
    {
        return array(
            
            array( 'name' => 'id', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'ID:' )
            ,
            array( 'name' => 'name', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Name:' )
            ,
            array( 'name' => 'label', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Label:' )
            ,
            array( 'name' => 'readonly', 
                   'type' => 'dropdown', 
                   'width' => '80px',
                   'options' => array( 0 => 'No', 1 => 'Yes'), 
                   'caption' => 'Readonly:' )
            ,
            array( 'name' => 'options', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Options:' )
            ,
            array( 'name' => 'value', 
                   'type' => 'text', 
                   'value' => '', 
                   'caption' => 'Default Value:' )
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
     * for overloading in children
     * @return string
     */
    public function getControlHtml()
    {
        // $this->arrAttributes['type'] = 'text';
        
        $view = new App_View();
        $strDefault = $this->getDefaultValue();
        $arrValues = array();
        
        if ( $this->get( 'options' ))  {
            if ( is_array( $this->get( 'options' ) ) )  {
                $arrValues = $this->get( 'options' );
            } else if ( $this->get( 'options' ) != '' ) {
                $arrFirstValues = explode(';', $this->get( 'options' ));
                //Sys_Debug::alert( $arrFirstValues );
                if (count($arrFirstValues) )
                    foreach ( $arrFirstValues as $strValue ) {
                        $array = explode(':', $strValue );
                        $arrValues[ trim( $array['0'] ) ] = trim( $array['1'] );
                    }
            }
        } else if ( $this->get( 'source' ))  {
            // TODO: get data source
        }
        $strContents = $view->SelectOptions( $arrValues, $strDefault );
        return $this->getTagHtml('select', ' '. $strContents );
    }
   
}