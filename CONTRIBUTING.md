# Guide de Contribution - StudiosDB

Merci de votre intérêt pour contribuer à StudiosDB ! 🥋

## 🚀 Comment contribuer

### Signaler un bug

1. Vérifiez que le bug n'a pas déjà été signalé dans les [Issues](https://github.com/lalphadb/studiosunisdb/issues)
2. Créez une nouvelle issue avec le template "Bug Report"
3. Incluez les détails techniques (PHP, Laravel, navigateur)
4. Ajoutez des captures d'écran si pertinent

### Proposer une fonctionnalité

1. Créez une issue avec le template "Feature Request"
2. Décrivez le besoin métier et la solution proposée
3. Attendez l'approbation avant de commencer le développement
4. Utilisez les [Discussions](https://github.com/lalphadb/studiosunisdb/discussions) pour les grandes idées

### Développement

#### Configuration

\`\`\`bash
# Fork et clone
git clone https://github.com/lalphadb/studiosunisdb.git
cd studiosunisdb

# Branche de développement
git checkout -b feature/nom-fonctionnalite

# Installation
composer install
npm install
cp .env.example .env
php artisan key:generate
\`\`\`

#### Standards de code

- Suivre PSR-12 pour le formatage PHP
- Utiliser les FormRequests pour la validation
- Implémenter les Policies pour les autorisations
- Documenter les méthodes publiques avec PHPDoc
- Tests unitaires requis pour les nouvelles fonctionnalités
- Utiliser le système HasMiddleware de Laravel 12.19
- Respecter l'architecture multi-tenant existante

#### Structure du code

\`\`\`php
// Exemple de contrôleur admin
class ExempleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\\Models\\Exemple', only: ['index'])
        ];
    }
    
    public function index(Request $request)
    {
        // Filtrage multi-tenant obligatoire
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
    }
}
\`\`\`

#### Commit Messages

Format : `type(scope): description`

Types :
- `feat`: nouvelle fonctionnalité
- `fix`: correction de bug
- `docs`: documentation
- `style`: formatage
- `refactor`: refactoring
- `test`: tests
- `chore`: maintenance

Exemples :
- `feat(users): ajouter export CSV des membres`
- `fix(auth): corriger redirection après login`
- `docs(readme): mettre à jour instructions installation`

### Pull Request

1. Assurez-vous que tous les tests passent
2. Mettez à jour la documentation si nécessaire
3. Décrivez clairement les changements apportés
4. Liez les issues concernées avec `closes #123`
5. Ajoutez des captures d'écran pour les changements UI

## 📋 Checklist PR

- [ ] Tests passent (`php artisan test`)
- [ ] Code formaté avec Pint (`./vendor/bin/pint`)
- [ ] Analyse statique propre (`./vendor/bin/phpstan analyse`)
- [ ] Documentation mise à jour
- [ ] CHANGELOG.md mis à jour si nécessaire
- [ ] Pas de conflits avec main
- [ ] Filtrage multi-tenant respecté
- [ ] Protection CSRF ajoutée aux formulaires POST

## 🧪 Tests

\`\`\`bash
# Lancer tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=UserTest

# Coverage
php artisan test --coverage
\`\`\`

## 🔍 Outils de développement

- **Telescope** : Debugging et monitoring (http://localhost:8001/telescope)
- **Pint** : Formatage automatique du code PHP
- **PHPStan** : Analyse statique du code
- **Laravel Debugbar** : Debug des requêtes et performances

## 📖 Ressources

- [Documentation Laravel 12.x](https://laravel.com/docs/12.x)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Architecture du projet](docs/architecture.md)

## 🏷️ Releases

Les releases suivent le [Semantic Versioning](https://semver.org/lang/fr/) :
- **MAJOR** : changements incompatibles
- **MINOR** : nouvelles fonctionnalités compatibles
- **PATCH** : corrections de bugs

## 📞 Contact

- 📧 Email : support@studiosdb.com
- 💬 Discussions : [GitHub Discussions](https://github.com/lalphadb/studiosunisdb/discussions)
- 🐛 Issues : [GitHub Issues](https://github.com/lalphadb/studiosunisdb/issues)

Merci pour votre contribution ! 🙏
