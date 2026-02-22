<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>منيو Snack Corner</title>

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

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
        }

        /* حاوية بحجم شاشة الهاتف لإجبار عرض صفحة واحدة */
        .app-container {
            width: 100%;
            max-width: 450px; /* هذا العرض يضمن بقاء العرض كصفحة واحدة دائماً */
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        /* شاشة التحميل */
        #loader {
            position: absolute;
            color: #fff;
            font-size: 1.2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            z-index: 50;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255,255,255,0.1);
            border-top: 4px solid #f59e0b; /* لون مناسب للوجبات السريعة */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* إخفاء الكتاب حتى يكتمل التحميل */
        .flip-book-wrapper {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            width: 100%;
            height: 100%;
        }

        .page {
            background-color: #ffffff;
            /* إضافة حواف ناعمة وظل واقعي للورقة */
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            overflow: hidden;
        }

        .page img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* يضمن ظهور الصورة كاملة بدون قص */
            background-color: #fff;
            pointer-events: none; /* لمنع ظهور قائمة حفظ الصورة عند اللمس */
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
    </style>
</head>
<body>

<div class="app-container">

    <div id="loader">
        <div class="spinner"></div>
        <span>جاري تجهيز المنيو...</span>
    </div>

    <div class="flip-book-wrapper" id="book-wrapper">
        <div id="book">
            @foreach($pages as $page)
                <div class="page">
                    <img src="{{ $page->getFirstMediaUrl('image') }}" alt="صفحة المنيو">
                </div>
            @endforeach
        </div>
    </div>

    <div class="swipe-hint" id="hint">
        <span>اسحب للتقليب</span>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // إعدادات المكتبة لإجبار صفحة واحدة دائماً
        const pageFlip = new St.PageFlip(document.getElementById('book'), {
            width: 400, // عرض الصفحة الافتراضي
            height: 650, // طول الصفحة الافتراضي
            size: "stretch", // التمدد ليملأ الحاوية
            minWidth: 300,
            maxWidth: 450, // الحد الأقصى يمنع ظهور صفحتين
            minHeight: 400,
            maxHeight: 850,
            showCover: false, // تعطيل الغلاف المزدوج للحفاظ على نظام الصفحة الواحدة
            mobileScrollSupport: true,
            usePortrait: true, // إجبار الوضع الطولي (صفحة واحدة)
            maxShadowOpacity: 0.3, // ظل خفيف وواقعي
            showPageCorners: true, // إظهار طية الورق في الزاوية
            swipeDistance: 30 // حساسية السحب
        });

        // تحميل الصفحات
        const pages = document.querySelectorAll('.page');
        pageFlip.loadFromHTML(pages);

        // بعد اكتمال التحميل، نظهر المنيو ونخفي اللودر
        pageFlip.on('init', () => {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('book-wrapper').style.opacity = '1';
        });

        // إخفاء تلميح "اسحب للتقليب" بمجرد أن يقوم الزبون بأول تقليبة
        pageFlip.on('flip', (e) => {
            const hint = document.getElementById('hint');
            if(hint) {
                hint.style.transition = 'opacity 0.5s';
                hint.style.opacity = '0';
                setTimeout(() => hint.remove(), 500);
            }
        });
    });
</script>
</body>
</html>
