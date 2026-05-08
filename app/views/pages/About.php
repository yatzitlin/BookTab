<?php
$aboutPage = isset($aboutPage) && is_array($aboutPage) ? $aboutPage : null;
?>

<style>
.prose { font-family: "Be Vietnam Pro", sans-serif; font-size: 16px; line-height: 1.75; color: #374151; }
.prose h1 { font-size: 2em; font-weight: 800; line-height: 1.25; margin-top: 0; margin-bottom: 0.5em; color: #111827; }
.prose h2 { font-size: 1.5em; font-weight: 700; line-height: 1.333; margin-top: 1.5em; margin-bottom: 0.75em; padding-bottom: 0.3em; border-bottom: 1px solid #e5e7eb; color: #111827; }
.prose h3 { font-size: 1.25em; font-weight: 600; line-height: 1.6; margin-top: 1.25em; margin-bottom: 0.5em; color: #111827; }
.prose p { margin-top: 0.75em; margin-bottom: 0.75em; }
.prose ul, .prose ol { margin-top: 0.75em; margin-bottom: 0.75em; padding-left: 1.5em; }
.prose ul { list-style-type: disc; }
.prose ol { list-style-type: decimal; }
.prose li { margin-top: 0.25em; margin-bottom: 0.25em; }
.prose blockquote { margin-top: 1em; margin-bottom: 1em; padding-left: 1em; border-left: 4px solid #ef4444; color: #6b7280; font-style: italic; }
.prose a { color: #ef4444; text-decoration: underline; }
.prose strong { font-weight: 600; }
.prose img { max-width: 100%; height: auto; }
.prose table { border-collapse: collapse; width: 100%; }
.prose table th, .prose table td { border: 1px solid #d1d5db; padding: 0.5em 0.75em; }
.prose .not-prose { color: inherit; font-size: inherit; font-weight: inherit; line-height: inherit; margin: inherit; padding: inherit; border: inherit; font-style: inherit; text-decoration: inherit; }
</style>

<?php if (!$aboutPage || empty($aboutPage['noi_dung'])): ?>
    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center text-gray-500">
        Nội dung giới thiệu đang được cập nhật. Vui lòng quay lại sau.
    </div>
<?php else: ?>
    <article class="prose prose-lg max-w-none">
        <?php echo $aboutPage['noi_dung']; ?>
    </article>
<?php endif; ?>
