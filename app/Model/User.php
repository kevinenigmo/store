<?php

class User extends AppModel {
	
	public $hasMany = 'Comment';
	
	public $validate = array(
		'username' => array('usernameEmpty' => array('rule' => 'notEmpty', 'message' => 'Username is required'), 
							'usernameUnique' => array('rule' => 'isUnique', 'message' => 'username should be unique'), 
							'usernameLength' => array('rule' => array('minLength', 8), 'message' => 'username should be at least 8 characters')),
		'email'	=> array('emailEmpty' => array('rule' => 'notEmpty', 'message' => 'email is required'), 
						'emailUnique' => array('rule' => 'isUnique', 'message' => 'email should be unique'), 
						'emailEmail' => array('rule' => 'email', 'message' => 'should be email')),
		'password' => array('passwordEmpty' => array('rule' => 'notEmpty', 'message' => 'password is required'), 
							'passwordLength' => array('rule' => array('minLength', 8), 'message' => 'password should be at least 8 characters'),
							'passwordIdentical' => array('rule' => array('identicalPassword', 'confirmPassword'), 'message' => 'password should be the same')),
	);
	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
	}
	
	public function identicalPassword($password1, $password2) {
		if (!strcmp($this->data['User'][key($password1)], $this->data['User'][$password2])) {
			return 1;
		}
		return 0;
	}
}