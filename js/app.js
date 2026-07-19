// CONTROL DE VISTAS DE PESTAÑAS
function switchView(viewName, btn) {
  document
    .querySelectorAll(".view-section")
    .forEach((s) => s.classList.remove("active"));
  document
    .querySelectorAll(".menu-btn")
    .forEach((b) => b.classList.remove("active"));
  document.getElementById("view-" + viewName).classList.add("active");
  if (btn) btn.classList.add("active");
}

// BUSCADOR EN TIEMPO REAL
const buscador = document.getElementById("buscador");
const box = document.getElementById("suggestions");

if (buscador) {
  buscador.addEventListener("input", function () {
    let texto = this.value;
    if (texto.length < 1) {
      box.style.display = "none";
      return;
    }
    fetch("buscar.php?q=" + encodeURIComponent(texto))
      .then((res) => res.json())
      .then((data) => {
        box.innerHTML = "";
        if (data.length > 0) {
          box.style.display = "block";
          data.forEach((titulo) => {
            let item = document.createElement("div");
            item.classList.add("suggestion-item");
            item.innerText = titulo;
            item.onclick = function () {
              buscador.value = titulo;
              box.style.display = "none";
              buscador.form.submit();
            };
            box.appendChild(item);
          });
        } else {
          box.style.display = "none";
        }
      });
  });
}

// ACCIÓN DEL CORAZÓN (FAVORITOS)
function toggleFav(id, btn) {
  const formData = new FormData();
  formData.append("pelicula_id", id);

  fetch("interacciones.php?action=toggle_favorito", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((res) => {
      if (res.trim() === "added") {
        btn.classList.add("active");
      } else if (res.trim() === "removed") {
        btn.classList.remove("active");
      } else if (res.trim() === "no_session") {
        openAuthModal("login");
      }
    });
}

// COMPONENTE DETALLES PELICULA Y ESCENAS
function openMovie(id, titulo, genero, sinopsis, escenasString) {
  activeMovieId = id; // Guardar ID global de la película seleccionada
  document.getElementById("modalTitle").innerText = titulo;
  document.getElementById("modalGenre").innerText = "Género: " + genero;
  document.getElementById("modalSynopsis").innerText = sinopsis;

  // Resetear estrellas visualmente
  resetStars();

  const contenedorEscenas = document.getElementById("modalScenes");
  contenedorEscenas.innerHTML = "";

  if (escenasString) {
    let listaEscenas = escenasString.split(",");
    listaEscenas.forEach((url) => {
      let img = document.createElement("img");
      img.src = url.trim();
      img.classList.add("escena-img");
      contenedorEscenas.appendChild(img);
    });
  }

  // Configurar clic en botón reproducir
  document.getElementById("btnPlayAction").onclick = function () {
    closeModal("movieModal");
    if (isLogged) {
      // Guardar en historial de vistos automáticamente
      const formV = new FormData();
      formV.append("pelicula_id", id);
      fetch("interacciones.php?action=marcar_vista", {
        method: "POST",
        body: formV,
      });

      alert("¡¡¡ Disfruta tu película !!! 🍿🎬");
    } else {
      openAuthModal("login");
    }
  };

  document.getElementById("movieModal").style.display = "flex";
}

// CALIFICADOR POR ESTRELLAS DINÁMICO
function sendRating(puntos) {
  if (!activeMovieId) return;

  const formData = new FormData();
  formData.append("pelicula_id", activeMovieId);
  formData.append("puntos", puntos);

  fetch("interacciones.php?action=calificar", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((res) => {
      if (res.trim() === "success") {
        alert("¡Gracias por calificar! Tu voto modificará los Tops globales.");
        // Pintar estrellas
        const stars = document.querySelectorAll(".star-item");
        stars.forEach((star, index) => {
          if (index < puntos) star.classList.add("active");
          else star.classList.remove("active");
        });
      }
    });
}

function resetStars() {
  document
    .querySelectorAll(".star-item")
    .forEach((s) => s.classList.remove("active"));
}

function closeModal(id) {
  document.getElementById(id).style.display = "none";
}
function openAuthModal(view) {
  document.getElementById("authModal").style.display = "flex";
  toggleAuthViews(view);
}
function toggleAuthViews(view) {
  document.getElementById("loginFormSection").style.display =
    view === "login" ? "block" : "none";
  document.getElementById("registerFormSection").style.display =
    view === "register" ? "block" : "none";
}

function submitLogin(e) {
  e.preventDefault();
  fetch("auth.php?action=login", {
    method: "POST",
    body: new FormData(document.getElementById("loginForm")),
  })
    .then((res) => res.text())
    .then((res) => {
      if (res.trim() === "success") {
        location.reload();
      } else {
        alert("Error");
      }
    });
}

function submitRegister(e) {
  e.preventDefault();
  fetch("auth.php?action=register", {
    method: "POST",
    body: new FormData(document.getElementById("registerForm")),
  })
    .then((res) => res.text())
    .then((res) => {
      if (res.trim() === "success") {
        location.reload();
      } else {
        alert("Error");
      }
    });
}
// FUNCIÓN PARA FILTRAR PELÍCULAS POR GÉNERO EN RECOMENDACIONES
function filtrarPorGenero(generoSeleccionado, botonActivo) {
    // 1. Cambiar los estilos de los botones de género
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.style.background = '#f1ece6';
        btn.style.color = 'var(--text-dark)';
    });
    
    // Resaltar el botón presionado
    botonActivo.style.background = 'var(--pastel-primary)';
    botonActivo.style.color = '#7c4c4c';

    // 2. Filtrar las tarjetas de películas
    const peliculas = document.querySelectorAll('.rec-movie-item');
    
    peliculas.forEach(pelicula => {
        const generoPelicula = pelicula.getAttribute('data-genero');
        
        if (generoSeleccionado === 'Todos' || generoPelicula === generoSeleccionado) {
            pelicula.style.display = 'block'; // Mostrar
        } else {
            pelicula.style.display = 'none';  // Ocultar
        }
    });
}