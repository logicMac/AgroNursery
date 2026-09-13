<?php if (empty($modalId)) return; ?>
<div id="<?= e($modalId) ?>" class="modal fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-transparent transition-opacity" data-modal-close></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-mint-100 shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-page-load">
            <div class="px-6 py-4 border-b border-mint-100 bg-mint-50/50 flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-mint-600 flex items-center justify-center text-white shadow-md">
                    <i data-lucide="<?= e($modalIcon ?? 'plus') ?>" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-slate-800 leading-tight"><?= e($modalTitle ?? 'Modal') ?></h3>
                    <p class="text-xs text-slate-500 mt-0.5"><?= e($modalSubtitle ?? 'Fill in the details below') ?></p>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-md hover:bg-slate-100 transition" data-modal-close>
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <?= $modalContent ?? '' ?>
            </div>
        </div>
    </div>
</div>
