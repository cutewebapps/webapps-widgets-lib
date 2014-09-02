<?php

abstract class Widget_Abstract
{
    
    public abstract function getCategory();
    
    public abstract function getName();
    
    /**
     * By default, widget can be installed on a payment form
     * @return string
     */
    public function getContext()
    {
        return 'payment';
    }
    
    public function isInstalledOnce()
    {
        return false;
    }

    /**
     * @return boolean
     */
    public function isGlobal()
    {
        return false;
    }
    /**
     * @return int
     */
    public function getOwner()
    {
        return 0;
    }
    /**
     * @return boolean
     */
    public function isDisabled()
    {
        return false;
    }
    
    /**
     * @var array
     */
    protected $_arrProperties = array();
    /**
     * @var array
     */
    protected $_arrChildren   = array();
    /**
     * @var string
     */
    protected $_strId = '';
    
    /**
     * 
     * @return string
     */
    public function getCtrlId()
    {
        return $this->_strId;
    }
    
    public function __construct( array $arrProperties = array() )
    {
        $this->_arrProperties = $arrProperties;
        
        $nId = isset( $arrProperties['id'] ) ? $arrProperties['id'] 
                : (isset( $arrProperties['wiid'] ) ? $arrProperties['wiid'] : substr(sha1(mt_rand(0,10000)), 0, 8));
        $this->_strId = 'ctrl-'.$nId;
    }
    
    /**
     * @param string $strKey
     * @return string
     */
    public function addChild( $strKey, $strContents )
    {
        if ( !isset( $this->_arrChildren[ $strKey ] ) )
            $this->_arrChildren[ $strKey ] = array();
        $this->_arrChildren[ $strKey ][] = $strContents; 
        return $this;
    }
    /**
     * @param string $strKey
     * @return string
     */
    public function getChild( $strKey, $bPreview = false )
    {
        $strInside = implode( "\n", $this->_arrChildren[ $strKey ] );
        if ( $bPreview ) {
            return '<div class="element-layout" data-id="'.$this->get('wiid').'" data-placeholder="'.$strKey.'" '
                .'onclick="winstance.edit(this,'.$this->get('wiid').'); return false;">'
                . $strInside . '</div>';
        } else {
            if ( $strInside == "" ) $strInside = ' &nbsp; ';
        }
        return $strInside;
    }
    /**
     * For overloading
     * @return array
     */
    public function getPlaceholders()
    {
        return array();
    }
    
    /**
     * For overloading: types of widget instances 
     * 
     * @return array
     */
    public function getOptions()
    {
        return array();
    }
    
    /**
     * @return array
     */
    public function getStorageOptions()
    {
        return $this->getOptions();
    }

    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get( $key, $default = '' )
    {
        return ( isset( $this->_arrProperties[ $key ] ) && $this->_arrProperties[ $key ] != '' ) ? $this->_arrProperties[ $key ] : $default;
    }
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return Widget_Abstract
     */
    public function set( $key, $value = null )
    {
        if ( is_array( $key ) ) {
            foreach ( $key as $k => $v ) {
                $this->_arrProperties[ $k ]  = $v;
            }
        } else {
            $this->_arrProperties[ $key ]  = $value;
        }
        return $this;
    }
    
    /*
     * for overloading, no rendering by default
     * @param App_View $view
     * @param boolean $bPreview
     * @return string
     */
    public function render( App_View $view, $bPreview = false )
    {
        return '';
    }
    
    /*
     * for overloading, no rendering by default
     * @param App_View $view
     * @return string
     */
    public function renderBackend( App_View $view, 
           Widget_Instance $objWidgetInstance )
    {
        $strOut = '';
        $arrOptions  = $this->getOptions();
        if ( !is_array( $arrOptions )) { $arrOptions = array() ; } 
        foreach( $this->getOptions() as $arrOption ) {
            $objCtrl = new Widget_Control( $arrOption );
            $strOut .= $objCtrl->render( $view, $objWidgetInstance );
        }
        return $strOut;
    }
    
    /**
     * 
     * @param string $strText
     * @return string
     */
    public function getConstructorHtml( $strText )
    {
        if ( $this->get('wiid') ) {
            return '<div class="element-constructor" rel="'.$this->get('wiid').'" '
                .'onclick="winstance.edit(this,'.$this->get('wiid').'); return false;">'
                . $strText . '</div>';
        }
        return '<div class="element-constructor">' . $strText . '</div>';
    }
    
    /**
     * 
     * @return boolean
     */
    public function isBootstrap2()
    {
        $configWidgetCategories  = App_Application::getInstance()->getConfig()->widgets;
        $bRes = false;
        if  ( is_object( $configWidgetCategories )) {
            $bRes = ($configWidgetCategories->style == 'bootstrap2' );
        }
        return $bRes;
    }
    /**
     * 
     * @return string
     */
    public function getHorizontalForm()
    {
        return  $this->isBootstrap2() ? 'form-horizontal' : 'form-fluid';
    }
    /**
     * 
     * @return boolean
     */
    public function hasLocalUpload()
    {
        $configWidgetCategories  = App_Application::getInstance()->getConfig()->widgets;
        $bRes = false;
        if  ( is_object( $configWidgetCategories )) {
            $bRes = $configWidgetCategories->local_upload;
        }
        return $bRes;
    }
}