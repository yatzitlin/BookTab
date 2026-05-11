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
<script src="https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.js"></script>
<script>
	(function() {
		const triggers = document.querySelectorAll('.image-zoom-trigger');
		const lb = document.createElement('div');
		lb.id = 'qna-lb';
		lb.style.cssText = 'position:fixed;inset:0;z-index:9999;display:none;background:rgba(0,0,0,0.93);';
		lb.innerHTML = `
			<div id="lb-stage" style="position:absolute;inset:0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
				<img id="lb-img" draggable="false" alt=""
					style="max-width:100%;max-height:100%;object-fit:contain;transform-origin:center;will-change:transform;user-select:none;-webkit-user-drag:none;" />
			</div>
			<button id="lb-close" aria-label="Đóng"
				style="position:absolute;top:16px;right:16px;z-index:10;width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,0.13);border:1px solid rgba(255,255,255,0.22);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
			</button>
			<div id="lb-hint" style="position:absolute;top:16px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,0.45);font-size:12px;pointer-events:none;white-space:nowrap;transition:opacity .6s;">
				Scroll để zoom &middot; Kéo để di chuyển &middot; Double-click để phóng to
			</div>
			<p id="lb-cap" style="position:absolute;bottom:76px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,0.65);font-size:13px;max-width:80%;pointer-events:none;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></p>
			<div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);display:flex;align-items:center;gap:6px;background:rgba(255,255,255,0.11);backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,0.18);border-radius:40px;padding:7px 12px;">
				<button id="lb-out" title="Thu nhỏ (-)" style="width:34px;height:34px;border-radius:50%;background:0;border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
				</button>
				<button id="lb-pct" title="Đặt lại (0)" style="min-width:54px;height:34px;border-radius:20px;background:0;border:none;color:#fff;cursor:pointer;font-size:13px;font-weight:700;">100%</button>
				<button id="lb-in" title="Phóng to (+)" style="width:34px;height:34px;border-radius:50%;background:0;border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
				</button>
			</div>
		`;
		document.body.appendChild(lb);

		const stage  = lb.querySelector('#lb-stage');
		const img    = lb.querySelector('#lb-img');
		const cap    = lb.querySelector('#lb-cap');
		const pctBtn = lb.querySelector('#lb-pct');
		const hint   = lb.querySelector('#lb-hint');

		let s = 1, tx = 0, ty = 0;
		let drag = false, dsx = 0, dsy = 0, dtx = 0, dty = 0;
		let lastDist = null, hintTimer;
		const MIN = 0.5, MAX = 5;

		function apply(anim) {
			img.style.transition = anim ? 'transform .2s ease' : 'none';
			img.style.transform  = `translate(${tx}px,${ty}px) scale(${s})`;
			img.style.cursor     = s > 1 ? (drag ? 'grabbing' : 'grab') : 'default';
			pctBtn.textContent   = Math.round(s * 100) + '%';
		}

		function clamp() {
			if (s <= 1) { tx = 0; ty = 0; return; }
			const r = stage.getBoundingClientRect();
			const mx = r.width  * (s - 1) / 2;
			const my = r.height * (s - 1) / 2;
			tx = Math.max(-mx, Math.min(mx, tx));
			ty = Math.max(-my, Math.min(my, ty));
		}

		function reset(anim) { s = 1; tx = 0; ty = 0; apply(anim); }

		function zoomAt(ns, px, py) {
			ns = Math.min(MAX, Math.max(MIN, ns));
			const r  = stage.getBoundingClientRect();
			const ox = px - (r.left + r.width  / 2);
			const oy = py - (r.top  + r.height / 2);
			const rt = ns / s;
			tx = ox * (1 - rt) + tx * rt;
			ty = oy * (1 - rt) + ty * rt;
			s  = ns; clamp(); apply(false);
		}

		function cZoom(ns) {
			const r = stage.getBoundingClientRect();
			zoomAt(ns, r.left + r.width / 2, r.top + r.height / 2);
		}

		const open = (src, alt) => {
			reset(false);
			img.src = src; img.alt = alt || '';
			cap.textContent = alt || '';
			lb.style.display = 'block';
			document.body.style.overflow = 'hidden';
			hint.style.opacity = '1';
			clearTimeout(hintTimer);
			hintTimer = setTimeout(() => hint.style.opacity = '0', 3000);
		};

		const close = () => {
			lb.style.display = 'none';
			img.src = ''; reset(false);
			document.body.style.overflow = '';
		};

		lb.querySelector('#lb-close').addEventListener('click', close);
		stage.addEventListener('click', (e) => { if (e.target === stage) close(); });

		lb.querySelector('#lb-in') .addEventListener('click', () => { cZoom(s * 1.35); apply(true); });
		lb.querySelector('#lb-out').addEventListener('click', () => { cZoom(s / 1.35); apply(true); });
		lb.querySelector('#lb-pct').addEventListener('click', () => reset(true));

		img.addEventListener('dblclick', (e) => {
			if (s > 1) reset(true); else { zoomAt(2.5, e.clientX, e.clientY); apply(true); }
		});

		stage.addEventListener('wheel', (e) => {
			e.preventDefault();
			zoomAt(s * (e.deltaY < 0 ? 1.12 : 1 / 1.12), e.clientX, e.clientY);
		}, { passive: false });

		stage.addEventListener('mousedown', (e) => {
			if (s <= 1 || e.button !== 0) return;
			e.preventDefault();
			drag = true; dsx = e.clientX; dsy = e.clientY; dtx = tx; dty = ty; apply(false);
		});
		window.addEventListener('mousemove', (e) => {
			if (!drag) return;
			tx = dtx + (e.clientX - dsx); ty = dty + (e.clientY - dsy);
			clamp(); apply(false);
		});
		window.addEventListener('mouseup', () => { if (drag) { drag = false; apply(false); } });

		stage.addEventListener('touchstart', (e) => {
			if (e.touches.length === 1 && s > 1) { dsx = e.touches[0].clientX; dsy = e.touches[0].clientY; dtx = tx; dty = ty; }
			if (e.touches.length === 2) lastDist = null;
		}, { passive: true });
		stage.addEventListener('touchmove', (e) => {
			e.preventDefault();
			if (e.touches.length === 1 && s > 1) {
				tx = dtx + (e.touches[0].clientX - dsx); ty = dty + (e.touches[0].clientY - dsy);
				clamp(); apply(false);
			}
			if (e.touches.length === 2) {
				const dist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
				const mx = (e.touches[0].clientX + e.touches[1].clientX) / 2;
				const my = (e.touches[0].clientY + e.touches[1].clientY) / 2;
				if (lastDist !== null) zoomAt(s * dist / lastDist, mx, my);
				lastDist = dist;
			}
		}, { passive: false });
		stage.addEventListener('touchend', () => { lastDist = null; });

		document.addEventListener('keydown', (e) => {
			if (lb.style.display !== 'block') return;
			if (e.key === 'Escape') close();
			if (e.key === '+' || e.key === '=') { cZoom(s * 1.25); apply(true); }
			if (e.key === '-') { cZoom(s / 1.25); apply(true); }
			if (e.key === '0') reset(true);
		});

		triggers.forEach((t) => t.addEventListener('click', () =>
			open(t.dataset.lightboxSrc || '', t.dataset.lightboxAlt || '')));
	})();
</script>
