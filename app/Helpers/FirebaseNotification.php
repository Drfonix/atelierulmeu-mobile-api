<?php

if (!function_exists('generate_alert_notification_data')) {

    function generate_alert_notification_data(\App\Models\Alert $alert)
    {
        $formattedDate = $alert->alert_date->format('Y-m-d');

        return [
            "token" => $alert->user->device_token,
            "title" => generate_alert_title($alert->type),
            "body" => generate_alert_body($alert->car, $alert->type, $formattedDate),
        ];
    }
}

if (!function_exists('generate_alert_title')) {

    function generate_alert_title($type)
    {
        return sprintf("Upcoming Expiration Alert - %s", $type);
    }
}

if (!function_exists('generate_alert_body')) {

    function generate_alert_body(\App\Models\Car $car,$type, $expirationDate)
    {
        $carMakeModel = sprintf('%s %s', $car->make, $car->model);

        return sprintf('Your car (%s %s) %s due to expire at %s. Renew it before expiration date!!!',$carMakeModel, $car->plate_number, $type, $expirationDate);
    }
}

if (!function_exists('generate_appointment_notification_data')) {

    function generate_appointment_notification_data(\App\Models\AppointmentRequest $appointmentRequest)
    {

        return [
            "token" => $appointmentRequest->user->device_token,
            "title" => generate_appointment_title($appointmentRequest->title),
            "body" => generate_appointment_body($appointmentRequest),
        ];
    }
}

if (!function_exists('generate_appointment_title')) {

    function generate_appointment_title($type)
    {
        return sprintf("Upcoming Appointment Alert - %s", $type);
    }
}

if (!function_exists('generate_appointment_body')) {

    function generate_appointment_body(\App\Models\AppointmentRequest $appointmentRequest)
    {

        return sprintf('You have an upcoming appointment for car (%s) %s at %s.',$appointmentRequest->car_make_model, $appointmentRequest->car_plate_number, $appointmentRequest->from);
    }
}
