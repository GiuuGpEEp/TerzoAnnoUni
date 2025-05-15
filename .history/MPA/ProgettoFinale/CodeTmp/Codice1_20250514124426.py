import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Dropout
from tensorflow.keras.optimizers import Adam

# 1. Carica il file Excel
df = pd.read_excel('MPA/ProgettoFinale/CodeTmp/DatiTraining.xlsx')

# 2. Seleziona SOLO i dati dei primi 10 minuti
df = df[df['minutaggio'] <= 10]

# 3. Crea le feature (X) e la label (y)
# Esempio: prendi solo alcune colonne chiave come feature
feature_cols = [
    'gold diff', 'gold blue', 'gold red',
    'blue kills', 'red kills',
    'torri abbattute blue', 'torri abbattute rosse',
    'inhibitor rotti blue', 'inhibitor rotti red',
    'mostri uccisi'
]

# X = features, y = vincitore (1 = blue, 0 = red)
X = df[feature_cols].values
y = df['winner'].values  # ⚠️ Assicurati di avere una colonna "winner" nel tuo file!

# 4. Normalizza (scaling) le features
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

# 5. Dividi in training e test
X_train, X_test, y_train, y_test = train_test_split(
    X_scaled, y, test_size=0.2, random_state=42
)

# 6. Crea il modello MLP
model = Sequential([
    Dense(64, activation='relu', input_shape=(X_train.shape[1],)),
    Dropout(0.3),
    Dense(32, activation='relu'),
    Dropout(0.2),
    Dense(1, activation='sigmoid')  # 1 output → probabilità di vittoria Blue
])

# 7. Compila
model.compile(
    optimizer=Adam(learning_rate=0.001),
    loss='binary_crossentropy',
    metrics=['accuracy']
)

# 8. Allena
history = model.fit(
    X_train, y_train,
    epochs=30,
    batch_size=32,
    validation_split=0.2
)

# 9. Valuta
test_loss, test_acc = model.evaluate(X_test, y_test)
print(f'Test Accuracy: {test_acc:.2f}')
