import ast
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns

from sklearn.preprocessing import StandardScaler
from imblearn.over_sampling import SMOTE 
from sklearn.model_selection import GridSearchCV, train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report, confusion_matrix

# ------------------------- FUNZIONI DI SUPPORTO -------------------------

def plot_class_distribution(y_train, y_train_res):
    fig, axes = plt.subplots(1, 2, figsize=(12,4))
    axes[0].bar(['RED', 'BLUE'], [sum(y_train == 0), sum(y_train == 1)], color=['red', 'blue'])
    axes[0].set_title('Distribuzione classi - Prima di SMOTE')
    axes[1].bar(['RED', 'BLUE'], [sum(y_train_res == 0), sum(y_train_res == 1)], color=['red', 'blue'])
    axes[1].set_title('Distribuzione classi - Dopo SMOTE')
    plt.show()

def calc_slope(values):
    if len(values) < 2:
        return 0
    x = np.arange(len(values))
    y = np.array(values)
    A = np.vstack([x, np.ones(len(x))]).T
    m, _ = np.linalg.lstsq(A, y, rcond=None)[0]
    return m

def analizza_errori(X_test, y_test, y_pred, df_original):
    fp_idx = X_test[(y_test == 0) & (y_pred == 1)].index
    fn_idx = X_test[(y_test == 1) & (y_pred == 0)].index
    print(f"False Positives: {len(fp_idx)}\nFalse Negatives: {len(fn_idx)}")
    if df_original is not None:
        print("\nDettagli Falsi Positivi:")
        print(df_original.loc[fp_idx].head(3))
        print("\nDettagli Falsi Negativi:")
        print(df_original.loc[fn_idx].head(3))

def parse_tower_list_detailed(tower_raw, prefix):
    stats = {f"{prefix}_{key}": 0 for key in [
        "towers_20min", "top_towers", "mid_towers", "bot_towers",
        "outer_towers", "inner_towers", "base_towers", "nexus_towers"]}
    if isinstance(tower_raw, str):
        try:
            tower_raw = ast.literal_eval(tower_raw)
        except Exception:
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
                minute, lane, kind = float(tower[0]), tower[1].upper(), tower[2].upper()
                if minute < 20:
                    stats[f"{prefix}_towers_20min"] += 1
                    if "TOP" in lane: stats[f"{prefix}_top_towers"] += 1
                    if "MID" in lane: stats[f"{prefix}_mid_towers"] += 1
                    if "BOT" in lane: stats[f"{prefix}_bot_towers"] += 1
                    if "OUTER" in kind: stats[f"{prefix}_outer_towers"] += 1
                    if "INNER" in kind: stats[f"{prefix}_inner_towers"] += 1
                    if "BASE" in kind: stats[f"{prefix}_base_towers"] += 1
                    if "NEXUS" in kind: stats[f"{prefix}_nexus_towers"] += 1
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
                if float(minute) < 20:
                    count += 1
            except:
                continue
    return {f"{prefix}_{event_name}_20min": count}

