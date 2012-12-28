<?php

	class MLP {
		private $learn_rate = 0.5;
		private $error_rate = 0.1;
		private $num_layers = 0;
		private $layers = array();
		private $values = array();
		private $output = array();
		private $weight = array();
		private $deltas = array();

		public function __construct($layers = null) {
			if ((!is_null($layers)) && (is_array($layers)))
				$this->create($layers);
		}

		private static function activation($value) {
			return round((1 / (1.0 + exp(-$value))), 4);
		}

		private static function derivate($value) {
			return round((exp(-$value) / pow((1.0 + exp(-$value)), 2)), 4);
		}

		public function setLR($value) {
			$this->learn_rate = $value;
		}

		public function setER($value) {
			$this->error_rate = $value;
		}

		public function create(array $layers) {
			$this->layers = $layers;
			$this->num_layers = count($layers);
			mt_srand(date('dmYHis'));
			for ($layer = 0; $layer < $this->num_layers; $layer++)
				for ($item = 0; $item <= $layers[$layer]; $item++) {
					if ($item < $layers[$layer]) {
						$this->values[$layer][$item] = 0.0;
						$this->output[$layer][$item] = 0.0;
						$this->deltas[$layer][$item] = 0.0;
					}
					if ($layer < ($this->num_layers - 1))
						for ($link = 0; $link < $layers[($layer + 1)]; $link++)
							$this->weight[$layer][$item][$link] = (mt_rand(-1, 1) * 0.1);
				}
		}

		public function destroy() {
			$this->num_layers = 0;
			$this->layers = array();
			$this->values = array();
			$this->output = array();
			$this->weight = array();
			$this->deltas = array();
		}

		public function save($file) {
			$this->clean();
			$data = array(
				'num_layers' => $this->num_layers,
				'layers' => $this->layers,
				'values' => $this->values,
				'output' => $this->output,
				'weight' => $this->weight,
				'deltas' => $this->deltas
			);
			return file_put_contents($file, serialize($data));
		}

		public function load($file) {
			$source = file_get_contents($file);
			if ($source) {
				$data = unserialize($source);
				$this->num_layers = $data['num_layers'];
				$this->layers = $data['layers'];
				$this->values = $data['values'];
				$this->output = $data['output'];
				$this->weight = $data['weight'];
				$this->deltas = $data['deltas'];
				return true;
			} else
				return false;
		}

		public function __toString() {
			$ret = 'layers ';
			$ret .= print_r($this->layers, true);
			$ret .= "\n";
			$ret .= 'values ';
			$ret .= print_r($this->values, true);
			$ret .= "\n";
			$ret .= 'output ';
			$ret .= print_r($this->output, true);
			$ret .= "\n";
			$ret .= 'weight ';
			$ret .= print_r($this->weight, true);
			$ret .= "\n";
			$ret .= 'deltas ';
			$ret .= print_r($this->deltas, true);
			return $ret;
		}

		private function clean() {
			//limpa os vetores auxiliares usados no cálculo da saída e no aprendizado da rede
			for ($layer = 0; $layer < $this->num_layers; $layer++)
				for ($item = 0; $item < $this->layers[$layer]; $item++) {
					$this->values[$layer][$item] = 0.0;
					$this->output[$layer][$item] = 0.0;
					$this->deltas[$layer][$item] = 0.0;
				}
		}

		public function work(array $input) {
			//verifica se o número de entradas fornecido é igual ao tamanho da entrada da rede
			if (count($input) != $this->layers[0])
				return false;
			//limpa os vetores de valores e de deltas
			$this->clean();
			//inicializa o vetor de valores com a entrada
			for ($item = 0; $item < $this->layers[0]; $item++) {
				$this->values[0][$item] = $input[$item];
				$this->output[0][$item] = $input[$item];
			}
			//calcula a saída da rede
			for ($layer = 0; $layer < ($this->num_layers - 1); $layer++) {
				//calcula para cada entrada o valor potencial do neurônio
				for ($input = 0; $input <= $this->layers[$layer]; $input++)
					for ($output = 0; $output < $this->layers[($layer + 1)]; $output++)
						if (isset($this->output[$layer][$input])) //entradas
							$this->values[($layer + 1)][$output] += round(($this->output[$layer][$input] * $this->weight[$layer][$input][$output]), 4);
						else //bias
							$this->values[($layer + 1)][$output] += round($this->weight[$layer][$input][$output], 4);
				//calcula a função de ativação para a saída
				for ($output = 0; $output < $this->layers[($layer + 1)]; $output++)
					$this->output[($layer + 1)][$output] = self::activation($this->values[($layer + 1)][$output]);
			}
			//retorna o valor da última camada (saída)
			return $this->output[($this->num_layers - 1)];
		}

		public function teach(array $tests, $epoch = 1000) {
			//verifica se os padrões de teste tem o número certo de entradas e saídas
			$valid = true;
			foreach ($tests as $test)
				if ((count($test['in']) != $this->layers[0]) || (count($test['out']) != $this->layers[($this->num_layers - 1)]))
					$valid = false;
			if (!$valid)
				return false;
			//laço principal do treinamento (etivo enquanto a taxa de erro for maior que a tolerância e existirem épocas disponíveis)
			do {
				//inicializa o acumulador da taxa de erro
				$error = 0.0;
				//embaralha os casos de teste
				shuffle($tests);
				//para cada caso de teste
				foreach ($tests as $test) {
					//repete o backpropagation até que a taxa de erro seja menor que a tolerância
					do {
						//executa o algoritmo de backpropagation para o caso de teste atual
						$bperr = $this->backpropagation($test);
						//se o erro do backpropagation é maior que o erro atual, atualiza o erro
						$error = max($error, $bperr);
						//decrementa o contador de épocas
						$epoch--;
					} while (($bperr > $this->error_rate) && ($epoch));
					echo 'error back: '.$bperr."\n";
					echo 'error rate: '.$error."\n";
					echo 'epochs last: '.$epoch."\n\n";
				}
			} while (($error > $this->error_rate) && ($epoch > 0));
		}

		public function backpropagation(array $test) {
			//garante que os vetores auxiliares estão limpos
			$this->clean();
			//calcula o resultado da rede para a entrada de teste
			$result = $this->work($test['in']);
			//acumulador do erro
			$error = 0.0;
			//se o resultado obtido é diferente do resultado esperado, propaga o erro e atualiza os pesos
			if ($result != $test['out']) {
				//calcula o delta da saída
				for ($item = 0; $item < $this->layers[($this->num_layers - 1)]; $item++) {
					//echo 'item: '.$item."\n";
					//echo '	esperado: '.$test['output'][$item]."\n";
					//echo '	obtido:   '.$result[$item]."\n";
					$this->deltas[($this->num_layers - 1)][$item] = round((($test['out'][$item] - $result[$item]) * self::derivate($this->values[($this->num_layers - 1)][$item])), 4);
					$error += pow(($test['out'][$item] - $result[$item]), 2);
				}
				$error /= 2;
				//se o erro na saída for maior que a tolerância, atualiza os pesos
				if ($error > $this->error_rate)
					for ($layer = ($this->num_layers - 1); $layer > 0; $layer--) {
						for ($input = 0; $input <= $this->layers[($layer - 1)]; $input++)
							for ($output = 0; $output < $this->layers[$layer]; $output++)
								if (isset($this->values[($layer - 1)][$input])) //entradas
									$this->weight[($layer - 1)][$input][$output] += round(($this->learn_rate * $this->deltas[$layer][$output] * $this->output[($layer - 1)][$input]), 4);
								else //bias
									$this->weight[($layer - 1)][$input][$output] += round(($this->learn_rate * $this->deltas[$layer][$output]), 4);
						if ($layer > 1)
							for ($input = 0; $input < $this->layers[($layer - 1)]; $input++) {
								for ($output = 0; $output < $this->layers[$layer]; $output++)
									$this->deltas[($layer - 1)][$input] += ($this->deltas[$layer][$output] * $this->weight[($layer - 1)][$input][$output]);
								$this->deltas[($layer - 1)][$input] *= self::derivate($this->values[($layer - 1)][$input]);
							}
					}
			}
			return round($error, 4);
		}

	}

?>
