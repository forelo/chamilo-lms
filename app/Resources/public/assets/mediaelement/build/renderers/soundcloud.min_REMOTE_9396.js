/*!
 * MediaElement.js
 * http://www.mediaelementjs.com/
 *
 * Wrapper that mimics native HTML5 MediaElement (audio and video)
 * using a variety of technologies (pure JavaScript, Flash, iframe)
 *
 * Copyright 2010-2017, John Dyer (http://j.hn/)
 * License: MIT
 *
 */
!function e(t,n,r){function a(s,o){if(!n[s]){if(!t[s]){var u="function"==typeof require&&require;if(!o&&u)return u(s,!0);if(i)return i(s,!0);var d=new Error("Cannot find module '"+s+"'");throw d.code="MODULE_NOT_FOUND",d}var c=n[s]={exports:{}};t[s][0].call(c.exports,function(e){var n=t[s][1][e];return a(n||e)},c,c.exports,e,t,n,r)}return n[s].exports}for(var i="function"==typeof require&&require,s=0;s<r.length;s++)a(r[s]);return a}({1:[function(e,t,n){"use strict";var r={promise:null,load:function(e){"undefined"!=typeof SC?r._createPlayer(e):(r.promise=r.promise||mejs.Utils.loadScript("https://w.soundcloud.com/player/api.js"),r.promise.then(function(){r._createPlayer(e)}))},_createPlayer:function(e){var t=SC.Widget(e.iframe);window["__ready__"+e.id](t)}},a={name:"soundcloud_iframe",options:{prefix:"soundcloud_iframe"},canPlayType:function(e){return~["video/soundcloud","video/x-soundcloud"].indexOf(e.toLowerCase())},create:function(e,t,n){var a={},i=[],s=e.originalNode.autoplay,o=null!==e.originalNode&&"video"===e.originalNode.tagName.toLowerCase(),u=0,d=0,c=0,l=!0,p=!1,f=null,v=null;a.options=t,a.id=e.id+"_"+t.prefix,a.mediaElement=e;for(var m=mejs.html5media.properties,h=0,y=m.length;h<y;h++)!function(t){var n=""+t.substring(0,1).toUpperCase()+t.substring(1);a["get"+n]=function(){if(null!==f){switch(t){case"currentTime":return d;case"duration":return u;case"volume":return 1;case"paused":return l;case"ended":return p;case"muted":return!1;case"buffered":return{start:function(){return 0},end:function(){return c*u},length:1};case"src":return v?v.src:"";case"readyState":return 4}return null}return null},a["set"+n]=function(n){if(null!==f)switch(t){case"src":var r="string"==typeof n?n:n[0].src;f.load(r),s&&f.play();break;case"currentTime":f.seekTo(1e3*n);break;case"muted":n?f.setVolume(0):f.setVolume(1),setTimeout(function(){var t=mejs.Utils.createEvent("volumechange",a);e.dispatchEvent(t)},50);break;case"volume":f.setVolume(n),setTimeout(function(){var t=mejs.Utils.createEvent("volumechange",a);e.dispatchEvent(t)},50);break;case"readyState":var o=mejs.Utils.createEvent("canplay",a);e.dispatchEvent(o)}else i.push({type:"set",propName:t,value:n})}}(m[h]);for(var E=mejs.html5media.methods,g=0,S=E.length;g<S;g++)!function(e){a[e]=function(){if(null!==f)switch(e){case"play":return f.play();case"pause":return f.pause();case"load":return null}else i.push({type:"call",methodName:e})}}(E[g]);window["__ready__"+a.id]=function(t){if(e.scPlayer=f=t,s&&f.play(),i.length)for(var n=0,r=i.length;n<r;n++){var o=i[n];if("set"===o.type){var v=o.propName,m=""+v.substring(0,1).toUpperCase()+v.substring(1);a["set"+m](o.value)}else"call"===o.type&&a[o.methodName]()}f.bind(SC.Widget.Events.PLAY_PROGRESS,function(){l=!1,p=!1,f.getPosition(function(t){d=t/1e3;var n=mejs.Utils.createEvent("timeupdate",a);e.dispatchEvent(n)})}),f.bind(SC.Widget.Events.PAUSE,function(){l=!0;var t=mejs.Utils.createEvent("pause",a);e.dispatchEvent(t)}),f.bind(SC.Widget.Events.PLAY,function(){l=!1,p=!1;var t=mejs.Utils.createEvent("play",a);e.dispatchEvent(t)}),f.bind(SC.Widget.Events.FINISHED,function(){l=!1,p=!0;var t=mejs.Utils.createEvent("ended",a);e.dispatchEvent(t)}),f.bind(SC.Widget.Events.READY,function(){f.getDuration(function(t){u=t/1e3;var n=mejs.Utils.createEvent("loadedmetadata",a);e.dispatchEvent(n)})}),f.bind(SC.Widget.Events.LOAD_PROGRESS,function(){f.getDuration(function(t){if(u>0){c=u*t;var n=mejs.Utils.createEvent("progress",a);e.dispatchEvent(n)}}),f.getDuration(function(t){u=t;var n=mejs.Utils.createEvent("loadedmetadata",a);e.dispatchEvent(n)})});for(var h=["rendererready","loadeddata","loadedmetadata","canplay"],y=0,E=h.length;y<E;y++){var g=mejs.Utils.createEvent(h[y],a);e.dispatchEvent(g)}},(v=document.createElement("iframe")).id=a.id,v.width=o?"100%":1,v.height=o?"100%":1,v.frameBorder=0,v.style.visibility=o?"visible":"hidden",v.src=n[0].src,v.scrolling="no",e.appendChild(v),e.originalNode.style.display="none";var U={iframe:v,id:a.id};return r.load(U),a.setSize=function(){},a.hide=function(){a.pause(),v&&(v.style.display="none")},a.show=function(){v&&(v.style.display="")},a.destroy=function(){f.destroy()},a}};mejs.Utils.typeChecks.push(function(e){return/\/\/(w\.)?soundcloud.com/i.test(e)?"video/x-soundcloud":null}),mejs.Renderers.add(a)},{}]},{},[1]);