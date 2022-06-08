<h3><?php echo $helloWorld ?></h3>
<strong>List Book</strong>
<ul>
	<?php foreach ($book as $key => $value): ?>
		<li><?php echo $value->book_name ?></li>
	<?php endforeach ?>
</ul>