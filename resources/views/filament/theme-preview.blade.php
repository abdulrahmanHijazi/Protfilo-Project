{{-- Live preview of the site palette for Site Settings → Appearance. $c = validated colors keyed by setting name. --}}
@php
    $modes = [
        ['label' => 'Dark mode', 'bg' => $c['theme_dark_bg'], 'surface' => $c['theme_dark_surface'], 'text' => $c['theme_dark_text'], 'soft' => $c['theme_accent_light']],
        ['label' => 'Light mode', 'bg' => $c['theme_light_bg'], 'surface' => $c['theme_light_surface'], 'text' => $c['theme_light_text'], 'soft' => $c['theme_accent_dark']],
    ];
@endphp
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
    @foreach ($modes as $m)
        <div style="background:{{ $m['bg'] }};color:{{ $m['text'] }};border-radius:16px;padding:18px;border:1px solid color-mix(in srgb, {{ $m['text'] }} 12%, transparent);font-family:Inter,Cairo,sans-serif">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                <span style="font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:{{ $c['theme_accent'] }};font-weight:700">{{ $m['label'] }}</span>
                <span style="display:flex;gap:4px">
                    @foreach ([$c['theme_accent'], $c['theme_accent_light'], $c['theme_accent_dark'], $m['bg'], $m['surface'], $m['text']] as $swatch)
                        <span title="{{ $swatch }}" style="width:14px;height:14px;border-radius:999px;background:{{ $swatch }};border:1px solid color-mix(in srgb, {{ $m['text'] }} 20%, transparent)"></span>
                    @endforeach
                </span>
            </div>
            <div style="background:{{ $m['surface'] }};border-radius:12px;padding:14px;border:1px solid color-mix(in srgb, {{ $m['text'] }} 9%, transparent)">
                <div style="font-weight:800;font-size:18px">Abdulrahman Hijazi</div>
                <div style="font-weight:700;font-size:13px;margin-top:2px;background:linear-gradient(120deg, {{ $c['theme_accent'] }}, {{ $m['soft'] }});-webkit-background-clip:text;background-clip:text;color:transparent">Backend Developer — PHP / Laravel</div>
                <p style="font-size:12px;margin:8px 0 12px;color:color-mix(in srgb, {{ $m['text'] }} 65%, {{ $m['bg'] }})">Muted body text looks like this on the page.</p>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <span style="background:linear-gradient(90deg, {{ $c['theme_accent'] }}, {{ $m['soft'] }});color:{{ \App\Support\Theme::onColor($c['theme_accent']) }};font-size:12px;font-weight:700;padding:7px 14px;border-radius:10px">Explore my work</span>
                    <span style="border:1px solid color-mix(in srgb, {{ $c['theme_accent'] }} 45%, transparent);color:{{ $m['soft'] }};font-size:12px;font-weight:700;padding:7px 14px;border-radius:10px">Download CV</span>
                    <span style="background:color-mix(in srgb, {{ $c['theme_accent'] }} 12%, transparent);color:{{ $m['soft'] }};font-size:11px;padding:4px 10px;border-radius:999px;align-self:center">Laravel</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
