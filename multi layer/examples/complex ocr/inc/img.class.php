<?php

	require_once 'printer.class.php';

	class IMG {
		private $img = null;
		public $data = array();
		private $w = 0;
		private $h = 0;
		private $minx = 0;
		private $miny = 0;
		private $maxx = 0;
		private $maxy = 0;
		public $threshold = -1;

		const RADIUS = 3;
		const TOLERANCE = 10;

		public function __construct($file = '') {
			if ($file)
				$this->load($file);
		}

		public function __destruct() {
			$this->unload();
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

				$this->minx = $this->w;
				$this->miny = $this->h;

				$this->data = array();
				for ($x = 0; $x < $this->w; $x++)
					for ($y = 0; $y < $this->h; $y++)
						$this->data[$x][$y] = imagecolorat($this->img, $x, $y);
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
			$this->minx = 0;
			$this->miny = 0;
			$this->maxx = 0;
			$this->maxy = 0;
		}

		public function findborders() {
			if (!is_null($this->img))
				for ($x = 0; $x < $this->w; $x++)
					for ($y = 0; $y < $this->h; $y++)
						if (imagecolorat($this->img, $x, $y) <= $this->threshold) {
							$this->minx = min($this->minx, $x);
							$this->miny = min($this->miny, $y);
							$this->maxx = max($this->maxx, $x);
							$this->maxy = max($this->maxy, $y);
						}
		}

		private function extractblock(array $block) {
			$minx = $this->w;
			$miny = $this->h;
			$maxx = 0;
			$maxy = 0;
			for ($x = 0; $x < $this->w; $x++)
				for ($y = 0; $y < $this->h; $y++)
					if ($block[$x][$y]) {
						$minx = min($minx, $x);
						$miny = min($miny, $y);
						$maxx = max($maxx, $x);
						$maxy = max($maxy, $y);
					}
			if (($maxx - $minx) > 10) {
				PRINTER::png($block, $this->w, $this->h, './erro.png');
				return array();
			}
			$padx = floor(((10 - ($maxx - $minx)) / 2));
			if ($padx < 0) {
				$maxx -= $padx;
				$padx = 0;
			}
			if (($maxy - $miny) > 10) {
				PRINTER::png($block, $this->w, $this->h, './erro.png');
				return array();
			}
			$pady = floor(((10 - ($maxy - $miny)) / 2));
			if ($pady < 0) {
				$maxy -= $pady;
				$pady = 0;
			}
			$new = array_fill(0, 10, array_fill(0, 10, false));
			for ($x = $minx; $x <= $maxx; $x++)
				for ($y = $miny; $y <= $maxy; $y++)
					$new[($padx + ($x - $minx))][($pady + ($y - $miny))] = $block[$x][$y];
			return $new;
		}

		private function checkblock(array $block) {
			$w = count($block);
			if (isset($block[0])) {
				$h = count($block[0]);
				if (($w != 10) || ($h != 10))
					return false;
				$c = 0;
				for ($x = 0; $x < $w; $x++)
					for ($y = 0; $y < $h; $y++)
						if ($block[$x][$y])
							$c++;
				return (round(($c * 100) / ($w * $h)) >= self::TOLERANCE);
			} else
				return false;
		}

		private function discoverblock($x, $y, array &$check, array &$block) {
			$minx = max(0, ($x - self::RADIUS));
			$maxx = min($this->w, ($x + self::RADIUS));
			$miny = max(0, ($y - self::RADIUS));
			$maxy = min($this->h, ($y + self::RADIUS));
			for ($x = $minx; $x < $maxx; $x++)
				for ($y = $miny; $y < $maxy; $y++)
					if (imagecolorat($this->img, $x, $y) <= $this->threshold)
						if (!$check[$x][$y]) {
							$check[$x][$y] = true;
							$block[$x][$y] = true;
							$this->discoverblock($x, $y, $check, $block);
						}
		}

		public function detectblocks() {
			$blocks = array();
			$lastx = 0;
			$lasty = 0;
			$check = array_fill(0, $this->w, array_fill(0, $this->h, false));
			for ($y = $this->miny; $y < $this->maxy; $y += 10)
				for ($x = $this->minx; $x < $this->maxx; $x++) {
					$flag = false;
					$i = 0;
					if (($y + 10) > $this->h)
						$inc = ($this->h - $y);
					else
						$inc = 10;
					while (($i < $inc) && (!$flag)) {
						if (imagecolorat($this->img, $x, ($y + $i)) <= $this->threshold) {
							if (!$check[$x][($y + $i)]) {
								$check[$x][($y + $i)] = true;
								$block = array_fill(0, $this->w, array_fill(0, $this->h, false));
								$block[$x][($y + $i)] = true;
								$this->discoverblock($x, ($y + $i), $check, $block);
								$tmp = $this->extractblock($block);
								if ($this->checkblock($tmp)) {
									if (abs($x - $lastx) > 5)
										$blocks[] = 'space';
									$lastx = ($x + count($tmp));
									if (abs($y - $lasty) >= 10)
										$blocks[] = 'newline';
									$lasty = $y;
									$blocks[] = $tmp;
									$flag = true;
									$x += 5;
								}
							}
						}
						$i++;
					}
				}
			return $blocks;
		}

		public function w() {
			return $this->w;
		}

		public function h() {
			return $this->h;
		}

		public function minx() {
			return $this->minx;
		}

		public function miny() {
			return $this->miny;
		}

		public function maxx() {
			return $this->maxx;
		}

		public function maxy() {
			return $this->maxy;
		}

	}

?>
