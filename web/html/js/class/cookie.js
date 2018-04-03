/**
 * Classe statique simplifiant la manipulation des cookies côté client
 * @see http://www.w3schools.com/js/js_cookies.asp
 */
function Cookie() {
	/**
	 * Récupère la valeur du cookie donné.
	 * 
	 * @param string cname Le cookie à récupérer
	 * @param string fault La valeur a prendre en cas d'erreur (cookie inexistant ?)
	 * @returns string La valeur du cookie demandé. Ou fault si le cokkie n'existe pas
	 */
	Cookie.get_cookie = function(cname, fault) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	
	    for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length,c.length);
			}
	    }
		if (typeof fault === 'undefined') {
			return fault;
		}
	    return 0;
	};
	
	/**
	 * Défini la valeur d'un cookie.
	 * 
	 * @param cname Le nom du cookie à définir
	 * @param cvalue La valeur du cookie à entrer
	 * @param exdays Le temps d'expiration. Par défaut 365 jours
	 */
	Cookie.set_cookie = function(cname, cvalue, exdays) {
		var d = new Date();
		if (typeof exdays === 'undefined') {
			exdays = 365;
		}
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		var expires = "expires="+d.toUTCString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	};
}
new Cookie();