// Script buat animasi dan fitur-fitur interaktif
// Semua dijalanin pas DOM udah fully loaded
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('cipherForm');
    const inputText = document.getElementById('inputText');
    const shiftKey = document.getElementById('shiftKey');
    const submitBtn = document.querySelector('.btn-submit');

    // === Character Counter ===
    // Bikin element baru buat tampilin jumlah karakter
    const charCounter = document.createElement('div');
    charCounter.className = 'char-counter';
    charCounter.textContent = '0 karakter';
    inputText.parentNode.appendChild(charCounter);

    // Update counter setiap kali user ngetik
    inputText.addEventListener('input', function () {
        const charCount = this.value.length;
        charCounter.textContent = charCount + ' karakter';

        // Tambahin class 'active' kalau ada teks
        if (charCount > 0) {
            charCounter.classList.add('active');
        } else {
            charCounter.classList.remove('active');
        }
    });

    // === Node Count Slider ===
    // Slider buat ngatur berapa banyak node yang mau dipake
    const numNodesSlider = document.getElementById('numNodes');
    const nodeCountDisplay = document.querySelector('.node-count-display');

    if (numNodesSlider && nodeCountDisplay) {
        numNodesSlider.addEventListener('input', function () {
            const nodeCount = this.value;
            nodeCountDisplay.textContent = nodeCount + ' Nodes';

            // Kasih efek pulse biar keliatan ada perubahan
            nodeCountDisplay.classList.add('pulse');
            setTimeout(() => nodeCountDisplay.classList.remove('pulse'), 300);
        });
    }

    // === Clear Button ===
    // Tombol buat reset semua input ke default
    const clearBtn = document.createElement('button');
    clearBtn.type = 'button';
    clearBtn.className = 'btn-clear';
    clearBtn.innerHTML = '🗑️ Hapus Semua';

    clearBtn.addEventListener('click', function () {
        // Reset semua form ke nilai awal
        inputText.value = '';
        shiftKey.value = '3';
        charCounter.textContent = '0 karakter';
        charCounter.classList.remove('active');

        // Kasih animasi shake biar user tau tombolnya diklik
        this.classList.add('clicked');
        setTimeout(() => this.classList.remove('clicked'), 300);
    });
    submitBtn.parentNode.appendChild(clearBtn);

    // === Shift Key Display ===
    // Badge visual buat nampilin nilai shift key
    const shiftDisplay = document.createElement('div');
    shiftDisplay.className = 'shift-display';
    shiftDisplay.textContent = 'Shift: ' + shiftKey.value;
    shiftKey.parentNode.appendChild(shiftDisplay);

    // Update display setiap kali user ubah shift value
    shiftKey.addEventListener('input', function () {
        shiftDisplay.textContent = 'Shift: ' + this.value;

        // Animasi pulse biar lebih interactive
        shiftDisplay.classList.add('pulse');
        setTimeout(() => shiftDisplay.classList.remove('pulse'), 300);
    });

    // === Form Submission ===
    // Tampilin loading animation pas form disubmit
    form.addEventListener('submit', function (e) {
        submitBtn.classList.add('loading');
        submitBtn.innerHTML = '<span class="spinner"></span> Memproses...';
    });

    // === Node Cards Animation ===
    // Animasi buat node cards yang udah ada (hasil processing)
    const nodeCards = document.querySelectorAll('.node-card');
    if (nodeCards.length > 0) {
        // Fade in satu-satu dengan delay
        nodeCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('fade-in');
            }, index * 150); // Delay 150ms antar card
        });

        // === Copy to Clipboard ===
        // Fitur copy hasil ke clipboard
        const finalResult = document.querySelector('.final-result');
        if (finalResult) {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'btn-copy';
            copyBtn.innerHTML = '📋 Salin Hasil';

            copyBtn.addEventListener('click', function () {
                const resultText = document.querySelector('.result-box p').textContent;

                // Pake Clipboard API modern
                navigator.clipboard.writeText(resultText).then(() => {
                    copyBtn.innerHTML = '✓ Tersalin!';
                    copyBtn.classList.add('success');

                    // Balik ke teks normal setelah 2 detik
                    setTimeout(() => {
                        copyBtn.innerHTML = '📋 Salin Hasil';
                        copyBtn.classList.remove('success');
                    }, 2000);
                });
            });
            finalResult.querySelector('.result-box').appendChild(copyBtn);
        }

        // Tambahin pulse effect ke status badge
        nodeCards.forEach(card => {
            const statusBadge = card.querySelector('.node-status');
            statusBadge.classList.add('pulse-success');
        });
    }

    // === Typing Animation ===
    // Efek ngetik satu-satu buat judul halaman
    const title = document.querySelector('header h1');
    const fullTitle = title.textContent;
    title.textContent = '';
    title.style.opacity = '1';

    let charIndex = 0;
    function typeNextChar() {
        if (charIndex < fullTitle.length) {
            title.textContent += fullTitle.charAt(charIndex);
            charIndex++;
            setTimeout(typeNextChar, 100); // Delay 100ms per karakter
        }
    }
    typeNextChar();

    // === Parallax Scrolling ===
    // Header jadi agak fade out pas di-scroll
    window.addEventListener('scroll', function () {
        const scrollDistance = window.pageYOffset;
        const header = document.querySelector('header');

        if (header) {
            // Transform dan fade out berdasarkan scroll
            header.style.transform = 'translateY(' + scrollDistance * 0.5 + 'px)';
            header.style.opacity = 1 - scrollDistance / 300;
        }
    });

    // === Input Section Reveal ===
    // Smooth reveal animation pas pertama kali muncul
    const inputSection = document.querySelector('.input-section');
    inputSection.style.opacity = '0';
    inputSection.style.transform = 'translateY(20px)';

    setTimeout(() => {
        inputSection.style.transition = 'all 0.6s ease';
        inputSection.style.opacity = '1';
        inputSection.style.transform = 'translateY(0)';
    }, 100);

    // === Mode Select Animation ===
    // Kasih highlight pas user ganti mode
    const modeSelect = document.getElementById('mode');
    modeSelect.addEventListener('change', function () {
        this.classList.add('changed');
        setTimeout(() => this.classList.remove('changed'), 300);
    });

    // === Easter Egg ===
    // Double click 3x buat surprise message :)
    const subtitle = document.querySelector('.subtitle');
    let doubleClickCount = 0;

    subtitle.addEventListener('dblclick', function () {
        doubleClickCount++;

        if (doubleClickCount >= 3) {
            this.textContent = '🎉 Keren! Kamu menemukan easter egg!';
            this.style.fontSize = '1.3em';

            // Reset balik ke teks normal setelah 3 detik
            setTimeout(() => {
                this.textContent = 'Simulasi Komputasi Paralel dan Terdistribusi';
                this.style.fontSize = '1.1em';
                doubleClickCount = 0;
            }, 3000);
        }
    });

    // === Cursor Particle Trail Effect ===
    // Partikel kecil yang nge-follow cursor pas mouse gerak
    const particles = [];
    const maxParticles = 15;

    class Particle {
        constructor(x, y) {
            this.x = x;
            this.y = y;
            this.size = Math.random() * 5 + 2;
            this.speedX = Math.random() * 2 - 1;
            this.speedY = Math.random() * 2 - 1;
            this.life = 30;
            this.maxLife = 30;

            this.element = document.createElement('div');
            this.element.className = 'particle';
            this.element.style.width = this.size + 'px';
            this.element.style.height = this.size + 'px';
            this.element.style.left = this.x + 'px';
            this.element.style.top = this.y + 'px';
            document.body.appendChild(this.element);
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            this.life--;

            const opacity = this.life / this.maxLife;
            this.element.style.left = this.x + 'px';
            this.element.style.top = this.y + 'px';
            this.element.style.opacity = opacity;
            this.element.style.transform = `scale(${opacity})`;
        }

        destroy() {
            this.element.remove();
        }
    }

    let mouseX = 0;
    let mouseY = 0;

    document.addEventListener('mousemove', function (e) {
        mouseX = e.clientX;
        mouseY = e.clientY + window.scrollY;

        // Bikin partikel baru di posisi mouse
        if (particles.length < maxParticles) {
            particles.push(new Particle(mouseX, mouseY));
        }
    });

    // Animation loop buat update semua partikel
    function animateParticles() {
        for (let i = particles.length - 1; i >= 0; i--) {
            particles[i].update();

            // Hapus partikel yang udah mati
            if (particles[i].life <= 0) {
                particles[i].destroy();
                particles.splice(i, 1);
            }
        }

        requestAnimationFrame(animateParticles);
    }

    animateParticles();
});
