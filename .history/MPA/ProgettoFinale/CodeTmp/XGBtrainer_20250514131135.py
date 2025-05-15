import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from xgboost import XGBClassifier
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix

# 1. Carica e prepara i dati (stessa logica di prima)
df = pd.read_excel('../Datitraining.xlsx')
df = df[df['minutaggio'] <= 10]

df['kill_diff'] = df['blue kills'] - df['red kills']
df['tower_diff'] = df['torri abbattute blue'] - df['torri abbattute rosse']
df['inhib_diff'] = df['inhibitor rotti blue'] - df['inhibitor rotti red']
df['gold_diff_calc'] = df['gold blue'] - df['gold red']

agg_df = df.groupby('match_id').agg({
    'gold diff': 'max',
    'gold blue': 'max',
    'gold red': 'max',
    'kill_diff': 'max',
    'tower_diff': 'max',
    'inhib_diff': 'max',
    'mostri uccisi': 'max',
    'winner': 'max'
}).reset_index()

feature_cols = [
    'gold diff', 'gold blue', 'gold red',
    'kill_diff', 'tower_diff', 'inhib_diff',
    'mostri uccisi'
]

X = agg_df[feature_cols].values
y = agg_df['winner'].values

# 2. Dividi in train/test
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

# 3. Modello XGBoost
xgb_model = XGBClassifier(
    n_estimators=100,
    max_depth=4,
    learning_rate=0.1,
    use_label_encoder=False,
    eval_metric='logloss'
)

# 4. Allena
xgb_model.fit(X_train, y_train)

# 5. Predici
y_pred = xgb_model.predict(X_test)

# 6. Valuta
acc = accuracy_score(y_test, y_pred)
print(f'Test Accuracy XGBoost: {acc:.2f}')

print("\nReport dettagliato:\n")
print(classification_report(y_test, y_pred, target_names=["Red", "Blue"]))

# 7. Matrice di confusione
import matplotlib.pyplot as plt
import seaborn as sns

cm = confusion_matrix(y_test, y_pred)
plt.figure(figsize=(5,4))
sns.heatmap(cm, annot=True, fmt='d', cmap='Blues', xticklabels=["Red", "Blue"], yticklabels=["Red", "Blue"])
plt.xlabel('Predetto')
plt.ylabel('Reale')
plt.title('Confusione Matrix')
plt.show()
