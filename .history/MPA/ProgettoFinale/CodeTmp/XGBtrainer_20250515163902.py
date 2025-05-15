import pandas as pd
import ast
import numpy as np
from sklearn.model_selection import train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report

# 1. Caricamento del dataset
df = pd.read_excel("partite_lol.xlsx")

# 2. Funzione per estrarre feature dai primi 10 minuti
def extract_features(row):
    features = {}

    # Estrai differenza oro media nei primi 10 minuti
    gold_diff = ast.literal_eval(row["goldDiff"])  # es. "[100, 200, 300, ...]"
    features["avg_gold_diff_10min"] = np.mean(gold_diff[:10])
    features["gold_diff_min10"] = gold_diff[9] if len(gold_diff) >= 10 else gold_diff[-1]

    # Estrarre kill nei primi 10 minuti
    b_kills = [int(minuto) for minuto in ast.literal_eval(row["bKills"]) if int(minuto) < 10]
    r_kills = [int(minuto) for minuto in ast.literal_eval(row["rKills"]) if int(minuto) < 10]
    features["b_kills_10min"] = len(b_kills)
    features["r_kills_10min"] = len(r_kills)

    # Torri distrutte nei primi 10 minuti
    b_towers = [event for event in ast.literal_eval(row["bTowers"]) if int(event.split("-")[0]) < 10]
    r_towers = [event for event in ast.literal_eval(row["rTowers"]) if int(event.split("-")[0]) < 10]
    features["b_towers_10min"] = len(b_towers)
    features["r_towers_10min"] = len(r_towers)

    # Draghi presi nei primi 10 minuti
    b_dragons = [int(minuto) for minuto in ast.literal_eval(row["bDragons"]) if int(minuto) < 10]
    r_dragons = [int(minuto) for minuto in ast.literal_eval(row["rDragons"]) if int(minuto) < 10]
    features["b_dragons_10min"] = len(b_dragons)
    features["r_dragons_10min"] = len(r_dragons)

    return pd.Series(features)

# 3. Applica estrazione al dataset
features_df = df.apply(extract_features, axis=1)

# 4. Label di classificazione: 1 se vince il blue team, 0 se vince il red team
labels = df["bResult"]

# 5. Split dei dati
X_train, X_test, y_train, y_test = train_test_split(features_df, labels, test_size=0.2, random_state=42)

# 6. Training del modello XGBoost
model = XGBClassifier(use_label_encoder=False, eval_metric='logloss')
model.fit(X_train, y_train)

# 7. Predizioni e valutazione
y_pred = model.predict(X_test)
print(classification_report(y_test, y_pred))
