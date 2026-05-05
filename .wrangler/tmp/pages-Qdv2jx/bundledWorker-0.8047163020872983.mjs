var __defProp = Object.defineProperty;
var __name = (target, value) => __defProp(target, "name", { value, configurable: true });

// ../.wrangler/tmp/bundle-PaGw5N/checked-fetch.js
var urls = /* @__PURE__ */ new Set();
function checkURL(request, init) {
  const url = request instanceof URL ? request : new URL(
    (typeof request === "string" ? new Request(request, init) : request).url
  );
  if (url.port && url.port !== "443" && url.protocol === "https:") {
    if (!urls.has(url.toString())) {
      urls.add(url.toString());
      console.warn(
        `WARNING: known issue with \`fetch()\` requests to custom HTTPS ports in published Workers:
 - ${url.toString()} - the custom port will be ignored when the Worker is published using the \`wrangler deploy\` command.
`
      );
    }
  }
}
__name(checkURL, "checkURL");
globalThis.fetch = new Proxy(globalThis.fetch, {
  apply(target, thisArg, argArray) {
    const [request, init] = argArray;
    checkURL(request, init);
    return Reflect.apply(target, thisArg, argArray);
  }
});

// ../.wrangler/tmp/bundle-PaGw5N/strip-cf-connecting-ip-header.js
function stripCfConnectingIPHeader(input, init) {
  const request = new Request(input, init);
  request.headers.delete("CF-Connecting-IP");
  return request;
}
__name(stripCfConnectingIPHeader, "stripCfConnectingIPHeader");
globalThis.fetch = new Proxy(globalThis.fetch, {
  apply(target, thisArg, argArray) {
    return Reflect.apply(target, thisArg, [
      stripCfConnectingIPHeader.apply(null, argArray)
    ]);
  }
});

// _worker.js
var Vt = Object.defineProperty;
var ot = /* @__PURE__ */ __name((e) => {
  throw TypeError(e);
}, "ot");
var Pt = /* @__PURE__ */ __name((e, t, s) => t in e ? Vt(e, t, { enumerable: true, configurable: true, writable: true, value: s }) : e[t] = s, "Pt");
var E = /* @__PURE__ */ __name((e, t, s) => Pt(e, typeof t != "symbol" ? t + "" : t, s), "E");
var et = /* @__PURE__ */ __name((e, t, s) => t.has(e) || ot("Cannot " + s), "et");
var l = /* @__PURE__ */ __name((e, t, s) => (et(e, t, "read from private field"), s ? s.call(e) : t.get(e)), "l");
var m = /* @__PURE__ */ __name((e, t, s) => t.has(e) ? ot("Cannot add the same private member more than once") : t instanceof WeakSet ? t.add(e) : t.set(e, s), "m");
var h = /* @__PURE__ */ __name((e, t, s, a) => (et(e, t, "write to private field"), a ? a.call(e, s) : t.set(e, s), s), "h");
var A = /* @__PURE__ */ __name((e, t, s) => (et(e, t, "access private method"), s), "A");
var lt = /* @__PURE__ */ __name((e, t, s, a) => ({ set _(n) {
  h(e, t, n, s);
}, get _() {
  return l(e, t, a);
} }), "lt");
var ct = /* @__PURE__ */ __name((e, t, s) => (a, n) => {
  let i = -1;
  return r(0);
  async function r(c) {
    if (c <= i)
      throw new Error("next() called multiple times");
    i = c;
    let o, d = false, u;
    if (e[c] ? (u = e[c][0][0], a.req.routeIndex = c) : u = c === e.length && n || void 0, u)
      try {
        o = await u(a, () => r(c + 1));
      } catch (p) {
        if (p instanceof Error && t)
          a.error = p, o = await t(p, a), d = true;
        else
          throw p;
      }
    else
      a.finalized === false && s && (o = await s(a));
    return o && (a.finalized === false || d) && (a.res = o), a;
  }
  __name(r, "r");
}, "ct");
var Ft = Symbol();
var qt = /* @__PURE__ */ __name(async (e, t = /* @__PURE__ */ Object.create(null)) => {
  const { all: s = false, dot: a = false } = t, i = (e instanceof xt ? e.raw.headers : e.headers).get("Content-Type");
  return i != null && i.startsWith("multipart/form-data") || i != null && i.startsWith("application/x-www-form-urlencoded") ? kt(e, { all: s, dot: a }) : {};
}, "qt");
async function kt(e, t) {
  const s = await e.formData();
  return s ? Ht(s, t) : {};
}
__name(kt, "kt");
function Ht(e, t) {
  const s = /* @__PURE__ */ Object.create(null);
  return e.forEach((a, n) => {
    t.all || n.endsWith("[]") ? Wt(s, n, a) : s[n] = a;
  }), t.dot && Object.entries(s).forEach(([a, n]) => {
    a.includes(".") && (Jt(s, a, n), delete s[a]);
  }), s;
}
__name(Ht, "Ht");
var Wt = /* @__PURE__ */ __name((e, t, s) => {
  e[t] !== void 0 ? Array.isArray(e[t]) ? e[t].push(s) : e[t] = [e[t], s] : t.endsWith("[]") ? e[t] = [s] : e[t] = s;
}, "Wt");
var Jt = /* @__PURE__ */ __name((e, t, s) => {
  if (/(?:^|\.)__proto__\./.test(t))
    return;
  let a = e;
  const n = t.split(".");
  n.forEach((i, r) => {
    r === n.length - 1 ? a[i] = s : ((!a[i] || typeof a[i] != "object" || Array.isArray(a[i]) || a[i] instanceof File) && (a[i] = /* @__PURE__ */ Object.create(null)), a = a[i]);
  });
}, "Jt");
var _t = /* @__PURE__ */ __name((e) => {
  const t = e.split("/");
  return t[0] === "" && t.shift(), t;
}, "_t");
var Yt = /* @__PURE__ */ __name((e) => {
  const { groups: t, path: s } = Qt(e), a = _t(s);
  return $t(a, t);
}, "Yt");
var Qt = /* @__PURE__ */ __name((e) => {
  const t = [];
  return e = e.replace(/\{[^}]+\}/g, (s, a) => {
    const n = `@${a}`;
    return t.push([n, s]), n;
  }), { groups: t, path: e };
}, "Qt");
var $t = /* @__PURE__ */ __name((e, t) => {
  for (let s = t.length - 1; s >= 0; s--) {
    const [a] = t[s];
    for (let n = e.length - 1; n >= 0; n--)
      if (e[n].includes(a)) {
        e[n] = e[n].replace(a, t[s][1]);
        break;
      }
  }
  return e;
}, "$t");
var He = {};
var Gt = /* @__PURE__ */ __name((e, t) => {
  if (e === "*")
    return "*";
  const s = e.match(/^\:([^\{\}]+)(?:\{(.+)\})?$/);
  if (s) {
    const a = `${e}#${t}`;
    return He[a] || (s[2] ? He[a] = t && t[0] !== ":" && t[0] !== "*" ? [a, s[1], new RegExp(`^${s[2]}(?=/${t})`)] : [e, s[1], new RegExp(`^${s[2]}$`)] : He[a] = [e, s[1], true]), He[a];
  }
  return null;
}, "Gt");
var nt = /* @__PURE__ */ __name((e, t) => {
  try {
    return t(e);
  } catch {
    return e.replace(/(?:%[0-9A-Fa-f]{2})+/g, (s) => {
      try {
        return t(s);
      } catch {
        return s;
      }
    });
  }
}, "nt");
var zt = /* @__PURE__ */ __name((e) => nt(e, decodeURI), "zt");
var vt = /* @__PURE__ */ __name((e) => {
  const t = e.url, s = t.indexOf("/", t.indexOf(":") + 4);
  let a = s;
  for (; a < t.length; a++) {
    const n = t.charCodeAt(a);
    if (n === 37) {
      const i = t.indexOf("?", a), r = t.indexOf("#", a), c = i === -1 ? r === -1 ? void 0 : r : r === -1 ? i : Math.min(i, r), o = t.slice(s, c);
      return zt(o.includes("%25") ? o.replace(/%25/g, "%2525") : o);
    } else if (n === 63 || n === 35)
      break;
  }
  return t.slice(s, a);
}, "vt");
var Kt = /* @__PURE__ */ __name((e) => {
  const t = vt(e);
  return t.length > 1 && t.at(-1) === "/" ? t.slice(0, -1) : t;
}, "Kt");
var be = /* @__PURE__ */ __name((e, t, ...s) => (s.length && (t = be(t, ...s)), `${(e == null ? void 0 : e[0]) === "/" ? "" : "/"}${e}${t === "/" ? "" : `${(e == null ? void 0 : e.at(-1)) === "/" ? "" : "/"}${(t == null ? void 0 : t[0]) === "/" ? t.slice(1) : t}`}`), "be");
var yt = /* @__PURE__ */ __name((e) => {
  if (e.charCodeAt(e.length - 1) !== 63 || !e.includes(":"))
    return null;
  const t = e.split("/"), s = [];
  let a = "";
  return t.forEach((n) => {
    if (n !== "" && !/\:/.test(n))
      a += "/" + n;
    else if (/\:/.test(n))
      if (/\?/.test(n)) {
        s.length === 0 && a === "" ? s.push("/") : s.push(a);
        const i = n.replace("?", "");
        a += "/" + i, s.push(a);
      } else
        a += "/" + n;
  }), s.filter((n, i, r) => r.indexOf(n) === i);
}, "yt");
var tt = /* @__PURE__ */ __name((e) => /[%+]/.test(e) ? (e.indexOf("+") !== -1 && (e = e.replace(/\+/g, " ")), e.indexOf("%") !== -1 ? nt(e, It) : e) : e, "tt");
var Tt = /* @__PURE__ */ __name((e, t, s) => {
  let a;
  if (!s && t && !/[%+]/.test(t)) {
    let r = e.indexOf("?", 8);
    if (r === -1)
      return;
    for (e.startsWith(t, r + 1) || (r = e.indexOf(`&${t}`, r + 1)); r !== -1; ) {
      const c = e.charCodeAt(r + t.length + 1);
      if (c === 61) {
        const o = r + t.length + 2, d = e.indexOf("&", o);
        return tt(e.slice(o, d === -1 ? void 0 : d));
      } else if (c == 38 || isNaN(c))
        return "";
      r = e.indexOf(`&${t}`, r + 1);
    }
    if (a = /[%+]/.test(e), !a)
      return;
  }
  const n = {};
  a ?? (a = /[%+]/.test(e));
  let i = e.indexOf("?", 8);
  for (; i !== -1; ) {
    const r = e.indexOf("&", i + 1);
    let c = e.indexOf("=", i);
    c > r && r !== -1 && (c = -1);
    let o = e.slice(i + 1, c === -1 ? r === -1 ? void 0 : r : c);
    if (a && (o = tt(o)), i = r, o === "")
      continue;
    let d;
    c === -1 ? d = "" : (d = e.slice(c + 1, r === -1 ? void 0 : r), a && (d = tt(d))), s ? (n[o] && Array.isArray(n[o]) || (n[o] = []), n[o].push(d)) : n[o] ?? (n[o] = d);
  }
  return t ? n[t] : n;
}, "Tt");
var Xt = Tt;
var Zt = /* @__PURE__ */ __name((e, t) => Tt(e, t, true), "Zt");
var It = decodeURIComponent;
var dt = /* @__PURE__ */ __name((e) => nt(e, It), "dt");
var ge;
var j;
var k;
var Nt;
var St;
var at;
var G;
var ht;
var xt = (ht = /* @__PURE__ */ __name(class {
  constructor(e, t = "/", s = [[]]) {
    m(this, k);
    E(this, "raw");
    m(this, ge);
    m(this, j);
    E(this, "routeIndex", 0);
    E(this, "path");
    E(this, "bodyCache", {});
    m(this, G, (e2) => {
      const { bodyCache: t2, raw: s2 } = this, a = t2[e2];
      if (a)
        return a;
      const n = Object.keys(t2)[0];
      return n ? t2[n].then((i) => (n === "json" && (i = JSON.stringify(i)), new Response(i)[e2]())) : t2[e2] = s2[e2]();
    });
    this.raw = e, this.path = t, h(this, j, s), h(this, ge, {});
  }
  param(e) {
    return e ? A(this, k, Nt).call(this, e) : A(this, k, St).call(this);
  }
  query(e) {
    return Xt(this.url, e);
  }
  queries(e) {
    return Zt(this.url, e);
  }
  header(e) {
    if (e)
      return this.raw.headers.get(e) ?? void 0;
    const t = {};
    return this.raw.headers.forEach((s, a) => {
      t[a] = s;
    }), t;
  }
  async parseBody(e) {
    return qt(this, e);
  }
  json() {
    return l(this, G).call(this, "text").then((e) => JSON.parse(e));
  }
  text() {
    return l(this, G).call(this, "text");
  }
  arrayBuffer() {
    return l(this, G).call(this, "arrayBuffer");
  }
  blob() {
    return l(this, G).call(this, "blob");
  }
  formData() {
    return l(this, G).call(this, "formData");
  }
  addValidatedData(e, t) {
    l(this, ge)[e] = t;
  }
  valid(e) {
    return l(this, ge)[e];
  }
  get url() {
    return this.raw.url;
  }
  get method() {
    return this.raw.method;
  }
  get [Ft]() {
    return l(this, j);
  }
  get matchedRoutes() {
    return l(this, j)[0].map(([[, e]]) => e);
  }
  get routePath() {
    return l(this, j)[0].map(([[, e]]) => e)[this.routeIndex].path;
  }
}, "ht"), ge = /* @__PURE__ */ new WeakMap(), j = /* @__PURE__ */ new WeakMap(), k = /* @__PURE__ */ new WeakSet(), Nt = /* @__PURE__ */ __name(function(e) {
  const t = l(this, j)[0][this.routeIndex][1][e], s = A(this, k, at).call(this, t);
  return s && /\%/.test(s) ? dt(s) : s;
}, "Nt"), St = /* @__PURE__ */ __name(function() {
  const e = {}, t = Object.keys(l(this, j)[0][this.routeIndex][1]);
  for (const s of t) {
    const a = A(this, k, at).call(this, l(this, j)[0][this.routeIndex][1][s]);
    a !== void 0 && (e[s] = /\%/.test(a) ? dt(a) : a);
  }
  return e;
}, "St"), at = /* @__PURE__ */ __name(function(e) {
  return l(this, j)[1] ? l(this, j)[1][e] : e;
}, "at"), G = /* @__PURE__ */ new WeakMap(), ht);
var es = { Stringify: 1 };
var Rt = /* @__PURE__ */ __name(async (e, t, s, a, n) => {
  typeof e == "object" && !(e instanceof String) && (e instanceof Promise || (e = e.toString()), e instanceof Promise && (e = await e));
  const i = e.callbacks;
  return i != null && i.length ? (n ? n[0] += e : n = [e], Promise.all(i.map((c) => c({ phase: t, buffer: n, context: a }))).then((c) => Promise.all(c.filter(Boolean).map((o) => Rt(o, t, false, a, n))).then(() => n[0]))) : Promise.resolve(e);
}, "Rt");
var ts = "text/plain; charset=UTF-8";
var st = /* @__PURE__ */ __name((e, t) => ({ "Content-Type": e, ...t }), "st");
var De = /* @__PURE__ */ __name((e, t) => new Response(e, t), "De");
var Oe;
var Le;
var V;
var _e;
var P;
var S;
var Me;
var ve;
var ye;
var ie;
var Be;
var Ve;
var z;
var me;
var Et;
var ss = (Et = /* @__PURE__ */ __name(class {
  constructor(e, t) {
    m(this, z);
    m(this, Oe);
    m(this, Le);
    E(this, "env", {});
    m(this, V);
    E(this, "finalized", false);
    E(this, "error");
    m(this, _e);
    m(this, P);
    m(this, S);
    m(this, Me);
    m(this, ve);
    m(this, ye);
    m(this, ie);
    m(this, Be);
    m(this, Ve);
    E(this, "render", (...e2) => (l(this, ve) ?? h(this, ve, (t2) => this.html(t2)), l(this, ve).call(this, ...e2)));
    E(this, "setLayout", (e2) => h(this, Me, e2));
    E(this, "getLayout", () => l(this, Me));
    E(this, "setRenderer", (e2) => {
      h(this, ve, e2);
    });
    E(this, "header", (e2, t2, s) => {
      this.finalized && h(this, S, De(l(this, S).body, l(this, S)));
      const a = l(this, S) ? l(this, S).headers : l(this, ie) ?? h(this, ie, new Headers());
      t2 === void 0 ? a.delete(e2) : s != null && s.append ? a.append(e2, t2) : a.set(e2, t2);
    });
    E(this, "status", (e2) => {
      h(this, _e, e2);
    });
    E(this, "set", (e2, t2) => {
      l(this, V) ?? h(this, V, /* @__PURE__ */ new Map()), l(this, V).set(e2, t2);
    });
    E(this, "get", (e2) => l(this, V) ? l(this, V).get(e2) : void 0);
    E(this, "newResponse", (...e2) => A(this, z, me).call(this, ...e2));
    E(this, "body", (e2, t2, s) => A(this, z, me).call(this, e2, t2, s));
    E(this, "text", (e2, t2, s) => !l(this, ie) && !l(this, _e) && !t2 && !s && !this.finalized ? new Response(e2) : A(this, z, me).call(this, e2, t2, st(ts, s)));
    E(this, "json", (e2, t2, s) => A(this, z, me).call(this, JSON.stringify(e2), t2, st("application/json", s)));
    E(this, "html", (e2, t2, s) => {
      const a = /* @__PURE__ */ __name((n) => A(this, z, me).call(this, n, t2, st("text/html; charset=UTF-8", s)), "a");
      return typeof e2 == "object" ? Rt(e2, es.Stringify, false, {}).then(a) : a(e2);
    });
    E(this, "redirect", (e2, t2) => {
      const s = String(e2);
      return this.header("Location", /[^\x00-\xFF]/.test(s) ? encodeURI(s) : s), this.newResponse(null, t2 ?? 302);
    });
    E(this, "notFound", () => (l(this, ye) ?? h(this, ye, () => De()), l(this, ye).call(this, this)));
    h(this, Oe, e), t && (h(this, P, t.executionCtx), this.env = t.env, h(this, ye, t.notFoundHandler), h(this, Ve, t.path), h(this, Be, t.matchResult));
  }
  get req() {
    return l(this, Le) ?? h(this, Le, new xt(l(this, Oe), l(this, Ve), l(this, Be))), l(this, Le);
  }
  get event() {
    if (l(this, P) && "respondWith" in l(this, P))
      return l(this, P);
    throw Error("This context has no FetchEvent");
  }
  get executionCtx() {
    if (l(this, P))
      return l(this, P);
    throw Error("This context has no ExecutionContext");
  }
  get res() {
    return l(this, S) || h(this, S, De(null, { headers: l(this, ie) ?? h(this, ie, new Headers()) }));
  }
  set res(e) {
    if (l(this, S) && e) {
      e = De(e.body, e);
      for (const [t, s] of l(this, S).headers.entries())
        if (t !== "content-type")
          if (t === "set-cookie") {
            const a = l(this, S).headers.getSetCookie();
            e.headers.delete("set-cookie");
            for (const n of a)
              e.headers.append("set-cookie", n);
          } else
            e.headers.set(t, s);
    }
    h(this, S, e), this.finalized = true;
  }
  get var() {
    return l(this, V) ? Object.fromEntries(l(this, V)) : {};
  }
}, "Et"), Oe = /* @__PURE__ */ new WeakMap(), Le = /* @__PURE__ */ new WeakMap(), V = /* @__PURE__ */ new WeakMap(), _e = /* @__PURE__ */ new WeakMap(), P = /* @__PURE__ */ new WeakMap(), S = /* @__PURE__ */ new WeakMap(), Me = /* @__PURE__ */ new WeakMap(), ve = /* @__PURE__ */ new WeakMap(), ye = /* @__PURE__ */ new WeakMap(), ie = /* @__PURE__ */ new WeakMap(), Be = /* @__PURE__ */ new WeakMap(), Ve = /* @__PURE__ */ new WeakMap(), z = /* @__PURE__ */ new WeakSet(), me = /* @__PURE__ */ __name(function(e, t, s) {
  const a = l(this, S) ? new Headers(l(this, S).headers) : l(this, ie) ?? new Headers();
  if (typeof t == "object" && "headers" in t) {
    const i = t.headers instanceof Headers ? t.headers : new Headers(t.headers);
    for (const [r, c] of i)
      r.toLowerCase() === "set-cookie" ? a.append(r, c) : a.set(r, c);
  }
  if (s)
    for (const [i, r] of Object.entries(s))
      if (typeof r == "string")
        a.set(i, r);
      else {
        a.delete(i);
        for (const c of r)
          a.append(i, c);
      }
  const n = typeof t == "number" ? t : (t == null ? void 0 : t.status) ?? l(this, _e);
  return De(e, { status: n, headers: a });
}, "me"), Et);
var y = "ALL";
var as = "all";
var ns = ["get", "post", "put", "delete", "options", "patch"];
var Ut = "Can not add a route since the matcher is already built.";
var Dt = /* @__PURE__ */ __name(class extends Error {
}, "Dt");
var is = "__COMPOSED_HANDLER";
var rs = /* @__PURE__ */ __name((e) => e.text("404 Not Found", 404), "rs");
var ut = /* @__PURE__ */ __name((e, t) => {
  if ("getResponse" in e) {
    const s = e.getResponse();
    return t.newResponse(s.body, s);
  }
  return console.error(e), t.text("Internal Server Error", 500);
}, "ut");
var C;
var T;
var wt;
var O;
var ae;
var We;
var Je;
var Te;
var os = (Te = /* @__PURE__ */ __name(class {
  constructor(t = {}) {
    m(this, T);
    E(this, "get");
    E(this, "post");
    E(this, "put");
    E(this, "delete");
    E(this, "options");
    E(this, "patch");
    E(this, "all");
    E(this, "on");
    E(this, "use");
    E(this, "router");
    E(this, "getPath");
    E(this, "_basePath", "/");
    m(this, C, "/");
    E(this, "routes", []);
    m(this, O, rs);
    E(this, "errorHandler", ut);
    E(this, "onError", (t2) => (this.errorHandler = t2, this));
    E(this, "notFound", (t2) => (h(this, O, t2), this));
    E(this, "fetch", (t2, ...s) => A(this, T, Je).call(this, t2, s[1], s[0], t2.method));
    E(this, "request", (t2, s, a2, n2) => t2 instanceof Request ? this.fetch(s ? new Request(t2, s) : t2, a2, n2) : (t2 = t2.toString(), this.fetch(new Request(/^https?:\/\//.test(t2) ? t2 : `http://localhost${be("/", t2)}`, s), a2, n2)));
    E(this, "fire", () => {
      addEventListener("fetch", (t2) => {
        t2.respondWith(A(this, T, Je).call(this, t2.request, t2, void 0, t2.request.method));
      });
    });
    [...ns, as].forEach((i) => {
      this[i] = (r, ...c) => (typeof r == "string" ? h(this, C, r) : A(this, T, ae).call(this, i, l(this, C), r), c.forEach((o) => {
        A(this, T, ae).call(this, i, l(this, C), o);
      }), this);
    }), this.on = (i, r, ...c) => {
      for (const o of [r].flat()) {
        h(this, C, o);
        for (const d of [i].flat())
          c.map((u) => {
            A(this, T, ae).call(this, d.toUpperCase(), l(this, C), u);
          });
      }
      return this;
    }, this.use = (i, ...r) => (typeof i == "string" ? h(this, C, i) : (h(this, C, "*"), r.unshift(i)), r.forEach((c) => {
      A(this, T, ae).call(this, y, l(this, C), c);
    }), this);
    const { strict: a, ...n } = t;
    Object.assign(this, n), this.getPath = a ?? true ? t.getPath ?? vt : Kt;
  }
  route(t, s) {
    const a = this.basePath(t);
    return s.routes.map((n) => {
      var r;
      let i;
      s.errorHandler === ut ? i = n.handler : (i = /* @__PURE__ */ __name(async (c, o) => (await ct([], s.errorHandler)(c, () => n.handler(c, o))).res, "i"), i[is] = n.handler), A(r = a, T, ae).call(r, n.method, n.path, i);
    }), this;
  }
  basePath(t) {
    const s = A(this, T, wt).call(this);
    return s._basePath = be(this._basePath, t), s;
  }
  mount(t, s, a) {
    let n, i;
    a && (typeof a == "function" ? i = a : (i = a.optionHandler, a.replaceRequest === false ? n = /* @__PURE__ */ __name((o) => o, "n") : n = a.replaceRequest));
    const r = i ? (o) => {
      const d = i(o);
      return Array.isArray(d) ? d : [d];
    } : (o) => {
      let d;
      try {
        d = o.executionCtx;
      } catch {
      }
      return [o.env, d];
    };
    n || (n = (() => {
      const o = be(this._basePath, t), d = o === "/" ? 0 : o.length;
      return (u) => {
        const p = new URL(u.url);
        return p.pathname = p.pathname.slice(d) || "/", new Request(p, u);
      };
    })());
    const c = /* @__PURE__ */ __name(async (o, d) => {
      const u = await s(n(o.req.raw), ...r(o));
      if (u)
        return u;
      await d();
    }, "c");
    return A(this, T, ae).call(this, y, be(t, "*"), c), this;
  }
}, "Te"), C = /* @__PURE__ */ new WeakMap(), T = /* @__PURE__ */ new WeakSet(), wt = /* @__PURE__ */ __name(function() {
  const t = new Te({ router: this.router, getPath: this.getPath });
  return t.errorHandler = this.errorHandler, h(t, O, l(this, O)), t.routes = this.routes, t;
}, "wt"), O = /* @__PURE__ */ new WeakMap(), ae = /* @__PURE__ */ __name(function(t, s, a) {
  t = t.toUpperCase(), s = be(this._basePath, s);
  const n = { basePath: this._basePath, path: s, method: t, handler: a };
  this.router.add(t, s, [a, n]), this.routes.push(n);
}, "ae"), We = /* @__PURE__ */ __name(function(t, s) {
  if (t instanceof Error)
    return this.errorHandler(t, s);
  throw t;
}, "We"), Je = /* @__PURE__ */ __name(function(t, s, a, n) {
  if (n === "HEAD")
    return (async () => new Response(null, await A(this, T, Je).call(this, t, s, a, "GET")))();
  const i = this.getPath(t, { env: a }), r = this.router.match(n, i), c = new ss(t, { path: i, matchResult: r, env: a, executionCtx: s, notFoundHandler: l(this, O) });
  if (r[0].length === 1) {
    let d;
    try {
      d = r[0][0][0][0](c, async () => {
        c.res = await l(this, O).call(this, c);
      });
    } catch (u) {
      return A(this, T, We).call(this, u, c);
    }
    return d instanceof Promise ? d.then((u) => u || (c.finalized ? c.res : l(this, O).call(this, c))).catch((u) => A(this, T, We).call(this, u, c)) : d ?? l(this, O).call(this, c);
  }
  const o = ct(r[0], this.errorHandler, l(this, O));
  return (async () => {
    try {
      const d = await o(c);
      if (!d.finalized)
        throw new Error("Context is not finalized. Did you forget to return a Response object or `await next()`?");
      return d.res;
    } catch (d) {
      return A(this, T, We).call(this, d, c);
    }
  })();
}, "Je"), Te);
var jt = [];
function ls(e, t) {
  const s = this.buildAllMatchers(), a = /* @__PURE__ */ __name((n, i) => {
    const r = s[n] || s[y], c = r[2][i];
    if (c)
      return c;
    const o = i.match(r[0]);
    if (!o)
      return [[], jt];
    const d = o.indexOf("", 1);
    return [r[1][d], o];
  }, "a");
  return this.match = a, a(e, t);
}
__name(ls, "ls");
var Qe = "[^/]+";
var je = ".*";
var Ce = "(?:|/.*)";
var Ae = Symbol();
var cs = new Set(".\\+*[^]$()");
function ds(e, t) {
  return e.length === 1 ? t.length === 1 ? e < t ? -1 : 1 : -1 : t.length === 1 || e === je || e === Ce ? 1 : t === je || t === Ce ? -1 : e === Qe ? 1 : t === Qe ? -1 : e.length === t.length ? e < t ? -1 : 1 : t.length - e.length;
}
__name(ds, "ds");
var re;
var oe;
var L;
var de;
var us = (de = /* @__PURE__ */ __name(class {
  constructor() {
    m(this, re);
    m(this, oe);
    m(this, L, /* @__PURE__ */ Object.create(null));
  }
  insert(t, s, a, n, i) {
    if (t.length === 0) {
      if (l(this, re) !== void 0)
        throw Ae;
      if (i)
        return;
      h(this, re, s);
      return;
    }
    const [r, ...c] = t, o = r === "*" ? c.length === 0 ? ["", "", je] : ["", "", Qe] : r === "/*" ? ["", "", Ce] : r.match(/^\:([^\{\}]+)(?:\{(.+)\})?$/);
    let d;
    if (o) {
      const u = o[1];
      let p = o[2] || Qe;
      if (u && o[2] && (p === ".*" || (p = p.replace(/^\((?!\?:)(?=[^)]+\)$)/, "(?:"), /\((?!\?:)/.test(p))))
        throw Ae;
      if (d = l(this, L)[p], !d) {
        if (Object.keys(l(this, L)).some((f) => f !== je && f !== Ce))
          throw Ae;
        if (i)
          return;
        d = l(this, L)[p] = new de(), u !== "" && h(d, oe, n.varIndex++);
      }
      !i && u !== "" && a.push([u, l(d, oe)]);
    } else if (d = l(this, L)[r], !d) {
      if (Object.keys(l(this, L)).some((u) => u.length > 1 && u !== je && u !== Ce))
        throw Ae;
      if (i)
        return;
      d = l(this, L)[r] = new de();
    }
    d.insert(c, s, a, n, i);
  }
  buildRegExpStr() {
    const s = Object.keys(l(this, L)).sort(ds).map((a) => {
      const n = l(this, L)[a];
      return (typeof l(n, oe) == "number" ? `(${a})@${l(n, oe)}` : cs.has(a) ? `\\${a}` : a) + n.buildRegExpStr();
    });
    return typeof l(this, re) == "number" && s.unshift(`#${l(this, re)}`), s.length === 0 ? "" : s.length === 1 ? s[0] : "(?:" + s.join("|") + ")";
  }
}, "de"), re = /* @__PURE__ */ new WeakMap(), oe = /* @__PURE__ */ new WeakMap(), L = /* @__PURE__ */ new WeakMap(), de);
var $e;
var Pe;
var bt;
var ps = (bt = /* @__PURE__ */ __name(class {
  constructor() {
    m(this, $e, { varIndex: 0 });
    m(this, Pe, new us());
  }
  insert(e, t, s) {
    const a = [], n = [];
    for (let r = 0; ; ) {
      let c = false;
      if (e = e.replace(/\{[^}]+\}/g, (o) => {
        const d = `@\\${r}`;
        return n[r] = [d, o], r++, c = true, d;
      }), !c)
        break;
    }
    const i = e.match(/(?::[^\/]+)|(?:\/\*$)|./g) || [];
    for (let r = n.length - 1; r >= 0; r--) {
      const [c] = n[r];
      for (let o = i.length - 1; o >= 0; o--)
        if (i[o].indexOf(c) !== -1) {
          i[o] = i[o].replace(c, n[r][1]);
          break;
        }
    }
    return l(this, Pe).insert(i, t, a, l(this, $e), s), a;
  }
  buildRegExp() {
    let e = l(this, Pe).buildRegExpStr();
    if (e === "")
      return [/^$/, [], []];
    let t = 0;
    const s = [], a = [];
    return e = e.replace(/#(\d+)|@(\d+)|\.\*\$/g, (n, i, r) => i !== void 0 ? (s[++t] = Number(i), "$()") : (r !== void 0 && (a[Number(r)] = ++t), "")), [new RegExp(`^${e}`), s, a];
  }
}, "bt"), $e = /* @__PURE__ */ new WeakMap(), Pe = /* @__PURE__ */ new WeakMap(), bt);
var fs = [/^$/, [], /* @__PURE__ */ Object.create(null)];
var Ye = /* @__PURE__ */ Object.create(null);
function Ct(e) {
  return Ye[e] ?? (Ye[e] = new RegExp(e === "*" ? "" : `^${e.replace(/\/\*$|([.\\+*[^\]$()])/g, (t, s) => s ? `\\${s}` : "(?:|/.*)")}$`));
}
__name(Ct, "Ct");
function hs() {
  Ye = /* @__PURE__ */ Object.create(null);
}
__name(hs, "hs");
function Es(e) {
  var d;
  const t = new ps(), s = [];
  if (e.length === 0)
    return fs;
  const a = e.map((u) => [!/\*|\/:/.test(u[0]), ...u]).sort(([u, p], [f, g]) => u ? 1 : f ? -1 : p.length - g.length), n = /* @__PURE__ */ Object.create(null);
  for (let u = 0, p = -1, f = a.length; u < f; u++) {
    const [g, _, w] = a[u];
    g ? n[_] = [w.map(([U]) => [U, /* @__PURE__ */ Object.create(null)]), jt] : p++;
    let R;
    try {
      R = t.insert(_, p, g);
    } catch (U) {
      throw U === Ae ? new Dt(_) : U;
    }
    g || (s[p] = w.map(([U, b]) => {
      const D = /* @__PURE__ */ Object.create(null);
      for (b -= 1; b >= 0; b--) {
        const [Q, Xe] = R[b];
        D[Q] = Xe;
      }
      return [U, D];
    }));
  }
  const [i, r, c] = t.buildRegExp();
  for (let u = 0, p = s.length; u < p; u++)
    for (let f = 0, g = s[u].length; f < g; f++) {
      const _ = (d = s[u][f]) == null ? void 0 : d[1];
      if (!_)
        continue;
      const w = Object.keys(_);
      for (let R = 0, U = w.length; R < U; R++)
        _[w[R]] = c[_[w[R]]];
    }
  const o = [];
  for (const u in r)
    o[u] = s[r[u]];
  return [i, o, n];
}
__name(Es, "Es");
function Ee(e, t) {
  if (e) {
    for (const s of Object.keys(e).sort((a, n) => n.length - a.length))
      if (Ct(s).test(t))
        return [...e[s]];
  }
}
__name(Ee, "Ee");
var K;
var X;
var Ge;
var Ot;
var mt;
var bs = (mt = /* @__PURE__ */ __name(class {
  constructor() {
    m(this, Ge);
    E(this, "name", "RegExpRouter");
    m(this, K);
    m(this, X);
    E(this, "match", ls);
    h(this, K, { [y]: /* @__PURE__ */ Object.create(null) }), h(this, X, { [y]: /* @__PURE__ */ Object.create(null) });
  }
  add(e, t, s) {
    var c;
    const a = l(this, K), n = l(this, X);
    if (!a || !n)
      throw new Error(Ut);
    a[e] || [a, n].forEach((o) => {
      o[e] = /* @__PURE__ */ Object.create(null), Object.keys(o[y]).forEach((d) => {
        o[e][d] = [...o[y][d]];
      });
    }), t === "/*" && (t = "*");
    const i = (t.match(/\/:/g) || []).length;
    if (/\*$/.test(t)) {
      const o = Ct(t);
      e === y ? Object.keys(a).forEach((d) => {
        var u;
        (u = a[d])[t] || (u[t] = Ee(a[d], t) || Ee(a[y], t) || []);
      }) : (c = a[e])[t] || (c[t] = Ee(a[e], t) || Ee(a[y], t) || []), Object.keys(a).forEach((d) => {
        (e === y || e === d) && Object.keys(a[d]).forEach((u) => {
          o.test(u) && a[d][u].push([s, i]);
        });
      }), Object.keys(n).forEach((d) => {
        (e === y || e === d) && Object.keys(n[d]).forEach((u) => o.test(u) && n[d][u].push([s, i]));
      });
      return;
    }
    const r = yt(t) || [t];
    for (let o = 0, d = r.length; o < d; o++) {
      const u = r[o];
      Object.keys(n).forEach((p) => {
        var f;
        (e === y || e === p) && ((f = n[p])[u] || (f[u] = [...Ee(a[p], u) || Ee(a[y], u) || []]), n[p][u].push([s, i - d + o + 1]));
      });
    }
  }
  buildAllMatchers() {
    const e = /* @__PURE__ */ Object.create(null);
    return Object.keys(l(this, X)).concat(Object.keys(l(this, K))).forEach((t) => {
      e[t] || (e[t] = A(this, Ge, Ot).call(this, t));
    }), h(this, K, h(this, X, void 0)), hs(), e;
  }
}, "mt"), K = /* @__PURE__ */ new WeakMap(), X = /* @__PURE__ */ new WeakMap(), Ge = /* @__PURE__ */ new WeakSet(), Ot = /* @__PURE__ */ __name(function(e) {
  const t = [];
  let s = e === y;
  return [l(this, K), l(this, X)].forEach((a) => {
    const n = a[e] ? Object.keys(a[e]).map((i) => [i, a[e][i]]) : [];
    n.length !== 0 ? (s || (s = true), t.push(...n)) : e !== y && t.push(...Object.keys(a[y]).map((i) => [i, a[y][i]]));
  }), s ? Es(t) : null;
}, "Ot"), mt);
var Z;
var F;
var At;
var ms = (At = /* @__PURE__ */ __name(class {
  constructor(e) {
    E(this, "name", "SmartRouter");
    m(this, Z, []);
    m(this, F, []);
    h(this, Z, e.routers);
  }
  add(e, t, s) {
    if (!l(this, F))
      throw new Error(Ut);
    l(this, F).push([e, t, s]);
  }
  match(e, t) {
    if (!l(this, F))
      throw new Error("Fatal error");
    const s = l(this, Z), a = l(this, F), n = s.length;
    let i = 0, r;
    for (; i < n; i++) {
      const c = s[i];
      try {
        for (let o = 0, d = a.length; o < d; o++)
          c.add(...a[o]);
        r = c.match(e, t);
      } catch (o) {
        if (o instanceof Dt)
          continue;
        throw o;
      }
      this.match = c.match.bind(c), h(this, Z, [c]), h(this, F, void 0);
      break;
    }
    if (i === n)
      throw new Error("Fatal error");
    return this.name = `SmartRouter + ${this.activeRouter.name}`, r;
  }
  get activeRouter() {
    if (l(this, F) || l(this, Z).length !== 1)
      throw new Error("No active router has been determined yet.");
    return l(this, Z)[0];
  }
}, "At"), Z = /* @__PURE__ */ new WeakMap(), F = /* @__PURE__ */ new WeakMap(), At);
var we = /* @__PURE__ */ Object.create(null);
var As = /* @__PURE__ */ __name((e) => {
  for (const t in e)
    return true;
  return false;
}, "As");
var ee;
var N;
var le;
var Ie;
var x;
var q;
var ne;
var xe;
var gs = (xe = /* @__PURE__ */ __name(class {
  constructor(t, s, a) {
    m(this, q);
    m(this, ee);
    m(this, N);
    m(this, le);
    m(this, Ie, 0);
    m(this, x, we);
    if (h(this, N, a || /* @__PURE__ */ Object.create(null)), h(this, ee, []), t && s) {
      const n = /* @__PURE__ */ Object.create(null);
      n[t] = { handler: s, possibleKeys: [], score: 0 }, h(this, ee, [n]);
    }
    h(this, le, []);
  }
  insert(t, s, a) {
    h(this, Ie, ++lt(this, Ie)._);
    let n = this;
    const i = Yt(s), r = [];
    for (let c = 0, o = i.length; c < o; c++) {
      const d = i[c], u = i[c + 1], p = Gt(d, u), f = Array.isArray(p) ? p[0] : d;
      if (f in l(n, N)) {
        n = l(n, N)[f], p && r.push(p[1]);
        continue;
      }
      l(n, N)[f] = new xe(), p && (l(n, le).push(p), r.push(p[1])), n = l(n, N)[f];
    }
    return l(n, ee).push({ [t]: { handler: a, possibleKeys: r.filter((c, o, d) => d.indexOf(c) === o), score: l(this, Ie) } }), n;
  }
  search(t, s) {
    var u;
    const a = [];
    h(this, x, we);
    let i = [this];
    const r = _t(s), c = [], o = r.length;
    let d = null;
    for (let p = 0; p < o; p++) {
      const f = r[p], g = p === o - 1, _ = [];
      for (let R = 0, U = i.length; R < U; R++) {
        const b = i[R], D = l(b, N)[f];
        D && (h(D, x, l(b, x)), g ? (l(D, N)["*"] && A(this, q, ne).call(this, a, l(D, N)["*"], t, l(b, x)), A(this, q, ne).call(this, a, D, t, l(b, x))) : _.push(D));
        for (let Q = 0, Xe = l(b, le).length; Q < Xe; Q++) {
          const it = l(b, le)[Q], $ = l(b, x) === we ? {} : { ...l(b, x) };
          if (it === "*") {
            const fe = l(b, N)["*"];
            fe && (A(this, q, ne).call(this, a, fe, t, l(b, x)), h(fe, x, $), _.push(fe));
            continue;
          }
          const [Bt, rt, Re] = it;
          if (!f && !(Re instanceof RegExp))
            continue;
          const B = l(b, N)[Bt];
          if (Re instanceof RegExp) {
            if (d === null) {
              d = new Array(o);
              let he = s[0] === "/" ? 1 : 0;
              for (let Ue = 0; Ue < o; Ue++)
                d[Ue] = he, he += r[Ue].length + 1;
            }
            const fe = s.substring(d[p]), Ze = Re.exec(fe);
            if (Ze) {
              if ($[rt] = Ze[0], A(this, q, ne).call(this, a, B, t, l(b, x), $), As(l(B, N))) {
                h(B, x, $);
                const he = ((u = Ze[0].match(/\//)) == null ? void 0 : u.length) ?? 0;
                (c[he] || (c[he] = [])).push(B);
              }
              continue;
            }
          }
          (Re === true || Re.test(f)) && ($[rt] = f, g ? (A(this, q, ne).call(this, a, B, t, $, l(b, x)), l(B, N)["*"] && A(this, q, ne).call(this, a, l(B, N)["*"], t, $, l(b, x))) : (h(B, x, $), _.push(B)));
        }
      }
      const w = c.shift();
      i = w ? _.concat(w) : _;
    }
    return a.length > 1 && a.sort((p, f) => p.score - f.score), [a.map(({ handler: p, params: f }) => [p, f])];
  }
}, "xe"), ee = /* @__PURE__ */ new WeakMap(), N = /* @__PURE__ */ new WeakMap(), le = /* @__PURE__ */ new WeakMap(), Ie = /* @__PURE__ */ new WeakMap(), x = /* @__PURE__ */ new WeakMap(), q = /* @__PURE__ */ new WeakSet(), ne = /* @__PURE__ */ __name(function(t, s, a, n, i) {
  for (let r = 0, c = l(s, ee).length; r < c; r++) {
    const o = l(s, ee)[r], d = o[a] || o[y], u = {};
    if (d !== void 0 && (d.params = /* @__PURE__ */ Object.create(null), t.push(d), n !== we || i && i !== we))
      for (let p = 0, f = d.possibleKeys.length; p < f; p++) {
        const g = d.possibleKeys[p], _ = u[d.score];
        d.params[g] = i != null && i[g] && !_ ? i[g] : n[g] ?? (i == null ? void 0 : i[g]), u[d.score] = true;
      }
  }
}, "ne"), xe);
var ce;
var gt;
var _s = (gt = /* @__PURE__ */ __name(class {
  constructor() {
    E(this, "name", "TrieRouter");
    m(this, ce);
    h(this, ce, new gs());
  }
  add(e, t, s) {
    const a = yt(t);
    if (a) {
      for (let n = 0, i = a.length; n < i; n++)
        l(this, ce).insert(e, a[n], s);
      return;
    }
    l(this, ce).insert(e, t, s);
  }
  match(e, t) {
    return l(this, ce).search(e, t);
  }
}, "gt"), ce = /* @__PURE__ */ new WeakMap(), gt);
var I = /* @__PURE__ */ __name(class extends os {
  constructor(e = {}) {
    super(e), this.router = e.router ?? new ms({ routers: [new bs(), new _s()] });
  }
}, "I");
var vs = /* @__PURE__ */ __name((e) => {
  const s = { ...{ origin: "*", allowMethods: ["GET", "HEAD", "PUT", "POST", "DELETE", "PATCH"], allowHeaders: [], exposeHeaders: [] }, ...e }, a = ((i) => typeof i == "string" ? i === "*" ? s.credentials ? (r) => r || null : () => i : (r) => i === r ? r : null : typeof i == "function" ? i : (r) => i.includes(r) ? r : null)(s.origin), n = ((i) => typeof i == "function" ? i : Array.isArray(i) ? () => i : () => [])(s.allowMethods);
  return async function(r, c) {
    var u;
    function o(p, f) {
      r.res.headers.set(p, f);
    }
    __name(o, "o");
    const d = await a(r.req.header("origin") || "", r);
    if (d && o("Access-Control-Allow-Origin", d), s.credentials && o("Access-Control-Allow-Credentials", "true"), (u = s.exposeHeaders) != null && u.length && o("Access-Control-Expose-Headers", s.exposeHeaders.join(",")), r.req.method === "OPTIONS") {
      (s.origin !== "*" || s.credentials) && o("Vary", "Origin"), s.maxAge != null && o("Access-Control-Max-Age", s.maxAge.toString());
      const p = await n(r.req.header("origin") || "", r);
      p.length && o("Access-Control-Allow-Methods", p.join(","));
      let f = s.allowHeaders;
      if (!(f != null && f.length)) {
        const g = r.req.header("Access-Control-Request-Headers");
        g && (f = g.split(/\s*,\s*/));
      }
      return f != null && f.length && (o("Access-Control-Allow-Headers", f.join(",")), r.res.headers.append("Vary", "Access-Control-Request-Headers")), r.res.headers.delete("Content-Length"), r.res.headers.delete("Content-Type"), new Response(null, { headers: r.res.headers, status: 204, statusText: "No Content" });
    }
    await c(), (s.origin !== "*" || s.credentials) && r.header("Vary", "Origin", { append: true });
  };
}, "vs");
var Fe = new I();
Fe.post("/login", async (e) => {
  const { email: t, password: s } = await e.req.json();
  if (!t)
    return e.json({ success: false, message: "\u8ACB\u8F38\u5165 Email" }, 400);
  const a = e.env.DB, n = await a.prepare("SELECT * FROM User WHERE USR_EMAIL = ?").bind(t).first();
  if (!n)
    return e.json({ success: false, message: "\u67E5\u7121\u6B64\u5E33\u865F\uFF0C\u8ACB\u78BA\u8A8D Email \u6216\u5EFA\u7ACB\u5E33\u865F" }, 401);
  if (n.USR_SUSPENDED === 1)
    return e.json({ success: false, message: "\u5E33\u865F\u5DF2\u88AB\u505C\u6B0A\uFF0C\u8ACB\u806F\u7E6B\u8AB2\u6307\u7D44\u6216\u900F\u904E\u7533\u5FA9\u6D41\u7A0B\u8655\u7406" }, 403);
  if (n.USR_EXPIRE_DATE && new Date(n.USR_EXPIRE_DATE) < /* @__PURE__ */ new Date())
    return e.json({ success: false, message: `\u5E33\u865F\u4F7F\u7528\u8CC7\u683C\u5DF2\u5230\u671F\uFF08\u5230\u671F\u65E5\uFF1A${n.USR_EXPIRE_DATE}\uFF09\uFF0C\u5982\u6709\u7591\u554F\u8ACB\u6D3D\u8AB2\u6307\u7D44` }, 403);
  const i = JSON.stringify({ id: n.USR_ID, email: n.USR_EMAIL, role: n.USR_ROLE, name: n.USR_NAME, isAdmin: n.USR_IS_ADMIN }), r = new TextEncoder().encode(i), c = Array.from(r).map((u) => String.fromCharCode(u)).join(""), o = btoa(c), { results: d } = await a.prepare("SELECT UM.UM_UNIT_ID, U.UNIT_NAME, U.UNIT_TYPE FROM UnitMember UM LEFT JOIN Unit U ON UM.UM_UNIT_ID = U.UNIT_ID WHERE UM.UM_USR_ID = ? AND UM.UM_ACTIVE = 1 AND U.UNIT_ACTIVE = 1").bind(n.USR_ID).all();
  return e.json({ success: true, message: "\u767B\u5165\u6210\u529F", token: o, user: { id: n.USR_ID, email: n.USR_EMAIL, name: n.USR_NAME, role: n.USR_ROLE, isAdmin: n.USR_IS_ADMIN, phone: n.USR_PHONE, avatar: n.USR_AVATAR, expireDate: n.USR_EXPIRE_DATE, units: d || [] } });
});
Fe.post("/register", async (e) => {
  const { email: t, name: s, phone: a, role: n, password: i } = await e.req.json();
  if (!t || !s || !i)
    return e.json({ success: false, message: "\u8ACB\u586B\u5BEB\u5FC5\u8981\u6B04\u4F4D" }, 400);
  if (i.length < 8)
    return e.json({ success: false, message: "\u5BC6\u78BC\u81F3\u5C11\u9700 8 \u5B57\u5143" }, 400);
  if (!t.endsWith("@mail.fju.edu.tw") && !t.endsWith("@cloud.fju.edu.tw"))
    return e.json({ success: false, message: "\u50C5\u9650\u8F14\u4EC1\u5927\u5B78\u5E2B\u751F\u4F7F\u7528\uFF0C\u8ACB\u4F7F\u7528\u5B78\u6821\u5E33\u865F (@mail.fju.edu.tw) \u8A3B\u518A" }, 400);
  const r = e.env.DB;
  if (await r.prepare("SELECT USR_ID FROM User WHERE USR_EMAIL = ?").bind(t).first())
    return e.json({ success: false, message: "\u6B64 Email \u5DF2\u88AB\u8A3B\u518A\uFF0C\u8ACB\u76F4\u63A5\u767B\u5165" }, 409);
  let o = n || "student";
  ["student", "officer", "professor", "staff"].includes(o) || (o = "student"), o === "student" && t.match(/^s\d{7}@/);
  const u = ["professor", "staff"].includes(o) ? null : "2027-07-31", p = await r.prepare("INSERT INTO User (USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_PASSWORD_HASH, USR_EXPIRE_DATE) VALUES (?, ?, ?, ?, ?, ?)").bind(t, s, a || null, o, "pbkdf2_" + i, u).run();
  return e.json({ success: true, message: "\u5E33\u865F\u5EFA\u7ACB\u6210\u529F\uFF01\u8ACB\u4F7F\u7528\u5B78\u6821\u5E33\u865F\u767B\u5165\u7CFB\u7D71", userId: p.meta.last_row_id }, 201);
});
Fe.post("/forgot-password", async (e) => {
  const { email: t } = await e.req.json();
  if (!t)
    return e.json({ success: false, message: "\u8ACB\u8F38\u5165 Email" }, 400);
  const a = await e.env.DB.prepare("SELECT USR_EMAIL, USR_NAME FROM User WHERE USR_EMAIL = ?").bind(t).first();
  return a ? e.json({ success: true, message: `\u5BC6\u78BC\u91CD\u8A2D\u9023\u7D50\u5DF2\u5BC4\u9001\u81F3 ${a.USR_EMAIL}\uFF0C\u8ACB\u81F3\u4FE1\u7BB1\u67E5\u6536` }) : e.json({ success: false, message: "\u67E5\u7121\u6B64 Email\uFF0C\u8ACB\u78BA\u8A8D\u5F8C\u91CD\u8A66\u6216\u5EFA\u7ACB\u65B0\u5E33\u865F" }, 404);
});
Fe.post("/reset-password", async (e) => {
  const { email: t, newPassword: s } = await e.req.json();
  return !t || !s ? e.json({ success: false, message: "\u8ACB\u586B\u5BEB\u5FC5\u8981\u6B04\u4F4D" }, 400) : s.length < 8 ? e.json({ success: false, message: "\u5BC6\u78BC\u81F3\u5C11\u9700 8 \u5B57\u5143" }, 400) : (await e.env.DB.prepare("UPDATE User SET USR_PASSWORD_HASH = ? WHERE USR_EMAIL = ?").bind("pbkdf2_" + s, t).run(), e.json({ success: true, message: "\u5BC6\u78BC\u5DF2\u91CD\u8A2D\uFF0C\u8ACB\u4F7F\u7528\u65B0\u5BC6\u78BC\u767B\u5165" }));
});
var H = new I();
H.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("q") || "";
  let a = "SELECT * FROM Facility WHERE FAC_ACTIVE = 1";
  const n = [];
  s && (a += " AND (FAC_NAME LIKE ? OR FAC_BUILDING LIKE ?)", n.push(`%${s}%`, `%${s}%`)), a += " ORDER BY FAC_NAME";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
H.get("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await t.prepare("SELECT * FROM Facility WHERE FAC_ID = ?").bind(s).first();
  return a ? e.json({ data: a }) : e.json({ error: "\u5834\u5730\u4E0D\u5B58\u5728" }, 404);
});
H.get("/:id/calendar", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = e.req.query("start") || "", n = e.req.query("end") || "";
  let i = "SELECT VB.*, U.USR_NAME, UN.UNIT_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID WHERE VB.VB_FAC_ID = ? AND VB.VB_STATUS IN (0, 1)";
  const r = [s];
  a && (i += " AND VB.VB_END_DATETIME >= ?", r.push(a)), n && (i += " AND VB.VB_START_DATETIME <= ?", r.push(n)), i += " ORDER BY VB.VB_START_DATETIME";
  const { results: c } = await t.prepare(i).bind(...r).all();
  return e.json({ data: c });
});
H.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO Facility (FAC_NAME, FAC_TYPE, FAC_BUILDING, FAC_FLOOR, FAC_CAPACITY, FAC_GIS_LAT, FAC_GIS_LNG, FAC_STATUS, FAC_DESC) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)").bind(s.name, s.type || 0, s.building, s.floor || 1, s.capacity, s.lat || null, s.lng || null, s.status || 0, s.desc || null).run();
  return e.json({ success: true, id: a.meta.last_row_id }, 201);
});
H.put("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE Facility SET FAC_NAME=?, FAC_TYPE=?, FAC_BUILDING=?, FAC_FLOOR=?, FAC_CAPACITY=?, FAC_STATUS=?, FAC_DESC=? WHERE FAC_ID=?").bind(a.name, a.type, a.building, a.floor, a.capacity, a.status, a.desc || null, s).run(), e.json({ success: true });
});
H.delete("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id");
  return await t.prepare("UPDATE Facility SET FAC_ACTIVE = 0 WHERE FAC_ID = ?").bind(s).run(), e.json({ success: true });
});
H.get("/:id/maintenance", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), { results: a } = await t.prepare("SELECT FML.*, U.USR_NAME FROM FacilityMaintenanceLog FML LEFT JOIN User U ON FML.FML_ADMIN_ID = U.USR_ID WHERE FML.FML_FAC_ID = ? ORDER BY FML.FML_CREATED_AT DESC").bind(s).all();
  return e.json({ data: a });
});
H.post("/:id/maintenance", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json(), n = await t.prepare("INSERT INTO FacilityMaintenanceLog (FML_FAC_ID, FML_START_DATE, FML_END_DATE, FML_NOTE, FML_ADMIN_ID) VALUES (?, ?, ?, ?, ?)").bind(s, a.startDate, a.endDate || null, a.note || null, a.adminId || 1).run();
  return await t.prepare("UPDATE Facility SET FAC_STATUS = 1 WHERE FAC_ID = ?").bind(s).run(), e.json({ success: true, id: n.meta.last_row_id }, 201);
});
var M = new I();
M.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("q") || "";
  let a = "SELECT E.*, ECT.ECT_NAME as CERT_NAME FROM Equipment E LEFT JOIN EquipmentCertType ECT ON E.EQ_CERT_TYPE_ID = ECT.ECT_ID WHERE E.EQ_ACTIVE = 1";
  const n = [];
  s && (a += " AND E.EQ_NAME LIKE ?", n.push(`%${s}%`)), a += " ORDER BY E.EQ_NAME";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
