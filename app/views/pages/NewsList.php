<?php
$latestNews = $latestNews ?? [];
$trendingNews = $trendingNews ?? [];
$categoriesWithNews = $categoriesWithNews ?? [];
$newsItems = $newsItems ?? [];
$selectedCategory = $selectedCategory ?? null;
$searchKeyword = $searchKeyword ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;

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

$pageMode = $_GET['type'] ?? 'all';
$pageTitleText = 'Tất cả bài viết';
$pageDescriptionText = 'Danh sách toàn bộ bài viết mới nhất từ BookTab.';
if ($pageMode === 'category' && $selectedCategory) {
    $pageTitleText = $selectedCategory['ten_loai'];
    $pageDescriptionText = 'Tất cả bài viết thuộc danh mục này.';
} elseif ($pageMode === 'search' && $searchKeyword !== '') {
    $pageTitleText = 'Tìm kiếm: ' . $searchKeyword;
    $pageDescriptionText = 'Kết quả tìm kiếm cho từ khóa bạn vừa nhập.';
}

function booktab_news_list_page_url($type, $value, $pageNumber = 1) {
    // Nếu trang > 1 -> thêm ?news_page=...
    $pageParam = $pageNumber > 1 ? '?news_page=' . (int) $pageNumber : '';

    if ($type === 'category') {
        return BASE_URL . '/public/danh-muc/' . urlencode($value) . $pageParam;
    }

    if ($type === 'search') {
        return BASE_URL . '/public/tim-kiem-bai-viet?keyword=' . urlencode($value) . ($pageNumber > 1 ? '&news_page=' . (int) $pageNumber : '');
    }

    // Trang xem tất cả
    return BASE_URL . '/public/bai-viet' . $pageParam;
}
?>

