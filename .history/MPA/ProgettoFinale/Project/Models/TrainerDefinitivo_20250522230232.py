import sys
import os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))

import ast
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns

from Utils.utils import *
from sklearn.preprocessing import StandardScaler
from imblearn.over_sampling import SMOTE 
from sklearn.model_selection import GridSearchCV, train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import classification_report, confusion_matrix
from sklearn.metrics import f1_score, recall_score, precision_score


# ------------------------- CARICAMENTO E MODELLAZIONE -------------------------


def train_and_evaluate(minuti, verbose=False):
    
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    file_path = os.path.join(BASE_DIR, '..', 'Tables', 'Dati10Min.xlsx')

    df = pd.read_excel(file_path)
    features_df = df.apply(lambda row: extract_features(row, minuti), axis=1).dropna()
    y = df.loc[features_df.index, "bResult"]
    
    X_train, X_test, y_train, y_test = train_test_split(
        features_df, y, test_size=0.2, random_state=42, stratify=y)

    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)

    smote = SMOTE(random_state=42)
    X_train_res, y_train_res = smote.fit_resample(X_train_scaled, y_train)

    if verbose:
        plot_class_distribution(y_train, y_train_res)

    param_grid = {
        'n_estimators': [50, 100],
        'max_depth': [3, 5],
        'learning_rate': [0.05, 0.1],
        'subsample': [0.8, 1],
        'colsample_bytree': [0.8, 1]
    }

    xgb = XGBClassifier(eval_metric="logloss", random_state=42)
    model = GridSearchCV(xgb, param_grid, cv=3, scoring='f1_weighted', n_jobs=-1, verbose=0)
    model.fit(X_train_res, y_train_res)

    best_model = model.best_estimator_
    y_pred = best_model.predict(X_test_scaled)

    acc = (y_pred == y_test).mean()
    f1 = f1_score(y_test, y_pred)
    recall = recall_score(y_test, y_pred)
    precision = precision_score(y_test, y_pred)

    if verbose:
        print("=== CLASSIFICATION REPORT ===")
        print(classification_report(y_test, y_pred))
        analizza_errori(X_test, y_test, y_pred, df.loc[features_df.index])

        # Feature importance
        importances = best_model.feature_importances_
        feat_names = features_df.columns
        feat_imp_df = pd.DataFrame({"Feature": feat_names, "Importanza": importances})
        feat_imp_df = feat_imp_df.sort_values(by="Importanza", ascending=False)

        plt.figure(figsize=(12, 6))
        sns.barplot(x="Importanza", y="Feature", data=feat_imp_df.head(20))
        plt.title("Top 20 Feature Importance - XGBoost")
        plt.tight_layout()
        plt.show()

        # Confusion Matrix
        cm = confusion_matrix(y_test, y_pred)
        plt.figure(figsize=(6, 4))
        sns.heatmap(cm, annot=True, fmt="d", cmap="Blues", xticklabels=["RED", "BLUE"], yticklabels=["RED", "BLUE"])
        plt.xlabel("Predicted")
        plt.ylabel("True")
        plt.title("Confusion Matrix")
        plt.show()

    return {
        "accuracy": acc,
        "f1": f1,
        "recall": recall,
        "precision": precision
    }
