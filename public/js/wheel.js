aleart("Welcome to the Safety Competition! Spin the wheel to win exciting prizes!");


const sectors = [
    { color: "#f82", label: "Discount 10%" },
    { color: "#0bf", label: "Try Again" },
    { color: "#fb0", label: "Free Shipping" },
    { color: "#0fb", label: "Gift Box" },
    { color: "#b0f", label: "$50 Credit" },
    { color: "#f0b", label: "Try Again" },
    { color: "#bf0", label: "Mega Prize" },
];

const rand = (m, M) => Math.random() * (M - m) + m;
const tot = sectors.length;
const canvas = document.querySelector("#wheel");
const ctx = canvas.getContext("2d");
const dia = canvas.width;
const rad = dia / 2;
const PI = Math.PI;
const TAU = 2 * PI;
const arc = TAU / tot;

const friction = 0.995; 
let angVel = 0; 
let ang = 0; 
let isSpinning = false;

sectors.forEach((sector, i) => {
    const ang = arc * i;
    ctx.save();
    ctx.beginPath();
    ctx.fillStyle = sector.color;
    ctx.moveTo(rad, rad);
    ctx.arc(rad, rad, rad, ang, ang + arc);
    ctx.lineTo(rad, rad);
    ctx.fill();
    
    // Draw Text
    ctx.translate(rad, rad);
    ctx.rotate(ang + arc / 2);
    ctx.textAlign = "right";
    ctx.fillStyle = "#fff";
    ctx.font = "bold 16px sans-serif";
    ctx.fillText(sector.label, rad - 10, 5);
    ctx.restore();
});

function getIndex() {
    return Math.floor(tot - (ang % TAU) / TAU * tot) % tot;
}

function engine() {
    if (!isSpinning) return;

    angVel *= friction; 
    ang += angVel; 

    canvas.style.transform = `rotate(${ang}rad)`;

    if (angVel < 0.002) {
        isSpinning = false;
        const winIndex = getIndex();
        alert(`Congratulations! You won: ${sectors[winIndex].label}`);
    } else {
        requestAnimationFrame(engine);
    }
}

document.querySelector("#spin-btn").addEventListener("click", () => {
    if (!isSpinning) {
        isSpinning = true;
        angVel = rand(0.2, 0.35); // Random spin speed
        engine();
    }
});