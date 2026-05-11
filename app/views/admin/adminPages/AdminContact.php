<div class="container-fluid py-4">

    <h1 class="fw-bold mb-4">
        Quản lý liên hệ
    </h1>

    <div class="card shadow border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Họ tên</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Trạng thái</th>
                            <th class="px-4 py-3">Ngày gửi</th>
                            <th class="px-4 py-3">Hành động</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if (!empty($contacts)): ?>

                        <?php foreach($contacts as $contact): ?>

                            <tr>

                                <!-- ID -->
                                <td class="px-4 py-3">
                                    <?= $contact['ma_lien_he'] ?>
                                </td>

                                <!-- Họ tên -->
                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($contact['ho_va_ten']) ?>
                                </td>

                                <!-- Email -->
                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($contact['email']) ?>
                                </td>

                                <!-- Trạng thái -->
                                <td class="px-4 py-3">

                                    <?php if($contact['trang_thai'] == 'unread'): ?>

                                        <span class="badge bg-danger">Chưa đọc</span>

                                    <?php elseif($contact['trang_thai'] == 'read'): ?>

                                        <span class="badge bg-warning text-dark">Đã đọc</span>

                                    <?php else: ?>

                                        <span class="badge bg-success">Đã phản hồi</span>

                                    <?php endif; ?>

                                </td>

                                <!-- Ngày gửi -->
                                <td class="px-4 py-3">
                                    <?= $contact['thoi_gian_tao'] ?>
                                </td>

                                <!-- Hành động -->
                                <td class="px-4 py-3">

                                    <div class="d-flex gap-2">

                                        <!-- Xem -->
                                        <button
                                            class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-<?= $contact['ma_lien_he'] ?>">

                                            Xem

                                        </button>

                                        <!-- Xóa -->
                                        <a
                                            href="index.php?page=admin&action=deleteContact&id=<?= $contact['ma_lien_he'] ?>"
                                            onclick="return confirm('Xóa liên hệ này?')"
                                            class="btn btn-danger btn-sm">

                                            Xóa

                                        </a>

                                    </div>

                                </td>

                            </tr>

                            <!-- MODAL -->
                            <div
                                class="modal fade"
                                id="modal-<?= $contact['ma_lien_he'] ?>"
                                tabindex="-1">

                                <div class="modal-dialog modal-lg modal-dialog-centered">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title fw-bold">
                                                Chi tiết liên hệ
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                        </div>

                                        <div class="modal-body">

                                            <p><b>Họ tên:</b> <?= htmlspecialchars($contact['ho_va_ten']) ?></p>
                                            <p><b>Email:</b> <?= htmlspecialchars($contact['email']) ?></p>

                                            <p class="mt-3"><b>Nội dung:</b></p>

                                            <div class="bg-light p-3 border rounded">
                                                <?= nl2br(htmlspecialchars($contact['noi_dung'])) ?>
                                            </div>

                                            <!-- UPDATE STATUS -->
                                            <form
                                                method="POST"
                                                action="index.php?page=admin&action=updateContactStatus"
                                                class="mt-3 d-flex gap-2">

                                                <input type="hidden" name="id" value="<?= $contact['ma_lien_he'] ?>">

                                                <select name="status" class="form-select w-auto">

                                                    <option value="unread" <?= $contact['trang_thai']=='unread'?'selected':'' ?>>
                                                        Chưa đọc
                                                    </option>

                                                    <option value="read" <?= $contact['trang_thai']=='read'?'selected':'' ?>>
                                                        Đã đọc
                                                    </option>

                                                    <option value="replied" <?= $contact['trang_thai']=='replied'?'selected':'' ?>>
                                                        Đã phản hồi
                                                    </option>

                                                </select>

                                                <button class="btn btn-success">
                                                    Cập nhật
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Không có liên hệ nào
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>