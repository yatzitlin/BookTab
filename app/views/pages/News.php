<?php
$latestNews = $latestNews ?? [];
$trendingNews = $trendingNews ?? [];
$categoriesWithNews = $categoriesWithNews ?? [];
$newsByCategory = $newsByCategory ?? [];
$selectedCategory = $selectedCategory ?? null;

if (!function_exists('booktab_news_thumbnail_url')) {
    function booktab_news_thumbnail_url($thumbnailUrl) {
        $thumbnailUrl = trim((string) $thumbnailUrl);

        if ($thumbnailUrl === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $thumbnailUrl)) {
            return $thumbnailUrl;
        }

        $thumbnailUrl = str_replace('\\', '/', $thumbnailUrl);
        $thumbnailUrl = ltrim($thumbnailUrl, '/');

        if (strpos($thumbnailUrl, 'public/') === 0) {
            return BASE_URL . '/' . $thumbnailUrl;
        }

        return BASE_URL . '/public/' . $thumbnailUrl;
    }
}
?>

<style>    
    .news-card {
        background-color: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        transition: box-shadow 0.3s ease, transform 1s ease;
    }

    /* Hiệu ứng hover card bài viết */
    .wave-glass {
        position: relative;
        overflow: hidden;
    }

    /* Định dạng chung cho 4 mảnh kính */
    .wave-glass .glass-piece {
        position: absolute;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        width: 0;
        height: 0;
        opacity: 1;
        z-index: 20;
        pointer-events: none;
    }

    /* Đặt 4 mảnh vào 4 góc */
    .wave-glass .glass-piece.top-left { top: 0; left: 0}
    .wave-glass .glass-piece.top-right { top: 0; right: 0}
    .wave-glass .glass-piece.bottom-left { bottom: 0; left: 0}
    .wave-glass .glass-piece.bottom-right { bottom: 0; right: 0}

    .wave-glass:hover .glass-piece {
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: all 1.3s;
    }
</style>

<!-- ===================================================================================== -->
<!-- Section chứa danh sách các danh mục + ô tìm kiếm -->
<!-- ===================================================================================== -->
<div id="discoverBar" class="mb-8 bg-white/80 backdrop-blur-md border-b border-gray-200 relative z-50">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-10 gap-6 items-start">
            
            <div class="lg:col-span-7">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="font-semibold text-gray-700 whitespace-nowrap mr-2">Khám phá:</div>

                    <?php if (!empty($categoriesWithNews)): ?>
                        <?php foreach ($categoriesWithNews as $cat): ?>
                            <a href="#<?php echo htmlspecialchars($cat['slug'] ?? ('category-' . $cat['ma_loai']), ENT_QUOTES, 'UTF-8'); ?>" 
                                class="px-4 py-1.5 rounded-lg flex items-center gap-2 hover:bg-gray-100 transition-colors group cursor-pointer border border-gray-200 bg-white"
                            >
                                <span class="font-medium text-sm text-gray-700">
                                    <?php echo htmlspecialchars($cat['ten_loai']); ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-3 w-full">
                <form id="newsSearchForm" action="<?php echo BASE_URL; ?>/public/index.php" method="get" class="relative">
                    <input type="hidden" name="page" value="news_list">
                    <input type="hidden" name="type" value="search">
                    <div class="p-1 rounded-xl flex items-center border border-gray-200 bg-gray-50 focus-within:bg-white focus-within:border-gray-400 focus-within:shadow-sm transition-all">
                        <input 
                            id="newsSearchInput" 
                            name="keyword" 
                            type="text" 
                            placeholder="Tìm kiếm bài viết..." 
                            autocomplete="off" 
                            class="w-full bg-transparent px-4 py-2 outline-none text-sm text-gray-700 placeholder-gray-400"
                        >
                        <button type="submit" class="p-2 bg-gray-800 text-white rounded-lg hover:bg-black transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <div 
                        id="newsSearchDropdown" 
                        class="absolute left-0 right-0 mt-2 hidden rounded-2xl border border-gray-200 bg-white shadow-xl overflow-hidden z-50">
                    </div>
                </form>                
            </div>
        </div>
    </section>
