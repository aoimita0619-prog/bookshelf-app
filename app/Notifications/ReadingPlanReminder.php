<?php

namespace App\Notifications;

use App\Models\ReadingPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReadingPlanReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
    public ReadingPlan $readingPlan, 
    public string $timing
    ){
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
         return [
        'reading_plan_id' => $this->readingPlan->id,
        'book_id' => $this->readingPlan->book_id,
        'book_title' => $this->readingPlan->book->title,
        'target_date' => $this->readingPlan->target_date->format('Y-m-d'),

        'timing' => match ($this->timing) {
            'before_3_days' => 'three_days_before',
            'today' => 'on_due_date',
            'after_3_days' => 'three_days_after',
        },

        'title' => match ($this->timing) {
            'before_3_days' => '読書計画の目標日が近づいています',
            'today' => '読書計画の目標日です',
            'after_3_days' => '読書計画の目標日を過ぎています',
        },

        'body' => match ($this->timing) {
            'before_3_days' =>
                "「{$this->readingPlan->book->title}」の目標日まであと3日です。",

            'today' =>
                "「{$this->readingPlan->book->title}」の目標日です。",

            'after_3_days' =>
                "「{$this->readingPlan->book->title}」の目標日から3日経過しました。",
        },
    ];
    }

    private function message(): string
    {
        return match ($this->timing) {
            'before_3_days' =>
                '「' . $this->readingPlan->book->title . '」の読書計画の目標日まであと3日です。',

            'today' =>
                '「' . $this->readingPlan->book->title . '」の読書計画の目標日です。',

            'after_3_days' =>
                '「' . $this->readingPlan->book->title . '」の読書計画の目標日から3日経過しました。',

            default =>
                '読書計画の目標日を確認してください。',
        };
    }


}
