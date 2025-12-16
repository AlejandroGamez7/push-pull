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
    </style>
</head>
<body>

    <header>
        <h1>RetroGroove Manager</h1>
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
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $res): ?>
                        <?php $clase_fila = ($res['VISIBLE'] == 0) ? 'row-oculto' : ''; ?>
                        <tr class="<?php echo $clase_fila; ?>">
                            <td><img src="img/covers/<?php echo $res['FOTO']; ?>" class="cover-preview"></td>
                            <td>
                                <strong style="color:white;"><?php echo $res['NOMBRE']; ?></strong><br>
                                <span style="color:var(--text-dim); font-size:0.9rem;"><?php echo $res['ARTISTA']; ?></span>
                            </td>
                            <td><?php echo $res['PRECIO']; ?>€</td>
                            <td>
                                <div class="actions">
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
        // Script pequeño para que cambie el texto "Seleccionar Portada" 
        // por el nombre del archivo cuando el usuario elige uno.
        function actualizarNombreArchivo() {
            const input = document.getElementById('inputFoto');
            const texto = document.getElementById('texto-archivo');
            
            if (input.files && input.files.length > 0) {
                texto.textContent = input.files[0].name;
                texto.style.color = '#fff'; // Resaltar que ya hay archivo
            } else {
                texto.textContent = 'Seleccionar Portada...';
            }
        }
    </script>
</body>
</html>