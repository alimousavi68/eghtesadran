<!-- Single Sidebar (4 cols) -->
<aside class="lg:col-span-4 space-y-8 flex flex-col self-start sticky top-28 print:hidden">

	<?php if ( is_active_sidebar( 'sidebar-single' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-single' ); ?>
	<?php else : ?>
    <!-- Hot News (پربازدیدترین‌ها) -->
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
        <div class="mb-4 border-b border-slate-100 dark:border-slate-700 pb-3">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-primary"></i>
                پربازدیدترین‌ها
            </h3>
        </div>

        <div class="flex flex-col gap-4 relative z-10">
            <!-- Item 1 (Featured) -->
            <a href="#" class="group relative block rounded-2xl overflow-hidden shadow-sm">
                <img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/bours-18.jpg' ) ); ?>" alt="بورس" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent flex items-end p-5">
                    <div class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-lg flex items-center gap-1">
                        <i data-lucide="flame" class="w-3.5 h-3.5"></i> داغ
                    </div>
                    <h4 class="text-sm md:text-base font-black text-white leading-relaxed group-hover:text-primary transition-colors">
                        رکوردشکنی تاریخی شاخص کل بورس اوراق بهادار تهران
                    </h4>
                </div>
            </a>

            <!-- Item 2 -->
            <a href="#" class="group flex gap-4 items-center bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-colors">
                <div class="relative w-20 h-20 rounded-lg overflow-hidden shrink-0 shadow-sm">
                    <img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/apartoman.jpg' ) ); ?>" alt="مسکن" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                        جزئیات وام‌های مسکن روستایی با سود ۵ درصد اعلام شد
                    </h4>
                </div>
            </a>

            <!-- Item 3 -->
            <a href="#" class="group flex gap-4 items-center bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-colors">
                <div class="relative w-20 h-20 rounded-lg overflow-hidden shrink-0 shadow-sm">
                    <img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/gold-05.jpg' ) ); ?>" alt="طلا" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                        افت بی‌سابقه قیمت جهانی طلا در پی انتشار آمارهای اقتصادی آمریکا
                    </h4>
                </div>
            </a>

            <!-- Item 4 -->
            <a href="#" class="group flex gap-4 items-center bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-colors">
                <div class="relative w-20 h-20 rounded-lg overflow-hidden shrink-0 shadow-sm">
                    <img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/bank-168.jpg' ) ); ?>" alt="بانک" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                        تغییر در ساعات کاری بانک‌ها از ابتدای هفته آینده
                    </h4>
                </div>
            </a>
            
            <!-- Item 5 -->
            <a href="#" class="group flex gap-4 items-center bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-colors">
                <div class="relative w-20 h-20 rounded-lg overflow-hidden shrink-0 shadow-sm">
                    <img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/N82862417-72240196.jpg' ) ); ?>" alt="صنعت" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                        افتتاح خط تولید جدید ایران‌خودرو با حضور وزیر صمت
                    </h4>
                </div>
            </a>
        </div>
    </div>

    <!-- Latest News Feed (آخرین اخبار) -->
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-primary"></i>
                    آخرین اخبار
                </h3>
            </div>
            <a href="#" class="group flex text-[10px] font-bold text-slate-500 dark:text-slate-400 hover:text-primary transition-colors gap-0.5 items-center">
                مشاهده همه <i data-lucide="chevron-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5"></i>
            </a>
        </div>

        <div class="flex flex-col gap-3.5">
            <!-- Feed 1 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 flex items-center justify-center gap-1 shrink-0">
                    <i data-lucide="play-circle" class="w-3 h-3 text-red-500"></i> ۱۰:۴۵
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    افزایش صادرات خدمات فنی و مهندسی به کشورهای حوزه خلیج فارس
                </h4>
            </div>

            <!-- Feed 2 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-primary text-white font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0 animate-pulse">
                    فوری
                </div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    بیانیه جدید بانک مرکزی در خصوص ضوابط جدید تخصیص ارز کالاها
                </h4>
            </div>

            <!-- Feed 3 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    ۰۹:۳۰
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    افزایش عرضه ورق‌های فولادی در تالار معاملات بورس کالا
                </h4>
            </div>
            
            <!-- Feed 4 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    ۰۹:۰۰
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    امضای تفاهم‌نامه سه‌جانبه برای تامین مسکن کارگران شهرک‌های صنعتی
                </h4>
            </div>
            
            <!-- Feed 5 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    ۰۸:۴۵
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    ترخیص ۱۰۰ هزار تن روغن خام غذایی از گمرکات جنوبی کشور
                </h4>
            </div>
            
            <!-- Feed 6 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    ۰۸:۱۵
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    آغاز به کار نمایشگاه بین‌المللی تجهیزات معدنی
                </h4>
            </div>
            
            <!-- Feed 7 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    دیروز
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    ثبات نسبی قیمت خودروهای مونتاژی در بازار آزاد
                </h4>
            </div>
            
            <!-- Feed 8 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    دیروز
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    توافق مالی راهبردی ایران و روسیه برای تسهیل تراکنش‌ها
                </h4>
            </div>
            
            <!-- Feed 9 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    دیروز
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    افزایش بی‌سابقه ظرفیت پالایشگاهی روزانه گاز طبیعی
                </h4>
            </div>
            
            <!-- Feed 10 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    دیروز
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    رشد تحقق درآمدهای مالیاتی در سه‌ماهه اول
                </h4>
            </div>
            
            <!-- Feed 11 -->
            <div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
                <div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
                    ۲ روز پیش
                </div>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
                    ابلاغ آیین‌نامه اجرایی واردات خودروهای کارکرده
                </h4>
            </div>
        </div>
    </div>

    <!-- Market Dashboard Widget (پیشخوان بازار) -->
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
        <div class="mb-4 border-b border-slate-100 dark:border-slate-700 pb-3">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-5 h-5 text-primary"></i>
                پیشخوان بازار
            </h3>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <a href="#" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
                <i data-lucide="layers" class="w-[26px] h-[26px] text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors duration-300" stroke-width="1.5"></i>
                <span class="text-slate-700 dark:text-slate-200 text-[11px] sm:text-[11.5px] font-bold mt-5 text-center transition-colors duration-300 group-hover:text-primary">
                    فلزات اساسی
                </span>
            </a>

            <a href="#" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
                <i data-lucide="droplet" class="w-[26px] h-[26px] text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors duration-300" stroke-width="1.5"></i>
                <span class="text-slate-700 dark:text-slate-200 text-[11px] sm:text-[11.5px] font-bold mt-5 text-center transition-colors duration-300 group-hover:text-primary">
                    پتروشیمی
                </span>
            </a>

            <a href="#" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
                <i data-lucide="coins" class="w-[26px] h-[26px] text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors duration-300" stroke-width="1.5"></i>
                <span class="text-slate-700 dark:text-slate-200 text-[11px] sm:text-[11.5px] font-bold mt-5 text-center transition-colors duration-300 group-hover:text-primary">
                    ارز و طلا
                </span>
            </a>

            <a href="#" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
                <i data-lucide="line-chart" class="w-[26px] h-[26px] text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors duration-300" stroke-width="1.5"></i>
                <span class="text-slate-700 dark:text-slate-200 text-[11px] sm:text-[11.5px] font-bold mt-5 text-center transition-colors duration-300 group-hover:text-primary">
                    بورس و اوراق
                </span>
            </a>

            <a href="#" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
                <i data-lucide="car" class="w-[26px] h-[26px] text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors duration-300" stroke-width="1.5"></i>
                <span class="text-slate-700 dark:text-slate-200 text-[11px] sm:text-[11.5px] font-bold mt-5 text-center transition-colors duration-300 group-hover:text-primary">
                    خودرو
                </span>
            </a>

            <a href="#" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
                <i data-lucide="zap" class="w-[26px] h-[26px] text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors duration-300" stroke-width="1.5"></i>
                <span class="text-slate-700 dark:text-slate-200 text-[11px] sm:text-[11.5px] font-bold mt-5 text-center transition-colors duration-300 group-hover:text-primary">
                    انرژی
                </span>
            </a>
        </div>
    </div>

    <!-- Sidebar Ads Widget -->
    <div class="relative bg-slate-200 rounded-2xl overflow-hidden aspect-[16/11] flex items-center justify-center cursor-pointer group shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="absolute top-3 left-3 bg-black/55 backdrop-blur text-white text-[9px] px-2.5 py-1 rounded-md z-10 font-bold">
            تبلیغات
        </div>
        <img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/bours-26.jpg' ) ); ?>" alt="تبلیغات بورس" class="w-full h-full object-cover opacity-85 group-hover:scale-105 transition-all duration-700" />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent flex flex-col justify-end p-5">
            <span class="text-white font-extrabold text-base md:text-lg mb-1.5 leading-snug">
                صندوق‌های نوین سرمایه‌گذاری املاک
            </span>
            <span class="text-red-300 text-xs font-bold flex items-center gap-1">
                مدیریت ریسک هوشمند مسکن <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            </span>
        </div>
    </div>
    
    <!-- Banner Ad 2 -->
    <div class="relative bg-slate-200 rounded-2xl overflow-hidden aspect-[16/11] flex items-center justify-center cursor-pointer group shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="absolute top-3 left-3 bg-black/55 backdrop-blur text-white text-[9px] px-2.5 py-1 rounded-md z-10 font-bold">
            تبلیغات
        </div>
        <img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/bank-168.jpg' ) ); ?>" alt="تبلیغات بانک" class="w-full h-full object-cover opacity-85 group-hover:scale-105 transition-all duration-700" />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent flex flex-col justify-end p-5">
            <span class="text-white font-extrabold text-base md:text-lg mb-1.5 leading-snug">
                تسهیلات ویژه کارآفرینان جوان
            </span>
            <span class="text-red-300 text-xs font-bold flex items-center gap-1">
                همین حالا افتتاح حساب کنید <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            </span>
        </div>
    </div>
	<?php endif; ?>

</aside>
