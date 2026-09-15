<?php

namespace App\Export;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllCandidateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private Collection $applicants;

    public function __construct(Collection $applicants)
    {
        $this->applicants = $applicants;
    }

    public function headings(): array
    {
        return [
            __t('name'),
            __t('mother_name'),
            __t('father_name'),
            __t('rg'),
            __t('cpf'),
            __t('email'),
            __t('phone'),
            __t('gender'),
            __t('birth'),
            __t('applied_job'),
            // Endereço
            'CEP',
            'Logradouro',
            'Número',
            'Complemento',
            'Bairro',
            'Cidade',
            'Estado',
            // Educação (formação mais recente)
            'Nível de Formação',
            'Instituição de Ensino',
            'Curso',
            'Situação da Formação',
            'Início da Formação',
            'Fim da Formação',
            // Experiência Profissional (mais recente)
            'Empresa',
            'Função',
            'Início da Experiência',
            'Fim da Experiência',
        ];
    }

    public function array(): array
    {
        return $this->applicants->map(fn($applicant) => $this->getApplicantRow($applicant))->toArray();
    }

    protected function getApplicantRow($applicant): array
    {
        $formAnswers = $this->getLatestAnswerData($applicant);

        $educationFields = $this->findCustomFormFields($formAnswers, 'Formação');
        $experienceFields = $this->findCustomFormFields($formAnswers, 'Experiência Profissional');

        return [
            $this->upper($applicant->name),
            $this->upper($applicant->mother_name),
            $this->upper($applicant->father_name),
            $this->upper($applicant->rg),
            $this->upper($applicant->cpf),
            $this->upper($applicant->email),
            $this->upper($applicant->phone),
            $this->upper(match($applicant->gender) {
                'male' => 'Masculino',
                'female' => 'Feminino',
                'other' => 'Outro',
                default => 'Outro',
            }),
            optional($applicant->date_of_birth)?->format('d/m/Y'),
            $this->upper(implode(', ', $applicant->jobApplicants->pluck('jobPost.name')->toArray())),
            // Endereço
            $this->upper($this->findValueByIdRecursive($formAnswers, 'zipcode')),
            $this->upper($this->findValueByIdRecursive($formAnswers, 'adrress') ?? $this->findValueByIdRecursive($formAnswers, 'address')),
            $this->upper($this->findValueByIdRecursive($formAnswers, 'number')),
            $this->upper($this->findValueByIdRecursive($formAnswers, 'complement')),
            $this->upper($this->findValueByIdRecursive($formAnswers, 'neighborhood')),
            $this->upper($this->findValueByIdRecursive($formAnswers, 'city') ?? $this->findValueByIdRecursive($formAnswers, 'cidade')),
            $this->upper($this->findValueByIdRecursive($formAnswers, 'state')),
            // Educação
            $this->upper($this->findValueByTitle($educationFields, 'Nível')),
            $this->upper($this->findValueByTitle($educationFields, 'Instituição de Ensino')),
            $this->upper($this->findValueByTitle($educationFields, 'Curso')),
            $this->upper($this->findValueByTitle($educationFields, 'Situação')),
            $this->formatDate($this->findValueByTitle($educationFields, 'Inicio')),
            $this->formatDate($this->findValueByTitle($educationFields, 'Fim')),
            // Experiência
            $this->upper($this->findValueByTitle($experienceFields, 'Empresa')),
            $this->upper($this->findValueByTitle($experienceFields, 'Função')),
            $this->formatDate($this->findValueByTitle($experienceFields, 'Inicio')),
            $this->formatDate($this->findValueByTitle($experienceFields, 'Fim')),
        ];
    }

    /**
     * Converte para maiúsculo apenas na saída do arquivo exportado,
     * sem afetar o dado original salvo no banco. Usa mb_strtoupper
     * (em vez de strtoupper) para converter corretamente acentos e
     * cedilha (ex: "ç" -> "Ç"), já que o strtoupper nativo do PHP não
     * lida bem com caracteres multibyte.
     */
    protected function upper(?string $value): ?string
    {
        return $value !== null ? mb_strtoupper($value, 'UTF-8') : $value;
    }

    protected function formatDate(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable $e) {
            return $value;
        }
    }

    /**
     * Pega o JSON do formulário de candidatura (apply_form_setting) da
     * candidatura mais recente do candidato. Esse campo, salvo na própria
     * tabela job_applicants, é o formulário de candidatura completo
     * (inclui o bloco de endereço/cidade), diferente da relação "answers"
     * (tabela application_answers), que guarda apenas respostas avulsas de
     * perguntas customizadas de entrevista — uma linha por pergunta — e
     * por isso não é uma fonte confiável para dados de endereço.
     */
    protected function getLatestAnswerData($applicant): array
    {
        $jobApplicant = $applicant->jobApplicants->sortByDesc('created_at')->first();

        if (!$jobApplicant) {
            return [];
        }

        // Usamos o valor bruto (sem o cast 'object' do model) para poder
        // decodificar como array associativo, que é o formato esperado
        // pelos helpers recursivos abaixo (findValueByIdRecursive etc).
        $raw = $jobApplicant->getRawOriginal('apply_form_setting');

        if (!$raw) {
            return [];
        }

        return json_decode($raw, true) ?? [];
    }

    /**
     * O JSON de "answer" é um array dinâmico de seções/itens/campos
     * (form builder). Essa função varre a árvore recursivamente
     * procurando o campo composto (type: custom-form) cujo "title"
     * bate com o informado (ex: "Endereço", "Formação"), e retorna
     * os sub-campos dele. Se houver mais de um bloco com o mesmo
     * título (ex: múltiplas formações), retorna o primeiro encontrado.
     */
    protected function findCustomFormFields($nodes, string $title): array
    {
        if (!is_array($nodes)) {
            return [];
        }

        if (($nodes['title'] ?? null) === $title && array_key_exists('fields', $nodes)) {
            return $nodes['fields'] ?? [];
        }

        foreach ($nodes as $value) {
            if (is_array($value)) {
                $result = $this->findCustomFormFields($value, $title);
                if (!empty($result)) {
                    return $result;
                }
            }
        }

        return [];
    }

    protected function findValueById(array $fields, string $id): ?string
    {
        foreach ($fields as $field) {
            if (($field['id'] ?? null) === $id) {
                return $field['value'] ?? null;
            }
        }

        return null;
    }

    protected function findValueByIdRecursive($nodes, string $id): ?string
    {
        if (!is_array($nodes)) {
            return null;
        }

        if (($nodes['id'] ?? null) === $id && array_key_exists('value', $nodes)) {
            return $nodes['value'];
        }

        foreach ($nodes as $value) {
            if (is_array($value)) {
                $result = $this->findValueByIdRecursive($value, $id);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    protected function findValueByTitle(array $fields, string $title): ?string
    {
        foreach ($fields as $field) {
            if (($field['title'] ?? null) === $title) {
                return $field['value'] ?? null;
            }
        }

        return null;
    }
}