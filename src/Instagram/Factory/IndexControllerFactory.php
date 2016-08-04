<?php
namespace Instagram\Factory; 

use Instagram\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $baseSettings = $realServiceLocator->get('SettingsService');
        $instagramService = $realServiceLocator->get('InstagramService');
        $instagramProfileService = $realServiceLocator->get('InstagramProfileService');
        return new IndexController($instagramService, $instagramProfileService, $baseSettings);
    }
}