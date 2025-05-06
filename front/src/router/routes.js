const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        name: 'home',
        path: '',
        component: () => import('pages/HomePage.vue'),
        meta: {
          title: 'romainlamerde.com'
        }
      },
      {
        name: 'settings',
        path: 'settings',
        component: () => import('pages/SettingsPage.vue'),
        meta: {
          title: 'Paramètres'
        },
      },
      {
        name: 'infos',
        path: 'infos',
        component: () => import('pages/InfosPage.vue'),
        meta: {
          title: 'Infos'
        }
      },
      {
        name: 'account',
        path: 'account',
        component: () => import('pages/AccountPage.vue'),
        meta: {
          title: 'Mon compte'
        }
      }
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
