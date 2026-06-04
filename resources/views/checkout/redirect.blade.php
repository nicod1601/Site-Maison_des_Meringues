<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Redirection vers le paiement...</title>
</head>
<body>
	<p style="font-family:sans-serif;text-align:center;margin-top:3rem;">
		Redirection vers le paiement sécurisé CIC…
	</p>

	<form id="monetico-form" action="{{ $url }}" method="POST">
		@foreach($fields as $name => $value)
			<input type="hidden" name="{{ $name }}" value="{{ $value }}">
		@endforeach
	</form>

	<script>document.getElementById('monetico-form').submit();</script>
</body>
</html>
