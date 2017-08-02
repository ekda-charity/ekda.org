<?php
namespace Application {
    
    use Application\API\Canonicals\General\Constants;

    return [
        'controllers' => [
            'invokables' => [],
            'factories' => [
                'Index'          => 'Application\ControllerFactory\IndexControllerFactory',
                'Admin'          => 'Application\ControllerFactory\AdminControllerFactory',
                'QurbaniApi'     => 'Application\ControllerFactory\QurbaniApiControllerFactory',
                'BatchMail'      => 'Application\ControllerFactory\BatchMailControllerFactory',
                'AdminApi'       => 'Application\ControllerFactory\AdminApiControllerFactory',
            ],
        ],

        'router' => [
            'routes' => [
                'web' => [
                    'type' => 'segment',
                    'options' => [
                        'route'    => '/[:controller[/:action[/:p1[/:p2[/:p3[/:p4]]]]]]',
                        'constraints' => [
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'p1'         => '[a-zA-Z0-9_-]*',
                            'p2'         => '[a-zA-Z0-9_-]*',
                            'p3'         => '[a-zA-Z0-9_-]*', 
                            'p4'         => '[a-zA-Z0-9_-]*', 
                        ],
                        'defaults' => [
                            'controller' => 'Index',
                            'action'     => 'index',
                        ],
                    ],
                ],
                'api' => [
                    'type' => 'segment',
                    'options' => [
                        'route'    => '/api/:controller/:action[/:p1[/:p2[/:p3[/:p4]]]]',
                        'constraints' => [
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'p1'         => '[a-zA-Z0-9_-]*',
                            'p2'         => '[a-zA-Z0-9_-]*',
                            'p3'         => '[a-zA-Z0-9_-]*',
                            'p4'         => '[a-zA-Z0-9_-]*',
                        ],
                    ],
                ],
            ],
        ],

        'service_manager' => [
            'abstract_factories' => [
                'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
                'Zend\Log\LoggerAbstractServiceFactory',
            ],
            'factories' => [
                'Navigation'        => 'Zend\Navigation\Service\DefaultNavigationFactory',
                'WordPrRepo'        => 'Application\API\Repositories\Factories\WordPressRepositoryFactory',
                'EMailSvc'          => 'Application\API\Repositories\Factories\EMailServiceFactory',
                'GMailSvc'          => 'Application\API\Repositories\Factories\GeneralMailingServiceFactory',
                'QurbaniRepo'       => 'Application\API\Repositories\Factories\QurbaniRepositoryFactory',
                'UsersRepo'         => 'Application\API\Repositories\Factories\UsersRepositoryFactory',
                'AdminAuthService'  => 'Application\API\Repositories\Factories\AdminAuthServiceFactory',
            ],
        ],

        'doctrine' => [
            'driver' => [
                __NAMESPACE__ . '_driver' => [
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/API/Canonicals/Entity']
                ],
                'orm_default' => [
                    'drivers' => [
                        __NAMESPACE__ . '\API\Canonicals\Entity' => __NAMESPACE__ . '_driver'
                    ]
                ]
            ]
        ],


        'view_manager' => [
            'display_not_found_reason' => true,
            'display_exceptions'       => true,
            'doctype'                  => 'HTML5',
            'not_found_template'       => 'error/404',
            'exception_template'       => 'error/index',
            'template_map' => [
                'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
                'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
                'error/404'               => __DIR__ . '/../view/error/404.phtml',
                'error/index'             => __DIR__ . '/../view/error/index.phtml',
                'layout/messages'         => __DIR__ . '/../view/layout/_messages.phtml',
                'layout/wppost'           => __DIR__ . '/../view/layout/_wppost.phtml',
                'admin/_qurbani_modal'    => __DIR__ . '/../view/application/admin/_qurbani_modal.phtml',
            ],
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ],

        'ENV' => (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : (getenv('REDIRECT_APPLICATION_ENV') ? getenv('REDIRECT_APPLICATION_ENV') : 'development')),

        'navigation' => [
            'default' => [
                'preview' => [
                    'id' => Constants::PREVIEW_ID,
                    'label' => 'Preview',
                    'controller' => 'Index',
                    'action' => 'preview',
                    'visible' => false,
                ],
                'admin' => [
                    'id' => Constants::ADMIN_ID,
                    'label' => 'Admin',
                    'controller' => 'Admin',
                    'action' => 'index',
                    'visible' => false,
                ],
                'qurbani' => [
                    'id' => 'Qurbani',
                    'label' => 'Qurbani',
                    'controller' => 'Admin',
                    'action' => 'qurbani',
                    'requireslogin' => true,
                ],
                'Home' => [
                    'id' => Constants::HOME_PAGE_NAVIGATION_ID,
                    'label' => 'Home',
                    'controller' => 'Index',
                    'action' => 'index',
                ],
                'About' => [
                    'id' => 'About',
                    'label' => 'About us',
                    'controller' => 'Index',
                    'action' => 'about',
                ],
                'Projects' => [
                    'id' => 'Projects',
                    'label' => 'Projects',
                    'controller' => 'Index',
                    'action' => 'projects',
                ],
                'News' => [
                    'id' => 'News',
                    'label' => 'News',
                    'controller' => 'Index',
                    'action' => 'news',
                ],
                'Sponsors' => [
                    'id' => 'Sponsors',
                    'label' => 'Sponsors',
                    'controller' => 'Index',
                    'action' => 'sponsors',
                ],
                'Donate' => [
                    'id' => 'Donate',
                    'label' => 'Donate',
                    'controller' => 'Index',
                    'action' => 'donate',
                ],
                'Contacts' => [
                    'id' => 'Contacts',
                    'label' => 'Contact us',
                    'controller' => 'Index',
                    'action' => 'contacts',
                ],
            ],
        ],
    ];
}
