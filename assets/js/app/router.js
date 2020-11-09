import React from 'react';
import { Route, Switch } from "react-router-dom";
import routes from './../routes';

export default () => {
	return (
		<main>
			<Switch>
				{routes.map((item, key) => 
					<Route key={key} path={item.route ?? null} exact={item.exact ?? false} component={item.component} />
				)}
			</Switch>
		</main>
	);
};