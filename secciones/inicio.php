<section id="view-inicio" class="view-section active">
    <!-- CARRUSEL DESTACADAS TOP 3 -->
    <?php if (empty($search) && empty($genero)): ?>
        <h2 class="section-title">Destacadas</h2>
        <div class="carousel-container">
            <?php
            $top3 = $conn->query("SELECT p.*, IFNULL(AVG(c.puntos), 0) as promedio FROM peliculas p LEFT JOIN calificaciones c ON p.id = c.pelicula_id GROUP BY p.id ORDER BY promedio DESC LIMIT 3");
            $i = 0;
            while ($row = $top3->fetch_assoc()):
                ?>
                <div class="carousel-slide <?php echo ($i == 0) ? 'active' : ''; ?>" style="background-image: url('<?php echo $row['imagen']; ?>');"
                    onclick="openMovie(<?php echo $row['id']; ?>, '<?php echo $row['titulo']; ?>', '<?php echo $row['genero']; ?>', '<?php echo addslashes($row['sinopsis']); ?>', '<?php echo $row['escenas']; ?>')">
                    <div class="carousel-overlay">
                        <span class="badge-rating" style="background: var(--pastel-secondary); color: white;">🏆 TOP <?php echo $i + 1; ?></span>
                        <div class="carousel-title"><?php echo $row['titulo']; ?></div>
                        <p>⭐ <?php echo number_format($row['promedio'], 1); ?> - <?php echo $row['genero']; ?></p>
                    </div>
                </div>
                <?php $i++; endwhile; ?>
        </div>
        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('.carousel-slide');
            if (slides.length > 0) {
                setInterval(() => {
                    slides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].classList.add('active');
                }, 4000);
            }
        </script>
    <?php endif; ?>

    <h2 class="section-title" style="margin-top: 30px;">Catálogo Completo</h2>
    <div class="movies-grid">
        <?php
        // Obtener favoritos del usuario para pintar el corazón rojo si corresponde
        $favs_user = [];
        if ($usuario_logueado) {
            $u_name = $_SESSION['usuario'];
            $f_res = $conn->query("SELECT pelicula_id FROM favoritos WHERE usuario_id = (SELECT id FROM usuarios WHERE username='$u_name')");
            while($f_row = $f_res->fetch_assoc()) { $favs_user[] = $f_row['pelicula_id']; }
        }

        $sql = "SELECT p.*, IFNULL(AVG(c.puntos), 0) as promedio FROM peliculas p LEFT JOIN calificaciones c ON p.id = c.pelicula_id WHERE 1=1";
        if (!empty($search)) { $sql .= " AND p.titulo LIKE '%$search%'"; }
        $sql .= " GROUP BY p.id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $is_fav = in_array($row['id'], $favs_user) ? 'active' : '';
                echo "
                <div class='movie-card'>
                    " . ($usuario_logueado ? "<button class='fav-heart-btn $is_fav' onclick='event.stopPropagation(); toggleFav({$row['id']}, this)'>♥</button>" : "") . "
                    <div onclick=\"openMovie({$row['id']}, '{$row['titulo']}', '{$row['genero']}', '" . addslashes($row['sinopsis']) . "', '{$row['escenas']}')\">
                        <img src='{$row['imagen']}' class='movie-img'>
                        <div class='movie-info'>
                            <span class='movie-meta'>{$row['genero']}</span>
                            <div class='movie-title'>{$row['titulo']}</div>
                            <span class='badge-rating'>⭐ " . number_format($row['promedio'], 1) . "</span>
                        </div>
                    </div>
                </div>";
            }
        }
        ?>
    </div>
</section>