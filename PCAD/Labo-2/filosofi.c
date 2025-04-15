#include <stdio.h>
#include <stdlib.h>
#include <pthread.h>
#include <unistd.h>
#include "my_semaphore.c"

#define N 5

my_semaphore bacchette[N];
my_semaphore sala_attesa;

void *cenaFilosofi(void *arg) {
    int id = *(int *)arg;
    int sinistra = id;
    int destra = (id + 1) % N;

    for (int i = 0; i < 5; i++) {
        printf("Filosofo %d sta pensando\n", id + 1);

        // Entra nella sala d'attesa
        my_sem_wait(&sala_attesa);

        my_sem_wait(&bacchette[sinistra]);
        printf("Filosofo %d ha la sua bacchetta sinistra\n", id + 1);
        sleep(1);
        my_sem_wait(&bacchette[destra]);
        printf("Filosofo %d ha la sua bacchetta destra\n", id + 1);
        sleep(1);

        printf("Filosofo %d sta mangiando\n", id + 1);
        sleep(1);

        my_sem_signal(&bacchette[sinistra]);
        my_sem_signal(&bacchette[destra]);
        printf("Filosofo %d ha rilasciato le due bacchette\n", id + 1);

        // Esce dalla sala d'attesa
        my_sem_signal(&sala_attesa);
    }

    return NULL;
}

int main() {
    pthread_t filosofi[N];
    int id[N];

    for (int i = 0; i < N; i++) {
        my_sem_init(&bacchette[i], 1);
    }

    my_sem_init(&sala_attesa, N - 1); // Sala d'attesa con N-1 posti

    for (int i = 0; i < N; i++) {
        id[i] = i;
        pthread_create(&filosofi[i], NULL, cenaFilosofi, &id[i]);
    }

    for (int i = 0; i < N; i++) {
        pthread_join(filosofi[i], NULL);
    }
    
    for (int i = 0; i < N; i++) {
        my_sem_destroy(&bacchette[i]);
    }
    my_sem_destroy(&sala_attesa);

    return 0;
}
