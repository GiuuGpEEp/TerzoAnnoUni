import ast
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report

# 1. Caricamento del dataset
df = pd.read_excel('../Datitraining.xlsx')

# 2. Funzione per estrarre feature dai primi 10 minuti
def extract_features(row):
    features = {}

    # Estraggo la gold diff dal file excel e lo salvo 
    gold_diff = row.get("goldDiff", [])
    
    # Se è una stringa, la converto in lista di numeri
    if isinstance(gold_diff, str):
        try:
            gold_diff = ast.literal_eval(gold_diff) 
        
    features["avg_gold_diff_10min"] = np.mean(gold_diff[:10]) # Calcolo la media della gold diff nei primi 10 minuti
    features["gold_diff_min10"] = gold_diff[9] if len(gold_diff) >= 10 else gold_diff[-1] # Ultimo valore della gold diff (minuto 10)

    # Kill nei primi 10 minuti
    b_kills = [float(minuto) for minuto in row["bKills"] if float(minuto) < 10] 
    r_kills = [float(minuto) for minuto in row["rKills"] if float(minuto) < 10]
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

# 3. Applica estrazione feature
features_df = df.apply(extract_features, axis=1)

# 4. Label target: 1 se vince blue, 0 se vince red
labels = df["bResult"]

# 5. Split train/test
X_train, X_test, y_train, y_test = train_test_split(features_df, labels, test_size=0.2, random_state=42)

# 6. Addestra XGBoost
model = XGBClassifier(use_label_encoder=False, eval_metric="logloss")
model.fit(X_train, y_train)

# 7. Valutazione
y_pred = model.predict(X_test)
print(classification_report(y_test, y_pred))
