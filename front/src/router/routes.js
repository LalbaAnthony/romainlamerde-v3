const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        name: 'home',
        path: '',
        component: () => import('pages/HomePage.vue'),
        meta: { title: '' }
      },
      {
        name: 'search',
        path: 'search',
        component: () => import('pages/SearchPage.vue'),
        meta: { title: 'Recherche' }
      },
      {
        name: 'infos',
        path: 'infos',
        component: () => import('pages/InfosPage.vue'),
        meta: { title: 'Infos' }
      },
      {
        name: 'quotes',
        path: 'quotes',
        children: [
          {
            name: 'quotes.list',
            path: '',
            component: () => import('pages/quotes/ListPage.vue'),
            meta: { title: 'Tous' }
          },
          {
            name: 'quotes.favorites',
            path: 'favorites',
            component: () => import('pages/quotes/ListPage.vue'),
            meta: { title: 'Favoris' }
          },
          {
            name: 'quotes.add',
            path: 'add',
            component: () => import('pages/quotes/DetailsPage.vue'),
            meta: { title: 'Ajouter' },
          },
          {
            name: 'quotes.details',
            path: ':id',
            component: () => import('pages/quotes/DetailsPage.vue'),
            meta: { title: '' },
          }
        ]
      },
      {
        name: 'account',
        path: 'account',
        children: [
          {
            name: 'account.base',
            path: '',
            component: () => import('pages/AccountPage.vue'),
            meta: { title: 'Moi' }
          },
          {
            name: 'account.settings',
            path: 'settings',
            component: () => import('pages/SettingsPage.vue'),
            meta: { title: 'Paramètres' },
          }
        ]
      },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
