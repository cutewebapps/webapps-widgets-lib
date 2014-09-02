<?php

/**
  * table for storage of ...
  * related classes: Widget_TypeCtrl
  */
class Widget_Type_Table extends DBx_Table
{
/**
 * database table name
 * @var string
 */
    protected $_name='widget_type';
/**
 * database table primary key
 * @var string
 */
    protected $_primary='wdg_id';
    
    /**
     * @param string $strClass
     * @return Widget_Type
     */
    public function fetchByConfigClass( $strClass )
    {
        $select = $this->select()->where( 'wdg_class = ?', $strClass )->limit(1);
        return $this->fetchRow( $select );
    }
    /**
     * @return array
     */
    public function getAsTree()
    {
        $arrResult = array();
        foreach ( $this->fetchAll() as $objWidget ) {
            $strCategory = $objWidget->getCategory();

            if ( !isset( $arrResult[ $strCategory ] ) )
                $arrResult[ $strCategory ] = array();
            
            $arrResult[ $strCategory ][] = $objWidget;
        }
        return $arrResult;
    }
    
    /**
     * Scan classes and add the records about them into the database
     * @return void
     */
    public function addFromConfig()
    {
        $configWidgetCategories  = App_Application::getInstance()->getConfig()->widgets;
        if  ( !is_object( $configWidgetCategories )) {
            throw new App_Exception( 'Widgets are not configured' );
        }
        
        if ( is_object( $configWidgetCategories->types )) {
            foreach ( $configWidgetCategories->types as $strCategory => $arrWidgets ) {
                foreach ( $arrWidgets as $strClass ) {
                    // now we have a class that should be have a record in the widgets collection
                    $objClass = new $strClass();
                    $objWidget = $this->fetchByConfigClass( $strClass );
                    if ( !is_object( $objWidget )) {
                        Sys_Io::out( 'creating '.$strClass );
                        $objWidget = $this->createRow();
                    
                        $objWidget->wdg_class    = $strClass;
                        $objWidget->wdg_category = $objClass->getCategory();
                        $objWidget->wdg_owner    = $objClass->getOwner();
                        $objWidget->wdg_context  = $objClass->getContext();
                        $objWidget->wdg_name     = $objClass->getName();
                        $objWidget->wdg_dt_created = date( 'Y-m-d H:i:s');
                        $objWidget->save( false );
                    }

                }
            }
        }
    }
}
/**
 * class of the rowset
 */
class Widget_Type_List extends DBx_Table_Rowset
{
}

/**
 * class for extending form filtration
 */
class Widget_Type_Form_Filter extends App_Form_Filter
{
    /**
     * specify elements that could be filtered with standard controller
     * @return void
     */
    public function createElements()
    {
        $this->allowFiltering( array( ) );
    }
}

/**
 * class for extending editing procedures
 */
class Widget_Type_Form_Edit extends App_Form_Edit
{
    /**
     * specify elements that could be edited with standard controller
     * @return void
     */
    public function createElements()
    {
        $this->allowEditing(array(  ) );
    }
}

/**
 * class of the database row
 */
class Widget_Type extends DBx_Table_Row
{
    /** 
      * Get class name - for php 5.2 compatibility
      * @return string 
      */
    public static function getClassName() { return 'Widget_Type'; }
    /** 
      * Get table class object 
      * @return string 
      */
    public static function TableClass() { return self::getClassName().'_Table'; }
    /** 
      *  Get table class instance
      *  @return Widget_Type_Table 
      */
    public static function Table() { $strClass = self::TableClass();  return new $strClass; }
    /** 
      * get table name 
      * @return string 
      */
    public static function TableName() { return self::Table()->getTableName(); }
    /** 
      * get class name for the specified form ("Filter" or "Edit")
      * @return string 
      */
    public static function FormClass( $name ) { return self::getClassName().'_Form_'.$name; }
    /** 
      * get class instance for the specified form ("Filter" or "Edit")
      *  @return mixed 
      */
    public static function Form( $name ) { $strClass = self::getClassName().'_Form_'.$name; return new $strClass; }
    
    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->wgt_category;
    }
    
    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->wdg_owner;
    }
    
    
    public function getCategory()
    {
        return $this->wdg_category;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->wdg_name;
    }

    // which context widget can be installed to
    public function getContext()
    {
        return '';
    }
    /**
     * 
     * @return mixed
     */
    public function getConfiguration( $arrProperties = array() )
    {
        // getConfiguration
        $strClass = $this->wdg_class;
        if ( $strClass == '' ) return null;
        if ( !class_exists( $strClass ) ) return null;
        
        return new $strClass( $arrProperties );
    }

}

/**
 
            wdg_id           INT(11)         NOT NULL AUTO_INCREMENT,            
            wdg_category     VARCHAR(255)    NOT NULL DEFAULT \'\',   
            wdg_name         VARCHAR(255)    NOT NULL DEFAULT \'\',
            wdg_class        VARCHAR(255)    NOT NULL DEFAULT \'\',
            wdg_owner        INT(11)         NOT NULL DEFAULT 0,
            wdg_dt_created   DATETIME        NOT NULL DEFAULT \'0000-00-00 00:00:00\
 */