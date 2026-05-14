<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — AutoTech Pro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link href="{{ asset('css/login.css') }}?v=1" rel="stylesheet">
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow bg-glow-red"></div>
<div class="bg-glow bg-glow-red2"></div>
<canvas id="particles-canvas"></canvas>

<div class="page-wrap">
    <div class="login-split">

        <div class="panel-left">
            <div class="panel-left-inner">
                <div class="brand-mark">
                    <div class="brand-gear">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                    <div>
                        <div class="brand-name-left">AutoTech</div>
                        <div class="brand-tagline">Oficina Pro</div>
                    </div>
                </div>

                <div class="panel-headline">
                    Gestão completa<br>da sua oficina.
                    
                </div>

                <ul class="panel-features">
                    <li><i class="bi bi-clipboard2-check"></i> Ordens de Serviço</li>
                    <li><i class="bi bi-box-seam"></i> Controle de Estoque</li>
                    <li><i class="bi bi-people"></i> Gestão de Clientes</li>
                    <li><i class="bi bi-bar-chart-line"></i> Relatórios e Métricas</li>
                    <li><i class="bi bi-shield-check"></i> Controle de Garantias</li>
                </ul>
            </div>

            <div class="panel-version">AutoTech Pro</div>
        </div>

        <div class="panel-right">
            <div class="login-heading">Bem-vindo</div>
            <div class="login-sub">Acesse sua conta para continuar</div>

            @if($errors->any())
            <div class="alert-box">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="login-form">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">E-mail</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input
                            class="field-input"
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="seu@email.com"
                            required
                            autofocus
                            autocomplete="email"
                        >
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Senha</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock field-icon" id="lock-icon"></i>
                        <input
                            class="field-input"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            style="padding-right: 42px"
                        >
                        <button type="button" class="toggle-pass" id="toggle-pass" tabindex="-1">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="row-bottom">
                    <label class="check-wrap">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="check-label">Lembrar acesso</span>
                    </label>
                </div>

                <button type="submit" class="btn-entrar" id="btn-submit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="btn-text">Entrar</span>
                    <div class="btn-spinner"></div>
                </button>
            </form>

            <div style="text-align: center; font-size: 12px; margin-top: 1rem;">
                <span style="color: var(--text3);">Não tem uma conta? </span>
                <a href="{{ route('register') }}" style="color: var(--red); text-decoration: none; font-weight: 600; transition: color .2s;" onmouseover="this.style.color='var(--red-h)'" onmouseout="this.style.color='var(--red)'">Cadastre-se aqui</a>
            </div>

            <div class="divider"><span>AutoTech Pro</span></div>
            <div class="footer-note">© 2026 AutoTech</div>
        </div>

    </div>
</div>

<script>
(function () {
    const canvas = document.getElementById('particles-canvas');
    const ctx    = canvas.getContext('2d');
    let W, H, particles = [];

    function resize() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    function rand(a, b) { return a + Math.random() * (b - a); }

    class Particle {
        constructor() { this.reset(); }
        reset() {
            this.x  = rand(0, W);
            this.y  = rand(0, H);
            this.r  = rand(.8, 2.2);
            this.vx = rand(-.25, .25);
            this.vy = rand(-.4, -.05);
            this.alpha = rand(.12, .45);
            this.fade  = rand(.001, .003);
            this.color = Math.random() > .65 ? `rgba(196,0,0,` : `rgba(255,255,255,`;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = this.color + this.alpha + ')';
            ctx.fill();
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            this.alpha -= this.fade;
            if (this.alpha <= 0 || this.y < -10) this.reset();
        }
    }

    for (let i = 0; i < 90; i++) particles.push(new Particle());

    function drawLines() {
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const d  = Math.sqrt(dx * dx + dy * dy);
                if (d < 90) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(196,0,0,${.06 * (1 - d / 90)})`;
                    ctx.lineWidth = .5;
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }
    }

    function animate() {
        ctx.clearRect(0, 0, W, H);
        drawLines();
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(animate);
    }
    animate();
})();

document.getElementById('toggle-pass').addEventListener('click', function () {
    const inp  = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye';
    }
});

document.getElementById('login-form').addEventListener('submit', function () {
    const btn = document.getElementById('btn-submit');
    btn.classList.add('loading');
    btn.disabled = true;
});

document.getElementById('btn-submit').addEventListener('click', function (e) {
    const btn  = this;
    const rect = btn.getBoundingClientRect();
    const s    = document.createElement('span');
    s.style.cssText = `
        position:absolute; border-radius:50%;
        width:8px; height:8px; background:rgba(255,255,255,.3);
        top:${e.clientY - rect.top - 4}px;
        left:${e.clientX - rect.left - 4}px;
        transform:scale(0);
        animation:ripple .55s linear;
        pointer-events:none;
    `;
    btn.appendChild(s);
    setTimeout(() => s.remove(), 560);
});

(function () {
    const els = document.querySelectorAll('.field-group, .row-bottom, .btn-entrar, .login-heading, .login-sub, .alert-box, .divider, .footer-note');
    els.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px)';
        el.style.transition = 'opacity .35s ease, transform .35s ease';
        el.style.transitionDelay = (120 + i * 70) + 'ms';
        requestAnimationFrame(() => requestAnimationFrame(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }));
    });
})();

const s = document.createElement('style');
s.textContent = '@keyframes ripple { to { transform: scale(28); opacity: 0; } }';
document.head.appendChild(s);
</script>
</body>
</html>
