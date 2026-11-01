<div
    x-data
    x-init="
        Livewire.on('chat:message-added', () => {
            $nextTick(() => {
                document.getElementById('chat-end')?.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    "
    class="space-y-4"
>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">

        {{-- رأس البطاقة --}}
        <div class="flex flex-col gap-3 border-b border-gray-200 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    محادثة المساعدة الذكية
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    النموذج:
                    <span class="font-semibold text-gray-700">
                        {{ config('services.ollama.model', 'qwen3:0.6b') }}
                    </span>
                    —
                    تشغيل محلي
                </p>
            </div>

            <button
                type="button"
                wire:click="clearChat"
                wire:loading.attr="disabled"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#006C35] focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
            >
                محادثة جديدة
            </button>
        </div>

        {{-- منطقة الرسائل --}}
        <div
            class="h-[60vh] space-y-4 overflow-y-auto bg-gray-50 p-4 sm:p-6"
            aria-live="polite"
        >

            @foreach ($messages as $chatMessage)
                @continue($chatMessage['role'] === 'system')

                @if ($chatMessage['role'] === 'user')

   {{-- رسالة المستخدم --}}
                    <div class="flex justify-end mb-4">
                        <div class="max-w-[80%] sm:max-w-[65%]">
                            <div class="rounded-2xl rounded-bl-sm bg-[#006C35] px-1 py-1 text-white shadow-sm sm:px-5 sm:py-3.5">
                                <p class="whitespace-pre-wrap text-sm leading-7 sm:text-base sm:leading-8">
                                    {!! nl2br(e($chatMessage['content'])) !!}
                                </p>
                            </div>

                            <p class="mt-1.5 text-right text-[11px] text-gray-400">
                                أنت
                            </p>
                        </div>
                    </div>

                @else

                    {{-- رد المساعد --}}
                    <div class="flex justify-start mb-4">
                        <div class="max-w-[80%] sm:max-w-[65%]">
                            <div class="rounded-2xl rounded-tr-sm border border-gray-200 bg-white px-1 py-1 shadow-sm sm:px-5 sm:py-3.5">
                                <p class="whitespace-pre-wrap text-sm leading-7 text-gray-800 sm:text-base sm:leading-8">
                                    {!! nl2br(e($chatMessage['content'])) !!}
                                </p>
                            </div>

                            <p class="mt-1.5 text-right text-[11px] text-gray-400">
                                المساعد الذكي
                            </p>
                        </div>
                    </div>

                @endif
            @endforeach

            {{-- مؤشر التحميل --}}
            <div wire:loading wire:target="send">
                <div class="flex justify-start">
                    <div class="max-w-[85%] sm:max-w-[70%]">
                        <div class="rounded-3xl rounded-tr-md border border-gray-200 bg-white px-4 py-3 shadow-sm sm:px-5">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="h-2 w-2 animate-bounce rounded-full bg-[#006C35] [animation-delay:-0.3s]"></span>
                                <span class="h-2 w-2 animate-bounce rounded-full bg-[#006C35] [animation-delay:-0.15s]"></span>
                                <span class="h-2 w-2 animate-bounce rounded-full bg-[#006C35]"></span>
                                <span class="mr-1">جارٍ التجهيز...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="chat-end"></div>
        </div>

        {{-- منطقة الإدخال --}}
        <div class="border-t border-gray-200 bg-white p-4 sm:p-5">

            @if ($error)
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $error }}
                </div>
            @endif

            @error('message')
                <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    {{ $message }}
                </div>
            @enderror

            <form wire:submit="send" class="flex items-end gap-3">

                <div class="flex-1">
                    <label for="message" class="sr-only">
                        اكتب رسالتك هنا
                    </label>

                    <textarea
                        id="message"
                        rows="2"
                        wire:model="message"
                        placeholder="اكتب رسالتك هنا..."
                        class="block w-full resize-none rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-[#006C35] focus:outline-none focus:ring-2 focus:ring-[#006C35]/20 sm:text-base"
                        @keydown.enter="
                            if (!event.shiftKey) {
                                event.preventDefault();
                                $wire.send();
                            }
                        "
                    ></textarea>
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="send"
                    class="inline-flex min-w-[110px] items-center justify-center rounded-2xl bg-[#006C35] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#00592c] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#006C35] focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 sm:text-base"
                >
                    <span wire:loading.remove wire:target="send">
                        إرسال
                    </span>

                    <span wire:loading wire:target="send">
                        جارٍ الإرسال...
                    </span>
                </button>

            </form>

            <p class="mt-3 text-xs text-gray-400">
                اضغط Enter للإرسال، و Shift + Enter لسطر جديد.
            </p>

        </div>

    </div>

    {{-- بطاقة معلومات نظامية --}}
    <div class="grid gap-4 md:grid-cols-3">

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900">
                الخصوصية
            </h3>
            <p class="mt-2 text-sm leading-6 text-gray-500">
                المحادثة تُعالج محلياً عبر Ollama داخل نطاقك، ولا تُرسل إلى مزود سحابي خارجي.
            </p>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900">
                التقنية
            </h3>
            <p class="mt-2 text-sm leading-6 text-gray-500">
                الواجهة مبنية باستخدام Laravel و Livewire و Tailwind CSS و Alpine.js.
            </p>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900">
                بيئة الاستخدام
            </h3>
            <p class="mt-2 text-sm leading-6 text-gray-500">
                هذا النموذج مخصص للتجربة الداخلية، ويمكن ربطه لاحقاً بسجلات أو خدمات حكومية داخلية.
            </p>
        </div>

    </div>

</div>