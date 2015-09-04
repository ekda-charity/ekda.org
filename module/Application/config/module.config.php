<?php
namespace Application {

    use Application\API\Canonicals\General\Constants;
    
    return array(
        'controllers' => array(
            'invokables' => array(
                'Index'          => 'Application\Controller\IndexController',
                'Admin'          => 'Application\Controller\AdminController',
                'QurbaniApi'     => 'Application\Controller\QurbaniApiController',
                'BatchMail'      => 'Application\Controller\BatchMailController',
                'AdminApi'       => 'Application\Controller\AdminApiController',
            ),
        ),
        'router' => array(
            'routes' => array(
                'web' => array(
                    'type'    => 'segment',
                    'options' => array(
                        'route'    => '/[:controller[/:action[/:p1[/:p2[/:p3[/:p4]]]]]]',
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'p1'         => '[a-zA-Z0-9_-]*',
                            'p2'         => '[a-zA-Z0-9_-]*',
                            'p3'         => '[a-zA-Z0-9_-]*', 
                            'p4'         => '[a-zA-Z0-9_-]*', 
                        ),
                        'defaults' => array(
                            'controller' => 'Index',
                            'action'     => 'index',
                        ),
                    ),
                ),
                'api' => array(
                    'type'    => 'segment',
                    'options' => array(
                        'route'    => '/api/:controller/:action[/:p1[/:p2[/:p3[/:p4]]]]',
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'p1'         => '[a-zA-Z0-9_-]*',
                            'p2'         => '[a-zA-Z0-9_-]*',
                            'p3'         => '[a-zA-Z0-9_-]*',
                            'p4'         => '[a-zA-Z0-9_-]*',
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
            'factories' => array(
                'translator'        => 'Zend\Mvc\Service\TranslatorServiceFactory',
                'Navigation'        => 'Zend\Navigation\Service\DefaultNavigationFactory',
                'WordPrRepo'        => 'Application\API\Repositories\Factories\WordPressRepositoryFactory',
                'EMailSvc'          => 'Application\API\Repositories\Factories\EMailServiceFactory',
                'GMailSvc'          => 'Application\API\Repositories\Factories\GeneralMailingServiceFactory',
                'QurbaniRepo'       => 'Application\API\Repositories\Factories\QurbaniRepositoryFactory',
                'UsersRepo'         => 'Application\API\Repositories\Factories\UsersRepositoryFactory',
                'ZendDbAdapter'     => 'Application\API\Repositories\Factories\ZendDbAdapterFactory',
                'AdminAuthStorage'  => 'Application\API\Repositories\Factories\AdminAuthStorageFactory',
                'AdminAuthService'  => 'Application\API\Repositories\Factories\AdminAuthServiceFactory',
            ),
        ),
        'doctrine' => array(
            'driver' => array(
                __NAMESPACE__ . '_driver' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/API/Canonicals/Entity')
                ),
                'orm_default' => array(
                    'drivers' => array(
                        __NAMESPACE__ . '\API\Canonicals\Entity' => __NAMESPACE__ . '_driver'
                    )
                )
            )
        ),
        'translator' => array(
            'locale' => 'en_US',
            'translation_file_patterns' => array(
                array(
                    'type'     => 'gettext',
                    'base_dir' => __DIR__ . '/../language',
                    'pattern'  => '%s.mo',
                ),
            ),
        ),
        'view_manager' => array(
            'display_not_found_reason' => true,
            'display_exceptions'       => true,
            'doctype'                  => 'HTML5',
            'not_found_template'       => 'error/404',
            'exception_template'       => 'error/index',
            'template_map' => array(
                'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
                'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
                'error/404'               => __DIR__ . '/../view/error/404.phtml',
                'error/index'             => __DIR__ . '/../view/error/index.phtml',
                'layout/messages'         => __DIR__ . '/../view/layout/_messages.phtml',
                'layout/wppost'           => __DIR__ . '/../view/layout/_wppost.phtml',
            ),
            'template_path_stack' => array(
                __DIR__ . '/../view',
            ),
            'strategies' => array(
                'ViewJsonStrategy',
            ),
        ),
        // Placeholder for console routes
        'console' => array(
            'router' => array(
                'routes' => array(
                ),
            ),
        ),
        
        'navigation' => array(
            'default' => array(
                'preview' => array(
                    'id' => Constants::PREVIEW_ID,
                    'label' => 'Preview',
                    'controller' => 'Index',
                    'action' => 'preview',
                    'visible' => false,
                ),
                'admin' => array(
                    'id' => Constants::ADMIN_ID,
                    'label' => 'Admin',
                    'controller' => 'Admin',
                    'action' => 'index',
                    'visible' => false,
                ),
                'qurbani' => array(
                    'id' => 'Qurbani',
                    'label' => 'Qurbani',
                    'controller' => 'Admin',
                    'action' => 'qurbani',
                    'requireslogin' => true,
                ),
                'Home' => array(
                    'id' => Constants::HOME_PAGE_NAVIGATION_ID,
                    'label' => 'Home',
                    'controller' => 'Index',
                    'action' => 'index',
                ),
                'About' => array(
                    'id' => 'About',
                    'label' => 'About us',
                    'controller' => 'Index',
                    'action' => 'about',
                ),
                'Projects' => array(
                    'id' => 'Projects',
                    'label' => 'Projects',
                    'controller' => 'Index',
                    'action' => 'projects',
                ),
                'News' => array(
                    'id' => 'News',
                    'label' => 'News',
                    'controller' => 'Index',
                    'action' => 'news',
                ),
                'Sponsors' => array(
                    'id' => 'Sponsors',
                    'label' => 'Sponsors',
                    'controller' => 'Index',
                    'action' => 'sponsors',
                ),
                'Donate' => array(
                    'id' => 'Donate',
                    'label' => 'Donate',
                    'controller' => 'Index',
                    'action' => 'donate',
                ),
                'Contacts' => array(
                    'id' => 'Contacts',
                    'label' => 'Contact us',
                    'controller' => 'Index',
                    'action' => 'contacts',
                ),
            ),
        ),
    );
}
