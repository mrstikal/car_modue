!function(){"use strict";function e(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}const t={_storage:new WeakMap,put:function(e,t,n){this._storage.has(e)||this._storage.set(e,new Map),this._storage.get(e).set(t,n)},get:function(e,t){return this._storage.get(e).get(t)},has:function(e,t){return this._storage.has(e)&&this._storage.get(e).has(t)},remove:function(e,t){var n=this._storage.get(e).delete(t);return 0===!this._storage.get(e).size&&this._storage.delete(e),n}};class n{constructor(){e(this,"mandatories",[]),e(this,"optionals",[]),e(this,"throwErrors",!0)}process(){let e=!1,n="",r=[],c=this.mandatories,a=this.optionals,s=this.throwErrors;if(c.length&&(r=Array.from(document.querySelectorAll(c)),r.forEach((function(t,o){if(t){let o=t.value;null==o&&(o=""),""==o.trim()&&(t.closest(".js_parent").querySelector(".this_element_name").classList.add("red_error"),t.classList.add("red_error"),e=!0,n+='<p>Je třeba vyplnit "'+t.closest(".js_parent").querySelector(".this_element_name").textContent+'"</p>')}})),e)){if(s){let e=document.querySelector(".general_error");e.querySelector(".error_is").innerHTML=n,e.style.display="block",TweenMax.to([document.documentElement,document.body],1,{scrollTop:o(document.querySelector(".red_error")).top})}return!1}a.length&&(r=r.concat(Array.from(document.querySelectorAll(a))));let i=new FormData;return r.forEach((function(e,n){if(e){let n=e.id,o=0;if(t.has(e,"Datepick")){let n=new Date(t.get(e,"Datepick").getDate("mm/dd/yyyy"));e.classList.contains("from")?(n.setHours(0,0,0,0),o=Math.ceil(n.getTime()/1e3)):e.classList.contains("to")?(n.setHours(23,59,59,999),o=Math.floor(n.getTime()/1e3)):(n.setHours(0,0,0,0),o=Math.ceil(n.getTime()/1e3))}else o=e.matches('[type="checkbox"]')?Number(e.checked):e.value.trim();i.append(n,o)}})),i}}const o=function(e){const t=e.getBoundingClientRect();return{top:t.top+window.pageYOffset-document.documentElement.clientTop,left:t.left+window.pageXOffset-document.documentElement.clientLeft}};Date,document.addEventListener("DOMContentLoaded",(function(e){IMask(document.querySelector("#fin_dph_amount"),{mask:Number,scale:0,signed:!1,max:99,min:0}),function(e,t,n,o){let r;r=Array.from(document.querySelectorAll(e));let c="click".split(" ");{let e=n;r.forEach((function(t){c.forEach((function(n){t.addEventListener(n,(function(n){e(t,n)}))}))}))}}(".save",0,(function(){let e=new n;e.optionals=["#fin_dph","#fin_dph_amount","#fin_account_number","#fin_bank_code","#fin_ico","#fin_dic","#fin_registration","#place_company_name","#place_company_street","#place_company_town","#place_zip","#place_state","#place_infoline","#place_email","#place_web","#place_opening","#branch_street","#branch_town","#branch_zip","#branch_state"];let t=e.process();!function(e,t="block"){Array.from(document.querySelectorAll(e)).forEach((function(e){e.style.display=t}))}(".load_overlay"),fetch(save_company_ajax_url,{method:"POST",body:t,cache:"no-cache"}).then((e=>{if(!e.ok)throw new Error("Network response error");return e.json()})).then((e=>{Array.from(document.querySelectorAll(".load_overlay")).forEach((function(e){e.style.display="none"})),show_success.play(0)})).catch((function(e){console.log(e)}))}))}))}();