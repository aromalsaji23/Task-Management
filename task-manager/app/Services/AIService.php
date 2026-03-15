<?php

namespace App\Services;

use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class AIService
{
    /**
     * Generate AI summary and suggested priority for a task.
     * Expected return format:
     * [
     *     'ai_summary'  => 'string',
     *     'ai_priority' => 'low|medium|high'
     * ]
     *
     * @param Task $task
     * @return array
     */
    public function generateSummary(Task $task): array
    {
        $prompt = $this->buildPrompt($task);
        $provider = config('app.ai_provider', env('AI_PROVIDER', 'openai'));
        
        try {
            if ($provider === 'openai' && env('OPENAI_API_KEY')) {
                return $this->callOpenAI($prompt);
            }
            
            // If Gemini or other providers exist, you would add them here.
            // if ($provider === 'gemini' && env('GEMINI_API_KEY')) {
            //     return $this->callGemini($prompt);
            // }

            // Fallback when no keys are configured
            return $this->fallbackMock($task);
            
        } catch (Exception $e) {
            Log::error("AI Service Error: " . $e->getMessage());
            // Never throw to the user UI, gracefully fallback
            return $this->fallbackMock($task);
        }
    }

    /**
     * Calls OpenAI and parses JSON response
     */
    private function callOpenAI(string $prompt): array
    {
        // Require openai-php/laravel or standard client. 
        // For standard client without facade:
        $client = \OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI task assistant. Always return valid JSON only.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.7,
        ]);

        $content = $response->choices[0]->message->content;
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON returned from AI: " . $content);
        }

        return [
            'ai_summary'  => $decoded['ai_summary'] ?? 'Summary could not be generated.',
            'ai_priority' => in_array($decoded['ai_priority'] ?? '', ['low', 'medium', 'high']) 
                                ? $decoded['ai_priority'] 
                                : 'medium',
        ];
    }

    /**
     * Build the prompt string to send to the AI
     */
    private function buildPrompt(Task $task): string
    {
        return <<<PROMPT
Please analyze the following task and provide a concise, professional summary and suggest a priority level based on the wording and urgency.

Task Title: {$task->title}
Task Description: {$task->description}
Current Priority: {$task->priority->value}
Due Date: {$task->due_date->format('Y-m-d')}

Return EXACTLY a JSON object in this format:
{
  "ai_summary": "A concise 1-2 sentence summary of the task.",
  "ai_priority": "low|medium|high"
}
PROMPT;
    }

    /**
     * Fallback mock when APIs are unavailable or fail
     */
    private function fallbackMock(Task $task): array
    {
        return [
            'ai_summary'  => "[Automated Mock] This task titled '{$task->title}' needs attention by {$task->due_date->format('Y-m-d')}.",
            'ai_priority' => $task->priority->value, // Keep existing priority as fallback
        ];
    }
}
