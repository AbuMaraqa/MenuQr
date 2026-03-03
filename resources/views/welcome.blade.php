<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>منيو Snack Corner</title>

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

    <audio id="flip-sound" src="{{ asset('./assets/page-flip.mp3') }}" preload="auto"></audio>

    <style>
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: #000000;
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

        /* حاوية الأقسام بحجم الشاشة */
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

        /* ----- واجهة الكتاب الشاشة الكاملة (Full Screen) ----- */
        #book-section {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: none;
            background: #000;
            z-index: 1000;
            overflow: hidden;
        }

        /* زر العودة فوق الكتاب */
        .back-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.6);
            color: #f59e0b;
            border: 1px solid #f59e0b;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 1rem;
            font-family: inherit;
            font-weight: bold;
            cursor: pointer;
            z-index: 9999;
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
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* إزالة الانحناءات والهوامش لتفادي الخطوط السوداء */
        .page {
            background-color: #000000;
            border-radius: 0;
            box-shadow: none;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        /* جعل الصورة تأخذ كامل مساحة الشاشة بالكامل */
        .page img {
            width: 100vw;
            height: 100vh;
            object-fit: fill; /* تمدد لملء الشاشة بدون أي مساحة سوداء */
            background-color: transparent;
            pointer-events: none;
        }

        /* تلميح السحب */
        .swipe-hint {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: bold;
            background: rgba(0, 0, 0, 0.5);
            padding: 5px 15px;
            border-radius: 20px;
            display: none; /* يتم إظهاره عبر الجافاسكربت */
            align-items: center;
            gap: 8px;
            pointer-events: none;
            animation: pulseHint 2s infinite ease-in-out;
            z-index: 9999;
        }

        @keyframes pulseHint {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
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
            z-index: 9999;
            transition: all 0.2s;
        }

        .nav-btn:hover, .nav-btn:active {
            background: rgba(245, 158, 11, 0.8);
            color: #000;
            border-color: #f59e0b;
        }

        .next-btn {
            left: 10px; /* سهم التالي على اليسار */
        }

        .prev-btn {
            right: 10px; /* سهم السابق على اليمين */
        }

        /* رسالة عدم وجود صفحات */
        .no-pages-msg {
            display: none;
            color: #ccc;
            font-size: 1.2rem;
            text-align: center;
            margin-top: 50vh;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="app-container" id="app-container">
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
</div>

<!-- القسم الثاني: عارض الكتاب الشاشة الكاملة -->
<div id="book-section">
    <button class="back-btn" onclick="backToCategories()">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 18l6-6-6-6"/>
        </svg>
        العودة
    </button>

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
            <!-- سيتم حقن الصفحات هنا -->
        </div>
    </div>

    <div class="swipe-hint" id="hint">
        <span>اسحب للتقليب</span>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>
<script>
    // تخزين بيانات صفحات كل قسم
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
        
        const appContainer = document.getElementById('app-container');
        const bookSection = document.getElementById('book-section');
        const bookWrapper = document.getElementById('book-wrapper');
        const noPagesMsg = document.getElementById('no-pages-msg');
        const hint = document.getElementById('hint');
        
        // إخفاء حاوية الأقسام بشكل كامل لإتاحة المجال للشاشة الكاملة للكتاب
        appContainer.style.display = 'none';
        bookSection.style.display = 'block';
        hint.style.display = 'flex';
        hint.style.opacity = '1';

        // تفريغ الكتاب القديم وإعادة البناء
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
            document.querySelectorAll('.nav-btn').forEach(b => b.style.display = 'none');
            noPagesMsg.style.display = 'block';
            return;
        }

        noPagesMsg.style.display = 'none';
        bookWrapper.style.display = 'flex';
        document.querySelectorAll('.nav-btn').forEach(b => b.style.display = 'flex');

        // إنشاء الصفحات
        pages.forEach(url => {
            const pageDiv = document.createElement('div');
            pageDiv.className = 'page';
            const img = document.createElement('img');
            img.src = url;
            pageDiv.appendChild(img);
            bookDOM.appendChild(pageDiv);
        });

        // قياس العرض والطول الحقيقي لمتصفح الهاتف الداخلي بالكامل
        const screenWidth = window.innerWidth;
        const screenHeight = window.innerHeight;

        // تهيئة المنيو حتى يعرض بعرض وطول الشاشة 100%
        pageFlipInstance = new St.PageFlip(bookDOM, {
            width: screenWidth,
            height: screenHeight,
            size: "stretch",    
            touch: true,
            minWidth: 200,
            maxWidth: 2000,
            minHeight: 300,
            maxHeight: 2500,
            showCover: false,
            mobileScrollSupport: true,
            usePortrait: true,
            maxShadowOpacity: 0.3,
            showPageCorners: true,
            swipeDistance: 10,
            flippingTime: 800
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
        // العودة للأقسام
        document.getElementById('book-section').style.display = 'none';
        document.getElementById('app-container').style.display = 'flex';
        
        // إخفاء الصوت أو التلميحات
        const hint = document.getElementById('hint');
        if(hint) hint.style.display = 'none';
    }
</script>
</body>
</html>
