<?php

	class IMG {

		public function __construct($file) {
			$type = substr($file, (strrpos($file, '.') + 1));
			switch ($type) {
				case 'jpg':
				case 'jpeg':
					$img = imagecreatefromjpeg($file);
					break;
				case 'png':
					$img = imagecreatefrompng($file);
					break;
				case 'gif':
					$img = imagecreatefromgif($file);
					break;
				default:
					$img = false;
			}
			if (!$img)
				die('Imagem inválida!'."\n");

			$w = imagesx($img);
			$h = imagesy($img);

			$qw = round($w / 4);
			$qh = round($h / 4);

			$colors = array();
			for ($x = 0; $x < $w; $x++)
				for ($y = 0; $y < $h; $y++) {
					$dx = round($x / $qw);
					$dy = round($y / $qh);
					if (!isset($colors[$dx][$dy]))
						$colors[$dx][$dy] = imagecolorat($img, $x, $y);
					else {
						$colors[$dx][$dy] += imagecolorat($img, $x, $y);
						$colors[$dx][$dy] /= 2;
					}
				}
			imagedestroy($img);

			$this->data = array();
			foreach ($colors as &$item)
				foreach ($item as &$value)
					$this->data[] = round($value);
		}

		public function data() {
			return $this->data;
		}

	}

?>
