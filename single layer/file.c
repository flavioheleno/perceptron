#include "file.h"

int loadFile(char *file, uint8_t ***in, uint8_t *in_count, uint8_t ***out, uint8_t *out_count, uint8_t *size) {
	FILE *f;
	int i, j;
	unsigned int a;
	f = fopen(file, "r");
	if (f) {
		fscanf(f, "%u", &a);
		*size = (uint8_t)a;
		fscanf(f, "%u", &a);
		*in_count = (uint8_t)a;
		fscanf(f, "%u", &a);
		*out_count = (uint8_t)a;
		*in = (uint8_t **)malloc((sizeof(uint8_t *) * (*size)));
		*out = (uint8_t **)malloc((sizeof(uint8_t *) * (*size)));
		for (i = 0; i < (*size); i++) {
			(*in)[i] = (uint8_t *)malloc((sizeof(uint8_t) * (*in_count)));
			for (j = 0; j < (*in_count); j++) {
				fscanf(f, "%u", &a);
				(*in)[i][j] = (uint8_t)a;
			}
			(*out)[i] = (uint8_t *)malloc((sizeof(uint8_t) * (*out_count)));
			for (j = 0; j < (*out_count); j++) {
				fscanf(f, "%u", &a);
				(*out)[i][j] = (uint8_t)a;
			}
		}
		fclose(f);
		return 1;
	} else
		return 0;
}

int saveFile(char *file, uint8_t **in, uint8_t in_count, uint8_t **out, uint8_t out_count, uint8_t size) {
	FILE *f;
	int i, j;
	f = fopen(file, "w");
	if (f) {
		fprintf(f, "%u %u %u\n", (unsigned int)size, (unsigned int)in_count, (unsigned int)out_count);
		for (i = 0; i < size; i++) {
			for (j = 0; j < in_count; j++)
				fprintf(f, "%u ", (unsigned int)in[i][j]);
			for (j = 0; j < out_count; j++)
				fprintf(f, "%u ", (unsigned int)out[i][j]);
			fprintf(f, "\n");
		}
		fclose(f);
		return 1;
	} else
		return 0;
}
