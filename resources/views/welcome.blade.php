<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Chakoza Menu</title>

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <audio id="flip-sound" src="{{ asset('./assets/page-flip.mp3') }}" preload="auto"></audio>

    <style>
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body {
            @if(isset($setting) && $setting->getFirstMediaUrl('background'))
            background: url("{{ $setting->getFirstMediaUrl('background') }}") center center / cover no-repeat fixed;
            @else
            background: #000;
            @endif

            width: 100vw;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
            font-family: 'Tajawal', sans-serif;
            color: #fff;
            display: block;
        }

        .app-container {
            width: 100vw;
            max-width: 100vw;
            height: 100%;
            position: relative;
            display: block;
        }

        #categories-section {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            scrollbar-width: thin;
            scrollbar-color: #f59e0b #222;
        }

        #categories-section::-webkit-scrollbar {
            width: 6px;
        }
        #categories-section::-webkit-scrollbar-track {
            background: #222;
        }
        #categories-section::-webkit-scrollbar-thumb {
            background-color: #f59e0b;
            border-radius: 10px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            width: 100%;
            padding: 10px;
            padding-bottom: 50px;
            align-items: stretch;
        }

        .category-card {
            background: #111;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
            border: 1px solid rgba(245, 158, 11, 0.2);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .category-card:active {
            transform: scale(0.95);
        }

        .category-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
            display: block;
        }

        .category-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            pointer-events: none;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 12px;
        }

        .category-title {
            color: #f59e0b;
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            margin: 0;
            padding: 0 5px;
        }

        .category-card:hover .category-img {
            transform: scale(1.05);
        }

        .category-card:hover {
            border-color: rgba(245, 158, 11, 0.8);
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.8);
        }

        /* =========================
           BOOK SECTION (FULLSCREEN)
           ========================= */
        #book-section {
            position: absolute;
            inset: 0;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            width: 100vw;
            height: 100vh;
            height: 100dvh;

            margin: 0;
            padding: 0;

            background: rgba(0, 0, 0, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);

            z-index: 9999;
            overflow: hidden;
        }

        .back-btn {
            position: absolute;
            top: calc(14px + env(safe-area-inset-top, 0));
            right: calc(14px + env(safe-area-inset-right, 0));
            background: rgba(30,30,30, 0.55);
            color: #f59e0b;
            border: 1px solid rgba(245,158,11,0.7);
            border-radius: 25px;
            padding: 9px 16px;
            font-size: 0.95rem;
            font-family: inherit;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        }

        .back-btn:hover, .back-btn:active {
            background: #f59e0b;
            color: #000;
        }

        /* =========================
           SMALL TRANSPARENT ARROWS
           ========================= */
        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 26px;
            height: 26px;
            background: rgba(255, 255, 255, 0.10);
            color: rgba(255, 255, 255, 0.55);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10000;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            opacity: 0.35;
            transition: 0.25s ease;
        }

        .nav-arrow svg {
            width: 12px;
            height: 12px;
        }

        .nav-arrow:hover,
        .nav-arrow:active {
            opacity: 0.75;
            background: rgba(255, 255, 255, 0.18);
            color: rgba(255, 255, 255, 0.95);
            transform: translateY(-50%) scale(1.03);
        }

        .right-arrow {
            right: calc(10px + env(safe-area-inset-right, 0));
        }

        .left-arrow {
            left: calc(10px + env(safe-area-inset-left, 0));
        }

        /* =========================
           SWIPER FULLSCREEN + NO BLACK SIDES
           (blur background trick)
           ========================= */
        .swiper, .swiper-wrapper, .swiper-slide {
    width: 100vw !important;
    height: 100dvh !important;
}

        .swiper {
            display: block;
        }

        .swiper-slide {
    overflow: hidden !important;
    background: transparent !important;
}

        /* background = same image cover + blur */
        .swiper-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--bg);
            background-size: cover;
            background-position: center;
            filter: blur(18px);
            transform: scale(1.15);
            opacity: 0.55;
        }

        /* dark overlay for contrast */
        .swiper-slide::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.25);
        }

        .swiper-zoom-container {
    width: 100% !important;
    height: 100% !important;
}

        .swiper-slide img {
    width: 100vw !important;
    height: 100dvh !important;
    object-fit: fill !important;   /* المهم */
    display: block !important;
    margin: 0 !important;
}

.swiper,
.swiper-wrapper,
.swiper-slide,
.swiper-zoom-container,
.swiper-slide img {
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
}

.swiper {
  -webkit-perspective: 1000px;
  perspective: 1000px;
}

