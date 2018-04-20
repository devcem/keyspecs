var _cryptoApp = function(coin){
	return new Vue({
		el : '#crypto_wrapper_' + coin,
		data : {
			currency : { symbol : '$', code : 'USD' },
			service  : 'coinmarketcap',
			selectedCoin : { code : 'BTC', name : 'bitcoin' },
			coin : {
				name : '',
				rank : 1,
				symbol : '',
				web  : null,
				explorer : null,
				forum : null,
				icon : '',
				open : 0,
				high : 0,
				low  : 0,
				marketCap : 0,
				currentChange : 0,
				currentValue  : 0,
				currentVolume : 0,
				currentVolumeCrypto : 0
			}
		},
		methods : {
			init : function(coin, service, currency){
				this.selectedCoin  = coin;
				this.service       = service;
				this.currency      = currency;

				this.update();

				setInterval(function(coin, app){
					app.update();
				}, 6000, coin, this);
			},
			update : function(){
				jQuery.get('https://cryptowp.org/service/?request=ticker&coin=' + this.selectedCoin.name + '&currency=' + this.currency.code, function(data){
					var data = data[0];
					cryptoApp.coin.rank = data.rank;
					cryptoApp.coin.name = data.name;
					cryptoApp.coin.icon = data.id;
					cryptoApp.coin.symbol = '(' + data.symbol + ')';
					cryptoApp.coin.currentVolume = data['24h_volume_usd'];
					cryptoApp.coin.marketCap = data['market_cap_usd'];
					//cryptoApp.coin.currentValue = data['price_usd'];
					//cryptoApp.coin.currentChange = data['percent_change_7d'];

					cryptoApp.details();
				});
			},
			details : function(){
				jQuery.get('https://cryptowp.org/service/?request=pricemultifull&coin=' + this.selectedCoin.code + '&currency=' + this.currency.code, function(data){
					var data = data['RAW'][cryptoApp.selectedCoin.code][cryptoApp.currency.code];

					//cryptoApp.coin.name = cryptoApp.selectedCoin.name;
					cryptoApp.coin.open = data.OPENDAY;
					cryptoApp.coin.low  = data.LOWDAY;
					cryptoApp.coin.high = data.HIGHDAY;
					cryptoApp.coin.currentValue = data.PRICE;
					cryptoApp.coin.currentChange = parseFloat(data.CHANGEPCTDAY).toFixed(2);

					/*
					var data = data[0];
					cryptoApp[data.id].coin.rank = data.rank;
					cryptoApp[data.id].coin.name = data.name;
					cryptoApp[data.id].coin.icon = data.id;
					cryptoApp[data.id].coin.symbol = '(' + data.symbol + ')';
					cryptoApp[data.id].coin.currentVolume = data['24h_volume_usd'];
					cryptoApp[data.id].coin.marketCap = data['market_cap_usd'];
					cryptoApp[data.id].coin.currentValue = data['price_usd'];
					cryptoApp[data.id].coin.currentChange = data['percent_change_7d'];
					*/
					//console.log(data);
				});
			},
			bigNumber : function(integer){
				return parseFloat(integer / 1000000000).toFixed(2);
			}
		}
	});
};