<style>
    .news-card {
        background-color: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 8px 10px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        transition: box-shadow 0.3s ease, transform 1s ease;
    }

    .news-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .search-result-item:hover {
        background: #f8fafc;
    }

    .search-dropdown-empty {
        color: #64748b;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6">
    <nav class="flex text-sm text-gray-400 mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a 
                    href="<?php echo BASE_URL; ?>/public/bai-viet"
                    class="hover:text-gray-600"
                >
                    Bài viết
                </a>
            </li>
            <li> > </li>
            <li class="text-gray-600 font-medium truncate max-w-[240px] md:max-w-none">
                <?php echo htmlspecialchars($pageTitleText); ?>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-2"><?php echo htmlspecialchars($pageTitleText); ?></h1>
            <p class="text-gray-500"><?php echo htmlspecialchars($pageDescriptionText); ?></p>
        </div>

        <div class="w-full lg:w-[420px]">
            <form 
                id="newsSearchForm" 
                action="<?php echo BASE_URL; ?>/public/tim-kiem-tin-tuc" 
                method="get" 
                class="relative"
            >
                <div class="p-1 rounded-xl flex items-center border border-gray-200 bg-gray-50 focus-within:bg-white focus-within:border-gray-400 focus-within:shadow-sm transition-all">
                    <input 
                        id="newsSearchInput" 
                        name="keyword" 
                        value="<?php echo htmlspecialchars($searchKeyword); ?>" 
                        type="text" 
                        placeholder="Tìm kiếm bài viết..." 
                        autocomplete="off" 
                        class="w-full bg-transparent px-4 py-2 outline-none text-sm text-gray-700 placeholder-gray-400"
                    >
                    <button type="submit" class="p-2 bg-gray-800 text-white rounded-lg hover:bg-black transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
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

    <?php if (!empty($newsItems)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($newsItems as $news): ?>
                <?php $newsUrl = BASE_URL . '/public/bai-viet/' . urlencode($news['slug'] ?? ''); ?>
                <article class="news-card cursor-pointer overflow-hidden group" onclick="window.location='<?php echo $newsUrl; ?>'">  
                    <div class="relative h-52 bg-gray-200 overflow-hidden">
                        <?php if (!empty($news['thumbnail_url'])): ?>
                            <img 
                                src="
                                    <?php echo htmlspecialchars(booktab_news_thumbnail_url($news['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>
                                " 
                                alt="<?php echo htmlspecialchars($news['tieu_de']); ?>" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute top-4 left-4 px-3 py-1 rounded-full bg-white/90 text-xs font-semibold text-gray-700">
                            <?php echo htmlspecialchars($news['ten_loai'] ?? 'Tin tức'); ?>
                        </div>
                    </div>
                    <div class="p-5">
                        <h2 class="font-bold text-lg text-gray-900 line-clamp-2 mb-3">
                            <?php echo htmlspecialchars($news['tieu_de']); ?>
                        </h2>
                        <p class="text-sm text-gray-500 line-clamp-3 mb-4 leading-relaxed">
                            <?php echo htmlspecialchars($news['tom_tat'] ?? ''); ?>
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span><?php echo htmlspecialchars($news['ho_ten'] ?? 'BookTab'); ?></span>
                            <span><?php echo date('d/m/Y', strtotime($news['ngay_dang'] ?? 'now')); ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center text-gray-500">
            Không tìm thấy bài viết phù hợp.
        </div>
    <?php endif; ?>

    <div class="mt-10 flex items-center justify-between gap-4 flex-wrap">
        <div class="text-sm text-gray-500">
            Trang <?php echo (int) $currentPage; ?> / <?php echo (int) $totalPages; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="flex items-center gap-2">
                <?php
                    $prevPage = max(1, $currentPage - 1);
                    $nextPage = min($totalPages, $currentPage + 1);
                ?>
                <a 
                    href="<?php echo htmlspecialchars(($pageMode === 'category' && $selectedCategory) 
                        ? booktab_news_list_page_url('category', $selectedCategory['slug'], $prevPage) 
                        : (($pageMode === 'search' && $searchKeyword !== '') 
                        ? booktab_news_list_page_url('search', $searchKeyword, $prevPage) 
                        : booktab_news_list_page_url('all', '', $prevPage)), ENT_QUOTES, 'UTF-8'); ?>
                    " 
                    class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 
                        <?php echo $currentPage <= 1 ? 'pointer-events-none opacity-40' : ''; ?>
                    "
                >
                    Trước
                </a>
                <span class="px-4 py-2 rounded-lg bg-gray-900 text-white font-semibold">
                    <?php echo (int) $currentPage; ?>
                </span>
                <a 
                    href="
                        <?php echo htmlspecialchars(($pageMode === 'category' && $selectedCategory) 
                            ? booktab_news_list_page_url('category', $selectedCategory['slug'], $nextPage) 
                            : (($pageMode === 'search' && $searchKeyword !== '') 
                            ? booktab_news_list_page_url('search', $searchKeyword, $nextPage) 
                            : booktab_news_list_page_url('all', '', $nextPage)), ENT_QUOTES, 'UTF-8'); 
                        ?>
                    " 
                    class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 
                        <?php echo $currentPage >= $totalPages ? 'pointer-events-none opacity-40' : ''; ?>
                    "
                >
                    Sau
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('newsSearchInput');
        const dropdown = document.getElementById('newsSearchDropdown');
        const form = document.getElementById('newsSearchForm');
        const appBaseUrl = '<?php echo BASE_URL; ?>/public';

        const getValidImageUrl = (url) => {
            if (!url) return '';
            if (url.startsWith('http')) return url;
            let cleanUrl = url.replace(/\\/g, '/').replace(/^\//, '');
            if (cleanUrl.startsWith('public/')) {
                return `<?php echo BASE_URL; ?>/${cleanUrl}`;
            }
            return `<?php echo BASE_URL; ?>/public/${cleanUrl}`;
        };

        if (!input || !dropdown || !form) {
            return;
        }

        let debounceTimer = null;
        let latestRequestId = 0;

        const buildSearchUrl = (keyword) => {
            const url = new URL(`${appBaseUrl}/tim-kiem-tin-tuc`, window.location.origin);
            url.searchParams.set('keyword', keyword);
            return url.toString();
        };

        const renderEmpty = (message, keyword) => {
            dropdown.innerHTML = `
                <div class="p-4 text-sm search-dropdown-empty">${message}</div>
                <div class="border-t border-gray-100 p-3 bg-gray-50">
                    <a 
                        href="${buildSearchUrl(keyword)}" 
                        class="block text-center px-4 py-2 rounded-lg bg-gray-900 text-white font-medium hover:bg-black transition-colors"
                    >
                        Xem toàn bộ kết quả
                    </a>
                </div>
            `;
            dropdown.classList.remove('hidden');
        };

        const renderResults = (results, keyword) => {
            if (!results.length) {
                renderEmpty('Không có gợi ý phù hợp.', keyword);
                return;
            }

            dropdown.innerHTML = `
                <div class="max-h-[420px] overflow-y-auto">
                    ${results.map((item) => `
                        <button 
                            type="button" 
                            data-news-id="${item.ma_bai_viet}" 
                            data-news-url="${appBaseUrl}/bai-viet/${encodeURIComponent(item.slug)}" 
                            class="search-result-item w-full text-left p-4 flex gap-3 border-b border-gray-100 last:border-b-0 transition-colors"
                        >
                            <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                ${
                                    item.thumbnail_url 
                                    ? `<img src="${getValidImageUrl(item.thumbnail_url)}" 
                                        alt="${item.tieu_de.replaceAll('"', '&quot;')}" 
                                        class="w-full h-full object-cover">
                                        ` 
                                    : ''
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
                                    <span>
                                        ${item.ho_ten || 'BookTab'}
                                    </span>
                                    <span>
                                        ${
                                            item.ngay_dang 
                                            ? new Date(item.ngay_dang).toLocaleDateString('vi-VN') 
                                            : ''
                                        }
                                    </span>
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
            dropdown.classList.remove('hidden');

            dropdown.querySelectorAll('[data-news-url]').forEach((button) => {
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

        input.addEventListener('input', () => {
            const keyword = input.value.trim();

            clearTimeout(debounceTimer);

            if (keyword.length < 2) {
                dropdown.classList.add('hidden');
                dropdown.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => fetchResults(keyword), 250);
        });

        input.addEventListener('focus', () => {
            if (dropdown.innerHTML.trim() !== '') {
                dropdown.classList.remove('hidden');
            }
        });

        document.addEventListener('click', (event) => {
            if (!form.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        form.addEventListener('submit', (event) => {
            const keyword = input.value.trim();
        });
    });
</script>
