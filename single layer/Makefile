FLAGS = -O2 -Wall -lpthread
INSTR = -pg

all: clean compile run

compile:
	gcc $(FLAGS) -c *.c
	gcc $(FLAGS) -o perceptron *.o

clean:
	clear
	rm -f perceptron *.o

run:
	./perceptron

instr:
	gcc $(FLAGS) $(INSTR) -c *.c
	gcc $(FLAGS) $(INSTR) -o perceptron *.o
	gprof perceptron
