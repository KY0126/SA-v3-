var Vt=Object.defineProperty;var ot=e=>{throw TypeError(e)};var Pt=(e,t,s)=>t in e?Vt(e,t,{enumerable:!0,configurable:!0,writable:!0,value:s}):e[t]=s;var E=(e,t,s)=>Pt(e,typeof t!="symbol"?t+"":t,s),et=(e,t,s)=>t.has(e)||ot("Cannot "+s);var l=(e,t,s)=>(et(e,t,"read from private field"),s?s.call(e):t.get(e)),m=(e,t,s)=>t.has(e)?ot("Cannot add the same private member more than once"):t instanceof WeakSet?t.add(e):t.set(e,s),h=(e,t,s,a)=>(et(e,t,"write to private field"),a?a.call(e,s):t.set(e,s),s),A=(e,t,s)=>(et(e,t,"access private method"),s);var lt=(e,t,s,a)=>({set _(n){h(e,t,n,s)},get _(){return l(e,t,a)}});var ct=(e,t,s)=>(a,n)=>{let i=-1;return r(0);async function r(c){if(c<=i)throw new Error("next() called multiple times");i=c;let o,d=!1,u;if(e[c]?(u=e[c][0][0],a.req.routeIndex=c):u=c===e.length&&n||void 0,u)try{o=await u(a,()=>r(c+1))}catch(p){if(p instanceof Error&&t)a.error=p,o=await t(p,a),d=!0;else throw p}else a.finalized===!1&&s&&(o=await s(a));return o&&(a.finalized===!1||d)&&(a.res=o),a}},Ft=Symbol(),qt=async(e,t=Object.create(null))=>{const{all:s=!1,dot:a=!1}=t,i=(e instanceof xt?e.raw.headers:e.headers).get("Content-Type");return i!=null&&i.startsWith("multipart/form-data")||i!=null&&i.startsWith("application/x-www-form-urlencoded")?kt(e,{all:s,dot:a}):{}};async function kt(e,t){const s=await e.formData();return s?Ht(s,t):{}}function Ht(e,t){const s=Object.create(null);return e.forEach((a,n)=>{t.all||n.endsWith("[]")?Wt(s,n,a):s[n]=a}),t.dot&&Object.entries(s).forEach(([a,n])=>{a.includes(".")&&(Jt(s,a,n),delete s[a])}),s}var Wt=(e,t,s)=>{e[t]!==void 0?Array.isArray(e[t])?e[t].push(s):e[t]=[e[t],s]:t.endsWith("[]")?e[t]=[s]:e[t]=s},Jt=(e,t,s)=>{if(/(?:^|\.)__proto__\./.test(t))return;let a=e;const n=t.split(".");n.forEach((i,r)=>{r===n.length-1?a[i]=s:((!a[i]||typeof a[i]!="object"||Array.isArray(a[i])||a[i]instanceof File)&&(a[i]=Object.create(null)),a=a[i])})},_t=e=>{const t=e.split("/");return t[0]===""&&t.shift(),t},Yt=e=>{const{groups:t,path:s}=Qt(e),a=_t(s);return $t(a,t)},Qt=e=>{const t=[];return e=e.replace(/\{[^}]+\}/g,(s,a)=>{const n=`@${a}`;return t.push([n,s]),n}),{groups:t,path:e}},$t=(e,t)=>{for(let s=t.length-1;s>=0;s--){const[a]=t[s];for(let n=e.length-1;n>=0;n--)if(e[n].includes(a)){e[n]=e[n].replace(a,t[s][1]);break}}return e},He={},Gt=(e,t)=>{if(e==="*")return"*";const s=e.match(/^\:([^\{\}]+)(?:\{(.+)\})?$/);if(s){const a=`${e}#${t}`;return He[a]||(s[2]?He[a]=t&&t[0]!==":"&&t[0]!=="*"?[a,s[1],new RegExp(`^${s[2]}(?=/${t})`)]:[e,s[1],new RegExp(`^${s[2]}$`)]:He[a]=[e,s[1],!0]),He[a]}return null},nt=(e,t)=>{try{return t(e)}catch{return e.replace(/(?:%[0-9A-Fa-f]{2})+/g,s=>{try{return t(s)}catch{return s}})}},zt=e=>nt(e,decodeURI),vt=e=>{const t=e.url,s=t.indexOf("/",t.indexOf(":")+4);let a=s;for(;a<t.length;a++){const n=t.charCodeAt(a);if(n===37){const i=t.indexOf("?",a),r=t.indexOf("#",a),c=i===-1?r===-1?void 0:r:r===-1?i:Math.min(i,r),o=t.slice(s,c);return zt(o.includes("%25")?o.replace(/%25/g,"%2525"):o)}else if(n===63||n===35)break}return t.slice(s,a)},Kt=e=>{const t=vt(e);return t.length>1&&t.at(-1)==="/"?t.slice(0,-1):t},be=(e,t,...s)=>(s.length&&(t=be(t,...s)),`${(e==null?void 0:e[0])==="/"?"":"/"}${e}${t==="/"?"":`${(e==null?void 0:e.at(-1))==="/"?"":"/"}${(t==null?void 0:t[0])==="/"?t.slice(1):t}`}`),yt=e=>{if(e.charCodeAt(e.length-1)!==63||!e.includes(":"))return null;const t=e.split("/"),s=[];let a="";return t.forEach(n=>{if(n!==""&&!/\:/.test(n))a+="/"+n;else if(/\:/.test(n))if(/\?/.test(n)){s.length===0&&a===""?s.push("/"):s.push(a);const i=n.replace("?","");a+="/"+i,s.push(a)}else a+="/"+n}),s.filter((n,i,r)=>r.indexOf(n)===i)},tt=e=>/[%+]/.test(e)?(e.indexOf("+")!==-1&&(e=e.replace(/\+/g," ")),e.indexOf("%")!==-1?nt(e,It):e):e,Tt=(e,t,s)=>{let a;if(!s&&t&&!/[%+]/.test(t)){let r=e.indexOf("?",8);if(r===-1)return;for(e.startsWith(t,r+1)||(r=e.indexOf(`&${t}`,r+1));r!==-1;){const c=e.charCodeAt(r+t.length+1);if(c===61){const o=r+t.length+2,d=e.indexOf("&",o);return tt(e.slice(o,d===-1?void 0:d))}else if(c==38||isNaN(c))return"";r=e.indexOf(`&${t}`,r+1)}if(a=/[%+]/.test(e),!a)return}const n={};a??(a=/[%+]/.test(e));let i=e.indexOf("?",8);for(;i!==-1;){const r=e.indexOf("&",i+1);let c=e.indexOf("=",i);c>r&&r!==-1&&(c=-1);let o=e.slice(i+1,c===-1?r===-1?void 0:r:c);if(a&&(o=tt(o)),i=r,o==="")continue;let d;c===-1?d="":(d=e.slice(c+1,r===-1?void 0:r),a&&(d=tt(d))),s?(n[o]&&Array.isArray(n[o])||(n[o]=[]),n[o].push(d)):n[o]??(n[o]=d)}return t?n[t]:n},Xt=Tt,Zt=(e,t)=>Tt(e,t,!0),It=decodeURIComponent,dt=e=>nt(e,It),ge,j,k,Nt,St,at,G,ht,xt=(ht=class{constructor(e,t="/",s=[[]]){m(this,k);E(this,"raw");m(this,ge);m(this,j);E(this,"routeIndex",0);E(this,"path");E(this,"bodyCache",{});m(this,G,e=>{const{bodyCache:t,raw:s}=this,a=t[e];if(a)return a;const n=Object.keys(t)[0];return n?t[n].then(i=>(n==="json"&&(i=JSON.stringify(i)),new Response(i)[e]())):t[e]=s[e]()});this.raw=e,this.path=t,h(this,j,s),h(this,ge,{})}param(e){return e?A(this,k,Nt).call(this,e):A(this,k,St).call(this)}query(e){return Xt(this.url,e)}queries(e){return Zt(this.url,e)}header(e){if(e)return this.raw.headers.get(e)??void 0;const t={};return this.raw.headers.forEach((s,a)=>{t[a]=s}),t}async parseBody(e){return qt(this,e)}json(){return l(this,G).call(this,"text").then(e=>JSON.parse(e))}text(){return l(this,G).call(this,"text")}arrayBuffer(){return l(this,G).call(this,"arrayBuffer")}blob(){return l(this,G).call(this,"blob")}formData(){return l(this,G).call(this,"formData")}addValidatedData(e,t){l(this,ge)[e]=t}valid(e){return l(this,ge)[e]}get url(){return this.raw.url}get method(){return this.raw.method}get[Ft](){return l(this,j)}get matchedRoutes(){return l(this,j)[0].map(([[,e]])=>e)}get routePath(){return l(this,j)[0].map(([[,e]])=>e)[this.routeIndex].path}},ge=new WeakMap,j=new WeakMap,k=new WeakSet,Nt=function(e){const t=l(this,j)[0][this.routeIndex][1][e],s=A(this,k,at).call(this,t);return s&&/\%/.test(s)?dt(s):s},St=function(){const e={},t=Object.keys(l(this,j)[0][this.routeIndex][1]);for(const s of t){const a=A(this,k,at).call(this,l(this,j)[0][this.routeIndex][1][s]);a!==void 0&&(e[s]=/\%/.test(a)?dt(a):a)}return e},at=function(e){return l(this,j)[1]?l(this,j)[1][e]:e},G=new WeakMap,ht),es={Stringify:1},Rt=async(e,t,s,a,n)=>{typeof e=="object"&&!(e instanceof String)&&(e instanceof Promise||(e=e.toString()),e instanceof Promise&&(e=await e));const i=e.callbacks;return i!=null&&i.length?(n?n[0]+=e:n=[e],Promise.all(i.map(c=>c({phase:t,buffer:n,context:a}))).then(c=>Promise.all(c.filter(Boolean).map(o=>Rt(o,t,!1,a,n))).then(()=>n[0]))):Promise.resolve(e)},ts="text/plain; charset=UTF-8",st=(e,t)=>({"Content-Type":e,...t}),De=(e,t)=>new Response(e,t),Oe,Le,V,_e,P,S,Me,ve,ye,ie,Be,Ve,z,me,Et,ss=(Et=class{constructor(e,t){m(this,z);m(this,Oe);m(this,Le);E(this,"env",{});m(this,V);E(this,"finalized",!1);E(this,"error");m(this,_e);m(this,P);m(this,S);m(this,Me);m(this,ve);m(this,ye);m(this,ie);m(this,Be);m(this,Ve);E(this,"render",(...e)=>(l(this,ve)??h(this,ve,t=>this.html(t)),l(this,ve).call(this,...e)));E(this,"setLayout",e=>h(this,Me,e));E(this,"getLayout",()=>l(this,Me));E(this,"setRenderer",e=>{h(this,ve,e)});E(this,"header",(e,t,s)=>{this.finalized&&h(this,S,De(l(this,S).body,l(this,S)));const a=l(this,S)?l(this,S).headers:l(this,ie)??h(this,ie,new Headers);t===void 0?a.delete(e):s!=null&&s.append?a.append(e,t):a.set(e,t)});E(this,"status",e=>{h(this,_e,e)});E(this,"set",(e,t)=>{l(this,V)??h(this,V,new Map),l(this,V).set(e,t)});E(this,"get",e=>l(this,V)?l(this,V).get(e):void 0);E(this,"newResponse",(...e)=>A(this,z,me).call(this,...e));E(this,"body",(e,t,s)=>A(this,z,me).call(this,e,t,s));E(this,"text",(e,t,s)=>!l(this,ie)&&!l(this,_e)&&!t&&!s&&!this.finalized?new Response(e):A(this,z,me).call(this,e,t,st(ts,s)));E(this,"json",(e,t,s)=>A(this,z,me).call(this,JSON.stringify(e),t,st("application/json",s)));E(this,"html",(e,t,s)=>{const a=n=>A(this,z,me).call(this,n,t,st("text/html; charset=UTF-8",s));return typeof e=="object"?Rt(e,es.Stringify,!1,{}).then(a):a(e)});E(this,"redirect",(e,t)=>{const s=String(e);return this.header("Location",/[^\x00-\xFF]/.test(s)?encodeURI(s):s),this.newResponse(null,t??302)});E(this,"notFound",()=>(l(this,ye)??h(this,ye,()=>De()),l(this,ye).call(this,this)));h(this,Oe,e),t&&(h(this,P,t.executionCtx),this.env=t.env,h(this,ye,t.notFoundHandler),h(this,Ve,t.path),h(this,Be,t.matchResult))}get req(){return l(this,Le)??h(this,Le,new xt(l(this,Oe),l(this,Ve),l(this,Be))),l(this,Le)}get event(){if(l(this,P)&&"respondWith"in l(this,P))return l(this,P);throw Error("This context has no FetchEvent")}get executionCtx(){if(l(this,P))return l(this,P);throw Error("This context has no ExecutionContext")}get res(){return l(this,S)||h(this,S,De(null,{headers:l(this,ie)??h(this,ie,new Headers)}))}set res(e){if(l(this,S)&&e){e=De(e.body,e);for(const[t,s]of l(this,S).headers.entries())if(t!=="content-type")if(t==="set-cookie"){const a=l(this,S).headers.getSetCookie();e.headers.delete("set-cookie");for(const n of a)e.headers.append("set-cookie",n)}else e.headers.set(t,s)}h(this,S,e),this.finalized=!0}get var(){return l(this,V)?Object.fromEntries(l(this,V)):{}}},Oe=new WeakMap,Le=new WeakMap,V=new WeakMap,_e=new WeakMap,P=new WeakMap,S=new WeakMap,Me=new WeakMap,ve=new WeakMap,ye=new WeakMap,ie=new WeakMap,Be=new WeakMap,Ve=new WeakMap,z=new WeakSet,me=function(e,t,s){const a=l(this,S)?new Headers(l(this,S).headers):l(this,ie)??new Headers;if(typeof t=="object"&&"headers"in t){const i=t.headers instanceof Headers?t.headers:new Headers(t.headers);for(const[r,c]of i)r.toLowerCase()==="set-cookie"?a.append(r,c):a.set(r,c)}if(s)for(const[i,r]of Object.entries(s))if(typeof r=="string")a.set(i,r);else{a.delete(i);for(const c of r)a.append(i,c)}const n=typeof t=="number"?t:(t==null?void 0:t.status)??l(this,_e);return De(e,{status:n,headers:a})},Et),y="ALL",as="all",ns=["get","post","put","delete","options","patch"],Ut="Can not add a route since the matcher is already built.",Dt=class extends Error{},is="__COMPOSED_HANDLER",rs=e=>e.text("404 Not Found",404),ut=(e,t)=>{if("getResponse"in e){const s=e.getResponse();return t.newResponse(s.body,s)}return console.error(e),t.text("Internal Server Error",500)},C,T,wt,O,ae,We,Je,Te,os=(Te=class{constructor(t={}){m(this,T);E(this,"get");E(this,"post");E(this,"put");E(this,"delete");E(this,"options");E(this,"patch");E(this,"all");E(this,"on");E(this,"use");E(this,"router");E(this,"getPath");E(this,"_basePath","/");m(this,C,"/");E(this,"routes",[]);m(this,O,rs);E(this,"errorHandler",ut);E(this,"onError",t=>(this.errorHandler=t,this));E(this,"notFound",t=>(h(this,O,t),this));E(this,"fetch",(t,...s)=>A(this,T,Je).call(this,t,s[1],s[0],t.method));E(this,"request",(t,s,a,n)=>t instanceof Request?this.fetch(s?new Request(t,s):t,a,n):(t=t.toString(),this.fetch(new Request(/^https?:\/\//.test(t)?t:`http://localhost${be("/",t)}`,s),a,n)));E(this,"fire",()=>{addEventListener("fetch",t=>{t.respondWith(A(this,T,Je).call(this,t.request,t,void 0,t.request.method))})});[...ns,as].forEach(i=>{this[i]=(r,...c)=>(typeof r=="string"?h(this,C,r):A(this,T,ae).call(this,i,l(this,C),r),c.forEach(o=>{A(this,T,ae).call(this,i,l(this,C),o)}),this)}),this.on=(i,r,...c)=>{for(const o of[r].flat()){h(this,C,o);for(const d of[i].flat())c.map(u=>{A(this,T,ae).call(this,d.toUpperCase(),l(this,C),u)})}return this},this.use=(i,...r)=>(typeof i=="string"?h(this,C,i):(h(this,C,"*"),r.unshift(i)),r.forEach(c=>{A(this,T,ae).call(this,y,l(this,C),c)}),this);const{strict:a,...n}=t;Object.assign(this,n),this.getPath=a??!0?t.getPath??vt:Kt}route(t,s){const a=this.basePath(t);return s.routes.map(n=>{var r;let i;s.errorHandler===ut?i=n.handler:(i=async(c,o)=>(await ct([],s.errorHandler)(c,()=>n.handler(c,o))).res,i[is]=n.handler),A(r=a,T,ae).call(r,n.method,n.path,i)}),this}basePath(t){const s=A(this,T,wt).call(this);return s._basePath=be(this._basePath,t),s}mount(t,s,a){let n,i;a&&(typeof a=="function"?i=a:(i=a.optionHandler,a.replaceRequest===!1?n=o=>o:n=a.replaceRequest));const r=i?o=>{const d=i(o);return Array.isArray(d)?d:[d]}:o=>{let d;try{d=o.executionCtx}catch{}return[o.env,d]};n||(n=(()=>{const o=be(this._basePath,t),d=o==="/"?0:o.length;return u=>{const p=new URL(u.url);return p.pathname=p.pathname.slice(d)||"/",new Request(p,u)}})());const c=async(o,d)=>{const u=await s(n(o.req.raw),...r(o));if(u)return u;await d()};return A(this,T,ae).call(this,y,be(t,"*"),c),this}},C=new WeakMap,T=new WeakSet,wt=function(){const t=new Te({router:this.router,getPath:this.getPath});return t.errorHandler=this.errorHandler,h(t,O,l(this,O)),t.routes=this.routes,t},O=new WeakMap,ae=function(t,s,a){t=t.toUpperCase(),s=be(this._basePath,s);const n={basePath:this._basePath,path:s,method:t,handler:a};this.router.add(t,s,[a,n]),this.routes.push(n)},We=function(t,s){if(t instanceof Error)return this.errorHandler(t,s);throw t},Je=function(t,s,a,n){if(n==="HEAD")return(async()=>new Response(null,await A(this,T,Je).call(this,t,s,a,"GET")))();const i=this.getPath(t,{env:a}),r=this.router.match(n,i),c=new ss(t,{path:i,matchResult:r,env:a,executionCtx:s,notFoundHandler:l(this,O)});if(r[0].length===1){let d;try{d=r[0][0][0][0](c,async()=>{c.res=await l(this,O).call(this,c)})}catch(u){return A(this,T,We).call(this,u,c)}return d instanceof Promise?d.then(u=>u||(c.finalized?c.res:l(this,O).call(this,c))).catch(u=>A(this,T,We).call(this,u,c)):d??l(this,O).call(this,c)}const o=ct(r[0],this.errorHandler,l(this,O));return(async()=>{try{const d=await o(c);if(!d.finalized)throw new Error("Context is not finalized. Did you forget to return a Response object or `await next()`?");return d.res}catch(d){return A(this,T,We).call(this,d,c)}})()},Te),jt=[];function ls(e,t){const s=this.buildAllMatchers(),a=(n,i)=>{const r=s[n]||s[y],c=r[2][i];if(c)return c;const o=i.match(r[0]);if(!o)return[[],jt];const d=o.indexOf("",1);return[r[1][d],o]};return this.match=a,a(e,t)}var Qe="[^/]+",je=".*",Ce="(?:|/.*)",Ae=Symbol(),cs=new Set(".\\+*[^]$()");function ds(e,t){return e.length===1?t.length===1?e<t?-1:1:-1:t.length===1||e===je||e===Ce?1:t===je||t===Ce?-1:e===Qe?1:t===Qe?-1:e.length===t.length?e<t?-1:1:t.length-e.length}var re,oe,L,de,us=(de=class{constructor(){m(this,re);m(this,oe);m(this,L,Object.create(null))}insert(t,s,a,n,i){if(t.length===0){if(l(this,re)!==void 0)throw Ae;if(i)return;h(this,re,s);return}const[r,...c]=t,o=r==="*"?c.length===0?["","",je]:["","",Qe]:r==="/*"?["","",Ce]:r.match(/^\:([^\{\}]+)(?:\{(.+)\})?$/);let d;if(o){const u=o[1];let p=o[2]||Qe;if(u&&o[2]&&(p===".*"||(p=p.replace(/^\((?!\?:)(?=[^)]+\)$)/,"(?:"),/\((?!\?:)/.test(p))))throw Ae;if(d=l(this,L)[p],!d){if(Object.keys(l(this,L)).some(f=>f!==je&&f!==Ce))throw Ae;if(i)return;d=l(this,L)[p]=new de,u!==""&&h(d,oe,n.varIndex++)}!i&&u!==""&&a.push([u,l(d,oe)])}else if(d=l(this,L)[r],!d){if(Object.keys(l(this,L)).some(u=>u.length>1&&u!==je&&u!==Ce))throw Ae;if(i)return;d=l(this,L)[r]=new de}d.insert(c,s,a,n,i)}buildRegExpStr(){const s=Object.keys(l(this,L)).sort(ds).map(a=>{const n=l(this,L)[a];return(typeof l(n,oe)=="number"?`(${a})@${l(n,oe)}`:cs.has(a)?`\\${a}`:a)+n.buildRegExpStr()});return typeof l(this,re)=="number"&&s.unshift(`#${l(this,re)}`),s.length===0?"":s.length===1?s[0]:"(?:"+s.join("|")+")"}},re=new WeakMap,oe=new WeakMap,L=new WeakMap,de),$e,Pe,bt,ps=(bt=class{constructor(){m(this,$e,{varIndex:0});m(this,Pe,new us)}insert(e,t,s){const a=[],n=[];for(let r=0;;){let c=!1;if(e=e.replace(/\{[^}]+\}/g,o=>{const d=`@\\${r}`;return n[r]=[d,o],r++,c=!0,d}),!c)break}const i=e.match(/(?::[^\/]+)|(?:\/\*$)|./g)||[];for(let r=n.length-1;r>=0;r--){const[c]=n[r];for(let o=i.length-1;o>=0;o--)if(i[o].indexOf(c)!==-1){i[o]=i[o].replace(c,n[r][1]);break}}return l(this,Pe).insert(i,t,a,l(this,$e),s),a}buildRegExp(){let e=l(this,Pe).buildRegExpStr();if(e==="")return[/^$/,[],[]];let t=0;const s=[],a=[];return e=e.replace(/#(\d+)|@(\d+)|\.\*\$/g,(n,i,r)=>i!==void 0?(s[++t]=Number(i),"$()"):(r!==void 0&&(a[Number(r)]=++t),"")),[new RegExp(`^${e}`),s,a]}},$e=new WeakMap,Pe=new WeakMap,bt),fs=[/^$/,[],Object.create(null)],Ye=Object.create(null);function Ct(e){return Ye[e]??(Ye[e]=new RegExp(e==="*"?"":`^${e.replace(/\/\*$|([.\\+*[^\]$()])/g,(t,s)=>s?`\\${s}`:"(?:|/.*)")}$`))}function hs(){Ye=Object.create(null)}function Es(e){var d;const t=new ps,s=[];if(e.length===0)return fs;const a=e.map(u=>[!/\*|\/:/.test(u[0]),...u]).sort(([u,p],[f,g])=>u?1:f?-1:p.length-g.length),n=Object.create(null);for(let u=0,p=-1,f=a.length;u<f;u++){const[g,_,w]=a[u];g?n[_]=[w.map(([U])=>[U,Object.create(null)]),jt]:p++;let R;try{R=t.insert(_,p,g)}catch(U){throw U===Ae?new Dt(_):U}g||(s[p]=w.map(([U,b])=>{const D=Object.create(null);for(b-=1;b>=0;b--){const[Q,Xe]=R[b];D[Q]=Xe}return[U,D]}))}const[i,r,c]=t.buildRegExp();for(let u=0,p=s.length;u<p;u++)for(let f=0,g=s[u].length;f<g;f++){const _=(d=s[u][f])==null?void 0:d[1];if(!_)continue;const w=Object.keys(_);for(let R=0,U=w.length;R<U;R++)_[w[R]]=c[_[w[R]]]}const o=[];for(const u in r)o[u]=s[r[u]];return[i,o,n]}function Ee(e,t){if(e){for(const s of Object.keys(e).sort((a,n)=>n.length-a.length))if(Ct(s).test(t))return[...e[s]]}}var K,X,Ge,Ot,mt,bs=(mt=class{constructor(){m(this,Ge);E(this,"name","RegExpRouter");m(this,K);m(this,X);E(this,"match",ls);h(this,K,{[y]:Object.create(null)}),h(this,X,{[y]:Object.create(null)})}add(e,t,s){var c;const a=l(this,K),n=l(this,X);if(!a||!n)throw new Error(Ut);a[e]||[a,n].forEach(o=>{o[e]=Object.create(null),Object.keys(o[y]).forEach(d=>{o[e][d]=[...o[y][d]]})}),t==="/*"&&(t="*");const i=(t.match(/\/:/g)||[]).length;if(/\*$/.test(t)){const o=Ct(t);e===y?Object.keys(a).forEach(d=>{var u;(u=a[d])[t]||(u[t]=Ee(a[d],t)||Ee(a[y],t)||[])}):(c=a[e])[t]||(c[t]=Ee(a[e],t)||Ee(a[y],t)||[]),Object.keys(a).forEach(d=>{(e===y||e===d)&&Object.keys(a[d]).forEach(u=>{o.test(u)&&a[d][u].push([s,i])})}),Object.keys(n).forEach(d=>{(e===y||e===d)&&Object.keys(n[d]).forEach(u=>o.test(u)&&n[d][u].push([s,i]))});return}const r=yt(t)||[t];for(let o=0,d=r.length;o<d;o++){const u=r[o];Object.keys(n).forEach(p=>{var f;(e===y||e===p)&&((f=n[p])[u]||(f[u]=[...Ee(a[p],u)||Ee(a[y],u)||[]]),n[p][u].push([s,i-d+o+1]))})}}buildAllMatchers(){const e=Object.create(null);return Object.keys(l(this,X)).concat(Object.keys(l(this,K))).forEach(t=>{e[t]||(e[t]=A(this,Ge,Ot).call(this,t))}),h(this,K,h(this,X,void 0)),hs(),e}},K=new WeakMap,X=new WeakMap,Ge=new WeakSet,Ot=function(e){const t=[];let s=e===y;return[l(this,K),l(this,X)].forEach(a=>{const n=a[e]?Object.keys(a[e]).map(i=>[i,a[e][i]]):[];n.length!==0?(s||(s=!0),t.push(...n)):e!==y&&t.push(...Object.keys(a[y]).map(i=>[i,a[y][i]]))}),s?Es(t):null},mt),Z,F,At,ms=(At=class{constructor(e){E(this,"name","SmartRouter");m(this,Z,[]);m(this,F,[]);h(this,Z,e.routers)}add(e,t,s){if(!l(this,F))throw new Error(Ut);l(this,F).push([e,t,s])}match(e,t){if(!l(this,F))throw new Error("Fatal error");const s=l(this,Z),a=l(this,F),n=s.length;let i=0,r;for(;i<n;i++){const c=s[i];try{for(let o=0,d=a.length;o<d;o++)c.add(...a[o]);r=c.match(e,t)}catch(o){if(o instanceof Dt)continue;throw o}this.match=c.match.bind(c),h(this,Z,[c]),h(this,F,void 0);break}if(i===n)throw new Error("Fatal error");return this.name=`SmartRouter + ${this.activeRouter.name}`,r}get activeRouter(){if(l(this,F)||l(this,Z).length!==1)throw new Error("No active router has been determined yet.");return l(this,Z)[0]}},Z=new WeakMap,F=new WeakMap,At),we=Object.create(null),As=e=>{for(const t in e)return!0;return!1},ee,N,le,Ie,x,q,ne,xe,gs=(xe=class{constructor(t,s,a){m(this,q);m(this,ee);m(this,N);m(this,le);m(this,Ie,0);m(this,x,we);if(h(this,N,a||Object.create(null)),h(this,ee,[]),t&&s){const n=Object.create(null);n[t]={handler:s,possibleKeys:[],score:0},h(this,ee,[n])}h(this,le,[])}insert(t,s,a){h(this,Ie,++lt(this,Ie)._);let n=this;const i=Yt(s),r=[];for(let c=0,o=i.length;c<o;c++){const d=i[c],u=i[c+1],p=Gt(d,u),f=Array.isArray(p)?p[0]:d;if(f in l(n,N)){n=l(n,N)[f],p&&r.push(p[1]);continue}l(n,N)[f]=new xe,p&&(l(n,le).push(p),r.push(p[1])),n=l(n,N)[f]}return l(n,ee).push({[t]:{handler:a,possibleKeys:r.filter((c,o,d)=>d.indexOf(c)===o),score:l(this,Ie)}}),n}search(t,s){var u;const a=[];h(this,x,we);let i=[this];const r=_t(s),c=[],o=r.length;let d=null;for(let p=0;p<o;p++){const f=r[p],g=p===o-1,_=[];for(let R=0,U=i.length;R<U;R++){const b=i[R],D=l(b,N)[f];D&&(h(D,x,l(b,x)),g?(l(D,N)["*"]&&A(this,q,ne).call(this,a,l(D,N)["*"],t,l(b,x)),A(this,q,ne).call(this,a,D,t,l(b,x))):_.push(D));for(let Q=0,Xe=l(b,le).length;Q<Xe;Q++){const it=l(b,le)[Q],$=l(b,x)===we?{}:{...l(b,x)};if(it==="*"){const fe=l(b,N)["*"];fe&&(A(this,q,ne).call(this,a,fe,t,l(b,x)),h(fe,x,$),_.push(fe));continue}const[Bt,rt,Re]=it;if(!f&&!(Re instanceof RegExp))continue;const B=l(b,N)[Bt];if(Re instanceof RegExp){if(d===null){d=new Array(o);let he=s[0]==="/"?1:0;for(let Ue=0;Ue<o;Ue++)d[Ue]=he,he+=r[Ue].length+1}const fe=s.substring(d[p]),Ze=Re.exec(fe);if(Ze){if($[rt]=Ze[0],A(this,q,ne).call(this,a,B,t,l(b,x),$),As(l(B,N))){h(B,x,$);const he=((u=Ze[0].match(/\//))==null?void 0:u.length)??0;(c[he]||(c[he]=[])).push(B)}continue}}(Re===!0||Re.test(f))&&($[rt]=f,g?(A(this,q,ne).call(this,a,B,t,$,l(b,x)),l(B,N)["*"]&&A(this,q,ne).call(this,a,l(B,N)["*"],t,$,l(b,x))):(h(B,x,$),_.push(B)))}}const w=c.shift();i=w?_.concat(w):_}return a.length>1&&a.sort((p,f)=>p.score-f.score),[a.map(({handler:p,params:f})=>[p,f])]}},ee=new WeakMap,N=new WeakMap,le=new WeakMap,Ie=new WeakMap,x=new WeakMap,q=new WeakSet,ne=function(t,s,a,n,i){for(let r=0,c=l(s,ee).length;r<c;r++){const o=l(s,ee)[r],d=o[a]||o[y],u={};if(d!==void 0&&(d.params=Object.create(null),t.push(d),n!==we||i&&i!==we))for(let p=0,f=d.possibleKeys.length;p<f;p++){const g=d.possibleKeys[p],_=u[d.score];d.params[g]=i!=null&&i[g]&&!_?i[g]:n[g]??(i==null?void 0:i[g]),u[d.score]=!0}}},xe),ce,gt,_s=(gt=class{constructor(){E(this,"name","TrieRouter");m(this,ce);h(this,ce,new gs)}add(e,t,s){const a=yt(t);if(a){for(let n=0,i=a.length;n<i;n++)l(this,ce).insert(e,a[n],s);return}l(this,ce).insert(e,t,s)}match(e,t){return l(this,ce).search(e,t)}},ce=new WeakMap,gt),I=class extends os{constructor(e={}){super(e),this.router=e.router??new ms({routers:[new bs,new _s]})}},vs=e=>{const s={...{origin:"*",allowMethods:["GET","HEAD","PUT","POST","DELETE","PATCH"],allowHeaders:[],exposeHeaders:[]},...e},a=(i=>typeof i=="string"?i==="*"?s.credentials?r=>r||null:()=>i:r=>i===r?r:null:typeof i=="function"?i:r=>i.includes(r)?r:null)(s.origin),n=(i=>typeof i=="function"?i:Array.isArray(i)?()=>i:()=>[])(s.allowMethods);return async function(r,c){var u;function o(p,f){r.res.headers.set(p,f)}const d=await a(r.req.header("origin")||"",r);if(d&&o("Access-Control-Allow-Origin",d),s.credentials&&o("Access-Control-Allow-Credentials","true"),(u=s.exposeHeaders)!=null&&u.length&&o("Access-Control-Expose-Headers",s.exposeHeaders.join(",")),r.req.method==="OPTIONS"){(s.origin!=="*"||s.credentials)&&o("Vary","Origin"),s.maxAge!=null&&o("Access-Control-Max-Age",s.maxAge.toString());const p=await n(r.req.header("origin")||"",r);p.length&&o("Access-Control-Allow-Methods",p.join(","));let f=s.allowHeaders;if(!(f!=null&&f.length)){const g=r.req.header("Access-Control-Request-Headers");g&&(f=g.split(/\s*,\s*/))}return f!=null&&f.length&&(o("Access-Control-Allow-Headers",f.join(",")),r.res.headers.append("Vary","Access-Control-Request-Headers")),r.res.headers.delete("Content-Length"),r.res.headers.delete("Content-Type"),new Response(null,{headers:r.res.headers,status:204,statusText:"No Content"})}await c(),(s.origin!=="*"||s.credentials)&&r.header("Vary","Origin",{append:!0})}};const Fe=new I;Fe.post("/login",async e=>{const{email:t,password:s}=await e.req.json();if(!t)return e.json({success:!1,message:"請輸入 Email"},400);const a=e.env.DB,n=await a.prepare("SELECT * FROM User WHERE USR_EMAIL = ?").bind(t).first();if(!n)return e.json({success:!1,message:"查無此帳號，請確認 Email 或建立帳號"},401);if(n.USR_SUSPENDED===1)return e.json({success:!1,message:"帳號已被停權，請聯繫課指組或透過申復流程處理"},403);if(n.USR_EXPIRE_DATE&&new Date(n.USR_EXPIRE_DATE)<new Date)return e.json({success:!1,message:`帳號使用資格已到期（到期日：${n.USR_EXPIRE_DATE}），如有疑問請洽課指組`},403);const i=JSON.stringify({id:n.USR_ID,email:n.USR_EMAIL,role:n.USR_ROLE,name:n.USR_NAME,isAdmin:n.USR_IS_ADMIN}),r=new TextEncoder().encode(i),c=Array.from(r).map(u=>String.fromCharCode(u)).join(""),o=btoa(c),{results:d}=await a.prepare("SELECT UM.UM_UNIT_ID, U.UNIT_NAME, U.UNIT_TYPE FROM UnitMember UM LEFT JOIN Unit U ON UM.UM_UNIT_ID = U.UNIT_ID WHERE UM.UM_USR_ID = ? AND UM.UM_ACTIVE = 1 AND U.UNIT_ACTIVE = 1").bind(n.USR_ID).all();return e.json({success:!0,message:"登入成功",token:o,user:{id:n.USR_ID,email:n.USR_EMAIL,name:n.USR_NAME,role:n.USR_ROLE,isAdmin:n.USR_IS_ADMIN,phone:n.USR_PHONE,avatar:n.USR_AVATAR,expireDate:n.USR_EXPIRE_DATE,units:d||[]}})});Fe.post("/register",async e=>{const{email:t,name:s,phone:a,role:n,password:i}=await e.req.json();if(!t||!s||!i)return e.json({success:!1,message:"請填寫必要欄位"},400);if(i.length<8)return e.json({success:!1,message:"密碼至少需 8 字元"},400);if(!t.endsWith("@mail.fju.edu.tw")&&!t.endsWith("@cloud.fju.edu.tw"))return e.json({success:!1,message:"僅限輔仁大學師生使用，請使用學校帳號 (@mail.fju.edu.tw) 註冊"},400);const r=e.env.DB;if(await r.prepare("SELECT USR_ID FROM User WHERE USR_EMAIL = ?").bind(t).first())return e.json({success:!1,message:"此 Email 已被註冊，請直接登入"},409);let o=n||"student";["student","officer","professor","staff"].includes(o)||(o="student"),o==="student"&&t.match(/^s\d{7}@/);const u=["professor","staff"].includes(o)?null:"2027-07-31",p=await r.prepare("INSERT INTO User (USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_PASSWORD_HASH, USR_EXPIRE_DATE) VALUES (?, ?, ?, ?, ?, ?)").bind(t,s,a||null,o,"pbkdf2_"+i,u).run();return e.json({success:!0,message:"帳號建立成功！請使用學校帳號登入系統",userId:p.meta.last_row_id},201)});Fe.post("/forgot-password",async e=>{const{email:t}=await e.req.json();if(!t)return e.json({success:!1,message:"請輸入 Email"},400);const a=await e.env.DB.prepare("SELECT USR_EMAIL, USR_NAME FROM User WHERE USR_EMAIL = ?").bind(t).first();return a?e.json({success:!0,message:`密碼重設連結已寄送至 ${a.USR_EMAIL}，請至信箱查收`}):e.json({success:!1,message:"查無此 Email，請確認後重試或建立新帳號"},404)});Fe.post("/reset-password",async e=>{const{email:t,newPassword:s}=await e.req.json();return!t||!s?e.json({success:!1,message:"請填寫必要欄位"},400):s.length<8?e.json({success:!1,message:"密碼至少需 8 字元"},400):(await e.env.DB.prepare("UPDATE User SET USR_PASSWORD_HASH = ? WHERE USR_EMAIL = ?").bind("pbkdf2_"+s,t).run(),e.json({success:!0,message:"密碼已重設，請使用新密碼登入"}))});const H=new I;H.get("/",async e=>{const t=e.env.DB,s=e.req.query("q")||"";let a="SELECT * FROM Facility WHERE FAC_ACTIVE = 1";const n=[];s&&(a+=" AND (FAC_NAME LIKE ? OR FAC_BUILDING LIKE ?)",n.push(`%${s}%`,`%${s}%`)),a+=" ORDER BY FAC_NAME";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});H.get("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await t.prepare("SELECT * FROM Facility WHERE FAC_ID = ?").bind(s).first();return a?e.json({data:a}):e.json({error:"場地不存在"},404)});H.get("/:id/calendar",async e=>{const t=e.env.DB,s=e.req.param("id"),a=e.req.query("start")||"",n=e.req.query("end")||"";let i="SELECT VB.*, U.USR_NAME, UN.UNIT_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID WHERE VB.VB_FAC_ID = ? AND VB.VB_STATUS IN (0, 1)";const r=[s];a&&(i+=" AND VB.VB_END_DATETIME >= ?",r.push(a)),n&&(i+=" AND VB.VB_START_DATETIME <= ?",r.push(n)),i+=" ORDER BY VB.VB_START_DATETIME";const{results:c}=await t.prepare(i).bind(...r).all();return e.json({data:c})});H.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO Facility (FAC_NAME, FAC_TYPE, FAC_BUILDING, FAC_FLOOR, FAC_CAPACITY, FAC_GIS_LAT, FAC_GIS_LNG, FAC_STATUS, FAC_DESC) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)").bind(s.name,s.type||0,s.building,s.floor||1,s.capacity,s.lat||null,s.lng||null,s.status||0,s.desc||null).run();return e.json({success:!0,id:a.meta.last_row_id},201)});H.put("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE Facility SET FAC_NAME=?, FAC_TYPE=?, FAC_BUILDING=?, FAC_FLOOR=?, FAC_CAPACITY=?, FAC_STATUS=?, FAC_DESC=? WHERE FAC_ID=?").bind(a.name,a.type,a.building,a.floor,a.capacity,a.status,a.desc||null,s).run(),e.json({success:!0})});H.delete("/:id",async e=>{const t=e.env.DB,s=e.req.param("id");return await t.prepare("UPDATE Facility SET FAC_ACTIVE = 0 WHERE FAC_ID = ?").bind(s).run(),e.json({success:!0})});H.get("/:id/maintenance",async e=>{const t=e.env.DB,s=e.req.param("id"),{results:a}=await t.prepare("SELECT FML.*, U.USR_NAME FROM FacilityMaintenanceLog FML LEFT JOIN User U ON FML.FML_ADMIN_ID = U.USR_ID WHERE FML.FML_FAC_ID = ? ORDER BY FML.FML_CREATED_AT DESC").bind(s).all();return e.json({data:a})});H.post("/:id/maintenance",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json(),n=await t.prepare("INSERT INTO FacilityMaintenanceLog (FML_FAC_ID, FML_START_DATE, FML_END_DATE, FML_NOTE, FML_ADMIN_ID) VALUES (?, ?, ?, ?, ?)").bind(s,a.startDate,a.endDate||null,a.note||null,a.adminId||1).run();return await t.prepare("UPDATE Facility SET FAC_STATUS = 1 WHERE FAC_ID = ?").bind(s).run(),e.json({success:!0,id:n.meta.last_row_id},201)});const M=new I;M.get("/",async e=>{const t=e.env.DB,s=e.req.query("q")||"";let a="SELECT E.*, ECT.ECT_NAME as CERT_NAME FROM Equipment E LEFT JOIN EquipmentCertType ECT ON E.EQ_CERT_TYPE_ID = ECT.ECT_ID WHERE E.EQ_ACTIVE = 1";const n=[];s&&(a+=" AND E.EQ_NAME LIKE ?",n.push(`%${s}%`)),a+=" ORDER BY E.EQ_NAME";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});M.get("/cert-types",async e=>{const t=e.env.DB,{results:s}=await t.prepare("SELECT * FROM EquipmentCertType").all();return e.json({data:s})});M.get("/:id",async e=>{const s=await e.env.DB.prepare("SELECT E.*, ECT.ECT_NAME as CERT_NAME FROM Equipment E LEFT JOIN EquipmentCertType ECT ON E.EQ_CERT_TYPE_ID = ECT.ECT_ID WHERE E.EQ_ID = ?").bind(e.req.param("id")).first();return s?e.json({data:s}):e.json({error:"器材不存在"},404)});M.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO Equipment (EQ_NAME, EQ_TOTAL, EQ_AVAILABLE, EQ_MAX_PER_LOAN, EQ_CERT_TYPE_ID, EQ_DESC, EQ_PHYSICAL_CODE) VALUES (?, ?, ?, ?, ?, ?, ?)").bind(s.name,s.total,s.total,s.maxPerLoan||1,s.certTypeId||null,s.desc||null,s.physicalCode||null).run();return e.json({success:!0,id:a.meta.last_row_id},201)});M.put("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE Equipment SET EQ_NAME=?, EQ_TOTAL=?, EQ_AVAILABLE=?, EQ_MAX_PER_LOAN=?, EQ_DESC=? WHERE EQ_ID=?").bind(a.name,a.total,a.available,a.maxPerLoan,a.desc||null,s).run(),e.json({success:!0})});M.delete("/:id",async e=>(await e.env.DB.prepare("UPDATE Equipment SET EQ_ACTIVE = 0 WHERE EQ_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})));M.post("/loans",async e=>{const t=e.env.DB,s=await e.req.json();if(!await t.prepare("SELECT AA_ID FROM ActivityApplication WHERE AA_ID = ? AND AA_STATUS = 1").bind(s.activityId).first())return e.json({success:!1,message:"需先取得已核准的活動申請"},400);const i=(await t.prepare("INSERT INTO EquipmentLoan (EL_AA_ID, EL_UNIT_ID, EL_USR_ID, EL_BORROW_START, EL_RETURN_DUE, EL_USE_LOCATION, EL_PURPOSE, EL_LOAN_TYPE) VALUES (?, ?, ?, ?, ?, ?, ?, ?)").bind(s.activityId,s.unitId,s.userId,s.borrowStart,s.returnDue,s.useLocation,s.purpose,s.loanType||0).run()).meta.last_row_id;if(s.items&&Array.isArray(s.items))for(const r of s.items){const c=await t.prepare("SELECT EQ_AVAILABLE, EQ_MAX_PER_LOAN, EQ_CERT_TYPE_ID FROM Equipment WHERE EQ_ID = ? AND EQ_ACTIVE = 1").bind(r.equipmentId).first();if(c){if(r.quantity>c.EQ_MAX_PER_LOAN)return e.json({success:!1,message:`${r.equipmentId} 超過單次借用上限 ${c.EQ_MAX_PER_LOAN}`},400);if(r.quantity>c.EQ_AVAILABLE)return e.json({success:!1,message:"庫存不足"},400);if(c.EQ_CERT_TYPE_ID&&!await t.prepare("SELECT EC_ID FROM EquipmentCert WHERE EC_USR_ID = ? AND EC_TYPE_ID = ? AND EC_STATUS = 0").bind(s.userId,c.EQ_CERT_TYPE_ID).first())return e.json({success:!1,message:"需先取得對應器材操作證"},400);await t.prepare("INSERT INTO EquipmentLoanDetail (ELD_EL_ID, ELD_EQ_ID, ELD_QUANTITY) VALUES (?, ?, ?)").bind(i,r.equipmentId,r.quantity).run(),await t.prepare("UPDATE Equipment SET EQ_AVAILABLE = EQ_AVAILABLE - ? WHERE EQ_ID = ?").bind(r.quantity,r.equipmentId).run()}}return e.json({success:!0,loanId:i,message:"器材借用申請已送出"},201)});M.get("/loans/list",async e=>{const t=e.env.DB,s=e.req.query("userId");let a="SELECT EL.*, U.USR_NAME, UN.UNIT_NAME FROM EquipmentLoan EL LEFT JOIN User U ON EL.EL_USR_ID = U.USR_ID LEFT JOIN Unit UN ON EL.EL_UNIT_ID = UN.UNIT_ID";const n=[];s&&(a+=" WHERE EL.EL_USR_ID = ?",n.push(s)),a+=" ORDER BY EL.EL_CREATED_AT DESC";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});M.get("/loans/:id/details",async e=>{const t=e.env.DB,s=e.req.param("id"),{results:a}=await t.prepare("SELECT ELD.*, EQ.EQ_NAME FROM EquipmentLoanDetail ELD LEFT JOIN Equipment EQ ON ELD.ELD_EQ_ID = EQ.EQ_ID WHERE ELD.ELD_EL_ID = ?").bind(s).all();return e.json({data:a})});const W=new I;W.get("/",async e=>{const t=e.env.DB,s=e.req.query("status"),a=e.req.query("unitId");let n="SELECT AA.*, U.USR_NAME, UN.UNIT_NAME FROM ActivityApplication AA LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID WHERE 1=1";const i=[];s!==void 0&&(n+=" AND AA.AA_STATUS = ?",i.push(Number(s))),a&&(n+=" AND AA.AA_UNIT_ID = ?",i.push(a)),n+=" ORDER BY AA.AA_CREATED_AT DESC";const{results:r}=await t.prepare(n).bind(...i).all();return e.json({data:r})});W.get("/:id",async e=>{const s=await e.env.DB.prepare("SELECT AA.*, U.USR_NAME, UN.UNIT_NAME FROM ActivityApplication AA LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID WHERE AA.AA_ID = ?").bind(e.req.param("id")).first();return s?e.json({data:s}):e.json({error:"活動申請不存在"},404)});W.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("SELECT COUNT(*) as cnt FROM ActivityApplication").first(),n="AA"+new Date().getFullYear()+String(a.cnt+1).padStart(6,"0"),i=await t.prepare("INSERT INTO ActivityApplication (AA_SERIAL_NO, AA_USR_ID, AA_UNIT_ID, AA_ACTIVITY_NAME, AA_START_DATETIME, AA_END_DATETIME, AA_HEADCOUNT, AA_DESCRIPTION, AA_CONTACT_NAME, AA_CONTACT_PHONE, AA_CONTACT_EMAIL, AA_THEMES, AA_HAS_ALCOHOL, AA_HAS_FIRE, AA_HAS_BOOTH) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)").bind(n,s.userId,s.unitId,s.activityName,s.startDatetime,s.endDatetime,s.headcount,s.description||null,s.contactName||null,s.contactPhone||null,s.contactEmail||null,s.themes||null,s.hasAlcohol||0,s.hasFire||0,s.hasBooth||0).run();return e.json({success:!0,id:i.meta.last_row_id,serialNo:n,message:"活動申請已送出"},201)});W.put("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE ActivityApplication SET AA_ACTIVITY_NAME=?, AA_START_DATETIME=?, AA_END_DATETIME=?, AA_HEADCOUNT=?, AA_DESCRIPTION=? WHERE AA_ID=?").bind(a.activityName,a.startDatetime,a.endDatetime,a.headcount,a.description||null,s).run(),e.json({success:!0})});W.patch("/:id/approve",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE ActivityApplication SET AA_STATUS = 1, AA_ADMIN_ID = ?, AA_ADMIN_NOTE = ?, AA_REVIEWED_AT = datetime('now') WHERE AA_ID = ?").bind(a.adminId||1,a.note||null,s).run(),e.json({success:!0,message:"活動已核准"})});W.patch("/:id/reject",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE ActivityApplication SET AA_STATUS = 2, AA_ADMIN_ID = ?, AA_ADMIN_NOTE = ?, AA_REVIEWED_AT = datetime('now') WHERE AA_ID = ?").bind(a.adminId||1,a.note||"未符合規定",s).run(),e.json({success:!0,message:"活動已駁回"})});W.patch("/:id/cancel",async e=>{const t=e.env.DB,s=e.req.param("id");return await t.prepare("UPDATE ActivityApplication SET AA_STATUS = 5 WHERE AA_ID = ?").bind(s).run(),e.json({success:!0,message:"活動已取消"})});W.delete("/:id",async e=>(await e.env.DB.prepare("DELETE FROM ActivityApplication WHERE AA_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})));const J=new I;J.get("/",async e=>{const t=e.env.DB,s=e.req.query("status"),a=e.req.query("userId"),n=e.req.query("facId");let i="SELECT VB.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE 1=1";const r=[];s!==void 0&&(i+=" AND VB.VB_STATUS = ?",r.push(Number(s))),a&&(i+=" AND VB.VB_USR_ID = ?",r.push(a)),n&&(i+=" AND VB.VB_FAC_ID = ?",r.push(n)),i+=" ORDER BY VB.VB_CREATED_AT DESC";const{results:c}=await t.prepare(i).bind(...r).all();return e.json({data:c})});J.get("/pending",async e=>{const t=e.env.DB,{results:s}=await t.prepare("SELECT VB.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE VB.VB_STATUS = 0 ORDER BY VB.VB_CREATED_AT ASC").all();return e.json({data:s})});J.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("SELECT VB_ID, VB_START_DATETIME, VB_END_DATETIME FROM VenueBooking WHERE VB_FAC_ID = ? AND VB_STATUS IN (0, 1) AND VB_START_DATETIME < ? AND VB_END_DATETIME > ?").bind(s.facId,s.endDatetime,s.startDatetime).all();if(a.results&&a.results.length>0)return e.json({success:!1,message:"此時段已有預約，建議進入衝突協調",conflictBookings:a.results},409);const n=await t.prepare("INSERT INTO VenueBooking (VB_AA_ID, VB_FAC_ID, VB_UNIT_ID, VB_USR_ID, VB_START_DATETIME, VB_END_DATETIME, VB_PURPOSE, VB_HEADCOUNT, VB_BOOKING_TYPE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)").bind(s.activityId,s.facId,s.unitId,s.userId,s.startDatetime,s.endDatetime,s.purpose,s.headcount,s.bookingType||0).run();return e.json({success:!0,id:n.meta.last_row_id,message:"場地預約已送出，等待審核"},201)});J.patch("/:id/approve",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE VenueBooking SET VB_STATUS = 1, VB_ADMIN_ID = ? WHERE VB_ID = ?").bind(a.adminId||1,s).run(),e.json({success:!0,message:"場地預約已核准"})});J.patch("/:id/reject",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE VenueBooking SET VB_STATUS = 2, VB_ADMIN_ID = ?, VB_REJECT_REASON = ? WHERE VB_ID = ?").bind(a.adminId||1,a.reason||"",s).run(),e.json({success:!0,message:"場地預約已拒絕"})});J.patch("/:id/cancel",async e=>(await e.env.DB.prepare("UPDATE VenueBooking SET VB_STATUS = 3 WHERE VB_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0,message:"場地預約已取消"})));J.patch("/:id/return",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json(),n=a.abnormal?5:4;return await t.prepare("UPDATE VenueBooking SET VB_STATUS = ?, VB_RETURN_AT = datetime('now'), VB_RETURN_NOTE = ? WHERE VB_ID = ?").bind(n,a.note||null,s).run(),e.json({success:!0,message:"場地已歸還"})});J.delete("/:id",async e=>(await e.env.DB.prepare("DELETE FROM VenueBooking WHERE VB_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})));const Ne=new I;Ne.get("/",async e=>{const t=e.env.DB,s=e.req.query("status");let a="SELECT RR.*, U.USR_NAME, F.FAC_NAME, A.USR_NAME as ADMIN_NAME FROM RepairRequest RR LEFT JOIN User U ON RR.RR_USR_ID = U.USR_ID LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID LEFT JOIN User A ON RR.RR_ADMIN_ID = A.USR_ID WHERE 1=1";const n=[];s!==void 0&&(a+=" AND RR.RR_STATUS = ?",n.push(Number(s))),a+=" ORDER BY RR.RR_CREATED_AT DESC";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});Ne.get("/:id",async e=>{const t=e.env.DB,s=await t.prepare("SELECT RR.*, U.USR_NAME, F.FAC_NAME FROM RepairRequest RR LEFT JOIN User U ON RR.RR_USR_ID = U.USR_ID LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID WHERE RR.RR_ID = ?").bind(e.req.param("id")).first();if(!s)return e.json({error:"報修不存在"},404);const{results:a}=await t.prepare("SELECT * FROM RepairRequestPhoto WHERE RRP_RR_ID = ?").bind(e.req.param("id")).all();return e.json({data:s,photos:a})});Ne.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO RepairRequest (RR_FAC_ID, RR_USR_ID, RR_DESCRIPTION) VALUES (?, ?, ?)").bind(s.facId,s.userId,s.description).run();return e.json({success:!0,id:a.meta.last_row_id,message:"報修已提交"},201)});Ne.put("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE RepairRequest SET RR_STATUS=?, RR_ADMIN_ID=?, RR_ADMIN_NOTE=?, RR_RESOLVED_AT=CASE WHEN ?=2 THEN datetime('now') ELSE RR_RESOLVED_AT END WHERE RR_ID=?").bind(a.status,a.adminId||1,a.adminNote||null,a.status,s).run(),e.json({success:!0})});Ne.delete("/:id",async e=>{const t=e.env.DB;return await t.prepare("DELETE FROM RepairRequestPhoto WHERE RRP_RR_ID = ?").bind(e.req.param("id")).run(),await t.prepare("DELETE FROM RepairRequest WHERE RR_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})});const ue=new I;ue.get("/",async e=>{const t=e.env.DB,s=e.req.query("status");let a="SELECT AC.*, U.USR_NAME, A.USR_NAME as ADMIN_NAME FROM AppealCase AC LEFT JOIN User U ON AC.AC_USR_ID = U.USR_ID LEFT JOIN User A ON AC.AC_ADMIN_ID = A.USR_ID WHERE 1=1";const n=[];s!==void 0&&(a+=" AND AC.AC_STATUS = ?",n.push(Number(s))),a+=" ORDER BY AC.AC_CREATED_AT DESC";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});ue.get("/:id",async e=>{const s=await e.env.DB.prepare("SELECT AC.*, U.USR_NAME FROM AppealCase AC LEFT JOIN User U ON AC.AC_USR_ID = U.USR_ID WHERE AC.AC_ID = ?").bind(e.req.param("id")).first();return s?e.json({data:s}):e.json({error:"申訴不存在"},404)});ue.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO AppealCase (AC_USR_ID, AC_TYPE, AC_REF_LOG_ID, AC_REASON, AC_EVIDENCE) VALUES (?, ?, ?, ?, ?)").bind(s.userId,s.type,s.refLogId||null,s.reason,s.evidence||null).run();return e.json({success:!0,id:a.meta.last_row_id,message:"申訴已提交"},201)});ue.patch("/:id/approve",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE AppealCase SET AC_STATUS=1, AC_ADMIN_ID=?, AC_ADMIN_NOTE=?, AC_REVIEWED_AT=datetime('now') WHERE AC_ID=?").bind(a.adminId||1,a.note||null,s).run(),e.json({success:!0,message:"申訴已核准"})});ue.patch("/:id/reject",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE AppealCase SET AC_STATUS=2, AC_ADMIN_ID=?, AC_ADMIN_NOTE=?, AC_REVIEWED_AT=datetime('now') WHERE AC_ID=?").bind(a.adminId||1,a.note||"未符合申訴條件",s).run(),e.json({success:!0,message:"申訴已駁回"})});ue.delete("/:id",async e=>(await e.env.DB.prepare("DELETE FROM AppealCase WHERE AC_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})));const Se=new I;Se.get("/",async e=>{const t=e.env.DB,s=e.req.query("active");let a="SELECT ANN.*, U.USR_NAME as ADMIN_NAME FROM Announcement ANN LEFT JOIN User U ON ANN.ANN_ADMIN_ID = U.USR_ID";s==="1"&&(a+=" WHERE ANN.ANN_START_DATE <= date('now') AND ANN.ANN_END_DATE >= date('now')"),a+=" ORDER BY ANN.ANN_CREATED_AT DESC";const{results:n}=await t.prepare(a).all();return e.json({data:n})});Se.get("/:id",async e=>{const s=await e.env.DB.prepare("SELECT ANN.*, U.USR_NAME as ADMIN_NAME FROM Announcement ANN LEFT JOIN User U ON ANN.ANN_ADMIN_ID = U.USR_ID WHERE ANN.ANN_ID = ?").bind(e.req.param("id")).first();return s?e.json({data:s}):e.json({error:"公告不存在"},404)});Se.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO Announcement (ANN_TITLE, ANN_CONTENT, ANN_START_DATE, ANN_END_DATE, ANN_ADMIN_ID) VALUES (?, ?, ?, ?, ?)").bind(s.title,s.content,s.startDate,s.endDate,s.adminId||1).run();return e.json({success:!0,id:a.meta.last_row_id,message:"公告已發布"},201)});Se.put("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE Announcement SET ANN_TITLE=?, ANN_CONTENT=?, ANN_START_DATE=?, ANN_END_DATE=? WHERE ANN_ID=?").bind(a.title,a.content,a.startDate,a.endDate,s).run(),e.json({success:!0})});Se.delete("/:id",async e=>(await e.env.DB.prepare("DELETE FROM Announcement WHERE ANN_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})));const ze=new I;ze.get("/dashboard",async e=>{const t=e.env.DB,s=e.req.query("period")||new Date().toISOString().slice(0,7),a=await t.prepare("SELECT COUNT(*) as cnt FROM Facility WHERE FAC_ACTIVE=1").first(),n=await t.prepare("SELECT COUNT(*) as cnt FROM Equipment WHERE EQ_ACTIVE=1").first(),i=await t.prepare("SELECT COUNT(*) as cnt FROM User").first(),r=await t.prepare("SELECT COUNT(*) as cnt FROM Unit WHERE UNIT_ACTIVE=1").first(),c=await t.prepare("SELECT COUNT(*) as cnt FROM VenueBooking WHERE VB_STATUS=0").first(),o=await t.prepare("SELECT COUNT(*) as cnt FROM VenueBooking WHERE VB_STATUS=1").first(),d=await t.prepare("SELECT COUNT(*) as cnt FROM ActivityApplication WHERE AA_STATUS=0").first(),u=await t.prepare("SELECT COUNT(*) as cnt FROM EquipmentLoan WHERE EL_STATUS IN (0,1,2,3)").first(),p=await t.prepare("SELECT COUNT(*) as cnt FROM RepairRequest WHERE RR_STATUS IN (0,1)").first(),f=await t.prepare("SELECT COUNT(*) as cnt FROM AppealCase WHERE AC_STATUS=0").first(),{results:g}=await t.prepare("SELECT SS.*, F.FAC_NAME FROM StatsSummary SS LEFT JOIN Facility F ON SS.SS_FAC_ID = F.FAC_ID WHERE SS.SS_PERIOD = ?").bind(s).all();return e.json({period:s,totalFacilities:(a==null?void 0:a.cnt)||0,totalEquipment:(n==null?void 0:n.cnt)||0,totalUsers:(i==null?void 0:i.cnt)||0,totalUnits:(r==null?void 0:r.cnt)||0,pendingBookings:(c==null?void 0:c.cnt)||0,approvedBookings:(o==null?void 0:o.cnt)||0,pendingActivities:(d==null?void 0:d.cnt)||0,activeLoans:(u==null?void 0:u.cnt)||0,openRepairs:(p==null?void 0:p.cnt)||0,pendingAppeals:(f==null?void 0:f.cnt)||0,facilitySummary:g})});ze.get("/facility-usage",async e=>{const t=e.env.DB,{results:s}=await t.prepare(`SELECT F.FAC_NAME, F.FAC_CAPACITY, COUNT(VB.VB_ID) as booking_count,
     COALESCE(SUM(ROUND((julianday(VB.VB_END_DATETIME) - julianday(VB.VB_START_DATETIME)) * 24, 2)), 0) as total_hours
     FROM Facility F LEFT JOIN VenueBooking VB ON F.FAC_ID = VB.VB_FAC_ID AND VB.VB_STATUS IN (1, 4)
     WHERE F.FAC_ACTIVE = 1 GROUP BY F.FAC_ID ORDER BY booking_count DESC`).all();return e.json({data:s})});ze.get("/equipment-usage",async e=>{const t=e.env.DB,{results:s}=await t.prepare(`SELECT E.EQ_NAME, E.EQ_TOTAL, E.EQ_AVAILABLE, COUNT(ELD.ELD_ID) as loan_count,
     COALESCE(SUM(ELD.ELD_QUANTITY), 0) as total_borrowed
     FROM Equipment E LEFT JOIN EquipmentLoanDetail ELD ON E.EQ_ID = ELD.ELD_EQ_ID
     WHERE E.EQ_ACTIVE = 1 GROUP BY E.EQ_ID ORDER BY loan_count DESC`).all();return e.json({data:s})});const te=new I;te.get("/",async e=>{const t=e.env.DB,{results:s}=await t.prepare("SELECT U.*, CU.USR_NAME as CONTACT_NAME, UVP.UVP_POINT FROM Unit U LEFT JOIN User CU ON U.UNIT_CONTACT_USR = CU.USR_ID LEFT JOIN UnitViolationPoint UVP ON U.UNIT_ID = UVP.UVP_UNIT_ID WHERE U.UNIT_ACTIVE = 1 ORDER BY U.UNIT_NAME").all();return e.json({data:s})});te.get("/:id",async e=>{const t=e.env.DB,s=await t.prepare("SELECT U.*, CU.USR_NAME as CONTACT_NAME FROM Unit U LEFT JOIN User CU ON U.UNIT_CONTACT_USR = CU.USR_ID WHERE U.UNIT_ID = ?").bind(e.req.param("id")).first();if(!s)return e.json({error:"單位不存在"},404);const{results:a}=await t.prepare("SELECT UM.*, USR.USR_NAME, USR.USR_EMAIL FROM UnitMember UM LEFT JOIN User USR ON UM.UM_USR_ID = USR.USR_ID WHERE UM.UM_UNIT_ID = ? AND UM.UM_ACTIVE = 1").bind(e.req.param("id")).all();return e.json({data:s,members:a})});te.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO Unit (UNIT_NAME, UNIT_TYPE, UNIT_CONTACT_USR) VALUES (?, ?, ?)").bind(s.name,s.type,s.contactUserId).run();return await t.prepare("INSERT INTO UnitViolationPoint (UVP_UNIT_ID, UVP_POINT) VALUES (?, 0)").bind(a.meta.last_row_id).run(),e.json({success:!0,id:a.meta.last_row_id},201)});te.put("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE Unit SET UNIT_NAME=?, UNIT_TYPE=?, UNIT_CONTACT_USR=? WHERE UNIT_ID=?").bind(a.name,a.type,a.contactUserId,s).run(),e.json({success:!0})});te.delete("/:id",async e=>(await e.env.DB.prepare("UPDATE Unit SET UNIT_ACTIVE = 0 WHERE UNIT_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})));te.post("/:id/members",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("INSERT OR IGNORE INTO UnitMember (UM_UNIT_ID, UM_USR_ID) VALUES (?, ?)").bind(s,a.userId).run(),e.json({success:!0},201)});te.delete("/:id/members/:userId",async e=>(await e.env.DB.prepare("UPDATE UnitMember SET UM_ACTIVE = 0 WHERE UM_UNIT_ID = ? AND UM_USR_ID = ?").bind(e.req.param("id"),e.req.param("userId")).run(),e.json({success:!0})));const Ke=new I;Ke.get("/",async e=>{const t=e.env.DB,s=e.req.query("userId"),a=e.req.query("unitId");let n="SELECT VPL.*, U.USR_NAME, UN.UNIT_NAME, A.USR_NAME as ADMIN_NAME FROM ViolationPointLog VPL LEFT JOIN User U ON VPL.VPL_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VPL.VPL_UNIT_ID = UN.UNIT_ID LEFT JOIN User A ON VPL.VPL_ADMIN_ID = A.USR_ID WHERE 1=1";const i=[];s&&(n+=" AND VPL.VPL_USR_ID = ?",i.push(s)),a&&(n+=" AND VPL.VPL_UNIT_ID = ?",i.push(a)),n+=" ORDER BY VPL.VPL_CREATED_AT DESC";const{results:r}=await t.prepare(n).bind(...i).all();return e.json({data:r})});Ke.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO ViolationPointLog (VPL_TARGET_TYPE, VPL_USR_ID, VPL_UNIT_ID, VPL_DELTA, VPL_REASON, VPL_SOURCE, VPL_ADMIN_ID, VPL_REF_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)").bind(s.targetType,s.userId||null,s.unitId||null,s.delta,s.reason,s.source||0,s.adminId||null,s.refId||null).run();if(s.targetType===1&&s.unitId){await t.prepare("UPDATE UnitViolationPoint SET UVP_POINT = UVP_POINT + ? WHERE UVP_UNIT_ID = ?").bind(s.delta,s.unitId).run();const n=await t.prepare("SELECT UVP_POINT FROM UnitViolationPoint WHERE UVP_UNIT_ID = ?").bind(s.unitId).first();n&&n.UVP_POINT>=10&&await t.prepare("UPDATE UnitViolationPoint SET UVP_SUSPENDED = 1 WHERE UVP_UNIT_ID = ?").bind(s.unitId).run()}return e.json({success:!0,id:a.meta.last_row_id},201)});Ke.get("/unit-points",async e=>{const t=e.env.DB,{results:s}=await t.prepare("SELECT UVP.*, U.UNIT_NAME FROM UnitViolationPoint UVP LEFT JOIN Unit U ON UVP.UVP_UNIT_ID = U.UNIT_ID ORDER BY UVP.UVP_POINT DESC").all();return e.json({data:s})});const se=new I;se.get("/",async e=>{const t=e.env.DB,s=e.req.query("status");let a=`SELECT CN.*, 
    VBA.VB_START_DATETIME as A_START, VBA.VB_END_DATETIME as A_END, VBA.VB_PURPOSE as A_PURPOSE,
    VBB.VB_START_DATETIME as B_START, VBB.VB_END_DATETIME as B_END, VBB.VB_PURPOSE as B_PURPOSE,
    F.FAC_NAME, UA.USR_NAME as A_USER, UB.USR_NAME as B_USER, UNA.UNIT_NAME as A_UNIT, UNB.UNIT_NAME as B_UNIT
    FROM ConflictNegotiation CN
    LEFT JOIN VenueBooking VBA ON CN.CN_VB_ID_A = VBA.VB_ID LEFT JOIN VenueBooking VBB ON CN.CN_VB_ID_B = VBB.VB_ID
    LEFT JOIN Facility F ON VBA.VB_FAC_ID = F.FAC_ID
    LEFT JOIN User UA ON VBA.VB_USR_ID = UA.USR_ID LEFT JOIN User UB ON VBB.VB_USR_ID = UB.USR_ID
    LEFT JOIN Unit UNA ON VBA.VB_UNIT_ID = UNA.UNIT_ID LEFT JOIN Unit UNB ON VBB.VB_UNIT_ID = UNB.UNIT_ID
    WHERE 1=1`;const n=[];s!==void 0&&(a+=" AND CN.CN_STATUS = ?",n.push(Number(s))),a+=" ORDER BY CN.CN_CREATED_AT DESC";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});se.get("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await t.prepare(`SELECT CN.*, 
    VBA.VB_START_DATETIME as A_START, VBA.VB_END_DATETIME as A_END, VBA.VB_PURPOSE as A_PURPOSE, VBA.VB_FAC_ID,
    VBB.VB_START_DATETIME as B_START, VBB.VB_END_DATETIME as B_END, VBB.VB_PURPOSE as B_PURPOSE,
    F.FAC_NAME, UA.USR_NAME as A_USER, UB.USR_NAME as B_USER, UNA.UNIT_NAME as A_UNIT, UNB.UNIT_NAME as B_UNIT
    FROM ConflictNegotiation CN
    LEFT JOIN VenueBooking VBA ON CN.CN_VB_ID_A = VBA.VB_ID LEFT JOIN VenueBooking VBB ON CN.CN_VB_ID_B = VBB.VB_ID
    LEFT JOIN Facility F ON VBA.VB_FAC_ID = F.FAC_ID
    LEFT JOIN User UA ON VBA.VB_USR_ID = UA.USR_ID LEFT JOIN User UB ON VBB.VB_USR_ID = UB.USR_ID
    LEFT JOIN Unit UNA ON VBA.VB_UNIT_ID = UNA.UNIT_ID LEFT JOIN Unit UNB ON VBB.VB_UNIT_ID = UNB.UNIT_ID
    WHERE CN.CN_ID = ?`).bind(s).first();if(!a)return e.json({error:"衝突不存在"},404);const{results:n}=await t.prepare("SELECT * FROM CoordinationMessage WHERE CM_CN_ID = ? ORDER BY CM_SENT_AT ASC").bind(s).all();return e.json({data:a,messages:n})});se.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO ConflictNegotiation (CN_VB_ID_A, CN_VB_ID_B, CN_OPENED_AT) VALUES (?, ?, datetime('now'))").bind(s.bookingIdA,s.bookingIdB).run();return e.json({success:!0,id:a.meta.last_row_id},201)});se.post("/:id/messages",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();await t.prepare("INSERT INTO CoordinationMessage (CM_CN_ID, CM_SENDER_ROLE, CM_CONTENT) VALUES (?, ?, ?)").bind(s,a.senderRole,a.content).run(),await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 1 WHERE CN_ID = ? AND CN_STATUS = 0").bind(s).run();const{results:n}=await t.prepare("SELECT * FROM CoordinationMessage WHERE CM_CN_ID = ? ORDER BY CM_SENT_AT ASC").bind(s).all();return e.json({success:!0,messages:n})});se.patch("/:id/resolve",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 2, CN_RESOLVED_BY = ?, CN_CLOSED_AT = datetime('now'), CN_DELETE_AT = datetime('now', '+30 days') WHERE CN_ID = ?").bind(a.resolvedBy||1,s).run(),e.json({success:!0,message:"協調已解決"})});se.patch("/:id/fail",async e=>{const t=e.env.DB,s=e.req.param("id");return await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 3, CN_CLOSED_AT = datetime('now') WHERE CN_ID = ?").bind(s).run(),e.json({success:!0,message:"協調失敗"})});se.patch("/:id/timeout",async e=>{const t=e.env.DB,s=e.req.param("id");return await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 4, CN_CLOSED_AT = datetime('now') WHERE CN_ID = ?").bind(s).run(),e.json({success:!0,message:"協調超時關閉"})});const qe=new I,ys="https://models.inference.ai.azure.com/chat/completions",pt={admin:`你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助課指組管理員。
你的知識庫包含以下法規：
- 場地使用管理規則 v3.0：場地借用資格、時段限制、歸還規定
- 器材借用管理辦法 v1.5：器材借用流程、操作證要求、損壞賠償
- 違規記點處理要點 v2.0：違規類型（逾時+2點、損壞+3點、未到場+1點）、停權門檻10點
- 活動申請辦法 v2.1：活動申請流程、審核標準、含酒精/明火須提前30天送件

管理員常見操作：審核預約、管理違規記點、查看統計報表、處理報修、管理使用者帳號。
請用繁體中文回答，並提供具體的操作步驟。`,officer:`你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助社團幹部。
你的知識庫包含以下法規：
- 場地預約流程：提交活動申請 → 取得核准 → 預約場地 → 完成紙本程序
- 器材借用規則：需先有核准活動、部分器材需操作證、領取時段限週一至週五09:30-16:30
- 衝突協調機制：場協大會登記 → 私下協調聊天室 → 一方撤回
- 場地使用注意：借用日前7天須完成申請、使用完畢恢復原狀、逾時記點

社團幹部可用 AI 功能：活動企劃書生成、預約風險評估、法規查詢。
請用繁體中文回答。`,professor:`你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助教授。
你的知識庫包含以下規定：
- 教授可借用教室進行課程或學術活動
- 借用流程與一般使用者相同，需先提交活動申請
- 可透過行政單位名義申請借用
- 教授帳號無有效期限制
請用繁體中文回答。`,student:`你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助學生。
你的知識庫包含以下資訊：
- 學生需透過所屬單位（社團/系學會）借用場地和器材
- 場地預約需先有已核准的活動申請
- 違規記點達10點將停權，可透過勞動服務銷點或申復
- 如需加入社團，請聯繫社團幹部或至課指組登記
請用繁體中文回答。`,staff:`你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助行政職員。
你的知識庫包含以下規定：
- 職員可以行政單位名義提交活動申請與場地預約
- 報修設施可至報修管理頁面提交
- 借用流程與社團相同但審核較快
- 行政職員帳號無有效期限制
請用繁體中文回答。`},Ts={場地預約:`**場地預約流程（依據《場地使用管理規則 v3.0》）：**

1. **提交活動申請**：至「活動申請」填寫活動資料，系統產生含流水編號的 PDF 申請單
2. **紙本審核**：持列印的申請單至課指組完成紙本審核
3. **取得核准**：課指組標記核准後，該單位成員可送出場地預約
4. **預約場地**：至「場地預約」選擇場地與時段，填寫使用事由
5. **等待審核**：課指組管理員審核後通知結果

⚠️ 注意事項：
- 場地借用申請最晚須於借用日前 **7 天** 完成送出
- 含酒精、明火或涉及攤位的活動須於活動 **1 個月前** 送件
- 例行性借用可設定每週重複，系統自動展開為多筆預約`,器材借用:`**器材借用流程（依據《器材借用管理辦法 v1.5》）：**

1. **前提條件**：需先取得已核准的活動申請
2. **檢查器材證**：部分器材（如音響、投影機）需先取得操作證
3. **填寫申請**：可在一筆申請中選取多種器材
4. **領取時段**：週一至週五 09:30-16:30，週三另增 17:00-19:00
5. **申請時限**：最早領取前 30 日，最晚領取前 4 個工作天

⚠️ 注意事項：
- 超過 2 個工作日未領取，器材證將自動註銷
- 逾期歸還或損壞將依規定記點`,衝突協調:`**場地衝突協調機制：**

1. **場協大會**：學期初由課指組舉辦，登記截止前可提交場地時段需求
2. **私下協調**：系統偵測衝突後，可透過站內匿名聊天室協商
3. **協調流程**：發起邀請 → 對方同意 → 進入聊天室 → 一方撤回申請
4. **時限**：聊天室開啟後 24 小時內需完成，否則自動關閉
5. **紀錄保存**：對話內容保存半年，管理員可調閱`,違規:`**違規記點規則（依據《違規記點處理要點 v2.0》）：**

- 逾時使用場地：+2 點
- 場地/器材損壞：+3 點
- 未到場使用：+1 點
- 逾期未歸還器材：+2 點
- **單位累計達 10 點自動停權**

銷點方式：
1. 勞動服務：至「違規記點」頁面申請，完成後由管理員核准
2. 申復：對不合理記點提出正式申訴`,申訴:`**申訴流程（依據 Epic 7）：**

1. 至「申訴」頁面選擇類型（停權申復/違規記點申復/其他檢舉）
2. 填寫申訴理由與佐證資料（必填）
3. 送出後等待課指組審核
4. 不可對同一案件重複申訴

審核結果：
- 核准：撤銷記點或解除停權
- 駁回：維持原判，附駁回說明`,報修:`**報修流程（依據 Epic 6）：**

1. 至「報修管理」頁面
2. 選擇報修設施（必填）
3. 填寫問題描述（至少 10 字）
4. 可上傳現場照片（最多 3 張）
5. 送出後由課指組派工處理
6. 完成後系統自動解除維修中標記`,活動申請:`**活動申請流程（依據《活動申請辦法 v2.1》）：**

1. 至「活動申請」頁面填寫活動資訊
2. 系統自動產生流水編號（AAYYYYNNNNNN格式）
3. 列印含流水編號的 PDF 申請單
4. 持紙本至課指組窗口辦理審核
5. 核准後可進行場地預約和器材借用

⚠️ 特殊規定：
- 含酒精飲料活動須提前 1 個月送件
- 涉及明火設備需附消防安全計畫
- 攤位販售活動須另附企劃書及相關申請`,場協大會:`**場協大會說明：**

1. 每學期初由課指組舉辦
2. 登記截止日前至系統登記場地需求
3. 場協大會現場由課指組協調
4. 未出席視同放棄
5. 核准後系統自動產生整學期的場地預約

逾期未完成紙本流程者視同放棄。`,勞動服務:`**勞動服務銷點流程：**

1. 至「勞動服務銷點」頁面提交申請
2. 選擇服務類型（場地清潔/器材整理/活動支援/行政協助）
3. 填寫服務日期和時數
4. 管理員審核通過後扣除對應點數

服務類型與可扣點數對應：
- 場地清潔 2 小時 = -1 點
- 器材整理 2 小時 = -1 點
- 活動支援 4 小時 = -2 點`,操作證:`**器材操作證說明：**

需要操作證的器材：
- 音響設備（無線麥克風組、桌上型音響）→ 音響設備操作證
- 投影設備（可攜式投影機）→ 投影機操作證

取得方式：
1. 至課指組預約操作訓練
2. 完成訓練並通過考核
3. 管理員核發操作證

注意：超過 2 個工作日未領取借用器材，操作證將自動註銷`};qe.post("/chat",async e=>{var c,o,d;const t=await e.req.json(),s=t.message||"",a=t.role||"student";let n="";for(const[u,p]of Object.entries(Ts))if(s.includes(u)){n=p;break}const i=e.env.GITHUB_TOKEN;if(i)try{const u=await fetch(ys,{method:"POST",headers:{"Content-Type":"application/json",Authorization:`Bearer ${i}`},body:JSON.stringify({model:"gpt-4o",messages:[{role:"system",content:pt[a]||pt.student},...n?[{role:"system",content:`以下是相關法規知識供參考：
${n}`}]:[],{role:"user",content:s}],temperature:.7,max_tokens:1e3})});if(u.ok){const p=await u.json(),f=((d=(o=(c=p.choices)==null?void 0:c[0])==null?void 0:o.message)==null?void 0:d.content)||"抱歉，無法取得回應";return e.json({success:!0,response:f,model:p.model||"gpt-4o",usage:p.usage,source:"GitHub Models API (GPT-4o)"})}}catch{}let r="";if(n)r=n;else{const u={怎麼:"您的問題我來幫您解答！請問您想了解哪個功能？可以直接問我關於場地預約、器材借用、衝突協調、違規記點、申訴、報修等主題。",如何:"很高興為您服務！請直接告訴我您想操作的功能，例如：場地預約、器材借用、活動申請等。",幫助:`我可以協助您以下事項：
1. 場地預約流程與規定
2. 器材借用與操作證
3. 衝突協調機制
4. 違規記點與銷點
5. 申訴流程
6. 報修管理
7. 活動申請流程

請告訴我您需要哪方面的協助！`,你好:`您好！我是輔仁大學課指組智慧預約平台的 AI 助理。有什麼我可以幫您的嗎？

您可以問我關於場地預約、器材借用、活動申請等問題。`,謝謝:"不客氣！如果還有其他問題，隨時歡迎詢問。祝您使用愉快！"};let p=!1;for(const[f,g]of Object.entries(u))if(s.includes(f)){r=g,p=!0;break}p||(r=`感謝您的提問！關於「${s}」，以下是相關說明：

`,r+=`這是一個很好的問題。建議您可以：
`,r+=`1. 查看系統公告了解最新規定
`,r+=`2. 至對應功能頁面操作
`,r+=`3. 聯繫課指組取得進一步協助

`,r+=`💡 提示：您可以試著問我以下問題：
`,r+=`- 場地預約怎麼操作？
`,r+=`- 器材借用需要什麼條件？
`,r+=`- 違規記點規則是什麼？
`,r+=`- 如何申請勞動服務銷點？
`,r+=`- 場協大會是什麼？
`)}return e.json({success:!0,response:r,model:"RAG 知識庫 (本地)",source:n?"法規知識庫匹配":"Demo 模式 — 設定 GITHUB_TOKEN 後可連線 GPT-4o",note:"設定 GITHUB_TOKEN 環境變數後即可連線 GitHub Models API"})});qe.post("/pre-review",async e=>{const t=await e.req.json(),s=t.headcount||0,a=t.hasAlcohol||!1,n=t.hasFire||!1,i=t.hasBooth||!1,r=t.startDate||"",c=[],o=[];let d="safe";if(s>300?(c.push("人數超過 300 人，需申請大型活動許可"),o.push("請至學務處申請大型集會許可"),d="high"):s>80&&(o.push("建議申請較大場地（進修部地下演講廳或中美堂）"),o.push("需提交安全計畫書"),d="warning"),a&&(c.push("活動涉及酒精飲料，依規定須於活動一個月前檢附企劃書"),d="high"),n&&(c.push("活動涉及明火設備，需消防安全計畫"),d="high"),i&&(o.push("涉及擺攤販售之活動，須於活動一個月前檢附企劃書及相關申請資料"),d==="safe"&&(d="warning")),r){const u=new Date(r),p=new Date,f=Math.floor((u.getTime()-p.getTime())/(1e3*60*60*24));f<7&&(c.push(`距離活動僅剩 ${f} 天，場地借用申請需至少 7 天前送出`),d="high"),(a||n||i)&&f<30&&(c.push("含酒精/明火/攤位的活動須於活動 1 個月前送件，目前時間不足"),d="high")}return e.json({allowNextStep:c.length===0,riskLevel:d,violations:c,suggestions:o.length>0?o:["符合規定，可直接送審"],confidence:.95,reviewedRegulations:["活動申請辦法 v2.1","場地使用管理規則 v3.0","安全管理規範 v1.8"]})});qe.post("/generate-report",async e=>{const t=e.env.DB,{results:s}=await t.prepare(`SELECT VB.VB_UNIT_ID, U.UNIT_NAME, COUNT(*) as usage_count 
     FROM VenueBooking VB LEFT JOIN Unit U ON VB.VB_UNIT_ID = U.UNIT_ID 
     WHERE VB.VB_STATUS IN (1, 4) GROUP BY VB.VB_UNIT_ID`).all();let a=0;const n=(s||[]).reduce((b,D)=>b+(D.usage_count||0),0);if(n>1){let b=0;for(const D of s||[]){const Q=D.usage_count||0;b+=Q/n*(Q/n)}a=1-b}const{results:i}=await t.prepare(`SELECT F.FAC_ID, F.FAC_NAME, F.FAC_CAPACITY, F.FAC_TYPE, F.FAC_BUILDING, COUNT(VB.VB_ID) as usage_count,
     COALESCE(SUM(ROUND((julianday(VB.VB_END_DATETIME) - julianday(VB.VB_START_DATETIME)) * 24, 2)), 0) as total_hours
     FROM Facility F LEFT JOIN VenueBooking VB ON F.FAC_ID = VB.VB_FAC_ID AND VB.VB_STATUS IN (1, 4)
     WHERE F.FAC_ACTIVE = 1 GROUP BY F.FAC_ID ORDER BY usage_count DESC`).all(),{results:r}=await t.prepare(`SELECT E.EQ_NAME, E.EQ_TOTAL, E.EQ_AVAILABLE,
     COUNT(ELD.ELD_ID) as loan_count,
     COALESCE(SUM(ELD.ELD_QUANTITY), 0) as total_borrowed
     FROM Equipment E LEFT JOIN EquipmentLoanDetail ELD ON E.EQ_ID = ELD.ELD_EQ_ID
     WHERE E.EQ_ACTIVE = 1 GROUP BY E.EQ_ID ORDER BY loan_count DESC`).all(),{results:c}=await t.prepare(`SELECT UVP.*, U.UNIT_NAME FROM UnitViolationPoint UVP
     LEFT JOIN Unit U ON UVP.UVP_UNIT_ID = U.UNIT_ID
     ORDER BY UVP.UVP_POINT DESC`).all(),o=await t.prepare(`SELECT COUNT(*) as total, SUM(CASE WHEN AA_STATUS=1 THEN 1 ELSE 0 END) as approved,
     SUM(CASE WHEN AA_STATUS=0 THEN 1 ELSE 0 END) as pending,
     SUM(CASE WHEN AA_STATUS=2 THEN 1 ELSE 0 END) as rejected
     FROM ActivityApplication`).first(),d=await t.prepare(`SELECT COUNT(*) as total, SUM(CASE WHEN RR_STATUS=0 THEN 1 ELSE 0 END) as pending,
     SUM(CASE WHEN RR_STATUS=1 THEN 1 ELSE 0 END) as in_progress,
     SUM(CASE WHEN RR_STATUS=2 THEN 1 ELSE 0 END) as completed
     FROM RepairRequest`).first(),u=await t.prepare(`SELECT COUNT(*) as total, SUM(CASE WHEN CN_STATUS=2 THEN 1 ELSE 0 END) as resolved,
     SUM(CASE WHEN CN_STATUS IN (3,4) THEN 1 ELSE 0 END) as failed
     FROM ConflictNegotiation`).first(),{results:p}=await t.prepare(`SELECT CAST(substr(VB_START_DATETIME, 12, 2) AS INTEGER) as hour, COUNT(*) as cnt
     FROM VenueBooking WHERE VB_STATUS IN (0,1,4)
     GROUP BY hour ORDER BY cnt DESC LIMIT 5`).all(),f=200,g=(i||[]).map(b=>({...b,utilizationRate:Math.round((b.total_hours||0)/f*100*10)/10})),_=[];a<.5?_.push("【多樣性不足】建議鼓勵更多單位使用場地，提升資源多樣性指數"):_.push("【多樣性良好】資源分配均衡 (D="+Math.round(a*1e3)/1e3+")，持續維持現行政策");const w=g.filter(b=>b.utilizationRate<20&&b.usage_count===0);w.length>0&&_.push("【閒置場地】"+w.map(b=>b.FAC_NAME).join("、")+" 利用率偏低，建議開放為自習空間或調整開放時段");const R=g.filter(b=>b.utilizationRate>70);R.length>0&&_.push("【熱門場地】"+R.map(b=>b.FAC_NAME).join("、")+" 使用率高，建議延長開放時間或增設替代場地");const U=(r||[]).filter(b=>b.EQ_AVAILABLE===0);return U.length>0&&_.push("【庫存不足】"+U.map(b=>b.EQ_NAME).join("、")+" 已全數借出，建議增購"),_.push("建議定期舉辦器材操作證訓練課程，降低借用門檻"),((d==null?void 0:d.pending)||0)>2&&_.push("【報修積壓】目前有 "+d.pending+" 件待處理報修，建議優先排程"),p&&p.length>0&&_.push("【尖峰時段】最熱門時段為 "+p.map(b=>b.hour+":00").join("、")+"，建議錯開排程"),e.json({reportTitle:"114學年度第2學期 課指組資源使用評鑑報告",generatedAt:new Date().toISOString(),simpson:{value:Math.round(a*1e3)/1e3,interpretation:a>.7?"資源被各群體均衡使用（多樣性高）":a>.4?"資源分配尚可，部分群體使用較多":"資源被少數群體壟斷，建議調整配置政策",unitBreakdown:s},facilityUsage:g,equipmentUsage:r,violationSummary:c,activitySummary:o||{total:0,approved:0,pending:0,rejected:0},repairSummary:d||{total:0,pending:0,in_progress:0,completed:0},conflictSummary:u||{total:0,resolved:0,failed:0},peakHours:p||[],recommendations:_})});qe.post("/generate-pdf",async e=>{const t=e.env.DB,a=(await e.req.json()).activityId;if(!a)return e.json({success:!1,message:"請提供活動 ID"},400);const n=await t.prepare(`SELECT AA.*, U.USR_NAME, UN.UNIT_NAME, UN.UNIT_TYPE
     FROM ActivityApplication AA
     LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID
     LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID
     WHERE AA.AA_ID = ?`).bind(a).first();if(!n)return e.json({success:!1,message:"活動不存在"},404);const i={0:"待審核",1:"已核准",2:"已拒絕",3:"已取消"};return e.json({success:!0,pdfData:{title:"輔仁大學課外活動指導組 活動申請書",serialNo:n.AA_SERIAL_NO,activityName:n.AA_ACTIVITY_NAME,applicantName:n.USR_NAME,unitName:n.UNIT_NAME,startDatetime:n.AA_START_DATETIME,endDatetime:n.AA_END_DATETIME,headcount:n.AA_HEADCOUNT,description:n.AA_DESCRIPTION,contactName:n.AA_CONTACT_NAME,contactPhone:n.AA_CONTACT_PHONE,contactEmail:n.AA_CONTACT_EMAIL,themes:n.AA_THEMES,hasAlcohol:n.AA_HAS_ALCOHOL===1,hasFire:n.AA_HAS_FIRE===1,hasBooth:n.AA_HAS_BOOTH===1,status:i[n.AA_STATUS]||"未知",createdAt:n.AA_CREATED_AT,reviewedAt:n.AA_REVIEWED_AT,adminNote:n.AA_ADMIN_NOTE,generatedAt:new Date().toISOString()}})});const Lt=new I;Lt.get("/",async e=>{const t=e.req.query("role")||"student",s=[{q:"如何預約場地？",a:`1. 先至「活動申請」提交活動登記，取得流水編號
2. 持紙本申請單至課指組完成審核
3. 核准後至「場地預約」選擇場地與時段
4. 系統偵測衝突後轉入待審狀態
5. 課指組管理員審核通過後即完成預約

⚠️ 場地借用申請最晚須於借用日前 7 天送出`,category:"場地預約"},{q:"器材借用需要什麼條件？",a:`1. 需有已核准的活動申請（作為借用前提）
2. 部分器材需先取得操作證（音響設備、投影機等）
3. 單次借用數量不可超過上限
4. 領取時段限週一至週五 09:30-16:30，週三另增 17:00-19:00
5. 最早領取前30日、最晚領取前4個工作天申請
6. 歸還時需由管理員確認狀態`,category:"器材借用"},{q:"場地衝突如何處理？",a:`場地預約衝突有兩種解決管道：

**1. 場協大會（學期初）**
- 在登記截止日前至系統登記場地需求
- 場協大會現場由課指組協調
- 未出席視同放棄

**2. 私下協調（場協截止後）**
- 系統偵測衝突後可申請私下協調
- 進入匿名聊天室協商
- 24小時內一方撤回即解決
- 對話紀錄保存半年`,category:"衝突協調"},{q:"違規記點規則？",a:`**違規類型與記點：**
- 逾時使用場地：+2 點
- 場地/器材損壞：+3 點
- 未到場使用：+1 點
- 逾期未歸還器材：+2 點

**停權門檻：** 單位累計達 10 點自動停權

**銷點方式：**
1. 勞動服務銷點
2. 對不合理記點提出申復`,category:"違規記點"},{q:"如何查看公告？",a:"Dashboard 首頁會顯示最新公告，也可至「公告」專區查看所有歷史公告。公告依發布日期由新至舊排序。",category:"公告"},{q:"帳號過期怎麼辦？",a:`學生帳號每學年更新有效期。若已過期：
1. 請聯繫課指組申請續期
2. 管理員可批次更新有效期限

教授與職員帳號預設永久有效（USR_EXPIRE_DATE = NULL）`,category:"帳號管理"},{q:"如何提交報修？",a:`1. 至「報修管理」頁面
2. 選擇報修設施（必填）
3. 填寫問題描述（至少 10 字）
4. 可上傳現場照片（最多 3 張，每張 ≤ 5MB）
5. 送出後課指組處理

報修完成後設施自動恢復可用狀態`,category:"報修"},{q:"如何申訴？",a:`至「申訴」頁面：
1. 選擇類型：停權申復 / 違規記點申復 / 其他檢舉
2. 填寫申訴理由（必填）與佐證資料
3. 等待管理員審核

⚠️ 不可對同一案件重複申訴`,category:"申訴"}],a={admin:[{q:"如何審核場地預約？",a:`至「場地預約」頁面查看待審預約列表，點擊「核准」或「拒絕」按鈕。拒絕時必須填寫原因。核准後借用者收到通知。

例行性預約整筆審核，所有時段同時更新為已核准。`,category:"審核管理"},{q:"如何管理違規記點？",a:`至「違規記點」頁面：
- 新增記點：搜尋使用者 → 輸入分數與事由
- 撤銷記點：選取紀錄 → 填寫原因
- 單位累計 10 點自動停權

所有異動記錄不可刪改，確保可追溯性。`,category:"違規管理"},{q:"統計報表如何使用？",a:`至「統計」頁面查看：
- 場地使用率（本月/本學期）
- 器材借用統計
- Simpson 多樣性指數
- AI 學期總結評鑑報告

可將儀表板數據匯出為 CSV 或 PDF 格式。`,category:"統計分析"},{q:"如何標記設施維修？",a:`至「設施管理」頁面：
1. 選取設施 → 改為「維修中」
2. 系統自動取消維修期間所有未來預約
3. 受影響借用者收到通知
4. 維修完成後恢復為「可用」`,category:"設施管理"},{q:"如何管理單位成員？",a:`至「單位管理」頁面：
- 手動新增/移除成員
- 批次匯入 CSV 名單
- 新增單位（社團/學生自治組織/行政單位）

借用者只能以有成員資格的單位名義借用。`,category:"單位管理"}],officer:[{q:"如何提交活動申請？",a:`至「活動申請」頁面：
1. 填寫活動名稱、主辦單位、日期時段、預計人數等
2. 勾選主題分類與特殊事項（酒精/明火/攤位）
3. 系統產生含流水編號的 PDF 申請單
4. 列印後持紙本至課指組審核

核准後您的單位成員即可送出場地/器材借用申請。`,category:"活動申請"},{q:"社團成員如何管理？",a:"社團幹部可至「單位管理」頁面查看成員列表。如需新增或移除成員，請洽課指組管理員處理。",category:"社團管理"},{q:"AI 功能有哪些？",a:`社團幹部可使用以下 AI 功能：
- AI 助理聊天：查詢法規、借用規則
- 活動企劃書生成：輸入基本資訊自動產生
- 活動風險預審：檢查人數、特殊事項是否合規`,category:"AI 功能"},{q:"如何登記場協大會？",a:`在場協登記截止日前：
1. 至「場地預約」→「場協大會登記」
2. 填寫場地、時段、代表單位、活動名稱
3. 可查看已登記清單確認是否衝突

場協大會結束後系統提醒完成紙本流程。逾期未完成者視同放棄。`,category:"場協大會"}],professor:[{q:"教授如何借用教室？",a:`與一般場地預約流程相同：
1. 提交活動申請（可以行政單位或指導社團名義）
2. 取得核准後預約場地
3. 教授身份的申請審核優先處理`,category:"場地預約"},{q:"如何查看指導社團狀態？",a:"至 Dashboard 可查看相關統計。如需指導社團的詳細狀況，可至「單位管理」查看社團資訊。",category:"社團指導"}],student:[{q:"我可以個人借用場地嗎？",a:`個人無法直接借用場地，需透過所屬單位（社團/系學會）提出申請。

如尚未加入任何單位，請：
1. 聯繫社團幹部申請加入
2. 或至課指組登記加入`,category:"場地預約"},{q:"如何加入社團？",a:"請聯繫社團幹部或至課指組登記加入。加入後即可在系統中看到所屬單位，並以該單位名義參與借用申請。",category:"社團"},{q:"我的違規點數怎麼查？",a:`至「個人中心」頁面可查看：
- 目前違規點數
- 完整異動歷程
- 每筆記錄包含日期、事由、變動點數

對有異議的記點可點選「申復」進入申訴流程。`,category:"違規記點"}],staff:[{q:"職員如何申請行政用途場地？",a:`職員可以行政單位名義提交活動申請與場地預約，流程與社團相同但審核較快。

需先確認已加入對應行政單位的成員名單中。`,category:"場地預約"},{q:"如何報修設施？",a:`至「報修管理」頁面：
1. 選擇設施
2. 描述問題（至少 10 字）
3. 可上傳照片
4. 送出後由課指組處理`,category:"報修"}]};return e.json({common:s,roleSpecific:a[t]||a.student||[],role:t,regulations:[{name:"活動申請辦法 v2.1",summary:"規範活動申請流程、審核標準、含酒精/明火須提前30天送件"},{name:"場地使用管理規則 v3.0",summary:"場地借用資格、時段限制（借用日前7天）、歸還規定"},{name:"器材借用管理辦法 v1.5",summary:"器材借用流程、操作證要求、領取時段、逾期處理"},{name:"違規記點處理要點 v2.0",summary:"違規類型、記點標準（逾時+2/損壞+3/未到場+1）、停權門檻10點"},{name:"安全管理規範 v1.8",summary:"大型活動安全計畫、消防要求、保險規定"}]})});const Y=new I;Y.get("/",async e=>{const t=e.env.DB,s=e.req.query("role");let a="SELECT USR_ID, USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_IS_ADMIN, USR_SUSPENDED, USR_EXPIRE_DATE, USR_AVATAR, USR_CREATED_AT FROM User WHERE 1=1";const n=[];s&&(a+=" AND USR_ROLE = ?",n.push(s)),a+=" ORDER BY USR_ID";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});Y.get("/:id",async e=>{const t=e.env.DB,s=await t.prepare("SELECT USR_ID, USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_IS_ADMIN, USR_SUSPENDED, USR_EXPIRE_DATE, USR_AVATAR, USR_CREATED_AT FROM User WHERE USR_ID = ?").bind(e.req.param("id")).first();if(!s)return e.json({error:"使用者不存在"},404);const{results:a}=await t.prepare("SELECT UM.*, U.UNIT_NAME FROM UnitMember UM LEFT JOIN Unit U ON UM.UM_UNIT_ID = U.UNIT_ID WHERE UM.UM_USR_ID = ? AND UM.UM_ACTIVE = 1").bind(e.req.param("id")).all(),{results:n}=await t.prepare("SELECT EC.*, ECT.ECT_NAME FROM EquipmentCert EC LEFT JOIN EquipmentCertType ECT ON EC.EC_TYPE_ID = ECT.ECT_ID WHERE EC.EC_USR_ID = ? AND EC.EC_STATUS = 0").bind(e.req.param("id")).all();return e.json({data:s,units:a,certs:n})});Y.put("/:id",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE User SET USR_NAME=?, USR_PHONE=?, USR_ROLE=?, USR_AVATAR=? WHERE USR_ID=?").bind(a.name,a.phone||null,a.role,a.avatar||null,s).run(),e.json({success:!0})});Y.patch("/:id/suspend",async e=>{const t=e.env.DB,s=e.req.param("id");return await t.prepare("UPDATE User SET USR_SUSPENDED = 1 WHERE USR_ID = ?").bind(s).run(),e.json({success:!0,message:"帳號已停權"})});Y.patch("/:id/unsuspend",async e=>{const t=e.env.DB,s=e.req.param("id");return await t.prepare("UPDATE User SET USR_SUSPENDED = 0 WHERE USR_ID = ?").bind(s).run(),e.json({success:!0,message:"帳號已解除停權"})});Y.patch("/:id/toggle-admin",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await t.prepare("SELECT USR_IS_ADMIN FROM User WHERE USR_ID = ?").bind(s).first(),n=(a==null?void 0:a.USR_IS_ADMIN)===1?0:1;return await t.prepare("UPDATE User SET USR_IS_ADMIN = ? WHERE USR_ID = ?").bind(n,s).run(),e.json({success:!0,isAdmin:n})});Y.get("/:id/profile",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await t.prepare("SELECT USR_ID, USR_EMAIL, USR_NAME, USR_ROLE, USR_AVATAR, USR_CREATED_AT FROM User WHERE USR_ID = ?").bind(s).first(),{results:n}=await t.prepare("SELECT VB.*, F.FAC_NAME FROM VenueBooking VB LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE VB.VB_USR_ID = ? ORDER BY VB.VB_CREATED_AT DESC LIMIT 10").bind(s).all(),{results:i}=await t.prepare("SELECT EL.* FROM EquipmentLoan EL WHERE EL.EL_USR_ID = ? ORDER BY EL.EL_CREATED_AT DESC LIMIT 10").bind(s).all(),{results:r}=await t.prepare("SELECT * FROM AppealCase WHERE AC_USR_ID = ? ORDER BY AC_CREATED_AT DESC LIMIT 5").bind(s).all(),{results:c}=await t.prepare("SELECT RR.*, F.FAC_NAME FROM RepairRequest RR LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID WHERE RR.RR_USR_ID = ? ORDER BY RR.RR_CREATED_AT DESC LIMIT 5").bind(s).all(),{results:o}=await t.prepare("SELECT * FROM ViolationPointLog WHERE VPL_USR_ID = ? ORDER BY VPL_CREATED_AT DESC LIMIT 10").bind(s).all();return e.json({user:a,bookings:n,loans:i,appeals:r,repairs:c,violations:o})});Y.patch("/:id/avatar",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return a.avatar?(await t.prepare("UPDATE User SET USR_AVATAR = ? WHERE USR_ID = ?").bind(a.avatar,s).run(),e.json({success:!0,avatar:a.avatar,message:"大頭貼已更新"})):e.json({success:!1,message:"請選擇大頭貼"},400)});const ke=new I;ke.get("/",async e=>{const t=e.env.DB,s=e.req.query("userId");let a="SELECT LSA.*, U.USR_NAME FROM LaborServiceApplication LSA LEFT JOIN User U ON LSA.LSA_USR_ID = U.USR_ID WHERE 1=1";const n=[];s&&(a+=" AND LSA.LSA_USR_ID = ?",n.push(s)),a+=" ORDER BY LSA.LSA_CREATED_AT DESC";const{results:i}=await t.prepare(a).bind(...n).all();return e.json({data:i})});ke.post("/",async e=>{const t=e.env.DB,s=await e.req.json(),a=await t.prepare("INSERT INTO LaborServiceApplication (LSA_USR_ID, LSA_SERVICE_TYPE, LSA_SERVICE_DATE, LSA_HOURS, LSA_POINTS_TO_DEDUCT) VALUES (?, ?, ?, ?, ?)").bind(s.userId,s.serviceType,s.serviceDate,s.hours,s.pointsToDeduct).run();return e.json({success:!0,id:a.meta.last_row_id,message:"勞動服務銷點申請已送出"},201)});ke.patch("/:id/approve",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json(),n=await t.prepare("SELECT * FROM LaborServiceApplication WHERE LSA_ID = ?").bind(s).first();return n?(await t.prepare("UPDATE LaborServiceApplication SET LSA_STATUS=1, LSA_ADMIN_ID=?, LSA_ADMIN_NOTE=?, LSA_REVIEWED_AT=datetime('now') WHERE LSA_ID=?").bind(a.adminId||1,a.note||null,s).run(),await t.prepare("INSERT INTO ViolationPointLog (VPL_TARGET_TYPE, VPL_USR_ID, VPL_DELTA, VPL_REASON, VPL_SOURCE, VPL_ADMIN_ID, VPL_REF_ID) VALUES (0, ?, ?, ?, 3, ?, ?)").bind(n.LSA_USR_ID,-n.LSA_POINTS_TO_DEDUCT,"勞動服務銷點",a.adminId||1,s).run(),e.json({success:!0,message:"勞動服務銷點已核准"})):e.json({error:"申請不存在"},404)});ke.patch("/:id/reject",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE LaborServiceApplication SET LSA_STATUS=2, LSA_ADMIN_ID=?, LSA_ADMIN_NOTE=?, LSA_REVIEWED_AT=datetime('now') WHERE LSA_ID=?").bind(a.adminId||1,a.note||"未符合條件",s).run(),e.json({success:!0,message:"勞動服務銷點已駁回"})});const pe=new I;pe.get("/",async e=>{const t=e.env.DB,s=e.req.query("semester"),a=e.req.query("status");let n=`SELECT VC.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME
    FROM VenueCoordination VC
    LEFT JOIN User U ON VC.VC_USR_ID = U.USR_ID
    LEFT JOIN Unit UN ON VC.VC_UNIT_ID = UN.UNIT_ID
    LEFT JOIN Facility F ON VC.VC_FAC_ID = F.FAC_ID WHERE 1=1`;const i=[];s&&(n+=" AND VC.VC_SEMESTER = ?",i.push(s)),a!==void 0&&(n+=" AND VC.VC_STATUS = ?",i.push(Number(a))),n+=" ORDER BY VC.VC_DAY_OF_WEEK, VC.VC_TIME_START";const{results:r}=await t.prepare(n).bind(...i).all();return e.json({data:r})});pe.get("/:id",async e=>{const s=await e.env.DB.prepare(`SELECT VC.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME
    FROM VenueCoordination VC LEFT JOIN User U ON VC.VC_USR_ID = U.USR_ID
    LEFT JOIN Unit UN ON VC.VC_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VC.VC_FAC_ID = F.FAC_ID
    WHERE VC.VC_ID = ?`).bind(e.req.param("id")).first();return s?e.json({data:s}):e.json({error:"登記不存在"},404)});pe.post("/",async e=>{const t=e.env.DB,s=await e.req.json();if(!s.unitId||!s.facId||s.dayOfWeek===void 0||!s.timeStart||!s.timeEnd||!s.semester)return e.json({success:!1,message:"請填寫必要欄位"},400);if(await t.prepare(`SELECT VC_ID FROM VenueCoordination WHERE VC_FAC_ID = ? AND VC_DAY_OF_WEEK = ? AND VC_SEMESTER = ? AND VC_STATUS IN (0, 1)
     AND VC_TIME_START < ? AND VC_TIME_END > ?`).bind(s.facId,s.dayOfWeek,s.semester,s.timeEnd,s.timeStart).first())return e.json({success:!1,message:"此時段已有其他單位登記"},409);const n=await t.prepare("INSERT INTO VenueCoordination (VC_USR_ID, VC_UNIT_ID, VC_FAC_ID, VC_DAY_OF_WEEK, VC_TIME_START, VC_TIME_END, VC_PURPOSE, VC_SEMESTER, VC_STATUS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)").bind(s.userId,s.unitId,s.facId,s.dayOfWeek,s.timeStart,s.timeEnd,s.purpose||null,s.semester).run();return e.json({success:!0,id:n.meta.last_row_id,message:"場協大會登記已送出"},201)});pe.patch("/:id/approve",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE VenueCoordination SET VC_STATUS = 1, VC_ADMIN_NOTE = ? WHERE VC_ID = ?").bind(a.note||null,s).run(),e.json({success:!0,message:"登記已核准"})});pe.patch("/:id/reject",async e=>{const t=e.env.DB,s=e.req.param("id"),a=await e.req.json();return await t.prepare("UPDATE VenueCoordination SET VC_STATUS = 2, VC_ADMIN_NOTE = ? WHERE VC_ID = ?").bind(a.note||"未符合條件",s).run(),e.json({success:!0,message:"登記已駁回"})});pe.delete("/:id",async e=>(await e.env.DB.prepare("DELETE FROM VenueCoordination WHERE VC_ID = ?").bind(e.req.param("id")).run(),e.json({success:!0})));function Is(){return`<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>輔仁大學課指組 - 器材與場地預約平台</title>
<script src="https://cdn.tailwindcss.com"><\/script>
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        fju: { blue: '#003366', 'blue-light': '#004488', yellow: '#FFD700', green: '#28a745', red: '#dc3545', bg: '#f8f9fa' }
      },
      borderRadius: { 'fju': '8px', 'fju-lg': '12px' }
    }
  }
}
<\/script>
<style>
.btn-primary { @apply bg-fju-blue text-white px-4 py-2 rounded-fju hover:bg-fju-blue-light transition-all font-medium; }
.btn-yellow { @apply bg-fju-yellow text-fju-blue px-4 py-2 rounded-fju hover:brightness-110 transition-all font-bold; }
.btn-danger { @apply bg-fju-red text-white px-4 py-2 rounded-fju hover:brightness-110 transition-all font-medium; }
.btn-success { @apply bg-fju-green text-white px-4 py-2 rounded-fju hover:brightness-110 transition-all font-medium; }
.card { @apply bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100; }
.stat-card { @apply bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center transition-transform hover:scale-105; }
.sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-fju text-sm transition-all cursor-pointer; }
.sidebar-link:hover { @apply bg-fju-blue/10 text-fju-blue; }
.sidebar-link.active { @apply bg-fju-blue text-white; }
.modal-overlay { @apply fixed inset-0 bg-black/50 z-50 flex items-center justify-center; }
.modal-content { @apply bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto; }
.table-header { @apply bg-gray-50 text-left text-xs text-gray-500 uppercase; }
.table-cell { @apply p-3 text-sm; }
.status-pending { @apply px-2 py-1 rounded-fju bg-yellow-100 text-yellow-700 text-xs font-medium; }
.status-approved { @apply px-2 py-1 rounded-fju bg-green-100 text-green-700 text-xs font-medium; }
.status-rejected { @apply px-2 py-1 rounded-fju bg-red-100 text-red-700 text-xs font-medium; }
.status-info { @apply px-2 py-1 rounded-fju bg-blue-100 text-blue-700 text-xs font-medium; }
.fade-in { animation: fadeIn 0.3s ease-in; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.input-field { @apply w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm focus:ring-2 focus:ring-fju-blue/20 focus:border-fju-blue outline-none; }
.toast { position:fixed;top:20px;right:20px;z-index:99;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;animation:slideIn .3s ease; }
@keyframes slideIn { from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1} }
</style>
</head>
<body class="bg-fju-bg min-h-screen">

<!-- ========== Avatar Selection Modal (Prompt7 9E) ========== -->
<div id="avatar-modal" class="hidden fixed inset-0 bg-black/60 z-[60] flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4 shadow-2xl">
    <h3 class="font-bold text-fju-blue text-lg mb-2 text-center"><i class="fas fa-user-circle mr-2 text-fju-yellow"></i>選擇你的大頭貼</h3>
    <p class="text-xs text-gray-400 text-center mb-4">進入系統前請先選擇一個大頭貼</p>
    <div class="grid grid-cols-4 gap-3" id="avatar-grid"></div>
    <button onclick="confirmAvatar()" id="avatar-confirm-btn" disabled class="w-full btn-yellow py-3 mt-4 disabled:opacity-50 disabled:cursor-not-allowed">確認選擇</button>
  </div>
</div>

<!-- ========== Login Page (Prompt7 9B: tab切換) ========== -->
<div id="login-page" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-fju-blue via-fju-blue-light to-fju-blue p-4">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="w-20 h-20 bg-fju-yellow rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg">
        <i class="fas fa-university text-fju-blue text-3xl"></i>
      </div>
      <h1 class="text-2xl font-bold text-white">輔仁大學</h1>
      <p class="text-fju-yellow text-sm mt-1">課指組器材與場地預約平台 v3.2</p>
    </div>
    <div class="flex bg-white/10 rounded-fju p-1 mb-4">
      <button onclick="switchAuthTab('login')" id="tab-login" class="flex-1 py-2 text-sm rounded-fju bg-white text-fju-blue font-bold transition-all">登入</button>
      <button onclick="switchAuthTab('register')" id="tab-register" class="flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all">建立帳號</button>
      <button onclick="switchAuthTab('forgot')" id="tab-forgot" class="flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all">忘記密碼</button>
    </div>
    <!-- Login Form -->
    <div id="form-login" class="card fade-in">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-sign-in-alt mr-2"></i>帳號登入</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">學校 Email</label><input id="login-email" type="email" placeholder="example@mail.fju.edu.tw" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">密碼</label><input id="login-password" type="password" placeholder="請輸入密碼" class="input-field" onkeypress="if(event.key==='Enter')doLogin()"></div>
        <button onclick="doLogin()" class="w-full btn-yellow py-3"><i class="fas fa-sign-in-alt mr-2"></i>登入</button>
        <div id="login-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
        <button onclick="switchAuthTab('forgot')" class="flex-1 text-xs px-3 py-2 rounded-fju bg-gray-100 text-gray-600 hover:bg-gray-200"><i class="fas fa-key mr-1"></i>忘記密碼</button>
        <button onclick="switchAuthTab('register')" class="flex-1 text-xs px-3 py-2 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20"><i class="fas fa-user-plus mr-1"></i>建立帳號</button>
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="text-xs text-gray-400 mb-2">快速 Demo 登入：</p>
        <div class="grid grid-cols-3 gap-2">
          <button onclick="quickLogin('admin01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20">管理員</button>
          <button onclick="quickLogin('s1100001@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-yellow/20 text-fju-blue hover:bg-fju-yellow/30">幹部A(王)</button>
          <button onclick="quickLogin('s1100002@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-orange-100 text-orange-700 hover:bg-orange-200">幹部B(李)</button>
          <button onclick="quickLogin('s1100003@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-green/10 text-fju-green hover:bg-fju-green/20">學生</button>
          <button onclick="quickLogin('prof01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-purple-100 text-purple-700 hover:bg-purple-200">教授</button>
          <button onclick="quickLogin('staff01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-teal-100 text-teal-700 hover:bg-teal-200">職員</button>
        </div>
        <p class="text-xs text-gray-400 mt-2">* 借用者A(王小明)與借用者B(李小花)模擬預約衝突場景</p>
      </div>
    </div>
    <!-- Register Form (Prompt7 9C: 註冊時選身分) -->
    <div id="form-register" class="card fade-in hidden">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-user-plus mr-2"></i>建立帳號</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">姓名 *</label><input id="reg-name" type="text" placeholder="王小明" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">學校 Email *</label><input id="reg-email" type="email" placeholder="s1234567@mail.fju.edu.tw" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">手機號碼</label><input id="reg-phone" type="tel" placeholder="0912345678" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">身份角色 *</label>
          <select id="reg-role" class="input-field">
            <option value="student">學生</option>
            <option value="officer">社團幹部</option>
            <option value="professor">教授</option>
            <option value="staff">行政職員</option>
          </select>
          <p class="text-xs text-gray-400 mt-1">* 社團幹部身分需經課指組確認後生效</p>
        </div>
        <div><label class="text-xs text-gray-500 block mb-1">密碼 * (至少8字元)</label><input id="reg-password" type="password" placeholder="請設定密碼" class="input-field"></div>
        <button onclick="doRegister()" class="w-full btn-primary py-3"><i class="fas fa-user-plus mr-2"></i>建立帳號</button>
        <div id="reg-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-3 text-center"><button onclick="switchAuthTab('login')" class="text-xs text-fju-blue hover:underline">已有帳號？返回登入</button></div>
    </div>
    <!-- Forgot Password Form -->
    <div id="form-forgot" class="card fade-in hidden">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-key mr-2"></i>忘記密碼</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">學校 Email</label><input id="forgot-email" type="email" placeholder="example@mail.fju.edu.tw" class="input-field"></div>
        <button onclick="doForgotPassword()" class="w-full btn-primary py-3"><i class="fas fa-envelope mr-2"></i>寄送重設連結</button>
        <div id="forgot-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-3 text-center"><button onclick="switchAuthTab('login')" class="text-xs text-fju-blue hover:underline">返回登入</button></div>
    </div>
  </div>
</div>

<!-- ========== Main App ========== -->
<div id="app-page" class="hidden min-h-screen flex">
  <aside id="sidebar" class="w-64 bg-white border-r border-gray-100 min-h-screen flex flex-col shadow-sm fixed left-0 top-0 bottom-0 z-40 transition-transform">
    <div class="p-4 border-b border-gray-100">
      <div class="flex items-center gap-3 cursor-pointer" onclick="navigateTo('profile')">
        <div id="user-avatar" class="w-10 h-10 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue font-bold shadow text-lg overflow-hidden"></div>
        <div>
          <div id="user-name-display" class="text-sm font-bold text-fju-blue"></div>
          <div id="user-role-display" class="text-xs text-gray-400"></div>
        </div>
      </div>
    </div>
    <nav class="flex-1 p-3 space-y-1 overflow-y-auto" id="sidebar-nav"></nav>
    <div class="p-3 border-t border-gray-100">
      <button onclick="doLogout()" class="sidebar-link w-full text-red-500 hover:bg-red-50"><i class="fas fa-sign-out-alt w-5"></i><span>登出</span></button>
    </div>
  </aside>
  <main class="flex-1 ml-64">
    <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between sticky top-0 z-30">
      <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-fju-blue"><i class="fas fa-bars text-lg"></i></button>
        <h2 id="page-title" class="text-lg font-bold text-fju-blue"></h2>
      </div>
      <div class="flex items-center gap-3">
        <span id="role-badge" class="px-3 py-1 rounded-fju text-xs font-bold"></span>
      </div>
    </header>
    <div id="page-content" class="p-6 fade-in"></div>
  </main>
</div>

<script>
// ========== State ==========
let currentUser = null;
let currentPage = 'dashboard';
let selectedAvatar = null;
const API = '/api';
const roleNames = { admin:'課指組管理員', officer:'社團幹部', professor:'教授', student:'學生', staff:'行政職員' };
const badgeColors = { admin:'bg-fju-blue text-white', officer:'bg-fju-yellow text-fju-blue', professor:'bg-purple-100 text-purple-700', student:'bg-fju-green/10 text-fju-green', staff:'bg-teal-100 text-teal-700' };
const avatarEmojis = ['😊','😎','🤓','🧑‍💻','👩‍🎓','👨‍🏫','🦊','🐱','🐶','🦁','🐼','🐨','🦉','🐬','🌟','🎯'];

// ========== Toast ==========
function toast(msg, type) {
  const bg = type==='error'?'#dc3545':type==='success'?'#28a745':'#003366';
  const el = document.createElement('div');
  el.className='toast'; el.style.background=bg; el.textContent=msg;
  document.body.appendChild(el);
  setTimeout(()=>el.remove(),3000);
}

// ========== Auth ==========
function switchAuthTab(tab) {
  ['login','register','forgot'].forEach(t => {
    document.getElementById('form-'+t).classList.toggle('hidden', t !== tab);
    const btn = document.getElementById('tab-'+t);
    if(btn) btn.className = t === tab
      ? 'flex-1 py-2 text-sm rounded-fju bg-white text-fju-blue font-bold transition-all'
      : 'flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all';
  });
}

async function doLogin() {
  const email = document.getElementById('login-email').value;
  const password = document.getElementById('login-password').value;
  if (!email) return showMsg('login-msg', '請輸入 Email', 'error');
  try {
    const res = await fetch(API+'/auth/login', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({email, password}) });
    const data = await res.json();
    if (data.success) {
      currentUser = data.user;
      localStorage.setItem('fjuUser', JSON.stringify(data.user));
      localStorage.setItem('fjuToken', data.token);
      if (!currentUser.avatar) { showAvatarSelection(); }
      else { enterApp(); }
    } else { showMsg('login-msg', data.message, 'error'); }
  } catch(e) { showMsg('login-msg', '連線錯誤', 'error'); }
}

function quickLogin(email) { document.getElementById('login-email').value = email; document.getElementById('login-password').value = 'demo123'; doLogin(); }

async function doRegister() {
  const name = document.getElementById('reg-name').value;
  const email = document.getElementById('reg-email').value;
  const phone = document.getElementById('reg-phone').value;
  const role = document.getElementById('reg-role').value;
  const password = document.getElementById('reg-password').value;
  if (!name || !email || !password) return showMsg('reg-msg', '請填寫必要欄位', 'error');
  if (password.length < 8) return showMsg('reg-msg', '密碼至少需 8 字元', 'error');
  try {
    const res = await fetch(API+'/auth/register', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({name, email, phone, role, password}) });
    const data = await res.json();
    showMsg('reg-msg', data.message, data.success ? 'success' : 'error');
    if (data.success) setTimeout(() => switchAuthTab('login'), 1500);
  } catch(e) { showMsg('reg-msg', '連線錯誤', 'error'); }
}

