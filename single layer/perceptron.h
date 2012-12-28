#ifndef __PERCEPTRON_H__
#define __PERCEPTRON_H__

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <pthread.h>

void init(uint8_t inputs, uint8_t outputs);
void destroy();
void set(uint8_t *pattern);
void get(uint8_t *result);
void teach(uint8_t **pattern, uint8_t **result, uint8_t size);
void run();
int save(char *file);
int load(char *file);
void dump();

#endif
