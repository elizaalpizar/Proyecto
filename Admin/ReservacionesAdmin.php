<?php
require_once '../Php/Session.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Actualizar Reservaciones</title>
  <link rel="stylesheet" href="../Css/Catalogo_Menu.css">
  <style>
    .container { max-width: 1100px; margin: 24px auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { text-align: left; padding: 10px; border-bottom: 1px solid #eee; }
    .badge { padding: 4px 8px; border-radius: 999px; font-size: 12px; }
    .badge.ok { background: #dcfce7; color: #166534; }
    .badge.warn { background: #fee2e2; color: #991b1b; }
    .btn { padding: 6px 10px; border-radius: 8px; border: none; cursor: pointer; color: #fff; }
    .btn-mark { background: #16a34a; }
    .btn-pending { background: #f59e0b; }
    .controls { display:flex; gap:8px; }
    .toolbar { display:flex; gap:10px; align-items:center; }
    .toolbar input { padding:8px; border:1px solid #e5e7eb; border-radius:8px; }
  </style>
</head>
<body>
  <header style="background:#111827; color:#fff; padding:12px 16px;">
    <nav style="display:flex; gap:16px; align-items:center;">
      <strong>Energym Admin</strong>
      <a href="../Admin/CatalogoAtleta.php" style="color:#fff; text-decoration:none;">Atletas</a>
      <a href="../Admin/Catalogo_menu(CRUD).html" style="color:#fff; text-decoration:none;">Servicios</a>
      <a href="../Admin/ReservacionesAdmin.php" style="color:#fff; text-decoration:none;">Reservaciones</a>
      <span style="flex:1"></span>
      <a href="../Php/logoutAdmin.php" style="color:#fca5a5; text-decoration:none;">Cerrar sesión</a>
    </nav>
  </header>
  <div class="container">
    <h2>Actualizar Reservaciones</h2>
    <p>Marque como <strong>Facturada</strong> si ya se atendió; de lo contrario queda <strong>Pendiente</strong>.</p>

    <div class="toolbar">
      <input id="buscar" placeholder="Buscar por atleta o servicio" />
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Atleta</th>
          <th>Servicio</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
  </div>

  <script>
    const tbody = document.getElementById('tbody');
    const buscar = document.getElementById('buscar');
    let all = [];

    function formatEstado(estado){
      const esFact = (estado || '').toLowerCase() === 'facturada';
      return `<span class="badge ${esFact ? 'ok' : 'warn'}">${esFact ? 'Facturada' : 'Pendiente'}</span>`;
    }

    function render(items){
      tbody.innerHTML = '';
      if (!items.length){
        tbody.innerHTML = '<tr><td colspan="7">Sin reservaciones</td></tr>';
        return;
      }
      items.forEach(r => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${r.id}</td>
          <td>${r.atleta_nombre ?? r.id_atleta}</td>
          <td>${r.servicio}</td>
          <td>${r.fecha}</td>
          <td>${r.hora?.substring(0,5) ?? ''}</td>
          <td>${formatEstado(r.estado)}</td>
          <td class="controls">
            <button class="btn btn-mark" data-id="${r.id}" data-estado="facturada">Marcar Facturada</button>
            <button class="btn btn-pending" data-id="${r.id}" data-estado="pendiente">Marcar Pendiente</button>
          </td>`;
        tbody.appendChild(tr);
      });

      tbody.querySelectorAll('button[data-id]').forEach(btn => {
        btn.addEventListener('click', async () => {
          const id = btn.getAttribute('data-id');
          const estado = btn.getAttribute('data-estado');
          const fd = new FormData();
          fd.append('id', id);
          fd.append('estado', estado);
          const res = await fetch('../Php/admin_reservaciones_actualizar_estado.php', { method: 'POST', credentials: 'include', body: fd });
          if (res.ok){
            await load();
          } else {
            alert('Error al actualizar estado');
          }
        });
      });
    }

    async function load(){
      tbody.innerHTML = '<tr><td colspan="7">Cargando...</td></tr>';
      const res = await fetch('../Php/admin_reservaciones_listar.php', { credentials: 'include' });
      const data = await res.json().catch(() => ([]));
      all = Array.isArray(data) ? data : [];
      applyFilter();
    }

    function applyFilter(){
      const q = buscar.value.trim().toLowerCase();
      if (!q) return render(all);
      const filtered = all.filter(r =>
        (r.atleta_nombre || '').toLowerCase().includes(q) ||
        (r.id_atleta || '').toLowerCase().includes(q) ||
        (r.servicio || '').toLowerCase().includes(q)
      );
      render(filtered);
    }

    buscar.addEventListener('input', applyFilter);
    load();
  </script>
</body>
</html>


