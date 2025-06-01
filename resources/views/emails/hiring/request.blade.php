<x-mail::message>
# New Hiring Request

A new hiring request has been submitted with the following details:

- **Request Type:** {{ $hiringRequest->request_type }}
- **Category ID:** {{ $hiringRequest->category_id }}
- **Location:** {{ $hiringRequest->location }}
- **Minimum Budget:** ${{ number_format($hiringRequest->min_budget, 2) }}
- **Maximum Budget:** ${{ number_format($hiringRequest->max_budget, 2) }}
- **Bedrooms:** {{ $hiringRequest->bedrooms }}
- **Requirements:**  
{{ $hiringRequest->requirements }}

<x-mail::button :url="route('hiring-requests.index')">
View All Requests
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
