<?php
$aboutSections = isset($aboutSections) && is_array($aboutSections) ? $aboutSections : [];
$hero = !empty($aboutSections) ? $aboutSections[0] : null;
$otherSections = count($aboutSections) > 1 ? array_slice($aboutSections, 1) : [];
?>

<section class="mb-10">
    <div class="rounded-2xl bg-gradient-to-r from-red-500 to-orange-500 text-white p-8 md:p-12 shadow-lg">
        <p class="uppercase text-sm tracking-widest font-semibold mb-3">BookTab</p>
        <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-4">
            <?php echo $hero ? htmlspecialchars($hero['tieu_de'], ENT_QUOTES, 'UTF-8') : 'Chúng tôi yêu sách và công nghệ'; ?>
        </h1>
        <p class="text-red-50 max-w-3xl">
            <?php echo $hero ? htmlspecialchars($hero['mo_ta_ngan'], ENT_QUOTES, 'UTF-8') : 'BookTab được xây dựng để mang trải nghiệm mua sách trực tuyến nhanh, rõ ràng và đáng tin cậy cho mọi độc giả.'; ?>
        </p>
    </div>
</section>

<?php if ($hero && !empty($hero['noi_dung'])): ?>
<section class="mb-10 bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Tổng quan</h2>
    <p class="text-gray-700 leading-8 whitespace-pre-line">
        <?php echo htmlspecialchars($hero['noi_dung'], ENT_QUOTES, 'UTF-8'); ?>
    </p>
</section>
<?php endif; ?>

<section>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Những điều làm nên BookTab</h2>

    <?php if (empty($otherSections)): ?>
        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-gray-600">
            Nội dung giới thiệu đang được cập nhật. Vui lòng quay lại sau.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($otherSections as $section): ?>
                <article class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        <?php echo htmlspecialchars($section['tieu_de'], ENT_QUOTES, 'UTF-8'); ?>
                    </h3>

                    <?php if (!empty($section['mo_ta_ngan'])): ?>
                        <p class="text-gray-600 mb-4">
                            <?php echo htmlspecialchars($section['mo_ta_ngan'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($section['noi_dung'])): ?>
                        <p class="text-gray-700 leading-7 whitespace-pre-line">
                            <?php echo htmlspecialchars($section['noi_dung'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
