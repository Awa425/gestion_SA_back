<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use App\Models\Apprenant;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class SendMail extends Notification
{
    use Queueable;
    public $apprenant;
    // public $notifiable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {

        // $this->notifiable = $notifiable;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */

    public function toMail(object $notifiable): MailMessage
    {


        return (new MailMessage)

            ->subject('Admission à Sonatel Academy')


            ->greeting('Bonjour' . $notifiable->prenom . ',')

            ->line('C\'est avec une immense joie que nous vous annonçons que vous avez brillamment réussi le test d\'entrée à notre école !')
            ->line('Votre détermination, votre engagement et votre talent ont vraiment fait la différence.')
            ->line('Détails de votre réussite :')
            ->line('Le début de votre aventure éducative est prévu pour le 16 Janvier 2024.')
            ->line('Félicitations pour cette réalisation exceptionnelle ! Nous croyons en votre potentiel et sommes impatients de vous accompagner dans votre parcours académique. Votre réussite inspire et motive toute notre communauté.')
            ->line('Nous sommes fiers de vous accueillir parmi nous.')
            ->line('-Matricule: ' . $notifiable->matricule)
            ->line('- Nom: ' . $notifiable->nom )
            ->line('-Prenom: ' . $notifiable->prenom)
            ->line('-Date de naissance: ' . $notifiable->date_naissance)
            ->action('Télécharger l\'application mobile', url('https://play.google.com/store/apps/details?id=com.gestionodc.gestion_sa'))
            ->line('tes informations pour se connecter dans l\'application :')
            ->line('-email: ' . $notifiable->email)
            ->line('-mot de passe: Passer')
            ->line('À bientôt sur le campus !')

            // ->attachData(file_get_contents($logoUrl), 'logo.png', ['mime' => 'image/png'])
            ->salutation(new HtmlString('Merci pour votre participation.'));
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
