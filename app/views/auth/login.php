<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — EduAdmin SMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Inter',sans-serif;min-height:100vh;overflow:hidden;background:#0a0e1a;}
#bgCanvas{position:fixed;inset:0;z-index:0;}
</style>
</head>
<body>
<canvas id="bgCanvas"></canvas>

<!-- SCENE LAYER -->
<div id="scene">

  <!-- Gate on the left -->
  <div id="gate">
    <div class="gate-post left-post"></div>
    <div class="gate-door" id="gateDoor"></div>
    <div class="gate-post right-post"></div>
    <div class="gate-arch"></div>
    <div class="gate-sign"><i class="bi bi-mortarboard-fill"></i></div>
  </div>

  <!-- Ground line -->
  <div id="ground"></div>

  <!-- Walking person (SVG sprite) -->
  <div id="person">
    <svg id="personSvg" viewBox="0 0 60 120" xmlns="http://www.w3.org/2000/svg">
      <!-- Head -->
      <circle id="head" cx="30" cy="18" r="12" fill="#fbbf24"/>
      <!-- Eyes -->
      <circle cx="25" cy="16" r="2" fill="#1e2130"/>
      <circle cx="35" cy="16" r="2" fill="#1e2130"/>
      <!-- Smile -->
      <path id="smile" d="M24 22 Q30 27 36 22" stroke="#1e2130" stroke-width="1.5" fill="none" stroke-linecap="round"/>
      <!-- Body -->
      <rect id="body" x="18" y="32" width="24" height="32" rx="6" fill="#6366f1"/>
      <!-- Belt -->
      <rect x="18" y="55" width="24" height="4" rx="2" fill="#4f46e5"/>
      <!-- Left arm -->
      <rect id="armL" x="6"  y="32" width="10" height="26" rx="5" fill="#6366f1" transform-origin="11 32"/>
      <!-- Right arm -->
      <rect id="armR" x="44" y="32" width="10" height="26" rx="5" fill="#6366f1" transform-origin="49 32"/>
      <!-- Left leg -->
      <rect id="legL" x="18" y="64" width="10" height="34" rx="5" fill="#1e40af" transform-origin="23 64"/>
      <!-- Right leg -->
      <rect id="legR" x="32" y="64" width="10" height="34" rx="5" fill="#1e40af" transform-origin="37 64"/>
      <!-- Shoes -->
      <ellipse id="shoeL" cx="23" cy="98" rx="9" ry="5" fill="#0f172a"/>
      <ellipse id="shoeR" cx="37" cy="98" rx="9" ry="5" fill="#0f172a"/>
      <!-- School bag -->
      <rect id="bag" x="42" y="34" width="14" height="22" rx="4" fill="#8b5cf6"/>
      <rect x="44" y="30" width="4" height="8" rx="2" fill="#7c3aed"/>
      <circle cx="49" cy="45" r="2" fill="#a78bfa"/>
    </svg>
  </div>

  <!-- Login card (slides in from right) -->
  <div id="loginCard">
    <div class="lcard-logo">
      <div class="lcard-icon"><i class="bi bi-mortarboard-fill"></i></div>
      <h1>EduAdmin</h1>
      <p>Student Management System</p>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
    <div class="lerror" id="lerror">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <?= htmlspecialchars($_SESSION['error']) ?>
      <button onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

    <form method="POST" action="?page=login" id="loginForm" novalidate>
      <div class="lfield">
        <i class="bi bi-person lfield-icon"></i>
        <input type="text" id="username" name="username" placeholder="Username"
               required autocomplete="username">
      </div>
      <div class="lfield" style="margin-bottom:1.75rem">
        <i class="bi bi-lock lfield-icon"></i>
        <input type="password" id="password" name="password" placeholder="Password"
               required autocomplete="current-password">
        <button type="button" class="lfield-eye" onclick="togglePwd()">
          <i class="bi bi-eye" id="eyeIcon"></i>
        </button>
      </div>
      <button type="submit" class="lbtn" id="loginBtn">
        <span id="btnText"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</span>
        <span id="btnLoad" style="display:none">
          <span class="spinner-border spinner-border-sm me-2"></span>Signing in…
        </span>
      </button>
    </form>

    <div class="lcard-footer">
      <i class="bi bi-shield-check"></i> Protected by session-based authentication
    </div>
  </div><!-- /loginCard -->

