import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter } from "react-router-dom";
import Router from './router';

import Header from './../components/header';
import './app.scss';

function App() {
	// const store = createStore(rootReducer);

	return (
		<div id='main'>
			<BrowserRouter>
				<Header />
				<Router />
			</BrowserRouter>
		</div>
	);
}

ReactDOM.render(<App/>, document.getElementById('app'));