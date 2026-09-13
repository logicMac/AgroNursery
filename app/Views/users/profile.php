<?php require __DIR__ . '/../layouts/main.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">My Profile</h1>
            <p class="text-sm text-slate-500 mt-0.5">Update your photo and account details.</p>
        </div>
        <a href="<?= url('dashboard') ?>" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-sm font-medium transition duration-150">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                <i data-lucide="user" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800">Profile Settings</h2>
                <p class="text-xs text-slate-500">Update your information</p>
            </div>
        </div>
        <form action="<?= url('profile/update') ?>" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">
            <?= \App\Core\Csrf::field() ?>

            <!-- Photo -->
            <div class="flex items-center gap-4">
                <div class="h-20 w-20 rounded-full bg-slate-100 border-2 border-mint-200 flex items-center justify-center overflow-hidden shrink-0">
                    <?php if (!empty($user['profile_photo'])): ?>
                    <img src="<?= url($user['profile_photo']) ?>" alt="Profile" class="h-full w-full object-cover" id="currentPhoto">
                    <?php else: ?>
                    <i data-lucide="user" class="w-8 h-8 text-slate-400" id="currentPhotoIcon"></i>
                    <img id="currentPhoto" class="hidden h-full w-full object-cover" alt="Profile">
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" onchange="previewProfilePhoto(this)" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-mint-50 file:text-mint-700 hover:file:bg-mint-100 file:cursor-pointer">
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG or GIF. Max 2MB. Shown in the top-right header.</p>
                </div>
            </div>

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                <input type="text" name="name" value="<?= e($user['name']) ?>" required class="w-full rounded-lg border border-gray-300 focus:border-mint-500 focus:ring-0 px-4 py-2.5 text-sm">
            </div>

            <!-- Email (read-only) -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                <input type="email" value="<?= e($user['email']) ?>" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 text-slate-500 px-4 py-2.5 text-sm">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">New Password <span class="text-xs text-slate-400 font-normal">(leave blank to keep current)</span></label>
                <input type="password" name="password" minlength="8" class="w-full rounded-lg border border-gray-300 focus:border-mint-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Min. 8 characters">
            </div>

            <button type="submit" class="w-full bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition duration-200">
                <i data-lucide="save" class="w-4 h-4"></i> Save Profile
            </button>
        </form>
    </div>
</div>

<script>
function previewProfilePhoto(input) {
    const preview = document.getElementById('currentPhoto');
    const icon = document.getElementById('currentPhotoIcon');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (icon) icon.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
