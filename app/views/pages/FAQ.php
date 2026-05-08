<?php
$qnaCategories = isset($qnaCategories) && is_array($qnaCategories) ? $qnaCategories : [];
$faqItems = isset($faqItems) && is_array($faqItems) ? $faqItems : [];
$faqPagination = isset($faqPagination) && is_array($faqPagination) ? $faqPagination : [
	'currentPage' => 1,
	'totalPages' => 1,
	'totalItems' => 0,
	'itemsPerPage' => 10
];
$selectedCategory = isset($selectedCategory) ? (int) $selectedCategory : (isset($_GET['category']) ? (int) $_GET['category'] : 0);

// Build category query parameter
$categoryParam = $selectedCategory > 0 ? '&category=' . $selectedCategory : '';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.css" />

<section class="mb-8">
	<div class="rounded-2xl bg-gradient-to-r from-red-500 to-orange-500 text-white p-8 md:p-10 shadow-lg">
		<p class="text-red-50 font-semibold uppercase tracking-wider text-sm mb-2">FAQ</p>
		<h1 class="text-3xl md:text-4xl font-bold mb-4">Câu hỏi thường gặp</h1>
		<p class="text-red-50 max-w-3xl">
			Trang FAQ chỉ để tra cứu nhanh. Khách và tài khoản đã đăng nhập đều xem được nội dung ở đây.
		</p>
		<div class="mt-6 flex flex-wrap gap-3">
			<a href="<?php echo BASE_URL; ?>/public/index.php?page=qna" class="inline-flex items-center px-5 py-3 rounded-full bg-gray-900 text-white font-semibold hover:bg-black transition">
				<i class="fas fa-comments mr-2"></i> Về phần hỏi đáp
			</a>
		</div>
	</div>
</section>

<section class="mb-8">
	<h2 class="text-xl font-bold text-gray-900 mb-4">Lọc theo chủ đề</h2>
	<div class="flex flex-wrap gap-3">
		<a href="<?php echo BASE_URL; ?>/public/index.php?page=qna&tab=faq"
		   class="px-4 py-2 rounded-full border <?php echo $selectedCategory === 0 ? 'bg-red-500 border-red-500 text-white' : 'bg-white border-gray-300 text-gray-700 hover:border-red-400'; ?> transition">
			Tất cả
		</a>

		<?php foreach ($qnaCategories as $category): ?>
			<?php $isActive = $selectedCategory === (int) $category['ma_loai']; ?>
			<a href="<?php echo BASE_URL; ?>/public/index.php?page=qna&tab=faq&category=<?php echo (int) $category['ma_loai']; ?>"
			   class="px-4 py-2 rounded-full border <?php echo $isActive ? 'bg-red-500 border-red-500 text-white' : 'bg-white border-gray-300 text-gray-700 hover:border-red-400'; ?> transition">
				<?php echo htmlspecialchars($category['ten_loai'], ENT_QUOTES, 'UTF-8'); ?>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<?php if ($faqPagination['totalItems'] > 0): ?>
	<div class="mb-4 flex items-center justify-between text-sm text-gray-600">
		<span>Hiển thị trang <?php echo (int)$faqPagination['currentPage']; ?> của <?php echo (int)$faqPagination['totalPages']; ?> (<?php echo (int)$faqPagination['totalItems']; ?> FAQ)</span>
	</div>
<?php endif; ?>

<section>
	<div class="space-y-4">
		<?php if (empty($faqItems)): ?>
			<div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-gray-600">
				Chưa có FAQ nào phù hợp với bộ lọc hiện tại.
			</div>
		<?php else: ?>
			<?php foreach ($faqItems as $item): ?>
				<article class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm animate-fade-in hover:shadow-md transition">
					<div class="flex flex-wrap items-center gap-3 mb-3">
						<span class="text-xs font-semibold bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-full">
							<?php echo htmlspecialchars($item['ten_loai'], ENT_QUOTES, 'UTF-8'); ?>
						</span>
						<?php if (!empty($item['ngay_dang'])): ?>
							<span class="text-xs text-gray-500">
								Cập nhật: <?php echo htmlspecialchars(date('d/m/Y', strtotime($item['ngay_dang'])), ENT_QUOTES, 'UTF-8'); ?>
							</span>
						<?php endif; ?>
					</div>

					<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-3">
						<?php echo htmlspecialchars($item['ten_cau_hoi'], ENT_QUOTES, 'UTF-8'); ?>
					</h3>

					<?php if (!empty($item['images'])): ?>
						<div class="mb-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
							<?php foreach ($item['images'] as $img): ?>
								<?php $imageUrl = BASE_URL . '/' . htmlspecialchars(ltrim($img['url_anh'], '/'), ENT_QUOTES, 'UTF-8'); ?>
								<button type="button" class="group overflow-hidden rounded-lg bg-gray-50 border text-left image-zoom-trigger" data-lightbox-src="<?php echo $imageUrl; ?>" data-lightbox-alt="<?php echo htmlspecialchars($img['ten_file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
									<figure class="relative">
										<img src="<?php echo $imageUrl; ?>" 
										     alt="<?php echo htmlspecialchars($img['ten_file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
										     class="w-full h-44 object-cover lazyload transition duration-300 group-hover:scale-105" 
										     loading="lazy" 
										     data-src="<?php echo $imageUrl; ?>" />
										<span class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></span>
									</figure>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-gray-700 leading-7 mb-3 flex flex-col gap-4">
						<div class="answer-content">
							<?php echo $item['cau_tra_loi'] ?? ''; ?>
						</div>

					<?php if (!empty($item['answer_images'])): ?>
						<div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3">
							<?php foreach ($item['answer_images'] as $img): ?>
								<?php $answerImageUrl = BASE_URL . '/' . htmlspecialchars(ltrim($img['url_anh'], '/'), ENT_QUOTES, 'UTF-8'); ?>
								<button type="button" class="group overflow-hidden rounded-lg bg-white border text-left image-zoom-trigger" data-lightbox-src="<?php echo $answerImageUrl; ?>" data-lightbox-alt="<?php echo htmlspecialchars($img['ten_file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
									<figure class="relative">
										<img src="<?php echo $answerImageUrl; ?>" 
										     alt="<?php echo htmlspecialchars($img['ten_file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
										     class="w-full h-36 object-cover lazyload transition duration-300 group-hover:scale-105" 
										     loading="lazy" 
										     data-src="<?php echo $answerImageUrl; ?>" />
										<span class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></span>
									</figure>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>

