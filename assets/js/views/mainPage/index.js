import React, { useEffect, useState } from 'react';
import { getMovies, getMovieCategories, getCinemas } from "./../../utils/api";

export default () => {
	const [movies, setMovies] = useState([]);
	const [cinemas, setCinemas] = useState([]);
	const [categories, setCategories] = useState([]);

	useEffect(() => {
		if(movies.length === 0)
			getMovies({ limit: 3 }).then(res => !res.errors ? setMovies(res) : []);
		if(cinemas.length === 0)
			getCinemas({ limit: 2 }).then(res => !res.errors ? setCinemas(res) : []);
		if(categories.length === 0)
			getMovieCategories({ limit: 3 }).then(res => !res.errors ? setCategories(res) : []);
	}, []);

	return (
		<div id='main-page'>
			<section id='slider'>
				slider z ostatnimi filmami // 4 by update
			</section>
			<section id='movies'>
				<div className="container">
					top filmy // 3 per col, oldest by premiere_date
					button show more
					{movies.map((movie, key) => (<div key={key}>{movie.title}</div>))}
				</div>
			</section>
			<section id='cinemas'>
				<div className="container">
					kina // 2 per col
					{cinemas.map((cinema, key) => (<div key={key}>{cinema.name}</div>))}
				</div>
			</section>
			<section id='cinemas'>
				<div className="container">
					categorie // 3 per col
					{categories.map((category, key) => (<div key={key}>{category.name}</div>))}
				</div>
			</section>
		</div>
	);
};