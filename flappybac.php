<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>Flappy BAC · 80s</title>
<style>
  :root{
    --bac:#e10600; /* rojo BAC */
    --bg1:#0b0d1a; --bg2:#1b0033; --bg3:#000;
    --neon:#ff6bd6; --cy:#00f0ff; --gold:#ffd700;
  }
  *{box-sizing:border-box}
  html,body{height:100%;margin:0;background:radial-gradient(120% 120% at 50% 0%, var(--bg2) 0%, var(--bg1) 60%, var(--bg3) 100%);color:#fff;font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Arial, sans-serif}

  .scan{position:fixed;inset:0;pointer-events:none;opacity:.15;background:repeating-linear-gradient(0deg, rgba(255,255,255,.03) 0 1px, transparent 1px 3px)}

  .wrap{max-width:480px;margin:0 auto;padding:12px}
  .title{margin:10px 0 6px;text-align:center;text-transform:uppercase;font-weight:900;letter-spacing:.08em;text-shadow:0 0 6px var(--neon),0 0 12px var(--neon)}

  .hud{display:flex;gap:8px;justify-content:space-between;margin-bottom:8px}
  .pill{border:2px solid var(--cy);padding:6px 10px;border-radius:999px;font-weight:800;box-shadow:0 0 10px rgba(0,240,255,.3)}
  .best{border-color:var(--neon);box-shadow:0 0 10px rgba(255,107,214,.3)}

  .game{position:relative;width:100%;height:74vh;max-height:720px;border:3px solid var(--bac);border-radius:18px;overflow:hidden;box-shadow:0 0 16px rgba(225,6,0,.5), inset 0 0 12px rgba(225,6,0,.35);background:
    linear-gradient(180deg, rgba(255,255,255,.04) 0 2px, transparent 2px 80px),
    radial-gradient(50% 60% at 50% 0%, rgba(255,255,255,.05), transparent 60%),
    linear-gradient(180deg, rgba(225,6,0,.06), rgba(225,6,0,.02));
    background-size:100% 80px, 100% 100%, 100% 100%;
    animation: bgScroll 1.6s linear infinite;
    touch-action:none; -ms-touch-action:none;
  }
  @keyframes bgScroll{to{background-position-y:80px, 0, 0}}

  .bird{position:absolute;left:22%;top:45%;width:58px;height:40px;transform-origin:center;will-change:transform, top;
    background:linear-gradient(180deg, #fff, #eaeaea);
    border:3px solid var(--bac);border-radius:10px;box-shadow:0 0 12px rgba(225,6,0,.55), inset 0 0 8px rgba(225,6,0,.25)}
  .bird:before{content:"BAC";position:absolute;inset:0;display:grid;place-items:center;font-weight:900;color:var(--bac);text-shadow:0 0 6px rgba(225,6,0,.8)}
  .wing{position:absolute;right:-10px;top:12px;width:18px;height:18px;border:2px solid var(--cy);border-radius:50%;box-shadow:0 0 8px rgba(0,240,255,.4)}
  .flap{animation:flap .25s ease-in-out infinite}
  @keyframes flap{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}

  .pipe{position:absolute;top:0;bottom:0;width:70px;will-change:transform;filter:drop-shadow(0 0 10px rgba(0,240,255,.25))}
  .pTop, .pBottom{position:absolute;left:0;width:100%;background:linear-gradient(90deg, rgba(0,240,255,.25), rgba(0,240,255,.6), rgba(0,240,255,.25));border:2px solid var(--cy);}
  .pTop{top:0;border-bottom:6px solid var(--neon)}
  .pBottom{bottom:0;border-top:6px solid var(--neon)}

  .ground{position:absolute;left:0;right:0;bottom:0;height:14%;background:
    repeating-linear-gradient(90deg, rgba(255,255,255,.06) 0 10px, transparent 10px 20px),
    linear-gradient(0deg, rgba(225,6,0,.25), rgba(225,6,0,.05));
    border-top:3px solid var(--bac);
  }

  .overlay{position:absolute;inset:0;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.72); touch-action:none;-ms-touch-action:none}
  .card{background:linear-gradient(180deg, rgba(0,240,255,.1), rgba(255,107,214,.1));border:2px solid var(--cy);border-radius:16px;padding:18px 16px;max-width:90%;text-align:center;box-shadow:0 0 18px rgba(0,240,255,.3)}
  .btn{display:inline-block;margin-top:10px;padding:10px 16px;border:2px solid var(--neon);border-radius:999px;font-weight:900;letter-spacing:.05em;background:transparent;color:#fff;text-decoration:none}
  .btn:active{transform:translateY(2px)}

  .hint{margin-top:6px;opacity:.85}
  .blink{animation:blink 1s step-end infinite}
  @keyframes blink{50%{opacity:.35}}

  @media (max-height:620px){.game{height:66vh}}
</style>
</head>
<body>
<div class="wrap">
  <h1 class="title">FLAPPY BAC · 80S</h1>
  <div class="hud">
    <div class="pill"><strong>Puntaje:</strong> <span id="score">0</span></div>
    <div class="pill best"><strong>Récord:</strong> <span id="best">0</span></div>
  </div>

  <div class="game" id="game" role="application" aria-label="Juego Flappy BAC 80s">
    <div class="bird" id="bird"><div class="wing flap"></div></div>
    <div class="ground" id="ground"></div>

    <div class="overlay" id="overlay">
      <div class="card">
        <h2 id="ovTitle">Flappy BAC</h2>
        <p id="ovMsg">Toca para aletear. Evita los tubos y supera tu récord.</p>
        <a href="#" class="btn" id="btnStart">Iniciar</a>
        <div class="hint">Consejo: toques cortos y rítmicos ayudan a estabilizar.</div>
      </div>
    </div>
  </div>

  <p style="text-align:center;opacity:.8;margin-top:8px">Estética retro de los 80. Optimizado para móviles. Temática lúdica sobre BAC Guatemala. (Sin PHP; récord con <code>localStorage</code>).</p>
</div>
<div class="scan"></div>

<script>
(function(){
  const game = document.getElementById('game');
  const bird = document.getElementById('bird');
  const overlay = document.getElementById('overlay');
  const ovTitle = document.getElementById('ovTitle');
  const ovMsg = document.getElementById('ovMsg');
  const btnStart = document.getElementById('btnStart');
  const scoreEl = document.getElementById('score');
  const bestEl = document.getElementById('best');
  const storageKey = 'flappy_bac_best_v1';

  let best = 0;
  try { best = Number(localStorage.getItem(storageKey) || 0); } catch(e) { best = 0; }
  bestEl.textContent = best;

  let running = false;
  let width = 0, height = 0, groundY = 0;
  let y = 0;         // posición vertical del pájaro (px)
  let vy = 0;        // velocidad vertical
  let gravity = 1600; // px/s^2
  let jump = -460;    // impulso (px/s)
  let pipes = [];    // obstáculos
  let speed = 170;    // px/s (velocidad hacia la izquierda)
  let spawnEach = 1500; // ms
  let gap = 165;     // px de gap entre tubos
  let score = 0;
  let lastTime = null;
  let spawnTimer = 0;
  let rafId = null, intId = null;

  function resize(){
    const r = game.getBoundingClientRect();
    width = r.width; height = r.height; groundY = height*0.86; // coincide con .ground height:14%
    if (!running) { y = height*0.45; vy = 0; updateBird(); }
  }

  function updateBird(){
    bird.style.top = (y-20) + 'px'; // centrar visual
    // rotación basada en velocidad
    const angle = Math.max(-35, Math.min(80, vy*0.08));
    bird.style.transform = `translateZ(0) rotate(${angle}deg)`;
  }

  function spawnPipe(){
    const x = width + 10;
    const minTop = 60; // px
    const maxTop = Math.max(120, groundY - gap - 60);
    const topH = Math.floor(minTop + Math.random()*(maxTop - minTop));

    const el = document.createElement('div');
    el.className = 'pipe';
    el.style.left = '0px'; // evitamos doble offset con transform
    const pTop = document.createElement('div'); pTop.className = 'pTop'; pTop.style.height = topH + 'px';
    const pBottom = document.createElement('div'); pBottom.className = 'pBottom'; pBottom.style.height = (groundY - (topH + gap)) + 'px';
    el.appendChild(pTop); el.appendChild(pBottom);
    game.appendChild(el);

    const pipe = { x, width:70, top:topH, gap:gap, el, scored:false };
    el.style.transform = `translateX(${x}px)`;
    pipes.push(pipe);
  }

  function tick(dt){
    if (!running) return;

    // Física del pájaro
    vy += gravity * dt; // aplicar gravedad
    y += vy * dt;       // mover

    // Colisiones techo/suelo
    if (y < 20) { y = 20; vy = 0; }
    if (y + 20 > groundY) { y = groundY - 20; return gameOver(); }

    updateBird();

    // Spawner
    spawnTimer += dt*1000;
    if (spawnTimer > spawnEach) { spawnTimer = 0; spawnPipe(); }

    // Mover y chequear tubos
    for (let i=0;i<pipes.length;i++){
      const p = pipes[i];
      // Avance del tubo usando transform (coincide con lo que se ve)
      p.x -= speed * dt;
      p.el.style.transform = `translateX(${p.x}px)`;

      // --- COLISIONES Y SCORE USANDO DOM RECTS (robusto en móviles) ---
      const b = bird.getBoundingClientRect();
      const pr = p.el.getBoundingClientRect();
      const topRect = p.el.children[0].getBoundingClientRect();
      const bottomRect = p.el.children[1].getBoundingClientRect();

      const birdCenterX = b.left + b.width/2;
      const overlap = (r1, r2) => !(r1.right < r2.left || r1.left > r2.right || r1.bottom < r2.top || r1.top > r2.bottom);

      // Puntaje cuando el tubo pasa completamente al centro del pájaro
      if (!p.scored && pr.right < birdCenterX){
        p.scored = true; score++; scoreEl.textContent = String(score);
        // Dificultad progresiva leve
        if (score % 5 === 0){ speed += 8; if (gap>130) gap -= 4; if (spawnEach>1200) spawnEach -= 20; }
      }

      // Remover cuando sale de pantalla
      if (pr.right < -10){ p.el.remove(); pipes.splice(i,1); i--; continue; }

      // Colisiones reales con la parte superior o inferior del tubo
      if (overlap(b, topRect) || overlap(b, bottomRect)){
        return gameOver();
      }
    }
  }

  // Bucle de juego unificado
  function gameFrame(ts){
    if (!running) return;
    if (lastTime == null) lastTime = ts;
    const dt = Math.min(32, ts - lastTime) / 1000; // segundos
    lastTime = ts;
    tick(dt);
    rafId = requestAnimationFrame(gameFrame);
  }

  function startLoop(){
    stopLoop();
    if ('requestAnimationFrame' in window){
      rafId = requestAnimationFrame(gameFrame);
    } else {
      intId = setInterval(()=>{ if (!running) return; tick(0.016); }, 16);
    }
  }

  function stopLoop(){
    if (rafId) cancelAnimationFrame(rafId);
    if (intId) clearInterval(intId);
    rafId=null; intId=null;
  }

  function start(){
    resize(); // recalcular dimensiones reales del móvil
    running = true; score = 0; scoreEl.textContent = '0';
    pipes.forEach(p => p.el.remove());
    pipes = []; lastTime = null; spawnTimer = -800; // retraso primer tubo
    vy = jump; y = height*0.45; // impulso inicial para evitar caída temprana
    updateBird();
    overlay.style.display = 'none';
    startLoop();
    // Garantizar primer tubo por si rAF tarda en disparar
    setTimeout(()=>{ if (running && pipes.length===0) spawnPipe(); }, 700);
  }

  function gameOver(){
    if (!running) return; running = false; stopLoop();
    // guardar récord local
    if (score > best){ best = score; try{ localStorage.setItem(storageKey, String(best)); }catch(e){} }
    bestEl.textContent = best;
    ovTitle.textContent = 'Fin de partida';
    ovMsg.innerHTML = `Puntaje final: <strong>${score}</strong><br>Récord: <strong>${best}</strong>`;
    btnStart.textContent = 'Reintentar';
    overlay.style.display = 'flex';
  }

  // Controles
  const onPress = (e)=>{ if (!running) start(); vy = jump; };
  const supportsPointer = 'onpointerdown' in window;
  if (supportsPointer){
    game.addEventListener('pointerdown', onPress);
    overlay.addEventListener('pointerdown', (e)=>{ if (!running) { start(); } vy = jump; });
  } else {
    game.addEventListener('touchstart', onPress, {passive:true});
    overlay.addEventListener('touchstart', (e)=>{ if (!running) { start(); } vy = jump; }, {passive:true});
    game.addEventListener('mousedown', onPress);
    overlay.addEventListener('mousedown', (e)=>{ if (!running) { start(); } vy = jump; });
  }

  // Teclado (desktop)
  window.addEventListener('keydown', (e)=>{
    if (e.code==='Space' || e.key===' '){ if (!running) start(); vy = jump; }
  });

  // Pausa al cambiar de pestaña
  document.addEventListener('visibilitychange', ()=>{
    if (document.hidden && running){ gameOver(); }
  });

  window.addEventListener('resize', resize);
  resize();
  overlay.style.display = 'flex';
})();
</script>
</body>
</html>
