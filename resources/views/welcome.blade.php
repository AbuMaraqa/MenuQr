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
        }

        /* منع السحب والإفلات الأفتراضي وعمليات التمرير للمتصفح على الهاتف لكي لا يتعارض مع الكتاب */
        body {
            background: linear-gradient(135deg, #000000 0%, #000000 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            touch-action: none; /* مهم جداً للهاتف لمنع سحب المتصفح بدلاً من الكتاب */
            font-family: 'Tajawal', sans-serif;
            color: #fff;
        }

        /* حاوية بحجم شاشة الهاتف لإجبار عرض صفحة واحدة */
        .app-container {
            width: 100%;
            max-width: 450px;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ----- واجهة الأقسام ----- */
        #categories-section {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; /* هذه الخاصية لمركزه العناصر في منتصف الشاشة عمودياً */
            /* Scrollbar styling */
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
            grid-template-columns: repeat(2, 1fr); /* عرض في عمودين */
            gap: 15px;
            width: 100%;
            padding: 10px;
            padding-bottom: 50px;
            align-items: stretch; /* لمساواة الارتفاع بين الكروت */
        }

        .category-card {
            background: #111;
            border-radius: 12px; /* انحناء احترافي */
            overflow: hidden;
            position: relative;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
            border: 1px solid rgba(245, 158, 11, 0.2);
            /* تمت إزالة aspect-ratio للسماح للصورة بأخذ ارتفاعها الكامل الطبيعي */
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
            object-fit: cover; /* لملء البطاقة بشكل كامل ومرتب بدون تشويه */
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
            transform: scale(1.05); /* تكبير سلس عند التمرير */
        }

        .category-card:hover {
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.2);
        }

        /* ----- واجهة الكتاب ----- */
        #book-section {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0; /* إزالة الـ padding لكي تلامس الصورة الحواف */
            background: #000;
            z-index: 10;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(30,30,30, 0.8);
            color: #f59e0b;
            border: 1px solid #f59e0b;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 1rem;
            font-family: inherit;
            font-weight: bold;
            cursor: pointer;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(5px);
            transition: all 0.2s ease;
        }

        .back-btn:hover, .back-btn:active {
            background: #f59e0b;
            color: #000;
        }

        /* ----- أزرار التقليب (الأسهم) ----- */
        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(30,30,30, 0.8);
            color: #f59e0b;
            border: 1px solid #f59e0b;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 100;
            backdrop-filter: blur(5px);
            transition: all 0.2s ease;
        }

        .nav-arrow:hover, .nav-arrow:active {
            background: #f59e0b;
            color: #000;
        }

        .nav-arrow svg {
            width: 24px;
            height: 24px;
        }

        .right-arrow {
            right: 15px;
        }

        .left-arrow {
            left: 15px;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            background-color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            background-color: transparent;
            pointer-events: none;
        }

        /* تلميح السحب */
        .swipe-hint {
            position: absolute;
            bottom: 30px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            pointer-events: none;
            animation: slideLeft 2s infinite ease-in-out;
            z-index: 10;
        }

        @keyframes slideLeft {
            0%, 100% { transform: translateX(0); opacity: 0.5; }
            50% { transform: translateX(-15px); opacity: 1; }
        }

        /* رسالة عدم وجود صفحات */
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
                    <!-- <div class="category-overlay">
                        <h3 class="category-title">{{ $category->name }}</h3>
                    </div> -->
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

        <button class="nav-arrow right-arrow" onclick="flipToPrev()" id="prev-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </button>

        <button class="nav-arrow left-arrow" onclick="flipToNext()" id="next-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </button>

        <div class="no-pages-msg" id="no-pages-msg">
            عذراً، لا يوجد صفحات مضافة لهذا القسم حالياً.
        </div>

        <div class="swiper" id="swiper-container-wrapper" style="display: none;">
            <div class="swiper-wrapper" id="swiper-wrapper">
                <!-- Swiper slides go here -->
            </div>
        </div>

        <div class="swipe-hint" id="hint">
            <span>اسحب للتقليب</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // تخزين بيانات صفحات كل قسم في كائن JS
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

        // إخفاء الأقسام وإظهار قسم الكتاب
        categoriesSection.style.display = 'none';
        bookSection.style.display = 'flex';
        hint.style.display = 'flex';
        hint.style.opacity = '1';

        // تفريغ الكتاب القديم وإعادة بناء العنصر
        if(swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
        }

        swiperWrapper.innerHTML = '';

        if (!pages || pages.length === 0) {
            // لا يوجد صفحات
            swiperContainer.style.display = 'none';
            hint.style.display = 'none';
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            noPagesMsg.style.display = 'block';
            return;
        }

        // يوجد صفحات
        noPagesMsg.style.display = 'none';
        swiperContainer.style.display = 'block';
        prevBtn.style.display = 'flex';
        nextBtn.style.display = 'flex';

        // إنشاء الصفحات كعناصر HTML
        pages.forEach(url => {
            const slideDiv = document.createElement('div');
            slideDiv.className = 'swiper-slide';
            const img = document.createElement('img');
            img.src = url;
            slideDiv.appendChild(img);
            swiperWrapper.appendChild(slideDiv);
        });

        // تهيئة Swiper
        swiperInstance = new Swiper('.swiper', {
            effect: 'flip', // تأثير التقليب للصورة كاملة بدون دراغ زوايا
            grabCursor: true,
            loop: false,
            speed: 800,
            flipEffect: {
                slideShadows: true,
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
        });
    }

    function backToCategories() {
        // إخفاء قسم الكتاب والعودة للأقسام
        document.getElementById('book-section').style.display = 'none';
        document.getElementById('categories-section').style.display = 'flex';
    }

    // دوال التقليب بواسطة الأسهم
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