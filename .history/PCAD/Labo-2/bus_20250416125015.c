#include <pthread.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <stdint.h>
#include "my_semaphore.c"

#define N 7   // Numero totale di passeggeri
#define C 5   // Numero di posti sul bus (C < N)

my_semaphore busSemaphore;
my_semaphore peopleSemaphore;
pthread_mutex_t countMutex;

void initialize_mutex() {
    if (pthread_mutex_init(&countMutex, NULL) != 0) {
        perror("Mutex initialization failed");
        exit(EXIT_FAILURE);
    }
}
int people_count = 0;

void secure_count(){
    pthread_mutex_lock(&countMutex); 
    people_count++;
    pthread_mutex_unlock(&countMutex);
}

void* bus_function(){
    printf("BUS: Porte aperte per la salita.\n");
    printf("In attesa che tutti i passeggeri salgano");
    if(people_count < C){
        my_sem_wait(&busSemaphore);
        printf("...\n");
    }
    printf("Tutti i passeggeri sono saliti, pronti a partire...\n");
    sleep(2); // Simula il tempo di viaggio
    printf("BUS: Il viaggio è terminato. Tutti i passeggeri scendono.\n");
    //people_count = 0; // Resetta il conteggio dei passeggeri
    //my_sem_signal(peopleSemaphore); // Permette ai nuovi passeggeri di salire - provare a sostituire con un broadcast
}

void* person_function(void* arg){
    int id = *(int*)arg;
    printf("Persona %d: In attesa di salire sul bus.\n", id);
    my_sem_wait(&peopleSemaphore);
    if(people_count < C){
        secure_count();
        printf("Persona %d: E' salita sul bus. Persone a bordo: %d\n", id, people_count);
        my_sem_signal(&peopleSemaphore);
    }
    my_sem_signal(&busSemaphore);
    
    return NULL;
}

int main(){
    pthread_t bus;
    pthread_t pedoni[N];

    my_sem_init(&busSemaphore, 1);
    my_sem_init(&peopleSemaphore, C);

    pthread_create(&bus, NULL, bus_function, NULL);
    for(int i = 0; i < N; i++){
        int* id = malloc(sizeof(int));
        *id = i;
        pthread_create(&pedoni[i], NULL, person_function, id);
    }
    
    for(int i = 0; i < N; i++){
        pthread_join(pedoni[i], NULL);
    }

    pthread_join(bus, NULL);

    return 0;

}

/**/