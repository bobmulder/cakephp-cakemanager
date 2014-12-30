<?php

namespace CakeManager\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Controller\Component\AuthComponent;

/**
 * IsAuthorized behavior
 */
class IsAuthorizedBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     *
     * ### Options
     * - field          string      field to check
     */
    protected $_defaultConfig = [
        'field' => 'user_id'
    ];

    public function __construct(Table $table, array $config = array()) {
        parent::__construct($table, $config);

        $this->Table = $table;

    }

    public function authorize($id, $user) {

        $item = $this->Table->get($id);


        if ($item->get($this->config('field')) == $user['id']) {
            return true;
        }

        return false;
    }

}