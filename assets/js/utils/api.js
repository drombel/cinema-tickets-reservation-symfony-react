import query from './query';

export const getMovies = async (params = {}) => {
    return await query.get('/movies/', {params: params})
        .then(response => response.data)
        .catch((error) => {
            console.error(error);
            return [];
        })
    ;
};

export const getMovieCategories = async (params = {}) => {
    return await query.get('/moviecategories/', {params: params})
        .then(response => response.data)
        .catch((error) => {
            console.error(error);
            return [];
        })
    ;
};

export const getCinemas = async (params = {}) => {
    return await query.get('/cinemas/', {params: params})
        .then(response => response.data)
        .catch((error) => {
            console.error(error);
            return [];
        })
    ;
};