var cryptoTable = {};
var _cryptoTable = function(hash){
	return new Vue({
		el : '#crypto_table_' + hash,
		data : {
			hash : hash,
			currency : { symbol : '$', code : 'USD' },
			currencies : [],
			selectedCoins : [],
			coins : [],
			oldValues : {},
			coinDatabase : {},
			type : 'normal',
			ajax_url : '',
			form : {
				page : 0,
				time_range : '24h',
				search : '',
				currency : 0,
				limit : 10
			},
			favorites : [],
			time_ranges : ['1h', '24h', '7d'],
			sortBy : 'rank',
			sortMethod : true,
			sortType : 'number'
		},
		methods : {
			init : function(coins, service, currencies, coinDatabase, type, defaultCurrency, limit){
				this.selectedCoins = coins.split(',');
				this.service       = service;
				this.currencies    = Object.values(currencies);
				this.coinDatabase  = coinDatabase;
				this.type          = type;
				this.currency      = defaultCurrency;

				if(localStorage.getItem("cwp_favorites")){
					this.favorites = JSON.parse(localStorage.getItem("cwp_favorites"));
				}

				if(limit){
					this.form.limit = parseInt(limit);
				}

				if(this.type == 'full_list'){
					this.form.limit = 25;
				}

				for(currency in this.currencies){
					if(this.currencies[currency].code == defaultCurrency.code){
						this.form.currency = currency;
					}
				}
				
				this.ajax_url      = 'https://cryptowp.org/service/';

				this.update();

				setInterval(function(app){
					app.update();
				}, 20000, this);
			},
			getHash : function(name, value){
				return name + value;
			},
			update : function(){
				this.currency = this.currencies[this.form.currency];

				var hash = this.hash;
				var priceCode = this.currencies[this.form.currency].code.toLowerCase();

				if(this.type == 'full_list'){
					var data = {
						action: 'cryptowp_ajax',
						request : 'full_list'
					};

					jQuery.post(this.ajax_url + '?currency=' + this.currencies[this.form.currency].code, data, function(response) {
						//var data = JSON.parse(response);
						var data = response;

						cryptoTable[hash].coins = [];
						for(coinIndex in data){
							var coin = data[coinIndex];
							var currentValue = parseFloat(coin['price']).toFixed(2);
							var coinName    = coin.id.toLowerCase();
							var highlighted = cryptoTable[hash].oldValues[coin.name] != currentValue;
							var sparklines  = cryptoTable[hash].coinDatabase[coinName] ? cryptoTable[hash].coinDatabase[coinName].sparklines : 0;

							cryptoTable[hash].oldValues[coin.name] = currentValue;

							/*
							var volume = parseInt(coin['volume'].replace(/,/g,''));
							var market = parseInt(coin['market'].replace(/,/g,''));
							var supply = parseInt(coin['supply'].replace(/,/g,''));
							*/

							var volume = coin['volume_1'];
							var market = coin['market_1'];
							var supply = coin['supply_1'];

							cryptoTable[hash].coins.push({
								highlighted : highlighted,
								name : coin.name,
								icon : coin.id,
								id   : coin.id,
								rank : coin.rank,
								type : 'full_list',
								time_range : '24h',
								sparklines : sparklines,
								symbol : '(' + coin.symbol + ')',
								currentVolume : volume,
								marketCap : market,
								currentValue : currentValue,
								currentChange : coin['change_24h'],
								availableSupply : supply
							});
						}
				 	}).error(function(){
				 		
				 	});
				}else if(this.type == 'normal'){
					jQuery.get('https://cryptowp.org/service/?request=list&currency=' + this.currencies[this.form.currency].code + '&selectedCoins=' + this.selectedCoins, function(data){
						//cryptoTable.coins = data;

						cryptoTable[hash].coins = [];
						for(coinIndex in data){
							var coin = data[coinIndex];
							var currentValue = parseFloat(coin['price_' + priceCode]).toFixed(2);
							var coinName    = coin.id.toLowerCase();
							var highlighted = cryptoTable[hash].oldValues[coin.name] != currentValue;
							var sparklines  = cryptoTable[hash].coinDatabase[coinName] ? cryptoTable[hash].coinDatabase[coinName].sparklines : 0;

							cryptoTable[hash].oldValues[coin.name] = currentValue;

							cryptoTable[hash].coins.push({
								highlighted : highlighted,
								name : coin.name,
								icon : coin.id,
								type : 'normal',
								id   : coin.id,
								rank : coin.rank,
								sparklines : sparklines,
								time_range : '24h',
								symbol : '(' + coin.symbol + ')',
								currentVolume : coin['24h_volume_' + priceCode],
								marketCap : coin['market_cap_' + priceCode],
								currentValue : currentValue,
								currentChange : coin['percent_change_24h'],
								availableSupply : parseFloat(coin['available_supply']).toFixed(2)
							});
						}
					});
				}else if(this.type == 'gainers' || this.type == 'losers'){
					var data = {
						action: 'cryptowp_ajax',
						request : 'gainers_or_losers'
					};

					jQuery.post(this.ajax_url, data, function(response) {
						//var data = JSON.parse(response);
						var data = response;

						cryptoTable[hash].coins = [];
						for(coinIndex in data){
							var coin = data[coinIndex];
							var currentValue = parseFloat(coin['price']).toFixed(2);
							var currentVolume = parseFloat(coin['volume']).toFixed(2);
							var coinName    = coin.id.toLowerCase();
							var highlighted = cryptoTable[hash].oldValues[coin.name] != currentValue;
							var sparklines  = cryptoTable[hash].coinDatabase[coinName] ? cryptoTable[hash].coinDatabase[coinName].sparklines : 0;

							cryptoTable[hash].oldValues[coin.name] = currentValue;

							cryptoTable[hash].coins.push({
								highlighted : highlighted,
								name : coin.name,
								icon : coin.id,
								id   : coin.id,
								rank : coin.rank,
								type : coin.gainer_or_loser,
								time_range : coin.range,
								sparklines : sparklines,
								symbol : '(' + coin.symbol + ')',
								currentVolume : currentVolume,
								marketCap : 0,
								currentValue : currentValue,
								currentChange : coin['change'],
								availableSupply : 0
							});
						}
				 	}).error(function(){
				 		
				 	});
				}
			},
			sortByAction : function(field, type){
				this.sortBy = field;
				this.sortType = type;
				this.sortMethod = !this.sortMethod;

				this.form.page = 0;
			},
			getPagination : function(){
				var total = [];
				var count = 0;
				var coins = this.filterResult(true);
				var limit = coins.length / this.form.limit;

				for(i=this.form.page+1;i<limit;i++){

					if(count < 8){
						total.push(i);
						count++;
					}
					
				}

				return total;
				//return this.form.limit % this.coins;
			},
			markFavorite : function(coin){
				if(this.isFavorite(coin)){
					swal({
						title: "Removed",
						text: coin.name + " removed from favorite list!",
						icon: "error",
					});

					this.favorites.splice(this.favorites.indexOf(coin.id), 1);
				}else{
					swal({
						title: "Favorite!",
						text: coin.name + " marked as favorite!",
						icon: "success",
					});

					this.favorites.push(coin.id);
				}

				localStorage.setItem('cwp_favorites', JSON.stringify(this.favorites));
			},
			isFavorite : function(coin){
				return this.favorites.indexOf(coin.id) > -1;
			},
			filterResult : function(total){
				var output = [];

				var table = this;
				var coins = JSON.parse(JSON.stringify(this.coins));

				coins.sort(function(_a, _b) {
					if(table.sortMethod){
						a = _a;
						b = _b;
					}else{
						a = _b;
						b = _a;
					}

					if(table.sortType == 'string'){
						if (a[table.sortBy] < b[table.sortBy]){
			                return -1;
			            } else if (a[table.sortBy] > b[table.sortBy]){
			                return 1;
			            } else {
			                return 0;   
			            }
					}else{
						return parseFloat(a[table.sortBy]) - parseFloat(b[table.sortBy]);
					}
				});

				for(coinIndex in coins){
					var coin = coins[coinIndex];
					var willbeAdded = true;

					if(typeof coin.symbol == 'string'){
						if(this.type == coin.type){
							willbeAdded = true;

							if(this.form.time_range == coin.time_range){
								willbeAdded = true;
							}else{
								willbeAdded = false;
							}
						}else{
							willbeAdded = false;
						}

						if(this.form.search.length > 0){
							willbeAdded = false;
							if(coin.name.toLowerCase().search(this.form.search.toLowerCase()) > -1){
								willbeAdded = true;
							}

							if(coin.symbol.toLowerCase().search(this.form.search.toLowerCase()) > -1){
								willbeAdded = true;
							}
						}

						if(this.selectedCoins[0] != 'all' && this.type != 'full_list' && this.type != 'gainers' && this.type != 'losers' && this.selectedCoins.length > 0){
							willbeAdded = false;

							if(this.selectedCoins.indexOf(coin.icon.toLowerCase()) > -1){
								willbeAdded = true;
							}
						}

						if(willbeAdded){
							output.push(coin);
						}
					}
				}

				if(!total){
					output = output.splice(this.form.page * this.form.limit, this.form.limit);
				}

				if(this.type == 'full_list'){
					return output;
				}else if(this.type == 'normal'){
					return output;
				}else if(this.type == 'gainers'){
					return output;
				}else if(this.type == 'losers'){
					return output;
				}else{
					return coins;
				}
			},
			goToCoin : function(url){
				window.location = url;
			},
			formatNumber : function(value){
				var thousand = 1000;
			    var million = 1000000;
			    var billion = 1000000000;
			    var trillion = 1000000000000;
			    if (value < thousand) {
			        return String(value);   
			    }
			    
			    if (value >= thousand && value <= 1000000) {
			         return  Math.round(value/thousand) + 'k';   
			    }
			    
			    if (value >= million && value <= billion) {
			        return Math.round(value/million) + 'M';   
			    }
			    
			    if (value >= billion && value <= trillion) {
			        return Math.round(value/billion) + 'B';   
			    }
			    
			    else {
			        return Math.round(value/trillion) + 'T';   
			    }
			},
			bigNumber : function(integer){
				return parseFloat(integer / 1000000000).toFixed(2);
			},
			money : function(value){
				var c = 2;
				var d = '.'; 
				var t = ',';
				var n = value, 
			    c = isNaN(c = Math.abs(c)) ? 2 : c, 
			    d = d == undefined ? "." : d, 
			    t = t == undefined ? "," : t, 
			    s = n < 0 ? "-" : "", 
			    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
			    j = (j = i.length) > 3 ? j % 3 : 0;
			   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
			}
		}
	});
};

