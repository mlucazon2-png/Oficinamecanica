<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — AutoTech Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red:       #C40000;
            --red-h:     #E50000;
            --red-dim:   rgba(196,0,0,.12);
            --red-glow:  rgba(196,0,0,.22);
            --bg:        #060606;
            --surface:   #0E0E0E;
            --surface2:  #161616;
            --border:    rgba(255,255,255,.07);
            --border2:   rgba(255,255,255,.12);
            --text:      #F0F0F0;
            --text2:     #BBBBBB;
            --text3:     #666;
        }

        html, body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow: auto;
        }

        #particles-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 52px 52px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
            pointer-events: none;
        }

        .bg-glow {
            position: fixed;
            z-index: 0;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }
        .bg-glow-red {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(196,0,0,.18) 0%, transparent 70%);
            top: -100px; left: -100px;
            animation: floatGlow 8s ease-in-out infinite;
        }
        .bg-glow-red2 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(196,0,0,.10) 0%, transparent 70%);
            bottom: -80px; right: -50px;
            animation: floatGlow 10s ease-in-out infinite reverse;
        }

        @keyframes floatGlow {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(30px, -20px) scale(1.05); }
            66%       { transform: translate(-20px, 30px) scale(.96); }
        }

        .page-wrap {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-split {
            display: flex;
            width: 100%;
            max-width: 860px;
            min-height: auto;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--border2);
            box-shadow:
                0 0 0 1px rgba(196,0,0,.08),
                0 32px 80px rgba(0,0,0,.7),
                0 0 120px rgba(196,0,0,.06);
            animation: cardIn .6s cubic-bezier(.34,1.4,.64,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(28px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .panel-left {
            width: 44%;
            background: var(--red);
            padding: 3rem 2.2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(0,0,0,.25) 0%, transparent 50%),
                radial-gradient(circle at 80% 10%, rgba(255,255,255,.08) 0%, transparent 45%);
        }
        .panel-left::after {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 220px; height: 220px;
            border: 40px solid rgba(255,255,255,.06);
            border-radius: 50%;
        }

        .panel-left-inner { position: relative; z-index: 1; }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2.5rem;
        }
        .brand-gear {
            width: 44px; height: 44px;
            background: rgba(0,0,0,.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.15);
        }
        .brand-gear i { font-size: 22px; color: #fff; animation: spin 12s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .brand-name-left {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: .03em;
            line-height: 1;
        }
        .brand-tagline {
            font-size: 10px;
            color: rgba(255,255,255,.65);
            text-transform: uppercase;
            letter-spacing: .18em;
            margin-top: 3px;
        }

        .panel-headline {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1rem;
            letter-spacing: -.01em;
        }
        .panel-headline span {
            display: block;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            color: rgba(255,255,255,.65);
            letter-spacing: 0;
            margin-top: 6px;
        }

        .panel-features { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .panel-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12.5px;
            color: rgba(255,255,255,.8);
        }
        .panel-features li i {
            width: 22px; height: 22px;
            background: rgba(255,255,255,.15);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .panel-version {
            font-size: 10px;
            color: rgba(255,255,255,.35);
            font-family: 'DM Mono', monospace;
            letter-spacing: .08em;
            margin-top: 2rem;
        }

        .panel-right {
            flex: 1;
            background: var(--surface);
            padding: 2.8rem 2.4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-heading {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }
        .login-sub {
            font-size: 13px;
            color: var(--text3);
            margin-bottom: 2rem;
        }

        .alert-box {
            background: rgba(196,0,0,.12);
            border: 1px solid rgba(196,0,0,.3);
            border-radius: 10px;
            padding: .65rem .9rem;
            font-size: 12.5px;
            color: #E06060;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.4rem;
            animation: shakeX .4s ease;
        }
        @keyframes shakeX {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-6px); }
            40%      { transform: translateX(6px); }
            60%      { transform: translateX(-4px); }
            80%      { transform: translateX(4px); }
        }

        .field-group { margin-bottom: 1.1rem; }
        .field-label {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text2);
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: 7px;
            display: block;
        }
        .field-wrap {
            position: relative;
        }
        .field-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--text3);
            font-size: 15px;
            pointer-events: none;
            transition: color .2s;
        }
        .field-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
        }
        .field-input::placeholder { color: #3a3a3a; }
        .field-input:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(196,0,0,.14);
        }
        .field-input:focus + .field-line { width: 100%; }
        .field-input:focus ~ .field-icon { color: var(--red); }

        .toggle-pass {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text3);
            cursor: pointer;
            font-size: 15px;
            padding: 0;
            transition: color .2s;
        }
        .toggle-pass:hover { color: var(--text2); }

        .btn-entrar {
            width: 100%;
            padding: 13px;
            background: var(--red);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .05em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background .2s, transform .15s, box-shadow .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-entrar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.12) 0%, transparent 55%);
            opacity: 0;
            transition: opacity .2s;
        }
        .btn-entrar:hover {
            background: var(--red-h);
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(196,0,0,.4);
        }
        .btn-entrar:hover::before { opacity: 1; }
        .btn-entrar:active { transform: translateY(0); box-shadow: none; }

        .btn-entrar .btn-spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }
        .btn-entrar.loading .btn-text { display: none; }
        .btn-entrar.loading .btn-spinner { display: block; }
        .btn-entrar.loading i { display: none; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.4rem 0 .2rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .divider span {
            font-size: 10px;
            color: var(--text3);
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .footer-note {
            text-align: center;
            font-size: 10.5px;
            color: #333;
            font-family: 'DM Mono', monospace;
            margin-top: 1.4rem;
            letter-spacing: .06em;
        }

        .login-link {
            text-align: center;
            font-size: 12px;
            margin-top: 1rem;
        }

        .login-link a {
            color: var(--red);
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }

        .login-link a:hover {
            color: var(--red-h);
        }

        .success-message {
            background: rgba(76, 175, 80, .12);
            border: 1px solid rgba(76, 175, 80, .3);
            color: #81c784;
        }

        .error-message {
            background: var(--red-dim);
            border: 1px solid rgba(196,0,0,.3);
            color: #E06060;
        }

        @media (max-width: 640px) {
            .panel-left { display: none; }
            .login-split { max-width: 400px; }
            .panel-right { padding: 2rem 1.6rem; }
        }
    </style>
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
                    Cadastre-se e comece<br>a solicitar ordens.
                    <span>Como cliente, você poderá fazer requisições de OS diretamente</span>
                </div>

                <ul class="panel-features">
                    <li><i class="bi bi-clipboard2-check"></i> Solicitar Ordens de Serviço</li>
                    <li><i class="bi bi-car-front"></i> Gerenciar seus Veículos</li>
                    <li><i class="bi bi-shield-check"></i> Acompanhar Garantias</li>
                    <li><i class="bi bi-file-earmark-text"></i> Ver Histórico de Serviços</li>
                    <li><i class="bi bi-bell"></i> Receber Atualizações</li>
                </ul>
            </div>

            <div class="panel-version">AutoTech Pro</div>
        </div>

        <div class="panel-right">
            <div class="login-heading">Criar Conta</div>
            <div class="login-sub">Preencha os dados abaixo para se cadastrar</div>

            @if($errors->any())
            <div class="alert-box error-message">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="alert-box success-message">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" id="register-form">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="name">Nome Completo</label>
                    <div class="field-wrap">
                        <i class="bi bi-person field-icon"></i>
                        <input
                            class="field-input"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Seu nome completo"
                            required
                            autofocus
                            autocomplete="name"
                        >
                    </div>
                    @error('name')
                    <small style="color: #E06060; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

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
                            autocomplete="email"
                        >
                    </div>
                    @error('email')
                    <small style="color: #E06060; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
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
                            autocomplete="new-password"
                            style="padding-right: 42px"
                        >
                        <button type="button" class="toggle-pass" id="toggle-pass" tabindex="-1">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                    </div>
                    @error('password')
                    <small style="color: #E06060; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="cpf">CPF</label>
                    <div class="field-wrap">
                        <i class="bi bi-person-badge field-icon"></i>
                        <input
                            class="field-input"
                            type="text"
                            id="cpf"
                            name="cpf"
                            value="{{ old('cpf') }}"
                            placeholder="00000000000"
                            required
                            autocomplete="off"
                        >
                    </div>
                    @error('cpf')
                    <small style="color: #E06060; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="telefone">Telefone</label>
                    <div class="field-wrap">
                        <i class="bi bi-telephone field-icon"></i>
                        <input
                            class="field-input"
                            type="text"
                            id="telefone"
                            name="telefone"
                            value="{{ old('telefone') }}"
                            placeholder="(00) 00000-0000"
                            required
                            autocomplete="off"
                        >
                    </div>
                    @error('telefone')
                    <small style="color: #E06060; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="password_confirmation">Confirmar Senha</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock field-icon"></i>
                        <input
                            class="field-input"
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                            style="padding-right: 42px"
                        >
                    </div>
                </div>

                <button type="submit" class="btn-entrar" id="btn-submit">

                    <i class="bi bi-person-plus"></i>
                    <span class="btn-text">Criar Conta</span>
                    <div class="btn-spinner"></div>
                </button>
            </form>

            <div class="login-link">
                Já tem uma conta? <a href="{{ route('login') }}">Faça login</a>
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

document.getElementById('register-form').addEventListener('submit', function () {
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
    const els = document.querySelectorAll('.field-group, .btn-entrar, .login-heading, .login-sub, .alert-box, .login-link, .divider, .footer-note');
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
