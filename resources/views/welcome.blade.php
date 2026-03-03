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
            background: linear-gradient(135deg, #000000 0%, #000000 100%);
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
            background: #000;
            z-index: 10;
        }

        .back-btn-container {
            width: 100%;
            padding: 15px;
            display: flex;
            justify-content: flex-end;
            z-index: 100;
            position: absolute; /* فوق الكتاب مباشرة */
            top: 0;
            left: 0;
        }

        .back-btn {
            background: rgba(30,30,30, 0.8);
            color: #f59e0b;
            border: 1px solid #f59e0b;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 1rem;
            font-family: inherit;
            font-weight: bold;
            cursor: pointer;
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

        .flip-book-wrapper {
            flex: 1;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            position: relative;
        }

        /* إزالة الهوامش والحواف لملء الصفحة */
        .page {
            background-color: #000000;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .page img {
            width: 100%;
            height: 100%;
            /* خاصية الإمتداد لكامل الشاشة */
            object-fit: fill; 
            background-color: transparent;
            pointer-events: none;
        }

        /* تلميح السحب */
        .swipe-hint {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            pointer-events: none;
            z-index: 10;
            width: 100%;
        }

        .swipe-hint span {
            animation: slideLeft 2s infinite ease-in-out;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @keyframes slideLeft {
            0%, 100% { transform: translateX(0); opacity: 0.5; }
            50% { transform: translateX(-15px); opacity: 1; }
        }

        /* أزرار الأسهم لتقليب الصفحة */
        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(245, 158, 11, 0.5);
            color: #f59e0b;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 50;
            transition: all 0.2s;
        }

        .nav-btn:hover, .nav-btn:active {
            background: rgba(245, 158, 11, 0.8);
            color: #000;
            border-color: #f59e0b;
        }

        .next-btn {
            left: 10px; /* سهم التالي يسار الشاشة للي بيقرأ بالعربي من اليمين لليسار */
        }

        .prev-btn {
            right: 10px; /* سهم السابق يمين الشاشة */
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

    <!-- القسم الأول: قائمة الأقسام -->
    <div id="categories-section">
        <div class="categories-grid">
            @foreach($categories as $category)
                <div class="category-card" onclick="openCategory({{ $category->id }})">
                    <img class="category-img" src="{{ $category->getFirstMediaUrl('thumb') ?: 'https://via.placeholder.com/400x600.png?text=بدون+صورة' }}" alt="صورة القسم">
                </div>
            @endforeach
        </div>
    </div>

    <!-- القسم الثاني: عارض الكتاب (يظهر عند الضغط على قسم) -->
    <div id="book-section">
        
        <div class="back-btn-container">
            <button class="back-btn" onclick="backToCategories()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
                العودة
            </button>
        </div>

        <!-- أزرار تقليب الكتاب -->
        <button class="nav-btn prev-btn" onclick="if(pageFlipInstance) pageFlipInstance.flipPrev()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </button>
        
        <button class="nav-btn next-btn" onclick="if(pageFlipInstance) pageFlipInstance.flipNext()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>

        <div class="no-pages-msg" id="no-pages-msg">
            عذراً، لا يوجد صفحات مضافة لهذا القسم حالياً.
        </div>

        <div class="flip-book-wrapper" id="book-wrapper">
            <div id="book">
                <!-- سيتم حقن الصفحات هنا بواسطة الجافاسكربت -->
            </div>
        </div>

        <div class="swipe-hint" id="hint">
            <span>
                اسحب للتقليب
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            </span>
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
        
        categoriesSection.style.display = 'none';
        bookSection.style.display = 'flex';
        hint.style.display = 'flex';
        hint.style.opacity = '1';

        if(pageFlipInstance) {
            pageFlipInstance.destroy();
            pageFlipInstance = null;
        }

        let oldBook = document.getElementById('book');
        if(oldBook) {
            oldBook.remove();
        }

        const bookDOM = document.createElement('div');
        bookDOM.id = 'book';
        bookWrapper.appendChild(bookDOM);

        if (!pages || pages.length === 0) {
            bookWrapper.style.display = 'none';
            hint.style.display = 'none';
            noPagesMsg.style.display = 'block';
            return;
        }

        noPagesMsg.style.display = 'none';
        bookWrapper.style.display = 'flex';

        pages.forEach(url => {
            const pageDiv = document.createElement('div');
            pageDiv.className = 'page';
            const img = document.createElement('img');
            img.src = url;
            pageDiv.appendChild(img);
            bookDOM.appendChild(pageDiv);
        });

        // نعيد استخدام stretch مع حساب الحاوية حتى يغطي العرض والارتفاع الكامل ويمط الصورة لتصل الحواف
        const wrapWidth = bookWrapper.clientWidth;
        const wrapHeight = bookWrapper.clientHeight;

        pageFlipInstance = new St.PageFlip(bookDOM, {
            width: wrapWidth,
            height: wrapHeight,
            size: "stretch", // يضمن التمدد الكامل مع استرجاع الانيميشن الطبيعي
            minWidth: 200,
            maxWidth: 1000,
            minHeight: 300,
            maxHeight: 1500,
            showCover: false,
            mobileScrollSupport: true,
            usePortrait: true, 
            maxShadowOpacity: 0.3,
            showPageCorners: true,
            swipeDistance: 10,
            flippingTime: 800 // سرعة الانيميشن
        });

        pageFlipInstance.loadFromHTML(document.querySelectorAll('#book .page'));

        pageFlipInstance.on('flip', (e) => {
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
        });
    }

    function backToCategories() {
        document.getElementById('book-section').style.display = 'none';
        document.getElementById('categories-section').style.display = 'flex';
    }
</script>
</body>
</html>
