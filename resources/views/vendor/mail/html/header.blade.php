@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<div style="font-size: 28px; font-weight: bold; color: #e53e3e; text-transform: uppercase; letter-spacing: 1px;">SpeedCartel</div>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
