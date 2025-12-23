<?php

class CipherProcessor {
    private $numNodes = 3;
    
    // Fungsi utama buat proses text dengan simulasi paralel processing
    // Ini bakal split text jadi beberapa chunk terus proses di tiap "node"
    public function processText($text, $shift, $mode, $numNodes = 3) {
        // Catat waktu mulai biar bisa hitung total processing time
        $startTime = microtime(true);
        
        // Bagi text jadi beberapa bagian sesuai jumlah node
        $chunks = $this->splitText($text, $numNodes);
        
        $nodeResults = [];
        $totalChars = 0;
        
        // Loop tiap chunk dan proses di "node" masing-masing
        for ($i = 0; $i < count($chunks); $i++) {
            $currentChunk = $chunks[$i];
            $nodeStartTime = microtime(true);
            
            // Kasih delay random buat simulasi processing time yang realistis
            // Dalam real distributed system, tiap node punya waktu proses yang beda-beda
            usleep(rand(100000, 300000)); // Delay 100-300ms
            
            // Proses enkripsi atau dekripsi sesuai mode yang dipilih
            if ($mode === 'encrypt') {
                $processedText = $this->encrypt($currentChunk, $shift);
            } else {
                $processedText = $this->decrypt($currentChunk, $shift);
            }
            
            $nodeEndTime = microtime(true);
            $processingTime = ($nodeEndTime - $nodeStartTime) * 1000; // Convert ke milisecond
            
            $totalChars += strlen($currentChunk);
            
            // Simpen hasil dari node ini
            $nodeResults[] = [
                'name' => 'Node ' . ($i + 1),
                'input' => $currentChunk,
                'output' => $processedText,
                'time' => round($processingTime, 2),
                'chars' => strlen($currentChunk)
            ];
        }
        
        $endTime = microtime(true);
        $totalProcessingTime = ($endTime - $startTime) * 1000;
        
        // Hitung estimasi waktu kalau diproses sequential (satu-satu)
        // Ini buat nunjukin benefit dari parallel processing
        $estimatedSequentialTime = 0;
        foreach ($nodeResults as $node) {
            $estimatedSequentialTime += $node['time'];
        }
        
        // Speedup = seberapa cepet parallel dibanding sequential
        // Kalau speedup 2x berarti parallel 2x lebih cepet
        $speedup = $estimatedSequentialTime / $totalProcessingTime;
        
        // Gabungin semua output dari tiap node jadi satu hasil akhir
        $finalResult = '';
        foreach ($nodeResults as $node) {
            $finalResult .= $node['output'];
        }
        
        // Return semua data yang diperlukan
        return [
            'nodes' => $nodeResults,
            'final' => $finalResult,
            'metrics' => [
                'total_time' => round($totalProcessingTime, 2),
                'sequential_time' => round($estimatedSequentialTime, 2),
                'speedup' => round($speedup, 2),
                'total_chars' => $totalChars,
                'num_nodes' => count($chunks),
                'efficiency' => round(($speedup / count($chunks)) * 100, 1)
            ]
        ];
    }
    
    // Fungsi buat bagi text jadi beberapa bagian yang kurang lebih sama besar
    private function splitText($text, $parts) {
        $textLength = strlen($text);
        $chunkSize = ceil($textLength / $parts);
        $chunks = [];
        
        for ($i = 0; $i < $parts; $i++) {
            $startPos = $i * $chunkSize;
            $chunk = substr($text, $startPos, $chunkSize);
            
            // Cek dulu apakah chunk valid (bukan false atau string kosong)
            if ($chunk !== false && $chunk !== '') {
                $chunks[] = $chunk;
            }
        }
        
        return $chunks;
    }
    
    // Enkripsi pakai Caesar Cipher - geser tiap huruf sebanyak shift
    private function encrypt($text, $shift) {
        $result = '';
        $length = strlen($text);
        
        for ($i = 0; $i < $length; $i++) {
            $char = $text[$i];
            
            // Huruf besar (A-Z)
            if (ctype_upper($char)) {
                $shifted = ((ord($char) - 65 + $shift) % 26) + 65;
                $result .= chr($shifted);
            } 
            // Huruf kecil (a-z)
            elseif (ctype_lower($char)) {
                $shifted = ((ord($char) - 97 + $shift) % 26) + 97;
                $result .= chr($shifted);
            } 
            // Karakter lain (spasi, angka, simbol) tetep sama
            else {
                $result .= $char;
            }
        }
        
        return $result;
    }
    
    // Dekripsi - kebalikan dari enkripsi, geser balik
    private function decrypt($text, $shift) {
        $result = '';
        $length = strlen($text);
        
        for ($i = 0; $i < $length; $i++) {
            $char = $text[$i];
            
            if (ctype_upper($char)) {
                // Tambah 26 biar hasil modulo selalu positif
                $shifted = ((ord($char) - 65 - $shift + 26) % 26) + 65;
                $result .= chr($shifted);
            } 
            elseif (ctype_lower($char)) {
                $shifted = ((ord($char) - 97 - $shift + 26) % 26) + 97;
                $result .= chr($shifted);
            } 
            else {
                $result .= $char;
            }
        }
        
        return $result;
    }
}

