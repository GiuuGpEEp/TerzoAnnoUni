import matplotlib.pyplot as plt
import pandas as pd
import importlib

def load_trainer_module(minuti):
    if minuti == 10:
        module_name = "Models.TrainerDefinitivo"
    else:
        module_name = f"Models.TrainerDefinitivo{minuti}Min"
    return importlib.import_module(module_name)

def run_and_get_metrics():
    minuti_values = [10, 15, 20, 25, 30]
    models = {}

    for minuti in minuti_values:
        module = load_trainer_module(minuti)
        print(f"[INFO] Avvio modello {minuti} minuti...")
        results = module.train_and_evaluate(minuti=minuti, verbose=False)
        models[f"{minuti}min"] = results

    df_metrics = pd.DataFrame(models).T
    df_metrics.to_csv("results/metrics_comparison.csv")
    return df_metrics

def plot_metrics(df_metrics):
    df_metrics.plot(kind="bar", figsize=(12, 6))
    plt.title("Confronto metriche tra modelli XGBoost")
    plt.ylabel("Valore")
    plt.xlabel("Modello (minuti)")
    plt.xticks(rotation=0)
    plt.grid(True)
    plt.tight_layout()
    plt.savefig("results/metrics_plot.png")
    plt.show()

def run_single_model():
    try:
        minuti = int(input("Inserisci il minutaggio del modello da avviare (10, 15, 20, 25, 30): "))
        if minuti not in [10, 15, 20, 25, 30]:
            print("Minutaggio non valido.")
            return
    except ValueError:
        print("Inserisci un numero intero valido.")
        return

    print(f"\n[INFO] Avvio modello per {minuti} minuti con verbose=True...\n")
    module = load_trainer_module(minuti)
    results = module.train_and_evaluate(minuti=minuti, verbose=True)

    print("\n[METRICHE]")
    for k, v in results.items():
        print(f"{k}: {v}")

def chat_loop():
    print("Benvenuto! Seleziona una delle seguenti opzioni:")
    print("1. Confronta i modelli (10-30 minuti)")
    print("2. Esegui un singolo modello (verbose)")
    print("Scrivi 'killProcess' per terminare il programma.\n")

    while True:
        user_input = input(">> ").strip()

        if user_input == "killProcess":
            print("Chiusura del programma. Arrivederci!")
            break
        elif user_input == "1":
            print("[INFO] Avvio confronto modelli...")
            df = run_and_get_metrics()
            print("\n[METRICHE]")
            print(df)
            plot_metrics(df)
        elif user_input == "2":
            run_single_model()
        else:
            print("Comando non riconosciuto. Riprova.")

def main():
    chat_loop()

if __name__ == "__main__":
    main()