</div>

<!-- ===================================================================================== -->
<!-- Bài viết mới nhất -->
<!-- ===================================================================================== -->
<section class="mb-12 max-w-7xl mx-auto px-4 sm:px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <span class="w-1.5 h-6 bg-gray-800 rounded-full"></span> Bài viết mới nhất
    </h2>
    
    <div class="relative w-full lg:w-[95%] mx-auto h-[450px] rounded-2xl overflow-hidden group shadow-lg news-card">
        
        <button 
            id="btnPrev" 
            class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 flex items-center justify-center bg-white/30 backdrop-blur hover:bg-white/70 text-white hover:text-gray-900 rounded-full transition-all duration-300 opacity-0 group-hover:opacity-100 shadow-md"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button 
            id="btnNext" 
            class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 flex items-center justify-center bg-white/30 backdrop-blur hover:bg-white/70 text-white hover:text-gray-900 rounded-full transition-all duration-300 opacity-0 group-hover:opacity-100 shadow-md"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <div id="sliderContainer" class="relative w-full h-full">
            <?php if (!empty($latestNews)): ?>
                <?php foreach ($latestNews as $index => $news): ?>
                    <?php
                        $slug = urlencode($news['slug'] ?? '');
                        $detailUrl = BASE_URL . "/public/bai-viet/{$slug}";
                        $jsAction = "window.location='" . $detailUrl . "'";
                    ?>
                    <article class="slide absolute inset-0 transition-opacity duration-700 ease-in-out 
                        <?php echo $index === 0 ? 'opacity-100 z-20' : 'opacity-0 z-10'; ?> 
                        cursor-pointer"
                        onclick="<?php echo htmlspecialchars($jsAction, ENT_QUOTES, 'UTF-8'); ?>"
                    >  
                        <div class="absolute inset-0 bg-gray-800"></div>
                        <?php if (!empty($news['thumbnail_url'])): ?>
                            <img 
                                src="<?php echo htmlspecialchars(booktab_news_thumbnail_url($news['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>" 
                                alt="<?php echo htmlspecialchars($news['tieu_de']); ?>" class="absolute inset-0 w-full h-full object-cover opacity-70"
                            >
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 w-full p-8 md:p-12 text-white">
                            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur text-xs font-semibold rounded-md mb-4 border border-white/30">
                                <?php echo htmlspecialchars($news['ten_loai'] ?? 'Tin tức'); ?>
                            </span>

                            <h3 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
                                <?php echo htmlspecialchars($news['tieu_de']); ?>
                            </h3>

                            <p class="text-gray-300 text-sm md:text-base line-clamp-2 mb-4 w-3/4">
                                <?php echo htmlspecialchars($news['tom_tat'] ?? ''); ?>
                            </p>

                            <div class="text-sm text-gray-300 flex items-center gap-3 font-medium">
                                <span>
                                    <?php echo htmlspecialchars($news['ho_ten'] ?? 'BookTab'); ?>
                                </span>
                                <span>
                                    •
                                </span>
                                <span>
                                    <?php echo date('d/m/Y', strtotime($news['ngay_dang'] ?? 'now')); ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <article class="slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-100 z-20">
                    <div class="absolute inset-0 bg-gray-700"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 w-full p-8 md:p-12 text-white">
                        <h3 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
                            Chưa có bài viết nổi bật
                        </h3>
                        <p class="text-gray-300 text-sm md:text-base">
                            Hãy đăng bài viết từ trang admin để hiển thị dữ liệu.
                        </p>
                    </div>
                </article>
            <?php endif; ?>
        </div>

        <div id="sliderDots" class="absolute bottom-6 right-8 z-30 flex gap-2"></div>
    </div>
</section>

