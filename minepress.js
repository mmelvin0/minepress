jQuery(function () {

	jQuery('.minepress').each(function (index, element) {
		var host, now;
		element = jQuery(element);
		host = element.attr('data-minepress-host');
		update('host', {host: host});
		refresh();

		function refresh() {
			now = +new Date;
			jQuery.ajax(element.attr('data-minepress-url') + 'query.php', {
				data     : {host: host},
				success  : function (data) {
					update('load', data);
					update('max-players', data);
					update('motd', data);
					update('ping', (data && data.up) ? ((+new Date - now) + ' ms') : 'down');
					update('players', data);
					update('ram', data);
					update('uptime', data);
				},
				error    : function () {
					update('load');
					update('max-players');
					update('motd');
					update('ping', 'down');
					update('players');
					update('ram');
					update('uptime');
				},
				complete : function () {
					setTimeout(refresh, 5000);
				}
			});
		}

		function update(key, data) {
			var value;
			if (data && typeof data === 'object' && key in data) {
				value = data[key];
			} else if (typeof data === 'string' || typeof data === 'number') {
				value = data;
			} else {
				value = '';
			}
			element.find('.minepress-' + key).text(value);
		}

	});

});
