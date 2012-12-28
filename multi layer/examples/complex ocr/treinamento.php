<?php

	require_once __DIR__.'/inc/char.class.php';
	require_once __DIR__.'/inc/mlp.class.php';

	$char = new CHAR();
	$char->threshold = 0xC0C0C0;
	$minusculas = scandir(__DIR__.'/treinamento/minusculas/');
	$maiusculas = scandir(__DIR__.'/treinamento/maiusculas/');
	$numeros = scandir(__DIR__.'/treinamento/numeros/');

	$t = array();

	foreach ($minusculas as $imagem)
		if (!is_dir(__DIR__.'/treinamento/minusculas/'.$imagem)) {
			$char->load(__DIR__.'/treinamento/minusculas/'.$imagem);
			$t[] = array(
				'in' => $char->data(),
				'out' => array_fill(0, 62, 0)
			);
			$char->unload();
		}

	foreach ($maiusculas as $imagem)
		if (!is_dir(__DIR__.'/treinamento/maiusculas/'.$imagem)) {
			$char->load(__DIR__.'/treinamento/maiusculas/'.$imagem);
			$t[] = array(
				'in' => $char->data(),
				'out' => array_fill(0, 62, 0)
			);
			$char->unload();
		}

	foreach ($numeros as $imagem)
		if (!is_dir(__DIR__.'/treinamento/numeros/'.$imagem)) {
			$char->load(__DIR__.'/treinamento/numeros/'.$imagem);
			$t[] = array(
				'in' => $char->data(),
				'out' => array_fill(0, 62, 0)
			);
			$char->unload();
		}

	for ($i = 0; $i < 62; $i++)
		$t[$i]['out'][$i] = 1;

	$single = new MLP();
	if (file_exists('./mlp/single.mlp')) {
		echo 'carregando a rede a partir de single.mlp'."\n";
		$single->load('./mlp/single.mlp');
	} else {
		echo 'criando uma rede nova'."\n";
		$single->create(array(100, 70, 62));
		$single->teach($t, 500000);
		$single->save('./mlp/single.mlp');
	}
?>
