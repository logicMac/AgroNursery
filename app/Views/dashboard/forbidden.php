<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="max-w-xl mx-auto text-center py-20">
    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
        <i data-lucide="lock" class="w-8 h-8"></i>
    </div>
    <h1 class="text-2xl font-bold text-slate-800 mb-2">Access Denied</h1>
    <p class="text-slate-500 mb-6"><?= e($message ?? 'You do not have permission to view this page.') ?></p>
    <a href="<?= url('dashboard') ?>" class="bg-mint-600 hover:bg-mint-700 text-white px-6 py-2 rounded-lg text-sm font-medium">Return to Dashboard</a>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
