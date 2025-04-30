@component('mail::message')
# {{ 'وضعیت تیکت شما بروزرسانی شد' }}  <!-- Translated subject -->

**تیکت:** {{ $ticket->subject }}  
**وضعیت قبلی:** {{ $oldStatus }}  
**وضعیت جدید:** {{ $newStatus }}  

@component('mail::button', ['url' => $url])
مشاهده تیکت
@endcomponent

{{ __('emails.thank_you') }}  
@endcomponent