</div><!-- /scene -->

<style>
/* ── SCENE ─────────────────────────────────── */
#scene{
  position:relative;z-index:1;
  width:100vw;height:100vh;
  overflow:hidden;
}

/* ── GROUND ─────────────────────────────────── */
#ground{
  position:absolute;bottom:80px;left:0;right:0;
  height:3px;
  background:linear-gradient(90deg,transparent,rgba(99,102,241,.6),transparent);
  box-shadow:0 0 20px rgba(99,102,241,.4);
}

/* ── GATE ────────────────────────────────────── */
#gate{
  position:absolute;
  bottom:80px;left:40px;
  width:90px;height:140px;
  z-index:10;
}
.gate-post{
  position:absolute;bottom:0;width:12px;height:130px;
  background:linear-gradient(180deg,#818cf8,#4f46e5);
  border-radius:4px 4px 0 0;
}
.left-post{left:0;}
.right-post{right:0;}
.gate-arch{
  position:absolute;top:0;left:0;right:0;height:28px;
  border:6px solid #6366f1;border-bottom:none;
  border-radius:50px 50px 0 0;
}
.gate-door{
  position:absolute;bottom:0;left:12px;right:12px;
  height:110px;
  background:rgba(99,102,241,.15);
  border:2px solid rgba(99,102,241,.4);
  border-bottom:none;
  border-radius:4px 4px 0 0;
  transition:transform .5s ease;
  transform-origin:left center;
}
.gate-sign{
  position:absolute;top:-36px;left:50%;transform:translateX(-50%);
  background:#6366f1;color:#fff;
  width:32px;height:32px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:1rem;
  box-shadow:0 4px 16px rgba(99,102,241,.6);
}

/* ── PERSON ──────────────────────────────────── */
#person{
  position:absolute;
  bottom:83px;
  width:60px;height:120px;
  z-index:20;
  will-change:transform;
}
#personSvg{ width:60px;height:120px; }

