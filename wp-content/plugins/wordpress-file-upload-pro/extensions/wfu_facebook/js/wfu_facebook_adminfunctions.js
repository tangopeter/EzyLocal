function wfu_facebook_authorize_app_start(e){var a=wfu_GetHttpRequestObject();if(null!=a){var t=AdminParams.wfu_ajax_url;params=new Array(2),params[0]=new Array(2),params[0][0]="action",params[0][1]="wfu_ajax_action_facebook_authorize_app_start",params[1]=new Array(2),params[1][0]="token",params[1][1]=e;for(var r="",s=0;s<params.length;s++)r+=(s>0?"&":"")+params[s][0]+"="+encodeURI(params[s][1]);a.open("POST",t,!0),a.setRequestHeader("Content-type","application/x-www-form-urlencoded"),a.onreadystatechange=function(){if(4==a.readyState&&200==a.status){var t=a.responseText.indexOf("wfu_facebook_authorize_app_start:");-1==t&&(t=a.responseText.length),a.responseText.substr(0,t);var r=a.responseText.substr(t+33,a.responseText.length-t-33);t=r.indexOf(":");var s=r.substr(0,t);if(txt_value=r.substr(t+1,r.length-t-1),"success"==s){var n=window.open(wfu_plugin_decode_string(txt_value),"_blank");n?(n.plugin_window=window,wfu_facebook_authorize_app_finish(e,n)):alert("Please enable popup windows from the browser's settings!")}else"error"==s&&console.log("Facebook application authorization error: "+txt_value)}},a.send(r)}}function wfu_facebook_authorize_app_finish(e,a){var t=wfu_GetHttpRequestObject();if(null!=t){var r=AdminParams.wfu_ajax_url;params=new Array(2),params[0]=new Array(2),params[0][0]="action",params[0][1]="wfu_ajax_action_facebook_authorize_app_finish",params[1]=new Array(2),params[1][0]="token",params[1][1]=e;for(var s="",n=0;n<params.length;n++)s+=(n>0?"&":"")+params[n][0]+"="+encodeURI(params[n][1]);t.open("POST",r,!0),t.setRequestHeader("Content-type","application/x-www-form-urlencoded"),t.onreadystatechange=function(){if(4==t.readyState&&200==t.status){var e=t.responseText.indexOf("wfu_facebook_authorize_app_finish:");-1==e&&(e=t.responseText.length),t.responseText.substr(0,e);var r=t.responseText.substr(e+34,t.responseText.length-e-34);e=r.indexOf(":");var s=r.substr(0,e);txt_value=r.substr(e+1,r.length-e-1),"success"==s?(a.close(),document.getElementById("editsettings").submit()):"error"==s&&console.log("Facebook application authorization error: "+txt_value)}},t.send(s)}}function wfu_facebook_authorize_app_reset(e){var a=wfu_GetHttpRequestObject();if(null!=a){var t=AdminParams.wfu_ajax_url;params=new Array(2),params[0]=new Array(2),params[0][0]="action",params[0][1]="wfu_ajax_action_facebook_authorize_app_reset",params[1]=new Array(2),params[1][0]="token",params[1][1]=e;for(var r="",s=0;s<params.length;s++)r+=(s>0?"&":"")+params[s][0]+"="+encodeURI(params[s][1]);a.open("POST",t,!0),a.setRequestHeader("Content-type","application/x-www-form-urlencoded"),a.onreadystatechange=function(){if(4==a.readyState&&200==a.status){var e=a.responseText.indexOf("wfu_facebook_authorize_app_reset:");-1==e&&(e=a.responseText.length),a.responseText.substr(0,e);var t=a.responseText.substr(e+33,a.responseText.length-e-33);e=t.indexOf(":");var r=t.substr(0,e);txt_value=t.substr(e+1,t.length-e-1),"success"==r?document.getElementById("editsettings").submit():"error"==r&&console.log("Facebook application authorization error: "+txt_value)}},a.send(r)}}