var cryptoAlarm = {};
var _cryptoAlarm = function(){
	return new Vue({
		el : '#cryptowp_alarm',
		data : {
			currencies : {'USD' : { symbol : '$', code : 'USD' }},
			coins : [],
			current : [],
			alarms : [],
			totalTimeout : 60,
			timeout : 60,
			form : {
				currency : 'USD',
				coin : 'bitcoin',
				value : 0
			}
		},
		methods : {
			init : function(coins, currencies, totalTimeout){
				this.coins = coins;
				//this.currencies = currencies;
				this.totalTimeout = totalTimeout;
				this.timeout = this.totalTimeout;

				var _alarms = localStorage.getItem("_alarms");
				if(_alarms){
					this.alarms = JSON.parse(_alarms);
				}

				this.update();

				setInterval(function(){
					cryptoAlarm.timeout--;
				}, 1000);

				setInterval(function(){
					cryptoAlarm.timeout = cryptoAlarm.totalTimeout;
					cryptoAlarm.update();
				}, this.timeout*1000);
			},
			update : function(){
				jQuery.get('https://cryptowp.org/service/?request=list&currency=USD', function(data){

					cryptoAlarm.current = [];
					for(coinIndex in data){
						var coin = data[coinIndex];
						var currentValue = parseInt(coin['price_usd']);

						cryptoAlarm.current[coin.name.toLowerCase()] = currentValue;

						if(cryptoAlarm.form.coin == coin.name.toLowerCase()){
							cryptoAlarm.form.value = parseInt(currentValue);
						}
					}
				});

				for(alarmIndex in this.alarms){
					var alarm = this.alarms[alarmIndex];

					if(this.current[alarm.coin]){
						if(parseInt(alarm.value) == parseInt(this.current[alarm.coin])){
							this.notify(parseInt(alarm.value));
							this.alarms.splice(alarmIndex, 1);
							this.saveAlarms();
						}
					}
				}
			},
			notify : function(value){
				Push.create("Cryptocurrency Alarm", {
				    body: 'Your cryptocurrency alarm reach to ' + value + '$.',
				    timeout: 4000,
				    onClick: function () {
				        window.focus();
				        this.close();
				    }
				});
			},
			saveAlarms : function(){
				localStorage.setItem('_alarms', JSON.stringify(this.alarms));
			},
			removeAlarm : function(index){
				this.alarms.splice(index, 1);
			},
			setAlarm : function(){
				Push.Permission.request(function(){
					cryptoAlarm.alarms.push(cryptoAlarm.form);
					cryptoAlarm.saveAlarms();

					swal({
						title: "Alarm Set!",
						text: "Your cryptocurrency alarm set.",
						icon: "success",
					});
				}, function(){
					swal({
						title: "We couldn\'t set the alarm.",
						text: "Please enable notification for this web site. If you are developer, please make sure that your site has SSL.",
						icon: "danger",
					});
				});
				
			}
		}
	});
};

