@if ($interests->isNotEmpty())
    <div class="interests-cell">
        @foreach ($visible as $interest)
            <span class="badge-interest">{{ $interest->name }}</span>
        @endforeach
        @if ($overflow > 0)
            <span class="badge-overflow"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  title="{{ $allNames }}">+{{ $overflow }}</span>
        @endif
    </div>
@else
    <span style="color:#94a3b8;font-size:0.8rem;">-</span>
@endif
