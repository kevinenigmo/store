<div class="container">

<div class="page-header">
<h1>商品一覧</h1>
</div>

<div><?php echo $this->Paginator->counter('{:count}件中{:start}~{:end}を表示中') ?></div>
<div class="pagination">
	<?php if ($this->Paginator->hasPrev()) {echo $this->Paginator->prev('<<Prev', null, null, array('class' => 'disabled'));} ?>
	<?php echo $this->Paginator->first('First') ?>
	<?php echo $this->Paginator->numbers(array('modulus' => 4)) ?>
	<?php echo $this->Paginator->last('Last') ?>
	<?php if ($this->Paginator->hasNext()) {echo $this->Paginator->next('Next>>', null, null, array('class' => 'disabled'));} ?>
</div>

<table class="table table-striped">
	
	<tr>
		<th>sort</th>
		<th><?php echo $this->Paginator->sort('price', 'Price') ?></th>
		<th><?php echo $this->Paginator->sort('Brand.brand_name', 'Brand') ?></th>
	</tr>
	
</table>

<?php $c = 0;
	foreach ($products as $product):
	if ($c % 4 == 0) { 
?>
	<div class="row" id="equalize" style="padding: 10px">
	<?php }; ?>
	
	<div class="col-sm-6 col-md-3">
	    <div class="thumbnail">
	      <?php if ($product['Product']['image']) { echo $this->Html->image('products/' . $product['Product']['id'] .'/small_'. $product['Product']['image']);} ?>
	      <div class="caption">
	        <h4><?php echo $this->Html->link($product['Product']['item_title'], array('controller' => 'products', 'action' => 'detail', $product['Product']['id'])) ?></h4>
	        <p><?php echo $this->Text->truncate($product['Product']['item_comment'], 100, array('ellipsis' => '..')) ?></p>
	        <p><?php echo $this->Number->currency($product['Product']['price'], 'JPY') ?></p>
	        <p><?php echo $product['User']['username'] ?></p>
	        <?php if($userData['id'] === $product['User']['id'] || $userData['admin'] ==='1'): ?>
			<p><?php echo $this->Html->link('Edit', array('action' => 'edit', $product['Product']['id'])) ?></p>
			<?php endif ?>
	      </div>
	    </div>
  	</div>
	<?php if ($c % 4 == 3) { ?>
	</div>
	<?php }; 
		$c++;
	?>

<?php endforeach ?>
<?php unset($product) ?>

<div class="pagination">
	<?php if ($this->Paginator->hasPrev()) {echo $this->Paginator->prev('<<Prev', null, null, array('class' => 'disabled'));} ?>
	<?php echo $this->Paginator->first('First') ?>
	<?php echo $this->Paginator->numbers(array('modulus' => 4)) ?>
	<?php echo $this->Paginator->last('Last') ?>
	<?php if ($this->Paginator->hasNext()) {echo $this->Paginator->next('Next>>', null, null, array('class' => 'disabled'));} ?>
</div>

</div>