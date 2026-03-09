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
            width: 100vw;
            max-width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body {
            @if(isset($setting) && $setting->getFirstMediaUrl('background'))
            background: url("{{ $setting->getFirstMediaUrl('background') }}") center center / cover no-repeat fixed;
            @else
            background: linear-gradient(135deg, #000000 0%, #000000 100%);
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
            overflow-x: hidden;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            scrollbar-width: thin;
            scrollbar-color: #f59e0b #222;
            -webkit-overflow-scrolling: touch;
        }

        #categories-section::-webkit-scrollbar {
            width: 8px;
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
            max-width: 1200px;
            margin: auto;
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
            aspect-ratio: 1 / 1;
            height: auto;
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

        #book-section {
            width: 100%;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0;
            /* التعديلات هنا */
            background: rgba(0, 0, 0, 0.4); /* خلفية شبه شفافة بدلاً من الأسود القاتم */
            backdrop-filter: blur(8px); /* تأثير ضبابي احترافي */
            -webkit-backdrop-filter: blur(8px); /* لدعم أجهزة الآيفون وسفاري */
            z-index: 10;
            width: 100%;
            height: 100vh;
            height: 100dvh;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            background: rgba(0, 0, 0, 0.35); 
            backdrop-filter: blur(14px) saturate(120%); 
            -webkit-backdrop-filter: blur(14px) saturate(120%);
            z-index: 9999;
            overflow: hidden;
            box-sizing: border-box;
        }

        .back-btn {
            position: absolute;
            top: calc(20px + env(safe-area-inset-top, 0));
            right: calc(20px + env(safe-area-inset-right, 0));
            background: rgba(30,30,30, 0.8);
            color: #f59e0b;
            border: 1px solid #f59e0b;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1rem;
            font-family: inherit;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .back-btn:hover, .back-btn:active {
            background: #f59e0b;
            color: #000;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10000;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            transition: all 0.3s ease;
        }

        .nav-arrow:hover, .nav-arrow:active {
            background: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            transform: translateY(-50%) scale(1.05);
        }

        .nav-arrow svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s;
        }

        .right-arrow {
            right: calc(10px + env(safe-area-inset-right, 0));
        }

        .left-arrow {
            left: calc(10px + env(safe-area-inset-left, 0));
        }

        .swiper {
            width: 100%;
        }

        .swiper-slide {
            background-color: transparent;
            display: flex;
            justify-content: center;
            align-items: center; /* تم التعديل للمنتصف */
            overflow: hidden; /* تم التعديل لمنع السكرول نهائياً */
        }

        .swiper-zoom-container {
            width: 100%;
            height: 100%; /* تم التعديل ليكون 100% بدلاً من auto */
            display: flex;
            justify-content: center;
            align-items: center; /* تم التعديل للمنتصف */
        }

        .swiper-slide img {
            width: 100% !important; 
            height: 100% !important; /* تم التعديل لاحتواء الصورة في الشاشة */
            max-width: 100%;
            max-height: 100%; /* تم التعديل لمنع التمدد خارج الشاشة */
            object-fit: contain; /* يحافظ على أبعاد الصورة بدون قص أو سكرول */
            display: block;
            margin: auto;
            user-select: none;
            -webkit-user-drag: none;
            transition: transform 0.3s ease;
        }

        .swipe-hint {
            position: absolute;
            bottom: calc(40px + env(safe-area-inset-bottom, 0));
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 30px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
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
            margin: auto;
        }

        /* التصميم المتجاوب للكمبيوتر والآيباد */
        @media (min-width: 576px) {
            .categories-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            #categories-section {
                padding: 40px 20px;
            }
        }

        @media (min-width: 768px) {
            .categories-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 25px;
            }
            .nav-arrow {
                width: 50px;
                height: 50px;
                background: rgba(255, 255, 255, 0.15);
            }
            .nav-arrow svg {
                width: 28px;
                height: 28px;
            }
            .right-arrow {
                right: 40px;
            }
            .left-arrow {
                left: 40px;
            }
            .back-btn {
                top: 40px;
                right: 40px;
                font-size: 1.2rem;
                padding: 12px 25px;
            }
            .nav-arrow:hover, .nav-arrow:active {
                background: rgba(255, 255, 255, 0.3);
                transform: translateY(-50%) scale(1.1);
            }
            .hint-text-mobile {
                display: none;
            }
            .hint-text-desktop {
                display: inline;
            }
            
            /* أخذ الحجم الكامل للشاشة لضمان احتواء الصورة بالكامل وتجنب القص */
            .swiper {
                height: 100%;
            }
            .swiper-slide img {
                height: 100% !important;
                object-fit: contain !important;
            }
        }

        @media (min-width: 1024px) {
            .categories-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 30px;
            }
            .back-btn:hover {
                transform: scale(1.05);
            }
        }

        @media (min-width: 1400px) {
            .categories-grid {
                grid-template-columns: repeat(6, 1fr);
                max-width: 1400px;
            }
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
            <div class="swiper-wrapper" id="swiper-wrapper">
            </div>
        </div>

        <div class="swipe-hint" id="hint">
            <span class="hint-text-mobile">اسحب للتقليب</span>
            <span class="hint-text-desktop" style="display: none;">التقليب بالسحب أو الأسهم</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
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

        if(swiperInstance) {
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
                maxRatio: 3, // أقصى حد للتكبير
                minRatio: 1, // الحد الأدنى (الوضع الطبيعي)
                toggle: true, // تفعيل التكبير/التصغير بالنقر المزدوج (ممتاز للآيفون)
            },
            keyboard: {
                enabled: true, // تفعيل التنقل بلوحة المفاتيح للكمبيوتر
                onlyInViewport: false,
            },
            on: {
                slideChange: function () {
                    const flipSound = document.getElementById('flip-sound');
                    if(flipSound) {
                        flipSound.currentTime = 0;
                        flipSound.play().catch(error => console.log('المتصفح منع الصوت:', error));
                    }

                    if(hint && hint.style.display !== 'none') {
                        hint.style.transition = 'opacity 0.5s';
                        hint.style.opacity = '0';
                        setTimeout(() => hint.style.display = 'none', 500);
                    }
                }
            }
        };

        if (isIOS) {
            // تأثير بديل رائع يشبه تقليب الصفحات يعمل بشكل مثالي مع الـ Zoom في غوغل و سفاري
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
            // تأثير القلب الافتراضي للأجهزة الأخرى
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
        if (swiperInstance) {
            swiperInstance.slideNext();
        }
    }

    function flipToPrev() {
        if (swiperInstance) {
            swiperInstance.slidePrev();
        }
    }
</script>
</body>
</html>