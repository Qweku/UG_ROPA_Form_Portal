@props(['code' => '404', 'stampColor' => 'var(--accent)', 'stampDark' => 'var(--accent-dark)'])

<div class="error-illustration" aria-hidden="true">
    <svg viewBox="0 0 320 260" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
        <!-- Folder back -->
        <path d="M30 70 H140 L160 95 H290 C295 95 300 100 300 105 V210 C300 218 293 225 285 225 H35 C27 225 20 218 20 210 V85 C20 77 27 70 35 70 Z"
              fill="var(--primary-light)" stroke="var(--primary)" stroke-width="2" opacity="0.5"/>
        <!-- Document -->
        <rect x="70" y="40" width="180" height="160" rx="8" fill="white" stroke="var(--primary)" stroke-width="2.5"/>
        <!-- Folded corner -->
        <path d="M220 40 H250 V70 Z" fill="var(--primary-light)" stroke="var(--primary)" stroke-width="2.5" stroke-linejoin="round"/>
        <!-- Document lines (redacted record) -->
        <rect x="90" y="65" width="100" height="8" rx="4" fill="var(--gray-200)"/>
        <rect x="90" y="85" width="130" height="8" rx="4" fill="var(--gray-200)"/>
        <rect x="90" y="105" width="80" height="8" rx="4" fill="var(--gray-200)"/>
        <rect x="90" y="135" width="120" height="8" rx="4" fill="var(--gray-200)"/>
        <rect x="90" y="155" width="60" height="8" rx="4" fill="var(--gray-200)"/>
        <!-- Folder front -->
        <path d="M15 110 H305 C310 110 313 115 311 120 L292 215 C290 222 284 227 277 227 H43 C36 227 30 222 28 215 L9 120 C7 115 10 110 15 110 Z"
              fill="var(--primary)" />

        <!-- Stamp -->
        <g transform="translate(160 150) rotate(-12)" class="error-stamp">
            <circle cx="0" cy="0" r="58" fill="none" stroke="{{ $stampColor }}" stroke-width="5"/>
            <circle cx="0" cy="0" r="48" fill="none" stroke="{{ $stampColor }}" stroke-width="2"/>
            <text x="0" y="8" text-anchor="middle" font-family="'Instrument Sans', ui-sans-serif, sans-serif"
                  font-size="30" font-weight="800" fill="{{ $stampColor }}" letter-spacing="1">{{ $code }}</text>
        </g>
    </svg>
</div>
