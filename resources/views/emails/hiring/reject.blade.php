<x-mail::message>
# Hiring Request Rejected

Dear {{ $hiringRequest->user->name }},

We regret to inform you that your hiring request has been **rejected**.  
Below are the details of your request for your reference:

---

### 📝 Request Details:
- **Request Type:** {{ $hiringRequest->request_type }}
- **Category:** {{ $hiringRequest->category->name ?? '—' }}
- **Location:** {{ $hiringRequest->location }}
- **Min Budget:** PKR {{ number_format($hiringRequest->min_budget) }}
- **Max Budget:** PKR {{ number_format($hiringRequest->max_budget) }}
- **Bedrooms:** {{ $hiringRequest->bedrooms }}
- **Additional Requirements:**  
  {{ $hiringRequest->requirements ?? 'None specified.' }}

---

### 👤 Submitted By:
- **Name:** {{ $hiringRequest->user->name }}
- **Email:** {{ $hiringRequest->user->email }}

@if($hiringRequest->agent)
---

### 🧑‍💼 Assigned Agent:
- **Name:** {{ $hiringRequest->agent->user->name }}
- **Email:** {{ $hiringRequest->agent->user->email }}
@endif

---

If you have any questions or believe this decision was made in error, feel free to reach out.

<x-mail::button :url="url('/contact')">
Contact Support
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
