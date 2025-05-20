import ast
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split, GridSearchCV
from sklearn.preprocessing import StandardScaler
from xgboost import XGBClassifier
from sklearn.metrics import classification_report, confusion_matrix

# (Mantieni qui tutte le tue funzioni di parsing feature esattamente come prima...)
# =====================
# Feature extraction helpers
# =====================
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


def extract_features(row):
    features = {}

    # Gold diff media e valore minuto 10
    gold_diff = row.get("goldDiff", [])
    if isinstance(gold_diff, str):
        try:
            gold_diff = ast.literal_eval(gold_diff)
        except (ValueError, SyntaxError):
            gold_diff = []
    if isinstance(gold_diff, list) and len(gold_diff) > 0:
        first_10 = gold_diff[:10] if len(gold_diff) >= 10 else gold_diff
        try:
            features["avg_gold_diff_10min"] = np.mean(gold_diff[:10])
            features["gold_diff_min10"] = gold_diff[9] if len(gold_diff) >= 10 else gold_diff[-1]
        except:
            features["avg_gold_diff_10min"] = 0
            features["gold_diff_min10"] = 0
    else:
        features["avg_gold_diff_10min"] = 0
        features["gold_diff_min10"] = 0

    # Kill
    features.update(parse_event_list_simple(row.get("bKills", []), "b", "kills"))
    features.update(parse_event_list_simple(row.get("rKills", []), "r", "kills"))

    # Draghi
    features.update(parse_event_list_simple(row.get("bDragons", []), "b", "dragons"))
    features.update(parse_event_list_simple(row.get("rDragons", []), "r", "dragons"))

    # Torri
    b_tower_stats = parse_tower_list_detailed(row.get("bTowers", []), "b")
    r_tower_stats = parse_tower_list_detailed(row.get("rTowers", []), "r")
    features.update(b_tower_stats)
    features.update(r_tower_stats)

    ## Rapporto kill/tower (con gestione zero division)
    #b_kills_10min = features.get("b_kills_10min", 0)
    #r_kills_10min = features.get("r_kills_10min", 0)
    #b_towers_10min = features.get("b_towers_10min", 0)
    #r_towers_10min = features.get("r_towers_10min", 0)

    #features["b_kills_towers_ratio"] = b_kills_10min / b_towers_10min if b_towers_10min > 0 else 0
    #features["r_kills_towers_ratio"] = r_kills_10min / r_towers_10min if r_towers_10min > 0 else 0

    return pd.Series(features)


# -------------------
# Pipeline principale
# -------------------

# Caricamento dati
df = pd.read_excel('../Datitraining.xlsx')

# Estrazione feature
features_df = df.apply(extract_features, axis=1)

# Labels
labels = df["bResult"]

# Split
X_train, X_test, y_train, y_test = train_test_split(features_df, labels, test_size=0.2, random_state=42)

# Normalizzazione
#scaler = StandardScaler()
#X_train_scaled = scaler.fit_transform(X_train)
#X_test_scaled = scaler.transform(X_test)

# Setup modello base
xgb = XGBClassifier(eval_metric="logloss")

# Definizione grid di parametri da testare
param_grid = {
    'n_estimators': [50, 100, 150],
    'max_depth': [3, 5, 7],
    'learning_rate': [0.01, 0.1, 0.2],
    'subsample': [0.7, 0.8, 1.0],
    'colsample_bytree': [0.7, 0.8, 1.0]
}

# GridSearch con cross-validation 5-fold
grid_search = GridSearchCV(estimator=xgb, param_grid=param_grid, scoring='accuracy', cv=5, n_jobs=-1, verbose=1)

# Fit
grid_search.fit(X_train_scaled, y_train)

print("Migliori parametri trovati:", grid_search.best_params_)

# Valutazione con modello ottimizzato
best_model = grid_search.best_estimator_
y_pred = best_model.predict(X_test_scaled)

print(classification_report(y_test, y_pred))

# Matrice di Confusione
conf = confusion_matrix(y_test, y_pred)
TP = conf[1][1]
FP = conf[0][1]
TN = conf[0][0]
FN = conf[1][0]
print("\n=== MATRICE DI CONFUSIONE ===")
print("\n--- Risultato dettagliato ---")
print(f"🔵 Vittorie reali squadra BLUE (classe 1): {sum(y_test == 1)}")
print(f"🔴 Vittorie reali squadra RED  (classe 0): {sum(y_test == 0)}")
print(f"✅ Vittorie BLUE predette correttamente (True Positives): {TP}")
print(f"❌ Vittorie BLUE predette ma era RED (False Positives): {FP}")
print(f"✅ Vittorie RED predette correttamente (True Negatives): {TN}")
print(f"❌ Vittorie RED predette ma era BLUE (False Negatives): {FN}")
print(f"\n🎯 Accuratezza totale: {round((TP+TN)/(TP+TN+FP+FN), 2)}")
