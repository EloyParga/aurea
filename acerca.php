<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acerca de Nosotros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AOS Animaciones -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="assets/css/estilos.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        .hero {
            background: url('assets/media/img/banner-about-us.jpeg') no-repeat center center/cover;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.8);
        }

        .section-title {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 2.5rem;
            font-weight: lighter;
            color: #9D7AC0;
        }

        .icono {
            font-size: 3rem;
            color: #9D7AC0;
        }
    </style>
</head>
<body>

<?php include('partials/navbar.php'); ?>

<!-- Hero -->
<section class="hero text-center">
    <div data-aos="fade-up">
        <h1 class="display-4">Conoce Nuestra Historia</h1>
        <p class="lead">Pasión por la belleza, dedicación a nuestros clientes</p>
    </div>
</section>

<!-- Historia -->
<section class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6" data-aos="fade-right">
            <img src="assets/media/img/fundadora.jpg" alt="Fundadora" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <h2 class="section-title mb-4">Nuestra Historia</h2>
            <p>Desde nuestros humildes comienzos, nos hemos dedicado a ofrecer productos de alta calidad que resaltan la belleza natural. Con un enfoque ético, artesanal y sostenible, nuestra marca busca empoderar a cada persona para que se sienta auténtica y radiante.</p>
            <p>Fundada en 2018, hemos crecido manteniendo nuestra esencia: cuidado, respeto y elegancia.</p>
        </div>
    </div>
</section>

<!-- Misión y visión -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-6" data-aos="zoom-in">
                <div class="p-4 bg-white rounded shadow">
                    <div class="icono mb-3">🌱</div>
                    <h3 class="section-title">Nuestra Misión</h3>
                    <p>Crear productos que respeten el cuerpo y el medio ambiente, fomentando el bienestar y la belleza consciente.</p>
                </div>
            </div>
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="150">
                <div class="p-4 bg-white rounded shadow">
                    <div class="icono mb-3">🌟</div>
                    <h3 class="section-title">Nuestra Visión</h3>
                    <p>Ser una marca referente en el cuidado personal natural, inclusivo y sostenible a nivel global.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ingredientes y Fórmula Secreta -->
<section class="bg-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="section-title mb-4">Ingredientes Naturales</h2>
                <p>Cada uno de nuestros productos está formulado con ingredientes cuidadosamente seleccionados de origen vegetal. Utilizamos aceites esenciales, extractos botánicos, mantecas orgánicas y activos naturales que nutren, protegen y realzan tu piel de manera saludable.</p>
                <p>Priorizamos materias primas biodegradables, sin parabenos, sin sulfatos y libres de crueldad animal.</p>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <img src="assets/media/img/ingredientes.jpg" alt="Ingredientes naturales" class="img-fluid rounded shadow">
            </div>
        </div>
        <div class="row align-items-center mt-5">
            <div class="col-md-6 order-md-2" data-aos="fade-left">
                <h2 class="section-title mb-4">Nuestra Fórmula Secreta</h2>
                <p>Lo que hace únicos nuestros productos no solo son sus ingredientes, sino la manera en que los combinamos. Nuestra fórmula patentada equilibra tradición herbolaria y ciencia cosmética para obtener resultados visibles sin comprometer tu salud ni la del planeta.</p>
                <p>Esta mezcla única es el corazón de nuestra marca: efectiva, ética y exclusiva.</p>
            </div>
            <div class="col-md-6 order-md-1" data-aos="fade-right">
                <img src="assets/media/img/ingredientes.jpg" alt="Fórmula secreta" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Valores -->
<section class="container my-5">
    <h2 class="text-center section-title mb-5" data-aos="fade-up">Nuestros Valores</h2>
    <div class="row text-center g-4">
        <div class="col-sm-6 col-md-4" data-aos="flip-left">
            <div class="p-4 bg-light rounded shadow">
                💜 <h5>Autenticidad</h5>
                <p>Celebramos la belleza real y única de cada persona.</p>
            </div>
        </div>
        <div class="col-sm-6 col-md-4" data-aos="flip-left" data-aos-delay="150">
            <div class="p-4 bg-light rounded shadow">
                🌍 <h5>Sostenibilidad</h5>
                <p>Usamos ingredientes naturales y envases responsables.</p>
            </div>
        </div>
        <div class="col-sm-6 col-md-4" data-aos="flip-left" data-aos-delay="300">
            <div class="p-4 bg-light rounded shadow">
                👥 <h5>Inclusión</h5>
                <p>Diseñamos para todas las personas, sin distinción.</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include('partials/footer.php'); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>
