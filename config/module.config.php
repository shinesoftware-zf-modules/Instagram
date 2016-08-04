<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletoninstagram for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
        'asset_manager' => array(
                'resolver_configs' => array(
                        'collections' => array(
                                'js/application.js' => array(
                                        'js/jquery.prettyPhoto.js',
                                        'js/instagram.js',
                                ),
                                'css/application.css' => array(
                                        'css/prettyPhoto.css',
                                        'css/instagram.css',
                                ),
                        ),
                        'paths' => array(
                                __DIR__ . '/../public',
                        ),
                ),
        ),
		'bjyauthorize' => array(
				'guards' => array(
					'BjyAuthorize\Guard\Route' => array(
							
		                // Generic route guards
		                array('route' => 'instagram', 'roles' => array('user')),
		                array('route' => 'instagram/default', 'roles' => array('user')),
		                array('route' => 'instagram/auth', 'roles' => array('user')),
		                array('route' => 'instagram/callback', 'roles' => array('user')),

					),
			  ),
		),
		'navigation' => array(
				'admin' => array(
        				
				),
		),
    'router' => array(
        'routes' => array(
            'instagram' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/instagram',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Instagram\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'page'			=> 1
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    
                    'auth' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                    'route'    => '/auth[/:params]',
                                    'constraints' => array(
                                            'params'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                            'controller'        => 'Index',
                                            'action'        => 'auth',
                                    ),
                            ),
                    ),
                    
                    'callback' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                    'route'    => '/callback[/:code]',
                                    'constraints' => array(
                                            'code'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                            'controller'        => 'Index',
                                            'action'        => 'callback',
                                    ),
                            ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
        ),
        'factories' => array(
        		'Instagram\Controller\Index' => 'Instagram\Factory\IndexControllerFactory',
        )
    ),
    'view_helpers' => array (
    		'invokables' => array (
    		    'instabutton' => 'Instagram\View\Helper\InstagramButton',
    		    'instawidget' => 'Instagram\View\Helper\InstagramWidget',
    		    'instawidgetbytag' => 'Instagram\View\Helper\InstagramWidgetByTag',
    		    'photos' => 'Instagram\View\Helper\Photos'
    		)
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
