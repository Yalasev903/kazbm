@php $heroSettings = app(\App\Filament\Settings\HeroSettings::class) @endphp
@if($heroSettings->title)
    <div class="block1">
        <div class="title">{!! $heroSettings->title[\Illuminate\Support\Facades\App::getLocale()] !!}</div>
        <div class="banner">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="605" viewBox="0 0 100% 605" fill="none">
                <path d="M563.75 215.5C574.796 215.5 583.75 206.546 583.75 195.5V20C583.75 8.9543 592.704 0 603.75 0H1360.25C1371.3 0 1380.25 8.95431 1380.25 20V504V585C1380.25 596.046 1371.3 605 1360.25 605H1283.75H20.25C9.20429 605 0.25 596.046 0.25 585L0.25 235.5C0.25 224.454 9.2043 215.5 20.25 215.5H563.75Z" fill="url(#pattern0_279_21675)"></path>
                <defs>
                    <pattern id="pattern0_279_21675" patternContentUnits="objectBoundingBox" width="1" height="1">
                        <use xlink:href="#image0_279_21675" transform="matrix(0.00121304 0 0 0.00276694 -0.0121044 -0.218107)"></use>
                    </pattern>
                    <image id="image0_279_21675" width="1000" height="500" xlink:href="{{ $heroSettings->getRealFormat('photo') }}"></image>
                </defs>
            </svg>
            <picture class="img">
                @if($photo = $heroSettings->getWebpFormat('photo'))
                    <source srcset="{{$photo}}" type="image/webp">
                    <source srcset="{{$photo}}" type="image/pjp2">
                @endif
                <img src="{{ $heroSettings->getRealFormat('photo') }}" alt="hero image" loading="lazy">
            </picture>
        </div>
    </div>
@endif
