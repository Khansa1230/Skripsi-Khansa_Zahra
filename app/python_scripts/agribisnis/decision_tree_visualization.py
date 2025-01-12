import pandas as pd
from sklearn.tree import DecisionTreeClassifier, export_text
from sklearn.model_selection import train_test_split
from sklearn.metrics import confusion_matrix, accuracy_score, precision_score, recall_score, f1_score
import json
import os
import numpy as np

# Memastikan file JSON dapat diakses
json_file_path = 'C:\\xampp\\htdocs\\skripsi\\app\\python_scripts\\agribisnis\\data.json'
test_size_file_path = 'C:\\xampp\\htdocs\\skripsi\\app\\python_scripts\\agribisnis\\test_size.json'

# Inisialisasi output
output = {
    'tree_text': '',
    'first_true_path': [],
    'accuracy': 0.0,
    'precision': 0.0,
    'recall': 0.0,
    'f1_score': 0.0,
    'test_size': None,
    'confusion_matrix': [] , # Menambahkan key untuk matriks kebingungan
    
}

if not os.path.exists(json_file_path):
    output['tree_text'] = "File tidak ditemukan."
else:
    # Membaca dataset dari file JSON
    try:
        with open(json_file_path, 'r', encoding='utf-8') as file:
            data = json.load(file)
    except Exception:
        output['tree_text'] = "Kesalahan membaca file JSON."
        data = None

    if data is not None:
        # Mengonversi data JSON ke DataFrame
        try:
            df = pd.DataFrame(data)

            # Mengonversi kategori ke tipe numerik jika diperlukan
            df_encoded = pd.get_dummies(df, drop_first=True)

            # Memisahkan fitur (X) dan target (y)
            X = df_encoded.iloc[:, :-1]  # Mengambil semua kolom kecuali kolom terakhir sebagai fitur
            y = df_encoded.iloc[:, -1]    # Mengambil kolom terakhir sebagai target

            # Membaca nilai test_size dari file JSON
            with open(test_size_file_path, 'r', encoding='utf-8') as file:
                test_size = json.load(file)['test_size']  # Ambil nilai test_size
                output['test_size'] = test_size  # Menyimpan test_size ke output

                # Membagi test_size dengan 100 untuk mengubahnya menjadi proporsi
                test_size = test_size / 100

            # Membagi data menjadi data pelatihan dan pengujian
            X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=test_size, random_state=42)

            # Membuat model pohon keputusan dengan entropy
            model = DecisionTreeClassifier(criterion='entropy')  # Menggunakan entropy
            model.fit(X_train, y_train)

            # Menampilkan teks pohon keputusan
            tree_text = export_text(model, feature_names=list(X.columns), decimals=3)
            output['tree_text'] = tree_text

            # Melakukan prediksi pada data pengujian
            y_pred = model.predict(X_test)

            # Menghitung matriks kebingungan
            cm = confusion_matrix(y_test, y_pred)
            output['confusion_matrix'] = cm.tolist()  # Menyimpan matriks kebingungan dalam output
            
            # Menghitung akurasi, precision, recall, dan F1 Score berdasarkan matriks kebingungan
            TP = cm[1, 1]  # True Positives
            TN = cm[0, 0]  # True Negatives
            FP = cm[0, 1]  # False Positives
            FN = cm[1, 0]  # False Negatives

            output['accuracy'] = (TP + TN) / np.sum(cm) * 100  # Menghitung akurasi
            output['precision'] = TP / (TP + FP) * 100 if (TP + FP) > 0 else 0  # Menghitung presisi
            output['recall'] = TP / (TP + FN) * 100 if (TP + FN) > 0 else 0  # Menghitung recall
            output['f1_score'] = (2 * output['precision'] * output['recall']) / (output['precision'] + output['recall']) if (output['precision'] + output['recall']) > 0 else 0  # Menghitung F1 Score ```python
            output['f1_score'] = (2 * output['precision'] * output['recall']) / (output['precision'] + output['recall']) if (output['precision'] + output['recall']) > 0 else 0  # Menghitung F1 Score

            # Fungsi untuk menemukan jalur pertama ke klasifikasi True
            def find_first_true_path(tree, feature_names):
                true_path = []
                
                for node in range(tree.node_count):
                    if tree.children_left[node] == -1 and tree.children_right[node] == -1:
                        if np.argmax(tree.value[node]) == 1:  # Asumsi kelas True adalah 1
                            current_node = node
                            path = []
                            
                            while current_node != 0:
                                parent_node = np.where(tree.children_left == current_node)[0]
                                if parent_node.size == 0:
                                    parent_node = np.where(tree.children_right == current_node)[0]
                                parent_node = parent_node[0] if parent_node.size > 0 else None
                                
                                if parent_node is not None:
                                    path.append((feature_names[tree.feature[parent_node]], tree.threshold[parent_node]))
                                    current_node = parent_node
                            
                            true_path = path[::-1]
                            break

                return true_path

            # Mendapatkan jalur ke klasifikasi True
            first_true_path = find_first_true_path(model.tree_, list(X.columns))
            output['first_true_path'] = first_true_path

        except Exception:
            output['tree_text'] = "Gunakan nilai antara 0 dan 100 untuk membagi dataset dengan benar."

# Mengoutputkan hasil dalam format JSON
print(json.dumps(output, ensure_ascii=False, indent=4))