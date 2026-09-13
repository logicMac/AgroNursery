<form action="<?= url('users/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Profile Photo</label>
        <div class="flex items-center gap-3">
            <div class="h-14 w-14 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                <i data-lucide="user" class="w-6 h-6 text-slate-400" id="photoPreviewIcon"></i>
                <img id="photoPreview" class="hidden h-full w-full object-cover" alt="Preview">
            </div>
            <div class="flex-1">
                <input type="file" name="profile_photo" accept="image/*" onchange="previewPhoto(this)" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-mint-50 file:text-mint-700 hover:file:bg-mint-100 file:cursor-pointer">
                <p class="text-xs text-slate-400 mt-1">JPG, PNG or GIF. Max 2MB.</p>
            </div>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Full name">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="email@example.com">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Role <span class="text-red-500">*</span></label>
        <select name="role_id" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <?php foreach ($roles as $r): ?><option value="<?= $r['id'] ?>"><?= e($r['name']) ?></option><?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password" required minlength="8" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Min. 8 characters">
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Create User
        </button>
    </div>
</form>

<script>
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    const icon = document.getElementById('photoPreviewIcon');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