def extract_features(row):
    features = {}
    gold_diff = row.get("goldDiff", [])
    if isinstance(gold_diff, str):
        try: gold_diff = ast.literal_eval(gold_diff)
        except: gold_diff = []
    if isinstance(gold_diff, list) and gold_diff:
        first_20 = gold_diff[:20]
        features["gold_diff_slope_0_20"] = calc_slope(first_20)
        features["gold_diff_slope_0_5"] = calc_slope(first_20[:5])
        features["gold_diff_slope_5_10"] = calc_slope(first_20[5:10])
        features["gold_diff_slope_10_15"] = calc_slope(first_20[10:15])
        features["gold_diff_slope_15_20"] = calc_slope(first_20[15:20])
        features["avg_gold_diff_20min"] = np.mean(first_20)
        features["gold_diff_min20"] = first_20[19] if len(first_20) >= 20 else first_20[-1]
        features["gold_diff_variance_20min"] = np.var(first_20)
        features["gold_diff_delta_last5min"] = first_20[19] - first_20[14] if len(first_20) >= 20 else 0
    else:
        for key in ["gold_diff_slope_0_20", "gold_diff_slope_0_5", "gold_diff_slope_5_10",
                    "gold_diff_slope_10_15", "gold_diff_slope_15_20", "avg_gold_diff_20min",
                    "gold_diff_min20", "gold_diff_variance_20min", "gold_diff_delta_last5min"]:
            features[key] = 0
    b_kills = row.get("bKills", [])
    r_kills = row.get("rKills", [])
    b_dragons = row.get("bDragons", [])
    r_dragons = row.get("rDragons", [])
    def count_events_in_range(event_list, min_t, max_t):
        if isinstance(event_list, str):
            try: event_list = ast.literal_eval(event_list)
            except: return 0
        return sum(1 for e in event_list if min_t <= float(e) < max_t)
    features.update(parse_event_list_simple(b_kills, "b", "kills"))
    features.update(parse_event_list_simple(r_kills, "r", "kills"))
    features.update(parse_event_list_simple(b_dragons, "b", "dragons"))
    features.update(parse_event_list_simple(r_dragons, "r", "dragons"))
    features["kills_diff_20min"] = features["b_kills_20min"] - features["r_kills_20min"]
    features["dragons_diff_20min"] = features["b_dragons_20min"] - features["r_dragons_20min"]
    for team in ['b', 'r']:
        for event in ['kills', 'dragons']:
            features[f"{team}_{event}_0_5"] = count_events_in_range(row.get(f"{team.capitalize()}{event.capitalize()}", []), 0, 5)
            features[f"{team}_{event}_5_10"] = count_events_in_range(row.get(f"{team.capitalize()}{event.capitalize()}", []), 5, 10)
            features[f"{team}_{event}_10_15"] = count_events_in_range(row.get(f"{team.capitalize()}{event.capitalize()}", []), 10, 15)
            features[f"{team}_{event}_15_20"] = count_events_in_range(row.get(f"{team.capitalize()}{event.capitalize()}", []), 15, 20)
    b_tower_stats = parse_tower_list_detailed(row.get("bTowers", []), "b")
    r_tower_stats = parse_tower_list_detailed(row.get("rTowers", []), "r")
    features.update(b_tower_stats)
    features.update(r_tower_stats)
    features["tower_diff_20min"] = b_tower_stats["b_towers_20min"] - r_tower_stats["r_towers_20min"]
    return pd.Series(features)

# ------------------------- CARICAMENTO E MODELLAZIONE -------------------------

df = pd.read_excel('../Project/Tables/Dati20Min3.xlsx')
print(f"Righe originali: {len(df)}")

features_df = df.apply(extract_features, axis=1).dropna()
print(f"Righe dopo estrazione e dropna: {len(features_df)}")

y = df.loc[features_df.index, "bResult"]
X_train, X_test, y_train, y_test = train_test_split(
    features_df, y, test_size=0.2, random_state=42, stratify=y)

scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

smote = SMOTE(random_state=42)
X_train_res, y_train_res = smote.fit_resample(X_train_scaled, y_train)

plot_class_distribution(y_train, y_train_res)

param_grid = {
    'n_estimators': [50, 100],
    'max_depth': [3, 5],
    'learning_rate': [0.05, 0.1],
    'subsample': [0.8, 1],
    'colsample_bytree': [0.8, 1]
}

xgb = XGBClassifier(use_label_encoder=False, eval_metric="logloss", random_state=42)
model = GridSearchCV(xgb, param_grid, cv=3, scoring='f1_weighted', n_jobs=-1, verbose=1)
model.fit(X_train_res, y_train_res)

print("Migliori parametri trovati:")
print(model.best_params_)

best_model = model.best_estimator_

importances = best_model.feature_importances_
feat_names = features_df.columns
feat_imp_df = pd.DataFrame({"Feature": feat_names, "Importanza": importances})
feat_imp_df = feat_imp_df.sort_values(by="Importanza", ascending=False)

plt.figure(figsize=(12, 6))
sns.barplot(x="Importanza", y="Feature", data=feat_imp_df.head(20))
plt.title("Top 20 Feature Importance - XGBoost")
plt.tight_layout()
plt.show()

y_pred = best_model.predict(X_test_scaled)
print("\n=== CLASSIFICATION REPORT ===")
print(classification_report(y_test, y_pred))

analizza_errori(X_test, y_test, y_pred, df.loc[features_df.index])

cm = confusion_matrix(y_test, y_pred)
tn, fp, fn, tp = cm.ravel()

print(f"\nConfusion Matrix: {cm.tolist()}")
print(f"Accuracy: {(tp + tn) / (tp + tn + fp + fn):.2f}")

plt.figure(figsize=(6, 4))
sns.heatmap(cm, annot=True, fmt="d", cmap="Blues", xticklabels=["RED", "BLUE"], yticklabels=["RED", "BLUE"])
plt.xlabel("Predicted")
plt.ylabel("True")
plt.title("Confusion Matrix")
plt.show()