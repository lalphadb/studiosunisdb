// Routes Ziggy générées automatiquement
// Généré le: 2025-08-23

export const Ziggy = {
  url: 'http://localhost:8000',
  port: 8000,
  defaults: {},
  routes: {
    // Pages publiques
    'home': {
      'uri': '/',
      'methods': ['GET', 'HEAD']
    },
    'loi25': {
      'uri': 'loi-25',
      'methods': ['GET', 'HEAD']
    },
    
    // Auth routes
    'login': {
      'uri': 'login',
      'methods': ['GET', 'HEAD']
    },
    'logout': {
      'uri': 'logout',
      'methods': ['POST']
    },
    'register': {
      'uri': 'register',
      'methods': ['GET', 'HEAD']
    },
    'password.request': {
      'uri': 'forgot-password',
      'methods': ['GET', 'HEAD']
    },
    'password.email': {
      'uri': 'forgot-password',
      'methods': ['POST']
    },
    'password.reset': {
      'uri': 'reset-password/{token}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['token']
    },
    'password.update': {
      'uri': 'reset-password',
      'methods': ['POST']
    },
    'verification.notice': {
      'uri': 'verify-email',
      'methods': ['GET', 'HEAD']
    },
    'verification.verify': {
      'uri': 'verify-email/{id}/{hash}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['id', 'hash']
    },
    'verification.send': {
      'uri': 'email/verification-notification',
      'methods': ['POST']
    },
    
    // Dashboard
    'dashboard': {
      'uri': 'dashboard',
      'methods': ['GET', 'HEAD']
    },
    
    // Profile
    'profile.edit': {
      'uri': 'profile',
      'methods': ['GET', 'HEAD']
    },
    'profile.update': {
      'uri': 'profile',
      'methods': ['PATCH']
    },
    'profile.destroy': {
      'uri': 'profile',
      'methods': ['DELETE']
    },
    
    // Membres
    'membres.index': {
      'uri': 'membres',
      'methods': ['GET', 'HEAD']
    },
    'membres.create': {
      'uri': 'membres/create',
      'methods': ['GET', 'HEAD']
    },
    'membres.store': {
      'uri': 'membres',
      'methods': ['POST']
    },
    'membres.show': {
      'uri': 'membres/{membre}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['membre']
    },
    'membres.edit': {
      'uri': 'membres/{membre}/edit',
      'methods': ['GET', 'HEAD'],
      'parameters': ['membre']
    },
    'membres.update': {
      'uri': 'membres/{membre}',
      'methods': ['PUT', 'PATCH'],
      'parameters': ['membre']
    },
    'membres.destroy': {
      'uri': 'membres/{membre}',
      'methods': ['DELETE'],
      'parameters': ['membre']
    },
    'membres.export': {
      'uri': 'membres-export/{format?}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['format']
    },
    
    // Cours
    'cours.index': {
      'uri': 'cours',
      'methods': ['GET', 'HEAD']
    },
    'cours.create': {
      'uri': 'cours/create',
      'methods': ['GET', 'HEAD']
    },
    'cours.store': {
      'uri': 'cours',
      'methods': ['POST']
    },
    'cours.show': {
      'uri': 'cours/{cours}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['cours']
    },
    'cours.edit': {
      'uri': 'cours/{cours}/edit',
      'methods': ['GET', 'HEAD'],
      'parameters': ['cours']
    },
    'cours.update': {
      'uri': 'cours/{cours}',
      'methods': ['PUT', 'PATCH'],
      'parameters': ['cours']
    },
    'cours.destroy': {
      'uri': 'cours/{cours}',
      'methods': ['DELETE'],
      'parameters': ['cours']
    },
    'cours.planning': {
      'uri': 'cours/planning',
      'methods': ['GET', 'HEAD']
    },
    
    // Présences
    'presences.index': {
      'uri': 'presences',
      'methods': ['GET', 'HEAD']
    },
    'presences.store': {
      'uri': 'presences',
      'methods': ['POST']
    },
    'presences.show': {
      'uri': 'presences/{presence}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['presence']
    },
    'presences.update': {
      'uri': 'presences/{presence}',
      'methods': ['PUT', 'PATCH'],
      'parameters': ['presence']
    },
    'presences.destroy': {
      'uri': 'presences/{presence}',
      'methods': ['DELETE'],
      'parameters': ['presence']
    },
    'presences.tablette': {
      'uri': 'presences/tablette',
      'methods': ['GET', 'HEAD']
    },
    
    // Paiements
    'paiements.index': {
      'uri': 'paiements',
      'methods': ['GET', 'HEAD']
    },
    'paiements.show': {
      'uri': 'paiements/{paiement}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['paiement']
    },
    'paiements.store': {
      'uri': 'paiements',
      'methods': ['POST']
    },
    'paiements.update': {
      'uri': 'paiements/{paiement}',
      'methods': ['PUT', 'PATCH'],
      'parameters': ['paiement']
    },
    'paiements.refund': {
      'uri': 'paiements/{paiement}/refund',
      'methods': ['POST'],
      'parameters': ['paiement']
    },
    
    // Utilisateurs (admin only)
    'utilisateurs.index': {
      'uri': 'utilisateurs',
      'methods': ['GET', 'HEAD']
    },
    'utilisateurs.create': {
      'uri': 'utilisateurs/create',
      'methods': ['GET', 'HEAD']
    },
    'utilisateurs.store': {
      'uri': 'utilisateurs',
      'methods': ['POST']
    },
    'utilisateurs.edit': {
      'uri': 'utilisateurs/{utilisateur}/edit',
      'methods': ['GET', 'HEAD'],
      'parameters': ['utilisateur']
    },
    'utilisateurs.update': {
      'uri': 'utilisateurs/{utilisateur}',
      'methods': ['PUT', 'PATCH'],
      'parameters': ['utilisateur']
    },
    'utilisateurs.destroy': {
      'uri': 'utilisateurs/{utilisateur}',
      'methods': ['DELETE'],
      'parameters': ['utilisateur']
    },
    
    // Ceintures
    'ceintures.index': {
      'uri': 'ceintures',
      'methods': ['GET', 'HEAD']
    },
    'ceintures.show': {
      'uri': 'ceintures/{ceinture}',
      'methods': ['GET', 'HEAD'],
      'parameters': ['ceinture']
    },
    
    // Examens
    'examens.index': {
      'uri': 'examens',
      'methods': ['GET', 'HEAD']
    },
    'examens.store': {
      'uri': 'examens',
      'methods': ['POST']
    },
    'examens.update': {
      'uri': 'examens/{examen}',
      'methods': ['PUT', 'PATCH'],
      'parameters': ['examen']
    },
    
    // Debug routes (admin only)
    'debug.phpinfo': {
      'uri': 'debug/phpinfo',
      'methods': ['GET', 'HEAD']
    },
    'debug.dashboard.simple': {
      'uri': 'debug/dashboard-simple',
      'methods': ['GET', 'HEAD']
    },
    'debug.dashboard.dynamic': {
      'uri': 'debug/dashboard-dynamic',
      'methods': ['GET', 'HEAD']
    }
  }
};

// Helper function for route generation
if (typeof window !== 'undefined') {
  window.route = (name, params, absolute) => {
    const route = Ziggy.routes[name];
    if (!route) {
      console.error(`Route ${name} not found`);
      return '#';
    }
    
    let uri = route.uri;
    
    // Replace parameters
    if (params) {
      if (route.parameters) {
        route.parameters.forEach(param => {
          if (params[param]) {
            uri = uri.replace(`{${param}}`, params[param]);
          }
        });
      }
    }
    
    // Remove optional parameters
    uri = uri.replace(/\{[^}]*\?\}/g, '');
    
    return absolute ? `${Ziggy.url}/${uri}` : `/${uri}`;
  };
}
