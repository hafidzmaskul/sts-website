<x-mail::message>
# New Manual Product Request

A new product request has been submitted.

**Name:** {{ $productRequest->name }}
**Email:** {{ $productRequest->email }}
**Phone:** {{ $productRequest->phone }}
**Product:** {{ $productRequest->product->title ?? 'N/A' }} (ID: {{ $productRequest->product_id }})
**Date:** {{ $productRequest->created_at->format('F d, Y H:i:s') }}

**Message:**
{{ $productRequest->message ?? 'No message provided.' }}

**View Request:**
[{{ route('admin.product-requests.show', $productRequest) }}]({{ route('admin.product-requests.show', $productRequest) }})

Thanks,
{{ config('app.name') }}
</x-mail::message>