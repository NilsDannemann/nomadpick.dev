!function(e){var t={};function n(r){if(t[r])return t[r].exports;var a=t[r]={i:r,l:!1,exports:{}};return e[r].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(r,a,function(t){return e[t]}.bind(null,a));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=255)}({255:function(e,t,n){"use strict";var r;(r=jQuery)(".dashboard-deactivate-plugin").on("click",function(e){e.preventDefault();var t=r(this);return r.ajax({url:ajaxurl,type:"POST",data:{action:"rank_math_deactivate_plugins",security:rankMath.security,plugin:"all"}}).always(function(e){"1"===e?t.parents(".rank-math-notice").remove():console.log("Deactivation error")}),!1}),r(".rank-math-modules").on("change",function(){var e=r(this),t=e.closest(".rank-math-box"),n=e.is(":checked"),a=e.val(),o={"404-monitor":"404-monitor",redirections:"redirections","role-manager":"role-manager","search-console":"search-console","seo-analysis":"seo-analysis",sitemap:"options-sitemap"};t.addClass("saving"),r.ajax({url:rankMath.api.root+"rankmath/v1/saveModule",method:"POST",beforeSend:function(e){e.setRequestHeader("X-WP-Nonce",rankMath.api.nonce)},data:{module:a,state:n?"on":"off"}}).done(function(e){e?(t.removeClass("saving"),t.toggleClass("active",n),!n&&o.hasOwnProperty(a)&&r("#toplevel_page_rank-math").find('a[href="admin.php?page=rank-math-'+o[a]+'"]').parent("li").toggle()):alert("Something went wrong! Please try again.")})}),"undefined"!=typeof ClipboardJS&&(r(".get-debug-report").on("click",function(){return r("#debug-report").slideDown(),r("#debug-report textarea").focus().select(),r(this).parent().fadeOut(),!1}),new ClipboardJS("#copy-for-support"))}});