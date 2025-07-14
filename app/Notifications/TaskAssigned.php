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
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // send email & store in DB
    }

    /**
     * Get the mail representation.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('📌 New Task Assigned')
                    ->line('You have been assigned a new task.')
                    ->line('Type: ' . $this->task->type)
                    ->line('Deadline: ' . $this->task->deadline)
                    ->action('View Tasks', url('/tasks'))
                    ->line('Please attend to this task as soon as possible.');
    }

    /**
     * Get the array representation for database.
     */
    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'type' => $this->task->type,
            'priority' => $this->task->priority,
            'deadline' => $this->task->deadline,
        ];
    }
}