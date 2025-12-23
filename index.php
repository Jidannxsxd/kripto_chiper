<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cipher - Komputasi Paralel</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Caesar Cipher</h1>
            <p class="subtitle">Simulasi Komputasi Paralel dan Terdistribusi</p>
        </header>

        <div class="main-content">
            <div class="input-section">
                <form id="cipherForm" method="POST" action="">
                    <div class="form-group">
                        <label for="inputText">Masukkan Teks</label>
                        <textarea id="inputText" name="inputText" rows="5" placeholder="Ketik teks yang akan diproses..." required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="shiftKey">Shift Key (1-25)</label>
                            <input type="number" id="shiftKey" name="shiftKey" min="1" max="25" value="3" required>
                        </div>

                        <div class="form-group">
                            <label for="mode">Mode</label>
                            <select id="mode" name="mode" required>
                                <option value="encrypt">Enkripsi</option>
                                <option value="decrypt">Dekripsi</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="numNodes">Jumlah Node (Simulasi Scalability)</label>
                        <input type="range" id="numNodes" name="numNodes" min="2" max="6" value="3" step="1">
                        <div class="node-count-display">3 Nodes</div>
                    </div>

                    <button type="submit" class="btn-submit">Proses</button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $inputText = $_POST['inputText'];
                $shiftKey = intval($_POST['shiftKey']);
                $mode = $_POST['mode'];
                $numNodes = isset($_POST['numNodes']) ? intval($_POST['numNodes']) : 3;

                require_once 'process.php';
                
                $processor = new CipherProcessor();
                $result = $processor->processText($inputText, $shiftKey, $mode, $numNodes);
                
                echo '<div class="result-section">';
                
                // Performance Metrics Dashboard
                echo '<div class="metrics-dashboard">';
                echo '<h2>📊 Performance Metrics</h2>';
                echo '<div class="metrics-grid">';
                
                echo '<div class="metric-card">';
                echo '<div class="metric-label">Total Processing Time</div>';
                echo '<div class="metric-value">' . $result['metrics']['total_time'] . ' ms</div>';
                echo '</div>';
                
                echo '<div class="metric-card">';
                echo '<div class="metric-label">Sequential Time (Est.)</div>';
                echo '<div class="metric-value">' . $result['metrics']['sequential_time'] . ' ms</div>';
                echo '</div>';
                
                echo '<div class="metric-card highlight">';
                echo '<div class="metric-label">⚡ Speedup</div>';
                echo '<div class="metric-value">' . $result['metrics']['speedup'] . 'x</div>';
                echo '</div>';
                
                echo '<div class="metric-card">';
                echo '<div class="metric-label">Parallel Efficiency</div>';
                echo '<div class="metric-value">' . $result['metrics']['efficiency'] . '%</div>';
                echo '</div>';
                
                echo '<div class="metric-card">';
                echo '<div class="metric-label">Active Nodes</div>';
                echo '<div class="metric-value">' . $result['metrics']['num_nodes'] . '</div>';
                echo '</div>';
                
                echo '<div class="metric-card">';
                echo '<div class="metric-label">Characters Processed</div>';
                echo '<div class="metric-value">' . $result['metrics']['total_chars'] . '</div>';
                echo '</div>';
                
                echo '</div>';
                echo '</div>';
                
                echo '<h2>Proses Distributed Computing</h2>';
                echo '<div class="nodes-container">';
                
                foreach ($result['nodes'] as $node) {
                    echo '<div class="node-card">';
                    echo '<div class="node-header">';
                    echo '<span class="node-name">' . htmlspecialchars($node['name']) . '</span>';
                    echo '<span class="node-status">✓ Selesai</span>';
                    echo '</div>';
                    echo '<div class="node-content">';
                    echo '<p class="node-label">Input Chunk (' . $node['chars'] . ' chars):</p>';
                    echo '<p class="node-text">' . htmlspecialchars($node['input']) . '</p>';
                    echo '<p class="node-label">Output:</p>';
                    echo '<p class="node-text output">' . htmlspecialchars($node['output']) . '</p>';
                    echo '<div class="node-time">';
                    echo '<span class="time-icon">⏱️</span> Processing Time: <strong>' . $node['time'] . ' ms</strong>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '<div class="final-result">';
                echo '<h3>Hasil Akhir</h3>';
                echo '<div class="result-box">';
                echo '<p>' . htmlspecialchars($result['final']) . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <footer>
            <p>Mata Kuliah: Komputasi Paralel dan Terdistribusi</p>
        </footer>
    </div>
</body>
</html>
