<?php

	require_once './mlp.class.php';

	$t = array(
		array(
			'in' => array(1, 1),
			'out' => array(0)
		),
		array(
			'in' => array(1, 0),
			'out' => array(1)
		),
		array(
			'in' => array(0, 1),
			'out' => array(1)
		),
		array(
			'in' => array(0, 0),
			'out' => array(0)
		)
	);

	$mlp = new MLP();
	if (file_exists('./xor.mlp')) {
		echo 'carregando a rede a partir de xor.mlp'."\n";
		$mlp->load('./xor.mlp');
	} else {
		echo 'criando uma rede nova'."\n";
		$mlp->create(array(2, 2, 1));
		$mlp->teach($t, 500000);
		$mlp->save('./xor.mlp');
	}
	echo $mlp;
	foreach ($t as $item) {
		echo 'input: ';
		print_r($item['in']);
		echo 'output: ';
		$r = $mlp->work($item['in']);
		echo round($r[0]).' ('.$r[0].')';
		echo "\n";
	}
?>
