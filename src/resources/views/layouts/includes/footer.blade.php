<footer>
    <script>

        window.settings = {!! isset($settings) ? json_encode($settings) : '{}' !!}
        window.localStorage.setItem('app-language',
            '{!! app_get_locale() !!}'
        );


        window.localStorage.setItem('app-languages',
            JSON.stringify(
                {!! json_encode(include resource_path() . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . (app_get_locale()) . DIRECTORY_SEPARATOR . 'default.php') !!}
            )
        );


        window.appLanguage = window.localStorage.getItem('app-language');
        window.localStorage.setItem('base_url', '{{request()->root()}}');

        @if(env('APP_INSTALLED') && auth()->user())
                window.localStorage.setItem('permissions', JSON.stringify(
                    {!! json_encode(array_merge(
                resolve(\App\Repositories\Core\Auth\UserRepository::class)->getPermissionsForFrontEnd(),
                [
                    'is_app_admin' => auth()->user()->isAppAdmin()
                ]
            )) !!}

                ))
        @endif

    </script>

    @stack('before-scripts')
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/core.js')) !!}
    {!! script(mix('js/summernote-bs4.min.js')) !!}

    @stack('after-scripts')
</footer>