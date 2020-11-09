import React from 'react';
import { NavLink, Link } from 'react-router-dom';
import { ProSidebar, Menu, MenuItem, SidebarHeader, SidebarContent, SidebarFooter } from 'react-pro-sidebar';
import routes from './../../routes';

import './header.scss';
import menuMountain from './../../../images/mountain.jpg';

export default () => {
	const githubLink = 'https://github.com/drombel'; //TODO: change to be loaded from .env

	return (
		<header>
			<ProSidebar breakPoint='lg' image={menuMountain}>
				<SidebarHeader>
					<Link to='/' className='text-white d-block py-3 px-2 text-center h4 mb-0 text-decoration-none'><i className="fa fa-video-camera"></i> SuperCinema</Link>
				</SidebarHeader>
				<SidebarContent>
					<Menu iconShape="square">
						{routes
							.filter(item => item.menu && item.route)
							.map((item, key) =>
								<MenuItem key={key} icon={item.icon && <i className={item.icon} />}>
									<NavLink to={item?.route}>{item?.name}</NavLink>
								</MenuItem>
							)}
					</Menu>
				</SidebarContent>
				<SidebarFooter>
					<p className='small font-weight-light py-1 px-2 mb-0 text-center'>Code is available on <a href={githubLink} target='_blank' className='text-white text-decoration-none' rel='noopener noreferrer'>github.com/drombel</a></p>
				</SidebarFooter>
			</ProSidebar>
		</header>
	);
};