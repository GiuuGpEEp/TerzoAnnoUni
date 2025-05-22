

import matplotlib.pyplot as plt
import pandas as pd

from Models import TrainerDefinitivo, TrainerDefinitivo15Min, TrainerDefinitivo20Min, TrainerDefinitivo25Min, TrainerDefinitivo30Min
from Models import TrainerDefinitivo as model_10min
from Models.TrainerDefinitivo15Min import TrainerDefinitivo as model_15min
from Models.TrainerDefinitivo20Min import TrainerDefinitivo as model_20min
from Models.TrainerDefinitivo25Min import TrainerDefinitivo as model_25min
from Models.TrainerDefinitivo30Min import TrainerDefinitivo as model_30min

def run_and_get_metrics():
    models = {
        "10min": model_10min.train_and_evaluate(verbose=False),
        "15min": model_15min.train_and_evaluate(verbose=False),
        "20min": model_20min.train_and_evaluate(verbose=False),
        "25min": model_25min.train_and_evaluate(verbose=False),
        "30min": model_30min.train_and_evaluate(verbose=False),
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