M.get("/cert-types", async (e) => {
  const t = e.env.DB, { results: s } = await t.prepare("SELECT * FROM EquipmentCertType").all();
  return e.json({ data: s });
});
M.get("/:id", async (e) => {
  const s = await e.env.DB.prepare("SELECT E.*, ECT.ECT_NAME as CERT_NAME FROM Equipment E LEFT JOIN EquipmentCertType ECT ON E.EQ_CERT_TYPE_ID = ECT.ECT_ID WHERE E.EQ_ID = ?").bind(e.req.param("id")).first();
  return s ? e.json({ data: s }) : e.json({ error: "\u5668\u6750\u4E0D\u5B58\u5728" }, 404);
});
M.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO Equipment (EQ_NAME, EQ_TOTAL, EQ_AVAILABLE, EQ_MAX_PER_LOAN, EQ_CERT_TYPE_ID, EQ_DESC, EQ_PHYSICAL_CODE) VALUES (?, ?, ?, ?, ?, ?, ?)").bind(s.name, s.total, s.total, s.maxPerLoan || 1, s.certTypeId || null, s.desc || null, s.physicalCode || null).run();
  return e.json({ success: true, id: a.meta.last_row_id }, 201);
});
M.put("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE Equipment SET EQ_NAME=?, EQ_TOTAL=?, EQ_AVAILABLE=?, EQ_MAX_PER_LOAN=?, EQ_DESC=? WHERE EQ_ID=?").bind(a.name, a.total, a.available, a.maxPerLoan, a.desc || null, s).run(), e.json({ success: true });
});
M.delete("/:id", async (e) => (await e.env.DB.prepare("UPDATE Equipment SET EQ_ACTIVE = 0 WHERE EQ_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true })));
M.post("/loans", async (e) => {
  const t = e.env.DB, s = await e.req.json();
  if (!await t.prepare("SELECT AA_ID FROM ActivityApplication WHERE AA_ID = ? AND AA_STATUS = 1").bind(s.activityId).first())
    return e.json({ success: false, message: "\u9700\u5148\u53D6\u5F97\u5DF2\u6838\u51C6\u7684\u6D3B\u52D5\u7533\u8ACB" }, 400);
  const i = (await t.prepare("INSERT INTO EquipmentLoan (EL_AA_ID, EL_UNIT_ID, EL_USR_ID, EL_BORROW_START, EL_RETURN_DUE, EL_USE_LOCATION, EL_PURPOSE, EL_LOAN_TYPE) VALUES (?, ?, ?, ?, ?, ?, ?, ?)").bind(s.activityId, s.unitId, s.userId, s.borrowStart, s.returnDue, s.useLocation, s.purpose, s.loanType || 0).run()).meta.last_row_id;
  if (s.items && Array.isArray(s.items))
    for (const r of s.items) {
      const c = await t.prepare("SELECT EQ_AVAILABLE, EQ_MAX_PER_LOAN, EQ_CERT_TYPE_ID FROM Equipment WHERE EQ_ID = ? AND EQ_ACTIVE = 1").bind(r.equipmentId).first();
      if (c) {
        if (r.quantity > c.EQ_MAX_PER_LOAN)
          return e.json({ success: false, message: `${r.equipmentId} \u8D85\u904E\u55AE\u6B21\u501F\u7528\u4E0A\u9650 ${c.EQ_MAX_PER_LOAN}` }, 400);
        if (r.quantity > c.EQ_AVAILABLE)
          return e.json({ success: false, message: "\u5EAB\u5B58\u4E0D\u8DB3" }, 400);
        if (c.EQ_CERT_TYPE_ID && !await t.prepare("SELECT EC_ID FROM EquipmentCert WHERE EC_USR_ID = ? AND EC_TYPE_ID = ? AND EC_STATUS = 0").bind(s.userId, c.EQ_CERT_TYPE_ID).first())
          return e.json({ success: false, message: "\u9700\u5148\u53D6\u5F97\u5C0D\u61C9\u5668\u6750\u64CD\u4F5C\u8B49" }, 400);
        await t.prepare("INSERT INTO EquipmentLoanDetail (ELD_EL_ID, ELD_EQ_ID, ELD_QUANTITY) VALUES (?, ?, ?)").bind(i, r.equipmentId, r.quantity).run(), await t.prepare("UPDATE Equipment SET EQ_AVAILABLE = EQ_AVAILABLE - ? WHERE EQ_ID = ?").bind(r.quantity, r.equipmentId).run();
      }
    }
  return e.json({ success: true, loanId: i, message: "\u5668\u6750\u501F\u7528\u7533\u8ACB\u5DF2\u9001\u51FA" }, 201);
});
M.get("/loans/list", async (e) => {
  const t = e.env.DB, s = e.req.query("userId");
  let a = "SELECT EL.*, U.USR_NAME, UN.UNIT_NAME FROM EquipmentLoan EL LEFT JOIN User U ON EL.EL_USR_ID = U.USR_ID LEFT JOIN Unit UN ON EL.EL_UNIT_ID = UN.UNIT_ID";
  const n = [];
  s && (a += " WHERE EL.EL_USR_ID = ?", n.push(s)), a += " ORDER BY EL.EL_CREATED_AT DESC";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
M.get("/loans/:id/details", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), { results: a } = await t.prepare("SELECT ELD.*, EQ.EQ_NAME FROM EquipmentLoanDetail ELD LEFT JOIN Equipment EQ ON ELD.ELD_EQ_ID = EQ.EQ_ID WHERE ELD.ELD_EL_ID = ?").bind(s).all();
  return e.json({ data: a });
});
var W = new I();
W.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("status"), a = e.req.query("unitId");
  let n = "SELECT AA.*, U.USR_NAME, UN.UNIT_NAME FROM ActivityApplication AA LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID WHERE 1=1";
  const i = [];
  s !== void 0 && (n += " AND AA.AA_STATUS = ?", i.push(Number(s))), a && (n += " AND AA.AA_UNIT_ID = ?", i.push(a)), n += " ORDER BY AA.AA_CREATED_AT DESC";
  const { results: r } = await t.prepare(n).bind(...i).all();
  return e.json({ data: r });
});
W.get("/:id", async (e) => {
  const s = await e.env.DB.prepare("SELECT AA.*, U.USR_NAME, UN.UNIT_NAME FROM ActivityApplication AA LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID WHERE AA.AA_ID = ?").bind(e.req.param("id")).first();
  return s ? e.json({ data: s }) : e.json({ error: "\u6D3B\u52D5\u7533\u8ACB\u4E0D\u5B58\u5728" }, 404);
});
W.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("SELECT COUNT(*) as cnt FROM ActivityApplication").first(), n = "AA" + (/* @__PURE__ */ new Date()).getFullYear() + String(a.cnt + 1).padStart(6, "0"), i = await t.prepare("INSERT INTO ActivityApplication (AA_SERIAL_NO, AA_USR_ID, AA_UNIT_ID, AA_ACTIVITY_NAME, AA_START_DATETIME, AA_END_DATETIME, AA_HEADCOUNT, AA_DESCRIPTION, AA_CONTACT_NAME, AA_CONTACT_PHONE, AA_CONTACT_EMAIL, AA_THEMES, AA_HAS_ALCOHOL, AA_HAS_FIRE, AA_HAS_BOOTH) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)").bind(n, s.userId, s.unitId, s.activityName, s.startDatetime, s.endDatetime, s.headcount, s.description || null, s.contactName || null, s.contactPhone || null, s.contactEmail || null, s.themes || null, s.hasAlcohol || 0, s.hasFire || 0, s.hasBooth || 0).run();
  return e.json({ success: true, id: i.meta.last_row_id, serialNo: n, message: "\u6D3B\u52D5\u7533\u8ACB\u5DF2\u9001\u51FA" }, 201);
});
W.put("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE ActivityApplication SET AA_ACTIVITY_NAME=?, AA_START_DATETIME=?, AA_END_DATETIME=?, AA_HEADCOUNT=?, AA_DESCRIPTION=? WHERE AA_ID=?").bind(a.activityName, a.startDatetime, a.endDatetime, a.headcount, a.description || null, s).run(), e.json({ success: true });
});
W.patch("/:id/approve", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE ActivityApplication SET AA_STATUS = 1, AA_ADMIN_ID = ?, AA_ADMIN_NOTE = ?, AA_REVIEWED_AT = datetime('now') WHERE AA_ID = ?").bind(a.adminId || 1, a.note || null, s).run(), e.json({ success: true, message: "\u6D3B\u52D5\u5DF2\u6838\u51C6" });
});
W.patch("/:id/reject", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE ActivityApplication SET AA_STATUS = 2, AA_ADMIN_ID = ?, AA_ADMIN_NOTE = ?, AA_REVIEWED_AT = datetime('now') WHERE AA_ID = ?").bind(a.adminId || 1, a.note || "\u672A\u7B26\u5408\u898F\u5B9A", s).run(), e.json({ success: true, message: "\u6D3B\u52D5\u5DF2\u99C1\u56DE" });
});
W.patch("/:id/cancel", async (e) => {
  const t = e.env.DB, s = e.req.param("id");
  return await t.prepare("UPDATE ActivityApplication SET AA_STATUS = 5 WHERE AA_ID = ?").bind(s).run(), e.json({ success: true, message: "\u6D3B\u52D5\u5DF2\u53D6\u6D88" });
});
W.delete("/:id", async (e) => (await e.env.DB.prepare("DELETE FROM ActivityApplication WHERE AA_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true })));
var J = new I();
J.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("status"), a = e.req.query("userId"), n = e.req.query("facId");
  let i = "SELECT VB.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE 1=1";
  const r = [];
  s !== void 0 && (i += " AND VB.VB_STATUS = ?", r.push(Number(s))), a && (i += " AND VB.VB_USR_ID = ?", r.push(a)), n && (i += " AND VB.VB_FAC_ID = ?", r.push(n)), i += " ORDER BY VB.VB_CREATED_AT DESC";
  const { results: c } = await t.prepare(i).bind(...r).all();
  return e.json({ data: c });
});
J.get("/pending", async (e) => {
  const t = e.env.DB, { results: s } = await t.prepare("SELECT VB.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE VB.VB_STATUS = 0 ORDER BY VB.VB_CREATED_AT ASC").all();
  return e.json({ data: s });
});
J.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("SELECT VB_ID, VB_START_DATETIME, VB_END_DATETIME FROM VenueBooking WHERE VB_FAC_ID = ? AND VB_STATUS IN (0, 1) AND VB_START_DATETIME < ? AND VB_END_DATETIME > ?").bind(s.facId, s.endDatetime, s.startDatetime).all();
  if (a.results && a.results.length > 0)
    return e.json({ success: false, message: "\u6B64\u6642\u6BB5\u5DF2\u6709\u9810\u7D04\uFF0C\u5EFA\u8B70\u9032\u5165\u885D\u7A81\u5354\u8ABF", conflictBookings: a.results }, 409);
  const n = await t.prepare("INSERT INTO VenueBooking (VB_AA_ID, VB_FAC_ID, VB_UNIT_ID, VB_USR_ID, VB_START_DATETIME, VB_END_DATETIME, VB_PURPOSE, VB_HEADCOUNT, VB_BOOKING_TYPE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)").bind(s.activityId, s.facId, s.unitId, s.userId, s.startDatetime, s.endDatetime, s.purpose, s.headcount, s.bookingType || 0).run();
  return e.json({ success: true, id: n.meta.last_row_id, message: "\u5834\u5730\u9810\u7D04\u5DF2\u9001\u51FA\uFF0C\u7B49\u5F85\u5BE9\u6838" }, 201);
});
J.patch("/:id/approve", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE VenueBooking SET VB_STATUS = 1, VB_ADMIN_ID = ? WHERE VB_ID = ?").bind(a.adminId || 1, s).run(), e.json({ success: true, message: "\u5834\u5730\u9810\u7D04\u5DF2\u6838\u51C6" });
});
J.patch("/:id/reject", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE VenueBooking SET VB_STATUS = 2, VB_ADMIN_ID = ?, VB_REJECT_REASON = ? WHERE VB_ID = ?").bind(a.adminId || 1, a.reason || "", s).run(), e.json({ success: true, message: "\u5834\u5730\u9810\u7D04\u5DF2\u62D2\u7D55" });
});
J.patch("/:id/cancel", async (e) => (await e.env.DB.prepare("UPDATE VenueBooking SET VB_STATUS = 3 WHERE VB_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true, message: "\u5834\u5730\u9810\u7D04\u5DF2\u53D6\u6D88" })));
J.patch("/:id/return", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json(), n = a.abnormal ? 5 : 4;
  return await t.prepare("UPDATE VenueBooking SET VB_STATUS = ?, VB_RETURN_AT = datetime('now'), VB_RETURN_NOTE = ? WHERE VB_ID = ?").bind(n, a.note || null, s).run(), e.json({ success: true, message: "\u5834\u5730\u5DF2\u6B78\u9084" });
});
J.delete("/:id", async (e) => (await e.env.DB.prepare("DELETE FROM VenueBooking WHERE VB_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true })));
var Ne = new I();
Ne.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("status");
  let a = "SELECT RR.*, U.USR_NAME, F.FAC_NAME, A.USR_NAME as ADMIN_NAME FROM RepairRequest RR LEFT JOIN User U ON RR.RR_USR_ID = U.USR_ID LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID LEFT JOIN User A ON RR.RR_ADMIN_ID = A.USR_ID WHERE 1=1";
  const n = [];
  s !== void 0 && (a += " AND RR.RR_STATUS = ?", n.push(Number(s))), a += " ORDER BY RR.RR_CREATED_AT DESC";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
Ne.get("/:id", async (e) => {
  const t = e.env.DB, s = await t.prepare("SELECT RR.*, U.USR_NAME, F.FAC_NAME FROM RepairRequest RR LEFT JOIN User U ON RR.RR_USR_ID = U.USR_ID LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID WHERE RR.RR_ID = ?").bind(e.req.param("id")).first();
  if (!s)
    return e.json({ error: "\u5831\u4FEE\u4E0D\u5B58\u5728" }, 404);
  const { results: a } = await t.prepare("SELECT * FROM RepairRequestPhoto WHERE RRP_RR_ID = ?").bind(e.req.param("id")).all();
  return e.json({ data: s, photos: a });
});
Ne.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO RepairRequest (RR_FAC_ID, RR_USR_ID, RR_DESCRIPTION) VALUES (?, ?, ?)").bind(s.facId, s.userId, s.description).run();
  return e.json({ success: true, id: a.meta.last_row_id, message: "\u5831\u4FEE\u5DF2\u63D0\u4EA4" }, 201);
});
Ne.put("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE RepairRequest SET RR_STATUS=?, RR_ADMIN_ID=?, RR_ADMIN_NOTE=?, RR_RESOLVED_AT=CASE WHEN ?=2 THEN datetime('now') ELSE RR_RESOLVED_AT END WHERE RR_ID=?").bind(a.status, a.adminId || 1, a.adminNote || null, a.status, s).run(), e.json({ success: true });
});
Ne.delete("/:id", async (e) => {
  const t = e.env.DB;
  return await t.prepare("DELETE FROM RepairRequestPhoto WHERE RRP_RR_ID = ?").bind(e.req.param("id")).run(), await t.prepare("DELETE FROM RepairRequest WHERE RR_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true });
});
var ue = new I();
ue.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("status");
  let a = "SELECT AC.*, U.USR_NAME, A.USR_NAME as ADMIN_NAME FROM AppealCase AC LEFT JOIN User U ON AC.AC_USR_ID = U.USR_ID LEFT JOIN User A ON AC.AC_ADMIN_ID = A.USR_ID WHERE 1=1";
  const n = [];
  s !== void 0 && (a += " AND AC.AC_STATUS = ?", n.push(Number(s))), a += " ORDER BY AC.AC_CREATED_AT DESC";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
ue.get("/:id", async (e) => {
  const s = await e.env.DB.prepare("SELECT AC.*, U.USR_NAME FROM AppealCase AC LEFT JOIN User U ON AC.AC_USR_ID = U.USR_ID WHERE AC.AC_ID = ?").bind(e.req.param("id")).first();
  return s ? e.json({ data: s }) : e.json({ error: "\u7533\u8A34\u4E0D\u5B58\u5728" }, 404);
});
ue.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO AppealCase (AC_USR_ID, AC_TYPE, AC_REF_LOG_ID, AC_REASON, AC_EVIDENCE) VALUES (?, ?, ?, ?, ?)").bind(s.userId, s.type, s.refLogId || null, s.reason, s.evidence || null).run();
  return e.json({ success: true, id: a.meta.last_row_id, message: "\u7533\u8A34\u5DF2\u63D0\u4EA4" }, 201);
});
ue.patch("/:id/approve", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE AppealCase SET AC_STATUS=1, AC_ADMIN_ID=?, AC_ADMIN_NOTE=?, AC_REVIEWED_AT=datetime('now') WHERE AC_ID=?").bind(a.adminId || 1, a.note || null, s).run(), e.json({ success: true, message: "\u7533\u8A34\u5DF2\u6838\u51C6" });
});
ue.patch("/:id/reject", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE AppealCase SET AC_STATUS=2, AC_ADMIN_ID=?, AC_ADMIN_NOTE=?, AC_REVIEWED_AT=datetime('now') WHERE AC_ID=?").bind(a.adminId || 1, a.note || "\u672A\u7B26\u5408\u7533\u8A34\u689D\u4EF6", s).run(), e.json({ success: true, message: "\u7533\u8A34\u5DF2\u99C1\u56DE" });
});
ue.delete("/:id", async (e) => (await e.env.DB.prepare("DELETE FROM AppealCase WHERE AC_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true })));
var Se = new I();
Se.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("active");
  let a = "SELECT ANN.*, U.USR_NAME as ADMIN_NAME FROM Announcement ANN LEFT JOIN User U ON ANN.ANN_ADMIN_ID = U.USR_ID";
  s === "1" && (a += " WHERE ANN.ANN_START_DATE <= date('now') AND ANN.ANN_END_DATE >= date('now')"), a += " ORDER BY ANN.ANN_CREATED_AT DESC";
  const { results: n } = await t.prepare(a).all();
  return e.json({ data: n });
});
Se.get("/:id", async (e) => {
  const s = await e.env.DB.prepare("SELECT ANN.*, U.USR_NAME as ADMIN_NAME FROM Announcement ANN LEFT JOIN User U ON ANN.ANN_ADMIN_ID = U.USR_ID WHERE ANN.ANN_ID = ?").bind(e.req.param("id")).first();
  return s ? e.json({ data: s }) : e.json({ error: "\u516C\u544A\u4E0D\u5B58\u5728" }, 404);
});
Se.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO Announcement (ANN_TITLE, ANN_CONTENT, ANN_START_DATE, ANN_END_DATE, ANN_ADMIN_ID) VALUES (?, ?, ?, ?, ?)").bind(s.title, s.content, s.startDate, s.endDate, s.adminId || 1).run();
  return e.json({ success: true, id: a.meta.last_row_id, message: "\u516C\u544A\u5DF2\u767C\u5E03" }, 201);
});
Se.put("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE Announcement SET ANN_TITLE=?, ANN_CONTENT=?, ANN_START_DATE=?, ANN_END_DATE=? WHERE ANN_ID=?").bind(a.title, a.content, a.startDate, a.endDate, s).run(), e.json({ success: true });
});
Se.delete("/:id", async (e) => (await e.env.DB.prepare("DELETE FROM Announcement WHERE ANN_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true })));
var ze = new I();
ze.get("/dashboard", async (e) => {
  const t = e.env.DB, s = e.req.query("period") || (/* @__PURE__ */ new Date()).toISOString().slice(0, 7), a = await t.prepare("SELECT COUNT(*) as cnt FROM Facility WHERE FAC_ACTIVE=1").first(), n = await t.prepare("SELECT COUNT(*) as cnt FROM Equipment WHERE EQ_ACTIVE=1").first(), i = await t.prepare("SELECT COUNT(*) as cnt FROM User").first(), r = await t.prepare("SELECT COUNT(*) as cnt FROM Unit WHERE UNIT_ACTIVE=1").first(), c = await t.prepare("SELECT COUNT(*) as cnt FROM VenueBooking WHERE VB_STATUS=0").first(), o = await t.prepare("SELECT COUNT(*) as cnt FROM VenueBooking WHERE VB_STATUS=1").first(), d = await t.prepare("SELECT COUNT(*) as cnt FROM ActivityApplication WHERE AA_STATUS=0").first(), u = await t.prepare("SELECT COUNT(*) as cnt FROM EquipmentLoan WHERE EL_STATUS IN (0,1,2,3)").first(), p = await t.prepare("SELECT COUNT(*) as cnt FROM RepairRequest WHERE RR_STATUS IN (0,1)").first(), f = await t.prepare("SELECT COUNT(*) as cnt FROM AppealCase WHERE AC_STATUS=0").first(), { results: g } = await t.prepare("SELECT SS.*, F.FAC_NAME FROM StatsSummary SS LEFT JOIN Facility F ON SS.SS_FAC_ID = F.FAC_ID WHERE SS.SS_PERIOD = ?").bind(s).all();
  return e.json({ period: s, totalFacilities: (a == null ? void 0 : a.cnt) || 0, totalEquipment: (n == null ? void 0 : n.cnt) || 0, totalUsers: (i == null ? void 0 : i.cnt) || 0, totalUnits: (r == null ? void 0 : r.cnt) || 0, pendingBookings: (c == null ? void 0 : c.cnt) || 0, approvedBookings: (o == null ? void 0 : o.cnt) || 0, pendingActivities: (d == null ? void 0 : d.cnt) || 0, activeLoans: (u == null ? void 0 : u.cnt) || 0, openRepairs: (p == null ? void 0 : p.cnt) || 0, pendingAppeals: (f == null ? void 0 : f.cnt) || 0, facilitySummary: g });
});
ze.get("/facility-usage", async (e) => {
  const t = e.env.DB, { results: s } = await t.prepare(`SELECT F.FAC_NAME, F.FAC_CAPACITY, COUNT(VB.VB_ID) as booking_count,
     COALESCE(SUM(ROUND((julianday(VB.VB_END_DATETIME) - julianday(VB.VB_START_DATETIME)) * 24, 2)), 0) as total_hours
     FROM Facility F LEFT JOIN VenueBooking VB ON F.FAC_ID = VB.VB_FAC_ID AND VB.VB_STATUS IN (1, 4)
     WHERE F.FAC_ACTIVE = 1 GROUP BY F.FAC_ID ORDER BY booking_count DESC`).all();
  return e.json({ data: s });
});
ze.get("/equipment-usage", async (e) => {
  const t = e.env.DB, { results: s } = await t.prepare(`SELECT E.EQ_NAME, E.EQ_TOTAL, E.EQ_AVAILABLE, COUNT(ELD.ELD_ID) as loan_count,
     COALESCE(SUM(ELD.ELD_QUANTITY), 0) as total_borrowed
     FROM Equipment E LEFT JOIN EquipmentLoanDetail ELD ON E.EQ_ID = ELD.ELD_EQ_ID
     WHERE E.EQ_ACTIVE = 1 GROUP BY E.EQ_ID ORDER BY loan_count DESC`).all();
  return e.json({ data: s });
});
var te = new I();
te.get("/", async (e) => {
  const t = e.env.DB, { results: s } = await t.prepare("SELECT U.*, CU.USR_NAME as CONTACT_NAME, UVP.UVP_POINT FROM Unit U LEFT JOIN User CU ON U.UNIT_CONTACT_USR = CU.USR_ID LEFT JOIN UnitViolationPoint UVP ON U.UNIT_ID = UVP.UVP_UNIT_ID WHERE U.UNIT_ACTIVE = 1 ORDER BY U.UNIT_NAME").all();
  return e.json({ data: s });
});
te.get("/:id", async (e) => {
  const t = e.env.DB, s = await t.prepare("SELECT U.*, CU.USR_NAME as CONTACT_NAME FROM Unit U LEFT JOIN User CU ON U.UNIT_CONTACT_USR = CU.USR_ID WHERE U.UNIT_ID = ?").bind(e.req.param("id")).first();
  if (!s)
    return e.json({ error: "\u55AE\u4F4D\u4E0D\u5B58\u5728" }, 404);
  const { results: a } = await t.prepare("SELECT UM.*, USR.USR_NAME, USR.USR_EMAIL FROM UnitMember UM LEFT JOIN User USR ON UM.UM_USR_ID = USR.USR_ID WHERE UM.UM_UNIT_ID = ? AND UM.UM_ACTIVE = 1").bind(e.req.param("id")).all();
  return e.json({ data: s, members: a });
});
te.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO Unit (UNIT_NAME, UNIT_TYPE, UNIT_CONTACT_USR) VALUES (?, ?, ?)").bind(s.name, s.type, s.contactUserId).run();
  return await t.prepare("INSERT INTO UnitViolationPoint (UVP_UNIT_ID, UVP_POINT) VALUES (?, 0)").bind(a.meta.last_row_id).run(), e.json({ success: true, id: a.meta.last_row_id }, 201);
});
te.put("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE Unit SET UNIT_NAME=?, UNIT_TYPE=?, UNIT_CONTACT_USR=? WHERE UNIT_ID=?").bind(a.name, a.type, a.contactUserId, s).run(), e.json({ success: true });
});
te.delete("/:id", async (e) => (await e.env.DB.prepare("UPDATE Unit SET UNIT_ACTIVE = 0 WHERE UNIT_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true })));
te.post("/:id/members", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("INSERT OR IGNORE INTO UnitMember (UM_UNIT_ID, UM_USR_ID) VALUES (?, ?)").bind(s, a.userId).run(), e.json({ success: true }, 201);
});
te.delete("/:id/members/:userId", async (e) => (await e.env.DB.prepare("UPDATE UnitMember SET UM_ACTIVE = 0 WHERE UM_UNIT_ID = ? AND UM_USR_ID = ?").bind(e.req.param("id"), e.req.param("userId")).run(), e.json({ success: true })));
var Ke = new I();
Ke.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("userId"), a = e.req.query("unitId");
  let n = "SELECT VPL.*, U.USR_NAME, UN.UNIT_NAME, A.USR_NAME as ADMIN_NAME FROM ViolationPointLog VPL LEFT JOIN User U ON VPL.VPL_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VPL.VPL_UNIT_ID = UN.UNIT_ID LEFT JOIN User A ON VPL.VPL_ADMIN_ID = A.USR_ID WHERE 1=1";
  const i = [];
  s && (n += " AND VPL.VPL_USR_ID = ?", i.push(s)), a && (n += " AND VPL.VPL_UNIT_ID = ?", i.push(a)), n += " ORDER BY VPL.VPL_CREATED_AT DESC";
  const { results: r } = await t.prepare(n).bind(...i).all();
  return e.json({ data: r });
});
Ke.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO ViolationPointLog (VPL_TARGET_TYPE, VPL_USR_ID, VPL_UNIT_ID, VPL_DELTA, VPL_REASON, VPL_SOURCE, VPL_ADMIN_ID, VPL_REF_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)").bind(s.targetType, s.userId || null, s.unitId || null, s.delta, s.reason, s.source || 0, s.adminId || null, s.refId || null).run();
  if (s.targetType === 1 && s.unitId) {
    await t.prepare("UPDATE UnitViolationPoint SET UVP_POINT = UVP_POINT + ? WHERE UVP_UNIT_ID = ?").bind(s.delta, s.unitId).run();
    const n = await t.prepare("SELECT UVP_POINT FROM UnitViolationPoint WHERE UVP_UNIT_ID = ?").bind(s.unitId).first();
    n && n.UVP_POINT >= 10 && await t.prepare("UPDATE UnitViolationPoint SET UVP_SUSPENDED = 1 WHERE UVP_UNIT_ID = ?").bind(s.unitId).run();
  }
  return e.json({ success: true, id: a.meta.last_row_id }, 201);
});
Ke.get("/unit-points", async (e) => {
  const t = e.env.DB, { results: s } = await t.prepare("SELECT UVP.*, U.UNIT_NAME FROM UnitViolationPoint UVP LEFT JOIN Unit U ON UVP.UVP_UNIT_ID = U.UNIT_ID ORDER BY UVP.UVP_POINT DESC").all();
  return e.json({ data: s });
});
var se = new I();
se.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("status");
  let a = `SELECT CN.*, 
    VBA.VB_START_DATETIME as A_START, VBA.VB_END_DATETIME as A_END, VBA.VB_PURPOSE as A_PURPOSE,
    VBB.VB_START_DATETIME as B_START, VBB.VB_END_DATETIME as B_END, VBB.VB_PURPOSE as B_PURPOSE,
    F.FAC_NAME, UA.USR_NAME as A_USER, UB.USR_NAME as B_USER, UNA.UNIT_NAME as A_UNIT, UNB.UNIT_NAME as B_UNIT
    FROM ConflictNegotiation CN
    LEFT JOIN VenueBooking VBA ON CN.CN_VB_ID_A = VBA.VB_ID LEFT JOIN VenueBooking VBB ON CN.CN_VB_ID_B = VBB.VB_ID
    LEFT JOIN Facility F ON VBA.VB_FAC_ID = F.FAC_ID
    LEFT JOIN User UA ON VBA.VB_USR_ID = UA.USR_ID LEFT JOIN User UB ON VBB.VB_USR_ID = UB.USR_ID
    LEFT JOIN Unit UNA ON VBA.VB_UNIT_ID = UNA.UNIT_ID LEFT JOIN Unit UNB ON VBB.VB_UNIT_ID = UNB.UNIT_ID
    WHERE 1=1`;
  const n = [];
  s !== void 0 && (a += " AND CN.CN_STATUS = ?", n.push(Number(s))), a += " ORDER BY CN.CN_CREATED_AT DESC";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
