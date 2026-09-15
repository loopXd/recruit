<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de Idioma para Validação
    |--------------------------------------------------------------------------
    |
    | As seguintes linhas de idioma contêm as mensagens de erro padrão usadas pela
    | classe do validador. Algumas dessas regras têm várias versões, como
    | as regras de tamanho. Sinta-se à vontade para ajustar cada uma dessas mensagens aqui.
    |
    */

    'accepted' => 'O campo :attribute deve ser aceito.',
    'active_url' => 'O campo :attribute não é uma URL válida.',
    'after' => 'O campo :attribute deve ser uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute deve ser uma data posterior ou igual a :date.',
    'alpha' => 'O campo :attribute pode conter apenas letras.',
    'alpha_dash' => 'O campo :attribute pode conter apenas letras, números, traços e sublinhados.',
    'alpha_num' => 'O campo :attribute pode conter apenas letras e números.',
    'array' => 'O campo :attribute deve ser um array.',
    'before' => 'O campo :attribute deve ser uma data anterior a :date.',
    'before_or_equal' => 'O campo :attribute deve ser uma data anterior ou igual a :date.',
    'between' => [
        'numeric' => 'O campo :attribute deve estar entre :min e :max.',
        'file' => 'O campo :attribute deve ter entre :min e :max kilobytes.',
        'string' => 'O campo :attribute deve ter entre :min e :max caracteres.',
        'array' => 'O campo :attribute deve ter entre :min e :max itens.',
    ],
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação do campo :attribute não corresponde.',
    'date' => 'O campo :attribute não é uma data válida.',
    'date_equals' => 'O campo :attribute deve ser uma data igual a :date.',
    'date_format' => 'O campo :attribute não corresponde ao formato :format.',
    'different' => 'Os campos :attribute e :other devem ser diferentes.',
    'digits' => 'O campo :attribute deve ter :digits dígitos.',
    'digits_between' => 'O campo :attribute deve ter entre :min e :max dígitos.',
    'dimensions' => 'O campo :attribute tem dimensões de imagem inválidas.',
    'distinct' => 'O campo :attribute tem um valor duplicado.',
    'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'ends_with' => 'O campo :attribute deve terminar com um dos seguintes valores: :values',
    'exists' => 'O campo :attribute selecionado é inválido.',
    'file' => 'O campo :attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute deve ter um valor.',
    'gt' => [
        'numeric' => 'O campo :attribute deve ser maior que :value.',
        'file' => 'O campo :attribute deve ser maior que :value kilobytes.',
        'string' => 'O campo :attribute deve ter mais de :value caracteres.',
        'array' => 'O campo :attribute deve ter mais de :value itens.',
    ],
    'gte' => [
        'numeric' => 'O campo :attribute deve ser maior ou igual a :value.',
        'file' => 'O campo :attribute deve ser maior ou igual a :value kilobytes.',
        'string' => 'O campo :attribute deve ter mais de :value caracteres ou ser igual.',
        'array' => 'O campo :attribute deve ter :value itens ou mais.',
    ],
    'image' => 'O campo :attribute deve ser uma imagem.',
    'in' => 'O campo :attribute selecionado é inválido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'ip' => 'O campo :attribute deve ser um endereço IP válido.',
    'ipv4' => 'O campo :attribute deve ser um endereço IPv4 válido.',
    'ipv6' => 'O campo :attribute deve ser um endereço IPv6 válido.',
    'json' => 'O campo :attribute deve ser uma string JSON válida.',
    'lt' => [
        'numeric' => 'O campo :attribute deve ser menor que :value.',
        'file' => 'O campo :attribute deve ser menor que :value kilobytes.',
        'string' => 'O campo :attribute deve ter menos de :value caracteres.',
        'array' => 'O campo :attribute deve ter menos de :value itens.',
    ],
    'lte' => [
        'numeric' => 'O campo :attribute deve ser menor ou igual a :value.',
        'file' => 'O campo :attribute deve ser menor ou igual a :value kilobytes.',
        'string' => 'O campo :attribute deve ter menos de :value caracteres ou ser igual.',
        'array' => 'O campo :attribute não deve ter mais de :value itens.',
    ],
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file' => 'O campo :attribute não pode ser maior que :max kilobytes.',
        'string' => 'O campo :attribute não pode ser maior que :max caracteres.',
        'array' => 'O campo :attribute não pode ter mais de :max itens.',
    ],
    'mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => 'O campo :attribute deve ser pelo menos :min.',
        'file' => 'O campo :attribute deve ter pelo menos :min kilobytes.',
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
        'array' => 'O campo :attribute deve ter pelo menos :min itens.',
    ],
    'not_in' => 'O campo :attribute selecionado é inválido.',
    'not_regex' => 'O formato do campo :attribute é inválido.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'present' => 'O campo :attribute deve estar presente.',
    'regex' => 'O formato do campo :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other for :value.',
    'required_unless' => 'O campo :attribute é obrigatório, a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando todos os valores :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum dos valores :values estão presentes.',
    'same' => 'Os campos :attribute e :other devem ser iguais.',
    'size' => [
        'numeric' => 'O campo :attribute deve ser :size.',
        'file' => 'O campo :attribute deve ter :size kilobytes.',
        'string' => 'O campo :attribute deve ter :size caracteres.',
        'array' => 'O campo :attribute deve conter :size itens.',
    ],
    'starts_with' => 'O campo :attribute deve começar com um dos seguintes valores: :values',
    'string' => 'O campo :attribute deve ser uma string.',
    'timezone' => 'O campo :attribute deve ser um fuso horário válido.',
    'unique' => 'O campo :attribute já foi utilizado.',
    'uploaded' => 'O upload do campo :attribute falhou.',
    'url' => 'O formato do campo :attribute é inválido.',
    'uuid' => 'O campo :attribute deve ser um UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Linhas de Idioma para Validação Personalizadas
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar mensagens de validação personalizadas para atributos
    | usando a convenção "atributo.regra" para nomear as linhas. Isso torna rápido
    | especificar uma linha de idioma personalizada para uma determinada regra de atributo.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atributos de Validação Personalizados
    |--------------------------------------------------------------------------
    |
    | As seguintes linhas de idioma são usadas para trocar os espaços reservados
    | do atributo com algo mais amigável ao leitor, como Endereço de E-Mail
    | em vez de "email". Isso simplesmente nos ajuda a tornar as mensagens um pouco mais limpas.
    |
    */

    'must_be' => 'O campo :name deve estar em :other',

    'attributes' => [
        'core' => [
            'access' => [
                'permissions' => [
                    'associated_roles' => 'Papéis Associados',
                    'dependencies' => 'Dependências',
                    'display_name' => 'Nome de Exibição',
                    'group' => 'Grupo',
                    'group_sort' => 'Ordenação do Grupo',

                    'groups' => [
                        'name' => 'Nome do Grupo',
                    ],

                    'name' => 'Nome',
                    'first_name' => 'Primeiro Nome',
                    'last_name' => 'Sobrenome',
                    'system' => 'Sistema',
                ],

                'roles' => [
                    'associated_permissions' => 'Permissões Associadas',
                    'name' => 'Nome',
                    'sort' => 'Ordenação',
                ],

                'users' => [
                    'active' => 'Ativo',
                    'associated_roles' => 'Papéis Associados',
                    'confirmed' => 'Confirmado',
                    'email' => 'Endereço de E-mail',
                    'name' => 'Nome',
                    'last_name' => 'Sobrenome',
                    'first_name' => 'Primeiro Nome',
                    'other_permissions' => 'Outras Permissões',
                    'password' => 'Senha',
                    'password_confirmation' => 'Confirmação da Senha',
                    'send_confirmation_email' => 'Enviar E-mail de Confirmação',
                    'timezone' => 'Fuso Horário',
                    'language' => 'Idioma',
                ],
            ],
        ],

        'frontend' => [
            'avatar' => 'Localização do Avatar',
            'email' => 'Endereço de E-mail',
            'first_name' => 'Primeiro Nome',
            'last_name' => 'Sobrenome',
            'name' => 'Nome Completo',
            'password' => 'Senha',
            'password_confirmation' => 'Confirmação da Senha',
            'phone' => 'Telefone',
            'message' => 'Mensagem',
            'new_password' => 'Nova Senha',
            'new_password_confirmation' => 'Confirmação da Nova Senha',
            'old_password' => 'Senha Antiga',
            'timezone' => 'Fuso Horário',
            'language' => 'Idioma',
        ],
    ],
];
