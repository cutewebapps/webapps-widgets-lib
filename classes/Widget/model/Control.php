<?php

class Widget_Control
{
    protected $arrParams = array();
    
    public function __construct( array $arrParams ) 
    {
        $this->arrParams = $arrParams;    
    }
    public function getName()
    {
        return $this->arrParams['name'];
    }
    public function getType()
    {
        return $this->arrParams['type'];
    }
    
    public function getCaption()
    {
        return $this->arrParams['caption'];
    }
    
    public function getDefault()
    {
        return isset( $this->arrParams['value'] ) ? $this->arrParams['value'] : '';
    }    
    
    public function render( App_View $view, Widget_Instance $objWidgetInstance )
    {
        $arrDefaults = json_decode( $objWidgetInstance->wi_properties, true);
        
        switch ( $this->getType() )
        {
            case 'dropdown':
                //Sys_Debug::alert( $this->arrParams['options'] );
                $ctrl = new Widget_Input_Dropdown( array(
                    'id' => 'wi-'. $this->getName(),
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ? 
                                    $arrDefaults[ $this->getName() ] : $this->getDefault(),
                    'options' => $this->arrParams['options'],
                ) + $this->arrParams );
                return $ctrl->render( $view, false )."\n";
                
            
            case 'checkbox':
                // echo print_r( $arrDefaults, true  );
                // echo print_r( $this->arrParams, true  );
                $arrP = $this->arrParams;
                unset( $arrP['value'] );
                
                $ctrl = new Widget_Input_Checkbox( array(
                    'id' => 'wi-'. $this->getName(),
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ? 
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $arrP );
                return $ctrl->render( $view, false )."\n";
                
            case 'multicheck':
                // echo print_r( $arrDefaults, true  );
                // echo print_r( $this->arrParams, true  );
                $arrP = $this->arrParams;
                unset( $arrP['value'] );
                
                $ctrl = new Widget_Input_MultiCheck( array(
                    'id' => 'wi-'. $this->getName(),
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ? 
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $arrP );
                return $ctrl->render( $view, false )."\n";
                
            
            case 'image':
                $ctrl = new Widget_Static_Image( array(
                    'id' => 'wi-'. $this->getName(),
                    'label' => $this->getCaption(),
                    'width' => '100%',
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
            
            case 'text':
                //Sys_Debug::dump ( $this->arrParams );
                // Sys_Debug::dump ( $arrDefaults[ $this->getName() ]);
                $ctrl = new Widget_Input_Text( array(
                    'id' => 'wi-'. $this->getName(),
                    'label' => $this->getCaption(),
                    'width' => '100%',
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
            case 'label':
                //Sys_Debug::dump ( $this->arrParams );
                // Sys_Debug::dump ( $arrDefaults[ $this->getName() ]);
                $ctrl = new Widget_Static_Label(  array(
                    'id' => 'wi-'. $this->getName(),
                    'label' => $this->getCaption()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
            case 'textarea':
                //Sys_Debug::dump ( $this->arrParams );
                // Sys_Debug::dump ( $arrDefaults[ $this->getName() ]);
                $ctrl = new Widget_Input_Text( array(
                    'id' => 'wi-'. $this->getName(),
                    'rows' => 4,
                    'width' => '100%',
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
            case 'css':   
                $ctrl = new Widget_Input_Ace( array(
                    'highlight' => 'css',
                    'id' => 'wi-'. $this->getName(),
                    'rows' => 4,
                    'width' => '100%',
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
            case 'html':   
                $ctrl = new Widget_Input_Ace( array(
                    'highlight' => 'html',
                    'id' => 'wi-'. $this->getName(),
                    'rows' => 4,
                    'width' => '100%',
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
                
            case 'upload':
                $ctrl = new Widget_Input_Uploader( array(
                    'id' => 'wi-'. $this->getName(),
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
                //return '<!-- LOCAL UPLOADER and GALLERY REFERENCE - SHOULD GO HERE -->';
            case 'money':
                $ctrl = new Widget_Input_Money(  array(
                    'id' => 'wi-' . $this->getName(),
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
            case 'datetime':
                $ctrl = new Widget_Input_DateTime(  array(
                    'id' => 'wi-' . $this->getName(),
                    'label' => $this->getCaption(),
                    'value' => isset( $arrDefaults[ $this->getName() ] ) ?
                                    $arrDefaults[ $this->getName() ] : $this->getDefault()
                ) + $this->arrParams );
                return  $ctrl->render( $view, false )."\n";
            default:
                return print_r( $this->arrParams, true );
                
        }
    }
}