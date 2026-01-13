<?php
include 'conexion.php';

// --- 1. LÓGICA DE ACCIONES (Igual que antes) ---

// A) AÑADIR VINILO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $artista = $_POST['artista'];
    $desc = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $anyo = $_POST['anyo'];
    
    $foto = 'default.png'; 
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = time() . "_" . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], "img/covers/" . $nombre_archivo)) {
            $foto = $nombre_archivo;
        }
    }

    $stmt = $conn->prepare("INSERT INTO vinilos (NOMBRE, ARTISTA, FOTO, DESCRIPCION, PRECIO, AÑO, VISIBLE) VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("ssssdi", $nombre, $artista, $foto, $desc, $precio, $anyo);
    $stmt->execute();
    header("Location: catalogo.php"); 
    exit();
}

// B) BORRAR VINILO
if (isset($_GET['borrar'])) {
    $id = $_GET['borrar'];
    $conn->query("DELETE FROM vinilos WHERE ID = $id");
    header("Location: catalogo.php");
    exit();
}

// C) MOSTRAR/OCULTAR
if (isset($_GET['toggle']) && isset($_GET['estado_actual'])) {
    $id = $_GET['toggle'];
    $nuevo_estado = ($_GET['estado_actual'] == 1) ? 0 : 1;
    $conn->query("UPDATE vinilos SET VISIBLE = $nuevo_estado WHERE ID = $id");
    header("Location: catalogo.php");
    exit();
}

// D) EDITAR VINILO (AJAX)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    $id = intval($_POST['id']);
    $updates = array();
    
    if (!empty($_POST['nombre'])) {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $updates[] = "NOMBRE = '$nombre'";
    }
    if (!empty($_POST['artista'])) {
        $artista = $conn->real_escape_string($_POST['artista']);
        $updates[] = "ARTISTA = '$artista'";
    }
    if (!empty($_POST['precio'])) {
        $precio = floatval($_POST['precio']);
        $updates[] = "PRECIO = $precio";
    }
    if (!empty($_POST['anyo'])) {
        $anyo = intval($_POST['anyo']);
        $updates[] = "AÑO = $anyo";
    }
    if (!empty($_POST['descripcion'])) {
        $descripcion = $conn->real_escape_string($_POST['descripcion']);
        $updates[] = "DESCRIPCION = '$descripcion'";
    }
    
    if (count($updates) > 0) {
        $sql = "UPDATE vinilos SET " . implode(", ", $updates) . " WHERE ID = $id";
        $conn->query($sql);
    }
    
    header("Location: catalogo.php");
    exit();
}

