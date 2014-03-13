<?php

/**
  * table for storage of ...
  * related classes: Widget_ContextCtrl
  */
class Widget_Context_Table extends DBx_Table
{
/**
 * database table name
 * @var string
 */
    protected $_name='widget_context';
/**
 * database table primary key
 * @var string
 */
    protected $_primary='context_id';
    
    /**
     * @param string $strName
     * @return Widget_Context
     */
    public function fetchByName( $strName )
    {
        $select = $this->select()->where( 'context_name = ?', $strName );
        return $this->fetchRow( $select );
    }    
    
     /**
     * Scan classes and add the records about them into the database
     * @return void
     */
    public function addFromConfig()
    {
        $configWidgetCategories  = App_Application::getInstance()->getConfig()->widgets;
        if  ( !is_object( $configWidgetCategories ))
            throw new App_Exception( 'Widgets are not configured' );
        
        foreach ( $configWidgetCategories->contexts as $strContext ) {
            $objContext = $this->fetchByName( $strContext );
            if ( !is_object( $objContext )) {
                Sys_Io::out( "Creating context for widgets ".$strContext );
                $objContext = $this->createRow();
                $objContext->context_name = $strContext;
                $objContext->save( false );
            }
        }
    }
}
/**
 * class of the rowset
 */
class Widget_Context_List extends DBx_Table_Rowset
{
}

/**
 * class for extending form filtration
 */
class Widget_Context_Form_Filter extends App_Form_Filter
{
    /**
     * specify elements that could be filtered with standard controller
     * @return void
     */
    public function createElements()
    {
        $this->allowFiltering( array( 'context_name'  ) );
    }
}

/**
 * class for extending editing procedures
 */
class Widget_Context_Form_Edit extends App_Form_Edit
{
    /**
     * specify elements that could be edited with standard controller
     * @return void
     */
    public function createElements()
    {
        $this->allowEditing(array( 'context_name' ) );
    }
}

/**
 * class of the database row
 */
class Widget_Context extends DBx_Table_Row
{
    /** 
      * Get class name - for php 5.2 compatibility
      * @return string 
      */
    public static function getClassName() { return 'Widget_Context'; }
    /** 
      * Get table class object 
      * @return string 
      */
    public static function TableClass() { return self::getClassName().'_Table'; }
    /** 
      *  Get table class instance
      *  @return Widget_Context_Table 
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
    public function getName() 
    {
        return $this->context_name;
    }
    /**
     * 
     * @param App_View $view
     * @param integer $nRootId
     * @param boolean $bPreview
     * @return string
     */
    protected function _renderWidgets( App_View $view = null, $nRootId = 0, $bPreview = false )
    {
        // get list of widget instance for this context and render it.
        $lstWidgets = Widget_Instance::Table()->findByParentId( $this->getName(), $nRootId );
        $strOut = '';
        
        /* @var $objWidgetInstance Gateway_PaymentForm_Widget_Instance */
        //Sys_Debug::dump( $strOut );
        foreach ( $lstWidgets as $objWidgetInstance ) {
            $strOut .= $objWidgetInstance->render( $view, $bPreview );
        }
        
        if ( $bPreview && $strOut != '' ) 
            $strOut = '<div class="child-sortable">'
                    . $strOut
                    . '</div><div style="clearfix"></div>';
        return $strOut;
    }
    
    /**
     * 
     * @param boolean $bPreview
     * @return string
     */
    public function render( App_View $view, $bPreview = false )
    {
        $view->context = $this->getName();
        return $this->_renderWidgets( $view, 0, $bPreview );
    }
    /**
     * 
     * @return Widget_Type_List
     */
    public function getAvailableLayouts()
    {
        $select = Widget_Type::Table()->select()
                ->where( 'wdg_category = ?', 'Layout' )
                ->order( 'wdg_name' );
        
        $lstWidgetTypes = Widget_Type::Table()->fetchAll( $select );
        return $lstWidgetTypes;
    }
}
