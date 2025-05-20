import ast
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns

from imblearn.over_sampling import SMOTE 
from sklearn.model_selection import GridSearchCV
from sklearn.model_selection import train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report
from sklearn.metrics import confusion_matrix

def analizza_errori(X_test, y_test, y_pred, df_original):
    # Raccogliamo indici di false positive e false negative
    fp_idx = X_test[(y_test == 0) & (y_pred == 1)].index
    fn_idx = X_test[(y_test == 1) & (y_pred == 0)].index
    
    print(f"Numero falsi positivi (RED predette BLUE): {len(fp_idx)}")
    print(f"Numero falsi negativi (BLUE predette RED): {len(fn_idx)}\n")
    
    # Mostriamo qualche esempio di feature per FP e FN
    print("Esempi di falsi positivi (RED predette BLUE):")
    print(X_test.loc[fp_idx].head(3))
    print("\nEsempi di falsi negativi (BLUE predette RED):")
    print(X_test.loc[fn_idx].head(3))
    
    # Se vuoi, puoi anche concatenare con dati originali per vedere info più dettagliate
    if df_original is not None:
        print("\nInfo aggiuntive per i falsi positivi:")
        print(df_original.loc[fp_idx].head(3))
        print("\nInfo aggiuntive per i falsi negativi:")
        print(df_original.loc[fn_idx].head(3))


def parse_tower_list_detailed(tower_raw, prefix):
    stats = {
        f"{prefix}_towers_10min": 0,
        f"{prefix}_top_towers": 0,
        f"{prefix}_mid_towers": 0,
        f"{prefix}_bot_towers": 0,
        f"{prefix}_outer_towers": 0,
        f"{prefix}_inner_towers": 0,
        f"{prefix}_base_towers": 0,
        f"{prefix}_nexus_towers": 0
    }

    if isinstance(tower_raw, str):
        try:
            tower_raw = ast.literal_eval(tower_raw)
        except:
            return stats

    if not isinstance(tower_raw, list):
        return stats

    for tower in tower_raw:
        if isinstance(tower, str):
            try:
                tower = ast.literal_eval(tower)
            except:
                continue

        if isinstance(tower, (list, tuple)) and len(tower) >= 3:
            try:
                minute = float(tower[0])
                lane = tower[1].upper()
                kind = tower[2].upper()

                if minute < 10:
                    stats[f"{prefix}_towers_10min"] += 1

                    if "TOP" in lane:
                        stats[f"{prefix}_top_towers"] += 1
                    elif "MID" in lane:
                        stats[f"{prefix}_mid_towers"] += 1
                    elif "BOT" in lane:
                        stats[f"{prefix}_bot_towers"] += 1

                    if "OUTER" in kind:
                        stats[f"{prefix}_outer_towers"] += 1
                    elif "INNER" in kind:
                        stats[f"{prefix}_inner_towers"] += 1
                    elif "BASE" in kind:
                        stats[f"{prefix}_base_towers"] += 1
                    elif "NEXUS" in kind:
                        stats[f"{prefix}_nexus_towers"] += 1

            except:
                continue

    return stats

def parse_event_list_simple(event_raw, prefix, event_name):
    """Conta quanti eventi accadono nei primi 10 minuti.
       event_raw è una lista o stringa di minutaggi (float o str).
       prefix è 'b' o 'r'.
       event_name es. 'kills', 'dragons'.
    """
    count = 0
    if isinstance(event_raw, str):
        try:
            event_raw = ast.literal_eval(event_raw)
        except:
            event_raw = []

    if isinstance(event_raw, list):
        for minute in event_raw:
            try:
                if float(minute) < 10:
                    count += 1
            except:
                continue

    return {f"{prefix}_{event_name}_10min": count}

# Funzione per contare eventi entro una finestra temporale
def count_events_in_window(event_raw, end_minute):
    count = 0
    if isinstance(event_raw, str):
        try:
            event_raw = ast.literal_eval(event_raw)
        except:
            return 0
    if isinstance(event_raw, list):
        for minute in event_raw:
            try:
                if float(minute) < end_minute:
                    count += 1
            except:
                continue
    return count

