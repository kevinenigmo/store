<?php

class ProductsController extends AppController {
	
	public $helpers = array('Js' => array('Jquery'));
	public $components = array('Paginator', 'My');
	
	public $paginate = array('limit' => 20, 'order' => array('Product.id' => 'asc'));
	
	public function beforeFilter() {
		$this->Auth->allow('index', 'detail', 'save');
	}
	
	public function index() {
		$this->Paginator->settings = $this->paginate;
		
		$this->set('products', $this->Paginator->paginate('Product'));
	}
	
	public function detail($id = NULL) {
		if (!$id) {
			throw new NotFoundException(__('This item does not exist'));
		}
		
		$product = $this->Product->findById($id);
		if (!$product) {
			throw new NotFoundException(__('This item does not exist'));
		}
		
		$comments = $this->Product->Comment->findAllByProductId($product['Product']['id']);
		
		$this->set('product', $product);
		$this->set('comments', $comments);
	}
	
	public function add() {
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		
		$brands = array();
		$brandsTable = $this->Product->Brand->find('all');
		foreach ($brandsTable as $brandsItem) {
			$brands[] = $brandsItem['Brand']['brand_name'];
		}
		$this->set('brands', $brands);
		if ($this->request->is('post')) {
			if (!empty($this->request->data['Product']['file']['name'])) {
				$file = $this->request->data['Product']['file'];
				$extension = substr(strtolower(strchr($file['name'], '.')), 1);
				$array_extension = array('jpg', 'jpeg', 'gif', 'png');
				
				if (in_array($extension, $array_extension)) {
					$this->request->data['Product']['image'] = $file['name'];
				}
				else {
					$this->setFlash(__('Please choose a image file'), 'danger');
					return;
				}
			}
			else {
				$this->setFlash(__('Please choose a image file'), 'danger');
				return;
			}
			
			$this->Product->create();
			$this->request->data['Product']['user_id'] = $this->Auth->user('id');
			$this->request->data['Product']['brand_id']++;
			
			if ($this->Product->save($this->request->data)) {
				$this->setFlash(__('The item is created'), 'info');
				$product = $this->Product->find('first', array('order' => array('Product.id' => 'desc')));
				if ($product['Product']['image']) {
					$dir = new Folder(WWW_ROOT . 'img/products/' . $product['Product']['id'], true, 0755);
					move_uploaded_file($file['tmp_name'], $dir->path . '/' .$file['name']);
					
					$this->My->imageresize($dir->path . '/' .$file['name'], $dir->path . '/small_' .$file['name'], 200, 200);
				}
				return $this->redirect(array('action' => 'index'));
			}
			$this->setFlash(__('Unable to create the item'), 'danger');
		}
	}
	
	public function edit($id = NULL) {
		if (!$id) {
			throw new NotFoundException(__('This item does not exist'));
		}
		
		$product = $this->Product->findById($id);
		if (!$product) {
			throw new NotFoundException(__('This item does not exist'));
		}
		
		$brandsTable = $this->Product->Brand->find('all');
		foreach ($brandsTable as $brandsItem) {
			$brands[] = $brandsItem['Brand']['brand_name'];
		}
		$this->set('brands', $brands);
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Product->id = $id;
			$this->request->data['Product']['brand_id']++;
			if ($this->Product->save($this->request->data)) {
				$this->setFlash(__('Item is edited'), 'info');
				return $this->redirect(array('action' => 'index'));
			}
			$this->setFlash(__('Unable to edit the item'), 'danger');
		}
		
		if (!$this->request->data) {
			$this->request->data = $product;
		}
		
		$this->set('product', $product);
	}
	
	public function isAuthorized($user) {
		if ($this->action === 'add') {
			return 1;
		}
		
		if ($this->action === 'edit') {
			$productId = $this->request->params['pass'][0];
			if ($this->Product->isOwnedBy($productId, $user['id'])) {
				return 1;
			}
		}
		
		return parent::isAuthorized($user);
	}
}