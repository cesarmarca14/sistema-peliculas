<section id="view-estadisticas" class="view-section">
    <h2 class="section-title">📊 Analíticas y Estadísticas de la Plataforma</h2>

    <div class="stats-container"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Métrica 1 -->
        <div class="stat-card"
            style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--pastel-primary);">
            <p style="font-size: 14px; color: var(--text-light); margin: 0;">Película Más Votada (Popular)</p>
            <?php
            $res_pop = $conn->query("SELECT p.titulo, COUNT(c.id) as votos FROM peliculas p JOIN calificaciones c ON p.id = c.pelicula_id GROUP BY p.id ORDER BY votos DESC LIMIT 1");
            $pop = $res_pop->fetch_assoc();
            ?>
            <h3 style="margin: 10px 0 0 0; color: var(--text-dark);">
                <?php echo $pop ? $pop['titulo'] : 'Ninguna'; ?>
            </h3>
            <span style="font-size: 12px; color: var(--pastel-secondary); font-weight: bold;">
                <?php echo $pop ? $pop['votos'] : 0; ?> calificaciones en total
            </span>
        </div>

        <!-- Métrica 2 -->
        <div class="stat-card"
            style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--pastel-green);">
            <p style="font-size: 14px; color: var(--text-light); margin: 0;">Tu Género Favorito Personal</p>
            <?php
            if ($usuario_logueado) {
                $u_name = $_SESSION['usuario'];
                $res_gen = $conn->query("SELECT p.genero, COUNT(c.id) as conteo FROM calificaciones c JOIN peliculas p ON c.pelicula_id = p.id WHERE c.usuario_id = (SELECT id FROM usuarios WHERE username='$u_name') GROUP BY p.genero ORDER BY conteo DESC LIMIT 1");
                $gen = $res_gen->fetch_assoc();
                $gen_fav = $gen ? $gen['genero'] : 'Sin registrar';
            } else {
                $gen_fav = 'Inicia sesión';
            }
            ?>
            <h3 style="margin: 10px 0 0 0; color: var(--text-dark);">
                <?php echo $gen_fav; ?>
            </h3>
            <span style="font-size: 12px; color: #4c7c38; font-weight: bold;">Basado en tus estrellas otorgadas</span>
        </div>

        <!-- Métrica 3 -->
        <div class="stat-card"
            style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--pastel-accent);">
            <p style="font-size: 14px; color: var(--text-light); margin: 0;">Total Interacciones Globales</p>
            <?php
            $tot_c = $conn->query("SELECT COUNT(*) as t FROM calificaciones")->fetch_assoc()['t'];
            $tot_f = $conn->query("SELECT COUNT(*) as t FROM favoritos")->fetch_assoc()['t'];
            $tot_cm = $conn->query("SELECT COUNT(*) as t FROM comentarios")->fetch_assoc()['t'];
            ?>
            <h3 style="margin: 10px 0 0 0; color: var(--text-dark);">
                <?php echo ($tot_c + $tot_f + $tot_cm); ?>
            </h3>
            <span style="font-size: 12px; color: #7c4c4c; font-weight: bold;">⭐
                <?php echo $tot_c; ?> | ❤️
                <?php echo $tot_f; ?> | 💬
                <?php echo $tot_cm; ?>
            </span>
        </div>
    </div>

    <!-- TABLA DE CONTROL DE REPORTE -->
    <h3 class="section-title" style="font-size: 18px;">📋 Historial Analítico de Votaciones</h3>
    <table
        style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
        <thead>
            <tr style="background: #f1ece6; color: var(--text-dark); text-align: left;">
                <th style="padding: 12px;">Usuario</th>
                <th style="padding: 12px;">Película</th>
                <th style="padding: 12px;">Género</th>
                <th style="padding: 12px;">Puntuación</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tabla = $conn->query("SELECT u.username, p.titulo, p.genero, c.puntos FROM calificaciones c JOIN usuarios u ON c.usuario_id = u.id JOIN peliculas p ON c.pelicula_id = p.id ORDER BY c.id DESC LIMIT 10");
            if ($tabla && $tabla->num_rows > 0) {
                while ($t_row = $tabla->fetch_assoc()) {
                    echo "<tr style='border-bottom: 1px solid #f1ece6;'>
                            <td style='padding: 12px;'>👤 {$t_row['username']}</td>
                            <td style='padding: 12px; font-weight: bold;'>{$t_row['titulo']}</td>
                            <td style='padding: 12px;'>{$t_row['genero']}</td>
                            <td style='padding: 12px; color: #ffd166;'>";
                    for ($x = 0; $x < $t_row['puntos']; $x++)
                        echo "★";
                    echo "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='padding:12px; text-align:center;'>No hay votaciones registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>