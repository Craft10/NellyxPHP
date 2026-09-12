<?php
define('APP_NAME', 'Nellyx');
define('APP_VERSION', '1.0.0');

$phpVersion = phpversion();
$serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Servidor Desconocido';
$serverIp = $_SERVER['SERVER_ADDR'] ?? $_SERVER['HTTP_HOST'] ?? '127.0.0.1';
$uptimeStatus = 'Operativo';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> — Entorno Activo</title>
    <style>
        :root {
            --bg: #090d16;
            --card-bg: #111726;
            --border: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #6366f1;
            --accent-glow: rgba(99, 102, 241, 0.15);
            --success: #10b981;
            --success-bg: rgba(16, 185, 129, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .container {
            width: 100%;
            max-width: 640px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            color: #fff;
            box-shadow: 0 0 15px var(--accent-glow);
        }

        .brand-text h1 {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .brand-text p {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .badge {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            background-color: var(--success);
            border-radius: 50%;
            display: inline-block;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .card-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .card-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
            word-break: break-all;
        }

        .footer {
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="brand">
                <div class="logo">N</div>
                <div class="brand-text">
                    <h1><?php echo APP_NAME; ?></h1>
                    <p>Core Runtime Setup</p>
                </div>
            </div>
            <div class="badge">
                <span class="badge-dot"></span>
                <?php echo $uptimeStatus; ?>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="card-label">Versión de PHP</div>
                <div class="card-value">v<?php echo $phpVersion; ?></div>
            </div>
            
            <div class="card">
                <div class="card-label">Servidor Web</div>
                <div class="card-value"><?php echo htmlspecialchars($serverSoftware); ?></div>
            </div>

            <div class="card">
                <div class="card-label">Dirección Host</div>
                <div class="card-value"><?php echo htmlspecialchars($serverIp); ?></div>
            </div>

            <div class="card">
                <div class="card-label">Versión App</div>
                <div class="card-value">v<?php echo APP_VERSION; ?></div>
            </div>
        </div>

        <div class="footer">
            Sistema inicializado correctamente • Entorno listo para <a href="#">Nellyx Core</a>
        </div>
    </div>

</body>
</html>
