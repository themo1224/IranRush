@component('mail::message')
# {{ 'تیکت جدید ایجاد شد' }}  <!-- Translated subject: 'A new ticket has been created' -->

**تیکت:** {{ $ticket->subject }}  
**توضیحات:** {{ $ticket->description }}  
**وضعیت:** {{ $ticket->status }}  

@component('mail::button', ['url' => $url])
مشاهده تیکت
@endcomponent

{{ __('emails.thank_you') }}  <!-- This assumes you have a language file for thank you message -->
@endcomponent
