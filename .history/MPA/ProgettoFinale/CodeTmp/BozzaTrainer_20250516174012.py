import ast
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report
from sklearn.metrics import confusion_matrix

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


# Funzione per estrarre feature dai primi 10 minuti
def extract_features(row):
    features = {}

    #  # Gold difference: media primi 10 minuti + valore al minuto 10 (o ultimo)
    gold_diff = row.get("goldDiff", [])
    
    # Se è una stringa, la converto in lista di numeri
    if isinstance(gold_diff, str):
        try:
            gold_diff = ast.literal_eval(gold_diff)
        except (ValueError, SyntaxError):
            gold_diff = []     
        
    if isinstance(gold_diff, list) and len(gold_diff) > 0:    
        first_10 = gold_diff[:10] if len(gold_diff) >= 10 else gold_diff
        try:
            features["avg_gold_diff_10min"] = np.mean(gold_diff[:10]) # Calcolo la media della gold diff nei primi 10 minuti
            features["gold_diff_min10"] = gold_diff[9] if len(gold_diff) >= 10 else gold_diff[-1] # Ultimo valore della gold diff (minuto 10)
        except:
            features["avg_gold_diff_10min"] = 0
            features["gold_diff_min10"] = 0        
    else:
        features["avg_gold_diff_10min"] = 0
        features["gold_diff_min10"] = 0    

    # Faccio il parsing delle kill e le salvo
    features.update(parse_event_list_simple(row.get("bKills", []), "b", "kills"))
    features.update(parse_event_list_simple(row.get("rKills", []), "r", "kills"))

    # Draghi primi 10 minuti
    features.update(parse_event_list_simple(row.get("bDragons", []), "b", "dragons"))
    features.update(parse_event_list_simple(row.get("rDragons", []), "r", "dragons"))

    # Torri primi 10 minuti (dettagliato)
    b_tower_stats = parse_tower_list_detailed(row.get("bTowers", []), "b")
    r_tower_stats = parse_tower_list_detailed(row.get("rTowers", []), "r")
    features.update(b_tower_stats)
    features.update(r_tower_stats)
    
    return pd.Series(features)

# -----------------------
# Esempio di uso con DataFrame
# -----------------------

# Caricamento del dataset
df = pd.read_excel('../Datitraining.xlsx')
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

# Addestra XGBoost
model = XGBClassifier(use_label_encoder=False, eval_metric="logloss")
model.fit(X_train, y_train)

# Valutazione
y_pred = model.predict(X_test)
print("\n=== CLASSIFICATION REPORT ===")
print(classification_report(y_test, y_pred))

# Output esplicativi aggiuntivi
blue_real = sum(y_test == 1)
red_real = sum(y_test == 0)
blue_pred = sum(y_pred == 1)
red_pred = sum(y_pred == 0)

print("\n=== STATISTICHE DETTAGLIATE ===")
print(f"Vittorie reali squadra blue     → {blue_real}")
print(f"Vittorie reali squadra red      → {red_real}")
print(f"Vittorie predette squadra blue  → {blue_pred}")
print(f"Vittorie predette squadra red   → {red_pred}")

prin

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
