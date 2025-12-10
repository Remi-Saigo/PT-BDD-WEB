<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Chambres - Hôtel</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
  body { margin:0; font-family: Arial, sans-serif; background:#f8f8f8; color:#333; }
  header { background: url('https://www.parchotel-alsace.com/fr/img/slideshow_xlarge/1070_main.jpeg') center/cover no-repeat; height:60vh; display:flex; align-items:center; justify-content:center; text-align:center; color:white; position:relative; }
  header::after { content: ""; position:absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4); }
  header h1 { z-index:2; font-size:3rem; margin:0; }
  .carousel { width:80%; max-width:900px; margin:40px auto 10px; overflow:hidden; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  .slides { display:flex; transition: transform 0.5s ease; }
  .slides img { width:100%; object-fit:cover; transition: transform 0.5s; }
  .slides img:hover { transform: scale(1.05); }
  .carousel-buttons { text-align:center; margin-bottom:40px; }
  .carousel-buttons button { padding:10px 15px; margin:0 5px; cursor:pointer; background:#0066cc; color:white; border:none; border-radius:6px; transition: background 0.3s, transform 0.3s; }
  .carousel-buttons button:hover { background:#004999; transform: scale(1.1); }
  main { max-width:900px; margin:20px auto; padding:0 20px; text-align:center; }
  .links a { display:inline-flex; align-items:center; justify-content:center; margin:10px; padding:10px 20px; background:#004999; color:white; text-decoration:none; border-radius:6px; transition:0.3s, transform 0.3s; font-size:1.1rem; }
  .links a i { margin-right:10px; font-size:1.3rem; }
  .links a:hover { background:#002f66; transform: scale(1.05) rotate(-2deg); }
</style>
</head>
<body>
<header>
  <h1>Nos Chambres</h1>
</header>
<div class="carousel">
  <div class="slides">
    <img src="https://www.hotel-3-etoiles.com/wp-content/uploads/2024/10/pexels-photo-29000006.webp" />
    <img src="https://cdn.ronalbathrooms.com/assets_thumbnails/Magazine/2023/19106/image-thumb__19106__magazine-details-hero-img/625_high.jpg" />
    <img src="https://images.greengo.voyage/_/w_1440__q_75/plain/s3://greengobackend-production-media/pictures/accommmodation/ordered_images/20220917_120021.jpg" />
  </div>
</div>
<div class="carousel-buttons">
  <button onclick="prev()">⬅️</button>
  <button onclick="next()">➡️</button>
</div>
<script>
let index = 0;
const slides = document.querySelector('.slides');
const total = document.querySelectorAll('.slides img').length;
function updateCarousel() {
  const slideWidth = document.querySelector('.carousel').clientWidth;
  slides.style.transform = `translateX(${-index * slideWidth}px)`;
}
function next() { index = (index + 1) % total; updateCarousel(); }
function prev() { index = (index - 1 + total) % total; updateCarousel(); }
let autoSlide = setInterval(() => { next(); }, 3000);
const carousel = document.querySelector('.carousel');
carousel.addEventListener('mouseenter', () => { clearInterval(autoSlide); });
carousel.addEventListener('mouseleave', () => { autoSlide = setInterval(() => { next(); }, 3000); });
window.addEventListener('resize', updateCarousel);
</script>
</body>
</html>
