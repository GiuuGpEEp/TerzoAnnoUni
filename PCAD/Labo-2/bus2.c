#include <pthread.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <stdint.h>
#include "my_semaphore.h"

#define N 7   // Numero totale di passeggeri
#define C 5   // Numero di posti sul bus (C < N)

my_semaphore semSalite;
my_semaphore semDiscese;
my_semaphore semBus;

int saliti = 0;
int scesi = 0;

int my_sem_init(my_semaphore *ms, unsigned int v) {
    ms->V = v;
    pthread_mutex_init(&ms->lock, NULL);
    pthread_cond_init(&ms->varcond, NULL);
    return 0;
}

int my_sem_wait(my_semaphore *ms) {
    pthread_mutex_lock(&ms->lock);
    while (ms->V == 0) {
        pthread_cond_wait(&ms->varcond, &ms->lock);
    }
    ms->V--;
    pthread_mutex_unlock(&ms->lock);
    return 0;
}

int my_sem_signal(my_semaphore *ms) {
    pthread_mutex_lock(&ms->lock);
    ms->V++;
    pthread_cond_signal(&ms->varcond);
    pthread_mutex_unlock(&ms->lock);
    return 0;
}

int my_sem_destroy(my_semaphore *ms) {
    pthread_mutex_destroy(&ms->lock);
    pthread_cond_destroy(&ms->varcond);
    return 0;
}

void* bus_function(void* arg) {
    while (1) {
        saliti = 0;
        printf("BUS: Porte aperte per la salita.\n");

        for (int i = 0; i < C; i++)
            my_sem_signal(&semSalite);

        for (int i = 0; i < C; i++)
            my_sem_wait(&semBus);

        printf("BUS: Pieno con %d passeggeri. Partenza per il giro turistico.\n", C);
        sleep(2);
        printf("BUS: Giro turistico terminato. Arrivo al punto di partenza.\n");

        scesi = 0;
        for (int i = 0; i < C; i++)
            my_sem_signal(&semDiscese);

        for (int i = 0; i < C; i++)
            my_sem_wait(&semBus);

        printf("BUS: Tutti i passeggeri sono scesi. Pronto per il prossimo giro.\n");
        sleep(1);
    }
    return NULL;
}

void* passenger_function(void* arg) {
    intptr_t id = (intptr_t)arg;
    while (1) {
        my_sem_wait(&semSalite);
        saliti++;
        printf("PASSEGGERO %ld: Salito. (Saliti: %d)\n", (long)id, saliti);

        my_sem_wait(&semDiscese);
        scesi++;
        printf("PASSEGGERO %ld: Sceso. (Scesi: %d)\n", (long)id, scesi);
        my_sem_signal(&semBus);

        sleep(1);
    }
    return NULL;
}

int main(void) {
    pthread_t bus;
    pthread_t passeggeri[N];

    my_sem_init(&semSalite, 0);
    my_sem_init(&semDiscese, 0);
    my_sem_init(&semBus, 0);

    pthread_create(&bus, NULL, bus_function, NULL);

    for (int i = 0; i < N; i++) {
        pthread_create(&passeggeri[i], NULL, passenger_function, (void*)(intptr_t)i); // Casting a intptr_t
    }

    pthread_join(bus, NULL);
    for (int i = 0; i < N; i++) {
        pthread_join(passeggeri[i], NULL);
    }

    my_sem_destroy(&semSalite);
    my_sem_destroy(&semDiscese);
    my_sem_destroy(&semBus);

    return 0;
}
