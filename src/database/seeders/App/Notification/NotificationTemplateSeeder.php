<?php

namespace Database\Seeders\App\Notification;

use App\Models\Core\Notification\NotificationTemplate;
use App\Models\Core\Setting\NotificationEvent;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        NotificationEvent::withoutGlobalScope('name')->get()->map(function (NotificationEvent $event) {
            if ($this->checkCondition($event)) {
                [$name, $action] = explode('_', $event->name);
                $templates = [
                    'system' => '',
                    'subject' => '',
                    'content' => ''
                ];
                if (array_key_exists($event->name, $this->template())) {
                    $templates = $this->template()[$event->name];
                } elseif (array_key_exists($action, $this->template())) {
                    $templates = $this->template()[$action];
                }

                $mail = NotificationTemplate::query()->create([
                    'subject' => strtr($templates['subject'], [
                        '{resource}' => $name,
                        '{app_name}' => $event->type->alias == 'app' ? '{app_name}' : '{brand_name}'
                    ]),
                    'default_content' => strtr($templates['content'], [
                        '{resource}' => $name,
                        '{button_label}' => 'Ver ' . ucfirst($name)
                    ]),
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $database = NotificationTemplate::query()->create([
                    'subject' => null,
                    'default_content' => strtr($templates['system'], [
                        '{resource}' => $name
                    ]),
                    'custom_content' => null,
                    'type' => 'database'
                ]);

                $event->templates()->attach(
                    [$database->id, $mail->id]
                );

            } elseif ($event->name == 'user_invitation') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Convite para ingressar em {app_name}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Você foi convidado(a) por {action_by} a fazer parte da equipe em {app_name}. Será um prazer tê-lo(a) conosco!</p><br>
<p><a href="{invitation_url}" target="_blank" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none">Aceitar Convite</a></p><br>
<p></p><p>Atenciosamente,</p><p>{app_name}</p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } elseif ($event->name == 'password_reset') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Instruções para redefinir sua senha',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Recebemos uma solicitação para redefinir a senha da sua conta. Para criar uma nova senha, clique no botão abaixo.</p><br>
<p><a href="{reset_password_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">Redefinir Senha</a></p><br>
<p>Se você não solicitou esta alteração, por favor, desconsidere este e-mail.</p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach([$mail->id]);

            } elseif ($event->name == 'disqualification_mail_for_candidate') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Atualização sobre sua candidatura para a vaga {job_post}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {candidate_name},</span><br></p><p>Agradecemos imensamente o seu interesse na vaga de {job_post} e o tempo dedicado ao nosso processo seletivo. A sua candidatura foi cuidadosamente avaliada pela nossa equipe.
<br><br>Gostaríamos de informar que, nesta etapa, optamos por um perfil que se alinha mais de perto com as nossas necessidades atuais. No entanto, o seu currículo permanecerá em nosso banco de dados para futuras oportunidades.
<br><br>Desejamos muito sucesso em sua jornada profissional.
<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } elseif ($event->name == 'create_event_mail_for_candidate') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Convite para {event_type} - Vaga de {job_post}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {candidate_name},</span><br></p><p>Agradecemos seu interesse na vaga de {job_post}. Gostaríamos de convidá-lo(a) para a próxima etapa, um(a) {event_type}.
<br><br>Confira os detalhes do evento abaixo:
<br><br><strong>Descrição:</strong> {description}.
<br><strong>Local:</strong> {location}.
<br><strong>Horário de Início:</strong> <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{start_at_query}">{start_at} (UTC)</a>
<br><strong>Horário de Término:</strong> <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{end_at_query}">{end_at} (UTC)</a>
<br><br><strong>Detalhes da Reunião Zoom:</strong>
<br><strong>ID da Reunião:</strong> {zoom_meeting_id}.
<br><strong>Tópico:</strong> {topic}.
<br><strong>Duração:</strong> {duration} minutos.
<br><p><a href="{zoom_join_url}" target="_blank" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none">Acessar Reunião Zoom</a></p>.
<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } elseif ($event->name == 'job_apply_response_for_candidate') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Confirmação de candidatura para a vaga de {job_post}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {candidate_name},</span><br></p><p>Sua candidatura para a vaga de {job_post} foi recebida com sucesso.
<br><br>Agradecemos o seu interesse e entraremos em contato com você em breve sobre as próximas etapas.
<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } elseif ($event->name == 'job_alert') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Novas Oportunidades de Emprego em {app_name}!',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {candidate_name},</span><br></p><p>
Temos uma ótima notícia! Novas e empolgantes vagas de emprego acabam de ser publicadas em nossa plataforma. Se você está em busca de novos desafios, confira as últimas oportunidades abaixo:
<br><br>
{job_post_card}
<br><br>
<a href="{career_page_link}" style="cursor:pointer;  ">Ver todas as vagas</a>
<br><br>
Atenciosamente,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } elseif ($event->name == 'job_applied') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Novo candidato para a vaga {job_post}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>O candidato {candidate_name} se candidatou para a vaga {job_post}.<br><br>Para visualizar a candidatura, <a href="{job_application_url}">clique aqui</a>.<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $database = NotificationTemplate::query()->create([
                    'subject' => 'Novo candidato',
                    'default_content' => 'Um novo candidato, {candidate_name}, se candidatou para a vaga {job_post}.',
                    'custom_content' => null,
                    'type' => 'database'
                ]);

                $event->templates()->attach([$mail->id, $database->id]);
            }
            elseif ($event->name == 'note_created') {
                $mail = NotificationTemplate::query()->create([
                    'subject' => 'Nova nota criada para o candidato {candidate_name}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Uma nova nota foi criada por {noted_by} para o candidato {candidate_name}, referente à vaga {job_post}.<br><br>Para visualizar a nota, <a href="{note_url}">clique aqui</a>.<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $database = NotificationTemplate::query()->create([
                    'subject' => 'Nova nota criada',
                    'default_content' => 'Uma nova nota foi criada por {noted_by} para o candidato {candidate_name}, referente à vaga {job_post}.',
                    'custom_content' => null,
                    'type' => 'database'
                ]);

                $event->templates()->attach([$mail->id, $database->id]);
            }


        });
        $this->enableForeignKeys();
    }

    private function checkCondition($event)
    {
        return $event->name != 'user_invitation'
            && $event->name != 'password_reset'
            && $event->name != 'disqualification_mail_for_candidate'
            && $event->name != 'create_event_mail_for_candidate'
            && $event->name != 'job_apply_response_for_candidate'
            && $event->name != 'job_alert'
            && $event->name != 'job_applied'
            && $event->name != 'note_created';
    }

    public function template()
    {
        return [
            'user_joined' => [
                'system' => 'Um novo usuário se juntou a {app_name}.',
                'subject' => 'Novo usuário em {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Com grande satisfação, informamos que um novo usuário, {name}, se juntou à nossa plataforma. Esperamos que a colaboração seja produtiva e proveitosa.</p><br>
        <p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">Ver Perfil</a></p><br>
        <p></p><p>Agradecemos sua atenção.</p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'user_invited' => [
                'system' => '{name} foi convidado por {action_by}.',
                'subject' => 'Novo convite de usuário em {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Informamos que um novo usuário, {name}, foi convidado para a nossa plataforma por {action_by}.</p><br>
        <p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">Ver Convite</a></p><br>
        <p></p><p>Agradecemos sua atenção.</p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'created' => [
                'system' => 'Um novo {resource} chamado {name} foi criado por {action_by}.',
                'subject' => 'Novo {resource} criado em {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Um novo {resource} chamado {name} foi criado na plataforma por {action_by}. Para mais detalhes, por favor, clique no botão abaixo.</p><br>
        <p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; ; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>
        <p></p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'updated' => [
                'system' => 'Um {resource} chamado {name} foi atualizado por {action_by}.',
                'subject' => '{resource} atualizado em {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>O {resource} chamado {name} foi atualizado na plataforma por {action_by}. Para visualizar as alterações, clique no botão abaixo.</p><br>
        <p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>
        <p></p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'deleted' => [
                'system' => 'Um {resource} chamado {name} foi excluído por {action_by}.',
                'subject' => '{resource} excluído de {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Informamos que o {resource} chamado {name} foi excluído da plataforma por {action_by}.</p>
        <p></p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'confirmed' => [
                'system' => 'Um {resource} chamado {name} foi confirmado por {action_by}.',
                'subject' => '{resource} confirmado',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>O {resource} chamado {name} foi confirmado por {action_by}. Para mais informações, clique no botão abaixo.</p><br>
        <p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>
        <p></p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'sent' => [
                'system' => 'Um {resource} chamado {name} foi enviado com sucesso por {action_by}.',
                'subject' => '{resource} enviado com sucesso',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>O {resource} chamado {name} foi enviado com sucesso por {action_by}. Para visualizar o recurso, clique no botão abaixo.</p><br>
        <p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>
        <p></p><p>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'candidate_disqualified' => [
                'system' => '{candidate_name} foi desqualificado por {action_by} para o cargo de {job_post}.',
                'subject' => 'Atualização sobre sua candidatura para a vaga {job_post}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Informamos que o candidato {candidate_name} foi desclassificado do processo seletivo para o cargo de {job_post}.<br><br><strong>Motivo da desqualificação:</strong> {disqualification_reason}.<br><br>Ação realizada por: {action_by}.<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'event_created' => [
                'system' => '{event_type} com {candidate_name} agendado para {event_time}.',
                'subject' => 'Convite para {event_type} - Vaga de {job_post}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Você está sendo convidado(a) por {action_by} para um {event_type} com {candidate_name}, referente à vaga de {job_post}.<br><br><strong>Detalhes do evento:</strong><br><strong>Horário de Início:</strong> <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{start_at_query}">{start_at} (UTC)</a><br><strong>Horário de Término:</strong> <a href="https://www.timeanddate.com/worldclock/fixedtime.html?{end_at_query}">{end_at} (UTC)</a><br><br><strong>Local:</strong> <a href="{event_url}">{event_location}</a><br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'job_applied' => [
                'system' => 'Um novo candidato, {candidate_name}, se candidatou para a vaga {job_post}.',
                'subject' => 'Novo candidato para a vaga {job_post}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>O candidato {candidate_name} se candidatou para a vaga {job_post}.<br><br>Para visualizar a candidatura, <a href="{job_application_url}">clique aqui</a>.<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'note_created' => [
                'system' => 'Uma nova nota foi criada por {noted_by} para o candidato {candidate_name}.',
                'subject' => 'Nova nota para o candidato {candidate_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
        <p></p><p><span style="background-color: var(--form-control-bg); color: var(--default-font-color);">Olá {receiver_name},</span><br></p><p>Uma nova nota foi criada por {noted_by} para o candidato {candidate_name}, referente à vaga {job_post}.<br><br>Para visualizar a nota, <a href="{note_url}">clique aqui</a>.<br><br>Atenciosamente,</p><p>{app_name}</p><p></p><p></p>'
            ]
        ];
    }
}