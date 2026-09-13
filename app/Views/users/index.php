<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Users</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage staff and owner accounts.</p>
    </div>
    <button type="button" data-modal-open="usersModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto"><i data-lucide="plus" class="w-4 h-4"></i> New User</button>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-slate-700"></i>
            <div>
                <h2 class="font-semibold text-slate-800">User Accounts</h2>
                <p class="text-xs text-slate-500"><?= count($users) ?> account(s)</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Name</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Email</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Role</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Status</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Last Login</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No users found</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Create a user account to get started.</p>
                        <button type="button" data-modal-open="usersModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> New User
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <span class="font-medium text-slate-800"><?= e($u['name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= e($u['email']) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($u['role_name']) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?= $u['is_active'] ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' ?>">
                            <i data-lucide="<?= $u['is_active'] ? 'check-circle' : 'minus-circle' ?>" class="w-3 h-3"></i>
                            <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= e($u['last_login'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-right">
                        <form action="<?= url('users/' . $u['id'] . '/toggle') ?>" method="POST" class="inline">
                            <?= \App\Core\Csrf::field() ?>
                            <button class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                                <i data-lucide="<?= $u['is_active'] ? 'user-x' : 'user-check' ?>" class="w-3 h-3"></i>
                                <?= $u['is_active'] ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php ob_start(); ?>
    <?php require __DIR__ . '/_form.php'; ?>
<?php
$modalContent = ob_get_clean();
$modalId = 'usersModal';
$modalTitle = 'New User';
$modalIcon = 'users';
$modalSubtitle = 'Create a system user account';
require __DIR__ . '/../components/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
