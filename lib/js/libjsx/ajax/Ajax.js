/** 
 * 
 * JSX Javascript Library 0.0.1
 *
 * Copyright (C) 2008  Rogério Alencar Lino Filho
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 */
var Ajax = function(url, t) {

    var self = this;

    this.url = url;
    this.target = t;
    
    this.xmlhttp = null;
    this.method = "GET";
    this.parameters = "";
    this.showLoading = false;
    this.callback = undefined;
    this.callbackParam = undefined;

    this.getURL = function() {
        return self.url;
    }

    this.setMethod = function(m) {
        self.method = m;
    }

    this.getMethod = function() {
        return self.method;
    }

    this.setParameters = function(p) {
        self.parameters = p;
    }

    this.getParameters = function() {
        return self.parameters;
    }

    this.setShowLoading = function(b) {
        self.showLoading = (b == true);
    }

    this.setCallback = function(c) {
        self.callback = c;
    }

    this.getCallback = function() {
        return self.callback;
    }

    this.setCallbackParam = function(p) {
    	self.callbackParam = p;
    }

    this.getCallbackParam = function() {
        return self.callbackParam;
    }
    
    this.createObjectRequest = function() {
        var xml = null;
	    if (window.XMLHttpRequest)
		    xml = new XMLHttpRequest();
	    else if (window.ActiveXObject)
		    xml = new ActiveXObject("Microsoft.XMLHTTP");
        return xml;
    }
    
    this.getTarget = function() {
        return self.target;
    }    
    
    this.getUrlVars = function() {
	    var url = window.location.href;
	    vars = url.split("?")[1];
	    return vars;
    }

    this.load = function() {
        var xmlhttp = self.createObjectRequest();
        var target = self.getTarget();

        if (xmlhttp != null) {
	        xmlhttp.onreadystatechange = function() {
		        if (xmlhttp.readyState == 4) {
	                if (xmlhttp.status == 200) {
                        window.removeLoading();
                        
	                    target.load(xmlhttp.responseText);

		                if (self.callback !== undefined) {
		                	self.callback(self.callbackParam);
		                }
	                }
                    else if (xmlhttp.status != 0) {
                        alert("ERROR\nHTTP STATUS: "+xmlhttp.status+"\nURL: "+self.getURL());
                    }
                }
	        }
	        var method = self.getMethod();
	        if (method == "GET") {
    	        xmlhttp.open(method, self.getURL(), true);
	            xmlhttp.send(null);
	        }
	        else if (method == "POST") {
	            var p = self.getParameters();
	            xmlhttp.open(method, self.getURL(), true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.setRequestHeader("Content-length", p.length);
                xmlhttp.setRequestHeader("Connection", "close");
                xmlhttp.send(p);
	        }
	        return true;
	    }
        return false;
    }
}

/*
 * Codifica os parametros no Object.
 */
Ajax.encodePostParameters = function(obj) {
    var param = "";
    for (k in obj) {
    	if (obj[k] instanceof Array) {
    		param += Ajax.encodeArray(k, obj[k]);
    	}
    	else {
    		param += k + "=" + encodeURI(obj[k]) + "&";
    	}
    }
    return param.substring(0, param.length-1);
}

Ajax.encodeArray = function(k, array) {
    var param = "";
    for (var i = 0; i < array.length; i++) {
        param += k + "=" + encodeURI(array[i]) + "&";
    }
    return param;
}

Ajax.encodeFormAsPost = function(form, excludes) {
	var elem;
	var obj = new Object();
	if (excludes == null) {
		excludes = new Array("button", "submit", "reset");
	}
	
	for (var i = 0; i < form.elements.length; i++) {
		elem = form.elements[i];
		
		var found = false;
		if (excludes instanceof Array) {
			for (var j = 0; j < excludes.length && !found; j++) {
				if (elem.type == excludes[j]) {
					found = true;
				}
			}
		}
		
		if (found) {
			continue;
		}
		
		if (elem.type == 'checkbox' || elem.type == 'radio') {
			if (elem.checked) {
				Ajax.addParameter(obj, elem.name, elem.value);
			}
		}
		else {
			Ajax.addParameter(obj, elem.name, elem.value);
		}
	}
	
	return Ajax.encodePostParameters(obj);
}

Ajax.addParameter = function(obj, key, value) {
	if (obj[key] == null) {
		obj[key] = value;
	}
	else {
		if (obj[key] instanceof Array) {
			obj[key].push(value);
		}
		else {
			var array = new Array();
			array[0] = obj[key];
			array[1] = value;
			
			obj[key] = array;
		}
	}
}

Ajax.simpleLoad = function(url, id, method, params, loading, c, p) {
    var targetList = new TargetList();
    targetList.add(new Target(id));
    targetList.add(new ParametrizedCallbackTarget(c, p));

    if (loading) {
        window.createLoading(id);
    }

    var ajax = new Ajax(url, targetList);
    ajax.setMethod(method);
    ajax.setShowLoading(loading);
    ajax.setParameters(params);
    ajax.load();
}

Ajax.simpleRetrieve = function(url, method, params, c) {
    var ajax = new Ajax(url, new CallbackTarget(c));
    ajax.setMethod(method);
    ajax.setParameters(params);
    ajax.load();
}

/**
 *
 * AjaxList Object
 *
 * Objeto usado para guardar as resquisicoes em uma fila
 * e executa-las, para que nenhuma se perca.
 *
 */
var AjaxList = function() {

    var self = this;
    this.queue = Array();
    
    this.add = function(ajax) {
        self.queue.push(ajax);
    }
    
    this.get = function() {
        return self.queue.shift();
    }
    
    this.loadURLs = function() {
        var ajax;
        while (self.queue.length > 0) {
            ajax = self.get();
            ajax.load();
        }
    }

}

/**
 * 
 * Target Object 
 * 
 * Contem o id do tag html alvo aonde sera carregado o conteudo da url
 *
 */
var Target = function(id) {

    var self = this;
    this.id = id;
    
    this.getId = function() {
        return self.id;
    }

    this.load = function(content) {
        var tag = null;
        if (self.getId() != "") {
            tag = $("#"+self.getId()).get(0);
            if (tag != null) {
                tag.innerHTML = content;
            }
        }
        else {
            if (content != null && content != '') {
                tag = window.popup("response_popup");
                tag.innerHTML = content;
                $("body").get(0).appendChild(tag);
            }
        }

        if (tag != null) {
            var popup = window.getParentPopup(tag);
            if (popup) {
                $(popup).dialog( 'option' , 'position' , 'center' );
                $(popup).dialog( 'option' , 'width' , 'auto' );
                window.refreshPopupTitle(popup);
            }
        }
        SGA.loadComponentes();
    }

}

var CallbackTarget = function(callback) {

    var self = this;

    this.callback = callback;

    this.getCallback = function() {
        return self.callback;
    }

    this.load = function(content) {
        self.callback(content);
    }
}

var ParametrizedCallbackTarget = function(callback, param) {

    var self = this;

    this.callback = callback;
    this.param = param;

    this.getCallback = function() {
        return self.callback;
    }

    this.load = function(content) {
        if (typeof self.param == "undefined") {
            if (self.callback !=null){
            	self.callback();
            }
        }
        else {
        	if (self.callback !=null){
        		self.callback(param);
        	}
        }
    }
}

var TargetList = function() {

    var self = this;

    this.queue = new Array();

    this.add = function(ajax) {
        self.queue.push(ajax);
    }

    this.shift = function() {
        return self.queue.shift();
    }

    this.isEmpty = function() {
        return self.queue.length == 0;
    }

    this.load = function(content) {
        while (!self.isEmpty()) {
            self.shift().load(content);
        }
    }
}
