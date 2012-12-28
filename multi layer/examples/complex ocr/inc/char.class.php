<?php

	class CHAR {
		private $img = null;
		private $data = array();
		private $w = 0;
		private $h = 0;
		public $threshold = -1;

		public function __construct($file = '') {
			if ($file)
				$this->load($file);
		}

		public function __destruct() {
			$this->unload();
		}

		public function fromblock(array $block) {
			$this->w = count($block);
			$this->h = count($block[0]);
			for ($x = 0; $x < $this->w; $x++)
				for ($y = 0; $y < $this->h; $y++)
					$this->data[] = $block[$x][$y];
		}

		public function load($file) {
			if (is_null($this->img)) {
				$type = substr($file, (strrpos($file, '.') + 1));
				switch ($type) {
					case 'jpg':
					case 'jpeg':
						$this->img = imagecreatefromjpeg($file);
						break;
					case 'png':
						$this->img = imagecreatefrompng($file);
						break;
					case 'gif':
						$this->img = imagecreatefromgif($file);
						break;
					default:
						die('Imagem inválida!'."\n");
				}

				$this->w = imagesx($this->img);
				$this->h = imagesy($this->img);

				$this->data = array();
				for ($x = 0; $x < $this->w; $x++)
					for ($y = 0; $y < $this->h; $y++)
						if (imagecolorat($this->img, $x, $y) < $this->threshold)
							$this->data[] = true;
						else
							$this->data[] = false;
			}
		}

		public function unload() {
			if (!is_null($this->img)) {
				imagedestroy($this->img);
				$this->img = null;
			}
			$this->data = array();
			$this->w = 0;
			$this->h = 0;
		}

		public function w() {
			return $this->w;
		}

		public function h() {
			return $this->h;
		}

		public function data() {
			return $this->data;
		}

	}

?>
