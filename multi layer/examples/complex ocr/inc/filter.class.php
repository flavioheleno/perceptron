<?php

	class FILTER {

		private static function normalize(&$item, $key, $threshold) {
			if ($item <= $threshold)
				$item = false;
			else
				$item = true;
		}

		public static function color(array &$input, $threshold) {
			array_walk_recursive($input, 'FILTER::normalize', $threshold);
		}

	}

?>
