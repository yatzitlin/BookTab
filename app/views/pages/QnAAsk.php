<?php
$qnaCategories = isset($qnaCategories) && is_array($qnaCategories) ? $qnaCategories : [];
$formError = $_SESSION['qna_form_error'] ?? '';
unset($_SESSION['qna_form_error']);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />

<section class="max-w-3xl mx-auto">
	<div class="mb-8 rounded-2xl bg-gradient-to-r from-gray-900 to-gray-800 text-white p-8 md:p-10 shadow-lg">
		<p class="text-red-400 font-semibold uppercase tracking-wider text-sm mb-2">Hỏi/đáp</p>
		<h1 class="text-3xl md:text-4xl font-bold mb-4">Đặt câu hỏi mới</h1>
		<p class="text-gray-300">
			Trang này chỉ dành cho tài khoản đã đăng nhập. Khách truy cập chỉ có thể xem FAQ ở trang con trước đó.
		</p>
	</div>

	<?php if (!empty($formError)): ?>
		<div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 animate-fade-in">
			<?php echo htmlspecialchars($formError, ENT_QUOTES, 'UTF-8'); ?>
		</div>
	<?php endif; ?>

	<div id="clientValidationErrors" class="mb-6 hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 animate-fade-in">
		<p class="font-semibold text-red-700 mb-2">Vui lòng sửa các lỗi sau:</p>
		<ul id="errorList" class="list-disc list-inside text-sm text-red-700"></ul>
	</div>

	<div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
		<form id="qnaAskForm" action="<?php echo BASE_URL; ?>/public/index.php?page=qna_ask" method="POST" enctype="multipart/form-data" class="space-y-5" novalidate>
			<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

			<div class="animate-fade-in" style="animation-delay: 50ms;">
				<label for="ma_loai" class="block text-sm font-semibold text-gray-900 mb-2">Chủ đề <span class="text-red-500">*</span></label>
				<select id="ma_loai" name="ma_loai" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition" data-error="">
					<option value="">-- Chọn chủ đề --</option>
					<?php foreach ($qnaCategories as $category): ?>
						<option value="<?php echo (int)$category['ma_loai']; ?>"><?php echo htmlspecialchars($category['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></option>
					<?php endforeach; ?>
				</select>
				<span class="errorMsg text-xs text-red-600 mt-1 hidden"></span>
			</div>

			<div class="animate-fade-in" style="animation-delay: 100ms;">
				<label for="ten_cau_hoi" class="block text-sm font-semibold text-gray-900 mb-2">Nội dung câu hỏi <span class="text-red-500">*</span></label>
				<textarea id="ten_cau_hoi" name="ten_cau_hoi" rows="6" required minlength="10" maxlength="255" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition" placeholder="Nhập câu hỏi của bạn (tối thiểu 10 ký tự)..." data-error=""></textarea>
				<div class="flex justify-between mt-1">
					<span class="errorMsg text-xs text-red-600 hidden"></span>
					<span id="charCount" class="text-xs text-gray-500">0/255</span>
				</div>
			</div>

			<div class="animate-fade-in" style="animation-delay: 150ms;">
				<label for="images" class="block text-sm font-semibold text-gray-900 mb-2">Đính kèm ảnh (tối đa 5 ảnh, mỗi ảnh tối đa 3MB)</label>
				<div id="imageDropArea" class="border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition p-6 text-center cursor-pointer">
					<i class="fas fa-cloud-arrow-up text-4xl text-gray-400 mb-3 block"></i>
					<p class="text-gray-700 font-medium mb-1">Kéo thả ảnh vào đây hoặc nhấp để chọn</p>
					<p class="text-xs text-gray-500">Hỗ trợ: PNG, JPEG, WebP (tối đa 3MB/ảnh)</p>
					<button type="button" id="chooseImagesBtn" class="mt-4 inline-flex items-center justify-center px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
						<i class="fas fa-folder-open mr-2"></i> Chọn ảnh
					</button>
				</div>
				<input id="images" name="images[]" type="file" multiple accept="image/png,image/jpeg,image/webp" class="hidden" />
				<div id="imagePreviewList" class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3"></div>
				<span id="imagesError" class="errorMsg text-xs text-red-600 mt-2 hidden block"></span>
			</div>

			<div class="flex flex-wrap gap-3 animate-fade-in" style="animation-delay: 200ms;">
				<button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
					<i class="fas fa-paper-plane mr-2"></i> Gửi câu hỏi
				</button>
				<a href="<?php echo BASE_URL; ?>/public/index.php?page=qna" class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
					Quay lại hỏi đáp
				</a>
			</div>
		</form>
	</div>
</section>

<style>
	@keyframes fadeIn {
		from { opacity: 0; transform: translateY(10px); }
		to { opacity: 1; transform: translateY(0); }
	}
	.animate-fade-in {
		animation: fadeIn 0.5s ease-out forwards;
		opacity: 0;
	}

	/* Dropzone styling */
	.dropzone {
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.dropzone .dz-message {
		text-align: center;
		pointer-events: auto;
	}
	.dropzone .dz-preview {
		margin: 10px;
		width: 150px;
		height: 150px;
		display: inline-block;
	}
	.dropzone .dz-preview .dz-image {
		border-radius: 6px;
		overflow: hidden;
	}
	.dropzone .dz-preview .dz-details {
		background: rgba(0, 0, 0, 0.6);
		color: white;
		padding: 5px;
		font-size: 12px;
	}
	.dropzone .dz-preview .dz-progress {
		display: block;
		height: 4px;
		background: rgba(255, 255, 255, 0.3);
	}
	.dropzone .dz-preview .dz-error-message {
		color: #d32f2f;
		background: #ffebee;
		border-radius: 4px;
		padding: 4px 8px;
		margin-top: 4px;
		font-size: 12px;
	}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const form = document.getElementById('qnaAskForm');
	const textArea = document.getElementById('ten_cau_hoi');
	const imagesInput = document.getElementById('images');
	const imageDropArea = document.getElementById('imageDropArea');
	const chooseImagesBtn = document.getElementById('chooseImagesBtn');
	const imagePreviewList = document.getElementById('imagePreviewList');
	const charCount = document.getElementById('charCount');
	const errorContainer = document.getElementById('clientValidationErrors');
	const errorList = document.getElementById('errorList');
	const imagesError = document.getElementById('imagesError');
	const allowedTypes = ['image/png', 'image/jpeg', 'image/webp'];
	const maxFiles = 5;
	const maxSize = 3 * 1024 * 1024;
	const fileStore = new DataTransfer();

	// Update character count
	textArea.addEventListener('input', function() {
		charCount.textContent = this.value.length + '/255';
	});

	function formatSize(bytes) {
		if (bytes < 1024) return bytes + ' B';
		if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
		return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
	}

	function renderPreviews() {
		imagePreviewList.innerHTML = '';
		Array.from(imagesInput.files).forEach((file, index) => {
			const item = document.createElement('div');
			item.className = 'relative overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm';
			item.innerHTML = `
				<div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
					<img class="w-full h-full object-cover" alt="${file.name}">
				</div>
				<div class="p-2 text-xs text-gray-600">
					<div class="truncate font-medium text-gray-800">${file.name}</div>
					<div>${formatSize(file.size)}</div>
				</div>
				<button type="button" class="absolute top-2 right-2 h-7 w-7 rounded-full bg-black/70 text-white text-sm">×</button>
			`;
			const img = item.querySelector('img');
			img.src = URL.createObjectURL(file);
			item.querySelector('button').addEventListener('click', function() {
				const nextStore = new DataTransfer();
				Array.from(imagesInput.files).forEach((currentFile, currentIndex) => {
					if (currentIndex !== index) {
						nextStore.items.add(currentFile);
					}
				});
				imagesInput.files = nextStore.files;
				fileStore.items.clear();
				Array.from(nextStore.files).forEach(file => fileStore.items.add(file));
				renderPreviews();
			});
			imagePreviewList.appendChild(item);
		});
	}

	function syncSelectedFiles(fileList) {
		const incomingFiles = Array.from(fileList || []);
		const validationErrors = [];

		incomingFiles.forEach((file) => {
			if (!allowedTypes.includes(file.type)) {
				validationErrors.push(`Ảnh '${file.name}' không phải định dạng hỗ trợ (PNG, JPEG, WebP).`);
				return;
			}
			if (file.size > maxSize) {
				validationErrors.push(`Ảnh '${file.name}' vượt quá 3MB.`);
				return;
			}
			if (fileStore.files.length >= maxFiles) {
				validationErrors.push('Không được tải lên quá 5 ảnh.');
				return;
			}
			fileStore.items.add(file);
		});

		imagesInput.files = fileStore.files;
		renderPreviews();

		if (validationErrors.length > 0) {
			imagesError.textContent = validationErrors[0];
			imagesError.classList.remove('hidden');
		} else {
			imagesError.textContent = '';
			imagesError.classList.add('hidden');
		}
	}

	chooseImagesBtn.addEventListener('click', function() {
		imagesInput.click();
	});

	imageDropArea.addEventListener('click', function(e) {
		if (e.target === chooseImagesBtn) {
			return;
		}
		imagesInput.click();
	});

	imagesInput.addEventListener('change', function() {
		fileStore.items.clear();
		syncSelectedFiles(this.files);
	});

	imageDropArea.addEventListener('dragover', function(e) {
		e.preventDefault();
		imageDropArea.classList.add('border-red-400', 'bg-red-50');
	});

	imageDropArea.addEventListener('dragleave', function() {
		imageDropArea.classList.remove('border-red-400', 'bg-red-50');
	});

	imageDropArea.addEventListener('drop', function(e) {
		e.preventDefault();
		imageDropArea.classList.remove('border-red-400', 'bg-red-50');
		syncSelectedFiles(e.dataTransfer.files);
	});

	// Form validation on submit
	form.addEventListener('submit', function(e) {
		e.preventDefault();
		errorList.innerHTML = '';
		errorContainer.classList.add('hidden');
		imagesError.classList.add('hidden');
		imagesError.textContent = '';
		let isValid = true;
		const errors = [];

		// Validate category
		const category = document.getElementById('ma_loai').value;
		if (!category) {
			errors.push('Vui lòng chọn chủ đề');
			isValid = false;
		}

		// Validate question
		const question = textArea.value.trim();
		if (question.length < 10) {
			errors.push('Câu hỏi phải có ít nhất 10 ký tự');
			isValid = false;
		}
		if (question.length > 255) {
			errors.push('Câu hỏi không được vượt quá 255 ký tự');
			isValid = false;
		}

		// Validate images via native file input
		const files = Array.from(imagesInput.files || []);
		if (files.length > maxFiles) {
			errors.push('Không được tải lên quá 5 ảnh');
			isValid = false;
		}
		files.forEach(function(file) {
			if (!allowedTypes.includes(file.type)) {
				errors.push(`Ảnh '${file.name}' không phải định dạng hỗ trợ (PNG, JPEG, WebP).`);
				isValid = false;
			}
			if (file.size > maxSize) {
				errors.push(`Ảnh '${file.name}' vượt quá 3MB.`);
				isValid = false;
			}
		});

		if (!isValid) {
			errors.forEach(err => {
				const li = document.createElement('li');
				li.textContent = err;
				errorList.appendChild(li);
			});
			errorContainer.classList.remove('hidden');
		} else {
			form.submit();
		}
	});

	if (imagesInput.files.length) {
		syncSelectedFiles(imagesInput.files);
	}
});
</script>
