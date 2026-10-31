<div class="h-screen bg-slate-900 text-white flex flex-col max-w-3xl mx-auto p-4">

    <header class="flex items-center justify-between pb-4 mb-4 border-b border-slate-700">
        <h1 class="text-xl font-bold">⚡ Prism + Ollama</h1>

        <div class="flex items-center gap-2">
            <input type="text" wire:model.blur="model"
                   class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-1 text-xs w-36 focus:outline-none focus:ring-1 focus:ring-indigo-500">

            @if(count($messages))
                <button wire:click="clearChat"
                        class="text-xs bg-slate-700 hover:bg-slate-600 px-3 py-1.5 rounded-lg transition">
                    مسح
                </button>
            @endif
        </div>
    </header>

    <div class="flex-1 overflow-y-auto space-y-4 mb-4 pe-2"
         x-data
         x-on:scroll-to-bottom.window="$nextTick(() => $el.scrollTop = $el.scrollHeight)">

        @if(! count($messages))
            <div class="flex flex-col items-center justify-center h-full text-slate-400 text-center">
                <div class="text-5xl mb-3">🧠</div>
                <p>ابدأ محادثة — كل شيء يعمل محلياً على جهازك.</p>
            </div>
        @endif

        @foreach($messages as $i => $msg)
            <div wire:key="msg-{{ $i }}"
                 class="flex {{ $msg['role'] === 'user' ? 'justify-start' : 'justify-end' }}">

                <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm
                    {{ $msg['role'] === 'user'
                        ? 'bg-indigo-600'
                        : 'bg-slate-800 border border-slate-700' }}">

                    <div class="text-[11px] font-bold mb-1 opacity-60">
                        {{ $msg['role'] === 'user' ? 'أنت' : 'المساعد' }}
                    </div>

                    <div class="whitespace-pre-wrap leading-relaxed">{{ $msg['content'] }}</div>
                </div>
            </div>
        @endforeach

        <div wire:loading wire:target="sendMessage" class="flex justify-end">
            <div class="bg-slate-800 border border-slate-700 rounded-2xl px-4 py-3 flex gap-1">
                <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce"></span>
                <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce [animation-delay:150ms]"></span>
                <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce [animation-delay:300ms]"></span>
            </div>
        </div>
    </div>

    @error('prompt')
        <p class="text-red-400 text-xs mb-2">{{ $message }}</p>
    @enderror

    <form wire:submit="sendMessage"
          class="bg-slate-800 border border-slate-700 rounded-2xl p-3 flex items-end gap-3">

        <textarea
            wire:model="prompt"
            rows="1"
            placeholder="اكتب رسالتك... (Enter للإرسال، Shift+Enter لسطر جديد)"
            x-data
            x-on:input="$el.style.height='auto'; $el.style.height=Math.min($el.scrollHeight,150)+'px'"
            x-on:keydown.enter="if(! $event.shiftKey){ $event.preventDefault(); $wire.sendMessage(); }"
            class="flex-1 bg-transparent resize-none text-sm py-2 placeholder-slate-500 focus:outline-none"></textarea>

        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="sendMessage"
                class="bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed
                       rounded-xl px-5 py-2 text-sm font-medium transition">
            <span wire:loading.remove wire:target="sendMessage">إرسال</span>
            <span wire:loading wire:target="sendMessage">...</span>
        </button>
    </form>

    <p class="text-center text-[11px] text-slate-500 mt-2">
        {{ $model }} • Prism PHP • محلي 100% 🔒
    </p>
</div>