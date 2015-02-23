<?php

namespace CakeManager\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Core\Configure;

/**
 * Manager component
 */
class ManagerComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'components'  => [
            'Auth' => [
                'authorize'            => 'Controller',
                'userModel'            => 'CakeManager.Users',
                'authenticate'         => [
                    'Form' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password'
                        ],
                        'scope'  => ['Users.active' => true],
                    ]
                ],
                'logoutRedirect'       => [
                    'prefix'     => false,
                    'plugin'     => 'CakeManager',
                    'controller' => 'Users',
                    'action'     => 'login'
                ],
                'loginAction'          => [
                    'prefix'     => false,
                    'plugin'     => 'CakeManager',
                    'controller' => 'Users',
                    'action'     => 'login'
                ],
                'unauthorizedRedirect' => false,
            ]
        ],
        'adminTheme'  => 'CakeManager',
        'adminLayout' => 'CakeManager.admin',
        'adminMenus'  => [
            'main'   => 'CakeManager.MainMenu',
            'navbar' => 'CakeManager.NavbarMenu',
        ],
    ];

    /**
     * The original controller
     * @var type
     */
    public $Controller;

    /**
     * Preset Helpers to load
     * @var type
     */
    public $helpers = [];

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->Controller = $this->_registry->getController();


        if ($this->config('components.Auth')) {
            $this->Controller->loadComponent('Auth', $this->config('components.Auth'));
        }

        $this->Controller->loadComponent('CakeManager.Menu');
    }

    private function _loadHelpers()
    {
        if ($this->config('adminMenus')) {
            $this->Controller->helpers['CakeManager.Menu'] = $this->config('adminMenus');
        }
    }

    /**
     * BeforeFilter Callback
     *
     */
    public function beforeFilter($event)
    {

        $this->Controller->authUser = $this->Controller->Auth->user();

        // beforeFilter-event
        $_event = new Event('Component.Manager.beforeFilter', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        // beforeFilter-event for prefixes
        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

            if (method_exists($this, $event->subject()->request->prefix . '_beforeFilter')) {
                call_user_method($event->subject()->request->prefix . '_beforeFilter', $this, $event);
            }

            // beforeFilter-event with Prefix
            $_event = new Event('Component.Manager.beforeFilter.' . $prefix, $this, [
            ]);
            $this->Controller->eventManager()->dispatch($_event);
        }

        $this->_loadHelpers();
    }

    /**
     * Startup Callback
     *
     */
    public function startup($event)
    {

        // startup-event
        $_event = new Event('Component.Manager.startup', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

            if (method_exists($this, $event->subject()->request->prefix . '_startup')) {
                call_user_method($event->subject()->request->prefix . '_startup', $this, $event);
            }

            // startup-event with Prefix
            $_event = new Event('Component.Manager.startup.' . $prefix, $this, [
            ]);
            $this->Controller->eventManager()->dispatch($_event);
        }
    }

    /**
     * BeforeRender Callback
     *
     */
    public function beforeRender($event)
    {

        $this->Controller->set('authUser', $this->Controller->authUser);

        // beforeRender-event
        $_event = new Event('Component.Manager.beforeRender', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

            if (method_exists($this, $event->subject()->request->prefix . '_beforeRender')) {
                call_user_method($event->subject()->request->prefix . '_beforeRender', $this, $event);
            }

            // beforeRender-event with Prefix
            $_event = new Event('Component.Manager.beforeRender.' . $prefix, $this, [
            ]);
            $this->Controller->eventManager()->dispatch($_event);
        }
    }

    /**
     * Shutdown Callback
     *
     */
    public function shutdown($event)
    {

        // shutdown-event
        $_event = new Event('Component.Manager.shutdown', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

            if (method_exists($this, $event->subject()->request->prefix . '_shutdown')) {
                call_user_method($event->subject()->request->prefix . '_shutdown', $this, $event);
            }

            // shutdown-event with Prefix
            $_event = new Event('Component.Manager.shutdown.' . $prefix, $this, [
            ]);
            $this->Controller->eventManager()->dispatch($_event);
        }
    }

    /**
     * Admin BeforeFilter
     *
     * Loads the first menu-items for the admin-area
     * and sets the theme and layout
     *
     * @param type $event
     */
    public function admin_beforeFilter($event)
    {

        $this->Controller->Menu->add('Users', [
            'url'    => [
                'plugin'     => 'CakeManager',
                'prefix'     => 'admin',
                'controller' => 'users',
                'action'     => 'index',
            ],
            'weight' => 0,
        ]);

        $this->Controller->Menu->add('Roles', [
            'url'    => [
                'plugin'     => 'CakeManager',
                'prefix'     => 'admin',
                'controller' => 'roles',
                'action'     => 'index',
            ],
            'weight' => 1,
        ]);

        $this->Controller->theme = $this->config('adminTheme');

        $this->Controller->layout = $this->config('adminLayout');
    }

    /**
     * Quick method to check if a specific prefix is set.
     *
     * @param type $expected
     * @return boolean
     */
    public function prefix($expected = null)
    {

        $current = null;

        if ($this->Controller->request->prefix !== null) {
            $current = $this->Controller->request->prefix;
        }

        if ($current == $expected) {
            return true;
        }

        return false;
    }

}