/* اختياري: يساعد ببعض الأجهزة */
.swiper-slide img {
  image-rendering: auto;
}

        .swipe-hint {
            position: absolute;
            bottom: calc(32px + env(safe-area-inset-bottom, 0));
            background: rgba(0, 0, 0, 0.45);
            padding: 10px 18px;
            border-radius: 30px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: none;
            animation: slideLeft 2s infinite ease-in-out;
            z-index: 10000;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes slideLeft {
            0%, 100% { transform: translateX(0); opacity: 0.5; }
            50% { transform: translateX(-15px); opacity: 1; }
        }

        .no-pages-msg {
            display: none;
            color: #ccc;
            font-size: 1.2rem;
            text-align: center;
            margin-top: 50%;
        }
    </style>
</head>
<body>

<div class="app-container">

    <div id="categories-section">
        <div class="categories-grid">
            @foreach($categories as $category)
                <div class="category-card" onclick="openCategory({{ $category->id }})">
                    <img class="category-img" src="{{ $category->getFirstMediaUrl('thumb') ?: 'https://via.placeholder.com/400x400.png?text=بدون+صورة' }}" alt="صورة القسم">
                </div>
            @endforeach
        </div>
    </div>

    <div id="book-section">
        <button class="back-btn" onclick="backToCategories()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            الرئيسية
        </button>

        <button class="nav-arrow right-arrow" onclick="flipToNext()" id="next-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </button>

        <button class="nav-arrow left-arrow" onclick="flipToPrev()" id="prev-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </button>

        <div class="no-pages-msg" id="no-pages-msg">
            عذراً، لا يوجد صفحات مضافة لهذا القسم حالياً.
        </div>

        <div class="swiper" id="swiper-container-wrapper" style="display: none;" dir="ltr">
            <div class="swiper-wrapper" id="swiper-wrapper"></div>
        </div>

        <div class="swipe-hint" id="hint">
            <span>اسحب للتقليب</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const categoryData = {
        @foreach($categories as $category)
            {{ $category->id }}: [
                @foreach($category->menuPages as $page)
                    "{{ $page->getFirstMediaUrl('image') }}",
                @endforeach
            ],
        @endforeach
    };

    let swiperInstance = null;

    function openCategory(categoryId) {
        const pages = categoryData[categoryId];

        const categoriesSection = document.getElementById('categories-section');
        const bookSection = document.getElementById('book-section');
        const swiperContainer = document.getElementById('swiper-container-wrapper');
        const swiperWrapper = document.getElementById('swiper-wrapper');
        const noPagesMsg = document.getElementById('no-pages-msg');
        const hint = document.getElementById('hint');

        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        categoriesSection.style.display = 'none';
        bookSection.style.display = 'flex';
        hint.style.display = 'flex';
        hint.style.opacity = '1';

        if (swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
        }

        swiperWrapper.innerHTML = '';

        if (!pages || pages.length === 0) {
            swiperContainer.style.display = 'none';
            hint.style.display = 'none';
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            noPagesMsg.style.display = 'block';
            return;
        }

        noPagesMsg.style.display = 'none';
        swiperContainer.style.display = 'block';
        prevBtn.style.display = 'flex';
        nextBtn.style.display = 'flex';

        pages.forEach(url => {
            const slideDiv = document.createElement('div');
            slideDiv.className = 'swiper-slide';

            // ✅ مهم: نمرّر الصورة كخلفية blur للسلايد (تزيل الحواف السوداء)
            slideDiv.style.setProperty('--bg', `url("${url}")`);

            const zoomContainer = document.createElement('div');
            zoomContainer.className = 'swiper-zoom-container';

            const img = document.createElement('img');
            img.src = url;

            zoomContainer.appendChild(img);
            slideDiv.appendChild(zoomContainer);
            swiperWrapper.appendChild(slideDiv);
        });

        const isIOS = (/iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1));

        const swiperConfig = {
            effect: 'flip',
            grabCursor: true,
            loop: false,
            speed: 800,
            zoom: {
                maxRatio: 3,
                minRatio: 1,
                toggle: true,
            },
            on: {
                slideChange: function () {
                    const flipSound = document.getElementById('flip-sound');
                    if (flipSound) {
                        flipSound.currentTime = 0;
                        flipSound.play().catch(() => {});
                    }

                    if (hint && hint.style.display !== 'none') {
                        hint.style.transition = 'opacity 0.5s';
                        hint.style.opacity = '0';
                        setTimeout(() => hint.style.display = 'none', 500);
                    }
                }
            }
        };

        if (isIOS) {
            swiperConfig.creativeEffect = {
                prev: {
                    shadow: true,
                    translate: ['-20%', 0, -1],
                },
                next: {
                    translate: ['100%', 0, 0],
                },
            };
        } else {
            swiperConfig.flipEffect = {
                slideShadows: false,
            };
        }

        swiperInstance = new Swiper('.swiper', swiperConfig);
    }

    function backToCategories() {
        document.getElementById('book-section').style.display = 'none';
        document.getElementById('categories-section').style.display = 'flex';
    }

    function flipToNext() {
        if (swiperInstance) swiperInstance.slideNext();
    }

    function flipToPrev() {
        if (swiperInstance) swiperInstance.slidePrev();
    }
</script>
</body>
</html>