#ifndef __FILE_H__
#define __FILE_H__

#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int loadFile(char *file, uint8_t ***in, uint8_t *in_count, uint8_t ***out, uint8_t *out_count, uint8_t *size);
int saveFile(char *file, uint8_t **in, uint8_t in_count, uint8_t **out, uint8_t out_count, uint8_t size);

#endif
