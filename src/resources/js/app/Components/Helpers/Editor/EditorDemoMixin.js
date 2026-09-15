export default {
    data() {
        return {
            content: {
                title: 'Título da Vaga',
                subtitle: 'Descrição',
                details: 'Tipo de vaga - Localização',
                bodySection: [
                    {
                        headings: 'Sobre a Vaga',
                        description: ``
                    },
                    {
                        headings: 'Requisitos',
                        description: ``
                    },
                    {
                        headings: 'Responsabilidades',
                        description: ``
                    },
                    {
                        headings: 'Benefícios',
                        description: ``
                    }
                ]
            },
            pageStyle: {
                defaultView: [
                    {
                        name: 'Título',
                        key: 'title',
                        fontSize: 50,
                        fontWeight: 700,
                        letterSpacing: 1,
                        color: '#313131'
                    },
                    {
                        name: 'Subtítulo',
                        key: 'sub-title',
                        fontSize: 30,
                        fontWeight: 300,
                        letterSpacing: 1,
                        color: '#afb1b6'
                    },
                    {
                        name: 'Detalhes',
                        key: 'details',
                        fontSize: 20,
                        fontWeight: 500,
                        letterSpacing: 1,
                        color: '#00000',
                    },
                    {
                        name: 'Cabeçalhos',
                        key: 'headings',
                        fontSize: 27,
                        fontWeight: 600,
                        letterSpacing: 0,
                        color: '#313131',
                    },
                    {
                        name: 'Descrição',
                        key: 'description',
                        fontSize: 16,
                        fontWeight: 300,
                        letterSpacing: 0,
                        color: '#6c757d',
                    }
                ],
                mobileView: [
                    {
                        name: 'Título',
                        key: 'title',
                        fontSize: 40,
                        fontWeight: 700,
                        letterSpacing: 1,
                        color: '#313131'
                    },
                    {
                        name: 'Subtítulo',
                        key: 'sub-title',
                        fontSize: 25,
                        fontWeight: 300,
                        letterSpacing: 1,
                        color: '#afb1b6'
                    },
                    {
                        name: 'Detalhes',
                        key: 'details',
                        fontSize: 16,
                        fontWeight: 500,
                        letterSpacing: 1,
                        color: '#00000',
                    },
                    {
                        name: 'Cabeçalhos',
                        key: 'headings',
                        fontSize: 20,
                        fontWeight: 600,
                        letterSpacing: 0,
                        color: '#313131',
                    },
                    {
                        name: 'Descrição',
                        key: 'description',
                        fontSize: 14,
                        fontWeight: 300,
                        letterSpacing: 0,
                        color: '#6c757d',
                    }
                ],
            },
            pageBlocks: {
                defaultView: {
                    header: true,
                    body: true,
                    footer: true,
                    logo: true
                },
                mobileView: {
                    header: true,
                    body: true,
                    footer: true,
                    logo: true,
                }
            },
        }
    }
}
