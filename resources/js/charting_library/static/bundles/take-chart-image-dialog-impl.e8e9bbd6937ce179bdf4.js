(window.webpackJsonp=window.webpackJsonp||[]).push([["take-chart-image-dialog-impl"],{"1O6C":function(e,t,n){"use strict";var o,r,i,s,a,c,l,p;n.d(t,"a",function(){return p}),o=n("mrSG"),r=n("q1tI"),i=n("TSYQ"),s=n("+EG+"),a=n("jAh7"),c=n("QpNh"),l=n("aYmi"),p=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t._manager=new a.OverlapManager,t._handleSlot=function(e){t._manager.setContainer(e)},t}return Object(o.__extends)(t,e),t.prototype.render=function(){var e=this.props,t=e.rounded,n=void 0===t||t,a=e.shadowed,p=void 0===a||a,d=e.fullscreen,u=void 0!==d&&d,h=e.darker,m=void 0!==h&&h,w=e.className,v=e.backdrop,f=i(w,l.dialog,n&&l.rounded,p&&l.shadowed,u&&l.fullscreen,m&&l.darker),g=Object(c.a)(this.props);return r.createElement(r.Fragment,null,r.createElement(s.b.Provider,{value:this._manager},v&&r.createElement("div",{className:l.backdrop}),r.createElement("div",Object(o.__assign)({},g,{className:f,style:this._createStyles(),ref:this.props.reference,onFocus:this.props.onFocus,onMouseDown:this.props.onMouseDown,onMouseUp:this.props.onMouseUp,onClick:this.props.onClick,onKeyDown:this.props.onKeyDown,tabIndex:-1}),this.props.children)),r.createElement(s.a,{reference:this._handleSlot}))},t.prototype._createStyles=function(){var e=this.props,t=e.bottom,n=e.left,o=e.width;return{bottom:t,left:n,right:e.right,top:e.top,zIndex:e.zIndex,maxWidth:o,height:e.height}},t}(r.PureComponent)},"8MIK":function(e,t,n){e.exports={modal:"modal-C2LSTwhC",content:"content-tr28wPlV",form:"form-2GifjSKe",copyForm:"copyForm-1HuPoKA0",copyBtn:"copyBtn-1oB8tEqo",shadow:"shadow-2JTdO0Fb",actions:"actions-3fKk-h7d",link:"link-1alPBTTQ",socials:"socials-rht5Uvp-",icon:"icon-4wFj5iyU",socialsText:"socialsText-2yWFq9iu"}},BHQn:function(e,t){e.exports='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" width="28" height="28"><path fill="#1DA1F2" d="M10.28 22.26c7.55 0 11.68-6.26 11.68-11.67v-.53c.8-.58 1.49-1.3 2.04-2.13-.74.33-1.53.54-2.36.65.85-.5 1.5-1.32 1.8-2.28-.78.48-1.66.81-2.6 1a4.1 4.1 0 00-7 3.74c-3.4-.17-6.43-1.8-8.46-4.29a4.1 4.1 0 001.28 5.48c-.68-.02-1.3-.2-1.86-.5v.05a4.11 4.11 0 003.29 4.02 4 4 0 01-1.85.08 4.1 4.1 0 003.83 2.85A8.23 8.23 0 014 20.43a11.67 11.67 0 006.28 1.83z"/></svg>'},GyvH:function(e,t){e.exports='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 15" width="22" height="15"><g fill="none" fill-rule="evenodd" stroke-width="2"><path stroke="#757575" d="M6.25 5.812L11 10.087l4.75-4.275M11 9.868V.315"/><path stroke="#ADAEB0" d="M21 10v4H1v-4"/></g><path d="M.008 12.47V9.994H1.96v3.003h18.095V9.988l.958.021.957.021.02 2.46.02 2.458H.008v-2.477z"/><path d="M8.642 9.27a673.518 673.518 0 0 0-2.658-2.396l-.369-.325.657-.716.658-.716 1.49 1.35c.819.741 1.525 1.348 1.57 1.348.054 0 .079-1.143.079-3.716V.382H11.946v3.717c0 2.129.029 3.716.067 3.716.037 0 .738-.607 1.558-1.349l1.491-1.35.508.543c.28.298.57.626.647.73l.14.187-2.632 2.366c-1.447 1.3-2.668 2.372-2.712 2.381-.044.01-1.111-.915-2.37-2.054z"/></svg>'},RgaO:function(e,t,n){"use strict";function o(e){
var t=e.children,n=Object(r.__rest)(e,["children"]);return t(Object(i.a)(n))}var r,i;n.d(t,"a",function(){return o}),r=n("mrSG"),i=n("8Rai")},UJLh:function(e,t,n){e.exports={wrap:"wrap-3axdIL2R",container:"container-p3zks2PX",backdrop:"backdrop-1qZHPwi_",modal:"modal-GUK9cvUQ",dialog:"dialog-2Ei1ngXh"}},aYmi:function(e,t,n){e.exports={dialog:"dialog-2APwxL3O",rounded:"rounded-tXI9mwGE",shadowed:"shadowed-2M13-xZa",fullscreen:"fullscreen-2RqU2pqU",darker:"darker-2nhdv2oS",backdrop:"backdrop-1tKdKmN_"}},fMMV:function(e,t,n){"use strict";function o(e,t,n,o){return void 0===o&&(o={}),Object(c.__awaiter)(this,void 0,void 0,function(){var r,i,s,a,d,u,h;return Object(c.__generator)(this,function(m){if(r=new FormData,void 0!==o.previews)for(i=0,s=o.previews;i<s.length;i++)a=s[i],r.append("previews[]",a);return void 0!==o.cme&&r.append("cme",String(o.cme)),void 0!==o.wl&&r.append("wl",String(o.wl)),void 0!==o.onWidget&&r.append("onWidget",String(o.onWidget)),o.isReport&&r.append("isReport",String(o.isReport)),(d=window.urlParams)&&d.locale&&r.append("language",d.locale),u=e.activeChartWidget.value(),void 0!==(h=u.widgetCustomer())&&r.append("customer",h),r.append("timezone",u.properties().timezone.value()),r.append("images",JSON.stringify(e.images())),function(e,t,n,o){void 0===o&&(o={});Object(c.__awaiter)(this,void 0,void 0,function(){var r,i,s;return Object(c.__generator)(this,function(a){switch(a.label){case 0:r=l.enabled("charting_library_base")?o.snapshotUrl||"https://www.tradingview.com/snapshot/":"/snapshot/",a.label=1;case 1:return a.trys.push([1,4,,5]),[4,Object(p.fetch)(r,{body:e,method:"POST",credentials:"same-origin"})];case 2:return[4,(i=a.sent()).text()];case 3:return s=a.sent(),i.ok?t(s):n(),[3,5];case 4:return a.sent(),n(),[3,5];case 5:return[2]}})})}(r,t,n,o),[2]})})}function r(e){var t=m("tv-spinner","tv-spinner--shown","tv-spinner--size_"+(e.size||k.a));return u.createElement("div",{className:t,style:e.style,role:"progressbar"},u.createElement("div",{className:"tv-spinner__spinner-layer"},u.createElement("div",{className:"tv-spinner__background tv-spinner__width_element"}),u.createElement("div",{className:"tv-spinner__circle-clipper tv-spinner__width_element tv-spinner__circle-clipper--left"}),u.createElement("div",{className:"tv-spinner__circle-clipper tv-spinner__width_element tv-spinner__circle-clipper--right"})))}function i(e,t,n){function r(e){s||(s=document.createElement("div"),document.body.appendChild(s)),h.render(u.createElement(R,e),s)}function i(){r({isOpened:!1})}var s;void 0===t&&(t={}),Object(d.trackEvent)("GUI","Get image button"),r({isOpened:!1}),o(e,function(o){n&&n(o),r({isOpened:!0,onClose:i,imageUrl:l.enabled("charting_library_base")?(t.snapshotUrl?"":"https://www.tradingview.com/x/")+o:Object(a.isProd)()?"https://www.tradingview.com/x/"+o+"/":window.location.protocol+"//"+window.location.host+"/x/"+o+"/",symbol:e.activeChartWidget.value().symbolProperty().value()})},function(){r({isOpened:!0,onClose:i,error:window.t("URL cannot be received")})},{onWidget:e.onWidget,
snapshotUrl:t.snapshotUrl,onInvalidSnapshotImage:i}),r({isOpened:!0,onClose:i})}function s(e,t,n){o(e,function(e){n&&n(e)},function(){console.warn(window.t("Error while trying to create snapshot."))},{snapshotUrl:t.snapshotUrl})}var a,c,l,p,d,u,h,m,w,v,f,g,_,b,y,O,E,C,k,S,x,I,U,N,j,M,P,R;n.r(t),n("YFKU"),a=n("Wt0y"),c=n("mrSG"),n("HbRj"),l=n("Kxc7"),p=n("BHQF"),d=n("PC8g"),u=n("q1tI"),h=n("i8i4"),m=n("TSYQ"),w=n("1O6C"),v=n("uqKQ"),f=n("RgaO"),g=n("UJLh"),_=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return Object(c.__extends)(t,e),t.prototype.render=function(){var e=this,t=this.props,n=t.zIndex,o=t.onClickOutside,r=t.children,i=t.className;return u.createElement("div",{style:{zIndex:n}},u.createElement("div",{className:g.backdrop}),u.createElement("div",{className:g.wrap},u.createElement("div",{className:g.container},u.createElement(f.a,{mouseDown:!0,touchStart:!0,handler:o},function(t){return u.createElement("div",{className:g.modal,ref:t},u.createElement(w.a,Object(c.__assign)({},e.props,{className:m(i,g.dialog)}),r))}))))},t.defaultProps={width:500},t}(u.PureComponent),b=Object(v.a)(_),y=n("AVTG"),O=n("jjrI"),E=n("L0Sj"),C=n("oj21"),k=n("Owlf"),n("SzKR"),function(e){function t(e,t){return"cme"===TradingView.widgetCustomer?t+" from cmegroup.com via @tradingview $"+e:"#"+e+" chart "+t+" via https://www.tradingview.com"}function n(e,t){var n,o,r,i;return void 0===e&&(e="about:blank"),void 0===t&&(t="snapshot_tweet"),n=550,o=420,r=Math.round(screen.width/2-n/2),i=Math.round(screen.height/2-o/2),window.open(e,t,"scrollbars=yes,resizable=yes,toolbar=no,location=yes,\n\t\t\t\twidth="+n+",height="+o+",\n\t\t\t\tleft="+r+",top="+i)}e.getStatus=t,e.shareSnapshot=function(e){var o=n();return{onFailure:function(){o.close()},onSuccess:function(n){o.location.href=function(e,n){return"https://twitter.com/intent/tweet?&status="+encodeURIComponent(t(e,function(e){return window.location.protocol+"//"+window.location.host+"/x/"+e+"/"}(n)))}(e,n)}}},e.shareSnapshotInstantly=function(e,o){n(function(e,n){return"https://twitter.com/intent/tweet?&status="+encodeURIComponent(t(e,n))}(e,o))}}(S||(S={})),x=n("ycI/"),I=n("Ialn"),U=n("FQhm"),N=n("ZjKI"),j=n("8MIK"),M=n("BHQn"),P=n("GyvH"),R=function(e){function t(t){var n=e.call(this,t)||this;return n._input=null,n._hideMessages=function(){n.setState({message:"",error:""})},n._setInput=function(e){n._input=e},n._select=function(){null!==n._input&&n._input.select()},n._shareTwitter=function(){S.shareSnapshotInstantly(n.props.symbol||"",n.props.imageUrl||"")},n._onClose=function(){n.props.onClose&&n.props.onClose(),n._copyBtn=null},n.state={message:t.message,error:t.error},n}return Object(c.__extends)(t,e),t.prototype.componentDidMount=function(){U.subscribe(N.CLOSE_POPUPS_AND_DIALOGS_COMMAND,this._onClose,null)},t.prototype.componentWillUnmount=function(){U.unsubscribe(N.CLOSE_POPUPS_AND_DIALOGS_COMMAND,this._onClose,null)},t.prototype.componentWillReceiveProps=function(e){this.setState({message:e.message,error:e.error})},
t.prototype.componentDidUpdate=function(e){var t=this;!e.imageUrl&&this.props.imageUrl&&setTimeout(function(){null!==t._input&&(t._input.select(),t._input.focus())})},t.prototype.render=function(){var e=this,t=this.props.imageUrl,n=this.state,o=n.message,i=n.error,s=!t&&!this.props.message&&!this.props.error,a=m(j.copyBtn,!Object(I.isRtl)()&&j.shadow);return u.createElement(b,{isOpened:this.props.isOpened,className:j.modal,onClickOutside:this._onClose,"data-dialog-type":"take-snapshot-modal"},u.createElement(y.b,{onClose:this._onClose},window.t("Image URL")),o&&u.createElement(y.c,{text:o,isError:!1,onClickOutside:this._hideMessages}),i&&u.createElement(y.c,{text:i,isError:!0,onClickOutside:this._hideMessages}),u.createElement(y.a,null,u.createElement(x.a,{keyCode:27,handler:this._onClose}),u.createElement("div",{className:j.content},s&&u.createElement(r,{size:"mini"}),u.createElement("div",{className:j.form,style:{visibility:t?"visible":"hidden"}},u.createElement("div",{className:j.copyForm},u.createElement(E.b,{reference:this._setInput,readOnly:!0,value:t||"",onClick:this._select,onFocus:this._select,strictLeftDirectionInput:Object(I.isRtl)(),style:Object(I.isRtl)()?{paddingLeft:84}:void 0}),u.createElement("div",{ref:function(t){return t&&e._setupClipboard(t)},"data-clipboard-text":t,className:a},u.createElement(C.a,{type:"primary",theme:"ghost"},window.t("Copy")))),u.createElement("div",{className:j.actions},u.createElement("a",{className:j.link,href:t,target:"_blank"},u.createElement(O.a,{icon:P}),u.createElement("span",null,window.t("Save image"))),u.createElement("span",{className:j.socials,onClick:this._shareTwitter},u.createElement(O.a,{className:j.icon,icon:M}),u.createElement("span",{className:j.socialsText},window.t("Tweet"))))))))},t.prototype._setupClipboard=function(e){var t=this;this._copyBtn||(this._copyBtn=e,n.e("clipboard").then(function(o){var r=n("sxGJ"),i=new r(e);i.on("success",function(){TradingView.trackEvent("GUI","Copied Image Link"),t.setState({message:window.t("Copied to clipboard")})}),i.on("error",function(){t.setState({message:window.t("Sorry, the Copy Link button doesn't work in your browser. Please select the link and copy it manually.")})})}.bind(null,n)).catch(void 0))},t.defaultProps={imageUrl:""},t}(u.Component),n.d(t,"getImageOfChart",function(){return i}),n.d(t,"getImageOfChartSilently",function(){return s})},uqKQ:function(e,t,n){"use strict";function o(e){return function(t){function n(){return null!==t&&t.apply(this,arguments)||this}return Object(r.__extends)(n,t),n.prototype.render=function(){var t,n=this.props,o=n.isOpened,a=n.root;return o?(t=i.createElement(e,Object(r.__assign)({},this.props,{zIndex:150})),"parent"===a?t:i.createElement(s.a,null,t)):null},n}(i.PureComponent)}var r,i,s;n.d(t,"a",function(){return o}),r=n("mrSG"),i=n("q1tI"),s=n("AiMB")}}]);