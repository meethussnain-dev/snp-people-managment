<p>Good day {{ $person->name }} {{ $person->surname }},</p>

<p>This email confirms that you have been captured on the system.</p>

<p>Your recorded details include:</p>

<ul>
    <li>Email: {{ $person->email }}</li>
    <li>Mobile Number: {{ $person->mobile_number }}</li>
    <li>Language: {{ $person->language->name }}</li>
</ul>

<p>Regards,<br>SNP People Manager</p>
