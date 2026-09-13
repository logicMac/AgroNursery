            </main>
        </div>
    </div>

    <!-- Chatbot Widget -->
    <div id="chatbotWidget" class="fixed bottom-5 right-5 z-50">
        <!-- Chat Panel -->
        <div id="chatbotPanel" class="hidden flex-col bg-white rounded-3xl shadow-2xl border border-slate-200 mb-3 overflow-hidden" style="width: 400px; max-width: calc(100vw - 2.5rem); height: 560px; max-height: calc(100vh - 6rem);">
            <!-- Header -->
            <div class="bg-gradient-to-r from-mint-600 to-mint-700 px-5 py-4 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i data-lucide="sparkles" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white text-sm flex items-center gap-2">
                            AgroBot
                            <span class="text-[10px] bg-white/20 text-white px-1.5 py-0.5 rounded-full font-normal">AI</span>
                        </h3>
                        <p class="text-xs text-mint-50 flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 bg-mint-200 rounded-full animate-pulse"></span> Online & ready to help
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" onclick="clearChatbot()" class="text-white/70 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition" title="Clear chat">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                    <button type="button" onclick="toggleChatbot()" class="text-white/70 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition" title="Close">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div id="chatbotMessages" class="flex-1 overflow-y-auto px-4 py-5 space-y-4 bg-slate-50 scroll-smooth">
                <!-- Welcome message -->
                <div class="flex items-start gap-2.5">
                    <div class="h-8 w-8 rounded-full bg-mint-100 flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="sparkles" class="w-4 h-4 text-mint-700"></i>
                    </div>
                    <div class="bg-white rounded-2xl rounded-tl-md px-4 py-3 shadow-sm border border-slate-200 max-w-[80%]">
                        <p class="text-sm text-slate-700 leading-relaxed">Hi! I'm <span class="font-semibold text-mint-700">AgroBot</span>, your nursery AI assistant. I can help with sales, inventory, batches, quality grading, and more. What would you like to know?</p>
                    </div>
                </div>
            </div>

            <!-- Typing indicator -->
            <div id="chatbotTyping" class="hidden px-4 pb-2 bg-slate-50">
                <div class="flex items-start gap-2.5">
                    <div class="h-8 w-8 rounded-full bg-mint-100 flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="sparkles" class="w-4 h-4 text-mint-700"></i>
                    </div>
                    <div class="bg-white rounded-2xl rounded-tl-md px-4 py-3.5 shadow-sm border border-slate-200">
                        <div class="flex gap-1.5 items-center">
                            <span class="w-2 h-2 bg-mint-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></span>
                            <span class="w-2 h-2 bg-mint-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></span>
                            <span class="w-2 h-2 bg-mint-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick suggestions -->
            <div id="chatbotSuggestions" class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 flex gap-2 flex-wrap">
                <button type="button" onclick="sendQuickMsg('How do I record a sale?')" class="text-xs text-mint-700 bg-mint-50 hover:bg-mint-100 border border-mint-200 px-3 py-1.5 rounded-full transition flex items-center gap-1.5">
                    <i data-lucide="receipt" class="w-3 h-3"></i> Record a sale
                </button>
                <button type="button" onclick="sendQuickMsg('What is quality grading?')" class="text-xs text-mint-700 bg-mint-50 hover:bg-mint-100 border border-mint-200 px-3 py-1.5 rounded-full transition flex items-center gap-1.5">
                    <i data-lucide="clipboard-check" class="w-3 h-3"></i> Quality grading
                </button>
                <button type="button" onclick="sendQuickMsg('How to reduce shrinkage?')" class="text-xs text-mint-700 bg-mint-50 hover:bg-mint-100 border border-mint-200 px-3 py-1.5 rounded-full transition flex items-center gap-1.5">
                    <i data-lucide="trending-down" class="w-3 h-3"></i> Reduce shrinkage
                </button>
            </div>

            <!-- Input -->
            <div class="p-3 bg-white border-t border-slate-200 shrink-0">
                <form id="chatbotForm" onsubmit="return sendChatMessage(event)" class="flex items-center gap-2">
                    <div class="flex-1 relative">
                        <input type="text" id="chatbotInput" placeholder="Ask AgroBot anything..." class="w-full rounded-2xl border border-slate-200 focus:border-mint-500 focus:ring-1 focus:ring-mint-200 px-4 py-2.5 text-sm transition" autocomplete="off">
                    </div>
                    <button type="submit" id="chatbotSendBtn" class="bg-mint-600 hover:bg-mint-700 text-white p-2.5 rounded-2xl shadow-sm hover:shadow-md transition shrink-0">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </form>
                <p class="text-[10px] text-slate-400 text-center mt-1.5">Powered by Groq AI · Responses may take a few seconds</p>
            </div>
        </div>

        <!-- Toggle Button -->
        <button type="button" onclick="toggleChatbot()" id="chatbotToggle" class="bg-mint-600 hover:bg-mint-700 text-white h-14 w-14 rounded-full shadow-lg hover:shadow-xl transition duration-200 flex items-center justify-center group relative">
            <i data-lucide="message-circle" class="w-6 h-6 group-hover:scale-110 transition" id="chatbotToggleIcon"></i>
            <span class="absolute -top-1 -right-1 h-4 w-4 bg-mint-500 rounded-full border-2 border-white text-[9px] flex items-center justify-center text-white font-bold">AI</span>
        </button>
    </div>

    <script>
        const chatbotMessages = document.getElementById('chatbotMessages');
        const chatbotPanel = document.getElementById('chatbotPanel');
        const chatbotInput = document.getElementById('chatbotInput');
        const chatbotTyping = document.getElementById('chatbotTyping');
        const chatbotSuggestions = document.getElementById('chatbotSuggestions');
        let chatbotHistory = [];
        let chatbotOpen = false;

        function toggleChatbot() {
            chatbotOpen = !chatbotOpen;
            if (chatbotOpen) {
                chatbotPanel.classList.remove('hidden');
                chatbotPanel.classList.add('flex');
                setTimeout(() => chatbotInput.focus(), 100);
            } else {
                chatbotPanel.classList.add('hidden');
                chatbotPanel.classList.remove('flex');
            }
            lucide.createIcons();
        }

        function clearChatbot() {
            // Keep only the welcome message
            const welcome = chatbotMessages.querySelector('.flex.items-start');
            chatbotMessages.innerHTML = '';
            if (welcome) chatbotMessages.appendChild(welcome);
            chatbotHistory = [];
            chatbotSuggestions.classList.remove('hidden');
        }

        function scrollChatBottom() {
            chatbotMessages.scrollTo({ top: chatbotMessages.scrollHeight, behavior: 'smooth' });
        }

        // Strip markdown formatting from AI responses
        function stripMarkdown(text) {
            // Remove code blocks ```...```
            text = text.replace(/```[\s\S]*?```/g, (m) => {
                return m.replace(/```\w*\n?/g, '').replace(/```/g, '').trim();
            });
            // Remove inline code `code`
            text = text.replace(/`([^`]+)`/g, '$1');
            // Remove headers (###, ##, #)
            text = text.replace(/^#{1,6}\s+/gm, '');
            // Remove bold **text** or __text__
            text = text.replace(/\*\*([^*]+)\*\*/g, '$1');
            text = text.replace(/__([^_]+)__/g, '$1');
            // Remove italic *text* or _text_
            text = text.replace(/\*([^*]+)\*/g, '$1');
            text = text.replace(/_([^_]+)_/g, '$1');
            // Remove strikethrough ~~text~~
            text = text.replace(/~~([^~]+)~~/g, '$1');
            // Remove bullet list markers (-, *, +)
            text = text.replace(/^[\s]*[-*+]\s+/gm, '');
            // Remove numbered list markers (1., 2., etc.)
            text = text.replace(/^[\s]*\d+\.\s+/gm, '');
            // Remove blockquotes >
            text = text.replace(/^[\s]*>\s+/gm, '');
            // Remove horizontal rules ---
            text = text.replace(/^[\s]*[-*_]{3,}\s*$/gm, '');
            // Remove links [text](url) -> just text
            text = text.replace(/\[([^\]]+)\]\([^)]+\)/g, '$1');
            // Remove images ![alt](url)
            text = text.replace(/!\[([^\]]*)\]\([^)]+\)/g, '$1');
            // Collapse multiple blank lines
            text = text.replace(/\n{3,}/g, '\n\n');
            return text.trim();
        }

        function addMessage(role, text) {
            const isUser = role === 'user';
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-start gap-2.5 ' + (isUser ? 'flex-row-reverse' : '');

            const avatar = isUser
                ? '<div class="h-8 w-8 rounded-full bg-mint-600 flex items-center justify-center shrink-0 mt-0.5 text-white text-xs font-semibold">' + (chatbotUserInitial || 'U') + '</div>'
                : '<div class="h-8 w-8 rounded-full bg-mint-100 flex items-center justify-center shrink-0 mt-0.5"><i data-lucide="sparkles" class="w-4 h-4 text-mint-700"></i></div>';

            const bubbleClass = isUser
                ? 'bg-mint-600 text-white rounded-2xl rounded-tr-md'
                : 'bg-white text-slate-700 rounded-2xl rounded-tl-md border border-slate-200';

            // Strip markdown for AI responses
            const displayText = isUser ? text : stripMarkdown(text);

            wrapper.innerHTML = avatar + '<div class="' + bubbleClass + ' px-4 py-3 shadow-sm max-w-[80%]"><p class="text-sm leading-relaxed whitespace-pre-wrap">' + escapeHtml(displayText) + '</p></div>';
            chatbotMessages.appendChild(wrapper);
            lucide.createIcons();
            scrollChatBottom();
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function sendQuickMsg(msg) {
            chatbotInput.value = msg;
            sendChatMessage(new Event('submit'));
        }

        function sendChatMessage(e) {
            e.preventDefault();
            const msg = chatbotInput.value.trim();
            if (!msg) return false;

            addMessage('user', msg);
            chatbotHistory.push({ role: 'user', content: msg });
            chatbotInput.value = '';
            chatbotTyping.classList.remove('hidden');
            chatbotSuggestions.classList.add('hidden');
            scrollChatBottom();

            const sendBtn = document.getElementById('chatbotSendBtn');
            sendBtn.disabled = true;
            sendBtn.classList.add('opacity-50', 'cursor-not-allowed');

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="csrf_token"]')?.value;

            fetch('<?= url("chatbot/send") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'csrf_token=' + encodeURIComponent(csrf) + '&message=' + encodeURIComponent(msg) + '&history=' + encodeURIComponent(JSON.stringify(chatbotHistory))
            })
            .then(r => r.json())
            .then(data => {
                chatbotTyping.classList.add('hidden');
                if (data.error) {
                    addMessage('assistant', data.error);
                } else if (data.reply) {
                    addMessage('assistant', data.reply);
                    chatbotHistory.push({ role: 'assistant', content: data.reply });
                }
            })
            .catch(() => {
                chatbotTyping.classList.add('hidden');
                addMessage('assistant', 'Sorry, something went wrong. Please try again.');
            })
            .finally(() => {
                sendBtn.disabled = false;
                sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });

            return false;
        }

        // Allow Enter to send (without shift)
        chatbotInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendChatMessage(e);
            }
        });

        const chatbotUserInitial = '<?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 1)) ?>';
    </script>

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
