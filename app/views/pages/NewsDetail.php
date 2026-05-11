<?php
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
    /* 1. Phong cách nền Notion */
    body { background-color: #fbfbfb; }

    /* 2. Xử lý nội dung từ TinyMCE đổ ra */
    .chi-tiet-bai-viet {
        color: #37352f; /* Màu chữ đặc trưng của Notion */
    }
    .chi-tiet-bai-viet h2 {
        font-size: 1.875rem; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; color: #1a1a1a;
    }
    .chi-tiet-bai-viet h3 {
        font-size: 1.5rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1a1a1a;
    }
    .chi-tiet-bai-viet p {
        margin-bottom: 1.25rem; line-height: 1.8;
    }
    /* Ép kích thước ảnh và iframe không tràn khung */
    .chi-tiet-bai-viet img {
        max-width: 100% !important; height: auto !important; border-radius: 12px; margin: 2rem auto; display: block;
    }
    .chi-tiet-bai-viet iframe {
        width: 100% !important; aspect-ratio: 16 / 9; border-radius: 12px; margin: 2rem 0;
    }
    /* Style cho Caption ảnh (Nếu có figure/figcaption) */
    .chi-tiet-bai-viet figcaption {
        text-align: center; color: #6b7280; font-size: 0.875rem; font-style: italic; margin-top: -1rem; margin-bottom: 2rem;
    }

    /* 3. Hiệu ứng cho thanh mục lục (TOC) */
    .toc-link.active {
        color: #2563eb;
        border-left-color: #2563eb;
        background-color: #f3f4f6;
    }

    /* 4. Scroll margin để offset header sticky khi anchor jump */
    html {
        scroll-behavior: smooth;
    }
    section[id], h2[id], h1[id] {
        scroll-margin-top: 120px; /* Offset cho sticky header */
    }
</style>

    <nav class="flex text-sm text-gray-400 mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a 
                    href="<?php echo BASE_URL; ?>/public/bai-viet" 
                    class="hover:text-gray-600 transition-colors"
                >
                    Bài viết
                </a>
            </li>
            <li> > </li>
            <li>
                <?php 
                    $categorySlug = $newsDetail['slug_loai'];
                ?>
                <a 
                    href="<?php echo BASE_URL; ?>/public/danh-muc/<?php echo urlencode($categorySlug); ?>" 
                    class="hover:text-gray-600 transition-colors whitespace-nowrap"
                >
                    <?php echo htmlspecialchars($newsDetail['ten_loai']); ?>
                </a>
            </li>
            <li> > </li>
            <li 
                class="text-gray-600 font-medium truncate max-w-[150px] md:max-w-[300px]" 
                title="<?php echo htmlspecialchars($newsDetail['tieu_de']); ?>"
            >
                <?php echo htmlspecialchars($newsDetail['tieu_de']); ?>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-12">
        
        <article class="w-full lg:w-8/12 bg-white p-6 md:p-10 rounded-3xl shadow-sm border border-gray-100">
            
            <span class="inline-block px-3 py-1 bg-blue-50 text-brand text-xs font-bold rounded-md uppercase tracking-wider mb-4">
                <?php echo htmlspecialchars($newsDetail['ten_loai']); ?>
            </span>

            <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-6">
                <?php echo htmlspecialchars($newsDetail['tieu_de']); ?>
            </h1>

            <div class="flex items-center gap-4 mb-10 pb-8 border-b border-gray-100">
                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                    <?php echo mb_substr($newsDetail['ho_ten'], 0, 1); ?>
                </div>
                <div class="text-sm">
                    <div class="font-bold text-gray-900"><?php echo htmlspecialchars($newsDetail['ho_ten']); ?></div>
                    <div class="text-gray-400">
                        Đăng ngày <?php echo date('d/m/Y', strtotime($newsDetail['ngay_dang'])); ?> 
                        • <?php echo number_format($newsDetail['luot_xem']); ?> lượt xem
                    </div>
                </div>
            </div>

            <?php if(!empty($newsDetail['thumbnail_url'])): ?>
            <div class="mb-10">
                <img src="<?php echo htmlspecialchars(booktab_news_thumbnail_url($newsDetail['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>" 
                        alt="Thumbnail" 
                        class="w-full h-auto rounded-2xl shadow-lg">
            </div>
            <?php endif; ?>

            <?php if (!empty($newsDetail['tom_tat'])): ?>
                <div class="mb-8 p-6 bg-gray-50 border-l-4 border-red-400 rounded-r-xl">
                    <p class="text-lg text-gray-600 italic leading-relaxed text-justify">
                        <?php echo htmlspecialchars($newsDetail['tom_tat'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="chi-tiet-bai-viet">
                <?php 
                    // 1. Lấy nội dung gốc từ database
                    $content = $newsDetail['noi_dung']; 

                    // 2. Tự động sửa các đường dẫn ảnh tương đối thành tuyệt đối
                    // Tìm các thẻ src="upload/ hoặc src="public/upload/ và gắn thêm BASE_URL vào đầu
                    
                    // Trường hợp 1: Nếu TinyMCE lưu là public/upload/...
                    $content = str_replace('src="public/upload/', 'src="' . BASE_URL . '/public/upload/', $content);
                    
                    // Trường hợp 2: Nếu TinyMCE lưu là upload/...
                    $content = str_replace('src="upload/', 'src="' . BASE_URL . '/public/upload/', $content);

                    // 3. Hiển thị nội dung đã được xử lý
                    echo $content;
                ?>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100 flex justify-between items-center">
                <?php 
                    $categorySlug = $newsDetail['slug_loai'];
                ?>
                <a 
                    href="<?php echo BASE_URL; ?>/public/danh-muc/<?php echo urlencode($categorySlug); ?>"  
                    class="text-brand font-bold text-sm hover:underline"
                >
                    Quay lại danh sách
                </a>
            </div>
        </article>

        <aside class="w-full lg:w-4/12 flex flex-col gap-8">
            <div id="toc-container" class="sticky top-24 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Nội dung bài viết</h3>
                <nav id="toc-list" class="flex flex-col gap-1 border-l-2 border-gray-50"></nav>
            </div>
        </aside>

    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const article = document.querySelector('.chi-tiet-bai-viet');
        const tocList = document.getElementById('toc-list');
        const tocContainer = document.getElementById('toc-container');
        
        if (!article || !tocList) return;

        // Quét các thẻ H2 và H3 trong bài viết
        const headings = article.querySelectorAll('h2, h3, h4');
        
        if (headings.length === 0) {
            tocContainer.style.display = 'none';
            return;
        }

        headings.forEach((heading, index) => {
            // Tạo ID cho Heading nếu chưa có
            const id = `heading-${index}`;
            heading.id = id;

            // Tạo thẻ Link trong Mục lục
            const link = document.createElement('a');
            link.href = `#${id}`;
            link.innerText = heading.innerText;
            link.className = 'toc-link block py-2 px-4 text-sm transition-all border-l-2 border-transparent hover:bg-gray-50';

            // Phân cấp dựa trên tag
            if (heading.tagName.toLowerCase() === 'h2') {
                link.classList.add('font-bold', 'text-gray-700');
            } else if (heading.tagName.toLowerCase() === 'h3') {
                link.classList.add('pl-8', 'text-gray-500');
            } else if (heading.tagName.toLowerCase() === 'h4') {
                link.classList.add('pl-12', 'text-gray-400', 'text-sm');
            }

            tocList.appendChild(link);
        });

        // Xử lý Highlight khi cuộn trang (Active state)
        const links = document.querySelectorAll('.toc-link');
        window.addEventListener('scroll', () => {
            let current = '';
            headings.forEach(heading => {
                const sectionTop = heading.offsetTop;
                if (pageYOffset >= sectionTop - 120) {
                    current = heading.getAttribute('id');
                }
            });

            links.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        // Cuộn mượt (Smooth Scroll)
        document.documentElement.style.scrollBehavior = 'smooth';
    });
</script>