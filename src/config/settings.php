<?php

return [
    'user' => [
        ['name' => 'gender', 'value' => '', 'context' => 'user'],
        ['name' => 'contact', 'value' => '', 'context' => 'user'],
        ['name' => 'address', 'value' => '', 'context' => 'user'],
        ['name' => 'date_of_birth', 'value' => '', 'context' => 'user'],
    ],
    'app' => [
        ['name' => 'company_name', 'value' => env('APP_NAME', 'Grupo SEI'), 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_logo', 'value' => 'images/logo.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_icon', 'value' => 'images/icon.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_banner', 'value' => 'images/banner.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'job_post_cover', 'value' => 'images/job_post_cover.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'language', 'value' => 'pt_BR', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'layout', 'value' => 'ltr', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'date_format', 'value' => 'd/m/Y', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_format', 'value' => 'H', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_zone', 'value' => 'America/Sao_Paulo', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_symbol', 'value' => 'R$', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'decimal_separator', 'value' => ',', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'thousand_separator', 'value' => '.', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'number_of_decimal', 'value' => '2', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_position', 'value' => 'prefix_with_space', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        [
            'name' => 'application_form',
            'value' => '[{"id":"64adc53a-4cdf-4c4e-82e5-b3bfc6b990f9","is_visible":true,"title":"Informações Básicas","items":[{"id":"dac64396-e2a6-4c1b-bf30-ad864f3c7ff0","fields":[{"title":"Nome","type":"text","id":"first_name","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"Sobrenome","type":"text","id":"last_name","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"Nome da Mãe","type":"text","id":"mother_name","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"Nome do Pai","type":"text","id":"father_name","options":[],"required":false,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"Data de nascimento","type":"date","id":"birthday","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"RG","type":"text","id":"rg","options":[],"required":false,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"CPF","type":"text","id":"cpf","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"Gênero","type":"radio","id":"genre","options":["Masculino","Feminino","Outro"],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"Telefone","type":"tel-input","id":"telephone","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null},{"title":"Email","type":"email","id":"email","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":false},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[],"name":"fahim","value":null,"disabled":true}],"actions":{"edit":false,"delete":false,"move":false}}],"actions":{"edit":false,"delete":false,"move":false},"key":"basic_information"},{"id":"d6e5f546-73f8-45d4-96b5-fdd6e912ecad","is_visible":true,"title":"Endereço & Contato","items":[{"id":"40cf036c-6e70-4a5b-943c-e9df43cdfd46","fields":[{"title":"Endereço","type":"custom-form","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true},"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"fields":[{"title":"CEP","type":"text","id":"zipcode","options":[],"required":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Logradouro","type":"text","id":"adrress","options":[],"required":true,"readonly":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Número","type":"text","id":"number","options":[],"required":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Complemento","type":"text","id":"complement","options":[],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Bairro","type":"text","id":"neighborhood","options":[],"required":true,"readonly":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Cidade","type":"text","id":"city","options":[],"required":true,"readonly":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Estado","type":"text","id":"state","options":[],"required":true,"readonly":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null}],"name":"fahim"}],"actions":{"edit":false,"delete":false,"move":true}}],"actions":{"edit":false,"delete":false,"move":false},"key":"address_information"},{"id":"7d3e3733-15e0-440e-9e90-794f1e98b45c","is_visible":true,"title":"Educação & Experiência","items":[{"id":"9a3f0038-a6f5-4575-8c53-adc5b280cb22","fields":[{"title":"Formação","type":"custom-form","options":[],"required":true,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true},"fields":[{"title":"Nível","type":"radio","options":["Fundamental","Médio","Superior"],"required":true,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"is_visible":true,"value":null},{"title":"Instituição de Ensino","type":"text","options":[],"required":true,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"is_visible":true,"value":null},{"title":"Curso","type":"text","options":[],"required":true,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"is_visible":true,"value":null},{"title":"Situação","type":"radio","options":["Completo","Incompleto"],"required":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Inicio","type":"date","options":[],"required":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null},{"title":"Fim","type":"date","options":[],"required":true,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"value":null}],"duplicate":true,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"name":"fahim"},{"title":"Cursos","type":"custom-form","options":[],"required":false,"is_visible":true,"actions":{"edit":true,"delete":true,"move":true},"fields":[{"title":"Nível","type":"radio","options":["Técnico","Profissionalizante"],"required":false,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"is_visible":true},{"title":"Instituição de Ensino","type":"text","options":[],"required":false,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"is_visible":true},{"title":"Curso","type":"text","options":[],"required":false,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"is_visible":true},{"title":"Situação","type":"radio","options":["Completo","Incompleto"],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null}},{"title":"Inicio","type":"date","options":[],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null}},{"title":"Fim","type":"date","options":[],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null}}],"duplicate":true,"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"name":"fahim"},{"title":"Experiência Profissional","type":"custom-form","options":[],"required":false,"is_visible":true,"duplicate":true,"actions":{"edit":true,"delete":true,"move":true},"fields":[{"title":"Empresa","type":"text","options":[],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null}},{"title":"Função","type":"text","options":[],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null}},{"title":"Inicio","type":"date","options":[],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null}},{"title":"Fim","type":"date","options":[],"required":false,"is_visible":true,"fields":[],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null}}],"dateOptions":{"dateMode":"date","minDate":null,"maxDate":null},"name":"fahim"}],"actions":{"edit":true,"delete":true,"move":true}}],"actions":{"edit":true,"delete":true,"move":true}}]',
            'context' => 'app',
            'autoload' => 0,
            'public' => 1
        ],
        [
            'name' => 'career_page',
            'value' => '{"job_post_settings":{"content":{"title":"Junte-se a nós","subtitle":"Faça parte do nosso time","details":"Célio Junior","bodySection":[],"hero":true,"job_post_cover":"\\/public\\/images\\/job_post_cover.png"},"pageStyle":{"defaultView":[{"name":"Título","key":"title","fontSize":50,"fontWeight":700,"letterSpacing":1,"color":"#313131"},{"name":"Subtítulo","key":"sub-title","fontSize":30,"fontWeight":300,"letterSpacing":1,"color":"#afb1b6"},{"name":"Detalhes","key":"details","fontSize":20,"fontWeight":300,"letterSpacing":1,"color":"#3758b3"},{"name":"Títulos","key":"headings","fontSize":27,"fontWeight":600,"letterSpacing":0,"color":"#313131"},{"name":"Descrição","key":"description","fontSize":19,"fontWeight":300,"letterSpacing":0,"color":"#313131"}],"mobileView":[{"name":"Título","key":"title","fontSize":40,"fontWeight":700,"letterSpacing":1,"color":"#313131"},{"name":"Subtítulo","key":"sub-title","fontSize":25,"fontWeight":300,"letterSpacing":1,"color":"#afb1b6"},{"name":"Detalhes","key":"details","fontSize":16,"fontWeight":300,"letterSpacing":1,"color":"#3758b3"},{"name":"Títulos","key":"headings","fontSize":20,"fontWeight":600,"letterSpacing":0,"color":"#313131"},{"name":"Descrição","key":"description","fontSize":18,"fontWeight":300,"letterSpacing":0,"color":"#313131"}]},"pageBlocks":{"defaultView":{"header":true,"body":true,"footer":true,"logo":true},"mobileView":{"header":true,"body":true,"footer":true,"logo":true}}},"name":"Junte-se a nós","description":"Faça parte do nosso time"}',
            'context' => 'app',
            'autoload' => 0,
            'public' => 1
        ]
    ],
    'brand' => [
        ['name' => 'avatar', 'value' => null, 'context' => 'brand'],
        ['name' => 'address', 'value' => '', 'context' => 'brand'],
    ],
    'context' => [
        'app',
        'campaign',
        'list',
        'user',
        'segment',
        'subscriber',
        'brand',
        'role',
        'template'
    ],
    'time_format' => [
        'h',
        'H'
    ],
    'layouts' => [
        'ltr',
        'rtl'
    ],
    'currency_position' => [
        'prefix_only',
        'prefix_with_space',
        'suffix_only',
        'suffix_with_space'
    ],
    'amazon_ses' => [
        'hostname' => '',
        'access_key_id' => '',
        'secret_access_key' => '',
    ],
    'mailgun' => [
        'domain_name' => '',
        'api_key' => '',
        'webhook_key' => ''
    ],
    'mail_configs' => [
        'context' => '',
        'from_email' => '',
        'from_name' => ''
    ],
    'date_format' => [
        'd-m-Y',
        'm-d-Y',
        'Y-m-d',
        'm/d/Y',
        'd/m/Y',
        'Y/m/d',
        'm.d.Y',
        'd.m.Y',
        'Y.m.d'
    ],

    'decimal_separator' => [
        '.',
        ','
    ],

    'thousand_separator' => [
        '.',
        ',',
        ' '
    ],
    'number_of_decimal' => [
        '0',
        '2'
    ],
    'supported_mail_services' => [
        'amazon_ses' => 'Amazon SES',
        'mailgun' => 'Mailgun'
    ],
    'corn-job-context' => 'corn-job',
    'brand_default_prefix' => [
        'amazon_ses' => 'brand_default_amazon_ses',
        'mailgun' => 'brand_default_mailgun',
        'privacy' => 'brand_default_privacy',
        'notification' => 'brand_default_notification',
    ],
];
