import ast
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report

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

    # Estraggo la gold diff dal file excel e lo salvo 
    gold_diff = row.get("goldDiff", [])
    
    # Se è una stringa, la converto in lista di numeri
    if isinstance(gold_diff, str):
        try:
            gold_diff = ast.literal_eval(gold_diff)
        except (ValueError, SyntaxError):
            gold_diff = []     
        
    if isinstance(gold_diff, list) and len(gold_diff) > 0:    
        features["avg_gold_diff_10min"] = np.mean(gold_diff[:10]) # Calcolo la media della gold diff nei primi 10 minuti
        features["gold_diff_min10"] = gold_diff[9] if len(gold_diff) >= 10 else gold_diff[-1] # Ultimo valore della gold diff (minuto 10)
    else:
        features["avg_gold_diff_10min"] = 0
        features["gold_diff_min10"] = 0    

    # Faccio il parsing delle kill e le salvo
    b_kills = parse_kill_dragon(row.get("bKills", []))
    r_kills = parse_kill_dragon(row.get("rKills", []))
    
    features["b_kills_10min"] = len(b_kills)
    features["r_kills_10min"] = len(r_kills)

    # Torri distrutte < 10'
    b_towers = [event for event in row["bTowers"] if event[0] < 10]
    r_towers = [event for event in row["rTowers"] if event[0] < 10]
    features["b_towers_10min"] = len(b_towers)
    features["r_towers_10min"] = len(r_towers)

    # Draghi presi < 10'
    b_dragons = [t for t in row["bDragons"] if t < 10]
    r_dragons = [t for t in row["rDragons"] if t < 10]
    features["b_dragons_10min"] = len(b_dragons)
    features["r_dragons_10min"] = len(r_dragons)

    return pd.Series(features)

# Caricamento del dataset
df = pd.read_excel('../Datitraining.xlsx')

# Applica estrazione feature
features_df = df.apply(extract_features, axis=1)

# Label target: 1 se vince blue, 0 se vince red
labels = df["bResult"]

# Split train/test
X_train, X_test, y_train, y_test = train_test_split(features_df, labels, test_size=0.2, random_state=42)

# Addestra XGBoost
model = XGBClassifier(use_label_encoder=False, eval_metric="logloss")
model.fit(X_train, y_train)

# Valutazione
y_pred = model.predict(X_test)
print(classification_report(y_test, y_pred))
