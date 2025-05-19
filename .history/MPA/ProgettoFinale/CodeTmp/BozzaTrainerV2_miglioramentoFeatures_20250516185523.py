import ast
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report, confusion_matrix
from sklearn.preprocessing import StandardScaler

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

# =====================
# Feature extraction (base + arricchimento)
# =====================
def extract_features(row):
    features = {}

    gold_diff = row.get("goldDiff", [])
    if isinstance(gold_diff, str):
        try:
            gold_diff = ast.literal_eval(gold_diff)
        except:
            gold_diff = []

    if isinstance(gold_diff, list) and len(gold_diff) > 0:
        first_10 = gold_diff[:10] if len(gold_diff) >= 10 else gold_diff
        try:
            features["avg_gold_diff_10min"] = np.mean(first_10)
            features["gold_diff_min10"] = first_10[9] if len(first_10) >= 10 else first_10[-1]
            features["gold_diff_std_10min"] = np.std(first_10)
            features["gold_diff_slope"] = (first_10[-1] - first_10[0]) / len(first_10) if len(first_10) > 1 else 0
        except:
            features.update({"avg_gold_diff_10min": 0, "gold_diff_min10": 0, "gold_diff_std_10min": 0, "gold_diff_slope": 0})
    else:
        features.update({"avg_gold_diff_10min": 0, "gold_diff_min10": 0, "gold_diff_std_10min": 0, "gold_diff_slope": 0})

    features.update(parse_event_list_simple(row.get("bKills", []), "b", "kills"))
    features.update(parse_event_list_simple(row.get("rKills", []), "r", "kills"))
    features.update(parse_event_list_simple(row.get("bDragons", []), "b", "dragons"))
    features.update(parse_event_list_simple(row.get("rDragons", []), "r", "dragons"))

    b_tower_stats = parse_tower_list_detailed(row.get("bTowers", []), "b")
    r_tower_stats = parse_tower_list_detailed(row.get("rTowers", []), "r")
    features.update(b_tower_stats)
    features.update(r_tower_stats)

    # Feature arricchite
    features["b_kill_tower_ratio"] = features["b_kills_10min"] / (b_tower_stats["b_towers_10min"] + 1)
    features["r_kill_tower_ratio"] = features["r_kills_10min"] / (r_tower_stats["r_towers_10min"] + 1)

    return pd.Series(features)

# =====================
# Caricamento e pre-processing
# =====================
df = pd.read_excel('../Datitraining.xlsx')
features_df = df.apply(extract_features, axis=1)
print("Numero righe nel file originale:", len(df))
print("Numero righe dopo estrazione feature (con eventuali NaN):", len(features_df))
features_df = features_df.dropna()
print("Numero righe dopo dropna:", len(features_df))
labels = df.loc[features_df.index, "bResult"]

# Normalizzazione
scaler = StandardScaler()
features_scaled = scaler.fit_transform(features_df)



# Train/Test Split
X_train, X_test, y_train, y_test = train_test_split(features_scaled, labels, test_size=0.2, random_state=42)

# Addestramento
model = XGBClassifier(use_label_encoder=False, eval_metric="logloss")
model.fit(X_train, y_train)

# Predizione
y_pred = model.predict(X_test)
print("\n=== CLASSIFICATION REPORT ===")
print(classification_report(y_test, y_pred))

# Statistiche
print("\n=== STATISTICHE DETTAGLIATE ===")
print(f"Vittorie reali squadra blue     → {sum(y_test == 1)}")
print(f"Vittorie reali squadra red      → {sum(y_test == 0)}")
print(f"Vittorie predette squadra blue  → {sum(y_pred == 1)}")
print(f"Vittorie predette squadra red   → {sum(y_pred == 0)}")

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
