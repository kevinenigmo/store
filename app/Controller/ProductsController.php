<?php

class ProductsController extends AppController {
	
	public $helpers = array('Js' => array('Jquery'));
	public $components = array('Paginator', 'RequestHandler');
	
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
					$this->Session->setFlash(__('Please choose a image file'), 'default', array('class' => 'container alert alert-danger'));
					return;
				}
			}
			else {
				$this->Session->setFlash(__('Please choose a image file'), 'default', array('class' => 'container alert alert-danger'));
				return;
			}
			
			$this->Product->create();
			$this->request->data['Product']['user_id'] = $this->Auth->user('id');
			$this->request->data['Product']['brand_id']++;
			
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash(__('The item is created'), 'default', array('class' => 'container alert alert-info'));
				$product = $this->Product->find('first', array('order' => array('Product.id' => 'desc')));
				if ($product['Product']['image']) {
					$dir = new Folder(WWW_ROOT . 'img/products/' . $product['Product']['id'], true, 0755);
					move_uploaded_file($file['tmp_name'], $dir->path . '/' .$file['name']);
					
					$this->imageresize($dir->path . '/' .$file['name'], $dir->path . '/small_' .$file['name'], 200, 200);
				}
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Unable to create the item'), 'default', array('class' => 'container alert alert-danger'));
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
				$this->Session->setFlash(__('Item is edited'), 'default', array('class' => 'container alert alert-info'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Unable to edit the item'), 'default', array('class' => 'container alert alert-danger'));
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
	
	public function imageresize($imagePath, $thumb_path, $destinationWidth, $destinationHeight) {
		// The file has to exist to be resized
		if (file_exists($imagePath)) {
			// Gather some info about the image
			$imageInfo = getimagesize($imagePath);
	
			// Find the intial size of the image
			$sourceWidth = $imageInfo[0];
			$sourceHeight = $imageInfo[1];
	
			if ($sourceWidth > $sourceHeight) {
				$temp = $destinationWidth;
				$destinationWidth = $destinationHeight;
				$destinationHeight = $temp;
			}
	
			// Find the mime type of the image
			$mimeType = $imageInfo['mime'];
	
			// Create the destination for the new image
			$destination = imagecreatetruecolor($destinationWidth, $destinationHeight);
	
			// Now determine what kind of image it is and resize it appropriately
			if ($mimeType == 'image/jpeg' || $mimeType == 'image/jpg' || $mimeType == 'image/pjpeg') {
				$source = imagecreatefromjpeg($imagePath);
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagejpeg($destination, $thumb_path);
			} else if ($mimeType == 'image/gif') {
				$source = imagecreatefromgif($imagePath);
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagegif($destination, $thumb_path);
			} else if ($mimeType == 'image/png' || $mimeType == 'image/x-png') {
				$source = imagecreatefrompng($imagePath);
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagepng($destination, $thumb_path);
			} else {
				$this->Session->setFlash(__('This image type is not supported.'), 'default', array('class' => 'container alert alert-danger'));
			}
	
			// Free up memory
			imagedestroy($source);
			imagedestroy($destination);
		}
	}
}