se.get("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await t.prepare(`SELECT CN.*, 
    VBA.VB_START_DATETIME as A_START, VBA.VB_END_DATETIME as A_END, VBA.VB_PURPOSE as A_PURPOSE, VBA.VB_FAC_ID,
    VBB.VB_START_DATETIME as B_START, VBB.VB_END_DATETIME as B_END, VBB.VB_PURPOSE as B_PURPOSE,
    F.FAC_NAME, UA.USR_NAME as A_USER, UB.USR_NAME as B_USER, UNA.UNIT_NAME as A_UNIT, UNB.UNIT_NAME as B_UNIT
    FROM ConflictNegotiation CN
    LEFT JOIN VenueBooking VBA ON CN.CN_VB_ID_A = VBA.VB_ID LEFT JOIN VenueBooking VBB ON CN.CN_VB_ID_B = VBB.VB_ID
    LEFT JOIN Facility F ON VBA.VB_FAC_ID = F.FAC_ID
    LEFT JOIN User UA ON VBA.VB_USR_ID = UA.USR_ID LEFT JOIN User UB ON VBB.VB_USR_ID = UB.USR_ID
    LEFT JOIN Unit UNA ON VBA.VB_UNIT_ID = UNA.UNIT_ID LEFT JOIN Unit UNB ON VBB.VB_UNIT_ID = UNB.UNIT_ID
    WHERE CN.CN_ID = ?`).bind(s).first();
  if (!a)
    return e.json({ error: "\u885D\u7A81\u4E0D\u5B58\u5728" }, 404);
  const { results: n } = await t.prepare("SELECT * FROM CoordinationMessage WHERE CM_CN_ID = ? ORDER BY CM_SENT_AT ASC").bind(s).all();
  return e.json({ data: a, messages: n });
});
se.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO ConflictNegotiation (CN_VB_ID_A, CN_VB_ID_B, CN_OPENED_AT) VALUES (?, ?, datetime('now'))").bind(s.bookingIdA, s.bookingIdB).run();
  return e.json({ success: true, id: a.meta.last_row_id }, 201);
});
se.post("/:id/messages", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  await t.prepare("INSERT INTO CoordinationMessage (CM_CN_ID, CM_SENDER_ROLE, CM_CONTENT) VALUES (?, ?, ?)").bind(s, a.senderRole, a.content).run(), await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 1 WHERE CN_ID = ? AND CN_STATUS = 0").bind(s).run();
  const { results: n } = await t.prepare("SELECT * FROM CoordinationMessage WHERE CM_CN_ID = ? ORDER BY CM_SENT_AT ASC").bind(s).all();
  return e.json({ success: true, messages: n });
});
se.patch("/:id/resolve", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 2, CN_RESOLVED_BY = ?, CN_CLOSED_AT = datetime('now'), CN_DELETE_AT = datetime('now', '+30 days') WHERE CN_ID = ?").bind(a.resolvedBy || 1, s).run(), e.json({ success: true, message: "\u5354\u8ABF\u5DF2\u89E3\u6C7A" });
});
se.patch("/:id/fail", async (e) => {
  const t = e.env.DB, s = e.req.param("id");
  return await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 3, CN_CLOSED_AT = datetime('now') WHERE CN_ID = ?").bind(s).run(), e.json({ success: true, message: "\u5354\u8ABF\u5931\u6557" });
});
se.patch("/:id/timeout", async (e) => {
  const t = e.env.DB, s = e.req.param("id");
  return await t.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 4, CN_CLOSED_AT = datetime('now') WHERE CN_ID = ?").bind(s).run(), e.json({ success: true, message: "\u5354\u8ABF\u8D85\u6642\u95DC\u9589" });
});
var qe = new I();
var ys = "https://models.inference.ai.azure.com/chat/completions";
var pt = { admin: `\u4F60\u662F\u8F14\u4EC1\u5927\u5B78\u8AB2\u6307\u7D44\u300C\u667A\u6167\u9810\u7D04\u5E73\u53F0\u300D\u7684 AI \u52A9\u7406\u3002\u4F60\u6B63\u5728\u5354\u52A9\u8AB2\u6307\u7D44\u7BA1\u7406\u54E1\u3002
\u4F60\u7684\u77E5\u8B58\u5EAB\u5305\u542B\u4EE5\u4E0B\u6CD5\u898F\uFF1A
- \u5834\u5730\u4F7F\u7528\u7BA1\u7406\u898F\u5247 v3.0\uFF1A\u5834\u5730\u501F\u7528\u8CC7\u683C\u3001\u6642\u6BB5\u9650\u5236\u3001\u6B78\u9084\u898F\u5B9A
- \u5668\u6750\u501F\u7528\u7BA1\u7406\u8FA6\u6CD5 v1.5\uFF1A\u5668\u6750\u501F\u7528\u6D41\u7A0B\u3001\u64CD\u4F5C\u8B49\u8981\u6C42\u3001\u640D\u58DE\u8CE0\u511F
- \u9055\u898F\u8A18\u9EDE\u8655\u7406\u8981\u9EDE v2.0\uFF1A\u9055\u898F\u985E\u578B\uFF08\u903E\u6642+2\u9EDE\u3001\u640D\u58DE+3\u9EDE\u3001\u672A\u5230\u5834+1\u9EDE\uFF09\u3001\u505C\u6B0A\u9580\u6ABB10\u9EDE
- \u6D3B\u52D5\u7533\u8ACB\u8FA6\u6CD5 v2.1\uFF1A\u6D3B\u52D5\u7533\u8ACB\u6D41\u7A0B\u3001\u5BE9\u6838\u6A19\u6E96\u3001\u542B\u9152\u7CBE/\u660E\u706B\u9808\u63D0\u524D30\u5929\u9001\u4EF6

\u7BA1\u7406\u54E1\u5E38\u898B\u64CD\u4F5C\uFF1A\u5BE9\u6838\u9810\u7D04\u3001\u7BA1\u7406\u9055\u898F\u8A18\u9EDE\u3001\u67E5\u770B\u7D71\u8A08\u5831\u8868\u3001\u8655\u7406\u5831\u4FEE\u3001\u7BA1\u7406\u4F7F\u7528\u8005\u5E33\u865F\u3002
\u8ACB\u7528\u7E41\u9AD4\u4E2D\u6587\u56DE\u7B54\uFF0C\u4E26\u63D0\u4F9B\u5177\u9AD4\u7684\u64CD\u4F5C\u6B65\u9A5F\u3002`, officer: `\u4F60\u662F\u8F14\u4EC1\u5927\u5B78\u8AB2\u6307\u7D44\u300C\u667A\u6167\u9810\u7D04\u5E73\u53F0\u300D\u7684 AI \u52A9\u7406\u3002\u4F60\u6B63\u5728\u5354\u52A9\u793E\u5718\u5E79\u90E8\u3002
\u4F60\u7684\u77E5\u8B58\u5EAB\u5305\u542B\u4EE5\u4E0B\u6CD5\u898F\uFF1A
- \u5834\u5730\u9810\u7D04\u6D41\u7A0B\uFF1A\u63D0\u4EA4\u6D3B\u52D5\u7533\u8ACB \u2192 \u53D6\u5F97\u6838\u51C6 \u2192 \u9810\u7D04\u5834\u5730 \u2192 \u5B8C\u6210\u7D19\u672C\u7A0B\u5E8F
- \u5668\u6750\u501F\u7528\u898F\u5247\uFF1A\u9700\u5148\u6709\u6838\u51C6\u6D3B\u52D5\u3001\u90E8\u5206\u5668\u6750\u9700\u64CD\u4F5C\u8B49\u3001\u9818\u53D6\u6642\u6BB5\u9650\u9031\u4E00\u81F3\u9031\u4E9409:30-16:30
- \u885D\u7A81\u5354\u8ABF\u6A5F\u5236\uFF1A\u5834\u5354\u5927\u6703\u767B\u8A18 \u2192 \u79C1\u4E0B\u5354\u8ABF\u804A\u5929\u5BA4 \u2192 \u4E00\u65B9\u64A4\u56DE
- \u5834\u5730\u4F7F\u7528\u6CE8\u610F\uFF1A\u501F\u7528\u65E5\u524D7\u5929\u9808\u5B8C\u6210\u7533\u8ACB\u3001\u4F7F\u7528\u5B8C\u7562\u6062\u5FA9\u539F\u72C0\u3001\u903E\u6642\u8A18\u9EDE

\u793E\u5718\u5E79\u90E8\u53EF\u7528 AI \u529F\u80FD\uFF1A\u6D3B\u52D5\u4F01\u5283\u66F8\u751F\u6210\u3001\u9810\u7D04\u98A8\u96AA\u8A55\u4F30\u3001\u6CD5\u898F\u67E5\u8A62\u3002
\u8ACB\u7528\u7E41\u9AD4\u4E2D\u6587\u56DE\u7B54\u3002`, professor: `\u4F60\u662F\u8F14\u4EC1\u5927\u5B78\u8AB2\u6307\u7D44\u300C\u667A\u6167\u9810\u7D04\u5E73\u53F0\u300D\u7684 AI \u52A9\u7406\u3002\u4F60\u6B63\u5728\u5354\u52A9\u6559\u6388\u3002
\u4F60\u7684\u77E5\u8B58\u5EAB\u5305\u542B\u4EE5\u4E0B\u898F\u5B9A\uFF1A
- \u6559\u6388\u53EF\u501F\u7528\u6559\u5BA4\u9032\u884C\u8AB2\u7A0B\u6216\u5B78\u8853\u6D3B\u52D5
- \u501F\u7528\u6D41\u7A0B\u8207\u4E00\u822C\u4F7F\u7528\u8005\u76F8\u540C\uFF0C\u9700\u5148\u63D0\u4EA4\u6D3B\u52D5\u7533\u8ACB
- \u53EF\u900F\u904E\u884C\u653F\u55AE\u4F4D\u540D\u7FA9\u7533\u8ACB\u501F\u7528
- \u6559\u6388\u5E33\u865F\u7121\u6709\u6548\u671F\u9650\u5236
\u8ACB\u7528\u7E41\u9AD4\u4E2D\u6587\u56DE\u7B54\u3002`, student: `\u4F60\u662F\u8F14\u4EC1\u5927\u5B78\u8AB2\u6307\u7D44\u300C\u667A\u6167\u9810\u7D04\u5E73\u53F0\u300D\u7684 AI \u52A9\u7406\u3002\u4F60\u6B63\u5728\u5354\u52A9\u5B78\u751F\u3002
\u4F60\u7684\u77E5\u8B58\u5EAB\u5305\u542B\u4EE5\u4E0B\u8CC7\u8A0A\uFF1A
- \u5B78\u751F\u9700\u900F\u904E\u6240\u5C6C\u55AE\u4F4D\uFF08\u793E\u5718/\u7CFB\u5B78\u6703\uFF09\u501F\u7528\u5834\u5730\u548C\u5668\u6750
- \u5834\u5730\u9810\u7D04\u9700\u5148\u6709\u5DF2\u6838\u51C6\u7684\u6D3B\u52D5\u7533\u8ACB
- \u9055\u898F\u8A18\u9EDE\u905410\u9EDE\u5C07\u505C\u6B0A\uFF0C\u53EF\u900F\u904E\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\u6216\u7533\u5FA9
- \u5982\u9700\u52A0\u5165\u793E\u5718\uFF0C\u8ACB\u806F\u7E6B\u793E\u5718\u5E79\u90E8\u6216\u81F3\u8AB2\u6307\u7D44\u767B\u8A18
\u8ACB\u7528\u7E41\u9AD4\u4E2D\u6587\u56DE\u7B54\u3002`, staff: `\u4F60\u662F\u8F14\u4EC1\u5927\u5B78\u8AB2\u6307\u7D44\u300C\u667A\u6167\u9810\u7D04\u5E73\u53F0\u300D\u7684 AI \u52A9\u7406\u3002\u4F60\u6B63\u5728\u5354\u52A9\u884C\u653F\u8077\u54E1\u3002
\u4F60\u7684\u77E5\u8B58\u5EAB\u5305\u542B\u4EE5\u4E0B\u898F\u5B9A\uFF1A
- \u8077\u54E1\u53EF\u4EE5\u884C\u653F\u55AE\u4F4D\u540D\u7FA9\u63D0\u4EA4\u6D3B\u52D5\u7533\u8ACB\u8207\u5834\u5730\u9810\u7D04
- \u5831\u4FEE\u8A2D\u65BD\u53EF\u81F3\u5831\u4FEE\u7BA1\u7406\u9801\u9762\u63D0\u4EA4
- \u501F\u7528\u6D41\u7A0B\u8207\u793E\u5718\u76F8\u540C\u4F46\u5BE9\u6838\u8F03\u5FEB
- \u884C\u653F\u8077\u54E1\u5E33\u865F\u7121\u6709\u6548\u671F\u9650\u5236
\u8ACB\u7528\u7E41\u9AD4\u4E2D\u6587\u56DE\u7B54\u3002` };
var Ts = { \u5834\u5730\u9810\u7D04: `**\u5834\u5730\u9810\u7D04\u6D41\u7A0B\uFF08\u4F9D\u64DA\u300A\u5834\u5730\u4F7F\u7528\u7BA1\u7406\u898F\u5247 v3.0\u300B\uFF09\uFF1A**

1. **\u63D0\u4EA4\u6D3B\u52D5\u7533\u8ACB**\uFF1A\u81F3\u300C\u6D3B\u52D5\u7533\u8ACB\u300D\u586B\u5BEB\u6D3B\u52D5\u8CC7\u6599\uFF0C\u7CFB\u7D71\u7522\u751F\u542B\u6D41\u6C34\u7DE8\u865F\u7684 PDF \u7533\u8ACB\u55AE
2. **\u7D19\u672C\u5BE9\u6838**\uFF1A\u6301\u5217\u5370\u7684\u7533\u8ACB\u55AE\u81F3\u8AB2\u6307\u7D44\u5B8C\u6210\u7D19\u672C\u5BE9\u6838
3. **\u53D6\u5F97\u6838\u51C6**\uFF1A\u8AB2\u6307\u7D44\u6A19\u8A18\u6838\u51C6\u5F8C\uFF0C\u8A72\u55AE\u4F4D\u6210\u54E1\u53EF\u9001\u51FA\u5834\u5730\u9810\u7D04
4. **\u9810\u7D04\u5834\u5730**\uFF1A\u81F3\u300C\u5834\u5730\u9810\u7D04\u300D\u9078\u64C7\u5834\u5730\u8207\u6642\u6BB5\uFF0C\u586B\u5BEB\u4F7F\u7528\u4E8B\u7531
5. **\u7B49\u5F85\u5BE9\u6838**\uFF1A\u8AB2\u6307\u7D44\u7BA1\u7406\u54E1\u5BE9\u6838\u5F8C\u901A\u77E5\u7D50\u679C

\u26A0\uFE0F \u6CE8\u610F\u4E8B\u9805\uFF1A
- \u5834\u5730\u501F\u7528\u7533\u8ACB\u6700\u665A\u9808\u65BC\u501F\u7528\u65E5\u524D **7 \u5929** \u5B8C\u6210\u9001\u51FA
- \u542B\u9152\u7CBE\u3001\u660E\u706B\u6216\u6D89\u53CA\u6524\u4F4D\u7684\u6D3B\u52D5\u9808\u65BC\u6D3B\u52D5 **1 \u500B\u6708\u524D** \u9001\u4EF6
- \u4F8B\u884C\u6027\u501F\u7528\u53EF\u8A2D\u5B9A\u6BCF\u9031\u91CD\u8907\uFF0C\u7CFB\u7D71\u81EA\u52D5\u5C55\u958B\u70BA\u591A\u7B46\u9810\u7D04`, \u5668\u6750\u501F\u7528: `**\u5668\u6750\u501F\u7528\u6D41\u7A0B\uFF08\u4F9D\u64DA\u300A\u5668\u6750\u501F\u7528\u7BA1\u7406\u8FA6\u6CD5 v1.5\u300B\uFF09\uFF1A**

1. **\u524D\u63D0\u689D\u4EF6**\uFF1A\u9700\u5148\u53D6\u5F97\u5DF2\u6838\u51C6\u7684\u6D3B\u52D5\u7533\u8ACB
2. **\u6AA2\u67E5\u5668\u6750\u8B49**\uFF1A\u90E8\u5206\u5668\u6750\uFF08\u5982\u97F3\u97FF\u3001\u6295\u5F71\u6A5F\uFF09\u9700\u5148\u53D6\u5F97\u64CD\u4F5C\u8B49
3. **\u586B\u5BEB\u7533\u8ACB**\uFF1A\u53EF\u5728\u4E00\u7B46\u7533\u8ACB\u4E2D\u9078\u53D6\u591A\u7A2E\u5668\u6750
4. **\u9818\u53D6\u6642\u6BB5**\uFF1A\u9031\u4E00\u81F3\u9031\u4E94 09:30-16:30\uFF0C\u9031\u4E09\u53E6\u589E 17:00-19:00
5. **\u7533\u8ACB\u6642\u9650**\uFF1A\u6700\u65E9\u9818\u53D6\u524D 30 \u65E5\uFF0C\u6700\u665A\u9818\u53D6\u524D 4 \u500B\u5DE5\u4F5C\u5929

\u26A0\uFE0F \u6CE8\u610F\u4E8B\u9805\uFF1A
- \u8D85\u904E 2 \u500B\u5DE5\u4F5C\u65E5\u672A\u9818\u53D6\uFF0C\u5668\u6750\u8B49\u5C07\u81EA\u52D5\u8A3B\u92B7
- \u903E\u671F\u6B78\u9084\u6216\u640D\u58DE\u5C07\u4F9D\u898F\u5B9A\u8A18\u9EDE`, \u885D\u7A81\u5354\u8ABF: `**\u5834\u5730\u885D\u7A81\u5354\u8ABF\u6A5F\u5236\uFF1A**

1. **\u5834\u5354\u5927\u6703**\uFF1A\u5B78\u671F\u521D\u7531\u8AB2\u6307\u7D44\u8209\u8FA6\uFF0C\u767B\u8A18\u622A\u6B62\u524D\u53EF\u63D0\u4EA4\u5834\u5730\u6642\u6BB5\u9700\u6C42
2. **\u79C1\u4E0B\u5354\u8ABF**\uFF1A\u7CFB\u7D71\u5075\u6E2C\u885D\u7A81\u5F8C\uFF0C\u53EF\u900F\u904E\u7AD9\u5167\u533F\u540D\u804A\u5929\u5BA4\u5354\u5546
3. **\u5354\u8ABF\u6D41\u7A0B**\uFF1A\u767C\u8D77\u9080\u8ACB \u2192 \u5C0D\u65B9\u540C\u610F \u2192 \u9032\u5165\u804A\u5929\u5BA4 \u2192 \u4E00\u65B9\u64A4\u56DE\u7533\u8ACB
4. **\u6642\u9650**\uFF1A\u804A\u5929\u5BA4\u958B\u555F\u5F8C 24 \u5C0F\u6642\u5167\u9700\u5B8C\u6210\uFF0C\u5426\u5247\u81EA\u52D5\u95DC\u9589
5. **\u7D00\u9304\u4FDD\u5B58**\uFF1A\u5C0D\u8A71\u5167\u5BB9\u4FDD\u5B58\u534A\u5E74\uFF0C\u7BA1\u7406\u54E1\u53EF\u8ABF\u95B1`, \u9055\u898F: `**\u9055\u898F\u8A18\u9EDE\u898F\u5247\uFF08\u4F9D\u64DA\u300A\u9055\u898F\u8A18\u9EDE\u8655\u7406\u8981\u9EDE v2.0\u300B\uFF09\uFF1A**

- \u903E\u6642\u4F7F\u7528\u5834\u5730\uFF1A+2 \u9EDE
- \u5834\u5730/\u5668\u6750\u640D\u58DE\uFF1A+3 \u9EDE
- \u672A\u5230\u5834\u4F7F\u7528\uFF1A+1 \u9EDE
- \u903E\u671F\u672A\u6B78\u9084\u5668\u6750\uFF1A+2 \u9EDE
- **\u55AE\u4F4D\u7D2F\u8A08\u9054 10 \u9EDE\u81EA\u52D5\u505C\u6B0A**

\u92B7\u9EDE\u65B9\u5F0F\uFF1A
1. \u52DE\u52D5\u670D\u52D9\uFF1A\u81F3\u300C\u9055\u898F\u8A18\u9EDE\u300D\u9801\u9762\u7533\u8ACB\uFF0C\u5B8C\u6210\u5F8C\u7531\u7BA1\u7406\u54E1\u6838\u51C6
2. \u7533\u5FA9\uFF1A\u5C0D\u4E0D\u5408\u7406\u8A18\u9EDE\u63D0\u51FA\u6B63\u5F0F\u7533\u8A34`, \u7533\u8A34: `**\u7533\u8A34\u6D41\u7A0B\uFF08\u4F9D\u64DA Epic 7\uFF09\uFF1A**

1. \u81F3\u300C\u7533\u8A34\u300D\u9801\u9762\u9078\u64C7\u985E\u578B\uFF08\u505C\u6B0A\u7533\u5FA9/\u9055\u898F\u8A18\u9EDE\u7533\u5FA9/\u5176\u4ED6\u6AA2\u8209\uFF09
2. \u586B\u5BEB\u7533\u8A34\u7406\u7531\u8207\u4F50\u8B49\u8CC7\u6599\uFF08\u5FC5\u586B\uFF09
3. \u9001\u51FA\u5F8C\u7B49\u5F85\u8AB2\u6307\u7D44\u5BE9\u6838
4. \u4E0D\u53EF\u5C0D\u540C\u4E00\u6848\u4EF6\u91CD\u8907\u7533\u8A34

\u5BE9\u6838\u7D50\u679C\uFF1A
- \u6838\u51C6\uFF1A\u64A4\u92B7\u8A18\u9EDE\u6216\u89E3\u9664\u505C\u6B0A
- \u99C1\u56DE\uFF1A\u7DAD\u6301\u539F\u5224\uFF0C\u9644\u99C1\u56DE\u8AAA\u660E`, \u5831\u4FEE: `**\u5831\u4FEE\u6D41\u7A0B\uFF08\u4F9D\u64DA Epic 6\uFF09\uFF1A**

1. \u81F3\u300C\u5831\u4FEE\u7BA1\u7406\u300D\u9801\u9762
2. \u9078\u64C7\u5831\u4FEE\u8A2D\u65BD\uFF08\u5FC5\u586B\uFF09
3. \u586B\u5BEB\u554F\u984C\u63CF\u8FF0\uFF08\u81F3\u5C11 10 \u5B57\uFF09
4. \u53EF\u4E0A\u50B3\u73FE\u5834\u7167\u7247\uFF08\u6700\u591A 3 \u5F35\uFF09
5. \u9001\u51FA\u5F8C\u7531\u8AB2\u6307\u7D44\u6D3E\u5DE5\u8655\u7406
6. \u5B8C\u6210\u5F8C\u7CFB\u7D71\u81EA\u52D5\u89E3\u9664\u7DAD\u4FEE\u4E2D\u6A19\u8A18`, \u6D3B\u52D5\u7533\u8ACB: `**\u6D3B\u52D5\u7533\u8ACB\u6D41\u7A0B\uFF08\u4F9D\u64DA\u300A\u6D3B\u52D5\u7533\u8ACB\u8FA6\u6CD5 v2.1\u300B\uFF09\uFF1A**

1. \u81F3\u300C\u6D3B\u52D5\u7533\u8ACB\u300D\u9801\u9762\u586B\u5BEB\u6D3B\u52D5\u8CC7\u8A0A
2. \u7CFB\u7D71\u81EA\u52D5\u7522\u751F\u6D41\u6C34\u7DE8\u865F\uFF08AAYYYYNNNNNN\u683C\u5F0F\uFF09
3. \u5217\u5370\u542B\u6D41\u6C34\u7DE8\u865F\u7684 PDF \u7533\u8ACB\u55AE
4. \u6301\u7D19\u672C\u81F3\u8AB2\u6307\u7D44\u7A97\u53E3\u8FA6\u7406\u5BE9\u6838
5. \u6838\u51C6\u5F8C\u53EF\u9032\u884C\u5834\u5730\u9810\u7D04\u548C\u5668\u6750\u501F\u7528

\u26A0\uFE0F \u7279\u6B8A\u898F\u5B9A\uFF1A
- \u542B\u9152\u7CBE\u98F2\u6599\u6D3B\u52D5\u9808\u63D0\u524D 1 \u500B\u6708\u9001\u4EF6
- \u6D89\u53CA\u660E\u706B\u8A2D\u5099\u9700\u9644\u6D88\u9632\u5B89\u5168\u8A08\u756B
- \u6524\u4F4D\u8CA9\u552E\u6D3B\u52D5\u9808\u53E6\u9644\u4F01\u5283\u66F8\u53CA\u76F8\u95DC\u7533\u8ACB`, \u5834\u5354\u5927\u6703: `**\u5834\u5354\u5927\u6703\u8AAA\u660E\uFF1A**

1. \u6BCF\u5B78\u671F\u521D\u7531\u8AB2\u6307\u7D44\u8209\u8FA6
2. \u767B\u8A18\u622A\u6B62\u65E5\u524D\u81F3\u7CFB\u7D71\u767B\u8A18\u5834\u5730\u9700\u6C42
3. \u5834\u5354\u5927\u6703\u73FE\u5834\u7531\u8AB2\u6307\u7D44\u5354\u8ABF
4. \u672A\u51FA\u5E2D\u8996\u540C\u653E\u68C4
5. \u6838\u51C6\u5F8C\u7CFB\u7D71\u81EA\u52D5\u7522\u751F\u6574\u5B78\u671F\u7684\u5834\u5730\u9810\u7D04

\u903E\u671F\u672A\u5B8C\u6210\u7D19\u672C\u6D41\u7A0B\u8005\u8996\u540C\u653E\u68C4\u3002`, \u52DE\u52D5\u670D\u52D9: `**\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\u6D41\u7A0B\uFF1A**

1. \u81F3\u300C\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\u300D\u9801\u9762\u63D0\u4EA4\u7533\u8ACB
2. \u9078\u64C7\u670D\u52D9\u985E\u578B\uFF08\u5834\u5730\u6E05\u6F54/\u5668\u6750\u6574\u7406/\u6D3B\u52D5\u652F\u63F4/\u884C\u653F\u5354\u52A9\uFF09
3. \u586B\u5BEB\u670D\u52D9\u65E5\u671F\u548C\u6642\u6578
4. \u7BA1\u7406\u54E1\u5BE9\u6838\u901A\u904E\u5F8C\u6263\u9664\u5C0D\u61C9\u9EDE\u6578

\u670D\u52D9\u985E\u578B\u8207\u53EF\u6263\u9EDE\u6578\u5C0D\u61C9\uFF1A
- \u5834\u5730\u6E05\u6F54 2 \u5C0F\u6642 = -1 \u9EDE
- \u5668\u6750\u6574\u7406 2 \u5C0F\u6642 = -1 \u9EDE
- \u6D3B\u52D5\u652F\u63F4 4 \u5C0F\u6642 = -2 \u9EDE`, \u64CD\u4F5C\u8B49: `**\u5668\u6750\u64CD\u4F5C\u8B49\u8AAA\u660E\uFF1A**

\u9700\u8981\u64CD\u4F5C\u8B49\u7684\u5668\u6750\uFF1A
- \u97F3\u97FF\u8A2D\u5099\uFF08\u7121\u7DDA\u9EA5\u514B\u98A8\u7D44\u3001\u684C\u4E0A\u578B\u97F3\u97FF\uFF09\u2192 \u97F3\u97FF\u8A2D\u5099\u64CD\u4F5C\u8B49
- \u6295\u5F71\u8A2D\u5099\uFF08\u53EF\u651C\u5F0F\u6295\u5F71\u6A5F\uFF09\u2192 \u6295\u5F71\u6A5F\u64CD\u4F5C\u8B49

\u53D6\u5F97\u65B9\u5F0F\uFF1A
1. \u81F3\u8AB2\u6307\u7D44\u9810\u7D04\u64CD\u4F5C\u8A13\u7DF4
2. \u5B8C\u6210\u8A13\u7DF4\u4E26\u901A\u904E\u8003\u6838
3. \u7BA1\u7406\u54E1\u6838\u767C\u64CD\u4F5C\u8B49

\u6CE8\u610F\uFF1A\u8D85\u904E 2 \u500B\u5DE5\u4F5C\u65E5\u672A\u9818\u53D6\u501F\u7528\u5668\u6750\uFF0C\u64CD\u4F5C\u8B49\u5C07\u81EA\u52D5\u8A3B\u92B7` };
qe.post("/chat", async (e) => {
  var c, o, d;
  const t = await e.req.json(), s = t.message || "", a = t.role || "student";
  let n = "";
  for (const [u, p] of Object.entries(Ts))
    if (s.includes(u)) {
      n = p;
      break;
    }
  const i = e.env.GITHUB_TOKEN;
  if (i)
    try {
      const u = await fetch(ys, { method: "POST", headers: { "Content-Type": "application/json", Authorization: `Bearer ${i}` }, body: JSON.stringify({ model: "gpt-4o", messages: [{ role: "system", content: pt[a] || pt.student }, ...n ? [{ role: "system", content: `\u4EE5\u4E0B\u662F\u76F8\u95DC\u6CD5\u898F\u77E5\u8B58\u4F9B\u53C3\u8003\uFF1A
${n}` }] : [], { role: "user", content: s }], temperature: 0.7, max_tokens: 1e3 }) });
      if (u.ok) {
        const p = await u.json(), f = ((d = (o = (c = p.choices) == null ? void 0 : c[0]) == null ? void 0 : o.message) == null ? void 0 : d.content) || "\u62B1\u6B49\uFF0C\u7121\u6CD5\u53D6\u5F97\u56DE\u61C9";
        return e.json({ success: true, response: f, model: p.model || "gpt-4o", usage: p.usage, source: "GitHub Models API (GPT-4o)" });
      }
    } catch {
    }
  let r = "";
  if (n)
    r = n;
  else {
    const u = { \u600E\u9EBC: "\u60A8\u7684\u554F\u984C\u6211\u4F86\u5E6B\u60A8\u89E3\u7B54\uFF01\u8ACB\u554F\u60A8\u60F3\u4E86\u89E3\u54EA\u500B\u529F\u80FD\uFF1F\u53EF\u4EE5\u76F4\u63A5\u554F\u6211\u95DC\u65BC\u5834\u5730\u9810\u7D04\u3001\u5668\u6750\u501F\u7528\u3001\u885D\u7A81\u5354\u8ABF\u3001\u9055\u898F\u8A18\u9EDE\u3001\u7533\u8A34\u3001\u5831\u4FEE\u7B49\u4E3B\u984C\u3002", \u5982\u4F55: "\u5F88\u9AD8\u8208\u70BA\u60A8\u670D\u52D9\uFF01\u8ACB\u76F4\u63A5\u544A\u8A34\u6211\u60A8\u60F3\u64CD\u4F5C\u7684\u529F\u80FD\uFF0C\u4F8B\u5982\uFF1A\u5834\u5730\u9810\u7D04\u3001\u5668\u6750\u501F\u7528\u3001\u6D3B\u52D5\u7533\u8ACB\u7B49\u3002", \u5E6B\u52A9: `\u6211\u53EF\u4EE5\u5354\u52A9\u60A8\u4EE5\u4E0B\u4E8B\u9805\uFF1A
1. \u5834\u5730\u9810\u7D04\u6D41\u7A0B\u8207\u898F\u5B9A
2. \u5668\u6750\u501F\u7528\u8207\u64CD\u4F5C\u8B49
3. \u885D\u7A81\u5354\u8ABF\u6A5F\u5236
4. \u9055\u898F\u8A18\u9EDE\u8207\u92B7\u9EDE
5. \u7533\u8A34\u6D41\u7A0B
6. \u5831\u4FEE\u7BA1\u7406
7. \u6D3B\u52D5\u7533\u8ACB\u6D41\u7A0B

\u8ACB\u544A\u8A34\u6211\u60A8\u9700\u8981\u54EA\u65B9\u9762\u7684\u5354\u52A9\uFF01`, \u4F60\u597D: `\u60A8\u597D\uFF01\u6211\u662F\u8F14\u4EC1\u5927\u5B78\u8AB2\u6307\u7D44\u667A\u6167\u9810\u7D04\u5E73\u53F0\u7684 AI \u52A9\u7406\u3002\u6709\u4EC0\u9EBC\u6211\u53EF\u4EE5\u5E6B\u60A8\u7684\u55CE\uFF1F

\u60A8\u53EF\u4EE5\u554F\u6211\u95DC\u65BC\u5834\u5730\u9810\u7D04\u3001\u5668\u6750\u501F\u7528\u3001\u6D3B\u52D5\u7533\u8ACB\u7B49\u554F\u984C\u3002`, \u8B1D\u8B1D: "\u4E0D\u5BA2\u6C23\uFF01\u5982\u679C\u9084\u6709\u5176\u4ED6\u554F\u984C\uFF0C\u96A8\u6642\u6B61\u8FCE\u8A62\u554F\u3002\u795D\u60A8\u4F7F\u7528\u6109\u5FEB\uFF01" };
    let p = false;
    for (const [f, g] of Object.entries(u))
      if (s.includes(f)) {
        r = g, p = true;
        break;
      }
    p || (r = `\u611F\u8B1D\u60A8\u7684\u63D0\u554F\uFF01\u95DC\u65BC\u300C${s}\u300D\uFF0C\u4EE5\u4E0B\u662F\u76F8\u95DC\u8AAA\u660E\uFF1A

`, r += `\u9019\u662F\u4E00\u500B\u5F88\u597D\u7684\u554F\u984C\u3002\u5EFA\u8B70\u60A8\u53EF\u4EE5\uFF1A
`, r += `1. \u67E5\u770B\u7CFB\u7D71\u516C\u544A\u4E86\u89E3\u6700\u65B0\u898F\u5B9A
`, r += `2. \u81F3\u5C0D\u61C9\u529F\u80FD\u9801\u9762\u64CD\u4F5C
`, r += `3. \u806F\u7E6B\u8AB2\u6307\u7D44\u53D6\u5F97\u9032\u4E00\u6B65\u5354\u52A9

`, r += `\u{1F4A1} \u63D0\u793A\uFF1A\u60A8\u53EF\u4EE5\u8A66\u8457\u554F\u6211\u4EE5\u4E0B\u554F\u984C\uFF1A
`, r += `- \u5834\u5730\u9810\u7D04\u600E\u9EBC\u64CD\u4F5C\uFF1F
`, r += `- \u5668\u6750\u501F\u7528\u9700\u8981\u4EC0\u9EBC\u689D\u4EF6\uFF1F
`, r += `- \u9055\u898F\u8A18\u9EDE\u898F\u5247\u662F\u4EC0\u9EBC\uFF1F
`, r += `- \u5982\u4F55\u7533\u8ACB\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\uFF1F
`, r += `- \u5834\u5354\u5927\u6703\u662F\u4EC0\u9EBC\uFF1F
`);
  }
  return e.json({ success: true, response: r, model: "RAG \u77E5\u8B58\u5EAB (\u672C\u5730)", source: n ? "\u6CD5\u898F\u77E5\u8B58\u5EAB\u5339\u914D" : "Demo \u6A21\u5F0F \u2014 \u8A2D\u5B9A GITHUB_TOKEN \u5F8C\u53EF\u9023\u7DDA GPT-4o", note: "\u8A2D\u5B9A GITHUB_TOKEN \u74B0\u5883\u8B8A\u6578\u5F8C\u5373\u53EF\u9023\u7DDA GitHub Models API" });
});
qe.post("/pre-review", async (e) => {
  const t = await e.req.json(), s = t.headcount || 0, a = t.hasAlcohol || false, n = t.hasFire || false, i = t.hasBooth || false, r = t.startDate || "", c = [], o = [];
  let d = "safe";
  if (s > 300 ? (c.push("\u4EBA\u6578\u8D85\u904E 300 \u4EBA\uFF0C\u9700\u7533\u8ACB\u5927\u578B\u6D3B\u52D5\u8A31\u53EF"), o.push("\u8ACB\u81F3\u5B78\u52D9\u8655\u7533\u8ACB\u5927\u578B\u96C6\u6703\u8A31\u53EF"), d = "high") : s > 80 && (o.push("\u5EFA\u8B70\u7533\u8ACB\u8F03\u5927\u5834\u5730\uFF08\u9032\u4FEE\u90E8\u5730\u4E0B\u6F14\u8B1B\u5EF3\u6216\u4E2D\u7F8E\u5802\uFF09"), o.push("\u9700\u63D0\u4EA4\u5B89\u5168\u8A08\u756B\u66F8"), d = "warning"), a && (c.push("\u6D3B\u52D5\u6D89\u53CA\u9152\u7CBE\u98F2\u6599\uFF0C\u4F9D\u898F\u5B9A\u9808\u65BC\u6D3B\u52D5\u4E00\u500B\u6708\u524D\u6AA2\u9644\u4F01\u5283\u66F8"), d = "high"), n && (c.push("\u6D3B\u52D5\u6D89\u53CA\u660E\u706B\u8A2D\u5099\uFF0C\u9700\u6D88\u9632\u5B89\u5168\u8A08\u756B"), d = "high"), i && (o.push("\u6D89\u53CA\u64FA\u6524\u8CA9\u552E\u4E4B\u6D3B\u52D5\uFF0C\u9808\u65BC\u6D3B\u52D5\u4E00\u500B\u6708\u524D\u6AA2\u9644\u4F01\u5283\u66F8\u53CA\u76F8\u95DC\u7533\u8ACB\u8CC7\u6599"), d === "safe" && (d = "warning")), r) {
    const u = new Date(r), p = /* @__PURE__ */ new Date(), f = Math.floor((u.getTime() - p.getTime()) / (1e3 * 60 * 60 * 24));
    f < 7 && (c.push(`\u8DDD\u96E2\u6D3B\u52D5\u50C5\u5269 ${f} \u5929\uFF0C\u5834\u5730\u501F\u7528\u7533\u8ACB\u9700\u81F3\u5C11 7 \u5929\u524D\u9001\u51FA`), d = "high"), (a || n || i) && f < 30 && (c.push("\u542B\u9152\u7CBE/\u660E\u706B/\u6524\u4F4D\u7684\u6D3B\u52D5\u9808\u65BC\u6D3B\u52D5 1 \u500B\u6708\u524D\u9001\u4EF6\uFF0C\u76EE\u524D\u6642\u9593\u4E0D\u8DB3"), d = "high");
  }
  return e.json({ allowNextStep: c.length === 0, riskLevel: d, violations: c, suggestions: o.length > 0 ? o : ["\u7B26\u5408\u898F\u5B9A\uFF0C\u53EF\u76F4\u63A5\u9001\u5BE9"], confidence: 0.95, reviewedRegulations: ["\u6D3B\u52D5\u7533\u8ACB\u8FA6\u6CD5 v2.1", "\u5834\u5730\u4F7F\u7528\u7BA1\u7406\u898F\u5247 v3.0", "\u5B89\u5168\u7BA1\u7406\u898F\u7BC4 v1.8"] });
});
qe.post("/generate-report", async (e) => {
  const t = e.env.DB, { results: s } = await t.prepare(`SELECT VB.VB_UNIT_ID, U.UNIT_NAME, COUNT(*) as usage_count 
     FROM VenueBooking VB LEFT JOIN Unit U ON VB.VB_UNIT_ID = U.UNIT_ID 
     WHERE VB.VB_STATUS IN (1, 4) GROUP BY VB.VB_UNIT_ID`).all();
  let a = 0;
  const n = (s || []).reduce((b, D) => b + (D.usage_count || 0), 0);
  if (n > 1) {
    let b = 0;
    for (const D of s || []) {
      const Q = D.usage_count || 0;
      b += Q / n * (Q / n);
    }
    a = 1 - b;
  }
  const { results: i } = await t.prepare(`SELECT F.FAC_ID, F.FAC_NAME, F.FAC_CAPACITY, F.FAC_TYPE, F.FAC_BUILDING, COUNT(VB.VB_ID) as usage_count,
     COALESCE(SUM(ROUND((julianday(VB.VB_END_DATETIME) - julianday(VB.VB_START_DATETIME)) * 24, 2)), 0) as total_hours
     FROM Facility F LEFT JOIN VenueBooking VB ON F.FAC_ID = VB.VB_FAC_ID AND VB.VB_STATUS IN (1, 4)
     WHERE F.FAC_ACTIVE = 1 GROUP BY F.FAC_ID ORDER BY usage_count DESC`).all(), { results: r } = await t.prepare(`SELECT E.EQ_NAME, E.EQ_TOTAL, E.EQ_AVAILABLE,
     COUNT(ELD.ELD_ID) as loan_count,
     COALESCE(SUM(ELD.ELD_QUANTITY), 0) as total_borrowed
     FROM Equipment E LEFT JOIN EquipmentLoanDetail ELD ON E.EQ_ID = ELD.ELD_EQ_ID
     WHERE E.EQ_ACTIVE = 1 GROUP BY E.EQ_ID ORDER BY loan_count DESC`).all(), { results: c } = await t.prepare(`SELECT UVP.*, U.UNIT_NAME FROM UnitViolationPoint UVP
     LEFT JOIN Unit U ON UVP.UVP_UNIT_ID = U.UNIT_ID
     ORDER BY UVP.UVP_POINT DESC`).all(), o = await t.prepare(`SELECT COUNT(*) as total, SUM(CASE WHEN AA_STATUS=1 THEN 1 ELSE 0 END) as approved,
     SUM(CASE WHEN AA_STATUS=0 THEN 1 ELSE 0 END) as pending,
     SUM(CASE WHEN AA_STATUS=2 THEN 1 ELSE 0 END) as rejected
     FROM ActivityApplication`).first(), d = await t.prepare(`SELECT COUNT(*) as total, SUM(CASE WHEN RR_STATUS=0 THEN 1 ELSE 0 END) as pending,
     SUM(CASE WHEN RR_STATUS=1 THEN 1 ELSE 0 END) as in_progress,
     SUM(CASE WHEN RR_STATUS=2 THEN 1 ELSE 0 END) as completed
     FROM RepairRequest`).first(), u = await t.prepare(`SELECT COUNT(*) as total, SUM(CASE WHEN CN_STATUS=2 THEN 1 ELSE 0 END) as resolved,
     SUM(CASE WHEN CN_STATUS IN (3,4) THEN 1 ELSE 0 END) as failed
     FROM ConflictNegotiation`).first(), { results: p } = await t.prepare(`SELECT CAST(substr(VB_START_DATETIME, 12, 2) AS INTEGER) as hour, COUNT(*) as cnt
     FROM VenueBooking WHERE VB_STATUS IN (0,1,4)
     GROUP BY hour ORDER BY cnt DESC LIMIT 5`).all(), f = 200, g = (i || []).map((b) => ({ ...b, utilizationRate: Math.round((b.total_hours || 0) / f * 100 * 10) / 10 })), _ = [];
  a < 0.5 ? _.push("\u3010\u591A\u6A23\u6027\u4E0D\u8DB3\u3011\u5EFA\u8B70\u9F13\u52F5\u66F4\u591A\u55AE\u4F4D\u4F7F\u7528\u5834\u5730\uFF0C\u63D0\u5347\u8CC7\u6E90\u591A\u6A23\u6027\u6307\u6578") : _.push("\u3010\u591A\u6A23\u6027\u826F\u597D\u3011\u8CC7\u6E90\u5206\u914D\u5747\u8861 (D=" + Math.round(a * 1e3) / 1e3 + ")\uFF0C\u6301\u7E8C\u7DAD\u6301\u73FE\u884C\u653F\u7B56");
  const w = g.filter((b) => b.utilizationRate < 20 && b.usage_count === 0);
  w.length > 0 && _.push("\u3010\u9592\u7F6E\u5834\u5730\u3011" + w.map((b) => b.FAC_NAME).join("\u3001") + " \u5229\u7528\u7387\u504F\u4F4E\uFF0C\u5EFA\u8B70\u958B\u653E\u70BA\u81EA\u7FD2\u7A7A\u9593\u6216\u8ABF\u6574\u958B\u653E\u6642\u6BB5");
  const R = g.filter((b) => b.utilizationRate > 70);
  R.length > 0 && _.push("\u3010\u71B1\u9580\u5834\u5730\u3011" + R.map((b) => b.FAC_NAME).join("\u3001") + " \u4F7F\u7528\u7387\u9AD8\uFF0C\u5EFA\u8B70\u5EF6\u9577\u958B\u653E\u6642\u9593\u6216\u589E\u8A2D\u66FF\u4EE3\u5834\u5730");
  const U = (r || []).filter((b) => b.EQ_AVAILABLE === 0);
  return U.length > 0 && _.push("\u3010\u5EAB\u5B58\u4E0D\u8DB3\u3011" + U.map((b) => b.EQ_NAME).join("\u3001") + " \u5DF2\u5168\u6578\u501F\u51FA\uFF0C\u5EFA\u8B70\u589E\u8CFC"), _.push("\u5EFA\u8B70\u5B9A\u671F\u8209\u8FA6\u5668\u6750\u64CD\u4F5C\u8B49\u8A13\u7DF4\u8AB2\u7A0B\uFF0C\u964D\u4F4E\u501F\u7528\u9580\u6ABB"), ((d == null ? void 0 : d.pending) || 0) > 2 && _.push("\u3010\u5831\u4FEE\u7A4D\u58D3\u3011\u76EE\u524D\u6709 " + d.pending + " \u4EF6\u5F85\u8655\u7406\u5831\u4FEE\uFF0C\u5EFA\u8B70\u512A\u5148\u6392\u7A0B"), p && p.length > 0 && _.push("\u3010\u5C16\u5CF0\u6642\u6BB5\u3011\u6700\u71B1\u9580\u6642\u6BB5\u70BA " + p.map((b) => b.hour + ":00").join("\u3001") + "\uFF0C\u5EFA\u8B70\u932F\u958B\u6392\u7A0B"), e.json({ reportTitle: "114\u5B78\u5E74\u5EA6\u7B2C2\u5B78\u671F \u8AB2\u6307\u7D44\u8CC7\u6E90\u4F7F\u7528\u8A55\u9451\u5831\u544A", generatedAt: (/* @__PURE__ */ new Date()).toISOString(), simpson: { value: Math.round(a * 1e3) / 1e3, interpretation: a > 0.7 ? "\u8CC7\u6E90\u88AB\u5404\u7FA4\u9AD4\u5747\u8861\u4F7F\u7528\uFF08\u591A\u6A23\u6027\u9AD8\uFF09" : a > 0.4 ? "\u8CC7\u6E90\u5206\u914D\u5C1A\u53EF\uFF0C\u90E8\u5206\u7FA4\u9AD4\u4F7F\u7528\u8F03\u591A" : "\u8CC7\u6E90\u88AB\u5C11\u6578\u7FA4\u9AD4\u58DF\u65B7\uFF0C\u5EFA\u8B70\u8ABF\u6574\u914D\u7F6E\u653F\u7B56", unitBreakdown: s }, facilityUsage: g, equipmentUsage: r, violationSummary: c, activitySummary: o || { total: 0, approved: 0, pending: 0, rejected: 0 }, repairSummary: d || { total: 0, pending: 0, in_progress: 0, completed: 0 }, conflictSummary: u || { total: 0, resolved: 0, failed: 0 }, peakHours: p || [], recommendations: _ });
});
qe.post("/generate-pdf", async (e) => {
  const t = e.env.DB, a = (await e.req.json()).activityId;
  if (!a)
    return e.json({ success: false, message: "\u8ACB\u63D0\u4F9B\u6D3B\u52D5 ID" }, 400);
  const n = await t.prepare(`SELECT AA.*, U.USR_NAME, UN.UNIT_NAME, UN.UNIT_TYPE
     FROM ActivityApplication AA
     LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID
     LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID
     WHERE AA.AA_ID = ?`).bind(a).first();
  if (!n)
    return e.json({ success: false, message: "\u6D3B\u52D5\u4E0D\u5B58\u5728" }, 404);
  const i = { 0: "\u5F85\u5BE9\u6838", 1: "\u5DF2\u6838\u51C6", 2: "\u5DF2\u62D2\u7D55", 3: "\u5DF2\u53D6\u6D88" };
  return e.json({ success: true, pdfData: { title: "\u8F14\u4EC1\u5927\u5B78\u8AB2\u5916\u6D3B\u52D5\u6307\u5C0E\u7D44 \u6D3B\u52D5\u7533\u8ACB\u66F8", serialNo: n.AA_SERIAL_NO, activityName: n.AA_ACTIVITY_NAME, applicantName: n.USR_NAME, unitName: n.UNIT_NAME, startDatetime: n.AA_START_DATETIME, endDatetime: n.AA_END_DATETIME, headcount: n.AA_HEADCOUNT, description: n.AA_DESCRIPTION, contactName: n.AA_CONTACT_NAME, contactPhone: n.AA_CONTACT_PHONE, contactEmail: n.AA_CONTACT_EMAIL, themes: n.AA_THEMES, hasAlcohol: n.AA_HAS_ALCOHOL === 1, hasFire: n.AA_HAS_FIRE === 1, hasBooth: n.AA_HAS_BOOTH === 1, status: i[n.AA_STATUS] || "\u672A\u77E5", createdAt: n.AA_CREATED_AT, reviewedAt: n.AA_REVIEWED_AT, adminNote: n.AA_ADMIN_NOTE, generatedAt: (/* @__PURE__ */ new Date()).toISOString() } });
});
var Lt = new I();
Lt.get("/", async (e) => {
  const t = e.req.query("role") || "student", s = [{ q: "\u5982\u4F55\u9810\u7D04\u5834\u5730\uFF1F", a: `1. \u5148\u81F3\u300C\u6D3B\u52D5\u7533\u8ACB\u300D\u63D0\u4EA4\u6D3B\u52D5\u767B\u8A18\uFF0C\u53D6\u5F97\u6D41\u6C34\u7DE8\u865F
2. \u6301\u7D19\u672C\u7533\u8ACB\u55AE\u81F3\u8AB2\u6307\u7D44\u5B8C\u6210\u5BE9\u6838
3. \u6838\u51C6\u5F8C\u81F3\u300C\u5834\u5730\u9810\u7D04\u300D\u9078\u64C7\u5834\u5730\u8207\u6642\u6BB5
4. \u7CFB\u7D71\u5075\u6E2C\u885D\u7A81\u5F8C\u8F49\u5165\u5F85\u5BE9\u72C0\u614B
5. \u8AB2\u6307\u7D44\u7BA1\u7406\u54E1\u5BE9\u6838\u901A\u904E\u5F8C\u5373\u5B8C\u6210\u9810\u7D04

\u26A0\uFE0F \u5834\u5730\u501F\u7528\u7533\u8ACB\u6700\u665A\u9808\u65BC\u501F\u7528\u65E5\u524D 7 \u5929\u9001\u51FA`, category: "\u5834\u5730\u9810\u7D04" }, { q: "\u5668\u6750\u501F\u7528\u9700\u8981\u4EC0\u9EBC\u689D\u4EF6\uFF1F", a: `1. \u9700\u6709\u5DF2\u6838\u51C6\u7684\u6D3B\u52D5\u7533\u8ACB\uFF08\u4F5C\u70BA\u501F\u7528\u524D\u63D0\uFF09
2. \u90E8\u5206\u5668\u6750\u9700\u5148\u53D6\u5F97\u64CD\u4F5C\u8B49\uFF08\u97F3\u97FF\u8A2D\u5099\u3001\u6295\u5F71\u6A5F\u7B49\uFF09
3. \u55AE\u6B21\u501F\u7528\u6578\u91CF\u4E0D\u53EF\u8D85\u904E\u4E0A\u9650
4. \u9818\u53D6\u6642\u6BB5\u9650\u9031\u4E00\u81F3\u9031\u4E94 09:30-16:30\uFF0C\u9031\u4E09\u53E6\u589E 17:00-19:00
5. \u6700\u65E9\u9818\u53D6\u524D30\u65E5\u3001\u6700\u665A\u9818\u53D6\u524D4\u500B\u5DE5\u4F5C\u5929\u7533\u8ACB
6. \u6B78\u9084\u6642\u9700\u7531\u7BA1\u7406\u54E1\u78BA\u8A8D\u72C0\u614B`, category: "\u5668\u6750\u501F\u7528" }, { q: "\u5834\u5730\u885D\u7A81\u5982\u4F55\u8655\u7406\uFF1F", a: `\u5834\u5730\u9810\u7D04\u885D\u7A81\u6709\u5169\u7A2E\u89E3\u6C7A\u7BA1\u9053\uFF1A

**1. \u5834\u5354\u5927\u6703\uFF08\u5B78\u671F\u521D\uFF09**
- \u5728\u767B\u8A18\u622A\u6B62\u65E5\u524D\u81F3\u7CFB\u7D71\u767B\u8A18\u5834\u5730\u9700\u6C42
- \u5834\u5354\u5927\u6703\u73FE\u5834\u7531\u8AB2\u6307\u7D44\u5354\u8ABF
- \u672A\u51FA\u5E2D\u8996\u540C\u653E\u68C4

**2. \u79C1\u4E0B\u5354\u8ABF\uFF08\u5834\u5354\u622A\u6B62\u5F8C\uFF09**
- \u7CFB\u7D71\u5075\u6E2C\u885D\u7A81\u5F8C\u53EF\u7533\u8ACB\u79C1\u4E0B\u5354\u8ABF
- \u9032\u5165\u533F\u540D\u804A\u5929\u5BA4\u5354\u5546
- 24\u5C0F\u6642\u5167\u4E00\u65B9\u64A4\u56DE\u5373\u89E3\u6C7A
- \u5C0D\u8A71\u7D00\u9304\u4FDD\u5B58\u534A\u5E74`, category: "\u885D\u7A81\u5354\u8ABF" }, { q: "\u9055\u898F\u8A18\u9EDE\u898F\u5247\uFF1F", a: `**\u9055\u898F\u985E\u578B\u8207\u8A18\u9EDE\uFF1A**
- \u903E\u6642\u4F7F\u7528\u5834\u5730\uFF1A+2 \u9EDE
- \u5834\u5730/\u5668\u6750\u640D\u58DE\uFF1A+3 \u9EDE
- \u672A\u5230\u5834\u4F7F\u7528\uFF1A+1 \u9EDE
- \u903E\u671F\u672A\u6B78\u9084\u5668\u6750\uFF1A+2 \u9EDE

**\u505C\u6B0A\u9580\u6ABB\uFF1A** \u55AE\u4F4D\u7D2F\u8A08\u9054 10 \u9EDE\u81EA\u52D5\u505C\u6B0A

**\u92B7\u9EDE\u65B9\u5F0F\uFF1A**
1. \u52DE\u52D5\u670D\u52D9\u92B7\u9EDE
2. \u5C0D\u4E0D\u5408\u7406\u8A18\u9EDE\u63D0\u51FA\u7533\u5FA9`, category: "\u9055\u898F\u8A18\u9EDE" }, { q: "\u5982\u4F55\u67E5\u770B\u516C\u544A\uFF1F", a: "Dashboard \u9996\u9801\u6703\u986F\u793A\u6700\u65B0\u516C\u544A\uFF0C\u4E5F\u53EF\u81F3\u300C\u516C\u544A\u300D\u5C08\u5340\u67E5\u770B\u6240\u6709\u6B77\u53F2\u516C\u544A\u3002\u516C\u544A\u4F9D\u767C\u5E03\u65E5\u671F\u7531\u65B0\u81F3\u820A\u6392\u5E8F\u3002", category: "\u516C\u544A" }, { q: "\u5E33\u865F\u904E\u671F\u600E\u9EBC\u8FA6\uFF1F", a: `\u5B78\u751F\u5E33\u865F\u6BCF\u5B78\u5E74\u66F4\u65B0\u6709\u6548\u671F\u3002\u82E5\u5DF2\u904E\u671F\uFF1A
1. \u8ACB\u806F\u7E6B\u8AB2\u6307\u7D44\u7533\u8ACB\u7E8C\u671F
2. \u7BA1\u7406\u54E1\u53EF\u6279\u6B21\u66F4\u65B0\u6709\u6548\u671F\u9650

\u6559\u6388\u8207\u8077\u54E1\u5E33\u865F\u9810\u8A2D\u6C38\u4E45\u6709\u6548\uFF08USR_EXPIRE_DATE = NULL\uFF09`, category: "\u5E33\u865F\u7BA1\u7406" }, { q: "\u5982\u4F55\u63D0\u4EA4\u5831\u4FEE\uFF1F", a: `1. \u81F3\u300C\u5831\u4FEE\u7BA1\u7406\u300D\u9801\u9762
2. \u9078\u64C7\u5831\u4FEE\u8A2D\u65BD\uFF08\u5FC5\u586B\uFF09
3. \u586B\u5BEB\u554F\u984C\u63CF\u8FF0\uFF08\u81F3\u5C11 10 \u5B57\uFF09
4. \u53EF\u4E0A\u50B3\u73FE\u5834\u7167\u7247\uFF08\u6700\u591A 3 \u5F35\uFF0C\u6BCF\u5F35 \u2264 5MB\uFF09
5. \u9001\u51FA\u5F8C\u8AB2\u6307\u7D44\u8655\u7406

\u5831\u4FEE\u5B8C\u6210\u5F8C\u8A2D\u65BD\u81EA\u52D5\u6062\u5FA9\u53EF\u7528\u72C0\u614B`, category: "\u5831\u4FEE" }, { q: "\u5982\u4F55\u7533\u8A34\uFF1F", a: `\u81F3\u300C\u7533\u8A34\u300D\u9801\u9762\uFF1A
1. \u9078\u64C7\u985E\u578B\uFF1A\u505C\u6B0A\u7533\u5FA9 / \u9055\u898F\u8A18\u9EDE\u7533\u5FA9 / \u5176\u4ED6\u6AA2\u8209
2. \u586B\u5BEB\u7533\u8A34\u7406\u7531\uFF08\u5FC5\u586B\uFF09\u8207\u4F50\u8B49\u8CC7\u6599
3. \u7B49\u5F85\u7BA1\u7406\u54E1\u5BE9\u6838

\u26A0\uFE0F \u4E0D\u53EF\u5C0D\u540C\u4E00\u6848\u4EF6\u91CD\u8907\u7533\u8A34`, category: "\u7533\u8A34" }], a = { admin: [{ q: "\u5982\u4F55\u5BE9\u6838\u5834\u5730\u9810\u7D04\uFF1F", a: `\u81F3\u300C\u5834\u5730\u9810\u7D04\u300D\u9801\u9762\u67E5\u770B\u5F85\u5BE9\u9810\u7D04\u5217\u8868\uFF0C\u9EDE\u64CA\u300C\u6838\u51C6\u300D\u6216\u300C\u62D2\u7D55\u300D\u6309\u9215\u3002\u62D2\u7D55\u6642\u5FC5\u9808\u586B\u5BEB\u539F\u56E0\u3002\u6838\u51C6\u5F8C\u501F\u7528\u8005\u6536\u5230\u901A\u77E5\u3002

\u4F8B\u884C\u6027\u9810\u7D04\u6574\u7B46\u5BE9\u6838\uFF0C\u6240\u6709\u6642\u6BB5\u540C\u6642\u66F4\u65B0\u70BA\u5DF2\u6838\u51C6\u3002`, category: "\u5BE9\u6838\u7BA1\u7406" }, { q: "\u5982\u4F55\u7BA1\u7406\u9055\u898F\u8A18\u9EDE\uFF1F", a: `\u81F3\u300C\u9055\u898F\u8A18\u9EDE\u300D\u9801\u9762\uFF1A
- \u65B0\u589E\u8A18\u9EDE\uFF1A\u641C\u5C0B\u4F7F\u7528\u8005 \u2192 \u8F38\u5165\u5206\u6578\u8207\u4E8B\u7531
- \u64A4\u92B7\u8A18\u9EDE\uFF1A\u9078\u53D6\u7D00\u9304 \u2192 \u586B\u5BEB\u539F\u56E0
- \u55AE\u4F4D\u7D2F\u8A08 10 \u9EDE\u81EA\u52D5\u505C\u6B0A

\u6240\u6709\u7570\u52D5\u8A18\u9304\u4E0D\u53EF\u522A\u6539\uFF0C\u78BA\u4FDD\u53EF\u8FFD\u6EAF\u6027\u3002`, category: "\u9055\u898F\u7BA1\u7406" }, { q: "\u7D71\u8A08\u5831\u8868\u5982\u4F55\u4F7F\u7528\uFF1F", a: `\u81F3\u300C\u7D71\u8A08\u300D\u9801\u9762\u67E5\u770B\uFF1A
- \u5834\u5730\u4F7F\u7528\u7387\uFF08\u672C\u6708/\u672C\u5B78\u671F\uFF09
- \u5668\u6750\u501F\u7528\u7D71\u8A08
- Simpson \u591A\u6A23\u6027\u6307\u6578
- AI \u5B78\u671F\u7E3D\u7D50\u8A55\u9451\u5831\u544A

\u53EF\u5C07\u5100\u8868\u677F\u6578\u64DA\u532F\u51FA\u70BA CSV \u6216 PDF \u683C\u5F0F\u3002`, category: "\u7D71\u8A08\u5206\u6790" }, { q: "\u5982\u4F55\u6A19\u8A18\u8A2D\u65BD\u7DAD\u4FEE\uFF1F", a: `\u81F3\u300C\u8A2D\u65BD\u7BA1\u7406\u300D\u9801\u9762\uFF1A
1. \u9078\u53D6\u8A2D\u65BD \u2192 \u6539\u70BA\u300C\u7DAD\u4FEE\u4E2D\u300D
2. \u7CFB\u7D71\u81EA\u52D5\u53D6\u6D88\u7DAD\u4FEE\u671F\u9593\u6240\u6709\u672A\u4F86\u9810\u7D04
3. \u53D7\u5F71\u97FF\u501F\u7528\u8005\u6536\u5230\u901A\u77E5
4. \u7DAD\u4FEE\u5B8C\u6210\u5F8C\u6062\u5FA9\u70BA\u300C\u53EF\u7528\u300D`, category: "\u8A2D\u65BD\u7BA1\u7406" }, { q: "\u5982\u4F55\u7BA1\u7406\u55AE\u4F4D\u6210\u54E1\uFF1F", a: `\u81F3\u300C\u55AE\u4F4D\u7BA1\u7406\u300D\u9801\u9762\uFF1A
- \u624B\u52D5\u65B0\u589E/\u79FB\u9664\u6210\u54E1
- \u6279\u6B21\u532F\u5165 CSV \u540D\u55AE
- \u65B0\u589E\u55AE\u4F4D\uFF08\u793E\u5718/\u5B78\u751F\u81EA\u6CBB\u7D44\u7E54/\u884C\u653F\u55AE\u4F4D\uFF09

\u501F\u7528\u8005\u53EA\u80FD\u4EE5\u6709\u6210\u54E1\u8CC7\u683C\u7684\u55AE\u4F4D\u540D\u7FA9\u501F\u7528\u3002`, category: "\u55AE\u4F4D\u7BA1\u7406" }], officer: [{ q: "\u5982\u4F55\u63D0\u4EA4\u6D3B\u52D5\u7533\u8ACB\uFF1F", a: `\u81F3\u300C\u6D3B\u52D5\u7533\u8ACB\u300D\u9801\u9762\uFF1A
1. \u586B\u5BEB\u6D3B\u52D5\u540D\u7A31\u3001\u4E3B\u8FA6\u55AE\u4F4D\u3001\u65E5\u671F\u6642\u6BB5\u3001\u9810\u8A08\u4EBA\u6578\u7B49
2. \u52FE\u9078\u4E3B\u984C\u5206\u985E\u8207\u7279\u6B8A\u4E8B\u9805\uFF08\u9152\u7CBE/\u660E\u706B/\u6524\u4F4D\uFF09
3. \u7CFB\u7D71\u7522\u751F\u542B\u6D41\u6C34\u7DE8\u865F\u7684 PDF \u7533\u8ACB\u55AE
4. \u5217\u5370\u5F8C\u6301\u7D19\u672C\u81F3\u8AB2\u6307\u7D44\u5BE9\u6838

\u6838\u51C6\u5F8C\u60A8\u7684\u55AE\u4F4D\u6210\u54E1\u5373\u53EF\u9001\u51FA\u5834\u5730/\u5668\u6750\u501F\u7528\u7533\u8ACB\u3002`, category: "\u6D3B\u52D5\u7533\u8ACB" }, { q: "\u793E\u5718\u6210\u54E1\u5982\u4F55\u7BA1\u7406\uFF1F", a: "\u793E\u5718\u5E79\u90E8\u53EF\u81F3\u300C\u55AE\u4F4D\u7BA1\u7406\u300D\u9801\u9762\u67E5\u770B\u6210\u54E1\u5217\u8868\u3002\u5982\u9700\u65B0\u589E\u6216\u79FB\u9664\u6210\u54E1\uFF0C\u8ACB\u6D3D\u8AB2\u6307\u7D44\u7BA1\u7406\u54E1\u8655\u7406\u3002", category: "\u793E\u5718\u7BA1\u7406" }, { q: "AI \u529F\u80FD\u6709\u54EA\u4E9B\uFF1F", a: `\u793E\u5718\u5E79\u90E8\u53EF\u4F7F\u7528\u4EE5\u4E0B AI \u529F\u80FD\uFF1A
- AI \u52A9\u7406\u804A\u5929\uFF1A\u67E5\u8A62\u6CD5\u898F\u3001\u501F\u7528\u898F\u5247
- \u6D3B\u52D5\u4F01\u5283\u66F8\u751F\u6210\uFF1A\u8F38\u5165\u57FA\u672C\u8CC7\u8A0A\u81EA\u52D5\u7522\u751F
- \u6D3B\u52D5\u98A8\u96AA\u9810\u5BE9\uFF1A\u6AA2\u67E5\u4EBA\u6578\u3001\u7279\u6B8A\u4E8B\u9805\u662F\u5426\u5408\u898F`, category: "AI \u529F\u80FD" }, { q: "\u5982\u4F55\u767B\u8A18\u5834\u5354\u5927\u6703\uFF1F", a: `\u5728\u5834\u5354\u767B\u8A18\u622A\u6B62\u65E5\u524D\uFF1A
1. \u81F3\u300C\u5834\u5730\u9810\u7D04\u300D\u2192\u300C\u5834\u5354\u5927\u6703\u767B\u8A18\u300D
2. \u586B\u5BEB\u5834\u5730\u3001\u6642\u6BB5\u3001\u4EE3\u8868\u55AE\u4F4D\u3001\u6D3B\u52D5\u540D\u7A31
3. \u53EF\u67E5\u770B\u5DF2\u767B\u8A18\u6E05\u55AE\u78BA\u8A8D\u662F\u5426\u885D\u7A81

\u5834\u5354\u5927\u6703\u7D50\u675F\u5F8C\u7CFB\u7D71\u63D0\u9192\u5B8C\u6210\u7D19\u672C\u6D41\u7A0B\u3002\u903E\u671F\u672A\u5B8C\u6210\u8005\u8996\u540C\u653E\u68C4\u3002`, category: "\u5834\u5354\u5927\u6703" }], professor: [{ q: "\u6559\u6388\u5982\u4F55\u501F\u7528\u6559\u5BA4\uFF1F", a: `\u8207\u4E00\u822C\u5834\u5730\u9810\u7D04\u6D41\u7A0B\u76F8\u540C\uFF1A
1. \u63D0\u4EA4\u6D3B\u52D5\u7533\u8ACB\uFF08\u53EF\u4EE5\u884C\u653F\u55AE\u4F4D\u6216\u6307\u5C0E\u793E\u5718\u540D\u7FA9\uFF09
2. \u53D6\u5F97\u6838\u51C6\u5F8C\u9810\u7D04\u5834\u5730
3. \u6559\u6388\u8EAB\u4EFD\u7684\u7533\u8ACB\u5BE9\u6838\u512A\u5148\u8655\u7406`, category: "\u5834\u5730\u9810\u7D04" }, { q: "\u5982\u4F55\u67E5\u770B\u6307\u5C0E\u793E\u5718\u72C0\u614B\uFF1F", a: "\u81F3 Dashboard \u53EF\u67E5\u770B\u76F8\u95DC\u7D71\u8A08\u3002\u5982\u9700\u6307\u5C0E\u793E\u5718\u7684\u8A73\u7D30\u72C0\u6CC1\uFF0C\u53EF\u81F3\u300C\u55AE\u4F4D\u7BA1\u7406\u300D\u67E5\u770B\u793E\u5718\u8CC7\u8A0A\u3002", category: "\u793E\u5718\u6307\u5C0E" }], student: [{ q: "\u6211\u53EF\u4EE5\u500B\u4EBA\u501F\u7528\u5834\u5730\u55CE\uFF1F", a: `\u500B\u4EBA\u7121\u6CD5\u76F4\u63A5\u501F\u7528\u5834\u5730\uFF0C\u9700\u900F\u904E\u6240\u5C6C\u55AE\u4F4D\uFF08\u793E\u5718/\u7CFB\u5B78\u6703\uFF09\u63D0\u51FA\u7533\u8ACB\u3002

\u5982\u5C1A\u672A\u52A0\u5165\u4EFB\u4F55\u55AE\u4F4D\uFF0C\u8ACB\uFF1A
1. \u806F\u7E6B\u793E\u5718\u5E79\u90E8\u7533\u8ACB\u52A0\u5165
2. \u6216\u81F3\u8AB2\u6307\u7D44\u767B\u8A18\u52A0\u5165`, category: "\u5834\u5730\u9810\u7D04" }, { q: "\u5982\u4F55\u52A0\u5165\u793E\u5718\uFF1F", a: "\u8ACB\u806F\u7E6B\u793E\u5718\u5E79\u90E8\u6216\u81F3\u8AB2\u6307\u7D44\u767B\u8A18\u52A0\u5165\u3002\u52A0\u5165\u5F8C\u5373\u53EF\u5728\u7CFB\u7D71\u4E2D\u770B\u5230\u6240\u5C6C\u55AE\u4F4D\uFF0C\u4E26\u4EE5\u8A72\u55AE\u4F4D\u540D\u7FA9\u53C3\u8207\u501F\u7528\u7533\u8ACB\u3002", category: "\u793E\u5718" }, { q: "\u6211\u7684\u9055\u898F\u9EDE\u6578\u600E\u9EBC\u67E5\uFF1F", a: `\u81F3\u300C\u500B\u4EBA\u4E2D\u5FC3\u300D\u9801\u9762\u53EF\u67E5\u770B\uFF1A
- \u76EE\u524D\u9055\u898F\u9EDE\u6578
- \u5B8C\u6574\u7570\u52D5\u6B77\u7A0B
- \u6BCF\u7B46\u8A18\u9304\u5305\u542B\u65E5\u671F\u3001\u4E8B\u7531\u3001\u8B8A\u52D5\u9EDE\u6578

\u5C0D\u6709\u7570\u8B70\u7684\u8A18\u9EDE\u53EF\u9EDE\u9078\u300C\u7533\u5FA9\u300D\u9032\u5165\u7533\u8A34\u6D41\u7A0B\u3002`, category: "\u9055\u898F\u8A18\u9EDE" }], staff: [{ q: "\u8077\u54E1\u5982\u4F55\u7533\u8ACB\u884C\u653F\u7528\u9014\u5834\u5730\uFF1F", a: `\u8077\u54E1\u53EF\u4EE5\u884C\u653F\u55AE\u4F4D\u540D\u7FA9\u63D0\u4EA4\u6D3B\u52D5\u7533\u8ACB\u8207\u5834\u5730\u9810\u7D04\uFF0C\u6D41\u7A0B\u8207\u793E\u5718\u76F8\u540C\u4F46\u5BE9\u6838\u8F03\u5FEB\u3002

\u9700\u5148\u78BA\u8A8D\u5DF2\u52A0\u5165\u5C0D\u61C9\u884C\u653F\u55AE\u4F4D\u7684\u6210\u54E1\u540D\u55AE\u4E2D\u3002`, category: "\u5834\u5730\u9810\u7D04" }, { q: "\u5982\u4F55\u5831\u4FEE\u8A2D\u65BD\uFF1F", a: `\u81F3\u300C\u5831\u4FEE\u7BA1\u7406\u300D\u9801\u9762\uFF1A
1. \u9078\u64C7\u8A2D\u65BD
2. \u63CF\u8FF0\u554F\u984C\uFF08\u81F3\u5C11 10 \u5B57\uFF09
3. \u53EF\u4E0A\u50B3\u7167\u7247
4. \u9001\u51FA\u5F8C\u7531\u8AB2\u6307\u7D44\u8655\u7406`, category: "\u5831\u4FEE" }] };
  return e.json({ common: s, roleSpecific: a[t] || a.student || [], role: t, regulations: [{ name: "\u6D3B\u52D5\u7533\u8ACB\u8FA6\u6CD5 v2.1", summary: "\u898F\u7BC4\u6D3B\u52D5\u7533\u8ACB\u6D41\u7A0B\u3001\u5BE9\u6838\u6A19\u6E96\u3001\u542B\u9152\u7CBE/\u660E\u706B\u9808\u63D0\u524D30\u5929\u9001\u4EF6" }, { name: "\u5834\u5730\u4F7F\u7528\u7BA1\u7406\u898F\u5247 v3.0", summary: "\u5834\u5730\u501F\u7528\u8CC7\u683C\u3001\u6642\u6BB5\u9650\u5236\uFF08\u501F\u7528\u65E5\u524D7\u5929\uFF09\u3001\u6B78\u9084\u898F\u5B9A" }, { name: "\u5668\u6750\u501F\u7528\u7BA1\u7406\u8FA6\u6CD5 v1.5", summary: "\u5668\u6750\u501F\u7528\u6D41\u7A0B\u3001\u64CD\u4F5C\u8B49\u8981\u6C42\u3001\u9818\u53D6\u6642\u6BB5\u3001\u903E\u671F\u8655\u7406" }, { name: "\u9055\u898F\u8A18\u9EDE\u8655\u7406\u8981\u9EDE v2.0", summary: "\u9055\u898F\u985E\u578B\u3001\u8A18\u9EDE\u6A19\u6E96\uFF08\u903E\u6642+2/\u640D\u58DE+3/\u672A\u5230\u5834+1\uFF09\u3001\u505C\u6B0A\u9580\u6ABB10\u9EDE" }, { name: "\u5B89\u5168\u7BA1\u7406\u898F\u7BC4 v1.8", summary: "\u5927\u578B\u6D3B\u52D5\u5B89\u5168\u8A08\u756B\u3001\u6D88\u9632\u8981\u6C42\u3001\u4FDD\u96AA\u898F\u5B9A" }] });
});
var Y = new I();
Y.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("role");
  let a = "SELECT USR_ID, USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_IS_ADMIN, USR_SUSPENDED, USR_EXPIRE_DATE, USR_AVATAR, USR_CREATED_AT FROM User WHERE 1=1";
  const n = [];
  s && (a += " AND USR_ROLE = ?", n.push(s)), a += " ORDER BY USR_ID";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
Y.get("/:id", async (e) => {
  const t = e.env.DB, s = await t.prepare("SELECT USR_ID, USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_IS_ADMIN, USR_SUSPENDED, USR_EXPIRE_DATE, USR_AVATAR, USR_CREATED_AT FROM User WHERE USR_ID = ?").bind(e.req.param("id")).first();
  if (!s)
    return e.json({ error: "\u4F7F\u7528\u8005\u4E0D\u5B58\u5728" }, 404);
  const { results: a } = await t.prepare("SELECT UM.*, U.UNIT_NAME FROM UnitMember UM LEFT JOIN Unit U ON UM.UM_UNIT_ID = U.UNIT_ID WHERE UM.UM_USR_ID = ? AND UM.UM_ACTIVE = 1").bind(e.req.param("id")).all(), { results: n } = await t.prepare("SELECT EC.*, ECT.ECT_NAME FROM EquipmentCert EC LEFT JOIN EquipmentCertType ECT ON EC.EC_TYPE_ID = ECT.ECT_ID WHERE EC.EC_USR_ID = ? AND EC.EC_STATUS = 0").bind(e.req.param("id")).all();
  return e.json({ data: s, units: a, certs: n });
});
Y.put("/:id", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE User SET USR_NAME=?, USR_PHONE=?, USR_ROLE=?, USR_AVATAR=? WHERE USR_ID=?").bind(a.name, a.phone || null, a.role, a.avatar || null, s).run(), e.json({ success: true });
});
Y.patch("/:id/suspend", async (e) => {
  const t = e.env.DB, s = e.req.param("id");
  return await t.prepare("UPDATE User SET USR_SUSPENDED = 1 WHERE USR_ID = ?").bind(s).run(), e.json({ success: true, message: "\u5E33\u865F\u5DF2\u505C\u6B0A" });
});
Y.patch("/:id/unsuspend", async (e) => {
  const t = e.env.DB, s = e.req.param("id");
  return await t.prepare("UPDATE User SET USR_SUSPENDED = 0 WHERE USR_ID = ?").bind(s).run(), e.json({ success: true, message: "\u5E33\u865F\u5DF2\u89E3\u9664\u505C\u6B0A" });
});
Y.patch("/:id/toggle-admin", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await t.prepare("SELECT USR_IS_ADMIN FROM User WHERE USR_ID = ?").bind(s).first(), n = (a == null ? void 0 : a.USR_IS_ADMIN) === 1 ? 0 : 1;
  return await t.prepare("UPDATE User SET USR_IS_ADMIN = ? WHERE USR_ID = ?").bind(n, s).run(), e.json({ success: true, isAdmin: n });
});
Y.get("/:id/profile", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await t.prepare("SELECT USR_ID, USR_EMAIL, USR_NAME, USR_ROLE, USR_AVATAR, USR_CREATED_AT FROM User WHERE USR_ID = ?").bind(s).first(), { results: n } = await t.prepare("SELECT VB.*, F.FAC_NAME FROM VenueBooking VB LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE VB.VB_USR_ID = ? ORDER BY VB.VB_CREATED_AT DESC LIMIT 10").bind(s).all(), { results: i } = await t.prepare("SELECT EL.* FROM EquipmentLoan EL WHERE EL.EL_USR_ID = ? ORDER BY EL.EL_CREATED_AT DESC LIMIT 10").bind(s).all(), { results: r } = await t.prepare("SELECT * FROM AppealCase WHERE AC_USR_ID = ? ORDER BY AC_CREATED_AT DESC LIMIT 5").bind(s).all(), { results: c } = await t.prepare("SELECT RR.*, F.FAC_NAME FROM RepairRequest RR LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID WHERE RR.RR_USR_ID = ? ORDER BY RR.RR_CREATED_AT DESC LIMIT 5").bind(s).all(), { results: o } = await t.prepare("SELECT * FROM ViolationPointLog WHERE VPL_USR_ID = ? ORDER BY VPL_CREATED_AT DESC LIMIT 10").bind(s).all();
  return e.json({ user: a, bookings: n, loans: i, appeals: r, repairs: c, violations: o });
});
Y.patch("/:id/avatar", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return a.avatar ? (await t.prepare("UPDATE User SET USR_AVATAR = ? WHERE USR_ID = ?").bind(a.avatar, s).run(), e.json({ success: true, avatar: a.avatar, message: "\u5927\u982D\u8CBC\u5DF2\u66F4\u65B0" })) : e.json({ success: false, message: "\u8ACB\u9078\u64C7\u5927\u982D\u8CBC" }, 400);
});
var ke = new I();
ke.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("userId");
  let a = "SELECT LSA.*, U.USR_NAME FROM LaborServiceApplication LSA LEFT JOIN User U ON LSA.LSA_USR_ID = U.USR_ID WHERE 1=1";
  const n = [];
  s && (a += " AND LSA.LSA_USR_ID = ?", n.push(s)), a += " ORDER BY LSA.LSA_CREATED_AT DESC";
  const { results: i } = await t.prepare(a).bind(...n).all();
  return e.json({ data: i });
});
ke.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json(), a = await t.prepare("INSERT INTO LaborServiceApplication (LSA_USR_ID, LSA_SERVICE_TYPE, LSA_SERVICE_DATE, LSA_HOURS, LSA_POINTS_TO_DEDUCT) VALUES (?, ?, ?, ?, ?)").bind(s.userId, s.serviceType, s.serviceDate, s.hours, s.pointsToDeduct).run();
  return e.json({ success: true, id: a.meta.last_row_id, message: "\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\u7533\u8ACB\u5DF2\u9001\u51FA" }, 201);
});
ke.patch("/:id/approve", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json(), n = await t.prepare("SELECT * FROM LaborServiceApplication WHERE LSA_ID = ?").bind(s).first();
  return n ? (await t.prepare("UPDATE LaborServiceApplication SET LSA_STATUS=1, LSA_ADMIN_ID=?, LSA_ADMIN_NOTE=?, LSA_REVIEWED_AT=datetime('now') WHERE LSA_ID=?").bind(a.adminId || 1, a.note || null, s).run(), await t.prepare("INSERT INTO ViolationPointLog (VPL_TARGET_TYPE, VPL_USR_ID, VPL_DELTA, VPL_REASON, VPL_SOURCE, VPL_ADMIN_ID, VPL_REF_ID) VALUES (0, ?, ?, ?, 3, ?, ?)").bind(n.LSA_USR_ID, -n.LSA_POINTS_TO_DEDUCT, "\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE", a.adminId || 1, s).run(), e.json({ success: true, message: "\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\u5DF2\u6838\u51C6" })) : e.json({ error: "\u7533\u8ACB\u4E0D\u5B58\u5728" }, 404);
});
ke.patch("/:id/reject", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE LaborServiceApplication SET LSA_STATUS=2, LSA_ADMIN_ID=?, LSA_ADMIN_NOTE=?, LSA_REVIEWED_AT=datetime('now') WHERE LSA_ID=?").bind(a.adminId || 1, a.note || "\u672A\u7B26\u5408\u689D\u4EF6", s).run(), e.json({ success: true, message: "\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\u5DF2\u99C1\u56DE" });
});
var pe = new I();
pe.get("/", async (e) => {
  const t = e.env.DB, s = e.req.query("semester"), a = e.req.query("status");
  let n = `SELECT VC.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME
    FROM VenueCoordination VC
    LEFT JOIN User U ON VC.VC_USR_ID = U.USR_ID
    LEFT JOIN Unit UN ON VC.VC_UNIT_ID = UN.UNIT_ID
    LEFT JOIN Facility F ON VC.VC_FAC_ID = F.FAC_ID WHERE 1=1`;
  const i = [];
  s && (n += " AND VC.VC_SEMESTER = ?", i.push(s)), a !== void 0 && (n += " AND VC.VC_STATUS = ?", i.push(Number(a))), n += " ORDER BY VC.VC_DAY_OF_WEEK, VC.VC_TIME_START";
  const { results: r } = await t.prepare(n).bind(...i).all();
  return e.json({ data: r });
});
pe.get("/:id", async (e) => {
  const s = await e.env.DB.prepare(`SELECT VC.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME
    FROM VenueCoordination VC LEFT JOIN User U ON VC.VC_USR_ID = U.USR_ID
    LEFT JOIN Unit UN ON VC.VC_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VC.VC_FAC_ID = F.FAC_ID
    WHERE VC.VC_ID = ?`).bind(e.req.param("id")).first();
  return s ? e.json({ data: s }) : e.json({ error: "\u767B\u8A18\u4E0D\u5B58\u5728" }, 404);
});
pe.post("/", async (e) => {
  const t = e.env.DB, s = await e.req.json();
  if (!s.unitId || !s.facId || s.dayOfWeek === void 0 || !s.timeStart || !s.timeEnd || !s.semester)
    return e.json({ success: false, message: "\u8ACB\u586B\u5BEB\u5FC5\u8981\u6B04\u4F4D" }, 400);
  if (await t.prepare(`SELECT VC_ID FROM VenueCoordination WHERE VC_FAC_ID = ? AND VC_DAY_OF_WEEK = ? AND VC_SEMESTER = ? AND VC_STATUS IN (0, 1)
     AND VC_TIME_START < ? AND VC_TIME_END > ?`).bind(s.facId, s.dayOfWeek, s.semester, s.timeEnd, s.timeStart).first())
    return e.json({ success: false, message: "\u6B64\u6642\u6BB5\u5DF2\u6709\u5176\u4ED6\u55AE\u4F4D\u767B\u8A18" }, 409);
  const n = await t.prepare("INSERT INTO VenueCoordination (VC_USR_ID, VC_UNIT_ID, VC_FAC_ID, VC_DAY_OF_WEEK, VC_TIME_START, VC_TIME_END, VC_PURPOSE, VC_SEMESTER, VC_STATUS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)").bind(s.userId, s.unitId, s.facId, s.dayOfWeek, s.timeStart, s.timeEnd, s.purpose || null, s.semester).run();
  return e.json({ success: true, id: n.meta.last_row_id, message: "\u5834\u5354\u5927\u6703\u767B\u8A18\u5DF2\u9001\u51FA" }, 201);
});
pe.patch("/:id/approve", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE VenueCoordination SET VC_STATUS = 1, VC_ADMIN_NOTE = ? WHERE VC_ID = ?").bind(a.note || null, s).run(), e.json({ success: true, message: "\u767B\u8A18\u5DF2\u6838\u51C6" });
});
pe.patch("/:id/reject", async (e) => {
  const t = e.env.DB, s = e.req.param("id"), a = await e.req.json();
  return await t.prepare("UPDATE VenueCoordination SET VC_STATUS = 2, VC_ADMIN_NOTE = ? WHERE VC_ID = ?").bind(a.note || "\u672A\u7B26\u5408\u689D\u4EF6", s).run(), e.json({ success: true, message: "\u767B\u8A18\u5DF2\u99C1\u56DE" });
});
pe.delete("/:id", async (e) => (await e.env.DB.prepare("DELETE FROM VenueCoordination WHERE VC_ID = ?").bind(e.req.param("id")).run(), e.json({ success: true })));
function Is() {
  return `<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>\u8F14\u4EC1\u5927\u5B78\u8AB2\u6307\u7D44 - \u5668\u6750\u8207\u5834\u5730\u9810\u7D04\u5E73\u53F0</title>
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
    <h3 class="font-bold text-fju-blue text-lg mb-2 text-center"><i class="fas fa-user-circle mr-2 text-fju-yellow"></i>\u9078\u64C7\u4F60\u7684\u5927\u982D\u8CBC</h3>
    <p class="text-xs text-gray-400 text-center mb-4">\u9032\u5165\u7CFB\u7D71\u524D\u8ACB\u5148\u9078\u64C7\u4E00\u500B\u5927\u982D\u8CBC</p>
    <div class="grid grid-cols-4 gap-3" id="avatar-grid"></div>
    <button onclick="confirmAvatar()" id="avatar-confirm-btn" disabled class="w-full btn-yellow py-3 mt-4 disabled:opacity-50 disabled:cursor-not-allowed">\u78BA\u8A8D\u9078\u64C7</button>
  </div>
</div>

<!-- ========== Login Page (Prompt7 9B: tab\u5207\u63DB) ========== -->
<div id="login-page" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-fju-blue via-fju-blue-light to-fju-blue p-4">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="w-20 h-20 bg-fju-yellow rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg">
        <i class="fas fa-university text-fju-blue text-3xl"></i>
      </div>
      <h1 class="text-2xl font-bold text-white">\u8F14\u4EC1\u5927\u5B78</h1>
      <p class="text-fju-yellow text-sm mt-1">\u8AB2\u6307\u7D44\u5668\u6750\u8207\u5834\u5730\u9810\u7D04\u5E73\u53F0 v3.2</p>
    </div>
    <div class="flex bg-white/10 rounded-fju p-1 mb-4">
      <button onclick="switchAuthTab('login')" id="tab-login" class="flex-1 py-2 text-sm rounded-fju bg-white text-fju-blue font-bold transition-all">\u767B\u5165</button>
      <button onclick="switchAuthTab('register')" id="tab-register" class="flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all">\u5EFA\u7ACB\u5E33\u865F</button>
      <button onclick="switchAuthTab('forgot')" id="tab-forgot" class="flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all">\u5FD8\u8A18\u5BC6\u78BC</button>
    </div>
    <!-- Login Form -->
    <div id="form-login" class="card fade-in">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-sign-in-alt mr-2"></i>\u5E33\u865F\u767B\u5165</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">\u5B78\u6821 Email</label><input id="login-email" type="email" placeholder="example@mail.fju.edu.tw" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">\u5BC6\u78BC</label><input id="login-password" type="password" placeholder="\u8ACB\u8F38\u5165\u5BC6\u78BC" class="input-field" onkeypress="if(event.key==='Enter')doLogin()"></div>
        <button onclick="doLogin()" class="w-full btn-yellow py-3"><i class="fas fa-sign-in-alt mr-2"></i>\u767B\u5165</button>
        <div id="login-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
        <button onclick="switchAuthTab('forgot')" class="flex-1 text-xs px-3 py-2 rounded-fju bg-gray-100 text-gray-600 hover:bg-gray-200"><i class="fas fa-key mr-1"></i>\u5FD8\u8A18\u5BC6\u78BC</button>
        <button onclick="switchAuthTab('register')" class="flex-1 text-xs px-3 py-2 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20"><i class="fas fa-user-plus mr-1"></i>\u5EFA\u7ACB\u5E33\u865F</button>
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="text-xs text-gray-400 mb-2">\u5FEB\u901F Demo \u767B\u5165\uFF1A</p>
        <div class="grid grid-cols-3 gap-2">
          <button onclick="quickLogin('admin01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20">\u7BA1\u7406\u54E1</button>
          <button onclick="quickLogin('s1100001@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-yellow/20 text-fju-blue hover:bg-fju-yellow/30">\u5E79\u90E8A(\u738B)</button>
          <button onclick="quickLogin('s1100002@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-orange-100 text-orange-700 hover:bg-orange-200">\u5E79\u90E8B(\u674E)</button>
          <button onclick="quickLogin('s1100003@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-green/10 text-fju-green hover:bg-fju-green/20">\u5B78\u751F</button>
          <button onclick="quickLogin('prof01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-purple-100 text-purple-700 hover:bg-purple-200">\u6559\u6388</button>
          <button onclick="quickLogin('staff01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-teal-100 text-teal-700 hover:bg-teal-200">\u8077\u54E1</button>
        </div>
        <p class="text-xs text-gray-400 mt-2">* \u501F\u7528\u8005A(\u738B\u5C0F\u660E)\u8207\u501F\u7528\u8005B(\u674E\u5C0F\u82B1)\u6A21\u64EC\u9810\u7D04\u885D\u7A81\u5834\u666F</p>
      </div>
    </div>
    <!-- Register Form (Prompt7 9C: \u8A3B\u518A\u6642\u9078\u8EAB\u5206) -->
    <div id="form-register" class="card fade-in hidden">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-user-plus mr-2"></i>\u5EFA\u7ACB\u5E33\u865F</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">\u59D3\u540D *</label><input id="reg-name" type="text" placeholder="\u738B\u5C0F\u660E" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">\u5B78\u6821 Email *</label><input id="reg-email" type="email" placeholder="s1234567@mail.fju.edu.tw" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">\u624B\u6A5F\u865F\u78BC</label><input id="reg-phone" type="tel" placeholder="0912345678" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">\u8EAB\u4EFD\u89D2\u8272 *</label>
          <select id="reg-role" class="input-field">
            <option value="student">\u5B78\u751F</option>
            <option value="officer">\u793E\u5718\u5E79\u90E8</option>
            <option value="professor">\u6559\u6388</option>
            <option value="staff">\u884C\u653F\u8077\u54E1</option>
          </select>
          <p class="text-xs text-gray-400 mt-1">* \u793E\u5718\u5E79\u90E8\u8EAB\u5206\u9700\u7D93\u8AB2\u6307\u7D44\u78BA\u8A8D\u5F8C\u751F\u6548</p>
        </div>
        <div><label class="text-xs text-gray-500 block mb-1">\u5BC6\u78BC * (\u81F3\u5C118\u5B57\u5143)</label><input id="reg-password" type="password" placeholder="\u8ACB\u8A2D\u5B9A\u5BC6\u78BC" class="input-field"></div>
        <button onclick="doRegister()" class="w-full btn-primary py-3"><i class="fas fa-user-plus mr-2"></i>\u5EFA\u7ACB\u5E33\u865F</button>
        <div id="reg-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-3 text-center"><button onclick="switchAuthTab('login')" class="text-xs text-fju-blue hover:underline">\u5DF2\u6709\u5E33\u865F\uFF1F\u8FD4\u56DE\u767B\u5165</button></div>
    </div>
    <!-- Forgot Password Form -->
    <div id="form-forgot" class="card fade-in hidden">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-key mr-2"></i>\u5FD8\u8A18\u5BC6\u78BC</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">\u5B78\u6821 Email</label><input id="forgot-email" type="email" placeholder="example@mail.fju.edu.tw" class="input-field"></div>
        <button onclick="doForgotPassword()" class="w-full btn-primary py-3"><i class="fas fa-envelope mr-2"></i>\u5BC4\u9001\u91CD\u8A2D\u9023\u7D50</button>
        <div id="forgot-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-3 text-center"><button onclick="switchAuthTab('login')" class="text-xs text-fju-blue hover:underline">\u8FD4\u56DE\u767B\u5165</button></div>
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
      <button onclick="doLogout()" class="sidebar-link w-full text-red-500 hover:bg-red-50"><i class="fas fa-sign-out-alt w-5"></i><span>\u767B\u51FA</span></button>
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
const roleNames = { admin:'\u8AB2\u6307\u7D44\u7BA1\u7406\u54E1', officer:'\u793E\u5718\u5E79\u90E8', professor:'\u6559\u6388', student:'\u5B78\u751F', staff:'\u884C\u653F\u8077\u54E1' };
const badgeColors = { admin:'bg-fju-blue text-white', officer:'bg-fju-yellow text-fju-blue', professor:'bg-purple-100 text-purple-700', student:'bg-fju-green/10 text-fju-green', staff:'bg-teal-100 text-teal-700' };
const avatarEmojis = ['\u{1F60A}','\u{1F60E}','\u{1F913}','\u{1F9D1}\u200D\u{1F4BB}','\u{1F469}\u200D\u{1F393}','\u{1F468}\u200D\u{1F3EB}','\u{1F98A}','\u{1F431}','\u{1F436}','\u{1F981}','\u{1F43C}','\u{1F428}','\u{1F989}','\u{1F42C}','\u{1F31F}','\u{1F3AF}'];

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
  if (!email) return showMsg('login-msg', '\u8ACB\u8F38\u5165 Email', 'error');
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
  } catch(e) { showMsg('login-msg', '\u9023\u7DDA\u932F\u8AA4', 'error'); }
}

