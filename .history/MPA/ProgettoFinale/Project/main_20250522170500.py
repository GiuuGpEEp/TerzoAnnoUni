

import matplotlib.pyplot as plt
import pandas as pd

from MPA.ProgettoFinale.Project.Models import TrainerDefinitivo15Min
from Models import TrainerDefinitivo, TrainerDefinitivo15Min, TrainerDefinitivo20Min, TrainerDefinitivo25Min, TrainerDefinitivo30Min


def run_and_get_metrics():
    models = {
        "10min": model_10min.train_and_evaluate(),
        "15min": model_15min.train_and_evaluate(),
        "20min": model_20min.train_and_evaluate(),
        "25min": model_25min.train_and_evaluate(),
        "30min": model_30min.train_and_evaluate(),
    }

    df_metrics = pd.DataFrame(models).T  # Righe: modelli, Colonne: metriche
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

def main():
    print("[MAIN] Avvio confronto tra i modelli sui vari minuti...")
    df = run_and_get_metrics()
    print("\n[METRICHE]")
    print(df)
    plot_metrics(df)

if __name__ == "__main__":
    main()
