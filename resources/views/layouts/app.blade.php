<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'المساعد الحكومي المحلي' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- إذا عندك Tailwind مضبوط في المشروع، هذا هو الصحيح --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{--
        إذا Tailwind غير مثبت بعد وتريد تجربة التصميم مؤقتاً،
        ضع هذا السطر بدل @vite ثم احذفه لاحقاً:
        <script src="https://cdn.tailwindcss.com"></script>
    --}}

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'IBM Plex Sans Arabic', 'Segoe UI', Tahoma, Arial, sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">

    {{-- رابط تجاوز المحتوى، مهم لتحسين إمكانية الوصول --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:right-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-emerald-800 focus:shadow">
        تخطي إلى المحتوى الرئيسي
    </a>

    {{-- الشريط العلوي الحكومي --}}
    <div class="bg-[#003B23] text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-2 text-xs sm:text-sm">
            <p>بوابة تجريبية للذكاء الاصطناعي المحلي</p>

            <div class="hidden items-center gap-3 sm:flex">
                <span class="rounded-full border border-white/20 px-3 py-1">بيئة تطوير</span>
                <span class="rounded-full border border-white/20 px-3 py-1">Ollama محلي</span>
            </div>
        </div>
    </div>

    {{-- الهيدر --}}
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">

            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#006C35] text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75h4.5v3.023a6.002 6.002 0 0 1 2.25 10.477v.75h.75a1.5 1.5 0 0 1 1.5 1.5v.75a1.5 1.5 0 0 1-1.5 1.5h-.75v.75a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V21h-.75a1.5 1.5 0 0 1-1.5-1.5v-.75a1.5 1.5 0 0 1 1.5-1.5h.75v-.75A6.002 6.002 0 0 1 9.75 6.773V3.75Z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-lg font-bold leading-6 text-gray-900 sm:text-xl">
                        المساعد الحكومي الذكي
                    </h1>
                    <p class="text-xs text-gray-500 sm:text-sm">
                        نموذج محادثة محلي يعمل عبر Ollama
                    </p>
                </div>
            </div>

            <div class="hidden md:block">
                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-[#006C35] ring-1 ring-inset ring-emerald-200">
                    <span class="h-2 w-2 rounded-full bg-[#006C35]"></span>
                    تشغيل محلي آمن
                </span>
            </div>

        </div>
    </header>

    {{-- المحتوى --}}
    <main id="main-content" class="mx-auto max-w-6xl px-4 py-6 sm:py-8">
        {{ $slot }}
    </main>

    {{-- الفوتر --}}
    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-6">
            <div class="flex flex-col items-center justify-between gap-3 text-xs text-gray-500 sm:flex-row sm:text-sm">
                <p>
                    تصميم تجريبي مستوحى من الهوية البصرية للمنصات الحكومية.
                </p>

                <p>
                    البيانات تتم معالجتها محلياً عبر Ollama ولا تُرسل إلى خدمات خارجية.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>