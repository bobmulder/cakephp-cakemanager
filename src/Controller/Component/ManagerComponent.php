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
        'components' => [
            'Auth' => [
                'authorize'            => 'Controller',
                'userModel'            => 'CakeManager.Users',
                'authenticate'         => [
                    'Form' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password'
                        ]
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
                ]
            ]
        ]
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
    public $helpers = [
        'CakeManager.Menu'
    ];

    public function initialize(array $config) {
        parent::initialize($config);

        $this->Controller = $this->_registry->getController();

        if ($this->config('components.Auth')) {
            $this->Controller->loadComponent('Auth', $this->config('components.Auth'));
//            $this->Controller->Auth->allow([]); // this makes that you automatically need to be logged in to enter a page
        }

        $this->Controller->loadComponent('CakeManager.Menu');

        $this->Controller->loadComponent('CakeManager.IsAuthorized');

        $this->loadHelpers();
    }

    private function loadHelpers() {

        $this->Controller->helpers[] = 'CakeManager.Menu';
    }

    /**
     * BeforeFilter Callback
     *
     */
    public function beforeFilter($event) {

        $this->Controller->authUser = $this->Controller->Auth->user();

        // beforeFilter-event

        $_event = new Event('Component.Manager.beforeFilter', $this, [
        ]);
        $this->Controller->eventManager()->dispatch($_event);

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
    public function beforeRender($event) {

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
    public function shutdown($event) {


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

    public function admin_beforeFilter($event) {

        $this->Controller->layout = 'CakeManager.admin';
    }

    public function isAdmin($user) {

        $array = Configure::read('CM.Roles.Administrators');

        if (in_array($user['role_id'], $array)) {
            return true;
        }

        return false;
    }

    public function isModerator($user) {

        $array = Configure::read('CM.Roles.Moderators');

        if (in_array($user['role_id'], $array)) {
            return true;
        }

        return false;
    }

    public function isUser($user) {

        $array = Configure::read('CM.Roles.Users');

        if (in_array($user['role_id'], $array)) {
            return true;
        }

        return false;
    }

       public function isUnregistered($user) {

        $array = Configure::read('CM.Roles.Unregistered');

        if (in_array($user['role_id'], $array)) {
            return true;
        }

        return false;
    }

}
