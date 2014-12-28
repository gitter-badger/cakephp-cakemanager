<?php

namespace CakeManager\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Event\EventManager;

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
    protected $_defaultConfig = [];

    /**
     * The original controller
     * @var type
     */
    public $Controller;

    /**
     * Preset Helpers to load
     * @var type
     */
    public $helpers = [
        'CakeManager.Menu'
    ];

    public function initialize(array $config) {
        parent::initialize($config);

        $this->Controller = $this->_registry->getController();

        $this->Controller->loadComponent('Auth', [
            'authorize'            => 'Controller',
            'authenticate'         => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'unauthorizedRedirect' => [
                'prefix'     => false,
                'plugin'     => 'CakeManager',
                'controller' => 'Users',
                'action'     => 'login'
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
            ]
        ]);

        $this->Controller->loadComponent('CakeManager.Menu');

        $this->Controller->loadComponent('CakeManager.IsAuthorized');



        $this->loadHelpers();
    }

    private function loadHelpers() {

        $this->Controller->helpers[] = 'CakeManager.Menu';
        $this->Controller->helpers[] = 'CakeManager.Meta';
    }

    /**
     * BeforeFilter Callback
     *
     */
    public function beforeFilter($event) {

        // beforeFilter-event
        $_event = new Event('Component.Manager.beforeFilter', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

            // beforeFilter-event with Prefix
            $_event = new Event('Component.Manager.beforeFilter.' . $prefix, $this, [
            ]);
            $this->Controller->eventManager()->dispatch($_event);
        }
    }

    /**
     * Startup Callback
     *
     */
    public function startup($event) {

        // startup-event
        $_event = new Event('Component.Manager.startup', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

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
    public function beforeRender($event) {

        // beforeRender-event
        $_event = new Event('Component.Manager.beforeRender', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

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
    public function shutdown($event) {


        // shutdown-event
        $_event = new Event('Component.Manager.shutdown', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

        if ($event->subject()->request->prefix !== null) {

            $prefix = ucfirst($event->subject()->request->prefix);

            // shutdown-event with Prefix
            $_event = new Event('Component.Manager.shutdown.' . $prefix, $this, [
            ]);
            $this->Controller->eventManager()->dispatch($_event);
        }
    }

}