async function doForgotPassword() {
  const email = document.getElementById('forgot-email').value;
  if (!email) return showMsg('forgot-msg', '請輸入 Email', 'error');
  try {
    const res = await fetch(API+'/auth/forgot-password', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({email}) });
    const data = await res.json();
    showMsg('forgot-msg', data.message, data.success ? 'success' : 'error');
  } catch(e) { showMsg('forgot-msg', '連線錯誤', 'error'); }
}

function doLogout() { localStorage.removeItem('fjuUser'); localStorage.removeItem('fjuToken'); currentUser = null; document.getElementById('app-page').classList.add('hidden'); document.getElementById('login-page').classList.remove('hidden'); }
function showMsg(id, msg, type) { const el = document.getElementById(id); if(!el) return; el.classList.remove('hidden'); el.className = 'text-sm p-3 rounded-fju ' + (type === 'error' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'); el.textContent = msg; setTimeout(() => el.classList.add('hidden'), 5000); }

// ========== Avatar Selection (Prompt7 9E) ==========
function showAvatarSelection() {
  selectedAvatar = null;
  const grid = document.getElementById('avatar-grid');
  grid.innerHTML = avatarEmojis.map((e, i) =>
    '<div class="w-16 h-16 flex items-center justify-center text-3xl rounded-fju-lg border-2 border-gray-200 cursor-pointer hover:border-fju-yellow hover:bg-fju-yellow/10 transition-all" data-idx="'+i+'" onclick="selectAvatar(this,\\''+e+'\\')">'+e+'</div>'
  ).join('');
  document.getElementById('avatar-modal').classList.remove('hidden');
  document.getElementById('avatar-confirm-btn').disabled = true;
}
function selectAvatar(el, emoji) {
  selectedAvatar = emoji;
  document.querySelectorAll('#avatar-grid > div').forEach(d => { d.classList.remove('border-fju-yellow','bg-fju-yellow/10','ring-2','ring-fju-yellow'); d.classList.add('border-gray-200'); });
  el.classList.remove('border-gray-200'); el.classList.add('border-fju-yellow','bg-fju-yellow/10','ring-2','ring-fju-yellow');
  document.getElementById('avatar-confirm-btn').disabled = false;
}
async function confirmAvatar() {
  if (!selectedAvatar || !currentUser) return;
  currentUser.avatar = selectedAvatar;
  localStorage.setItem('fjuUser', JSON.stringify(currentUser));
  try { await fetch(API+'/users/'+currentUser.id+'/avatar', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({avatar:selectedAvatar}) }); } catch(e) {}
  document.getElementById('avatar-modal').classList.add('hidden');
  enterApp();
}

// ========== App Entry ==========
function enterApp() {
  document.getElementById('login-page').classList.add('hidden');
  document.getElementById('app-page').classList.remove('hidden');
  const u = currentUser;
  document.getElementById('user-name-display').textContent = u.name;
  document.getElementById('user-role-display').textContent = roleNames[u.role] || u.role;
  const avatarEl = document.getElementById('user-avatar');
  if (u.avatar) { avatarEl.textContent = u.avatar; avatarEl.style.fontSize = '1.5rem'; }
  else { avatarEl.textContent = u.name ? u.name[0] : '?'; }
  const badge = document.getElementById('role-badge');
  badge.className = 'px-3 py-1 rounded-fju text-xs font-bold ' + (badgeColors[u.role] || badgeColors.student);
  badge.textContent = roleNames[u.role] || u.role;
  buildSidebar();
  navigateTo('dashboard');
}

// Prompt7 7A: 移除活動牆; sidebar依角色顯示不同功能
function buildSidebar() {
  const role = currentUser?.role || 'student';
  const isAdmin = currentUser?.isAdmin === 1;
  const items = [
    { id:'dashboard', icon:'fa-tachometer-alt', label:'儀表板', roles:['admin','officer','professor','student','staff'] },
    { id:'activities', icon:'fa-calendar-check', label:'活動申請', roles:['admin','officer','professor','staff'] },
    { id:'venues', icon:'fa-map-marker-alt', label:'場地預約', roles:['admin','officer','professor','student','staff'] },
    { id:'equipment', icon:'fa-tools', label:'器材借用', roles:['admin','officer','professor','staff'] },
    { id:'coordination', icon:'fa-users-rectangle', label:'場協大會', roles:['admin','officer'] },
    { id:'conflicts', icon:'fa-handshake', label:'衝突協調', roles:['admin','officer'] },
    { id:'repairs', icon:'fa-wrench', label:'報修管理', roles:['admin','officer','student','staff'] },
    { id:'appeals', icon:'fa-gavel', label:'申訴管理', roles:['admin','officer','student'] },
    { id:'announcements', icon:'fa-bullhorn', label:'公告資訊', roles:['admin','officer','professor','student','staff'] },
    { id:'faq', icon:'fa-robot', label:'FAQ / AI 助理', roles:['admin','officer','professor','student','staff'] },
    { id:'stats', icon:'fa-chart-bar', label:'統計報表', roles:['admin'] },
    { id:'users', icon:'fa-users-cog', label:'使用者管理', roles:['admin'] },
    { id:'violations', icon:'fa-exclamation-triangle', label:'違規記點', roles:['admin','officer','student'] },
    { id:'labor', icon:'fa-hands-helping', label:'勞動服務銷點', roles:['admin','officer','student'] },
    { id:'units', icon:'fa-building', label:'單位管理', roles:['admin','officer'] },
    { id:'profile', icon:'fa-user-circle', label:'個人中心', roles:['admin','officer','professor','student','staff'] },
  ];
  const nav = document.getElementById('sidebar-nav');
  nav.innerHTML = items.filter(i => i.roles.includes(role) || isAdmin).map(i =>
    '<div class="sidebar-link" data-page="'+i.id+'" onclick="navigateTo(\\''+i.id+'\\')"><i class="fas '+i.icon+' w-5 text-center"></i><span>'+i.label+'</span></div>'
  ).join('');
}

// ========== Navigation ==========
function navigateTo(page) {
  currentPage = page;
  document.querySelectorAll('.sidebar-link').forEach(el => el.classList.toggle('active', el.dataset.page === page));
  const titles = { dashboard:'儀表板', activities:'活動申請管理', venues:'場地預約管理', equipment:'器材借用管理', coordination:'場協大會', conflicts:'衝突協調', repairs:'報修管理', appeals:'申訴管理', announcements:'公告資訊', faq:'FAQ / AI 助理', stats:'統計報表', users:'使用者管理', violations:'違規記點管理', labor:'勞動服務銷點', units:'單位管理', profile:'個人中心' };
  document.getElementById('page-title').textContent = titles[page] || page;
  document.getElementById('page-content').innerHTML = '<div class="text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2 text-sm">載入中...</p></div>';
  const loaders = { dashboard:loadDashboard, activities:loadActivities, venues:loadVenues, equipment:loadEquipment, coordination:loadCoordination, conflicts:loadConflicts, repairs:loadRepairs, appeals:loadAppeals, announcements:loadAnnouncements, faq:loadFaq, stats:loadStats, users:loadUsers, violations:loadViolations, labor:loadLabor, units:loadUnits, profile:loadProfile };
  if (loaders[page]) loaders[page]();
}
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); }

