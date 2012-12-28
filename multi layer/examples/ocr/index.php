<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
		<title>Web-based Optical Character Recognition</title>
		<style type="text/css">
			body {
				font-family: Trebuchet MS, Tahoma, Verdana, Arial, sans-serif;
				font-size: small;
			}
		</style>
	</head>
	<body>
		<div style="text-align: center">
			<h1>Web-based Optical Character Recognition</h1>
			<form action="" method="post" enctype="application/x-www-form-urlencoded" id="frmOCR">
				<table align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #000">
					<tr>
						<td><input type="checkbox" id="px0" name="px0" value="1" <?php echo (isset($_POST['px0']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px1" name="px1" value="1" <?php echo (isset($_POST['px1']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px2" name="px2" value="1" <?php echo (isset($_POST['px2']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px3" name="px3" value="1" <?php echo (isset($_POST['px3']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px4" name="px4" value="1" <?php echo (isset($_POST['px4']) ? 'checked="checked"' : ''); ?> /></td>
					</tr>
					<tr>
						<td><input type="checkbox" id="px5" name="px5" value="1" <?php echo (isset($_POST['px5']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px6" name="px6" value="1" <?php echo (isset($_POST['px6']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px7" name="px7" value="1" <?php echo (isset($_POST['px7']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px8" name="px8" value="1" <?php echo (isset($_POST['px8']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px9" name="px9" value="1" <?php echo (isset($_POST['px9']) ? 'checked="checked"' : ''); ?> /></td>
					</tr>
					<tr>
						<td><input type="checkbox" id="px10" name="px10" value="1" <?php echo (isset($_POST['px10']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px11" name="px11" value="1" <?php echo (isset($_POST['px11']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px12" name="px12" value="1" <?php echo (isset($_POST['px12']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px13" name="px13" value="1" <?php echo (isset($_POST['px13']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px14" name="px14" value="1" <?php echo (isset($_POST['px14']) ? 'checked="checked"' : ''); ?> /></td>
					</tr>
					<tr>
						<td><input type="checkbox" id="px15" name="px15" value="1" <?php echo (isset($_POST['px15']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px16" name="px16" value="1" <?php echo (isset($_POST['px16']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px17" name="px17" value="1" <?php echo (isset($_POST['px17']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px18" name="px18" value="1" <?php echo (isset($_POST['px18']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px19" name="px19" value="1" <?php echo (isset($_POST['px19']) ? 'checked="checked"' : ''); ?> /></td>
					</tr>
					<tr>
						<td><input type="checkbox" id="px20" name="px20" value="1" <?php echo (isset($_POST['px20']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px21" name="px21" value="1" <?php echo (isset($_POST['px21']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px22" name="px22" value="1" <?php echo (isset($_POST['px22']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px23" name="px23" value="1" <?php echo (isset($_POST['px23']) ? 'checked="checked"' : ''); ?> /></td>
						<td><input type="checkbox" id="px24" name="px24" value="1" <?php echo (isset($_POST['px24']) ? 'checked="checked"' : ''); ?> /></td>
					</tr>
				</table>
				<input type="submit" id="btnOCR" name="btnOCR" value="OCR" />
			</form>
<?php
	if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
		$ocr = array();
		for ($i = 0; $i < 25; $i++)
			if (isset($_POST['px'.$i]))
				$ocr[] = intval($_POST['px'.$i]);
			else
				$ocr[] = 0;

		require_once './mlp.class.php';
		$l = 'ABCDEFGHIJKLMNOPQRSTUVXYWZ';
		$mlp = new MLP();
		if (file_exists('./ocr.mlp'))
			$mlp->load('./ocr.mlp');
		else
			die('ocr.mlp not found');

		$ret = $mlp->work($ocr);
		$index = -1;
		$max = 0.0;
		for ($i = 0; $i < 26; $i++)
			if ($max < $ret[$i]) {
				$max = $ret[$i];
				$index = $l[$i];
			} else if ($max == $ret[$i])
				$index .= ', '.$l[$i];
		echo '			<fieldset style="border: 0; border-top: 1px dashed #000; margin-top: 20px">'."\n";
		echo '				<legend style="font-weight: bold">Resultado</legend>'."\n";
		echo '				A sa&iacute;da da rede neural representa:<br />'."\n";
		echo '				<h1>'.$index.'</h1>'."\n";
		echo '			</fieldset>'."\n";
	
	}
?>
		</div>
		<div style="margin-top: 50px; text-align: justify">
			<fieldset style="border: 0; border-top: 1px dotted #000">
				<legend style="font-weight: bold">Alunos respons&aacute;veis</legend>
				<ul>
					<li>Fl&aacute;vio Heleno - 5890027</li>
					<li>Vitor Utino - 5890330</li>
				</ul>
			</fieldset>
			<fieldset style="border: 0; border-top: 1px dotted #000">
				<legend style="font-weight: bold">Instru&ccedil;&otilde;es</legend>
				Desenhe uma letra (A-Z) mai&uacute;scula usando as checkboxes acima e em seguida clique em OCR para ver o resultado da rede neural.
			</fieldset>
			<fieldset style="border: 0; border-top: 1px dotted #000">
				<legend style="font-weight: bold">Funcionamento</legend>
				Cada checkbox representa um pixel, e cada pixel ser&aacute; atribu&iacute;do a uma entrada distinta de um multilayer perceptron. O resultado da rede neural &eacute; uma das 26 letras do alfabeto latino.
			</fieldset>
			<fieldset style="border: 0; border-top: 1px dotted #000">
				<legend style="font-weight: bold">Padr&otilde;es</legend>
				Abaixo os padr&otilde;es de treinamento usados na rede:
				<table align="center" cellspacing="0">
					<tr>
						<td style="border-bottom: 1px solid #000; font-weight: bold">A</td>
						<td style="border-bottom: 1px solid #000">
							11111<br />
							10001<br />
							11111<br />
							10001<br />
							10001
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">B</td>
						<td style="border-bottom: 1px solid #000">
							11110<br />
							10001<br />
							11110<br />
							10001<br />
							11110
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">C</td>
						<td style="border-bottom: 1px solid #000">
							01111<br />
							10000<br />
							10000<br />
							10000<br />
							01111
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">D</td>
						<td style="border-bottom: 1px solid #000">
							11110<br />
							10001<br />
							10001<br />
							10001<br />
							11110
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">E</td>
						<td style="border-bottom: 1px solid #000">
							11111<br />
							10000<br />
							11110<br />
							10000<br />
							11111
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">F</td>
						<td style="border-bottom: 1px solid #000">
							11111<br />
							10000<br />
							11110<br />
							10000<br />
							10000
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">G</td>
						<td style="border-bottom: 1px solid #000">
							01111<br />
							10000<br />
							10011<br />
							10001<br />
							01110
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">H</td>
						<td style="border-bottom: 1px solid #000">
							10001<br />
							10001<br />
							11111<br />
							10001<br />
							10001
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">I</td>
						<td style="border-bottom: 1px solid #000">
							01110<br />
							00100<br />
							00100<br />
							00100<br />
							01110
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">J</td>
						<td style="border-bottom: 1px solid #000">
							01110<br />
							00100<br />
							00100<br />
							10100<br />
							01100
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">K</td>
						<td style="border-bottom: 1px solid #000">
							10010<br />
							10100<br />
							11000<br />
							10100<br />
							10010
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">L</td>
						<td style="border-bottom: 1px solid #000">
							10000<br />
							10000<br />
							10000<br />
							10000<br />
							11111
						</td>
						<td>&nbsp;</td>
						<td style="border-bottom: 1px solid #000; font-weight: bold">M</td>
						<td style="border-bottom: 1px solid #000">
							10001<br />
							11011<br />
							10101<br />
							10001<br />
							10001
						</td>
					</tr>
					<tr>
						<td style="font-weight: bold">N</td>
						<td>
							10001<br />
							11001<br />
							10101<br />
							10011<br />
							10001
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">O</td>
						<td>
							01110<br />
							10001<br />
							10001<br />
							10001<br />
							01110
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">P</td>
						<td>
							11110<br />
							10001<br />
							11110<br />
							10000<br />
							10000
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">Q</td>
						<td>
							01110<br />
							10001<br />
							10001<br />
							10011<br />
							01111
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">R</td>
						<td>
							11110<br />
							10001<br />
							11110<br />
							10100<br />
							10010
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">S</td>
						<td>
							01111<br />
							10000<br />
							01110<br />
							00001<br />
							11110
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">T</td>
						<td>
							11111<br />
							00100<br />
							00100<br />
							00100<br />
							00100
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">U</td>
						<td>
							10001<br />
							10001<br />
							10001<br />
							10001<br />
							01110
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">V</td>
						<td>
							10001<br />
							10001<br />
							01010<br />
							01010<br />
							00100
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">X</td>
						<td>
							10001<br />
							01010<br />
							00100<br />
							01010<br />
							10001
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">Y</td>
						<td>
							10001<br />
							10001<br />
							01010<br />
							00100<br />
							00100
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">W</td>
						<td>
							10101<br />
							10101<br />
							10101<br />
							10101<br />
							01010
						</td>
						<td>&nbsp;</td>
						<td style="font-weight: bold">Z</td>
						<td>
							11111<br />
							00010<br />
							00100<br />
							01000<br />
							11111
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</body>
</html>
