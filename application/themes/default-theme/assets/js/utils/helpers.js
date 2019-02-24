import api from './api';

export const fetchDtRows = (url, pagination, search = '') => {
	const { sortBy, descending, page, rowsPerPage } = pagination;
	return new Promise((resolve, reject) => {
		api().get(url, {
			params: {
				sort: sortBy,
				order: descending ? 'desc' : 'asc',
				page: page,
				limit: rowsPerPage,
				search: search
			}
		}).then(response => {
			resolve({
				items: response.data.rows,
				total: response.data.total,
			});
		});
	});
}