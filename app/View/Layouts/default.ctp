<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		//echo $this->Html->css('cake.generic');
		
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-theme.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
		
	?>
</head>

<body>

<div class="container">
	<div class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php echo $this->Html->link('Web Store', array('controller' => 'products', 'action' => 'index'), array('class' => 'navbar-brand')) ?>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <?php if (!$userData): ?>
            <li><?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')) ?></li>
          <?php endif ?>  
            <li><?php echo $this->Html->link('商品登録', array('controller' => 'products', 'action' => 'add')) ?></li>
          <?php if ($userData['admin'] === '0'): ?>
            <li><?php echo $this->Html->link('ユーザー編集', array('controller' => 'users', 'action' => 'edit', $userData['id'])) ?></li>
          <?php endif ?>
          <?php if ($userData['admin'] === '1'): ?>
            <li><?php echo $this->Html->link('ユーザー一覧', array('controller' => 'users', 'action' => 'view')) ?></li>
          <?php endif ?>   
          </ul>
          <?php if ($userData): ?>
          <ul class="nav navbar-nav navbar-right">
            <li><?php echo $this->Html->link('You are '. $userData['username'] . '. Sign off', array('controller' => 'users', 'action' => 'signoff')) ?></li>
          </ul>
          <?php endif ?>
        </div><!--/.nav-collapse -->
	</div>
</div>

	<div id="container">
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
	<?php echo $this->Html->script('jquery-2.0.3.min'); ?>
	<?php echo $this->Html->script('bootstrap.min'); ?>
	<?php echo $this->Html->script('height.min'); ?>

	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