<!-- ===================================================================================== -->
<!-- Bài viết nổi bật -->
<!-- ===================================================================================== -->
<section class="mb-14 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-1.5 h-5 bg-red-500 rounded-full"></span> Bài viết nổi bật
        </h2>
    </div>
    
    <div class="flex overflow-x-auto hide-scroll snap-x gap-6 pb-4 lg:grid lg:grid-cols-5 lg:overflow-visible">
        <?php if (!empty($trendingNews)): ?>
            <?php foreach ($trendingNews as $index => $news): ?>
                <?php
                    $slug = urlencode($news['slug'] ?? '');
                    $detailUrl = BASE_URL . "/public/bai-viet/{$slug}";
                    $jsAction = "window.location='" . $detailUrl . "'";
                ?>
                <article 
                    class="snap-start shrink-0 w-[240px] lg:w-auto cursor-pointer group wave-glass news-card p-3" 
                    onclick="<?php echo htmlspecialchars($jsAction, ENT_QUOTES, 'UTF-8'); ?>"
                >
                    <div class="glass-piece top-left"></div>
                    <div class="glass-piece top-right"></div>
                    <div class="glass-piece bottom-left"></div>
                    <div class="glass-piece bottom-right"></div>

                    <div class="relative h-36 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                        <?php if (!empty($news['thumbnail_url'])): ?>
                            <img 
                                src="
                                    <?php echo htmlspecialchars(booktab_news_thumbnail_url($news['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>
                                " 
                                alt="<?php echo htmlspecialchars($news['tieu_de']); ?>" 
                                class="w-full h-full object-cover"
                            >
                        <?php endif; ?>
                        <div 
                            class="absolute top-2 left-2 bg-white/90 backdrop-blur text-gray-900 font-bold text-sm px-2.5 py-0.5 rounded-md shadow-sm z-10">
                            <?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?>
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-800 line-clamp-3 text-sm leading-relaxed">
                        <?php echo htmlspecialchars($news['tieu_de']); ?>
                    </h3>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-gray-500 text-sm">Chưa có dữ liệu bài viết đọc nhiều.</div>
        <?php endif; ?>
    </div>
</section>


<!-- ===================================================================================== -->
<!-- Section của từng danh mục -->
<!-- ===================================================================================== -->
<?php if ($selectedCategory !== null): ?>
<section class="mb-14 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-1.5 h-5 bg-blue-500 rounded-full"></span> 
            <?php echo htmlspecialchars($selectedCategory['ten_loai']); ?>
        </h2>
    </div>

    <?php if (!empty($newsByCategory)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($newsByCategory as $news): ?>
                <?php
                    $slug = urlencode($news['slug'] ?? '');
                    $detailUrl = BASE_URL . "/public/bai-viet/{$slug}";
                    $jsAction = "window.location='" . $detailUrl . "'";
                ?>
                <article 
                    class="cursor-pointer news-card p-4" 
                    onclick="<?php echo htmlspecialchars($jsAction, ENT_QUOTES, 'UTF-8'); ?>"
                >
                    <div class="h-44 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                        <?php if (!empty($news['thumbnail_url'])): ?>
                            <img 
                                src="
                                    <?php echo htmlspecialchars(booktab_news_thumbnail_url($news['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>
                                " 
                                alt="<?php echo htmlspecialchars($news['tieu_de']); ?>" 
                                class="w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                    <h3 class="font-semibold text-gray-800 line-clamp-2 mb-2">
                        <?php echo htmlspecialchars($news['tieu_de']); ?>
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2">
                        <?php echo htmlspecialchars($news['tom_tat'] ?? ''); ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">
            Danh mục này chưa có bài viết đã đăng.
        </p>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- ===================================================================================== -->
<!-- Các bài viết trong mỗi danh mục -->
<!-- ===================================================================================== -->
<div id="categories-sections" class="flex flex-col gap-14 mt-8 max-w-7xl mx-auto px-4 sm:px-6">
    
    <?php foreach ($categoriesWithNews as $category): ?>
        <?php
            $categoryPosts = $category['danh_sach_bai_viet'] ?? [];
            $featuredCategoryNews = $categoryPosts[0] ?? null;
            $otherCategoryNews = array_slice($categoryPosts, 1, 4);
        ?>
        <section 
            id="<?php echo htmlspecialchars($category['slug'] ?? ('category-' . $category['ma_loai']), ENT_QUOTES, 'UTF-8'); ?>" 
            class="scroll-mt-36"
        >
            <div class="flex justify-between items-end border-b border-gray-200 pb-3 mb-6">
                <h2 class="text-xl font-bold text-gray-800 uppercase tracking-widest">
                    <?php echo htmlspecialchars($category['ten_loai']); ?>
                </h2>
                <a 
                    href="
                        <?php echo BASE_URL; ?>/public/danh-muc/<?php echo urlencode($category['slug'] ?? ('category-' . $category['ma_loai'])); ?>
                    " 
                    class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition-colors"
                >
                    Xem thêm
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <?php if (!empty($categoryPosts)): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <?php if ($featuredCategoryNews !== null): ?>
                        <?php
                            $slug = urlencode($featuredCategoryNews['slug'] ?? '');
                            $detailUrl = BASE_URL . "/public/bai-viet/{$slug}";
                            $jsAction = "window.location='" . $detailUrl . "'";
                        ?>
                        <article 
                            class="flex flex-col cursor-pointer group wave-glass news-card p-4 lg:row-span-2" 
                            onclick="<?php echo htmlspecialchars($jsAction, ENT_QUOTES, 'UTF-8'); ?>"
                        >
                            <div class="glass-piece top-left"></div>
                            <div class="glass-piece top-right"></div>
                            <div class="glass-piece bottom-left"></div>
                            <div class="glass-piece bottom-right"></div>

                            <div class="h-72 bg-gray-200 rounded-xl mb-5 w-full overflow-hidden">
                                <?php if (!empty($featuredCategoryNews['thumbnail_url'])): ?>
                                    <img 
                                        src="
                                            <?php echo htmlspecialchars(booktab_news_thumbnail_url($featuredCategoryNews['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>
                                        " 
                                        alt="<?php echo htmlspecialchars($featuredCategoryNews['tieu_de']); ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 line-clamp-2 text-gray-900">
                                <?php echo htmlspecialchars($featuredCategoryNews['tieu_de']); ?>
                            </h3>
                            <p class="text-gray-500 text-base line-clamp-3 mb-4 leading-relaxed">
                                <?php echo htmlspecialchars($featuredCategoryNews['tom_tat'] ?? ''); ?>
                            </p>
                            <span class="text-sm text-gray-400 mt-auto font-medium">
                                <?php echo date('d/m/Y', strtotime($featuredCategoryNews['ngay_dang'])); ?>
                            </span>
                        </article>
                    <?php endif; ?>

                    <div class="grid grid-cols-2 gap-5">
                        <?php if (!empty($otherCategoryNews)): ?>
                            <?php foreach ($otherCategoryNews as $news): ?>
                                <?php
                                    $slug = urlencode($news['slug'] ?? '');
                                    $detailUrl = BASE_URL . "/public/bai-viet/{$slug}";
                                    $jsAction = "window.location='" . $detailUrl . "'";
                                ?>
                                <article 
                                    class="cursor-pointer group flex flex-col wave-glass news-card p-3" 
                                    onclick="<?php echo htmlspecialchars($jsAction, ENT_QUOTES, 'UTF-8'); ?>"
                                >
                                    <div class="glass-piece top-left"></div>
                                    <div class="glass-piece top-right"></div>
                                    <div class="glass-piece bottom-left"></div>
                                    <div class="glass-piece bottom-right"></div>

                                    <div class="h-32 bg-gray-200 rounded-lg mb-3 w-full overflow-hidden">
                                        <?php if (!empty($news['thumbnail_url'])): ?>
                                            <img 
                                                src="
                                                    <?php echo htmlspecialchars(booktab_news_thumbnail_url($news['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>
                                                " 
                                                alt="<?php echo htmlspecialchars($news['tieu_de']); ?>" 
                                                class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="text-sm font-semibold line-clamp-3 leading-snug text-gray-800 mb-2">
                                        <?php echo htmlspecialchars($news['tieu_de']); ?>
                                    </h4>
                                    <span class="text-xs text-gray-400 mt-auto">
                                        <?php echo date('d/m/Y', strtotime($news['ngay_dang'])); ?>
                                    </span>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Danh mục này chưa có bài viết.</p>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
</div>


<!-- ===================================================================================== -->
<!-- Các hàm hỗ trợ-->
<!-- ===================================================================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.slide');
        const btnNext = document.getElementById('btnNext');
        const btnPrev = document.getElementById('btnPrev');
        const dotsContainer = document.getElementById('sliderDots');
        
        let currentIndex = 0;
        const totalSlides = slides.length;
        let autoSlideInterval;

        // Tạo các dấu chấm  tương ứng với số lượng slide
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('w-2.5', 'h-2.5', 'rounded-full', 'cursor-pointer', 'transition-all', 'duration-300');
            // Chấm đầu tiên sáng lên
            if(index === 0) dot.classList.add('bg-brand', 'w-8'); 
            else dot.classList.add('bg-white/50', 'hover:bg-white');
            
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });
        const dots = dotsContainer.querySelectorAll('div');

        // Hàm chuyển slide
        function goToSlide(index) {
            // Xóa trạng thái active của slide hiện tại (chuyển về mờ)
            slides[currentIndex].classList.remove('opacity-100', 'z-20');
            slides[currentIndex].classList.add('opacity-0', 'z-10');
            
            // Xóa trạng thái active của dot hiện tại
            dots[currentIndex].classList.remove('bg-brand', 'w-8');
            dots[currentIndex].classList.add('bg-white/50');

            // Cập nhật index mới
            currentIndex = index;
            if (currentIndex < 0) {
                currentIndex = totalSlides - 1;
            }
            
            if (currentIndex >= totalSlides) {
                currentIndex = 0;
            }

            // Kích hoạt slide mới (chuyển sang rõ)
            slides[currentIndex].classList.remove('opacity-0', 'z-10');
            slides[currentIndex].classList.add('opacity-100', 'z-20');
            
            // Kích hoạt dot mới
            dots[currentIndex].classList.remove('bg-white/50');
            dots[currentIndex].classList.add('bg-brand', 'w-8');
            
            resetAutoSlide();
        }

        // Live search dropdown for News page
        const searchForm = document.getElementById('newsSearchForm');
        const searchInput = document.getElementById('newsSearchInput');
        const searchDropdown = document.getElementById('newsSearchDropdown');
        if (searchForm && searchInput && searchDropdown) {
            const appBaseUrl = '<?php echo BASE_URL; ?>/public/index.php';
            let searchTimer = null;
            let latestRequestId = 0;

            const buildSearchUrl = (keyword) => {
                const url = new URL(appBaseUrl);
                url.searchParams.set('page', 'news_list');
                url.searchParams.set('type', 'search');
                url.searchParams.set('keyword', keyword);
                return url.toString();
            };

            const renderEmpty = (message, keyword) => {
                searchDropdown.innerHTML = `
                    <div class="p-4 text-sm text-gray-500">${message}</div>
                    <div class="border-t border-gray-100 p-3 bg-gray-50">
                        <a 
                            href="${buildSearchUrl(keyword)}" 
                            class="block text-center px-4 py-2 rounded-lg bg-gray-900 text-white font-medium hover:bg-black transition-colors"
                        >
                            Xem toàn bộ kết quả
                        </a>
                    </div>
                `;
                searchDropdown.classList.remove('hidden');
            };

            const renderResults = (results, keyword) => {
                if (!results.length) {
                    renderEmpty('Không có gợi ý phù hợp.', keyword);
                    return;
                }

                searchDropdown.innerHTML = `
                    <div class="max-h-[420px] overflow-y-auto">
                        ${results.map((item) => `
                            <button 
                                type="button" 
                                data-news-url="${appBaseUrl}?page=news&news_action=detail&slug=${encodeURIComponent(item.slug)}" 
                                class="w-full text-left p-4 flex gap-3 border-b border-gray-100 last:border-b-0 transition-colors hover:bg-gray-50"
                            >
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                    ${
                                            item.thumbnail_url 
                                        ? 
                                            `<img 
                                                src="${item.thumbnail_url}" 
                                                alt="${item.tieu_de.replaceAll('"', '&quot;')}" 
                                                class="w-full h-full object-cover">
                                            ` 
                                        : 
                                            ''
                                    }
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[11px] uppercase tracking-wider text-gray-400 mb-1">
                                        ${item.ten_loai || 'Tin tức'}
                                    </div>
                                    <h3 class="font-semibold text-sm text-gray-900 line-clamp-2 mb-1">
                                        ${item.tieu_de}
                                    </h3>
                                    <div class="text-xs text-gray-400 flex items-center justify-between gap-2">
                                        <span>${item.ho_ten || 'BookTab'}</span>
                                        <span>${item.ngay_dang ? new Date(item.ngay_dang).toLocaleDateString('vi-VN') : ''}</span>
                                    </div>
                                </div>
                            </button>
                        `).join('')}
                    </div>
                    <div class="border-t border-gray-100 p-3 bg-gray-50">
                        <a 
                            href="${buildSearchUrl(keyword)}" 
                            class="block text-center px-4 py-2 rounded-lg bg-gray-900 text-white font-medium hover:bg-black transition-colors"
                        >
                            Xem toàn bộ kết quả
                        </a>
                    </div>
                `;
                searchDropdown.classList.remove('hidden');

                searchDropdown.querySelectorAll('[data-news-url]').forEach((button) => {
                    button.addEventListener('click', () => {
                        window.location.href = button.dataset.newsUrl;
                    });
                });
            };

            const fetchResults = async (keyword) => {
                const requestId = ++latestRequestId;
                const url = new URL(appBaseUrl);
                url.searchParams.set('page', 'news');
                url.searchParams.set('news_action', 'search_ajax');
                url.searchParams.set('keyword', keyword);

                try {
                    const response = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                    const payload = await response.json();

                    if (requestId !== latestRequestId) {
                        return;
                    }

                    renderResults(payload.results || [], keyword);
                } catch (error) {
                    if (requestId !== latestRequestId) {
                        return;
                    }
                    renderEmpty('Không thể tải dữ liệu gợi ý.', keyword);
                }
            };

            searchInput.addEventListener('input', () => {
                const keyword = searchInput.value.trim();

                clearTimeout(searchTimer);

                if (keyword.length < 2) {
                    searchDropdown.classList.add('hidden');
                    searchDropdown.innerHTML = '';
                    return;
                }

                searchTimer = setTimeout(() => fetchResults(keyword), 250);
            });

            searchInput.addEventListener('focus', () => {
                if (searchDropdown.innerHTML.trim() !== '') {
                    searchDropdown.classList.remove('hidden');
                }
            });

            document.addEventListener('click', (event) => {
                if (!searchForm.contains(event.target)) {
                    searchDropdown.classList.add('hidden');
                }
            });

            searchForm.addEventListener('submit', (event) => {
                const keyword = searchInput.value.trim();
            });
        }

        // Click nút chuyển slide kế
        btnNext.addEventListener('click', () => goToSlide(currentIndex + 1));
        btnPrev.addEventListener('click', () => goToSlide(currentIndex - 1));

        // Tự động chuyển slide kế sau 5 giây
        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, 5000);
        }
        
        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        startAutoSlide();
    });
</script>