function quickLogin(email) { document.getElementById('login-email').value = email; document.getElementById('login-password').value = 'demo123'; doLogin(); }

async function doRegister() {
  const name = document.getElementById('reg-name').value;
  const email = document.getElementById('reg-email').value;
  const phone = document.getElementById('reg-phone').value;
  const role = document.getElementById('reg-role').value;
  const password = document.getElementById('reg-password').value;
  if (!name || !email || !password) return showMsg('reg-msg', '\u8ACB\u586B\u5BEB\u5FC5\u8981\u6B04\u4F4D', 'error');
  if (password.length < 8) return showMsg('reg-msg', '\u5BC6\u78BC\u81F3\u5C11\u9700 8 \u5B57\u5143', 'error');
  try {
    const res = await fetch(API+'/auth/register', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({name, email, phone, role, password}) });
    const data = await res.json();
    showMsg('reg-msg', data.message, data.success ? 'success' : 'error');
    if (data.success) setTimeout(() => switchAuthTab('login'), 1500);
  } catch(e) { showMsg('reg-msg', '\u9023\u7DDA\u932F\u8AA4', 'error'); }
}

async function doForgotPassword() {
  const email = document.getElementById('forgot-email').value;
  if (!email) return showMsg('forgot-msg', '\u8ACB\u8F38\u5165 Email', 'error');
  try {
    const res = await fetch(API+'/auth/forgot-password', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({email}) });
    const data = await res.json();
    showMsg('forgot-msg', data.message, data.success ? 'success' : 'error');
  } catch(e) { showMsg('forgot-msg', '\u9023\u7DDA\u932F\u8AA4', 'error'); }
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

