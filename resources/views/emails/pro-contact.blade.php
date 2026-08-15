<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Nouvelle demande professionnelle</title>
</head>
<body style="margin:0;padding:0;background:#F5EFE8;font-family:Georgia,serif;color:#231508;">
	<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F5EFE8;padding:32px 16px;">
		<tr>
			<td align="center">
				<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#FFFCF9;border-radius:14px;overflow:hidden;border:1px solid #EAE0D4;">
					<tr>
						<td style="background:#2E1C0E;padding:28px 32px;">
							<span style="font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#C4527A;font-weight:600;font-family:Arial,sans-serif;">Espace professionnel</span>
							<h1 style="margin:6px 0 0;font-size:22px;color:#FFFCF9;font-weight:700;">Nouvelle demande de contact</h1>
						</td>
					</tr>
					<tr>
						<td style="padding:28px 32px;">
							<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial,sans-serif;font-size:14px;line-height:1.7;color:#231508;">
								<tr><td style="padding:6px 0;width:140px;color:#9E7D65;font-size:12px;text-transform:uppercase;letter-spacing:.06em;">Nom</td><td style="padding:6px 0;font-weight:600;">{{ $prenom }} {{ $nom }}</td></tr>
								@if(!empty($societe))
								<tr><td style="padding:6px 0;color:#9E7D65;font-size:12px;text-transform:uppercase;letter-spacing:.06em;">Société</td><td style="padding:6px 0;">{{ $societe }}</td></tr>
								@endif
								<tr><td style="padding:6px 0;color:#9E7D65;font-size:12px;text-transform:uppercase;letter-spacing:.06em;">Email</td><td style="padding:6px 0;"><a href="mailto:{{ $email }}" style="color:#9B2257;">{{ $email }}</a></td></tr>
								@if(!empty($telephone))
								<tr><td style="padding:6px 0;color:#9E7D65;font-size:12px;text-transform:uppercase;letter-spacing:.06em;">Téléphone</td><td style="padding:6px 0;">{{ $telephone }}</td></tr>
								@endif
								<tr><td style="padding:6px 0;color:#9E7D65;font-size:12px;text-transform:uppercase;letter-spacing:.06em;">Activité</td><td style="padding:6px 0;">{{ $type_pro_label }}</td></tr>
							</table>
							<div style="margin-top:20px;padding-top:20px;border-top:1px solid #EAE0D4;">
								<p style="margin:0 0 8px;font-family:Arial,sans-serif;font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#9E7D65;">Message</p>
								<p style="margin:0;font-family:Arial,sans-serif;font-size:14px;line-height:1.7;color:#231508;white-space:pre-line;">{{ $contenu_message }}</p>
							</div>
						</td>
					</tr>
				</table>
				<p style="font-family:Arial,sans-serif;font-size:11px;color:#9E7D65;margin-top:16px;">
					Reçu via le formulaire de l'espace professionnel — La Maison des Meringues
				</p>
			</td>
		</tr>
	</table>
</body>
</html>
