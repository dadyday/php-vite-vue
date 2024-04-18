import {createRouter, createWebHistory} from 'vue-router'

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		// { path: '/', redirect: '/' },
		{
			path: '/',
			// component: () => import('../layouts/default.vue'),
			children: [
				{
					path: '/',
					component: () => import('@pages/Start.vue'),
				},
				// {
				// 	path: 'entities',
				// 	component: () => import('@pages/Entities.vue'),
				// },
				{
					path: 'foo',
					component: () => import('@pages/Foo.vue'),
				},
				//{
				//  path: '/icons',
				//  component: () => import('../pages/Icons.vue'),
				//},
				{
					path: '/:pathMatch(.*)*',
					component: () => import('@pages/Error.vue'),
				},
				/*
				{
					path: 'test',
					component: () => import('../pages/Test.vue'),
				},

					{
						path: 'typography',
						component: () => import('../pages/typography.vue'),
					},
					{
						path: 'cards',
						component: () => import('../pages/cards.vue'),
					},
					{
						path: 'tables',
						component: () => import('../pages/tables.vue'),
					},
					{
						path: 'form-layouts',
						component: () => import('../pages/form-layouts.vue'),
					},
				],
			},
			{
				path: '/',
				component: () => import('../layouts/blank.vue'),
				children: [
					{
						path: 'login',
						component: () => import('../pages/login.vue'),
					},
					{
						path: 'register',
						component: () => import('../pages/register.vue'),
					},
			*/
			],
		},
	],
})

export default router
