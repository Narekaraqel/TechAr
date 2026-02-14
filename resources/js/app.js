import './bootstrap';


const canvas = document.getElementById('bgCanvas');
const ctx = canvas.getContext('2d');

let particles = [];

function init() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    particles = [];
    for (let i = 0; i < 80; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 2 + 1,
            speedX: Math.random() * 0.5 - 0.25,
            speedY: Math.random() * 0.5 - 0.25
        });
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Создаем радиальный градиент для фона
    let gradient = ctx.createRadialGradient(
        canvas.width/2, canvas.height/2, 0,
        canvas.width/2, canvas.height/2, canvas.width
    );
    gradient.addColorStop(0, '#0d1b2a');
    gradient.addColorStop(1, '#050a10');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    particles.forEach(p => {
        ctx.fillStyle = 'rgba(0, 210, 255, 0.3)';
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();

        p.x += p.speedX;
        p.y += p.speedY;

        if (p.x < 0 || p.x > canvas.width) p.speedX *= -1;
        if (p.y < 0 || p.y > canvas.height) p.speedY *= -1;
    });
    requestAnimationFrame(animate);
}

window.addEventListener('resize', init);
init();
animate();