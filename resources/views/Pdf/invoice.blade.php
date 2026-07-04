<!DOCTYPE html>
<html>
<head>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

<h1>{{ $data['order_number'] }}</h1>

<p>{{ $data['customer_name'] }}</p>
<p>{{ $data['customer_phone'] }}</p>
<p>{{ $data['location'] }}</p>
<p>{{ $data['total_amount'] }}</p>
<p>{{ $data['dp_amount'] }}</p>
<p>{{ $data['status'] }}</p>

</body>
</html>