# Funzione per estrarre feature dai primi 10 minuti
def extract_features(row):
    features = {}

    # Gold difference: media primi 10 minuti + valore al minuto 10
    gold_diff = row.get("goldDiff", [])
    
    if isinstance(gold_diff, str):
        try:
            gold_diff = ast.literal_eval(gold_diff)
        except (ValueError, SyntaxError):
            gold_diff = []     
        
    if isinstance(gold_diff, list) and len(gold_diff) > 0:    
        first_10 = gold_diff[:10] if len(gold_diff) >= 10 else gold_diff
        try:
            features["avg_gold_diff_10min"] = np.mean(first_10)
            features["gold_diff_min10"] = first_10[9] if len(first_10) >= 10 else first_10[-1]
            features["gold_diff_variance_10min"] = np.var(first_10)
            if len(first_10) >= 10:
                features["gold_diff_delta_last5min"] = first_10[9] - first_10[4]
            else:
                features["gold_diff_delta_last5min"] = 0
        except:
            features["avg_gold_diff_10min"] = 0
            features["gold_diff_min10"] = 0
            features["gold_diff_variance_10min"] = 0
            features["gold_diff_delta_last5min"] = 0
    else:
        features["avg_gold_diff_10min"] = 0
        features["gold_diff_min10"] = 0
        features["gold_diff_variance_10min"] = 0
        features["gold_diff_delta_last5min"] = 0

    # Eventi: kills e draghi
    b_kills = row.get("bKills", [])
    r_kills = row.get("rKills", [])
    b_dragons = row.get("bDragons", [])
    r_dragons = row.get("rDragons", [])

    # Funzione per contare eventi in intervalli
    def count_events_in_range(event_list, min_time, max_time):
        count = 0
        if isinstance(event_list, str):
            try:
                event_list = ast.literal_eval(event_list)
            except:
                return 0
        for e in event_list:
            try:
                if min_time <= float(e) < max_time:
                    count += 1
            except:
                continue
        return count

    # Kill e draghi nei primi 10 minuti
    features.update(parse_event_list_simple(b_kills, "b", "kills"))
    features.update(parse_event_list_simple(r_kills, "r", "kills"))
    features.update(parse_event_list_simple(b_dragons, "b", "dragons"))
    features.update(parse_event_list_simple(r_dragons, "r", "dragons"))

    # Differenze
    features["kills_diff_10min"] = features["b_kills_10min"] - features["r_kills_10min"]
    features["dragons_diff_10min"] = features["b_dragons_10min"] - features["r_dragons_10min"]

    # Eventi 0-5 e 5-10 minuti (momentum)
    features["b_kills_0_5"] = count_events_in_range(b_kills, 0, 5)
    features["b_kills_5_10"] = count_events_in_range(b_kills, 5, 10)
    features["r_kills_0_5"] = count_events_in_range(r_kills, 0, 5)
    features["r_kills_5_10"] = count_events_in_range(r_kills, 5, 10)

    features["b_dragons_0_5"] = count_events_in_range(b_dragons, 0, 5)
    features["b_dragons_5_10"] = count_events_in_range(b_dragons, 5, 10)
    features["r_dragons_0_5"] = count_events_in_range(r_dragons, 0, 5)
    features["r_dragons_5_10"] = count_events_in_range(r_dragons, 5, 10)

    # Torri primi 10 minuti (dettagliato)
    b_tower_stats = parse_tower_list_detailed(row.get("bTowers", []), "b")
    r_tower_stats = parse_tower_list_detailed(row.get("rTowers", []), "r")
    features.update(b_tower_stats)
    features.update(r_tower_stats)

    # Differenza torri
    features["tower_diff_10min"] = b_tower_stats["b_towers_10min"] - r_tower_stats["r_towers_10min"]

    return pd.Series(features)


# -----------------------
# Esempio di uso con DataFrame
# -----------------------

# Caricamento del dataset
df = pd.read_excel('../DatiTraining.xlsx')
print(f"Numero righe nel file originale: {len(df)}")

# Applica estrazione feature
features_df = df.apply(extract_features, axis=1)
print(f"Numero righe dopo estrazione feature (con eventuali NaN): {len(features_df)}")

# Elimina righe con NaN
features_df = features_df.dropna()
print(f"Numero righe dopo dropna: {len(features_df)}")

# Allinea le etichette alle righe valide
labels = df["bResult"].loc[features_df.index]

# Split train/test
X_train, X_test, y_train, y_test = train_test_split(features_df, labels, test_size=0.2, random_state=42)
smote = SMOTE(random_state=42)
X_train_res, y_train_res = smote.fit_resample(X_train, y_train)

# Parametri da esplorare (puoi espandere in seguito)
param_grid = {
    'n_estimators': [50, 100, 150],
    'max_depth': [3, 5, 7],
    'learning_rate': [0.01, 0.1, 0.2],
    'subsample': [0.8, 1],
    'colsample_bytree': [0.8, 1]
}

# Addestra XGBoost
xgb = XGBClassifier(use_label_encoder=False, eval_metric="logloss")
model = GridSearchCV(
    estimator=xgb,
    param_grid=param_grid,
    cv=3,  # Cross-validation a 3 fold
    n_jobs=-1,  # Usa tutti i core disponibili
    scoring='f1_weighted',
    verbose=1
)

# Addestra il modello
model.fit(X_train_res, y_train_res)

# Ottengo il miglior modello
print("🚀 Migliori parametri trovati:")
print(model.best_params_)
best_model = model.best_estimator_

# Ottieni l'importanza delle feature dal modello
importances = best_model.feature_importances_
feature_names = X_train.columns

# Crea un DataFrame ordinato
feat_imp_df = pd.DataFrame({
    "Feature": feature_names,
    "Importanza": importances
}).sort_values(by="Importanza", ascending=False)

# Plot
plt.figure(figsize=(12, 6))
sns.barplot(x="Importanza", y="Feature", data=feat_imp_df.head(20))
plt.title("Top 20 Feature Importance - XGBoost")
plt.tight_layout()
plt.show()

# Valutazione
y_pred = best_model.predict(X_test)
print("\n=== CLASSIFICATION REPORT ===")
print(classification_report(y_test, y_pred))



# Output esplicativi aggiuntivi
blue_real = sum(y_test == 1)
red_real = sum(y_test == 0)
blue_pred = sum(y_pred == 1)
red_pred = sum(y_pred == 0)

print("\n=== MATRICE DI CONFUSIONE ===")
# Confusion Matrix
cm = confusion_matrix(y_test, y_pred)

# cm è una matrice 2x2: [[TN, FP], [FN, TP]]
tn, fp, fn, tp = cm.ravel()

print("\n--- Risultato dettagliato ---")
print(f"🔵 Vittorie reali squadra BLUE (classe 1): {sum(y_test == 1)}")
print(f"🔴 Vittorie reali squadra RED  (classe 0): {sum(y_test == 0)}")

print(f"✅ Vittorie BLUE predette correttamente (True Positives): {tp}")
print(f"❌ Vittorie BLUE predette ma era RED (False Positives): {fp}")

print(f"✅ Vittorie RED predette correttamente (True Negatives): {tn}")
print(f"❌ Vittorie RED predette ma era BLUE (False Negatives): {fn}")

print(f"\n🎯 Accuratezza totale: {(tp + tn) / (tp + tn + fp + fn):.2f}")