// Prompt7 7A: \u79FB\u9664\u6D3B\u52D5\u7246; sidebar\u4F9D\u89D2\u8272\u986F\u793A\u4E0D\u540C\u529F\u80FD
function buildSidebar() {
  const role = currentUser?.role || 'student';
  const isAdmin = currentUser?.isAdmin === 1;
  const items = [
    { id:'dashboard', icon:'fa-tachometer-alt', label:'\u5100\u8868\u677F', roles:['admin','officer','professor','student','staff'] },
    { id:'activities', icon:'fa-calendar-check', label:'\u6D3B\u52D5\u7533\u8ACB', roles:['admin','officer','professor','staff'] },
    { id:'venues', icon:'fa-map-marker-alt', label:'\u5834\u5730\u9810\u7D04', roles:['admin','officer','professor','student','staff'] },
    { id:'equipment', icon:'fa-tools', label:'\u5668\u6750\u501F\u7528', roles:['admin','officer','professor','staff'] },
    { id:'coordination', icon:'fa-users-rectangle', label:'\u5834\u5354\u5927\u6703', roles:['admin','officer'] },
    { id:'conflicts', icon:'fa-handshake', label:'\u885D\u7A81\u5354\u8ABF', roles:['admin','officer'] },
    { id:'repairs', icon:'fa-wrench', label:'\u5831\u4FEE\u7BA1\u7406', roles:['admin','officer','student','staff'] },
    { id:'appeals', icon:'fa-gavel', label:'\u7533\u8A34\u7BA1\u7406', roles:['admin','officer','student'] },
    { id:'announcements', icon:'fa-bullhorn', label:'\u516C\u544A\u8CC7\u8A0A', roles:['admin','officer','professor','student','staff'] },
    { id:'faq', icon:'fa-robot', label:'FAQ / AI \u52A9\u7406', roles:['admin','officer','professor','student','staff'] },
    { id:'stats', icon:'fa-chart-bar', label:'\u7D71\u8A08\u5831\u8868', roles:['admin'] },
    { id:'users', icon:'fa-users-cog', label:'\u4F7F\u7528\u8005\u7BA1\u7406', roles:['admin'] },
    { id:'violations', icon:'fa-exclamation-triangle', label:'\u9055\u898F\u8A18\u9EDE', roles:['admin','officer','student'] },
    { id:'labor', icon:'fa-hands-helping', label:'\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE', roles:['admin','officer','student'] },
    { id:'units', icon:'fa-building', label:'\u55AE\u4F4D\u7BA1\u7406', roles:['admin','officer'] },
    { id:'profile', icon:'fa-user-circle', label:'\u500B\u4EBA\u4E2D\u5FC3', roles:['admin','officer','professor','student','staff'] },
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
  const titles = { dashboard:'\u5100\u8868\u677F', activities:'\u6D3B\u52D5\u7533\u8ACB\u7BA1\u7406', venues:'\u5834\u5730\u9810\u7D04\u7BA1\u7406', equipment:'\u5668\u6750\u501F\u7528\u7BA1\u7406', coordination:'\u5834\u5354\u5927\u6703', conflicts:'\u885D\u7A81\u5354\u8ABF', repairs:'\u5831\u4FEE\u7BA1\u7406', appeals:'\u7533\u8A34\u7BA1\u7406', announcements:'\u516C\u544A\u8CC7\u8A0A', faq:'FAQ / AI \u52A9\u7406', stats:'\u7D71\u8A08\u5831\u8868', users:'\u4F7F\u7528\u8005\u7BA1\u7406', violations:'\u9055\u898F\u8A18\u9EDE\u7BA1\u7406', labor:'\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE', units:'\u55AE\u4F4D\u7BA1\u7406', profile:'\u500B\u4EBA\u4E2D\u5FC3' };
  document.getElementById('page-title').textContent = titles[page] || page;
  document.getElementById('page-content').innerHTML = '<div class="text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2 text-sm">\u8F09\u5165\u4E2D...</p></div>';
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
  h += '<div class="card bg-gradient-to-r from-fju-blue to-fju-blue-light text-white"><div class="flex items-center justify-between"><div><h3 class="text-lg font-bold">\u6B61\u8FCE\u56DE\u4F86\uFF0C'+(currentUser?.name||'')+'</h3><p class="text-sm text-white/70 mt-1">'+roleNames[role]+' | '+(currentUser?.units?.length ? '\u6240\u5C6C\u55AE\u4F4D\uFF1A'+currentUser.units.map(u=>u.UNIT_NAME).join('\u3001') : '\u5C1A\u672A\u52A0\u5165\u4EFB\u4F55\u55AE\u4F4D')+'</p></div><div class="text-5xl">'+(currentUser?.avatar||'\u{1F3EB}')+'</div></div></div>';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">';
  h += statCard('fa-building','\u5834\u5730\u7E3D\u6578',d.totalFacilities,'blue');
  h += statCard('fa-tools','\u5668\u6750\u9805\u76EE',d.totalEquipment,'green');
  h += statCard('fa-users','\u4F7F\u7528\u8005',d.totalUsers,'purple');
  h += statCard('fa-clock','\u5F85\u5BE9\u9810\u7D04',d.pendingBookings,'yellow');
  h += statCard('fa-wrench','\u5F85\u4FEE\u4EF6\u6578',d.openRepairs,'red');
  h += '</div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-bolt mr-2 text-fju-yellow"></i>\u5FEB\u901F\u64CD\u4F5C</h3><div class="flex flex-wrap gap-2">';
  if (['officer','professor','staff','admin'].includes(role) || isAdmin) {
    h += '<button onclick="navigateTo(\\'activities\\')" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>\u65B0\u589E\u6D3B\u52D5\u7533\u8ACB</button>';
    h += '<button onclick="navigateTo(\\'venues\\')" class="btn-yellow text-xs"><i class="fas fa-calendar-plus mr-1"></i>\u9810\u7D04\u5834\u5730</button>';
    h += '<button onclick="navigateTo(\\'equipment\\')" class="btn-primary text-xs"><i class="fas fa-hand-holding mr-1"></i>\u501F\u7528\u5668\u6750</button>';
  }
  h += '<button onclick="navigateTo(\\'repairs\\')" class="text-xs px-3 py-2 rounded-fju bg-fju-red/10 text-fju-red hover:bg-fju-red/20"><i class="fas fa-wrench mr-1"></i>\u5831\u4FEE</button>';
  h += '<button onclick="navigateTo(\\'faq\\')" class="text-xs px-3 py-2 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20"><i class="fas fa-robot mr-1"></i>AI \u52A9\u7406</button>';
  h += '</div></div>';
  if (anns.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-bullhorn mr-2 text-fju-yellow"></i>\u6700\u65B0\u516C\u544A</h3><div class="space-y-2">';
    anns.forEach(a => { h += '<div class="p-3 rounded-fju bg-fju-bg border border-gray-100 hover:border-fju-yellow/30 cursor-pointer" onclick="navigateTo(\\'announcements\\')"><div class="flex items-center justify-between"><span class="font-medium text-sm text-fju-blue">'+(a.ANN_TITLE||'')+'</span><span class="text-xs text-gray-400">'+(a.ANN_START_DATE||'')+'</span></div><p class="text-xs text-gray-500 mt-1 line-clamp-1">'+(a.ANN_CONTENT||'')+'</p></div>'; });
    h += '</div></div>';
  }
  if (isAdmin || role === 'admin') {
    h += '<div class="grid md:grid-cols-2 gap-4"><div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-clipboard-list mr-2 text-fju-yellow"></i>\u5F85\u5BE9\u6838\u9805\u76EE</h3><div class="space-y-2">';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'venues\\')"><span class="text-sm text-gray-600">\u5F85\u5BE9\u6838\u9810\u7D04</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingBookings||0)+'</span></div>';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'activities\\')"><span class="text-sm text-gray-600">\u5F85\u5BE9\u6838\u6D3B\u52D5</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingActivities||0)+'</span></div>';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'appeals\\')"><span class="text-sm text-gray-600">\u5F85\u5BE9\u6838\u7533\u8A34</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingAppeals||0)+'</span></div>';
    h += '</div></div>';
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>\u672C\u6708\u6982\u89BD</h3><div class="space-y-2">';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">\u5DF2\u6838\u51C6\u9810\u7D04</span><span class="font-bold text-fju-green">'+(d.approvedBookings||0)+'</span></div>';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">\u9032\u884C\u4E2D\u501F\u7528</span><span class="font-bold text-fju-blue">'+(d.activeLoans||0)+'</span></div>';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">\u9032\u884C\u4E2D\u5831\u4FEE</span><span class="font-bold text-fju-red">'+(d.openRepairs||0)+'</span></div>';
    h += '</div></div></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}