<?php if ($faqPagination['totalPages'] > 1): ?>
	<nav class="mt-8 flex items-center justify-center gap-2">
		<?php if ($faqPagination['currentPage'] > 1): ?>
			<a href="<?php echo BASE_URL; ?>/public/index.php?page=qna&tab=faq&qna_page=<?php echo $faqPagination['currentPage'] - 1; ?><?php echo $categoryParam; ?>"
			   class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
				<i class="fas fa-chevron-left text-sm"></i> Trang trước
			</a>
		<?php endif; ?>

		<div class="flex gap-1">
			<?php for ($i = 1; $i <= $faqPagination['totalPages']; $i++): ?>
				<?php if ($i === $faqPagination['currentPage']): ?>
					<span class="px-3 py-2 rounded-lg bg-red-500 text-white font-semibold">
						<?php echo $i; ?>
					</span>
				<?php elseif ($i === 1 || $i === $faqPagination['totalPages'] || abs($i - $faqPagination['currentPage']) <= 2): ?>
					<a href="<?php echo BASE_URL; ?>/public/index.php?page=qna&tab=faq&qna_page=<?php echo $i; ?><?php echo $categoryParam; ?>"
					   class="px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
						<?php echo $i; ?>
					</a>
				<?php elseif ($i === 2 || $i === $faqPagination['totalPages'] - 1): ?>
					<span class="px-3 py-2 text-gray-400">...</span>
				<?php endif; ?>
			<?php endfor; ?>
		</div>

		<?php if ($faqPagination['currentPage'] < $faqPagination['totalPages']): ?>
			<a href="<?php echo BASE_URL; ?>/public/index.php?page=qna&tab=faq&qna_page=<?php echo $faqPagination['currentPage'] + 1; ?><?php echo $categoryParam; ?>"
			   class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
				Trang sau <i class="fas fa-chevron-right text-sm"></i>
			</a>
		<?php endif; ?>
	</nav>
<?php endif; ?>

<style>
	@keyframes fadeIn {
		from { opacity: 0; transform: translateY(10px); }
		to { opacity: 1; transform: translateY(0); }
	}
	.animate-fade-in {
		animation: fadeIn 0.5s ease-out;
	}
	/* Lazy loading placeholder */
	img.lazyload {
		background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
		background-size: 200% 100%;
		animation: loading 1.5s infinite;
	}
	@keyframes loading {
		0% { background-position: 200% 0; }
		100% { background-position: -200% 0; }
	}
</style>

<script src="https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.js"></script>
<script>
	(function() {
		const triggers = document.querySelectorAll('.image-zoom-trigger');
		const lightbox = document.createElement('div');
		lightbox.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-black/80 p-4';
		lightbox.innerHTML = `
			<div class="absolute inset-0" data-lightbox-close></div>
			<div class="relative max-w-5xl max-h-full w-full flex flex-col items-center gap-3">
				<button type="button" class="absolute -top-3 -right-3 h-10 w-10 rounded-full bg-white text-gray-900 shadow-lg flex items-center justify-center" data-lightbox-close aria-label="Đóng ảnh">
					<i class="fas fa-times"></i>
				</button>
				<img data-lightbox-image class="max-h-[85vh] w-auto max-w-full rounded-xl bg-white shadow-2xl object-contain" alt="" />
				<p data-lightbox-caption class="text-sm text-gray-200 text-center max-w-3xl"></p>
			</div>
		`;
		document.body.appendChild(lightbox);

		const image = lightbox.querySelector('[data-lightbox-image]');
		const caption = lightbox.querySelector('[data-lightbox-caption]');
		const open = (src, alt) => {
			image.src = src;
			image.alt = alt || '';
			caption.textContent = alt || '';
			lightbox.classList.remove('hidden');
			lightbox.classList.add('flex');
			document.body.classList.add('overflow-hidden');
		};
		const close = () => {
			lightbox.classList.add('hidden');
			lightbox.classList.remove('flex');
			image.src = '';
			caption.textContent = '';
			document.body.classList.remove('overflow-hidden');
		};

		triggers.forEach((trigger) => {
			trigger.addEventListener('click', () => {
				open(trigger.dataset.lightboxSrc || '', trigger.dataset.lightboxAlt || '');
			});
		});

		lightbox.addEventListener('click', (event) => {
			if (event.target.hasAttribute('data-lightbox-close')) {
				close();
			}
		});

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && !lightbox.classList.contains('hidden')) {
				close();
			}
		});
	})();
</script>
