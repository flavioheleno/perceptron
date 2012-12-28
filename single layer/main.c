#include <stdio.h>
#include "file.h"
#include "perceptron.h"

int main(int argc, const char *argv[]) {
	uint8_t **in = NULL, size = 4, in_count = 2;
	uint8_t **out = NULL, out_count = 1;
	uint8_t res;
	int i, j;

	printf("Carregando\n");
	if (!loadFile("input.txt", &in, &in_count, &out, &out_count, &size)) {
		printf("Erro ao carregar o arquivo 'input.txt'\n");
		return 1;
	}
	printf("Carregados %d conjuntos de treinamento\n", size);
	if (!load("perceptron.txt")) {
		printf("Inicializando\n");
		init(2, 1);
	} else
		dump();
	printf("\n\nTREINAMENTO\n\n");
	printf("Ensinando\n");
	teach(in, out, 4);
	printf("\n\nEXEECUÇÃO\n\n");
	for (i = 0; i < 4; i++) {
		printf("\nConjunto #%u\n", i);
		printf("Entradas: %u %u\n", in[i][0], in[i][1]);
		printf("Saída: %u\n", out[i][0]);
		set(in[i]);
		printf("Executando\n");
		run();
		printf("Pegando\n");
		get(&res);
		printf("Resultado: %d\n", res);
	}
	printf("Salvando\n");
	save("perceptron.txt");
	printf("Destruindo\n");
	destroy();
	for (i = 0; i < 4; i++) {
		free(in[i]);
		free(out[i]);
	}
	free(in);
	free(out);
	return 0;
}
