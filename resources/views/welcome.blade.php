<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>منيو Snack Corner</title>

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

    <audio id="flip-sound" src="{{ asset('./assets/page-flip.mp3') }}" preload="auto"></audio>

    <style>
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: #000; /* تغيير الخلفية للأسود السادة لتوحيد المظهر */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            touch-action: none;
            font-family: 'Tajawal', sans-serif;
            color: #fff;
        }

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
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            padding-bottom: 50px;
        }

        .category-card {
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
            border: 1px solid rgba(245, 158, 11, 0.1);
            display: flex;
            flex-direction: column;
        }

        .category-card:active {
            transform: scale(0.95);
        }

        .category-img {
            width: 100%;
            height: auto;
            object-fit: contain;
            transition: transform 0.4s ease;
            display: block;
        }

        .category-card:hover .category-img {
            transform: scale(1.02);
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
            padding: 0; /* تم إزالة الـ padding لكي يملأ الكتاب الشاشة */
            background: #000;
            z-index: 10;
        }

        .back-btn {
            position: absolute;
            top: env(safe-area-inset-top, 20px); /* مراعاة النوتش في الهواتف الحديثة */
            right: 20px;
            background: rgba(0,0,0, 0.6); /* جعل الخلفية أغمق قليلاً للوضوح فوق الصورة */
            color: #f59e0b;
            border: 1px solid #f59e0b;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 1rem;
            font-family: inherit;
            font-weight: bold;
            cursor: pointer;
            z-index: 110; /* زيادة z-index لضمان الظهور فوق الأسهم */
            display: flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(5px);
            transition: all 0.2s ease;
        }

        /* تنسيق الأسهم لضمان ظهورها بوضوح فوق الصورة كاملة الصفحة */
        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0, 0.5); /* خلفية نصف شفافة */
            color: #f59e0b;
            border: none; /* إزالة الحدود لمظهر عصري */
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 100;
            backdrop-filter: blur(3px);
            transition: all 0.2s ease;
        }

        .right-arrow { right: 10px; }
        .left-arrow { left: 10px; }

        .flip-book-wrapper {
            opacity: 1;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0; /* إزالة الهامش العلوي */
        }

        /* تنسيق الصفحة لتكون كاملة وبدون حواف */
        .page {
            background-color: #000;
            border-radius: 0; /* إزالة انحناء الزوايا لكي تلتصق بالحافة */
            box-shadow: none; /* إزالة الظل الذي قد يظهر كإطار */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .page img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* التعديل الأهم: الصورة تملأ الإطار بالكامل (قد يتم قص الأطراف) */
            object-position: center; /* تمركز الصورة */
            background-color: transparent;
            pointer-events: none;
        }

        /* تلميح السحب */
        .swipe-hint {
            position: absolute;
            bottom: env(safe-area-inset-bottom, 30px); /* مراعاة منطقة السحب في الشاشات الكاملة */
            color: #fff;
            background: rgba(0,0,0,0.5);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            pointer-events: none;
            animation: slideLeft 2s infinite ease-in-out;
            z-index: 10;
        }

        @keyframes slideLeft {
            0%, 100% { transform: translateX(0); opacity: 0.8; }
            50% { transform: translateX(-15px); opacity: 1; }
        }

        .no-pages-msg {
            display: none;
            color: #ccc;
            font-size: 1.2rem;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>

<div class="app-container">

    <div id="categories-section">
        <div class="categories-grid">
            @foreach($categories as $category)
                <div class="category-card" onclick="openCategory({{ $category->id }})">
                    <img class="category-img" src="{{ $category->getFirstMediaUrl('thumb') ?: 'https://via.placeholder.com/400x600.png?text=بدون+صورة' }}" alt="صورة القسم">
                </div>
            @endforeach
        </div>
    </div>

    <div id="book-section">
        <button class="back-btn" onclick="backToCategories()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            العودة
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

        <div class="flip-book-wrapper" id="book-wrapper">
            <div id="book">
                </div>
        </div>

        <div class="swipe-hint" id="hint">
            <span>اسحب للتقليب</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>
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

    let pageFlipInstance = null;

    function openCategory(categoryId) {
        const pages = categoryData[categoryId];
        const categoriesSection = document.getElementById('categories-section');
        const bookSection = document.getElementById('book-section');
        const bookWrapper = document.getElementById('book-wrapper');
        const noPagesMsg = document.getElementById('no-pages-msg');
        const hint = document.getElementById('hint');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        categoriesSection.style.display = 'none';
        bookSection.style.display = 'flex';
        hint.style.display = 'flex';
        hint.style.opacity = '1';

        if(pageFlipInstance) {
            pageFlipInstance.destroy();
            pageFlipInstance = null;
        }

        let oldBook = document.getElementById('book');
        if(oldBook) oldBook.remove();

        const bookDOM = document.createElement('div');
        bookDOM.id = 'book';
        bookWrapper.appendChild(bookDOM);

        if (!pages || pages.length === 0) {
            bookWrapper.style.display = 'none';
            hint.style.display = 'none';
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            noPagesMsg.style.display = 'block';
            return;
        }

        noPagesMsg.style.display = 'none';
        bookWrapper.style.display = 'flex';
        prevBtn.style.display = 'flex';
        nextBtn.style.display = 'flex';

        pages.forEach(url => {
            const pageDiv = document.createElement('div');
            pageDiv.className = 'page';
            const img = document.createElement('img');
            img.src = url;
            // إضافة خاصية لمنع التحميل الكسول الافتراضي لضمان سرعة الظهور عند التقليب
            img.loading = "eager"; 
            pageDiv.appendChild(img);
            bookDOM.appendChild(pageDiv);
        });

        // جلب أبعاد الحاوية لضبط الكتاب عليها تماماً
        const containerRect = bookWrapper.getBoundingClientRect();

        pageFlipInstance = new St.PageFlip(bookDOM, {
            // استخدام أبعاد الحاوية الحقيقية
            width: containerRect.width, 
            height: containerRect.height,
            size: "stretch", // تمدد ليملأ الحاوية
            showCover: false,
            mobileScrollSupport: true,
            usePortrait: true,
            maxShadowOpacity: 0.2, // تقليل الظل قليلاً لمظهر أنظف على الصور الكاملة
            showPageCorners: true,
            swipeDistance: 15,
            flippingTime: 800 // تسريع التقليب قليلاً
        });

        pageFlipInstance.loadFromHTML(document.querySelectorAll('#book .page'));

        pageFlipInstance.on('flip', (e) => {
            const flipSound = document.getElementById('flip-sound');
            if(flipSound) {
                flipSound.currentTime = 0;
                flipSound.play().catch(error => console.log('Audio blocked:', error));
            }

            if(hint && hint.style.display !== 'none') {
                hint.style.transition = 'opacity 0.5s';
                hint.style.opacity = '0';
                setTimeout(() => hint.style.display = 'none', 500);
            }
        });
    }

    function backToCategories() {
        document.getElementById('book-section').style.display = 'none';
        document.getElementById('categories-section').style.display = 'flex';
    }

    function flipToNext() { if (pageFlipInstance) pageFlipInstance.flipNext(); }
    function flipToPrev() { if (pageFlipInstance) pageFlipInstance.flipPrev(); }

    // إعادة تهيئة الكتاب عند تغيير حجم الشاشة (مثلاً تدوير الهاتف) لضمان بقائه كامل الشاشة
    window.addEventListener('resize', () => {
        if (pageFlipInstance && document.getElementById('book-section').style.display === 'flex') {
            const wrapper = document.getElementById('book-wrapper');
            const rect = wrapper.getBoundingClientRect();
            pageFlipInstance.updateFromHtml(document.querySelectorAll('#book .page'));
            // قد تحتاج مكتبة PageFlip إلى إعادة تحميل أو تحديث الأبعاد هنا إذا لم تتجاوب تلقائياً مع stretch
        }
    });
</script>
</body>
</html>