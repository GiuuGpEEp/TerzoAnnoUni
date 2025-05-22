import ast
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

from sklearn.preprocessing import StandardScaler
from imblearn.over_sampling import SMOTE
from sklearn.metrics import classification_report, confusion_matrix

# ------------------------- FUNZIONI DI SUPPORTO -------------------------

def plot_class_distribution(y_train, y_train_res):
    fig, axes = plt.subplots(1, 2, figsize=(12, 4))
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

def parse_tower_list_detailed(tower_raw, prefix, max_minute):
    stats = {f"{prefix}_{key}": 0 for key in [
        "towers_limited", "top_towers", "mid_towers", "bot_towers",
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
                if minute < max_minute:
                    stats[f"{prefix}_towers_limited"] += 1
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

def parse_event_list_simple(event_raw, prefix, event_name, max_minute):
    count = 0
    if isinstance(event_raw, str):
        try:
            event_raw = ast.literal_eval(event_raw)
        except:
            event_raw = []
    if isinstance(event_raw, list):
        for minute in event_raw:
            try:
                if float(minute) < max_minute:
                    count += 1
            except:
                continue
    return {f"{prefix}_{event_name}_{max_minute}min": count}

def extract_features(row, minute):
    features = {}
    gold_diff = row.get("goldDiff", [])
    if isinstance(gold_diff, str):
        try:
            gold_diff = ast.literal_eval(gold_diff)
        except:
            gold_diff = []
    if isinstance(gold_diff, list) and gold_diff:
        first_n = gold_diff[:minute]
        features[f"gold_diff_slope_0_{minute}"] = calc_slope(first_n)
        features[f"gold_diff_slope_0_{minute//2}"] = calc_slope(first_n[:minute//2])
        features[f"gold_diff_slope_{minute//2}_{minute}"] = calc_slope(first_n[minute//2:minute])
        features[f"avg_gold_diff_{minute}min"] = np.mean(first_n)
        features[f"gold_diff_min{minute}"] = first_n[minute-1] if len(first_n) >= minute else first_n[-1]
        features[f"gold_diff_variance_{minute}min"] = np.var(first_n)
        features[f"gold_diff_delta_last{minute//2}min"] = first_n[minute-1] - first_n[minute//2-1] if len(first_n) >= minute else 0
    else:
        for key in [
            f"gold_diff_slope_0_{minute}",
            f"gold_diff_slope_0_{minute//2}",
            f"gold_diff_slope_{minute//2}_{minute}",
            f"avg_gold_diff_{minute}min",
            f"gold_diff_min{minute}",
            f"gold_diff_variance_{minute}min",
            f"gold_diff_delta_last{minute//2}min"
        ]:
            features[key] = 0

    def count_events_in_range(event_list, min_t, max_t):
        if isinstance(event_list, str):
            try:
                event_list = ast.literal_eval(event_list)
            except:
                return 0
        return sum(1 for e in event_list if min_t <= float(e) < max_t)

    for team in ['b', 'r']:
        for event in ['kills', 'dragons']:
            event_list = row.get(f"{team.capitalize()}{event.capitalize()}", [])
            # Eventi totali fino a minute
            features.update(parse_event_list_simple(event_list, team, event,minute))
            # Eventi in 0-minute//2 e minute//2-minute
            features[f"{team}_{event}_0_{minute//2}"] = count_events_in_range(event_list, 0, minute//2)
            features[f"{team}_{event}_{minute//2}_{minute}"] = count_events_in_range(event_list, minute//2, minute)

    features[f"kills_diff_{minute}min"] = features[f"b_kills_10min"] - features[f"r_kills_10min"] if minute == 10 else features.get(f"b_kills_{minute}min", 0) - features.get(f"r_kills_{minute}min", 0)
    features[f"dragons_diff_{minute}min"] = features[f"b_dragons_10min"] - features[f"r_dragons_10min"] if minute == 10 else features.get(f"b_dragons_{minute}min", 0) - features.get(f"r_dragons_{minute}min", 0)

    b_tower_stats = parse_tower_list_detailed(row.get("bTowers", []), "b")
    r_tower_stats = parse_tower_list_detailed(row.get("rTowers", []), "r")
    features.update(b_tower_stats)
    features.update(r_tower_stats)
    features[f"tower_diff_{minute}min"] = b_tower_stats.get(f"b_towers_{minute}min", 0) - r_tower_stats.get(f"r_towers_{minute}min", 0)
    return pd.Series(features)

