<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Jnanasudha API Tester</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f172a;color:#e2e8f0;min-height:100vh}
header{background:#1e293b;border-bottom:1px solid #334155;padding:16px 24px;display:flex;align-items:center;gap:12px;position:sticky;top:0;z-index:100}
header h1{font-size:18px;font-weight:700;color:#f8fafc}
header span{font-size:12px;background:#6366f1;color:#fff;padding:2px 8px;border-radius:99px}
.layout{display:flex;height:calc(100vh - 57px)}
.sidebar{width:260px;flex-shrink:0;background:#1e293b;border-right:1px solid #334155;overflow-y:auto;padding:12px 0}
.sidebar-section{padding:8px 16px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#64748b}
.ep-btn{display:flex;align-items:center;gap:8px;width:100%;padding:8px 16px;background:none;border:none;cursor:pointer;text-align:left;color:#cbd5e1;font-size:13px;transition:background .15s}
.ep-btn:hover{background:#334155}
.ep-btn.active{background:#312e81;color:#a5b4fc}
.method{font-size:10px;font-weight:700;padding:2px 5px;border-radius:4px;min-width:36px;text-align:center}
.method.POST{background:#d97706;color:#fff}
.method.GET{background:#059669;color:#fff}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.token-bar{background:#1e293b;border-bottom:1px solid #334155;padding:10px 20px;display:flex;align-items:center;gap:8px}
.token-bar label{font-size:12px;color:#94a3b8;white-space:nowrap}
.token-bar input{flex:1;background:#0f172a;border:1px solid #334155;border-radius:6px;padding:6px 10px;color:#e2e8f0;font-size:12px;font-family:monospace}
.token-bar button{background:#6366f1;color:#fff;border:none;border-radius:6px;padding:6px 12px;font-size:12px;cursor:pointer;white-space:nowrap}
.token-bar button:hover{background:#4f46e5}
.panel{flex:1;display:flex;gap:0;overflow:hidden}
.req-panel{width:45%;border-right:1px solid #334155;display:flex;flex-direction:column;overflow:hidden}
.res-panel{flex:1;display:flex;flex-direction:column;overflow:hidden}
.panel-header{background:#1e293b;padding:12px 16px;font-size:13px;font-weight:600;color:#94a3b8;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between}
.panel-header .ep-title{color:#f1f5f9;font-size:14px}
.panel-body{flex:1;overflow-y:auto;padding:16px}
.field-group{margin-bottom:14px}
.field-group label{display:block;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px}
.field-group input,.field-group textarea{width:100%;background:#0f172a;border:1px solid #334155;border-radius:6px;padding:8px 10px;color:#e2e8f0;font-size:13px;font-family:monospace;resize:vertical}
.field-group textarea{min-height:120px}
.field-group input:focus,.field-group textarea:focus{outline:none;border-color:#6366f1}
.send-btn{width:100%;padding:10px;background:#6366f1;color:#fff;border:none;border-radius:6px;font-size:14px;font-weight:600;cursor:pointer;transition:background .15s}
.send-btn:hover{background:#4f46e5}
.send-btn:disabled{background:#374151;cursor:not-allowed}
.status-bar{display:flex;align-items:center;gap:10px;padding:0 16px}
.status-pill{font-size:12px;padding:2px 8px;border-radius:99px;font-weight:700}
.status-pill.ok{background:#064e3b;color:#6ee7b7}
.status-pill.err{background:#7f1d1d;color:#fca5a5}
.status-pill.warn{background:#78350f;color:#fcd34d}
.time-label{font-size:11px;color:#64748b}
.copy-btn{margin-left:auto;background:#334155;border:none;color:#94a3b8;padding:4px 10px;border-radius:5px;font-size:11px;cursor:pointer}
.copy-btn:hover{color:#f1f5f9}
pre#response{flex:1;overflow:auto;padding:16px;font-size:12px;font-family:'Cascadia Code','Fira Code',monospace;white-space:pre-wrap;word-break:break-all;color:#e2e8f0;line-height:1.6}
.placeholder{flex:1;display:flex;align-items:center;justify-content:center;color:#334155;font-size:14px;text-align:center;padding:40px}
.url-display{font-size:11px;color:#64748b;font-family:monospace;padding:0 16px 8px}
.jk{color:#93c5fd}.jv{color:#86efac}.js{color:#fcd34d}.jb{color:#f87171}.jn{color:#94a3b8}
</style>
</head>
<body>
<header>
  <h1>Jnanasudha API Tester</h1>
  <span>v1</span>
  <?php
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $base   = $scheme . '://' . $host;
  ?>
  <span style="background:#0f172a;color:#64748b;margin-left:auto;font-size:11px">Base: <b id="baseUrl"><?php echo htmlspecialchars($base); ?></b></span>
</header>

<div class="layout">
  <nav class="sidebar">

    <div class="sidebar-section">Auth</div>
    <button class="ep-btn" onclick="load('POST','/api/auth/login',{phone:'',password:''},false,'Login')">
      <span class="method POST">POST</span>/api/auth/login
    </button>

    <div class="sidebar-section">FCM</div>
    <button class="ep-btn" onclick="load('POST','/api/fcm/register',{user_id:'',phone:'',device_token:'',platform:'android',app_version:'1.0.0'},true,'Register FCM Token')">
      <span class="method POST">POST</span>/api/fcm/register
    </button>
    <button class="ep-btn" onclick="load('POST','/api/fcm/unregister',{device_token:''},true,'Unregister FCM Token')">
      <span class="method POST">POST</span>/api/fcm/unregister
    </button>

    <div class="sidebar-section">Notifications</div>
    <button class="ep-btn" onclick="load('GET','/api/notifications/{phone}',null,true,'Notification Inbox',{phone:''})">
      <span class="method GET">GET</span>/api/notifications/{phone}
    </button>

    <div class="sidebar-section">Student — Home &amp; Profile</div>
    <button class="ep-btn" onclick="load('GET','/api/student/home',null,true,'Student Home')">
      <span class="method GET">GET</span>/api/student/home
    </button>
    <button class="ep-btn" onclick="load('GET','/api/student/profile',null,true,'Student Profile')">
      <span class="method GET">GET</span>/api/student/profile
    </button>

    <div class="sidebar-section">Student — Packages</div>
    <button class="ep-btn" onclick="load('GET','/api/student/packages',null,true,'Student Packages')">
      <span class="method GET">GET</span>/api/student/packages
    </button>
    <button class="ep-btn" onclick="load('GET','/api/student/package/{id}',null,true,'Package Detail',{id:''})">
      <span class="method GET">GET</span>/api/student/package/{id}
    </button>
    <button class="ep-btn" onclick="load('GET','/api/student/quizzes/{package_id}',null,true,'Quizzes in Package',{package_id:''})">
      <span class="method GET">GET</span>/api/student/quizzes/{package_id}
    </button>

    <div class="sidebar-section">Student — Results</div>
    <button class="ep-btn" onclick="load('GET','/api/student/results',null,true,'All Results')">
      <span class="method GET">GET</span>/api/student/results
    </button>
    <button class="ep-btn" onclick="load('GET','/api/student/result/{quiz_id}',null,true,'Result Detail',{quiz_id:''})">
      <span class="method GET">GET</span>/api/student/result/{quiz_id}
    </button>

    <div class="sidebar-section">Teacher</div>
    <button class="ep-btn" onclick="load('GET','/api/teacher/packages',null,true,'Teacher Packages')">
      <span class="method GET">GET</span>/api/teacher/packages
    </button>
    <button class="ep-btn" onclick="load('GET','/api/teacher/students/{package_id}',null,true,'Students in Package',{package_id:''})">
      <span class="method GET">GET</span>/api/teacher/students/{package_id}
    </button>

  </nav>

  <div class="main">
    <div class="token-bar">
      <label>JWT Token:</label>
      <input type="text" id="jwtToken" placeholder="Paste token here, or use Login to auto-fill">
      <button onclick="clearToken()">Clear</button>
    </div>

    <div class="panel">
      <div class="req-panel">
        <div class="panel-header">
          <span>Request</span>
          <span class="ep-title" id="epTitle">— select an endpoint —</span>
        </div>
        <div class="panel-body" id="reqBody">
          <div class="placeholder">Select an endpoint from the sidebar</div>
        </div>
      </div>

      <div class="res-panel">
        <div class="panel-header">
          <span>Response</span>
          <div class="status-bar" id="statusBar" style="display:none">
            <span class="status-pill" id="statusPill"></span>
            <span class="time-label" id="timeLabel"></span>
            <button class="copy-btn" onclick="copyResponse()">Copy</button>
          </div>
        </div>
        <div class="url-display" id="urlDisplay"></div>
        <pre id="response"><span style="color:#334155">Response will appear here...</span></pre>
      </div>
    </div>
  </div>
</div>

<script>
const BASE = document.getElementById('baseUrl').textContent.trim();
let currentMethod = 'GET';
let currentPathTemplate = '';
let currentParams = {};

function load(method, path, body, needsJwt, title, params) {
  document.querySelectorAll('.ep-btn').forEach(b => b.classList.remove('active'));
  event.currentTarget.classList.add('active');

  currentMethod = method;
  currentPathTemplate = path;
  currentParams = params || {};
  document.getElementById('epTitle').textContent = title;

  const rb = document.getElementById('reqBody');
  let html = '';

  const paramNames = (path.match(/\{(\w+)\}/g) || []).map(p => p.slice(1,-1));
  if (paramNames.length) {
    html += '<div class="field-group"><label>Path Parameters</label>';
    paramNames.forEach(p => {
      const val = currentParams[p] || '';
      html += `<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
        <span style="font-size:12px;color:#94a3b8;min-width:90px">{${p}}</span>
        <input type="text" id="param_${p}" value="${val}" placeholder="${p}" oninput="updateUrl()">
      </div>`;
    });
    html += '</div>';
  }

  if (needsJwt) {
    html += `<div style="background:#1e3a5f;border:1px solid #1d4ed8;border-radius:6px;padding:8px 12px;font-size:12px;color:#93c5fd;margin-bottom:14px">
      🔒 Requires JWT — paste token in the bar above or login first
    </div>`;
  }

  if (body !== null) {
    html += `<div class="field-group"><label>Request Body (JSON)</label>
      <textarea id="reqBodyJson">${JSON.stringify(body, null, 2)}</textarea>
    </div>`;
  }

  html += `<button class="send-btn" id="sendBtn" onclick="send()">Send Request</button>`;
  rb.innerHTML = html;
  updateUrl();
}

function updateUrl() {
  let path = currentPathTemplate;
  (path.match(/\{(\w+)\}/g) || []).forEach(p => {
    const name = p.slice(1,-1);
    const el = document.getElementById('param_' + name);
    if (el) path = path.replace(p, el.value || p);
  });
  document.getElementById('urlDisplay').textContent = currentMethod + '  ' + BASE + path;
}

async function send() {
  const btn = document.getElementById('sendBtn');
  btn.disabled = true;
  btn.textContent = 'Sending…';

  let path = currentPathTemplate;
  (path.match(/\{(\w+)\}/g) || []).forEach(p => {
    const name = p.slice(1,-1);
    const el = document.getElementById('param_' + name);
    if (el) path = path.replace(p, el.value || '');
  });

  const token = document.getElementById('jwtToken').value.trim();
  const headers = {};
  if (token) headers['Authorization'] = 'Bearer ' + token;

  const bodyEl = document.getElementById('reqBodyJson');
  if (bodyEl) {
    headers['Content-Type'] = 'application/json';
  }
  const opts = {method: currentMethod, headers};

  if (bodyEl) {
    try {
      JSON.parse(bodyEl.value); // validate
      opts.body = bodyEl.value;
    } catch(e) {
      opts.body = bodyEl.value;
    }
  }

  const t0 = Date.now();
  try {
    const resp = await fetch(BASE + path, opts);
    const ms = Date.now() - t0;
    const text = await resp.text();

    try {
      const json = JSON.parse(text);
      if (json.token) document.getElementById('jwtToken').value = json.token;
    } catch(e) {}

    showResponse(resp.status, text, ms);
  } catch(e) {
    showResponse(0, 'Network error: ' + e.message, Date.now() - t0);
  }

  btn.disabled = false;
  btn.textContent = 'Send Request';
}

function showResponse(status, text, ms) {
  const bar  = document.getElementById('statusBar');
  const pill = document.getElementById('statusPill');
  const time = document.getElementById('timeLabel');
  bar.style.display = 'flex';

  pill.textContent = status || 'ERR';
  pill.className   = 'status-pill ' + (status >= 200 && status < 300 ? 'ok' : status === 0 ? 'warn' : 'err');
  time.textContent = ms + 'ms';

  let pretty = text;
  try { pretty = JSON.stringify(JSON.parse(text), null, 2); } catch(e) {}
  document.getElementById('response').innerHTML = highlight(pretty);
}

function highlight(json) {
  return json
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, m => {
      if (/^"/.test(m)) return /:$/.test(m) ? `<span class="jk">${m}</span>` : `<span class="js">${m}</span>`;
      if (/true|false/.test(m)) return `<span class="jv">${m}</span>`;
      if (/null/.test(m))       return `<span class="jn">${m}</span>`;
      return `<span class="jb">${m}</span>`;
    });
}

function copyResponse() {
  navigator.clipboard.writeText(document.getElementById('response').textContent).then(() => {
    const btn = document.querySelector('.copy-btn');
    btn.textContent = 'Copied!';
    setTimeout(() => btn.textContent = 'Copy', 1500);
  });
}

function clearToken() {
  document.getElementById('jwtToken').value = '';
}
</script>
</body>
</html>
