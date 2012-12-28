<?php
	require_once __DIR__.'/inc/img.class.php';
	require_once __DIR__.'/inc/char.class.php';
	require_once __DIR__.'/inc/filter.class.php';
	require_once __DIR__.'/inc/mlp.class.php';
	require_once __DIR__.'/inc/printer.class.php';

	$img = new IMG('./img/texto.png');
	$img->threshold = 0xC0C0C0;
	FILTER::color($img->data, 0xC0C0C0);
	$img->findborders();
	$blocks = $img->detectblocks();

	$map = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	$single = new MLP();
	$single->load('./mlp/single.mlp');

	echo 'encontrados '.count($blocks).' elementos (caracteres/números/espaços/quebras de linha)'."\n";
	$char = new CHAR();
	$c = 0;
	foreach ($blocks as $block) {
		if (is_array($block)) {
			PRINTER::png($block, 10, 10, './print/'.$c++.'.png');
			$char->fromblock($block);
			$value = $single->work($char->data());
			arsort($value);
			echo $map[key($value)];
			$char->unload();
		} else
			switch ($block) {
				case 'newline':
					echo "\n";
					break;
				case 'space':
					echo ' ';
					break;
			}
	}
	echo "\n";
?>
