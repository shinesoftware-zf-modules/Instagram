<?php
/**
* Copyright (c) 2014 Shine Software.
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions
* are met:
*
* * Redistributions of source code must retain the above copyright
* notice, this list of conditions and the following disclaimer.
*
* * Redistributions in binary form must reproduce the above copyright
* notice, this list of conditions and the following disclaimer in
* the documentation and/or other materials provided with the
* distribution.
*
* * Neither the names of the copyright holders nor the names of the
* contributors may be used to endorse or promote products derived
* from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
* FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
* COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
* CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
* LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
* ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* @package Instagram
* @subpackage Entity
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/


namespace Instagram;

use Base\View\Helper\Datetime;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Instagram\Service\InstagramService;
use Instagram\Service\SocialEventsService;
use Instagram\Entity\InstagramProfiles;
use Instagram\Entity\InstagramEvents as google_event;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Instagram\InstagramSession;
use Instagram\Entities\AccessToken;
use Instagram\InstagramSDKException;

class Module implements DependencyIndicatorInterface{
	
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $sm = $e->getApplication()->getServiceManager();
        $headLink = $sm->get('viewhelpermanager')->get('headLink');
//         $headLink->appendStylesheet('/css/Instagram/Instagram.css');
        
        $inlineScript = $sm->get('viewhelpermanager')->get('inlineScript');
//         $inlineScript->appendFile('/js/Instagram/Instagram.js');
        
    }
    
    /**
     * Check the dependency of the module
     * (non-PHPdoc)
     * @see Zend\ModuleManager\Feature.DependencyIndicatorInterface::getModuleDependencies()
     */
    public function getModuleDependencies()
    {
    	return array('Base', 'ZfcUser', 'Events');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    /**
     * Set the Services Manager items
     */
    public function getServiceConfig ()
    { 
    	return array(
    			'factories' => array(
    			        
    			        'InstagramProfileService' => function  ($sm)
    			        {
    			            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			            $translator = $sm->get('translator');
    			            $resultSetPrototype = new ResultSet();
    			            $resultSetPrototype->setArrayObjectPrototype(new \Instagram\Entity\InstagramProfiles());
    			            $tableGateway = new TableGateway('instagram_profiles', $dbAdapter, null, $resultSetPrototype);
    			            $service = new \Instagram\Service\InstagramProfileService($tableGateway, $translator);
    			            return $service;
    			        },
    			        
    					'InstagramService' => function  ($sm)
    					{
    					    $instagram = new \Instagram\Instagram();
    					    if(!empty($_SESSION['instagram_access_token'])){
    					        $instagram->setAccessToken( $_SESSION['instagram_access_token'] );
    					    }
    						$service = new \Instagram\Service\InstagramService($instagram, $sm->get('translator'));
    						return $service;
    					},
    					
    				),
    			);
    }
    
    
    /**
     * Get the form elements
     */
    public function getFormElementConfig ()
    {
        return array (
                'factories' => array (
                     )
                );
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
}
