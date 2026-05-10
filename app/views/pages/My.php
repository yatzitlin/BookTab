<?php
$qnaItems = isset($qnaItems) && is_array($qnaItems) ? $qnaItems : [];
$qnaPagination = isset($qnaPagination) && is_array($qnaPagination) ? $qnaPagination : [
	'currentPage' => 1,
	'totalPages' => 1,
	'totalItems' => 0,
	'itemsPerPage' => 10
];

$askUrl = BASE_URL . '/public/index.php?page=qna&tab=ask';
$faqUrl = BASE_URL . '/public/index.php?page=qna&tab=faq';
$qnaBaseUrl = BASE_URL . '/public/index.php?page=qna';
$paginationBase = BASE_URL . '/public/index.php?page=qna&tab=my';

$successMessage = $_SESSION['qna_form_success'] ?? '';
unset($_SESSION['qna_form_success']);

function myStatusBadge($status) {
	switch ($status) {
		case 'cho_duyet':
			return '<span class="text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1 rounded-full">Chờ duyệt</span>';
		case 'chua_tra_loi':
			return '<span class="text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200 px-3 py-1 rounded-full">Chưa trả lời</span>';
		case 'da_tra_loi':
			return '<span class="text-xs font-semibold bg-green-50 text-green-700 border border-green-200 px-3 py-1 rounded-full">Đã trả lời</span>';
		case 'da_an':
			return '<span class="text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200 px-3 py-1 rounded-full">Đã ẩn</span>';
		default:
			return '';
	}
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.css" />

<section class="mb-8">
	<div class="rounded-2xl bg-gray-900 text-white p-8 md:p-10">
		<p class="text-red-400 font-semibold uppercase tracking-wider text-sm mb-2">Câu hỏi của tôi</p>
		<h1 class="text-3xl md:text-4xl font-bold mb-4">Quản lý câu hỏi của bạn</h1>
		<p class="text-gray-300 max-w-3xl">
			Xin hãy theo dõi trạng thái các câu hỏi bạn đã gửi. Chúng tôi sẽ cố gắng phản hồi trong thời gian sớm nhất.
		</p>
		<div class="mt-6 flex flex-wrap gap-3">
			<a href="<?php echo $faqUrl; ?>" class="inline-flex items-center px-5 py-3 rounded-full border border-gray-600 text-gray-200 hover:bg-gray-800 transition">
				<i class="fas fa-book-open mr-2"></i> FAQ
			</a>
			<a href="<?php echo $qnaBaseUrl; ?>" class="inline-flex items-center px-5 py-3 rounded-full border border-gray-600 text-gray-200 hover:bg-gray-800 transition">
				<i class="fas fa-comments mr-2"></i> Hỏi đáp
			</a>
			<a href="<?php echo $askUrl; ?>" class="inline-flex items-center px-5 py-3 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600 transition">
				<i class="fas fa-pen-to-square mr-2"></i> Đặt câu hỏi
			</a>
		</div>
	</div>
</section>

<?php if (!empty($successMessage)): ?>
	<div class="mb-8 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 animate-fade-in">
		<?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
	</div>
<?php endif; ?>

<section>
	<div class="space-y-4">
		<?php if (empty($qnaItems)): ?>
			<div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-600">
				<i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
				<p class="text-lg font-medium mb-2">Bạn chưa gửi câu hỏi nào</p>
				<p class="text-sm text-gray-500 mb-4">Hãy đặt câu hỏi để nhận được hỗ trợ từ đội ngũ BookTab.</p>
				<a href="<?php echo $askUrl; ?>" class="inline-flex items-center px-5 py-2.5 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600 transition">
					<i class="fas fa-pen-to-square mr-2"></i> Đặt câu hỏi ngay bây giờ!
				</a>
			</div>
		<?php else: ?>
			<?php foreach ($qnaItems as $item): ?>
				<article class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm animate-fade-in hover:shadow-md transition">
					<div class="flex flex-wrap items-center gap-3 mb-3">
						<span class="text-xs font-semibold bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-full">
							<?php echo htmlspecialchars($item['ten_loai'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
						</span>
						<?php echo myStatusBadge($item['trang_thai'] ?? ''); ?>
						<?php if (!empty($item['ngay_tao'])): ?>
							<span class="text-xs text-gray-500">
								Gửi: <?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($item['ngay_tao'])), ENT_QUOTES, 'UTF-8'); ?>
							</span>
						<?php endif; ?>
					</div>

					<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-2">
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

					<?php if (($item['trang_thai'] ?? '') === 'cho_duyet'): ?>
						<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-800 text-sm">
							<i class="fas fa-clock mr-1"></i>
							Câu hỏi đang chờ quản trị viên duyệt. Vui lòng kiên nhẫn chờ đợi.
						</div>
					<?php elseif (($item['trang_thai'] ?? '') === 'chua_tra_loi'): ?>
						<div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-orange-800 text-sm">
							<i class="fas fa-hourglass-half mr-1"></i>
							Câu hỏi đã được duyệt và đang chờ quản trị viên trả lời.
						</div>
					<?php elseif (($item['trang_thai'] ?? '') === 'da_an'): ?>
						<div class="bg-gray-100 border border-gray-200 rounded-lg p-4 text-gray-500 text-sm">
							<i class="fas fa-eye-slash mr-1"></i>
							Câu hỏi này đã bị ẩn bởi quản trị viên.
						</div>
					<?php elseif (!empty($item['cau_tra_loi'])): ?>
						<div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-gray-700 mb-3 flex flex-col gap-4">
							<?php if (!empty($item['ngay_dang'])): ?>
								<p class="text-xs text-gray-400">Ngày trả lời: <?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($item['ngay_dang'])), ENT_QUOTES, 'UTF-8'); ?></p>
							<?php endif; ?>
							<div class="answer-content prose max-w-none">
								<?php echo $item['cau_tra_loi']; ?>
							</div>

							<?php if (!empty($item['admin_ho_va_ten_dem']) || !empty($item['admin_ten'])): ?>
								<div class="mt-auto flex justify-end">
									<p class="text-sm text-gray-500 text-right">
										<span class="block font-semibold text-gray-700">Người trả lời</span>
										<?php echo htmlspecialchars(trim(($item['admin_ho_va_ten_dem'] ?? '') . ' ' . ($item['admin_ten'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>
									</p>
								</div>
							<?php endif; ?>

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
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>

<?php if ($qnaPagination['totalPages'] > 1): ?>
	<nav class="mt-8 flex items-center justify-center gap-2">
		<?php if ($qnaPagination['currentPage'] > 1): ?>
			<a href="<?php echo $paginationBase; ?>&p=<?php echo $qnaPagination['currentPage'] - 1; ?>"
			   class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
				<i class="fas fa-chevron-left text-sm"></i> <span class="hidden sm:inline">Trang trước</span>
			</a>
		<?php else: ?>
			<span class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">
				<i class="fas fa-chevron-left text-sm"></i> <span class="hidden sm:inline">Trang trước</span>
			</span>
		<?php endif; ?>

		<div class="flex gap-1">
			<?php for ($i = 1; $i <= $qnaPagination['totalPages']; $i++): ?>
				<?php if ($i === $qnaPagination['currentPage']): ?>
					<span class="px-3 py-2 rounded-lg bg-red-500 text-white font-semibold">
						<?php echo $i; ?>
					</span>
				<?php elseif ($i === 1 || $i === $qnaPagination['totalPages'] || abs($i - $qnaPagination['currentPage']) <= 2): ?>
					<a href="<?php echo $paginationBase; ?>&p=<?php echo $i; ?>"
					   class="px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
						<?php echo $i; ?>
					</a>
				<?php elseif ($i === 2 || $i === $qnaPagination['totalPages'] - 1): ?>
					<span class="px-3 py-2 text-gray-400">...</span>
				<?php endif; ?>
			<?php endfor; ?>
		</div>

		<?php if ($qnaPagination['currentPage'] < $qnaPagination['totalPages']): ?>
			<a href="<?php echo $paginationBase; ?>&p=<?php echo $qnaPagination['currentPage'] + 1; ?>"
			   class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
				<span class="hidden sm:inline">Trang sau</span> <i class="fas fa-chevron-right text-sm"></i>
			</a>
		<?php else: ?>
			<span class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">
				<span class="hidden sm:inline">Trang sau</span> <i class="fas fa-chevron-right text-sm"></i>
			</span>
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