// ========== Utility ==========
function statCard(icon, label, value, color) {
  const colors = { blue:'text-fju-blue', green:'text-fju-green', yellow:'text-fju-yellow', red:'text-fju-red', purple:'text-purple-600', teal:'text-teal-600' };
  return '<div class="stat-card"><div class="text-2xl font-black '+(colors[color]||'text-fju-blue')+'">'+((value!=null)?value:0)+'</div><div class="text-xs text-gray-400 mt-1"><i class="fas '+icon+' mr-1"></i>'+label+'</div></div>';
}
function closeModal() { document.querySelector('.modal-overlay')?.remove(); }
function filterTable(id, q) { document.querySelectorAll('#'+id+' tbody tr').forEach(r => { r.style.display = r.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none'; }); }

// ========== Dashboard ==========
async function loadDashboard() {
  const [dashRes, annRes] = await Promise.all([fetch(API+'/stats/dashboard').then(r=>r.json()).catch(()=>({})), fetch(API+'/announcements?active=1').then(r=>r.json()).catch(()=>({data:[]}))]);
  const d = dashRes; const anns = (annRes.data || []).slice(0,3);
  const role = currentUser?.role || 'student'; const isAdmin = currentUser?.isAdmin === 1;
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="card bg-gradient-to-r from-fju-blue to-fju-blue-light text-white"><div class="flex items-center justify-between"><div><h3 class="text-lg font-bold">歡迎回來，'+(currentUser?.name||'')+'</h3><p class="text-sm text-white/70 mt-1">'+roleNames[role]+' | '+(currentUser?.units?.length ? '所屬單位：'+currentUser.units.map(u=>u.UNIT_NAME).join('、') : '尚未加入任何單位')+'</p></div><div class="text-5xl">'+(currentUser?.avatar||'🏫')+'</div></div></div>';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">';
  h += statCard('fa-building','場地總數',d.totalFacilities,'blue');
  h += statCard('fa-tools','器材項目',d.totalEquipment,'green');
  h += statCard('fa-users','使用者',d.totalUsers,'purple');
  h += statCard('fa-clock','待審預約',d.pendingBookings,'yellow');
  h += statCard('fa-wrench','待修件數',d.openRepairs,'red');
  h += '</div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-bolt mr-2 text-fju-yellow"></i>快速操作</h3><div class="flex flex-wrap gap-2">';
  if (['officer','professor','staff','admin'].includes(role) || isAdmin) {
    h += '<button onclick="navigateTo(\\'activities\\')" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>新增活動申請</button>';
    h += '<button onclick="navigateTo(\\'venues\\')" class="btn-yellow text-xs"><i class="fas fa-calendar-plus mr-1"></i>預約場地</button>';
    h += '<button onclick="navigateTo(\\'equipment\\')" class="btn-primary text-xs"><i class="fas fa-hand-holding mr-1"></i>借用器材</button>';
  }
  h += '<button onclick="navigateTo(\\'repairs\\')" class="text-xs px-3 py-2 rounded-fju bg-fju-red/10 text-fju-red hover:bg-fju-red/20"><i class="fas fa-wrench mr-1"></i>報修</button>';
  h += '<button onclick="navigateTo(\\'faq\\')" class="text-xs px-3 py-2 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20"><i class="fas fa-robot mr-1"></i>AI 助理</button>';
  h += '</div></div>';
  if (anns.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-bullhorn mr-2 text-fju-yellow"></i>最新公告</h3><div class="space-y-2">';
    anns.forEach(a => { h += '<div class="p-3 rounded-fju bg-fju-bg border border-gray-100 hover:border-fju-yellow/30 cursor-pointer" onclick="navigateTo(\\'announcements\\')"><div class="flex items-center justify-between"><span class="font-medium text-sm text-fju-blue">'+(a.ANN_TITLE||'')+'</span><span class="text-xs text-gray-400">'+(a.ANN_START_DATE||'')+'</span></div><p class="text-xs text-gray-500 mt-1 line-clamp-1">'+(a.ANN_CONTENT||'')+'</p></div>'; });
    h += '</div></div>';
  }
  if (isAdmin || role === 'admin') {
    h += '<div class="grid md:grid-cols-2 gap-4"><div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-clipboard-list mr-2 text-fju-yellow"></i>待審核項目</h3><div class="space-y-2">';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'venues\\')"><span class="text-sm text-gray-600">待審核預約</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingBookings||0)+'</span></div>';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'activities\\')"><span class="text-sm text-gray-600">待審核活動</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingActivities||0)+'</span></div>';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'appeals\\')"><span class="text-sm text-gray-600">待審核申訴</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingAppeals||0)+'</span></div>';
    h += '</div></div>';
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>本月概覽</h3><div class="space-y-2">';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">已核准預約</span><span class="font-bold text-fju-green">'+(d.approvedBookings||0)+'</span></div>';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">進行中借用</span><span class="font-bold text-fju-blue">'+(d.activeLoans||0)+'</span></div>';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">進行中報修</span><span class="font-bold text-fju-red">'+(d.openRepairs||0)+'</span></div>';
    h += '</div></div></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}

// ========== Activities (Epic 3) ==========
async function loadActivities() {
  const res = await fetch(API+'/activities').then(r=>r.json()).catch(()=>({data:[]}));
  const items = res.data || [];
  const sL = { 0:'待審核', 1:'已核准', 2:'已拒絕', 5:'已取消' };
  const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected', 5:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-file-alt','總申請',items.length,'blue')+statCard('fa-check-circle','已核准',items.filter(i=>i.AA_STATUS===1).length,'green')+statCard('fa-clock','待審核',items.filter(i=>i.AA_STATUS===0).length,'yellow')+statCard('fa-times-circle','已拒絕',items.filter(i=>i.AA_STATUS===2).length,'red')+'</div>';
  h += '<div class="flex items-center justify-between flex-wrap gap-2"><input type="text" placeholder="搜尋活動..." class="input-field w-64" oninput="filterTable(\\'act-table\\',this.value)">';
  if (['officer','professor','staff','admin'].includes(currentUser?.role) || isAdmin) h += '<button onclick="showAddActivityForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增活動申請</button>';
  h += '</div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm" id="act-table"><thead class="table-header"><tr><th class="table-cell">流水號</th><th class="table-cell">活動名稱</th><th class="table-cell">申請人/單位</th><th class="table-cell">時間</th><th class="table-cell">人數</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">暫無活動申請</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs font-mono text-fju-blue">'+(i.AA_SERIAL_NO||'')+'</td><td class="table-cell font-medium">'+(i.AA_ACTIVITY_NAME||'')+'</td><td class="table-cell text-xs text-gray-500">'+(i.USR_NAME||'')+' / '+(i.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(i.AA_START_DATETIME||'').slice(0,16)+'</td><td class="table-cell">'+i.AA_HEADCOUNT+'</td><td class="table-cell"><span class="'+(sC[i.AA_STATUS]||'')+'">'+((sL[i.AA_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.AA_STATUS === 1) h += '<button onclick="generatePDF('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1" title="產生PDF"><i class="fas fa-file-pdf"></i></button>';
    if (i.AA_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red mr-1">拒絕</button>';
    }
    if (i.AA_STATUS === 0) h += '<button onclick="cancelActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-500">取消</button>';
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAddActivityForm() {
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">預設單位</option>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-calendar-check mr-2 text-fju-yellow"></i>新增活動申請</h3><div class="space-y-3"><input id="aa-name" placeholder="活動名稱 *" class="input-field"><select id="aa-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始</label><input id="aa-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">結束</label><input id="aa-end" type="datetime-local" class="input-field"></div></div><input id="aa-headcount" type="number" placeholder="預計人數 *" class="input-field"><textarea id="aa-desc" placeholder="活動說明" rows="2" class="input-field"></textarea><div class="flex gap-4 text-sm"><label><input type="checkbox" id="aa-alcohol"> 含酒精</label><label><input type="checkbox" id="aa-fire"> 含明火</label><label><input type="checkbox" id="aa-booth"> 含攤位</label></div><button onclick="submitActivity()" class="w-full btn-yellow py-2.5">送出申請</button></div></div></div>');
}
async function submitActivity() {
  const body = { userId:currentUser?.id||1, unitId:parseInt(document.getElementById('aa-unit').value), activityName:document.getElementById('aa-name').value, startDatetime:document.getElementById('aa-start').value, endDatetime:document.getElementById('aa-end').value, headcount:parseInt(document.getElementById('aa-headcount').value)||20, description:document.getElementById('aa-desc').value, hasAlcohol:document.getElementById('aa-alcohol').checked?1:0, hasFire:document.getElementById('aa-fire').checked?1:0, hasBooth:document.getElementById('aa-booth').checked?1:0 };
  if (!body.activityName) return alert('請填寫活動名稱');
  const res = await fetch(API+'/activities', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('活動申請已送出','success'); closeModal(); loadActivities(); }
  else { alert(data.message || '申請失敗'); }
}
async function approveActivity(id) { await fetch(API+'/activities/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('活動已核准','success'); loadActivities(); }
async function rejectActivity(id) { const note = prompt('拒絕原因：'); if(!note) return; await fetch(API+'/activities/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,note}) }); loadActivities(); }
async function cancelActivity(id) { if(!confirm('確定取消此活動？')) return; await fetch(API+'/activities/'+id+'/cancel', { method:'PATCH' }); loadActivities(); }
async function generatePDF(id) {
  try {
    const res = await fetch(API+'/ai/generate-pdf', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({activityId:id}) });
    const data = await res.json();
    if (data.success) {
      const p = data.pdfData;
      const w = window.open('','_blank');
      w.document.write('<html><head><title>活動申請書 - '+p.serialNo+'</title><style>body{font-family:sans-serif;padding:40px;max-width:800px;margin:0 auto}h1{text-align:center;border-bottom:3px double #003366;padding-bottom:10px;color:#003366}table{width:100%;border-collapse:collapse;margin:20px 0}td,th{border:1px solid #ccc;padding:8px 12px;text-align:left;font-size:14px}th{background:#f5f5f5;width:120px}.footer{margin-top:40px;text-align:center;font-size:12px;color:#999}.header{text-align:center;margin-bottom:20px}.stamp{display:inline-block;border:2px solid '+(p.status==='已核准'?'green':'red')+';color:'+(p.status==='已核准'?'green':'red')+';padding:5px 15px;border-radius:4px;font-weight:bold;font-size:18px;transform:rotate(-5deg)}</style></head><body>');
      w.document.write('<div class="header"><h1>輔仁大學課外活動指導組<br>活動申請書</h1></div>');
      w.document.write('<table><tr><th>流水編號</th><td>'+p.serialNo+'</td><th>審核狀態</th><td><span class="stamp">'+p.status+'</span></td></tr>');
      w.document.write('<tr><th>活動名稱</th><td colspan="3">'+p.activityName+'</td></tr>');
      w.document.write('<tr><th>主辦單位</th><td>'+p.unitName+'</td><th>申請人</th><td>'+p.applicantName+'</td></tr>');
      w.document.write('<tr><th>活動時間</th><td colspan="3">'+p.startDatetime+' ~ '+p.endDatetime+'</td></tr>');
      w.document.write('<tr><th>預計人數</th><td>'+p.headcount+' 人</td><th>聯絡人</th><td>'+(p.contactName||p.applicantName)+' '+(p.contactPhone||'')+'</td></tr>');
      w.document.write('<tr><th>活動說明</th><td colspan="3">'+(p.description||'')+'</td></tr>');
      w.document.write('<tr><th>特殊事項</th><td colspan="3">'+(p.hasAlcohol?'✅ 含酒精 ':'')+(p.hasFire?'✅ 含明火 ':'')+(p.hasBooth?'✅ 含攤位 ':'')+(!p.hasAlcohol&&!p.hasFire&&!p.hasBooth?'無':'')+'</td></tr>');
      if (p.adminNote) w.document.write('<tr><th>審核備註</th><td colspan="3">'+p.adminNote+'</td></tr>');
      w.document.write('</table>');
      w.document.write('<div class="footer"><p>本申請書由系統自動產生 | 產生時間：'+new Date(p.generatedAt).toLocaleString('zh-TW')+'</p><p>輔仁大學學生事務處課外活動指導組</p></div>');
      w.document.write('</body></html>');
      w.document.close();
    } else { alert(data.message); }
  } catch(e) { alert('PDF 產生失敗'); }
}

// ========== Venues (Epic 4) ==========
async function loadVenues() {
  const [facRes, bkRes] = await Promise.all([fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/venue-bookings').then(r=>r.json()).catch(()=>({data:[]}))])
  const venues = facRes.data || []; const bookings = bkRes.data || [];
  const sL = { 0:'可預約', 1:'維修中' }; const sC = { 0:'status-approved', 1:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-building','場地總數',venues.length,'blue')+statCard('fa-check-circle','可預約',venues.filter(v=>v.FAC_STATUS===0).length,'green')+statCard('fa-tools','維修中',venues.filter(v=>v.FAC_STATUS===1).length,'red')+statCard('fa-calendar-check','預約數',bookings.length,'yellow')+'</div>';
  h += '<div class="flex items-center justify-between flex-wrap gap-2"><input type="text" placeholder="搜尋場地..." class="input-field w-64" oninput="filterTable(\\'venue-table\\',this.value)">';
  if (isAdmin) h += '<button onclick="showAddVenueForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增場地</button>';
  h += '</div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm" id="venue-table"><thead class="table-header"><tr><th class="table-cell">場地名稱</th><th class="table-cell">大樓/樓層</th><th class="table-cell">類型</th><th class="table-cell">容量</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  const typeL = { 0:'場地', 1:'教室', 2:'運動場館', 3:'會議室' };
  venues.forEach(v => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(v.FAC_NAME||'')+'</td><td class="table-cell text-gray-500">'+(v.FAC_BUILDING||'')+' '+(v.FAC_FLOOR||'')+'F</td><td class="table-cell text-xs">'+(typeL[v.FAC_TYPE]||'')+'</td><td class="table-cell">'+(v.FAC_CAPACITY||'')+' 人</td><td class="table-cell"><span class="'+(sC[v.FAC_STATUS]||'')+'">'+((sL[v.FAC_STATUS])||'')+'</span></td><td class="table-cell">';
    if (v.FAC_STATUS === 0) h += '<button onclick="showBookingForm('+v.FAC_ID+',\\''+v.FAC_NAME+'\\')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1">預約</button>';
    h += '<button onclick="showCalendar('+v.FAC_ID+',\\''+v.FAC_NAME+'\\')" class="text-xs px-2 py-1 rounded bg-purple-100 text-purple-700">行事曆</button>';
    h += '</td></tr>';
  });
  h += '</tbody></table></div>';
  // 預約紀錄
  if (bookings.length > 0) {
    const bkSL = { 0:'待審核', 1:'已核准', 2:'已拒絕', 3:'已取消', 4:'已歸還', 5:'歸還異常' };
    const bkSC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected', 3:'status-rejected', 4:'status-approved', 5:'status-rejected' };
    h += '<div class="card p-0 overflow-x-auto"><h3 class="p-4 font-bold text-fju-blue"><i class="fas fa-list mr-2 text-fju-yellow"></i>預約紀錄</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">場地</th><th class="table-cell">申請人/單位</th><th class="table-cell">時間</th><th class="table-cell">事由</th><th class="table-cell">狀態</th>'+(isAdmin?'<th class="table-cell">操作</th>':'')+'</tr></thead><tbody>';
    bookings.forEach(b => {
      h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-fju-blue font-medium">'+(b.FAC_NAME||'')+'</td><td class="table-cell text-xs">'+(b.USR_NAME||'')+' / '+(b.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(b.VB_START_DATETIME||'').slice(0,16)+'</td><td class="table-cell text-xs">'+(b.VB_PURPOSE||'')+'</td><td class="table-cell"><span class="'+(bkSC[b.VB_STATUS]||'')+'">'+((bkSL[b.VB_STATUS])||'')+'</span></td>';
      if (isAdmin) {
        h += '<td class="table-cell">';
        if (b.VB_STATUS === 0) { h += '<button onclick="approveBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button><button onclick="rejectBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">拒絕</button>'; }
        else if (b.VB_STATUS === 1) { h += '<button onclick="returnBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue">歸還</button>'; }
        h += '</td>';
      }
      h += '</tr>';
    });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function approveBooking(id) { await fetch(API+'/venue-bookings/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('預約已核准','success'); loadVenues(); }
async function rejectBooking(id) { const r = prompt('拒絕原因：'); if(!r) return; await fetch(API+'/venue-bookings/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,reason:r}) }); loadVenues(); }
async function returnBooking(id) { await fetch(API+'/venue-bookings/'+id+'/return', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({abnormal:false}) }); loadVenues(); }
function showBookingForm(facId, facName) {
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('');
  if (!uOpts) uOpts = '<option value="1">預設單位</option>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>預約場地：'+facName+'</h3><div class="space-y-3"><select id="vb-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始</label><input id="vb-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">結束</label><input id="vb-end" type="datetime-local" class="input-field"></div></div><input id="vb-purpose" placeholder="使用事由 *" class="input-field"><input id="vb-headcount" type="number" placeholder="使用人數" class="input-field"><input type="hidden" id="vb-fac-id" value="'+facId+'"><button onclick="submitBooking()" class="w-full btn-yellow py-2.5">送出預約</button></div></div></div>');
}
async function submitBooking() {
  const body = { facId:parseInt(document.getElementById('vb-fac-id').value), unitId:parseInt(document.getElementById('vb-unit').value), userId:currentUser?.id||1, startDatetime:document.getElementById('vb-start').value, endDatetime:document.getElementById('vb-end').value, purpose:document.getElementById('vb-purpose').value, headcount:parseInt(document.getElementById('vb-headcount').value)||20, activityId:1, bookingType:0 };
  if (!body.purpose) return alert('請填寫使用事由');
  const res = await fetch(API+'/venue-bookings', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('場地預約已送出','success'); closeModal(); loadVenues(); }
  else { alert(data.message || '預約失敗，可能有時段衝突'); }
}
function showAddVenueForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-plus mr-2 text-fju-yellow"></i>新增場地</h3><div class="space-y-3"><input id="fac-name" placeholder="場地名稱 *" class="input-field"><input id="fac-building" placeholder="所在大樓 *" class="input-field"><input id="fac-floor" type="number" placeholder="樓層" class="input-field"><input id="fac-capacity" type="number" placeholder="容納人數" class="input-field"><select id="fac-type" class="input-field"><option value="0">場地</option><option value="1">教室</option><option value="2">運動場館</option><option value="3">會議室</option></select><textarea id="fac-desc" placeholder="場地描述" rows="2" class="input-field"></textarea><button onclick="submitFacility()" class="w-full btn-yellow py-2.5">新增</button></div></div></div>');
}
async function submitFacility() {
  await fetch(API+'/facilities', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ name:document.getElementById('fac-name').value, building:document.getElementById('fac-building').value, floor:parseInt(document.getElementById('fac-floor').value)||1, capacity:parseInt(document.getElementById('fac-capacity').value)||50, type:parseInt(document.getElementById('fac-type').value), desc:document.getElementById('fac-desc').value }) });
  closeModal(); loadVenues();
}
// 場地行事曆檢視 — 月曆格式 (Epic 4 行事曆視圖)
let calViewYear, calViewMonth;
async function showCalendar(facId, facName) {
  const now = new Date(); calViewYear = now.getFullYear(); calViewMonth = now.getMonth();
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content" style="max-width:720px"><h3 class="font-bold text-fju-blue text-lg mb-2"><i class="fas fa-calendar-alt mr-2 text-fju-yellow"></i>'+facName+' — 月曆</h3><div id="cal-grid"></div></div></div>');
  window._calFacId = facId; window._calFacName = facName;
  renderCalendar(facId);
}
async function renderCalendar(facId) {
  const y = calViewYear, m = calViewMonth;
  const first = new Date(y, m, 1); const last = new Date(y, m+1, 0);
  const startPad = first.getDay(); const daysInMonth = last.getDate();
  const startQ = y+'-'+String(m+1).padStart(2,'0')+'-01';
  const endQ = y+'-'+String(m+1).padStart(2,'0')+'-'+String(daysInMonth).padStart(2,'0');
  const res = await fetch(API+'/facilities/'+facId+'/calendar?start='+startQ+'&end='+endQ).then(r=>r.json()).catch(()=>({data:[]}));
  const events = res.data || [];
  const byDay = {};
  events.forEach(e => { const d = parseInt((e.VB_START_DATETIME||'').slice(8,10)); if(!byDay[d]) byDay[d]=[]; byDay[d].push(e); });
  const mNames = ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];
  let h = '<div class="flex items-center justify-between mb-3"><button onclick="calViewMonth--;if(calViewMonth<0){calViewMonth=11;calViewYear--;}renderCalendar(window._calFacId)" class="text-fju-blue hover:bg-fju-bg px-2 py-1 rounded"><i class="fas fa-chevron-left"></i></button><span class="font-bold text-fju-blue">'+y+' 年 '+mNames[m]+'</span><button onclick="calViewMonth++;if(calViewMonth>11){calViewMonth=0;calViewYear++;}renderCalendar(window._calFacId)" class="text-fju-blue hover:bg-fju-bg px-2 py-1 rounded"><i class="fas fa-chevron-right"></i></button></div>';
  h += '<div class="grid grid-cols-7 text-center text-xs font-bold text-gray-400 mb-1">';
  ['日','一','二','三','四','五','六'].forEach(d => { h += '<div class="py-1">'+d+'</div>'; });
  h += '</div><div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200 rounded-fju overflow-hidden">';
  for (let i=0; i<startPad; i++) h += '<div class="bg-gray-50 min-h-[56px]"></div>';
  const today = new Date(); const isThisMonth = today.getFullYear()===y && today.getMonth()===m;
  for (let d=1; d<=daysInMonth; d++) {
    const isToday = isThisMonth && today.getDate()===d;
    const evts = byDay[d] || [];
    h += '<div class="bg-white min-h-[56px] p-1 relative '+(isToday?'ring-2 ring-fju-yellow ring-inset':'')+'">';
    h += '<div class="text-xs font-medium '+(isToday?'text-fju-blue font-bold':'text-gray-500')+'">'+d+'</div>';
    evts.slice(0,2).forEach(e => {
      const color = e.VB_STATUS===1?'bg-fju-green/20 text-fju-green border-fju-green/30':'bg-fju-yellow/20 text-fju-blue border-fju-yellow/30';
      h += '<div class="mt-0.5 text-[10px] leading-tight px-1 py-0.5 rounded border truncate '+color+'" title="'+(e.VB_PURPOSE||'')+' ('+(e.VB_START_DATETIME||'').slice(11,16)+'~'+(e.VB_END_DATETIME||'').slice(11,16)+')">'+(e.VB_PURPOSE||'').slice(0,6)+'</div>';
    });
    if (evts.length > 2) h += '<div class="text-[9px] text-gray-400 mt-0.5">+' + (evts.length - 2) + ' 更多</div>';
    h += '</div>';
  }
  const totalCells = startPad + daysInMonth; const remain = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
  for (let i=0; i<remain; i++) h += '<div class="bg-gray-50 min-h-[56px]"></div>';
  h += '</div>';
  h += '<div class="flex gap-3 mt-3 text-xs text-gray-400"><span><span class="inline-block w-3 h-3 rounded bg-fju-green/20 border border-fju-green/30 mr-1 align-middle"></span>已核准</span><span><span class="inline-block w-3 h-3 rounded bg-fju-yellow/20 border border-fju-yellow/30 mr-1 align-middle"></span>待審核</span></div>';
  document.getElementById('cal-grid').innerHTML = h;
}

// ========== Equipment (Epic 5) ==========
async function loadEquipment() {
  const [eqRes, loanRes] = await Promise.all([fetch(API+'/equipment').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/equipment/loans/list').then(r=>r.json()).catch(()=>({data:[]}))])
  const items = eqRes.data || []; const loans = loanRes.data || [];
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-box','器材項目',items.length,'blue')+statCard('fa-check','可借用',items.filter(i=>i.EQ_AVAILABLE>0).length,'green')+statCard('fa-certificate','需操作證',items.filter(i=>i.EQ_CERT_TYPE_ID).length,'yellow')+statCard('fa-boxes-stacked','總庫存',items.reduce((s,i)=>s+i.EQ_TOTAL,0),'purple')+'</div>';
  if (['officer','professor','staff','admin'].includes(currentUser?.role) || currentUser?.isAdmin===1) h += '<div class="flex justify-end"><button onclick="showLoanForm()" class="btn-yellow text-xs"><i class="fas fa-hand-holding mr-1"></i>借用器材</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">器材名稱</th><th class="table-cell">總數</th><th class="table-cell">可用</th><th class="table-cell">單次上限</th><th class="table-cell">需操作證</th><th class="table-cell">說明</th></tr></thead><tbody>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.EQ_NAME||'')+'</td><td class="table-cell">'+i.EQ_TOTAL+'</td><td class="table-cell">'+(i.EQ_AVAILABLE>0?'<span class="status-approved">'+i.EQ_AVAILABLE+'</span>':'<span class="status-rejected">0</span>')+'</td><td class="table-cell">'+i.EQ_MAX_PER_LOAN+'</td><td class="table-cell">'+(i.CERT_NAME?'<span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs">'+i.CERT_NAME+'</span>':'<span class="text-gray-400">-</span>')+'</td><td class="table-cell text-xs text-gray-400">'+(i.EQ_DESC||'-')+'</td></tr>';
  });
  h += '</tbody></table></div>';
  if (loans.length > 0) {
    const elSL = { 0:'待領取', 1:'借用中', 2:'部分領取', 3:'部分歸還', 4:'已歸還', 5:'歸還異常' };
    h += '<div class="card p-0 overflow-x-auto"><h3 class="p-4 font-bold text-fju-blue"><i class="fas fa-history mr-2 text-fju-yellow"></i>借用紀錄</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">借用人</th><th class="table-cell">單位</th><th class="table-cell">用途</th><th class="table-cell">借用日</th><th class="table-cell">歸還期限</th><th class="table-cell">狀態</th></tr></thead><tbody>';
    loans.forEach(l => { h += '<tr class="border-t border-gray-50"><td class="table-cell">'+(l.USR_NAME||'')+'</td><td class="table-cell text-xs">'+(l.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(l.EL_PURPOSE||'')+'</td><td class="table-cell text-xs">'+(l.EL_BORROW_START||'').slice(0,10)+'</td><td class="table-cell text-xs">'+(l.EL_RETURN_DUE||'').slice(0,10)+'</td><td class="table-cell text-xs">'+(elSL[l.EL_STATUS]||'')+'</td></tr>'; });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
  window._eqItems = items;
}
function showLoanForm() {
  const items = window._eqItems || [];
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">預設單位</option>';
  let eqHtml = items.filter(i=>i.EQ_AVAILABLE>0).map(i => '<label class="flex items-center gap-2 p-2 rounded-fju hover:bg-fju-bg"><input type="checkbox" class="eq-check" value="'+i.EQ_ID+'"><span class="text-sm">'+i.EQ_NAME+' (可用:'+i.EQ_AVAILABLE+')</span><input type="number" class="eq-qty w-16 px-2 py-1 border rounded text-xs" min="1" max="'+i.EQ_MAX_PER_LOAN+'" value="1"></label>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hand-holding mr-2 text-fju-yellow"></i>借用器材</h3><div class="space-y-3"><select id="el-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">領取日期</label><input id="el-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">歸還期限</label><input id="el-due" type="datetime-local" class="input-field"></div></div><input id="el-location" placeholder="使用地點 *" class="input-field"><input id="el-purpose" placeholder="用途說明 *" class="input-field"><div class="border rounded-fju p-3 max-h-40 overflow-y-auto"><p class="text-xs text-gray-500 mb-2">選擇器材：</p>'+eqHtml+'</div><button onclick="submitLoan()" class="w-full btn-yellow py-2.5">送出借用申請</button></div></div></div>');
}
async function submitLoan() {
  const checks = document.querySelectorAll('.eq-check:checked');
  const items = [];
  checks.forEach(ck => { const row = ck.closest('label'); const qty = row.querySelector('.eq-qty'); items.push({ equipmentId: parseInt(ck.value), quantity: parseInt(qty.value)||1 }); });
  if (items.length === 0) return alert('請選擇至少一項器材');
  const body = { activityId:1, unitId:parseInt(document.getElementById('el-unit').value), userId:currentUser?.id||1, borrowStart:document.getElementById('el-start').value, returnDue:document.getElementById('el-due').value, useLocation:document.getElementById('el-location').value, purpose:document.getElementById('el-purpose').value, items };
  const res = await fetch(API+'/equipment/loans', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('器材借用已送出','success'); closeModal(); loadEquipment(); }
  else { alert(data.message || '借用失敗'); }
}

// ========== Coordination (場協大會 Epic 4.2) ==========
async function loadCoordination() {
  const res = await fetch(API+'/venue-coordination?semester=114-2').then(r=>r.json()).catch(()=>({data:[]}));
  const items = res.data || [];
  const dayNames = ['日','一','二','三','四','五','六'];
  const sL = { 0:'待審核', 1:'已核准', 2:'已駁回' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>場協大會說明</h3><div class="text-xs text-gray-600 space-y-1"><p>1. 學期初由課指組舉辦場協大會，各單位登記每週固定使用場地需求</p><p>2. 登記截止後，課指組統一審核並協調衝突</p><p>3. 核准後系統自動產生整學期的場地預約</p></div></div>';
  if (['officer','admin'].includes(currentUser?.role) || isAdmin) h += '<div class="flex justify-end"><button onclick="showCoordinationForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增登記</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">單位</th><th class="table-cell">場地</th><th class="table-cell">每週</th><th class="table-cell">時段</th><th class="table-cell">用途</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">暫無登記</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.UNIT_NAME||'')+'</td><td class="table-cell">'+(i.FAC_NAME||'')+'</td><td class="table-cell">週'+(dayNames[i.VC_DAY_OF_WEEK]||'')+'</td><td class="table-cell text-xs">'+(i.VC_TIME_START||'')+' ~ '+(i.VC_TIME_END||'')+'</td><td class="table-cell text-xs">'+(i.VC_PURPOSE||'')+'</td><td class="table-cell"><span class="'+(sC[i.VC_STATUS]||'')+'">'+((sL[i.VC_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.VC_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveCoord('+i.VC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectCoord('+i.VC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">駁回</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function approveCoord(id) { await fetch(API+'/venue-coordination/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({}) }); toast('登記已核准','success'); loadCoordination(); }
async function rejectCoord(id) { const note = prompt('駁回原因：'); if(!note) return; await fetch(API+'/venue-coordination/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({note}) }); loadCoordination(); }
async function showCoordinationForm() {
  const facRes = await fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]}));
  const facs = facRes.data || []; const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">預設單位</option>';
  let fOpts = facs.filter(f=>f.FAC_STATUS===0).map(f => '<option value="'+f.FAC_ID+'">'+f.FAC_NAME+'</option>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-users-rectangle mr-2 text-fju-yellow"></i>場協大會登記</h3><div class="space-y-3"><select id="vc-unit" class="input-field">'+uOpts+'</select><select id="vc-fac" class="input-field">'+fOpts+'</select><select id="vc-day" class="input-field"><option value="1">週一</option><option value="2">週二</option><option value="3">週三</option><option value="4">週四</option><option value="5">週五</option></select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始</label><input id="vc-start" type="time" class="input-field" value="14:00"></div><div><label class="text-xs text-gray-500 block mb-1">結束</label><input id="vc-end" type="time" class="input-field" value="17:00"></div></div><input id="vc-purpose" placeholder="使用事由" class="input-field"><button onclick="submitCoordination()" class="w-full btn-yellow py-2.5">送出登記</button></div></div></div>');
}
async function submitCoordination() {
  const body = { userId:currentUser?.id||1, unitId:parseInt(document.getElementById('vc-unit').value), facId:parseInt(document.getElementById('vc-fac').value), dayOfWeek:parseInt(document.getElementById('vc-day').value), timeStart:document.getElementById('vc-start').value, timeEnd:document.getElementById('vc-end').value, purpose:document.getElementById('vc-purpose').value, semester:'114-2' };
  const res = await fetch(API+'/venue-coordination', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('登記已送出','success'); closeModal(); loadCoordination(); }
  else { alert(data.message || '登記失敗'); }
}

// ========== Repairs (Epic 6) ==========
async function loadRepairs() {
  const [repRes, facRes] = await Promise.all([fetch(API+'/repairs').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]}))])
  const items = repRes.data || []; window._facilities = facRes.data || [];
  const sL = { 0:'待處理', 1:'處理中', 2:'已完成' }; const sC = { 0:'status-pending', 1:'status-info', 2:'status-approved' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in"><div class="flex justify-end"><button onclick="showRepairForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>新增報修</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">設施</th><th class="table-cell">問題描述</th><th class="table-cell">申報人</th><th class="table-cell">狀態</th><th class="table-cell">時間</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">暫無報修</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.FAC_NAME||'-')+'</td><td class="table-cell text-xs">'+(i.RR_DESCRIPTION||'')+'</td><td class="table-cell text-xs">'+(i.USR_NAME||'-')+'</td><td class="table-cell"><span class="'+(sC[i.RR_STATUS]||'')+'">'+((sL[i.RR_STATUS])||'')+'</span></td><td class="table-cell text-xs text-gray-400">'+(i.RR_CREATED_AT||'')+'</td><td class="table-cell">';
    if (i.RR_STATUS < 2 && isAdmin) {
      const ns = i.RR_STATUS === 0 ? 1 : 2;
      h += '<button onclick="updateRepairStatus('+i.RR_ID+','+ns+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue">'+(ns===1?'開始處理':'完成')+'</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showRepairForm() {
  const facs = window._facilities || [];
  let opts = facs.map(f => '<option value="'+f.FAC_ID+'">'+f.FAC_NAME+'</option>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>新增報修</h3><div class="space-y-3"><select id="rr-fac" class="input-field"><option value="">選擇設施 *</option>'+opts+'</select><textarea id="rr-desc" placeholder="問題描述（至少10字）*" rows="3" class="input-field"></textarea><button onclick="submitRepair()" class="w-full btn-yellow py-2.5">送出報修</button></div></div></div>');
}
async function submitRepair() {
  const desc = document.getElementById('rr-desc').value;
  if (desc.length < 10) return alert('問題描述至少需要10個字');
  await fetch(API+'/repairs', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ facId:parseInt(document.getElementById('rr-fac').value), userId:currentUser?.id||1, description:desc }) });
  toast('報修已送出','success'); closeModal(); loadRepairs();
}
async function updateRepairStatus(id, status) { await fetch(API+'/repairs/'+id, { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ status, adminId:currentUser?.id||1 }) }); loadRepairs(); }

// ========== Appeals (Epic 7) ==========
async function loadAppeals() {
  const res = await fetch(API+'/appeals').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const tL = { 0:'停權申復', 1:'違規記點申復', 2:'其他檢舉' }; const sL = { 0:'待審核', 1:'已核准', 2:'已駁回' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in"><div class="flex justify-end"><button onclick="showAppealForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>提交申訴</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">類型</th><th class="table-cell">申訴人</th><th class="table-cell">理由</th><th class="table-cell">狀態</th><th class="table-cell">時間</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">暫無申訴</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell"><span class="px-2 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(tL[i.AC_TYPE]||'')+'</span></td><td class="table-cell text-sm">'+(i.USR_NAME||'-')+'</td><td class="table-cell text-xs">'+(i.AC_REASON||'')+'</td><td class="table-cell"><span class="'+(sC[i.AC_STATUS]||'')+'">'+((sL[i.AC_STATUS])||'')+'</span></td><td class="table-cell text-xs text-gray-400">'+(i.AC_CREATED_AT||'')+'</td><td class="table-cell">';
    if (i.AC_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveAppeal('+i.AC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectAppeal('+i.AC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">駁回</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAppealForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>提交申訴</h3><div class="space-y-3"><select id="ac-type" class="input-field"><option value="0">停權申復</option><option value="1">違規記點申復</option><option value="2">其他檢舉</option></select><textarea id="ac-reason" placeholder="申訴理由 *（請詳述事由）" rows="3" class="input-field"></textarea><input id="ac-evidence" placeholder="佐證資料（選填）" class="input-field"><button onclick="submitAppeal()" class="w-full btn-yellow py-2.5">送出申訴</button></div></div></div>');
}
async function submitAppeal() {
  const reason = document.getElementById('ac-reason').value;
  if (!reason) return alert('請填寫申訴理由');
  await fetch(API+'/appeals', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ userId:currentUser?.id||1, type:parseInt(document.getElementById('ac-type').value), reason, evidence:document.getElementById('ac-evidence').value }) });
  toast('申訴已送出','success'); closeModal(); loadAppeals();
}
async function approveAppeal(id) { await fetch(API+'/appeals/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('申訴已核准','success'); loadAppeals(); }
async function rejectAppeal(id) { const note = prompt('駁回原因：'); if(!note) return; await fetch(API+'/appeals/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,note}) }); loadAppeals(); }

// ========== Announcements (Epic 8) ==========
async function loadAnnouncements() {
  const res = await fetch(API+'/announcements').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  if (isAdmin) h += '<div class="flex justify-end"><button onclick="showAnnouncementForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增公告</button></div>';
  if (items.length === 0) h += '<div class="card text-center text-gray-400"><i class="fas fa-bullhorn text-2xl mb-2"></i><p>暫無公告</p></div>';
  items.forEach(a => {
    h += '<div class="card hover:border-fju-yellow/30 transition-all"><div class="flex items-start justify-between"><div class="flex-1"><h3 class="font-bold text-fju-blue">'+(a.ANN_TITLE||'')+'</h3><p class="text-sm text-gray-600 mt-2 whitespace-pre-line">'+(a.ANN_CONTENT||'')+'</p><div class="flex gap-3 mt-3 text-xs text-gray-400"><span><i class="fas fa-calendar mr-1"></i>'+(a.ANN_START_DATE||'')+' ~ '+(a.ANN_END_DATE||'')+'</span><span><i class="fas fa-user mr-1"></i>'+(a.ADMIN_NAME||'管理員')+'</span></div></div>';
    if (isAdmin) h += '<button onclick="deleteAnnouncement('+a.ANN_ID+')" class="text-gray-300 hover:text-fju-red ml-3"><i class="fas fa-trash"></i></button>';
    h += '</div></div>';
  });
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAnnouncementForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-bullhorn mr-2 text-fju-yellow"></i>新增公告</h3><div class="space-y-3"><input id="ann-title" placeholder="公告標題 *" class="input-field"><textarea id="ann-content" placeholder="公告內容 *" rows="4" class="input-field"></textarea><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始日期</label><input id="ann-start" type="date" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">結束日期</label><input id="ann-end" type="date" class="input-field"></div></div><button onclick="submitAnnouncement()" class="w-full btn-yellow py-2.5">發布公告</button></div></div></div>');
}
async function submitAnnouncement() {
  await fetch(API+'/announcements', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ title:document.getElementById('ann-title').value, content:document.getElementById('ann-content').value, startDate:document.getElementById('ann-start').value, endDate:document.getElementById('ann-end').value, adminId:currentUser?.id||1 }) });
  toast('公告已發布','success'); closeModal(); loadAnnouncements();
}
async function deleteAnnouncement(id) { if(!confirm('確定刪除此公告？')) return; await fetch(API+'/announcements/'+id, { method:'DELETE' }); loadAnnouncements(); }

// ========== FAQ / AI (Prompt7 9A: 整合至單一頁面) ==========
async function loadFaq() {
  const role = currentUser?.role || 'student';
  const res = await fetch(API+'/faq?role='+role).then(r=>r.json()).catch(()=>({common:[],roleSpecific:[],regulations:[]}));
  let h = '<div class="space-y-6 fade-in">';
  // AI Chat
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 智慧助理 <span class="text-xs font-normal text-gray-400 ml-2">GPT-4o via GitHub Models</span></h3>';
  h += '<div id="ai-chat-history" class="space-y-3 max-h-72 overflow-y-auto mb-3 p-3 bg-fju-bg rounded-fju-lg">';
  h += '<div class="flex"><div class="bg-white border border-gray-100 rounded-fju-lg px-4 py-2 text-sm max-w-[85%] shadow-sm"><div class="text-gray-700">👋 您好！我是課指組AI助理。您可以問我關於場地預約、器材借用、違規記點等問題。</div><div class="text-xs text-gray-400 mt-1">RAG 知識庫</div></div></div>';
  h += '</div>';
  h += '<div class="flex gap-2"><input id="ai-input" type="text" placeholder="輸入問題...（例如：場地預約怎麼操作？）" class="flex-1 input-field" onkeypress="if(event.key===\\'Enter\\')askAI()"><button onclick="askAI()" class="btn-primary px-6"><i class="fas fa-paper-plane mr-1"></i>送出</button></div>';
  h += '<div class="flex flex-wrap gap-2 mt-2">';
  ['場地預約','器材借用','違規','衝突協調','操作證','活動申請','場協大會'].forEach(k => { h += '<button onclick="document.getElementById(\\'ai-input\\').value=\\''+k+'\\';askAI()" class="text-xs px-3 py-1 rounded-fju bg-fju-blue/5 text-fju-blue hover:bg-fju-blue/10">'+k+'</button>'; });
  h += '</div></div>';
  // Role-specific FAQ
  if (res.roleSpecific?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-user-tag mr-2 text-fju-yellow"></i>'+(roleNames[role]||'')+'常見問題</h3><div class="space-y-2">';
    res.roleSpecific.forEach(f => {
      h += '<details class="border border-gray-100 rounded-fju"><summary class="p-3 cursor-pointer hover:bg-fju-bg text-sm font-medium text-fju-blue"><i class="fas fa-chevron-right mr-2 text-xs text-fju-yellow"></i>'+f.q+'</summary><div class="p-3 pt-0 text-sm text-gray-600 whitespace-pre-line border-t border-gray-100 mt-1">'+f.a+'</div></details>';
    });
    h += '</div></div>';
  }
  // Common FAQ
  if (res.common?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-question-circle mr-2 text-fju-yellow"></i>通用常見問題</h3><div class="space-y-2">';
    res.common.forEach(f => {
      h += '<details class="border border-gray-100 rounded-fju"><summary class="p-3 cursor-pointer hover:bg-fju-bg text-sm font-medium text-gray-700"><i class="fas fa-chevron-right mr-2 text-xs text-fju-yellow"></i>'+f.q+' <span class="text-xs text-gray-400 ml-2">'+f.category+'</span></summary><div class="p-3 pt-0 text-sm text-gray-600 whitespace-pre-line border-t border-gray-100 mt-1">'+f.a+'</div></details>';
    });
    h += '</div></div>';
  }
  // Regulations
  if (res.regulations?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-book mr-2 text-fju-yellow"></i>法規參考</h3><div class="space-y-2">';
    res.regulations.forEach(r => { h += '<div class="p-3 rounded-fju bg-fju-bg border border-gray-100"><div class="font-medium text-sm text-fju-blue">'+(r.name||'')+'</div><div class="text-xs text-gray-500 mt-1">'+(r.summary||'')+'</div></div>'; });
    h += '</div></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function askAI() {
  const input = document.getElementById('ai-input');
  const msg = input.value.trim(); if (!msg) return;
  const history = document.getElementById('ai-chat-history');
  history.innerHTML += '<div class="flex justify-end"><div class="bg-fju-blue text-white rounded-fju-lg px-4 py-2 text-sm max-w-[80%]">'+msg+'</div></div>';
  history.innerHTML += '<div id="ai-loading" class="flex"><div class="bg-fju-bg rounded-fju-lg px-4 py-2 text-sm text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>AI 思考中...</div></div>';
  history.scrollTop = history.scrollHeight; input.value = '';
  try {
    const res = await fetch(API+'/ai/chat', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ message:msg, role:currentUser?.role||'student' }) });
    const data = await res.json();
    document.getElementById('ai-loading')?.remove();
    history.innerHTML += '<div class="flex"><div class="bg-white border border-gray-100 rounded-fju-lg px-4 py-3 text-sm max-w-[85%] shadow-sm"><div class="whitespace-pre-line text-gray-700">'+(data.response||'')+'</div><div class="text-xs text-gray-400 mt-2 pt-2 border-t border-gray-100"><i class="fas fa-microchip mr-1"></i>'+(data.source||data.model||'AI')+'</div></div></div>';
  } catch(e) {
    document.getElementById('ai-loading')?.remove();
    history.innerHTML += '<div class="flex"><div class="bg-red-50 rounded-fju-lg px-4 py-2 text-sm text-red-500">AI 回應失敗，請稍後重試</div></div>';
  }
  history.scrollTop = history.scrollHeight;
}

