<li class="kt-nav__item">
    <form action="{{ $route }}" method="post" class="kt-nav__link">
        @csrf
        @method('PUT')
        <button class="btn w-100 text-left p-0">
            <i class="kt-nav__link-icon flaticon2-power"></i>
            <span class="kt-nav__link-text">Activate</span>
        </button>
    </form>
</li>