/* ── LOGIN CARD ──────────────────────────────── */
#loginCard{
  position:absolute;
  top:50%;left:50%;
  transform:translate(150vw,-50%);
  width:min(420px,92vw);
  background:rgba(20,24,40,.88);
  backdrop-filter:blur(24px);
  -webkit-backdrop-filter:blur(24px);
  border:1px solid rgba(99,102,241,.3);
  border-radius:24px;
  padding:2.5rem 2.25rem 2rem;
  box-shadow:0 32px 80px rgba(0,0,0,.6),0 0 0 1px rgba(255,255,255,.04);
  z-index:15;
  transition:transform .7s cubic-bezier(.22,1,.36,1);
}
.lcard-logo{text-align:center;margin-bottom:1.75rem;}
.lcard-icon{
  width:68px;height:68px;border-radius:20px;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);
  display:inline-flex;align-items:center;justify-content:center;
  font-size:2rem;color:#fff;margin-bottom:.9rem;
  box-shadow:0 8px 32px rgba(99,102,241,.45);
}
.lcard-logo h1{font-size:1.55rem;font-weight:800;color:#f1f5f9;letter-spacing:-.4px;}
.lcard-logo p{font-size:.83rem;color:#64748b;margin-top:.2rem;}
.lerror{
  background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.35);
  color:#fca5a5;border-radius:12px;font-size:.875rem;
  padding:.7rem 1rem;margin-bottom:1.1rem;
  display:flex;align-items:center;gap:.5rem;
}
.lerror button{background:none;border:none;color:#fca5a5;margin-left:auto;cursor:pointer;}
.lfield{position:relative;margin-bottom:1rem;}
.lfield input{
  width:100%;padding:.85rem 1rem .85rem 2.8rem;
  background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);
  color:#f1f5f9;border-radius:12px;font-size:.93rem;outline:none;
  transition:border-color .2s,box-shadow .2s;
}
.lfield input:focus{
  border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.2);
  background:rgba(99,102,241,.07);
}
.lfield input::placeholder{color:#475569;}
.lfield-icon{
  position:absolute;left:.9rem;top:50%;transform:translateY(-50%);
  color:#64748b;pointer-events:none;font-size:1rem;
}
.lfield-eye{
  position:absolute;right:.9rem;top:50%;transform:translateY(-50%);
  background:none;border:none;color:#64748b;cursor:pointer;
  transition:color .2s;
}
.lfield-eye:hover{color:#818cf8;}
.lbtn{
  width:100%;padding:.85rem;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);
  border:none;border-radius:12px;
  color:#fff;font-weight:700;font-size:.95rem;
  cursor:pointer;transition:transform .2s,box-shadow .2s;
  letter-spacing:.3px;
}
.lbtn:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(99,102,241,.5);}
.lbtn:disabled{opacity:.7;transform:none;cursor:default;}
.lcard-footer{
  text-align:center;margin-top:1.25rem;
  color:#334155;font-size:.75rem;
}
.lcard-footer i{color:#6366f1;}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── CANVAS BACKGROUND ───────────────────── */
(function(){
  const c=document.getElementById('bgCanvas'),ctx=c.getContext('2d');
  let W,H,nodes=[];
  const COLS=['#6366f1','#8b5cf6','#06b6d4','#3b82f6','#a78bfa'],N=65;
  function resize(){W=c.width=window.innerWidth;H=c.height=window.innerHeight;}
  function mkNode(){return{x:Math.random()*W,y:Math.random()*H,
    r:Math.random()*2.2+.6,vx:(Math.random()-.5)*.35,vy:(Math.random()-.5)*.35,
    col:COLS[Math.random()*COLS.length|0],a:Math.random()*.55+.1};}
  function hexRgb(h){const x=h.replace('#','');
    return[parseInt(x.slice(0,2),16),parseInt(x.slice(2,4),16),parseInt(x.slice(4,6),16)];}
  function draw(){
    ctx.clearRect(0,0,W,H);
    const g=ctx.createLinearGradient(0,0,W,H);
    g.addColorStop(0,'#0a0e1a');g.addColorStop(.5,'#0f1428');g.addColorStop(1,'#0d0620');
    ctx.fillStyle=g;ctx.fillRect(0,0,W,H);
    for(let i=0;i<nodes.length;i++){
      for(let j=i+1;j<nodes.length;j++){
        const dx=nodes[i].x-nodes[j].x,dy=nodes[i].y-nodes[j].y,d=Math.hypot(dx,dy);
        if(d<120){ctx.beginPath();ctx.moveTo(nodes[i].x,nodes[i].y);
          ctx.lineTo(nodes[j].x,nodes[j].y);
          ctx.strokeStyle=`rgba(99,102,241,${.14*(1-d/120)})`;
          ctx.lineWidth=.7;ctx.stroke();}
      }
    }
    nodes.forEach(n=>{
      const [r,g2,b]=hexRgb(n.col);
      ctx.beginPath();ctx.arc(n.x,n.y,n.r,0,Math.PI*2);
      ctx.fillStyle=`rgba(${r},${g2},${b},${n.a})`;ctx.fill();
      const gl=ctx.createRadialGradient(n.x,n.y,0,n.x,n.y,n.r*7);
      gl.addColorStop(0,`rgba(${r},${g2},${b},.12)`);gl.addColorStop(1,`rgba(${r},${g2},${b},0)`);
      ctx.beginPath();ctx.arc(n.x,n.y,n.r*7,0,Math.PI*2);ctx.fillStyle=gl;ctx.fill();
      n.x+=n.vx;n.y+=n.vy;
      if(n.x<0||n.x>W)n.vx*=-1;if(n.y<0||n.y>H)n.vy*=-1;
    });
    requestAnimationFrame(draw);
  }
  resize();nodes=Array.from({length:N},mkNode);draw();
  window.addEventListener('resize',()=>{resize();nodes=Array.from({length:N},mkNode);});
})();
</script>

<script>
/* ── PERSON + SCENE ANIMATION ────────────────
   Timeline (all in ms):
   0        → card slides in from right
   400      → person enters walking from right
   ~2200    → person reaches sitting position (in front of card)
   2300     → person sits down (shrinks, legs fold)
   5300     → person stands back up
   5500     → person walks left toward gate
   7000     → gate door opens
   7200     → person walks through gate
   7800     → gate door closes
   8000     → person fully gone, form is active
──────────────────────────────────────────── */

const person   = document.getElementById('person');
const armL     = document.getElementById('armL');
const armR     = document.getElementById('armR');
const legL     = document.getElementById('legL');
const legR     = document.getElementById('legR');
const shoeL    = document.getElementById('shoeL');
const shoeR    = document.getElementById('shoeR');
const bag      = document.getElementById('bag');
const smile    = document.getElementById('smile');
const gateDoor = document.getElementById('gateDoor');
const card     = document.getElementById('loginCard');
const inputs   = card.querySelectorAll('input,button');

// Disable form until animation ends
inputs.forEach(el=>el.setAttribute('tabindex','-1'));

// Viewport width
const VW = window.innerWidth;
// Start person off-screen to the right
const startX  = VW + 20;
// Sitting position: just in front of the centered card
const sitX    = Math.max(VW * 0.5 - 90, 180);
// Gate exit X (left side)
const gateX   = 55; // roughly where gate center is

let walkRaf=null, walkPhase=0, walkTime=0;

/* ── walking leg/arm oscillation ── */
function animateWalk(forward) {
  const t = (Date.now() - walkTime) / 180; // cycle speed
  const swing = forward ? 28 : -28;
  const s = Math.sin(t * Math.PI);

  armL.setAttribute('transform',`rotate(${s*swing},11,32)`);
  armR.setAttribute('transform',`rotate(${-s*swing},49,32)`);
  legL.setAttribute('transform',`rotate(${-s*swing},23,64)`);
  legR.setAttribute('transform',`rotate(${s*swing},37,64)`);

  // Slight body bob
  person.style.marginBottom = (Math.abs(s)*4) + 'px';

  walkRaf = requestAnimationFrame(()=>animateWalk(forward));
}
function stopWalk(){
  cancelAnimationFrame(walkRaf);walkRaf=null;
  // Reset limbs to neutral
  ['armL','armR','legL','legR'].forEach(id=>{
    document.getElementById(id).setAttribute('transform','rotate(0)');
  });
  person.style.marginBottom='0';
}

/* ── sitting pose ── */
function sitDown(){
  // Fold legs forward, hunch slightly
  legL.setAttribute('transform','rotate(70,23,64)');
  legR.setAttribute('transform','rotate(70,37,64)');
  armL.setAttribute('transform','rotate(-20,11,32)');
  armR.setAttribute('transform','rotate(20,49,32)');
  // Squish person to look seated
  person.style.transition='transform .4s ease';
  person.style.transform='scaleY(0.82) translateY(18px)';
  // Happy expression
  smile.setAttribute('d','M22 22 Q30 30 38 22');
}
function standUp(){
  legL.setAttribute('transform','rotate(0,23,64)');
  legR.setAttribute('transform','rotate(0,37,64)');
  armL.setAttribute('transform','rotate(0,11,32)');
  armR.setAttribute('transform','rotate(0,49,32)');
  person.style.transform='scaleY(1) translateY(0)';
  smile.setAttribute('d','M24 22 Q30 27 36 22');
}
</script>

<script>
/* ── MOVE PERSON (CSS transition approach) ── */
function moveTo(x, duration, onDone) {
  person.style.transition = `left ${duration}ms linear`;
  person.style.left = x + 'px';
  if (onDone) setTimeout(onDone, duration);
}

/* ── RESET STATE before each loop ── */
function resetState() {
  stopWalk();
  // Reset person position & pose
  person.style.transition = 'none';
  person.style.transform  = 'scaleX(1)';
  person.style.left       = startX + 'px';
  person.style.bottom     = '83px';
  person.style.marginBottom = '0';
  person.style.display    = 'block';
  standUp();
  // Reset gate
  gateDoor.style.transition = 'none';
  gateDoor.style.transform  = 'rotateY(0deg)';
  // Slide card back off-screen right instantly
  card.style.transition = 'none';
  card.style.transform  = 'translate(150vw,-50%)';
  // Disable form while animating
  inputs.forEach(el => el.setAttribute('tabindex','-1'));
}

/* ── MAIN SEQUENCE ── */
function runSequence() {
  resetState();

  // Give browser one frame to apply the reset before re-enabling transitions
  requestAnimationFrame(()=> requestAnimationFrame(()=>{

    // Restore transitions
    card.style.transition     = 'transform .7s cubic-bezier(.22,1,.36,1)';
    gateDoor.style.transition = 'transform .5s ease';

    // Step 0: Slide card in to center
    card.style.transform = 'translate(-50%,-50%)';

    // Step 1 (t=300): Person walks in from right
    setTimeout(()=>{
      walkTime = Date.now();
      animateWalk(false);
      person.style.transform = 'scaleX(-1)';
      moveTo(sitX, 1800);
    }, 300);

    // Step 2 (t=2200): Arrive, sit down
    setTimeout(()=>{
      stopWalk();
      person.style.transform = 'scaleX(1)';
      setTimeout(sitDown, 150);
    }, 2200);

    // Step 3 (t=5300): Stand up
    setTimeout(()=>{
      standUp();
    }, 5300);

    // Step 4 (t=5700): Walk toward gate
    setTimeout(()=>{
      walkTime = Date.now();
      animateWalk(true);
      person.style.transform = 'scaleX(-1)';
      moveTo(gateX - 20, 1400);
    }, 5700);

    // Step 5 (t=7000): Open gate
    setTimeout(()=>{
      gateDoor.style.transform = 'rotateY(-80deg)';
    }, 7000);

    // Step 6 (t=7200): Walk through & off screen
    setTimeout(()=>{
      moveTo(-80, 700);
    }, 7200);

    // Step 7 (t=7900): Gate closes, form enabled
    setTimeout(()=>{
      stopWalk();
      gateDoor.style.transform = 'rotateY(0deg)';
      person.style.display = 'none';
      inputs.forEach(el => el.removeAttribute('tabindex'));
      // Only focus if nothing is already focused / typed
      const u = document.getElementById('username');
      if (!u.value) u.focus();

      // ── Schedule next loop in 5 seconds (unless user started typing) ──
      setTimeout(()=>{ if (!loopStopped) runSequence(); }, 5000);

    }, 7900);

  }));
}

// Kick off after page load
window.addEventListener('load', ()=> setTimeout(runSequence, 300));

/* ── PASSWORD TOGGLE ── */
function togglePwd(){
  const i=document.getElementById('password');
  const e=document.getElementById('eyeIcon');
  i.type = i.type==='password' ? 'text' : 'password';
  e.className = i.type==='password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

/* ── STOP LOOP when user starts typing ── */
let loopStopped = false;
['username','password'].forEach(id=>{
  document.getElementById(id).addEventListener('input', ()=>{
    loopStopped = true;
  });
});

/* ── FORM SUBMIT ── */
document.getElementById('loginForm').addEventListener('submit',function(e){
  const u=document.getElementById('username').value.trim();
  const p=document.getElementById('password').value.trim();
  if(!u||!p){e.preventDefault();return;}
  document.getElementById('btnText').style.display='none';
  document.getElementById('btnLoad').style.display='inline';
  document.getElementById('loginBtn').disabled=true;
});
</script>
</body>
</html>
