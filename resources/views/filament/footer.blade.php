<footer class="w-full bg-white border-t border-gray-200 dark:bg-gray-900 dark:border-gray-800 text-sm mx-auto text-gray-500  dark:text-gray-400">
    <div class="flex flex-col items-center sm:items-stretch sm:flex-row max-w-7xl w-full justify-between py-4 px-6 mx-auto">
        <span class="">© {{now()->year}} {{config('app.name')}}</span>
        <div class="flex">
            <a href="{{route('filament.guest.pages.terms')}}" class="mx-2">{{__('Terms')}}</a>
            <a href="{{route('filament.guest.pages.data-policy')}}" class="mx-2">{{__('Data Policy')}}</a>
            <a href="{{route('filament.guest.pages.imprint')}}" class="mx-2">{{__('Imprint')}}</a>
        </div>
    </div>
</footer>
