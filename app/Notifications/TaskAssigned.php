<?php

namespace App\Notifications;

use App\Models\WorkDistribution\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Email version.
     */
    public function toMail($notifiable)
    {
        $orderId = $this->task->stockMovement?->order?->id;

        return (new MailMessage)
            ->subject('📌 New Task Assigned')
            ->line('You have been assigned a new task.')
            ->line('Type: ' . $this->task->type)
            ->line('Priority: ' . $this->task->priority)
            ->line('Deadline: ' . $this->task->deadline)
            ->when($orderId, fn($mail) =>
                $mail->line('Related Order: #' . $orderId)
            )
            ->action('View Tasks', url('/tasks'))
            ->line('Please attend to this task as soon as possible.');
    }

    /**
     * Database version.
     */
    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'type' => $this->task->type,
            'priority' => $this->task->priority,
            'deadline' => $this->task->deadline,
            'order_id' => $this->task->stockMovement?->order?->id ?? null,
        ];
    }
}