// ========== Activities (Epic 3) ==========
async function loadActivities() {
  const res = await fetch(API+'/activities').then(r=>r.json()).catch(()=>({data:[]}));
  const items = res.data || [];
  const sL = { 0:'\u5F85\u5BE9\u6838', 1:'\u5DF2\u6838\u51C6', 2:'\u5DF2\u62D2\u7D55', 5:'\u5DF2\u53D6\u6D88' };
  const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected', 5:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-file-alt','\u7E3D\u7533\u8ACB',items.length,'blue')+statCard('fa-check-circle','\u5DF2\u6838\u51C6',items.filter(i=>i.AA_STATUS===1).length,'green')+statCard('fa-clock','\u5F85\u5BE9\u6838',items.filter(i=>i.AA_STATUS===0).length,'yellow')+statCard('fa-times-circle','\u5DF2\u62D2\u7D55',items.filter(i=>i.AA_STATUS===2).length,'red')+'</div>';
  h += '<div class="flex items-center justify-between flex-wrap gap-2"><input type="text" placeholder="\u641C\u5C0B\u6D3B\u52D5..." class="input-field w-64" oninput="filterTable(\\'act-table\\',this.value)">';
  if (['officer','professor','staff','admin'].includes(currentUser?.role) || isAdmin) h += '<button onclick="showAddActivityForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>\u65B0\u589E\u6D3B\u52D5\u7533\u8ACB</button>';
  h += '</div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm" id="act-table"><thead class="table-header"><tr><th class="table-cell">\u6D41\u6C34\u865F</th><th class="table-cell">\u6D3B\u52D5\u540D\u7A31</th><th class="table-cell">\u7533\u8ACB\u4EBA/\u55AE\u4F4D</th><th class="table-cell">\u6642\u9593</th><th class="table-cell">\u4EBA\u6578</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">\u66AB\u7121\u6D3B\u52D5\u7533\u8ACB</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs font-mono text-fju-blue">'+(i.AA_SERIAL_NO||'')+'</td><td class="table-cell font-medium">'+(i.AA_ACTIVITY_NAME||'')+'</td><td class="table-cell text-xs text-gray-500">'+(i.USR_NAME||'')+' / '+(i.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(i.AA_START_DATETIME||'').slice(0,16)+'</td><td class="table-cell">'+i.AA_HEADCOUNT+'</td><td class="table-cell"><span class="'+(sC[i.AA_STATUS]||'')+'">'+((sL[i.AA_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.AA_STATUS === 1) h += '<button onclick="generatePDF('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1" title="\u7522\u751FPDF"><i class="fas fa-file-pdf"></i></button>';
    if (i.AA_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">\u6838\u51C6</button>';
      h += '<button onclick="rejectActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red mr-1">\u62D2\u7D55</button>';
    }
    if (i.AA_STATUS === 0) h += '<button onclick="cancelActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-500">\u53D6\u6D88</button>';
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAddActivityForm() {
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">\u9810\u8A2D\u55AE\u4F4D</option>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-calendar-check mr-2 text-fju-yellow"></i>\u65B0\u589E\u6D3B\u52D5\u7533\u8ACB</h3><div class="space-y-3"><input id="aa-name" placeholder="\u6D3B\u52D5\u540D\u7A31 *" class="input-field"><select id="aa-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">\u958B\u59CB</label><input id="aa-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">\u7D50\u675F</label><input id="aa-end" type="datetime-local" class="input-field"></div></div><input id="aa-headcount" type="number" placeholder="\u9810\u8A08\u4EBA\u6578 *" class="input-field"><textarea id="aa-desc" placeholder="\u6D3B\u52D5\u8AAA\u660E" rows="2" class="input-field"></textarea><div class="flex gap-4 text-sm"><label><input type="checkbox" id="aa-alcohol"> \u542B\u9152\u7CBE</label><label><input type="checkbox" id="aa-fire"> \u542B\u660E\u706B</label><label><input type="checkbox" id="aa-booth"> \u542B\u6524\u4F4D</label></div><button onclick="submitActivity()" class="w-full btn-yellow py-2.5">\u9001\u51FA\u7533\u8ACB</button></div></div></div>');
}
async function submitActivity() {
  const body = { userId:currentUser?.id||1, unitId:parseInt(document.getElementById('aa-unit').value), activityName:document.getElementById('aa-name').value, startDatetime:document.getElementById('aa-start').value, endDatetime:document.getElementById('aa-end').value, headcount:parseInt(document.getElementById('aa-headcount').value)||20, description:document.getElementById('aa-desc').value, hasAlcohol:document.getElementById('aa-alcohol').checked?1:0, hasFire:document.getElementById('aa-fire').checked?1:0, hasBooth:document.getElementById('aa-booth').checked?1:0 };
  if (!body.activityName) return alert('\u8ACB\u586B\u5BEB\u6D3B\u52D5\u540D\u7A31');
  const res = await fetch(API+'/activities', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('\u6D3B\u52D5\u7533\u8ACB\u5DF2\u9001\u51FA','success'); closeModal(); loadActivities(); }
  else { alert(data.message || '\u7533\u8ACB\u5931\u6557'); }
}
async function approveActivity(id) { await fetch(API+'/activities/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('\u6D3B\u52D5\u5DF2\u6838\u51C6','success'); loadActivities(); }
async function rejectActivity(id) { const note = prompt('\u62D2\u7D55\u539F\u56E0\uFF1A'); if(!note) return; await fetch(API+'/activities/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,note}) }); loadActivities(); }
async function cancelActivity(id) { if(!confirm('\u78BA\u5B9A\u53D6\u6D88\u6B64\u6D3B\u52D5\uFF1F')) return; await fetch(API+'/activities/'+id+'/cancel', { method:'PATCH' }); loadActivities(); }
async function generatePDF(id) {
  try {
    const res = await fetch(API+'/ai/generate-pdf', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({activityId:id}) });
    const data = await res.json();
    if (data.success) {
      const p = data.pdfData;
      const w = window.open('','_blank');
      w.document.write('<html><head><title>\u6D3B\u52D5\u7533\u8ACB\u66F8 - '+p.serialNo+'</title><style>body{font-family:sans-serif;padding:40px;max-width:800px;margin:0 auto}h1{text-align:center;border-bottom:3px double #003366;padding-bottom:10px;color:#003366}table{width:100%;border-collapse:collapse;margin:20px 0}td,th{border:1px solid #ccc;padding:8px 12px;text-align:left;font-size:14px}th{background:#f5f5f5;width:120px}.footer{margin-top:40px;text-align:center;font-size:12px;color:#999}.header{text-align:center;margin-bottom:20px}.stamp{display:inline-block;border:2px solid '+(p.status==='\u5DF2\u6838\u51C6'?'green':'red')+';color:'+(p.status==='\u5DF2\u6838\u51C6'?'green':'red')+';padding:5px 15px;border-radius:4px;font-weight:bold;font-size:18px;transform:rotate(-5deg)}</style></head><body>');
      w.document.write('<div class="header"><h1>\u8F14\u4EC1\u5927\u5B78\u8AB2\u5916\u6D3B\u52D5\u6307\u5C0E\u7D44<br>\u6D3B\u52D5\u7533\u8ACB\u66F8</h1></div>');
      w.document.write('<table><tr><th>\u6D41\u6C34\u7DE8\u865F</th><td>'+p.serialNo+'</td><th>\u5BE9\u6838\u72C0\u614B</th><td><span class="stamp">'+p.status+'</span></td></tr>');
      w.document.write('<tr><th>\u6D3B\u52D5\u540D\u7A31</th><td colspan="3">'+p.activityName+'</td></tr>');
      w.document.write('<tr><th>\u4E3B\u8FA6\u55AE\u4F4D</th><td>'+p.unitName+'</td><th>\u7533\u8ACB\u4EBA</th><td>'+p.applicantName+'</td></tr>');
      w.document.write('<tr><th>\u6D3B\u52D5\u6642\u9593</th><td colspan="3">'+p.startDatetime+' ~ '+p.endDatetime+'</td></tr>');
      w.document.write('<tr><th>\u9810\u8A08\u4EBA\u6578</th><td>'+p.headcount+' \u4EBA</td><th>\u806F\u7D61\u4EBA</th><td>'+(p.contactName||p.applicantName)+' '+(p.contactPhone||'')+'</td></tr>');
      w.document.write('<tr><th>\u6D3B\u52D5\u8AAA\u660E</th><td colspan="3">'+(p.description||'')+'</td></tr>');
      w.document.write('<tr><th>\u7279\u6B8A\u4E8B\u9805</th><td colspan="3">'+(p.hasAlcohol?'\u2705 \u542B\u9152\u7CBE ':'')+(p.hasFire?'\u2705 \u542B\u660E\u706B ':'')+(p.hasBooth?'\u2705 \u542B\u6524\u4F4D ':'')+(!p.hasAlcohol&&!p.hasFire&&!p.hasBooth?'\u7121':'')+'</td></tr>');
      if (p.adminNote) w.document.write('<tr><th>\u5BE9\u6838\u5099\u8A3B</th><td colspan="3">'+p.adminNote+'</td></tr>');
      w.document.write('</table>');
      w.document.write('<div class="footer"><p>\u672C\u7533\u8ACB\u66F8\u7531\u7CFB\u7D71\u81EA\u52D5\u7522\u751F | \u7522\u751F\u6642\u9593\uFF1A'+new Date(p.generatedAt).toLocaleString('zh-TW')+'</p><p>\u8F14\u4EC1\u5927\u5B78\u5B78\u751F\u4E8B\u52D9\u8655\u8AB2\u5916\u6D3B\u52D5\u6307\u5C0E\u7D44</p></div>');
      w.document.write('</body></html>');
      w.document.close();
    } else { alert(data.message); }
  } catch(e) { alert('PDF \u7522\u751F\u5931\u6557'); }
}

// ========== Venues (Epic 4) ==========
async function loadVenues() {
  const [facRes, bkRes] = await Promise.all([fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/venue-bookings').then(r=>r.json()).catch(()=>({data:[]}))])
  const venues = facRes.data || []; const bookings = bkRes.data || [];
  const sL = { 0:'\u53EF\u9810\u7D04', 1:'\u7DAD\u4FEE\u4E2D' }; const sC = { 0:'status-approved', 1:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-building','\u5834\u5730\u7E3D\u6578',venues.length,'blue')+statCard('fa-check-circle','\u53EF\u9810\u7D04',venues.filter(v=>v.FAC_STATUS===0).length,'green')+statCard('fa-tools','\u7DAD\u4FEE\u4E2D',venues.filter(v=>v.FAC_STATUS===1).length,'red')+statCard('fa-calendar-check','\u9810\u7D04\u6578',bookings.length,'yellow')+'</div>';
  h += '<div class="flex items-center justify-between flex-wrap gap-2"><input type="text" placeholder="\u641C\u5C0B\u5834\u5730..." class="input-field w-64" oninput="filterTable(\\'venue-table\\',this.value)">';
  if (isAdmin) h += '<button onclick="showAddVenueForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>\u65B0\u589E\u5834\u5730</button>';
  h += '</div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm" id="venue-table"><thead class="table-header"><tr><th class="table-cell">\u5834\u5730\u540D\u7A31</th><th class="table-cell">\u5927\u6A13/\u6A13\u5C64</th><th class="table-cell">\u985E\u578B</th><th class="table-cell">\u5BB9\u91CF</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
  const typeL = { 0:'\u5834\u5730', 1:'\u6559\u5BA4', 2:'\u904B\u52D5\u5834\u9928', 3:'\u6703\u8B70\u5BA4' };
  venues.forEach(v => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(v.FAC_NAME||'')+'</td><td class="table-cell text-gray-500">'+(v.FAC_BUILDING||'')+' '+(v.FAC_FLOOR||'')+'F</td><td class="table-cell text-xs">'+(typeL[v.FAC_TYPE]||'')+'</td><td class="table-cell">'+(v.FAC_CAPACITY||'')+' \u4EBA</td><td class="table-cell"><span class="'+(sC[v.FAC_STATUS]||'')+'">'+((sL[v.FAC_STATUS])||'')+'</span></td><td class="table-cell">';
    if (v.FAC_STATUS === 0) h += '<button onclick="showBookingForm('+v.FAC_ID+',\\''+v.FAC_NAME+'\\')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1">\u9810\u7D04</button>';
    h += '<button onclick="showCalendar('+v.FAC_ID+',\\''+v.FAC_NAME+'\\')" class="text-xs px-2 py-1 rounded bg-purple-100 text-purple-700">\u884C\u4E8B\u66C6</button>';
    h += '</td></tr>';
  });
  h += '</tbody></table></div>';
  // \u9810\u7D04\u7D00\u9304
  if (bookings.length > 0) {
    const bkSL = { 0:'\u5F85\u5BE9\u6838', 1:'\u5DF2\u6838\u51C6', 2:'\u5DF2\u62D2\u7D55', 3:'\u5DF2\u53D6\u6D88', 4:'\u5DF2\u6B78\u9084', 5:'\u6B78\u9084\u7570\u5E38' };
    const bkSC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected', 3:'status-rejected', 4:'status-approved', 5:'status-rejected' };
    h += '<div class="card p-0 overflow-x-auto"><h3 class="p-4 font-bold text-fju-blue"><i class="fas fa-list mr-2 text-fju-yellow"></i>\u9810\u7D04\u7D00\u9304</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u5834\u5730</th><th class="table-cell">\u7533\u8ACB\u4EBA/\u55AE\u4F4D</th><th class="table-cell">\u6642\u9593</th><th class="table-cell">\u4E8B\u7531</th><th class="table-cell">\u72C0\u614B</th>'+(isAdmin?'<th class="table-cell">\u64CD\u4F5C</th>':'')+'</tr></thead><tbody>';
    bookings.forEach(b => {
      h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-fju-blue font-medium">'+(b.FAC_NAME||'')+'</td><td class="table-cell text-xs">'+(b.USR_NAME||'')+' / '+(b.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(b.VB_START_DATETIME||'').slice(0,16)+'</td><td class="table-cell text-xs">'+(b.VB_PURPOSE||'')+'</td><td class="table-cell"><span class="'+(bkSC[b.VB_STATUS]||'')+'">'+((bkSL[b.VB_STATUS])||'')+'</span></td>';
      if (isAdmin) {
        h += '<td class="table-cell">';
        if (b.VB_STATUS === 0) { h += '<button onclick="approveBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">\u6838\u51C6</button><button onclick="rejectBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">\u62D2\u7D55</button>'; }
        else if (b.VB_STATUS === 1) { h += '<button onclick="returnBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue">\u6B78\u9084</button>'; }
        h += '</td>';
      }
      h += '</tr>';
    });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function approveBooking(id) { await fetch(API+'/venue-bookings/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('\u9810\u7D04\u5DF2\u6838\u51C6','success'); loadVenues(); }
async function rejectBooking(id) { const r = prompt('\u62D2\u7D55\u539F\u56E0\uFF1A'); if(!r) return; await fetch(API+'/venue-bookings/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,reason:r}) }); loadVenues(); }
async function returnBooking(id) { await fetch(API+'/venue-bookings/'+id+'/return', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({abnormal:false}) }); loadVenues(); }
function showBookingForm(facId, facName) {
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('');
  if (!uOpts) uOpts = '<option value="1">\u9810\u8A2D\u55AE\u4F4D</option>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>\u9810\u7D04\u5834\u5730\uFF1A'+facName+'</h3><div class="space-y-3"><select id="vb-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">\u958B\u59CB</label><input id="vb-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">\u7D50\u675F</label><input id="vb-end" type="datetime-local" class="input-field"></div></div><input id="vb-purpose" placeholder="\u4F7F\u7528\u4E8B\u7531 *" class="input-field"><input id="vb-headcount" type="number" placeholder="\u4F7F\u7528\u4EBA\u6578" class="input-field"><input type="hidden" id="vb-fac-id" value="'+facId+'"><button onclick="submitBooking()" class="w-full btn-yellow py-2.5">\u9001\u51FA\u9810\u7D04</button></div></div></div>');
}
async function submitBooking() {
  const body = { facId:parseInt(document.getElementById('vb-fac-id').value), unitId:parseInt(document.getElementById('vb-unit').value), userId:currentUser?.id||1, startDatetime:document.getElementById('vb-start').value, endDatetime:document.getElementById('vb-end').value, purpose:document.getElementById('vb-purpose').value, headcount:parseInt(document.getElementById('vb-headcount').value)||20, activityId:1, bookingType:0 };
  if (!body.purpose) return alert('\u8ACB\u586B\u5BEB\u4F7F\u7528\u4E8B\u7531');
  const res = await fetch(API+'/venue-bookings', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('\u5834\u5730\u9810\u7D04\u5DF2\u9001\u51FA','success'); closeModal(); loadVenues(); }
  else { alert(data.message || '\u9810\u7D04\u5931\u6557\uFF0C\u53EF\u80FD\u6709\u6642\u6BB5\u885D\u7A81'); }
}
function showAddVenueForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-plus mr-2 text-fju-yellow"></i>\u65B0\u589E\u5834\u5730</h3><div class="space-y-3"><input id="fac-name" placeholder="\u5834\u5730\u540D\u7A31 *" class="input-field"><input id="fac-building" placeholder="\u6240\u5728\u5927\u6A13 *" class="input-field"><input id="fac-floor" type="number" placeholder="\u6A13\u5C64" class="input-field"><input id="fac-capacity" type="number" placeholder="\u5BB9\u7D0D\u4EBA\u6578" class="input-field"><select id="fac-type" class="input-field"><option value="0">\u5834\u5730</option><option value="1">\u6559\u5BA4</option><option value="2">\u904B\u52D5\u5834\u9928</option><option value="3">\u6703\u8B70\u5BA4</option></select><textarea id="fac-desc" placeholder="\u5834\u5730\u63CF\u8FF0" rows="2" class="input-field"></textarea><button onclick="submitFacility()" class="w-full btn-yellow py-2.5">\u65B0\u589E</button></div></div></div>');
}
async function submitFacility() {
  await fetch(API+'/facilities', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ name:document.getElementById('fac-name').value, building:document.getElementById('fac-building').value, floor:parseInt(document.getElementById('fac-floor').value)||1, capacity:parseInt(document.getElementById('fac-capacity').value)||50, type:parseInt(document.getElementById('fac-type').value), desc:document.getElementById('fac-desc').value }) });
  closeModal(); loadVenues();
}
// \u5834\u5730\u884C\u4E8B\u66C6\u6AA2\u8996 \u2014 \u6708\u66C6\u683C\u5F0F (Epic 4 \u884C\u4E8B\u66C6\u8996\u5716)
let calViewYear, calViewMonth;
async function showCalendar(facId, facName) {
  const now = new Date(); calViewYear = now.getFullYear(); calViewMonth = now.getMonth();
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content" style="max-width:720px"><h3 class="font-bold text-fju-blue text-lg mb-2"><i class="fas fa-calendar-alt mr-2 text-fju-yellow"></i>'+facName+' \u2014 \u6708\u66C6</h3><div id="cal-grid"></div></div></div>');
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
  const mNames = ['1\u6708','2\u6708','3\u6708','4\u6708','5\u6708','6\u6708','7\u6708','8\u6708','9\u6708','10\u6708','11\u6708','12\u6708'];
  let h = '<div class="flex items-center justify-between mb-3"><button onclick="calViewMonth--;if(calViewMonth<0){calViewMonth=11;calViewYear--;}renderCalendar(window._calFacId)" class="text-fju-blue hover:bg-fju-bg px-2 py-1 rounded"><i class="fas fa-chevron-left"></i></button><span class="font-bold text-fju-blue">'+y+' \u5E74 '+mNames[m]+'</span><button onclick="calViewMonth++;if(calViewMonth>11){calViewMonth=0;calViewYear++;}renderCalendar(window._calFacId)" class="text-fju-blue hover:bg-fju-bg px-2 py-1 rounded"><i class="fas fa-chevron-right"></i></button></div>';
  h += '<div class="grid grid-cols-7 text-center text-xs font-bold text-gray-400 mb-1">';
  ['\u65E5','\u4E00','\u4E8C','\u4E09','\u56DB','\u4E94','\u516D'].forEach(d => { h += '<div class="py-1">'+d+'</div>'; });
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
    if (evts.length > 2) h += '<div class="text-[9px] text-gray-400 mt-0.5">+' + (evts.length - 2) + ' \u66F4\u591A</div>';
    h += '</div>';
  }
  const totalCells = startPad + daysInMonth; const remain = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
  for (let i=0; i<remain; i++) h += '<div class="bg-gray-50 min-h-[56px]"></div>';
  h += '</div>';
  h += '<div class="flex gap-3 mt-3 text-xs text-gray-400"><span><span class="inline-block w-3 h-3 rounded bg-fju-green/20 border border-fju-green/30 mr-1 align-middle"></span>\u5DF2\u6838\u51C6</span><span><span class="inline-block w-3 h-3 rounded bg-fju-yellow/20 border border-fju-yellow/30 mr-1 align-middle"></span>\u5F85\u5BE9\u6838</span></div>';
  document.getElementById('cal-grid').innerHTML = h;
}

// ========== Equipment (Epic 5) ==========
async function loadEquipment() {
  const [eqRes, loanRes] = await Promise.all([fetch(API+'/equipment').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/equipment/loans/list').then(r=>r.json()).catch(()=>({data:[]}))])
  const items = eqRes.data || []; const loans = loanRes.data || [];
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-box','\u5668\u6750\u9805\u76EE',items.length,'blue')+statCard('fa-check','\u53EF\u501F\u7528',items.filter(i=>i.EQ_AVAILABLE>0).length,'green')+statCard('fa-certificate','\u9700\u64CD\u4F5C\u8B49',items.filter(i=>i.EQ_CERT_TYPE_ID).length,'yellow')+statCard('fa-boxes-stacked','\u7E3D\u5EAB\u5B58',items.reduce((s,i)=>s+i.EQ_TOTAL,0),'purple')+'</div>';
  if (['officer','professor','staff','admin'].includes(currentUser?.role) || currentUser?.isAdmin===1) h += '<div class="flex justify-end"><button onclick="showLoanForm()" class="btn-yellow text-xs"><i class="fas fa-hand-holding mr-1"></i>\u501F\u7528\u5668\u6750</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u5668\u6750\u540D\u7A31</th><th class="table-cell">\u7E3D\u6578</th><th class="table-cell">\u53EF\u7528</th><th class="table-cell">\u55AE\u6B21\u4E0A\u9650</th><th class="table-cell">\u9700\u64CD\u4F5C\u8B49</th><th class="table-cell">\u8AAA\u660E</th></tr></thead><tbody>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.EQ_NAME||'')+'</td><td class="table-cell">'+i.EQ_TOTAL+'</td><td class="table-cell">'+(i.EQ_AVAILABLE>0?'<span class="status-approved">'+i.EQ_AVAILABLE+'</span>':'<span class="status-rejected">0</span>')+'</td><td class="table-cell">'+i.EQ_MAX_PER_LOAN+'</td><td class="table-cell">'+(i.CERT_NAME?'<span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs">'+i.CERT_NAME+'</span>':'<span class="text-gray-400">-</span>')+'</td><td class="table-cell text-xs text-gray-400">'+(i.EQ_DESC||'-')+'</td></tr>';
  });
  h += '</tbody></table></div>';
  if (loans.length > 0) {
    const elSL = { 0:'\u5F85\u9818\u53D6', 1:'\u501F\u7528\u4E2D', 2:'\u90E8\u5206\u9818\u53D6', 3:'\u90E8\u5206\u6B78\u9084', 4:'\u5DF2\u6B78\u9084', 5:'\u6B78\u9084\u7570\u5E38' };
    h += '<div class="card p-0 overflow-x-auto"><h3 class="p-4 font-bold text-fju-blue"><i class="fas fa-history mr-2 text-fju-yellow"></i>\u501F\u7528\u7D00\u9304</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u501F\u7528\u4EBA</th><th class="table-cell">\u55AE\u4F4D</th><th class="table-cell">\u7528\u9014</th><th class="table-cell">\u501F\u7528\u65E5</th><th class="table-cell">\u6B78\u9084\u671F\u9650</th><th class="table-cell">\u72C0\u614B</th></tr></thead><tbody>';
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
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">\u9810\u8A2D\u55AE\u4F4D</option>';
  let eqHtml = items.filter(i=>i.EQ_AVAILABLE>0).map(i => '<label class="flex items-center gap-2 p-2 rounded-fju hover:bg-fju-bg"><input type="checkbox" class="eq-check" value="'+i.EQ_ID+'"><span class="text-sm">'+i.EQ_NAME+' (\u53EF\u7528:'+i.EQ_AVAILABLE+')</span><input type="number" class="eq-qty w-16 px-2 py-1 border rounded text-xs" min="1" max="'+i.EQ_MAX_PER_LOAN+'" value="1"></label>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hand-holding mr-2 text-fju-yellow"></i>\u501F\u7528\u5668\u6750</h3><div class="space-y-3"><select id="el-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">\u9818\u53D6\u65E5\u671F</label><input id="el-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">\u6B78\u9084\u671F\u9650</label><input id="el-due" type="datetime-local" class="input-field"></div></div><input id="el-location" placeholder="\u4F7F\u7528\u5730\u9EDE *" class="input-field"><input id="el-purpose" placeholder="\u7528\u9014\u8AAA\u660E *" class="input-field"><div class="border rounded-fju p-3 max-h-40 overflow-y-auto"><p class="text-xs text-gray-500 mb-2">\u9078\u64C7\u5668\u6750\uFF1A</p>'+eqHtml+'</div><button onclick="submitLoan()" class="w-full btn-yellow py-2.5">\u9001\u51FA\u501F\u7528\u7533\u8ACB</button></div></div></div>');
}
async function submitLoan() {
  const checks = document.querySelectorAll('.eq-check:checked');
  const items = [];
  checks.forEach(ck => { const row = ck.closest('label'); const qty = row.querySelector('.eq-qty'); items.push({ equipmentId: parseInt(ck.value), quantity: parseInt(qty.value)||1 }); });
  if (items.length === 0) return alert('\u8ACB\u9078\u64C7\u81F3\u5C11\u4E00\u9805\u5668\u6750');
  const body = { activityId:1, unitId:parseInt(document.getElementById('el-unit').value), userId:currentUser?.id||1, borrowStart:document.getElementById('el-start').value, returnDue:document.getElementById('el-due').value, useLocation:document.getElementById('el-location').value, purpose:document.getElementById('el-purpose').value, items };
  const res = await fetch(API+'/equipment/loans', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('\u5668\u6750\u501F\u7528\u5DF2\u9001\u51FA','success'); closeModal(); loadEquipment(); }
  else { alert(data.message || '\u501F\u7528\u5931\u6557'); }
}

// ========== Coordination (\u5834\u5354\u5927\u6703 Epic 4.2) ==========
async function loadCoordination() {
  const res = await fetch(API+'/venue-coordination?semester=114-2').then(r=>r.json()).catch(()=>({data:[]}));
  const items = res.data || [];
  const dayNames = ['\u65E5','\u4E00','\u4E8C','\u4E09','\u56DB','\u4E94','\u516D'];
  const sL = { 0:'\u5F85\u5BE9\u6838', 1:'\u5DF2\u6838\u51C6', 2:'\u5DF2\u99C1\u56DE' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>\u5834\u5354\u5927\u6703\u8AAA\u660E</h3><div class="text-xs text-gray-600 space-y-1"><p>1. \u5B78\u671F\u521D\u7531\u8AB2\u6307\u7D44\u8209\u8FA6\u5834\u5354\u5927\u6703\uFF0C\u5404\u55AE\u4F4D\u767B\u8A18\u6BCF\u9031\u56FA\u5B9A\u4F7F\u7528\u5834\u5730\u9700\u6C42</p><p>2. \u767B\u8A18\u622A\u6B62\u5F8C\uFF0C\u8AB2\u6307\u7D44\u7D71\u4E00\u5BE9\u6838\u4E26\u5354\u8ABF\u885D\u7A81</p><p>3. \u6838\u51C6\u5F8C\u7CFB\u7D71\u81EA\u52D5\u7522\u751F\u6574\u5B78\u671F\u7684\u5834\u5730\u9810\u7D04</p></div></div>';
  if (['officer','admin'].includes(currentUser?.role) || isAdmin) h += '<div class="flex justify-end"><button onclick="showCoordinationForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>\u65B0\u589E\u767B\u8A18</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u55AE\u4F4D</th><th class="table-cell">\u5834\u5730</th><th class="table-cell">\u6BCF\u9031</th><th class="table-cell">\u6642\u6BB5</th><th class="table-cell">\u7528\u9014</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">\u66AB\u7121\u767B\u8A18</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.UNIT_NAME||'')+'</td><td class="table-cell">'+(i.FAC_NAME||'')+'</td><td class="table-cell">\u9031'+(dayNames[i.VC_DAY_OF_WEEK]||'')+'</td><td class="table-cell text-xs">'+(i.VC_TIME_START||'')+' ~ '+(i.VC_TIME_END||'')+'</td><td class="table-cell text-xs">'+(i.VC_PURPOSE||'')+'</td><td class="table-cell"><span class="'+(sC[i.VC_STATUS]||'')+'">'+((sL[i.VC_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.VC_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveCoord('+i.VC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">\u6838\u51C6</button>';
      h += '<button onclick="rejectCoord('+i.VC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">\u99C1\u56DE</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function approveCoord(id) { await fetch(API+'/venue-coordination/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({}) }); toast('\u767B\u8A18\u5DF2\u6838\u51C6','success'); loadCoordination(); }
async function rejectCoord(id) { const note = prompt('\u99C1\u56DE\u539F\u56E0\uFF1A'); if(!note) return; await fetch(API+'/venue-coordination/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({note}) }); loadCoordination(); }
async function showCoordinationForm() {
  const facRes = await fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]}));
  const facs = facRes.data || []; const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">\u9810\u8A2D\u55AE\u4F4D</option>';
  let fOpts = facs.filter(f=>f.FAC_STATUS===0).map(f => '<option value="'+f.FAC_ID+'">'+f.FAC_NAME+'</option>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-users-rectangle mr-2 text-fju-yellow"></i>\u5834\u5354\u5927\u6703\u767B\u8A18</h3><div class="space-y-3"><select id="vc-unit" class="input-field">'+uOpts+'</select><select id="vc-fac" class="input-field">'+fOpts+'</select><select id="vc-day" class="input-field"><option value="1">\u9031\u4E00</option><option value="2">\u9031\u4E8C</option><option value="3">\u9031\u4E09</option><option value="4">\u9031\u56DB</option><option value="5">\u9031\u4E94</option></select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">\u958B\u59CB</label><input id="vc-start" type="time" class="input-field" value="14:00"></div><div><label class="text-xs text-gray-500 block mb-1">\u7D50\u675F</label><input id="vc-end" type="time" class="input-field" value="17:00"></div></div><input id="vc-purpose" placeholder="\u4F7F\u7528\u4E8B\u7531" class="input-field"><button onclick="submitCoordination()" class="w-full btn-yellow py-2.5">\u9001\u51FA\u767B\u8A18</button></div></div></div>');
}
async function submitCoordination() {
  const body = { userId:currentUser?.id||1, unitId:parseInt(document.getElementById('vc-unit').value), facId:parseInt(document.getElementById('vc-fac').value), dayOfWeek:parseInt(document.getElementById('vc-day').value), timeStart:document.getElementById('vc-start').value, timeEnd:document.getElementById('vc-end').value, purpose:document.getElementById('vc-purpose').value, semester:'114-2' };
  const res = await fetch(API+'/venue-coordination', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('\u767B\u8A18\u5DF2\u9001\u51FA','success'); closeModal(); loadCoordination(); }
  else { alert(data.message || '\u767B\u8A18\u5931\u6557'); }
}

// ========== Repairs (Epic 6) ==========
async function loadRepairs() {
  const [repRes, facRes] = await Promise.all([fetch(API+'/repairs').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]}))])
  const items = repRes.data || []; window._facilities = facRes.data || [];
  const sL = { 0:'\u5F85\u8655\u7406', 1:'\u8655\u7406\u4E2D', 2:'\u5DF2\u5B8C\u6210' }; const sC = { 0:'status-pending', 1:'status-info', 2:'status-approved' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in"><div class="flex justify-end"><button onclick="showRepairForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>\u65B0\u589E\u5831\u4FEE</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u8A2D\u65BD</th><th class="table-cell">\u554F\u984C\u63CF\u8FF0</th><th class="table-cell">\u7533\u5831\u4EBA</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u6642\u9593</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">\u66AB\u7121\u5831\u4FEE</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.FAC_NAME||'-')+'</td><td class="table-cell text-xs">'+(i.RR_DESCRIPTION||'')+'</td><td class="table-cell text-xs">'+(i.USR_NAME||'-')+'</td><td class="table-cell"><span class="'+(sC[i.RR_STATUS]||'')+'">'+((sL[i.RR_STATUS])||'')+'</span></td><td class="table-cell text-xs text-gray-400">'+(i.RR_CREATED_AT||'')+'</td><td class="table-cell">';
    if (i.RR_STATUS < 2 && isAdmin) {
      const ns = i.RR_STATUS === 0 ? 1 : 2;
      h += '<button onclick="updateRepairStatus('+i.RR_ID+','+ns+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue">'+(ns===1?'\u958B\u59CB\u8655\u7406':'\u5B8C\u6210')+'</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showRepairForm() {
  const facs = window._facilities || [];
  let opts = facs.map(f => '<option value="'+f.FAC_ID+'">'+f.FAC_NAME+'</option>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>\u65B0\u589E\u5831\u4FEE</h3><div class="space-y-3"><select id="rr-fac" class="input-field"><option value="">\u9078\u64C7\u8A2D\u65BD *</option>'+opts+'</select><textarea id="rr-desc" placeholder="\u554F\u984C\u63CF\u8FF0\uFF08\u81F3\u5C1110\u5B57\uFF09*" rows="3" class="input-field"></textarea><button onclick="submitRepair()" class="w-full btn-yellow py-2.5">\u9001\u51FA\u5831\u4FEE</button></div></div></div>');
}
async function submitRepair() {
  const desc = document.getElementById('rr-desc').value;
  if (desc.length < 10) return alert('\u554F\u984C\u63CF\u8FF0\u81F3\u5C11\u9700\u898110\u500B\u5B57');
  await fetch(API+'/repairs', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ facId:parseInt(document.getElementById('rr-fac').value), userId:currentUser?.id||1, description:desc }) });
  toast('\u5831\u4FEE\u5DF2\u9001\u51FA','success'); closeModal(); loadRepairs();
}
async function updateRepairStatus(id, status) { await fetch(API+'/repairs/'+id, { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ status, adminId:currentUser?.id||1 }) }); loadRepairs(); }

// ========== Appeals (Epic 7) ==========
async function loadAppeals() {
  const res = await fetch(API+'/appeals').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const tL = { 0:'\u505C\u6B0A\u7533\u5FA9', 1:'\u9055\u898F\u8A18\u9EDE\u7533\u5FA9', 2:'\u5176\u4ED6\u6AA2\u8209' }; const sL = { 0:'\u5F85\u5BE9\u6838', 1:'\u5DF2\u6838\u51C6', 2:'\u5DF2\u99C1\u56DE' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in"><div class="flex justify-end"><button onclick="showAppealForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>\u63D0\u4EA4\u7533\u8A34</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u985E\u578B</th><th class="table-cell">\u7533\u8A34\u4EBA</th><th class="table-cell">\u7406\u7531</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u6642\u9593</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">\u66AB\u7121\u7533\u8A34</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell"><span class="px-2 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(tL[i.AC_TYPE]||'')+'</span></td><td class="table-cell text-sm">'+(i.USR_NAME||'-')+'</td><td class="table-cell text-xs">'+(i.AC_REASON||'')+'</td><td class="table-cell"><span class="'+(sC[i.AC_STATUS]||'')+'">'+((sL[i.AC_STATUS])||'')+'</span></td><td class="table-cell text-xs text-gray-400">'+(i.AC_CREATED_AT||'')+'</td><td class="table-cell">';
    if (i.AC_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveAppeal('+i.AC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">\u6838\u51C6</button>';
      h += '<button onclick="rejectAppeal('+i.AC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">\u99C1\u56DE</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAppealForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>\u63D0\u4EA4\u7533\u8A34</h3><div class="space-y-3"><select id="ac-type" class="input-field"><option value="0">\u505C\u6B0A\u7533\u5FA9</option><option value="1">\u9055\u898F\u8A18\u9EDE\u7533\u5FA9</option><option value="2">\u5176\u4ED6\u6AA2\u8209</option></select><textarea id="ac-reason" placeholder="\u7533\u8A34\u7406\u7531 *\uFF08\u8ACB\u8A73\u8FF0\u4E8B\u7531\uFF09" rows="3" class="input-field"></textarea><input id="ac-evidence" placeholder="\u4F50\u8B49\u8CC7\u6599\uFF08\u9078\u586B\uFF09" class="input-field"><button onclick="submitAppeal()" class="w-full btn-yellow py-2.5">\u9001\u51FA\u7533\u8A34</button></div></div></div>');
}
async function submitAppeal() {
  const reason = document.getElementById('ac-reason').value;
  if (!reason) return alert('\u8ACB\u586B\u5BEB\u7533\u8A34\u7406\u7531');
  await fetch(API+'/appeals', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ userId:currentUser?.id||1, type:parseInt(document.getElementById('ac-type').value), reason, evidence:document.getElementById('ac-evidence').value }) });
  toast('\u7533\u8A34\u5DF2\u9001\u51FA','success'); closeModal(); loadAppeals();
}
async function approveAppeal(id) { await fetch(API+'/appeals/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('\u7533\u8A34\u5DF2\u6838\u51C6','success'); loadAppeals(); }
async function rejectAppeal(id) { const note = prompt('\u99C1\u56DE\u539F\u56E0\uFF1A'); if(!note) return; await fetch(API+'/appeals/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,note}) }); loadAppeals(); }

// ========== Announcements (Epic 8) ==========
async function loadAnnouncements() {
  const res = await fetch(API+'/announcements').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  if (isAdmin) h += '<div class="flex justify-end"><button onclick="showAnnouncementForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>\u65B0\u589E\u516C\u544A</button></div>';
  if (items.length === 0) h += '<div class="card text-center text-gray-400"><i class="fas fa-bullhorn text-2xl mb-2"></i><p>\u66AB\u7121\u516C\u544A</p></div>';
  items.forEach(a => {
    h += '<div class="card hover:border-fju-yellow/30 transition-all"><div class="flex items-start justify-between"><div class="flex-1"><h3 class="font-bold text-fju-blue">'+(a.ANN_TITLE||'')+'</h3><p class="text-sm text-gray-600 mt-2 whitespace-pre-line">'+(a.ANN_CONTENT||'')+'</p><div class="flex gap-3 mt-3 text-xs text-gray-400"><span><i class="fas fa-calendar mr-1"></i>'+(a.ANN_START_DATE||'')+' ~ '+(a.ANN_END_DATE||'')+'</span><span><i class="fas fa-user mr-1"></i>'+(a.ADMIN_NAME||'\u7BA1\u7406\u54E1')+'</span></div></div>';
    if (isAdmin) h += '<button onclick="deleteAnnouncement('+a.ANN_ID+')" class="text-gray-300 hover:text-fju-red ml-3"><i class="fas fa-trash"></i></button>';
    h += '</div></div>';
  });
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAnnouncementForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-bullhorn mr-2 text-fju-yellow"></i>\u65B0\u589E\u516C\u544A</h3><div class="space-y-3"><input id="ann-title" placeholder="\u516C\u544A\u6A19\u984C *" class="input-field"><textarea id="ann-content" placeholder="\u516C\u544A\u5167\u5BB9 *" rows="4" class="input-field"></textarea><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">\u958B\u59CB\u65E5\u671F</label><input id="ann-start" type="date" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">\u7D50\u675F\u65E5\u671F</label><input id="ann-end" type="date" class="input-field"></div></div><button onclick="submitAnnouncement()" class="w-full btn-yellow py-2.5">\u767C\u5E03\u516C\u544A</button></div></div></div>');
}
async function submitAnnouncement() {
  await fetch(API+'/announcements', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ title:document.getElementById('ann-title').value, content:document.getElementById('ann-content').value, startDate:document.getElementById('ann-start').value, endDate:document.getElementById('ann-end').value, adminId:currentUser?.id||1 }) });
  toast('\u516C\u544A\u5DF2\u767C\u5E03','success'); closeModal(); loadAnnouncements();
}
async function deleteAnnouncement(id) { if(!confirm('\u78BA\u5B9A\u522A\u9664\u6B64\u516C\u544A\uFF1F')) return; await fetch(API+'/announcements/'+id, { method:'DELETE' }); loadAnnouncements(); }

// ========== FAQ / AI (Prompt7 9A: \u6574\u5408\u81F3\u55AE\u4E00\u9801\u9762) ==========
async function loadFaq() {
  const role = currentUser?.role || 'student';
  const res = await fetch(API+'/faq?role='+role).then(r=>r.json()).catch(()=>({common:[],roleSpecific:[],regulations:[]}));
  let h = '<div class="space-y-6 fade-in">';
  // AI Chat
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI \u667A\u6167\u52A9\u7406 <span class="text-xs font-normal text-gray-400 ml-2">GPT-4o via GitHub Models</span></h3>';
  h += '<div id="ai-chat-history" class="space-y-3 max-h-72 overflow-y-auto mb-3 p-3 bg-fju-bg rounded-fju-lg">';
  h += '<div class="flex"><div class="bg-white border border-gray-100 rounded-fju-lg px-4 py-2 text-sm max-w-[85%] shadow-sm"><div class="text-gray-700">\u{1F44B} \u60A8\u597D\uFF01\u6211\u662F\u8AB2\u6307\u7D44AI\u52A9\u7406\u3002\u60A8\u53EF\u4EE5\u554F\u6211\u95DC\u65BC\u5834\u5730\u9810\u7D04\u3001\u5668\u6750\u501F\u7528\u3001\u9055\u898F\u8A18\u9EDE\u7B49\u554F\u984C\u3002</div><div class="text-xs text-gray-400 mt-1">RAG \u77E5\u8B58\u5EAB</div></div></div>';
  h += '</div>';
  h += '<div class="flex gap-2"><input id="ai-input" type="text" placeholder="\u8F38\u5165\u554F\u984C...\uFF08\u4F8B\u5982\uFF1A\u5834\u5730\u9810\u7D04\u600E\u9EBC\u64CD\u4F5C\uFF1F\uFF09" class="flex-1 input-field" onkeypress="if(event.key===\\'Enter\\')askAI()"><button onclick="askAI()" class="btn-primary px-6"><i class="fas fa-paper-plane mr-1"></i>\u9001\u51FA</button></div>';
  h += '<div class="flex flex-wrap gap-2 mt-2">';
  ['\u5834\u5730\u9810\u7D04','\u5668\u6750\u501F\u7528','\u9055\u898F','\u885D\u7A81\u5354\u8ABF','\u64CD\u4F5C\u8B49','\u6D3B\u52D5\u7533\u8ACB','\u5834\u5354\u5927\u6703'].forEach(k => { h += '<button onclick="document.getElementById(\\'ai-input\\').value=\\''+k+'\\';askAI()" class="text-xs px-3 py-1 rounded-fju bg-fju-blue/5 text-fju-blue hover:bg-fju-blue/10">'+k+'</button>'; });
  h += '</div></div>';
  // Role-specific FAQ
  if (res.roleSpecific?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-user-tag mr-2 text-fju-yellow"></i>'+(roleNames[role]||'')+'\u5E38\u898B\u554F\u984C</h3><div class="space-y-2">';
    res.roleSpecific.forEach(f => {
      h += '<details class="border border-gray-100 rounded-fju"><summary class="p-3 cursor-pointer hover:bg-fju-bg text-sm font-medium text-fju-blue"><i class="fas fa-chevron-right mr-2 text-xs text-fju-yellow"></i>'+f.q+'</summary><div class="p-3 pt-0 text-sm text-gray-600 whitespace-pre-line border-t border-gray-100 mt-1">'+f.a+'</div></details>';
    });
    h += '</div></div>';
  }
  // Common FAQ
  if (res.common?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-question-circle mr-2 text-fju-yellow"></i>\u901A\u7528\u5E38\u898B\u554F\u984C</h3><div class="space-y-2">';
    res.common.forEach(f => {
      h += '<details class="border border-gray-100 rounded-fju"><summary class="p-3 cursor-pointer hover:bg-fju-bg text-sm font-medium text-gray-700"><i class="fas fa-chevron-right mr-2 text-xs text-fju-yellow"></i>'+f.q+' <span class="text-xs text-gray-400 ml-2">'+f.category+'</span></summary><div class="p-3 pt-0 text-sm text-gray-600 whitespace-pre-line border-t border-gray-100 mt-1">'+f.a+'</div></details>';
    });
    h += '</div></div>';
  }
  // Regulations
  if (res.regulations?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-book mr-2 text-fju-yellow"></i>\u6CD5\u898F\u53C3\u8003</h3><div class="space-y-2">';
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
  history.innerHTML += '<div id="ai-loading" class="flex"><div class="bg-fju-bg rounded-fju-lg px-4 py-2 text-sm text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>AI \u601D\u8003\u4E2D...</div></div>';
  history.scrollTop = history.scrollHeight; input.value = '';
  try {
    const res = await fetch(API+'/ai/chat', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ message:msg, role:currentUser?.role||'student' }) });
    const data = await res.json();
    document.getElementById('ai-loading')?.remove();
    history.innerHTML += '<div class="flex"><div class="bg-white border border-gray-100 rounded-fju-lg px-4 py-3 text-sm max-w-[85%] shadow-sm"><div class="whitespace-pre-line text-gray-700">'+(data.response||'')+'</div><div class="text-xs text-gray-400 mt-2 pt-2 border-t border-gray-100"><i class="fas fa-microchip mr-1"></i>'+(data.source||data.model||'AI')+'</div></div></div>';
  } catch(e) {
    document.getElementById('ai-loading')?.remove();
    history.innerHTML += '<div class="flex"><div class="bg-red-50 rounded-fju-lg px-4 py-2 text-sm text-red-500">AI \u56DE\u61C9\u5931\u6557\uFF0C\u8ACB\u7A0D\u5F8C\u91CD\u8A66</div></div>';
  }
  history.scrollTop = history.scrollHeight;
}

