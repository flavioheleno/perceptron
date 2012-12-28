#include "perceptron.h"

#define RATE 0.5

#define THRESHOLD(x) (x > 0.0 ? 1 : 0)

uint8_t *input;
uint8_t input_size;
uint8_t *output;
uint8_t output_size;
float **weight;

void init(uint8_t inputs, uint8_t outputs) {
	int i, j;
	//creating bias
	inputs++;
	//input memory allocation
	input = (uint8_t *)malloc(sizeof(uint8_t) * inputs);
	for (i = 0; i < inputs; i++)
		input[i] = 0;
	input_size = inputs;
	//output memory allocation
	output = (uint8_t *)malloc(sizeof(uint8_t) * outputs);
	for (i = 0; i < outputs; i++)
		output[i] = 0;
	output_size = outputs;
	//weight memory allocation
	weight = (float **)malloc(sizeof(float *) * inputs);
	for (i = 0; i < inputs; i++) {
		weight[i] = (float *)malloc(sizeof(float) * outputs);
		//weight initialization
		for (j = 0; j < outputs; j++)
			weight[i][j] = 0.0;
	}
}

void destroy() {
	int i;
	//weight memory liberation
	for (i = 0; i < input_size; i++)
		free(weight[i]);
	free(weight);
	//input/output memory liberation
	free(input);
	free(output);
}

void set(uint8_t *pattern) {
	int i;
	//input pattern set
	for (i = 0; i < (input_size - 1); i++)
		input[i] = pattern[i];
	//bias controller
	input[i] = 1;
}

void get(uint8_t *result) {
	int i;
	//copies the output result into result vector
	for (i = 0; i < output_size; i++)
		result[i] = output[i];
}

void teach(uint8_t **pattern, uint8_t **result, uint8_t size) {
	int z, i, j;
	int err, gerr;
	do {
		//initializes global error for this iteration
		gerr = 0;
		//step through every input pattern
		for (z = 0; z < size; z++) {
			printf("\nConjunto #%u\n", z);
			printf("Entradas: ");
			for (i = 0; i < (input_size - 1); i++)
				printf("%u ", pattern[z][i]);
			printf("\n");
			printf("Saídas: ");
			for (i = 0; i < output_size; i++)
				printf("%u ", result[z][i]);
			printf("\n");
			//sets the input
			set(pattern[z]);
			//calculates the network output
			run();
			//for every output item, calculates the error
			for (j = 0; j < output_size; j++) {
				printf("Calculando erro no neuronio #%d\n", j);
				err = (result[z][j] - output[j]);
				//updates global error with local error
				gerr |= err;
				printf("Erro = %d (%d)\n", err, gerr);
				if (err != 0)
					//updates the weight for every input connected with this output item
					for (i = 0; i < input_size; i++) {
						weight[i][j] += (RATE * input[i] * err);
						printf("Peso[%d][%d] = %f\n", i, j, weight[i][j]);
					}
			}
		}
	} while (gerr);
}

void run() {
	int i, j;
	float temp;
	for (j = 0; j < output_size; j++) {
		printf("Calculando neuronio #%d\n", j);
		//calculates the sum of every input connected with the output item
		temp = 0.0;
		for (i = 0; i < input_size; i++) {
			temp += (input[i] * weight[i][j]);
			printf("Peso[%d][%d] = %f\n", i, j, weight[i][j]);
		}
		//checks if the sum got the threshold value
		output[j] = THRESHOLD(temp);
		printf("Saída: %d\n", output[j]);
	}
}

int save(char *file) {
	int i, j;
	FILE *f;
	f = fopen(file, "w");
	if (f) {
		//saves input and output size
		fprintf(f, "%u ", (unsigned int)input_size);
		fprintf(f, "%u ", (unsigned int)output_size);
		//saves every connection weight
		for (j = 0; j < output_size; j++)
			for (i = 0; i < input_size; i++)
				fprintf(f, "%f ", weight[i][j]);
		fclose(f);
		return 1;
	} else
		return 0;
}

int load(char *file) {
	int i, j;
	unsigned int a;
	uint8_t in, out;
	FILE *f;
	f = fopen(file, "r");
	if (f) {
		//loads the input and output sizes
		fscanf(f, "%u", &a);
		in = (uint8_t)a;
		fscanf(f, "%u", &a);
		out = (uint8_t)a;
		//initializes the input and output vectors
		//(in - 1) is to avoid reallocation of bias
		init((in - 1), out);
		//loads the weight of every connection
		for (i = 0; i < in; i++)
			for (j = 0; j < out; j++)
				fscanf(f, "%f", &weight[i][j]);
		fclose(f);
		return 1;
	} else
		return 0;
}

void dump() {
	int i, j;
	printf("\nDUMP\n");
	//dumps the input and output size
	printf("Input size: %d\n", input_size);
	printf("Output size: %d\n", output_size);
	//dumps the weight of every connection
	for (j = 0; j < output_size; j++)
		for (i = 0; i < input_size; i++)
			printf("Weight[%d][%d]: %f\n", i, j, weight[i][j]);
	printf("\n");
}
