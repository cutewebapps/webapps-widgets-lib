<?php

/**
  * table for storage of ...
  * related classes: Widget_InstanceCtrl
  */
class Widget_Instance_Table extends DBx_Table
{
/**
 * database table name
 * @var string
 */
    protected $_name='widget_instance';
/**
 * database table primary key
 * @var string
 */
    protected $_primary='wi_id';
    /**
     * 
     * @param int $strContext
     * @param int $nParentId
     * @return Widget_Instance_List
     */
    public function findByParentId( $strContext, $nParentId, $strPlaceholder = '' )
    {
        $select = Widget_Instance::Table()->select()
                ->setIntegrityCheck( false )
                ->from( $this )
                ->join( Widget_Type::TableName(), 'wi_widget_id = wdg_id ')
                ->where( 'wi_parent_id = ?', $nParentId )
                ->order( 'wi_sortorder');
        if ( $strPlaceholder != '')
            $select->where( 'wi_placeholder = ?', $strPlaceholder );
        if ( $strContext != '' )
            $select->where( 'wi_context = ?', $strContext );
        return $this->fetchAll( $select );
    }
    /**
     * 
     * @param int $strContext
     * @param int $nWidgetId
     * @return Widget_Instance 
     */
    public function fetchByWidgetId( $strContext, $nWidgetId )
    {
        $select = Widget_Instance::Table()->select()
                ->setIntegrityCheck( false )
                ->from( $this )
                ->join( Widget_Type::TableName(), 'wi_widget_id = wdg_id ')
                ->where( 'wi_widget_id = ?', $nWidgetId );
        if ( $strContext != '' )
            $select->where( 'wi_context = ? ', $strContext );
        //Sys_Debug::alert($strContext . '------' . $nWidgetId );
        return $this->fetchRow( $select );
    }
    /* 
     * @return Widget_Instance_List
     */
    public function findInContext( $strContext )
    {
        $select = $this->select()
                ->setIntegrityCheck( false )
                ->from( $this )
                ->where( 'wi_context = ?', $strContext );
        return $this->fetchAll( $select );
    }
}
/**
 * class of the rowset
 */
class Widget_Instance_List extends DBx_Table_Rowset
{
}

/**
 * class for extending form filtration
 */
class Widget_Instance_Form_Filter extends App_Form_Filter
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
class Widget_Instance_Form_Edit extends App_Form_Edit
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
class Widget_Instance extends DBx_Table_Row
{
    /** 
      * Get class name - for php 5.2 compatibility
      * @return string 
      */
    public static function getClassName() { return 'Widget_Instance'; }
    /** 
      * Get table class object 
      * @return string 
      */
    public static function TableClass() { return self::getClassName().'_Table'; }
    /** 
      *  Get table class instance
      *  @return Widget_Instance_Table 
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
     * 
     * @return array
     */
    public function getPropertiesArray()
    {
        $arrProperties = $this->wi_properties == '' ? array() : json_decode( $this->wi_properties, true );
        return $arrProperties;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get( $key, $default = '' )
    {
        $arrProps = $this->getPropertiesArray();
        return isset( $arrProps[ $key ] ) ? $arrProps[ $key ] : $default;
    }
    
    /**
     * 
     * @return Widget_Type
     */
    public function getWidgetObject()
    {
        return $this->getJoinedObject( Widget_Type::Table(), 'wdg_id', 'wi_widget_id' );
    }
    
    
    /**
     * 
     * @param App_View $view
     * @param boolean $bPreview
     * @return string
     * @throws App_Exception
     */
    public function render( App_View $view, $bPreview = false )
    {
        $objWidget = $this->getWidgetObject();
        $strClass = $objWidget->wdg_class;
        if ( $strClass ) {
            
            
            $objInstanceFromClass = new $strClass( 
                    array( 'wiid' => $this->getId() ) + json_decode( $this->wi_properties, true ) );
            
            // Sys_Debug::dump( $objInstanceFromClass );
            $bForbid = false;

            if ( !$bForbid ) {
                foreach ( $objInstanceFromClass->getPlaceholders() as $strPlaceholder ) {
                    
                    $strChildHtml = '';
                    if ( $bPreview ) $strChildHtml .= '<div class="child-sortable">';

                    $lstChildWidgets = Widget_Instance::Table()
                                ->findByParentId( '', $this->getId(), $strPlaceholder );
                    foreach( $lstChildWidgets as $objChildWidget ) {
                        $strChildHtml .= $objChildWidget->render( $view, $bPreview );
                    }
                    if ( $bPreview ) $strChildHtml .= '<div style="height:10px"></div>';
                    if ( $bPreview ) $strChildHtml .= '</div>';

                    $objInstanceFromClass->addChild( $strPlaceholder, $strChildHtml );
                }

                //Sys_Debug::dump( $objInstanceFromClass );
                return $objInstanceFromClass->render( $view, $bPreview  );
            }
            return '';
            
        } else {
            throw new App_Exception( 'no class for a widget' );
        }
    }
    
}


/**
    wi_id           INT NOT NULL AUTO_INCREMENT,
    wi_form_id      INT NOT NULL DEFAULT 0,
    wi_context      VARCHAR(40)  NOT NULL DEFAULT \'global\',
                
    wi_widget_id    INT NOT NULL DEFAULT 0, -- widget class id
    wi_placebolder  VARCHAR(50)  NOT NULL DEFAULT \'\',
    wi_parent_id    INT NOT NULL DEFAULT 0, -- which widget is a parent (wi_id)
    wi_sortorder    INT NOT NULL DEFAULT 0, -- order of a widget inside a parent folder
    wi_properties   TEXT, -- customization values for this widget instance
 */