// ========== Stats (Epic 10) ==========
async function loadStats() {
  const [dashRes, facRes, eqRes] = await Promise.all([fetch(API+'/stats/dashboard').then(r=>r.json()).catch(()=>({})), fetch(API+'/stats/facility-usage').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/stats/equipment-usage').then(r=>r.json()).catch(()=>({data:[]}))])
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-5 gap-4">'+statCard('fa-building','\u5834\u5730',dashRes.totalFacilities,'blue')+statCard('fa-tools','\u5668\u6750',dashRes.totalEquipment,'green')+statCard('fa-users','\u4F7F\u7528\u8005',dashRes.totalUsers,'purple')+statCard('fa-calendar-check','\u5DF2\u6838\u51C6\u9810\u7D04',dashRes.approvedBookings,'yellow')+statCard('fa-wrench','\u5F85\u4FEE',dashRes.openRepairs,'red')+'</div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>\u5834\u5730\u4F7F\u7528\u7D71\u8A08</h3><div class="space-y-3">';
  (facRes.data||[]).forEach(f => { const pct = Math.min((f.booking_count||0)*5,100); h += '<div class="flex items-center gap-3"><span class="w-32 text-sm text-gray-600 truncate">'+(f.FAC_NAME||'')+'</span><div class="flex-1 bg-gray-200 rounded-full h-3"><div class="bg-fju-blue rounded-full h-3 transition-all" style="width:'+pct+'%"></div></div><span class="text-xs text-gray-500 w-24 text-right">'+(f.booking_count||0)+' \u6B21 / '+parseFloat(f.total_hours||0).toFixed(1)+'h</span></div>'; });
  h += '</div></div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-boxes-stacked mr-2 text-fju-yellow"></i>\u5668\u6750\u4F7F\u7528\u7D71\u8A08</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u5668\u6750</th><th class="table-cell">\u7E3D\u6578</th><th class="table-cell">\u53EF\u7528</th><th class="table-cell">\u501F\u51FA\u6B21\u6578</th><th class="table-cell">\u7D2F\u8A08\u501F\u51FA</th></tr></thead><tbody>';
  (eqRes.data||[]).forEach(e => { h += '<tr class="border-t border-gray-50"><td class="table-cell font-medium text-fju-blue">'+(e.EQ_NAME||'')+'</td><td class="table-cell">'+e.EQ_TOTAL+'</td><td class="table-cell">'+e.EQ_AVAILABLE+'</td><td class="table-cell">'+e.loan_count+'</td><td class="table-cell">'+e.total_borrowed+'</td></tr>'; });
  h += '</tbody></table></div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-brain mr-2 text-fju-yellow"></i>AI \u5B78\u671F\u7E3D\u7D50\u8A55\u9451\u5831\u544A</h3><p class="text-sm text-gray-500 mb-3">\u542B Simpson \u591A\u6A23\u6027\u6307\u6578\u3001\u4F7F\u7528\u7387\u5206\u6790\u3001\u9055\u898F\u7D71\u8A08\u3001\u8CC7\u6E90\u8ABF\u914D\u5EFA\u8B70</p><button onclick="generateAIReport()" class="btn-yellow text-sm"><i class="fas fa-magic mr-1"></i>\u7522\u751F\u5B78\u671F\u8A55\u9451\u5831\u544A</button><div id="ai-report-content" class="hidden mt-4"></div></div>';
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function generateAIReport() {
  const el = document.getElementById('ai-report-content');
  el.classList.remove('hidden'); el.innerHTML = '<div class="text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>AI \u6B63\u5728\u751F\u6210\u5831\u544A...</div>';
  try {
    const res = await fetch(API+'/ai/generate-report', { method:'POST', headers:{'Content-Type':'application/json'}, body:'{}' });
    const data = await res.json();
    let rh = '<div class="space-y-4 p-4 bg-fju-bg rounded-fju-lg border border-gray-200">';
    rh += '<h4 class="font-bold text-fju-blue text-lg">'+(data.reportTitle||'')+'</h4>';
    rh += '<div class="grid md:grid-cols-2 gap-4">';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-chart-pie mr-1 text-fju-yellow"></i>Simpson \u591A\u6A23\u6027\u6307\u6578</h5><div class="text-3xl font-black text-fju-blue">D = '+(data.simpson?.value||0)+'</div><p class="text-xs text-gray-500 mt-1">'+(data.simpson?.interpretation||'')+'</p>';
    if (data.simpson?.unitBreakdown?.length) { rh += '<div class="mt-2 space-y-1">'; data.simpson.unitBreakdown.forEach(u => { rh += '<div class="flex justify-between text-xs"><span class="text-gray-500">'+(u.UNIT_NAME||'\u672A\u77E5')+'</span><span class="font-bold text-fju-blue">'+u.usage_count+' \u6B21</span></div>'; }); rh += '</div>'; }
    rh += '</div>';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-calendar-check mr-1 text-fju-yellow"></i>\u6D3B\u52D5\u7D71\u8A08</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-green">'+(data.activitySummary?.approved||0)+'</div><div class="text-xs text-gray-400">\u5DF2\u6838\u51C6</div></div><div><div class="text-lg font-bold text-fju-yellow">'+(data.activitySummary?.pending||0)+'</div><div class="text-xs text-gray-400">\u5F85\u5BE9\u6838</div></div><div><div class="text-lg font-bold text-fju-red">'+(data.activitySummary?.rejected||0)+'</div><div class="text-xs text-gray-400">\u5DF2\u62D2\u7D55</div></div></div></div>';
    rh += '</div>';
    rh += '<div class="grid md:grid-cols-2 gap-4">';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-wrench mr-1 text-fju-yellow"></i>\u5831\u4FEE\u7D71\u8A08</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-yellow">'+(data.repairSummary?.pending||0)+'</div><div class="text-xs text-gray-400">\u5F85\u8655\u7406</div></div><div><div class="text-lg font-bold text-fju-blue">'+(data.repairSummary?.in_progress||0)+'</div><div class="text-xs text-gray-400">\u8655\u7406\u4E2D</div></div><div><div class="text-lg font-bold text-fju-green">'+(data.repairSummary?.completed||0)+'</div><div class="text-xs text-gray-400">\u5DF2\u5B8C\u6210</div></div></div></div>';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-handshake mr-1 text-fju-yellow"></i>\u885D\u7A81\u5354\u8ABF</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-blue">'+(data.conflictSummary?.total||0)+'</div><div class="text-xs text-gray-400">\u7E3D\u6848\u4EF6</div></div><div><div class="text-lg font-bold text-fju-green">'+(data.conflictSummary?.resolved||0)+'</div><div class="text-xs text-gray-400">\u5DF2\u89E3\u6C7A</div></div><div><div class="text-lg font-bold text-fju-red">'+(data.conflictSummary?.failed||0)+'</div><div class="text-xs text-gray-400">\u5931\u6557</div></div></div></div>';
    rh += '</div>';
    if (data.peakHours?.length) { rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-clock mr-1 text-fju-yellow"></i>\u71B1\u9580\u9810\u7D04\u6642\u6BB5 TOP 5</h5><div class="flex gap-2 flex-wrap">'; data.peakHours.forEach(p => { rh += '<span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs font-bold">'+p.hour+':00 <span class="text-gray-400">('+p.cnt+'\u6B21)</span></span>'; }); rh += '</div></div>'; }
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-lightbulb mr-1 text-fju-yellow"></i>AI \u667A\u6167\u5EFA\u8B70</h5><ul class="text-xs text-gray-600 space-y-1.5">';
    (data.recommendations||[]).forEach(r => { rh += '<li class="flex items-start gap-1.5"><i class="fas fa-check-circle text-fju-green mt-0.5 flex-shrink-0"></i><span>'+r+'</span></li>'; });
    rh += '</ul></div>';
    rh += '<div class="text-xs text-gray-400 text-right">\u7522\u751F\u6642\u9593\uFF1A'+new Date(data.generatedAt||'').toLocaleString('zh-TW')+'</div></div>';
    el.innerHTML = rh;
  } catch(e) { el.innerHTML = '<div class="text-red-500 text-sm">\u5831\u544A\u7522\u751F\u5931\u6557</div>'; }
}

// ========== Users Management ==========
async function loadUsers() {
  const res = await fetch(API+'/users').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  let h = '<div class="space-y-4 fade-in"><div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">ID</th><th class="table-cell">\u59D3\u540D</th><th class="table-cell">Email</th><th class="table-cell">\u89D2\u8272</th><th class="table-cell">\u7BA1\u7406\u54E1</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
  items.forEach(u => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs">'+u.USR_ID+'</td><td class="table-cell font-medium text-fju-blue">'+(u.USR_NAME||'')+'</td><td class="table-cell text-xs text-gray-500">'+(u.USR_EMAIL||'')+'</td><td class="table-cell"><span class="px-2 py-0.5 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(roleNames[u.USR_ROLE]||u.USR_ROLE)+'</span></td><td class="table-cell">'+(u.USR_IS_ADMIN?'<i class="fas fa-shield-alt text-fju-yellow"></i>':'<span class="text-gray-300">-</span>')+'</td><td class="table-cell">'+(u.USR_SUSPENDED?'<span class="status-rejected">\u505C\u6B0A</span>':'<span class="status-approved">\u6B63\u5E38</span>')+'</td><td class="table-cell"><div class="flex gap-1">';
    h += '<button onclick="toggleAdmin('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-yellow/20 text-fju-blue" title="\u5207\u63DB\u7BA1\u7406\u54E1"><i class="fas fa-shield-alt"></i></button>';
    h += u.USR_SUSPENDED ? '<button onclick="unsuspendUser('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green">\u89E3\u9664</button>' : '<button onclick="suspendUser('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">\u505C\u6B0A</button>';
    h += '</div></td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function toggleAdmin(id) { await fetch(API+'/users/'+id+'/toggle-admin', { method:'PATCH' }); loadUsers(); }
async function suspendUser(id) { const reason = prompt('\u505C\u6B0A\u539F\u56E0\uFF1A'); if(!reason) return; await fetch(API+'/users/'+id+'/suspend', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({reason}) }); loadUsers(); }
async function unsuspendUser(id) { await fetch(API+'/users/'+id+'/unsuspend', { method:'PATCH' }); loadUsers(); }

// ========== Violations (Epic 9) ==========
async function loadViolations() {
  const [logRes, ptsRes] = await Promise.all([fetch(API+'/violations').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/violations/unit-points').then(r=>r.json()).catch(()=>({data:[]}))])
  const logs = logRes.data || []; const points = ptsRes.data || [];
  const srcL = { 0:'\u4EBA\u5DE5', 1:'\u7CFB\u7D71\u81EA\u52D5', 2:'\u7533\u5FA9\u64A4\u92B7', 3:'\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>\u55AE\u4F4D\u9055\u898F\u9EDE\u6578\u4E00\u89BD</h3><div class="grid md:grid-cols-3 gap-3">';
  points.forEach(p => { const danger = p.UVP_POINT >= 10; h += '<div class="p-3 rounded-fju border '+(danger?'border-fju-red bg-fju-red/5':'border-gray-100')+' flex items-center justify-between"><div><span class="font-medium text-sm">'+(p.UNIT_NAME||'')+'</span>'+(p.UVP_SUSPENDED?'<span class="ml-2 status-rejected">\u505C\u6B0A\u4E2D</span>':'')+'</div><span class="text-lg font-black '+(danger?'text-fju-red':'text-fju-blue')+'">'+p.UVP_POINT+' \u9EDE</span></div>'; });
  h += '</div></div>';
  if (isAdmin) h += '<div class="flex justify-end"><button onclick="showAddViolationForm()" class="btn-danger text-xs"><i class="fas fa-plus mr-1"></i>\u65B0\u589E\u8A18\u9EDE</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u5C0D\u8C61</th><th class="table-cell">\u589E\u6E1B</th><th class="table-cell">\u539F\u56E0</th><th class="table-cell">\u4F86\u6E90</th><th class="table-cell">\u64CD\u4F5C\u8005</th><th class="table-cell">\u6642\u9593</th></tr></thead><tbody>';
  if (logs.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">\u66AB\u7121\u8A18\u9304</td></tr>';
  logs.forEach(l => { const target = l.VPL_TARGET_TYPE === 0 ? (l.USR_NAME||'\u500B\u4EBA') : (l.UNIT_NAME||'\u55AE\u4F4D'); h += '<tr class="border-t border-gray-50"><td class="table-cell">'+target+'</td><td class="table-cell"><span class="font-bold '+(l.VPL_DELTA>0?'text-fju-red':'text-fju-green')+'">'+((l.VPL_DELTA>0)?'+':'')+l.VPL_DELTA+'</span></td><td class="table-cell text-xs">'+(l.VPL_REASON||'')+'</td><td class="table-cell text-xs">'+(srcL[l.VPL_SOURCE]||'')+'</td><td class="table-cell text-xs">'+(l.ADMIN_NAME||'\u7CFB\u7D71')+'</td><td class="table-cell text-xs text-gray-400">'+(l.VPL_CREATED_AT||'')+'</td></tr>'; });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAddViolationForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>\u65B0\u589E\u8A18\u9EDE</h3><div class="space-y-3"><select id="vpl-target" class="input-field"><option value="1">\u55AE\u4F4D</option><option value="0">\u500B\u4EBA</option></select><input id="vpl-unit-id" type="number" placeholder="\u55AE\u4F4D ID" class="input-field"><input id="vpl-delta" type="number" placeholder="\u9EDE\u6578\uFF08\u6B63\u6578\u52A0\u9EDE\uFF0C\u8CA0\u6578\u6263\u9EDE\uFF09" class="input-field" value="2"><input id="vpl-reason" placeholder="\u539F\u56E0 *" class="input-field"><button onclick="submitViolation()" class="w-full btn-danger py-2.5">\u9001\u51FA</button></div></div></div>');
}
async function submitViolation() {
  const body = { targetType:parseInt(document.getElementById('vpl-target').value), unitId:parseInt(document.getElementById('vpl-unit-id').value)||null, delta:parseInt(document.getElementById('vpl-delta').value), reason:document.getElementById('vpl-reason').value, source:0, adminId:currentUser?.id||1 };
  await fetch(API+'/violations', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  closeModal(); loadViolations();
}

// ========== Labor Service (Epic 9 \u92B7\u9EDE) ==========
async function loadLabor() {
  const res = await fetch(API+'/labor').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const sL = { 0:'\u5F85\u5BE9\u6838', 1:'\u5DF2\u6838\u51C6', 2:'\u5DF2\u99C1\u56DE' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE\u8AAA\u660E</h3><p class="text-xs text-gray-600">\u9055\u898F\u9EDE\u6578\u53EF\u900F\u904E\u5B8C\u6210\u52DE\u52D5\u670D\u52D9\u4F86\u6263\u9664\u3002\u63D0\u4EA4\u7533\u8ACB\u5F8C\u7D93\u7BA1\u7406\u54E1\u5BE9\u6838\u901A\u904E\u5373\u53EF\u92B7\u9EDE\u3002</p></div>';
  h += '<div class="flex justify-end"><button onclick="showLaborForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>\u7533\u8ACB\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">\u7533\u8ACB\u4EBA</th><th class="table-cell">\u670D\u52D9\u985E\u578B</th><th class="table-cell">\u670D\u52D9\u65E5\u671F</th><th class="table-cell">\u6642\u6578</th><th class="table-cell">\u6263\u9664\u9EDE\u6578</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">\u66AB\u7121\u7533\u8ACB</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell">'+(i.USR_NAME||'')+'</td><td class="table-cell text-xs">'+(i.LSA_SERVICE_TYPE||'')+'</td><td class="table-cell text-xs">'+(i.LSA_SERVICE_DATE||'')+'</td><td class="table-cell">'+i.LSA_HOURS+' \u5C0F\u6642</td><td class="table-cell font-bold text-fju-green">-'+i.LSA_POINTS_TO_DEDUCT+'</td><td class="table-cell"><span class="'+(sC[i.LSA_STATUS]||'')+'">'+((sL[i.LSA_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.LSA_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveLabor('+i.LSA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">\u6838\u51C6</button>';
      h += '<button onclick="rejectLabor('+i.LSA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">\u99C1\u56DE</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showLaborForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hands-helping mr-2 text-fju-yellow"></i>\u7533\u8ACB\u52DE\u52D5\u670D\u52D9\u92B7\u9EDE</h3><div class="space-y-3"><select id="lsa-type" class="input-field"><option value="\u5834\u5730\u6E05\u6F54">\u5834\u5730\u6E05\u6F54</option><option value="\u5668\u6750\u6574\u7406">\u5668\u6750\u6574\u7406</option><option value="\u6D3B\u52D5\u652F\u63F4">\u6D3B\u52D5\u652F\u63F4</option><option value="\u884C\u653F\u5354\u52A9">\u884C\u653F\u5354\u52A9</option></select><input id="lsa-date" type="date" class="input-field"><input id="lsa-hours" type="number" placeholder="\u670D\u52D9\u6642\u6578" class="input-field" value="2"><input id="lsa-points" type="number" placeholder="\u7533\u8ACB\u6263\u9664\u9EDE\u6578" class="input-field" value="1"><button onclick="submitLabor()" class="w-full btn-yellow py-2.5">\u9001\u51FA\u7533\u8ACB</button></div></div></div>');
}
async function submitLabor() {
  const body = { userId:currentUser?.id||1, serviceType:document.getElementById('lsa-type').value, serviceDate:document.getElementById('lsa-date').value, hours:parseInt(document.getElementById('lsa-hours').value)||2, pointsToDeduct:parseInt(document.getElementById('lsa-points').value)||1 };
  await fetch(API+'/labor', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  toast('\u92B7\u9EDE\u7533\u8ACB\u5DF2\u9001\u51FA','success'); closeModal(); loadLabor();
}
async function approveLabor(id) { await fetch(API+'/labor/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('\u92B7\u9EDE\u5DF2\u6838\u51C6','success'); loadLabor(); }
async function rejectLabor(id) { await fetch(API+'/labor/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); loadLabor(); }

// ========== Conflicts (Epic 4.2) ==========
async function loadConflicts() {
  const res = await fetch(API+'/conflicts').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const sL = { 0:'\u9080\u8ACB\u4E2D', 1:'\u5354\u8ABF\u4E2D', 2:'\u5354\u8ABF\u6210\u529F', 3:'\u5354\u8ABF\u5931\u6557', 4:'\u8D85\u6642\u95DC\u9589', 5:'\u9080\u8ACB\u88AB\u62D2' };
  const sC = { 0:'status-pending', 1:'status-info', 2:'status-approved', 3:'status-rejected', 4:'status-rejected', 5:'status-rejected' };
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>\u885D\u7A81\u5354\u8ABF\u6A5F\u5236</h3><div class="text-xs text-gray-600 space-y-1"><p>1. \u5834\u5354\u5927\u6703\u767B\u8A18\uFF1A\u5B78\u671F\u521D\u767B\u8A18\u5834\u5730\u9700\u6C42\uFF0C\u7531\u8AB2\u6307\u7D44\u5354\u8ABF</p><p>2. \u79C1\u4E0B\u5354\u8ABF\uFF1A\u7CFB\u7D71\u5075\u6E2C\u885D\u7A81\u5F8C\uFF0C\u96D9\u65B9\u900F\u904E\u533F\u540D\u804A\u5929\u5BA4\u5354\u5546</p><p>3. \u804A\u5929\u5BA4\u9650\u6642 24 \u5C0F\u6642\uFF0C\u4E00\u65B9\u64A4\u56DE\u5373\u89E3\u6C7A\uFF1B\u7D00\u9304\u4FDD\u5B58\u534A\u5E74</p></div></div>';
  if (items.length === 0) h += '<div class="card text-center text-gray-400"><i class="fas fa-handshake text-2xl mb-2"></i><p>\u76EE\u524D\u7121\u885D\u7A81\u5354\u8ABF\u6848\u4EF6</p></div>';
  else {
    h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">ID</th><th class="table-cell">\u7532\u65B9</th><th class="table-cell">\u4E59\u65B9</th><th class="table-cell">\u5834\u5730</th><th class="table-cell">\u72C0\u614B</th><th class="table-cell">\u64CD\u4F5C</th></tr></thead><tbody>';
    items.forEach(i => {
      h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs">#'+i.CN_ID+'</td><td class="table-cell text-sm">'+(i.A_USER||'-')+' <span class="text-xs text-gray-400">('+(i.A_UNIT||'')+')</span></td><td class="table-cell text-sm">'+(i.B_USER||'-')+' <span class="text-xs text-gray-400">('+(i.B_UNIT||'')+')</span></td><td class="table-cell">'+(i.FAC_NAME||'-')+'</td><td class="table-cell"><span class="'+(sC[i.CN_STATUS]||'')+'">'+((sL[i.CN_STATUS])||'')+'</span></td><td class="table-cell">';
      if (i.CN_STATUS <= 1) {
        h += '<button onclick="openChatRoom('+i.CN_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1"><i class="fas fa-comments mr-1"></i>\u804A\u5929\u5BA4</button>';
        h += '<button onclick="resolveConflict('+i.CN_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green">\u89E3\u6C7A</button>';
      }
      h += '</td></tr>';
    });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function resolveConflict(id) { if(!confirm('\u78BA\u5B9A\u6A19\u8A18\u70BA\u5DF2\u89E3\u6C7A\uFF1F')) return; await fetch(API+'/conflicts/'+id+'/resolve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({resolvedBy:currentUser?.id||1}) }); toast('\u885D\u7A81\u5DF2\u89E3\u6C7A','success'); loadConflicts(); }
async function openChatRoom(id) {
  const res = await fetch(API+'/conflicts/'+id).then(r=>r.json()).catch(()=>({data:{},messages:[]}));
  const cn = res.data || {}; const msgs = res.messages || [];
  let mHtml = msgs.map(m => '<div class="p-2 rounded-fju '+(m.CM_SENDER_ROLE===0?'bg-fju-blue/10 text-fju-blue ml-auto':'bg-gray-100 text-gray-700')+' max-w-[80%] text-sm"><div>'+(m.CM_CONTENT||'')+'</div><div class="text-xs text-gray-400 mt-1">'+(m.CM_SENT_AT||'')+'</div></div>').join('');
  if (!mHtml) mHtml = '<div class="text-center text-gray-400 text-sm py-4">\u5C1A\u7121\u5C0D\u8A71\u7D00\u9304\uFF0C\u958B\u59CB\u5354\u5546\u5427\uFF01</div>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-comments mr-2 text-fju-yellow"></i>\u885D\u7A81\u5354\u8ABF\u804A\u5929\u5BA4 #'+id+'</h3><div class="text-xs text-gray-500 mb-3">'+(cn.A_USER||'')+' ('+(cn.A_UNIT||'')+') vs '+(cn.B_USER||'')+' ('+(cn.B_UNIT||'')+') \u2014 '+(cn.FAC_NAME||'')+'</div><div id="chat-messages" class="space-y-2 max-h-60 overflow-y-auto mb-3 p-3 bg-fju-bg rounded-fju">'+mHtml+'</div><div class="flex gap-2"><input id="chat-input" type="text" placeholder="\u8F38\u5165\u8A0A\u606F..." class="flex-1 input-field" onkeypress="if(event.key===\\'Enter\\')sendChatMsg('+id+')"><button onclick="sendChatMsg('+id+')" class="btn-primary px-4">\u9001\u51FA</button></div></div></div>');
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
  const typeL = { 0:'\u793E\u5718', 1:'\u5B78\u751F\u81EA\u6CBB\u7D44\u7E54', 2:'\u884C\u653F\u55AE\u4F4D' };
  let h = '<div class="space-y-4 fade-in"><div class="grid md:grid-cols-3 gap-4">';
  items.forEach(u => {
    h += '<div class="card hover:border-fju-yellow/30 transition-all cursor-pointer" onclick="showUnitDetail('+u.UNIT_ID+')"><div class="flex items-center justify-between mb-2"><h3 class="font-bold text-fju-blue text-sm">'+(u.UNIT_NAME||'')+'</h3><span class="px-2 py-0.5 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(typeL[u.UNIT_TYPE]||'')+'</span></div><div class="text-xs text-gray-500"><i class="fas fa-user mr-1"></i>\u806F\u7D61\u4EBA\uFF1A'+(u.CONTACT_NAME||'-')+'</div><div class="text-xs text-gray-400 mt-1"><i class="fas fa-flag mr-1"></i>\u9055\u898F\u9EDE\u6578\uFF1A<span class="font-bold '+(u.UVP_POINT>=10?'text-fju-red':'')+'">'+(u.UVP_POINT!=null?u.UVP_POINT:0)+'</span></div></div>';
  });
  h += '</div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function showUnitDetail(id) {
  const res = await fetch(API+'/units/'+id).then(r=>r.json()).catch(()=>({data:{},members:[]}));
  const unit = res.data||{}; const members = res.members||[];
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-building mr-2 text-fju-yellow"></i>'+(unit.UNIT_NAME||'')+'</h3><div class="space-y-3"><div class="text-sm text-gray-600"><i class="fas fa-user mr-1"></i>\u806F\u7D61\u4EBA\uFF1A'+(unit.CONTACT_NAME||'-')+'</div><h4 class="font-bold text-sm text-fju-blue mt-3">\u6210\u54E1\u5217\u8868 ('+members.length+'\u4EBA)</h4><div class="space-y-1 max-h-48 overflow-y-auto">'+members.map(m => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(m.USR_NAME||'')+'</span><span class="text-xs text-gray-400">'+(m.USR_EMAIL||'')+'</span></div>').join('')+'</div></div></div></div>');
}

// ========== Profile (Epic 1.3) ==========
async function loadProfile() {
  const userId = currentUser?.id || 1;
  const res = await fetch(API+'/users/'+userId+'/profile').then(r=>r.json()).catch(()=>({}));
  const u = res.user || currentUser;
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="card flex items-center gap-6"><div class="w-20 h-20 rounded-full bg-fju-yellow flex items-center justify-center text-4xl shadow-lg">'+(currentUser?.avatar||(u?.USR_NAME||u?.name||'?')[0])+'</div><div><h2 class="text-xl font-bold text-fju-blue">'+(u?.USR_NAME||u?.name||'')+'</h2><p class="text-sm text-gray-500">'+(u?.USR_EMAIL||u?.email||'')+'</p><div class="flex gap-2 mt-2"><span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs font-bold">'+(roleNames[u?.USR_ROLE||u?.role]||'')+'</span>';
  if (u?.USR_PHONE || u?.phone) h += '<span class="px-3 py-1 rounded-fju bg-gray-100 text-gray-600 text-xs"><i class="fas fa-phone mr-1"></i>'+(u?.USR_PHONE||u?.phone)+'</span>';
  h += '</div><button onclick="showAvatarSelection()" class="mt-2 text-xs px-3 py-1 rounded-fju bg-fju-yellow/20 text-fju-blue hover:bg-fju-yellow/30"><i class="fas fa-user-circle mr-1"></i>\u66F4\u63DB\u5927\u982D\u8CBC</button></div></div>';
  const sections = [
    { title:'\u5834\u5730\u9810\u7D04\u7D00\u9304', icon:'fa-calendar-check', items:res.bookings||[], render:(b) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(b.FAC_NAME||'')+' ('+(b.VB_START_DATETIME||'').slice(0,10)+')</span><span class="text-xs '+((b.VB_STATUS===1)?'text-fju-green':'text-gray-400')+'">'+['\u5F85\u5BE9\u6838','\u5DF2\u6838\u51C6','\u5DF2\u62D2\u7D55','\u5DF2\u53D6\u6D88','\u5DF2\u6B78\u9084','\u6B78\u9084\u7570\u5E38'][b.VB_STATUS||0]+'</span></div>' },
    { title:'\u5668\u6750\u501F\u7528\u7D00\u9304', icon:'fa-tools', items:res.loans||[], render:(l) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(l.EL_PURPOSE||'')+' ('+(l.EL_BORROW_START||'').slice(0,10)+')</span><span class="text-xs text-gray-400">'+['\u5F85\u9818\u53D6','\u501F\u7528\u4E2D','\u90E8\u5206\u9818\u53D6','\u90E8\u5206\u6B78\u9084','\u5DF2\u6B78\u9084','\u6B78\u9084\u7570\u5E38'][l.EL_STATUS||0]+'</span></div>' },
    { title:'\u7533\u8A34\u7D00\u9304', icon:'fa-gavel', items:res.appeals||[], render:(a) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(a.AC_REASON||'').substring(0,30)+'</span><span class="text-xs '+((a.AC_STATUS===1)?'text-fju-green':'text-gray-400')+'">'+['\u5F85\u5BE9\u6838','\u5DF2\u6838\u51C6','\u5DF2\u99C1\u56DE'][a.AC_STATUS||0]+'</span></div>' },
    { title:'\u5831\u4FEE\u7D00\u9304', icon:'fa-wrench', items:res.repairs||[], render:(r) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(r.FAC_NAME||'')+': '+(r.RR_DESCRIPTION||'').substring(0,30)+'</span><span class="text-xs text-gray-400">'+['\u5F85\u8655\u7406','\u8655\u7406\u4E2D','\u5DF2\u5B8C\u6210'][r.RR_STATUS||0]+'</span></div>' },
  ];
  h += '<div class="grid md:grid-cols-2 gap-4">';
  sections.forEach(s => {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas '+s.icon+' mr-2 text-fju-yellow"></i>'+s.title+'</h3>';
    if (s.items.length === 0) h += '<p class="text-sm text-gray-400">\u66AB\u7121\u7D00\u9304</p>';
    else h += '<div class="space-y-1">'+s.items.map(s.render).join('')+'</div>';
    h += '</div>';
  });
  h += '</div>';
  if (res.violations?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>\u9055\u898F\u9EDE\u6578\u7D00\u9304</h3><div class="space-y-1">';
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
</html>`;
}
__name(Is, "Is");
var v = new I();
v.use("/api/*", vs());
v.route("/api/auth", Fe);
v.route("/api/facilities", H);
v.route("/api/equipment", M);
v.route("/api/activities", W);
v.route("/api/venue-bookings", J);
v.route("/api/repairs", Ne);
v.route("/api/appeals", ue);
v.route("/api/announcements", Se);
v.route("/api/stats", ze);
v.route("/api/units", te);
v.route("/api/violations", Ke);
v.route("/api/conflicts", se);
v.route("/api/ai", qe);
v.route("/api/faq", Lt);
v.route("/api/users", Y);
v.route("/api/labor", ke);
v.route("/api/venue-coordination", pe);
v.get("/api/health", (e) => e.json({ status: "ok", version: "3.2.0", framework: "Hono + Cloudflare Pages", tables: 27 }));
v.get("*", (e) => {
  const t = Is();
  return e.html(t);
});
var ft = new I();
var xs = Object.assign({ "/src/index.tsx": v });
var Mt = false;
for (const [, e] of Object.entries(xs))
  e && (ft.route("/", e), ft.notFound(e.notFoundHandler), Mt = true);
if (!Mt)
  throw new Error("Can't import modules from ['/src/index.tsx','/app/server.ts']");

// ../node_modules/wrangler/templates/middleware/middleware-ensure-req-body-drained.ts
var drainBody = /* @__PURE__ */ __name(async (request, env, _ctx, middlewareCtx) => {
  try {
    return await middlewareCtx.next(request, env);
  } finally {
    try {
      if (request.body !== null && !request.bodyUsed) {
        const reader = request.body.getReader();
        while (!(await reader.read()).done) {
        }
      }
    } catch (e) {
      console.error("Failed to drain the unused request body.", e);
    }
  }
}, "drainBody");
var middleware_ensure_req_body_drained_default = drainBody;

// ../node_modules/wrangler/templates/middleware/middleware-miniflare3-json-error.ts
function reduceError(e) {
  return {
    name: e?.name,
    message: e?.message ?? String(e),
    stack: e?.stack,
    cause: e?.cause === void 0 ? void 0 : reduceError(e.cause)
  };
}
__name(reduceError, "reduceError");
var jsonError = /* @__PURE__ */ __name(async (request, env, _ctx, middlewareCtx) => {
  try {
    return await middlewareCtx.next(request, env);
  } catch (e) {
    const error = reduceError(e);
    return Response.json(error, {
      status: 500,
      headers: { "MF-Experimental-Error-Stack": "true" }
    });
  }
}, "jsonError");
var middleware_miniflare3_json_error_default = jsonError;

// ../.wrangler/tmp/bundle-PaGw5N/middleware-insertion-facade.js
var __INTERNAL_WRANGLER_MIDDLEWARE__ = [
  middleware_ensure_req_body_drained_default,
  middleware_miniflare3_json_error_default
];
var middleware_insertion_facade_default = ft;

// ../node_modules/wrangler/templates/middleware/common.ts
var __facade_middleware__ = [];
function __facade_register__(...args) {
  __facade_middleware__.push(...args.flat());
}
__name(__facade_register__, "__facade_register__");
function __facade_invokeChain__(request, env, ctx, dispatch, middlewareChain) {
  const [head, ...tail] = middlewareChain;
  const middlewareCtx = {
    dispatch,
    next(newRequest, newEnv) {
      return __facade_invokeChain__(newRequest, newEnv, ctx, dispatch, tail);
    }
  };
  return head(request, env, ctx, middlewareCtx);
}
__name(__facade_invokeChain__, "__facade_invokeChain__");
function __facade_invoke__(request, env, ctx, dispatch, finalMiddleware) {
  return __facade_invokeChain__(request, env, ctx, dispatch, [
    ...__facade_middleware__,
    finalMiddleware
  ]);
}
__name(__facade_invoke__, "__facade_invoke__");

// ../.wrangler/tmp/bundle-PaGw5N/middleware-loader.entry.ts
var __Facade_ScheduledController__ = class {
  constructor(scheduledTime, cron, noRetry) {
    this.scheduledTime = scheduledTime;
    this.cron = cron;
    this.#noRetry = noRetry;
  }
  #noRetry;
  noRetry() {
    if (!(this instanceof __Facade_ScheduledController__)) {
      throw new TypeError("Illegal invocation");
    }
    this.#noRetry();
  }
};
__name(__Facade_ScheduledController__, "__Facade_ScheduledController__");
function wrapExportedHandler(worker) {
  if (__INTERNAL_WRANGLER_MIDDLEWARE__ === void 0 || __INTERNAL_WRANGLER_MIDDLEWARE__.length === 0) {
    return worker;
  }
  for (const middleware of __INTERNAL_WRANGLER_MIDDLEWARE__) {
    __facade_register__(middleware);
  }
  const fetchDispatcher = /* @__PURE__ */ __name(function(request, env, ctx) {
    if (worker.fetch === void 0) {
      throw new Error("Handler does not export a fetch() function.");
    }
    return worker.fetch(request, env, ctx);
  }, "fetchDispatcher");
  return {
    ...worker,
    fetch(request, env, ctx) {
      const dispatcher = /* @__PURE__ */ __name(function(type, init) {
        if (type === "scheduled" && worker.scheduled !== void 0) {
          const controller = new __Facade_ScheduledController__(
            Date.now(),
            init.cron ?? "",
            () => {
            }
          );
          return worker.scheduled(controller, env, ctx);
        }
      }, "dispatcher");
      return __facade_invoke__(request, env, ctx, dispatcher, fetchDispatcher);
    }
  };
}
__name(wrapExportedHandler, "wrapExportedHandler");
function wrapWorkerEntrypoint(klass) {
  if (__INTERNAL_WRANGLER_MIDDLEWARE__ === void 0 || __INTERNAL_WRANGLER_MIDDLEWARE__.length === 0) {
    return klass;
  }
  for (const middleware of __INTERNAL_WRANGLER_MIDDLEWARE__) {
    __facade_register__(middleware);
  }
  return class extends klass {
    #fetchDispatcher = (request, env, ctx) => {
      this.env = env;
      this.ctx = ctx;
      if (super.fetch === void 0) {
        throw new Error("Entrypoint class does not define a fetch() function.");
      }
      return super.fetch(request);
    };
    #dispatcher = (type, init) => {
      if (type === "scheduled" && super.scheduled !== void 0) {
        const controller = new __Facade_ScheduledController__(
          Date.now(),
          init.cron ?? "",
          () => {
          }
        );
        return super.scheduled(controller);
      }
    };
    fetch(request) {
      return __facade_invoke__(
        request,
        this.env,
        this.ctx,
        this.#dispatcher,
        this.#fetchDispatcher
      );
    }
  };
}
__name(wrapWorkerEntrypoint, "wrapWorkerEntrypoint");
var WRAPPED_ENTRY;
if (typeof middleware_insertion_facade_default === "object") {
  WRAPPED_ENTRY = wrapExportedHandler(middleware_insertion_facade_default);
} else if (typeof middleware_insertion_facade_default === "function") {
  WRAPPED_ENTRY = wrapWorkerEntrypoint(middleware_insertion_facade_default);
}
var middleware_loader_entry_default = WRAPPED_ENTRY;
export {
  __INTERNAL_WRANGLER_MIDDLEWARE__,
  middleware_loader_entry_default as default
};
//# sourceMappingURL=bundledWorker-0.8047163020872983.mjs.map
