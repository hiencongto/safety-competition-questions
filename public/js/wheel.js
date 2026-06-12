// wheel.js - Clean slot machine + Q&A system (FIXED VERSION)
(function () {

    // ================= DOM =================
    const overlay = document.getElementById('slotOverlay');
    const slotContainer = document.getElementById('slotContainer');

    const toggleBtn = document.getElementById('toggleSlotBtn');
    const spinBtn = document.getElementById('spinBtn');
    const nextBtn = document.getElementById('nextQuestionBtn');
    const resetBtn = document.getElementById('resetQuestionBtn');

    const reel1 = document.getElementById('reel1');
    const reel2 = document.getElementById('reel2');
    const reel3 = document.getElementById('reel3');

    const questionBox = document.getElementById('questionBox');
    const questionIdBox = document.getElementById('questionIdBox');
    const answerBox = document.getElementById('answerBox');
    const remainInfo = document.getElementById('remainInfo');

    // ================= DATA =================
    const allQuestions = window.allQuestions || [];

    // Ensure IDs are numbers to avoid mismatch bug
    let remainingIds = allQuestions.map(q => Number(q.id));

    let isSpinning = false;
    let spinIntervals = [];
    let hideTimeout = null;

    const spinAudio = new Audio(window.wheelSpinAudioUrl || '/sound/wheel-spin.mp3');
    spinAudio.loop = true;
    spinAudio.preload = 'auto';
    spinAudio.volume = 0.8;

    // Current question state
    let currentQuestion = null;

    // ================= UI =================

    function updateRemainInfo() {
        const hasRemaining = remainingIds.length > 0;

        remainInfo.innerText = `Câu còn lại: ${remainingIds.length}`;

        spinBtn.disabled = !hasRemaining;
        nextBtn.disabled = !hasRemaining;

        spinBtn.innerText = hasRemaining ? "QUAY" : "HẾT";
        nextBtn.innerText = hasRemaining ? "Câu hỏi tiếp theo" : "Hết câu hỏi";
    }

    // Pick random question ID
    function pickRandomId() {
        if (remainingIds.length === 0) return null;

        const index = Math.floor(Math.random() * remainingIds.length);
        const id = remainingIds[index];

        remainingIds.splice(index, 1);
        // updateRemainInfo();

        return id;
    }

    // ================= SLOT ANIMATION =================

    function numberToDigits(num) {
        return num.toString().padStart(3, '0').split('').map(Number);
    }

    function spinReel(el, target, duration, done) {
        let interval = setInterval(() => {
            el.innerText = Math.floor(Math.random() * 10);
        }, 50);

        spinIntervals.push(interval);

        setTimeout(() => {
            clearInterval(interval);
            el.innerText = target;
            done?.();
        }, duration);
    }

    function spinAll(digits) {
        return new Promise(resolve => {
            let count = 0;
            const reels = [reel1, reel2, reel3];

            reels.forEach((r, i) => {
                setTimeout(() => {
                    spinReel(r, digits[i], 800, () => {
                        count++;
                        if (count === 3) resolve();
                    });
                }, i * 800);
            });
        });
    }

    function stopSpin() {
        spinIntervals.forEach(clearInterval);
        spinIntervals = [];
        if (!spinAudio.paused) {
            spinAudio.pause();
            spinAudio.currentTime = 0;
        }
    }

    function resetReels() {
        reel1.innerText = "0";
        reel2.innerText = "0";
        reel3.innerText = "0";
    }

    // ================= ANSWER SYSTEM =================

    // Show answer button only (NOT the answer itself)
    function showAnswerButton() {
        answerBox.innerHTML = "";

        const btn = document.createElement("button");
        btn.className = "view-answer-btn";
        btn.innerText = "Xem đáp án";

        btn.addEventListener("click", async () => {
            if (!currentQuestion?.answer) {
                answerBox.innerText = "Chưa có đáp án";
                return;
            }

            // reveal answer
            answerBox.innerText = currentQuestion.answer;

            // send POST to server to mark as answered
            console.log("Marking question as answered:", currentQuestion.id);
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                await fetch('/HMT/answered', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    },
                    body: JSON.stringify({ id: currentQuestion.id })
                });

                console.log("Marked as answered:", currentQuestion.id);
                updateRemainInfo();
            } catch (err) {
                console.error('Failed to mark answered:', err);
            }
        });

        answerBox.appendChild(btn);
    }

    function resetAnswer() {
        answerBox.innerHTML = "";
    }

    // ================= SLOT UI =================

    function showSlot() {
        overlay.style.display = "block";
        slotContainer.style.display = "block";

        resetReels();
        stopSpin();

        isSpinning = false;
        toggleBtn.innerText = "Tắt máy quay";
    }

    function hideSlot() {
        overlay.style.display = "none";
        slotContainer.style.display = "none";

        resetReels();
        stopSpin();

        isSpinning = false;
        toggleBtn.innerText = "Mở máy quay";
    }

    // ================= MAIN LOGIC =================

    async function startSpin() {
        if (isSpinning) return;
        if (remainingIds.length === 0) return;

        isSpinning = true;
        spinBtn.disabled = true;
        nextBtn.disabled = true;

        try {
            spinAudio.currentTime = 0;
            await spinAudio.play();
        } catch (err) {
            console.warn('Wheel spin audio could not play:', err);
        }

        const selectedId = Number(pickRandomId());

        // FIX: ensure correct match
        const question = allQuestions.find(q => Number(q.id) === selectedId);

        console.log("Selected:", selectedId);
        console.log("Question:", question);

        if (!question) {
            console.error("Question not found!");
            isSpinning = false;
            return;
        }

        currentQuestion = question;

        // Spin reels
        const digits = numberToDigits(selectedId);
        await spinAll(digits);

        // Stop spin sound after reels finish
        if (!spinAudio.paused) {
            spinAudio.pause();
            spinAudio.currentTime = 0;
        }

        // Show question and its ID
        questionBox.innerText = `${question.question}`;
        questionIdBox.innerText = `${selectedId}`;

        // IMPORTANT FIX: store full object (not only string)
        resetAnswer();
        showAnswerButton();

        isSpinning = false;
        spinBtn.disabled = false;
        nextBtn.disabled = false;

        // Auto hide
        clearTimeout(hideTimeout);
        hideTimeout = setTimeout(() => hideSlot(), 2500);
    }

    // ================= NEXT QUESTION =================

    async function nextQuestion() {
        if (isSpinning) return;
        if (remainingIds.length === 0) return;

        hideSlot();
        showSlot();
        // await startSpin();
    }

    async function resetQuestions() {
        if (!confirm('Bạn có chắc muốn reset tất cả câu hỏi về trạng thái chưa trả lời không?')) {
            return;
        }

        resetBtn.disabled = true;
        resetBtn.innerText = 'Đang reset...';

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch('/HMT/reset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                }
            });

            if (!response.ok) {
                throw new Error('Reset failed');
            }

            window.location.reload();
        } catch (err) {
            console.error('Failed to reset questions:', err);
            alert('Reset câu hỏi thất bại. Vui lòng thử lại.');
            resetBtn.disabled = false;
            resetBtn.innerText = 'Reset câu hỏi';
        }
    }

    // ================= EVENTS =================

    toggleBtn.addEventListener("click", () => {
        if (slotContainer.style.display === "block") {
            hideSlot();
        } else {
            showSlot();
        }
    });

    spinBtn.addEventListener("click", startSpin);
    nextBtn.addEventListener("click", nextQuestion);
    resetBtn.addEventListener("click", resetQuestions);

    // ================= INIT =================

    updateRemainInfo();
    hideSlot();
    resetAnswer();

    questionBox.innerText =
        "Nhấn QUAY để bắt đầu!";

})();