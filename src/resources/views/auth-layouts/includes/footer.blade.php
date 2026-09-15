@stack('before-scripts')

<script>

    window.localStorage.setItem('app-language', '{!! config('app.local') ?? "en" !!}');

    window.localStorage.setItem('app-languages',
        JSON.stringify(
            {!! json_encode(include resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.(config('app.local') ?? 'en').DIRECTORY_SEPARATOR.'default.php') !!}
        )
    );

    window.appLanguage = window.localStorage.getItem('app-language');
    window.localStorage.setItem('base_url', '{{request()->root()}}');

</script>
{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/core.js')) !!}
@stack('after-scripts')
