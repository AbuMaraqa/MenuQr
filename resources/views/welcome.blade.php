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

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 10px;
        }

        .brand-header h1 {
            color: #f59e0b;
            margin: 0;
            font-size: 2.2rem;
            text-shadow: 0 3px 6px rgba(0,0,0,0.8);
        }

        .brand-header p {
            color: #ccc;
            margin: 5px 0 0;
            font-size: 1.1rem;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            width: 100%;
            padding-bottom: 50px;
        }

        .category-card {
            background: #1a1a1a;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
            border: 1px solid rgba(245, 158, 11, 0.1);
            display: flex;
            flex-direction: column;
            aspect-ratio: 1 / 1.1;
        }

        .category-card:active {
            transform: scale(0.95);
        }

        .category-img-wrapper {
            width: 100%;
            flex: 1;
            overflow: hidden;
            background: #000;
        }

        .category-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .category-card:hover .category-img {
            transform: scale(1.05);
        }

        .category-card:hover {
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.2);
        }

        .category-title {
            background: linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);
            color: #fff;
            padding: 15px 10px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            position: absolute;
            bottom: 0;
            width: 100%;
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
            padding: 15px;
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

        .flip-book-wrapper {
            opacity: 1;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 30px; /* لترك مساحة لزر العودة */
        }

        .page {
            background-color: #000000;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .page img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
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

    <!-- القسم الأول: شبكة الأقسام -->
    <div id="categories-section">
        <div class="brand-header">
            <h1>Snack Corner</h1>
            <p>اختر القسم لرؤية المنيو</p>
        </div>
        
        <div class="categories-grid">
            @foreach($categories as $category)
                <div class="category-card" onclick="openCategory({{ $category->id }})">
                    <div class="category-img-wrapper">
                        <!-- نستخدم الصورة الخاصة بالقسم التي تم رفعها في Filament -->
                        <img class="category-img" src="{{ $category->getFirstMediaUrl('thumb') ?: 'https://via.placeholder.com/300x300.png?text=بدون+صورة' }}" alt="{{ $category->name }}">
                    </div>
                    <div class="category-title">{{ $category->name }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- القسم الثاني: عارض الكتاب (يظهر عند الضغط على قسم) -->
    <div id="book-section">
        <button class="back-btn" onclick="backToCategories()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            العودة
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
            <span>اسحب للتقليب</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>
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

    let pageFlipInstance = null;

    function openCategory(categoryId) {
        const pages = categoryData[categoryId];
        
        const categoriesSection = document.getElementById('categories-section');
        const bookSection = document.getElementById('book-section');
        const bookWrapper = document.getElementById('book-wrapper');
        const noPagesMsg = document.getElementById('no-pages-msg');
        const hint = document.getElementById('hint');
        const bookDOM = document.getElementById('book');

        // إخفاء الأقسام وإظهار قسم الكتاب
        categoriesSection.style.display = 'none';
        bookSection.style.display = 'flex';
        hint.style.display = 'flex';
        hint.style.opacity = '1';

        // تفريغ الكتاب القديم
        if(pageFlipInstance) {
            pageFlipInstance.destroy();
            pageFlipInstance = null;
        }
        bookDOM.innerHTML = '';

        if (!pages || pages.length === 0) {
            // لا يوجد صفحات
            bookWrapper.style.display = 'none';
            hint.style.display = 'none';
            noPagesMsg.style.display = 'block';
            return;
        }

        // يوجد صفحات
        noPagesMsg.style.display = 'none';
        bookWrapper.style.display = 'flex';

        // إنشاء الصفحات كعناصر HTML
        pages.forEach(url => {
            const pageDiv = document.createElement('div');
            pageDiv.className = 'page';
            const img = document.createElement('img');
            img.src = url;
            pageDiv.appendChild(img);
            bookDOM.appendChild(pageDiv);
        });

        // تهيئة PageFlip من جديد
        pageFlipInstance = new St.PageFlip(bookDOM, {
            width: 320,
            height: 650,
            size: "stretch",
            minWidth: 300,
            maxWidth: 400,
            minHeight: 400,
            maxHeight: 850,
            showCover: false,
            mobileScrollSupport: true,
            usePortrait: true,
            maxShadowOpacity: 0.3,
            showPageCorners: true,
            swipeDistance: 30
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
        // إخفاء قسم الكتاب والعودة للأقسام
        document.getElementById('book-section').style.display = 'none';
        document.getElementById('categories-section').style.display = 'flex';
        
        // إيقاف الكتاب إذا كان يعمل
        if(pageFlipInstance) {
            pageFlipInstance.destroy();
            pageFlipInstance = null;
        }
    }
</script>
</body>
</html>
