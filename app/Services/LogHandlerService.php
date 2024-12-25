<?php

namespace App\Services;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Saasscaleup\LogAlarm\LogHandler;
use Saasscaleup\LogAlarm\NotificationService;

class LogHandlerService extends LogHandler
{
    /**
     * logError
     *
     * This method handles the logging of an error. It retrieves the current error logs
     * from the cache, adds a new log with the current timestamp, filters out logs that are
     * older than the specified time, saves the updated logs back to the cache, checks if
     * there have been a specified number of error logs in the last minute, and if so, sends
     * a notification and updates the time of the last notification.
     *
     * @param  MessageLogged $event The event that triggered the logging of the error.
     * @return void
     */
    protected function logError(MessageLogged $event)
    {

        $log_message =  __('LOG_LEVEL: ').$event->level.' | ' . __('LOG_MESSAGE: ').$event->message;

        $log_alarm_cache_key_enc = md5($log_message);
        
        // Retrieve the current error logs from the cache or initialize an empty array if no logs exist
        $errorLogs = Cache::get($log_alarm_cache_key_enc, []);
        
        // Add a new log with the current timestamp to the array of error logs
        $errorLogs[] = Carbon::now();
        
        // Get the time in minutes to consider an error log as recent
        $log_time_frame = config('log-alarm.log_time_frame');

        // Get specified number of error logs in time frame
        $log_per_time_frame = config('log-alarm.log_per_time_frame');

        // Filter out logs that are older than the specified time frame
        $errorLogs = array_filter($errorLogs, function ($timestamp) use ($log_time_frame) {
            return $timestamp >=  Carbon::now()->subMinutes($log_time_frame);
        });

        // Save the updated logs back to the cache with an expiration time of 1 minute
        Cache::put($log_alarm_cache_key_enc, $errorLogs, Carbon::now()->addMinutes($log_time_frame)); 

        // Check if there have been a specified number of error logs in time frame (in the last minute for example)
        if (count($errorLogs) >= $log_per_time_frame) {
            
            // Retrieve the time of the last notification from the cache or initialize null if no notification time exists
            $last_notification_time = Cache::get($this->notification_cache_key.'_'.$log_alarm_cache_key_enc);

            // Get the delay between notifications from the config file
            $delay_between_alarms = config('log-alarm.delay_between_alarms');

            // Send notification only if last notification was sent more than 5 minutes ago
            // The Carbon library is used to compare the current time with the time of the last notification
            if (!$last_notification_time || Carbon::now()->diffInMinutes($last_notification_time) >= $delay_between_alarms) {
                
                // Get the message to be sent in the notification from the config file
                $message = empty(config('log-alarm.notification_message')) ? $log_message : config('log-alarm.notification_message');

                $message = __('The Error was occurred') . ' ' . $log_per_time_frame . ' ' . __('times') . __(' in the last') . ' ' . $log_time_frame . ' ' . __('minutes: ') . $message;

                // Send the notification
                NotificationService::send($message);

                // Update the time of the last notification in the cache
                // The notification is set to expire in the delay between alarms specified in the config file
                Cache::put($this->notification_cache_key.'_'.$log_alarm_cache_key_enc, Carbon::now(), Carbon::now()->addMinutes($delay_between_alarms)); 
            }
        }
    }
}
