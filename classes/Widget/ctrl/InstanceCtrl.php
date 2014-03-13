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
}