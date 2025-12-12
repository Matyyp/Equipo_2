<tr>
    <td class="header" style="text-align:center;">

        @if (!empty($tenantLogo))
            <img src="{{ $tenantLogo }}"
                alt="Logo"
                style="max-height:60px; width:auto; display:block; margin:0 auto;">
        @else
            <span class="brand-text font-weight-light">
                {{ $tenantCompanyName ?? config('app.name') }}
            </span>
        @endif
    </td>
</tr>
