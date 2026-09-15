export const getApplyFormBasicInformation = ($t) => {
    return [
        {
            id: 1,
            name: 'first_name',
            type: 'text',
            show: true,
            require: true
        },
        {
            id: 2,
            name: 'last_name',
            type: 'text',
            show: true,
            require: true
        },
        {
            id: 3,
            name: 'email',
            type: 'email',
            show: true,
            require: true
        },
        {
            id: 4,
            name: 'phone',
            type: 'tel-input',
            show: true,
            require: true
        },
        {
            id: 5,
            name: 'gender',
            type: 'radio',
            show: true,
            require: false,
            options: ['male', 'female', 'other']
        },
        {
            id: 6,
            name: 'date_of_birth',
            type: 'date',
            show: true,
            require: false,
        },
        {
            id: 7,
            name: 'mother_name',
            type: 'text',
            show: true,
            require: false,
        },
        {
            id: 8,
            name: 'father_name',
            type: 'text',
            show: true,
            require: false,
        },
        {
            id: 9,
            name: 'rg',
            type: 'text',
            show: true,
            require: false,
        },
        {
            id: 10,
            name: 'cpf',
            type: 'text',
            show: true,
            require: false,
        },
    ]
}
