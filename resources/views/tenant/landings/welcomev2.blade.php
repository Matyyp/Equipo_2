@extends('tenant.layouts.landingv2')

@section('content')
@push('scripts')
<script>
  function setVideo(url) {
    document.getElementById('videoFrame').src = url;
  }
</script>
<script>
  new Swiper('.image-carousel', {
    loop: true,
    spaceBetween: 20,
    slidesPerView: 1,
    autoplay: { delay: 3000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    breakpoints: { 640: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
  });
</script>

<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});
</script>
@endpush

@endsection
