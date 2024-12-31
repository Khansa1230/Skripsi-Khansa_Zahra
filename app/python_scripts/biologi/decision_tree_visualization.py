import pandas as pd
from sklearn.tree import DecisionTreeClassifier, export_text
import json
import os
import numpy as np

# Memastikan file JSON dapat diakses
json_file_path = 'C:\\xampp\\htdocs\\skripsi\\app\\python_scripts\\biologi\\data.json'

# Inisialisasi output
output = {
    'tree_text': '',
    'first_true_path': []
}

if not os.path.exists(json_file_path):
    output['tree_text'] = f"File tidak ditemukan: {json_file_path}"
else:
    # Membaca dataset dari file JSON
    try:
        with open(json_file_path, 'r', encoding='utf-8') as file:
            data = json.load(file)
    except Exception as e:
        output['tree_text'] = f"Terjadi kesalahan saat membaca file JSON: {e}"
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

            # Membuat model pohon keputusan dengan entropy
            model = DecisionTreeClassifier(criterion='entropy')  # Menggunakan entropy
            model.fit(X, y)

            # Menampilkan teks pohon keputusan
            tree_text = export_text(model, feature_names=list(X.columns), decimals=3)
            output['tree_text'] = tree_text

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

        except Exception as e:
            output['tree_text'] = f"Terjadi kesalahan saat memproses DataFrame: {e}"

# Mengoutputkan hasil dalam format JSON
print(json.dumps(output))