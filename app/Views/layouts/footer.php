            </main>
        </div>
    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');
        const collapseBtn = document.getElementById('sidebarCollapse');

        function openSidebar() {
            sidebar.classList.remove('hidden');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('hidden');
            overlay.classList.add('hidden');
        }

        if (toggle) toggle.addEventListener('click', () => sidebar.classList.contains('hidden') ? openSidebar() : closeSidebar());
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        if (collapseBtn) collapseBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        lucide.createIcons();
    </script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
