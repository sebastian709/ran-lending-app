@props([
    'mobile' => false,
    'guestLabel' => 'Login',
])

@if (auth()->check())
    <details class="landing-profile-dropdown {{ $mobile ? 'is-mobile w-full' : '' }}">
        <summary class="landing-profile-trigger {{ $mobile ? 'w-full' : '' }}">
            @if (auth()->user()->profile_src)
                <img src="{{ asset('storage/' . auth()->user()->profile_src) }}" alt="Profile Picture"
                    class="landing-profile-avatar object-fit-cover"
                    onerror="this.setAttribute('hidden','hidden'); var fb=this.nextElementSibling; if(fb){ fb.removeAttribute('hidden'); }">
                <div class="landing-profile-avatar landing-profile-fallback" hidden>
                    {{ strtoupper(substr(auth()->user()->firstname, 0, 1) . substr(auth()->user()->lastname, 0, 1)) }}
                </div>
            @else
                <div class="landing-profile-avatar">
                    {{ strtoupper(substr(auth()->user()->firstname, 0, 1) . substr(auth()->user()->lastname, 0, 1)) }}
                </div>
            @endif

            <span class="landing-profile-name">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</span>
            <i class="ri-arrow-down-s-line landing-profile-chevron"></i>
        </summary>

        <div class="landing-profile-menu">
            <a href="#" data-url="{{ auth()->user()->is_admin ? '/admin/dashboard' : '/home' }}">
                <i class="ri-dashboard-line"></i>
                <span>{{ auth()->user()->is_admin ? 'Admin Dashboard' : 'Dashboard' }}</span>
            </a>
            <a href="#" data-url="{{ auth()->user()->is_admin ? '/admin/profile' : '/profile' }}">
                <i class="ri-user-line"></i>
                <span>Profile</span>
            </a>
            @if (auth()->user()->is_admin)
                <a href="#" data-url="/home">
                    <i class="ri-loop-left-line"></i>
                    <span>Borrower Mode</span>
                </a>
            @endif
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); this.nextElementSibling.submit();">
                <i class="ri-logout-box-line"></i>
                <span>Logout</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </details>
@else
    <button data-url="/login" type="button"
        class="{{ $mobile ? 'bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap w-full' : 'bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap' }}">
        {{ $guestLabel }}
    </button>
@endif
