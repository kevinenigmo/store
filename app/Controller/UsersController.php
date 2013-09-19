<?php

class UsersController extends AppController {
	
	public function beforeFilter() {
		$this->Auth->allow('create', 'login', 'signoff', 'edit');
	}
	
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			}
			$this->setFlash(__('username or password not exists'), 'danger');
		}
	}
	
	public function signoff() {
		return $this->redirect($this->Auth->logout());
	}
	
	public function create() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->setFlash(__('User is created'), 'info');
				return $this->redirect(array('action' => 'login'));
			}
			$this->setFlash('Unable to create user', 'danger');
		}
	}
	
	public function edit($id = NULL) {
		if (!$id) {
			throw new NotFoundException(__('User does not exist'));
		}
		
		$user = $this->User->findById($id);
		if (!$user) {
			throw new NotFoundException(__('User does not exist'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $id;
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('User is edited'), 'default', array('class' => 'container alert alert-info'));
				return $this->redirect(array('controller' => 'products', 'action' => 'index'));
			}
			$this->setFlash(__('Unable to edit user'), 'danger');
		}
		
		if (!$this->request->data) {
			$this->request->data = $user;
		}
	}
	
	public function view() {
		$this->set('users', $this->User->find('all'));
	}
	
	public function isAuthorized($user) {
		if ($this->action === 'edit') {
			$userId = $this->request->params['pass'][0];
			if ($userId === $user) {
				return 1;
			}
		}
	
		return parent::isAuthorized($user);
	}
}