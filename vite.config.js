import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
	plugins: [
		laravel({
			input: [
				'resources/css/style.css',
				'resources/css/gestion.css',
				'resources/css/login.css',
				'resources/css/index.css',
				'resources/css/boutique.css',
				'resources/css/pro.css',
				'resources/css/event/noel.css',
				'resources/css/event/halloween.css',
				'resources/css/blog.css',
				'resources/css/ticket.css',
				'resources/css/panier.css',
				'resources/css/checkout.css',
				'resources/js/app.js',
				'resources/js/auth.js',
			],
			refresh: true,
		}),
	],
});
