<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

#[Layout('layouts.app')]
class Chat extends Component
{
    public string $prompt = '';
    public array $messages = [];          // مصفوفة عادية — لا مشكلة تعديل متداخل معها
    public bool $isLoading = false;
    public string $model = 'llama3.2';
    public string $systemPrompt = 'أنت مساعد ذكي تجيب بالعربية بشكل مختصر وواضح.';

    public function mount(): void
    {
        $this->model = env('OLLAMA_MODEL', 'llama3.2');
    }

    public function sendMessage(): void
    {
        $this->validate([
            'prompt' => 'required|string|min:1|max:4000',
        ]);

        $question = trim($this->prompt);
        $this->prompt = '';

        // 1. ابنِ السياق من الرسائل السابقة قبل إضافة الجديدة
        $history = $this->buildHistory();

        // 2. أضف رسالة المستخدم + مكان فاضي للرد
        $this->messages[] = ['role' => 'user', 'content' => $question];
        $this->messages[] = ['role' => 'assistant', 'content' => ''];

        $index = count($this->messages) - 1;
        $this->isLoading = true;

        try {
            $response = Prism::text()
                ->using('ollama', $this->model)
                ->withSystemPrompt($this->systemPrompt)
                ->withMessages([
                    ...$history,
                    new UserMessage($question),
                ])
                ->generate();

            $this->messages[$index]['content'] = $response->text ?: '(رد فارغ من النموذج)';

        } catch (\Throwable $e) {
            $this->messages[$index]['content'] = '❌ خطأ: '.$e->getMessage();
        } finally {
            $this->isLoading = false;
            $this->dispatch('scroll-to-bottom');
        }
    }

    /**
     * تحويل الرسائل المخزنة إلى كائنات Prism
     */
    private function buildHistory(): array
    {
        $history = [];

        foreach ($this->messages as $msg) {
            if (trim($msg['content']) === '') {
                continue; // تجاهل الرسائل الفارغة
            }

            $history[] = $msg['role'] === 'user'
                ? new UserMessage($msg['content'])
                : new AssistantMessage($msg['content']);
        }

        // آخر 12 رسالة فقط حتى لا يكبر السياق بلا حدود
        return array_slice($history, -12);
    }

    public function clearChat(): void
    {
        $this->messages = [];
    }

    public function render()
    {
        return view('livewire.chat');
    }
}