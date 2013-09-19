<div class="container">

<div class="page-header">
<h1>商品登録</h1>
</div>

<p><?php echo $this->Html->link('リストに戻る', array('controller' => 'products', 'action' => 'index')) ?></p>

<?php echo $this->Form->create('Product', array(
    'class' => 'form-horizontal',
	'enctype' => 'multipart/form-data',
    'inputDefaults' => array(
        'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
        'div' => array('class' => 'form-group'),
      	'label' => array('class' => 'col-lg-2 control-label'),
      	'between' => '<div class="col-lg-10">',
       	'after' => '</div>',
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-warning')),
    )));?>
    
<fieldset>
<?php echo $this->Form->input('item_title', array(
	'class' => 'form-control'
    )); 
?>
    
<?php echo $this->Form->input('item_comment', array(
    'class' => 'form-control',
    'rows' => '5',
    'style' => 'resize:none'
    )); 
?>

<?php 
echo $this->Form->input('brand_id', array(
	'class' => 'form-control',
 	'type' => 'select',
	'options' => $brands
    )); 
?>

<?php 
echo $this->Form->input('price', array(
	'class' => 'form-control',
    )); 
?>

<?php echo $this->Form->input('release_date', array(
 		'timeFormat' => 'null',
		'minYear' => '1930',
		'maxYear' => '2030'
    )); 
?>

<?php 
echo $this->Form->input('stock', array(
	'class' => 'form-control',
    )); 
?>

<?php 
echo $this->Form->input('file', array(
		'class' => 'form-control',
		'type' => 'file'
));

?>

</fieldset>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
 </div>
 
<?php echo $this->Form->end(); ?>
</div>