<?php

/**
 */
class Widget_Update extends App_Update {
    /**
     * @var VERSION - Defines the current version of the component.
     */

    const VERSION = '0.1.0';

    /**
     * Returns class model name.
     *
     * @return string
     * @author Victor Lopata
     * */
    public static function getClassName() {
        return 'Widget_Update';
    }

    /**
     * Returns class table name.
     *
     * @return string
     * @author Victor Lopata
     * */
    public static function TableClass() {
        return self::getClassName() . '_Table';
    }

    /**
     * Returns class table object.
     *
     * @return object
     * @author Victor Lopata
     * */
    public static function Table() {
        $strClass = self::TableClass();
        return new $strClass;
    }

    /**
     * Returns class table name.
     *
     * @return string
     * @author Victor Lopata
     * */
    public static function TableName() {
        return self::Table()->getTableName();
    }

    /**
     * Updates all of the main tables in the database, updates version
     * upon completion.
     *
     * @return void
     * @author Victor Lopata
     * */
    public function update() {
        // init 3 tables: 

        // 1) contexts 
        if (!$this->getDbAdapterRead()->hasTable('widget_context')) {
            Sys_Io::out( 'adding table widget_context' );
            $this->getDbAdapterWrite()->addTableSql('widget_context', '
                context_id        INT NOT NULL AUTO_INCREMENT,
                context_name      VARCHAR(255)  NOT NULL DEFAULT \'main\',
                context_css       MEDIUMTEXT,
                KEY k_context_name( context_name )
            ', 'context_id' );
        }
        Widget_Context::Table()->addFromConfig();

        // 2) widget types 
        if (!$this->getDbAdapterRead()->hasTable('widget_type')) {
            
            Sys_Io::out( 'adding table widget_type' );
            $this->getDbAdapterWrite()->addTableSql('widget_type', '
                wdg_id           INT(11)         NOT NULL AUTO_INCREMENT,            
                wdg_category     VARCHAR(255)    NOT NULL DEFAULT \'\',   
                wdg_name         VARCHAR(255)    NOT NULL DEFAULT \'\',
                wdg_class        VARCHAR(255)    NOT NULL DEFAULT \'\',
                wdg_context      VARCHAR(255)    NOT NULL DEFAULT \'\',
                wdg_owner        INT(11)         NOT NULL DEFAULT 0,
                wdg_dt_created   DATETIME        NOT NULL DEFAULT \'0000-00-00 00:00:00\',
                
                KEY k_wdg_category( wdg_category ),
                KEY k_wdg_class( wdg_class ),
                KEY k_wdg_name( wdg_name )
            ', 'wdg_id' );
        }
        Widget_Type::Table()->addFromConfig();
        
        // 3) widget instances
        if (!$this->getDbAdapterRead()->hasTable('widget_instance')) {
            Sys_Io::out( 'adding table widget_instance' );
            $this->getDbAdapterWrite()->addTableSql('widget_instance', '
                wi_id           INT NOT NULL AUTO_INCREMENT,
                wi_tpl_id       INT NOT NULL DEFAULT 0, -- template from which it was copied?
                wi_context      VARCHAR(40)  NOT NULL DEFAULT \'\',
                
                wi_widget_id    INT NOT NULL DEFAULT 0, -- widget class id
                wi_parent_id    INT NOT NULL DEFAULT 0, -- which widget is a parent (wi_id)
                wi_placeholder  VARCHAR(40)  NOT NULL DEFAULT \'\',
                wi_sortorder    INT NOT NULL DEFAULT 0, -- order of a widget inside a parent folder
                wi_properties   TEXT, -- customization values for this widget instance
                
                KEY k_wi_tpl_id( wi_tpl_id ),
                KEY k_wi_context( wi_context ),
                KEY k_wi_parent_id( wi_parent_id ),
                KEY k_wi_placeholder( wi_placeholder )
            ', 'wi_id' );
        }
        
        $this->save(self::VERSION);
    }
    
    /**
     * Returns an array of table to remove
     *
     * @return array
     */
    public static function getTables() {
        return array( 'widget_context', 'widget_type', 'widget_instance' );
    }

}