!function($){$.path={};var t={rotate:function(t,s){var i=s*Math.PI/180,e=Math.cos(i),n=Math.sin(i);return[e*t[0]-n*t[1],n*t[0]+e*t[1]]},scale:function(t,s){return[s*t[0],s*t[1]]},add:function(t,s){return[t[0]+s[0],t[1]+s[1]]},minus:function(t,s){return[t[0]-s[0],t[1]-s[1]]}};$.path.bezier=function(s,i){s.start=$.extend({angle:0,length:.3333},s.start),s.end=$.extend({angle:0,length:.3333},s.end),this.p1=[s.start.x,s.start.y],this.p4=[s.end.x,s.end.y];var e=t.minus(this.p4,this.p1),n=t.scale(e,s.start.length),h=t.scale(e,-1),r=t.scale(h,s.end.length);n=t.rotate(n,s.start.angle),this.p2=t.add(this.p1,n),r=t.rotate(r,s.end.angle),this.p3=t.add(this.p4,r),this.f1=function(t){return t*t*t},this.f2=function(t){return 3*t*t*(1-t)},this.f3=function(t){return 3*t*(1-t)*(1-t)},this.f4=function(t){return(1-t)*(1-t)*(1-t)},this.css=function(t){var s=this.f1(t),e=this.f2(t),n=this.f3(t),h=this.f4(t),r={};return i&&(r.prevX=this.x,r.prevY=this.y),r.x=this.x=this.p1[0]*s+this.p2[0]*e+this.p3[0]*n+this.p4[0]*h+.5|0,r.y=this.y=this.p1[1]*s+this.p2[1]*e+this.p3[1]*n+this.p4[1]*h+.5|0,r.left=r.x+"px",r.top=r.y+"px",r}},$.path.arc=function(t,s){for(var i in t)this[i]=t[i];for(this.dir=this.dir||1;this.start>this.end&&this.dir>0;)this.start-=360;for(;this.start<this.end&&this.dir<0;)this.start+=360;this.css=function(t){var i=(this.start*t+this.end*(1-t))*Math.PI/180,e={};return s&&(e.prevX=this.x,e.prevY=this.y),e.x=this.x=Math.sin(i)*this.radius+this.center[0]+.5|0,e.y=this.y=Math.cos(i)*this.radius+this.center[1]+.5|0,e.left=e.x+"px",e.top=e.y+"px",e}},$.fx.step.path=function(t){var s=t.end.css(1-t.pos);null!=s.prevX&&$.cssHooks.transform.set(t.elem,"rotate("+Math.atan2(s.prevY-s.y,s.prevX-s.x)+")"),t.elem.style.top=s.top,t.elem.style.left=s.left}}(jQuery);