import React from 'react';
import NotFound from './../views/notFound';
import MainPage from './../views/mainPage';

export default [
	{
		name: 'Main Page',
		route: '/',
		component: MainPage,
		exact: true,
	},
	{
		name: 'Movies',
		route: '/movies',
		component: NotFound,
		icon: 'fa fa-film',
		menu: true,
	},
	{
		name: 'Not Found',
		component: NotFound,
	},
];