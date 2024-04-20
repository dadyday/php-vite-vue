export default [
	{ label: 'Home', items: [
			{ label: 'Entities'    , icon: 'bx-atom'                , to: '/entities' },
			{ label: 'Foo'         , icon: 'bx-home'                , to: '/foo', roles: [] },
			{ label: 'Bar'         , icon: 'mdi-account-cog-outline', to: '/bar', roles: ['admin'] },
		]},

	{ label: 'Pages', items: [
			{ label: 'Login'       , icon: 'bx-log-in'              , to: '/login' },
			{ label: 'Register'    , icon: 'bx-user-plus'           , to: '/register' },
			{ label: 'Error'       , icon: 'bx-info-circle'         , to: '/no-existence' },
		]},

	{ label: 'User', items: [
			{ label: 'Typography'  , icon: 'mdi-alpha-t-box-outline', to: '/typography' },
			{ label: 'Icons'       , icon: 'bx-show'                , to: '/icons' },
			{ label: 'Cards'       , icon: 'bx-credit-card'         , to: '/cards' },
			{ label: 'Tables'      , icon: 'bx-table'               , to: '/tables' },
			{ label: 'Form Layouts', icon: 'mdi-form-select'        , to: '/form-layouts' },
		]},
]