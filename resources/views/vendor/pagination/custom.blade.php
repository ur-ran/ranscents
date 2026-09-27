@if ($paginator->hasPages())
  <nav>
    <div style="display:flex; gap:6px; flex-wrap:wrap; justify-content:center;">
      {{-- Previous --}}
      @if ($paginator->onFirstPage())
        <span style="padding:8px 14px; border-radius:8px; font-size:13px; color:var(--text-400); border:1px solid var(--border);">
          ← Prev
        </span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}"
          style="padding:8px 14px; border-radius:8px; font-size:13px; color:var(--text-200); border:1px solid var(--border); text-decoration:none; transition:all 0.3s;"
          onmouseover="this.style.borderColor='var(--gold-500)';this.style.color='var(--gold-400)'"
          onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-200)'">
          ← Prev
        </a>
      @endif

      {{-- Pages --}}
      @foreach ($elements as $element)
        @if (is_string($element))
          <span style="padding:8px 14px; border-radius:8px; font-size:13px; color:var(--text-400);">{{ $element }}</span>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span style="padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700;
                 background:linear-gradient(135deg,var(--gold-500),var(--gold-700)); color:#0A0A0F;">
                {{ $page }}
              </span>
            @else
              <a href="{{ $url }}"
                style="padding:8px 14px; border-radius:8px; font-size:13px; color:var(--text-200); border:1px solid var(--border); text-decoration:none; transition:all 0.3s;"
                onmouseover="this.style.borderColor='var(--gold-500)';this.style.color='var(--gold-400)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-200)'">
                {{ $page }}
              </a>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
          style="padding:8px 14px; border-radius:8px; font-size:13px; color:var(--text-200); border:1px solid var(--border); text-decoration:none; transition:all 0.3s;"
          onmouseover="this.style.borderColor='var(--gold-500)';this.style.color='var(--gold-400)'"
          onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-200)'">
          Next →
        </a>
      @else
        <span style="padding:8px 14px; border-radius:8px; font-size:13px; color:var(--text-400); border:1px solid var(--border);">
          Next →
        </span>
      @endif
    </div>
  </nav>
@endif
