<div>
    @if(!empty($recipientName))
        <p>Hello {{ $recipientName }},</p>
    @else
        <p>Hello customer,</p>
    @endif

    <p>
        Order <strong>#{{ $orderId }}</strong> has overdue books. Please return them as soon as possible.
    </p>

    <p><strong>List of overdue books:</strong></p>
    <ul>
        @foreach($bookTitles as $title)
            <li>{{ $title }}</li>
        @endforeach
    </ul>

    <p>Regards</p>
</div>
