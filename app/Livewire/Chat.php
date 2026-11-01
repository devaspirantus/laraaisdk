<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Chat extends Component
{
    public array $messages = [];

    public string $message = '';

    public string $model = '';

    public bool $loading = false;

    public ?string $error = null;

    public function mount(): void
    {
        $this->model = config('services.ollama.model', env('OLLAMA_MODEL', 'qwen3:0.6b'));
        $this->startConversation();
    }

    protected function startConversation(): void
    {
        $this->messages = [
            [
                'role' => 'system',
                'content' => 'أنت مساعد حكومي سعودي محترف. تجيب باللغة العربية الفصحى بوضوح واحترافية واختصار.',
            ],
            [
                'role' => 'assistant',
                'content' => 'مرحباً بك، أنا مساعد تجريبي يعمل محلياً عبر Ollama. كيف أقدر أخدمك؟',
            ],
        ];
    }

    public function clearChat(): void
    {
        $this->reset([
            'message',
            'error',
            'loading',
        ]);

        $this->startConversation();
        $this->dispatch('chat:message-added');
    }

    public function send(): void
    {
        if ($this->loading) {
            return;
        }

        $this->validate(
            [
                'message' => ['required', 'string', 'max:8000'],
            ],
            [],
            [
                'message' => 'الرسالة',
            ]
        );

        $this->error = null;

        $this->messages[] = [
            'role' => 'user',
            'content' => trim($this->message),
        ];

        $this->message = '';
        $this->loading = true;

        try {
            $url = rtrim(config('services.ollama.url', env('OLLAMA_URL', 'http://172.17.0.1:11434')), '/');
            $currentModel = $this->model;

            $response = Http::timeout(300)
                ->connectTimeout(10)
                ->post($url . '/api/chat', [
                    'model' => $currentModel,
                    'messages' => $this->messages,
                    'stream' => false,
                    'options' => [
                        'temperature' => 0.3,
                    ],
                ]);

            if ($response->failed()) {
                throw new \RuntimeException('Ollama returned status: ' . $response->status());
            }

            $answer = $response->json('message.content');

            if (! is_string($answer) || trim($answer) === '') {
                throw new \RuntimeException('Empty response from Ollama.');
            }

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $answer,
            ];
        } catch (\Throwable $e) {
            logger()->error('Ollama chat error', [
                'message' => $e->getMessage(),
            ]);

            $this->error = 'تعذر الحصول على رد من Ollama. الخطأ: ' . $e->getMessage();
        }

        $this->loading = false;
        $this->dispatch('chat:message-added');
    }

    public function render()
    {
        return view('livewire.chat');
    }
}