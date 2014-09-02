<?php

/**
  * controller for the Widget_Instance model
  */   
class Widget_InstanceCtrl extends App_DbTableCtrl
{
    /**
     * get class of the model
     * @return string
     */
    public function getClassName() 
    {
        return 'Widget_Instance';
    }
    
    public function uploadAction()
    {
        // image uploader to CDN folder
        $dirTo = CWA_APPLICATION_DIR.'/cdn/upload';
        $dir = new Sys_Dir( $dirTo);
        if ( !$dir->exists() ) { $dir->create('', true); }

        $handler = new Widget_Upload_Handler();
        $arrResult = $handler->handleUpload( $dirTo.'/', true ); // slash at the end is important
        $strFileName = $handler->getUploadName();
        
        if ( isset( $arrResult['error'] )) {
            $this->view->result = array('success'  => false, 'error'  => $arrResult['error'] );
        } else { 
            $this->view->result = array( 'success'  => true);
        }

    }
    
    protected function getSortableField()
    {
        return 'wi_sortorder';
    }
      
    public function sortOrderAction()
    {
        $strSortableField = $this->getSortableField();
        $strIds = $this->_getParam('ids');
        if ( $strIds == '' ) $strIds = $this->_getParam('order'); // old compatibility...
            
        $arrIds = explode( ",", $strIds );
        if ( count( $arrIds ) == 0 )
            throw new App_Exception( "ids are not provided" );
        
        $this->_model = Widget_Instance::Table();
        $nIterator = 0;
        foreach ( $arrIds as $nId )  {
            $objRow = $this->_model->find( $nId )->current();
            if ( is_object( $objRow )) {
                $objRow->$strSortableField = $nIterator ++;
                $objRow->save( false );
            }
        }
    }

    public function getAction()
    {
        parent::getAction();
        $this->view->widget = Widget_Type::Table()->find( $this->view->object->wi_widget_id )->current();
    }       
    
    public function addAction()
    {
        if ( !$this->_getParam('wi_context') ) {
            throw new App_Exception( 'context was not defined' );
        }
        
        $nWidgetId = $this->_getParam('wi_widget_id');
        /* @var $objWidget Widget_Type */
        $objWidget = Widget_Type::Table()->find( $nWidgetId )->current();
        if ( !is_object( $objWidget ))
            throw new App_Exception( 'widget could not be found by UD');
        
        $nParentId = $this->_getParam('wi_parent_id');
        $arrOptions = array( 'class' => $objWidget->wdg_class );
        
        $this->_model = Widget_Instance::Table();

        $objWi = $this->_model->createRow();
        $objWi->wi_tpl_id = $this->_getParam('wi_tpl_id');
        $objWi->wi_context = $this->_getParam('wi_context');
        $objWi->wi_parent_id = $nParentId;
        $objWi->wi_sortorder = 0;
        $objWi->wi_placeholder = $this->_getParam('wi_placeholder');
        $objWi->wi_widget_id = $objWidget->getId(); // get widget ID from class name
        $objWi->wi_properties = json_encode( $arrOptions );
        $objWi->save();        
        
        $this->view->object = $objWi;

    }
    
    public function saveAction()
    {
        
      //  Sys_Debug::dump( $this->view->form->toArray() );
      //  Sys_Debug::dump( $this->_getAllParams());
        
        $nWidgetInstance = $this->_getParam( 'wi_id' );
        if ( ! $nWidgetInstance )
            throw new App_Exception( 'widget instance id is missing' );
        
        $this->_model = Widget_Instance::Table();
        
        $objWidgetInstance = $this->_model->find( $nWidgetInstance )->current();
        if ( ! is_object( $objWidgetInstance ) )
            throw new App_Exception( 'widget instance id is invalid ('.$nWidgetInstance.')' );
        $objWidget = $objWidgetInstance->getWidgetObject();
        if ( ! is_object( $objWidget ) )
            throw new App_Exception( 'widget type is invalid('.$objWidgetInstance->wi_widget.')' );
        
        $objClass = $objWidget->getConfiguration();
        if ( $this->_isPost() ) {
            //Sys_Debug::alert( $this->_getAllParams() );

            $arrProperties = json_decode( $objWidgetInstance->wi_properties, true );
            foreach ( $objClass->getStorageOptions() as $arrOptions ) { 
                $strType = $arrOptions['type'];
                $strName = $arrOptions['name'];
                
                if ( $this->_hasParam( 'wi-'.$strName ) ) {
                    if ( $strType == 'checkbox' )
                        $this->_adjustIntParam( 'wi-'. $strName );
                    
                    $arrProperties[ $strName ] = $this->_getParam( 'wi-'.$strName );
                }
            }
            $objWidgetInstance->wi_properties = json_encode( $arrProperties );
            $objWidgetInstance->save( false );
        }
        
        $this->view->object = $objWidgetInstance;
        $this->view->widget = Widget_Type::Table()->find( $this->view->object->wi_widget_id )->current();
    }

}