<?php

	class PRINTER {

		public static function png(array $data, $w, $h, $file = './print.png') {
			$img = imagecreatetruecolor($w, $h);
			$black = imagecolorallocate($img, 0x00, 0x00, 0x00);
			$white = imagecolorallocate($img, 0xFF, 0xFF, 0xFF);
			for ($x = 0; $x < $w; $x++)
				for ($y = 0; $y < $h; $y++)
					if ($data[$x][$y])
						imagesetpixel($img, $x, $y, $black);
					else
						imagesetpixel($img, $x, $y, $white);
			imagecolordeallocate($img, $black);
			imagecolordeallocate($img, $white);
			imagepng($img, $file);
			imagedestroy($img);
		}

	}

?>
