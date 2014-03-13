<?php

/**
  * controller for the Widget_Context model
  */   
class Widget_ContextCtrl extends App_DbTableCtrl
{
    /**
     * get class of the model
     * @return string
     */
    public function getClassName() 
    {
        return 'Widget_Context';
    }
    
    public function getAction()
    {
        if ( ! $this->_hasParam( 'context_id') && 
               $this->_hasParam( 'context_name') ) {
            
            $this->view->object = Widget_Context::Table()->fetchByName( $this->_getParam('context_name') );
        } else {
        
            parent::getAction();
        }
    }
}