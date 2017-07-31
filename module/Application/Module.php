<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $this->initializeEnvironment($e);
        $this->initializeMessages($e);
        $this->initializeAnalyticsTrackingID($e);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    private function initializeEnvironment(MvcEvent $e) {
        $env = (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : (getenv('REDIRECT_APPLICATION_ENV') ? getenv('REDIRECT_APPLICATION_ENV') : 'development'));
        $e->getViewModel()->setVariable('environment', $env);
    }
    
    private function initializeMessages(MvcEvent $e) {
        $flash = $e->getTarget()->getServiceManager()->get('ControllerPluginManager')->get('flashmessenger');            

        if (count($flash->getSuccessMessages()) > 0) {
            $e->getViewModel()->setVariable('succ', $flash->getSuccessMessages());
        } 

        if (count($flash->getInfoMessages()) > 0) {
            $e->getViewModel()->setVariable('info', $flash->getInfoMessages());
        }

        if (count($flash->getWarningMessages()) > 0) {
            $e->getViewModel()->setVariable('warning', $flash->getInfoMessages());
        }

        if (count($flash->getErrorMessages()) > 0) {
            $e->getViewModel()->setVariable('err', $flash->getErrorMessages());
        }

        $flash->clearMessages();
    }

    private function initializeAnalyticsTrackingID(MvcEvent $e) {
        $config = $e->getTarget()->getServiceManager()->get('Config');
        $analyticsTrackingID = $config['AnalyticsTrackingID'];
        $e->getViewModel()->setVariable('analyticsTrackingID', $analyticsTrackingID);
    }
}
