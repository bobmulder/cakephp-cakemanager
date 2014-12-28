<?php

namespace CakeManager\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->addBehavior('CakeManager.IsAuthorized');

        $this->hasMany('Bookmarks', [
            'alias'      => 'Bookmarks',
            'foreignKey' => 'user_id',
            'className'  => 'Bookmarks'
        ]);
        $this->belongsTo('Roles', [
            'className'    => 'CakeManager.Roles',
            'foreignKey'   => 'role_id',
            'propertyName' => 'role',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator instance
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->add('id', 'valid', ['rule' => 'numeric'])
                ->allowEmpty('id', 'create')
                ->add('email', 'valid', ['rule' => 'email'])
                ->requirePresence('email', 'create')
                ->notEmpty('email')
                ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
                ->requirePresence('password', 'create')
                ->notEmpty('password')
                ->notEmpty('confirm_password')
                ->add('confirm_password', 'custom', [
                    'rule' => function($value, $context) {
                        if ($value !== $context['data']['password']) {
                            return false;
                        }
                        return false;
                    },
                    'message' => 'The passwords are not equal',
        ]);

        return $validator;
    }

    public function beforeEdit($controller) {

        $controller->set('roles', $this->Roles->find('list'));

    }

}