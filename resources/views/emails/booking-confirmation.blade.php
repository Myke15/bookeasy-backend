@component('mail::message')

# Booking Confirmed!

Your appointment has been successfully scheduled

---

**Dear {{ $client->name }},**

Thank you for booking with us! Your appointment details are as follows:

@component('mail::panel')
## Booking Details

- **Service:** {{ $serviceName }}
- **Date:** {{ \Carbon\Carbon::parse($booking->date)->format('l, F j, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($booking->start_at)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_at)->format('g:i A') }}
@endcomponent

If you need to make any changes to your appointment or have any questions, please contact us.

We look forward to seeing you!

**Best regards,**  
Smart Booking Team

@endcomponent