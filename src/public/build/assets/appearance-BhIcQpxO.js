import{b as i,H as m}from"./app-C9IywKP_.js";import{a as t,c as a}from"./app-logo-icon-B0OBywsz.js";import{S as d,H as u}from"./layout-Bi_aM7F3.js";import{A as y}from"./app-layout-ChxqXliJ.js";/* empty css            */import"./index-DRN7Jx4a.js";import"./index-D6Fp34Bh.js";/**
 * @license lucide-react v0.536.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const h=[["rect",{width:"20",height:"14",x:"2",y:"3",rx:"2",key:"48i651"}],["line",{x1:"8",x2:"16",y1:"21",y2:"21",key:"1svkeh"}],["line",{x1:"12",x2:"12",y1:"17",y2:"21",key:"vw1qmm"}]],k=t("monitor",h);/**
 * @license lucide-react v0.536.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const g=[["path",{d:"M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401",key:"kfwtm"}]],x=t("moon",g);/**
 * @license lucide-react v0.536.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const b=[["circle",{cx:"12",cy:"12",r:"4",key:"4exip2"}],["path",{d:"M12 2v2",key:"tus03m"}],["path",{d:"M12 20v2",key:"1lh1kg"}],["path",{d:"m4.93 4.93 1.41 1.41",key:"149t6j"}],["path",{d:"m17.66 17.66 1.41 1.41",key:"ptbguv"}],["path",{d:"M2 12h2",key:"1t8f8n"}],["path",{d:"M20 12h2",key:"1q8mjw"}],["path",{d:"m6.34 17.66-1.41 1.41",key:"1m8zz5"}],["path",{d:"m19.07 4.93-1.41 1.41",key:"1shlcs"}]],f=t("sun",b);function v({className:n="",...c}){const{appearance:r,updateAppearance:o}=i(),s=[{value:"light",icon:f,label:"Light"},{value:"dark",icon:x,label:"Dark"},{value:"system",icon:k,label:"System"}];return React.createElement("div",{className:a("inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800",n),...c},s.map(({value:e,icon:l,label:p})=>React.createElement("button",{key:e,onClick:()=>o(e),className:a("flex items-center rounded-md px-3.5 py-1.5 transition-colors",r===e?"bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100":"text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60")},React.createElement(l,{className:"-ml-1 h-4 w-4"}),React.createElement("span",{className:"ml-1.5 text-sm"},p))))}const E=[{title:"Appearance settings",href:"/settings/appearance"}];function H(){return React.createElement(y,{breadcrumbs:E},React.createElement(m,{title:"Appearance settings"}),React.createElement(d,null,React.createElement("div",{className:"space-y-6"},React.createElement(u,{title:"Appearance settings",description:"Update your account's appearance settings"}),React.createElement(v,null))))}export{H as default};
