<?php

	require_once './mlp.class.php';
	require_once './img.class.php';

	if ($argc < 2) {
		if (!file_exists('./color.mlpt')) {
			$name = array(
				'red',
				'green',
				'blue'
			);
			for ($i = 0; $i < 3; $i++)
				for ($j = 1; $j < 15; $j++) {
					$file = './img/'.$name[$i].($j < 10 ? '0'.$j : $j).'.jpg';
					echo 'loading '.$file."\n";
					$output = array(0.0, 0.0, 0.0);
					$output[$i] = 1.0;
					$img = new IMG($file);
					$training[] = array(
						'in' => $img->data,
						'out' => $output
					);
					unset($img);
				}
			file_put_contents('./color.mlpt', serialize($training));
		} else {
			$source = file_get_contents('./color.mlpt');
			if ($source)
				$training = unserialize($source);
			else
				die('Training data not loaded'."\n");
		}
		if ($training) {
			$mlp = new MLP();
			if (file_exists('./color.mlp'))
				$mlp->load('./color.mlp');
			else
				$mlp->create(array(25, 14, 3));
			$mlp->teach($training, 100000);
			$mlp->save('./color.mlp');
		}
	} else {
		if ($argv[2] == 'help')
			die('usage: php -f '.$_SERVER['SCRIPT_NAME'].' /path/to/image [classification]'."\n");
		if (!file_exists($argv[1]))
			die('file not found '.$argv[1]);
		else {
			$img = new IMG($argv[1]);
			$mlp = new MLP();
			if (file_exists('./color.mlp'))
				$mlp->load('./color.mlp');
			else
				$mlp->create(array(25, 14, 3));

			if ($argc == 3) {
				$output = array(0.0, 0.0, 0.0);
				$output[intval($argv[2])] = 1.0;
				$test = array(
					array(
						'in' => $img->data,
						'out' => $output
					)
				);
				$mlp->teach($test);
				$mlp->save('./color.mlp');
			} else {
				$result = $mlp->work($img->data);
				echo 'resultado: ';
				print_r($result);
			}
		}
	}

?>