function time_format(d) {
    hours = format_two_digits(d.getHours());
    minutes = format_two_digits(d.getMinutes());
    seconds = format_two_digits(d.getSeconds());
    return hours + ":" + minutes + ":" + seconds;
}

function format_two_digits(n) {
    return n < 10 ? '0' + n : n;
}

var createChart = function(coin, timeout, currency){
	var lastTimestamp = 0;
	var ctx = document.getElementById("tradeChart").getContext('2d');
	var myChart = new Chart(ctx, {
	    type: 'line',
	    data: {
	        labels: [],
	        datasets: [{
	            label: coin.name,
	            data: [],
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255,99,132,1)'
	            ],
	            borderWidth: 1,
	            fill : 'start'
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
	});

	function getChartData(){
		jQuery.get('https://min-api.cryptocompare.com/data/histohour?fsym=' + coin.code + '&tsym=' + currency.code + '&limit=60&aggregate=3&e=CCCAGG', function(data){
			for(index in data.Data){
				item = data.Data[index];
				var timestamp = new Date(item.time);

				if(lastTimestamp < item.time){
					myChart.data.labels.push(time_format(timestamp));
					myChart.data.datasets[0].data.push(item.close);
	            	myChart.update();
	            }
			}

			lastTimestamp = data.Data[data.Data.length - 1].time;
		});
	}

	/*
	setInterval(function(){
		getChartData();
	}, timeout*1000);
	*/

	getChartData();
}