<?php

class Default_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    /**
     * Bootstrap autoloader for application resources
     * 
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default',
            'basePath'  => dirname(__FILE__),
        ));
        $resourceLoader->addResourceType('model', 'models/', 'Model');
        return $resourceLoader;
    }

}