// ========== Stats (Epic 10) ==========
async function loadStats() {
  const [dashRes, facRes, eqRes] = await Promise.all([fetch(API+'/stats/dashboard').then(r=>r.json()).catch(()=>({})), fetch(API+'/stats/facility-usage').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/stats/equipment-usage').then(r=>r.json()).catch(()=>({data:[]}))])
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-5 gap-4">'+statCard('fa-building','場地',dashRes.totalFacilities,'blue')+statCard('fa-tools','器材',dashRes.totalEquipment,'green')+statCard('fa-users','使用者',dashRes.totalUsers,'purple')+statCard('fa-calendar-check','已核准預約',dashRes.approvedBookings,'yellow')+statCard('fa-wrench','待修',dashRes.openRepairs,'red')+'</div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>場地使用統計</h3><div class="space-y-3">';
  (facRes.data||[]).forEach(f => { const pct = Math.min((f.booking_count||0)*5,100); h += '<div class="flex items-center gap-3"><span class="w-32 text-sm text-gray-600 truncate">'+(f.FAC_NAME||'')+'</span><div class="flex-1 bg-gray-200 rounded-full h-3"><div class="bg-fju-blue rounded-full h-3 transition-all" style="width:'+pct+'%"></div></div><span class="text-xs text-gray-500 w-24 text-right">'+(f.booking_count||0)+' 次 / '+parseFloat(f.total_hours||0).toFixed(1)+'h</span></div>'; });
  h += '</div></div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-boxes-stacked mr-2 text-fju-yellow"></i>器材使用統計</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">器材</th><th class="table-cell">總數</th><th class="table-cell">可用</th><th class="table-cell">借出次數</th><th class="table-cell">累計借出</th></tr></thead><tbody>';
  (eqRes.data||[]).forEach(e => { h += '<tr class="border-t border-gray-50"><td class="table-cell font-medium text-fju-blue">'+(e.EQ_NAME||'')+'</td><td class="table-cell">'+e.EQ_TOTAL+'</td><td class="table-cell">'+e.EQ_AVAILABLE+'</td><td class="table-cell">'+e.loan_count+'</td><td class="table-cell">'+e.total_borrowed+'</td></tr>'; });
  h += '</tbody></table></div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-brain mr-2 text-fju-yellow"></i>AI 學期總結評鑑報告</h3><p class="text-sm text-gray-500 mb-3">含 Simpson 多樣性指數、使用率分析、違規統計、資源調配建議</p><button onclick="generateAIReport()" class="btn-yellow text-sm"><i class="fas fa-magic mr-1"></i>產生學期評鑑報告</button><div id="ai-report-content" class="hidden mt-4"></div></div>';
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function generateAIReport() {
  const el = document.getElementById('ai-report-content');
  el.classList.remove('hidden'); el.innerHTML = '<div class="text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>AI 正在生成報告...</div>';
  try {
    const res = await fetch(API+'/ai/generate-report', { method:'POST', headers:{'Content-Type':'application/json'}, body:'{}' });
    const data = await res.json();
    let rh = '<div class="space-y-4 p-4 bg-fju-bg rounded-fju-lg border border-gray-200">';
    rh += '<h4 class="font-bold text-fju-blue text-lg">'+(data.reportTitle||'')+'</h4>';
    rh += '<div class="grid md:grid-cols-2 gap-4">';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-chart-pie mr-1 text-fju-yellow"></i>Simpson 多樣性指數</h5><div class="text-3xl font-black text-fju-blue">D = '+(data.simpson?.value||0)+'</div><p class="text-xs text-gray-500 mt-1">'+(data.simpson?.interpretation||'')+'</p>';
    if (data.simpson?.unitBreakdown?.length) { rh += '<div class="mt-2 space-y-1">'; data.simpson.unitBreakdown.forEach(u => { rh += '<div class="flex justify-between text-xs"><span class="text-gray-500">'+(u.UNIT_NAME||'未知')+'</span><span class="font-bold text-fju-blue">'+u.usage_count+' 次</span></div>'; }); rh += '</div>'; }
    rh += '</div>';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-calendar-check mr-1 text-fju-yellow"></i>活動統計</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-green">'+(data.activitySummary?.approved||0)+'</div><div class="text-xs text-gray-400">已核准</div></div><div><div class="text-lg font-bold text-fju-yellow">'+(data.activitySummary?.pending||0)+'</div><div class="text-xs text-gray-400">待審核</div></div><div><div class="text-lg font-bold text-fju-red">'+(data.activitySummary?.rejected||0)+'</div><div class="text-xs text-gray-400">已拒絕</div></div></div></div>';
    rh += '</div>';
    rh += '<div class="grid md:grid-cols-2 gap-4">';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-wrench mr-1 text-fju-yellow"></i>報修統計</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-yellow">'+(data.repairSummary?.pending||0)+'</div><div class="text-xs text-gray-400">待處理</div></div><div><div class="text-lg font-bold text-fju-blue">'+(data.repairSummary?.in_progress||0)+'</div><div class="text-xs text-gray-400">處理中</div></div><div><div class="text-lg font-bold text-fju-green">'+(data.repairSummary?.completed||0)+'</div><div class="text-xs text-gray-400">已完成</div></div></div></div>';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-handshake mr-1 text-fju-yellow"></i>衝突協調</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-blue">'+(data.conflictSummary?.total||0)+'</div><div class="text-xs text-gray-400">總案件</div></div><div><div class="text-lg font-bold text-fju-green">'+(data.conflictSummary?.resolved||0)+'</div><div class="text-xs text-gray-400">已解決</div></div><div><div class="text-lg font-bold text-fju-red">'+(data.conflictSummary?.failed||0)+'</div><div class="text-xs text-gray-400">失敗</div></div></div></div>';
    rh += '</div>';
    if (data.peakHours?.length) { rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-clock mr-1 text-fju-yellow"></i>熱門預約時段 TOP 5</h5><div class="flex gap-2 flex-wrap">'; data.peakHours.forEach(p => { rh += '<span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs font-bold">'+p.hour+':00 <span class="text-gray-400">('+p.cnt+'次)</span></span>'; }); rh += '</div></div>'; }
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-lightbulb mr-1 text-fju-yellow"></i>AI 智慧建議</h5><ul class="text-xs text-gray-600 space-y-1.5">';
    (data.recommendations||[]).forEach(r => { rh += '<li class="flex items-start gap-1.5"><i class="fas fa-check-circle text-fju-green mt-0.5 flex-shrink-0"></i><span>'+r+'</span></li>'; });
    rh += '</ul></div>';
    rh += '<div class="text-xs text-gray-400 text-right">產生時間：'+new Date(data.generatedAt||'').toLocaleString('zh-TW')+'</div></div>';
    el.innerHTML = rh;
  } catch(e) { el.innerHTML = '<div class="text-red-500 text-sm">報告產生失敗</div>'; }
}

// ========== Users Management ==========
async function loadUsers() {
  const res = await fetch(API+'/users').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  let h = '<div class="space-y-4 fade-in"><div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">ID</th><th class="table-cell">姓名</th><th class="table-cell">Email</th><th class="table-cell">角色</th><th class="table-cell">管理員</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  items.forEach(u => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs">'+u.USR_ID+'</td><td class="table-cell font-medium text-fju-blue">'+(u.USR_NAME||'')+'</td><td class="table-cell text-xs text-gray-500">'+(u.USR_EMAIL||'')+'</td><td class="table-cell"><span class="px-2 py-0.5 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(roleNames[u.USR_ROLE]||u.USR_ROLE)+'</span></td><td class="table-cell">'+(u.USR_IS_ADMIN?'<i class="fas fa-shield-alt text-fju-yellow"></i>':'<span class="text-gray-300">-</span>')+'</td><td class="table-cell">'+(u.USR_SUSPENDED?'<span class="status-rejected">停權</span>':'<span class="status-approved">正常</span>')+'</td><td class="table-cell"><div class="flex gap-1">';
    h += '<button onclick="toggleAdmin('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-yellow/20 text-fju-blue" title="切換管理員"><i class="fas fa-shield-alt"></i></button>';
    h += u.USR_SUSPENDED ? '<button onclick="unsuspendUser('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green">解除</button>' : '<button onclick="suspendUser('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">停權</button>';
    h += '</div></td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function toggleAdmin(id) { await fetch(API+'/users/'+id+'/toggle-admin', { method:'PATCH' }); loadUsers(); }
async function suspendUser(id) { const reason = prompt('停權原因：'); if(!reason) return; await fetch(API+'/users/'+id+'/suspend', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({reason}) }); loadUsers(); }
async function unsuspendUser(id) { await fetch(API+'/users/'+id+'/unsuspend', { method:'PATCH' }); loadUsers(); }

// ========== Violations (Epic 9) ==========
async function loadViolations() {
  const [logRes, ptsRes] = await Promise.all([fetch(API+'/violations').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/violations/unit-points').then(r=>r.json()).catch(()=>({data:[]}))])
  const logs = logRes.data || []; const points = ptsRes.data || [];
  const srcL = { 0:'人工', 1:'系統自動', 2:'申復撤銷', 3:'勞動服務銷點' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>單位違規點數一覽</h3><div class="grid md:grid-cols-3 gap-3">';
  points.forEach(p => { const danger = p.UVP_POINT >= 10; h += '<div class="p-3 rounded-fju border '+(danger?'border-fju-red bg-fju-red/5':'border-gray-100')+' flex items-center justify-between"><div><span class="font-medium text-sm">'+(p.UNIT_NAME||'')+'</span>'+(p.UVP_SUSPENDED?'<span class="ml-2 status-rejected">停權中</span>':'')+'</div><span class="text-lg font-black '+(danger?'text-fju-red':'text-fju-blue')+'">'+p.UVP_POINT+' 點</span></div>'; });
  h += '</div></div>';
  if (isAdmin) h += '<div class="flex justify-end"><button onclick="showAddViolationForm()" class="btn-danger text-xs"><i class="fas fa-plus mr-1"></i>新增記點</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">對象</th><th class="table-cell">增減</th><th class="table-cell">原因</th><th class="table-cell">來源</th><th class="table-cell">操作者</th><th class="table-cell">時間</th></tr></thead><tbody>';
  if (logs.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">暫無記錄</td></tr>';
  logs.forEach(l => { const target = l.VPL_TARGET_TYPE === 0 ? (l.USR_NAME||'個人') : (l.UNIT_NAME||'單位'); h += '<tr class="border-t border-gray-50"><td class="table-cell">'+target+'</td><td class="table-cell"><span class="font-bold '+(l.VPL_DELTA>0?'text-fju-red':'text-fju-green')+'">'+((l.VPL_DELTA>0)?'+':'')+l.VPL_DELTA+'</span></td><td class="table-cell text-xs">'+(l.VPL_REASON||'')+'</td><td class="table-cell text-xs">'+(srcL[l.VPL_SOURCE]||'')+'</td><td class="table-cell text-xs">'+(l.ADMIN_NAME||'系統')+'</td><td class="table-cell text-xs text-gray-400">'+(l.VPL_CREATED_AT||'')+'</td></tr>'; });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAddViolationForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>新增記點</h3><div class="space-y-3"><select id="vpl-target" class="input-field"><option value="1">單位</option><option value="0">個人</option></select><input id="vpl-unit-id" type="number" placeholder="單位 ID" class="input-field"><input id="vpl-delta" type="number" placeholder="點數（正數加點，負數扣點）" class="input-field" value="2"><input id="vpl-reason" placeholder="原因 *" class="input-field"><button onclick="submitViolation()" class="w-full btn-danger py-2.5">送出</button></div></div></div>');
}
async function submitViolation() {
  const body = { targetType:parseInt(document.getElementById('vpl-target').value), unitId:parseInt(document.getElementById('vpl-unit-id').value)||null, delta:parseInt(document.getElementById('vpl-delta').value), reason:document.getElementById('vpl-reason').value, source:0, adminId:currentUser?.id||1 };
  await fetch(API+'/violations', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  closeModal(); loadViolations();
}

// ========== Labor Service (Epic 9 銷點) ==========
async function loadLabor() {
  const res = await fetch(API+'/labor').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const sL = { 0:'待審核', 1:'已核准', 2:'已駁回' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>勞動服務銷點說明</h3><p class="text-xs text-gray-600">違規點數可透過完成勞動服務來扣除。提交申請後經管理員審核通過即可銷點。</p></div>';
  h += '<div class="flex justify-end"><button onclick="showLaborForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>申請勞動服務銷點</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">申請人</th><th class="table-cell">服務類型</th><th class="table-cell">服務日期</th><th class="table-cell">時數</th><th class="table-cell">扣除點數</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">暫無申請</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell">'+(i.USR_NAME||'')+'</td><td class="table-cell text-xs">'+(i.LSA_SERVICE_TYPE||'')+'</td><td class="table-cell text-xs">'+(i.LSA_SERVICE_DATE||'')+'</td><td class="table-cell">'+i.LSA_HOURS+' 小時</td><td class="table-cell font-bold text-fju-green">-'+i.LSA_POINTS_TO_DEDUCT+'</td><td class="table-cell"><span class="'+(sC[i.LSA_STATUS]||'')+'">'+((sL[i.LSA_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.LSA_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveLabor('+i.LSA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectLabor('+i.LSA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">駁回</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showLaborForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hands-helping mr-2 text-fju-yellow"></i>申請勞動服務銷點</h3><div class="space-y-3"><select id="lsa-type" class="input-field"><option value="場地清潔">場地清潔</option><option value="器材整理">器材整理</option><option value="活動支援">活動支援</option><option value="行政協助">行政協助</option></select><input id="lsa-date" type="date" class="input-field"><input id="lsa-hours" type="number" placeholder="服務時數" class="input-field" value="2"><input id="lsa-points" type="number" placeholder="申請扣除點數" class="input-field" value="1"><button onclick="submitLabor()" class="w-full btn-yellow py-2.5">送出申請</button></div></div></div>');
}
async function submitLabor() {
  const body = { userId:currentUser?.id||1, serviceType:document.getElementById('lsa-type').value, serviceDate:document.getElementById('lsa-date').value, hours:parseInt(document.getElementById('lsa-hours').value)||2, pointsToDeduct:parseInt(document.getElementById('lsa-points').value)||1 };
  await fetch(API+'/labor', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  toast('銷點申請已送出','success'); closeModal(); loadLabor();
}
async function approveLabor(id) { await fetch(API+'/labor/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('銷點已核准','success'); loadLabor(); }
async function rejectLabor(id) { await fetch(API+'/labor/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); loadLabor(); }

// ========== Conflicts (Epic 4.2) ==========
async function loadConflicts() {
  const res = await fetch(API+'/conflicts').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const sL = { 0:'邀請中', 1:'協調中', 2:'協調成功', 3:'協調失敗', 4:'超時關閉', 5:'邀請被拒' };
  const sC = { 0:'status-pending', 1:'status-info', 2:'status-approved', 3:'status-rejected', 4:'status-rejected', 5:'status-rejected' };
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>衝突協調機制</h3><div class="text-xs text-gray-600 space-y-1"><p>1. 場協大會登記：學期初登記場地需求，由課指組協調</p><p>2. 私下協調：系統偵測衝突後，雙方透過匿名聊天室協商</p><p>3. 聊天室限時 24 小時，一方撤回即解決；紀錄保存半年</p></div></div>';
  if (items.length === 0) h += '<div class="card text-center text-gray-400"><i class="fas fa-handshake text-2xl mb-2"></i><p>目前無衝突協調案件</p></div>';
  else {
    h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">ID</th><th class="table-cell">甲方</th><th class="table-cell">乙方</th><th class="table-cell">場地</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
    items.forEach(i => {
      h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs">#'+i.CN_ID+'</td><td class="table-cell text-sm">'+(i.A_USER||'-')+' <span class="text-xs text-gray-400">('+(i.A_UNIT||'')+')</span></td><td class="table-cell text-sm">'+(i.B_USER||'-')+' <span class="text-xs text-gray-400">('+(i.B_UNIT||'')+')</span></td><td class="table-cell">'+(i.FAC_NAME||'-')+'</td><td class="table-cell"><span class="'+(sC[i.CN_STATUS]||'')+'">'+((sL[i.CN_STATUS])||'')+'</span></td><td class="table-cell">';
      if (i.CN_STATUS <= 1) {
        h += '<button onclick="openChatRoom('+i.CN_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1"><i class="fas fa-comments mr-1"></i>聊天室</button>';
        h += '<button onclick="resolveConflict('+i.CN_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green">解決</button>';
      }
      h += '</td></tr>';
    });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function resolveConflict(id) { if(!confirm('確定標記為已解決？')) return; await fetch(API+'/conflicts/'+id+'/resolve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({resolvedBy:currentUser?.id||1}) }); toast('衝突已解決','success'); loadConflicts(); }
async function openChatRoom(id) {
  const res = await fetch(API+'/conflicts/'+id).then(r=>r.json()).catch(()=>({data:{},messages:[]}));
  const cn = res.data || {}; const msgs = res.messages || [];
  let mHtml = msgs.map(m => '<div class="p-2 rounded-fju '+(m.CM_SENDER_ROLE===0?'bg-fju-blue/10 text-fju-blue ml-auto':'bg-gray-100 text-gray-700')+' max-w-[80%] text-sm"><div>'+(m.CM_CONTENT||'')+'</div><div class="text-xs text-gray-400 mt-1">'+(m.CM_SENT_AT||'')+'</div></div>').join('');
  if (!mHtml) mHtml = '<div class="text-center text-gray-400 text-sm py-4">尚無對話紀錄，開始協商吧！</div>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-comments mr-2 text-fju-yellow"></i>衝突協調聊天室 #'+id+'</h3><div class="text-xs text-gray-500 mb-3">'+(cn.A_USER||'')+' ('+(cn.A_UNIT||'')+') vs '+(cn.B_USER||'')+' ('+(cn.B_UNIT||'')+') — '+(cn.FAC_NAME||'')+'</div><div id="chat-messages" class="space-y-2 max-h-60 overflow-y-auto mb-3 p-3 bg-fju-bg rounded-fju">'+mHtml+'</div><div class="flex gap-2"><input id="chat-input" type="text" placeholder="輸入訊息..." class="flex-1 input-field" onkeypress="if(event.key===\\'Enter\\')sendChatMsg('+id+')"><button onclick="sendChatMsg('+id+')" class="btn-primary px-4">送出</button></div></div></div>');
}
async function sendChatMsg(id) {
  const input = document.getElementById('chat-input');
  const content = input.value.trim(); if (!content) return;
  input.value = '';
  await fetch(API+'/conflicts/'+id+'/messages', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ senderRole:0, content }) });
  const msgs = document.getElementById('chat-messages');
  msgs.innerHTML += '<div class="p-2 rounded-fju bg-fju-blue/10 text-fju-blue ml-auto max-w-[80%] text-sm">'+content+'</div>';
  msgs.scrollTop = msgs.scrollHeight;
}

// ========== Units (Epic 1.5) ==========
async function loadUnits() {
  const res = await fetch(API+'/units').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const typeL = { 0:'社團', 1:'學生自治組織', 2:'行政單位' };
  let h = '<div class="space-y-4 fade-in"><div class="grid md:grid-cols-3 gap-4">';
  items.forEach(u => {
    h += '<div class="card hover:border-fju-yellow/30 transition-all cursor-pointer" onclick="showUnitDetail('+u.UNIT_ID+')"><div class="flex items-center justify-between mb-2"><h3 class="font-bold text-fju-blue text-sm">'+(u.UNIT_NAME||'')+'</h3><span class="px-2 py-0.5 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(typeL[u.UNIT_TYPE]||'')+'</span></div><div class="text-xs text-gray-500"><i class="fas fa-user mr-1"></i>聯絡人：'+(u.CONTACT_NAME||'-')+'</div><div class="text-xs text-gray-400 mt-1"><i class="fas fa-flag mr-1"></i>違規點數：<span class="font-bold '+(u.UVP_POINT>=10?'text-fju-red':'')+'">'+(u.UVP_POINT!=null?u.UVP_POINT:0)+'</span></div></div>';
  });
  h += '</div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function showUnitDetail(id) {
  const res = await fetch(API+'/units/'+id).then(r=>r.json()).catch(()=>({data:{},members:[]}));
  const unit = res.data||{}; const members = res.members||[];
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-building mr-2 text-fju-yellow"></i>'+(unit.UNIT_NAME||'')+'</h3><div class="space-y-3"><div class="text-sm text-gray-600"><i class="fas fa-user mr-1"></i>聯絡人：'+(unit.CONTACT_NAME||'-')+'</div><h4 class="font-bold text-sm text-fju-blue mt-3">成員列表 ('+members.length+'人)</h4><div class="space-y-1 max-h-48 overflow-y-auto">'+members.map(m => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(m.USR_NAME||'')+'</span><span class="text-xs text-gray-400">'+(m.USR_EMAIL||'')+'</span></div>').join('')+'</div></div></div></div>');
}

// ========== Profile (Epic 1.3) ==========
async function loadProfile() {
  const userId = currentUser?.id || 1;
  const res = await fetch(API+'/users/'+userId+'/profile').then(r=>r.json()).catch(()=>({}));
  const u = res.user || currentUser;
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="card flex items-center gap-6"><div class="w-20 h-20 rounded-full bg-fju-yellow flex items-center justify-center text-4xl shadow-lg">'+(currentUser?.avatar||(u?.USR_NAME||u?.name||'?')[0])+'</div><div><h2 class="text-xl font-bold text-fju-blue">'+(u?.USR_NAME||u?.name||'')+'</h2><p class="text-sm text-gray-500">'+(u?.USR_EMAIL||u?.email||'')+'</p><div class="flex gap-2 mt-2"><span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs font-bold">'+(roleNames[u?.USR_ROLE||u?.role]||'')+'</span>';
  if (u?.USR_PHONE || u?.phone) h += '<span class="px-3 py-1 rounded-fju bg-gray-100 text-gray-600 text-xs"><i class="fas fa-phone mr-1"></i>'+(u?.USR_PHONE||u?.phone)+'</span>';
  h += '</div><button onclick="showAvatarSelection()" class="mt-2 text-xs px-3 py-1 rounded-fju bg-fju-yellow/20 text-fju-blue hover:bg-fju-yellow/30"><i class="fas fa-user-circle mr-1"></i>更換大頭貼</button></div></div>';
  const sections = [
    { title:'場地預約紀錄', icon:'fa-calendar-check', items:res.bookings||[], render:(b) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(b.FAC_NAME||'')+' ('+(b.VB_START_DATETIME||'').slice(0,10)+')</span><span class="text-xs '+((b.VB_STATUS===1)?'text-fju-green':'text-gray-400')+'">'+['待審核','已核准','已拒絕','已取消','已歸還','歸還異常'][b.VB_STATUS||0]+'</span></div>' },
    { title:'器材借用紀錄', icon:'fa-tools', items:res.loans||[], render:(l) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(l.EL_PURPOSE||'')+' ('+(l.EL_BORROW_START||'').slice(0,10)+')</span><span class="text-xs text-gray-400">'+['待領取','借用中','部分領取','部分歸還','已歸還','歸還異常'][l.EL_STATUS||0]+'</span></div>' },
    { title:'申訴紀錄', icon:'fa-gavel', items:res.appeals||[], render:(a) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(a.AC_REASON||'').substring(0,30)+'</span><span class="text-xs '+((a.AC_STATUS===1)?'text-fju-green':'text-gray-400')+'">'+['待審核','已核准','已駁回'][a.AC_STATUS||0]+'</span></div>' },
    { title:'報修紀錄', icon:'fa-wrench', items:res.repairs||[], render:(r) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(r.FAC_NAME||'')+': '+(r.RR_DESCRIPTION||'').substring(0,30)+'</span><span class="text-xs text-gray-400">'+['待處理','處理中','已完成'][r.RR_STATUS||0]+'</span></div>' },
  ];
  h += '<div class="grid md:grid-cols-2 gap-4">';
  sections.forEach(s => {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas '+s.icon+' mr-2 text-fju-yellow"></i>'+s.title+'</h3>';
    if (s.items.length === 0) h += '<p class="text-sm text-gray-400">暫無紀錄</p>';
    else h += '<div class="space-y-1">'+s.items.map(s.render).join('')+'</div>';
    h += '</div>';
  });
  h += '</div>';
  if (res.violations?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>違規點數紀錄</h3><div class="space-y-1">';
    res.violations.forEach(v => { h += '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span class="text-gray-600">'+(v.VPL_REASON||'')+'</span><span class="font-bold '+((v.VPL_DELTA>0)?'text-fju-red':'text-fju-green')+'">'+((v.VPL_DELTA>0)?'+':'')+v.VPL_DELTA+'</span></div>'; });
    h += '</div></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}

// ========== Init ==========
(function init() {
  const saved = localStorage.getItem('fjuUser');
  if (saved) { try { currentUser = JSON.parse(saved); enterApp(); } catch(e) { doLogout(); } }
})();
<\/script>
</body>
</html>`}const v=new I;v.use("/api/*",vs());v.route("/api/auth",Fe);v.route("/api/facilities",H);v.route("/api/equipment",M);v.route("/api/activities",W);v.route("/api/venue-bookings",J);v.route("/api/repairs",Ne);v.route("/api/appeals",ue);v.route("/api/announcements",Se);v.route("/api/stats",ze);v.route("/api/units",te);v.route("/api/violations",Ke);v.route("/api/conflicts",se);v.route("/api/ai",qe);v.route("/api/faq",Lt);v.route("/api/users",Y);v.route("/api/labor",ke);v.route("/api/venue-coordination",pe);v.get("/api/health",e=>e.json({status:"ok",version:"3.2.0",framework:"Hono + Cloudflare Pages",tables:27}));v.get("*",e=>{const t=Is();return e.html(t)});const ft=new I,xs=Object.assign({"/src/index.tsx":v});let Mt=!1;for(const[,e]of Object.entries(xs))e&&(ft.route("/",e),ft.notFound(e.notFoundHandler),Mt=!0);if(!Mt)throw new Error("Can't import modules from ['/src/index.tsx','/app/server.ts']");export{ft as default};
