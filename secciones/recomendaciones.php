<section id="view-recomendaciones" class="view-section">
    <h2 class="section-title">Recomendaciones Personalizadas</h2>

    <!-- BARRA DE FILTROS POR GÉNERO -->
    <div class="generos-filter-container" style="margin-bottom: 25px; display: flex; gap: 10px; flex-wrap: wrap;">
        <button class="filter-btn active" onclick="filtrarPorGenero('Todos', this)"
            style="padding: 8px 16px; border: none; border-radius: 20px; cursor: pointer; background: var(--pastel-primary); color: #7c4c4c; font-weight: bold; transition: 0.2s;">✨
            Todos</button>
        <button class="filter-btn" onclick="filtrarPorGenero('Acción', this)"
            style="padding: 8px 16px; border: none; border-radius: 20px; cursor: pointer; background: #f1ece6; color: var(--text-dark); font-weight: bold; transition: 0.2s;">💥
            Acción</button>
        <button class="filter-btn" onclick="filtrarPorGenero('Ciencia Ficción', this)"
            style="padding: 8px 16px; border: none; border-radius: 20px; cursor: pointer; background: #f1ece6; color: var(--text-dark); font-weight: bold; transition: 0.2s;">🚀
            Ciencia Ficción</button>
        <button class="filter-btn" onclick="filtrarPorGenero('Animación', this)"
            style="padding: 8px 16px; border: none; border-radius: 20px; cursor: pointer; background: #f1ece6; color: var(--text-dark); font-weight: bold; transition: 0.2s;">🎨
            Animación</button>
        <button class="filter-btn" onclick="filtrarPorGenero('Romance', this)"
            style="padding: 8px 16px; border: none; border-radius: 20px; cursor: pointer; background: #f1ece6; color: var(--text-dark); font-weight: bold; transition: 0.2s;">💖
            Romance</button>
        <button class="filter-btn" onclick="filtrarPorGenero('Terror', this)"
            style="padding: 8px 16px; border: none; border-radius: 20px; cursor: pointer; background: #f1ece6; color: var(--text-dark); font-weight: bold; transition: 0.2s;">👻
            Terror</button>
    </div>

    <div class="movies-grid" id="recomendaciones-grid">
        <?php
        // Traemos todo el catálogo ordenado por las mejores calificaciones globales
        $sql_rec = "SELECT p.*, IFNULL(AVG(c.puntos), 0) as promedio 
                    FROM peliculas p 
                    LEFT JOIN calificaciones c ON p.id = c.pelicula_id 
                    GROUP BY p.id 
                    ORDER BY promedio DESC";

        $res_rec = $conn->query($sql_rec);
        if ($res_rec && $res_rec->num_rows > 0) {
            while ($row = $res_rec->fetch_assoc()) {
                echo "
                <div class='movie-card rec-movie-item' data-genero='{$row['genero']}' onclick=\"openMovie({$row['id']}, '{$row['titulo']}', '{$row['genero']}', '" . addslashes($row['sinopsis']) . "', '{$row['escenas']}')\">
                    <img src='{$row['imagen']}' class='movie-img' alt='{$row['titulo']}'>
                    <div class='movie-info'>
                        <span class='movie-meta'>{$row['genero']}</span>
                        <div class='movie-title'>{$row['titulo']}</div>
                        <span class='badge-rating' style='background-color: var(--pastel-green); color: #4c7c38;'>💖 Recomendada</span>
                    </div>
                </div>";
            }
        } else {
            echo "<p style='grid-column: 1/-1;'>No hay películas disponibles en este momento.</p>";
        }
        ?>
    </div>
</section>