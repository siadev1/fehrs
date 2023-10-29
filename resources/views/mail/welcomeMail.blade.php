<x-mail::message>
# Introduction

The body of your message .
Click [here]({{$url}})

<x-mail::button :url="'{{$url}}'">
Click Here to Register
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
