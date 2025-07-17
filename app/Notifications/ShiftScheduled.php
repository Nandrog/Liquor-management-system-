<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\WorkDistribution\ShiftSchedule;

class ShiftScheduled extends Notification
{
    use Queueable;

    public $shift;

    /**
     * Create a new notification instance.
     */
    public function __construct(ShiftSchedule $shift)
    {
        $this->shift = $shift;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🕒 New Shift Scheduled')
            ->line('You have been scheduled for a new shift.')
            ->line('Start: ' . $this->shift->start_time)
            ->line('End: ' . $this->shift->end_time)
            ->line('Break Hours: ' . $this->shift->break_hours)
            ->action('View Shifts', url('/shifts'))
            ->line('Thank you for your hard work!');
    }

    /**
     * Get the array representation for database.
     */
    public function toArray($notifiable)
    {
        return [
            'shift_id' => $this->shift->id,
            'start_time' => $this->shift->start_time,
            'end_time' => $this->shift->end_time,
            'break_hours' => $this->shift->break_hours,
        ];
    }
}