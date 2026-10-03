<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTest extends Command
{
    protected $signature   = 'mail:test {destinataire : adresse qui recevra le mail de test}';
    protected $description = 'Envoie un mail de test pour vérifier la configuration SMTP (.env)';

    public function handle(): int
    {
        $mailer = config('mail.default');
        $smtp   = config('mail.mailers.smtp');

        $this->components->info('Configuration utilisée');
        $this->table(['Paramètre', 'Valeur'], [
            ['MAIL_MAILER',        $mailer],
            ['MAIL_HOST : PORT',   ($smtp['host'] ?? '-') . ' : ' . ($smtp['port'] ?? '-')],
            ['MAIL_USERNAME',      $smtp['username'] ?: '(vide)'],
            ['MAIL_PASSWORD',      $smtp['password'] ? 'défini (' . strlen($smtp['password']) . ' caractères)' : '(vide)'],
            ['Expéditeur',         config('mail.from.name') . ' <' . config('mail.from.address') . '>'],
            ['Formulaire pro →',   config('mail.pro_contact_to')],
        ]);

        if (in_array($mailer, ['log', 'array'], true)) {
            $this->components->warn("MAIL_MAILER={$mailer} : le mail ne partira pas vraiment (il sera seulement écrit dans storage/logs).");
        }

        if ($mailer === 'smtp' && strlen((string) $smtp['password']) !== 16) {
            $this->components->warn('Un mot de passe d\'application Google fait 16 caractères, sans espaces.');
        }

        try {
            Mail::raw(
                "Ceci est un mail de test envoyé depuis La Maison des Meringues.\nSi vous le lisez, la configuration mail fonctionne.",
                fn ($m) => $m->to($this->argument('destinataire'))->subject('Test mail — La Maison des Meringues')
            );
        } catch (\Throwable $e) {
            $this->components->error('Échec de l\'envoi : ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->components->info('Mail envoyé à ' . $this->argument('destinataire') . ' (pense à vérifier les spams).');
        return self::SUCCESS;
    }
}
