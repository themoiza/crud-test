
var titlesVue = new Vue({
	el: '#idTitlesIndex',
	data: {
		titles: []
	}
});

var index = new Vue({
	el: '#idGridIndex',
	data: {
		controller: '/',
		controllers: [],
		filters: [],
		thead: [],
		titles: [],
		fetch: [],
		total: 0,
		pagination: new Pagination(['idPagination1', 'idPagination2'])
	},
	methods: {

		loadGrid(){

			var myHeaders = new Headers();
			myHeaders.append('Accept', 'application/json');

			var requestOptions = {
				method: 'GET',
				headers: myHeaders
			};

			fetch(this.controller+'grid', requestOptions)
			.then((response) => {

				if(response.status != 200){

				}

				return response.json();
			})
			.then((response) => {

				this.controllers = response.controllers;
				this.filters = response.filters;
				this.thead = response.thead;
				titlesVue.titles = response.titles;

				this.loadFetch(1);
			});
		},

		loadFetch(p){

			var myHeaders = new Headers();
			myHeaders.append('Accept', 'application/json');

			var requestOptions = {
				method: 'GET',
				headers: myHeaders
			};

			fetch(this.controller+'list?page='+p, requestOptions)
			.then((response) => {

				if(response.status != 200){

				}

				return response.json();
			})
			.then((response) => {

				this.fetch = response.fetch;
				this.total = response.total;

				if(this.total != this.pagination.getTotal()){

					this.pagination.setByPage(30);
					this.pagination.setTotal(Number(this.total));
				}
			});
		}
	},

	mounted(){

		this.loadGrid(1);

		this.pagination.setCallBack(() => {

			console.log(this.pagination.getPage());

			this.loadFetch(this.pagination.getPage());
		});
	}
});