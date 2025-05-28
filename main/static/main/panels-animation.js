// panels-animation.js
document.addEventListener('DOMContentLoaded', function() {
    const panels = document.querySelectorAll('.panel-card');

    // Уникальные параметры для каждой панели
    const panelParams = [
        { speed: 0.0003, xAmp: 25, yAmp: 15, rotAmp: 3, xPhase: 0, yPhase: 1.3 },
        { speed: 0.0004, xAmp: 30, yAmp: 20, rotAmp: 2, xPhase: 0.5, yPhase: 0.7 },
        { speed: 0.00035, xAmp: 20, yAmp: 25, rotAmp: 4, xPhase: 1.2, yPhase: 0.3 }
    ];

    // Инициализация панелей
    panels.forEach((panel, index) => {
        const params = panelParams[index % panelParams.length];
        let lastX = 0, lastY = 0;

        // Эффект клика
        panel.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');

            const rect = panel.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            ripple.style.width = ripple.style.height = `${Math.max(rect.width, rect.height)}px`;

            panel.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);

            // Эффект нажатия
            panel.style.transform = `translate(${lastX}px, ${lastY}px) scale(0.95)`;
            setTimeout(() => {
                panel.style.transition = 'transform 0.3s ease-out';
                panel.style.transform = `translate(${lastX}px, ${lastY}px) scale(1)`;
            }, 100);
        });

        // Анимация движения
        function animatePanel(time) {
            const x = Math.sin(time * params.speed + params.xPhase) * params.xAmp;
            const y = Math.cos(time * params.speed * 0.7 + params.yPhase) * params.yAmp;
            const rotate = Math.sin(time * params.speed * 0.5) * params.rotAmp;

            panel.style.transform = `translate(${x}px, ${y}px) rotate(${rotate}deg)`;
            panel.style.transition = 'transform 2s linear';

            lastX = x;
            lastY = y;

            requestAnimationFrame(animatePanel);
        }

        // Запуск анимации с небольшим случайным смещением
        setTimeout(() => {
            requestAnimationFrame(animatePanel);
        }, index * 300);
    });
});