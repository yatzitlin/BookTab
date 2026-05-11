<?php
/* ===================================================================================== */
/* Khởi tạo hàm hỗ trợ: Tạo URL ảnh thumbnail từ đường dẫn tương đối hoặc tuyệt đối */
/* ===================================================================================== */
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
    /* ===== Định dạng: Phong cách nền Notion - Mô phỏng giao diện Notion ===== */
    body { 
        background-color: #fbfbfb; 
    }

    /* ===== Xử lý: Nội dung từ TinyMCE - Style cho article body ===== */
    .chi-tiet-bai-viet {
        color: #37352f;
    }

    /* Heading 2: Tiêu đề cấp 2 trong nội dung */
    .chi-tiet-bai-viet h2 {
        font-size: 1.875rem; 
        font-weight: 700; 
        margin-top: 2rem; 
        margin-bottom: 1rem; 
        color: #1a1a1a;
    }

    /* Heading 3: Tiêu đề cấp 3 trong nội dung */
    .chi-tiet-bai-viet h3 {
        font-size: 1.5rem; 
        font-weight: 600; 
        margin-top: 1.5rem; 
        margin-bottom: 0.75rem; 
        color: #1a1a1a;
    }

    /* Paragraph: Đoạn văn bản trong nội dung */
    .chi-tiet-bai-viet p {
        margin-bottom: 1.25rem; 
        line-height: 1.8;
    }

    /* Ảnh: Ép kích thước, border-radius và margins */
    .chi-tiet-bai-viet img {
        max-width: 100% !important; 
        height: auto !important; 
        border-radius: 12px; 
        margin: 2rem auto; 
        display: block;
    }

    /* iFrame: Ép kích thước video, aspect ratio 16:9 */
    .chi-tiet-bai-viet iframe {
        width: 100% !important; 
        aspect-ratio: 16 / 9; 
        border-radius: 12px; 
        margin: 2rem 0;
    }

    /* Figcaption: Caption ảnh với style nhẹ */
    .chi-tiet-bai-viet figcaption {
        text-align: center; 
        color: #6b7280; 
        font-size: 0.875rem; 
        font-style: italic; 
        margin-top: -1rem; 
        margin-bottom: 2rem;
    }

    /* ===== Hiệu ứng: Table of Contents - TOC link active ===== */
    .toc-link.active {
        color: #2563eb;
        border-left-color: #2563eb;
        background-color: #f3f4f6;
    }

    /* ===== Scroll: Smooth scroll và scroll margin cho anchor jump ===== */
    html {
        scroll-behavior: smooth;
    }

    section[id], h2[id], h1[id] {
        scroll-margin-top: 120px;
    }

    /* ===== Responsive: Tablet và điện thoại ===== */
    @media (max-width: 1024px) {
        .news-detail-layout {
            gap: 2rem;
        }

        .news-detail-article {
            width: 100%;
        }

        .news-detail-toc {
            width: 100%;
            order: -1;
        }

        .news-detail-toc-panel {
            position: relative;
            top: auto;
        }
    }

    @media (max-width: 768px) {
        .news-detail-page {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .news-detail-page .breadcrumb ol {
            flex-wrap: wrap;
            row-gap: 0.35rem;
        }

        .news-detail-page .breadcrumb li {
            max-width: 100%;
        }

        .news-detail-article {
            padding: 1.25rem;
            border-radius: 1.5rem;
        }

        .news-detail-article h1 {
            font-size: 2rem;
            line-height: 1.2;
        }

        .news-detail-meta {
            gap: 0.75rem;
            align-items: flex-start;
        }

        .news-detail-toc-panel {
            padding: 1rem;
            border-radius: 1.25rem;
        }
    }

    @media (max-width: 640px) {
        .news-detail-page .breadcrumb {
            display: none;
        }

        .news-detail-article .mb-8 p {
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .news-detail-article .chi-tiet-bai-viet {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .news-detail-article .chi-tiet-bai-viet p {
            font-size: 0.9rem;
        }

        .news-detail-meta {
            gap: 0.75rem;
        }

        .news-detail-meta .w-12.h-12 {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 0.85rem;
        }

        .news-detail-meta > div .font-bold {
            font-size: 0.875rem;
        }

        .news-detail-meta > div .text-gray-400 {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .comments-list-item {
            padding: 0.75rem;
        }

        .comments-list-item .font-bold {
            font-size: 0.875rem;
        }

        .comments-list-item .text-gray-600,
        .comments-list-item .text-xs,
        .comments-list-item p {
            font-size: 0.8rem;
        }

        .comments-list-item .text-gray-400 {
            font-size: 0.7rem;
        }

        .news-detail-article h1 {
            font-size: 1.75rem;
        }

        .news-detail-article .chi-tiet-bai-viet h2 {
            font-size: 1.5rem;
        }

        .news-detail-article .chi-tiet-bai-viet h3 {
            font-size: 1.25rem;
        }
    }

        .news-detail-toc-panel {
            padding: 0.2rem;
        }

        .news-detail-toc-panel #toc-list {
            gap: 0.125rem;
        }
    }
</style>

<div class="news-detail-page max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Section: Breadcrumb Navigation - Đường dẫn điều hướng hiển thị vị trí hiện tại -->
    <nav 
        class="breadcrumb flex text-sm text-gray-400 mb-8" 
        aria-label="Breadcrumb"
    >
        <ol class="flex items-center space-x-2">
            <li>
                <a 
                    href="<?php echo BASE_URL; ?>/public/bai-viet" 
                    class="hover:text-gray-600 transition-colors"
                >
                    Bài viết
                </a>
            </li>
            <li>
                >
            </li>
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
            <li>
                >
            </li>
            <li 
                class="text-gray-600 font-medium truncate max-w-[150px] md:max-w-[300px]" 
                title="<?php echo htmlspecialchars($newsDetail['tieu_de']); ?>"
            >
                <?php echo htmlspecialchars($newsDetail['tieu_de']); ?>
            </li>
        </ol>
    </nav>

    <div class="news-detail-layout flex flex-col lg:flex-row gap-12">

        <!-- Sidebar: Table of Contents + Sticky navigation -->
        <aside class="news-detail-toc w-full lg:w-4/12 flex flex-col gap-8 order-first lg:order-last">
            <div 
                id="toc-container" 
                class="news-detail-toc-panel sticky top-24 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm"
            >
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">
                    Nội dung bài viết
                </h3>
                <nav 
                    id="toc-list" 
                    class="flex flex-col gap-1 border-l-2 border-gray-50"
                >
                </nav>
            </div>
        </aside>
        
        <!-- Article: Nội dung bài viết chính -->
        <article class="news-detail-article w-full lg:w-8/12 bg-white p-6 md:p-10 rounded-3xl shadow-sm border border-gray-100">
            
            <!-- Badge: Danh mục bài viết -->
            <span class="inline-block px-3 py-1 bg-blue-50 text-brand text-xs font-bold rounded-md uppercase tracking-wider mb-4">
                <?php echo htmlspecialchars($newsDetail['ten_loai']); ?>
            </span>

            <!-- Tiêu đề: Tiêu đề chính bài viết -->
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-6">
                <?php echo htmlspecialchars($newsDetail['tieu_de']); ?>
            </h1>

            <!-- Metadata: Tác giả, ngày đăng, lượt xem -->
            <div class="news-detail-meta flex items-center gap-4 mb-10 pb-8 border-b border-gray-100">
                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                    <?php echo mb_substr($newsDetail['ho_ten'], 0, 1); ?>
                </div>
                <div class="text-sm">
                    <div class="font-bold text-gray-900">
                        <?php echo htmlspecialchars($newsDetail['ho_ten']); ?>
                    </div>
                    <div class="text-gray-400">
                        Đăng ngày <?php echo date('d/m/Y', strtotime($newsDetail['ngay_dang'])); ?> 
                        • <?php echo number_format($newsDetail['luot_xem']); ?> lượt xem
                    </div>
                </div>
            </div>

            <!-- Ảnh: Thumbnail chính của bài viết -->
            <?php if(!empty($newsDetail['thumbnail_url'])): ?>
                <div class="mb-10">
                    <img 
                        src="<?php echo htmlspecialchars(booktab_news_thumbnail_url($newsDetail['thumbnail_url']), ENT_QUOTES, 'UTF-8'); ?>" 
                        alt="Thumbnail" 
                        class="w-full h-auto rounded-2xl shadow-lg"
                    >
                </div>
            <?php endif; ?>

            <!-- Tóm tắt: Đoạn mô tả tóm tắt bài viết -->
            <?php if (!empty($newsDetail['tom_tat'])): ?>
                <div class="mb-8 p-6 bg-gray-50 border-l-4 border-red-400 rounded-r-xl">
                    <p class="text-lg text-gray-600 italic leading-relaxed text-justify">
                        <?php echo htmlspecialchars($newsDetail['tom_tat'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Nội dung: Phần body bài viết từ TinyMCE editor -->
            <div class="chi-tiet-bai-viet">
                <?php 
                    $content = $newsDetail['noi_dung']; 
                    $content = str_replace('src="public/upload/', 'src="' . BASE_URL . '/public/upload/', $content);
                    $content = str_replace('src="upload/', 'src="' . BASE_URL . '/public/upload/', $content);
                    echo $content;
                ?>
            </div>

            <!-- Footer: Nút quay lại danh sách -->
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

            <!-- Section: Comments - Bình luận bài viết -->
            <section 
                class="mt-12 pt-10 border-t border-gray-100" 
                id="comments-section"
            >
                <!-- Header: Tiêu đề + mô tả section -->
                <div class="flex items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Bình Luận
                    </h2>
                    <span class="text-sm text-gray-500">
                        Chia sẻ suy nghĩ của bạn về bài viết này
                    </span>
                </div>

                <?php
                $articleId = $newsDetail['ma_bai_viet'] ?? 0;
                $isLoggedIn = isset($_SESSION['userid']);
                ?>

                <!-- Form: Hộp nhập bình luận (nếu đã đăng nhập) -->
                <div class="mb-8" id="commentFormContainer">
                    <?php if ($isLoggedIn): ?>
                        <form 
                            id="mainCommentForm" 
                            class="space-y-4"
                        >
                            <input 
                                type="hidden" 
                                name="article_id" 
                                value="<?php echo (int) $articleId; ?>"
                            >
                            <input 
                                type="hidden" 
                                name="parent_id" 
                                value=""
                            >
                            
                            <!-- Textarea: Ô nhập nội dung bình luận -->
                            <div>
                                <label 
                                    for="mainContent" 
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Bình Luận Của Bạn
                                </label>
                                <textarea
                                    id="mainContent"
                                    name="content"
                                    rows="4"
                                    placeholder="Chia sẻ suy nghĩ của bạn..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    minlength="5"
                                    maxlength="5000"
                                ></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    5 - 5000 ký tự
                                </p>
                            </div>

                            <!-- Nút hành động: Hủy + Gửi bình luận -->
                            <div class="flex justify-end gap-3">
                                <button
                                    type="button"
                                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                                    onclick="document.getElementById('mainContent').value='';"
                                >
                                    Hủy
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                >
                                    Gửi Bình Luận
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <!-- Notice: Thông báo yêu cầu đăng nhập -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700">
                                <a 
                                    href="<?php echo BASE_URL; ?>/public/index.php?page=auth&action=login" 
                                    class="text-blue-600 font-medium hover:underline"
                                >
                                    Đăng nhập
                                </a>
                                để bình luận
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Container: Danh sách bình luận được tải qua AJAX -->
                <div 
                    id="commentsList" 
                    class="space-y-6"
                >
                    <!-- Spinner: Hiển thị đang tải -->
                    <div class="text-center py-8">
                        <div class="inline-block animate-spin">
                            <i class="fas fa-spinner text-blue-600"></i>
                        </div>
                        <p class="text-gray-600 mt-2">
                            Đang tải bình luận...
                        </p>
                    </div>
                </div>

                <!-- Nút Load More: Xem thêm bình luận (ẩn nếu không có thêm) -->
                <div 
                    class="mt-8 text-center" 
                    id="loadMoreContainer" 
                    style="display: none;"
                >
                    <button
                        id="loadMoreBtn"
                        class="px-6 py-2 border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium"
                    >
                        Xem Thêm Bình Luận
                    </button>
                </div>
            </section>
        </article>

    </div>
</div>

<!-- ===================================================================================== -->
<!-- Script: Logic Table of Contents - Tạo mục lục tự động từ headings -->
<!-- ===================================================================================== -->
<script>
    /* ===== Khởi tạo: Xây dựng table of contents từ headings H2, H3, H4 ===== */
    document.addEventListener('DOMContentLoaded', function() {
        const article = document.querySelector('.chi-tiet-bai-viet');
        const tocList = document.getElementById('toc-list');
        const tocContainer = document.getElementById('toc-container');
        
        if (!article || !tocList) return;

        /* ===== Quét: Lấy tất cả headings H2, H3, H4 trong article ===== */
        const headings = article.querySelectorAll('h2, h3, h4');
        
        if (headings.length === 0) {
            tocContainer.style.display = 'none';
            return;
        }

        /* ===== Vòng lặp: Tạo link cho mỗi heading ===== */
        headings.forEach((heading, index) => {
            // Tạo ID duy nhất nếu chưa có
            const id = `heading-${index}`;
            heading.id = id;

            // Tạo link trong mục lục
            const link = document.createElement('a');
            link.href = `#${id}`;
            link.innerText = heading.innerText;
            link.className = 'toc-link block py-2 px-4 text-sm transition-all border-l-2 border-transparent hover:bg-gray-50';

            // Phân cấp indent dựa trên thẻ
            if (heading.tagName.toLowerCase() === 'h2') {
                link.classList.add('font-bold', 'text-gray-700');
            } else if (heading.tagName.toLowerCase() === 'h3') {
                link.classList.add('pl-8', 'text-gray-500');
            } else if (heading.tagName.toLowerCase() === 'h4') {
                link.classList.add('pl-12', 'text-gray-400', 'text-sm');
            }

            tocList.appendChild(link);
        });

        /* ===== Event: Scroll - Highlight active link khi cuộn trang ===== */
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

        /* ===== Smooth scroll: Cuộn mượt khi click vào TOC ===== */
        document.documentElement.style.scrollBehavior = 'smooth';
    });
</script>

<!-- ===================================================================================== -->
<!-- Script: Comment System - Xử lý hiển thị, tải, gửi bình luận -->
<!-- ===================================================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ===== Khởi tạo: Biến toàn cục cho comment system ===== */
    const articleId = <?php echo (int) $articleId; ?>;
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    let currentPage = 1;
    let hasMore = false;

    /* ===== Tải bình luận: Gọi API lấy comments khi trang load ===== */
    loadComments(1);

    /* ===== Event: Submit Form - Xử lý khi người dùng gửi bình luận chính ===== */
    document.getElementById('mainCommentForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'post_comment');

        try {
            const response = await fetch('<?php echo BASE_URL; ?>/public/index.php?page=news&news_action=post_comment', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.error) {
                alert('Lỗi: ' + result.error);
            } else if (result.success) {
                document.getElementById('mainContent').value = '';
                loadComments(1);
                showNotification('Bình luận của bạn đã được đăng');
                document.getElementById('comments-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } catch (error) {
            console.error('Error posting comment:', error);
            alert('Có lỗi xảy ra khi gửi bình luận');
        }
    });

    /* ===== Event: Load More Button - Tải thêm bình luận khi click nút ===== */
    document.getElementById('loadMoreBtn')?.addEventListener('click', function() {
        currentPage++;
        loadComments(currentPage);
    });

    /* ===== Hàm: Tải danh sách bình luận từ server qua AJAX ===== */
    async function loadComments(page = 1) {
        try {
            const url = `<?php echo BASE_URL; ?>/public/index.php?page=news&news_action=get_comments&article_id=${articleId}&comment_page=${page}`;
            const response = await fetch(url);
            const result = await response.json();

            if (result.success) {
                renderComments(result.comments, page);
                hasMore = result.hasMore;
                document.getElementById('loadMoreContainer').style.display = hasMore ? 'block' : 'none';
            } else {
                throw new Error(result.error || 'Không thể tải bình luận');
            }
        } catch (error) {
            console.error('Error loading comments:', error);
            const container = document.getElementById('commentsList');
            if (container && page === 1) {
                container.innerHTML = '<p class="text-center text-red-500 py-8">Không tải được bình luận. Vui lòng thử lại sau.</p>';
            }
            const loadMoreContainer = document.getElementById('loadMoreContainer');
            if (loadMoreContainer) {
                loadMoreContainer.style.display = 'none';
            }
        }
    }

    /* ===== Hàm: Render bình luận vào DOM ===== */
    function renderComments(comments, page) {
        const container = document.getElementById('commentsList');
        
        if (page === 1) {
            container.innerHTML = '';
        }

        if (!comments || comments.length === 0) {
            if (page === 1) {
                container.innerHTML = '<p class="text-center text-gray-500 py-8">Chưa có bình luận nào. Hãy là người đầu tiên!</p>';
            }
            return;
        }

        comments.forEach(comment => {
            const commentEl = createCommentElement(comment);
            container.appendChild(commentEl);
        });
    }

    /* ===== Hàm: Tạo DOM element cho từng bình luận (hỗ trợ nested replies) ===== */
    function createCommentElement(comment, isReply = false) {
        const div = document.createElement('div');
        div.className = isReply ? 'ml-8 border-l-2 border-gray-300 pl-4 pt-4' : 'border-b pb-6';

        const date = new Date(comment.ngay_tao);
        const formattedDate = date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

        let replyButton = '';
        if (!isReply && isLoggedIn) {
            replyButton = `
                <button 
                    class="text-sm text-blue-600 hover:underline mt-2"
                    onclick="toggleReplyForm(this, ${comment.ma_binh_luan})"
                >
                    Trả Lời
                </button>
            `;
        }

        let repliesHtml = '';
        if (comment.replies && comment.replies.length > 0) {
            repliesHtml = '<div class="mt-4 space-y-4">';
            comment.replies.forEach(reply => {
                const replyEl = createCommentElement(reply, true);
                repliesHtml += '<div>' + replyEl.outerHTML + '</div>';
            });
            repliesHtml += '</div>';
        }

        div.innerHTML = `
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                        ${(comment.ho_va_ten_dem ? comment.ho_va_ten_dem[0] : 'U').toUpperCase()}
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-gray-900">
                            ${comment.ho_va_ten_dem} ${comment.ten}
                        </h4>
                        <span class="text-sm text-gray-500">${formattedDate}</span>
                    </div>
                    <p class="text-gray-700 mt-2">${escapeHtml(comment.noi_dung)}</p>
                    ${replyButton}
                </div>
            </div>
            ${repliesHtml}
        `;

        return div;
    }

    /* ===== Hàm: Toggle hiện/ẩn form trả lời ===== */
    window.toggleReplyForm = function(button, parentId) {
        let replyForm = button.nextElementSibling;
        if (replyForm && replyForm.classList.contains('reply-form')) {
            replyForm.remove();
            button.textContent = 'Trả Lời';
        } else {
            button.textContent = 'Hủy';
            const form = document.createElement('div');
            form.className = 'reply-form mt-3 p-3 bg-gray-50 rounded-lg';
            form.innerHTML = `
                <textarea
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    placeholder="Viết câu trả lời..."
                    minlength="5"
                    maxlength="5000"
                ></textarea>
                <div class="flex justify-end gap-2 mt-2">
                    <button
                        type="button"
                        class="px-3 py-1 text-sm text-gray-700 border border-gray-300 rounded hover:bg-gray-100"
                        onclick="this.parentElement.parentElement.remove(); arguments[0].target.previousElementSibling.textContent='Trả Lời';"
                    >
                        Hủy
                    </button>
                    <button
                        type="button"
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
                        onclick="submitReply(${parentId}, this)"
                    >
                        Gửi
                    </button>
                </div>
            `;
            button.insertAdjacentElement('afterend', form);
        }
    };

    /* ===== Hàm: Gửi trả lời bình luận ===== */
    window.submitReply = async function(parentId, button) {
        const textarea = button.parentElement.parentElement.querySelector('textarea');
        const content = textarea.value.trim();

        if (content.length < 5 || content.length > 5000) {
            alert('Trả lời phải từ 5-5000 ký tự');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('article_id', articleId);
            formData.append('content', content);
            formData.append('parent_id', parentId);

            const response = await fetch('<?php echo BASE_URL; ?>/public/index.php?page=news&news_action=post_comment', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.error) {
                alert('Lỗi: ' + result.error);
            } else if (result.success) {
                loadComments(1);
                showNotification('Trả lời của bạn đã được đăng');
                document.getElementById('comments-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } catch (error) {
            console.error('Error posting reply:', error);
            alert('Có lỗi xảy ra khi gửi trả lời');
        }
    };

    /* ===== Hàm: Escape HTML - Ngăn chặn XSS injection ===== */
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* ===== Hàm: Hiển thị notification toast ===== */
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg animate-pulse';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
});
</script>
