var mo = (function(mo_ping_url, namespace, ua) {
	var 		
		each = namespace.each,
			
		cutTime = function(num) {
			var s = (num + '');
			return s.substring(s.length - 6);
		},
		stamps = {
			ua: (function() {
				for (var i in ua) {
					if (ua[i] != 0) {
						return i + ua[i];
					}
				}
			})(),
			domain: (function(s) {
				try {					
					var m = s.match(/http:\/\/([^\/]*)\/|$/i);
					return m[1];
				}
				catch (e) {
					return ''
				}
			})(location.href),
			QosS: cutTime((new Date()).getTime())
		},
		refs = namespace.__images = [],
		touch_ping = function(url) {
			var img = new Image(1, 1);
			img.src = url;
			refs.push(img);
			return this;
		},
		canPing = function() {
			return true;
		},
		ping = function() {
			if (canPing()) {
				touch_ping(mo_ping_url + '?' + namespace.serializeQuery(stamps));
			}
		}
	;
	
	// evt
	var evt = (function() {
		var evt = {};
	
		if (document.addEventListener) { // W3C
			evt.on = function(el, type, handler) {		
				el.addEventListener(type, handler, false);
				return handler;
			};
			
			evt.on2 = evt.on;
			
			evt.un = function(el, type, handler) {
				el.removeEventListener(type, handler, false);
			};
			
			evt.stopPropagation = function(e) {
				e.stopPropagation();
			};
			
			evt.preventDefault = function(e) {
				e.preventDefault();
			};
			
			evt.getTarget = function(e) {
				return e.target;
			};
		} else { // IE
			evt.on = function(el, type, handler) {					
				el.attachEvent('on' + type, handler);
			};
			
			evt.on2 = function(el, type, handler) {
				var actualHandler = function() {
					handler.call(el, window.event);
				};
				el.attachEvent('on' + type, actualHandler);
				// Return the 'actualHandler' reference, so that you can un it later.
				return actualHandler;	
			};
			
			evt.un = function(el, type, handler) {
				el.detachEvent('on' + type, handler);
			};
			
			evt.stopPropagation = function(e) {
				e.cancelBubble = true;
			};
			
			evt.preventDefault = function(e) {
				e.returnValue = false;
			};
			
			evt.getTarget = function(e) {
				return e.srcElement;
			};
		}
		
		evt.stop = function(e) {
			evt.stopPropagation(e);
			evt.preventDefault(e);
		};
		
		// ready
		(function() {
			var fns = [];
			var is_ready = false;
			
			evt.ready = function(f) {					
				fns.push(f);
			};
			
			var _ready = function() {
				if (!is_ready) {
					is_ready = true;
					evt.ready = function(f) {							
						f();
					};
					each(fns, function(f) {												
						f();
					});
				}
			};
			
			if (ua.ie) {
				
		        var timer = setInterval(function() {
		            try {
		                // throws an error if doc is not ready			              
		                document.doScroll();
		                clearInterval(timer);
		                timer = null;
		                _ready();
		            } catch (ex) { 
		            }
		        }, 64); 	
		       
				document.attachEvent("onreadystatechange", function() {
					if ( document.readyState === "complete" ) {
						document.detachEvent( "onreadystatechange", arguments.callee );
						_ready();
					}
				});
		        
		    } else {
		        evt.on(document, "DOMContentLoaded", _ready);
		    }
			
			evt.on(window, 'load', _ready);
		})();
		
		return evt;
	})();
	
	evt.ready(function() {
		mo.append('first_screen');
	});
	evt.on(window, 'load', function() {
		mo.append('full_screen');
		ping();
	});
	
	return {
		append: function(key) {
			stamps[key] = cutTime((new Date()).getTime());
		}
	};
})(
	'http://dp3.qq.com/play/',
	(function() {
	var    
	    each = function(o, fn, context) {
			if (typeof o.length == 'number') {
				for (var i = 0, len = o.length; i < len; i++) {
					fn.call(context, o[i], i);
				}
			} else if (typeof o == 'number'){
				for (var i = 0; i < o; i++) {
					fn.call(context, i, i);
				}
			} else {
				for (var i in o) {
					if (o.hasOwnProperty(i)) {
						fn.call(context, o[i], i);
					}
				}
			}
		},
		
	    serializeDictionary = function(assign_token, pair_separator, need_last, need_encode) {
	        var encode = need_encode ? encodeURIComponent: function(k) {
	            return k;
	        };
	        return function(o) {
				var ret = [];			
				each(o, function(v, k) {
	                if (k != null && v != undefined) {
	                    ret.push(encode(k) + assign_token + encode(v));
	                }
	            });			
				return ret.join(pair_separator) + (need_last ? pair_separator: '');
	        };
	    },
		
		deserializeString = function(assign_token, pair_separator, need_last, need_decode) {
			var decode = need_decode? decodeURIComponent: function(k) {
	            return k;
	        };
			return function(s) {
				var ret = {};
				if (need_last) {
					s = s.replace(new RegExp(pair_separator + '$'), '');
				}
				each(s.split(pair_separator), function(pair) {
					var key_value = pair.split(assign_token);
					ret[decode(key_value[0])] = decode(key_value[1]);
				});
				return ret;
			};
		}
	;
	
	return {
		slice: Array.slice || (function() {
	        var _slice = Array.prototype.slice;
	        return function(arr) {
	            return _slice.apply(arr, _slice.call(arguments, 1))
	        }
	    })(),
		
		generateId: (function() {
	        var id = 1;
	        return function() {
	            return 'auto_gen_' + id++
	        }
	    })(),
		
		each: each,
		
		map: function(o, fn) {
			var ret = [];
			each(o, function(v, k) {
				ret.push(fn(v, k));
			});
			return ret;
		},
			
		filter: function(o, fn) {
			var ret = [];
			each(o, function(v, k) {
				if (fn(v, k) === true) {
					ret.push(v);		
				}
			});
			return ret;
		},
		
		indexOf: function(arr, o) {
			if (arr.indexOf) {
				return arr.indexOf(o);
			}
			for (var i = 0; i < arr.length; i++) {
				if (arr[i] === o) {
					return i;
				}
			}
			return -1;
		},
		
		mix: function(r){
			if (!r) {
				r = {};
			}
	        for (var i = 1; i < arguments.length; i++) {
	            var s = arguments[i];
	            if (s) {
	                for (var j in s) {
						r[j] = s[j];
					}
	            }
	        }
	        return r;
	    },
		
		serializeDictionary: serializeDictionary,
		deserializeString: deserializeString,
			
		serializeStyles: serializeDictionary(':', ';', true, false),
	    serializeAttrs: (function() {
			var fn = serializeDictionary('=', ' ', true, false);
			
			return function(o) {					
				each(o, function(v, k) {
					o[k] = '"' + v + '"';
				});
				return fn(o);
			};
		})(),
		
		serializeQuery: serializeDictionary('=', '&', false, true),
		buffer: function(runner, delay) {				
        	var timer;
	        return function() {
	            if (timer) {
	                clearTimeout(timer)
	            }
	            var args = arguments;
	            timer = setTimeout(function() {							
	                runner.apply(window, args)
	            }, delay || 100);
	        }
	    },
		
		format: function(s, config, reserve) {
			return s.replace(/\{([^}]*)\}/g, (typeof config == 'object') ? function(m, i){
			        var ret = config[i];
					return ret == null && reserve?
						m
						:
						ret;
			    }
			 : config);
		},
		
		output: function(lib_name, lib) {
			var eval_arr = [];
			lib_name = lib_name || 'crystal';
			for (var p in (lib || this)) {					
				eval_arr.push('var ', p, '=', lib_name, '.', p, ';');
			}
			return eval_arr.join('');
		}
	}
})(),
(function(){var o={ie:0,opera:0,gecko:0,webkit:0,mobile:null};var ua=navigator.userAgent,m;if((/KHTML/).test(ua)){o.webkit=1}m=ua.match(/AppleWebKit\/([^\s]*)/);if(m&&m[1]){o.webkit=parseFloat(m[1]);if(/ Mobile\//.test(ua)){o.mobile="Apple"}else{m=ua.match(/NokiaN[^\/]*/);if(m){o.mobile=m[0]}}}if(!o.webkit){m=ua.match(/Opera[\s\/]([^\s]*)/);if(m&&m[1]){o.opera=parseFloat(m[1]);m=ua.match(/Opera Mini[^;]*/);if(m){o.mobile=m[0]}}else{m=ua.match(/MSIE\s([^;]*)/);if(m&&m[1]){o.ie=parseFloat(m[1])}else{m=ua.match(/Gecko\/([^\s]*)/);if(m){o.gecko=1;m=ua.match(/rv:([^\s\)]*)/);if(m&&m[1]){o.gecko=parseFloat(m[1])}}}}}return o})()

);