// --- 2. CONSULTA ---
$where = "";
$busqueda = "";
if (isset($_POST['busqueda'])) {
    $busqueda = $conn->real_escape_string($_POST['busqueda']);
    $where = "WHERE NOMBRE LIKE '%$busqueda%' OR ARTISTA LIKE '%$busqueda%'";
}
$sql = "SELECT * FROM vinilos $where ORDER BY ID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo RetroGroove</title>
      <link rel="icon" type="../frontend/image/png" href="img/favicon_o.svg">

    <style>
        /* --- ESTILOS RETROGROOVE --- */
        :root {
            --bg-dark: #0a0a0a;
            --bg-card: #161616;
            --accent: #e85d04;
            --text: #ffffff;
            --text-dim: #888888;
            --border: #333;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        
        body { background-color: var(--bg-dark); color: var(--text); padding: 20px; }
        
        h1 { color: var(--accent); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; }
        h2 { font-size: 1.1rem; margin-bottom: 15px; color: var(--text); border-bottom: 1px solid var(--border); padding-bottom: 10px; }

        .controls-container { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
        .card { background-color: var(--bg-card); padding: 20px; border-radius: 8px; border: 1px solid var(--border); }

        /* Inputs Generales */
        input[type="text"], input[type="number"], textarea {
            background-color: #222; border: 1px solid #444; color: white; padding: 10px; margin-bottom: 10px; border-radius: 4px; width: 100%; outline: none;
        }
        input:focus, textarea:focus { border-color: var(--accent); }

        /* --- ESTILO PERSONALIZADO INPUT FILE --- */
        .file-input-wrapper {
            margin-bottom: 15px;
            position: relative;
        }
        
        /* Ocultamos el input original feo */
        .file-input-wrapper input[type="file"] {
            display: none;
        }

        /* Estilamos el Label como si fuera un botón */
        .custom-file-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background-color: #222;
            border: 1px dashed #555;
            color: var(--text-dim);
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }

        .custom-file-label:hover {
            border-color: var(--accent);
            color: var(--text);
            background-color: #2a2a2a;
        }

        /* SVG Icon styles */
        .icon-svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* Botones Generales */
        input[type="submit"] {
            background-color: var(--accent); border: none; color: white; padding: 10px 20px; cursor: pointer; text-transform: uppercase; font-weight: bold; width: 100%; border-radius: 4px; transition: 0.3s;
        }
        input[type="submit"]:hover { background-color: #ff6b0a; }
        
        .btn-search { width: auto !important; margin-right: 10px; }

        /* Tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border); vertical-align: middle; }
        th { color: var(--accent); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        .cover-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; background-color: #333; }

        /* Acciones iconos */
        .actions { display: flex; gap: 10px; }
        .btn-icon {
            background: transparent; border: 1px solid #444; color: #ccc; padding: 6px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;
        }
        .btn-icon:hover { border-color: var(--accent); color: var(--accent); }
        .btn-del:hover { border-color: #ff4d4d; color: #ff4d4d; }

        .row-oculto { opacity: 0.5; }

        /* Estilos para edición inline */
        .edit-mode td input, .edit-mode td textarea {
            background-color: #222;
            border: 1px solid var(--accent);
            color: white;
            padding: 5px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .edit-cell {
            cursor: pointer;
            padding: 10px;
            min-height: 20px;
        }

        .edit-cell:hover {
            background-color: #1a1a1a;
        }

        .edit-mode .edit-cell input {
            width: 100%;
            box-sizing: border-box;
        }

        .btn-save, .btn-cancel {
            background-color: var(--accent);
            border: none;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.8rem;
            transition: 0.2s;
            margin-right: 5px;
        }

        .btn-save:hover { background-color: #ff6b0a; }
        .btn-cancel { background-color: #555; }
        .btn-cancel:hover { background-color: #666; }
    </style>
</head>
<body>

    <header>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>RetroGroove Manager</h1>
            <a href="index.html" style="background-color: var(--accent); color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; transition: 0.3s;">← Volver al inicio</a>
        </div>
    </header>

    <div class="controls-container">
        
        <div class="card" style="flex: 1; min-width: 320px;">
            <h2>Nuevo Vinilo</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="crear">
                <input type="text" name="nombre" placeholder="Nombre del Álbum" required>
                <input type="text" name="artista" placeholder="Artista" required>
                <div style="display:flex; gap:10px;">
                    <input type="number" step="0.01" name="precio" placeholder="Precio" required>
                    <input type="number" name="anyo" placeholder="Año">
                </div>
                <textarea name="descripcion" placeholder="Descripción breve" rows="2"></textarea>
                
                <div class="file-input-wrapper">
                    <input type="file" name="foto" id="inputFoto" accept="image/*" onchange="actualizarNombreArchivo()">
                    <label for="inputFoto" class="custom-file-label">
                        <svg class="icon-svg" viewBox="0 0 24 24">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span id="texto-archivo">Seleccionar Portada...</span>
                    </label>
                </div>

                <input type="submit" value="Guardar">
            </form>
        </div>

        <div class="card" style="flex: 1; min-width: 300px; height: fit-content;">
            <h2>Buscar</h2>
            <form action="" method="POST" style="display: flex; align-items: center;">
                <input type="text" name="busqueda" placeholder="Título o Artista..." value="<?php echo $busqueda; ?>" style="margin-bottom: 0; margin-right: 10px;">
                <input type="submit" value="Buscar" class="btn-search">
            </form>
             <?php if($busqueda != ""): ?>
                <div style="margin-top: 10px;">
                    <a href="catalogo.php" style="color: var(--text-dim); text-decoration: none; font-size: 0.9rem;">↺ Restablecer filtros</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Info</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $res): ?>
                        <?php $clase_fila = ($res['VISIBLE'] == 0) ? 'row-oculto' : ''; ?>
                        <tr class="<?php echo $clase_fila; ?>" data-id="<?php echo $res['ID']; ?>">
                            <td><img src="img/covers/<?php echo $res['FOTO']; ?>" class="cover-preview"></td>
                            <td>
                                <div class="edit-cell edit-nombre"><strong style="color:white;"><?php echo $res['NOMBRE']; ?></strong></div>
                                <div class="edit-cell edit-artista"><span style="color:var(--text-dim); font-size:0.9rem;"><?php echo $res['ARTISTA']; ?></span></div>
                            </td>
                            <td>
                                <div class="edit-cell edit-descripcion" style="font-size:0.9rem; color:var(--text-dim);"><?php echo $res['DESCRIPCION']; ?></div>
                            </td>
                            <td>
                                <div class="edit-cell edit-precio"><?php echo $res['PRECIO']; ?>€</div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-icon btn-edit" title="Editar" onclick="toggleEdit(this)">
                                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>

                                    <a href="catalogo.php?toggle=<?php echo $res['ID']; ?>&estado_actual=<?php echo $res['VISIBLE']; ?>" class="btn-icon" title="Visibilidad">
                                        <?php if($res['VISIBLE'] == 1): ?>
                                            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <?php else: ?>
                                            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M1 1l22 22"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path></svg>
                                        <?php endif; ?>
                                    </a>

                                    <a href="catalogo.php?borrar=<?php echo $res['ID']; ?>" class="btn-icon btn-del" onclick="return confirm('¿Eliminar?');" title="Eliminar">
                                        <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </a>

                                    <button class="btn-save" style="display:none;" onclick="saveEdit(this)">Guardar</button>
                                    <button class="btn-cancel" style="display:none;" onclick="cancelEdit(this)">Cancelar</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; color: var(--text-dim);">No hay resultados.</p>
        <?php endif; ?>
    </div>

    <script>
        // Script para edición inline
        function toggleEdit(btn) {
            const row = btn.closest('tr');
            const isEditing = row.classList.contains('edit-mode');

            if (isEditing) {
                cancelEdit(btn);
            } else {
                // Entrar en modo edición
                row.classList.add('edit-mode');
                
                // Convertir celdas a inputs
                const nombre = row.querySelector('.edit-nombre');
                const artista = row.querySelector('.edit-artista');
                const descripcion = row.querySelector('.edit-descripcion');
                const precio = row.querySelector('.edit-precio');
                
                const nombreText = nombre.textContent.trim();
                const artistaText = artista.textContent.trim();
                const descripcionText = descripcion.textContent.trim();
                const precioText = precio.textContent.trim().replace('€', '');

                nombre.innerHTML = `<input type="text" class="input-nombre" value="${nombreText}">`;
                artista.innerHTML = `<input type="text" class="input-artista" value="${artistaText}">`;
                descripcion.innerHTML = `<textarea class="input-descripcion" rows="2">${descripcionText}</textarea>`;
                precio.innerHTML = `<input type="number" step="0.01" class="input-precio" value="${precioText}">`;

                // Mostrar/ocultar botones
                row.querySelector('.btn-edit').style.display = 'none';
                row.querySelectorAll('.btn-save, .btn-cancel').forEach(b => b.style.display = 'inline-block');
            }
        }

        function saveEdit(btn) {
            const row = btn.closest('tr');
            const id = row.dataset.id;

            const nombre = row.querySelector('.input-nombre')?.value || '';
            const artista = row.querySelector('.input-artista')?.value || '';
            const descripcion = row.querySelector('.input-descripcion')?.value || '';
            const precio = row.querySelector('.input-precio')?.value || '';

            const formData = new FormData();
            formData.append('accion', 'editar');
            formData.append('id', id);
            if (nombre) formData.append('nombre', nombre);
            if (artista) formData.append('artista', artista);
            if (descripcion) formData.append('descripcion', descripcion);
            if (precio) formData.append('precio', precio);

            fetch('catalogo.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                location.reload();
            });
        }

        function cancelEdit(btn) {
            const row = btn.closest('tr');
            location.reload();
        }

        // Script pequeño para que cambie el texto "Seleccionar Portada" 
        function actualizarNombreArchivo() {
            const input = document.getElementById('inputFoto');
            const texto = document.getElementById('texto-archivo');
            
            if (input.files && input.files.length > 0) {
                texto.textContent = input.files[0].name;
                texto.style.color = '#fff';
            } else {
                texto.textContent = 'Seleccionar Portada...';
            }
        }
    </script>
</body>
</html>